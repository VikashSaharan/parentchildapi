<?php
class Child_Model extends Model
{
	public function __contruct()
	{
		parent::__construct();
	}	
	public function register($post)
	{
	
		$register_variable = array("deviceId","parentId","childId");
		$insert=array();
		//print_r($post);
		foreach($post as $key => $value)
		{
			if(in_array($key,$register_variable))
			{
					$insert[$key] = $value;
			}
		}
		if(!$this->checkExist("parentId",$insert["parentId"],'parentuser'))
		{
				$this->showError("parentId not Exist",RESPONSE_CODE_PARENTID_NOT_EXIST);
				
		}
		$childTableObj = $this->_db->connection->appformobile->child;
		$where = array("parentId"=>$insert["parentId"],"childId"=>$insert["childId"]);
		if($this->_isValidFees($insert["parentId"]) && $this->_isValidChild($insert))
		{
	
			$record = $childTableObj->findOne($where);
			$reponse=null;
			if(!$record)
			 {
				if($childTableObj->insert($insert))
				{
					$response = array('status'=>RESPONSE_CODE_SUCCESS,'parentId'=>$insert["parentId"],"childId"=>$insert["childId"],"message"=>"Registration Done SuccessFully Register as child to Enjoy the Service");
				}
				else
				{
					$response = array('status'=>RESPONSE_CODE_FAILURE,'parentId'=>$insert["parentId"],"childId"=>$insert["childId"],"message"=>"Registration not SuccessFully some reasons");
				}
			 }
			 else
			 {
				$response = array('status'=>RESPONSE_CODE_ACCOUNT_NAME_ALREADY_PRESENT,"listofActiveChild"=>$this->_getChild(array("parentId"=>$insert["parentId"]),array("childId"=>true,"parentId"=>true,"_id"=>false)),"message"=>"Registration not successfully child name already present");
				//echo json_encode($response);
				//Record already exist
			 }
		}
		echo json_encode($response);
		exit;
	}
	function deleteRecord($post)
	{
		//print_r($post);
		if(!$this->checkExist("parentId",$post["parentId"],'child'))
		{
				$this->showError("parentId not exist",RESPONSE_CODE_PARENTID_NOT_EXIST);
		}
		if(!$this->checkExist("childId",$post["childId"],'child'))
		{
				$this->showError("childId not exist",RESPONSE_CODE_CHILDNAME_NOT_EXIST);
		}
		if($this->delete($post,'child'))
		{
			$response = array('status'=>RESPONSE_CODE_SUCCESS,"message"=>"Successfully Deleted");	
		}
		else
		{
			$this->showError("Record Not Deleted Successfully",RESPONSE_CODE_METHOD_NOT_DELETED_SUCCESSFULLY);
		}
		echo json_encode($response);
		exit;
	}
	
}