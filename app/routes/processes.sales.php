<?php

Route::group ( [
	'prefix' => 'processes/sales' ,
	'before' => 'auth'
] , function()
{
	Route::get ( '' , [
		'as'	 => 'processes.sales.all' ,
		'before' => ['hasAbilities:view_sales' ] ,
		'uses'	 => 'Controllers\Processes\SaleController@all'
	] ) ;

	Route::post ( '' , [
		'as'	 => 'processes.sales.all' ,
		'before' => ['hasAbilities:view_sales' ] ,
		'uses'	 => 'Controllers\Processes\SaleController@all'
	] ) ;

	Route::get ( 'add' , [
		'as'	 => 'processes.sales.add' ,
		'before' => ['hasAbilities:add_sale' ] ,
		'uses'	 => 'Controllers\Processes\SaleController@add'
	] ) ;

	Route::post ( 'add' , [
		'as'	 => 'processes.sales.save' ,
		'before' => ['hasAbilities:add_sale' ] ,
		'uses'	 => 'Controllers\Processes\SaleController@save'
	] ) ;

	Route::get ( '{id}/edit' , [
		'as'	 => 'processes.sales.edit' ,
		'before' => ['hasAbilities:edit_sale' ] ,
		'uses'	 => 'Controllers\Processes\SaleController@edit'
	] ) ;

	Route::post ( '{id}/edit' , [
		'as'	 => 'processes.sales.edit' ,
		'before' => ['hasAbilities:edit_sale' ] ,
		'uses'	 => 'Controllers\Processes\SaleController@update'
	] ) ;
} ) ;
