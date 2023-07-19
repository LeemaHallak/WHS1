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

    public function CostsStatistics($type)
    {
        $manager = auth()->guard('manager-api')->user();
        $employee = $manager->employee;
        $branch_id = $employee->branch_id;
        $BranchCosts = Cost::where('branch_id', $branch_id);

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

    public function InProductsStatistics($type)
    {
        $manager = auth()->guard('manager-api')->user();
        $employee = $manager->employee;
        $branch_id = $employee->branch_id;
        $Products = BranchesProducts::where('branch_id', $branch_id);

        if($type == 'daily'){
            $dailyInProducts = $Products
            ->selectRaw('date_in as day,
                        SUM(quantity) as total_quantity')
            ->groupBy('day')
            ->get();
            return $dailyInProducts;
        }
        elseif($type == 'monthly'){
            $monthlyInProducts= $Products
            ->selectRaw('MONTH(date_in) as month,
                        YEAR(date_in) as year,
                        SUM(quantity) as total_cost')
            ->groupBy('month', 'year')
            ->get();
            return $monthlyInProducts;
        }
        elseif($type == 'yearly'){
            $yearlyInProducts= $Products
            ->selectRaw('YEAR(date_in) as year,
                        SUM(quantity) as total_quantity')
            ->groupBy('year')
            ->get();
            return $yearlyInProducts;
        }
    }

    public function InProductsByProducts($type)
    {
        $manager = auth()->guard('manager-api')->user();
        $employee = $manager->employee;
        $branch_id = $employee->branch_id;
        $Products = BranchesProducts::where('branch_id', $branch_id);

        if($type == 'daily'){
            $dailyInProducts = $Products
            ->selectRaw('date_in as day, product_id as product,
                        SUM(quantity) as total_quantity')
            ->groupBy('day', 'product_id')
            ->get();
            return $dailyInProducts;
        }
        elseif($type == 'monthly'){
            $monthlyInProducts= $Products
            ->selectRaw('MONTH(date_in) as month,
                        YEAR(date_in) as year,
                        product_id as product,
                        SUM(quantity) as total_cost')
            ->groupBy('month', 'year', 'product_id')
            ->get();
            return $monthlyInProducts;
        }
        elseif($type == 'yearly'){
            $yearlyInProducts= $Products
            ->selectRaw('YEAR(date_in) as year,
                        product_id as product,
                        SUM(quantity) as total_quantity')
            ->groupBy('year', 'product_id')
            ->get();
            return $yearlyInProducts;
        }
    }

    public function OutProductsStatistics($type)
    {
        $manager = auth()->guard('manager-api')->user();
        $employee = $manager->employee;
        $branch_id = $employee->branch_id;

        if($type == 'daily'){
            $data = Order::select([
                DB::raw('DATE(orders.order_date) as day'),
                DB::raw('SUM(order_products.quantity) as all_quantity, BranchesProducts_id as product')
                ])->join('order_lists','orders.OrderList_id','=','order_lists.id')
                ->join('order_products','order_lists.id','=','order_products.OrderList_id')
                ->where('branch_id', $branch_id)
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
                ->where('branch_id', $branch_id)
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
                ->where('branch_id', $branch_id)
                ->groupBy('year')
                ->groupBy('product')
                ->orderBy('year','asc')
                ->get();
                return $data;
        }
    }

    public function ordersIncomings($type)
    {
        $manager = auth()->guard('manager-api')->user();
        $employee = $manager->employee;
        $branch_id = $employee->branch_id;

        if($type == 'daily'){
            $data = Order::select([
                DB::raw('DATE(orders.order_date) as day'),
                DB::raw('SUM(order_lists.order_cost) as total_cost')
                ])->join('order_lists','orders.OrderList_id','=','order_lists.id')
                ->where('branch_id', $branch_id)
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
                ->where('branch_id', $branch_id)
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
                ->where('branch_id', $branch_id)
                ->groupBy('year')
                ->orderBy('year','asc')
                ->get();
                return $data;
        }
    }

    public function earningsStatistics($type)
    {
        $manager = auth()->guard('manager-api')->user();
        $employee = $manager->employee;
        $branch_id = $employee->branch_id;

        if($type == 'daily'){
        $costs = Cost::where('branch_id', $branch_id)
                ->groupBy('date')
                ->select('date', DB::raw('SUM(cost) as total_cost'))
                ->get();
        $equipment_costs = BranchesEquipments::where('branch_id', $branch_id)
                            ->groupBy('date_in')
                            ->select('date_in', DB::raw('SUM(cost) as total_cost'))
                            ->get();
        $EqFixing_costs = EquipmentFix::whereHas('branchEquipment',
                            function ($query) use ($branch_id){
                            $query->where('branch_id', $branch_id);
                            })
                            ->groupBy('fix_date')
                            ->select('fix_date', DB::raw('SUM(fixing_cost) as total_cost'))
                            ->get();
        $transaction_costs = InnerTransaction::where('branch_id', $branch_id)
                            ->groupBy('transaction_date')
                            ->select('transaction_date', DB::raw('SUM(transaction_date) as total_cost'))
                            ->get();
        $total_costs = $costs + $equipment_costs + $EqFixing_costs + $transaction_costs;

        $orders = Order::select([
            DB::raw('DATE(orders.order_date) as day'),
            DB::raw('SUM(order_lists.order_cost) as total_cost')
            ])->join('order_lists','orders.OrderList_id','=','order_lists.id')
            ->where('branch_id', $branch_id)
            ->groupBy('day')
            ->orderBy('day', 'asc')
            ->get();
        }
        $total_salaries = Employee::where('branch_id', $branch_id)->sum('salary');

        $earnings = $orders - $total_salaries - $total_costs;
        return response()->json([
            'data'=>$earnings,
            'status code'=>201,
        ]);
    }
}
