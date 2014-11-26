<?php

Route::group ( [
	'prefix' => 'tools' ,
	'before' => 'auth'
	] , function ()
{
	Route::get ( 'weight-calculator' , [
		'as'	 => 'tools.weightCalculator' ,
		'before' => ['hasAbilities:view_weight_calculator' ] ,
		'uses'	 => 'Controllers\Tools\WeightCalculatorController@view'
	] ) ;
} ) ;
