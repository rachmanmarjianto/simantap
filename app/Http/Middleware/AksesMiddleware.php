<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class AksesMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next, ...$role): Response
	{
		// dd(session('userdata'));
		// dd(session('userdata')['nama_role'], $role);
		if (Auth::check() && (in_array(session('userdata')['idrole'], $role) || in_array('All', $role) ) ) // admin aplikasi
		{
			return $next($request);
		}
		else
		{
			Auth::logout();
			return redirect('/login')->with([
				'status' => 'danger',
				'message' => 'Tidak memiliki akses pada halaman tersebut'
			]);
		}
	}
}
