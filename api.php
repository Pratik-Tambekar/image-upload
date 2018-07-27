<?php 
use \Firebase\JWT\JWT;
require_once('DbConnect.php');
require_once('customer.php');
require '../vendor/autoload.php';
class Api extends Rest{
		public $dbconn;
		public function __construct()
			{

		       parent::__construct();
		       $db = new DbConnect;
		       $this->dbconn = $db->connect();
			}
		public function generatetokens(){
			//print_r($this->param);
			$email = $this->validateParameter('email', $this->param['email'], STRING);
		
			$password = $this-> validateParameter('pass',$this->param['pass'], STRING);

			/*$sql = "Select * from tbl_users where email='$email' and password ='$password'";
            $stmt = dbconn::prepare($sql);
            $stmt->exceute();
            return $stmt->fetchAll();*/
            try{
			$stmt = $this->dbconn->prepare("Select * from tbl_users where email=:email and password =:pass");
			$stmt->bindParam(":email", $email);
			$stmt->bindParam(":pass", $password);
			$stmt->execute();
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
			if(!is_array($user)){
				$this->returnResponse(INVALID_USER_PASS,"Email or Password is incorrect");
			}
			if($user['status'] == 0){
			 $this->returnResponse(USER_NOT_ACTIVE,"user not actived. Please Contact to admin");
			}

			$payload=[
				'iat'=> time(),
				'iss'=> 'localhost',
				'exp'=> time() + (500*60),
				'userId'=> $user['id']
			];

			$token = JWT::encode($payload, SECRETE_KEY);
			//echo $token;

			$data = ['token'=>$token];
			$this->returnResponse(SUCCESS_RESPONSE,$data);
		}catch(Exception $e){
			$this->throwError(JWT_PROCESSING_ERROR,$e->getMessage());
		}

			 



		}
		public function addCustomer()
		{
			$name = $this->validateParameter('name', $this->param['name'], STRING, false);
		
			$email = $this-> validateParameter('email',$this->param['email'], STRING, false);

			$address = $this-> validateParameter('addr',$this->param['addr'], STRING);

			$mobile = $this-> validateParameter('mobile',$this->param['mobile'], STRING, false);

			try{
					$token=$this->getBearerToken();
					$payload = JWT::decode($token, SECRETE_KEY , ['HS256']);
					
					$stmt = $this->dbconn->prepare("Select * from tbl_users where id=:userId");
					$stmt->bindParam(":userId", $payload->userId);
					$stmt->execute();
					$user = $stmt->fetch(PDO::FETCH_ASSOC);
				if(!is_array($user)){
					$this->returnResponse(INVALID_USER_PASS,"This user is not found in our database");
				}
				if($user['status'] == 0){
				 $this->returnResponse(USER_NOT_ACTIVE,"This user may be deactived. Please Contact to admin");
				}

				$cust = new Customer();
				$cust->setName($name);
				$cust->setEmail($email);
				$cust->setAddress($address);
				$cust->setMobile($mobile);
				$cust->setCreatedBy($payload->userId);
				$cust->setCreatedOn(date('Y-m-d'));
				$booStatus = true;
				if(!$cust->insert()){
					$msg = 'Failed to insert';
					$booStatus = false;
				}else{
					$msg = 'Inserted successfully..';
				}
				$this->returnResponse(SUCCESS_RESPONSE,$msg);

			}catch(Exception $e){
				$this->throwError(ACCESS_TOKEN_ERRORS,$e->getMessage());
			}

		}

		public function  getCustomerDetails(){
			$customerId = $this-> validateParameter('customerId',$this->param['customerId'], STRING, INTEGER);
			$cust = new Customer();
			$cust->setId($customerId);
			$customers = $cust->getCustmerById();
			if(!is_array($customers)){
					$this->returnResponse(INVALID_USER_PASS,"This user is not found in our database");
				}
			$this->returnResponse(SUCCESS_RESPONSE,$customers);

		}



}






?>