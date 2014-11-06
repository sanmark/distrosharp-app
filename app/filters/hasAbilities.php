<?php

Route::filter ( 'hasAbilities' , function($route , $request , $varString , $delimiter = '-')
{
	$allowedAbilities = explode ( $delimiter , $varString ) ;
	
	if ( ! AbilityButler::checkAbilities ( $allowedAbilities ) )
	{
		\MessageButler::setError ( "You dont have permissions to access that page." ) ;
		return Redirect::to ( '/' ) ;
	}
} ) ;
