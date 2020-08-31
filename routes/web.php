<?php

use Illuminate\Support\Facades\Route;

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

/*Route::get('/', function () {
    return view('welcome');
});*/

//Route::get('/', 'DashboardController@index')->middleware('auth');
Route::get('/', 'SmscampaignController@index')->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('smscampaigns','SmscampaignController')->middleware('auth');
Route::get('/selectmorecampaignstatuses', 'SmscampaignStatusController@selectmorecampaignstatuses')->middleware('auth');
Route::get('/importcampaignfiles','SmscampaignController@importcampaignfiles')->middleware('auth');
