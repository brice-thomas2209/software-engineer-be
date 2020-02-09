<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Property;
use App\Http\Requests\PropertyStoreRequest;
use App\Http\Resources\PropertyResource;

class PropertyController extends Controller
{
    /**
     * @param PropertyStoreRequest $request
     * @return PropertyResource
     * @throws Exception
     */
    public function store(PropertyStoreRequest $request): PropertyResource
    {
        $property = Property::create($request->only('suburb', 'state', 'country', 'guid'));

        return new PropertyResource($property);
    }
}
