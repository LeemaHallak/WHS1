<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use App\Http\Requests\StoreCostRequest;
use App\Http\Requests\UpdateCostRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CostController extends Controller
{
    public function addCost(Request $request)
    {
        $manager = auth()->guard('manager-api')->user();
        $employee = $manager->employee;
        $branch_id = $employee->branch_id;
        $addCost = Cost::query()->create([
            'branch_id'=>$branch_id,
            'manager_id'=> Auth::id(),
            'content'=>$request->content,
            'date'=>$request->date,
            'cost'=>$request->cost,
        ]);

        return response()->json([
            'data'=>$addCost,
            'status code'=>http_response_code(),
        ]);
    }

    public function showCosts($type, Request $request)
    {
        $costs = Cost::query();
        if($type == 'branch'){
            $manager = auth()->guard('manager-api')->user();
            $employee = $manager->employee;
            $branch_id = $employee->branch_id;
            $costs = $costs->where('branch_id', $branch_id)->get();
        }
        elseif($type == 'mine'){
            $costs = $costs->where('manager_id', Auth::id())->get();
        }
        elseif($type == 'manager'){
            $costs = $costs->where('manager_id', $request->manager_id)->get();
        }
        return response()->json([
            'data'=>$costs,
            'status code'=>http_response_code(),
        ]);
    }
}
