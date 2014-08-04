<?php

class StaticPagesController extends Controller
{

	public function home ()
	{
		return View::make ( 'web.home' ) ;
	}

}
