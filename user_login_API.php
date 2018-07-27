<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Content-type: application/x-www-form-urlencoded");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
//$data = json_decode(file_get_contents("php://input"));
require 'conn_to_db.php';

$login_name=$_POST['email_id'];
$password=$_POST['password'];

//$email_id="amolbadge09@gmail.com";
//$password="12345";
if(!empty($login_name) && !empty($password))
{
	//$salt = '3x%%$bf83#dls2qgdf';
    //$md5 = md5($salt.$password);
	$get_pwd="SELECT `user_pass`,`ID`,`user_email` FROM `wpr3_users` WHERE `user_login` = '$login_name'";
	$result = mysqli_query($link,$get_pwd) or die('Errant query:  '.$get_pwd);
	$result1=mysqli_fetch_assoc($result);
	$encpass=$result1['user_pass'];
	$response=array();
	$count=mysqli_num_rows($result);
	if(!is_null($encpass)) 
	{
		require_once ('class-phpass.php');
		$wp_hasher = new PasswordHash(8, TRUE);
		$password_hashed = $encpass;
		$plain_password = $password;
  		if($wp_hasher->CheckPassword($plain_password, $password_hashed)) 
  		{
			$user_id=$result1['ID'];
			$email_id=$result1['user_email'];
			$fname="SELECT `meta_value` FROM `wpr3_usermeta` WHERE `user_id`='$user_id' and `meta_key`='first_name'";
			$result1 = mysqli_query($link,$fname) or die('Errant query:  '.$fname);
			$f_name=mysqli_fetch_assoc($result1);
			$first_name=$f_name['meta_value'];
			$lname="SELECT `meta_value` FROM `wpr3_usermeta` WHERE `user_id`='$user_id' and `meta_key`='last_name'";
			$result2 = mysqli_query($link,$lname) or die('Errant query:  '.$lname);
			$l_name=mysqli_fetch_assoc($result2);
			$last_name=$l_name['meta_value'];
			$ph="SELECT `meta_value` FROM `wpr3_usermeta` WHERE `user_id`='$user_id' and `meta_key`='_sln_phone'";
			$result3 = mysqli_query($link,$ph) or die('Errant query:  '.$ph);
			$ph_no=mysqli_fetch_assoc($result3);
			$phone=$ph_no['meta_value'];

			$get_count="SELECT count(*) as count FROM `wpr3_referral_code` WHERE `referrer_id`='$user_id'";
			$row1 = mysqli_query($link,$get_count) or die('Errant query:  '.$get_count);
			$val=mysqli_fetch_assoc($row1);
			$count=$val['count'];

			$get_address="SELECT `meta_value` FROM `wpr3_usermeta` WHERE `meta_key`='_sln_address' and `user_id`='$user_id'";
			$row12 = mysqli_query($link,$get_address) or die('Errant query:  '.$get_address);
			$v_add=mysqli_fetch_assoc($row12);
			$cust_address=$v_add['meta_value'];

			if($count=='0')
			{

			    $chars = rand(1, 100)."abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#".rand(0, 90);
				$reffral_code = substr(str_shuffle($chars),0,6);
				$reff_code = str_replace(' ', '', $reffral_code);

				$insert_reffcode="INSERT INTO `wpr3_referral_code`(`referrer_id`, `referred_email`, `status`, `refferal_code`) VALUES ('$user_id','$email_id','active','$reff_code')";
				$insert_data = mysqli_query($link,$insert_reffcode) or die('Errant query:  '.$insert_reffcode);
			}
			$reff_query="SELECT `refferal_code` FROM `wpr3_referral_code` WHERE `referrer_id`='$user_id'";
			$row2 = mysqli_query($link,$reff_query) or die('Errant query:  '.$ph);
			$val1=mysqli_fetch_assoc($row2);
			$refcode=$val1['refferal_code'];

			$redeem_query="SELECT `redeem_points` FROM `wpr3_redeem_points` WHERE `cust_id`='$user_id'";
			$row3 = mysqli_query($link,$redeem_query) or die('Errant query:  '.$redeem_query);
			$val2=mysqli_fetch_assoc($row3);
			$points=$val2['redeem_points'];
			$r_pts=($points=='')?"0":$points;

			$response[] = array(
				'user_id'=> $user_id,
				'user_fname'=> $first_name,
				'user_lname'=> $last_name,
				'user_email'=> $email_id,
				'user_phone'=> $phone,
				'reffral_code'=> $refcode,
				'redeem_points'=> $r_pts,
				'address'=>$cust_address
			);

			$postvalue['responseStatus'] = http_response_code(200);
			$postvalue['responseMessage'] = "OK";
			$postvalue['posts'] = $response;
			echo json_encode($postvalue,JSON_PRETTY_PRINT|JSON_FORCE_OBJECT);
		}else
		{
			$postvalue['responseStatus']="204";
			$postvalue['responseMessage']="Invalid Password..";
		    echo json_encode($postvalue);
		}

	}else
	{
		$postvalue['responseStatus']="204";
		$postvalue['responseMessage']="Plz Enter valid username and password";
	    echo json_encode($postvalue);
	}



}else
 {

 				$postvalue['responseStatus']="1000";
				$postvalue['responseMessage']="Invalid Input parameters required";
				$postvalue['posts']=null;
				echo  json_encode($postvalue,JSON_PRETTY_PRINT|JSON_FORCE_OBJECT); 
 }

mysqli_close($link);
?>