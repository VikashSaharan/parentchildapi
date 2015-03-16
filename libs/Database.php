<?php
/*This class example Singleton Pattern.
  This code genrated by Vikash Saharan
*/
class Database
{	
	private static $instance = null;
	public static function getDBObject()
	{
		if(!self::$instance)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	public $connection = null;
	public function __construct()
	{
		try
		{
			$this->connection= new MongoClient(DB_TYPE."://".DB_HOST.":27017", array("username" => DB_USERNAME, "password" => DB_PASS));
	
		}catch(Exception $e)
		{
			echo "Database Error: Couldn't connect database".$e->getMessage();
			exit;
		}
		//$this->connection = new MongoClient();
	}
		
}