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

	public static function dateTimeRefill ( $collection , $column )
	{
		$dateTimeWithUTC = date ( 'Y-m-dTH:i:s' , strtotime ( $collection -> $column ) ) ;
		$dateTime		 = str_replace ( 'UTC' , 'T' , $dateTimeWithUTC ) ;
		return $dateTime ;
	}

}
