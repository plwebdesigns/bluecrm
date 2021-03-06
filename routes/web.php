<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('register');

//Route::post('password/rest', 'ResetPasswordController');

Route::get('login', function () {
    return view('auth.login');
})->name('login');
Route::get('generate_pdf/{agent_name}/{production_year}', 'PDFController@generatePDF');
Route::get('detail_pdf/{id}', 'PDFController@generateSingleSalePDF');
Route::get('/', 'SaleController@index');

Route::get('add_sale', 'AddSaleController@create')->middleware('auth:api')->name('add_sale');
Route::post('add_sale/new', 'AddSaleController@store');