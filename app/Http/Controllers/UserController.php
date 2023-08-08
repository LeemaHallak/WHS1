<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use PHPUnit\TextUI\Configuration\Variable;

class UserController extends Controller
{
    //

    public function AddCustomer(Request $request )
    {
        //$this->authorize('addCustomer');
        $this-> validate($request, [
            'customer_name'=> 'required | string',
            'email'=>'required | email',
            'password'=>'required | string',
            'phone_number'=>'required ',
            'address_id'=>'required',
            'company_register' => 'required ',
            'industry_register'=> 'required',
            'is_ProducingCompany' => 'boolean',
        ]);
            $register_inputs = $request->all();
            $register_inputs['password'] = Hash::make($register_inputs['password']);
            $user = User::create($register_inputs);

            $accessToken = $user->createToken('personal access token', ['user']);
            $user['remember_token']= $accessToken;

            return response()->json([
            'data' => $user,
            'typeToken' => 'Bearer',
            'token' => $accessToken->accessToken
            ]);
    }

    public function showCustomers( Request $request, $getBy = null)
    {
        $customers = User::query();
        if($getBy == 'name'){
            $name = $request->name;
            $customers = $customers->where('customer_name', $name)->get();
        }
        if($getBy == 'address'){
            $address = $request->address;
            $customers = $customers->where('address_id', $address)->get();
        }
        if($getBy == null){ 
            $customers = $customers->get();
        }
        return response()->json([
            'customers' => $customers,
            'status code' => http_response_code(),
        ]);
    }

    public function LogIn (Request $request)
    {
        $LogInData = $request-> validate([
            'email' =>'email|required|exists:users',
            'password'=>'required',
        ]);

        if(auth()->guard('user')->attempt(['email' => request('email'), 'password' => request('password')]))
        {
            config(['auth.guards.api.provider' => 'user']);
            $user = User::select('users.*')->find(auth()->guard('user')->user()->id);
            $success =  $user;
            $success['token'] =  $user->createToken('MyApp',['user'])->accessToken; 
            return response()->json($success, 200);
        }
        else
        { 
            return response()->json(['error' => 'Email and Password are Wrong.'], 400 );
        }

        if(!auth()->attempt($LogInData))
        {
            return response()->json(['errors'=>
                ['message'=>
                ['could not log in with those data']
                ]
            ],422);
        }
        $user = $request->user();
        $accessToken = $user->createToken('personal access token');
        $user ['remember_token'] = $accessToken;

        return response()->json([
            'data' => $user,
            'typeToken' => 'Bearer',
            'token' => $accessToken->accessToken
        ], 200);
    }

    public function LogOut ()
    {
        $user = Auth::user()->token()->delete();
        return response()->json(['success'=>'logged out successfully'], 200);
    }


}

