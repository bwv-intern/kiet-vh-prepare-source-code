<?php

namespace App\Http\Controllers;

use App\Libs\ValueUtil;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * Handle pagination
     *
     * @param object $query
     */
    public function pagination($query) {
        $limit = ValueUtil::get('common.pagination_limit');

        return $query->paginate($limit)
            ->withQueryString();
    }

    /**
     * Get route name previous
     *
     * @return string
     */
    public function getRouteNamePrevious() {
        $prevRequest = app('request')->create(url()->previous());
        try {
            $routeName = app('router')->getRoutes()->match($prevRequest)->getName();
        } catch (NotFoundHttpException $e) {
            return null;
        }

        return $routeName;
    }
}
