<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and is admin (type == 1)
        if (Auth::check() && Auth::user()->type == 1) {
            return $next($request);
        }

        // For web requests, return a proper error page
        if ($request->expectsJson()) {
            return response()->json(['error' => 'you are not Admin'], 401);
        }

        // Return HTML error page for web requests
        return response('
            <!DOCTYPE html>
            <html>
            <head>
                <title>Access Denied</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        margin: 0;
                        background-color: #f8f9fa;
                    }
                    .error-container {
                        text-align: center;
                        padding: 2rem;
                        background: white;
                        border-radius: 8px;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    }
                    .error-code {
                        font-size: 48px;
                        color: #dc3545;
                        margin-bottom: 1rem;
                    }
                    .error-message {
                        font-size: 18px;
                        color: #6c757d;
                    }
                </style>
            </head>
            <body>
                <div class="error-container">
                    <div class="error-code">401</div>
                    <div class="error-message">You are not authorized to access the admin panel.</div>
                    <div style="margin-top: 1rem;">
                        <a href="/" style="color: #007bff; text-decoration: none;">← Back to Home</a>
                    </div>
                </div>
            </body>
            </html>
        ', 401);
    }
}
