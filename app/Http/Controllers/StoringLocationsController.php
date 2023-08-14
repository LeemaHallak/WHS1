<?php

namespace App\Http\Controllers;

use App\Models\StoringLocations;
use App\Http\Requests\StoreStoringLocationsRequest;
use App\Http\Requests\UpdateStoringLocationsRequest;
use Illuminate\Http\Request;

class StoringLocationsController extends Controller
{
    public function showAvailableSections($operator)
    {
        $manager = auth()->guard('manager-api')->user();
        if ($manager->role_id !=3){
            $employee = $manager->employee;
        $branch_id = $employee->branch_id;
        $location = StoringLocations::where('branch_id', $branch_id)
                    ->whereNotNull('locationNum')
                    ->where('available_quantity', $operator, 0)
                    ->orderBy('available_quantity')
                    ->get(['locationNum', 'available_quantity', 'unavailable_quantity']);
        }
        else {
            $location = StoringLocations::whereNotNull('locationNum')
                    ->where('available_quantity', $operator, 0)
                    ->orderBy('available_quantity')
                    ->get(['locationNum', 'available_quantity', 'unavailable_quantity']);
        }
        return $location;
    }

    public function showStoringLocation()
    {
        $availableSections = (new StoringLocationsController())->showAvailableSections('>');
        $unAvailableSections = (new StoringLocationsController())->showAvailableSections('=');  
        return response()->json([
            'available sections'=>$availableSections,
            'unavailable sections'=>$unAvailableSections,
        ],http_response_code());
    }

    public function showMainSections()
    {
        $manager = auth()->guard('manager-api')->user();
        if ($manager->role_id !=3){
            $employee = $manager->employee;
            $branchId = $employee->branch_id;
        }
        $Sections = StoringLocations::when($branchId, function($query) use ($branchId){
            $query->where('branch_id', $branchId);
        })->whereNotNull('locationNum');

        $availableSections = $Sections
        ->where('available_quantity', '>', 0)
        ->orderBy('available_quantity')
        ->distinct()
        ->pluck('main_section');

        $unAvailable = StoringLocations::when($branchId, function($query) use ($branchId){
            $query->where('branch_id', $branchId);
        })->whereNotNull('locationNum')
        ->where('available_quantity', '=', 0)
        ->distinct()
        ->pluck('main_section');

        $unAvailableSections = $unAvailable->diff($availableSections)->values()->toArray();    

        return response()->json([
            'available sections'=>$availableSections,
            'unavailable sections'=>$unAvailableSections,
        ],http_response_code());
    }

    public function showSections($mainSection)
    {
        $manager = auth()->guard('manager-api')->user();
        $employee = $manager->employee;
        $branch_id = $employee->branch_id;
        $location = StoringLocations::where('branch_id', $branch_id)
                    ->where('main_section', $mainSection)
                    ->whereNotNull('locationNum')
                    ->where('available_quantity', '>', 0)
                    ->orderBy('available_quantity')
                    ->get(['locationNum', 'available_quantity', 'unavailable_quantity']);
        return response()->json($location, http_response_code());
    }

    public function showDetails($id)
    {
        $locationDetails = StoringLocations::find($id);
        return response()->json([
            'data'=>$locationDetails,
            'status code'=>http_response_code(),
        ]);
    }

    public function store(Request $request)
    {
        $main_section = $request->main_section;
        $section = $request->section ;
        $branch_id = $request->branch_id ;

        $main_sectionString = strval($main_section);
        $sectionString = strval($section);
        $locationNum = $main_sectionString."-".$sectionString;

        $location = StoringLocations::query()->create([
            'main_section'=> $main_section,
            'section'=>$section,
            'branch_id'=>$branch_id,
            'available_quantity'=> $request->available_quantity,
            'unavailable_quantity'=> $request->unavailable_quantity,
            'locationNum'=> $locationNum ,
        ]);
        return response()->json($location->locationNum);
    
    }


}
