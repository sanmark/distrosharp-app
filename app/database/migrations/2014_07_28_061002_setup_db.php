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
			$t -> string ( 'remember_token' , 100 ) -> nullable () ;
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

		Schema::create ( 'stocks' , function ($t)
		{
			$t -> increments ( 'id' ) ;
			$t -> string ( 'name' ) ;
			$t -> integer ( 'incharge_id' ) -> unsigned () ;
			$t -> string ( 'stock_type_id' ) ;

			$t -> foreign ( 'incharge_id' )
			-> references ( 'id' )
			-> on ( 'users' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;
		} ) ;

		Schema::create ( 'stock_details' , function ($t)
		{
			$t -> increments ( 'id' ) ;
			$t -> integer ( 'stock_id' ) -> unsigned () ;
			$t -> integer ( 'item_id' ) -> unsigned () ;
			$t -> float ( 'good_quantity' ) ;
			$t -> float ( 'return_quantity' ) ;

			$t -> foreign ( 'stock_id' )
			-> references ( 'id' )
			-> on ( 'stocks' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;

			$t -> foreign ( 'item_id' )
			-> references ( 'id' )
			-> on ( 'items' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;
		} ) ;

		Schema::create ( 'buying_invoices' , function ($t)
		{
			$t -> increments ( 'id' ) ;
			$t -> date ( 'date' ) ;
			$t -> integer ( 'vendor_id' ) -> unsigned () ;
			$t -> string ( 'printed_invoice_num' ) ;
			$t -> boolean ( 'completely_paid' ) ;
			$t -> float ( 'other_expenses_amount' ) ;
			$t -> string ( 'other_expenses_total' ) ;
			$t -> string ( 'stock_id' ) ;


			$t -> foreign ( 'vendor_id' )
			-> references ( 'id' )
			-> on ( 'vendors' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;
		} ) ;

		Schema::create ( 'buying_items' , function ($t)
		{
			$t -> increments ( 'id' ) ;
			$t -> integer ( 'invoice_id' ) -> unsigned () ;
			$t -> integer ( 'item_id' ) -> unsigned () ;
			$t -> float ( 'price' ) ;
			$t -> float ( 'quantity' ) ;
			$t -> float ( 'free_quantity' ) ;
			$t -> date ( 'exp_date' ) ;
			$t -> string ( 'batch_number' ) ;

			$t -> foreign ( 'invoice_id' )
			-> references ( 'id' )
			-> on ( 'buying_invoices' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;

			$t -> foreign ( 'item_id' )
			-> references ( 'id' )
			-> on ( 'items' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;
		} ) ;

		Schema::create ( 'transfers' , function($t)
		{
			$t -> increments ( 'id' ) ;
			$t -> integer ( 'from_stock_id' ) -> unsigned () ;
			$t -> integer ( 'to_stock_id' ) -> unsigned () ;
			$t -> dateTime ( 'date_time' ) ;

			$t -> foreign ( 'from_stock_id' )
			-> references ( 'id' )
			-> on ( 'stocks' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;

			$t -> foreign ( 'to_stock_id' )
			-> references ( 'id' )
			-> on ( 'stocks' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;
		} ) ;

		Schema::create ( 'transfer_details' , function($t)
		{
			$t -> increments ( 'id' ) ;
			$t -> integer ( 'transfer_id' ) -> unsigned () ;
			$t -> integer ( 'item_id' ) -> unsigned () ;
			$t -> float ( 'quantity' ) ;

			$t -> foreign ( 'transfer_id' )
			-> references ( 'id' )
			-> on ( 'transfers' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;

			$t -> foreign ( 'item_id' )
			-> references ( 'id' )
			-> on ( 'items' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;
		} ) ;
	}

	public function down ()
	{
		Schema::dropIfExists ( 'transfer_details' ) ;
		Schema::dropIfExists ( 'transfers' ) ;
		Schema::dropIfExists ( 'buying_items' ) ;
		Schema::dropIfExists ( 'buying_invoices' ) ;
		Schema::dropIfExists ( 'stock_details' ) ;
		Schema::dropIfExists ( 'stocks' ) ;
		Schema::dropIfExists ( 'banks' ) ;
		Schema::dropIfExists ( 'customers' ) ;
		Schema::dropIfExists ( 'routes' ) ;
		Schema::dropIfExists ( 'vendors' ) ;
		Schema::dropIfExists ( 'items' ) ;
		Schema::dropIfExists ( 'ability_user' ) ;
		Schema::dropIfExists ( 'users' ) ;
	}

}
