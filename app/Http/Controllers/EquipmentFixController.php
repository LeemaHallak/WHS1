<?php

namespace App\Http\Controllers;

use App\Models\EquipmentFix;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EquipmentFixController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    
    public function showEquipmentsFixes($equipment_id)
    {
            $equipmentFixes = EquipmentFix::where('equipment_id', $equipment_id);
            $CountEquipmentFixes = $equipmentFixes->count();
            $GetEquipmentFixes = $equipmentFixes->get();

            return response()->json([
                'equipment fixes: ' => $GetEquipmentFixes,
                'equipment fix times: ' => $CountEquipmentFixes,
                'status code' => http_response_code(),
            ]);
    }

    public function showAllFixes()
    {
            $equipmentFixes = EquipmentFix::query();
            $CountEquipmentFixes = $equipmentFixes->count();
            $GetEquipmentFixes = $equipmentFixes->get()->groupBy('equipment_id');

            return response()->json([
                'equipment fixes: ' => $GetEquipmentFixes,
                'equipment fix times: ' => $CountEquipmentFixes,
                'status code' => http_response_code(),
            ]);
    }

}

