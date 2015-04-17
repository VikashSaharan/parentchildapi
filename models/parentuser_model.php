<?php
class Parentuser_Model extends Model
{
	public function __contruct()
	{
		parent::__construct();
	}	
	public function register($post)
	{
		if(count($post)>0)
		{
			$register_variable = array("deviceId","parentId");
			$insert=array();
			foreach($post as $key=>$value)
			{
				if(in_array($key,$register_variable))
				{
						$insert[$key] = $value ;
				}
			}
			
			$today = time();
			$no_of_month = "+".NO_OF_MONTH_FREE." months";
		//	$no_of_month = "+".NO_OF_MONTH_FREE." days";
			$twoMonthsLater =strtotime($no_of_month, $today);
			$endDate = new MongoDate($twoMonthsLater);
			$insert["no_of_child"] = NO_OF_CHILD_USER;
			$insert["no_of_month_free"] = $endDate;
			$where = array("deviceId"=>$insert["deviceId"],"parentId"=>$insert["parentId"]);
			$where2 = array("parentId"=>$insert["parentId"]);
			$parentUserTableObj = $this->_db->connection->appformobile->parentuser;
			 $record = $parentUserTableObj->findOne($where);
			 $reponse=null;
			 if(!$record)
			 {
				if($parentUserTableObj->insert($insert))
				{
					$response = array('status'=>RESPONSE_CODE_USER_NO_CHILD_HAS_BEEN_REGISTRED,'parentId'=>$insert["parentId"],"listofActiveChild"=>array(),"message"=>"Registration Done SuccessFully Register as Parent to Enjoy the Service");
				}
				else
				{
					$response = array('status'=>RESPONSE_CODE_FAILURE,'parentId'=>$insert["parentId"],"listofActiveChild"=>$this->_getChild($where2),"message"=>"Registration not SuccessFully some reasons");
				}
			 }
			 else
			 {
				$response = array('status'=>RESPONSE_CODE_ACCOUNT_NAME_ALREADY_PRESENT,"listofActiveChild"=>$this->_getChild($where2),"message"=>"This parent account is already present");
			 }
		}
		else
		{
			//this time temp after some time change
			$response = array('status'=>RESPONSE_CODE_FAILURE,"listofActiveChild"=>$this->_getChild($where2),"message"=>"Registration Done SuccessFully Register as child to Enjoy the Service");
		}
		echo json_encode($response);
	}
	function deleteRecord($post)
	{
		if(!$this->checkExist("parentId",$post["parentId"],'parentuser'))
		{
				$this->showError("parentId not exist",RESPONSE_CODE_PARENTID_NOT_EXIST);
		}
		if($this->delete($post,'parentuser'))
		{
			$response = array('status'=>RESPONSE_CODE_SUCCESS,"message"=>"Successfully Deleted");
		}
		else
		{
			$this->showError("Record Not Deleted Successfully",RESPONSE_CODE_METHOD_NOT_DELETED_SUCCESSFULLY);
			exit;
		}
		echo json_encode($response);
		exit;
	}
}