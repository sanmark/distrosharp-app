<?php

Route::group ( [
	'prefix' => 'entities/items' ,
	'before' => 'auth'
] , function()
{
	Route::get ( 'add' , [
		'as'	 => 'add-item' ,
		'uses'	 => 'Controllers\Entities\ItemController@add'
	] ) ;

	Route::post ( 'add' , [
		'as'	 => 'save-item' ,
		'uses'	 => 'Controllers\Entities\ItemController@save'
	] ) ;
} ) ;