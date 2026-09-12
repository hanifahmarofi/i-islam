<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    // Check if logged in
    if (!auth()->check()) {
        return redirect('/login');
    }

    // Get the role and make it lowercase for comparison
    $role = strtolower(auth()->user()->role); 

    // Check if role is admin
    if ($role === 'admin') {
        return $next($request);
    }

    // DEBUGGING: If it fails, show us what the role actually is!
    dd("Access Denied! Your role is: " . auth()->user()->role); 
    
    // (Once fixed, you can remove the line above and uncomment the line below)
    // abort(403, 'Unauthorized access - Admins only!');
}
}
