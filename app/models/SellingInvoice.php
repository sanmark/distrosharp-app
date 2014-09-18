<?php

namespace Models ;

class SellingInvoice extends BaseEntity implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function customer ()
	{
		return $this -> belongsTo ( 'Models\Customer' ) ;
	}

	public function rep ()
	{
		return $this -> belongsTo ( 'User' , 'rep_id' ) ;
	}

	public function sellingItems ()
	{
		return $this -> hasMany ( 'Models\SellingItem' ) ;
	}

	public function financeTransfers ()
	{
		return $this -> belongsToMany ( 'Models\FinanceTransfer' ) ;
	}

	public function sellingItemById ( $sellingItemId )
	{
		return $this -> hasOne ( 'Models\SellingItem' )
		-> where ( 'id' , '=' , $sellingItemId )
		-> first () ;
	}

	public function getInvoiceTotal ()
	{
		$this -> load ( 'sellingItems' ) ;

		$sellingItems = $this -> sellingItems ;

		$invoiceTotal = 0 ;

		foreach ( $sellingItems as $sellingItem )
		{
			$price					 = $sellingItem -> price ;
			$paidQuantity			 = $sellingItem -> paid_quantity ;
			$goodReturnPrice		 = $sellingItem -> good_return_price ;
			$goodReturnQuantity		 = $sellingItem -> good_return_quantity ;
			$companyReturnPrice		 = $sellingItem -> company_return_price ;
			$companyReturnQuantity	 = $sellingItem -> company_return_quantity ;

			$itemTotal = ($price * $paidQuantity) - (($goodReturnPrice * $goodReturnQuantity) + ($companyReturnPrice * $companyReturnQuantity)) ;

			$invoiceTotal += $itemTotal ;
		}

		return $invoiceTotal ;
	}

	public function getTotalPaymentValue ()
	{
		$totalPaymentValue = 0 ;

		$this -> load ( 'financeTransfers' ) ;

		$financeTransfers = $this -> financeTransfers ;

		foreach ( $financeTransfers as $financeTransfer )
		{
			$totalPaymentValue += $financeTransfer -> amount ;
		}

		return $totalPaymentValue ;
	}

	public function getInvoiceBalance ()
	{
		$invoiceTotal		 = $this -> getInvoiceTotal () ;
		$totalPaymentValue	 = $this -> getTotalPaymentValue () ;
		$discount			 = $this -> discount ;

		$invoiceBalance = $invoiceTotal - $totalPaymentValue - $discount ;

		return $invoiceBalance ;
	}

	public function isInvoiceBalanceZero ()
	{
		$invoiceBalance = $this -> getInvoiceBalance () ;

		if ( $invoiceBalance == 0 )
		{
			return TRUE ;
		}

		return FALSE ;
	}

	public function save ( array $options = array () )
	{
		$this -> date_time			 = \DateTimeHelper::convertTextToFormattedDateTime ( $this -> date_time ) ;
		$this -> is_completely_paid	 = \NullHelper::zeroIfNull ( $this -> is_completely_paid ) ;

		$this -> validateForSave () ;

		parent::save ( $options ) ;
	}

	public function update ( array $attributes = array () )
	{
		$this -> date_time			 = \DateTimeHelper::convertTextToFormattedDateTime ( $this -> date_time ) ;
		$this -> is_completely_paid	 = \NullHelper::zeroIfNull ( $this -> is_completely_paid ) ;

		$this -> validateForUpdate () ;

		parent::save ( $attributes ) ;
	}

	private function validateForSave ()
	{
		$data = $this -> toArray () ;

		$rules = [
			'date_time'				 => [
				'required' ,
				'date_format:Y-m-d H:i:s'
			] ,
			'customer_id'			 => [
				'required' ,
				'numeric'
			] ,
			'rep_id'				 => [
				'required' ,
				'numeric'
			] ,
			'printed_invoice_number' => [
				'required' ,
			] ,
			'discount'				 => [
				'numeric'
			] ,
			'is_completely_paid'	 => [
				'required' ,
				'numeric'
			]
		] ;

		$validator = \Validator::make ( $data , $rules ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

	private function validateForUpdate ()
	{
		$data = $this -> toArray () ;

		$rules = [
			'date_time'				 => [
				'required' ,
				'date_format:Y-m-d H:i:s'
			] ,
			'customer_id'			 => [
				'required' ,
				'numeric'
			] ,
			'rep_id'				 => [
				'required' ,
				'numeric'
			] ,
			'printed_invoice_number' => [
				'required' ,
			] ,
			'discount'				 => [
				'numeric'
			] ,
			'is_completely_paid'	 => [
				'required' ,
				'numeric'
			]
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
		$requestObject = new SellingInvoice() ;

		$requestObject = self::prepareRequestObjectForFiletering ( $requestObject , $filterValues ) ;

		$requestObject	 = $requestObject -> with ( 'customer' ) ;
		$requestObject	 = $requestObject -> with ( 'rep' ) ;

		return $requestObject -> get () ;
	}

	private static function prepareRequestObjectForFiletering ( SellingInvoice $requestObject , array $filterValues )
	{
		if ( count ( $filterValues ) > 0 )
		{
			$id						 = $filterValues[ 'id' ] ;
			$dateTimeFrom			 = $filterValues[ 'date_time_from' ] ;
			$dateTimeTo				 = $filterValues[ 'date_time_to' ] ;
			$customerId				 = $filterValues[ 'customer_id' ] ;
			$repId					 = $filterValues[ 'rep_id' ] ;
			$printedInvoiceNumber	 = $filterValues[ 'printed_invoice_number' ] ;
			$isCompletelyPaid		 = $filterValues[ 'is_completely_paid' ] ;


			if ( strlen ( $id ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'id' , 'LIKE' , '%' . $id . '%' ) ;
			}

			if ( strlen ( $dateTimeFrom ) > 0 && strlen ( $dateTimeTo ) > 0 )
			{
				$dateTimeFrom	 = \DateTimeHelper::convertTextToFormattedDateTime ( $dateTimeFrom ) ;
				$dateTimeTo		 = \DateTimeHelper::convertTextToFormattedDateTime ( $dateTimeTo ) ;

				$datesAndTimes = [$dateTimeFrom , $dateTimeTo ] ;

				$requestObject = $requestObject -> whereBetween ( 'date_time' , $datesAndTimes ) ;
			} elseif ( strlen ( $dateTimeFrom ) > 0 )
			{
				$dateTimeFrom	 = \DateTimeHelper::convertTextToFormattedDateTime ( $dateTimeFrom ) ;
				$requestObject	 = $requestObject -> where ( 'date_time' , '=' , $dateTimeFrom ) ;
			} elseif ( strlen ( $dateTimeTo ) > 0 )
			{
				$dateTimeTo		 = \DateTimeHelper::convertTextToFormattedDateTime ( $dateTimeTo ) ;
				$requestObject	 = $requestObject -> where ( 'date_time' , '=' , $dateTimeTo ) ;
			}

			if ( strlen ( $customerId ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'customer_id' , '=' , $customerId ) ;
			}

			if ( strlen ( $repId ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'rep_id' , '=' , $repId ) ;
			}

			if ( strlen ( $printedInvoiceNumber ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'printed_invoice_number' , 'LIKE' , '%' . $printedInvoiceNumber . '%' ) ;
			}

			if ( strlen ( $isCompletelyPaid ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'is_completely_paid' , '=' , $isCompletelyPaid ) ;
			}
		}

		return $requestObject ;
	}

}
