<?php
class Bootstrap
{
	private $_method_array = array('GET','POST','DELETE');
	private $_controller_array = array('parent','child','set','get');
	function __construct()
	{		
		$method = $_SERVER['REQUEST_METHOD'];
		if(!in_array($method,$this->_method_array))
		{
			//Error
			require 'controllers/error.php';
			$controller = new Error();
			$controller->showError(RESPONSE_CODE_REQUEST_METHOD_NOT_VALID,"Request method not valid");
			return false;
		}
		$request = explode("/", rtrim(substr(@$_SERVER['PATH_INFO'], 1),"/"));
		$data = json_decode(file_get_contents('php://input'), true);
		if(!(count($request) > 0 && isset($request[0]) && isset($request[1])))
		{
			require 'controllers/error.php';
			$controller = new Error();
			$controller->showError(RESPONSE_CODE_PARAMETERS_TWOO,"Parameters are too");
			exit;	
		}
		//$urlarr = explode("/",$requestrequest);
		$controllerName=null;
		if($request[0]=='parent')
		{
			$controllerName = 'parentuser';
		}
		else
		{
			$controllerName = $request[0];
			
		}
		$file = 'controllers/'. $controllerName .'.php';
		if(!(file_exists($file)))
		{
			require 'controllers/error.php';
			$controller = new Error();
			$controller->showError(RESPONSE_CODE_CONTROLLER_NOT_EXIST,"Controller are not exist");
			//error status code
			exit;
		}
		require $file;
		$controller = new $controllerName;
		$controller->loadModel($controllerName);
		$methodName='';
		if($request[1]=='setreport')
		{
				$methodName = 'setReport';
		}
		else if($request[1]=='getreport')
		{
				$methodName = 'getReport';
		}
		else
		{
				$methodName = $request[1];
		}
		if(count($data)>0)
		{
			if(method_exists($controller, $methodName))
			{
						$controller->{$methodName}($data);
			}
			else
			{
				require 'controllers/error.php';
				$controller = new Error();
				$controller->showError(RESPONSE_CODE_METHOD_NOT_EXIST,"Method does not exist");
				exit;
			}
		}
		else
		{
			if(isset($request[1]))
			{
				//echo $urlarr[1];
				if(method_exists($controller, $request[1]))
				{
					$controller->{$request[1]}();
				}
				else
				{
					require 'controllers/error.php';
					$controller = new Error();
					$controller->showError(RESPONSE_CODE_METHOD_NOT_EXIST,"Method does not exist");
					exit;
				}
			}
			else 
			{
					require 'controllers/error.php';
					$controller = new Error();
					$controller->showError(RESPONSE_CODE_METHOD_NOT_EXIST,"Parameters are twoo");
					exit;
			}
		 	
		}
	}
}