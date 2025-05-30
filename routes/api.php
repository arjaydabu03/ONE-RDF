<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CreditController;
use App\Http\Controllers\Api\SystemController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\SubUnitController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ChargingController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ChargeSyncController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\AccountTypeController;
use App\Http\Controllers\Api\AccountUnitController;
use App\Http\Controllers\Api\AccountGroupController;
use App\Http\Controllers\Api\AccountTitleController;
use App\Http\Controllers\Api\BusinessUnitController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\NormalBalanceController;
use App\Http\Controllers\Api\DepartmentUnitController;
use App\Http\Controllers\Api\AccountSubGroupController;
use App\Http\Controllers\Api\FinancialStatementController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// api outside
Route::middleware("api.key")->group(function () {
    Route::get("charging_api", [ChargingController::class, "charging_api"]);
    Route::get("account_title_external", [
        AccountTitleController::class,
        "account_title_api",
    ]);
});

Route::group(["middleware" => ["auth:sanctum"]], function () {
    //Masterlist
    Route::apiResource("user", AccountController::class);
    Route::apiResource("system", SystemController::class);
    Route::apiResource("notification", NotificationController::class);
    Route::apiResource("category", CategoryController::class);

    //Charging of accounts masterlist
    Route::apiResource("companies", CompanyController::class);
    Route::apiResource("business_unit", BusinessUnitController::class);
    Route::apiResource("departments", DepartmentController::class);
    Route::apiResource("department_unit", DepartmentUnitController::class);
    Route::apiResource("sub_unit", SubUnitController::class);
    Route::apiResource("location", LocationController::class);
    Route::apiResource("charging", ChargingController::class);
    Route::apiResource("account_group", AccountGroupController::class);
    Route::apiResource("account_sub_group", AccountSubGroupController::class);
    Route::apiResource("account_type", AccountTypeController::class);
    Route::apiResource("account_unit", AccountUnitController::class);
    Route::apiResource("normal_balance", NormalBalanceController::class);
    Route::apiResource("sync_charging", ChargeSyncController::class);
    Route::apiResource(
        "financial_statement",
        FinancialStatementController::class
    );
    Route::apiResource("credit", CreditController::class);
    Route::apiResource("account_title", AccountTitleController::class);

    //password management

    Route::patch("change_password/{id}", [
        AccountController::class,
        "change_password",
    ]);
    Route::patch("reset_password/{id}", [
        AccountController::class,
        "reset_password",
    ]);
    //Store and get image
    Route::post("store_file", [SystemController::class, "store_file"]);
    Route::get("get_file/{id}", [SystemController::class, "get_file"]);

    Route::delete("delete_file/{id}", [SystemController::class, "delete_file"]);
    Route::post("logout", [AccountController::class, "logout"]);

    // Export
    Route::get("companies_export", [CompanyController::class, "export"]);
    Route::get("business_unit_export", [
        BusinessUnitController::class,
        "export",
    ]);

    // Import
    Route::post("companies_import", [CompanyController::class, "import"]);
    Route::post("business_unit/import", [
        BusinessUnitController::class,
        "import",
    ]);
    Route::post("department/import", [DepartmentController::class, "import"]);
    Route::post("department_unit/import", [
        DepartmentUnitController::class,
        "import",
    ]);
    Route::post("department_unit/import", [
        DepartmentUnitController::class,
        "import",
    ]);
    Route::post("sub_unit/import", [SubUnitController::class, "import"]);

    Route::post("location/import", [LocationController::class, "import"]);

    Route::post("charging/import", [ChargingController::class, "import"]);

    Route::get("sync_charge/{id}", [
        ChargeSyncController::class,
        "sync_charge",
    ]);
});
Route::post("login", [AccountController::class, "login"]);
