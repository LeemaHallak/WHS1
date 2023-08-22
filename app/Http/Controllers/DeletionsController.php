<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\Shipment;
use App\Models\Financial;
use Illuminate\Http\Request;
use App\Models\OrderProducts;
use App\Models\BranchesProducts;
use App\Models\ProducingCompany;
use App\Models\StoringLocations;
use App\Models\BranchesEquipments;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Equipment;
use App\Models\Product;
use Illuminate\Http\Response;

class DeletionsController extends Controller
{
    public function RemoveBranchEquipment($id)
    {
        BranchesEquipments::query()->find($id)->delete();
        return Response::HTTP_OK;
    }

    public function RemoveEquipment($id)
    {
        Equipment::query()->find($id)->delete();
        return Response::HTTP_OK;
    }
    
    public function RemoveFinancial($id)
    {
        Financial::query()->find($id)->delete();
        return Response::HTTP_OK;
    }

    public function RemoveOrder($id)
    {
        Order::query()->find($id)->delete();
        return Response::HTTP_OK;
    }

    public function RemoveOrderProduct($id)
    {
        OrderProducts::query()->find($id)->delete();
        return Response::HTTP_OK;
    }
    
    public function RemoveProducingCompany($id)
    {
        ProducingCompany::query()->find($id)->delete();
        return Response::HTTP_OK;
    }
    
    public function RemoveLocation($main, $section)
    {
        StoringLocations::query()->where('main_section', $main)->Where('section', $section)->delete();
        return Response::HTTP_OK;
    }

    public function RemoveBranchProduct($id)
    {
        BranchesProducts::query()->where('id', $id)->delete();
        return Response::HTTP_OK;
    }
    
    public function RemoveEmployee($id)
    {
        Employee::query()->find($id)->delete();
        return Response::HTTP_OK;
    }

    public function RemoveCustomer($id)
    {
        User::query()->find($id)->delete();
        return Response::HTTP_OK;
    }
    
    public function RemoveShipment($id)
    {
        Shipment::query()->find($id)->delete();
        return Response::HTTP_OK;
    }

    public function RemoveCat($id)
    {
        Category::query()->find($id)->delete();
        return Response::HTTP_OK;
    }

    public function RemoveProduct($id)
    {
        Product::query()->find($id)->delete();
        return Response::HTTP_OK;
    }
    
    public function deleteBranch($id)
    {
        $branch = Branch::find($id);
        if ($branch) {
            $branch->delete();
            return response()->json([
                'message' => 'Branch deleted successfully'
            ], Response::HTTP_OK);
        } 
        else {
            return response()->json([
                'message' => 'Branch not found'
            ], Response::HTTP_OK);
        }
    }
}
