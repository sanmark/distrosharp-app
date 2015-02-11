<?php

namespace Models ;

class StockConfirmationDetail extends BaseEntity implements \Interfaces\iEntity
{

	public function item ()
	{
		return $this -> belongsTo ( 'Models\Item' ) ;
	}
}
