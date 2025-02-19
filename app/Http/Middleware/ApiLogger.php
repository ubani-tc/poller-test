<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiLogger
{
    public function handle(Request $request, Closure $next)
    {
        // Generate a unique identifier for this request
        $requestId = uniqid('req_');

        // Log the incoming request
        $this->logRequest($request, $requestId);

        // Process the request
        $response = $next($request);

        // Log the response
        $this->logResponse($response, $requestId);

        return $response;
    }

    private function logRequest(Request $request, string $requestId)
    {
        $logData = [
            'id' => $requestId,
            'timestamp' => now()->toIso8601String(),
            'ip' => $request->ip(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'body' => $request->all()
        ];

        Log::info('API Request', $logData);
    }

    private function logResponse($response, string $requestId)
    {
        $logData = [
            'id' => $requestId,
            'timestamp' => now()->toIso8601String(),
            'status' => $response->status(),
            'headers' => $response->headers->all()
        ];

        // Safely decode JSON response
        try {
            $logData['body'] = json_decode($response->getContent(), true);
        } catch (\Exception $e) {
            $logData['body'] = 'Could not decode response body';
        }

        Log::info('API Response', $logData);
    }
}
