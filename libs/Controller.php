<?php
class Controller
{
	public $model = null;
	function __construct()
	{
		//echo "Main Controller";
	//	$this->view = new View();
	}
	public function loadModel($name)
	{
		$path = 'models/'.$name.'_model.php';
		
		if(file_exists($path))
		{
			include(ROOT_SYS.'models/'.$name.'_model.php');
			
			$modelName = ucfirst($name).'_Model';
			$this->model = new $modelName();
			
			
		}
	}
	public function _mobile_validation($mobile)
	{		
		return preg_match("/^[0-9]{10}$/", $mobile);
	}
	public function checkEmptyOrNot($postArr)
	{
		if(!count($postArr))
		{
			$this->showErrors(RESPONSE_CODE_PARAMETERS_TWOO,"parameters are too");
		}		
	}
	public function showErrors($status_code, $error_msg)
	{
		echo json_encode(array('status'=>$status_code,'message'=>$error_msg));
		exit;
	}
}