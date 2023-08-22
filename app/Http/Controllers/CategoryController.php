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
    public function showAll()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function ShowParentsCategories(Request $request)
    {
        $id = $request->input('id');
        $categories = Category::find($id);
        $parent = $categories->parent;
        $ParentsWithIcons = $parent->load('icons');
        return response()->json([
            'data'=>$ParentsWithIcons,
            'status code'=>200,
        ]);
    }

    public function ShowChildrenCategories($branch_id, $category_id)
    {
        $products = (new BranchesProductsController)->BranchesCatProducts($branch_id, $category_id);
        $categories = Category::find($category_id);
        $children = $categories->children;
        if (!$products)
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
            $message = false;
            return response()->json([
                'massage'=> $message, 
                'data'=>$products,
                'status code'=>200,
            ]);
        }
        else if ($children->isNotEmpty()) {
            $message = true;
            $childrenWithIcons = $children->load('icons');
            return response()->json([
                'massage'=> $message, 
                'data'=>$children,
                'status code'=>200,
            ]);  
        }
    }

    public function GeneralChildrenCategories($category_id)
    {
        $products = (new ProductController)->CatProducts($category_id);
        $categories = Category::find($category_id);
        $children = $categories->children;
        if ($children->isEmpty())
        {
            $massage = false;
            return response()->json([
                'message'=> $massage, 
                'data'=>$products,
                'status code'=>200,
            ]);
        }
        else if ($children->isNotEmpty()) {
            $message = true;
            $childrenWithIcons = $children->load('icons');
        
            return response()->json([
                'message' => $message,
                'data' => $childrenWithIcons,
                'status code' => 200,
            ]);
        }
    }

    public function ShowAllChildrenCategories(Request $request,$branch_id, $category_id)
    {
        $categories = Category::find($category_id);
        $children = $categories->allChildren;
        $childrenWithIcons = $children->load('icons');
        return response()->json([
            'data'=>$children,
            'status code'=>200,
        ]);
    }

    public function ShowRootsCategories(Request $request)
    {
        $categories = Category::where('parent_id', null)->withAggregate('icons', 'icon')->get();
        return response()->json([
            'data'=>$categories,
            'status code'=>200,
        ]);
    }
    
    public function AddCat(Request $request)
    {        
        $Model = auth()->guard('manager-api')->user()->role_id == 3
            ? Category::class : CategoryAssis::class;
        $Category = $Model::query()->create([
            'category_name'=> $request->input('category_name'),
            'parent_id'=>$request->input('parent_id'),
        ]);

        return response()->json([$Category,201]);
    }

    public function editCategory(Request $request, int $id): JsonResponse
    {
        $Model = auth()->guard('manager-api')->user()->role_id == 3
            ? Category::class : CategoryAssis::class;
        $category = $Model::find($id);
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
}
