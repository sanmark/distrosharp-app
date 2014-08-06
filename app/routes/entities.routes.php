<?php

Route::group ( [
	'prefix' => 'entities/routes' ,
	'before' => 'auth'
] , function()
{
	Route::get('',[
		'as'=>'entities.routes.view',
		'before'=>['hasAbilities:view_routes'],
		'uses'=>'Controllers\Entities\RouteController@home'
	]);
	Route::get ( 'add' , [
		'as'	 => 'entities.routes.add' ,
		'before' => ['hasAbilities:add_route' ] ,
		'uses'	 => 'Controllers\Entities\RouteController@add'
	] ) ;
	Route::post ( 'add' , [
		'as'	 => 'entities.routes.save' ,
		'before' => ['hasAbilities:add_route' ] ,
		'uses'	 => 'Controllers\Entities\RouteController@save'
	] ) ;
} ) ;
