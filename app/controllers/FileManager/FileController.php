<?php

namespace Controllers\FileManager ;

class FileController extends \Controller
{

	public function home ()
	{

		\ActivityLogButler::add ( "View File Manager" ) ;

		return \View::make ( 'web.filemanager.filemanager' ) ;
	}

}
