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


    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(EquipmentFix $equipment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EquipmentFix $equipment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EquipmentFix $equipment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EquipmentFix $equipment)
    {
        //
    }
}

