<?php

Route::group ( [
	'prefix' => 'entities/banks' ,
	'before' => 'auth'
] , function ()
{
	Route::get ( '' , [
		'as'	 => 'entities.banks.view' ,
		'before' => ['hasAbilities:view_banks' ] ,
		'uses'	 => 'Controllers\Entities\BankController@home'
	] ) ;
	Route::post ( '' , [
		'as'	 => 'entities.banks.view' ,
		'before' => ['hasAbilities:view_banks' ] ,
		'uses'	 => 'Controllers\Entities\BankController@home'
	] ) ;
	Route::get ( 'add' , [
		'as'	 => 'entities.banks.add' ,
		'before' => ['hasAbilities:add_bank' ] ,
		'uses'	 => 'Controllers\Entities\BankController@add'
	] ) ;
	Route::post ( 'add' , [

		'as'	 => 'entities.banks.save' ,
		'before' => ['hasAbilities:add_bank' ] ,
		'uses'	 => 'Controllers\Entities\BankController@save'
	] ) ;
	Route::get ( '{id}/edit' , [
		'as'	 => 'entities.banks.edit' ,
		'before' => ['hasAbilities:edit_bank' ] ,
		'uses'	 => 'Controllers\Entities\BankController@edit'
	] ) ;
	Route::post ( '{id}/edit' , [
		'as'	 => 'entities.banks.update' ,
		'before' => ['hasAbilities:edit_bank' ] ,
		'uses'	 => 'Controllers\Entities\BankController@update'
	] ) ;
} ) ;

