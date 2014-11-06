<?php

namespace Controllers\Account ;

class SettingsController extends \Controller
{

	public function showSettings ()
	{
		$data = [ ] ;

		$user = \Auth::user () ;

		$data[ 'user' ] = $user ;

		return \View::make ( 'web.account.settings.home' , $data ) ;
	}

	public function updateBasic ()
	{
		try
		{
			$user = \Auth::user () ;

			$user -> first_name	 = \InputButler::get ( 'first_name' ) ;
			$user -> last_name	 = \InputButler::get ( 'last_name' ) ;

			$user -> update () ;

			\MessageButler::setSuccess ( 'Basic settings updated successfully.' ) ;

			\ActivityLogButler::add ( "Update basic settings. User: " . $user -> id ) ;

			return \Redirect::back () ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	public function updatePassword ()
	{
		try
		{
			$existingPassword	 = \InputButler::get ( 'existing_password' ) ;
			$newPassword		 = \InputButler::get ( 'new_password' ) ;
			$confirmNewPassword	 = \InputButler::get ( 'confirm_new_password' ) ;

			$user = \Auth::user () ;

			$user -> updatePassword ( $existingPassword , $newPassword , $confirmNewPassword ) ;

			\MessageButler::setSuccess ( 'Password changed successfully.' ) ;

			\ActivityLogButler::add ( "Change Password. User: " . $user -> id ) ;

			return \Redirect::back () ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator ) ;
		}
	}

}
