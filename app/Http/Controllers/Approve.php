<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Cjmellor\Approval\Concerns\MustBeApproved;
use Cjmellor\Approval\Models\Approval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Approve extends Controller
{
    //use MustBeApproved;
    public function updateState($request_id)
    {
        $request = Approval::query()->find($request_id);
        if(auth()->id() == $request->ResponsibleManager_id){
            $updated = $request->approve();
            return response()->json('approved');
        }
        else{
            return response()->json('you can not access');
        }
    }

    public function reject($request_id)
    {
        $request = Approval::query()->find($request_id);
        if(auth()->id() == $request->ResponsibleManager_id){
            $updated = $request->reject();
            return response()->json('rejected');
        }
        else{
            return response()->json('you can not access');
        }
    }

    public function showRequests()
    {
        $Responsible = Auth::id();
        $requests = Approval::where('ResponsibleManager_id', $Responsible)->get();
<<<<<<< HEAD
        return response()->json($requests);
=======
        return response()->json(['list'=>$requests]);
>>>>>>> c49dff98 (neew)
    }
    
}
