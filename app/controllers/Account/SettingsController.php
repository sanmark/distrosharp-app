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

			$user -> first_name	 = \Input::get ( 'first_name' ) ;
			$user -> last_name	 = \Input::get ( 'last_name' ) ;

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
			$existingPassword	 = \Input::get ( 'existing_password' ) ;
			$newPassword		 = \Input::get ( 'new_password' ) ;
			$confirmNewPassword	 = \Input::get ( 'confirm_new_password' ) ;

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
