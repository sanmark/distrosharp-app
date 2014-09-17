<?php

class DatabaseHelper
{

	public static function hasDatabase ( $databaseName )
	{
		$database = DB::select ( 'SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "' . $databaseName . '";' ) ;

		if ( count ( $database ) > 0 )
		{
			return TRUE ;
		}

		return FALSE ;
	}

}
