<?php

class SystemSettingsSeeder extends Seeder
{

	public function run ()
	{
		$systemSettings = [
			[
				'id'				 => 1 ,
				'system_settable_id' => 1 ,
				'value'				 => 4
			] ,
			[
				'id'				 => 2 ,
				'system_settable_id' => 2 ,
				'value'				 => 5
			] ,
//			[
//				'id'				 =>  ,
//				'system_settable_id' =>  ,
//				'value'				 => ''
//			] ,
		] ;

		foreach ( $systemSettings as $systemSetting )
		{
			$systemSettingO = new Models\SystemSetting() ;

			$systemSettingO -> id					 = $systemSetting[ 'id' ] ;
			$systemSettingO -> system_settable_id	 = $systemSetting[ 'system_settable_id' ] ;
			$systemSettingO -> value				 = $systemSetting[ 'value' ] ;

			$systemSettingO -> save () ;
		}
	}

}
