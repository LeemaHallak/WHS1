<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

use function PHPUnit\Framework\isEmpty;

class EmployeeController extends Controller
{

    public function register(Request $request)
    {
        $role_id = $request->role_id;
        $password = Hash::make($request->password);
        $branch_id = $request->branch_id;
        $is_manager = $request->is_manager;
        $position = $request->position;

        $this->validate($request,[
            'employee_name'=> 'required | string',
            'email'=>'required | email',
            'phone_number'=>'required | integer ',
            'salary' => 'required',
            'position'=> 'required',
        ]);

        $image_url = '/storage/' . $request->file('image')->store('employees', 'public');

        $employee = Employee ::query()->create([
            'employee_name' => $request->employee_name ,
            'email'=> $request->email,
            'phone_number' => $request->phone_number,
            'address_id'=> $request->address_id,
            'branch_id' => $branch_id,
            'salary'=>$request->salary,
            'photo'=>$image_url,
            'position'=>$position,
            'is_manager'=> $is_manager,
        ]);

        $employee_id = $employee->id;

        $manager = Manager::query()->create([
            'employee_id' => $employee_id,
            'password' => $password,
            'role_id'=>$role_id,
        ]);
        $accessToken = $manager->createToken('personal access token', ['manager']);
        $manager['remember_token']= $accessToken;
        return [
            'employee data' => $employee,
            'manager data'=>$manager,
            'typeToken' => 'Bearer',
            'token' => $accessToken->accessToken,
            201
        ];
    }

    public function addAK(Request $request)
    {
        $this->authorize('addAK');

        $role_id = $request->role_id;
        $password = Hash::make($request->password);
        $branch_id = $request->branch_id;
        $is_manager = $request->is_manager;
        
        if($role_id == 1){

            $branch = Branch::find($branch_id);
            $CheckManager = $branch->managers()->where('role_id', $role_id)->exists();
            
            if ($CheckManager){
                return response()->json([
                    'message'=>'manager exists'
                ], 400);
            }

            $manager = (new EmployeeController)->register($request, $role_id, $branch_id , $is_manager, $password);
                return $manager;
            }
            elseif ($role_id == 2){

                $manager = (new EmployeeController)->register($request, $role_id, $branch_id , $is_manager, $password);
                return $manager;
            }
            else
            {
                return response()->json([
                    'message'=> 'the roles are 1 or 2'
                ], 400);
            }
    }

    public function AddEmployee(Request $request, Branch $branch)
    {
        $role_id = $request->role_id;
        $password = Hash::make($request->password);
        $branch_id = $request->branch_id;
        $is_manager = $request->is_manager;
        
        if ($role_id == 3 ){
            return response()->json([
                'message'=>'there is a general manager already'
            ], 400);
        }

        else{
            if ($is_manager==0 ){

            $this->validate($request,[
                'employee_name'=> 'required | string',
                'email'=>'required | email',
                'phone_number'=>'required | integer',
                'address'=>'string',
                'salary' => 'required',
            ]);

            $image_url = '/storage/' . $request->file('image')->store('employees', 'public');
    
            $employee = Employee ::query()->create([
                'employee_name' => $request->employee_name ,
                'email'=> $request->email,
                'phone_number' => $request->phone_number,
                'address_id'=> $request->address_id,
                'branch_id' => $branch_id,
                'salary'=>$request->salary,
                'photo'=>$image_url,
                'position'=>$request->position,
                'is_manager'=> $is_manager,
            ]);
            return response()->json([
                'data' => $employee
                ], 201);
        }
        elseif ($is_manager == 1){

            $manager = (new EmployeeController)->addAK($request, $role_id, $branch_id , $is_manager, $password);
                return response()->json([
                    'data'=>$manager
                ], 201);

        }
        else
        {
            return response()->json([
                'message'=>'the roles are 1 or 2 or 3'
            ], 400);
        }
        }
        
    }

    public function ShowEmployees()
    {
        $employees = Employee::all();
        
        $responseData = $employees->isEmpty()
            ? ['message' => 'there is no employees', 204]
            : ['data' => $employees, 200];

        return response()->json($responseData);
    }

    public function ShowBranchesEmployee($branchId)
    {
        $branchEmployees = Employee::where('branch_id',$branchId)->get();
        
        $responseData = $branchEmployees->isEmpty()
            ? ['message' => 'there is no employees in this branch', 204]
            : ['data' => $branchEmployees, 200];

        return response()->json($responseData);

    }

    public function ShowBranchesManagers($roleId, $branchId = null )
    {
        if($branchId){
            $branch = Branch::find($branchId);
            $managers = $branch->managers()->where('role_id', $roleId)->with('employee')->get();
        }
        elseif (!$branchId){
            $managers = Manager::where('role_id', $roleId)->with('employee')->get()->groupBy(function ($manager) {
                return $manager->employee->branch_id ;
            });
        }
        
        $responseData = $managers->isEmpty()
            ? ['message' => 'there is no managers', 204]
            : ['data' => $managers, 200];

        return response()->json($responseData);
    }

    public function ShowAllBranchesManagers($branchId = null)
    {
        $keepers = (new EmployeeController)->ShowBranchesManagers( 1, $branchId );
        $assistants = (new EmployeeController)->ShowBranchesManagers(2, $branchId);
        return (!$keepers && !$assistants) 
            ? response()->json(['message' => 'no employyees',],204)
                : response()->json([
                    'keepers' => $keepers,
                    'assistants'=>$assistants,
                ],200);
    }

    public function showDetails($emplyeeId)
    {
        $EmployeeDetails = Employee::where('id', $emplyeeId);
        if ($EmployeeDetails->value('is_manager') == 1){
            $role = Manager::where('employee_id', $emplyeeId)->first('role_id');
            $EmployeeDetails =  $EmployeeDetails->first();
            return response()->json([
                'data'=>$EmployeeDetails,
                'role'=>$role
            ], 200);
        }
        else{
            $EmployeeDetails = $EmployeeDetails->first();
            return response()->json([
                'data'=>$EmployeeDetails
            ], 200);
        }
    }
    
}
