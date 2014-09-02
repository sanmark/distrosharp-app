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
		parent::save ( $options ) ;
	}

	public function update ( array $attributes = array () )
	{
		$this -> validateForUpdate () ;
		parent::update ( $attributes ) ;
	}

	public function validateForSave ()
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

	public function validateForUpdate ()
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

}
