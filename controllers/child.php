<?php
class Child extends Controller
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
	function register($postArr)
	{
			
		$this->model->register($postArr);
	}
	function delete($postArr=array())
	{
		$this->checkEmptyOrNot($postArr);
		$this->model->deleteRecord($postArr);
	}
}