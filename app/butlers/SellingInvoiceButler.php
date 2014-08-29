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

}
