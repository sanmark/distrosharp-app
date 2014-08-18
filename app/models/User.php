<?php

use Illuminate\Auth\UserTrait ;
use Illuminate\Auth\UserInterface ;
use Illuminate\Auth\Reminders\RemindableTrait ;
use Illuminate\Auth\Reminders\RemindableInterface ;

class User extends Eloquent implements UserInterface , RemindableInterface , Interfaces\iEntity
{

	use UserTrait ,
	 RemindableTrait ;

	protected $table	 = 'users' ;
	protected $hidden	 = array ( 'password' , 'remember_token' ) ;

	public function abilities ()
	{
		return $this -> belongsToMany ( 'Models\Ability' , Config::get ( CONFIG_DATABASE_CONNECTIONS_TENANTDB_DATABASE ) . '.ability_user' ) ;
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

	public static function filter ( $filterValues )
	{
		throw new Exceptions\NotImplementedException() ;
	}

	public static function getArray ( $key , $value )
	{
		$array = self::select ( $key , \DB::raw ( 'CONCAT (' . $value . ') as `value`' ) )
		-> lists ( 'value' , $key ) ;

		return $array ;
	}

	public static function getArrayByIds ( $key , $value , $by )
	{
		$array = self::whereIn ( 'id' , $by )
		-> select ( $key , \DB::raw ( 'CONCAT(' . $value . ')as `value`' ) )
		-> lists ( 'value' , $key ) ;

		return $array ;
	}

	public static function getArrayForHtmlSelect ( $key , $value )
	{
		$array		 = self::getArray ( $key , $value ) ;
		$anyElemet	 = [
			'0' => 'Any'
		] ;

		$array = $anyElemet + $array ;

		return $array ;
	}

	public static function getArrayForHtmlSelectByIds ( $key , $value , $by )
	{
		$array = self::getArrayByIds ( $key , $value , $by ) ;

		$anyElemet = [
			'0' => 'Any'
		] ;

		$array = $anyElemet + $array ;

		return $array ;
	}

}
