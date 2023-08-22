<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BranchesProductsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderListController;
use App\Http\Controllers\OrderProductsController;

Route:: prefix('/users')->group( function (){ 

    Route::post('/login',[UserController::class, 'LogIn']);
    Route::group( ['middleware' => ['auth:user-api','scopes:user'] ],function(){
        // authenticated staff routes here 
        Route::post('/logout',[UserController::class, 'LogOut']);
        
        Route::controller(BranchController::class)->group(function(){
            Route::get('/showBranches', 'ShowBranches'); 
            Route::get('/BranchDetails/{id}', 'BranchDetails');
        });
        Route::controller(BranchesProductsController::class)->group(function(){
            Route::get('/BranchProducts/{branch_id?}', 'BranchProducts');
            Route::get('/Details/{product_id}', 'BranchProductDetails');
            Route::get('/BranchCatProducts/{branch_id}/{category_id}', 'BranchesCatProducts');
        });
            Route::controller(CategoryController::class)->group(function(){
                Route::get('/Pcategories', 'ShowParentsCategories');
                Route::get('/CHcategories/{branch_id}/{category_id}', 'ShowChildrenCategories');
                Route::get('/AllCHcategories/{branch_id}/{category_id}', 'ShowAllChildrenCategories');
                Route::get('/RootsCats', 'ShowRootsCategories'); 
                Route::get('/All', 'showAll');
            });

            Route::get('/CatProducts/{category_id}',[ProductController::class, 'CatProducts']);
            Route::get('/allProducts',[ProductController::class,'AllProducts']);
            Route::get('/BranchManagers/{role}/{branch?}',[EmployeeController::class, 'ShowBranchesManagers']);
            
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
                Route::put('/Orderd/{orderlistId}', [OrderListController::class, 'ordering']);
                Route::get('showProducts/{Order_list_id}',[OrderProductsController::class,'showOrderProducts']);
            });
    });     
});  
