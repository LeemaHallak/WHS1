<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductAssis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function CatProducts($category_id)
    {
        $products = Product::where('Category_id',$category_id)->get();
        if ($products->isEmpty()) {
            return response()->json([
                'message' => 'no products to show',
                'status code' => 204,
            ]);
        }
        return response()->json([
            'data' => $products,
            'status code' => 200

        ]);   
    }

    public function AllProducts()
    {
        $products = Product::get();
        if ($products->isEmpty()) {
            return response()->json([
                'message' => 'no products to show',
                'status code' => 204,
            ]);
        }
        return response()->json([
            'data' => $products,
            'status code' => 200

        ]);   
    }
    public function showProductDetails($id)
    {
        $productDetails = Product::where('id',$id)->first();
        return response()->json([
            'data' => $productDetails,
            'status' => http_response_code()
        ]);
    }

    public function RemoveProduct($id)
    {
        $product = Product::query()->find($id)->delete();
        return http_response_code();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $image_name_to_store = 'default.png';
        if ($request->hasFile('image'))
        {
            $imagenameWithExt=$request->file('image')->getClientOriginalName();
            $imagename=pathinfo($imagenameWithExt,PATHINFO_FILENAME);
            $extension=$request->file('image')->getClientOriginalExtension();
            $image_name_to_store=$imagename . '_' . time() . '.' . $extension;
            $request->file('image')->storeAs('public/images',$image_name_to_store);
        }

        $product = Product::query()->create([
            'product_name'=> $request->input('product_name'),
            'InnerCategory_id'=> $request->input('Inner_Category_id'),
            'description'=> $request->input('description'),
            'ProducingCompany_id'=> $request->input('Producing_Company_id'),
            'Supplier_id'=>$request->input('supplier_id'),
            'UPC_code'=>$request->input('UPC_code'),
            'product_code'=>$request->input('product_code'),
            'Category_id'=>$request->input('Category_id'),
            'weight'=>$request->input('weight'),
            'WUnit_id'=>$request->input('WUnit_id'),
            'size'=>$request->input('size'),
            'SUnit_id'=>$request->input('SUnit_id'),
            'box_quantity'=>$request->input('box_quantity'),
            'image'=>$image_name_to_store,
        ]);
        return response()->json([$product,201]);
    }

    public function storeAss(Request $request)
    {
        
        $image_name_to_store = 'default.png';
        if ($request->hasFile('image'))
        {
            $imagenameWithExt=$request->file('image')->getClientOriginalName();
            $imagename=pathinfo($imagenameWithExt,PATHINFO_FILENAME);
            $extension=$request->file('image')->getClientOriginalExtension();
            $image_name_to_store=$imagename . '_' . time() . '.' . $extension;
            $request->file('image')->storeAs('public/images',$image_name_to_store);
        }

        $product = ProductAssis::query()->create([
            'product_name'=> $request->input('product_name'),
            'InnerCategory_id'=> $request->input('Inner_Category_id'),
            'description'=> $request->input('description'),
            'ProducingCompany_id'=> $request->input('Producing_Company_id'),
            'Supplier_id'=>$request->input('supplier_id'),
            'UPC_code'=>$request->input('UPC_code'),
            'product_code'=>$request->input('product_code'),
            'Category_id'=>$request->input('Category_id'),
            'weight'=>$request->input('weight'),
            'WUnit_id'=>$request->input('WUnit_id'),
            'size'=>$request->input('size'),
            'SUnit_id'=>$request->input('SUnit_id'),
            'box_quantity'=>$request->input('box_quantity'),
            'image'=>$image_name_to_store,
        ]);
        return response()->json([$product,201]);
    }
    /**
     * Display the specified resource.
     */
    public function show(product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(product $product)
    {
        //
    }
}
