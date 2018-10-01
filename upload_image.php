<?php
require_once('conn_to_db.php');
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
 
  
    $termid=$_POST['termid']; 
    extract($_POST);
    $error=array();
    $extension=array("jpeg","jpg","png","gif");
    foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name)
            {
                //echo $_POST['term_id'];
                $file_name=$_FILES["files"]["name"][$key];
                $file_tmp=$_FILES["files"]["tmp_name"][$key];
                $ext=pathinfo($file_name,PATHINFO_EXTENSION);
                if(in_array($ext,$extension))
                {
                  if(!file_exists("upload/".$txtGalleryName."/".$file_name))
                    {
                      $location="upload/".$txtGalleryName."_".$termid."_".$file_name;
                      move_uploaded_file($file_tmp=$_FILES["files"]["tmp_name"][$key],$location);
                      $result=mysqli_query($link,"SELECT `id` FROM `wpr3_term_image_path` WHERE `term_id`='$termid'") or die('Select Query Failed');
                      if(mysqli_num_rows($result)==0) 
                      {
                        mysqli_query($link,"INSERT INTO `wpr3_term_image_path`(`term_id`, `term_image_path`) VALUES ('$termid','$location')") or die('Insert Query Failed'); 
                        $msg="Image Uploded Successfully....";
                      }else
                      {
                        mysqli_query($link,"UPDATE `wpr3_term_image_path` SET `term_image_path`='$location' WHERE `term_id`='$termid'") or die('Update Query Failed'); 

                      }
                    

                    }
                   /* else
                    { 
                        $filename=basename($file_name,$ext);
                        $newFileName=$filename.time().".".$ext;
          move_uploaded_file($file_tmp=$_FILES["files"]["tmp_name"][$key],"upload/".$txtGalleryName."/".$newFileName);
                    }*/
                }
                else
                {
                    array_push($error,"$file_name, ");
                }
            }
  /*if(isset($_FILES['image'])){
      $errors= array();
      echo $file_name = $_FILES['image']['name'];
      echo $file_size =$_FILES['image']['size'];
      echo $file_tmp =$_FILES['image']['tmp_name'];
      echo $file_type=$_FILES['image']['type'];
      echo $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
     // echo $file_ext."hiiiiiiiiii";
      //$expensions= array("jpeg","jpg","png");
      
      if(in_array($file_ext,$expensions)=== false){
         //$errors[]="extension not allowed, please choose a JPEG or PNG file.";
         echo "<script type=text/javascript>alert('extension not allowed, please choose a JPEG or PNG file.'); window.location=''</script>";
      }
      
      if($file_size > 3097152){
         //$errors[]='File size must be excately 2 MB';
         echo "<script type=text/javascript>alert('File size must be excately 3 MB.'); window.location=''</script>";
      }
       /* $name= date("d/m/Y").'_'.$file_name;
        $location='./upload/'.$name;
        echo $location;
       // $location='./upload/'.$file_name."_".date("d/m/Y");
         move_uploaded_file($_FILES["file"]["tmp_name"], $location);*/
         //echo "Success";
    /*$test=explode(".", $_FILES["image"]["name"]);
    $extension=end($test);
    $name= rand(100,999) . '.' . $extension;
    $location='./upload/'.$name;
    //echo $location;
    move_uploaded_file($_FILES["image"]["tmp_name"], $location);
    echo '<img src="'.$location.'" height="150" width="225" class="img-thumbnail"/>';*/
        
     
  // }
}
//}

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<style type="text/css">
		html, body {
  font-family: helvetica, arial;
  background: #eee;
}

main {
  width: 80%;
  margin: 0 auto;
  padding-top: 20%;
}

.box {
  background-color: #fff;
  border: 1px solid #ddd;
  display: block;
  max-width: 40em;
  margin: 0 auto;
  border-radius: 4px;
}
.box header {
  border-bottom: 1px solid #ddd;
  padding: 0.5em 1em;
  margin-bottom: 1em;
}
.box .content {
  padding: 1em;
}

.btn, button {
  color: #fff;
  background-color: #09f;
  border: 1px solid #09f;
  text-align: center;
  display: inline-block;
  vertical-align: middle;
  white-space: nowrap;
  margin: 0.6em 0.6em .6em 0;
  padding: 0.35em .7em 0.4em;
  text-decoration: none;
  width: auto;
  position: relative;
  border-radius: 4px;
  user-select: none;
  outline: none;
  -webkit-transition: all, 0.25s, ease-in;
  -moz-transition: all, 0.25s, ease-in;
  transition: all, 0.25s, ease-in;
}
.btn:hover, button:hover {
  background-color: #ddd;
  color: #333;
  -webkit-transition: all, 0.25s, ease-in;
  -moz-transition: all, 0.25s, ease-in;
  transition: all, 0.25s, ease-in;
}
.btn:active, button:active {
  background-color: #ccc;
  box-shadow: 0 !important;
  top: 2px;
  -webkit-transition: background-color, 0.2s, linear;
  -moz-transition: background-color, 0.2s, linear;
  transition: background-color, 0.2s, linear;
  box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
}

form {
  display: table;
}

input {
 /* border: 2px solid #eee;*/
  padding: 1em .25em;
  width: 96%;
  color: #999;
  border-radius: 4px;
}

.left, .right {
  display: table-cell;
  vertical-align: middle;

}

.right{
  width: 113px;
  position: relative;
  left: 60px;
}
.left {
  width: 6em;
  min-width: 6em;
  /*padding-right: 1em;*/
  border: 1px solid #ccc;
  height:81px;
  position: relative;
    left: 32px;
}
.left img {
  width: 100%;
}

.img-holder {
  display: block;
  vertical-align: middle;
  width: 2em;
  height: 2em;
}
.img-holder img {
  width: 100%;
  max-width: 100%;
}

.file-wrapper {
  cursor: pointer;
  display: inline-block;
  overflow: hidden;
  position: relative;
}
.file-wrapper:hover .btn {
  background-color: #33adff !important;
}

.file-wrapper input {
  cursor: pointer;
  font-size: 100px;
  height: 100%;
  filter: alpha(opacity=1);
  -moz-opacity: 0.01;
  opacity: 0.01;
  position: absolute;
  right: 0;
  top: 0;
  z-index: 9;
}
.file-holder{display: none;}
.center{position: relative;top: 11px;width: 273px;float: left;}
.center_input_filed{padding: 0em .25em!important;border: 1px solid #ccc!important;}
.select_div{width: 276px;float: left;position: relative;
    top: 19px;padding: 5px;}
    .clear{clear: both;}
	</style>
</head>
<body>
	<div class="container">
		<main>
		  	<div class="box">
			    <header>
			      <h3>Image Uploader </h3>
			    </header>
		    
			    <div class="content">

					<!-- Custom File Uploader  -->
	
						<div class="center">
              <?php 

              function w1250_to_utf8($text) {
   
    $map = array(
        chr(0x8A) => chr(0xA9),
        chr(0x8C) => chr(0xA6),
        chr(0x8D) => chr(0xAB),
        chr(0x8E) => chr(0xAE),
        chr(0x8F) => chr(0xAC),
        chr(0x9C) => chr(0xB6),
        chr(0x9D) => chr(0xBB),
        chr(0xA1) => chr(0xB7),
        chr(0xA5) => chr(0xA1),
        chr(0xBC) => chr(0xA5),
        chr(0x9F) => chr(0xBC),
        chr(0xB9) => chr(0xB1),
        chr(0x9A) => chr(0xB9),
        chr(0xBE) => chr(0xB5),
        chr(0x9E) => chr(0xBE),
        chr(0x80) => '&euro;',
        chr(0x82) => '&sbquo;',
        chr(0x84) => '&bdquo;',
        chr(0x85) => '&hellip;',
        chr(0x86) => '&dagger;',
        chr(0x87) => '&Dagger;',
        chr(0x89) => '&permil;',
        chr(0x8B) => '&lsaquo;',
        chr(0x91) => '&lsquo;',
        chr(0x92) => '&rsquo;',
        chr(0x93) => '&ldquo;',
        chr(0x94) => '&rdquo;',
        chr(0x95) => '&bull;',
        chr(0x96) => '&ndash;',
        chr(0x97) => '&mdash;',
        chr(0x99) => '&trade;',
        chr(0x9B) => '&rsquo;',
        chr(0xA6) => '&brvbar;',
        chr(0xA9) => '&copy;',
        chr(0xAB) => '&laquo;',
        chr(0xAE) => '&reg;',
        chr(0xB1) => '&plusmn;',
        chr(0xB5) => '&micro;',
        chr(0xB6) => '&para;',
        chr(0xB7) => '&middot;',
        chr(0xBB) => '&raquo;',
    );
    return html_entity_decode(mb_convert_encoding(strtr($text, $map), 'UTF-8', 'ISO-8859-2'), ENT_QUOTES, 'UTF-8');
}     ?>
  
              <form action="" id="form_isp_status" method="POST" enctype="multipart/form-data">
					<!-- <input type="text" class="center_input_filed" name="term_name" value="<?php echo w1250_to_utf8($posts['name']);?>"> -->
          <select name="termid" class="select_div">
          <?php  $query="SELECT A.name,A.term_id,B.count FROM wpr3_terms as A inner join 
   `wpr3_term_taxonomy` as B on A.term_id=B.term_id where taxonomy='sln_service_category' group by `name` having B.count>0 order by A.term_id ASC";
  $result = mysqli_query($link,$query) or die('Errant query:  '.$query);
  if(mysqli_num_rows($result)>=1) 
      {
      
         while($posts = mysqli_fetch_assoc($result))
           {

              ?>
      <option value="<?php echo $posts['term_id'];?>"><?php echo w1250_to_utf8($posts['name']);?></option>

           <?php } } ?>
           </select>
          
         <!--  <p><?php echo $posts['term_id'];?></p> -->
       <!--   <input type="hidden" id="term_id" name="term_id" value="<?php echo $posts['term_id'];?>">  -->
					 </div>
					  	<div class="left">
                <?php 
                  $imagepath=mysqli_query($link,"SELECT  `term_image_path` FROM `wpr3_term_image_path` WHERE `term_id`='$termid'") or die('Errant query:  image path');
                $path=mysqli_fetch_assoc($imagepath);

                $img_path=$path['term_image_path'];
                if($img_path==''){
                ?>

							<img id="img-uploaded" src="upload/salon_avatar.jpg" alt="your image" style="    height: 81px;width:95px;" />
              <?php }else{?>
              <img id="img-uploaded" src="<?php echo $img_path;?>" alt="your image" style="height: 81px;width:95px;" />
              <?php }?>
					  	</div>
					  	
					  	<div class="right">
					    	<!-- <input type="text" class="img-path" placeholder="Image Path"> -->
                
							 
							  	<!-- <input type="file" name="image" />
							<span class="btn btn-large btn-alpha">Upload</span>  -->

         <!-- <input type="file" name="image"  /> -->
         <input type="file" name="files[]" multiple/>
        
          <!--  <input type="submit" id="selectedButton"/ class="btn btn-success" name="upload" value="Upload" /> --> 
      
						
               
                
             
              
					  	</div>
					  	<div style="position: relative;top: 8px;">
					  		 <!-- <input type="submit" id="<?php echo $posts['term_id'];?>" class="btn btn-success" name="submit" value="Save" />  -->
                <input class='btn btn-success' type='submit' name='isp_value[]' value=save style="float: right;"></input>
                
					  	</div>
             <div class="clear"></div>
              
					</form>
				</div>
		  	</div>
		</main>
	</div>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<!-- <script type="text/javascript">
		
/*----------------------------------------
Upload btn
------------------------------------------*/
var SITE = SITE || {};
 
SITE.fileInputs = function() {
  var $this = $(this),
      $val = $this.val(),
      valArray = $val.split('\\'),
      newVal = valArray[valArray.length-1],
      $button = $this.siblings('.btn'),
      $fakeFile = $this.siblings('.file-holder');
  if(newVal !== '') {
    $button.text('Change');

    if($fakeFile.length === 0) {
      $button.after('<span class="file-holder">' + newVal + '</span>');
    } else {
      $fakeFile.text(newVal);
    }
  }
};
 

$('.file-wrapper input[type=file]').bind('change focus click', SITE.fileInputs);

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    var tmppath = URL.createObjectURL(event.target.files[0]);

    reader.onload = function (e) {
      $('#img-uploaded').attr('src', e.target.result);
      $('input.img-path').val(tmppath);
    }

    reader.readAsDataURL(input.files[0]);
  }
}

$(".uploader").change(function(){
  readURL(this);
});


	</script> -->
</body>
</html>