<?php

use Illuminate\Database\Schema\Blueprint ;
use Illuminate\Database\Migrations\Migration ;

class RemoveItemOrder extends Migration
{

	public function up ()
	{
		Schema::table ( 'items' , function($t)
		{
			$t -> dropColumn ( [
				'buying_invoice_order' ,
				'selling_invoice_order' ,
			] ) ;
		} ) ;
	}

	public function down ()
	{
		Schema::table ( 'items' , function($t)
		{
			$t -> integer ( 'buying_invoice_order' ) ;
			$t -> integer ( 'selling_invoice_order' ) ;
		} ) ;
	}

}
