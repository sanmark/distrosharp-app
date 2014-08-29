<?php

namespace Models ;

class BuyingItem extends BaseEntity implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function item ()
	{
		return $this -> belongsTo ( 'Models\Item' ) ;
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

	private function validateForSave ()
	{
		$countRows = \Models\Item::all () ;
		foreach ( $countRows as $rows )
		{

			if ( \Input::get ( 'quantity_' . $rows -> id ) != '' )
			{
				$data = $this -> toArray () ;

				$rules = [
					'buying_price_' . $rows -> id	 => [
						'numeric'
					] ,
					'quantity_' . $rows -> id		 => [
						'numeric' ,
					] ,
					'free_quantity_' . $rows -> id	 => [
						'numeric' ,
					] ,
				] ;
			}
		}
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
		$countRows = \Models\Item::all () ;
		foreach ( $countRows as $rows )
		{

			if ( \Input::get ( 'quantity_' . $rows -> id ) != '' )
			{
				$data = $this -> toArray () ;

				$rules = [
					'buying_price_' . $rows -> id	 => [
						'numeric'
					] ,
					'quantity_' . $rows -> id		 => [
						'numeric' ,
					] ,
					'free_quantity_' . $rows -> id	 => [
						'numeric' ,
					] ,
				] ;
			}
		}
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
		throw new \Exceptions\NotImplementedException() ;
	}

}
