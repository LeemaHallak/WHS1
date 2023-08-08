<?php

namespace App\Http\Controllers;

use App\Models\ProducingCompany;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class ProducingCompanyController extends Controller
{
    public function store(Request $request, UserController $PC_customer)
    {
        $ProducingCompany = ProducingCompany::query()->create([
            'company_code'=>$request->input('company_code'),
            'company_name'=>$request->input('company_name'),
            'address_id'=>$request->input('address'),
            'phone_number'=>$request->input('phone_number'),
            'email'=>$request->input('email'),
            'company_register'=>$request->input('company_register'),
            'industry_register'=>$request->input('industry_register'),
        ]);
        return response()->json([$ProducingCompany],201 );
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

}
