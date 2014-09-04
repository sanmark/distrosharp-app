<?php

Route::group ( [
	'prefix' => 'filemanager' ,
	'before' => 'auth'
] , function()
{
	Route::get ( '' , [
		'as'	 => 'fileManager.home' ,
		'before' => ['hasAbilities:manage_files' ] ,
		'uses'	 => 'Controllers\FileManager\FileController@home'
	] ) ;
} ) ;
