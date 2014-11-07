<?php

namespace Models ;

class BuyingInvoice extends BaseEntity implements \Interfaces\iEntity
{

	public function stock ()
	{
		return $this -> belongsTo ( 'Models\Stock' ) ;
	}

	public function vendor ()
	{
		return $this -> belongsTo ( 'Models\Vendor' ) ;
	}

	public function buyingItems ()
	{
		return $this -> hasMany ( 'Models\BuyingItem' , 'invoice_id' ) ;
	}

	public function financeTransfers ()
	{
		return $this -> belongsToMany ( 'Models\FinanceTransfer' ) ;
	}

	public function save ( array $options = array () )
	{
		$this -> validateForSave () ;
		parent::save ( $options ) ;
	}

	private function validateForSave ()
	{
		$data = $this -> toArray () ;

		$rules = [
			'date_time'				 => [
				'required'
			] ,
			'vendor_id'				 => [
				'required' ,
			] ,
			'printed_invoice_num'	 => [
				'required' ,
			] ,
			'other_expenses_amount'	 => [
				'numeric' ,
			] ,
			'stock_id'				 => [
				'required' ,
			] ,
			] ;

		$validator = \Validator::make ( $data , $rules ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

	public function update ( array $attributes = array () )
	{
		$this -> validateForUpdate () ;
		parent::update ( $attributes ) ;
	}

	public function validateForUpdate ()
	{
		$data = $this -> toArray () ;

		$rules = [
			'date_time'				 => [
				'required' ,
			] ,
			'vendor_id'				 => [
				'required' ,
			] ,
			'printed_invoice_num'	 => [
				'required' ,
			] ,
			] ;

		$validator = \Validator::make ( $data , $rules ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

	public static function filter ( $filterValues )
	{
		$requestObject = new BuyingInvoice() ;

		if ( count ( $filterValues ) > 0 )
		{
			$id			 = $filterValues[ 'id' ] ;
			$vendorId	 = $filterValues[ 'vendor_id' ] ;
			$fromDate	 = $filterValues[ 'from_date_time' ] ;
			$toDate		 = $filterValues[ 'to_date_time' ] ;
			$isPaid		 = $filterValues[ 'is_paid' ] ;
			$sortBy		 = $filterValues[ 'sort_by' ] ;
			$sortOrder	 = $filterValues[ 'sort_order' ] ;
			$stockId	 = $filterValues[ 'stock_id' ] ;
			$minDate	 = $requestObject -> min ( 'date_time' ) ;
			$maxDate	 = $requestObject -> max ( 'date_time' ) ;

			if ( strlen ( $id ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'id' , '=' , $id ) ;
			}
			if ( strlen ( $vendorId ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'vendor_id' , '=' , $vendorId ) ;
			}
			if ( strlen ( $fromDate ) > 0 && strlen ( $toDate ) > 0 )
			{
				$requestObject = $requestObject -> whereBetween ( 'date_time' , [$fromDate , $toDate ] ) ;
			}
			if ( strlen ( $fromDate ) > 0 && strlen ( $toDate ) == 0 )
			{
				$requestObject = $requestObject -> whereBetween ( 'date_time' , [$fromDate , $maxDate ] ) ;
			}
			if ( strlen ( $fromDate ) == 0 && strlen ( $toDate ) > 0 )
			{
				$requestObject = $requestObject -> whereBetween ( 'date_time' , [$minDate , $toDate ] ) ;
			}
			if ( $stockId != '' )
			{
				$requestObject = $requestObject -> where ( 'stock_id' , '=' , $stockId ) ;
			}
			if ( $isPaid != '' )
			{
				$requestObject = $requestObject -> where ( 'completely_paid' , '=' , $isPaid ) ;
			}
			if ( strlen ( $sortBy ) > 0 && strlen ( $sortOrder ) > 0 )
			{
				$requestObject = $requestObject -> orderBy ( $sortBy , $sortOrder ) ;
			}
		}
		return $requestObject -> get () ;
	}

}
