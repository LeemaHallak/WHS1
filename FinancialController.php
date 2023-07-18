<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Financial;
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
        $total_costs = Cost::where('date', $curr_month)->sum('cost');
        $branch_id = $request->branch_id;
        $total_salaries = Employee::where('branch_id', $branch_id)->sum('salary');
        $earnings = $incomings - $total_salaries - $outgoings;
        $Financial = Financial::create([
            'branch_id' => $branch_id,
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
