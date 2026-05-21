<?php

namespace App\Http\Middleware;

use App\Support\CmsModuleVisibility;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCmsModuleEnabled
{
    public function handle(Request $request, Closure $next, string $module): Response
    {
        if (! CmsModuleVisibility::isEnabled($module)) {
            abort(404);
        }

        return $next($request);
    }
}
