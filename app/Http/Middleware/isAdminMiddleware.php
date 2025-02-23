<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponseTrait;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isAdminMiddleware
{
    use ApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->is_admin){
            throw new HttpResponseException($this->errorResponse(null, 'Unauthorized', 401));
        }
            return $next($request);
    }
}
