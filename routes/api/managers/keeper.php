<?php

use App\Http\Controllers\Approve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BranchesCustomersController;
use App\Http\Controllers\BranchesEquipmentsController;
use App\Http\Controllers\BranchesInnerCatController;
use App\Http\Controllers\BranchesProductsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CostController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\EquipmentFixController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\InnerCategoryController;
use App\Http\Controllers\InnerTransactionController;
use App\Http\Controllers\ProducingCompanyController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderListController;
use App\Http\Controllers\OrderProductsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\StoringLocationsController;
use App\Models\BranchesCustomers;
use App\Http\Middleware\Admin;
use App\Models\BranchesEquipments;
use App\Models\Category;
use App\Models\Equipment;
use App\Models\EquipmentFix;
use App\Models\Financial;
use App\Models\InnerTransaction;
use App\Models\OrderList;
use App\Models\StoringLocations;
use Database\Factories\FinancialFactory;

Route:: prefix('/keeper')->group( function (){ 
    Route::group( ['middleware' => ['auth:manager-api','scopes:manager'] ],function(){

        Route::post('/update/{requet_id}', [Approve::class, 'updateState']);
        Route::post('/reject/{request_id}', [Approve::class, 'reject']);

        
        Route::group([ 
            'middleware'=>'keeper',], function(){
                Route::post('/Orderd', [OrderListController::class, 'ordering']);
                Route:: prefix('/Add')->group( function (){ 
                    Route::post('/newEquipment', [EquipmentController::class, 'AddNewEquipments']);
                    Route::post('/AddNewProduct',[ProductController::class,'store']);
                    Route::post('/Addcategories',[CategoryController::class, 'AddCat']);
                    Route::post('/keeperAddShipment',[ShipmentController::class, 'keeperAddShipment']);
                    Route::post('/storeProduct',[BranchesProductsController::class, 'storeProduct']);
                    Route::post('/costs', [CostController::class, 'addCost']); //N
                    Route::post('/innerTransaction', [InnerTransactionController::class, 'addInnerTransaction']); //N
                });
                Route:: prefix('/show')->group( function (){
                    Route::get('/BranchEmployees/{id}',[EmployeeController::class, 'ShowBranchesEmployee']);
                    Route::get('/BranchManagers/{branch_id}/{role_id}',[EmployeeController::class, 'ShowBranchesAssistants']);
                    Route::get('/EmployeesDetails/{emp_id}',[EmployeeController::class, 'showDetails']);
                    Route::get('showProductDetails/{id}',[ProductController::class,'showProductDetails']);
                    Route::get('showShipments',[ShipmentController::class,'showShipments']);
                    Route::get('ShipmentDetails/{id}',[ShipmentController::class,'ShipmentDetails']);
                    Route::get('showOrderLists/{id}',[OrderListController::class,'showOrderLists']);
                    Route::get('showOrderProducts/{Order_list_id}',[OrderProductsController::class,'showOrderProducts']);
                    Route::get('/costs/{type}', [CostController::class, 'ShowCosts']); //N
                    Route::get('/innerTransactions', [InnerTransactionController::class, 'ShowInnerTransaction']); //N
//showDetails
                });
                Route::prefix('/edit')->group(function(){
                    Route::post('/editProduct/{id}',[BranchesProductsController::class, 'editProduct']);
                    Route::post('/editCategory/{id}',[CategoryController::class, 'editCategory']);
                    Route::post('/editEquipment/{id}',[EquipmentController::class, 'editEquipment']);
                });
                Route::prefix('/delete')->group(function(){
                    Route::delete('/BranchEquipment/{id}',[BranchesEquipmentsController::class, 'RemoveBranchEquipment']);
                    Route::delete('/BranchProduct/{id}',[BranchesProductsController::class, 'RemoveBranchProduct']);
                    Route::delete('/financial/{id}',[FinancialController::class, 'RemoveFinancial']);
                    Route::delete('/order/{id}',[OrderController::class, 'RemoveOrder']);
                    Route::delete('/orderProducts/{id}',[OrderProductsController::class, 'RemoveOrderProduct']);
                    Route::delete('/producingCompany/{id}',[ProducingCompanyController::class, 'RemoveProducingCompany']);
                    Route::delete('/storingLocations/{main}/{section}',[StoringLocationsController::class, 'RemoveLocation']);
                });
                Route::prefix('/order')->group(function(){
                    Route::post('/new', [OrderListController::class, 'StartOrder']);
                    Route::post('/addProducts',[OrderProductsController::class, 'store']);
                    Route::post('/submit',[OrderController::class, 'order']);
                    Route::post('/ready/{id}/{ready}', [OrderController::class, 'OrderReady']);
                    Route::post('/arrived/{id}/{ready}', [OrderController::class, 'OrderArrived']);
                    Route::get('/show', [OrderController::class, 'ShowOrders']);
                    Route::get('/showByShipment/{shipment_id}', [OrderController::class, 'ShowShipmentOrders']);
                });

                Route::controller(StatisticsController::class)
                ->prefix('/statistics')
                ->group(function(){
                    Route::get('costs/{type}', 'CostsStatistics');
                    Route::get('products/In/{type}', 'InProductsStatistics');
                    Route::get('productsByproduct/In/{type}', 'InProductsByProducts');
                    Route::get('products/Out/{type}', 'OutProductsStatistics');
                    Route::get('OrderIncomings/{type}', 'ordersIncomings');
                });
            
            }
        );
    });
});
