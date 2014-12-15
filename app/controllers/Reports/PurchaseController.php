<?php

namespace Controllers\Reports ;

class PurchaseController extends \Controller
{ 
	
	public function home ()
	{
		$filterValues		 = \Input::all () ;
		$buyingInvoiceRows	 = \Models\BuyingInvoice::filter ( $filterValues ) ;

		$data = [ ] ;

		$id				 = \InputButler::get ( 'id' ) ;
		$vendorId		 = \InputButler::get ( 'vendor_id' ) ;
		$fromDate		 = \InputButler::get ( 'from_date_time' ) ;
		$toDate			 = \InputButler::get ( 'to_date_time' ) ;
		$isPaid			 = \InputButler::get ( 'is_paid' ) ;
		$sortBy			 = \InputButler::get ( 'sort_by' ) ;
		$sortOrder		 = \InputButler::get ( 'sort_order' ) ;
		$stockId		 = \InputButler::get ( 'stock_id' ) ;
		$vendors		 = \Models\BuyingInvoice::distinct () -> lists ( 'vendor_id' ) ;
		$vendorSelectBox = \Models\Vendor::getArrayForHtmlSelectByIds ( 'id' , 'name' , $vendors , [ NULL => 'Any' ] ) ;
		$stockSelectBox	 = \Models\Stock::getArrayForHtmlSelect ( 'id' , 'name' , [ '' => 'Any' ] ) ;

		$lineTotalArray = [ ] ;
		foreach ( $buyingInvoiceRows as $key )
		{
			$price			 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $key -> id )
				-> lists ( 'price' ) ;
			$quantity		 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $key -> id )
				-> lists ( 'quantity' ) ;
			$finalLineTotal	 = 0 ;
			for ( $i = 0 ; $i < count ( $quantity ) ; $i ++ )
			{
				$sum[ $key -> id ]	 = $price[ $i ] * $quantity[ $i ] ;
				$finalLineTotal		 = $finalLineTotal + $sum[ $key -> id ] ;
			}
			$lineTotalArray[ $key -> id ] = $finalLineTotal ;
		}
		$data[ 'buyingInvoiceRows' ] = $buyingInvoiceRows ;
		$data[ 'id' ]				 = $id ;
		$data[ 'vendorId' ]			 = $vendorId ;
		$data[ 'fromDate' ]			 = $fromDate ;
		$data[ 'toDate' ]			 = $toDate ;
		$data[ 'isPaid' ]			 = $isPaid ;
		$data[ 'sortBy' ]			 = $sortBy ;
		$data[ 'sortOrder' ]		 = $sortOrder ;
		$data[ 'stockId' ]			 = $stockId ;
		$data[ 'vendorSelectBox' ]	 = $vendorSelectBox ;
		$data[ 'stockSelectBox' ]	 = $stockSelectBox ;
		$data[ 'lineTotalArray' ]	 = $lineTotalArray ;
		return \View::make ( 'web.reports.purchases.home' , $data ) ;
	}
 
}
