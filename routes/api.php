<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/index', 'Rest\HomeController@index');
Route::get('/groups', 'Rest\GroupsController@getGroups');
Route::get('/objects', 'Rest\HomeController@getObjects');
Route::get('/games', 'Rest\GamesController@getGames');
Route::get('/games/{game}/levels', 'Rest\GamesController@getLevels');
Route::get('/games/{game}/levels/{level}/elements', 'Rest\GamesController@getTags');
Route::middleware('auth:api')->get('/who-am-i', 'Rest\HomeController@whoAmI');

// auth
Route::post('/auth/login', '\App\Http\Controllers\Rest\Auth\LoginController@login');
Route::group(['middleware' => 'auth:api'], function () {
    //institutions
    Route::get('/institutions', 'Rest\InstitutionsController@getInstitutions');
    Route::post('/institutions/childs', 'Rest\InstitutionsController@getInstitutionChilds');
    //groups
    Route::get('/groups', 'Rest\GroupsController@getGroups');
    Route::post('/groups/create', '\App\Http\Controllers\Rest\GroupsController@createGroup');
    Route::post('/groups/update', '\App\Http\Controllers\Rest\GroupsController@updateGroup');
    Route::delete('/groups/destroy', '\App\Http\Controllers\Rest\GroupsController@destroyGroup');
    Route::post('/groups/childs', 'Rest\GroupsController@getGroupChilds');
    Route::post('/groups/childs/add', 'Rest\GroupsController@addGroupChilds');
    Route::delete('/groups/childs/remove', 'Rest\GroupsController@removeGroupChilds');
    Route::post('/groups/childs/update', 'Rest\GroupsController@updateGroupChilds');
    //users
    Route::get('/users', 'Rest\UsersController@getUsers');
    Route::post('/auth/logout', '\App\Http\Controllers\Rest\Auth\LoginController@logout');
    //gamification
    Route::get('/gamifications', 'Rest\GamificationController@getGamifications');
    Route::post('/gamifications/create', 'Rest\GamificationController@createGamification');
    //certification
    Route::get('/certifications', 'Rest\CertificationController@getCertifications');
    Route::get('/certifications/{institutionId}/checkAndGrant', 'Rest\CertificationController@checkAndGrantCertification');
    //childs
    Route::post('/childs/create', '\App\Http\Controllers\Rest\ChildsController@createChild');
    Route::post('/childs/update', '\App\Http\Controllers\Rest\ChildsController@updateChild');
    Route::delete('/childs/destroy', '\App\Http\Controllers\Rest\ChildsController@destroyChild');
});
