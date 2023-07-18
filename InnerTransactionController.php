<?php

namespace App\Http\Controllers;

use App\Models\InnerTransaction;
use App\Http\Requests\StoreInnerTransictionRequest;
use App\Http\Requests\UpdateInnerTransictionRequest;
use App\Models\BranchesProducts;
use App\Models\OrderProducts;
use Illuminate\Http\Request;

class InnerTransactionController extends Controller
{

    public function addInnerTransaction(Request $request)
    {
        $manager = auth()->guard('manager-api')->user();
        $employee = $manager->employee;
        $SourceBranch_id = $employee->branch_id;

        $quantity = $request->quantity;
        $BranchProduct_id = $request->BranchProduct_id;
        $Product = BranchesProducts::where('id', $BranchProduct_id);
        $ProductQuantity = $Product->value('quantity');
        $productBranch = $Product->branch_id;

        $DestinationBranch_id = $request->DestinationBranch_id;

        if(
            $ProductQuantity >= $quantity 
            &&
            $SourceBranch_id == $productBranch)
        {
            $innerTransaction = InnerTransaction::query()->create([
                'BranchesProducts_id' => $BranchProduct_id,
                'SourceBranch_id'=> $SourceBranch_id,
                'DestinationBranch_id'=>$DestinationBranch_id,
                'quantity'=>$quantity,
                'transaction_date'=>$request->date,
                'transaction_cost'=>$request->cost,
            ]);
        }

        $newProductQuantity = $ProductQuantity - $quantity;
        $Product->update([
            'quantity'=>$newProductQuantity
            ]); 
        $New_ProductQuantity = $Product->value('quantity');

        $newBranchProduct = BranchesProducts::query()->create([
            'product_id' => $Product->product_id,
            'branch_id'=>$DestinationBranch_id,
            'quantity'=>$quantity,
            'price'=>$Product->price,
            'prod_date'=>$Product->prod_date,
            'exp_date'=>$Product->exp_date,
            'date_in'=>$Product->date_in,
            'purchase_num'=>$Product->purchase_num,
            'buying_cost'=>$Product->buying_cost,
        ]);

        return response()->json([
            'inner transaction data'=>$innerTransaction,
            'new branch product data'=>$newBranchProduct,
            'status code'=>http_response_code(),
        ]);
    }

    public function showInnerTransaction() //Keeper&Assistant
    {
        $manager = auth()->guard('manager-api')->user();
        $employee = $manager->employee;
        $branch_id = $employee->branch_id;

        $out = InnerTransaction::where('SourceBranch_id', $branch_id)->get();
        $in = InnerTransaction::where('DestinationBranch_id', $branch_id)->get();

        return response()->json([
            'out transaction' => $out,
            'in transactions'=> $in,
            'status code'=> http_response_code()
        ]);
    }

    public function showInnerTransactionGeneral($branch_id) //General
    {
        $out = InnerTransaction::where('SourceBranch_id', $branch_id)->get();
        $in = InnerTransaction::where('DestinationBranch_id', $branch_id)->get();

        return response()->json([
            'out transaction' => $out,
            'in transactions'=> $in,
            'status code'=> http_response_code()
        ]);
    }
}
