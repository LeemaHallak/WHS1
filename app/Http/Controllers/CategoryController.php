<?php

namespace App\Http\Controllers;

use App\Models\BranchesProducts;
use App\Models\Category;
use App\Models\CategoryAssis;
use App\Models\Product;
use Illuminate\Http\Request;
use Cjmellor\Approval\Models\Approval;
use Illuminate\Support\Js;

use Symfony\Component\HttpFoundation\JsonResponse;
use function PHPUnit\Framework\isEmpty;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function ShowParentsCategories(Request $request)
    {
        $id = $request->input('id');
        $categories = Category::find($id);
        $parent = $categories->parent;
        return response()->json([
            'data'=>$parent,
            'status code'=>200,
        ]);
    }

    public function ShowChildrenCategories($branch_id, $category_id)
    {
        $products = (new BranchesProductsController)->BranchesCatProducts($branch_id, $category_id);
        $categories = Category::find($category_id);
        $children = $categories->children;
        if ($products->isEmpty())
        {
            $category = Category::find($category_id);
            $cats_products = $category->barnchesproducts()->get()->groupBy('branch_id');
            return response()->json([ 
                'data'=>$cats_products,
                'status code'=>200,
            ]);
        }
        if ($children->isEmpty())
        {
            $massage = false;
            return response()->json([
                'massage'=> $massage, 
                'data'=>$products,
                'status code'=>200,
            ]);
        }
        else{
            return response()->json('empty');   
        }
    }

    public function ShowAllChildrenCategories(Request $request,$branch_id, $category_id)
    {
        $id = $request->input('id');
        $categories = Category::find($category_id);
        $children = $categories->allChildren;
        return response()->json([
            'data'=>$children,
            'status code'=>200,
        ]);
    }

    public function ShowRootsCategories(Request $request)
    {
        $categories = Category::where('parent_id', null)->get();
        return response()->json([
            'data'=>$categories,
            'status code'=>200,
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
    public function approveAddCat(Request $request)
    {
        $Category = CategoryAssis::query()->create([
            'category_name'=> $request->input('category_name'),
            'parent_id'=>$request->input('parent_id'),
        ]);
        return response()->json([$Category,201]);
    }
    
    public function AddCat(Request $request)
    {
        $Category = Category::query()->create([
            'category_name'=> $request->input('category_name'),
            'parent_id'=>$request->input('parent_id'),
        ]);

        // $Category->withoutApproval();

        return response()->json([$Category,201]);
    }

    /**
     * Display the specified resource.
     */
    public function show(category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editCategory(Request $request, int $id): JsonResponse
    {
        $category = Category::find($id);
    
        if (!$category) {
            return response()->json([
                'error' => 'category not found'
            ], 404);
        }
    
        $validatedData = $request->validate([
            'category_name' => 'nullable',
            'parent_id' => 'nullable',
        ]);
    
        $category->fill($validatedData);
        $category->save();
    
        return response()->json([
            'message' => 'category updated successfully'
        ]);
    }
    public function Assistant_editCategory(Request $request, int $id): JsonResponse
    {
        $category = CategoryAssis::find($id);
    
        if (!$category) {
            return response()->json([
                'error' => 'category not found'
            ], 404);
        }
    
        $validatedData = $request->validate([
            'category_name' => 'nullable',
            'parent_id' => 'nullable',
        ]);
    
        $category->fill($validatedData);
        $category->save();
    
        return response()->json([
            'message' => 'waiting for the keeper aprove...'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(category $category)
    {
        //
    }
}
