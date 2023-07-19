<?php

namespace App\Http\Controllers;

use App\Models\BranchesEquipments;
use App\Models\Cost;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\EquipmentFix;
use App\Models\Financial;
use App\Models\InnerTransaction;
use App\Models\Shipment;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function ShowAllF()
    {
        $financial = Financial::all();
        if ($financial->isEmpty()) {
            return response()->json([
                'message' => 'no financial to show',
                'status code' => 204,
            ]);
        }
        return response()->json([
            'data'=>$financial,
            'status code' => 200,
        ]);
    }

    public function ShowMonthlyF(Request $request)
    {
        $month = $request->month;
        $Monthlyfinancial = Financial::where('month', $month);
        if ($Monthlyfinancial->isEmpty()) {
            return response()->json([
                'message' => 'no financial to show',
                'status code' => 204,
            ]);
        }
        return response()->json([
            'data'=>$Monthlyfinancial,
            'status code' => 200,
        ]);
    }

    public function store(Request $request)
    {
        $curr_month = Carbon::now()->month;
        $curr_shipment = Shipment::WhereMonth('shipment_date', $curr_month);
        $outgoings = $curr_shipment->where('I\O', 'In')->sum('shipment_cost');
        $incomings = $curr_shipment->where('I\O', 'Out')->sum('shipment_cost');
        $costs = Cost::whereMonth('date', $curr_month)->sum('cost');
        $equipment_costs = BranchesEquipments::whereMonth('date_in', $curr_month)->sum('cost');
        $EqFixing_costs = EquipmentFix::with('branches_equipments')->whereMonth('fix_date', $curr_month)->sum('fixing_cost');
        $transaction_costs = InnerTransaction::whereMonth('transaction_date', $curr_month)->sum('transaction_cost');
        $total_costs = $costs+$equipment_costs+$EqFixing_costs+$transaction_costs;
        $total_salaries = Employee::sum('salary');
        $earnings = $incomings - $total_salaries - $outgoings - $total_costs;
        $Financial = Financial::create([
            'month' => $curr_month,
            'outgoings' => $outgoings,
            'incomings'=> $incomings,
            'total_salaries'=>$total_salaries,
            'total_costs'=> $total_costs,
            'earnings'=> $earnings,
        ]);
        return response()->json([
            'data'=>$Financial,
            'status code'=>201,
        ]);
    }

    public function RemoveFinancial($id)
    {
        Financial::query()->find($id)->delete();
        return http_response_code();
    }
}
