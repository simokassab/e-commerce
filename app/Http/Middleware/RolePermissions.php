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

        dd( Permission::findByName( basename( Route::currentRouteAction() ) ) );
        $routeAction = basename(Route::currentRouteAction()); //we got the permission name
        $routeAction = Str::replaceAll(['show'], 'index', $routeAction);
        $routeAction = Str::replaceAll(['updateTst', 'unknowFunction'], 'update', $routeAction);

        if (! auth()->check() ) {
            throw new UnauthorizedException();
        }
        if (!auth()->user()->hasPermissionTo($routeAction)) {
            throw new UnauthorizedException();
        }

        return $next($request);
    }
}
