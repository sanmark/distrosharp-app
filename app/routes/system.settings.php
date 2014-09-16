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

	Route::post ( 'payment-source-accounts' , [
		'as'	 => 'system.settings.paymentSourceAccounts' ,
		'uses'	 => 'Controllers\System\SettingsController@updatePaymentSourceAccounts'
	] ) ;

	Route::get ( 'timezone' , [
		'as'	 => 'system.settings.timezone' ,
		'uses'	 => 'Controllers\System\SettingsController@selectTimeZone'
	] ) ;

	Route::post ( 'timezone' , [
		'as'	 => 'system.settings.timezone' ,
		'uses'	 => 'Controllers\System\SettingsController@updateTimeZone'
	] ) ;

	Route::get ( 'payment-target-accounts' , [
		'as'	 => 'system.settings.paymentTargetAccounts' ,
		'uses'	 => 'Controllers\System\SettingsController@showPaymentTargetAccounts'
	] ) ;

	Route::post ( 'payment-target-accounts' , [
		'as'	 => 'system.settings.paymentTargetAccounts' ,
		'uses'	 => 'Controllers\System\SettingsController@updatePaymentTargetAccounts'
	] ) ;
} ) ;
