<?php

namespace App\Http\Middleware;

use Closure;
use App;

class RequestLogger
{
    const ENABLE_ENVIRONMENT = [
        'local',
        'development',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('app.debug') && in_array(App::environment(), self::ENABLE_ENVIRONMENT, true)) {
            $this->writeLog($request);
        }

        return $next($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    private function writeLog(\Illuminate\Http\Request $request): void
    {
        \Log::debug('ライトログだよ');
        \Log::debug($request->method(), ['url' => $request->fullUrl(), 'request' => $request->all()]);
    }
}
