<?php

Route::group ( [
	'prefix' => 'entities/routes' ,
	'before' => 'auth'
] , function()
{
	Route::get ( 'add' , [
		'as'	 => 'entities.routes.add' ,
		'uses'	 => 'Controllers\Entities\RouteController@add'
	] ) ;
	Route::post ( 'add' , [
		'as'	 => 'entities.routes.save' ,
		'uses'	 => 'Controllers\Entities\RouteController@save'
	] ) ;
} ) ;
