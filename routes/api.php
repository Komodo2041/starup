<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/clients', [\App\Http\Controllers\API\ClientsController::class, 'clients']);
Route::post('/clients/add', [\App\Http\Controllers\API\ClientsController::class, 'add']);
Route::post('/clients/update/{id}', [\App\Http\Controllers\API\ClientsController::class, 'update']);
Route::delete('/clients/delete/{id}', [\App\Http\Controllers\API\ClientsController::class, 'destroy']);

Route::post('/clients/addemail/{id}', [\App\Http\Controllers\API\ClientsController::class, 'addemail']);
Route::post('/clients/removeemail/{id}', [\App\Http\Controllers\API\ClientsController::class, 'removeemail']);
Route::post('/clients/sendemail/{id}', [\App\Http\Controllers\API\ClientsController::class, 'sendEmail']);

 

 
 
 