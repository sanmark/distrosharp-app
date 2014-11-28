<?php

namespace Models ;

class CompanyReturnDetail extends BaseEntity implements \Interfaces\iEntity
{

	public function item ()
	{
		return $this -> belongsTo ( 'Models\Item' , 'item_id' ) ;
	}

}
