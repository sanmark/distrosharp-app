<?php

namespace Models ;

class StockConfirmationDetail extends BaseEntity implements \Interfaces\iEntity
{

	public function item ()
	{
		return $this -> belongsTo ( 'Models\Item' ) ;
	}

	public function getConfirmationDetails ( $id )
	{
		$confirmationDetails = $this -> where ( 'stock_confirmation_id' , '=' , $id ) -> get () ;
		return $confirmationDetails ;
	}
	
}
