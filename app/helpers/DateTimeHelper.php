<?php

class DateTimeHelper
{

	public static function convertTextToFormattedDateTime ( $text , $format = "Y-m-d H:i:s" )
	{
		if ( ! NullHelper::isNullEmptyOrWhitespace ( $text ) )
		{
			$unixTime			 = strtotime ( $text ) ;
			$formattedDateTime	 = date ( $format , $unixTime ) ;

			return $formattedDateTime ;
		}

		return NULL ;
	}

	public static function dateTimeRefill ( $dateTimeForFormat )
	{

		$dateTime	 = preg_replace ( "/[A-Z, ]/" , '' , $dateTimeForFormat ) ;
		$dateTime	 = substr_replace ( $dateTime , 'T' , 10,0) ;
		return $dateTime ;
	}

}
