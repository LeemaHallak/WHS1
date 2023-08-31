<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use App\Models\Order;
use App\Models\OrderList;
use App\Models\OrderProducts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class OrderListController extends Controller
{
    public function StartOrder(Request $request)
    {
        $manager = new Manager();
        $role = $manager->role();
        $customer_id = ($role == 1) ? $request->customer : Auth::id();
        $OrderList = OrderList::query()->create([
            'customer_id' => $customer_id,
            'branch_id'=>null,
            'order_quantity'=>0,
            'order_cost' => 0.0,
            'order_earnings'=>0.0,
            'orderd'=> 0,
        ]);
        return response()->json([
            'order list' => $OrderList,
        ], Response::HTTP_CREATED);
    }

    public function ordering($orderlistId)
    {
        $updtaing = OrderList::query()->find($orderlistId)->update(['ordered'=> 1]);
        return response()->json(['data'=>$updtaing], Response::HTTP_OK);
    }

    public function showOrderLists($shipmentId = null)
    {
        $ordersQuery = Order::with('OrderList.customers');
        $orders = $shipmentId ? $ordersQuery->where('shipment_id', $shipmentId)->get() : $ordersQuery->get();
        
        return $orders->isEmpty()
            ? response()->json(['message' => 'No orders found.'], Response::HTTP_NO_CONTENT)
            : response()->json([
                'message' => 'no orders to show'
            ], Response::HTTP_OK);        
    }

}
