<?php

Route::group ( [
	'prefix' => 'finances/transfers' ,
	'before' => ['auth' , 'hasAbilities:super_admin' ] ,
	] , function()
{
	Route::get ( 'select-accounts-involved' , [
		'as'	 => 'finances.transfers.selectAccountsInvolved' ,
		'uses'	 => 'Controllers\Finances\TransfersController@selectAccountsInvolved'
	] ) ;
	Route::post ( 'select-accounts-involved' , [
		'as'	 => 'finances.transfers.selectAccountsInvolved' ,
		'uses'	 => 'Controllers\Finances\TransfersController@pSelectAccountsInvolved'
	] ) ;
	Route::get ( 'add/{fromAccountId}/{toAccountId}' , [
		'as'	 => 'finances.transfers.add' ,
		'uses'	 => 'Controllers\Finances\TransfersController@add'
	] ) ;
	Route::post ( 'add/{fromAccountId}/{toAccountId}' , [
		'as'	 => 'finances.transfers.add' ,
		'uses'	 => 'Controllers\Finances\TransfersController@save'
	] ) ;
	Route::get ( '{accountId}/view' , [
		'as'	 => 'finances.transfers.view' ,
		'uses'	 => 'Controllers\Finances\TransfersController@home'
	] ) ;
	Route::post ( '{accountId}/view' , [
		'as'	 => 'finances.transfers.view' ,
		'uses'	 => 'Controllers\Finances\TransfersController@home'
	] ) ;
	Route::get ( '{transferId}/edit' , [
		'as'	 => 'finances.transfers.edit' ,
		'uses'	 => 'Controllers\Finances\TransfersController@edit'
	] ) ;
	Route::post ( '{transferId}/edit' , [
		'as'	 => 'finances.transfers.edit' ,
		'uses'	 => 'Controllers\Finances\TransfersController@update'
	] ) ;
	Route::get ( 'view-all' , [
		'as'	 => 'finances.transfers.viewAll' ,
		'uses'	 => 'Controllers\Finances\TransfersController@viewAll'
	] ) ;
	Route::post ( 'view-all' , [
		'as'	 => 'finances.transfers.viewAll' ,
		'uses'	 => 'Controllers\Finances\TransfersController@viewAll'
	] ) ;
} ) ;
