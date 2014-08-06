<?php

Route::group ( [
	'prefix' => 'entities/vendors' ,
	'before' => 'auth'
] , function()
{
	Route::get ( 'add' , [
		'as'	 => 'entities.vendors.add' ,
		'uses'	 => 'Controllers\Entities\VendorController@add'
	] ) ;
	Route::post ( 'add' , [
		'as'	 => 'entities.vendors.save' ,
		'uses'	 => 'Controllers\Entities\VendorController@save'
	] ) ;
} ) ;
