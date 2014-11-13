<?php

namespace Models ;

class ChequeDetail extends BaseEntity implements \Interfaces\iEntity
{

	public function bank ()
	{
		return $this -> belongsTo ( 'Models\Bank' ) ;
	}

	public function financeTransfer ()
	{
		return $this -> belongsTo ( 'Models\FinanceTransfer' ) ;
	}

}
