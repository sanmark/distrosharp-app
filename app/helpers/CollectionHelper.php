<?php

class CollectionHelper
{

	public static function toArrayAndSetSpecificIndex ( Illuminate\Database\Eloquent\Collection $collection , $indexName = 'id' )
	{
		$array		 = $collection -> toArray () ;
		$newArray	 = [ ] ;

		foreach ( $array as $element )
		{
			$newArray[ $element[ $indexName ] ] = $element ;
		}

		return $newArray ;
	}

}
