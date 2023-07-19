<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\BranchController;



Route:: prefix('/users')->group( function (){ 
    
      
    Route::post('/login',[UserController::class, 'LogIn']); 
    
    
    Route::group( ['middleware' => ['auth:user-api','scopes:user'] ],function(){
        // authenticated staff routes here 
        Route::post('/logout',[UserController::class, 'LogOut']);
        
    });     
});  
