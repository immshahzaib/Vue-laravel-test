<?php

use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\EmployeeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "user" middleware group. Now create something great!
|
*/

Route::resource("/employee", EmployeeController::class);
Route::get("/employee/{id}/current/employee", [EmployeeController::class, 'getCurrentEditEmployee']);

Route::resource("/company", CompanyController::class);
Route::get("/company/{id}/current/company", [CompanyController::class, 'getCurrentEditCompany']);
Route::get("/company-list", [CompanyController::class, 'companyList']);

