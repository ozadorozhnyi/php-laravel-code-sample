<?php

namespace App\Support\Navigation;

use App\Http\Controllers\Visible\PageController;
use App\Models\Page;
use Exception;
use Illuminate\Routing\Route as RouteAlias;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

/**
 * Class PageLoader
 * @package App\Support\Navigation
 */
class PageLoader
{
    protected string $cacheKey = 'pages';

    protected string $controller = PageController::class;

    protected ?Collection $pages = null;
    /**
     * @var Page|null
     */
    protected ?Page $page = null;

    /**
     * Load routes from cache
     */
    public function routes()
    {
        $this->pages()->each(
            fn(Page $page) => $this->createRoute($page)
        );
    }

    /**
     * @return Collection|Page[]
     */
    public function pages()
    {
        if($this->pages === null) {
//            $this->pages = Cache::remember($this->cacheKey, Carbon::now()->addMonth(), function() {
                try {
                    return Page::where('is_active', 1)
                        ->where('template', '!=', 'separator')->get();
                }
                catch(Exception $exception) {
                    return new Collection();
                }
//            });
        }

        return $this->pages;
    }

    /**
     * @return bool
     */
    public function cacheForget()
    {
        return Cache::forget($this->cacheKey);
    }

    /**
     * @return Page|null
     */
    public function getCurrentPage()
    {
        return $this->page;
    }

    /**
     * @param Page $page
     * @return RouteAlias
     */
    protected function createRoute(Page $page)
    {
        $templateParams = $page->getTemplate()->templateRouteParams();
        return Route::get($page->route_url . $templateParams, fn() => App::make($this->controller)->callAction('__invoke', ['page' => $page]))->name($page->route_name);
    }

}
