<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/clear', function () {
    Artisan::call('config:cache');
    return "Cleared!";
});

//////////////////////////////////////////// HomeController /////////////////////////////////////
Route::get('/', ['uses' =>'App\Http\Controllers\HomeController@index','as' => 'indexlogin']);
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', ['uses' => 'App\Http\Controllers\HomeController@home', 'as' => 'home']);
});
// Route::get('/login', ['uses' =>'App\Http\Controllers\HomeController@home','as' => 'login']);
Route::post('/user-login', ['uses' =>'App\Http\Controllers\HomeController@userLogin','as' => 'userlogin']);
// Route::get('/get-email-for-reset-password', ['uses' =>'App\Http\Controllers\HomeController@ForResetPassword','as' => 'forresetpassword']);
// Route::post('/forget-password', ['uses' =>'App\Http\Controllers\HomeController@forgetPassword','as' => 'forgetpassword']);
// Route::get('/reset-password/{unique_id}/{id}', ['uses' =>'App\Http\Controllers\HomeController@resetPassword','as' => 'restpassword']);
// Route::post('/change-password', ['uses' =>'App\Http\Controllers\HomeController@changePassword','as' => 'changepassword']);
Route::get('/logout', action: ['uses' =>'App\Http\Controllers\HomeController@logoutUser','as' => 'logoutuser']);

//////////////////////////////////////////// ROLES /////////////////////////////////////
Route::get('/manage-role', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\RoleController@index','as' => 'managerole']);
Route::get('/add-role', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\RoleController@create','as' => 'addrole']);
Route::post('/store-role', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\RoleController@store','as' => 'storerole']);
Route::get('/edit-role/{id}', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\RoleController@edit','as' => 'editrole']);
Route::post('/update-role', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\RoleController@update','as' => 'updaterole']);
Route::get('/destroy-role', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\RoleController@destroy','as' => 'destroyrole']);
Route::get('/change-role-status', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\RoleController@changeStatus','as' => 'changerolestatus']);
Route::post('/add-role-permission', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\RoleController@addRolePermission','as' => 'addRolePermission']);
Route::post('/role-has-permission', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\RoleController@roleHasPermission','as' => 'rolehaspermission']);

//////////////////////////////////////////// USER /////////////////////////////////////
Route::get('/manage-user', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\UserController@index','as' => 'manageuser']);
Route::get('/add-user', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\UserController@create','as' => 'adduser']);
Route::post('/store-user', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\UserController@store','as' => 'storeuser']);
Route::get('/edit-user/{id}', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\UserController@edit','as' => 'edituser']);
Route::get('/show-user/{id}', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\UserController@show','as' => 'showuser']);
Route::post('/update-user', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\UserController@update','as' => 'updateuser']);
Route::get('/destroy-user', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\UserController@destroy','as' => 'destroyuser']);
Route::get('/change-status', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\UserController@changeStatus','as' => 'changestatus']);
Route::get('/get-departments/{campus_id}', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\UserController@getDepartments','as' => 'getdepartments']);


Route::get('/get-user-curl', ['uses' =>'App\Http\Controllers\UserController@getUserCurl','as' => 'getusercurl']);
Route::get('/get-data-by-employee-id', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\UserController@getDataByEmployeeID','as' => 'getdatabyemployeeid']);
Route::get('/user-profile', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\UserController@profile','as' => 'userprofile']);
Route::post('/update-profile', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\UserController@updateProfile','as' => 'updateprofile']);

///////////////////////////////////////// Module /////////////////////////////////////
Route::get('/manage-module', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ModuleController@index','as' => 'managemodule']);
Route::get('/add-module', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ModuleController@create','as' => 'addmodule']);
Route::post('/store-module', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ModuleController@store','as' => 'storemodule']);
Route::get('/edit-module/{id}', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ModuleController@edit','as' => 'editmodule']);
Route::post('/update-module', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ModuleController@update','as' => 'updatemodule']);
Route::get('/destroy-module', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ModuleController@destroy','as' => 'destroymodule']);
Route::get('/change-module-status', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ModuleController@changeStatus','as' => 'changemodulestatus']);
Route::post('/change-module-url', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ModuleController@changeUrl','as' => 'changemoduleurl']);

//////////////////////////////////////////// Category /////////////////////////////////////
Route::get('/manage-category', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\CategoryController@index','as' => 'managecategory']);
Route::get('/add-category', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\CategoryController@create','as' => 'addcategory']);
Route::post('/store-category', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\CategoryController@store','as' => 'storecategory']);
Route::get('/edit-category/{id}', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\CategoryController@edit','as' => 'editcategory']);
Route::post('/update-category', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\CategoryController@update','as' => 'updatecategory']);
Route::get('/destroy-category', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\CategoryController@destroy','as' => 'destroycategory']);
// Route::get('/change-status-category', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\CategoryController@changeStatus','as' => 'changecategorystatus']);

//////////////////////////////////////////// Organization logo /////////////////////////////////////
Route::get('/manage-logo', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\OrganizationlogoController@index','as' => 'managelogo']);
Route::post('/update-logo', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\OrganizationlogoController@updateOrgLogo','as' => 'updateOrgLogo']);

//////////////////////////////////////////// Program /////////////////////////////////////
Route::get('/manage-program', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ProgramController@index','as' => 'manageprogram']);
Route::get('/add-program', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ProgramController@create','as' => 'addprogram']);
Route::post('/store-program', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ProgramController@store','as' => 'storeprogram']);
Route::get('/edit-program/{id}', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ProgramController@edit','as' => 'editprogram']);
Route::post('/update-program', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ProgramController@update','as' => 'updateprogram']);
Route::post('/program/import', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ProgramController@importPrograms','as' => 'programimport']);

//////////////////////////////////////////// Audit /////////////////////////////////////
Route::get('/manage-audit', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\AuditController@index','as' => 'manageaudit']);
Route::get('/add-audit', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\AuditController@create','as' => 'addaudit']);
Route::post('/store-audit', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\AuditController@store','as' => 'storeaudit']);
Route::get('/edit-audit/{id}', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\AuditController@edit','as' => 'editaudit']);
Route::post('/update-audit', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\AuditController@update','as' => 'updateaudit']);

//////////////////////////////////////////// Schedule /////////////////////////////////////
Route::get('/manage-schedule', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ScheduleController@index','as' => 'manageschedule']);
Route::get('/add-schedule', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ScheduleController@create','as' => 'addschedule']);
Route::post('/store-schedule', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ScheduleController@store','as' => 'storeschedule']);
Route::get('/edit-schedule/{id}', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ScheduleController@edit','as' => 'editschedule']);
Route::post('/update-schedule', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ScheduleController@update','as' => 'updateschedule']);
Route::get('/get-campus-details/{campusID}', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ScheduleController@getCampusDetails','as' => 'getcampusdetails']);

//////////////////////////////////////////// Checklist /////////////////////////////////////
Route::get('/manage-checklist', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ChecklistController@index','as' => 'managechecklist']);
Route::get('/add-checklist', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ChecklistController@create','as' => 'addchecklist']);
Route::post('/store-checklist', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ChecklistController@store','as' => 'storechecklist']);
Route::get('/edit-checklist/{id}', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ChecklistController@edit','as' => 'editchecklist']);
Route::post('/update-checklist', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ChecklistController@update','as' => 'updatechecklist']);

//////////////////////////////////////////// Checkitem /////////////////////////////////////
Route::get('/manage-checklistitem', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\CheckitemController@index','as' => 'managechecklistitem']);
Route::get('/add-checklistitem', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\CheckitemController@create','as' => 'addchecklistitem']);
Route::post('/store-checklistitem', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\CheckitemController@store','as' => 'storechecklistitem']);
Route::get('/edit-checklistitem/{id}', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\CheckitemController@edit','as' => 'editchecklistitem']);
Route::post('/update-checklistitem', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\CheckitemController@update','as' => 'updatechecklistitem']);
Route::post('/checklistitem/import', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\CheckitemController@importChecklistItems','as' => 'checklistitemimport']);


//////////////////////////////////////////// Headeritem /////////////////////////////////////
Route::get('/manage-headeritem', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ItemheaderController@index','as' => 'manageheaderitem']);
Route::get('/add-headeritem', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ItemheaderController@create','as' => 'addheaderitem']);
Route::post('/store-headeritem', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ItemheaderController@store','as' => 'storeheaderitem']);
Route::get('/edit-headeritem/{id}', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ItemheaderController@edit','as' => 'editheaderitem']);
Route::post('/update-headeritem', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ItemheaderController@update','as' => 'updateheaderitem']);

//////////////////////////////////////////// ShowSchedule /////////////////////////////////////
Route::get('/manage-showschedule', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ShowScheduleController@index','as' => 'manageshowschedule']);
Route::get('/show-showschedule/{id}', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ShowScheduleController@show','as' => 'viewschedule']);
// Route::post('/store-showschedule', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ShowScheduleController@store','as' => 'storeshowschedule']);
Route::post('/schedule/change-submit-type/{id}', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\ShowScheduleController@changeSubmitType','as' => 'changesubmittype']);
Route::post('/asing-checklist/{id}', ['middleware' => ['auth'],'uses' => 'App\Http\Controllers\ShowScheduleController@AsingChecklist','as' => 'asingchecklist']);
Route::post('/store-showschedule/{id}', ['middleware' => ['auth'],'uses' => 'App\Http\Controllers\ShowScheduleController@store','as' => 'storeshowschedule']);


//////////////////////////////////////////// CampusDepartment /////////////////////////////////////
Route::get('/manage-campusdepartment', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\CampusDepartmentController@index','as' => 'managecampusdepartment']);
Route::get('/add-campusdepartment', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\CampusDepartmentController@create','as' => 'addcampusdepartment']);
Route::post('/store-campusdepartment', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\CampusDepartmentController@store','as' => 'storecampusdepartment']);
Route::get('/edit-campusdepartment/{id}', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\CampusDepartmentController@edit','as' => 'editcampusdepartment']);
Route::post('/update-campusdepartment', ['middleware' => ['auth'],'uses' =>'App\Http\Controllers\CampusDepartmentController@update','as' => 'updatecampusdepartment']);
