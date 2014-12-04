<?php

use Illuminate\Database\Schema\Blueprint ;
use Illuminate\Database\Migrations\Migration ;

class AddCompanyReturnTables extends Migration
{

	public function up ()
	{
		Schema::create ( 'company_returns' , function($t)
		{
			$t -> increments ( 'id' ) ;
			$t -> string ( 'printed_return_number',50 ) ;
			$t -> integer ( 'vendor_id' ) -> unsigned () ;
			$t -> integer ( 'from_stock_id' ) -> unsigned () ;
			$t -> datetime ( 'date_time' ) ;
			$t -> timestamps () ;

			$t -> foreign ( 'vendor_id' )
				-> references ( 'id' )
				-> on ( 'vendors' )
				-> onUpdate ( 'cascade' )
				-> onDelete ( 'cascade' ) ;

			$t -> foreign ( 'from_stock_id' )
				-> references ( 'id' )
				-> on ( 'stocks' )
				-> onUpdate ( 'cascade' )
				-> onDelete ( 'cascade' ) ;
		} ) ;

		Schema::create ( 'company_return_details' , function($t)
		{
			$t -> increments ( 'id' ) ;
			$t -> integer ( 'return_id' ) -> unsigned () ;
			$t -> integer ( 'item_id' ) -> unsigned () ;
			$t -> double ( 'buying_price' ) ;
			$t -> double ( 'quantity' ) ;
			$t -> timestamps () ;

			$t -> foreign ( 'return_id' )
				-> references ( 'id' )
				-> on ( 'company_returns' )
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
		Schema::dropIfExists ( 'company_return_details' ) ;
		Schema::dropIfExists ( 'company_returns' ) ;
	}

}
