<?php

class SystemSettingButler
{

	public static function getValue ( $systemSettingName )
	{
		$value = NULL ;

		$requestObject	 = new \Models\SystemSettable() ;
		$requestObject	 = $requestObject -> where ( 'name' , '=' , $systemSettingName ) ;

		$systemSettable	 = $requestObject -> first () ;
		$systemSetting	 = $systemSettable -> systemSetting ;

		if ( ! is_null ( $systemSetting ) )
		{
			$value = $systemSetting -> value ;
		}

		return $value ;
	}

	public static function setValue ( $systemSettingName , $value )
	{
		$requestObject	 = new \Models\SystemSettable() ;
		$requestObject	 = $requestObject -> where ( 'name' , '=' , $systemSettingName ) ;

		$systemSettable	 = $requestObject -> firstOrFail () ;
		$systemSetting	 = $systemSettable -> getSystemSettingOrNew () ;

		$systemSetting -> value = $value ;

		return $systemSetting -> update () ;
	}

}
