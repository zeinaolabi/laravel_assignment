<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\exercisesController;



Route::group(["prefix" => "exercises"], function() {
    Route::get("/sort_string/{string}", [exercisesController::class, 'sortString']);


});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
