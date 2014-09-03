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

}
