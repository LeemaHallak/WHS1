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
use Illuminate\Http\Response;

class FinancialController extends Controller
{
    public function ShowAllFinancials()
    {
        $financial = Financial::all();
        if ($financial->isEmpty()) {
            return response()->json([
                'message' => 'no financial to show'
            ], Response::HTTP_NO_CONTENT);
        }
        return response()->json([
            'data'=>$financial,
        ], Response::HTTP_OK);
    }

    public function ShowMonthlyFinancials($month)
    {
        $Monthlyfinancial = Financial::where('month', $month)->get();
        if (!$Monthlyfinancial) {
            return response()->json([
                'message' => 'no financial to show'
            ], Response::HTTP_NO_CONTENT);
        }
        return response()->json([
            'data'=>$Monthlyfinancial
        ], Response::HTTP_OK);
    }

    public function store()
    {
        $currMonth = Carbon::now()->format('F');
        $currShipment = Shipment::WhereMonth('shipment_date', $currMonth);
        $outgoings = $currShipment->where('I\O', 'In')->sum('shipment_cost');
        $incomings = $currShipment->where('I\O', 'Out')->sum('shipment_cost');
        $costs = Cost::whereMonth('date', $currMonth)->sum('cost');
        $equipmentCosts = BranchesEquipments::whereMonth('date_in', $currMonth)->sum('cost');
        $EqFixingCosts = EquipmentFix::with('branches_equipments')->whereMonth('fix_date', $currMonth)->sum('fixing_cost');
        $transactionCosts = InnerTransaction::whereMonth('transaction_date', $currMonth)->sum('transaction_cost');
        $totalCosts = $costs+$equipmentCosts+$EqFixingCosts+$transactionCosts;
        $totalSalaries = Employee::sum('salary');
        $earnings = $incomings - $totalSalaries - $outgoings - $totalCosts;
        $Financial = Financial::create([
            'month' => $currMonth,
            'outgoings' => $outgoings,
            'incomings'=> $incomings,
            'total_salaries'=>$totalSalaries,
            'total_costs'=> $totalCosts,
            'earnings'=> $earnings,
        ]);
        return response()->json([
            'data'=>$Financial,
        ], Response::HTTP_CREATED);
    }

}
