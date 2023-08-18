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
    public function ShowAllFinancials()
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

    public function ShowMonthlyFinancials($month)
    {
        $Monthlyfinancial = Financial::where('month', $month)->get();
        if (!$Monthlyfinancial) {
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

<<<<<<< HEAD
    public function store(Request $request)
=======
    public function store()
>>>>>>> c49dff98 (neew)
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
            'status code'=>201,
        ]);
    }

}
