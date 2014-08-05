<?php

Route::filter ( 'hasAbilities' , function($route , $request , $varString , $delimiter = '-')
{
	$allowedAbilities = explode ( $delimiter , $varString ) ;

	if ( ! AbilityButler::checkAbilities ( $allowedAbilities ) )
	{
		return Redirect::to ( '/' ) ;
	}
} ) ;
