<?php

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {

    $api->get('/users', ['uses'=>'\App\Http\Controllers\Auth\RegisterController@users'])->middleware('auth:api');
    $api->post('/users', ['uses'=>'\App\Http\Controllers\Auth\RegisterController@registerUser']);
    $api->post('/article', ['uses'=>'\App\Http\Controllers\ArticleController@store'])->middleware(['auth:api','verified']);

    $api->put('/article/edit/{id}', ['uses'=>'\App\Http\Controllers\ArticleController@update'])->middleware(['auth:api']);
    $api->delete('/article/{id}', ['uses'=>'\App\Http\Controllers\ArticleController@destroy'])->middleware(['auth:api']);
    $api->get('/article', ['uses'=>'\App\Http\Controllers\ArticleController@index']);
    $api->get('/article/{id}', ['uses'=>'\App\Http\Controllers\ArticleController@show']);




    /*$api->get('/users', function(){
        $users  =  User::all();
        return Response::create(['users'=>$users]);
    });*/

});

//Route::get('/users', ['uses'=>'\App\Http\Controllers\Auth\RegisterController@users'])->middleware('auth:api');


Route::middleware('auth:api')->get('test', function (){
    $data  =  ['one', 'two', 'three'];
   return Response::create($data, 404);
});



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
