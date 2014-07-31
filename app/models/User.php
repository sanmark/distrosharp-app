<?php

use Illuminate\Auth\UserTrait ;
use Illuminate\Auth\UserInterface ;
use Illuminate\Auth\Reminders\RemindableTrait ;
use Illuminate\Auth\Reminders\RemindableInterface ;

class User extends Eloquent implements UserInterface , RemindableInterface
{

	use UserTrait ,
	 RemindableTrait ;

	protected $table	 = 'users' ;
	protected $hidden	 = array ( 'password' , 'remember_token' ) ;

	public function abilities ()
	{
		return $this -> belongsToMany ( 'Ability' , Config::get ( CONFIG_DATABASE_CONNECTIONS_TENANTDB_DATABASE ) . '.ability_user' ) ;
	}

	public function getAbilityCodes ()
	{
		$abilityCodes = NULL ;

		$this -> load ( 'abilities' ) ;

		foreach ( $this -> abilities as $ability )
		{
			$abilityCodes[] = $ability -> name ;
		}

		return $abilityCodes ;
	}

}
