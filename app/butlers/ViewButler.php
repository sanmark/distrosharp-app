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

}
