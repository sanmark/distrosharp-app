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

	Route::get ( '{stockId}' , [ 
		'as'=>'stocks.view',
		'before'=>['hasAbilities:view_stocks'],
		'uses'=>'Controllers\StockController@view'
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
