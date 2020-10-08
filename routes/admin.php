<?php

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


/*
 * Gdzieś na świecie płacze jeden jednorożec Taylor'a
 */
Route::get('?', 'Admin\DashboardController@index')->name('moderator.dashboard');
Route::get('', 'Admin\DashboardController@index')->name('admin.dashboard');

// ustawienia
Route::get('settings', 'Settings\SettingsController@index')->name('settings.list');
Route::get('settings/{setting}', 'Settings\SettingsController@edit')->name('settings.edit');
Route::put('settings/{setting}', 'Settings\SettingsController@update')->name('settings.update');
Route::post('settings/search', 'Settings\SettingsController@search')->name('settings.search');

Route::resource('games', 'Admin\Games\GamesController');
Route::post('levelOrder', 'Admin\Games\GamesController@levelOrder');
Route::resource('levels', 'Admin\Games\LevelsController');
Route::resource('objects', 'Admin\Games\ObjectsController');
Route::resource('tags', 'Admin\Games\TagsController');

// users Routes
Route::resource('users', 'Admin\Users\UsersController');
Route::get('/users/edit/{id}', 'Admin\Users\UsersController@edit');
Route::get('users/destroy/{id}', 'Admin\Users\UsersController@destroy')->name('users.destroy');
Route::resource('avatars', 'Admin\Users\AvatarsController');

// instytucje
Route::get('institutions', 'Admin\Institutions\InstitutionsController@index')->name('institutions.list');
Route::get('institutions/add', 'Admin\Institutions\InstitutionsController@add')->name('institutions.add');
Route::put('institutions/add', 'Admin\Institutions\InstitutionsController@create')->name('institutions.create');
Route::get('institutions/{institution}/edit', 'Admin\Institutions\InstitutionsController@edit')->name('institutions.edit');
Route::put('institutions/{institution}', 'Admin\Institutions\InstitutionsController@update')->name('institutions.update');
Route::get('institutions/{institution}', 'Admin\Institutions\InstitutionsController@show')->name('institutions.show');
Route::delete('institutions/{institution}', 'Admin\Institutions\InstitutionsController@destroy')->name('institutions.destroy');
Route::get('institutions/{institution}/babysitters', 'Admin\Institutions\InstitutionsController@babysitters')->name('institutions.babysitters');
Route::get('institutions/{institution}/babysitters/add', 'Admin\Institutions\InstitutionsController@babysittersAdd')->name('institutions.babysitters.add');
Route::put('institutions/{institution}/babysitters/add', 'Admin\Institutions\InstitutionsController@babysittersCreate')->name('institutions.babysitters.create');
Route::get('institutions/{institution}/babysitters/{babysitter}', 'Admin\Institutions\InstitutionsController@babysittersShow')->name('institutions.babysitters.show');
Route::delete('institutions/{institution}/babysitters/{babysitter}', 'Admin\Institutions\InstitutionsController@babysittersDestroy')->name('institutions.babysitters.destroy');
Route::get('institutions/{institution}/childs', 'Admin\Institutions\InstitutionsController@childs')->name('institutions.childs');
Route::get('institutions/{institution}/childs/add', 'Admin\Institutions\InstitutionsController@childsAdd')->name('institutions.childs.add');
Route::put('institutions/{institution}/childs/add', 'Admin\Institutions\InstitutionsController@childsCreate')->name('institutions.childs.create');
Route::get('institutions/{institution}/childs/{child}', 'Admin\Institutions\InstitutionsController@childsShow')->name('institutions.childs.show');
Route::delete('institutions/{institution}/childs/{child}', 'Admin\Institutions\InstitutionsController@childsDestroy')->name('institutions.childs.destroy');
Route::post('institutions/babysitters', 'Admin\Institutions\InstitutionsController@getBabysitters');

// grupy
Route::get('groups', 'Admin\Groups\GroupsController@index')->name('groups.list');
Route::get('groups/add', 'Admin\Groups\GroupsController@add')->name('groups.add');
Route::put('groups/add', 'Admin\Groups\GroupsController@create')->name('groups.create');
Route::get('groups/{group}/edit', 'Admin\Groups\GroupsController@edit')->name('groups.edit');
Route::put('groups/{group}', 'Admin\Groups\GroupsController@update')->name('groups.update');
Route::get('groups/{group}', 'Admin\Groups\GroupsController@show')->name('groups.show');
Route::delete('groups/{group}', 'Admin\Groups\GroupsController@destroy')->name('groups.destroy');
Route::get('groups/{group}/childs', 'Admin\Groups\GroupsController@childs')->name('groups.childs');
Route::get('groups/{group}/childs/add', 'Admin\Groups\GroupsController@childsAdd')->name('groups.childs.add');
Route::put('groups/{group}/childs/add', 'Admin\Groups\GroupsController@childsCreate')->name('groups.childs.create');
Route::get('groups/{group}/childs/{child}', 'Admin\Groups\GroupsController@childsShow')->name('groups.childs.show');
Route::delete('groups/{group}/childs/{child}', 'Admin\Groups\GroupsController@childsDestroy')->name('groups.childs.destroy');

// stoliki
Route::get('tables', 'Admin\Tables\TablesController@index')->name('tables.list');
Route::get('tables/add', 'Admin\Tables\TablesController@add')->name('tables.add');
Route::put('tables/add', 'Admin\Tables\TablesController@create')->name('tables.create');
Route::get('tables/{table}/edit', 'Admin\Tables\TablesController@edit')->name('tables.edit');
Route::put('tables/{table}', 'Admin\Tables\TablesController@update')->name('tables.update');
Route::get('tables/{table}', 'Admin\Tables\TablesController@show')->name('tables.show');
Route::delete('tables/{table}', 'Admin\Tables\TablesController@destroy')->name('tables.destroy');

//gamifikacja
Route::get('gamification', 'Admin\Gamification\GamificationController@index')->name('gamification.list');
Route::get('gamification/destroy/{id}', 'Admin\Gamification\GamificationController@destroy')->name('gamification.destroy');
Route::get('gamification/{id}/show', 'Admin\Gamification\GamificationController@show')->name('gamification.show');
Route::get('gamification/{id}/destroy', 'Admin\Gamification\GamificationController@destroy')->name('gamification.destroy');

//certifikacja
Route::get('certification', 'Admin\Certification\CertificationController@index')->name('certification.list');
Route::get('certification/{id}/toggleActive', 'Admin\Certification\CertificationController@toggleActive')->name('certification.toggleActive');
Route::get('certification/{id}/destroy', 'Admin\Certification\CertificationController@destroy')->name('certification.destroy');
