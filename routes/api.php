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

    // THIS IS ONLY FOR DEV
    $withMiddleware = ['namespace' => 'App\Http\Controllers', 'middleware' => 'jwt.verify'];
    $withOutMiddleware = ['namespace' => 'App\Http\Controllers'];


    $api->group(['namespace' => 'App\Http\Controllers'], function (Router $api) {
        $api->group(['prefix' => 'account'], function ($api) {
            $api->get('login', 'Auth\LoginController@login');
            $api->post('create', 'Auth\RegisterController@createAccountAndUser');
        });
    });

    $api->group($withOutMiddleware, function (Router $api) {

        // ACCOUNT    account/
        $api->group(['prefix' => 'account'], function ($api) {

            // USER   account/user
            $api->group(['prefix' => 'user'], function ($api) {
                $api->post('update', 'UserController@update');
            });

            // COMPANY    account/company
            $api->group(['prefix' => 'company'], function ($api) {
                $api->post('create', 'CompanyController@create');
                $api->patch('update', 'CompanyController@update');

                // EMPLOYEE    account/company/employee
                $api->group(['prefix' => 'employee'], function ($api) {
                    $api->post('create', 'EmployeeController@create');
                    $api->patch('update', 'EmployeeController@update');
                });
            });

            // ACCOUNT ID    account/{accountId}
            $api->group(['prefix' => '/{accountId}'], function ($api) {

                // USER    account/{accountId}/user
                $api->group(['prefix' => '/user'], function ($api) {
                    $api->get('{userId}', 'UserController@getUser');
                });

                // COMPANY    account/{accountId}/company
                $api->group(['prefix' => 'company'], function ($api) {

                    // COMPANY ID    account/{accountId}/company/{companyId}
                    $api->group(['prefix' => '{companyId}'], function ($api) {
                        $api->get('/', 'CompanyController@getCompany');
                        $api->get('/employees', 'CompanyController@getEmployeesByCompany');

                        // EMPLOYEE    account/{accountId}/company/{companyId}/employee
                        $api->group(['prefix' => 'employee'], function ($api) {
                            $api->get('{employeeId}', 'EmployeeController@getEmployee');
                            $api->delete('{employeeId}/delete', 'EmployeeController@delete');
                        });
                    });
                });
            });
        });
    });
});
