<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchesEquipment;
use App\Models\BranchesEquipments;
use App\Models\BranchesProducts;
use App\Models\Category;
use App\Models\Cost;
use App\Models\Employee;
use App\Models\EquipmentFix;
use App\Models\Financial;
use App\Models\InnerTransaction;
use App\Models\Manager;
use App\Models\Order;
use App\Models\OrderList;
use App\Models\OrderProducts;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\CssSelector\Node\FunctionNode;

class StatisticsController extends Controller
{
    public function CostsStatistics($type, $branchId = null)
    {
        $manager = new Manager();
            $role = $manager->role();
        if($role == 1){
            $branchId = $manager->branch();
        }
        $Costs = Cost::query();  
        if($branchId){
            $Products = $Costs->where('branch_id', $branchId);
        }
        if($type == 'daily'){
            $dailyCosts = $Costs->groupBy('date')
            ->selectRaw('date, SUM(cost) as total_cost')
            ->get();
            return response()->json([$dailyCosts, 200]);
        }
        elseif($type == 'monthly'){
            $monthlyCosts= $Costs->selectRaw('MONTH(date) as month, YEAR(date) as year, SUM(cost) as total_cost')
            ->groupBy('month', 'year')
            ->get();
            return response()->json([$monthlyCosts,200]);
        }
        elseif($type == 'yearly'){
            $yearlyCosts= $Costs->selectRaw('YEAR(date) as year, SUM(cost) as total_cost')
            ->groupBy('year')
            ->get();
            return response()->json([$yearlyCosts, 200]);
        }
    }

    public function InProductsStatistics($type, $branchId = null)
    {
        $manager = new Manager();
        $role = $manager->role();
        if($role == 1){
            $branchId = $manager->branch();
        }
        $Products = BranchesProducts::query();  
        if($branchId){
            $Products = $Products->where('branch_id', $branchId);
        }
        if($type == 'daily'){
            $dailyInProducts = $Products
            ->selectRaw('date_in as day,
                        SUM(in_quantity) as total_quantity')
            ->groupBy('day')
            ->get();
            return response()->json([$dailyInProducts, 200]);
        }
        elseif($type == 'monthly'){
            $monthlyInProducts= $Products
            ->selectRaw('MONTH(date_in) as month,
                        YEAR(date_in) as year,
                        SUM(in_quantity) as total_quantity')
            ->groupBy('month', 'year')
            ->get();
            return response()->json([$monthlyInProducts, 200]);
        }
        elseif($type == 'yearly'){
            $yearlyInProducts= $Products
            ->selectRaw('YEAR(date_in) as year,
                        SUM(in_quantity) as total_quantity')
            ->groupBy('year')
            ->get();
            return response()->json([$yearlyInProducts, 200]);
        }
    }
    public function InProductsByProducts($type, $branchId = null)
    {
        $manager = new Manager();
        $role = $manager->role();
        if($role == 1){
            $branchId = $manager->branch();
        }
        $Products = BranchesProducts::where('branch_id', $branchId);

        if($type == 'daily'){
            $dailyInProducts = $Products
            ->selectRaw('date_in as day, product_id as product,
                        SUM(in_quantity) as total_quantity')
            ->groupBy('day', 'product_id')
            ->get();
            return response()->json([$dailyInProducts, 200]);
        }
        elseif($type == 'monthly'){
            $monthlyInProducts= $Products
            ->selectRaw('MONTH(date_in) as month,
                        YEAR(date_in) as year,
                        product_id as product,
                        SUM(in_quantity) as total_quantity')
            ->groupBy('month', 'year', 'product_id')
            ->get();
            return response()->json([$monthlyInProducts, 200]);
        }
        elseif($type == 'yearly'){
            $yearlyInProducts= $Products
            ->selectRaw('YEAR(date_in) as year,
                        product_id as product,
                        SUM(in_quantity) as total_quantity')
            ->groupBy('year', 'product_id')
            ->get();
            return response()->json([$yearlyInProducts, 200]);
        }
    }

    public function InProductsBySupplier($type, $branchId = null)
    {
        $manager = new Manager();
        $role = $manager->role();
        if($role == 1){
            $branchId = $manager->branch();
        }
        $Products = BranchesProducts::query();  
        if($branchId){
            $Products = $Products->where('branch_id', $branchId);
        }
        if($type == 'daily'){
            $dailyInProducts = $Products
            ->selectRaw('date_in as day, supplier_id as supplier,
                        SUM(in_quantity) as total_quantity')
            ->groupBy('day',  'supplier')
            ->get();
            return response()->json([$dailyInProducts, 200]);
        }
        elseif($type == 'monthly'){
            $monthlyInProducts= $Products
            ->selectRaw('MONTH(date_in) as month,
                        YEAR(date_in) as year,
                        supplier_id as supplier,
                        SUM(in_quantity) as total_quantity')
            ->groupBy('month', 'year', 'supplier')
            ->get();
            return response()->json([$monthlyInProducts, 200]);
        }
        elseif($type == 'yearly'){
            $yearlyInProducts= $Products
            ->selectRaw('YEAR(date_in) as year,
                        supplier_id as supplier,
                        SUM(in_quantity) as total_quantity')
            ->groupBy('year', 'supplier')
            ->get();
            return response()->json([$yearlyInProducts, 200]);
        }
    }

    public function OutProductsStatistics($type, $branchId = null)
    {
        $manager = new Manager();
        $role = $manager->role();
        if($role == 1){
            $branchId = $manager->branch();
        }

        if($type == 'daily'){
            $dailyData = Order::select([
                DB::raw('DATE(orders.order_date) as day'),
                DB::raw('SUM(order_products.quantity) as all_quantity, BranchesProducts_id as product')
                ])->join('order_lists','orders.OrderList_id','=','order_lists.id')
                ->join('order_products','order_lists.id','=','order_products.OrderList_id')
                ->when($branchId, function ($query) use ($branchId) {
                    return $query->where('branch_id', $branchId);
                })
                ->groupBy('day')
                ->groupBy('product')
                ->orderBy('day', 'asc')
                ->get();
                return response()->json([$dailyData, 200]);
        }
        elseif($type == 'monthly'){
            $monthlyData = Order::select([
                DB::raw('DATE_FORMAT(orders.order_date, "%m") as month'),
                DB::raw('DATE_FORMAT(orders.order_date, "%Y") as year'),
                DB::raw('SUM(order_products.quantity) as all_quantity, BranchesProducts_id as product')
                ])->join('order_lists','orders.OrderList_id','=','order_lists.id')
                ->join('order_products','order_lists.id','=','order_products.OrderList_id')
                ->when($branchId, function ($query) use ($branchId) {
                    return $query->where('branch_id', $branchId);
                })
                ->groupBy('month', 'year')
                ->groupBy('product')
                ->orderBy('year','asc')
                ->orderBy('month','asc')
                ->get();
                return response()->json([$monthlyData, 200]);
        }
        elseif($type == 'yearly'){
            $yearlyData = Order::select([
                DB::raw('DATE_FORMAT(orders.order_date, "%Y") as year'),
                DB::raw('SUM(order_products.quantity) as all_quantity, BranchesProducts_id as product')
                ])->join('order_lists','orders.OrderList_id','=','order_lists.id')
                ->join('order_products','order_lists.id','=','order_products.OrderList_id')
                ->when($branchId, function ($query) use ($branchId) {
                    return $query->where('branch_id', $branchId);
                })
                ->groupBy('year')
                ->groupBy('product')
                ->orderBy('year','asc')
                ->get();
                return response()->json([$yearlyData, 200]);
        }
    }

    public function ordersIncomings($type, $branchId = null)
    {
        $manager = new Manager();
        $role = $manager->role();
        if($role == 1){
            $branchId = $manager->branch();
        }

        if($type == 'daily'){
            $dailyData = Order::select([
                DB::raw('DATE(orders.order_date) as day'),
                DB::raw('SUM(order_lists.order_earnings) as total_cost')
                ])->join('order_lists','orders.OrderList_id','=','order_lists.id')
                ->when($branchId, function ($query) use ($branchId) {
                    return $query->where('branch_id', $branchId);
                })
                ->groupBy('day')
                ->orderBy('day', 'asc')
                ->get();
                return response()->json([$dailyData, 200]);
        }
        elseif($type == 'monthly'){
            $monthlyData = Order::select([
                DB::raw('DATE_FORMAT(orders.order_date, "%m") as month'),
                DB::raw('DATE_FORMAT(orders.order_date, "%Y") as year'),
                DB::raw('SUM(order_lists.order_earnings) as total_cost')
                ])->join('order_lists','orders.OrderList_id','=','order_lists.id')
                ->when($branchId, function ($query) use ($branchId) {
                    return $query->where('branch_id', $branchId);
                })
                ->groupBy('month', 'year')
                ->orderBy('year','asc')
                ->orderBy('month','asc')
                ->get();
                return response()->json([$monthlyData, 200]);
        }
        elseif($type == 'yearly'){
            $yearlyData = Order::select([
                DB::raw('DATE_FORMAT(orders.order_date, "%Y") as year'),
                DB::raw('SUM(order_lists.order_earnings) as total_cost')
                ])->join('order_lists','orders.OrderList_id','=','order_lists.id')
                ->when($branchId, function ($query) use ($branchId) {
                    return $query->where('branch_id', $branchId);
                })
                ->groupBy('year')
                ->orderBy('year','asc')
                ->get();
                return response()->json([$yearlyData, 200]);
        }
    }

    public function earningsStatistics($branchId = null)
    {
        $manager = new Manager();
        $role = $manager->role();
        if($role == 1){
            $branchId = $manager->branch();
        }
        $TotalCosts = Cost::query()->when($branchId, function ($query) use ($branchId) {
            return $query->where('branch_id', $branchId);
        })
        ->selectRaw('MONTH(date), SUM(cost) as total_cost')
        ->groupBy('MONTH(date)')
        ->get();

        $totalEquipmentCosts = BranchesEquipment::query()->when($branchId, function ($query) use ($branchId) {
            return $query->where('branch_id', $branchId);
        })
        ->selectRaw('MONTH(date_in), SUM(cost) as total_cost')
        ->groupBy('MONTH(date_in)')
        ->get();
        
        $totalFixingCosts = EquipmentFix::query()->when($branchId, function ($query) use ($branchId) {
            return $query->whereHas('branches_equipments', function ($subQuery) use ($branchId) {
                $subQuery->where('branch_id', $branchId);
            });
        })->selectRaw('MONTH(fix_date), SUM(fixing_cost) as total_cost')
        ->groupBy('MONTH(fix_date)')
        ->get();

        $transactionCosts = InnerTransaction::query()->when($branchId, function ($query) use ($branchId) {
            return $query->where('DestinationBranch_id', $branchId);
        })
        ->selectRaw('MONTH(transaction_date), SUM(transaction_cost) as total_cost')
        ->groupBy('MONTH(transaction_date)')
        ->get();

        $totalCosts = $TotalCosts->sum('total_cost')
                        + $totalEquipmentCosts->sum('total_cost')
                        + $totalFixingCosts->sum('total_cost')
                        + $transactionCosts->sum('total_cost');
        
        $totalOrders = Order::query()
            ->when($branchId, function ($query) use ($branchId) {
                return $query->where('branch_id', $branchId);
            })->selectRaw(
            'MONTH(orders.order_date) as month,
            SUM(order_lists.order_earnings) as total_cost')
            ->join('order_lists','orders.OrderList_id','=','order_lists.id')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();
            
        $totalSalaries = Employee::query()->when($branchId, function ($query) use ($branchId) {
            return $query->where('branch_id', $branchId);
            })->sum('salary');
        
        $earningsByMonth = [];
        foreach ($totalOrders as $order) {
            $month = $order->month;
            $orderTotalCost = $order->total_cost;
            $earningsByMonth[$month] = $orderTotalCost - $totalSalaries - $totalCosts;
        }

        return response()->json([
            'branch'=> $branchId,
            'costs' => $totalCosts,
            'equipment costs'=>$totalEquipmentCosts,
            'Equipment fixing costs'=>$totalFixingCosts,
            'transactions costs'=>$transactionCosts,
            'total costs'=>$totalCosts,
            'orders'=>$totalOrders,
            'total salaries'=> $totalSalaries,
            'data'=>$earningsByMonth,
        ]);
    }

    public function BestCatQuantities($year, $month = null ,$branchId = null)
    {   
        $mostOrderedCategories = Category::withCount(['barnchesproducts as total_quantity' => function ($query) use ($year, $month) {
            $query->join('order_products', 'order_products.BranchesProducts_id', '=', 'branches_products.id')
                    ->join('order_lists', 'order_products.OrderList_id', '=', 'order_lists.id')
                    ->join('orders', 'order_lists.id', '=', 'orders.OrderList_id')
                    ->whereYear('orders.order_date', $year) 
                    ->when($month, function ($query) use ($month) {
                        return $query->whereMonth('orders.order_date', $month);
                    })
                ->select(DB::raw('SUM(order_products.quantity)'));
        }])
        ->orderBy('total_quantity', 'desc')
        ->get();
    return response()->json([$mostOrderedCategories, 200]);
    }

    public function BestCatEarnings($year, $month = null , $branchId = null)
    {   
        $mostEarningCategories = Category::withCount(['barnchesproducts as order_cost' => function ($query) use ($year, $month) {
                $query->join('order_products', 'branches_products.id', '=', 'order_products.BranchesProducts_id')
                    ->join('order_lists', 'order_products.OrderList_id', '=', 'order_lists.id')
                    ->join('orders', 'order_lists.id', '=', 'orders.OrderList_id')
                    ->whereYear('orders.order_date', $year) 
                    ->when($month, function ($query) use ($month) {
                        return $query->whereMonth('orders.order_date', $month);
                    })
                    ->select(DB::raw('SUM(order_lists.order_cost)'));
            }])
        ->orderBy('order_cost', 'desc')
        ->get();
        
        return response()->json([$mostEarningCategories, 200]);
    }

    public function BestBranch($year, $month = null)
    {   
        $mostActiveBranch = Branch::withSum(['orderLists' => function ($query)  use ($year, $month)    {
            $query->join('orders', 'orders.orderList_id', '=','order_lists.id')
                ->whereYear('orders.order_date', $year) 
                ->when($month, function ($query) use ($month) {
                    return $query->whereMonth('orders.order_date', $month);
                }); 
        }], 'order_quantity')
        ->orderBy('order_lists_sum_order_quantity', 'desc')
        ->first();
        return response()->json([$mostActiveBranch, 200]);


    }

    public function BestCustomer($branchId = null)
    {   
            $mostActiveCustomer = User::select(['users.*'])
            ->addSelect(DB::raw(
                '(SELECT SUM(order_lists.order_quantity) FROM order_lists WHERE order_lists.customer_id = users.id) as order_quantity_sum'
                ))
                ->when($branchId, function ($query, $branchId) {
                    return $query->where('branch_id', $branchId);
                })
                ->orderBy('order_quantity_sum', 'desc')
                ->first();
        return response()->json([$mostActiveCustomer, 200]);
    }
}
