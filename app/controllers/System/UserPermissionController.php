<?php

namespace Controllers\System ;

class UserPermissionController extends \Controller
{

	public function home ()
	{
		$usersList		 = \User::getArrayForHtmlSelect ( 'id' , 'username' , ['' => 'Select user' ] ) ;
		$userId			 = \Auth::user () -> id ;
		$permissions	 = \Models\Ability::get () ;
		$userPermissions = \Models\AbilityUser::where ( 'user_id' , '=' , $userId ) -> lists ( 'user_id' , 'ability_id' ) ;
		$data			 = compact ( [
			'usersList' ,
			'userId' ,
			'permissions' ,
			'userPermissions'
			] ) ;
		return \View::make ( 'web.system.userPermissions.home' , $data ) ;
	}

	public function view ()
	{
		$userId		 = \Input::get ( 'userId' ) ;
		$update		 = \Input::get ( 'update' ) ;
		$usersList	 = \User::getArrayForHtmlSelect ( 'id' , 'username' , ['' => 'Select user' ] ) ;
		$permissions = \Models\Ability::get () ;

		if ( $update != NULL )
		{
			$this -> updateUserPermissions ( $userId ) ;
			\MessageButler::setSuccess("Updated user permissions successfully");
		}

		$userPermissions = \Models\AbilityUser::where ( 'user_id' , '=' , $userId ) -> lists ( 'user_id' , 'ability_id' ) ;
		$data			 = compact ( [
			'usersList' ,
			'userId' ,
			'permissions' ,
			'userPermissions'
			] ) ;
		return \View::make ( 'web.system.userPermissions.home' , $data ) ;
	}

	public function updateUserPermissions ( $userId )
	{
		$allInputs			 = \Input::all () ;
		$deleteAllAbilities	 = \Models\AbilityUser::where ( 'user_id' , '=' , $userId ) -> delete () ;
		$newPermissions		 = [ ] ;
		foreach ( $allInputs as $input )
		{
			if ( \Input::get ( 'is_assigned_' . $input ) != NULL )
			{
				$newPermissions[] = \Input::get ( 'is_assigned_' . $input ) ;
			}
		}
		foreach ( $newPermissions as $permission )
		{
			$abilitySave				 = new \Models\AbilityUser() ;
			$abilitySave -> user_id		 = $userId ;
			$abilitySave -> ability_id	 = $permission ;
			$abilitySave -> save () ;
		}
	}

}
