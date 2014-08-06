<?php

Route::group ( [
	'prefix' => 'entities/items' ,
	'before' => 'auth'
] , function()
{
	Route::get ( '' , [
		'as'	 => 'entities.items.view' ,
		'before' => ['hasAbilities:view_items' ] ,
		'uses'	 => 'Controllers\Entities\ItemController@home'
	] ) ;

	Route::get ( 'add' , [
		'as'	 => 'entities.items.add' ,
		'before' => ['hasAbilities:add_item' ] ,
		'uses'	 => 'Controllers\Entities\ItemController@add'
	] ) ;

	Route::post ( 'add' , [
		'as'	 => 'entities.items.save' ,
		'before' => ['hasAbilities:add_item' ] ,
		'uses'	 => 'Controllers\Entities\ItemController@save'
	] ) ;
} ) ;
