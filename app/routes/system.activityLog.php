<?php

Route::group ( [
	'prefix' => 'system/activity-log' ,
	'before' => [
		'auth' ,
		'hasAbilities:view_activity_log'
	]
	] , function ()
{
	Route::get ( '' , [
		'as'	 => 'system.activityLog' ,
		'uses'	 => 'Controllers\System\ActivityLogController@home'
	] ) ;

	Route::post ( '' , [
		'as'	 => 'system.activityLog' ,
		'uses'	 => 'Controllers\System\ActivityLogController@view'
	] ) ;
} ) ;
