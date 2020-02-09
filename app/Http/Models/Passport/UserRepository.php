<?php

namespace App\Models\Passport;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use Laravel\Passport\Bridge\User;

class UserRepository extends \Laravel\Passport\Bridge\UserRepository
{
    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        $provider = config('auth.guards.' . config('auth.active-guard') . '.provider');
        if (is_null($model = config('auth.providers.'.$provider.'.model'))) {
            throw new RuntimeException('Unable to determine authentication model from configuration.');
        }

        if (method_exists($model, 'findForPassport')) {
            $user = (new $model)->findForPassport($username);
        } else {
            $user = (new $model)->where('email', $username)->first();
        }

        if (! $user) {
            return;
        } elseif (method_exists($user, 'validateForPassportPasswordGrant')) {
            if (! $user->validateForPassportPasswordGrant($password)) {
                return;
            }
        } elseif (! $this->hasher->check($password, $user->getAuthPassword())) {
            return;
        }
        return new User($user->getAuthIdentifier());
    }
}