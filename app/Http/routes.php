<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/




Route::get('/panel/Users/deleteacount/{id}', [
    'uses' => 'mycrudController@deleteacount',
    'as' => 'deleteacount.adminpanel'
]);

Route::get('/panel/Users/delete/{id}', [
    'uses' => 'mycrudController@deleteAdmin',
    'as' => 'delete.adminpanel'
]);

Route::group(['middleware' => ['web']], function () {
    Route::get('/', function () {
        return view('welcome');
    });
});


Route::group(['middleware' => 'guest'], function () {
    Route::post('/statistics/handlingStatistics', 'StatisticsController@handlingStatistics');
    Route::post('/statistics/handlingStatistics', [
        'uses' => 'StatisticsController@handlingStatistics',
        'as' => 'handlingStatistics'
    ]);
    //Route::post('/statistics/handlingSentSmiles', 'StatisticsController@handlingSentSmiles');
    Route::post('/statistics/handlingSentSmiles', [
        'uses' => 'StatisticsController@handlingSentSmiles',
        'as' => 'handlingSentSmiles'
    ]);
    //Route::post('/statistics/handlingFirstLetterSent', 'StatisticsController@handlingFirstLetterSent');
    Route::post('/statistics/handlingFirstLetterSent', [
        'uses' => 'StatisticsController@handlingFirstLetterSent',
        'as' => 'handlingFirstLetterSent'
    ]);
    //Route::post('/statistics/handlingBroadcasts', 'StatisticsController@handlingBroadcasts');
    Route::post('/statistics/handlingBroadcasts', [
        'uses' => 'StatisticsController@handlingBroadcasts',
        'as' => 'handlingBroadcasts'
    ]);
    //Route::post('/statistics/handlingresponseletters', 'StatisticsController@handlingresponseletters');
    Route::post('/statistics/handlingresponseletters', [
        'uses' => 'StatisticsController@handlingresponseletters',
        'as' => 'handlingresponseletters'
    ]);
    //Route::post('/statistics/updateStatisticsByDate', 'StatisticsController@updateStatisticsByDate');
    Route::post('/statistics/updateStatisticsByDate', [
        'uses' => 'StatisticsController@updateStatisticsByDate',
        'as' => 'updateStatisticsByDate'
    ]);
    //Route::post('/statistics/handlingBlackListInExtensions', 'StatisticsController@handlingBlackListInExtensions');
    Route::post('/statistics/handlingBlackListInExtensions', [
        'uses' => 'StatisticsController@handlingBlackListInExtensions',
        'as' => 'handlingBlackListInExtensions'
    ]);

	 Route::post('/handlingUnreadmsg/', [
        'uses' => 'unreadmsgController@handlingUnreadmsg',
        'as' => 'unreadmsg'
    ]);


	Route::post('/handlingActiveIDmsg/', [
        'uses' => 'ActiveIdController@handlingActiveIDmsg',
        'as' => 'handlingActiveIDmsg'
    ]);

});
Route::group(['middleware' => 'web'], function () {
    Route::auth();

	Route::get('panel/AllowedAdmins/index', [
        'uses' => 'AllowedAdminsController@index',
        'as' => 'AllowedAdmins'
    ]);

    Route::get('/panel/addUser', [
        'uses' => 'AllowedAdminsController@addUser',
        'as' => 'addUser'
    ]);

    Route::post('/panel/addUser', [
        'uses' => 'AllowedAdminsController@addUser',
        'as' => 'addUser'
    ]);

    Route::get('/panel/AllowedAdmins/editUser/{id}', [
        'uses' => 'AllowedAdminsController@editUser',
        'as' => 'editUser'
    ]);

    Route::get('/panel/AllowedAdmins/deleteUser/{id}', [
        'uses' => 'AllowedAdminsController@deleteUser',
        'as' => 'deleteUser'
    ]);

    Route::post('/panel/AllowedAdmins/editUser', [
        'uses' => 'AllowedAdminsController@editUser',
        'as' => 'editUser'
    ]);

    Route::get('/panel/Users/editacount/{id}', [
        'uses' => 'mycrudController@editacount',
        'as' => 'editacount.adminpanel'
    ]);

    Route::post('/panel/Users/editacount/{id}', [
        'uses' => 'mycrudController@posteditacount',
        'as' => 'posteditacount.adminpanel'
    ]);

    //Разблкировка всех аккаунтов одного админа
    Route::get('/panel/Users/blockAllID/{id}', [
        'uses' => 'mycrudController@blockAllID',
        'as' => 'blockAllID.adminpanel'
    ]);

    //Блкировка всех аккаунтов одного админа
    Route::get('/panel/Users/UnblockAllID/{id}', [
        'uses' => 'mycrudController@UnblockAllID',
        'as' => 'UnblockAllID.adminpanel'
    ]);

    /*//Блкировка всех аккаунтов одного админа
    Route::post('/panel/Users/blockacount/', [
        'uses' => 'mycrudController@blockacount',
        'as' => 'blockacount.adminpanel'
    ]);*/
    /*//Блкировка одного аккаунта у админа
    Route::post('/panel/Users/blockoneacount/{id}', [
        'uses' => 'mycrudController@blockoneacount',
        'as' => 'blockoneacount.adminpanel'
    ]);*/
    //Route::get('/home', 'HomeController@index');
    Route::get('/home', [
        'uses' => 'HomeController@index',
        'as' => 'home'
    ]);
    //Route::get('/support', 'SupportController@index');
    Route::get('/support', [
        'uses' => 'SupportController@index',
        'as' => 'indexSupport'
    ]);
    //Route::post('/support/feedbackFormsHandling', 'SupportController@feedbackFormsHandling');
    Route::post('/support/feedbackFormsHandling', [
        'uses' => 'SupportController@feedbackFormsHandling',
        'as' => 'feedbackFormsHandling'
    ]);
    //Route::get('/contacts', 'HomeController@contacts');
    Route::get('/contacts', [
        'uses' => 'AccountsController@contacts',
        'as' => 'contacts'
    ]);
    //Route::get('/accounts', 'AccountsController@index');
    Route::get('/accounts', [
        'uses' => 'AccountsController@index',
        'as' => 'indexAccount'
    ]);
    //Route::get('/accounts/edit/{item}', 'AccountsController@editaccount');
    Route::get('/accounts/edit/{item}', [
        'uses' => 'AccountsController@editaccount',
        'as' => 'editaccount'
    ]);
    //Route::post('/accounts', 'AccountsController@check');
    Route::post('/accounts', [
        'uses' => 'AccountsController@check',
        'as' => 'check'
    ]);
    //Route::post('/accounts/addaccont', 'AccountsController@addaccont');
    Route::post('/accounts/addaccont', [
        'uses' => 'AccountsController@addaccont',
        'as' => 'addaccont'
    ]);
    //Route::get('/accounts/delete/{item}', 'AccountsController@deleteaccount');
    Route::get('/accounts/delete/{item}', [
        'uses' => 'AccountsController@deleteaccount',
        'as' => 'deleteaccount'
    ]);
    //Route::post('/accounts/editAndSaveDb', 'AccountsController@editAndSaveDb');
    Route::post('/accounts/editAndSaveDb', [
        'uses' => 'AccountsController@editAndSaveDb',
        'as' => 'editAndSaveDb'
    ]);

     Route::post('/blacklist/editAndSaveToDB', [
        'uses' => 'BlacklistController@editAndSaveToDB',
        'as' => 'editAndSaveToDB'
    ]);

    //Route::post('/accounts/updateava', 'AccountsController@updateava');
    Route::get('/accounts/edit/accounts/updateava', [
        'uses' => 'AccountsController@updateava',
        'as' => 'updateava'
    ]);
    //Route::get('/payment', 'PaymentController@index');
    Route::get('/payment', [
        'uses' => 'PaymentController@index',
        'as' => 'indexPayment'
    ]);
    /*Route::get('/blacklist', 'BlacklistController@index');*/
    Route::get('/blacklist', [
        'uses' => 'BlacklistController@index',
        'as' => 'indexBlacklist'
    ]);
    //Route::post('/statistics/addStatistics', 'StatisticsController@addStatistics');
    Route::post('/statistics/addStatistics', [
        'uses' => 'StatisticsController@addStatistics',
        'as' => 'addStatistics'
    ]);
    //Route::get('/statistics', 'StatisticsController@index');
    Route::get('/statistics', [
        'uses' => 'StatisticsController@index',
        'as' => 'indexStatistics'
    ]);

    //Route::post('/addblacklist/add', 'BlacklistController@addblacklistitem');
    Route::post('/addblacklist/add', [
        'uses' => 'BlacklistController@addblacklistitem',
        'as' => 'addblacklistitem'
    ]);
    //Route::get('/addblacklist', 'BlacklistController@addblacklist');
    Route::get('/addblacklist', [
        'uses' => 'BlacklistController@addblacklist',
        'as' => 'addblacklist'
    ]);
    //Route::post('/blacklist/editAndSaveToDB/', 'BlacklistController@editAndSaveToDB');
    Route::get('/blacklist/editAndSaveToDB/', [
        'uses' => 'BlacklistController@editAndSaveToDB',
        'as' => 'editAndSaveToDB'
    ]);
    //Route::get('/blacklist/edit/{item}', 'BlacklistController@edit');
    Route::get('/blacklist/edit/{item}', [
        'uses' => 'BlacklistController@edit',
        'as' => 'editBacklist'
    ]);
    //Route::get('/blacklist/delete/{item}', 'BlacklistController@delete');
    Route::get('/blacklist/delete/{item}', [
        'uses' => 'BlacklistController@delete',
        'as' => 'deleteBacklist'
    ]);


	Route::get('/unreadmsg/', [
        'uses' => 'unreadmsgController@index',
        'as' => 'unreadmsg'
    ]);

	Route::get('/activeid/', [
        'uses' => 'ActiveIdController@index',
        'as' => 'activeid'
    ]);
	
	Route::get('/panel/addAdmin/', [
        'uses' => 'mycrudController@addAdmin',
        'as' => 'adminpanel.addAdmin'
    ]);

    Route::post('/panel/addAdmin/', [
        'uses' => 'mycrudController@addAdminPost',
        'as' => 'adminpanel.addAdmin'
    ]);

    Route::get('/panel/blockAdminPanel/{id}', [
        'uses' => 'mycrudController@blockAdminPanel',
        'as' => 'adminpanel.blockAdmin'
    ]);

    Route::get('/panel/deleteAdminPanel/{id}', [
        'uses' => 'mycrudController@deleteAdminPanel',
        'as' => 'adminpanel.deleteAdmin'
    ]);

});