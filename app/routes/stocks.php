<?php

Route::group ( [
	'prefix' => 'stocks' ,
	'before' => 'auth'
	] , function ()
{
	Route::get ( '' , [
		'as'	 => 'stocks.all' ,
		'before' => ['hasAbilities:view_stocks' ] ,
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
		'before' => ['hasAbilities:view_stocks' ] ,
		'uses'	 => 'Controllers\StockController@view'
	] ) ;
	Route::post ( '{stockId}' , [
		'as'	 => 'stocks.view' ,
		'before' => ['hasAbilities:confirm_stock' ] ,
		'uses'	 => 'Controllers\StockController@confirmStock'
	] ) ;

	Route::get ( '{stockId}/edit' , [
		'as'	 => 'stocks.edit' ,
		'before' => ['hasAbilities:edit_stock' ] ,
		'uses'	 => 'Controllers\StockController@edit'
	] ) ;

	Route::post ( '{stockId}/edit' , [
		'as'	 => 'stocks.update' ,
		'before' => ['hasAbilities:edit_stock' ] ,
		'uses'	 => 'Controllers\StockController@update'
	] ) ;
} ) ;
