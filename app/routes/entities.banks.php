<?php

Route::group ( [
	'prefix' => 'entities/banks' ,
	'before' => 'auth'
] , function ()
{
	Route::get ( 'add' , [
		'as'	 => 'entities.banks.add' ,
		'uses'	 => 'Controllers\Entities\BankController@add'
	] ) ;
	Route::post ( 'add' , [

		'as'	 => 'entities.banks.save' ,
		'uses'	 => 'Controllers\Entities\BankController@save'
	] ) ;
} ) ;

