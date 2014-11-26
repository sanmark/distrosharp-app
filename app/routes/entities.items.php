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
	Route::post ( '' , [
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

	Route::get ( '{id}/edit' , [
		'as'	 => 'entities.items.edit' ,
		'before' => ['hasAbilities:edit_item' ] ,
		'uses'	 => 'Controllers\Entities\ItemController@edit'
	] ) ;

	Route::post ( '{id}/edit' , [
		'as'	 => 'entities.items.update' ,
		'before' => ['hasAbilities:edit_item' ] ,
		'uses'	 => 'Controllers\Entities\ItemController@update'
	] ) ;
	Route::group ( [
		'prefix' => 'ajax' ,
		'before' => 'csrf'
		] , function()
	{
		Route::post ( 'getItemByCode' , [
			'as'	 => 'entities.items.ajax.getItemByCode' ,
			'uses'	 => 'Controllers\Entities\ItemController@getItemByCode'
		] ) ;

		Route::post ( 'getItemByName' , [
			'as'	 => 'entities.items.ajax.getItemByName' ,
			'uses'	 => 'Controllers\Entities\ItemController@getItemByName'
		] ) ;

		Route::post ( 'getItemById' , [
			'as'	 => 'entities.items.ajax.getItemById' ,
			'uses'	 => 'Controllers\Entities\ItemController@getItemById'
		] ) ;
	} ) ;
} ) ;
