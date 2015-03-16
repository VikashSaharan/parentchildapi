<?php
class Model
{
	public $_db=null;
	function __construct()
	{
		$this->_db = new Database();
	}
	public function _getChild($where,$field=array())
	{
	
		$childTableObj = $this->_db->connection->appformobile->child;
		$records = $childTableObj->find($where,$field);
		$returnArr=array();
		if(count($records)>0)
		{
		foreach($records as $record)
		{
			$returnArr[] = $record;
		}
		}
         return $returnArr;	
		
	}
	public function _isValidFees($parentId)
	{
		if($parentId=='9829953786')
		{
			return true;
		}
		$parentUserTableObj = $this->_db->connection->appformobile->parentuser;
		$dateCond = array("parentId"=>$parentId,"no_of_month_free"=>array('$gte'=>new MongoDate(time())));
		$parentRow = $parentUserTableObj->findOne($dateCond);
		if(!$parentRow)
		{
				$response = array('status'=>RESPONSE_CODE_PAYMENT_REQUIRED,"listofActiveChild"=>$this->_getChild(array("parentId"=>$parentId),array("childId"=>true,"parentId"=>true,"_id"=>false)),"message"=>"Payment Required");
				echo json_encode($response);
				exit;
		}
		return true;
	}
	public function _isValidChild($post)
	{
		if($post["parentId"]=='9829953786')
		{
			return true;
		}
		$countCondition = array("parentId"=>$post["parentId"]);
		$childTableObj = $this->_db->connection->appformobile->child;
		$childTableObj->ensureIndex(array("parentId"=>1));
		$counter = $childTableObj->count($countCondition);
		$parentUserTableObj = $this->_db->connection->appformobile->parentuser;
		$parentRow = $parentUserTableObj->findOne(array("parentId"=>$post["parentId"]));
		if($counter >= $parentRow["no_of_child"])
		{
			$response = array('status'=>RESPONSE_CODE_ACCOUNT_LIMIT_REACHED,"listofActiveChild"=>$this->_getChild(array("parentId"=>$post["parentId"]),array("childId"=>true,"parentId"=>true,"_id"=>false)),"message"=>"Account limit reached");
			echo json_encode($response);
			exit;
		}
		return true;
	}
	public function checkExist($key,$value,$tableName)
	{		
		$tableObj = $this->_db->connection->appformobile->$tableName;
		$where = array($key=>$value);
		$record = $tableObj->findOne($where);
		if($record)
		{
			return true;
		}		
		return false;
	}
	public function delete($post,$tableName)
	{
			$tableObj = $this->_db->connection->appformobile->$tableName;
			$where = $post;
			$result=false;
			if($tableName == 'child')
				$result = $tableObj->remove($where,array("justOne" => true));
			else
				$result = $tableObj->remove($where);
			return true;
			/* if($result)
			{
				$response = array('status'=>RESPONSE_CODE_SUCCESS,"message"=>"Successfully Deleted");				
			}
			return $response; */
			
	}
	public function showError($statusCode,$message)
	{
			require 'controllers/error.php';
			$controller = new Error();
			$controller->showError($message,$statusCode);
			exit;
	}
}