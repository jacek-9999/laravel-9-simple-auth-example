<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class UserIsActiveVerifiedHasActiveCompany
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $unlogged =  User::where('email', $request->get('email'))->first();
        if (($unlogged->role === 'user') && (!$unlogged->verified || !$unlogged->active || !$unlogged->hasActiveCompany()))
        {
            return redirect('/login')
                ->with('status', 'Konto nie jest zweryfikowane, nie jest aktywne, nie posiada aktywnej firmy lub nie ma roli użytkownika');
        }
        return $next($request);
    }
}
