<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use App\Services\ResponseService;

class CheckInternetConnection
{

    protected $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        try {
            $response = Http::timeout(5)->get('http://www.google.com');
            if ($response->successful()) {
                return $next($request);
            } else {
                return $this->responseService->error('No Internet Connection',503);
            }
        } catch (\Exception $ex) {
            return $this->responseService->error('No Internet Connection' , 503);
        }
    }
}
