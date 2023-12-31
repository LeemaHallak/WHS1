<?php

namespace App\Http\Controllers;

use App\Models\BranchesProducts;
use App\Models\OrderList;
use App\Models\OrderProducts;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class OrderProductsController extends Controller
{

    public function store(Request $request)
    {
        $orderList = $request->orderList;
        $BranchProduct_id = $request->BranchProduct_id;
        $quantity = $request->quantity;

        $Product = BranchesProducts::find($BranchProduct_id);
        $OrderList = OrderList::find($orderList);

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
                'please choose a product from the same branch, or start a new product',
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function createOrder($Product, $OrderList, $quantity, $BranchProduct_id, $orderList)
    {
        if (auth()->guard('manager-api'))
            $role = auth()->guard('manager-api')->user()->role_id;
        $ProductQuantity = $Product->value('recent_quantity');
        $IfOrdered = $OrderList->value('orderd');
        if($IfOrdered == 0 && $ProductQuantity >= $quantity )
        {
            $ProductPrice = $Product->value('price');
            $ProductEarning = $Product->value('buying_cost') - $ProductPrice;
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

            $orderEarning = $OrderList->value('order_earnings');
            $newOrderEarnings = $orderEarning + $ProductEarning;
            $updateEarnings = $OrderList->update([
                    'order_earnings'=>$newOrderEarnings
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
                        'recent_quantity'=>$newProductQuantity
                    ]); 
                $New_ProductQuantity = $Product->value('recent_quantity');
            }
        
            return response()->json([
                'products'=>$OrderProducts,
                'order cost'=>$New_OrderCost, 
                'product quantity'=>$New_ProductQuantity, 
                'order quantity'=>$New_OrderQuantity,
            ], Response::HTTP_CREATED); 
            
        }
        else
        {
            return response()->json([
                'message'=>'you can not add products to this order'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function RemoveOrderProduct($id)
    {
        OrderProducts::query()->find($id)->delete();
        return Response::HTTP_OK;
    }

    public function showOrderProducts($OrderListId)
    {
        $products = OrderProducts::join('branches_products', 'order_products.BranchesProducts_id', '=', 'branches_products.id')
            ->join('products', 'branches_products.product_id', '=', 'products.id')
            ->where('OrderList_id', $OrderListId)
            ->select('order_products.quantity', 'order_products.total_price', 'branches_products.price', 'products.product_name')
            ->get();

            return $products->isNotEmpty()
            ? response()->json($products, Response::HTTP_OK)
            : response()->json([
                'message' => 'no products to show'
            ], Response::HTTP_NO_CONTENT);      
    }
}
