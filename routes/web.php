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
Route::get('/', 'PagesController@home');
Route::get('english', 'PagesController@home');
Route::get('espanol', 'PagesController@home');

Route::get('english/markets', 'PagesController@market');
Route::get('espanol/markets', 'PagesController@market');

Route::get('english/markets/{type?}', 'PagesController@market');
Route::get('espanol/markets/{type?}', 'PagesController@market');

Route::get('english/analyzer/{type?}', 'PagesController@analyzer');
Route::get('espanol/analyzer/{type?}', 'PagesController@analyzer');

Route::get('english/about', 'PagesController@about');
Route::get('english/contact/{type?}', 'PagesController@contact');
Route::get('espanol/contact/{type?}', 'PagesController@contact');
//Route::get('english/services/{type}', 'PagesController@services');

Route::get('english/capital', 'PagesController@services');
Route::get('english/wealth', 'PagesController@services');
Route::get('english/securities', 'PagesController@services');
Route::get('english/prime', 'PagesController@services');
Route::get('english/analytics', 'PagesController@services');

Route::get('espanol/capital', 'PagesController@services');
Route::get('espanol/wealth', 'PagesController@services');
Route::get('espanol/securities', 'PagesController@services');
Route::get('espanol/prime', 'PagesController@services');
Route::get('espanol/analytics', 'PagesController@services');


Route::get('english/terms-of-uses', 'PagesController@terms_of_uses');
Route::get('english/privacy-statements', 'PagesController@privacy_statements');
Route::get('english/cookies', 'PagesController@cookies');

Route::get('espanol/about', 'PagesController@about');
Route::get('espanol/contact', 'PagesController@contact');
Route::get('espanol/services/{type}', 'PagesController@services');
Route::get('espanol/terms-of-uses', 'PagesController@terms_of_uses');
Route::get('espanol/privacy-statements', 'PagesController@privacy_statements');
Route::get('espanol/cookies', 'PagesController@cookies');

Route::get('change-language/{locale}', 'PagesController@change_locale');

Route::post('contact-form', 'PagesController@contact_form_data');
Route::post('about-form', 'PagesController@about_form_data');

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

 	Route::any('posts/data', 'admin\PostsController@data')->name('posts.data');
 	Route::resource('posts', 'admin\PostsController');

 	Route::get('getsecurities', 'admin\HomeSlidersController@getsecurities');
 	Route::get('getsecurities/banchmark', 'admin\HomeSlidersController@getsecuritiesbanchmark');
 	Route::get('getcountries/banchmark', 'admin\HomeSlidersController@getcountriesbanchmark');

 	Route::any('home-sliders/data', 'admin\HomeSlidersController@data')->name('home-sliders.data');
 	Route::resource('home-sliders', 'admin\HomeSlidersController');

 	Route::any('cms-graph-sliders/data', 'admin\GraphSliderContoller@data')->name('cms-graph-sliders.data');
 	Route::resource('cms-graph-sliders', 'admin\GraphSliderContoller');

 	Route::any('user-logs/data', 'admin\UserLogsController@data')->name('user-logs.data');
 	Route::resource('user-logs', 'admin\UserLogsController');

 	Route::get('uploadexcel','admin\SecuritiesController@upload')->name('uploadexcel');
    Route::post('validate','admin\SecuritiesController@validateexcel')->name('validate');
	Route::get('listsecurity', 'admin\SecuritiesController@index')->name('listsecurity');
	Route::any('datasecurity', 'admin\SecuritiesController@data')->name('datasecurity');
	Route::post('editsecurity/{id}', 'admin\SecuritiesController@update')->name('editsecurity');
	Route::get('edit-security-data/{id}', 'admin\SecuritiesController@edit_security_data')->name('edit-security-data');
	Route::post('update-security-data/{id}', 'admin\SecuritiesController@update_security_data')->name('update-security-data');
	Route::get('getcountries','admin\SecuritiesController@country');

	Route::any('securities/data', 'admin\SecurityController@data')->name('securities.data');
	Route::resource('securities', 'admin\SecurityController');

    // Only for mass upload data with excel
	// Route::get('massupload', 'admin\SecuritiesController@massupload')->name('massupload');
	// Route::post('massvalidate', 'admin\SecuritiesController@massinsert')->name('massvalidate');
    });
});

Route::get('english/countries', 'PagesController@economics');
Route::get('espanol/countries', 'PagesController@economics');

Route::get('english/countries/southamerica', 'PagesController@defaultCountry');
Route::get('espanol/countries/southamerica', 'PagesController@defaultCountry');

Route::get('english/countries/caribbean', 'PagesController@defaultCountry');
Route::get('espanol/countries/caribbean', 'PagesController@defaultCountry');

Route::get('english/countries/centralamerica', 'PagesController@defaultCountry');
Route::get('espanol/countries/centralamerica', 'PagesController@defaultCountry');

Route::get('english/countries/northamerica', 'PagesController@defaultCountry');
Route::get('espanol/countries/northamerica', 'PagesController@defaultCountry');

Route::get('english/{country}', 'PagesController@economics');
Route::get('espanol/{country}', 'PagesController@economics');
