<?php

use App\Models\Equipment;
use App\Models\Financial;
use App\Models\EquipmentFix;
use Illuminate\Http\Request;
use App\Http\Middleware\Admin;
use App\Http\Controllers\Approve;
use App\Models\BranchesCustomers;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BranchController;
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
use App\Http\Controllers\CostController;
use App\Http\Controllers\DeletionsController;
use App\Http\Controllers\InnerTransactionController;
use App\Http\Controllers\StatisticsController;
use App\Models\Employee;

Route::prefix('/GeneralManager')->group(function(){
    Route::group( ['middleware' => ['auth:manager-api','scopes:manager'] ],function(){
        Route::group([ 
            'middleware'=>'general_manager',], function(){
<<<<<<< HEAD
=======
                Route::put('/arrive/{id}', [ShipmentController::class, 'shipmentArrive']);
>>>>>>> c49dff98 (neew)
                Route:: prefix('/Add')->group( function (){
                    Route::post('/Branch',[BranchController::class, 'store']); 
                    Route::post('/NewProduct',[ProductController::class,'store']);
                    Route::post('/Category',[CategoryController::class, 'AddCat']);
                    Route::post('/costs', [CostController::class, 'addCost']); //NO
                    Route::post('/innerTransaction', [InnerTransactionController::class, 'addInnerTransaction']); 
                    Route::post('/Shipment',[ShipmentController::class, 'store']);
                    Route::post('/newEquipment', [EquipmentController::class, 'AddSysEquipment']);
                    Route::post('/customer',[UserController::class, 'AddCustomer']); 
                    Route::post('/employees',[EmployeeController::class, 'AddEmployee']);
                    Route::post('/financial', [FinancialController::class, 'store']);
                    Route::post('producingCompany', [ProducingCompanyController::class, 'store']);
                });
                Route:: prefix('/show')->group( function (){
                    Route::get('/BranchEmployees/{id}',[EmployeeController::class, 'ShowBranchesEmployee']);
                    Route::get('/costs/{type?}', [CostController::class, 'showCosts']); //NO
                    Route::get('/innerTransactions/{sourceBranch_id?}', [InnerTransactionController::class, 'showInnerTransaction']); 
                    Route::get('/allEquipmentFixes', [EquipmentFixController::class, 'showAllFixes' ]);
                    Route::get('/Products/{branch_id?}', [BranchesProductsController::class,'BranchProducts']);
                    Route::get('showProductDetails/{id}',[ProductController::class,'showProductDetails']);
                    Route::get('/EmployeesDetails/{emp_id}',[EmployeeController::class, 'showDetails']);
                    Route::get('/BranchManagers/{role}/{branch?}',[EmployeeController::class, 'ShowBranchesManagers']);
                    Route::get('/allManagers/{id?}', [EmployeeController::class, 'ShowAllBranchesManagers']);
                    Route::get('/employees',[EmployeeController::class, 'ShowEmployees']);
                    Route::get('/AllProducts',[ProductController::class, 'AllProducts']);
                    Route::get('/equipments',[EquipmentController::class, 'ShowAllEquipments']);
                    Route::get('/equipmentsCosting/{fixingCost}',[EquipmentController::class, 'showAllCosts']);
                    Route::get('/financial/{month}',[FinancialController::class, 'ShowMonthlyFinancials']);
                    Route::get('/allFinancials',[FinancialController::class, 'ShowAllFinancials']);
                    Route::get('/customers/{getBy?}',[UserController::class, 'showCustomers']);
                    Route::get('showOrderLists/{id?}',[OrderListController::class,'showOrderLists']);
                    Route::get('showOrderProducts/{OrderList_id}',[OrderProductsController::class,'showOrderProducts']); 
                    Route::controller(CategoryController::class)->group(function(){
                        Route::get('/Pcategories', 'ShowParentsCategories');
                        Route::get('/CHcategories/{category_id}', 'GeneralChildrenCategories');
                        Route::get('/RootsCats', 'ShowRootsCategories'); 
                    });
                });
                Route::prefix('/delete')->group(function(){
                    Route::controller(DeletionsController::class)->group(function(){
                        Route::delete('/Branch/{id}', 'deleteBranch');
                        Route::delete('/product/{id}', 'RemoveProduct');
                        Route::delete('/category/{id}','RemoveCat');
                        Route::delete('/employee/{id}', 'RemoveEmployee');
                        Route::delete('/equipment/{id}', 'RemoveEquipment');
                        Route::delete('/shipment/{id}', 'RemoveShipment');
                        Route::delete('/customer/{id}', 'RemoveCustomer');
                        Route::delete('/producingCompany/{id}', 'RemoveProducingCompany');
                        Route::delete('/financial/{id}',[FinancialController::class, 'RemoveFinancial']);
                        Route::delete('/order/{id}',[OrderController::class, 'RemoveOrder']);
                    });
                });

                Route::prefix('/order')->group(function(){
                    Route::controller(OrderController::class)->group(function(){
                        Route::post('/submit', 'order');
                        Route::post('/ready/{id}/{ready}', 'OrderReady');
                        Route::post('/arrived/{id}/{ready}', 'OrderArrived');
                        Route::get('/show', 'ShowOrders');
                        Route::get('/showByShipment/{shipment_id}/{branch_id?}', 'ShowShipmentOrders');
                    });
                    Route::post('/new', [OrderListController::class, 'StartOrder']);
                    Route::post('/addProducts',[OrderProductsController::class, 'store']);
                    Route::post('/Orderd', [OrderListController::class, 'ordering']);
                });
                Route::controller(StatisticsController::class)
                ->prefix('/statistics')
                ->group(function(){
                    Route::get('costs/{type}/{branch_id?}', 'CostsStatistics');
                    Route::get('products/In/{type}/{branch_id?}', 'InProductsStatistics');
                    Route::get('productsByproduct/In/{type}/{product_id}/{date}/{branch_id?}', 'InProductsByProducts');
                    Route::get('products/Out/{type}/{branch_id?}', 'OutProductsStatistics');
                    Route::get('OrderIncomings/{type}/{branch_id?}', 'ordersIncomings');
                    Route::get('/earnings/{branch_id?}', 'earningsStatistics');
                    Route::get('productsBySupplier/In/{type}/{branch_id?}', 'InProductsBySupplier');
                    Route::get('/bestCatQuantities/{year}/{month?}/{branch_id?}', 'BestCatQuantities');
                    Route::get('/bestCatEarnings/{year}/{month?}/{branch_id?}', 'BestCatEarnings');
                    Route::get('/bestCustomer/{branch_id? }', 'BestCustomer');
                    Route::get('/bestBranch/{year}/{month?}', 'BestBranch');
                });
                Route::controller(Approve::class)->group(function(){
                    Route::post('/update/{requet_id}', 'updateState');
                    Route::post('/reject/{request_id}', 'reject');
                }); 
            }
        );
    });
});

