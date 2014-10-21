<?php

class AbilityUserSeeder extends Seeder
{

	public function run ()
	{
		$abilityUsers = [
			[
				'ability_id' => 1 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 2 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 3 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 4 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 5 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 6 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 7 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 8 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 9 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 10 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 11 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 12 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 13 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 14 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 15 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 16 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 17 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 18 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 19 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 23 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 24 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 25 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 26 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 27 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 28 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 29 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 30 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 31 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 32 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 32 ,
				'user_id'	 => 2
			] ,
			[
				'ability_id' => 33 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 34 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 35 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 36 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 37 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 38 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 39 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 40 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 41 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 42 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 43 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 44 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 45 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 46 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 47 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 48 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 49 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 50 ,
				'user_id'	 => 1
			] ,
			[
				'ability_id' => 51 ,
				'user_id'	 => 1
			]
//			[
//				'ability_id' =>  ,
//				'user_id'	 =>
//			] ,
			] ;


		foreach ( $abilityUsers as $abilityUser )
		{
			$abilityId	 = $abilityUser[ 'ability_id' ] ;
			$userId		 = $abilityUser[ 'user_id' ] ;

			$user = User::find ( $userId ) ;

			$user -> abilities () -> attach ( $abilityId ) ;
		}
	}

}
