<?php

namespace App\Http\Controllers;

use App\Models\BranchesProducts;
use App\Models\OrderList;
use App\Models\OrderProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderProductsController extends Controller
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
    public function store(Request $request)
    {
        $this->authorize('add Order_Product');
        
        $orderList = $request->orderList;
        $BranchProduct_id = $request->BranchProduct_id;
        $quantity = $request->quantity;

        $Product = BranchesProducts::where('id', $BranchProduct_id);
        $OrderList = OrderList::where('id',$orderList);

        $branch = $Product->value('branch_id');
        $orderBranch = $OrderList->value('branch_id');

        if(!($orderBranch)){
            $updated = $OrderList->update([
            'branch_id'=>$branch,
            ]);
            $order = (new OrderProductsController)->createOrder($Product, $OrderList, $quantity, $BranchProduct_id, $orderList);
            return $order;
        }
        else if ($orderBranch == $branch){
            $order = (new OrderProductsController)->createOrder($Product, $OrderList, $quantity, $BranchProduct_id, $orderList);
            return $order;
        }
        else{
            return response()->json(
                'please choose a product from the same branch, or start a new product'
            );
        }
    }

    public function createOrder($Product, $OrderList, $quantity, $BranchProduct_id, $orderList)
    {
        $role = auth()->guard('manager-api')->user()->role_id;
        $ProductQuantity = $Product->value('quantity');
        $IfOrdered = $OrderList->value('orderd');
        if($IfOrdered == 0 && $ProductQuantity >= $quantity )
        {
            $ProductPrice = $Product->value('price');
            $TotalPrice = $ProductPrice*$quantity;

            $OrderProducts = OrderProducts::query()->create([
                'BranchesProducts_id' => $BranchProduct_id,
                'quantity'=> $quantity,
                'total_price'=>$TotalPrice,
                'OrderList_id'=>$orderList,
            ]);

            $order_cost = $OrderList->value('order_cost');
            $newOrderCost = $order_cost + $TotalPrice;
            $updateCost = $OrderList->update([
                    'order_cost'=>$newOrderCost
                ]); 
            $New_OrderCost = $OrderList->value('order_cost');

            $OrderQuantity = $OrderList->value('order_quantity');
            $TotalQuantity = $OrderQuantity + $quantity;
            $updateOrderQuantity = $OrderList->update(['order_quantity'=>$TotalQuantity]);
            $New_OrderQuantity = $OrderList->value('order_quantity');
            
            $New_ProductQuantity = 0;
            if ($role == 1){
                $IfOrdered = 1;
                $newProductQuantity = $ProductQuantity - $quantity;
                $updatequantity = $Product->update([
                        'quantity'=>$newProductQuantity
                    ]); 
                $New_ProductQuantity = $Product->value('quantity');
            }
        
            return response()->json([
                'products'=>$OrderProducts,
                'order cost'=>$New_OrderCost, 
                'product quantity'=>$New_ProductQuantity, 
                'order quantity'=>$New_OrderQuantity,
            ]); 
            
        }
        else
        {
            return response()->json([
                'message'=>'you can not add products to this order',
                'status cade' => 400,
            ]);
        }
    }

    public function RemoveOrderProduct($id)
    {
        OrderProducts::query()->find($id)->delete();
        return http_response_code();
    }

    /**
     * Display the specified resource.
     */

public function showOrderProducts($Order_list_id)
{
    $products = OrderProducts::join('branches_products', 'order_products.BranchesProducts_id', '=', 'branches_products.id')
        ->join('products', 'branches_products.product_id', '=', 'products.id')
        ->where('OrderList_id', $Order_list_id)
        ->select('order_products.quantity', 'order_products.total_price', 'branches_products.price', 'products.product_name')
        ->get();

    if ($products->isNotEmpty()) {
        return response()->json($products, 200);
    } 
    else {
        return response()->json(['message' => 'no products to show']);
    }
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderProducts $orderProducts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderProducts $orderProducts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderProducts $orderProducts)
    {
        //
    }
}
