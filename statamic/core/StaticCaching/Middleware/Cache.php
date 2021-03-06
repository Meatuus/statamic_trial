<?php

namespace Statamic\StaticCaching\Middleware;

use Closure;
use Statamic\API\Config;
use Statamic\StaticCaching\Cacher;

class Cache
{
    /**
     * @var Cacher
     */
    private $cacher;

    public function __construct(Cacher $cacher)
    {
        $this->cacher = $cacher;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    /**
     * Perform any final actions for the request lifecycle.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return void
     */
    public function terminate($request, $response)
    {
        if (! Config::get('caching.static_caching_enabled')) {
            return;
        }

        if ($response->getStatusCode() !== 200) {
            return;
        }

        $this->cacher->cachePage($request, $response);
    }
}