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
use App\Http\Controllers\DeletionsController;
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
        Route::group([ 
            'middleware'=>'keeper',], function(){
                Route:: prefix('/Add')->group( function (){ 
                    Route::post('/newEquipment', [EquipmentController::class, 'AddNewEquipments']);
                    Route::post('/AddNewProduct',[ProductController::class,'store']);
                    Route::post('/Addcategories',[CategoryController::class, 'AddCat']);
                    Route::post('/AddShipment',[ShipmentController::class, 'keeperAddShipment']);
                    Route::post('/storeProduct',[BranchesProductsController::class, 'storeProduct']);
                    Route::post('/costs', [CostController::class, 'addCost']); //N
                    Route::post('/innerTransaction', [InnerTransactionController::class, 'addInnerTransaction']); //N
                    Route::post('/addlocation',[StoringLocationsController::class, 'store']); 
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
                });
                Route::prefix('/edit')->group(function(){
                    Route::post('/editProduct/{id}',[BranchesProductsController::class, 'editProduct']);
                    Route::post('/editCategory/{id}',[CategoryController::class, 'editCategory']);
                    Route::post('/editEquipment/{id}',[EquipmentController::class, 'editEquipment']);
                });
                Route::prefix('/delete')->group(function(){
                    Route::controller(DeletionsController::class)->group(function(){
                        Route::delete('/BranchEquipment/{id}', 'RemoveBranchEquipment');
                        Route::delete('/BranchProduct/{id}', 'RemoveBranchProduct');
                        Route::delete('/financial/{id}', 'RemoveFinancial');
                        Route::delete('/order/{id}', 'RemoveOrder');
                        Route::delete('/orderProducts/{id}', 'RemoveOrderProduct');
                        Route::delete('/producingCompany/{id}', 'RemoveProducingCompany');
                        Route::delete('/storingLocations/{main}/{section}', 'RemoveLocation');
                    });
                });
                Route::prefix('/order')->group(function(){
                    Route::controller(OrderController::class)->group(function(){
                        Route::post('/submit', 'order');
                        Route::post('/ready/{id}/{ready}', 'OrderReady');
                        Route::post('/arrived/{id}/{ready}', 'OrderArrived');
                        Route::get('/show', 'ShowOrders');
                        Route::get('/showByShipment/{shipment_id}', 'ShowShipmentOrders');
                    });
                    Route::post('/new', [OrderListController::class, 'StartOrder']);
                    Route::post('/addProducts',[OrderProductsController::class, 'store']);
                    Route::post('/Orderd', [OrderListController::class, 'ordering']);
                });
                Route::controller(StatisticsController::class)
                ->prefix('/statistics')
                ->group(function(){
                    Route::get('costs/{type}', 'CostsStatistics');
                    Route::get('products/In/{type}', 'InProductsStatistics');
                    Route::get('productsByproduct/In/{type}', 'InProductsByProducts');
                    Route::get('products/Out/{type}', 'OutProductsStatistics');
                    Route::get('OrderIncomings/{type}', 'ordersIncomings');
                    Route::get('/earnings', 'earningsStatistics');
                });
                Route::controller(Approve::class)->group(function(){
                    Route::post('/update/{requet_id}', 'updateState');
                    Route::post('/reject/{request_id}', 'reject');
                }); 
            }
        );
    });
});
