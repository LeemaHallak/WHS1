
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

Route:: prefix('/assistant')->group( function (){
Route::group( ['middleware' => ['auth:manager-api','scopes:manager'] ],function(){

    Route::group([ 
        'middleware'=>'assistant',], function(){
            Route:: prefix('/Add')->group( function (){
                Route::post('/exequipApprove/{equipment_id}',  [EquipmentController::class, 'AddExistingEquipmentAssis' ] );
                Route::post('/NewProduct',[ProductController::class,'storeAssis']);
                Route::post('/storeProduct',[BranchesProductsController::class,'Assistant_storeProduct']);
                Route::post('/categories',[CategoryController::class, 'approveAddCat']);
            });
//AddExistingEquipmentAssis
            Route:: prefix('/show')->group( function (){
                Route::get('/BranchEmployees/{id}',[EmployeeController::class, 'ShowBranchesEmployee']);
                Route::get('/BranchManagers/{branch_id}/{role_id}',[EmployeeController::class, 'ShowBranchesAssistants']);
                Route::get('/EmployeesDetails/{emp_id}',[EmployeeController::class, 'showDetails']);
                Route::get('showProductDetails/{id}',[ProductController::class,'showProductDetails']);
                Route::get('showShipments',[ShipmentController::class,'showShipments']);
                Route::get('ShipmentDetails/{id}',[ShipmentController::class,'ShipmentDetails']);
                Route::get('showOrderLists/{id}',[OrderListController::class,'showOrderLists']);
                Route::get('showOrderProducts/{Order_list_id}',[OrderProductsController::class,'showOrderProducts']);
        });
        Route::prefix('/edit')->group(function(){
            Route::post('/Product/{id}',[BranchesProductsController::class, 'Assistant_editProduct']);
            Route::post('/category/{id}',[CategoryController::class, 'Assistant_editCategory']);
        });
        }
    );
    });
});