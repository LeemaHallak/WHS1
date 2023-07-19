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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function StartOrder(Request $request)
    {
        $this->authorize('add Order_List');
        
        $role = auth()->guard('manager-api')->user()->role_id;
        $customer = $request->customer;
        if($role == 1){
            $customer_id = $customer;
        }
        else {
            $customer_id = Auth::id();
        }
        $OrderList = OrderList::query()->create([
            'customer_id' => $customer_id,
            'branch_id'=>null,
            'order_quantity'=>0,
            'order_cost' => 0.0,
            'orderd'=> 0,
        ]);
        return $OrderList;
    }

    public function ordering(Request $request)
    {
        $OrderList_id = $request->OrderList_id;
        $ordered = $request->ordered;
        $updtaing = OrderList::query()->find($OrderList_id)->update(['ordered'=> $ordered]);
        return response()->json(['data'=>$updtaing,'status code'=>200]);
    }

    /**
     * Display the specified resource.
     */
    public function showOrderLists($id)
    {
        $orders = Order::with('OrderList.customers')->where('shipment_id',$id)->get();
        
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderList $orderList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderList $orderList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderList $orderList)
    {
        //
    }
}
