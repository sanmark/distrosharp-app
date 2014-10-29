<?php

Route::group ( [
	'prefix' => 'account/settings' ,
	'before' => 'auth'
] , function()
{
	Route::get ( '' , [
		'as'	 => 'account.settings' ,
		'uses'	 => 'Controllers\Account\SettingsController@showSettings'
	] ) ;

	Route::post ( 'basic' , [
		'as'	 => 'account.settings.basic' ,
		'uses'	 => 'Controllers\Account\SettingsController@updateBasic'
	] ) ;

	Route::post ( 'password' , [
		'as'	 => 'account.settings.password' ,
		'uses'	 => 'Controllers\Account\SettingsController@updatePassword'
	] ) ;
} ) ;
