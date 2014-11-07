<?php

Route::group ( [
	'prefix' => 'finances/accounts' ,
	'before' => 'auth'
	] , function()
{
	Route::get ( '' , [
		'as'	 => 'finances.accounts.view' ,
		'before' => ['hasAbilities:view_finance_accounts' ] ,
		'uses'	 => 'Controllers\Finances\AccountsController@home' ,
	] ) ;
	Route::post ( '' , [
		'as'	 => 'finances.accounts.view' ,
		'before' => ['hasAbilities:view_finance_accounts' ] ,
		'uses'	 => 'Controllers\Finances\AccountsController@home' ,
	] ) ;
	Route::get ( 'add' , [
		'as'	 => 'finances.accounts.add' ,
		'before' => ['hasAbilities:add_finance_account' ] ,
		'uses'	 => 'Controllers\Finances\AccountsController@add'
	] ) ;

	Route::post ( 'add' , [
		'as'	 => 'finances.accounts.add' ,
		'before' => ['hasAbilities:add_finance_account' ] ,
		'uses'	 => 'Controllers\Finances\AccountsController@save' ,
	] ) ;
	Route::get ( '{id}/edit' , [
		'as'	 => 'finances.accounts.edit' ,
		'before' => ['hasAbilities:edit_finance_account' ] ,
		'uses'	 => 'Controllers\Finances\AccountsController@edit' ,
	] ) ;
	Route::post ( '{id}/edit' , [
		'as'	 => 'finances.accounts.edit' ,
		'before' => ['hasAbilities:edit_finance_account' ] ,
		'uses'	 => 'Controllers\Finances\AccountsController@update'
	] ) ;
	Route::get ( 'confirm' , [
		'as'	 => 'finances.accounts.confirmAccountBalance' ,
		'before' => ['hasAbilities:confirm_finance_account_balance' ] ,
		'uses'	 => 'Controllers\Finances\AccountsController@confirmAccountHome'
	] ) ;
	Route::post ( 'confirm' , [
		'as'	 => 'finances.accounts.confirmAccountBalance' ,
		'before' => ['hasAbilities:confirm_finance_account_balance' ] ,
		'uses'	 => 'Controllers\Finances\AccountsController@confirmAccountFilter'
	] ) ;
} ) ;

