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

Route::prefix('/GeneralManager')->group(function(){
    Route::group( ['middleware' => ['auth:manager-api','scopes:manager'] ],function(){
        Route::group([ 
            'middleware'=>'general_manager',], function(){
                Route:: prefix('/Add')->group( function (){
                    Route::post('/exequipApprove/{id}', [EquipmentController::class, 'AddExistingEquipmentAssis' ] );
                    Route::post('/Branch',[BranchController::class, 'store']); 
                    Route::post('/AddNewProduct',[ProductController::class,'store']);
                    Route::post('/Addcategories',[CategoryController::class, 'AddCat']);
                    Route::post('/costs', [CostController::class, 'addCost']); //N
                    Route::post('/innerTransaction', [InnerTransactionController::class, 'addInnerTransaction']); //N
                    Route::post('/Shipment',[ShipmentController::class, 'store']);
                    Route::post('/newEquipment', [EquipmentController::class, 'AddNewEquipments']);
                    Route::post('/customer',[UserController::class, 'AddCustomer']);
                    Route::post('/employees',[EmployeeController::class, 'AddEmployee']);
                    Route::post('/financial', [FinancialController::class, 'store']);
                });
                Route:: prefix('/show')->group( function (){
                    Route::get('/BranchEmployees/{id}',[EmployeeController::class, 'ShowBranchesEmployee']);
                    Route::get('/employees',[EmployeeController::class, 'ShowEmployees']);
                    Route::get('/AllProducts',[ProductController::class, 'AllProducts']);
                    Route::get('/equipments',[EquipmentController::class, 'ShowAllEquipments']);
                    Route::get('/equipmentsCosting/{fixingCost}',[EquipmentController::class, 'showAllCosts']);
                    Route::get('/financial',[FinancialController::class, 'ShowMonthlyF']);
                });
                Route::prefix('/delete')->group(function(){
                    Route::post('/deleteBranch/{id}',[BranchController::class, 'deleteBranch']);
                    Route::delete('/category/{id}',[CategoryController::class, 'RemoveCat']);
                    Route::delete('/employee/{id}',[EmployeeController::class, 'RemoveEmployee']);
                    Route::delete('/equipment/{id}',[EquipmentController::class, 'RemoveEquipment']);
                    Route::delete('/financial/{id}',[FinancialController::class, 'RemoveFinancial']);
                    Route::delete('/order/{id}',[OrderController::class, 'RemoveOrder']);
                    Route::delete('/producingCompany/{id}',[ProducingCompanyController::class, 'RemoveProducingCompany']);
                    Route::delete('/shipment/{id}',[ShipmentController::class, 'RemoveShipment']);
                    Route::delete('/customer/{id}',[UserController::class, 'RemoveCustomer']);
                    Route::delete('/product/{id}',[ProductController::class, 'RemoveProduct']);
                });
            }
        );
    });
});

