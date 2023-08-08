<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\order;
use App\Models\OrderList;
use App\Models\Shipment;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function order(Request $request)
    {
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

    public function ShowOrders($branchId =null)
    {
        $manager = auth()->guard('manager-api')->user();
        $role = $manager->role_id;
        if($role == 1){
            $employee = $manager->employee;
            $branchId = $employee->branch_id;
        }
        $branch = Branch::find($branchId);
        $order = $branch->orders()->with('OrderList')->get();
        return response()->json([
            'orders'=>$order,
            'stauts code'=>http_response_code(),
        ]);
    }

    public function ShowShipmentOrders($shipmentId, $branchId = null)
    {
        $manager = auth()->guard('manager-api')->user();
        $role = $manager->role_id;
        if($role == 1){
            $employee = $manager->employee;
            $branchId = $employee->branch_id;
        }
        if(!$branchId){
            $orders = Order::with('OrderList')->where('shipment_id', $shipmentId)->get();
            return response()->json([
                'orders'=>$orders,
                'stauts code'=>http_response_code(),
            ]);
        }
        $branch = Branch::find($branchId);
        $order = $branch->orders()->with('OrderList')->where('shipment_id', $shipmentId)->get();
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


}
