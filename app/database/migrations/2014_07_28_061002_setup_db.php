<?php

use Illuminate\Database\Schema\Blueprint ;
use Illuminate\Database\Migrations\Migration ;
use Illuminate\Database\Query\Expression ;

class SetupDb extends Migration
{

	public function up ()
	{
		Schema::create ( 'users' , function($t)
		{
			$t -> increments ( 'id' ) ;
			$t -> string ( 'username' , 50 ) ;
			$t -> string ( 'email' , 100 ) ;
			$t -> string ( 'password' , 100 ) ;
			$t -> string ( 'first_name' , 50 ) ;
			$t -> string ( 'last_name' , 50 ) ;
			$t -> timestamps () ;
		} ) ;

		Schema::create ( 'ability_user' , function($t)
		{
			$t -> integer ( 'ability_id' ) -> unsigned () ;
			$t -> integer ( 'user_id' ) -> unsiged () ;
		} ) ;

		Schema::create ( 'vendors' , function ($t)
		{
			$t -> increments ( 'id' ) ;
			$t -> string ( 'name' , 50 ) ;
			$t -> longtext ( 'details' ) ;
			$t -> boolean ( 'is_active' ) ;
		} ) ;
	}

	public function down ()
	{
		Schema::drop ( 'vendors' ) ;
		Schema::drop ( 'ability_user' ) ;
		Schema::drop ( 'users' ) ;
	}

}
