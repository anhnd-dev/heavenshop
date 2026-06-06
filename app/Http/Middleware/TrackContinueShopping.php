<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackContinueShopping
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route()?->getName();

        if (in_array($route, ['product.show', 'collection.show'])) {
            session(['continue_shopping_url' => $request->url()]);
        }

        return $next($request);
    }
}
