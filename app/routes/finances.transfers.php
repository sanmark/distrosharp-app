<?php

Route::group ( [
	'prefix' => 'finances/transfers' ,
	'before' => 'auth'
] , function()
{
	Route::get ( 'select-accounts-involved' , [
		'as'	 => 'finances.transfers.selectAccountsInvolved' ,
		'before' => ['hasAbilities:add_finance_transfer' ] ,
		'uses'	 => 'Controllers\Finances\TransfersController@selectAccountsInvolved'
	] ) ;
	Route::post ( 'select-accounts-involved' , [
		'as'	 => 'finances.transfers.selectAccountsInvolved' ,
		'before' => ['hasAbilities:add_finance_transfer' ] ,
		'uses'	 => 'Controllers\Finances\TransfersController@pSelectAccountsInvolved'
	] ) ;
	Route::get ( 'add/{fromAccountId}/{toAccountId}' , [
		'as'	 => 'finances.transfers.add' ,
		'before' => ['hasAbilities:add_finance_transfer' ] ,
		'uses'	 => 'Controllers\Finances\TransfersController@add'
	] ) ;
	Route::post ( 'add/{fromAccountId}/{toAccountId}' , [
		'as'	 => 'finances.transfers.add' ,
		'before' => ['hasAbilities:add_finance_transfer' ] ,
		'uses'	 => 'Controllers\Finances\TransfersController@save'
	] ) ;
	Route::get ( '{accountId}/view' , [
		'as'	 => 'finances.transfers.view' ,
		'before' => ['hasAbilities:view_finance_transfers_details' ] ,
		'uses'	 => 'Controllers\Finances\TransfersController@home'
	] ) ;
	Route::post ( '{accountId}/view' , [
		'as'	 => 'finances.transfers.view' ,
		'before' => ['hasAbilities:view_finance_transfers_details' ] ,
		'uses'	 => 'Controllers\Finances\TransfersController@home'
	] ) ;
	Route::get ( '{transferId}/edit' , [
		'as'	 => 'finances.transfers.edit' ,
		'before' => ['hasAbilities:edit_finance_transfer_details' ] ,
		'uses'	 => 'Controllers\Finances\TransfersController@edit'
	] ) ;
	Route::post ( '{transferId}/edit' , [
		'as'	 => 'finances.transfers.edit' ,
		'before' => ['hasAbilities:edit_finance_transfer_details' ] ,
		'uses'	 => 'Controllers\Finances\TransfersController@update'
	] ) ;
	Route::get ( 'view-all' , [
		'as'	 => 'finances.transfers.viewAll' ,
		'before' => ['hasAbilities:view_finance_transfers_details' ] ,
		'uses'	 => 'Controllers\Finances\TransfersController@viewAll'
	] ) ;
	Route::post ( 'view-all' , [
		'as'	 => 'finances.transfers.viewAll' ,
		'before' => ['hasAbilities:view_finance_transfers_details' ] ,
		'uses'	 => 'Controllers\Finances\TransfersController@viewAll'
	] ) ;
} ) ;

