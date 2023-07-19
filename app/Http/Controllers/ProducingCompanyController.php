<?php

namespace App\Http\Controllers;

use App\Models\ProducingCompany;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class ProducingCompanyController extends Controller
{
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
    public function store(Request $request, UserController $PC_customer)
    {
        
        $ProducingCompany = ProducingCompany::query()->create([
            'company_name'=>$request->input('company_name'),
            'address'=>$request->input('address'),
            'phone_number'=>$request->input('phone_number'),
            'email'=>$request->input('email'),
            'company_register'=>$request->input('company_register'),
            'industry_register'=>$request->input('industry_register'),
            'is_customer'=>$request->input('is_customer'),
        ]);
        return response()->json([$ProducingCompany],201 );
    }

    public function RemoveProducingCompany($id)
    {
        $producingCompany = ProducingCompany::query()->find($id)->delete();
        return http_response_code();
    }

    /**
     * Display the specified resource.
     */
    public function show(ProducingCompany $producing_company)
    {
        //
    }

    public function showProducingCompanies()
    {
        $companies = ProducingCompany::all();
        if ($companies->isEmpty()) {
            return response()->json([
                'message' => 'no companies to show'
            ]);
        }
        return response()->json([
            'companies' => $companies
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProducingCompany $producing_company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProducingCompany $producing_company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProducingCompany $producing_company)
    {
        //
    }
}
