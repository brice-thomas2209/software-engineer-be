<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\AnalyticType;

class Property extends Model
{
    protected $guarded = [];

    protected $table = 'properties';

    /**
     * The analytic types that belong to the property.
     * @return BelongsToMany
     */
    public function analyticTypes(): BelongsToMany
    {
        return $this->belongsToMany(AnalyticType::class, 'property_analytics');
    }
}
