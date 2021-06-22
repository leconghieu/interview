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

Route::get('/', function () {
    return view('welcome');
});


Route::get('login', function(){
    return view('login');
});

Route::get('main-table', function() {
    return view('table_edit');
})->name('maintable');

Route::group(['prefix' => 'main-table', 'middleware' => ['jwt.verify']], function() {
    Route::post('edit', 'App\Http\Controllers\MainTableController@edit')->name('maintable.edit');
    Route::post('mass-edit', 'App\Http\Controllers\MainTableController@massEdit')->name('maintable.massedit');
    Route::get('search', 'App\Http\Controllers\MainTableController@search')->name('maintable.search');
});
