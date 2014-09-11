<?php

class SellingInvoiceButler
{

	public static function getAllRepsForHtmlSelect ()
	{
		$repIds = \Models\SellingInvoice::distinct ()
		-> lists ( 'rep_id' ) ;

		$reps = User::getArrayForHtmlSelectByIds ( 'id' , 'username' , $repIds , [NULL => 'Any' ] ) ;

		return $reps ;
	}

	public static function getNextId ()
	{
		$lastSellingInvoice = Models\SellingInvoice::orderBy ( 'id' , 'desc' ) -> first () ;

		if ( is_null ( $lastSellingInvoice ) )
		{
			return 1 ;
		}

		$lastId	 = $lastSellingInvoice -> id ;
		$nextId	 = $lastId + 1 ;

		return $nextId ;
	}

}
