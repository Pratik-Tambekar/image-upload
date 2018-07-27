<?php
require_once('DbConnect.php');
class Customer
{
	private $id;
	private $name;
	private $email;
	private $address;
	private $mobile;
	private $updatedBy;
	private $updatedOn;
	private $createdBy;
	private $createdOn;
	private $tablename='tbl_customers';
	private $dbconn;

	function setId($id){$this->id=$id;}
	function getId($id){return $this->id;}
	function setName($name){$this->name=$name;}
	function getName($name){return $this->name;}
	function setEmail($email){$this->email=$email;}
	function getEmail($email){return $this->email;}
	function setAddress($address){$this->address=$address;}
	function getAddress($address){return $this->address;}
	function setMobile($mobile){$this->mobile=$mobile;}
	function getMobile($mobile){return $this->mobile;}
	function setUpdatedBy($updatedBy){$this->updatedBy=$updatedBy;}
	function getUpdatedBy($updatedBy){return $this->updatedBy;}
	function setUpdatedOn($updatedOn){$this->updatedOn=$updatedOn;}
	function getUpdatedOn($updatedOn){return $this->updatedOn;}
	function setCreatedBy($createdBy){$this->createdBy=$createdBy;}
	function getCreatedBy($createdBy){return $this->createdBy;}
	function setCreatedOn($createdOn){$this->createdOn=$createdOn;}
	function getCreatedOn($createdOn){return $this->createdOn;}

	
	public function __construct()
	{
		$db = new DbConnect;
		$this->dbconn = $db->connect();

	}

	public function getAllCustomers(){
		
		$stmt = $this->dbconn->prepare("Select * from ". $this->tablename);
			$stmt->execute();
			$customer = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $customer;
		

	}

	public function getCustmerById(){

			$sql = "Select * from ". $this->tablename . ' where id=:userId';
			//echo $sql;
			$stmt = $this->dbconn->prepare($sql);
			$stmt->bindParam(":userId", $this->id);
			$stmt->execute();
			$customer = $stmt->fetch(PDO::FETCH_ASSOC);
			return $customer;

		
	}

	public function insert(){
		$sql='Insert into '.$this->tablename .'(id, name, email, address, mobile, created_by, created_on) VALUES (null,:name,:email,:address,:mobile,:created_by,:created_on)';
		$stmt = $this->dbconn->prepare($sql);
		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":email",  $this->email);
		$stmt->bindParam(":address", $this->address);
		$stmt->bindParam(":mobile", $this->mobile);
		$stmt->bindParam(":created_by", $this->createdBy);
		$stmt->bindParam(":created_on", $this->createdOn);
			if($stmt->execute()){
				return true;
			}else
			{
				return false;
			}
		}
		public function update(){
			$sql="update $this->tablename SET";
			if(null !=$this->getName()){
				$sql .=" name = '".$this->getName()."',";
			}
			if(null !=$this->getAddress()){
				$sql .=" name = '".$this->getAddress()."',";
			}
			if(null !=$this->getMobile()){
				$sql .=" name = '".$this->getMobile()."',";
			}

			$sql .= " updated_by = :updatedBy,
					  updated_on = :updatedOn
					  Where id=:userId";
			$stmt = $this->dbconn->prepare($sql);
			$stmt->bindParam(":updatedBy",  $this->updatedBy);
			$stmt->bindParam(":updatedOn",  $this->updatedOn);
			$stmt->bindParam(":userId",     $this->id);
			if($stmt->execute()){
				return true;
			}else
			{
				return false;
			}
		}
		public function delete(){
			$stmt = $this->dbconn->prepare("DELETE FROM ". $this->tablename . 'Where id=:userId');
			$stmt->bindParam(":userId",     $this->id);
			if($stmt->execute()){
				return true;
			}else
			{
				return false;
			}

		}
} 




?>