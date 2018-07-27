<?php
require_once('constant.php');
class Rest
{
	protected $request;
	protected $serviceName;
	protected $param;
	public function __construct()
	{
			if($_SERVER['REQUEST_METHOD'] !=='POST'){
				$this->throwError(REQUEST_METHOD_NOT_VALID,"Request method is not valid");
			}

			$handler = fopen('php://input','r');
			$this->request = stream_get_contents($handler); //how to access request 
			$this->ValidateRequest($this->request);

	}
	public function ValidateRequest($request){
				
			if($_SERVER["CONTENT_TYPE"] !== 'application/json'){
				$this->throwError(REQUEST_CONTENTTYPE_NOT_VALID,"Request content type is not valid");
			}
			$data=json_decode($this->request, true);
			//.print_r($data);
			if(!isset($data['name']) || $data['name'] == ""){
				$this->throwError(API_NAME_REQUIRED,"API name is required");
			}
			$this->serviceName=$data['name'];  //API NAME
			if(!is_array($data['param'])){
			
				$this->throwError(VALIDATE_PARAMETER_REQUIRED,"API parameter is required");
			}
			$this->param = $data['param'];

	}
	public function executeAPI(){
		$api = new Api;
		$rMethod = new reflectionMethod('Api',$this->serviceName); //create dynamic method of name generatetokens
		if(!method_exists($api, $this->serviceName)){
			$this->throwError(API_DOES_NOT_EXIXT,"API does not exist");
		}
		$rMethod->invoke($api);



	}
	function validateParameter($feildName, $value, $datatype, $required = true){ 
		if($required == true && empty($value) == true){

			$this->throwError(VALIDATE_PARAMETER_REQUIRED,$feildName." parameter is required");

		}
		switch($datatype)
		{
			case BOOLEAN:
						if(!is_bool($value)){
							$this->throwError(VALIDATE_PARAMETER_DATATYPE," Datatype is not valid for ".$feildName.'.It shoud be boolean');
						}
			case INTEGER:
						if(!is_numeric($value)){
							$this->throwError(VALIDATE_PARAMETER_DATATYPE," Datatype is not valid for ".$feildName.'.It shoud be numeric');
						}
			case STRING:
						if(!is_string($value)){
							$this->throwError(VALIDATE_PARAMETER_DATATYPE," Datatype is not valid for ".$feildName.'.It shoud be string');
						}
			break;

			default:
			#code
			break;

			

		}
		return $value;
	}


	
	 function throwError($code,$message){
          
        echo json_encode(['statuscode'=>$code,'message'=>$message]);
        exit;

	}
	 function returnResponse($code,$data){
   		header('content-type: application/json');
   		$response = json_encode(['response'=>['status'=>$code, "result"=>$data]]);
   		echo $response;
   		exit;

	}

    // get header authorization
    public function getAuthorizationHeader(){
    	$headers = null;
    	if(isset($_SERVER['Authorization'])){
    		$headers = trim($_SERVER['Authorization']);
    	}
    	else if(isset($_SERVER['HTTP_AUTHORIZATION'])){
    		//Nginx or CGI
    		$headers= trim($_SERVER['HTTP_AUTHORIZATION']);
    	}else if(function_exists('apache_request_headers')){
    		$requestHeaders = apache_request_headers();
    		$requestHeaders = array_combine(array_map('ucwords',array_keys($requestHeaders)), array_values($requestHeaders));
    		if(isset($requestHeaders['Authorization'])){
    			$headers = trim($requestHeaders['Authorization']);
    		}
    	}
    	return $headers;

    }
	public function getBearerToken(){
		$headers = $this->getAuthorizationHeader();
		//get the access token from the header
		if(!empty($headers)){
		if(preg_match('/Bearer\s(\S+)/',$headers, $matches)){
			return $matches[1];
		}
		}
		$this->throwError(ATHORIZATION_HEADER_NOT_FOUND,"Access token not found");

	}
}