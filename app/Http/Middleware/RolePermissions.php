<?php

namespace App\Http\Middleware;

use App\Exceptions\UnauthorizedException;
use App\Support\Str;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class RolePermissions
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Response|RedirectResponse
     * @throws UnauthorizedException
     */
    public function handle(Request $request, Closure $next)
    {
//        $routeAction = basename( $path ); //we got the permission name

        $path =Route::currentRouteAction();
        //hello world
        dd(auth()->check());
        $routeAction = mb_basename($path);
        $routeAction = Str::replaceAll(['create','getProductsForOrders','getCouponByCode','getAllParentsSorted','getAllChildsSorted','getAllLanguagesSorted'], 'store', $routeAction);
        $routeAction = Str::replaceAll(['getTableHeaders','getAllRoles'], 'index', $routeAction);
        $routeAction = Str::replaceAll(['setCurrencyIsDefault','toggleStatus','updateSortValues','getAllParentsSorted','getAllChildsSorted','setLanguageIsDefault'], 'update', $routeAction);
        $routeAction = Str::replaceAll(['getNestedPermissionsForRole'], 'show', $routeAction);
         if (! auth()->check() ) {
             throw new UnauthorizedException();
        }
        if (!auth()->user()->hasPermissionTo($routeAction)) {
            throw new UnauthorizedException();
        }

        return $next($request);
    }
}
