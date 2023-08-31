<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchesEquipment;
use App\Models\BranchesEquipmentApprove;
use App\Models\BranchesEquipments;
use App\Models\Equipment;
use App\Models\EquipmentFix;
use App\Models\Manager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class EquipmentController extends Controller
{
    public function showEquipment(Request $request, $branchId = null) 
    {
        $getBy = $request->input('get_by');
        $queryParams = $request->only(['employee_id', 'date_in', 'branch_id', 'name']);
        $GetEquipment = BranchesEquipment::query()->with('equipments')->when($branchId, function ($query) use ($branchId) {
            return $query->where('branch_id', $branchId);
        });
    
        if ($getBy == 'branch') {
            $GetEquipment = $GetEquipment->where('branch_id', $queryParams['branch_id'])->get();
        } 
        elseif ($getBy == 'employee') {
            $GetEquipment = $GetEquipment->where('employee_id', $queryParams['employee_id'])->get();
        }
        elseif ($getBy == 'date in') {
            $Equipment = $GetEquipment->where('date_in', $queryParams['date_in']);
            $GetEquipment = $Equipment->get();
            $LotQuantity = $Equipment->sum('quantity');
            $LotCost = $Equipment->sum('cost');
        } 
        elseif ($getBy == 'name') {
            $Equipment = $GetEquipment->whereHas('equipments', function($q) use ($queryParams){
                $q->where('equipment_name', $queryParams['name']);
            });
            $GetEquipment = $Equipment->get();
            $EquipmentQuantity = $Equipment->sum('quantity');
        }
    
        if ($GetEquipment->isEmpty()) {
            return response()->json([
                'message' => 'No equipment found',
                'status code' => http_response_code(),
            ]);
        }
    
        if (isset($LotQuantity) && isset($LotCost)) {
            return response()->json([
                'Equipments' => $GetEquipment,
                'total quantities in this lot' => $LotQuantity,
                'total cost for this purchase lot' => $LotCost,
                'status code' => http_response_code(),
            ]);
        }
    
        if (isset($EquipmentQuantity)) {
            return response()->json([
                'Equipments' => $GetEquipment,
                'total quantities' => $EquipmentQuantity,
                'status code' => http_response_code(),
            ]);
        }
    
        return response()->json([
            'data' => $GetEquipment,
            'status code' => http_response_code(),
        ]);
    }
    
    public function showCosts($branchId, $fixingCost)
    { 
        $GetCost = BranchesEquipment::query()->when($branchId, fn($query) =>
                $query->where('branch_id', $branchId))
                ->sum('cost');
        if ($fixingCost == 'true'){
            $branch = Branch::find($branchId);
            $GetfixingCost = $branch->equipments_fixing()->sum('fixing_cost');
            return response()->json([
                'total cost is:' => $GetCost,
                'total fixing cost is:' => $GetfixingCost
            ], 200);
        }
        return response()->json([
            'total cost is:' => $GetCost
        ], 200);
    }

    public function AddExistingEquipment(Request $request, $equipmentIid)
    {
        $manager = new Manager();
        $Model = $manager->role() == 1 
            ? BranchesEquipments::class : BranchesEquipmentApprove::class;
        $branch_id = $request->branch_id;
        $employee_id = $request->employee_id;
        $quantity = $request->quantity;
        $cost = $request->cost;
        $date_in = $request->date_in;

        $existingEquipment = $Model::query()->create([
            'branch_id'=> $branch_id,
            'equipment_id'=>$equipmentIid,
            'employee_id'=>$employee_id,
            'quantity'=>$quantity,
            'cost'=>$cost,
            'date_in'=>$date_in,
            'available'=>$quantity,
        ]);
        return $existingEquipment;
    }
    
    public function AddSysEquipment(Request $request)
    {
        $equipment_name = $request->equipment_name;
        $description = $request->description;
        $new_SysEquipment = Equipment::query()->create([
            'equipment_name'=>$equipment_name,
            'description'=>$description,
        ]);
        return $new_SysEquipment;
    }

    public function AddNewEquipments(Request $request)
    {
        $new_SysEquipment = $this->AddSysEquipment($request);
        $equipment_id = $new_SysEquipment->id;
        $new_equipment = $this->AddExistingEquipment($request, $equipment_id);
        return response()->json([
            'system eqipment data' => $new_SysEquipment,
            'barnch equipment data' => $new_equipment
        ], 201);
    }

    public function editEquipment(Request $request, int $equipmentIid): JsonResponse
    {
        $equipment = Equipment::find($equipmentIid);
    
        if (!$equipment) {
            return response()->json([
                'error' => 'equipment not found'
            ], 400);
        }
        $validatedData = $request->validate([
            'equipment_name' => 'nullable',
            'description' => 'nullable',
        ]);
        $equipment->fill($validatedData);
        $equipment->save();
        return response()->json([
            'message' => 'equipment updated successfully'
        ], 200);
    }
}