<?php
class Parentuser extends Controller
{
	function __construct()
	{
		parent::__construct();
		
		//echo "We are in index";
	}
	function Index()
	{
		//echo 'sdf';
		//$this->view->render('index/index');
	}
	function register($postArr=array())
	{
		$this->checkEmptyOrNot($postArr);
		if($this->_mobile_validation($postArr['parentId']))
		{
			$this->model->register($postArr);
		}
		else
		{
			$this->showErrors(RESPONSE_CODE_PARAMETERS_TWOO,"parameters are too");
		}
	}
	function delete($postArr=array())
	{
		$this->checkEmptyOrNot($postArr);
		$this->model->deleteRecord($postArr);
	}
}	