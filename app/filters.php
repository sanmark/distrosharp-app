<?php

/*
  |--------------------------------------------------------------------------
  | Application & Route Filters
  |--------------------------------------------------------------------------
  |
  | Below you will find the "before" and "after" events for the application
  | which may be used to do any work before or after a request into your
  | application. Here you may also register your custom route filters.
  |
 */

include app_path () . '/app/before.php' ;
include app_path () . '/app/after.php' ;

foreach ( glob ( app_path () . '/filters/*.php' ) as $filterFile )
{
	include $filterFile ;
}

