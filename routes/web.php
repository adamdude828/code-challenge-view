<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () use ($router) {
    return 'home';
});

Route::get("/nodes", "NodeController@index");
Route::post("/nodes", "NodeController@create");
Route::delete("/nodes/{node_id}", "NodeController@delete");
Route::put("/nodes/{node_id}", "NodeController@edit");
Route::get("/nodes/view", "NodeController@view");

