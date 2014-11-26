<?php

namespace Controllers\Tools ;

class WeightCalculatorController extends \Controller
{

	public function view ()
	{
		return \View::make ( 'web.tools.weightCalculator.home' ) ;
	}

}
