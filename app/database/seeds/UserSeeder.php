<?php

class UserSeeder extends Seeder
{

	public function run ()
	{
		$users = [
			[
				'id'		 => 1 ,
				'username'	 => 'user' ,
				'email'		 => 'budhajeewa@thesanmark.com' ,
				'password'	 => 'user' ,
				'first_name' => 'Firstname' ,
				'last_name'	 => 'Lastname' ,
			] ,
			[
				'id'		 => 2 ,
				'username'	 => 'sandun' ,
				'email'		 => 'sandun@thesanmark.com' ,
				'password'	 => 'sandun' ,
				'first_name' => 'Sandun' ,
				'last_name'	 => 'Dhanushka' ,
			] ,
			[
				'id'		 => 3 ,
				'username'	 => 'randika' ,
				'email'		 => 'randika@thesanmark.com' ,
				'password'	 => 'randika' ,
				'first_name' => 'Randika' ,
				'last_name'	 => 'Srimal' ,
			] ,
			[
				'id'		 => 4 ,
				'username'	 => 'kosala' ,
				'email'		 => 'kosala@thesanmark.com' ,
				'password'	 => 'kosala' ,
				'first_name' => 'Kosala' ,
				'last_name'	 => 'Indrasiri' ,
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
