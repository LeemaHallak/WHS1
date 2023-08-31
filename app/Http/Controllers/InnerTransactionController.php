<?php

namespace App\Http\Controllers;

use App\Models\InnerTransaction;
use App\Http\Requests\StoreInnerTransictionRequest;
use App\Http\Requests\UpdateInnerTransictionRequest;
use App\Models\BranchesProducts;
use App\Models\Manager;
use App\Models\OrderProducts;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InnerTransactionController extends Controller
{
    public function addInnerTransaction(Request $request)
    {
        $manager = new Manager();
        $role = $manager->role();
        $BranchProduct_id = $request->BranchProduct_id;
        $Product = BranchesProducts::find( $BranchProduct_id);
        $productBranch = $Product->branch_id;
        if($role == 1){
            $SourceBranch_id = $manager->branch();
        }
        else if($role == 3){
            $SourceBranch_id = $productBranch;
        }
        $quantity = $request->quantity;
        if($quantity<=0){
            return response()->json([
                'message' => 'please change the quantity'],
                Response::HTTP_BAD_REQUEST);
        }
        $ProductQuantity = $Product->value('recent_quantity');
        $DestinationBranch_id = $request->DestinationBranch_id;
        if(
            $ProductQuantity >= $quantity 
            &&
            $SourceBranch_id == $productBranch)
        {
            $innerTransaction = InnerTransaction::query()->create([
                'BranchProduct_id' => $BranchProduct_id,
                'SourceBranch_id'=> $SourceBranch_id,
                'DestinationBranch_id'=>$DestinationBranch_id,
                'quantity'=>$quantity,
                'transaction_date'=>$request->date,
                'transaction_cost'=>$request->cost,
            ]);
        }

        $newProductQuantity = $ProductQuantity - $quantity;
        $Product->update([
            'recent_quantity'=>$newProductQuantity
            ]); 
        $New_ProductQuantity = $Product->value('recent_quantity');

        $newBranchProduct = BranchesProducts::query()->create([
            'product_id' => $Product->product_id,
            'branch_id'=>$DestinationBranch_id,
            'Supplier_id'=>$Product->Supplier_id,
            'in_quantity'=>$quantity,
            'recent_quantity'=>$quantity,
            'price'=>$Product->price,
            'prod_date'=>$Product->prod_date,
            'exp_date'=>$Product->exp_date,
            'date_in'=>$Product->date_in,
            'purchase_num'=>$Product->purchase_num,
            'buying_cost'=>$Product->buying_cost,
        ]);

        return response()->json([
            'inner transaction data'=>$innerTransaction,
            'new branch product data'=>$newBranchProduct
        ], Response::HTTP_CREATED);
    }

    public function showInnerTransaction($sourceBranchId = null) 
    {
        $manager = new Manager();
        $role = $manager->role();
        if($role == 1){
            $branchId = $manager->branch();
        }
        else if($role == 3){
            $branchId = $sourceBranchId;
        }

        $out = InnerTransaction::when($branchId, function($query) use ($branchId){
            $query->where('SourceBranch_id', $branchId);
            })->get();
        $in = InnerTransaction::when($branchId, function($query) use ($branchId){
            $query->where('DestinationBranch_id', $branchId);
            })->get();

        return response()->json([
            'out transaction' => $out,
            'in transactions'=> $in
        ], Response::HTTP_OK);
    }
}
