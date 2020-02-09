<?php

namespace App\Models\Passport;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Token
 * @package App\Models\Passport
 */
class Token extends \Laravel\Passport\Token
{
    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        $provider = config('auth.guards.' . config('auth.active-guard') . '.provider');
        return $this->belongsTo(config('auth.providers.' . $provider . '.model'));
    }
}