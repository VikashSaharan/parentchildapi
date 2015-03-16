<?php
class View
{
	function __construct()
	{
		// echo "this is view";
	}
	public function render($name, $attachformat=false)
	{
		if($attachformat)
		{
			require 'views/'.$name.'.php';
		}
		else {
			//require 'views/header.php';
			//require 'views/'.$name.'.php';
			//require 'views/footer.php';
			
		}
		
	}
	public function addStyleSheet($list)
	{
		if(count($list)>0)
		{
			foreach($list as $Css)
			{
				echo '<link rel="stylesheet" type="text/css" href="'.$Css.'" />';
			}
		}
	}
	public function addScript($list)
	{
		if(count($list)>0)
		{
			foreach($list as $js)
			{
				echo '<script type="text/javascript" src="'.$js.'"></script>';
			}
		}
	}
}