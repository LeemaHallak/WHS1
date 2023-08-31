<?php

namespace App\Http\Controllers;

use App\Models\EquipmentFix;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EquipmentFixController extends Controller
{
    public function showEquipmentsFixes($equipmentIid)
    {
            $equipmentFixes = EquipmentFix::when($equipmentIid, fn($query) =>
                    $query->where('equipment_id', $equipmentIid));
            $CountEquipmentFixes = $equipmentFixes->count();
            $GetEquipmentFixes = $equipmentFixes->get();

            return response()->json([
                'equipment fixes: ' => $GetEquipmentFixes,
                'equipment fix times: ' => $CountEquipmentFixes
            ], Response::HTTP_OK);
    }

}

