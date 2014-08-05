<?php

Route::group ( [
	'prefix' => 'entities/items' ,
	'before' => 'auth'
] , function()
{
	Route::get ( 'add' , [
		'as'	 => 'add-item' ,
		'before' => ['hasAbilities:add_item' ] ,
		'uses'	 => 'Controllers\Entities\ItemController@add'
	] ) ;

	Route::post ( 'add' , [
		'as'	 => 'save-item' ,
		'before' => ['hasAbilities:add_item' ] ,
		'uses'	 => 'Controllers\Entities\ItemController@save'
	] ) ;
} ) ;
