<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<?php
session_start();
error_reporting( E_ALL );
if(!$_SESSION['login_user']) 
{
    header("location:index.php");
}
include "config.php";
if(isset($_POST['add']))
{
	$NewsTitle=str_replace("'","\'",$_POST['NewsTitle']);
	$NewsDesc=str_replace("'","\'",$_POST['NewsDesc']);

	// characterset conversion for title
	$fromCSTitle = mb_detect_encoding($NewsTitle);
	$toCSTitle = "UTF-8";
	$NewsTitle = mb_convert_encoding ($NewsTitle , $toCSTitle, $fromCSTitle); 

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
	
	
	if(count($_FILES['photo']['name']) > 0)
	{
		$q = "insert into tbl_news(news_cat,news_subcat,lang,news_title,news_desc) values('$newsCatId','$newsSubCatId','$language','$NewsTitle','$NewsDesc')";

		$exe=mysqli_query($conn,$q);

		$newsId = $conn->insert_id;
		
		echo "last insert id in query is ", $newsId, "\n";

		for($i=0; $i<count($_FILES['photo']['name']); $i++)
		{
			$imgname = $_FILES['photo']['name'][$i];
			$imgtype = $_FILES['photo']['type'][$i];
			$imgsize = $_FILES['photo']['size'][$i];
			$imgtmp = $_FILES['photo']['tmp_name'][$i];
			
			$imagedetails = getimagesize($imgtmp);
			$width = $imagedetails[0];
			$height = $imagedetails[1];
			
			//echo $width . $height;
			
			if($width == 168 and $height == 169)
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
					alert('Height and Width Must be 168 by 169.');
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
			window.location='news.php';
		</script>
		
		<?php
	}
}

if(isset($_GET['did']))
{
	$id = $_GET['did'];
	
	$select1 = "select news_img from tbl_news_img where news_id=$id";
	$result1 = mysqli_query($conn,$select1);
	$rowcount=mysqli_num_rows($result1);

	for($i=0; $i<$rowcount; $i++)
	{
		while($row1 = mysqli_fetch_array($result1))
		{
			unlink("GujaratAboardWebService/news_img/".$row1['news_img']);
		}
	}
	
	$q = "delete from tbl_news where news_id='$id'";
	$exe_del=mysqli_query($conn,$q);

	$q1 = "delete from tbl_news_img where news_id='$id'";
	$exe_del1=mysqli_query($conn,$q1);

	if($exe_del & $exe_del1){
		?>
		<script>
			alert('Deleted Record successfully.');
			window.location='news.php';
		</script>
		
		<?php
	}
	else{
		?>
		<script>
		alert('Failed to Delete news!');
		window.location='news.php';
		</script>
		
		<?php
	}
}

if(isset($_GET['id']))
{

	$id=$_GET['id'];
	$q1 = "select * from tbl_news,tbl_category,tbl_subcategory where news_id = $id and cat_id = news_cat and subcat_id = news_subcat";
	$exe_data=mysqli_query($conn,$q1);
	
	$row1=mysqli_fetch_array($exe_data);

	$q2= "select * from tbl_news_img where news_id = $id";
	$exe_data2=mysqli_query($conn,$q2);
	$rowImgcount=mysqli_num_rows($exe_data2);
	$row2=mysqli_fetch_array($exe_data2);
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
	
	$q = "update tbl_news set news_cat='$newsCatId', lang='$language', news_title = '$NewsTitle', news_desc = '$NewsDesc' where news_id = '".$uid."'";
	
	$exe_update=mysqli_query($conn,$q);	
	
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
		window.location='news.php';
		</script>
		
		<?php
	}
}

?>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>


<html lang="en">

<head>

	<script src="//cdn.ckeditor.com/4.7.1/full/ckeditor.js"></script>
   
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
    <style type="text/css">
    
    </style>
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
                        <h1>
                            News
                        </h1><hr style="border-top: 1px solid #ddd !important;padding:5px;">
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
								<div>
                                    <label>Title:</label>
                                    <input name='NewsTitle' value="<?php if(isset($_GET['id'])){ echo $row1['news_title'];} ?>" type='text' id='NewsTitle' placeholder='Enter News Title' required='required' />
                                </div>
								<?php 
								if(isset($_GET['id']))
								{
								?>
								<div>
									<label></label>
									<?php 
									for($i=0; $i<$rowImgcount; $i++)
									{?>
										<img src="GujaratAboardWebService/news_img/<?php if(isset($_GET['id'])){ echo $row2['news_img'];} ?>" width="100px" height="100px" />
									<?php } ?>
								</div>
								<div>
                                    <label>Image:</label>
									<input name='photo' type='file' id='img' />
                                </div>
								<?php } else { ?>
								<div>
                                    <label>Image:</label>
									<input name='photo' type='file' id='img' required/>
                                </div>
								<?php } ?>
                                <div>
                                	<label></label>
                                    <p style="color:#F00">Image Size must be 168 X 169.</p>
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
                        <h3 align="center"> News Record </h3><hr style="border-top: 1px solid #ddd !important;padding:5px;">
                    </div>
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="table-responsive">
                        	<?php 
								$num_rec_per_page=10;
								if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
								$start_from = ($page-1) * $num_rec_per_page; 
								
								$q = "select * from tbl_news";
								$result = mysqli_query($conn,$q);
								$total_records = mysqli_num_rows($result);  //count number of records
								$total_pages = ceil($total_records / $num_rec_per_page); ?>
                                
								<div style="background-color:#337ab7; margin-bottom:20px; border-radius:4px"><?php 
								echo"<center><ul class='pagination'><li><a href='news.php?page=1'>First Page</a></li>";
								for ($i=1; $i<=$total_pages; $i++) { 
											echo "<li><a href='news.php?page=".$i."'>".$i."</a></li> "; 
								}; 
								echo "<li><a href='news.php?page=$total_pages'>".'Last Page'."</a></li></ul></center>"; // Goto last page
                                ?></div>
                                
								<?php
								$q = "select * from tbl_news, tbl_category where cat_id = news_cat LIMIT $start_from, $num_rec_per_page";
									
								$result = mysqli_query($conn,$q);
												
								?>
								<table class='table table-bordered table-hover'>
										<thead>
											<tr>
												<th>Id</th>
												<th>Category</th>
												<th>SubCat</th>
												<th>Language</th>
												<th>Title</th>
												<th>Description</th>
												<th>Date</th>
												<th colspan='3' align='center'>Action</th>
											</tr>
										</thead><tbody>
								<?php while($row = mysqli_fetch_array($result)) { ?>
								<tr>
									<td><?php echo $row['news_id']; ?></td>
									<td><?php echo $row['cat_name']; ?></td>
									<td><?php echo $row['news_subcat']; ?></td>
									<td>
										<?php 
										if($row['lang']==1)
										{ 
											echo'English';
										}
										else if($row['lang']==2)
										{ 
											echo 'Gujarati';
										} 
										?>							
									</td>
									<td><?php echo $row['news_title']; ?></td>
									<td><?php echo substr($row['news_desc'],0,200); ?>...</td>
									<td><?php echo $row['date']; ?></td>
									<td><a href='newsView.php?id=<?php echo $row[0];?>'><i class='fa fa-eye fa-2x' aria-hidden='true'></i></a></td>
									<td><a href='news.php?id=<?php echo $row[0];?>'><i class='fa fa-pencil-square fa-2x' aria-hidden='true'></i></a></td>
									<td><a onClick="return confirm('are You sure to delete?')" href="news.php?did=<?php echo $row[0];?>"><i class="fa fa-trash-o fa-2x" aria-hidden="true"></i></a></td>
								</tr>

								<?php } ?>
								</tbody></table>
                            
                        </div>
                    </div>
                </div>
				<!-- /.row -->
                <div class="row">
                	<br><br><br><br>
                </div>
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
		<div>
        	<?php include 'footer.php'; ?>
        </div>
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script>
	   		CKEDITOR.replace('NewsDesc');
    </script>  
       
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="js/plugins/morris/raphael.min.js"></script>
    <script src="js/plugins/morris/morris.min.js"></script>
    <script src="js/plugins/morris/morris-data.js"></script>
	
</body>
</html>
