<?php

Route::group ( [
	'prefix' => 'system/user-permissions' ,
	'before' => [
		'auth' ,
		'hasAbilities:change_user_permissions'
	]
	] , function ()
{
	Route::get ( '' , [
		'as'	 => 'system.userPermissions' ,
		'uses'	 => 'Controllers\System\UserPermissionController@home'
	] ) ;

	Route::post ( '' , [
		'as'	 => 'system.userPermissions' ,
		'uses'	 => 'Controllers\System\UserPermissionController@view'
	] ) ;
} ) ;
