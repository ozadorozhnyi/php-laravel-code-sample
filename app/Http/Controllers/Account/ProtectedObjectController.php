<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Requests\Account\ProtectedObject\{ShowRequest, StoreRequest, UpdateRequest};
use App\Http\Resources\SuccessfullyUpdatedResource;
use App\Http\Resources\ProtectedObject\{ObjectResource, ProtectedObjectCollection};
use App\Models\{ProtectedObject, ProtectedObjectStatus};
use App\Classes\ProtectedObject\{OwnerObjectDirector, ProtectedObjectBuilder, ProtectedObjectDirector};
use App\Events\ProtectedObject\ChangeStatusEvent;
use App\Repository\{OwnerObject, ProtectedObjectTypes};
use Carbon\Carbon;
use Illuminate\Support\Arr;
use \Symfony\Component\HttpFoundation\Response;

class ProtectedObjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return ProtectedObjectCollection
     */
    public function index()
    {
        $protectedObjects = user()
            ->protectedObjects()
            ->withType()
            ->withStatusNames()
            ->get();

        $protectedObjects->transform(function($object) {
            $object->status = $object->statuses->first();
            unset($object->statuses);
            return $object;
        });

        return new ProtectedObjectCollection(
            $protectedObjects
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $owner_object = (new OwnerObjectDirector())
            ->createOrGetOwner(
                user()->id,
                Arr::get($data, 'first_name'),
                Arr::get($data, 'last_name'),
                Arr::get($data, 'father_name'),
                Arr::get($data, 'id_passport'),
                Arr::get($data, 'passport'),
                Arr::get($data, 'passport_issued'),
                Arr::get($data, 'passport_date') ? Carbon::parse(Arr::get($data, 'passport_date'))->format("Y-m-d") : '',
                Arr::get($data, 'id_tax'),
            );

        $protectedObject = (new ProtectedObjectDirector)->userCreate(
            user()->id,
            $owner_object->id,
            Arr::get($data, 'type_id'),
            Arr::get($data, 'city'),
            Arr::get($data, 'region'),
            Arr::get($data, 'street'),
            Arr::get($data, 'house'),
            Arr::get($data, 'detached_building'),
            Arr::get($data, 'number_of_inputs'),
            Arr::get($data, 'apartment') ?? '',
            Arr::get($data, 'floor') ?? '',
            Arr::get($data, 'entrance') ?? '',
            Arr::get($data, 'addition') ?? '',
        );

        ChangeStatusEvent::dispatch(
            $protectedObject, ProtectedObjectStatus::SUCCESSFULLY_REGISTERED
        );

        return $this->jsonResponse([
            'id' => $protectedObject->id,
            'success' => true,
        ], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource.
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function update(UpdateRequest $request)
    {
        $data = $request->validated();

        $protectedObject = (new ProtectedObjectDirector())->objectUpdate(
            Arr::get($data, 'id'),
            Arr::get($data, 'city'),
            Arr::get($data, 'region'),
            Arr::get($data, 'street'),
            Arr::get($data, 'house'),
            Arr::get($data, 'detached_building'),
            Arr::get($data, 'number_of_inputs'),
            Arr::get($data, 'apartment'),
            Arr::get($data, 'floor'),
            Arr::get($data, 'entrance'),
            Arr::get($data, 'addition'),
        );

        return (new SuccessfullyUpdatedResource($protectedObject))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param ShowRequest $request
     * @return ObjectResource
     */
    public function show(ShowRequest $request)
    {
        $validated = $request->validated();

        $protectedObject = ProtectedObject::with('owner')
            ->withType()->withStatusNames()
            ->find($validated['id']);

        $protectedObject->status = $protectedObject->statuses->first();
        unset($protectedObject->statuses);

        return new ObjectResource(
            $protectedObject
        );
    }

    public function types()
    {
        $types = (new ProtectedObjectTypes)
            ->all(language()->code)
            ->map(function ($type) {
                $type->name = $type->locale->name;
                $type->icon = asset($type->icon);
                $type->icon_ios = asset($type->icon_ios);

                return collect($type->toArray())
                    ->only('id', 'name', 'icon', 'icon_ios');
            });

        return $this->jsonResponse([
            'types' => $types
        ], Response::HTTP_OK);
    }
    public function owners()
    {
        $owners = (new OwnerObject())
            ->all(user()->id);

        return $this->jsonResponse([
            'owners' => $owners
        ], Response::HTTP_OK);
    }
    public function instructions()
    {
        $lang = language()->code;
        $created_at = $updated_at = \Carbon\Carbon::now()->toIso8601String();

        return [
            'instructions' => [
                'pdf' => [
                    'url' => asset("/media/pdf/instructions/{$lang}/Connecting_To_The_Security_Console_Guide.pdf"),
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ],
                'video' => [
                    'url' => 'https://youtube.com/uos/instructions/Connecting_To_The_Security_Console/sF76f986Dj',
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ],
            ]
        ];
    }
}
