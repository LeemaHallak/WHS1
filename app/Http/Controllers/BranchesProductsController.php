<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchesProducts;
use App\Models\BranchesProductsApprove;
use App\Models\Manager;
use App\Models\OrderList;
use App\Models\OrderProducts;
use App\Models\Product;
use App\Models\ProductApprove;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class BranchesProductsController extends Controller
{
    
    public function findBranchesCatProducts($branchId, $categoryId)
    {
        $branch = BranchesProducts::where('branch_id', $branchId);
        $show = $branch->withWhereHas('Products', fn($query) =>
        $query->where('Category_id', $categoryId)
        )->get();
        return $show;
    }

    public function BranchesCatProducts($branchId, $categoryId)
    {
        $products = $this->findBranchesCatProducts($branchId, $categoryId);
        return $products;
    }

    public function BranchProducts($branchId = null)                      
    {
        $products = Product::whereHas('BranchProducts', function ($query) use ($branchId) {
            $query->when($branchId, function($q) use ($branchId){
                $q->where('branch_id', $branchId);
            });
        })
        ->with('BranchProducts')
        ->get();
        return response()->json($products, Response::HTTP_OK);
    }

    public function BranchProductDetails($productId)                    
    {
        $product = BranchesProducts::with(['Products', 'Products.producing_companies'])->find($productId);
        $systemProduct = $product->product_id;
        $branch = $product->branch_id;
        $details = $product;
        $sumQuantity = BranchesProducts::where('product_id', $systemProduct)->where('branch_id', $branch)->sum('recent_quantity');
        
        if (!$details) {
            return response()->json([
                'message' => 'no products to show'
            ], Response::HTTP_NO_CONTENT);
        }
        return response()->json([
            'Details' => $details,
            'quanty' =>$sumQuantity
        ], Response::HTTP_OK);   
    }

    public function storeProduct(Request $request)
    {
        $this-> validate($request, [
            'purchase_num'=>'required|integer',
        ]);
        $manager = new Manager();
        $in_quantity = $request->in_quantity;
        $Model = $manager->role() == 1 
            ? BranchesProducts::class : BranchesProductsApprove::class;
        $BranchesProducts = $Model::query()->create([
            'product_id' => $request->product_id,
            'branch_id'=> $request->branch_id,
            'Supplier_id'=> $request->Supplier_id,
            'in_quantity'=>$in_quantity,
            'recent_quantity'=>$in_quantity,
            'price'=>$request->price,
            'prod_date'=>$request->prod_date,
            'exp_date'=>$request->exp_date,
            'date_in'=>$request->date_in,
            'purchase_num'=>$request->purchase_num,
            'buying_cost'=>$request->buying_cost,
        ]);
        return response()->json(['data'=>$BranchesProducts ], Response::HTTP_CREATED);
    }

    public function editProduct(Request $request, int $productId): JsonResponse
    {
        $manager = new Manager();
        $Model = $manager->role() == 1 
                ? BranchesProducts::class : BranchesProductsApprove::class;
        $product = $Model::find($productId);
        if (!$product) {
            return response()->json([
                'error' => 'product not found'
            ], 400);
        }
        $validatedData = $request->validate([
            'product_id' => 'nullable',
            'branch_id' => 'nullable',
            'supplier_id'=> 'required',
            'in_quantity' => 'nullable|integer',
            'price' => 'nullable',
            'prod_date' => 'nullable|date',
            'exp_date' => 'nullable|date',
            'date_in'=>'nullable|date', 
            'purchase_num'=>'nullable|integer',
            'buying_cost'=>'nullable', 
        ]);
        $product->fill($validatedData);
        $product->save();
        return response()->json([
            'message' => 'product updated successfully'
        ], 200);
    }

    public function ProductTransition($productId)
    {
        $product = BranchesProducts::find($productId);
        $systemProduct = $product->product_id;
        $branch = $product->branch_id;
        $inProduct = BranchesProducts::where('product_id', $systemProduct)->where('branch_id', $branch)
            ->selectRaw('"in" as state, date_in, in_quantity, in_quantity * buying_cost as total_cost')
            ->get();
        $outProduct = OrderProducts::join('order_lists', 'order_lists.id', '=', 'order_products.OrderList_id')
            ->join('orders', 'orders.OrderList_id', '=', 'order_lists.id')
            ->where('order_products.BranchesProducts_id', $productId)
            ->selectRaw('"out" as state, orders.order_date, order_products.quantity, order_products.total_price')
            ->get();
        $productTransation = $inProduct->concat($outProduct);
        
        return response()->json($productTransation, 200);
    }
}
