<?php

namespace Controllers\FileManager ;

class FileController extends \Controller
{

	public function home ()
	{
		return \View::make ( 'web.filemanager.filemanager' ) ;
	}

}
