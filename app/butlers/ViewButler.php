<?php

class ViewButler
{

	public static function makeMenuHtmlFromArray ( $menuArray )
	{
		$html = '<ul>' ;

		foreach ( $menuArray as $menuItem )
		{
			if ( is_string ( $menuItem[ 1 ] ) )
			{
				//End Point
				$html .= '<li><a href="' . URL::action ( $menuItem[ 1 ] ) . '">' . $menuItem[ 0 ] . '</a></li>' ;
			} elseif ( is_array ( $menuItem[ 1 ] ) )
			{
				//Mid Point
				$html .= '<li>' . $menuItem[ 0 ] ;
				$html .= self::makeMenuHtmlFromArray ( $menuItem[ 1 ] ) ;
				$html .= '<span class="glyphicon glyphicon-chevron-right"></span>' ;
				$html .= '</li>' ;
			}
		}

		$html .= '</ul>' ;

		return $html ;
	}

	public static function getYesNoFromBoolean ( $value )
	{
		$value = boolval ( $value ) ;

		if ( $value )
		{
			return 'Yes' ;
		}

		return 'No' ;
	}

	public static function htmlSelectAnyYesNo ()
	{
		$values = [
			NULL => 'Any' ,
			'1'	 => 'Yes' ,
			'0'	 => 'No'
			] ;

		return $values ;
	}

	public static function htmlSelectSortItems ()
	{
		$values = [
			NULL					 => 'None' ,
			'reorder_level'			 => 'Reorder Level' ,
			'current_buying_price'	 => 'Buying Price' ,
			'current_selling_price'	 => 'Selling Price' ,
			] ;
		return $values ;
	}

	public static function htmlSelectSortOrder ()
	{
		$values = [
			'ASC'	 => 'Ascending' ,
			'DESC'	 => 'Descending' ,
			] ;
		return $values ;
	}

	public static function bootstrapDismissibleAlertCloseButton ()
	{
		return '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>' ;
	}

	public static function formatCurrency ( $value )
	{
		return number_format ( $value , 2 ) ;
	}

}
