<?php

namespace App\Support;

use App\Http\Requests\Account\DataTableRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Http\JsonResponse as BaseJsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\MessageBag;
use Throwable;

/**
 * Class JsonResponse
 * @package App\Support
 */
class JsonResponse extends BaseJsonResponse
{
    /**
     * JsonResponse constructor.
     * @param null $data
     * @param int $status
     * @param array $headers
     * @param int $options
     */
    private function __construct($data = null, $status = 200, $headers = [], $options = 0)
    {
        parent::__construct($data, $status, $headers, $options);
    }

    /**
     * @param string|null $message
     * @param int $status
     * @return static
     */
    public static function success(?string $message = null, int $status = self::HTTP_OK)
    {
        $response = new static();
        $response->setStatusCode($status);
        $response->addMessage($message);
        $response->translateMessage();

        return $response;
    }

    /**
     * @param string|null $message
     * @param MessageBag|null $bag
     * @param int $status
     * @return $this
     */
    public static function error(?string $message, ?MessageBag $bag, int $status = self::HTTP_BAD_REQUEST)
    {
        $bag = $bag instanceof MessageBag ? $bag->toArray() : null;

        $response = new static();
        $response->setStatusCode($status);
        $response->addMessage($message);
        $response->addData('errors', $bag);
        $response->translateMessage();

        return $response;
    }

    /**
     * @param string $name
     * @param array $data
     * @param int $status
     * @return static
     * @throws Throwable
     */
    public static function view(string $name, array $data = [], int $status = self::HTTP_OK)
    {
        return self::success(null, $status)->addData('view', view($name, $data)->render());
    }

    /**
     * @param DataTableRequest $request
     * @param LengthAwarePaginator|LengthAwarePaginatorContract $paginator
     * @return JsonResponse
     */
    public static function dataTable(DataTableRequest $request, LengthAwarePaginator $paginator)
    {
        return static::success(null)
            ->addData('data', $paginator->items())
            ->addData('meta', [
                'page' => $paginator->currentPage(),
                'pages' => $paginator->lastPage(),
                'perpage' => $paginator->perPage(),
                'total' => $paginator->total(),
                'sort' => $request->sortDirection(),
                'field' => $request->sortColumn()
            ]);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function addData(string $key, $value)
    {
        $data = $this->getData();
        $data->$key = $value;
        $this->setData($data);

        return $this;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function redirectTo(string $url)
    {
        return $this->addData('redirect_to', $url);
    }

    /**
     * @param string $name
     * @param array $parameters
     * @return $this
     */
    public function redirectToRoute(string $name, $parameters = [])
    {
        return $this->redirectTo(route($name, $parameters));
    }

    /**
     * @param string|null $message
     * @return $this
     */
    public function addMessage(?string $message)
    {
        return $this->addData('message', $message);
    }

    /**
     * @return $this
     */
    public function translateMessage()
    {
        $data = $this->getData();
        $data->message = isset($data->message) ? __($data->message) : null;
        $this->setData($data);

        return $this;
    }
}
