<?php

namespace App\Http\Controllers;

use App\Models\EquipmentFix;
use Carbon\Carbon;
//use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EquipmentFixController extends Controller
{
    public function showEquipmentsFixes($equipment_id)
    {
            $equipmentFixes = EquipmentFix::where('equipment_id', $equipment_id);
            $CountEquipmentFixes = $equipmentFixes->count();
            $GetEquipmentFixes = $equipmentFixes->get();

            return response()->json([
                'equipment fixes: ' => $GetEquipmentFixes,
                'equipment fix times: ' => $CountEquipmentFixes
            ], Response::HTTP_OK);
    }

    public function showAllFixes()
    {
            $equipmentFixes = EquipmentFix::query();
            $CountEquipmentFixes = $equipmentFixes->count();
            $GetEquipmentFixes = $equipmentFixes->get()->groupBy('equipment_id');

            return response()->json([
                'equipment fixes: ' => $GetEquipmentFixes,
                'equipment fix times: ' => $CountEquipmentFixes
            ], Response::HTTP_OK);
    }
}

