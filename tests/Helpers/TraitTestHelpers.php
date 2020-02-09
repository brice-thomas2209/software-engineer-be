<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Client;

trait TraitTestHelpers
{
    protected function installPassport()
    {
        if (Client::wherePasswordClient(true)->first() !== null) {
            return;
        }

        Artisan::call('passport:install');
        $client = Client::wherePasswordClient(true)->first();
        config([
            'auth.oauth.client.id' => $client->id,
            'auth.oauth.client.secret' => $client->secret,
        ]);
    }
}