<?php

class UserSeeder extends Seeder
{

	public function run ()
	{
		$users = [
			[
				'id'		 => 1 ,
				'username'	 => 'budhajeewa' ,
				'email'		 => 'budhajeewa@thesanmark.com' ,
				'password'	 => 'budhajeewa' ,
				'first_name' => 'Supun' ,
				'last_name'	 => 'Budhajeewa' ,
			] ,
			[
				'id'		 => 2 ,
				'username'	 => 'sandun' ,
				'email'		 => 'sandun@thesanmark.com' ,
				'password'	 => 'sandun' ,
				'first_name' => 'Sandun' ,
				'last_name'	 => 'Dhanushka' ,
			] ,
//			[
//				'id'		 =>  ,
//				'username'	 => '' ,
//				'email'		 => '' ,
//				'password'	 => '' ,
//				'first_name' => '' ,
//				'last_name'	 => '' ,
//			] ,
		] ;

		foreach ( $users as $user )
		{
			$user[ 'password' ] = Hash::make ( $user[ 'password' ] ) ;

			User::create ( $user ) ;
		}
	}

}
