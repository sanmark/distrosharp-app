<?php

namespace Models ;

class SellingInvoice extends BaseEntity implements \Interfaces\iEntity
{

	public function customer ()
	{
		return $this -> belongsTo ( 'Models\Customer' ) ;
	}

	public function rep ()
	{
		return $this -> belongsTo ( 'User' , 'rep_id' ) ;
	}

	public static function ageCreditFilter ( $filterValues )
	{
		$requestObject = new \Models\SellingInvoice() ;

		if ( count ( $filterValues ) > 0 )
		{
			$repId		 = $filterValues[ 'rep' ] ;
			$customerId	 = $filterValues[ 'customer' ] ;
			$ageDays	 = $filterValues[ 'age_by_days' ] ;
			if ( strlen ( $repId ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'rep_id' , '=' , $repId ) ;
			}
			if ( strlen ( $customerId ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'customer_id' , '=' , $customerId ) ;
			}
			if ( strlen ( $ageDays ) > 0 )
			{
				$DateTime		 = date ( 'Y-m-d H:i:s' , strtotime ( "now -$ageDays days" ) ) ;
				$requestObject	 = $requestObject -> where ( 'date_time' , '<' , $DateTime ) ;
			}
		}
		return $requestObject -> where ( 'is_completely_paid' , '=' , FALSE ) -> get () ;
	}

	public function sellingItems ()
	{
		return $this -> hasMany ( 'Models\SellingItem' ) ;
	}

	public function financeTransfers ()
	{
		return $this -> belongsToMany ( 'Models\FinanceTransfer' ) -> withPivot ( 'paid_invoice_id' ) ;
	}

	public function financeTransfersPaidByThis ()
	{
		return $this -> belongsToMany ( 'Models\FinanceTransfer' , 'finance_transfer_selling_invoice' , 'paid_invoice_id' )
				-> withPivot ( [
					'selling_invoice_id'
				] ) ;
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

	public function getCostofSoldGoods ()
	{
		$this -> load ( 'sellingItems' ) ;
		$sellingItems	 = $this -> sellingItems ;
		$costOfSoldGoods = 0 ;

		foreach ( $sellingItems as $sellingItem )
		{
			$item					 = $sellingItem -> item ;
			$current_buying_price	 = $item -> current_buying_price ;
			$paidQuantity			 = $sellingItem -> paid_quantity ;
			$freeQuantity			 = $sellingItem -> free_quantity ;
			$allQuantity			 = $paidQuantity + $freeQuantity ;

			$costOfSoldGoods += $current_buying_price * $allQuantity ;
		}

		return $costOfSoldGoods ;
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

	public function getPaymentValueByCash ()
	{
		$paymentValueByCash = 0 ;

		$this -> load ( 'financeTransfers' ) ;

		$financeTransfers = $this -> financeTransfers ;

		foreach ( $financeTransfers as $financeTransfer )
		{
			if ( $financeTransfer -> isCash () )
			{
				$paymentValueByCash += $financeTransfer -> amount ;
			} else
			{
				$paymentValueByCash += 0 ;
			}
		}

		return $paymentValueByCash ;
	}

	public function getPaymentValueByCheque ()
	{
		$paymentValueByCheque = 0 ;

		$this -> load ( 'financeTransfers' ) ;

		$financeTransfers = $this -> financeTransfers ;

		foreach ( $financeTransfers as $financeTransfer )
		{
			if ( $financeTransfer -> isCheque () )
			{
				$paymentValueByCheque += $financeTransfer -> amount ;
			} else
			{
				$paymentValueByCheque += 0 ;
			}
		}

		return $paymentValueByCheque ;
	}

	public function getInvoiceCredit ( $byCash = null , $byCheque = null , $invoiceTotal = null )
	{
		if ( is_null ( $byCash ) )
		{
			$byCash = $this -> getPaymentValueByCash () ;
		}

		if ( is_null ( $byCheque ) )
		{
			$byCheque = $this -> getPaymentValueByCheque () ;
		}

		if ( is_null ( $invoiceTotal ) )
		{
			$invoiceTotal = $this -> getInvoiceTotal () ;
		}

		$discount = $this -> discount ;

		$paidAmount = $byCash + $byCheque + $discount ;

		$credit = $invoiceTotal - $paidAmount ;

		return $credit ;
	}

	public function getSubTotal ( $paymentByCash = null , $paymentByCheque = null , $invoiceCredit = null )
	{
		if ( is_null ( $paymentByCash ) )
		{
			$paymentByCash = $this -> getPaymentValueByCash () ;
		}

		if ( is_null ( $paymentByCheque ) )
		{
			$paymentByCheque = $this -> getPaymentValueByCheque () ;
		}

		if ( is_null ( $invoiceCredit ) )
		{
			$invoiceCredit = $this -> getInvoiceCredit () ;
		}

		$subTotal = $paymentByCash + $paymentByCheque + $invoiceCredit + $this -> discount ;

		return $subTotal ;
	}

	public function getTotal ( $paymentCash = null , $paymentCheque = null , $invoiceCredit = null )
	{
		if ( is_null ( $paymentCash ) )
		{
			$paymentCash = $this -> getPaymentValueByCash () ;
		}

		if ( is_null ( $paymentCheque ) )
		{
			$paymentCheque = $this -> getPaymentValueByCheque () ;
		}

		if ( is_null ( $invoiceCredit ) )
		{
			$invoiceCredit = $this -> getInvoiceCredit () ;
		}

		$amount = $paymentCash + $paymentCheque + $invoiceCredit ;

		return $amount ;
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
				'unique:selling_invoices,printed_invoice_number'
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
				'unique:selling_invoices,printed_invoice_number,' . $this -> id
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

		$requestObject -> get () ;

		return $requestObject -> get () ;
	}

	private static function prepareRequestObjectForFiletering ( SellingInvoice $requestObject , array $filterValues )
	{
		if ( count ( $filterValues ) > 0 )
		{
			$id						 = \ArrayHelper::getValueIfKeyExistsOrNull ( $filterValues , 'id' ) ;
			$dateTimeFrom			 = \ArrayHelper::getValueIfKeyExistsOrNull ( $filterValues , 'date_time_from' ) ;
			$dateTimeTo				 = \ArrayHelper::getValueIfKeyExistsOrNull ( $filterValues , 'date_time_to' ) ;
			$customerId				 = \ArrayHelper::getValueIfKeyExistsOrNull ( $filterValues , 'customer_id' ) ;
			$repId					 = \ArrayHelper::getValueIfKeyExistsOrNull ( $filterValues , 'rep_id' ) ;
			$printedInvoiceNumber	 = \ArrayHelper::getValueIfKeyExistsOrNull ( $filterValues , 'printed_invoice_number' ) ;
			$isCompletelyPaid		 = \ArrayHelper::getValueIfKeyExistsOrNull ( $filterValues , 'is_completely_paid' ) ;
			$isDiscount				 = \ArrayHelper::getValueIfKeyExistsOrNull ( $filterValues , 'discount' ) ;

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

			if ( strlen ( $isDiscount ) > 0 )
			{
				if ( $isDiscount )
				{
					$requestObject = $requestObject -> where ( 'discount' , '>' , 0 ) ;
				}
				if ( !$isDiscount )
				{ 
					$requestObject = $requestObject -> where ( 'discount' , '=' , null ) ;
				}
			}
		}
		return $requestObject ;
	}

	public static function filterForSalesSummary ( $filterValues )
	{
		$requestObject = new SellingInvoice() ;

		$requestObject = self::prepareRequestObjectForfilterForSalesSummary ( $requestObject , $filterValues ) ;

		$requestObject = $requestObject -> with ( ['customer' , 'rep' , 'financeTransfers' , 'sellingItems' ] ) ;

		return $requestObject -> get () ;
	}

	private static function prepareRequestObjectForfilterForSalesSummary ( SellingInvoice $requestObject , array $filterValues )
	{

		if ( count ( $filterValues ) > 0 )
		{
			$route_id		 = $filterValues[ 'route_id' ] ;
			$customer_id	 = $filterValues[ 'customer_id' ] ;
			$rep_id			 = $filterValues[ 'rep_id' ] ;
			$date_from		 = $filterValues[ 'date_from' ] ;
			$date_to		 = $filterValues[ 'date_to' ] ;
			$invoice_number	 = $filterValues[ 'invoice_number' ] ;

			if ( strlen ( $customer_id ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'customer_id' , '=' , $customer_id ) ;
			}
			if ( strlen ( $route_id ) > 0 )
			{
				$customers = \Models\Customer::where ( 'route_id' , '=' , $route_id ) -> lists ( 'id' ) ;
				if ( ! $customers )
				{
					$customers [ 0 ] = NULL ;
				}
				$requestObject = $requestObject -> whereIn ( 'customer_id' , $customers ) ;
			}
			if ( strlen ( $rep_id ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'rep_id' , '=' , $rep_id ) ;
			}

			if ( strlen ( $date_from ) > 0 && strlen ( $date_to ) > 0 )
			{
				$date_from	 = $date_from . " 00:00:00" ;
				$date_to	 = $date_to . " 23:59:59" ;

				$datesAndTimes = [$date_from , $date_to ] ;

				$requestObject = $requestObject -> whereBetween ( 'date_time' , $datesAndTimes ) ;
			} elseif ( strlen ( $date_from ) > 0 )
			{
				$date_from		 = $date_from . " 00:00:00" ;
				$requestObject	 = $requestObject -> where ( 'date_time' , '=' , $date_from ) ;
			} elseif ( strlen ( $date_to ) > 0 )
			{
				$date_to		 = $date_to . " 23:59:59" ;
				$requestObject	 = $requestObject -> where ( 'date_time' , '=' , $date_to ) ;
			}

			if ( strlen ( $invoice_number ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'printed_invoice_number' , '=' , $invoice_number ) ;
			}
		} else
		{

			$time_1 = date ( 'Y-m-d' , mktime ( 0 , 0 , 0 , date ( 'm' ) , date ( 'd' ) - 3 , date ( 'Y' ) ) ) . " 00:00:00" ;

			$time_2 = date ( 'Y-m-d' ) . " 23:59:59" ;

			$requestObject = $requestObject -> whereBetween ( 'date_time' , array ( $time_1 , $time_2 ) ) ;
		}


		return $requestObject ;
	}

	public function getLateCreditInvoices ()
	{
		return \SellingInvoiceButler::getLateCreditInvoices ( $this -> id ) ;
	}

	public function getLateCreditPayments ()
	{
		return \SellingInvoiceButler::getLateCreditPayments ( $this -> id ) ;
	}

	public function getTotalCollection ( $lateCreditPaymentsCash = null , $lateCreditPaymentsCheque = null , $paymentByCash = null , $paymentByCheque = null )
	{
		if ( is_null ( $lateCreditPaymentsCash ) )
		{
			$lateCreditPaymentsCash = $this -> getLateCreditPayments ()[ 'amount_cash' ] ;
		}

		if ( is_null ( $lateCreditPaymentsCheque ) )
		{
			$lateCreditPaymentsCheque = $this -> getLateCreditPayments ()[ 'amount_cheque' ] ;
		}

		if ( is_null ( $paymentByCash ) )
		{
			$paymentByCash = $this -> getPaymentValueByCash () ;
		}

		if ( is_null ( $paymentByCheque ) )
		{
			$paymentByCheque = $this -> getPaymentValueByCheque () ;
		}

		$result = $lateCreditPaymentsCash + $lateCreditPaymentsCheque + $paymentByCash + $paymentByCheque ;

		return $result ;
	}

}
