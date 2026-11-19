<?php
namespace App\Http\Middleware;
use Closure;use Illuminate\Http\Request;
class Locale {public function handle(Request $request, Closure $next){app()->setLocale(in_array(session('locale'),['en','km','zh'])?session('locale'):'en');return $next($request);}}
