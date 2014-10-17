<?php

namespace Models ;

class SystemSettable extends \Eloquent
{

	public $timestamps		 = FALSE ;
	protected $connection	 = 'central_db' ;

	public function systemSetting ()
	{
		return $this -> hasOne ( 'Models\SystemSetting' ) ;
	}

	public function getSystemSettingOrNew ()
	{
		$systemSetting = $this -> systemSetting ;

		if ( is_null ( $systemSetting ) )
		{
			$systemSetting						 = new SystemSetting() ;
			$systemSetting -> system_settable_id = $this -> id ;
		}

		return $systemSetting ;
	}

}
