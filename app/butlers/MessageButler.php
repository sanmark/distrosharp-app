<?php

class MessageButler
{

	public static function setError ( $errorMessage )
	{
		return Session::set ( MESSAGE_ERROR , $errorMessage ) ;
	}

	public static function hasError ()
	{
		return Session::has ( MESSAGE_ERROR ) ;
	}

	public static function getError ()
	{
		$error = Session::get ( MESSAGE_ERROR ) ;
		Session::forget ( MESSAGE_ERROR ) ;
		return $error ;
	}

	public static function setInfo ( $message )
	{
		Session::set ( MESSAGE_INFO , $message ) ;
	}

	public static function hasInfo ()
	{
		return Session::has ( MESSAGE_INFO ) ;
	}

	public static function getInfo ()
	{
		$message = Session::get ( MESSAGE_INFO ) ;
		Session::forget ( MESSAGE_INFO ) ;
		return $message ;
	}

	public static function setSuccess ( $successMessage )
	{
		return Session::set ( MESSAGE_SUCCESS , $successMessage ) ;
	}

	public static function hasSuccess ()
	{
		return Session::has ( MESSAGE_SUCCESS ) ;
	}

	public static function getSuccess ()
	{
		$success = Session::get ( MESSAGE_SUCCESS ) ;
		Session::forget ( MESSAGE_SUCCESS ) ;

		return $success ;
	}

}
