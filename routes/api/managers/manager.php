<?php

use App\Models\Equipment;
use App\Models\Financial;
use App\Models\EquipmentFix;
use Illuminate\Http\Request;
use App\Http\Middleware\Admin;
use App\Http\Controllers\Approve;
use App\Models\BranchesCustomers;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\OrderListController;
use App\Http\Controllers\EquipmentFixController;
use App\Http\Controllers\InnerCategoryController;
use App\Http\Controllers\OrderProductsController;
use App\Http\Controllers\BranchesInnerCatController;
use App\Http\Controllers\BranchesProductsController;
use App\Http\Controllers\ProducingCompanyController;
use App\Http\Controllers\StoringLocationsController;
use App\Http\Controllers\BranchesCustomersController;

Route:: prefix('/managers')->group( function (){ 
    Route::post('/register',[EmployeeController::class, 'register']);
    Route::post('/login',[ManagerController::class, 'LogIn']);
    Route::group( ['middleware' => ['auth:manager-api','scopes:manager'] ],function(){
        Route::post('/logout',[ManagerController::class, 'LogOut']);
        Route:: prefix('/show')->group( function (){
            Route::controller(BranchController::class)->group(function(){
                Route::get('/branches', 'ShowBranches'); 
                Route::get('/BranchDetails/{id}', 'BranchDetails');
            });
            Route::controller(BranchesProductsController::class)->group(function(){
                Route::get('/BranchProducts/{branch_id?}', 'BranchProducts');
                Route::get('/Details/{product_id}', 'BranchProductDetails');
                Route::get('/BranchCatProducts/{branch_id}/{category_id}', 'BranchesCatProducts');
            });
            Route::controller(EquipmentController::class)->group(function(){
                Route::get('/equipment/{branch_id}', 'ShowEquipment');
                Route::get('/equipmentsCosting/{branch_id}/{fixingCost}', 'showCosts');
            });
            Route::controller(CategoryController::class)->group(function(){
                Route::get('/Pcategories', 'ShowParentsCategories');
                Route::get('/CHcategories/{branch_id}/{category_id}', 'ShowChildrenCategories');
                Route::get('/AllCHcategories/{branch_id}/{category_id}', 'ShowAllChildrenCategories');
                Route::get('/RootsCats', 'ShowRootsCategories'); 
            });
            Route::controller(AddressController::class)->group(function(){
                Route::get('/countries', 'showCountries');
                Route::get('/cities', 'showCities');
                Route::get('/regions', 'showRegions');
                Route::get('/addresses', 'showAddresses');
            });
            Route::controller(StoringLocationsController::class)->group(function(){
                Route::get('/location', 'showStoringLocation');
                Route::get('/sectionLocation/{mainSection}', 'showSections');
                Route::get('/locationDetails/{id}', 'showDetails');
                Route::get('/mainSections', 'showMainSections');
                Route::get('/availableSections/{operator}', 'showAvailableSections');
            });
            Route::get('/ProducingCompanies',[ProducingCompanyController::class, 'ShowProducingCompanies']);
            Route::get('/CatProducts/{category_id}',[ProductController::class, 'CatProducts']);
            Route::get('/allProducts',[ProductController::class,'AllProducts']);
            Route::get('/equipmentFixes/{equipment_id}', [EquipmentFixController::class, 'showEquipmentsFixes' ]);
            Route::get('/employees/{id}',[EmployeeController::class, 'ShowBranchesEmployee']);
            Route::get('/units', [UnitController::class, 'showUnits']);
            Route::get('showShipments',[ShipmentController::class,'showShipments']);
            Route::get('ShipmentDetails/{id}',[ShipmentController::class,'ShipmentDetails']);
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