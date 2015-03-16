<?php
class User extends Controller
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
	public function setReport($postArr)
	{
		
		$returnResponse=false;
		$childId = 0;
		$parentId=0;
		if(count($postArr)>0)
		{
			
			
			if($postArr['childId'])
			{
				$childId = $postArr['childId'];
			}
			else
			{
				$this->showErrors(RESPONSE_CODE_CHILDNAME_NOT_EXIST_IN_PARAMETER,"Child name not exist in parameter");
				exit;
			}
			if($postArr['parentId'])
			{
				$parentId = $postArr['parentId'];
			}
			else
			{
				$this->showErrors(RESPONSE_CODE_PARENTID_NOT_EXIST_IN_PARAMETER,"parentId not exist in parameter");
				exit;
			}
			$postArr = array_filter($postArr);
			foreach($postArr as $dataKey=>$mainArrs)
			{
				
				 if($dataKey == 'childId' || $dataKey == 'parentId')
				{
					continue;
				} 
				foreach($mainArrs as $key=>$mainArr)
				{
					
					if($dataKey == 'Call')
					{
						//print_r($mainArr);
						$returnResponse= $this->model->saveCall($mainArr,$childId,$parentId);
						if(!$returnResponse)
							{
								break;
							}
					}
					else if($dataKey == 'SMS')
					{
						$returnResponse= $this->model->saveSMS($mainArr,$childId,$parentId);
						if(!$returnResponse)
						{
							break;
						}	
					}
					else if($dataKey == 'Website')
					{
						$returnResponse= $this->model->saveWebsite($mainArr,$childId,$parentId);
					    if(!$returnResponse)
						{
							break;
						}
					}
					/* foreach($mainArr as $singleKey => $singleValue)
					{
						
						if($singleKey=='Duration')
						{
							if(!Validation::check_number($singleValue,'int',1,4))
							{
								echo "Duration not valid";
							}
							else{
							echo "Duration valid";
							}
						}
						
						if($singleKey=='Type')
						{
							if(!Validation::check_number($singleValue,'int',1,1))
							{
								echo "Type not valid";
							}
							else{
							echo "Type valid";
							}
						}
						
						if($singleKey=='MN')
						{
							if(!Validation::check_number($singleValue,'mobile'))
							{
								echo "Mobile number not valid";
							}
							else{
							echo "Mobile number valid";
							}	
						}
						
						if($singleKey=='TimeStamp')
						{
							if(!Validation::check_number($singleValue,'dateTime'))
							{
								echo "Date Time not valid";
							}
							else{
								echo "Date Time valid";
							}
						}
						echo "\n";
					}*/
					
				}
				/*if(!$returnResponse)
				{
					break;
				}*/
			}
		}
		 if($returnResponse)
		{
			$response = array('status'=>RESPONSE_CODE_USERREPORT_SAVED_SUCESSFULLY,"message"=>"User report saved successfully");
			echo json_encode($response);		
		}
		
	}
	public function getReport($postArr)
	{
		$this->model->getReport($postArr);
	}
	function delete($postArr=array())
	{
		$this->checkEmptyOrNot($postArr);
		$this->model->deleteData($postArr);
	}
}	