<?php

namespace ValidationRules ;

class CustomValidationRules
{

	public function hashMatch ( $field , $value , $parameters )
	{
		$originalPassword = $parameters[ 0 ] ;

		return \Hash::check ( $value , $originalPassword ) ;
	}

}
