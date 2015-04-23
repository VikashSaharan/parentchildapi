<?php
class User_Model extends Model
{
	public function __contruct()
	{
		parent::__construct();
	}	
	public function saveCall($post,$childId,$parentId)
	{
		
		if(!$this->checkExist("parentId",$parentId,'parentuser'))
		{
				$this->showError(RESPONSE_CODE_PARENTID_NOT_EXIST,"parentId not exist");
				exit;
		}
		if(!$this->checkExist("childId",$childId,'child'))
		{
				$this->showError(RESPONSE_CODE_CHILDNAME_NOT_EXIST,"Child name not exist");
				exit;
		}
		if($this->_isValidFees($parentId))
		{
			/* $where = array("deviceId"=>$insert["deviceId"],"parentId"=>$insert["parentId"]); */
			 $dataCallTableObj = $this->_db->connection->appformobile->data_call;
			/* $record = $parentUserTableObj->findOne($where); */
			 $response=false;
			 $record=false;
			 if(!$record)
			 {
				$post['childId']=$childId;
				$post['parentId']=$parentId;
				if($dataCallTableObj->insert($post))
				{
					$response = true;
				}
			 }
			 else
			 {
				$response = false;
			 }
				
			return $response;
		}
	}
	public function saveSMS($post,$childId,$parentId)
	{
		
		/* $where = array("deviceId"=>$insert["deviceId"],"parentId"=>$insert["parentId"]); */
		 $dataSMSTableObj = $this->_db->connection->appformobile->data_sms;
		/* $record = $parentUserTableObj->findOne($where); */
		  $response=false;
		 $record=false;
		 if(!$record)
		 {
			$post['childId']=$childId;
			$post['parentId']=$parentId;
			if($dataSMSTableObj->insert($post))
			{
				$response = true;
			}
		 }
		 else
		 {
			$response = false;
		  }	
		
		return $response;
		
	}
	public function saveWebsite($post,$childId,$parentId)
	{
		
		/* $where = array("deviceId"=>$insert["deviceId"],"parentId"=>$insert["parentId"]); */
		 $dataWebsiteTableObj = $this->_db->connection->appformobile->data_website;
		/* $record = $parentUserTableObj->findOne($where); */
		 $response=false;
		 $record=false;
		 if(!$record)
		 {
			$post['childId']=$childId;
			$post['parentId']=$parentId;
			if($dataWebsiteTableObj->insert($post))
			{
				$response = true;
			}
		 }
		 else
		 {
			$response = false;
		}
		return $response;
	}
	public function getReport($post)
	{
		if(!$this->checkExist("parentId",$post["parentId"],'parentuser'))
		{
				$this->showError("parentId not exist",RESPONSE_CODE_PARENTID_NOT_EXIST);
				exit;
		}
		if(!$this->checkExist("childId",$post["childId"],'child'))
		{
				
				$this->showError("Child name not exist",RESPONSE_CODE_CHILDNAME_NOT_EXIST);
				exit;
		}
		if($this->_isValidFees($post["parentId"]))
		{	
			 $where = array("parentId"=>$post["parentId"],"childId"=>$post["childId"]);
			// print_r($post);
			 $childTableObj = $this->_db->connection->appformobile->child;
			$record = $childTableObj->findOne($where); 
			 $response=array();
			// $record=false;
			 if($record)
			 {  $response['status']= 1000;
				$response['Call']=$this->_getDatacall($post['childId']);
				$response['SMS']=$this->_getDatasms($post['childId']);
				$response['Website']=$this->_getDatawebsite($post['childId']);
				if(!(count($response['Call'])>0 || count($response['SMS'])>0 || count($response['Website'])>0))
				{
						$response['status']= 1002;
						$response['message'] = "Record Does not Exist";
						$response['Call']=array();
						$response['SMS']=array();
						$response['Website']=array();
				}
			 }
			 else
			 {
				$response['status']= 1002;
				$response['message'] = "Record Does not Exist";
				$response['Call']=array();
				$response['SMS']=array();
				$response['Website']=array();
				
			 }
			echo json_encode($response);
		}
		
	}
	function _getDatacall($childId)
	{
		$where = array("childId"=>$childId);
		 $dataCallTableObj = $this->_db->connection->appformobile->data_call;
		 
		 $records = $dataCallTableObj->find($where,array('_id'=>0))->sort(array("TimeStamp"=>-1));
		 $dataCall = array();
		 foreach($records as $record)
		 {
			//print_r($record);
			$dataCall[] = $record;
		 }
		 return $dataCall;
	}
	function _getDatasms($childId)
	{
		$where = array("childId"=>$childId);
		$dataSMSTableObj = $this->_db->connection->appformobile->data_sms;
		$records = $dataSMSTableObj->find($where,array('_id'=>0))->sort(array("TimeStamp"=>-1));;
		$dataSMS = array();
		foreach($records as $record)
		{
			$dataSMS[] = $record;
		}
		return $dataSMS;
	}
	function _getDatawebsite($childId)
	{
		$where = array("childId"=>$childId);
		$dataWebsiteTableObj = $this->_db->connection->appformobile->data_website;
		$records = $dataWebsiteTableObj->find($where,array('_id'=>0,'childId'=>0))->sort(array("LastActive"=>-1));
		$dataWebsite = array();
		foreach($records as $record)
		{
			$dataWebsite[] = $record;
		}
		return $dataWebsite;
	}
	function deleteData($post)
	{
		$response='';
		if(!$this->checkExist("parentId",$post["parentId"],'child'))
		{
				$this->showError("parentId not exist",RESPONSE_CODE_PARENTID_NOT_EXIST);
		}
		if(!$this->checkExist("childId",$post["childId"],'child'))
		{
				$this->showError("childId not exist",RESPONSE_CODE_CHILDNAME_NOT_EXIST);
		}
		unset($post['parentId']);
		if($this->delete($post,'data_call'))
		{
			if($this->delete($post,'data_sms'))
			{
				if($this->delete($post,'data_website'))
				{
					$response = array('status'=>RESPONSE_CODE_SUCCESS,"message"=>"Successfully Deleted");	
				}
				else
				{
					$this->showError("Record Not Deleted",RESPONSE_CODE_CHILDNAME_NOT_EXIST);
					exit;
				}
			}
			else
			{
				$this->showError("Record Not Deleted Successfully",RESPONSE_CODE_METHOD_NOT_DELETED_SUCCESSFULLY);
				exit;
			}
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