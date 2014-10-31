<?php

class TransfersSeeder extends Seeder
{

	public function run ()
	{
		$transfers = [
			[
				'from_stock_id'		 => 1 ,
				'to_stock_id'		 => 3 ,
				'date_time'			 => '2014-10-08 15:25:25' ,
				'description'		 => 'Demo transfer.' ,
				'transfer_details'	 => [
					[
						'item_id'	 => 1 ,
						'quantity'	 => 10 ,
					] ,
					[
						'item_id'	 => 2 ,
						'quantity'	 => 20 ,
					] ,
					[
						'item_id'	 => 3 ,
						'quantity'	 => 30 ,
					] ,
//					[
//						'item_id'	 => NULL ,
//						'quantity'	 => NULL ,
//					] ,
				] ,
			] ,
			[
				'from_stock_id'		 => 2 ,
				'to_stock_id'		 => 1 ,
				'date_time'			 => '2014-10-08 15:27:01' ,
				'description'		 => 'To adjust main account imbalance.' ,
				'transfer_details'	 => [
					[
						'item_id'	 => 1 ,
						'quantity'	 => 1 ,
					] ,
					[
						'item_id'	 => 2 ,
						'quantity'	 => 2 ,
					] ,
					[
						'item_id'	 => 3 ,
						'quantity'	 => 3 ,
					] ,
//					[
//						'item_id'	 => NULL ,
//						'quantity'	 => NULL ,
//					] ,
				] ,
			] ,
//			[
//				'from_stock_id'		 => NULL ,
//				'to_stock_id'		 => NULL ,
//				'date_time'			 => NULL ,
//				'description'		 => NULL ,
//				'transfer_details'	 => [
//					[
//						'item_id'	 => NULL ,
//						'quantity'	 => NULL ,
//					] ,
////					[
////						'item_id'	 => NULL ,
////						'quantity'	 => NULL ,
////					] ,
//				] ,
//			] ,
			] ;

		foreach ( $transfers as $transfer )
		{
			$transferO = new Models\Transfer() ;

			$transfer[ 'date_time' ] = DateTimeHelper::convertTextToFormattedDateTime ( $transfer[ 'date_time' ] ) ;

			$transferO -> from_stock_id	 = $transfer[ 'from_stock_id' ] ;
			$transferO -> to_stock_id	 = $transfer[ 'to_stock_id' ] ;
			$transferO -> date_time		 = $transfer[ 'date_time' ] ;
			$transferO -> description	 = $transfer[ 'description' ] ;

			$transferO -> save () ;

			$this -> saveTransferDetails ( $transferO , $transfer[ 'transfer_details' ] ) ;
		}
	}

	public function saveTransferDetails ( $transfer , $transferDetails )
	{
		foreach ( $transferDetails as $transferDetail )
		{
			$transferDetailO = new \Models\TransferDetail() ;

			$transferDetailO -> transfer_id	 = $transfer -> id ;
			$transferDetailO -> item_id		 = $transferDetail[ 'item_id' ] ;
			$transferDetailO -> quantity	 = $transferDetail[ 'quantity' ] ;

			$transferDetailO -> save () ;
		}
	}

}
