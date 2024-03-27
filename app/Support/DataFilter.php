<?php

namespace App\Support;

use App\Http\Requests\Account\DataTableRequest;
use App\Support\DataFilter\AbstractFilter;
use App\Support\DataFilter\WhereStringFilter;
use Closure;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

/**
 * Class DataFilter
 * @package App\Support
 * @method $this whereString(string $column, string $input)
 */
class DataFilter
{
    protected ?Builder $builder = null;

    protected ?DataTableRequest $request = null;

    protected ?Closure $filterClosure = null;

    protected array $actions = [];

    protected array $filters = [
        'whereString' => WhereStringFilter::class
    ];

    /**
     * @return Builder
     */
    public function builder()
    {
        return $this->builder;
    }

    /**
     * @param $actions
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
    }

    /**
     * @return $this
     */
    public function filter()
    {
        if($this->filterClosure instanceof Closure) {
            call_user_func_array($this->filterClosure, [$this->builder, $this->request->filter()]);
        }

        return $this;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function paginate()
    {
        $result = $this->builder->paginate(
            $this->request->value('pagination.perpage'), ['*'], 'page', $this->request->value('pagination.page')
        );

        if($actions = $this->actions) {
            foreach($result as $key => $item) {
                $result[$key]->actions = collect($actions)->map(function($i) use ($key, $item){
                    if(is_array($i)) {
                        if(!isset($i['route'])) {
                            throw new \Exception(__('No route specified for action: "'.$key.'"'));
                        }
                        $params = !empty($i['params']) ? (is_callable($i['params']) ? $i['params']($item) : $i['params']) :$item;
                        unset($i['params']);
                        $i['route'] = route($i['route'], $params);
                        return $i;
                    } else {
                        return route($i, $item);
                    }
                })->toArray();
            }
        }

        return $result;
    }

    /**
     * @return $this
     */
    public function sort()
    {
        $this->builder->orderBy($this->request->sortColumn(), $this->request->sortDirection());

        return $this;
    }

    /**
     * @param Closure $closure
     * @return $this
     */
    public function setFilter(Closure $closure)
    {
        $this->filterClosure = $closure;

        return $this;
    }

    /**
     * @param Builder|string $builder
     * @return $this
     */
    public function setBuilder($builder)
    {
        $this->builder = is_string($builder) ? $builder::query() : $builder;

        return $this;
    }

    /**
     * @param DataTableRequest $request
     * @return $this
     */
    public function setFormRequest(DataTableRequest $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call(string $name, array $arguments)
    {
        if(!in_array($name, $this->filters)) {
            throw new Exception("Filter $name not found.");
        }

        /** @var AbstractFilter $filter */
        $filter = App::make($this->filters[$name]);
        $filter->column = $arguments[0];
        $filter->input = $arguments[1];
        $filter->handler($this->builder, $this->request);

        return $this;
    }
}
