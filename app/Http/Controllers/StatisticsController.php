<?php

namespace App\Http\Controllers;

use App\Models\BranchesEquipments;
use App\Models\BranchesProducts;
use App\Models\Cost;
use App\Models\Employee;
use App\Models\EquipmentFix;
use App\Models\Financial;
use App\Models\InnerTransaction;
use App\Models\Order;
use App\Models\OrderList;
use App\Models\OrderProducts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\CssSelector\Node\FunctionNode;

class StatisticsController extends Controller
{

    public function CostsStatistics($type, $branchId = null)
    {
        $manager = auth()->guard('manager-api')->user();
        $role = $manager->role_id;
        if($role == 1){
            $employee = $manager->employee;
            $branchId = $employee->branch_id;
        }
        $BranchCosts = Cost::where('branch_id', $branchId);

        if($type == 'daily'){
            $dailyCosts = $BranchCosts->groupBy('date')
            ->selectRaw('date, SUM(cost) as total_cost')
            ->get();
            return $dailyCosts;
        }
        elseif($type == 'monthly'){
            $monthlyCosts= $BranchCosts->selectRaw('MONTH(date) as month, YEAR(date) as year, SUM(cost) as total_cost')
            ->groupBy('month', 'year')
            ->get();
            return $monthlyCosts;
        }
        elseif($type == 'yearly'){
            $yearlyCosts= $BranchCosts->selectRaw('YEAR(date) as year, SUM(cost) as total_cost')
            ->groupBy('year')
            ->get();
            return $yearlyCosts;
        }
    }

    public function InProductsStatistics($type, $branchId = null)
    {
        $manager = auth()->guard('manager-api')->user();
        $role = $manager->role_id;
        if($role == 1){
            $employee = $manager->employee;
            $branchId = $employee->branch_id;
        }
        $Products = BranchesProducts::where('branch_id', $branchId);

        if($type == 'daily'){
            $dailyInProducts = $Products
            ->selectRaw('date_in as day,
                        SUM(in_quantity) as total_quantity')
            ->groupBy('day')
            ->get();
            return $dailyInProducts;
        }
        elseif($type == 'monthly'){
            $monthlyInProducts= $Products
            ->selectRaw('MONTH(date_in) as month,
                        YEAR(date_in) as year,
                        SUM(in_quantity) as total_quantity')
            ->groupBy('month', 'year')
            ->get();
            return $monthlyInProducts;
        }
        elseif($type == 'yearly'){
            $yearlyInProducts= $Products
            ->selectRaw('YEAR(date_in) as year,
                        SUM(in_quantity) as total_quantity')
            ->groupBy('year')
            ->get();
            return $yearlyInProducts;
        }
    }

    public function InProductsByProducts($type, $branchId = null)
    {
        $manager = auth()->guard('manager-api')->user();
        $role = $manager->role_id;
        if($role == 1){
            $employee = $manager->employee;
            $branchId = $employee->branch_id;
        }
        $Products = BranchesProducts::where('branch_id', $branchId);

        if($type == 'daily'){
            $dailyInProducts = $Products
            ->selectRaw('date_in as day, product_id as product,
                        SUM(in_quantity) as total_quantity')
            ->groupBy('day', 'product_id')
            ->get();
            return $dailyInProducts;
        }
        elseif($type == 'monthly'){
            $monthlyInProducts= $Products
            ->selectRaw('MONTH(date_in) as month,
                        YEAR(date_in) as year,
                        product_id as product,
                        SUM(in_quantity) as total_quantity')
            ->groupBy('month', 'year', 'product_id')
            ->get();
            return $monthlyInProducts;
        }
        elseif($type == 'yearly'){
            $yearlyInProducts= $Products
            ->selectRaw('YEAR(date_in) as year,
                        product_id as product,
                        SUM(in_quantity) as total_quantity')
            ->groupBy('year', 'product_id')
            ->get();
            return $yearlyInProducts;
        }
    }

    public function OutProductsStatistics($type, $branchId = null)
    {
        $manager = auth()->guard('manager-api')->user();
        $role = $manager->role_id;
        if($role == 1){
            $employee = $manager->employee;
            $branchId = $employee->branch_id;
        }

        if($type == 'daily'){
            $data = Order::select([
                DB::raw('DATE(orders.order_date) as day'),
                DB::raw('SUM(order_products.quantity) as all_quantity, BranchesProducts_id as product')
                ])->join('order_lists','orders.OrderList_id','=','order_lists.id')
                ->join('order_products','order_lists.id','=','order_products.OrderList_id')
                ->where('branch_id', $branchId)
                ->groupBy('day')
                ->groupBy('product')
                ->orderBy('day', 'asc')
                ->get();
                return $data;
        }
        elseif($type == 'monthly'){
            $data = Order::select([
                DB::raw('DATE_FORMAT(orders.order_date, "%m") as month'),
                DB::raw('DATE_FORMAT(orders.order_date, "%Y") as year'),
                DB::raw('SUM(order_products.quantity) as all_quantity, BranchesProducts_id as product')
                ])->join('order_lists','orders.OrderList_id','=','order_lists.id')
                ->join('order_products','order_lists.id','=','order_products.OrderList_id')
                ->where('branch_id', $branchId)
                ->groupBy('month', 'year')
                ->groupBy('product')
                ->orderBy('year','asc')
                ->orderBy('month','asc')
                ->get();
                return $data;
        }
        elseif($type == 'yearly'){
            $data = Order::select([
                DB::raw('DATE_FORMAT(orders.order_date, "%Y") as year'),
                DB::raw('SUM(order_products.quantity) as all_quantity, BranchesProducts_id as product')
                ])->join('order_lists','orders.OrderList_id','=','order_lists.id')
                ->join('order_products','order_lists.id','=','order_products.OrderList_id')
                ->where('branch_id', $branchId)
                ->groupBy('year')
                ->groupBy('product')
                ->orderBy('year','asc')
                ->get();
                return $data;
        }
    }

    public function ordersIncomings($type, $branchId = null)
    {
        $manager = auth()->guard('manager-api')->user();
        $role = $manager->role_id;
        if($role == 1){
            $employee = $manager->employee;
            $branchId = $employee->branch_id;
        }

        if($type == 'daily'){
            $data = Order::select([
                DB::raw('DATE(orders.order_date) as day'),
                DB::raw('SUM(order_lists.order_cost) as total_cost')
                ])->join('order_lists','orders.OrderList_id','=','order_lists.id')
                ->where('branch_id', $branchId)
                ->groupBy('day')
                ->orderBy('day', 'asc')
                ->get();
                return $data;
        }
        elseif($type == 'monthly'){
            $data = Order::select([
                DB::raw('DATE_FORMAT(orders.order_date, "%m") as month'),
                DB::raw('DATE_FORMAT(orders.order_date, "%Y") as year'),
                DB::raw('SUM(order_lists.order_cost) as total_cost')
                ])->join('order_lists','orders.OrderList_id','=','order_lists.id')
                ->where('branch_id', $branchId)
                ->groupBy('month', 'year')
                ->orderBy('year','asc')
                ->orderBy('month','asc')
                ->get();
                return $data;
        }
        elseif($type == 'yearly'){
            $data = Order::select([
                DB::raw('DATE_FORMAT(orders.order_date, "%Y") as year'),
                DB::raw('SUM(order_lists.order_cost) as total_cost')
                ])->join('order_lists','orders.OrderList_id','=','order_lists.id')
                ->where('branch_id', $branchId)
                ->groupBy('year')
                ->orderBy('year','asc')
                ->get();
                return $data;
        }
    }

    public function earningsStatistics($branchId = null)
    {
        $manager = auth()->guard('manager-api')->user();
        $role = $manager->role_id;
        if($role == 1){
            $employee = $manager->employee;
            $branchId = $employee->branch_id;
        }
        
        $costs = Cost::where('branch_id', $branchId)
                ->selectRaw('MONTH(date), SUM(cost) as total_cost')
                ->groupBy('MONTH(date)')
                ->get();
        $equipment_costs = BranchesEquipments::where('branch_id', $branchId)
                            ->selectRaw('MONTH(date_in), SUM(cost) as total_cost')
                            ->groupBy('MONTH(date_in)')
                            ->get();
        $EqFixing_costs = EquipmentFix::whereHas('branches_equipments',
                            function ($query) use ($branchId){
                            $query->where('branch_id', $branchId);
                            })
                            ->selectRaw('MONTH(fix_date), SUM(fixing_cost) as total_cost')
                            ->groupBy('MONTH(fix_date)')
                            ->get();
        $transaction_costs = InnerTransaction::where('DestinationBranch_id', $branchId)
                            ->selectRaw('MONTH(transaction_date), SUM(transaction_cost) as total_cost')
                            ->groupBy('MONTH(transaction_date)')
                            ->get();
        $total_costs = $costs->sum('total_cost')
                        + $equipment_costs->sum('total_cost')
                        + $EqFixing_costs->sum('total_cost')
                        + $transaction_costs->sum('total_cost');

        $orders = Order::selectRaw(
            'MONTH(orders.order_date) as month,
            SUM(order_lists.order_cost) as total_cost')
            ->join('order_lists','orders.OrderList_id','=','order_lists.id')
            ->where('branch_id', $branchId)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();
            
        $total_salaries = Employee::where('branch_id', $branchId)->sum('salary');
        $earnings = $orders->sum('total_cost') - $total_salaries - $total_costs;

        return response()->json([
            'branch'=> $branchId,
            'costs' => $costs,
            'equipment costs'=>$equipment_costs,
            'Equipment fixing costs'=>$EqFixing_costs,
            'transactions costs'=>$transaction_costs,
            'total costs'=>$total_costs,
            'orders'=>$orders,
            'total salaries'=> $total_salaries,
            'data'=>$earnings,
            'status code'=>201,
        ]);
    }
}
