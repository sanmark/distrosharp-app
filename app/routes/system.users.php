<?php

Route::group ( [
	'prefix' => 'system/users' ,
	'before' => [
		'auth' ,
		'hasAbilities:super_admin'
	]
	] , function ()
{
	Route::get ( 'add' , [
		'as'	 => 'system.addNewUser' ,
		'uses'	 => 'Controllers\System\SettingsController@addUser'
	] ) ;
	Route::post ( 'add' , [
		'as'	 => 'system.addNewUser' ,
		'uses'	 => 'Controllers\System\SettingsController@saveUser'
	] ) ;
} ) ;
