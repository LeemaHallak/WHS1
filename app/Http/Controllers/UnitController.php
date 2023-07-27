<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function showUnits()
    {
        $units = Unit::get('unit_name');
        return response()->json([
            'data'=>$units,
            'status code'=>http_response_code(),
        ]);
    }

    public function addUnits(Request $request)
    {
        $units = Unit::create([
            'unit_name' => $request->unit_name,
        ]);

        return response()->json([
            'massage'=> 'the unit has been added successfully',
            'data'=>$units->unit_name,
            'status code'=>http_response_code(),
        ]);
    }
}
