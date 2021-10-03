<?php

use App\Http\Controllers\AgentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// SALES CONTROL
Route::middleware('auth:api')->get('/', 'SaleController@dash');
Route::middleware('auth:api')->get('sales/{id}', 'SaleController@SaleDetail');
Route::middleware('auth:api')->get('sales', 'SaleController@getSales');
Route::middleware('auth:api')->get('quarter-breakdown', 'SaleController@quarterBreakdown');
Route::middleware('auth:api')->post('sales', 'AdminController@searchSales');
Route::middleware('auth:api')->post('search_production', 'SaleController@searchProduction');

// AGENT CONTROLLER
Route::middleware('auth:api')->get('agents', 'AgentController@listAgents');

// REPORTING CONTROL
Route::middleware('auth:api')->post('commission', 'ReportController@getSaleUser');

// ADMIN SECTION
Route::middleware('auth:api')->get('admin', 'AdminController@quarterBreakDown');
Route::middleware('auth:api')->get('leaderboard', 'SaleController@dash');
Route::middleware('auth:api')->post('report', 'AdminController@getReport');
Route::middleware('auth:api')->post('detail', 'AdminController@getSale');
Route::middleware('auth:api')->post('delete-sale', 'AdminController@deleteSale');
Route::middleware('auth:api')->get('all-sales', 'AdminController@getAllSales');
Route::middleware('auth:api')->post('add-sale', 'AdminController@store');
Route::middleware('auth:api')->get('profits', 'AdminController@profit');
Route::middleware('auth:api')->post('deleteSaleUser', 'AdminController@deleteSaleUser'); // Deletes agent from specific sale
Route::middleware('auth:api')->post('addCommission', 'AdminController@addCommission'); // Add commission row to existing sale
Route::middleware('auth:api')->post('updatesale', 'AdminController@updateRecord');
Route::middleware('auth:api')->get('agent_control', 'AdminController@getAgents');
Route::middleware('auth:api')->post('update_agent', 'AdminController@updateAgent');
Route::middleware('auth:api')->post('admin/changeAgentPassword', 'AdminController@updateUserPassword');

// LOGIN CONTROL
Route::post('login', 'LoginController@authenticate');
Route::get('logout', 'LogoutController@logout');
//Route::get('runscript', 'ScriptController@runScript');

// USERS CONTROL
Route::get('allDropdowns', 'UserController@getAllDropdowns');
Route::middleware('auth:api')->get('agent_titles', 'UserController@getAgentTitles');
Route::middleware('auth:api')->get('profile', 'UserController@getAgentProfile');
Route::middleware('auth:api')->post('upload_pic', 'UserController@uploadFile');
Route::middleware('auth:api')->post('add-agent', 'UserController@store');
Route::middleware('auth:api')->post('changePassword', 'UserController@changePassword');
Route::middleware('auth:api')->get('agent_profile', 'UserController@getFullProfile');
Route::middleware('auth:api')->post('update_profile', 'UserController@updateProfile');
Route::middleware('auth:api')->post('delete_agent', 'UserController@deleteAgent');

// OPTIONS CONTROL
Route::middleware('auth:api')->get('allLists', 'OptionsController@getAllLists');
Route::middleware('auth:api')->post('updateOptions', 'OptionsController@updateOptions');
