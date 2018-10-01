<!DOCTYPE html>
<html>
<head>
	<title>Complete CRUD Laravel</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<!-- <script type="text/javascript" src="{{URL::asset('assets/js/jquery.min.js')}}"></script>
	<script type="text/javascript" src="{{URL::asset('assets/js/jquery-3.3.1.js')}}"></script> -->
</head>
<body>
	<br/><br/>
	<div class="container" align="center">
		<h2 align="center">upload file</h2>
		<br/>
		<br/>
		<label>Select Image</label>
		<input type="file" name="file" id="file" />
		<br/>
		<span id="uploaded_image"></span>
	</div>
</body>
</html>
<script type="text/javascript">
	
	$(document).ready(function()
	{
		$(document).on('change','#file',function()
		{
			var property=document.getElementById("file").files[0];
			var image_name=property.name;
			var image_extension=image_name.split(".").pop().toLowerCase();
			if(jQuery.inArray(image_extension, ['gif','png','jpg','jpeg'])==-1)
			{
				alert("invalid Image File");
			}
			var image_size=property.size;
			if(image_size>2000000)
			{
				alert("Image file size is very big");
			}
			else
			{

				var form_data= new FormData();
				form_data.append("file", property);
				$.ajax({
							url:"upload.php",
							method:"POST",
							data:form_data,
							contentType:false,
							cache:false,
							processData:false,
							beforeSend:function(){

								$('#uploaded_image').html("<label class='text-success'>Image Uploading....</label>");
							},
							success:function(data)
							{
								$('#uploaded_image').html(data);
							}


				})
			}

	})

		});
	
</script>