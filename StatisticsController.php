<?php

namespace App\Http\Controllers;

use App\Models\BranchesProducts;
use App\Models\Cost;
use App\Models\Order;
use App\Models\OrderList;
use App\Models\OrderProducts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $Products = OrderList::with(['orders', 'orders.OrderProducts']);

        if($type == 'daily'){
            $data = Order::with('orderList')->withSum('orderProducts', 'quantity')->get('order_date');
        }
        elseif($type == 'monthly'){
            $monthlyQuantities = $Products
            ->selectRaw('branch_id, MONTH(orders.order_date) as order_month, SUM(order_products.quantity) as total_quantity')
            ->groupBy('branch_id', 'order_month')
            ->get();
            return $monthlyQuantities;
        }
        elseif($type == 'yearly'){
            $yearlyQuantities = $Products
            ->selectRaw('branch_id, YEAR(orders.order_date) as order_year, SUM(order_products.quantity) as total_quantity')
            ->groupBy('branch_id', 'order_year')
            ->get();
            return $yearlyQuantities;
        }
    }
}
