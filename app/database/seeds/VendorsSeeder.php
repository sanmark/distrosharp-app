<?php

class VendorsSeeder extends Seeder
{

	public function run ()
	{

		$vendors = [
			[
				'id'		 => 1 ,
				'name'		 => 'M. K. Weerasinghe' ,
				'details'	 => 'Test Details' ,
				'is_active'	 => 1 ,
			] ,
			[
				'id'		 => 2 ,
				'name'		 => 'K. M. Ranasinghe' ,
				'details'	 => 'Test Details' ,
				'is_active'	 => 1 ,
			] ,
			[
				'id'		 => 3 ,
				'name'		 => 'Y. G. Mahesh Chathuranga ' ,
				'details'	 => 'Test Details' ,
				'is_active'	 => 1 ,
			] ,
//			[
//				'id'		 => 1 ,
//				'name'		 => '' ,
//				'details'	 => '' ,
//				'is_active'	 => ''
//			] ,
		] ;


		foreach ( $vendors as $vendor )
		{

			$vendorO = new \Models\Vendor() ;

			$vendorO -> id			 = $vendor[ 'id' ] ;
			$vendorO -> name		 = $vendor[ 'name' ] ;
			$vendorO -> details		 = $vendor[ 'details' ] ;
			$vendorO -> is_active	 = $vendor[ 'is_active' ] ;

			$vendorO -> save () ;
		}
	}

}
