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
    

Route::post('mobileLogin', 'CustomAuthController@mobileLogin');
Route::post('mobile', 'MobileController@index');
Route::post('mobileNews', 'MobileController@news');
Route::post('mobileFollowsubject', 'MobileController@followsubject');
Route::post('mobileStatusfollow', 'MobileController@statusfollow');
Route::post('mobilefeedNews', 'MobileController@feedNews');
Route::post('mobileSearch', 'MobileController@mobileSearch');
Route::post('mobileLeave', 'MobileController@leave');
Route::post('mobileNotifications', 'MobileController@notifications');
Route::post('mobilereadallNotifications', 'MobileController@readallnotifications');
Route::post('mobilestatusActivitys', 'MobileController@statusactivitys');
Route::post('roles', 'MobileController@roles');
Route::post('showleave', 'MobileController@showleave');
Route::post('teacherNews', 'MobileController@teacherNews');
Route::post('teacherComment', 'MobileController@teacherComment');
Route::post('teacherSubject', 'MobileController@teacherSubject');
Route::post('teacherRequest', 'MobileController@teacherRequest');
Route::post('teacherEject', 'MobileController@teacherEject');
Route::post('teacherAccept', 'MobileController@teacherAccept');
Route::post('mobileDelete', 'MobileController@mobileDelete');
Route::post('mobilecodeSubject', 'MobileController@mobilecodeSubject');

Auth::routes();
Route::post('login', 'CustomAuthController@login');

Route::get('/', 'HomeController@index');

Route::group(['middleware' => ['auth', 'acl'], 'is' => 'student|teacher|admin'], function () {
    Route::get('showstatus', 'SubjectController@status');
    Route::get('search', 'SubjectController@search');
    Route::get('markasRead', 'SubjectController@mark');
    Route::get('/home', 'SubjectController@index');
    Route::resource('user', 'UserController');
    Route::get('teacher', 'UserController@roleTeacher')->name('user.teacher');
    Route::get('admin', 'UserController@roleAdmin')->name('user.admin');
    Route::get('follow/{id}', 'SubjectController@follow');
    Route::get('activity', 'SubjectController@status');
    Route::get('activity/create/{id}', 'SubjectController@createLeave');
    Route::post('leave/{id}', 'SubjectController@leave');
    Route::resource('subject', 'SubjectController');
    Route::get('activity/delete/{id}','SubjectController@deletes');
    Route::get('status', 'SubjectController@adminStatus');
    Route::get('eject/{id}', 'SubjectController@ejectLeave');
    Route::get('accept/{id}', 'SubjectController@acceptLeave');
    Route::get('activity/edit/{id}', 'SubjectController@editActivity');
    Route::get('student', 'UserController@roleStudent')->name('user.student');
    Route::post('postnews/{id}', 'SubjectController@postNews');
    Route::get('teacher', 'UserController@roleTeacher')->name('user.teacher');
    Route::get('admin', 'UserController@roleAdmin')->name('user.admin');
    Route::post('deleteall', 'SubjectController@deleteAll');
    Route::post('acceptall', 'SubjectController@acceptAll');
    Route::post('ejectall', 'SubjectController@ejectAll');
    Route::get('deletenews/{id}', 'SubjectController@deleteNews');
    Route::get('requests', 'SubjectController@reqs');
    Route::get('subjectFollow', 'SubjectController@subjectFollow');
    Route::get('ownerSubject', 'SubjectController@ownerSubject');
    
});

Route::group(['middleware' => ['auth', 'acl'], 'is' => 'teacher|admin'], function () {
    
    //Route::resource('subject', 'SubjectController');
    // Route::get('subject.create', 'SubjectController@create');
});

Route::group(['middleware' => ['auth', 'acl'], 'is' => 'student|admin'], function () {
    
});

Route::group(['middleware' => ['auth', 'acl'], 'is' => 'teacher'], function () {
    
});

Route::group(['middleware' => ['auth', 'acl'], 'is' => 'admin'], function () {
    //Route::resource('user', 'UserController');
    //Route::resource('subject', 'SubjectController');
    Route::resource('section', 'SectionController');
    
    //Route::get('teacher', 'UserController@roleTeacher')->name('user.teacher');
    //Route::get('admin', 'UserController@roleAdmin')->name('user.admin');
    
    
    
    
});