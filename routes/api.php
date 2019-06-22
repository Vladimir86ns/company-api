<?php

use Dingo\Api\Routing\Router;

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

$api = app(Router::class);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\Http\Controllers'], function (Router $api) {
        $api->group(['prefix' => 'account'], function ($api) {
            $api->get('login', 'Auth\LoginController@login');
            $api->post('create', 'Auth\RegisterController@createAccountAndUser');
        });
    });

//    $api->group(['namespace' => 'App\Http\Controllers', 'middleware' => 'jwt.verify'], function (Router $api) {
    $api->group(['namespace' => 'App\Http\Controllers'], function (Router $api) {
        // USER
        $api->group(['prefix' => 'user'], function ($api) {
            $api->get('/{id}/{accountId}', 'UserController@getUser');
            $api->post('update', 'UserController@update');
        });

        // COMPANY
        $api->group(['prefix' => 'company'], function ($api) {
            $api->get('/{id}/{accountId}', 'CompanyController@getCompanyByIdAndAccountId');
            $api->post('create', 'CompanyController@create');
            $api->patch('update', 'CompanyController@update');
            $api->get('/{companyId}/account/{accountId}', 'CompanyController@getEmployeesByCompany');
        });

        // EMPLOYEE
        $api->group(['prefix' => 'employee'], function ($api) {
            $api->post('create', 'EmployeeController@create');
            $api->get('{id}/{companyId}', 'EmployeeController@getEmployee');
        });
    });
});
