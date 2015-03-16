<?php
class Error extends Controller
{
	function __construct()
	{
		parent::__construct();
		//echo "this is an Error!";
		
	}
	public function showError($status_code, $error_msg)
	{
		$this->showErrors($status_code, $error_msg);
	}
}