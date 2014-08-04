<?php

Route::group ( [
	'prefix' => 'entities/vendors'
] , function()
{
	Route::get ( 'add' , [
		'as'	 => 'add-vendor' ,
		'uses'	 => 'Controllers\Entities\VendorController@add'
	] ) ;
	Route::post ( 'add' , [
		'as'	 => 'save-vendor' ,
		'uses'	 => 'Controllers\Entities\VendorController@save'
	] ) ;
} ) ;
