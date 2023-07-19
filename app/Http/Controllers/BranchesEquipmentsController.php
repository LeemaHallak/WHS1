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
    
    /**
     * Display a listing of the resource.
     */
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
    public function store(StoreBranchesEquipmentsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BranchesEquipments $branchesEquipments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BranchesEquipments $branchesEquipments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBranchesEquipmentsRequest $request, BranchesEquipments $branchesEquipments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BranchesEquipments $branchesEquipments)
    {
        //
    }
}
