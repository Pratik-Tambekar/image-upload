<?php

if($_FILES["file"]["name"]!='')
{
		$test=explode(".", $_FILES["file"]["name"]);
		$extension=end($test);
		$name= rand(100,999) . '.' . $extension;
		$location='./upload/'.$name;
		//echo $location;
		move_uploaded_file($_FILES["file"]["tmp_name"], $location);
		echo '<img src="'.$location.'" height="150" width="225" class="img-thumbnail"/>';


}


?>