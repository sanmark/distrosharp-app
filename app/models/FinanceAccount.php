<?php

namespace Models ;

class FinanceAccount extends BaseEntity implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function bank ()
	{

		return $this -> belongsTo ( 'Models\Bank' ) ;
	}

	public function financeAccountVerifications ()
	{
		return $this -> hasMany ( 'Models\FinanceAccountVerification' ) ;
	}

	public function financeAccountVerificationsBefore ( $dateTime )
	{
		return $this -> hasMany ( 'Models\FinanceAccountVerification' )
		-> where ( 'date_time' , '<' , $dateTime )
		-> get () ;
	}

	public function financeAccountVerificationAt ( $dateTime )
	{
		return $this -> hasMany ( 'Models\FinanceAccountVerification' )
		-> where ( 'date_time' , '=' , $dateTime )
		-> first () ;
	}

	public function incomingFinanceTransfers ()
	{
		return $this -> hasMany ( 'Models\FinanceTransfer' , 'to_id' ) ;
	}

	public function outgoingFinanceTransfers ()
	{
		return $this -> hasMany ( 'Models\FinanceTransfer' , 'from_id' ) ;
	}

	public function financeTransfers ()
	{
		$incomingTransfers	 = $this -> incomingFinanceTransfers ;
		$outgoingTransfers	 = $this -> outgoingFinanceTransfers ;

		$financeTransfers = $incomingTransfers -> merge ( $outgoingTransfers ) ;

		return $financeTransfers ;
	}

	public function save ( array $options = array () )
	{
		$this -> validateForSave () ;
		parent::save ( $options ) ;
	}

	public function update ( array $attributes = array () )
	{
		$this -> validateForUpdate () ;
		parent::update ( $attributes ) ;
	}

	public function validateForUpdate ()
	{
		$data		 = $this -> toArray () ;
		$rules		 = [
			'name' => [
				'required'
			]
		] ;
		$validator	 = \Validator::make ( $data , $rules ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

	public function validateForSave ()
	{
		$data		 = $this -> toArray () ;
		$rules		 = [
			'name' => [
				'required'
			]
		] ;
		$validator	 = \Validator::make ( $data , $rules ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

	public static function filter ( $filterValues )
	{
		$requestObject = new FinanceAccount() ;

		if ( count ( $filterValues ) > 0 )
		{
			$name		 = $filterValues [ 'name' ] ;
			$bankId		 = $filterValues [ 'bank_id' ] ;
			$isActive	 = $filterValues [ 'is_active' ] ;

			if ( strlen ( $name ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'name' , 'LIKE' , "%$name%" ) ;
			}
			if ( ! empty ( $bankId ) )
			{
				if ( $bankId == 'none' )
				{
					$requestObject = $requestObject -> whereNull ( 'bank_id' ) ;
				} else
				{
					$requestObject = $requestObject -> where ( 'bank_id' , '=' , $bankId ) ;
				}
			}
			if ( $isActive != '' )
			{
				$requestObject = $requestObject -> where ( 'is_active' , '=' , $isActive ) ;
			}
		}
		return $requestObject -> where ( 'is_in_house' , '=' , 1 ) -> get () ;
	}

	public function bankReportFilter ( $filterValues )
	{

		$viewDateTime = $filterValues[ 'datetime' ] ;

		$viewDateTime = \NullHelper::ifNullEmptyOrWhitespace ( $viewDateTime , date ( 'Y-m-d H:i:s' ) ) ;

		$viewDateTime = \DateTimeHelper::convertTextToFormattedDateTime ( $viewDateTime , 'Y-m-d H:i:s' ) ;

		$confirmDateTime = $this -> getLastConfirmDateBefore ( $viewDateTime ) ;

		$financeTransfers = $this -> financeTransfers () ;


		if ( $confirmDateTime == NULL )
		{
			$financeTransfers = $financeTransfers -> filter ( function($financeTransfer) use ($viewDateTime)
			{

				if ( $financeTransfer -> date_time < $viewDateTime )
				{

					return TRUE ;
				}
			} ) ;
		} else
		{
			$financeTransfers = $financeTransfers -> filter ( function($financeTransfer) use($confirmDateTime , $viewDateTime)
			{
				if ( $confirmDateTime < ($financeTransfer -> date_time) && ($financeTransfer -> date_time) < $viewDateTime )
				{
					return TRUE ;
				}
			} ) ;
		}

		$financeTransfers -> sortBy ( 'date_time' ) ;

		return $financeTransfers ;
	}

	public function getFinanceAccountStartBalance ( $dateTime )
	{
		$financeAccountVerification = $this -> financeAccountVerificationAt ( $dateTime ) ;

		$amount = \NullHelper::ifNullEmptyOrWhitespace ( $financeAccountVerification[ 'amount' ] , 0 ) ;

		return $amount ;
	}

	public function getFinanceAccountEndBalance ( $lastConfirmDateTime , $transferData )
	{
		$financeAccountVerification = $this -> financeAccountVerificationAt ( $lastConfirmDateTime ) ;

		$amount = \NullHelper::ifNullEmptyOrWhitespace ( $financeAccountVerification[ 'amount' ] , 0 ) ;

		foreach ( $transferData as $transfer )
		{
			if ( $this -> id == $transfer -> from_id )
			{
				$amount = ($amount) - ($transfer -> amount) ;
			}
			if ( $this -> id == $transfer -> to_id )
			{
				$amount = ($amount) + ($transfer -> amount) ;
			}
		}

		return $amount ;
	}

	public function getLastConfirmDateBefore ( $dateTime )
	{
		$lastVerificationBefore	 = $this -> lastFinanceAccountVerificationBefore ( $dateTime ) ;
		$lastConfirmDateTime	 = $lastVerificationBefore[ 'date_time' ] ;
		return $lastConfirmDateTime ;
	}

	public function getLastConfirmDate ()
	{
		$lastConfirmDateTime = $this -> getLastConfirmDateBefore ( date ( 'Y-m-d H:i:s' ) ) ;
		return $lastConfirmDateTime ;
	}

	public function verifyFinanceAccountBalance ( $viewDateTime )
	{

		$financeAccountConfirmation = new \Models\FinanceAccountVerification() ;

		$financeAccountConfirmation -> finance_account_id	 = $this -> id ;
		$financeAccountConfirmation -> date_time			 = $viewDateTime ;
		$financeAccountConfirmation -> amount				 = \Input::get ( 'endBalance' ) ;

		$financeAccountConfirmation -> save () ;
	}

	public function lastFinanceAccountVerification ()
	{
		$this -> load ( 'financeAccountVerifications' ) ;

		$financeAccountVerifications	 = $this -> financeAccountVerifications ;
		$lastFinanceAccountVerification	 = $financeAccountVerifications
		-> sortByDesc ( 'date_time' )
		-> first () ;

		return $lastFinanceAccountVerification ;
	}

	public function lastFinanceAccountVerificationBefore ( $dateTime )
	{
		$financeAccountVerificationsBefore = $this -> financeAccountVerificationsBefore ( $dateTime ) ;

		$lastFinanceAccountVerificationBefore = $financeAccountVerificationsBefore
		-> sortByDesc ( 'date_time' )
		-> first () ;

		return $lastFinanceAccountVerificationBefore ;
	}

}
