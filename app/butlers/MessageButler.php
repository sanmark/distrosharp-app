<?php

class MessageButler
{

	public static function setError ( $errorMessage )
	{
		return Session::set ( 'message_error' , $errorMessage ) ;
	}

	public static function hasError ()
	{
		return Session::has ( 'message_error' ) ;
	}

	public static function getError ()
	{
		$error = Session::get ( 'message_error' ) ;
		Session::forget ( 'message_error' ) ;
		return $error ;
	}

	public static function setMessage ( $message )
	{
		Session::set ( 'message_message' , $message ) ;
	}

	public static function hasMessage ()
	{
		return Session::has ( 'message_message' ) ;
	}

	public static function getMessage ()
	{
		$message = Session::get ( 'message_message' ) ;
		Session::forget ( 'message_message' ) ;
		return $message ;
	}

}
