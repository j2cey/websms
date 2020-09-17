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

Route::get('/', function () {
    if (Auth::check()) {
        return view('admin01');
    }
    return redirect('/login');
});

//Route::get('/', 'SmscampaignController@index')->middleware('auth');
Route::get('/test', 'SmscampaignController@testfunction')->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('smscampaigns','SmscampaignController')->middleware('auth');
Route::resource('smscampaignfiles','SmscampaignFileController')->middleware('auth');//--model=Usuario2
Route::resource('smscampaignplanninglines','SmscampaignPlanningLineController')->middleware('auth');

Route::get('/selectmoreimportstatuses', 'SmsimportStatusController@selectmoreimportstatuses')->middleware('auth');
Route::get('/selectmoresendstatuses', 'SmssendStatusController@selectmoresendstatuses')->middleware('auth');
Route::get('/selectmoretreatmentresults', 'SmstreatmentResultController@selectmoretreatmentresults')->middleware('auth');

Route::get('/importcampaignfiles','SmscampaignController@importcampaignfiles')->middleware('auth');
