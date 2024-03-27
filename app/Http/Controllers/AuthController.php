<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\CheckEmailRequest;
use App\Http\Requests\Auth\CheckPhoneRequest;
use App\Http\Requests\Auth\CheckPhoneVerifyRequest;
use App\Http\Requests\Auth\ForgotRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RefreshTokenRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Mail\Auth\Forgot;
use App\Models\OClient;
use App\Models\User;
use App\Models\UserPhoneVerify;
use App\Repository\UserTypes;
use Illuminate\Support\Str;
use Laravel\Passport\Http\Controllers\HandlesOAuthErrors;
use Mcamara\LaravelLocalization\LaravelLocalization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use \Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use HandlesOAuthErrors;

    public $token = true;

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function types()
    {
        $types = (new UserTypes())
            ->all(language()->code)
            ->map(function ($type) {
                $type->name = $type->locale->name;
                return collect($type->toArray())
                    ->only('id', 'name');
            });

        return $this->jsonResponse([
            'types' => $types
        ], Response::HTTP_OK);
    }

    /**
     * @param CheckEmailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerCheckEmail(CheckEmailRequest $request)
    {
        return $this->jsonResponse([
            'success' => true
        ], Response::HTTP_OK);
    }

    /**
     * @param CheckPhoneRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerCheckPhone(CheckPhoneRequest $request)
    {
        $userPhoneVerify = new UserPhoneVerify();
        $userPhoneVerify->phone = $request->phone;
        $userPhoneVerify->token = Str::random(50);
        $userPhoneVerify->code = 1111;
        $userPhoneVerify->save();

        return $this->jsonResponse([
            'success' => true,
            'token' => $userPhoneVerify->token
        ], Response::HTTP_OK);
    }

    /**
     * @param CheckPhoneVerifyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerPhoneVerify(CheckPhoneVerifyRequest $request)
    {
        $user_phone = UserPhoneVerify::where('code', $request->code)
            ->where('token', $request->token)
            ->first();

        $user_phone->verified = 1;
        $user_phone->save();

        return $this->jsonResponse([
            'success' => true
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegistrationRequest $request)
    {
        $user = new User();
        $user->type_id = $request->type_id;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->region = $request->region;
        $user->city = $request->city;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = bcrypt($request->password);
        $user->save();

        if ($this->token) {
            return $this->getAuth($request);
        }

        return $this->jsonResponse([
            'success' => true,
            'data' => $user
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @example https://stackoverflow.com/questions/44172818/registering-user-with-laravel-passport
     */
    public function login(LoginRequest $request)
    {
        return $this->getAuth($request);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function getAuth(Request $request)
    {
        $client = $this->getClient();

        request()->request->add([
            'grant_type' => 'password',
            'username' => $request->email,
            'password' => $request->password,
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'scope' => ''
        ]);

        $proxy = Request::create(
            'oauth/token',
            'POST',
            [
                'grant_type' => 'password',
                'username' => $request->email,
                'password' => $request->password,
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'scope' => ''
            ]
        );

        $response = \Route::dispatch($proxy);

        if ($response->status() == 200) {
            return $response;
        } else {
            return $this->jsonResponse([
                'title' => __('api.response.status.error.title'),
                'message' => __('validation.auth')
            ], Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * @param RefreshTokenRequest $request
     * @return mixed
     */
    public function refreshToken(RefreshTokenRequest $request)
    {
        $client = $this->getClient();

        request()->request->add([
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'scope' => ''
        ]);

        $proxy = Request::create(
            'oauth/token',
            'POST'
        );

        return \Route::dispatch($proxy);
    }

    /**
     * @param ForgotRequest $request
     * @param LaravelLocalization $localization
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgot(ForgotRequest $request, LaravelLocalization $localization)
    {
        $user = $this->getUser($request->email);
        $password = Str::random(8);
        $user->password = Hash::make($password);
        $user->update();

        Mail::queue(new Forgot($user, $password, $localization->getCurrentLocale()));

        return response()->json([
            'success' => true,
        ]);
    }

//    public function logout(Request $request)
//    {
//        $this->validate($request, [
//            'token' => 'required'
//        ]);
//
//        try {
//            JWTAuth::invalidate($request->token);
//
//            return response()->json([
//                'success' => true,
//                'message' => 'User logged out successfully'
//            ]);
//        } catch (JWTException $exception) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Sorry, the user cannot be logged out'
//            ], Response::HTTP_INTERNAL_SERVER_ERROR);
//        }
//    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function isLogin(Request $request)
    {
        return response()->json(['success' => !!Auth::guard()->user()]);
    }

    /**
     * @param string $email
     * @return User
     */
    protected function getUser(string $email)
    {
        return User::whereEmail($email)->firstOrFail();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    protected function getClient()
    {
        return OClient::query()->where('password_client', 1)->first();
    }
}
