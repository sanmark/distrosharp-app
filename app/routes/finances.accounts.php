<?php

Route::group ( [
	'prefix' => 'finances/accounts' ,
	'before' => ['auth' , 'hasAbilities:super_admin' ] ,
	] , function()
{
	Route::get ( '' , [
		'as'	 => 'finances.accounts.view' ,
		'uses'	 => 'Controllers\Finances\AccountsController@home' ,
	] ) ;
	Route::post ( '' , [
		'as'	 => 'finances.accounts.view' ,
		'uses'	 => 'Controllers\Finances\AccountsController@home' ,
	] ) ;
	Route::get ( 'add' , [
		'as'	 => 'finances.accounts.add' ,
		'uses'	 => 'Controllers\Finances\AccountsController@add'
	] ) ;

	Route::post ( 'add' , [
		'as'	 => 'finances.accounts.add' ,
		'uses'	 => 'Controllers\Finances\AccountsController@save' ,
	] ) ;
	Route::get ( '{id}/edit' , [
		'as'	 => 'finances.accounts.edit' ,
		'uses'	 => 'Controllers\Finances\AccountsController@edit' ,
	] ) ;
	Route::post ( '{id}/edit' , [
		'as'	 => 'finances.accounts.edit' ,
		'uses'	 => 'Controllers\Finances\AccountsController@update'
	] ) ;
	Route::get ( 'confirm' , [
		'as'	 => 'finances.accounts.confirmAccountBalance' ,
		'uses'	 => 'Controllers\Finances\AccountsController@confirmAccountHome'
	] ) ;
	Route::post ( 'confirm' , [
		'as'	 => 'finances.accounts.confirmAccountBalance' ,
		'uses'	 => 'Controllers\Finances\AccountsController@confirmAccountFilter'
	] ) ;
} ) ;

