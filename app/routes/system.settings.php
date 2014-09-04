<?php

Route::group ( [
	'prefix' => 'system/settings' ,
	'before' => [
		'auth' ,
		'hasAbilities:edit_system_settings'
	]
] , function ()
{
	Route::get ( '' , [
		'as'	 => 'system.settings' ,
		'uses'	 => 'Controllers\System\SettingsController@showHome'
	] ) ;

	Route::get ( 'payment-source-accounts' , [
		'as'	 => 'system.settings.paymentSourceAccounts' ,
		'uses'	 => 'Controllers\System\SettingsController@showPaymentSourceAccounts'
	] ) ;
	
	Route::get ( 'timezone' , [
		'as'	 => 'system.settings.timezone' ,
		'uses'	 => 'Controllers\System\SettingsController@selectTimeZone'
	] ) ;
	Route::post ( 'timezone' , [
		'as'	 => 'system.settings.timezone' ,
		'uses'	 => 'Controllers\System\SettingsController@updateTimeZone'
	] ) ;

	Route::post ( 'payment-source-accounts' , [
		'as'	 => 'system.settings.paymentSourceAccounts' ,
		'uses'	 => 'Controllers\System\SettingsController@updatePaymentSourceAccounts'
	] ) ;
} ) ;
