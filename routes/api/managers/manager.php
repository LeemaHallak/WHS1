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
use App\Http\Controllers\InnerTransactionController;
use App\Models\Category;
use App\Models\Manager;
use Illuminate\Support\Facades\Auth;

Route:: prefix('/managers')->group( function (){
    Route::post('/register',[EmployeeController::class, 'register']);
    Route::post('/login',[ManagerController::class, 'LogIn']);
    Route::group( ['middleware' => ['auth:manager-api','scopes:manager'] ],function(){
    Route::get('/branch', [ManagerController::class, 'branch']);
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
                Route::get('/productTransition/{productId}', 'ProductTransition');
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
                Route::get('/All', 'showAll');
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
                Route::get('/allSections/{operator}', 'showAllSections');
            });
            Route::controller(EmployeeController::class)->group(function(){
                Route::get('/EmployeesDetails/{emp_id}', 'showDetails');
                Route::get('/BranchManagers/{role}/{branch?}', 'ShowBranchesManagers');
                Route::get('/allManagers/{id?}', 'ShowAllBranchesManagers');
                Route::get('/employees', 'ShowEmployees');
                Route::get('/BranchEmployees/{id}','ShowBranchesEmployee');
            });
            Route::controller(ProductController::class)->group(function(){
                Route::get('/CatProducts/{category_id}', 'CatProducts');
                Route::get('/allProducts','AllProducts');
                Route::get('showProductDetails/{id}','showProductDetails');
            });
            Route::get('showOrderLists/{id?}',[OrderListController::class,'showOrderLists']);
            Route::get('showOrderProducts/{Order_list_id}',[OrderProductsController::class,'showOrderProducts']);
            Route::get('/costs/{type?}', [CostController::class, 'ShowCosts']); //N
            Route::get('/innerTransactions/{sourceBranchId?}', [InnerTransactionController::class, 'ShowInnerTransaction']); //N
            Route::get('/ProducingCompanies',[ProducingCompanyController::class, 'ShowProducingCompanies']);
            Route::get('/equipmentFixes/{equipment_id}', [EquipmentFixController::class, 'showEquipmentsFixes' ]);
            Route::get('/units', [UnitController::class, 'showUnits']);
            Route::get('/showShipments',[ShipmentController::class,'showShipments']);
            Route::get('/ShipmentDetails/{id}',[ShipmentController::class,'ShipmentDetails']);
            Route::get('/requests', [Approve::class, 'showRequests']);
        });
        Route::prefix('/Add')->group(function(){
            Route::post('/units', [UnitController::class, 'addUnits']);
        });
        Route::controller(Approve::class)->group(function(){
            Route::post('/update/{requet_id}', 'updateState');
            Route::post('/reject/{request_id}', 'reject');
        }); 
    });
});  