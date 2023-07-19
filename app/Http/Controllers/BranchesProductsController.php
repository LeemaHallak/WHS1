<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchesProducts;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class BranchesProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
        return response()->json([ 
            'data'=>$products,
            'status code'=>200,
        ]);
    }

    public function BranchProducts($branch_id)                      // all roles
    {
        $products = BranchesProducts::where('branch_id',$branch_id)->with('products')->get();
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
    public function storeProduct(Request $request)
    {
        $this->authorize('add Branch_Product');
        $this->validate($request, [
            'product_id' => 'required',
            'branch_id'=>'required',
            'quantity'=>'required|integer',
            'price'=>'required',
            'prod_date'=>'required|date',
            'exp_date'=>'required|date',
            'date_in'=>'required|date',
            'purchase_num'=>'required|string',
            'buying_cost'=>'required',
        ]);
        $BranchProduct_data = $request->all();
        $BranchesProducts = BranchesProducts::query()->create($BranchProduct_data);

        return response()->json(['data'=>$BranchesProducts ,'status code'=> 201]);
    }

    /**
     * Display the specified resource.
     */
    public function show(BranchesProducts $branchesProducts)
    {
        //
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
            'quantity' => 'nullable|integer',
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

    public function RemoveBranchProduct($id)
    {
        $branchesProducts = BranchesProducts::query()->find($id)->delete();
        return http_response_code();
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BranchesProducts $branchesProducts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BranchesProducts $branchesProducts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BranchesProducts $branchesProducts)
    {
        //
    }
}
