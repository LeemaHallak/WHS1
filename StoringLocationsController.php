<?php

namespace App\Http\Controllers;

use App\Models\StoringLocations;
use App\Http\Requests\StoreStoringLocationsRequest;
use App\Http\Requests\UpdateStoringLocationsRequest;
use Illuminate\Http\Request;

class StoringLocationsController extends Controller
{
    public function getnum()
    {
        $location = StoringLocations::get('locationNum');
        return response()->json($location);
    }

    public function store(Request $request)
    {
        $main_section = $request->main_section;
        $section = $request->section ;
        $branch_id = $request->branch_id ;

        $main_sectionString = strval($main_section);
        $sectionString = strval($section);
        $branch_idString = strval($branch_id);
        $locationNum = $main_sectionString.$sectionString.$branch_idString;

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

    public function RemoveLocation($main, $section)
    {
        StoringLocations::query()->where('main_section', $main)->Where('section', $section)->delete();
        return http_response_code();
    }

}
