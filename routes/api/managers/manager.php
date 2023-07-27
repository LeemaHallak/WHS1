<?php

use App\Http\Controllers\AddressController;
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
use App\Http\Controllers\UnitController;
use App\Models\BranchesCustomers;
use App\Http\Middleware\Admin;
use App\Models\Equipment;
use App\Models\EquipmentFix;
use App\Models\Financial;

Route:: prefix('/managers')->group( function (){ 
    Route::post('/register',[EmployeeController::class, 'register']);
    Route::post('/login',[ManagerController::class, 'LogIn']);
    Route::get('/location',[StoringLocationsController::class, 'getnum']);

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
            Route::get('/BranchProducts/{branch_id}',[BranchesProductsController::class, 'BranchProducts']);
            Route::get('/BranchCatProducts/{branch_id}/{category_id}',[BranchesProductsController::class, 'BranchesCatProducts']);
            Route::get('/equipment/{branch_id}',[EquipmentController::class, 'ShowEquipment']);
            Route::get('/equipmentFixes/{equipment_id}', [EquipmentFixController::class, 'showEquipmentsFixes' ]);
            Route::get('/equipmentsCosting/{branch_id}/{fixingCost}',[EquipmentController::class, 'showCosts']);
            Route::get('/employees/{id}',[EmployeeController::class, 'ShowBranchesEmployee']);
            Route::controller(AddressController::class)->group(function(){
                Route::get('/countries', 'showCountries');
                Route::get('/cities', 'showCities');
                Route::get('/regions', 'showRegions');
                Route::get('/addresses', 'showAddresses');
            });
            Route::get('/units', [UnitController::class, 'showUnits']);
        });

        Route::prefix('/Add')->group(function(){
            Route::post('/assistants',[EmployeeController::class, 'addAK']);  //define, Gate
            Route::post('/customer',[UserController::class, 'AddCustomer']);  //define, Gate
            Route::post('/units', [UnitController::class, 'addUnits']);

        });
        Route::prefix('/edit')->group(function(){
            Route::post('/editShipment/{id}',[ShipmentController::class, 'editShipment']);
        });

    });
});  