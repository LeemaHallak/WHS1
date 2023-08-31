<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Cjmellor\Approval\Concerns\MustBeApproved;
use Cjmellor\Approval\Models\Approval;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class Approve extends Controller
{
    //use MustBeApproved;
    public function updateState($requestId)
    {
        $request = Approval::query()->find($requestId);
        if(auth()->id() == $request->ResponsibleManager_id){
            $updated = $request->approve();
            return response()->json('approved', 200);
        }
        else{
            return response()->json('you can not access', Response::HTTP_UNAUTHORIZED);
        }
    }

    public function reject($requestId)
    {
        $request = Approval::query()->find($requestId);
        if(auth()->id() == $request->ResponsibleManager_id){
            $updated = $request->reject();
            return response()->json('rejected', 200);
        }
        else{
            return response()->json('you can not access', Response::HTTP_UNAUTHORIZED);
        }
    }

    public function showRequests()
    {
        $Responsible = Auth::id();
        $requests = Approval::where('ResponsibleManager_id', $Responsible)->get();
        return response()->json($requests, 200);
    }
    
}
