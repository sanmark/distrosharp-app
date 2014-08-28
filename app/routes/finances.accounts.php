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
} ) ;
