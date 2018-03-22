<?php
$ADMIN_PREFIX = "admin";
Route::model('user', 'App\Models\User');

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

Route::get('/', 'PagesController@home')->name("home");
Route::get('market', 'PagesController@market');
Route::get('market/{type?}', 'PagesController@market');
Route::get('economics', 'PagesController@economics')->name("economics");
Route::get('analyzer', 'PagesController@analyzer')->name("analyzer");
Route::get('about', 'PagesController@about')->name("about");
Route::get('contact', 'PagesController@contact')->name("contact");

Route::get('clear-cache', function () {
	$exitCode = Artisan::call('cache:clear');
	$exitCode = Artisan::call('view:clear');
	$exitCode = Artisan::call('route:clear');
	$exitCode = Artisan::call('config:clear');
	$exitCode = Artisan::call('debugbar:clear');
	return ["status" => 1, "msg" => "Cache cleared successfully!"];
});

/***************    Admin routes  **********************************/
Route::group(['prefix' => $ADMIN_PREFIX], function(){

    Route::get('/', 'admin\AdminLoginController@getLogin')->name("admin_login");
    Route::get('login', 'admin\AdminLoginController@getLogin')->name("admin_login");
    Route::post('login', 'admin\AdminLoginController@postLogin')->name("check_admin_login");

    Route::group(['middleware' => 'admin_auth'], function(){

        Route::get('logout', 'admin\AdminLoginController@getLogout')->name("admin_logout");

        Route::get('dashboard', 'admin\AdminController@index')->name("admin_dashboard");

        Route::get('change-password', 'admin\AdminController@changePassword')->name("admin_change_password");
        Route::post('change-password', 'admin\AdminController@postChangePassword')->name("admin_update_password");

        Route::get('profile', 'admin\AdminController@editProfile')->name("admin_edit_profile");
        Route::post('profile', 'admin\AdminController@updateProfile')->name("admin_update_profile");

	Route::get('user-type-rights', 'admin\AdminController@rights')->name("list-assign-rights");
	Route::post('user-type-rights', 'admin\AdminController@rights')->name("assign-rights");

	Route::any('modules/data', 'admin\AdminModulesController@data')->name('modules.data');
	Route::resource('modules', 'admin\AdminModulesController');

	Route::any('module-pages/data', 'admin\AdminModulePagesController@data')->name('module-pages.data');
	Route::resource('module-pages', 'admin\AdminModulePagesController');

	Route::any('admin-actions/data', 'admin\AdminActionController@data')->name('admin-actions.data');
	Route::resource('admin-actions', 'admin\AdminActionController');

	Route::any('admin-userlogs/data', 'admin\AdminUserLogsController@data')->name('admin-userlogs.data');
	Route::resource('admin-userlogs', 'admin\AdminUserLogsController');

	Route::any('user-actions/data', 'admin\UserActionController@data')->name('user-actions.data');
	Route::resource('user-actions', 'admin\UserActionController');

	Route::any('countries/data', 'admin\CountriesController@data')->name('countries.data');
	Route::resource('countries', 'admin\CountriesController');

	Route::any('states/data', 'admin\StatesController@data')->name('states.data');
	Route::resource('states', 'admin\StatesController');

	Route::any('cities/data', 'admin\CitiesController@data')->name('cities.data');
	Route::resource('cities', 'admin\CitiesController');
	Route::any('cities/getstates', 'admin\CitiesController@getstates')->name('getstates');

	Route::any('admin-users/data', 'admin\AdminUserController@data')->name('admin-users.data');
	Route::resource('admin-users', 'admin\AdminUserController');
	Route::get('admin-users/changepassword/{id}', 'admin\AdminUserController@changePassword');
	Route::put('admin-users/changepassword/{id}', 'admin\AdminUserController@postChangePassword');

	Route::any('users/data', 'admin\UsersController@data')->name('users.data');
 	Route::resource('users', 'admin\UsersController');

 	Route::any('blog-categories/data', 'admin\BlogCategoryController@data')->name('blog-categories.data');
 	Route::resource('blog-categories', 'admin\BlogCategoryController');

 	Route::any('blog-tags/data', 'admin\BlogTagsController@data')->name('blog-tags.data');
 	Route::resource('blog-tags', 'admin\BlogTagsController');

 	Route::any('blog-posts/data', 'admin\BlogPostsController@data')->name('blog-posts.data');
 	Route::resource('blog-posts', 'admin\BlogPostsController');

 	Route::any('cms-pages/data', 'admin\CmsPagesController@data')->name('cms-pages.data');
 	Route::resource('cms-pages', 'admin\CmsPagesController');

 	Route::any('cms-graphs/data', 'admin\CMSGraphsController@data')->name('cms-graphs.data');
 	Route::resource('cms-graphs', 'admin\CMSGraphsController');

 	Route::any('cms-graph-sliders/data', 'admin\GraphSliderContoller@data')->name('cms-graph-sliders.data');
 	Route::resource('cms-graph-sliders', 'admin\GraphSliderContoller');

 	Route::any('user-logs/data', 'admin\UserLogsController@data')->name('user-logs.data');
 	Route::resource('user-logs', 'admin\UserLogsController');

 	Route::get('uploadexcel','admin\SecuritiesController@upload')->name('uploadexcel');
    Route::post('validate','admin\SecuritiesController@validateexcel')->name('validate');
	Route::get('testes', 'admin\SecuritiesController@index')->name('listsecurity');
	Route::any('datasecurity', 'admin\SecuritiesController@data')->name('datasecurity');

    // Only for mass upload data with excel
	// Route::get('massupload', 'admin\SecuritiesController@massupload')->name('massupload');
	// Route::post('massvalidate', 'admin\SecuritiesController@massinsert')->name('massvalidate');
    });
});
