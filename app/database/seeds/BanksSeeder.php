<?php

class BanksSeeder extends Seeder
{

	public function run ()
	{
		$banks = [
			[
				'id'		 => 1 ,
				'name'		 => 'HNB' ,
				'is_active'	 => 1
			] ,
			[
				'id'		 => 2 ,
				'name'		 => 'NSB' ,
				'is_active'	 => 1
			] ,
			[
				'id'		 => 3 ,
				'name'		 => 'Sampath' ,
				'is_active'	 => 1
			] ,
//			[
//				'id'		 =>  ,
//				'name'		 => '' ,
//				'is_active'	 => 
//			] ,
		] ;
		foreach ( $banks as $bank )
		{
			$bankO = new \Models\Bank() ;

			$bankO -> id		 = $bank[ 'id' ] ;
			$bankO -> name		 = $bank[ 'name' ] ;
			$bankO -> is_active	 = $bank[ 'is_active' ] ;

			$bankO -> save () ;
		}
	}

}
