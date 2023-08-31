<?php

namespace App\Http\Controllers;

use App\Models\ProducingCompany;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;

class ProducingCompanyController extends Controller
{
    public function store(Request $request)
    {
        $this-> validate($request, [
            'company_code'=> 'required | integer ', 
            'phone_number'=> 'required | integer ', 
            'company_register'=> 'required | integer ', 
            'industry_register'=> 'required | integer ', 
        ]);
        $ProducingCompany = ProducingCompany::query()->create([
            'company_code'=>$request->company_code,
            'company_name'=>$request->company_name,
            'address_id'=>$request->address,
            'phone_number'=>$request->phone_number,
            'email'=>$request->email,
            'company_register'=>$request->company_register,
            'industry_register'=>$request->industry_register,
        ]);
        return response()->json([$ProducingCompany], Response::HTTP_CREATED );
    }

    public function showProducingCompanies()
    {
        $companies = ProducingCompany::all();
        return $companies->isEmpty()
            ? response()->json(['message' => 'No company found.'], Response::HTTP_NO_CONTENT)
            : response()->json(['data' => $companies], Response::HTTP_OK);      
    }

}
