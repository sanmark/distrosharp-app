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
			$t -> integer ( 'user_id' ) -> unsigned () ;
			$t -> foreign ( 'user_id' )
			-> references ( 'id' )
			-> on ( 'users' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;
		} ) ;

		Schema::create ( 'items' , function($t)
		{
			$t -> increments ( 'id' ) ;
			$t -> string ( 'code' , 20 ) ;
			$t -> string ( 'name' , 50 ) ;
			$t -> integer ( 'reorder_level' ) ;
			$t -> float ( 'current_buying_price' ) ;
			$t -> float ( 'current_selling_price' ) ;
			$t -> integer ( 'buying_invoice_order' ) ;
			$t -> integer ( 'selling_invoice_order' ) ;
			$t -> boolean ( 'is_active' ) ;
		} ) ;

		Schema::create ( 'vendors' , function ($t)
		{
			$t -> increments ( 'id' ) ;
			$t -> string ( 'name' , 50 ) ;
			$t -> longtext ( 'details' ) ;
			$t -> boolean ( 'is_active' ) ;
		} ) ;

		Schema::create ( 'routes' , function($t)
		{
			$t -> increments ( 'id' ) ;

			$t -> string ( 'name' , 50 ) ;
			$t -> boolean ( 'is_active' ) ;
			$t -> integer ( 'rep_id' ) -> unsigned () ;
			
			$t -> foreign ( 'rep_id' )
			-> references ( 'id' )
			-> on ( 'users' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;
		} ) ;

		Schema::create ( 'customers' , function ($t)
		{
			$t -> increments ( 'id' ) ;
			$t -> string ( 'name' , 50 ) ;
			$t -> integer ( 'route_id' ) -> unsigned () ;
			$t -> boolean ( 'is_active' ) ;
			$t -> longtext ( 'details' ) ;
			$t -> foreign ( 'route_id' )
			-> references ( 'id' )
			-> on ( 'routes' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;
		} ) ;


		Schema::create ( 'banks' , function ($t)
		{
			$t -> increments ( 'id' ) ;
			$t -> string ( 'name' , 50 ) ;
			$t -> boolean ( 'is_active' ) ;
		} ) ;
	}

	public function down ()
	{

		Schema::drop ( 'banks' ) ;
		Schema::drop ( 'customers' ) ;
		Schema::drop ( 'routes' ) ;
		Schema::drop ( 'vendors' ) ;
		Schema::drop ( 'items' ) ;
		Schema::drop ( 'ability_user' ) ;
		Schema::drop ( 'users' ) ;
	}

}
