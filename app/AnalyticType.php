<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Property;

class AnalyticType extends Model
{
    protected $guarded = [];

    protected $table = 'analytic_types';

    /**
     * The properties that belong to the analytic type.
     * @return BelongsToMany
     */
    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'property_analytics');
    }
}
