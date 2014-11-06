<?php

Route::group ( [
	'before' => 'guest'
	] , function()
{
	Route::get ( 'login/{superAdminLoginToken?}' , [
		'as'	 => 'account.login' ,
		'uses'	 => 'UserController@login'
	] ) ;

	Route::post ( 'login/{superAdminLoginToken?}' , [
		'as'	 => 'account.login' ,
		'uses'	 => 'UserController@pLogin'
	] ) ;
} ) ;

Route::group ( [
	'before' => 'auth'
	] , function()
{
	Route::get ( 'logout' , [
		'as'	 => 'account.logout' ,
		'uses'	 => 'UserController@logout'
	] ) ;
} ) ;
