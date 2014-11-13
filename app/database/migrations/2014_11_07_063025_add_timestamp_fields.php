<?php

use Illuminate\Database\Schema\Blueprint ;
use Illuminate\Database\Migrations\Migration ;

class AddTimestampFields extends Migration
{

	public function up ()
	{
		Schema::table ( 'ability_user' , function($t)
		{
			$t -> timestamps () ;
		} ) ;

		Schema::table ( 'activity_logs' , function($t)
		{
			$t -> timestamps () ;
		} ) ;

		Schema::table ( 'banks' , function($t)
		{
			$t -> timestamps () ;
		} ) ;

		Schema::table ( 'buying_invoices' , function($t)
		{
			$t -> timestamps () ;
		} ) ;

		Schema::table ( 'buying_items' , function($t)
		{
			$t -> timestamps () ;
		} ) ;

		Schema::table ( 'cheque_details' , function($t)
		{
			$t -> timestamps () ;
		} ) ;

		Schema::table ( 'customers' , function($t)
		{
			$t -> timestamps () ;
		} ) ;

		Schema::table ( 'finance_accounts' , function($t)
		{
			$t -> timestamps () ;
		} ) ;

		Schema::table ( 'finance_account_verifications' , function($t)
		{
			$t -> timestamps () ;
		} ) ;

		Schema::table ( 'finance_transfers' , function($t)
		{
			$t -> timestamps () ;
		} ) ;

		Schema::table ( 'items' , function($t)
		{
			$t -> timestamps () ;
		} ) ;

		Schema::table ( 'routes' , function($t)
		{
			$t -> timestamps () ;
		} ) ;

		Schema::table ( 'selling_invoices' , function($t)
		{
			$t -> timestamps () ;
		} ) ;

		Schema::table ( 'selling_items' , function($t)
		{
			$t -> timestamps () ;
		} ) ;

		Schema::table ( 'stocks' , function($t)
		{
			$t -> timestamps () ;
		} ) ;

		Schema::table ( 'stock_confirmations' , function($t)
		{
			$t -> timestamps () ;
		} ) ;

		Schema::table ( 'stock_confirmation_details' , function($t)
		{
			$t -> timestamps () ;
		} ) ;

		Schema::table ( 'stock_details' , function($t)
		{
			$t -> timestamps () ;
		} ) ;

		Schema::table ( 'system_settings' , function($t)
		{
			$t -> timestamps () ;
		} ) ;

		Schema::table ( 'transfers' , function($t)
		{
			$t -> timestamps () ;
		} ) ;

		Schema::table ( 'transfer_details' , function($t)
		{
			$t -> timestamps () ;
		} ) ;

		Schema::table ( 'vendors' , function($t)
		{
			$t -> timestamps () ;
		} ) ;
	}

	public function down ()
	{
		Schema::table ( 'vendors' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;

		Schema::table ( 'transfer_details' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;

		Schema::table ( 'transfers' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;

		Schema::table ( 'system_settings' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;

		Schema::table ( 'stock_details' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;

		Schema::table ( 'stock_confirmation_details' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;

		Schema::table ( 'stock_confirmations' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;

		Schema::table ( 'stocks' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;

		Schema::table ( 'selling_items' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;

		Schema::table ( 'selling_invoices' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;

		Schema::table ( 'routes' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;

		Schema::table ( 'items' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;

		Schema::table ( 'finance_transfers' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;

		Schema::table ( 'finance_account_verifications' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;

		Schema::table ( 'finance_accounts' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;

		Schema::table ( 'customers' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;

		Schema::table ( 'cheque_details' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;

		Schema::table ( 'buying_items' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;

		Schema::table ( 'buying_invoices' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;

		Schema::table ( 'banks' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;

		Schema::table ( 'activity_logs' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;

		Schema::table ( 'ability_user' , function($t)
		{
			$t -> dropColumn ( [
				'created_at' ,
				'updated_at' ,
			] ) ;
		} ) ;
	}

}
