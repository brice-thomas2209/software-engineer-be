<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Property;
use App\AnalyticType;
use App\Http\Resources\AnalyticTypeResource;
use App\Http\Requests\AnalyticTypeStoreRequest;
use App\Http\Requests\AnalyticTypeUpdateRequest;

class PropertyAnalyticController extends Controller
{
    /**
     * @param Property $property
     * @param AnalyticTypeStoreRequest
     * @return AnalyticTypeResource
     */
    public function store(Property $property, AnalyticTypeStoreRequest $analyticTypeStoreRequest)
    {
        $analyticType = AnalyticType::create($analyticTypeStoreRequest->only([
            'name', 'units', 'is_numeric', 'num_decimal_places'
        ]));

        $property->analyticTypes()->attach($analyticType, ['value' => request('value')]);

        return new AnalyticTypeResource($analyticType);
    }

    /**
     * @param Property $property
     * @param AnalyticType $analyticType
     * @param AnalyticTypeUpdateRequest
     * @return AnalyticTypeResource
     */
    public function update(Property $property, AnalyticType $analyticType, AnalyticTypeUpdateRequest $analyticTypeUpdateRequest)
    {
        $analyticType = $analyticType->fill($analyticTypeUpdateRequest->only([
            'name', 'units', 'is_numeric', 'num_decimal_places'
        ]));
        $analyticType->save();
        
        $property->analyticTypes()->syncWithoutDetaching([$analyticType->id]);
        if(request('value'))
            $property->analyticTypes()->save($analyticType, ['value' => request('value')]);

        return new AnalyticTypeResource($analyticType);
    }

    /**
     * @param Property $property
     * @return AnalyticTypeResource
     */
    public function get(Property $property)
    {
        return AnalyticTypeResource::collection($property->analyticTypes()->get());
    }
}
