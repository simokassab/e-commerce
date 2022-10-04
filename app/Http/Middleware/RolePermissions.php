<?php

namespace App\Http\Middleware;

use App\Exceptions\AuthenticationException;
use App\Exceptions\PermissionException;
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
     * @throws AuthenticationException
     * @throws PermissionException
     */
    public function handle(Request $request, Closure $next)
    {
//        $routeAction = basename( $path ); //we got the permission name
        $path = Route::currentRouteAction();

        $routeAction = mbBaseName($path);
        $routeAction = Str::replaceAll(
            [
                'create',
                'getAllProductsAndPrices',
                'getProductsForOrders',
                'getCouponByCode',
                'getAllParentsSorted',
                'getAllChildsSorted',
                'getAllLanguagesSorted',

            ], 'store', $routeAction);

        $routeAction = Str::replaceAll(
            [
                'getTableHeaders',
                'getAllRoles',
                'getPricesList',
            ], 'index', $routeAction);

        $routeAction = Str::replaceAll([
            'setCurrencyIsDefault',
            'toggleStatus',
            'updateSortValues',
            'getAllParentsSorted',
            'getAllChildsSorted',
            'setLanguageIsDefault',
        ], 'update', $routeAction);

        $routeAction = Str::replaceAll(
            [
                'getNestedPermissionsForRole',
                'getPricesList',
            ], 'show', $routeAction);

        if (!auth()->user()->hasPermissionTo($routeAction)) {
            throw new PermissionException();
        }

        return $next($request);
    }
}
