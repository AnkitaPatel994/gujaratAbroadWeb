<?php
session_start();

if(!$_SESSION['login_user']) 
{
    header("location:index.php");
}

include "config.php";

$ad_query = mysqli_query($conn,"select * from tbl_ads_type");
$adTypeArray = array();

while($typeRow = mysqli_fetch_array($ad_query))
{
	$adTypeArray[$typeRow["column_code"]] = $typeRow;
}

if(isset($_POST['add']))
{
	$typeArray = $_POST["checkbox"];
	$ttl=$_POST['NewsTitle'];
	$url=$_POST['NewsWebUrl'];
	//$position=$_POST['poId'];
	
	$imgname = $_FILES['photo']['name'];
	$imgtype = $_FILES['photo']['type'];
	$imgsize = $_FILES['photo']['size'];
	$imgtmp = $_FILES['photo']['tmp_name'];
	
	//echo $imgname . $imgtype . $imgsize . $imgtmp;
	
	$imagedetails = getimagesize($imgtmp);
	$width = $imagedetails[0];
	$height = $imagedetails[1];
	
	//echo $width . $height;
	
	$imgnamebanner = $_FILES['photobanner']['name'];
	$imgtypebanner = $_FILES['photobanner']['type'];
	$imgsizebanner = $_FILES['photobanner']['size'];
	$imgtmpbanner = $_FILES['photobanner']['tmp_name'];
	
	//echo $imgnamebanner . $imgtypebanner . $imgsizebanner . $imgtmpbanner;
	
	$imagedetailsbanner = getimagesize($imgtmpbanner);
	$widthbanner = $imagedetailsbanner[0];
	$heightbanner = $imagedetailsbanner[1];
	
	//echo $widthbanner . $heightbanner;
	
	if($widthbanner == 1136 and $heightbanner == 338 and $width == 600 and $height == 800)
	{
		$imgExt = strtolower(pathinfo($imgname,PATHINFO_EXTENSION));
		
		$pic = rand(100000000,100000000000).".".$imgExt;
		$imgpath = "GujaratAboardWebService/add_img/$pic";
		
		move_uploaded_file($imgtmp,$imgpath);	
		
		$imgExtbanner = strtolower(pathinfo($imgnamebanner,PATHINFO_EXTENSION));
		
		$picbanner = rand(100000000,100000000000).".".$imgExtbanner;
		$imgpathbanner = "GujaratAboardWebService/add_img/$picbanner";
		
		move_uploaded_file($imgtmpbanner,$imgpathbanner);
		
		$q = "insert into tbl_advertice(add_title,add_link,add_banner,add_thumbnill) values('$ttl','$url','$picbanner','$pic')";
		
		$exe=mysqli_query($conn,$q);
		
		if($exe){
			if(sizeof($typeArray)!=0){

				$inId = mysqli_insert_id($conn);
				$partialQuery = array();
				foreach ($typeArray as $key => $columnCode) {
					$partialQuery[] = $columnCode."='1'";
				}
				$queryString = implode(",",$partialQuery);

				$que = "update tbl_advertice set $queryString where add_id = $inId";
				mysqli_query($conn,$que);
				
			}

			?>
			<script>
			alert('Addes news successfully.');
			window.location='advertisment.php';
			</script>
			
			<?php
		}
		else{
			?>
			<script>
			alert('Failed to add news!');
			window.location='advertisment.php';
			</script>
			
			<?php
		}
	}
	else
	{
		?>
		<script>
		alert('Height and Width Must be 1136 X 338.');
		window.location='advertisment.php';
		</script>
		
		<?php
	}
}

if(isset($_GET['did']))
{
	$id = $_GET['did'];
	
	$select = "select add_thumbnill,add_banner from tbl_advertice where add_id=$id";
	$result = mysqli_query($conn,$select);
	
	while($row = mysqli_fetch_array($result))
	{
		unlink("GujaratAboardWebService/add_img/".$row['add_banner']);
		unlink("GujaratAboardWebService/add_img/".$row['add_thumbnill']);
	}
	
	$q = "delete from tbl_advertice where add_id='$id'";
	$exe_del=mysqli_query($conn,$q);
	
	if($exe_del){
		?>
		<script>
		alert('Deleted Record successfully.');
		window.location='advertisment.php';
		</script>
		
		<?php
	}
	else{
		?>
		<script>
		alert('Failed to Delete advertisement!');
		window.location='advertisment.php';
		</script>
		
		<?php
	}
}

if(isset($_GET['id']))
{

	$id=$_GET['id'];
	$q1 = "select * from tbl_advertice where add_id = $id";
	$exe_data=mysqli_query($conn,$q1);
	
	$row1=mysqli_fetch_array($exe_data);
}

if(isset($_POST['update']))
{
	$uid=$_POST['uid'];
	$NewsTitle = $_POST['NewsTitle'];
	$NewsWebUrl = $_POST['NewsWebUrl'];
	$typeArray = $_POST["checkbox"];
	
	$imgname = $_FILES['photo']['name'];
	$imgtype = $_FILES['photo']['type'];
	$imgsize = $_FILES['photo']['size'];
	$imgtmp = $_FILES['photo']['tmp_name'];
	
	echo $imgname . $imgtype . $imgsize . $imgtmp;
	
	$imagedetails = getimagesize($imgtmp);
	$width = $imagedetails[0];
	$height = $imagedetails[1];
	
	echo $width . $height;
	
	$imgnamebanner = $_FILES['photobanner']['name'];
	$imgtypebanner = $_FILES['photobanner']['type'];
	$imgsizebanner = $_FILES['photobanner']['size'];
	$imgtmpbanner = $_FILES['photobanner']['tmp_name'];
	
	echo $imgnamebanner . $imgtypebanner . $imgsizebanner . $imgtmpbanner;
	
	$imagedetailsbanner = getimagesize($imgtmpbanner);
	$widthbanner = $imagedetailsbanner[0];
	$heightbanner = $imagedetailsbanner[1];
	
	echo $widthbanner . $heightbanner;

	$partialQuery = array();
	foreach ($adTypeArray as $key => $adTypeRow) {
		$adTypeCode = $adTypeRow["column_code"];
		if (in_array($adTypeCode, $typeArray)){
			$partialQuery[] = $adTypeCode."='1'";
		}else{
			$partialQuery[] = $adTypeCode."='0'";
		}
	}
	$queryString = implode(",",$partialQuery);
	$que = "update tbl_advertice set $queryString where add_id = $uid";
	mysqli_query($conn,$que);

	if($imgname == "")
	{
		
		$q = "update tbl_advertice set add_title = '$NewsTitle', add_link = '$NewsWebUrl' where add_id = '".$uid."'";
	}
	else
	{
		if($widthbanner == 1136 and $heightbanner == 338 and $width == 600 and $height == 800)
		{
			$imgExt = strtolower(pathinfo($imgname,PATHINFO_EXTENSION));
			
			$pic = rand(100000000,100000000000).".".$imgExt;
			$imgpath = "GujaratAboardWebService/add_img/$pic";
			
			$imgExtbanner = strtolower(pathinfo($imgnamebanner,PATHINFO_EXTENSION));
		
			$picbanner = rand(100000000,100000000000).".".$imgExtbanner;
			$imgpathbanner = "GujaratAboardWebService/add_img/$picbanner";
		
			$select2 = "select add_thumbnill,add_banner from tbl_advertice where add_id=$uid";
			$result2 = mysqli_query($conn,$select2);
			
			while($row2 = mysqli_fetch_array($result2))
			{
				unlink("GujaratAboardWebService/add_img/".$row2['add_thumbnill']);
				move_uploaded_file($imgtmp,$imgpath);
				
				unlink("GujaratAboardWebService/add_img/".$row2['add_banner']);
				move_uploaded_file($imgtmpbanner,$imgpathbanner);
			}
			
			$q = "update tbl_advertice set add_title = '$NewsTitle', add_link = '$NewsWebUrl', add_banner = '$picbanner', add_thumbnill = '$pic' where add_id = '".$uid."'";
		}
		else
		{
			?>
			<script>
				alert('Height and Width Must be 1136 X 338.');
				window.location='advertisment.php';
			</script>
			
			<?php
		}
	}

	$exe_update=mysqli_query($conn,$q);	
	
	if($exe_update)
	{
		?>
		<script>
		alert('Updated Record successfully.');
		window.location='advertisment.php';
		</script>
		
		<?php
	}
	else
	{
		?>
		<script>
		alert('Failed to Update Advertisement!');
		window.location='advertisment.php';
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

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
           	<?php include 'header.php'; ?>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li>
                        <a href="NewsCat.php"> News Category </a>
                    </li>
					<li>
                        <a href="NewsSubCat.php"> News Sub Category </a>
                    </li>
                    <li>
                        <a href="news.php"> News </a>
                    </li>
                    <li>
                        <a href="video.php"> Video </a>
                    </li>
                    <li class="active">
                        <a href="advertisment.php"> Advertisement </a>
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
                            Advertisement
                        </h1><hr style="border-top: 1px solid #ddd !important;padding:5px;">
                    </div>
                </div>
                <!-- /.row -->
                
                <div class="row">
                    <div class="col-lg-12">
                    	<section id='contact'>
                            <form name='NewsCatAddForm' method='post' action='<?php echo $_SERVER['PHP_SELF']; ?>' id='contactform' enctype= 'multipart/form-data'>
								<input type="hidden" value="<?php if(isset($_GET['id'])){ echo $row1['add_id'];} ?>" name="uid">
								<div>
                                    <label>Title:</label>
                                    <input name='NewsTitle' value="<?php if(isset($_GET['id'])){ echo $row1['add_title'];} ?>" type='text' id='NewsTitle' placeholder='Enter Advertisement Title' required='required' />
                                </div>
								<?php 
								if(isset($_GET['id']))
								{
								?>
								<div>
									<label></label>
									<img src="GujaratAboardWebService/add_img/<?php if(isset($_GET['id'])){ echo $row1['add_banner'];} ?>" width="120px" height="50px" />
								</div>
								<div>
                                    <label>Banner:</label>
									<input name='photobanner' type='file' id='img'/>
                                </div>
								<?php } else { ?>
								<div>
                                    <label>Banner:</label>
									<input name='photobanner' type='file' id='img' required/>
                                </div>
								<?php } ?>
                                <div>
                                	<label></label>
                                    <p style="color:#F00">Image Size must be 1136 X 338.</p>
                                </div>
                                
                                <?php 
								if(isset($_GET['id']))
								{

								?>
								<div>
									<label></label>
									<img src="GujaratAboardWebService/add_img/<?php if(isset($_GET['id'])){ echo $row1['add_thumbnill'];} ?>" width="100px" height="100px" />
								</div>
								<div>
                                    <label>Image:</label>
									<input name='photo' type='file' id='img'/>
                                </div>
								<?php } else { ?>
								<div>
                                    <label>Image:</label>
									<input name='photo' type='file' id='img' required/>
                                </div>
								<?php } ?>
                                <div>
                                	<label></label>
                                    <p style="color:#F00">Image Size must be 600 X 800.</p>
                                </div>
                                
								<div>
                                    <label>Web URL:</label>
                                    <input name='NewsWebUrl' value="<?php if(isset($_GET['id'])){ echo $row1['add_link'];} ?>" type='text' id='NewsWebUrl' placeholder='Enter Web URL' required='required'/>
                                </div>
                                <div>
                                    <label>Position:</label>
									<h4 class="check"><?php 
										$q3 = "select * from tbl_ads_type";
										$result3 = mysqli_query($conn,$q3);
										
										while($row3 = mysqli_fetch_array($result3))
										{
											$checkCode = "";
											if(isset($_GET["id"])){
												if($row1[$row3['column_code']] == 1){
													$checkCode = "checked='checked'";
												}
											}
											
											?>
											<input name="checkbox[]" type="checkbox" <?php echo $checkCode; ?> value="<?php echo $row3['column_code'];?>"/><?php echo $row3['name'];?>
									<?php }
									 ?></h4>
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
                        <h3 align="center"> Advertisement Record </h3><hr style="border-top: 1px solid #ddd !important;padding:5px;">
                    </div>
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="table-responsive">
                        	<?php 
								include "config.php";
								
								$num_rec_per_page=10;
								if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
								$start_from = ($page-1) * $num_rec_per_page; 
								
								$q = "select * from tbl_advertice";
								$result = mysqli_query($conn,$q);
								$total_records = mysqli_num_rows($result);  //count number of records
								$total_pages = ceil($total_records / $num_rec_per_page); ?>
								
                                <div style="background-color:#337ab7; margin-bottom:20px; border-radius:4px">
									<?php echo"<center><ul class='pagination'><li><a href='advertisment.php?page=1'>Last Page</a></li>";
                                    for ($i=1; $i<=$total_pages; $i++) { 
                                                echo "<li><a href='advertisment.php?page=".$i."'>".$i."</a></li> "; 
                                    }; 
                                    echo "<li><a href='advertisment.php?page=$total_pages'>".'Last Page'."</a></li></ul></center>"; ?>
								</div>
                                
								<?php $q = "select * from tbl_advertice LIMIT $start_from, $num_rec_per_page";
								$result = mysqli_query($conn,$q);
												
								echo"<table class='table table-bordered table-hover'>
										<thead>
											<tr>
												<th>Id</th>
												<th>Position</th>
												<th>Title</th>
												<th>Banner</th>
												<th>Image</th>
												<th>Web URL Link</th>
												<th>Date</th>
												<th colspan='2'>Action</th>
											</tr>
										</thead>";
								while($row = mysqli_fetch_array($result))
								{
									echo "<tbody>
											<tr>
												<td>".$row['add_id']."</td>";?>
												<td>
													<?php
														if($row['id_top'] == 1)
														{
															echo "Top,";
														}
														if($row['id_center'] == 1)
														{
															echo "Center,";
														}
														if($row['id_bottom'] == 1)
														{
															echo "Bottom,";
														}
														if($row['id_popup'] == 1)
														{
															echo "Popup";
														}
													?>
												</td>
												<?php echo "<td>".$row['add_title']."</td>";?>
                                                <td>
													<img src="GujaratAboardWebService/add_img/<?php echo $row['add_banner']; ?>" width="120px" height="50px" />
												</td>
												<td>
													<img src="GujaratAboardWebService/add_img/<?php echo $row['add_thumbnill']; ?>" width="70px" height="70px" />
												</td>
												<?php echo"<td>".$row['add_link']."</td>
												<td>$row[date]</td>
												<td><a href='advertisment.php?id=$row[0]'><i class='fa fa-pencil-square fa-2x' aria-hidden='true'></i></a></td>";
												?>
												<td><a onClick="return confirm('are You sure to delete?')" href="advertisment.php?did=<?php echo $row[0];?>"><i class="fa fa-trash-o fa-2x" aria-hidden="true"></i></a></td>
												<?php
											echo "</tr>
										</tbody>";
								}
                            echo"</table>";?>
  
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
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="js/plugins/morris/raphael.min.js"></script>
    <script src="js/plugins/morris/morris.min.js"></script>
    <script src="js/plugins/morris/morris-data.js"></script>

</body>

</html>
