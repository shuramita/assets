<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/asset')
    ->middleware(['auth:api','cors','ass_check'])
    ->namespace('Shura\Asset\Controllers\API')
    ->group(function () {
        Route::get('/system-info','AssetController@info')->name('user.get.asset.system-info');
    });
Route::prefix('api/asset/system')
    ->namespace('Shura\Asset\Controllers\API')
    ->group(function () {
        Route::get('/info','SystemController@info')->name('user.get.asset.system.info');
    });
Route::prefix('api/asset')
    ->middleware(['auth:api','cors','org_check:api,organization,business-unit'])
    ->namespace('Shura\Asset\Controllers\API')
    ->group(function () {
        Route::post('/','AssetController@add')->name('user.add.asset');
        Route::post('/field','AssetController@addField')->name('user.add.asset.field');
        Route::put('/','AssetController@update')->name('user.update.asset');
        Route::get('/{id}','AssetController@detail')->name('user.get.asset.detail')->where('id', '[0-9]+');
        Route::get('/search','AssetController@search')->name('user.search.asset');
    });
Route::prefix('api/asset/public')
    ->middleware(['cors'])
    ->namespace('Shura\Asset\Controllers\API')
    ->group(function () {
        Route::get('/search/','AssetController@searchMarketPlace')->name('user.search.asset.public');
        Route::get('/info/{id}','AssetController@detailForMarket')->name('user.get.asset.detail.public');
    });
Route::prefix('api/asset/price')
    ->middleware(['auth:api','cors','ass_check:api,organization,building'])
    ->namespace('Shura\Asset\Controllers\API')
    ->group(function () {
        Route::post('/add','Price@add')->name('user.add.asset.price');
        Route::post('/update','Price@update')->name('user.update.asset.price');
        Route::get('/info','Price@info')->name('user.info.asset.price');
        Route::post('/calculate','Price@calculate')->name('user.info.asset.calculate.price');
    });

Route::prefix('api/asset/booking')
    ->middleware(['cors'])
    ->namespace('Shura\Asset\Controllers\API')
    ->group(function () {
        Route::post('/','BookingController@book')->name('user.submit.asset.venue.booking');
        Route::get('/{booking}','BookingController@info')->name('user.get.asset.booking.detail')->where(['booking'=>'[0-9]+'])->middleware(['auth:api','cors',"ass_check:api,organization"]);
        Route::post('/approve/{booking}','BookingController@approve')->name('user.approve.asset.booking')->where(['booking'=>'[0-9]+'])->middleware(['auth:api','cors',"ass_check:api,organization"]);
        Route::post('/reject/{booking}','BookingController@reject')->name('user.approve.asset.booking')->where(['booking'=>'[0-9]+'])->middleware(['auth:api','cors',"ass_check:api,organization"]);
        Route::get('/search','BookingController@search')->name('user.search.asset.venue.booking')->middleware(['auth:api','cors',"ass_check:api,organization"]);
    });


Route::prefix('asset')
    ->middleware(['web'])
    ->namespace('Shura\Asset\Controllers')
    ->group(function () {
        Route::any('{all?}', function($slug = null){
            return view('Asset::index-spa');
        })->where(['all' => '.*'])->name('asset.index');
    });

Route::prefix('asset/setting')
    ->middleware(['web'])
    ->namespace('Shura\Asset\Controllers')
    ->group(function () {
        Route::any('{all?}', function($slug = null){
            return view('Asset::index-spa');
        })->where(['all' => '.*'])->name('asset.setting');
    });
