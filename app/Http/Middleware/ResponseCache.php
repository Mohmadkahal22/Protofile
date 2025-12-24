<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ResponseCache
{
    /**
     * Handle an incoming request and cache GET responses in Redis.
     */
    public function handle(Request $request, Closure $next)
    {
        // Only cache safe GET requests
        if (! $request->isMethod('get')) {
            return $next($request);
        }

        $ttl = (int) env('CACHE_RESPONSE_TTL', 60);
        $key = 'response_cache:' . md5($request->fullUrl());

        try {
            if (Cache::store('redis')->has($key)) {
                $cached = Cache::store('redis')->get($key);
                if (is_array($cached) && isset($cached['content'])) {
                    $response = response($cached['content'], $cached['status'] ?? 200);
                    if (! empty($cached['headers']) && is_array($cached['headers'])) {
                        $response->headers->add($cached['headers']);
                    }
                    return $response;
                }
            }
        } catch (\Exception $e) {
            // Redis may be down — silently continue to live response
        }

        $response = $next($request);

        // Only cache successful, text/json/html responses
        try {
            if ($response instanceof Response && $response->getStatusCode() === 200) {
                $contentType = $response->headers->get('Content-Type', '');
                if (str_contains($contentType, 'text') || str_contains($contentType, 'json') || str_contains($contentType, 'application/javascript')) {
                    $payload = [
                        'content' => $response->getContent(),
                        'status' => $response->getStatusCode(),
                        'headers' => array_filter($response->headers->all(), function ($v) { return ! empty($v); }),
                    ];

                    try {
                        Cache::store('redis')->put($key, $payload, $ttl);
                    } catch (\Exception $e) {
                        // ignore redis store errors
                    }
                }
            }
        } catch (\Exception $e) {
            // ignore any serialization or header issues
        }

        return $response;
    }
}
