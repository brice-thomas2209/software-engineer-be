<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Passport\Client;
use App\Models\Passport\Token;
use Closure;
use Laravel\Passport\Passport;

class AuthenticateUser
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->request->add([
            'grant_type' => 'password',
            'client_id' => config('auth.oauth.client.id'),
            'client_secret' => config('auth.oauth.client.secret'),
            'scope' => 'user-access'
        ]);

        config([
            'auth.active-guard' => 'user-api'
        ]);

        Passport::useClientModel(Client::class);
        Passport::useTokenModel(Token::class);
        Passport::tokensCan([
            'user-access' => 'UserAccess',
        ]);

        return $next($request);
    }
}
