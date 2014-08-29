<?php

Route::group ( [
	'prefix' => 'finances/accounts' ,
	'before' => 'auth'
] , function()
{
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
	Route::get ( 'view' , [
		'as'	 => 'finances.accounts.view' ,
		'before' => ['hasAbilities:view_finance_accounts' ] ,
		'uses'	 => 'Controllers\Finances\AccountsController@home' ,
	] ) ;
	Route::post ( 'view' , [
		'as'	 => 'finances.accounts.view' ,
		'before' => ['hasAbilities:view_finance_accounts' ] ,
		'uses'	 => 'Controllers\Finances\AccountsController@home' ,
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
} ) ;
