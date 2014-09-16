<?php

class SystemSettingsSeeder extends Seeder
{

	public function run ()
	{
		$systemSettings = [
			[
				'id'				 => 1 ,
				'system_settable_id' => 1 ,
				'value'				 => 1
			] ,
			[
				'id'				 => 2 ,
				'system_settable_id' => 2 ,
				'value'				 => 2
			] ,
			[
				'id'				 => 3 ,
				'system_settable_id' => 3 ,
				'value'				 => 'Asia/Colombo'
			] ,
			[
				'id'				 => 4 ,
				'system_settable_id' => 4 ,
				'value'				 => 1
			] ,
			[
				'id'				 => 5 ,
				'system_settable_id' => 5 ,
				'value'				 => 2
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
