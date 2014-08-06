<?php

Route::group ( [
	'prefix' => 'entities/banks' ,
	'before' => 'auth'
] , function ()
{
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
} ) ;

