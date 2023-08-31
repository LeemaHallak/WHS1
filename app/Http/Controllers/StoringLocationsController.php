<?php

namespace App\Http\Controllers;

use App\Models\StoringLocations;
use App\Http\Requests\StoreStoringLocationsRequest;
use App\Http\Requests\UpdateStoringLocationsRequest;
use App\Models\Manager;
use Illuminate\Http\Request;

class StoringLocationsController extends Controller
{
    private function querySections($operator, $branchId = null)
    {
        return StoringLocations::when($branchId, fn($query) => $query->where('branch_id', $branchId))
            ->whereNotNull('locationNum')
            ->where('available_quantity', $operator, 0)
            ->orderBy('available_quantity');
    }

    public function showAllSections($operator, $branchId = null)
    {
        $manager = new Manager();
        $branchId = $manager->role() != 3 ? $manager->branch() : $branchId;

        $sections = $this->querySections($operator, $branchId)->get();
        return response()->json($sections, 200);
    }

    public function showStoringLocation()
    {
        $availableSections = $this->querySections('>')->get();
        $unAvailableSections = $this->querySections('=')->get();

        return response()->json([
            'available sections' => $availableSections,
            'unavailable sections' => $unAvailableSections,
        ], 200);
    }

    public function showMainSections()
    {
        $manager = new Manager();
        $branchId = $manager->role() != 3 ? $manager->branch() : null;

        $availableSections = $this->querySections('>', $branchId)
            ->distinct()
            ->pluck('main_section');

        $unAvailableSections = $this->querySections('=', $branchId)
            ->distinct()
            ->pluck('main_section')
            ->diff($availableSections)
            ->values()
            ->toArray();

        return response()->json([
            'available sections' => $availableSections,
            'unavailable sections' => $unAvailableSections,
        ], 200);
    }

    public function showSections($mainSection, $branchId = null)
    {
        $user = auth()->guard('manager-api')->user();
        $branchId = $branchId ?? ($user->employee->branch_id );
    
        $location = $this->querySections('>', $branchId = null)
            ->where('main_section', $mainSection)
            ->get(['locationNum', 'total_quantity', 'available_quantity']);
    
        return response()->json($location, 200);
    }
    

    public function showDetails($id)
    {
        $locationDetails = StoringLocations::find($id);
        return response()->json(['data' => $locationDetails], 200);
    }

    public function store(Request $request)
    {
        $main_section = $request->main_section;
        $section = $request->section;
        $branch_id = $request->branch_id;

        $locationNum = "$main_section-$section";

        $location = StoringLocations::query()->create([
            'main_section' => $main_section,
            'section' => $section,
            'branch_id' => $branch_id,
            'total_quantity' => $request->total_quantity,
            'available_quantity' => $request->available_quantity,
            'locationNum' => $locationNum,
        ]);

        return response()->json($location->locationNum, 201);
    }
}
