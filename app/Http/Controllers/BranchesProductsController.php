<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchesProducts;
use App\Models\BranchesProductsAssis;
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
        if($branch_id){
            $products = BranchesProducts::where('branch_id',$branch_id)->with('products')->get()->groupBy('product_id');
        }
        elseif (!$branch_id){
            $products = BranchesProducts::with('products')->get()->groupBy('product_id');
        }
        
        if ($products->isEmpty()) {
            return response()->json([
                'message' => 'no products to show',
                'status code' => http_response_code(),
            ]);
        }
        return response()->json([
            'data' => $products,
            'status code' => http_response_code()

        ]);   
    }

    public function storeProduct(Request $request)
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
}
