<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use App\Http\Requests\StoreCostRequest;
use App\Http\Requests\UpdateCostRequest;
use App\Models\Manager;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CostController extends Controller
{
    public function addCost(Request $request)
    {
        $manager = new Manager();
        $role = $manager->role();
        if($role == 1){
            $branchId = $manager->branch();
            $managerId = Auth::id();
        }
        else if($role == 3){
            $branchId = $request->branchId;
            $managerId = $request->managerId;
            if(Manager::find($managerId)->employee->branch_id != $branchId){
                return response()->json([
                    'massage' => 'please choose another branch or manager'
                ]);
            }
        }
        $cost = Cost::query()->create([
            'manager_id'=> $managerId,
            'content'=>$request->content,
            'date'=>$request->date,
            'cost'=>$request->cost,
        ]);
        $cost->branch_id = $branchId;

        return response()->json([
            'data'=>$cost,
            'status code'=>http_response_code(),
        ]);
    }

    public function showCosts(Request $request, $type = null)
    {
        $costs = Cost::query();
        if($type == 'branch'){
            $$manager = new Manager();
            $role = $manager->role();
            if($role == 1){
                $branchId = $manager->branch();
            }
            else if($role == 3){
                $branchId = $request->branch_id;
            }
            $costs = $costs->where('branch_id', $branchId)->get();
        }
        elseif($type == 'mine'){
            $costs = $costs->where('manager_id', Auth::id())->get();
        }
        elseif($type == 'manager'){
            $costs = $costs->where('manager_id', $request->manager_id)->get();
        }
        elseif($type == null){
            $costs = $costs->get();
        }
        return response()->json([
            'data'=>$costs,
            'status code'=>http_response_code(),
        ]);
    }
}
