<?php

Route::group ( [
	'prefix' => 'stocks' ,
	'before' => 'auth'
	] , function ()
{
	Route::get ( '' , [
		'as'	 => 'stocks.all' ,
		'before' => ['hasAbilities:super_admin' ] ,
		'uses'	 => 'Controllers\StockController@all'
	] ) ;

	Route::get ( 'add' , [
		'as'	 => 'stocks.add' ,
		'before' => ['hasAbilities:super_admin' ] ,
		'uses'	 => 'Controllers\StockController@create'
	] ) ;

	Route::post ( 'add' , [
		'as'	 => 'stocks.add' ,
		'before' => ['hasAbilities:super_admin' ] ,
		'uses'	 => 'Controllers\StockController@save'
	] ) ;

	Route::get ( '{stockId}' , [
		'as'	 => 'stocks.view' ,
		'before' => ['hasAbilities:super_admin' ] ,
		'uses'	 => 'Controllers\StockController@view'
	] ) ;
	
	Route::get ( '{stockId}/edit' , [
		'as'	 => 'stocks.edit' ,
		'before' => ['hasAbilities:super_admin' ] ,
		'uses'	 => 'Controllers\StockController@edit'
	] ) ;

	Route::post ( '{stockId}/edit' , [
		'as'	 => 'stocks.update' ,
		'before' => ['hasAbilities:super_admin' ] ,
		'uses'	 => 'Controllers\StockController@update'
	] ) ;

	Route::group ( [
		'prefix' => 'ajax' ,
		'before' => 'csrf'
		] , function()
	{
		Route::post ( 'getAvailableQuantity' , [
			'as'	 => 'stocks.ajax.getAvailableQuantity' ,
			'uses'	 => 'Controllers\StockController@getAvailableQuantity'
		] ) ;
		Route::post ( 'getItemByName' , [
			'as'	 => 'stocks.ajax.getItemByName' ,
			'uses'	 => 'Controllers\StockController@getItemByName'
		] ) ;
		Route::post ( 'getItemByCode' , [
			'as'	 => 'stocks.ajax.getItemByCode' ,
			'uses'	 => 'Controllers\StockController@getItemByCode'
		] ) ;
	} ) ;
} ) ;
