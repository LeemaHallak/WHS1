
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
                Route::post('/exequipApprove/{id}',  [EquipmentController::class, 'AddExistingEquipmentAssis' ] );
                Route::post('/productAdd_Assistant',[ProductController::class,'storeAss']);
                Route::post('/Ass_Addcategories',[CategoryController::class, 'approveAddCat']);
            });
//AddExistingEquipmentAssis
            Route:: prefix('/show')->group( function (){
        });
        }
    );
    });
});