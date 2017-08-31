<?php

session_start();
error_reporting( E_ALL );
include "config.php";

if(!$_SESSION['login_user']) 
{
    header("location:index.php");
}

if(isset($_GET['id']))
{

	$id=$_GET['id'];
	$q1 = "select * from tbl_news,tbl_category,tbl_subcategory where news_id = $id";
	$exe_data=mysqli_query($conn,$q1);
	
	$row1=mysqli_fetch_array($exe_data);


	$q2= "select * from tbl_news_img where news_id = $id";
	$exe_data2=mysqli_query($conn,$q2);
	$rowImgcount=mysqli_num_rows($exe_data2);
	//$row2=mysqli_fetch_array($exe_data2);
	$newsImageData = [];
	while($imageRow = mysqli_fetch_array($exe_data2)){
		$newsImageData[] = $imageRow;
	}
}
if(isset($_GET['did']))
{
	$did = $_GET['did'];

	$q = "delete from tbl_news_img where id='$did'";
	$exe_del=mysqli_query($conn,$q);
	if($exe_del){
		?>
		<script>
			alert('Deleted Record successfully.');
			window.location='editNews.php?id=<?php echo $_GET["uid"]; ?>';
		</script>
		
		<?php
	}
	else{
		?>
		<script>
		alert('Failed to Delete Img!');
		window.location='editNews.php?id=<?php echo $_GET["uid"]; ?>';
		</script>
		
		<?php
	}
}
if(isset($_POST['update']))
{
	$uid=$_POST['uid'];
	$NewsTitle=$_POST['NewsTitle'];
	$NewsDesc=$_POST['NewsDesc'];
	$language=$_POST['language'];
	$newsCatId=$_POST['newsCatId'];


	// characterset conversion for title
	$fromCSTitle = mb_detect_encoding($NewsTitle);
	$toCSTitle = "UTF-8";
	$NewsTitle = mb_convert_encoding ($NewsTitle , $toCSTitle, $fromCSTitle); 

	// characterset conversion for description
	$fromCS = mb_detect_encoding($NewsDesc);
	$toCS = "UTF-8";
	$NewsDesc = mb_convert_encoding ($NewsDesc , $toCS, $fromCS); 

	$imgname = $_FILES['imgad']['name'];
	$imgtype = $_FILES['imgad']['type'];
	$imgsize = $_FILES['imgad']['size'];
	$imgtmp = $_FILES['imgad']['tmp_name'];
	
	$imagedetails = getimagesize($imgtmp);
	$width = $imagedetails[0];
	$height = $imagedetails[1];
	
	echo $width . $height;

	if($imgname == "")
	{
		$q = "update tbl_news set news_cat='$newsCatId', lang='$language', news_title = '$NewsTitle', news_desc = '$NewsDesc' where news_id = '".$uid."'";
	}
	else
	{
		if($width == 168 and $height == 169)
		{
			$imgExt = strtolower(pathinfo($imgname,PATHINFO_EXTENSION));
			
			$pic1 = rand(100000000,100000000000).".".$imgExt;
			$imgpath = "GujaratAboardWebService/news_img/$pic1";

			$select2 = "select * from tbl_news where news_id = '".$uid."'";
			$result2 = mysqli_query($conn,$select2);
			
			while($row2 = mysqli_fetch_array($result2))
			{
				unlink("GujaratAboardWebService/news_img/".$row2['news_img']);
				move_uploaded_file($imgtmp,$imgpath);
			}
			$q = "update tbl_news set news_cat='$newsCatId', lang='$language', news_title = '$NewsTitle', news_img = '$pic1', news_desc = '$NewsDesc' where news_id = '".$uid."'";
		}
	}
	
	$exe_update=mysqli_query($conn,$q);	

	var_dump($_FILES['photo']['name']);
	if(sizeof($_FILES['photo']['name']) > 0)
		{
			
			$newsId = $uid;
			
			for($i=0; $i<count($_FILES['photo']['name']); $i++)
			{
				$imgname = $_FILES['photo']['name'][$i];
				$imgtype = $_FILES['photo']['type'][$i];
				$imgsize = $_FILES['photo']['size'][$i];
				$imgtmp = $_FILES['photo']['tmp_name'][$i];
				
				$imagedetails = getimagesize($imgtmp);
				$width = $imagedetails[0];
				$height = $imagedetails[1];
				
				echo $width . $height;
				
				if($width == 1200 and $height == 600)
				{

				
					$imgExt = strtolower(pathinfo($imgname,PATHINFO_EXTENSION));
					
					$pic = rand(100000000,100000000000).".".$imgExt;
					$imgpath = "GujaratAboardWebService/news_img/$pic";
					
					move_uploaded_file($imgtmp,$imgpath);	
					
					$q1 = "insert into tbl_news_img(news_id,news_img) values('$newsId','$pic')";
					
					$exe1=mysqli_query($conn,$q1);
				}
				else
				{?>
					<script>
						alert('Height and Width Must be 1200 by 600.');
						window.location='news.php';
			        </script> <?php
			    }
			}
		}
	
	
	
	if($exe_update)
	{
		?>
		<script>
		alert('Updated Record successfully.');
		window.location='news.php';
		</script>
		
		<?php
	}
	else
	{
		?>
		<script>
		alert('Failed to Update Advertisment!');
		window.location='editNews.php?id=<?php echo $uid; ?>';
		</script>
		
		<?php
	}
}


?>


<html lang="en">

<head>

    <title>Gujarat Abroad</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    
    <link href='css/contact.css' rel='stylesheet' type='text/css' />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//cdn.ckeditor.com/4.7.1/full/ckeditor.js"></script>
    <script>
	function getState(val) {
		$.ajax({
		type: "POST",
		url: "cat_ajx.php",
		data:'cat_id='+val,
		success: function(data){
				$("#seSubCat").html(data);
		}
		});
	}
	</script> 
        <script>
	    $(function(){
			CKEDITOR.replace('NewsDesc');
	    });	
    </script> 
</head>

<body>

	

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="n-avbar navbar-inverse navbar-fixed-top" role="navigation">
            <?php include 'header.php'; ?>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li>
                        <a href="NewsCat.php"> News Category </a>
                    </li>
					<li>
                        <a href="NewsSubCat.php"> News Sub Category </a>
                    </li>
                    <li class="active">
                        <a href="news.php"> News </a>
                    </li>
                    <li>
                        <a href="video.php"> Video </a>
                    </li>
                    <li>
                        <a href="advertisment.php"> Advertisment </a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h3 align="left"> Edit News </h3><hr style="border-top: 1px solid #ddd !important;padding:5px;">
                    </div>
                </div>
                <!-- /.row -->
                
                <div class="row">
                    <div class="col-lg-12">
                    	<section id='contact'>
						
						    <form name='NewsAddForm' method='post' action='<?php echo $_SERVER['PHP_SELF']; ?>' id='contactform' enctype= 'multipart/form-data'>
								<input type="hidden" value="<?php if(isset($_GET['id'])){ echo $row1['news_id'];} ?>" name="uid">
								
								<div>
                                    <label>Category:</label>
                                    
									<select name = 'newsCatId' id = 'seCat' onChange="getState(this.value);">
									<?php 
									if(isset($_GET['id'])){
										
									$id_news=$_GET['id'];
									
									?>
									<option value="<?php if(isset($_GET['id'])) 
															{
																echo $row1['news_cat']; 
															} 
													?>">
													<?php 
														if(isset($_GET['id'])) 
														{ 
															echo $row1['cat_name']; 
														} }?></option>
										<?php 
											$q3 = "select * from tbl_category";
											$result3 = mysqli_query($conn,$q3);
											
											while($row3 = mysqli_fetch_array($result3))
											{?>
												<option value="<?php echo $row3['cat_id'];?>"><?php echo $row3['cat_name'];?></option>
										<?php }
										 ?>
										 
									</select>
                                    
                                </div>
                                <div>
                                    <label>Sub Category:</label>
									<select name = 'newsSubCatId' id = 'seSubCat'>
										<?php 
									if(isset($_GET['id'])){
										
									$id_news=$_GET['id'];
									
									?>
									<option value="<?php if(isset($_GET['id'])) 
															{
																echo $row1['news_subcat']; 
															} 
													?>">
													<?php 
														if(isset($_GET['id'])) 
														{ 
															echo $row1['subcat_name']; 
														} }?></option>
									</select>
                                </div>
								<!--
								<div>
                                    <label>Language:</label>
									<select name = 'language'>
										<?php 
											if(isset($_GET['id']))
											{ ?>
												<option value="<?php if(isset($_GET['id'])){ echo $row1['lang'];} ?>"><?php if($row1['lang'] == 1){ echo 'English';} else if($row1['lang'] == 2){echo 'Gujarati';} ?></option>
										<?php } 
										?>
										<option value="1">English</option>
										 <option value="2">Gujarati</option>
									</select>
                                </div>
                                -->
                                <input type="hidden" value="2" name="language" />
								<div>
                                    <label>Title:</label>
                                    <input name='NewsTitle' value="<?php if(isset($_GET['id'])){ echo $row1['news_title'];} ?>" type='text' id='NewsTitle' placeholder='Enter News Title' required='required' />
                                </div>
                                <div>
									<label></label>
									<img src="GujaratAboardWebService/news_img/<?php if(isset($_GET['id'])){ echo $row1['news_img'];} ?>" width="100px" height="100px" />
								</div>
								<div>
                                    <label>Image:</label>
									<input name='imgad' type='file' id='img' />
                                </div>
                                <div>
                                	<label></label>
                                    <p style="color:#F00">Image Size must be 168 X 169.</p>
                                </div>
								<div>
									<label></label>
									<?php 
									foreach($newsImageData as $newsImageRow)
									{?>

										<img src="GujaratAboardWebService/news_img/<?php if(isset($_GET['id'])){ echo $newsImageRow['news_img'];} ?>" width="100px" height="100px"/>
										<a href="editNews.php?uid=<?php echo $row1['news_id']; ?>&did=<?php echo $newsImageRow['id'];?>"" class="btn btn-info btn">
								          <span class="glyphicon glyphicon-remove"></span>
								        </a>

									<?php } ?>
								</div>
								<div>
                                    <label>Image:</label>
									<input name='photo[]' type='file' id='img' multiple/>
                                </div>
                                <div>
                                	<label></label>
                                    <p style="color:#F00">Image Size must be 1200 X 600 and Multiple Image Select.</p>
                                </div>
                                
								<div>
                                	<label>Details: </label><br/><br/><br/>
									<textarea name='NewsDesc' id = 'NewsDesc'  placeholder='Enter News Details' required='required'><?php if(isset($_GET['id'])){ echo $row1['news_desc'];} ?></textarea>
                                </div>
                                <br>
								<?php 
								if(isset($_GET['id']))
								{
								?>
									<input type='submit' name="update" class='submit' id='submit' value='Update' />
								<?php } else {?>
									<input type='submit' name="add" class='submit' id='submit' value='Submit' />
									<input type='reset' class='cancel' id='cancel' value='Cancel' />
								<?php } ?>
                            </form><br>
                        </section>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
