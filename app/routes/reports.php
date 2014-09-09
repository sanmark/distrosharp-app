<?php

Route::group ( [
	'prefix' => 'reports' ,
	'before' => 'auth'
] , function ()
{
	Route::get ( 'stocks' , [
		'as'	 => 'reports.stocks' ,
		'before' => ['hasAbilities:view_stock_report' ] ,
		'uses'	 => 'Controllers\Reports\StockController@show'
	] ) ;

	Route::post ( 'stocks' , [
		'as'	 => 'reports.stocks' ,
		'before' => ['hasAbilities:view_stock_report' ] ,
		'uses'	 => 'Controllers\Reports\StockController@update'
	] ) ;
} ) ;
