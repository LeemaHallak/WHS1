<?php

use App\Http\Controllers\Approve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BranchesCustomersController;
use App\Http\Controllers\BranchesInnerCatController;
use App\Http\Controllers\BranchesProductsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\EquipmentFixController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\InnerCategoryController;
use App\Http\Controllers\ProducingCompanyController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderListController;
use App\Http\Controllers\OrderProductsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\StoringLocationsController;
use App\Models\BranchesCustomers;
use App\Http\Middleware\Admin;
use App\Models\Equipment;
use App\Models\EquipmentFix;
use App\Models\Financial;

Route:: prefix('/managers')->group( function (){ 
    Route::post('/register',[EmployeeController::class, 'register']);
    Route::post('/login',[ManagerController::class, 'LogIn']);
    Route::get('/location',[StoringLocationsController::class, 'getnum']);
    Route::post('/addlocation',[StoringLocationsController::class, 'store']); 
    Route::post('/store',[ShipmentController::class, 'store']);
    
    
    Route::group( ['middleware' => ['auth:manager-api','scopes:manager'] ],function(){
        // authenticated staff routes here 
        Route::post('/logout',[ManagerController::class, 'LogOut']);
        Route::post('/responsible', [EquipmentController::class, 'Responsible']);
        
        Route::post('/update/{request_id}', [Approve::class, 'updateState']);
        Route::post('/reject/{request_id}', [Approve::class, 'reject']);

        Route:: prefix('/show')->group( function (){
            Route::get('/branches',[BranchController::class, 'ShowBranches']); 
            Route::get('/Pcategories',[CategoryController::class, 'ShowParentsCategories']);
            Route::get('/CHcategories/{branch_id}/{category_id}',[CategoryController::class, 'ShowChildrenCategories']);
            Route::get('/AllCHcategories/{branch_id}/{category_id}',[CategoryController::class, 'ShowAllChildrenCategories']);
            Route::get('/RootsCats',[CategoryController::class, 'ShowRootsCategories']); 
            Route::get('/BranchDetails/{id}',[BranchController::class, 'BranchDetails']);
            Route::get('/ProducingCompanies',[ProducingCompanyController::class, 'ShowProducingCompanies']);
            Route::get('/CatProducts/{category_id}',[ProductController::class, 'CatProducts']);
            //Route::get('/branchescats',[BranchesCategoriesController::class, 'showBranchCategories']);  
            Route::get('/BranchProducts/{branch_id}',[BranchesProductsController::class, 'BranchProducts']);
            Route::get('/BranchCatProducts/{branch_id}/{category_id}',[BranchesProductsController::class, 'BranchesCatProducts']);
            Route::get('/equipments/{branch_id}',[EquipmentController::class, 'ShowEquipments']);
            Route::get('/equipmentFixes/{equipment_id}', [EquipmentFixController::class, 'showEquipmentsFixes' ]);
            Route::get('/equipmentsCosting/{branch_id}/{fixingCost}',[EquipmentController::class, 'showCosts']);
            Route::get('/employees/{id}',[EmployeeController::class, 'ShowBranchesEmployee']);
        });

        Route::prefix('/Add')->group(function(){
            Route::post('/assistants',[EmployeeController::class, 'addAK']);  //define, Gate
            Route::post('/customer',[UserController::class, 'AddCustomer']);  //define, Gate
            Route::post('/existingEquipment/{equipment_id}', [EquipmentController::class, 'AddExistingEquipment']);

        });
        Route::prefix('/edit')->group(function(){
            Route::post('/editShipment/{id}',[ShipmentController::class, 'editShipment']);
        });
        Route::prefix('/delete')->group(function(){
            Route::delete('/category/{id}',[CategoryController::class, 'RemoveCat']);
            Route::delete('/BranchEquipment/{id}',[BranchesEquipmentsController::class, 'RemoveBranchEquipment']);
            Route::delete('/BranchProduct/{id}',[BranchesProductsController::class, 'RemoveBranchProduct']);
            Route::delete('/employee/{id}',[EmployeeController::class, 'RemoveEmployee']);
            Route::delete('/equipment/{id}',[EquipmentController::class, 'RemoveEquipment']);
            Route::delete('/financial/{id}',[FinancialController::class, 'RemoveFinancial']);
            Route::delete('/order/{id}',[OrderController::class, 'RemoveOrder']);
            Route::delete('/orderProducts/{id}',[OrderProductsController::class, 'RemoveOrderProduct']);
            Route::delete('/producingCompany/{id}',[ProducingCompanyController::class, 'RemoveProducingCompany']);
            Route::delete('/shipment/{id}',[ShipmentController::class, 'RemoveShipment']);
            Route::delete('/storingLocations/{id}',[StoringLocationsController::class, 'RemoveLocation']);
            Route::delete('/customer/{id}',[UserController::class, 'RemoveCustomer']);
            Route::delete('/product/{id}',[ProductController::class, 'RemoveProduct']);

        });

    });
});  