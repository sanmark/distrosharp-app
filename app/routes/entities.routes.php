<?php

Route::group ( [
	'prefix' => 'entities/routes' ,
	'before' => 'auth'
] , function()
{
	Route::get ( 'add' , [
		'as'	 => 'add-route' ,
		'uses'	 => 'Controllers\Entities\RouteController@add'
	] ) ;
	Route::post ( 'add' , [
		'as'	 => 'save-route' ,
		'uses'	 => 'Controllers\Entities\RouteController@save'
	] ) ;
} ) ;
