<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchesProducts;
use App\Models\BranchesProductsAssis;
use App\Models\OrderList;
use App\Models\OrderProducts;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class BranchesProductsController extends Controller
{
    
    public function findBranchesCatProducts($branch_id, $category_id)   // all roles
    {
        $branch = BranchesProducts::where('branch_id', $branch_id);
        $show = $branch->withWhereHas('Products', fn($query) =>
        $query->where('Category_id', $category_id)
        )->get();
        return $show;
    }

    public function BranchesCatProducts($branch_id, $category_id)   // all roles
    {
        $products = (new BranchesProductsController)->findBranchesCatProducts($branch_id, $category_id);
        return $products;
    }

    public function BranchProducts($branch_id = null)                      // all roles
    {
        $products = Product::whereHas('BranchProducts', function ($query) use ($branch_id) {
            $query->when($branch_id, function($q) use ($branch_id){
                $q->where('branch_id', $branch_id);
            });
        })
        ->with('BranchProducts')
        ->get();
        return response()->json($products, http_response_code());
    }

    public function BranchProductDetails($product_id)                    
    {
        $product = BranchesProducts::with(['Products', 'Products.producing_companies'])->find($product_id);
        $systemProduct = $product->product_id;
        $branch = $product->branch_id;
        $details = $product;
        $sumQuantity = BranchesProducts::where('product_id', $systemProduct)->where('branch_id', $branch)->sum('recent_quantity');
        
        if (!$details) {
            return response()->json([
                'message' => 'no products to show',
                'status code' => http_response_code(),
            ]);
        }
        return response()->json([
            'Details' => $details,
            'quanty' =>$sumQuantity,
            'status code' => http_response_code()
        ]);   
    }

    public function storeProduct(Request $request)
    {
        $this->authorize('add Branch_Product');
        $this->validate($request, [
            'product_id' => 'required',
            'branch_id'=>'required',
            'Supplier_id'=> 'required',
            'in_quantity'=>'required|integer',
            'price'=>'required',
            'prod_date'=>'required|date',
            'exp_date'=>'required|date',
            'date_in'=>'required|date',
            'purchase_num'=>'required|string',
            'buying_cost'=>'required',
        ]);
        $in_quantity = $request->in_quantity;
        $BranchProduct_data = $request->all();
        $BranchesProducts = BranchesProducts::query()->create([
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

        

        return response()->json(['data'=>$BranchesProducts ,'status code'=> 201]);
    }

    public function Assistant_storeProduct(Request $request)
    {
        $this->authorize('add Branch_Product');
        $this->validate($request, [
            'product_id' => 'required',
            'branch_id'=>'required',
            'supplier_id'=> 'required',
            'in_quantity'=>'required|integer',
            'price'=>'required',
            'prod_date'=>'required|date',
            'exp_date'=>'required|date',
            'date_in'=>'required|date',
            'purchase_num'=>'required|string',
            'buying_cost'=>'required',
        ]);
        $BranchProduct_data = $request->all();
        $BranchesProducts = BranchesProducts::query()->create([$BranchProduct_data, 'recent_quantity'=>$BranchProduct_data['in_quantity']]);

        return response()->json(['data'=>$BranchesProducts ,'status code'=> 201]);
    }

    public function editProduct(Request $request, int $id): JsonResponse
    {
        $product = BranchesProducts::find($id);
    
        if (!$product) {
            return response()->json([
                'error' => 'product not found'
            ], 404);
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
            'purchase_num'=>'nullable|string',
            'buying_cost'=>'nullable', 
        ]);
    
        $product->fill($validatedData);
        $product->save();
    
        return response()->json([
            'message' => 'product updated successfully'
        ]);
    }

    public function Assistant_editProduct(Request $request, int $id): JsonResponse
    {
        $product = BranchesProductsAssis::find($id);
    
        if (!$product) {
            return response()->json([
                'error' => 'product not found'
            ], 404);
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
            'purchase_num'=>'nullable|string',
            'buying_cost'=>'nullable', 
        ]);
    
        $product->fill($validatedData);
        $product->save();
    
        return response()->json([
            'message' => 'waiting for the keeper aprove...'
        ]);
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
        
        return response()->json($productTransation);
    }
}
