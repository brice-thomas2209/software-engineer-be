<?php

namespace App\Models\Passport;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Token
 * @package App\Models\Passport
 */
class Client extends \Laravel\Passport\Client
{
    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            config(
                'auth.providers.' . config('auth.guards.' . config('auth.active-guard') . '.provider') . '.model'
            )
        );
    }
}