<?php

namespace App\Http\Controllers;

use App\Models\BranchesProducts;
use App\Models\Category;
use App\Models\CategoryApprove;
use App\Models\Manager;
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
        $categoryId = $request->input('id');
        $categories = Category::find($categoryId);
        $parent = $categories->parent;
        $ParentsWithIcons = $parent->load('icons');
        return response()->json([
            'data'=>$ParentsWithIcons,
            'status code'=>200,
        ]);
    }

    public function ShowChildrenCategories($branchId, $categoryId)
    {
        $products = (new BranchesProductsController)->BranchesCatProducts($branchId, $categoryId);
        $categories = Category::find($categoryId);
        $children = $categories->children;
        if (!$products)
        {
            $category = Category::find($categoryId);
            $cats_products = $category->barnchesproducts()->get()->groupBy('branch_id');
            return response()->json([ 
                'data'=>$cats_products,
                'status code'=>200,
            ]);
        }
        $responseData = $children->isEmpty()
            ? ['message' => false, 'data' => $products]
            : ['message' => true, 'data' => $children->load('icons')];

        return response()->json($responseData, 200);

    }

    public function GeneralChildrenCategories($category_id)
    {
        $products = (new ProductController)->CatProducts($category_id);
        $categories = Category::find($category_id);
        $children = $categories->children;
        
        $responseData = $children->isEmpty()
            ? ['message' => false, 'data' => $products]
            : ['message' => true, 'data' => $children->load('icons')];

        return response()->json($responseData, 200);
    }

    public function ShowAllChildrenCategories(Request $request,$branch_id, $category_id)
    {
        $categories = Category::find($category_id);
        $children = $categories->allChildren;
        $childrenWithIcons = $children->load('icons');
        return response()->json([
            'data'=>$childrenWithIcons,
            'status code'=>200,
        ]);
    }

    public function ShowRootsCategories()
    {
        $categories = Category::where('parent_id', null)->withAggregate('icons', 'icon')->get();
        return response()->json([
            'data'=>$categories,
            'status code'=>200,
        ]);
    }
    
    public function AddCat(Request $request)
    {        
        $manager = new Manager();
        $Model = $manager->role() == 3
            ? Category::class : CategoryApprove::class;
        $Category = $Model::query()->create([
            'category_name'=> $request->input('category_name'),
            'parent_id'=>$request->input('parent_id'),
        ]);

        return response()->json([$Category,201]);
    }

    public function editCategory(Request $request, int $id): JsonResponse
    {
        $manager = new Manager();
        $Model = $manager->role() == 3
            ? Category::class : CategoryApprove::class;
        $category = $Model::find($id);
        if (!$category) {
            return response()->json([
                'error' => 'category not found'
            ], 400);
        }
        $validatedData = $request->validate([
            'category_name' => 'nullable',
            'parent_id' => 'nullable',
        ]);
        $category->fill($validatedData);
        $category->save();
        return response()->json([
            'message' => 'category updated successfully'
        ], 200);
    }
}
