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

	public function stock ()
	{
		return $this -> hasOne ( 'Models\Stock' , 'incharge_id' ) ;
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

	public function update ( array $attributes = array () )
	{
		$this -> validateForUpdate () ;

		parent::update ( $attributes ) ;
	}

	public function updatePassword ( $existingPassword , $newPassword , $confirmNewPassword )
	{
		$this -> validateForPasswordUpdate ( $existingPassword , $newPassword , $confirmNewPassword ) ;

		$this -> password = Hash::make ( $newPassword ) ;

		return $this -> update () ;
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
		$array = [ ] ;

		if ( count ( $by ) > 0 )
		{
			$array = self::whereIn ( 'id' , $by )
			-> select ( $key , \DB::raw ( 'CONCAT(' . $value . ')as `value`' ) )
			-> lists ( 'value' , $key ) ;
		}

		return $array ;
	}

	public static function getArrayForHtmlSelect ( $key , $value , array $firstElement = NULL )
	{
		$array = self::getArray ( $key , $value ) ;

		if ( ! is_null ( $firstElement ) )
		{
			$array = $firstElement + $array ;
		}

		return $array ;
	}

	public static function getArrayForHtmlSelectByIds ( $key , $value , $by , array $firstElement = NULL )
	{
		$array = self::getArrayByIds ( $key , $value , $by ) ;

		if ( ! is_null ( $firstElement ) )
		{
			$array = $firstElement + $array ;
		}

		return $array ;
	}

	private function validateForUpdate ()
	{
		$data = $this -> toArray () ;

		$rules = [
			'first_name' => 'required' ,
			'last_name'	 => 'required'
		] ;

		$validator = Validator::make ( $data , $rules ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;
			throw $iie ;
		}
	}

	private function validateForPasswordUpdate ( $existingPassword , $newPassword , $confirmNewPassword )
	{
		$data = [
			'existing_password'		 => $existingPassword ,
			'new_password'			 => $newPassword ,
			'confirm_new_password'	 => $confirmNewPassword
		] ;

		$rules = [
			'existing_password'		 => [
				'required' ,
				'hash_match:' . Auth::user () -> password
			] ,
			'new_password'			 => [
				'required'
			] ,
			'confirm_new_password'	 => [
				'required' ,
				'same:new_password'
			]
		] ;

		$validator = Validator::make ( $data , $rules ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

}
