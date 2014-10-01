<?php

namespace Models ;

class FinanceTransfer extends BaseEntity implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function toAccount ()
	{
		return $this -> belongsTo ( 'Models\FinanceAccount' , 'to_id' ) ;
	}

	public function fromAccount ()
	{
		return $this -> belongsTo ( 'Models\FinanceAccount' , 'from_id' ) ;
	}

	public function chequeDetail ()
	{
		return $this -> hasOne ( 'Models\ChequeDetail' ) ;
	}

	public function isCheque ()
	{
		if ( count ( $this -> chequeDetail ) > 0 )
		{
			return TRUE ;
		}

		return FALSE ;
	}

	public function isCash ()
	{
		if ( $this -> isCheque () )
		{
			return FALSE ;
		}

		return TRUE ;
	}

	public static function viewAllFilter ( $filterValues )
	{
		$requestObject = new FinanceTransfer () ;

		if ( count ( $filterValues ) > 0 )
		{
			$fromDate	 = $filterValues[ 'from_date' ] ;
			$toDate		 = $filterValues[ 'to_date' ] ;
			$amount		 = $filterValues[ 'amount' ] ;
			$compareSign = $filterValues[ 'compare_sign' ] ;
			$fromAccount = $filterValues[ 'from_account' ] ;
			$toAccount	 = $filterValues[ 'to_account' ] ;
			$minDate	 = $requestObject -> min ( 'date_time' ) ;
			$maxDate	 = $requestObject -> max ( 'date_time' ) ;

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
			if ( strlen ( $amount ) > 0 && strlen ( $compareSign ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'amount' , $compareSign , $amount ) ;
			}
			if ( strlen ( $fromAccount ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'from_id' , '=' , $fromAccount ) ;
			}
			if ( strlen ( $toAccount ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'to_id' , '=' , $toAccount ) ;
			}
		}
		return $requestObject -> get () ;
	}

	public static function filter ( $filterValues )
	{
		$requestObject = new FinanceTransfer() ;

		if ( count ( $filterValues ) > 1 )
		{
			$fromDate		 = $filterValues[ 'from_date' ] ;
			$toDate			 = $filterValues[ 'to_date' ] ;
			$amount			 = $filterValues[ 'amount' ] ;
			$compareSign	 = $filterValues[ 'compare_sign' ] ;
			$transferAccount = $filterValues[ 'transfer_account' ] ;
			$inOrOut		 = $filterValues[ 'in_or_out' ] ;
			$minDate		 = $requestObject -> min ( 'date_time' ) ;
			$maxDate		 = $requestObject -> max ( 'date_time' ) ;

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
			if ( strlen ( $amount ) > 0 && strlen ( $compareSign ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'amount' , $compareSign , $amount ) ;
			}

			if ( strlen ( $transferAccount ) > 0 )
			{
				$accountId = $transferAccount ;
			} else
			{
				$accountId = $filterValues[ 'id' ] ;
			}

			$firstResult	 = clone $requestObject ;
			$secondResult	 = clone $requestObject ;

			if ( $inOrOut == 'in' )
			{
				if ( strlen ( $transferAccount ) > 0 )
				{
					$firstResult1	 = $firstResult -> where ( 'from_id' , '=' , $accountId ) -> get () ;
					$secondResult1	 = $secondResult -> where ( 'to_id' , '=' , $accountId ) -> get () ;

					$result1		 = $firstResult1 -> merge ( $secondResult1 ) ;
					$inOutAccount	 = $filterValues[ 'id' ] ;
					$secondResult2	 = $result1 -> filter ( function($account2) use($inOutAccount)
					{
						if ( $account2 -> to_id == $inOutAccount )
						{
							return TRUE ;
						}
					} ) ;

					$result = $secondResult2 ;

					return $result ;
				} else
				{
					$firstResult1	 = $firstResult -> where ( 'from_id' , '=' , $filterValues[ 'id' ] ) -> get () ;
					$secondResult1	 = $secondResult -> where ( 'to_id' , '=' , $filterValues[ 'id' ] ) -> get () ;

					$result1		 = $firstResult1 -> merge ( $secondResult1 ) ;
					$accountId1		 = $filterValues[ 'id' ] ;
					$secondResult2	 = $result1 -> filter ( function($account2) use($accountId1)
					{
						if ( $account2 -> to_id == $accountId1 )
						{
							return TRUE ;
						}
					} ) ;

					$result = $secondResult2 ;

					return $result ;
				}
			} elseif ( $inOrOut == 'out' )
			{
				if ( strlen ( $transferAccount ) > 0 )
				{
					$firstResult1	 = $firstResult -> where ( 'from_id' , '=' , $accountId ) -> get () ;
					$secondResult1	 = $secondResult -> where ( 'to_id' , '=' , $accountId ) -> get () ;

					$result1		 = $firstResult1 -> merge ( $secondResult1 ) ;
					$inOutAccount	 = $filterValues[ 'id' ] ;
					$firstResult2	 = $result1 -> filter ( function($account1) use($inOutAccount)
					{
						if ( $account1 -> from_id == $inOutAccount )
						{
							return TRUE ;
						}
					} ) ;

					$result = $firstResult2 ;

					return $result ;
				} else
				{
					$firstResult1	 = $firstResult -> where ( 'from_id' , '=' , $filterValues[ 'id' ] ) -> get () ;
					$secondResult1	 = $secondResult -> where ( 'to_id' , '=' , $filterValues[ 'id' ] ) -> get () ;

					$result1		 = $firstResult1 -> merge ( $secondResult1 ) ;
					$accountId1		 = $filterValues[ 'id' ] ;
					$firstResult2	 = $result1 -> filter ( function($account1) use($accountId1)
					{
						if ( $account1 -> from_id == $accountId1 )
						{
							return TRUE ;
						}
					} ) ;

					$result = $firstResult2 ;

					return $result ;
				}
			} else
			{

				$firstResult1	 = $firstResult -> where ( 'from_id' , '=' , $filterValues[ 'id' ] ) -> get () ;
				$secondResult1	 = $secondResult -> where ( 'to_id' , '=' , $filterValues[ 'id' ] ) -> get () ;

				$result1 = $firstResult1 -> merge ( $secondResult1 ) ;

				$firstResult2 = $result1 -> filter ( function($account1) use($accountId)
				{
					if ( $account1 -> from_id == $accountId )
					{
						return TRUE ;
					}
				} ) ;

				$secondResult2 = $result1 -> filter ( function($account2) use($accountId)
				{
					if ( $account2 -> to_id == $accountId )
					{
						return TRUE ;
					}
				} ) ;

				$result = $firstResult2 -> merge ( $secondResult2 ) ;

				return $result ;
			}
		} else
		{
			$firstResult	 = $requestObject -> where ( 'from_id' , '=' , $filterValues[ 'id' ] ) -> get () ;
			$secondResult	 = $requestObject -> where ( 'to_id' , '=' , $filterValues[ 'id' ] ) -> get () ;

			$result = $firstResult -> merge ( $secondResult ) ;

			return $result ;
		}
	}

	public function save ( array $options = array () )
	{
		$this -> validateForSave () ;

		$this -> adjustAccountBalances () ;

		parent::save ( $options ) ;
	}

	public function update ( array $attributes = array () )
	{
		$this -> validateForUpdate () ;

		$this -> resetAccountBalances () ;
		$this -> adjustAccountBalances () ;

		parent::save ( $attributes ) ;
	}

	private function validateForSave ()
	{
		$data = $this -> toArray () ;

		$rules = [
			'date_time'	 => [
				'required'
			] ,
			'amount'	 => [
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
			'date_time'	 => [
				'required'
			] ,
			'amount'	 => [
				'required' ,
				'numeric'
			] ,
			'from_id'	 => [
				'different:to_id'
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

	private function adjustAccountBalances ()
	{
		$financeAccountFrom	 = FinanceAccount::findOrFail ( $this -> from_id ) ;
		$financeAccountTo	 = FinanceAccount::findOrFail ( $this -> to_id ) ;
		$amount				 = $this -> amount ;

		$financeAccountFrom -> account_balance -= $amount ;
		$financeAccountTo -> account_balance += $amount ;

		$financeAccountFrom -> update () ;
		$financeAccountTo -> update () ;
	}

	private function resetAccountBalances ()
	{
		$originalFinanceTransfer = FinanceTransfer::findOrFail ( $this -> id ) ;

		$originalFinanceAccountFrom	 = FinanceAccount::findOrFail ( $originalFinanceTransfer -> from_id ) ;
		$originalFinanceAccountTo	 = FinanceAccount::findOrFail ( $originalFinanceTransfer -> to_id ) ;

		$originalFinanceAccountFrom -> account_balance += $originalFinanceTransfer -> amount ;
		$originalFinanceAccountTo -> account_balance -= $originalFinanceTransfer -> amount ;

		$originalFinanceAccountFrom -> update () ;
		$originalFinanceAccountTo -> update () ;
	}

}
