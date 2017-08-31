<?php
session_start();
error_reporting( E_ALL );
include "config.php";

if(!$_SESSION['login_user']) 
{
    header("location:index.php");
}


if(isset($_POST['add']))
{
	$NewsTitle=str_replace("'","\'",$_POST['NewsTitle']);
	$NewsDesc=str_replace("'","\'",$_POST['NewsDesc']);

	// characterset conversion for title
	$fromCSTitle = mb_detect_encoding($NewsTitle);
	$toCSTitle = "UTF-8";
	$NewsTitle = mb_convert_encoding ($NewsTitle , $toCSTitle, $fromCSTitle); 
	$NewsTitle = "<p>".$NewsTitle."</p>";

	// characterset conversion for description
	$fromCS = mb_detect_encoding($NewsDesc);
	$toCS = "UTF-8";
	$NewsDesc = mb_convert_encoding ($NewsDesc , $toCS, $fromCS); 

	$language=$_POST['language'];
	$newsCatId=$_POST['newsCatId'];
	if(isset($_POST['newsSubCatId'])){
		$newsSubCatId=$_POST['newsSubCatId'];
	}else{
		$newsSubCatId=0;
	}

	$imgname = $_FILES['imgad']['name'];
	$imgtype = $_FILES['imgad']['type'];
	$imgsize = $_FILES['imgad']['size'];
	$imgtmp = $_FILES['imgad']['tmp_name'];
	
	$imagedetails = getimagesize($imgtmp);
	$width = $imagedetails[0];
	$height = $imagedetails[1];
	
	echo $width . $height;
	
	if($width == 168 and $height == 169)
	{
		$imgExt = strtolower(pathinfo($imgname,PATHINFO_EXTENSION));
		
		$pic1 = rand(100000000,100000000000).".".$imgExt;
		$imgpath = "GujaratAboardWebService/news_img/$pic1";
		
		move_uploaded_file($imgtmp,$imgpath);	
		
		$q = "insert into tbl_news(news_cat,news_subcat,lang,news_title,news_img,news_desc) values('$newsCatId','$newsSubCatId','$language','$NewsTitle','$pic1','$NewsDesc')";
		
		$exe=mysqli_query($conn,$q);
	}
	else
	{?>
		<script>
			alert('Height and Width Must be 168 by 169.');
			window.location='news.php';
        </script> <?php
    }
	
	
	if(count($_FILES['photo']['name']) > 0)
	{
		
		$newsId = $conn->insert_id;
		
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

		if($exe & $exe1){
			?>
			<script>
				alert('Addes news successfully.');exit;
				window.location='news.php';
			</script>
			
			<?php
		}
		else{
			?>
			<script>
				alert('Failed to add news!');
				window.location='news.php';
			</script>
			
			<?php
		}

	}
	else
	{
		?>
		<script>
			alert('Please 3 image Select...');
			window.location='addNews.php';
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
                        <h3 align="left"> Add News</h3><hr style="border-top: 1px solid #ddd !important;padding:5px;">

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
                                    <input name='NewsTitle' type='text' id='NewsTitle' placeholder='Enter News Title' required='required' />
                                </div>
                                <div>
                                    <label>Image AD:</label>
									<input name='imgad' type='file' id='img' required/>
                                </div>
								
                                <div>
                                	<label></label>
                                    <p style="color:#F00">Image Size must be 168 X 169.</p>
                                </div>
								<div>
                                    <label>Image:</label>
									<input name='photo[]' type='file' id='img' required multiple/>
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
									<input type='submit' name="add" class='submit' id='submit' value='Submit' />
									<input type='reset' class='cancel' id='cancel' value='Cancel' />
                            </form><br>
                        </section>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>