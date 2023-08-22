<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderList;
use App\Models\OrderProducts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderListController extends Controller
{
    public function StartOrder(Request $request)
    {
        $role = auth()->guard('manager-api')->user()->role_id;
        $customer = $request->customer;
        $customer_id = ($role == 1) ? $customer : Auth::id();
        $OrderList = OrderList::query()->create([
            'customer_id' => $customer_id,
            'branch_id'=>null,
            'order_quantity'=>0,
            'order_cost' => 0.0,
            'order_earnings'=>0.0,
            'orderd'=> 0,
        ]);
        return [
            'order list' => $OrderList,
        ];
    }

    public function ordering($orderlistId)
    {
        $updtaing = OrderList::query()->find($orderlistId)->update(['ordered'=> 1]);
        return response()->json(['data'=>$updtaing,'status code'=>200]);
    }

    public function showOrderLists($id = null)
    {
        $orders = Order::with('OrderList.customers');
        if(!$id){
            $orders = $orders->get();
            return response()->json(
                $orders
                ,200);
        }
        $orders = $orders->where('shipment_id',$id)->get();
        if ($orders->isNotEmpty()) {
            return response()->json(
                $orders
            ,200);
        } else {
            return response()->json([
                'message' => 'no orders to show'
            ]);
        }
            
    }

}
