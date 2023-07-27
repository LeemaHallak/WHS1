<?php

namespace App\Http\Controllers;

use App\Models\BranchesEquipments;
use App\Http\Requests\StoreBranchesEquipmentsRequest;
use App\Http\Requests\UpdateBranchesEquipmentsRequest;

class BranchesEquipmentsController extends Controller
{
    public function RemoveBranchEquipment($id)
    {
        $branchEquipment = BranchesEquipments::query()->find($id)->delete();
        return http_response_code();
    }

}
