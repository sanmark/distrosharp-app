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
			$t -> double ( 'current_buying_price' ) ;
			$t -> double ( 'current_selling_price' ) ;
			$t -> integer ( 'buying_invoice_order' ) ;
			$t -> integer ( 'selling_invoice_order' ) ;
			$t -> boolean ( 'is_active' ) ;
		} ) ;

		Schema::create ( 'banks' , function ($t)
		{
			$t -> increments ( 'id' ) ;
			$t -> string ( 'name' , 50 ) ;
			$t -> boolean ( 'is_active' ) ;
		} ) ;

		Schema::create ( 'finance_accounts' , function($t)
		{
			$t -> increments ( 'id' ) ;
			$t -> string ( 'name' ) ;
			$t -> integer ( 'bank_id' ) -> unsigned () -> nullable () ;
			$t -> boolean ( 'is_active' ) ;
			$t -> double ( 'account_balance' ) -> default ( 0 ) ;
			$t -> boolean ( 'is_in_house' ) ;

			$t -> foreign ( 'bank_id' )
			-> references ( 'id' )
			-> on ( 'banks' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;
		} ) ;

		Schema::create ( 'vendors' , function ($t)
		{
			$t -> increments ( 'id' ) ;
			$t -> string ( 'name' , 50 ) ;
			$t -> longtext ( 'details' ) ;
			$t -> boolean ( 'is_active' ) ;
			$t -> integer ( 'finance_account_id' ) -> unsigned () ;

			$t -> foreign ( 'finance_account_id' )
			-> references ( 'id' )
			-> on ( 'finance_accounts' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;
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
			$t -> integer ( 'finance_account_id' ) -> unsigned () ;

			$t -> foreign ( 'route_id' )
			-> references ( 'id' )
			-> on ( 'routes' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;

			$t -> foreign ( 'finance_account_id' )
			-> references ( 'id' )
			-> on ( 'finance_accounts' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;
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
			$t -> double ( 'good_quantity' ) ;
			$t -> double ( 'return_quantity' ) ;

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
			$t -> datetime ( 'date_time' ) ;
			$t -> integer ( 'vendor_id' ) -> unsigned () ;
			$t -> string ( 'printed_invoice_num' ) ;
			$t -> boolean ( 'completely_paid' ) ;
			$t -> double ( 'other_expenses_amount' ) ;
			$t -> text ( 'other_expenses_details' ) ;
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
			$t -> double ( 'price' ) ;
			$t -> double ( 'quantity' ) -> nullable () ;
			$t -> double ( 'free_quantity' ) -> nullable () ;
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
			$t -> text ( 'description' ) -> nullable () ;

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
			$t -> double ( 'quantity' ) ;

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

		Schema::create ( 'selling_invoices' , function($t)
		{
			$t -> increments ( 'id' ) ;
			$t -> dateTime ( 'date_time' ) ;
			$t -> integer ( 'customer_id' ) -> unsigned () ;
			$t -> integer ( 'rep_id' ) -> unsigned () ;
			$t -> string ( 'printed_invoice_number' , 100 ) ;
			$t -> double ( 'discount' ) -> default ( 0 ) ;
			$t -> boolean ( 'is_completely_paid' ) ;

			$t -> foreign ( 'customer_id' )
			-> references ( 'id' )
			-> on ( 'customers' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;

			$t -> foreign ( 'rep_id' )
			-> references ( 'id' )
			-> on ( 'users' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;
		} ) ;

		Schema::create ( 'selling_items' , function($t)
		{
			$t -> increments ( 'id' ) ;
			$t -> integer ( 'selling_invoice_id' ) -> unsigned () ;
			$t -> integer ( 'item_id' ) -> unsigned () ;
			$t -> double ( 'price' ) -> nullable () ;
			$t -> double ( 'paid_quantity' ) -> nullable () ;
			$t -> double ( 'free_quantity' ) -> nullable () ;
			$t -> double ( 'good_return_price' ) -> nullable () ;
			$t -> double ( 'good_return_quantity' ) -> nullable () ;
			$t -> double ( 'company_return_price' ) -> nullable () ;
			$t -> double ( 'company_return_quantity' ) -> nullable () ;

			$t -> foreign ( 'selling_invoice_id' )
			-> references ( 'id' )
			-> on ( 'selling_invoices' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;

			$t -> foreign ( 'item_id' )
			-> references ( 'id' )
			-> on ( 'items' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;
		} ) ;

		Schema::create ( 'finance_transfers' , function ($t)
		{
			$t -> increments ( 'id' ) ;
			$t -> integer ( 'from_id' ) -> unsigned () ;
			$t -> integer ( 'to_id' ) -> unsigned () ;
			$t -> dateTime ( 'date_time' ) ;
			$t -> double ( 'amount' ) ;
			$t -> text ( 'description' ) -> nullable () ;

			$t -> foreign ( 'from_id' )
			-> references ( 'id' )
			-> on ( 'finance_accounts' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;

			$t -> foreign ( 'to_id' )
			-> references ( 'id' )
			-> on ( 'finance_accounts' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;
		} ) ;

		Schema::create ( 'system_settings' , function($t)
		{
			$t -> increments ( 'id' ) ;
			$t -> integer ( 'system_settable_id' ) -> unsigned () ;
			$t -> text ( 'value' ) ;
		} ) ;

		Schema::create ( 'buying_invoice_finance_transfer' , function($t)
		{
			$t -> integer ( 'buying_invoice_id' ) -> unsigned () ;
			$t -> integer ( 'finance_transfer_id' ) -> unsigned () ;

			$t -> foreign ( 'buying_invoice_id' )
			-> references ( 'id' )
			-> on ( 'buying_invoices' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;

			$t -> foreign ( 'finance_transfer_id' )
			-> references ( 'id' )
			-> on ( 'finance_transfers' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;
		} ) ;

		Schema::create ( 'finance_transfer_selling_invoice' , function($t)
		{
			$t -> integer ( 'finance_transfer_id' ) -> unsigned () ;
			$t -> integer ( 'selling_invoice_id' ) -> unsigned () ;

			$t -> foreign ( 'finance_transfer_id' )
			-> references ( 'id' )
			-> on ( 'finance_transfers' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;

			$t -> foreign ( 'selling_invoice_id' )
			-> references ( 'id' )
			-> on ( 'selling_invoices' )
			-> onUpdate ( 'cascade' )
			-> onDelete ( 'cascade' ) ;
		} ) ;
	}

	public function down ()
	{
		Schema::dropIfExists ( 'finance_transfer_selling_invoice' ) ;
		Schema::dropIfExists ( 'buying_invoice_finance_transfer' ) ;
		Schema::dropIfExists ( 'system_settings' ) ;
		Schema::dropIfExists ( 'finance_transfers' ) ;
		Schema::dropIfExists ( 'selling_items' ) ;
		Schema::dropIfExists ( 'selling_invoices' ) ;
		Schema::dropIfExists ( 'transfer_details' ) ;
		Schema::dropIfExists ( 'transfers' ) ;
		Schema::dropIfExists ( 'buying_items' ) ;
		Schema::dropIfExists ( 'buying_invoices' ) ;
		Schema::dropIfExists ( 'stock_details' ) ;
		Schema::dropIfExists ( 'stocks' ) ;
		Schema::dropIfExists ( 'customers' ) ;
		Schema::dropIfExists ( 'routes' ) ;
		Schema::dropIfExists ( 'vendors' ) ;
		Schema::dropIfExists ( 'finance_accounts' ) ;
		Schema::dropIfExists ( 'banks' ) ;
		Schema::dropIfExists ( 'items' ) ;
		Schema::dropIfExists ( 'ability_user' ) ;
		Schema::dropIfExists ( 'users' ) ;
	}

}
