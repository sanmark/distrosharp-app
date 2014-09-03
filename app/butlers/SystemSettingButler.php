<?php

class SystemSettingButler
{

	public static function getValue ( $systemSettingName )
	{
		$requesObject	 = new \Models\SystemSettable() ;
		$requesObject	 = $requesObject -> where ( 'name' , '=' , $systemSettingName ) ;

		$systemSettable	 = $requesObject -> firstOrFail () ;
		$systemSetting	 = $systemSettable -> systemSetting ;
		$value			 = $systemSetting -> value ;

		return $value ;
	}

	public static function setValue ( $systemSettingName , $value )
	{
		$requestObject	 = new \Models\SystemSettable() ;
		$requestObject	 = $requestObject -> where ( 'name' , '=' , $systemSettingName ) ;

		$systemSettable			 = $requestObject -> firstOrFail () ;
		$systemSetting			 = $systemSettable -> systemSetting ;
		$systemSetting -> value	 = $value ;

		return $systemSetting -> update () ;
	}

}
