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
            'phone_number'=>'required ',
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
                ]);
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
                    'message'=> 'the roles are 1 or 2',
                    'status code'=> http_response_code(),
                ]);
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
            ]);
        }

        else{
            if ($is_manager==0 ){

            $this->validate($request,[
                'employee_name'=> 'required | string',
                'email'=>'required | email',
                'phone_number'=>'required ',
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
                'data' => $employee,
                'status code'=>http_response_code()
                ]);
        }
        elseif ($is_manager == 1){

            $manager = (new EmployeeController)->addAK($request, $role_id, $branch_id , $is_manager, $password);
                return response()->json([
                    'data'=>$manager,
                    'status code' => http_response_code()
                ]);

        }
        else
        {
            return response()->json([
                'message'=>'the roles are 1 or 2 or 3',
                'status code'=>http_response_code()
            ]);
        }
        }
        
    }

    public function ShowEmployees()
    {
        $employees = Employee::all();
        if ($employees->isEmpty()) {
            return response()->json([
                'message' => 'no employees to show',
                'status code'=>http_response_code(),
            ]);
        }
        return response()->json([
            'data'=>$employees,
            'status code'=> http_response_code(),
        ]);
    }

    public function ShowBranchesEmployee($id)
    {
        $branch = Employee::where('branch_id',$id)->get();
        if ($branch->isEmpty()) {
            return response()->json([
                'message' => 'no employyees',
            'status code' => http_response_code()
            ],http_response_code());
        }
        return response()->json([
            'data' => $branch,
            'status code' => http_response_code()
        ]);

    }

    public function ShowBranchesManagers($role_id, $branch_id = null )
    {
        if($branch_id){
            $branch = Branch::find($branch_id);
            $managers = $branch->managers()->where('role_id', $role_id)->with('employee')->get();
        }
        elseif (!$branch_id){
            $managers = Manager::where('role_id', $role_id)->with('employee')->get()->groupBy(function ($manager) {
                return $manager->employee->branch_id ;
            });
        }
        if ($managers->isEmpty()) {
            return response()->json([
                'message' => 'no employyees',
            'status code' => http_response_code()
            ],http_response_code());
        }
        return [
            'data' => $managers,
            'status code' => http_response_code()
        ];
    }

    public function ShowAllBranchesManagers($branch_id = null)
    {
        $keepers = (new EmployeeController)->ShowBranchesManagers( 1, $branch_id );
        $assistants = (new EmployeeController)->ShowBranchesManagers(2, $branch_id);
        return (!$keepers && !$assistants) 
            ? response()->json(['message' => 'no employyees',],http_response_code())
                : response()->json([
                    'keepers' => $keepers,
                    'assistants'=>$assistants,
                ],http_response_code());
    }

    public function showDetails($emp_id)
    {
        $EmployeeDetails = Employee::where('id', $emp_id);
        if ($EmployeeDetails->value('is_manager') == 1){
            $role = Manager::where('employee_id', $emp_id)->first('role_id');
            $EmployeeDetails =  $EmployeeDetails->first();
            return response()->json([
                'data'=>$EmployeeDetails,
                'role'=>$role,
                'status code'=>200,
            ]);
        }
        else{
            $EmployeeDetails = $EmployeeDetails->first();
            return response()->json([
                'data'=>$EmployeeDetails,
                'status code'=>200,
            ]);
        }
    }
    
}
