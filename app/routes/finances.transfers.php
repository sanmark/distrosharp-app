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
} ) ;

