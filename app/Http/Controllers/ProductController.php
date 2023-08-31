<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use App\Models\Product;
use App\Models\ProductApprove;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{

    public function CatProducts($categoryId)
    {
        $products = Product::where('Category_id',$categoryId)->get();
        if ($products->isEmpty()) {
            return response()->json([
                'message' => 'no products to show'
            ], Response::HTTP_NO_CONTENT);
        }
        return $products;   
    }

    public function AllProducts()
    {
        $products = Product::get();
        return $products->isEmpty()
            ? response()->json(['message' => 'No products found.'], Response::HTTP_NO_CONTENT)
            : response()->json(['data' => $products], Response::HTTP_OK);      
    }

    public function showProductDetails($productId)
    {
        $productDetails = Product::where('id',$productId)->first();
        return response()->json([
            'data' => $productDetails
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $this-> validate($request, [
            'UPC_code'=> ' required | integer',
            'product_code'=> ' required | integer',
        ]);
        $manager = new Manager();
        $Model = $manager->role == 3
            ? Product::class : ProductApprove::class;
        $image_url = '/storage/' . $request->file('image')->store('products', 'public');
        $product = $Model::query()->create([
            'product_name'=> $request->product_name,
            'description'=> $request->description,
            'ProducingCompany_id'=> $request->Producing_Company_id,
            'Supplier_id'=>$request->supplier_id,
            'UPC_code'=>$request->UPC_code,
            'product_code'=>$request->product_code,
            'Category_id'=>$request->Category_id,
            'weight'=>$request->weight,
            'WUnit'=>$request->WUnit,
            'size'=>$request->size,
            'SUnit'=>$request->SUnit,
            'box_quantity'=>$request->box_quantity,
            'image'=>$image_url,
        ]);
        return response()->json([$product,201]);
    }
}
