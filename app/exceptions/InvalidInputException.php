<?php

class InvalidInputException extends Exception
{

	public $validator ;

	public function __construct ( $message = NULL , $code = NULL , $previous = NULL )
	{
		parent::__construct ( $message , $code , $previous ) ;
	}

}
