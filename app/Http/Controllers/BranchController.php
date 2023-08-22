<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function ShowBranches()
    {
        $branches = Branch::with('address')->get();
        
        return response()->json([
            'data'=>$branches
        ], Response::HTTP_FOUND);
    }

    public function BranchDetails($id)
    {
        $branchDetails = Branch::with([
            'address.regions.cities.countries',
            'employees' => function ($query) {
                $query->whereHas('manager', function ($query) {
                    $query->whereIn('role_id', [1, 2]);
                });
            },
            'employees.manager' => function ($query) {
                $query->whereIn('role_id', [1, 2]);
            }
        ])->find($id);
        
        return response()->json(
            $branchDetails, Response::HTTP_FOUND
            );
    }

    public function store(Request $request)
    {
        $this-> validate($request, [
            'phone_number'=>'required',
            'space'=>'required',
            'company_register' => 'required ',
        ]);
            
            $branch = Branch::query()->create([
                'address_id'=>$request->address_id,
                'phone_number'=>$request->phone_number,
                'space'=>$request->space,
                'company_register' => $request->company_register,
                'sectionMaxCapacity'=> $request->section_maxCapacity,
            ]);
            return response()->json([
                'data'=>$branch
            ], Response::HTTP_OK);
    }
}
