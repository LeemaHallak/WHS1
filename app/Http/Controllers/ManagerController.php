<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Manager;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{

    public function LogIn (Request $request)
    {
        $LogInData = $request-> validate([
            'email' =>'required|exists:employees',
            'password'=>'required',
        ]);
        $employee = Employee::firstWhere('email', $LogInData['email']);

        if ($employee) {
            if ($employee->manager && Hash::check($LogInData['password'], $employee->manager->password)) {
                config(['auth.guards.api.provider' => 'manager']);
                $manager = $employee->manager;
                $success =  $manager;
                $success['token'] =  $manager->createToken('MyApp',['manager'])->accessToken; 
                $employee = Employee::find($manager->employee_id)->select('branch_id')->first();
                return response()->json([
                   'data' => $success,
                   'employeeData' => $employee
                ], 200);
                
            }
            else if (!$employee->manager){
                return response()->json(['message' => 'wrong email'], 401);
            }
            else if (!Hash::check($LogInData['password'], $employee->manager->password)){
                return response()->json(['message' => 'wrong password'], 401);
            }
        } else {
            return response()->json(['message' => 'Employee not found'], 404);
        }
        
        $manager = $request->user();
        $accessToken = $manager->createToken('personal access token');
        $manager ['remember_token'] = $accessToken;
        $employee = Employee::find($manager->employee_id)->select('branch_id')->first();
        return response()->json([
            'data' => $manager,
            'employee' => $employee,
            'typeToken' => 'Bearer',
            'token' => $accessToken->accessToken
        
        ], 200);
    }

    public function LogOut ()
    {
        $manager = Auth::manager()->token()->delete();
        return response()->json(['success'=>'logged out successfully'], 200);
    }

    public function RemoveManager($id)
    {
        $manager = Manager::query()->find($id)->delete();
        return http_response_code();
    }

    
}
    