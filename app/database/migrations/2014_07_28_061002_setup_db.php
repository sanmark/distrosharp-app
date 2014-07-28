<?php

use Illuminate\Database\Schema\Blueprint ;
use Illuminate\Database\Migrations\Migration ;

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
	}

	public function down ()
	{
		Schema::drop ( 'users' ) ;
	}

}
