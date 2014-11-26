<?php

Route::group ( [
	'prefix' => 'processes/transfers' ,
	'before' => 'auth'
	] , function()
{
	Route::get ( '' , [
		'as'	 => 'processes.transfers.all' ,
		'before' => ['hasAbilities:view_transfers' ] ,
		'uses'	 => 'Controllers\Processes\TransferController@all'
	] ) ;

	Route::post ( '' , [
		'as'	 => 'processes.transfers.all' ,
		'before' => ['hasAbilities:view_transfers' ] ,
		'uses'	 => 'Controllers\Processes\TransferController@all'
	] ) ;

	Route::get ( 'select-stocks-involved' , [
		'as'	 => 'processes.transfers.selectStocksInvolved' ,
		'before' => ['hasAbilities:add_transfer' ] ,
		'uses'	 => 'Controllers\Processes\TransferController@selectStocksInvolved'
	] ) ;

	Route::post ( 'select-stocks-involved' , [
		'as'	 => 'processes.transfers.selectStocksInvolved' ,
		'before' => ['hasAbilities:add_transfer' ] ,
		'uses'	 => 'Controllers\Processes\TransferController@pSelectStocksInvolved'
	] ) ;

	Route::get ( 'add/{fromStockId}/{toStockId}/{isUnload}' , [
		'as'	 => 'processes.transfers.add' ,
		'before' => ['hasAbilities:add_transfer' ] ,
		'uses'	 => 'Controllers\Processes\TransferController@add'
	] ) ;

	Route::post ( 'add/{fromStockId}/{toStockId}/{isUnload}' , [
		'as'	 => 'processes.transfers.add' ,
		'before' => ['hasAbilities:add_transfer' ] ,
		'uses'	 => 'Controllers\Processes\TransferController@save'
	] ) ;
	Route::get ( '{id}/view' , [
		'as'	 => 'processes.transfers.view' ,
		'before' => ['hasAbilities:view_transfers' ] ,
		'uses'	 => 'Controllers\Processes\TransferController@viewTransfer'
	] ) ;
	Route::group ( [
		'prefix' => 'ajax' ,
		'before' => 'csrf'
		] , function()
	{
		Route::post ( 'getAvailableQuantity' , [
			'as'	 => 'processes.transfer.ajax.getAvailableQuantity' ,
			'uses'	 => 'Controllers\Processes\TransferController@getAvailableQuantity'
		] ) ;
		Route::post ( 'getTargetStockQuantity' , [
			'as'	 => 'processes.transfer.ajax.getTragetStockQuantity' ,
			'uses'	 => 'Controllers\Processes\TransferController@getTargetStockQuantity'
		] ) ;
	} ) ;
} ) ;
