<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\order;
use App\Models\OrderList;
use App\Models\Shipment;
use Illuminate\Http\Request;

class OrderController extends Controller
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
    public function order(Request $request)
    {
        $this->authorize('add Order');

        $OrderList_id = $request->OrderList_id;
        $shipment_id = $request->shipment_id;
        $order_date = $request->date;

        $OrderList = OrderList::where('id', $OrderList_id);
        $shipment = Shipment::where('id', $shipment_id);

        $order = order::query()->create([
            'OrderList_id'=>$OrderList_id,
            'shipment_id'=>$shipment_id,
            'order_date'=>$order_date,
            'ready'=>0,
            'arrived'=>0,
        ]);

        $OrderQuantity = $OrderList->value('order_quantity');
        $shipmentQuantity = $shipment->value('shipment_quantity');
        $max_quantity = $shipment->value('max_quantity');
        $totalQuantity = $OrderQuantity + $shipmentQuantity;
        if($totalQuantity <= $max_quantity)
        {
            $updatingQuantity = $shipment->update(['shipment_quantity'=>$totalQuantity]);
            $New_shipmentQuantity = $shipment->first('shipment_quantity');

            $OrderCost =$OrderList->value('order_cost');
            $shipment_cost = $shipment ->value('shipment_cost');
            $newShipmentCost = $OrderCost + $shipment_cost;
            $ShipCoUpdate = $shipment->update(['shipment_cost'=>$newShipmentCost]);
            $New_shipmentCost = $shipment->first('shipment_cost');

            return [
                $order,
                $New_shipmentCost,
                $New_shipmentQuantity
            ];
        } 
        else
        {
            return response()->json([
                'message'=>'please change the shipment',
                'status code'=> 400
            ]);
        }
        
    }

    public function ShowOrders(order $order)
    {
        $manager = auth()->guard('manager-api')->user();
        $employee = $manager->employee;
        $branch_id = $employee->branch_id;
        $branch = Branch::find($branch_id);
        $order = $branch->orders()->with('OrderList')->get();
        return response()->json([
            'orders'=>$order,
            'stauts code'=>http_response_code(),
        ]);
    }

    public function ShowShipmentOrders(order $order, $shipment_id)
    {
        $manager = auth()->guard('manager-api')->user();
        $employee = $manager->employee;
        $branch_id = $employee->branch_id;
        $branch = Branch::find($branch_id);
        $order = $branch->orders()->with('OrderList')->where('shipment_id', $shipment_id)->get();
        return response()->json([
            'orders'=>$order,
            'stauts code'=>http_response_code(),
        ]);
    }

    public function OrderReady($id, $ready){
        $order = Order::find($id);
        $isReady = $order->ready;
        if ($isReady != $ready){
            $updated = $order->update([
                'ready' => $ready
            ]);
        }
    }

    public function OrderArrived($id, $arrived){
        $order = Order::find($id);
        $isArrived = $order->arrived;
        if ($isArrived != $arrived){
            $updated = $order->update([
                'arrived' => $arrived
            ]);
        }
    }

    public function RemoveOrder($id)
    {
        $order = Order::query()->find($id)->delete();
        return http_response_code();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(order $order)
    {
        //
    }
}
