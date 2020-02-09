<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Property;
use Illuminate\Support\Facades\DB;

class PropertyAnalyticsSummaryController extends Controller
{

     /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $request->validate([
            'type'  => 'required|in:suburb,state,country',
            'value' => 'required|string|max:150' 
        ]);
        extract($request->only(['type', 'value']));
        $pivotTable = (new Property)->analyticTypes()->getTable();
        $propertyTable = (new Property)->getTable();

        $values = DB::table($pivotTable)
            ->join($propertyTable , "{$propertyTable}.id", '=', "{$pivotTable}.property_id")
            ->where($type, $value)
            ->select('value')->get(); 
            
        $getNullValueCount = $values->where('value', null)->count();   
        $values = $values->pluck('value');
        $total = $values->count();
        $perWithoutValue = ($getNullValueCount / $total) * 100;

        return response()->json([
            'data' => [
                'min' => $values->min(),
                'max' => $values->max(),
                'median' => $values->median(),
                'per_with_value' => number_format(100 - $perWithoutValue, 2),
                'per_without_value' => number_format($perWithoutValue, 2),
            ],
        ]);
    }
}
