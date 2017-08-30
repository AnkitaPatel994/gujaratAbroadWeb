<?php
session_start();

if(!$_SESSION['login_user']) 
{
    header("location:index.php");
}

include "config.php";

if(isset($_POST['add']))
{
	$title=str_replace("'","\'",$_POST['VideoTitle']);
	$lang=$_POST['language'];
	
	$imgname = $_FILES['video']['name'];
	$imgtype = $_FILES['video']['type'];
	$imgsize = $_FILES['video']['size'];
	$imgtmp = $_FILES['video']['tmp_name'];
	
	//echo $imgname . $imgtype . $imgsize . $imgtmp;
	
	$imgExt = strtolower(pathinfo($imgname,PATHINFO_EXTENSION));
	
	$pic = rand(100000000,100000000000).".".$imgExt;
	$imgpath = "GujaratAboardWebService/video/$pic";
	
	move_uploaded_file($imgtmp,$imgpath);	
	
	$q="insert into tbl_video(lang,video_title,video_thumbnill) values('$lang','$title','$pic')";
	
	
	$exe=mysqli_query($conn,$q);
	
	if($exe){
		?>
		<script>
		alert('Addes Video successfully.');
		window.location='video.php';
		</script>
		
		<?php
	}
	else{
		?>
		<script>
		alert('Failed to add Video!');
		window.location='video.php';
		</script>
		
		<?php
	}
}

if(isset($_GET['did']))
{
	$id = $_GET['did'];
	
	$select = "select video_thumbnill from tbl_video where video_id=$id";
	$result = mysqli_query($conn,$select);
	
	while($row = mysqli_fetch_array($result))
	{
		unlink("GujaratAboardWebService/video/".$row['video_thumbnill']);
	}
	
	$q = "delete from tbl_video where video_id='$id'";
	$exe_del=mysqli_query($conn,$q);
	
	if($exe_del){
		?>
		<script>
		alert('Deleted Record successfully.');
		window.location='video.php';
		</script>
		
		<?php
	}
	else{
		?>
		<script>
		alert('Failed to Delete video!');
		window.location='video.php';
		</script>
		
		<?php
	}
}

if(isset($_GET['id']))
{

	$id=$_GET['id'];
	$q1 = "select * from tbl_video where video_id = $id";
	$exe_data=mysqli_query($conn,$q1);
	
	$row1=mysqli_fetch_array($exe_data);
}

if(isset($_POST['update']))
{
	$uid=$_POST['uid'];
	$title=$_POST['VideoTitle'];
	$lang=$_POST['language'];
	
	$imgname = $_FILES['video']['name'];
	$imgtype = $_FILES['video']['type'];
	$imgsize = $_FILES['video']['size'];
	$imgtmp = $_FILES['video']['tmp_name'];
	
	//echo $imgname . $imgtype . $imgsize . $imgtmp;
	
	if($imgname == "")
	{
		$q = "update tbl_video set lang = '$lang', video_title = '$title' where video_id = '".$uid."'";
	}
	else
	{
		$imgExt = strtolower(pathinfo($imgname,PATHINFO_EXTENSION));
	
		$pic = rand(100000000,100000000000).".".$imgExt;
		$imgpath = "GujaratAboardWebService/video/$pic";
		
		$select2 = "select video_thumbnill from tbl_video where video_id=$uid";
		$result2 = mysqli_query($conn,$select2);
		
		while($row2 = mysqli_fetch_array($result2))
		{
			unlink("GujaratAboardWebService/video/".$row2['video_thumbnill']);
			move_uploaded_file($imgtmp,$imgpath);
		}

		$q = "update tbl_video set lang = '$lang', video_title = '$title', video_thumbnill = '$pic' where video_id = '".$uid."'";
	}

	$exe_update=mysqli_query($conn,$q);	
	
	if($exe_update)
	{
		?>
		<script>
		alert('Updated Record successfully.');
		window.location='video.php';
		</script>
		
		<?php
	}
	else
	{
		?>
		<script>
		alert('Failed to Update video!');
		window.location='video.php';
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
            <!-- Brand and toggle get grouped for better mobile display -->
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
                    <li class="active">
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
                            Video
                        </h1><hr style="border-top: 1px solid #ddd !important;padding:5px;">
                    </div>
                </div>
                <!-- /.row -->
                
                <div class="row">
                    <div class="col-lg-12">
                    	<section id='contact'>
                            <form name='NewsCatAddForm' method='post' action='<?php echo $_SERVER['PHP_SELF']; ?>' id='contactform' enctype= 'multipart/form-data'>
								<input type="hidden" value="<?php if(isset($_GET['id'])){ echo $row1['video_id'];} ?>" name="uid">
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
                                    <input name='VideoTitle' value="<?php if(isset($_GET['id'])){ echo $row1['video_title'];} ?>" type='text' id='VideoTitle' placeholder='Enter Video Title' required='required' />
                                </div>
								<?php 
								if(isset($_GET['id']))
								{
								?>
								<div>
                                    <label></label>
									<video width="100px" height="100px">
                                        <source src="GujaratAboardWebService/video/<?php echo $row1['video_thumbnill'];?>">
                                    </video>
                                </div>
                                <div>
                                    <label>Video:</label>
									<input name='video' type='file' id='video'/>
                                </div>
								<?php } else { ?>
								<div>
                                    <label>Video:</label>
									<input name='video' type='file' id='video' required/>
                                </div>
								<?php } ?>
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
                        <h3 align="center"> Video Record </h3><hr style="border-top: 1px solid #ddd !important;padding:5px;">
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
								
								$q = "select * from tbl_video";
								$result = mysqli_query($conn,$q);
								$total_records = mysqli_num_rows($result);  //count number of records
								$total_pages = ceil($total_records / $num_rec_per_page); ?>
								
                                <div style="background-color:#337ab7; margin-bottom:20px; border-radius:4px">
									<?php echo"<center><ul class='pagination'><li><a href='video.php?page=1'>First Page</a></li>";
                                    for ($i=1; $i<=$total_pages; $i++) { 
                                                echo "<li><a href='video.php?page=".$i."'>".$i."</a></li> "; 
                                    }; 
                                    echo "<li><a href='video.php?page=$total_pages'>".'Last Page'."</a></li></ul></center>";?>
								</div>
								<?php $q = "select * from tbl_video LIMIT $start_from, $num_rec_per_page";
								$result = mysqli_query($conn,$q);
												
								echo"<table class='table table-bordered table-hover'>
										<thead>
											<tr>
												<th>Id</th>
												<th>Language</th>
												<th>Title</th>
												<th>Video</th>
												<th>Date</th>
												<th colspan='2'>Action</th>
											</tr>
										</thead>";
								while($row = mysqli_fetch_array($result))
								{
									echo "<tbody>
											<tr>
												<td>$row[video_id]</td>";
?>
												<td><?php if($row['lang']==1)
													{ 
														echo'English';
													}
													else if($row['lang']==2)
													{ 
														echo 'Gujarati';
													} ?>
												</td>
												<?php echo "<td>$row[video_title]</td>";?>
												<td>
                                                	<video width="100px" height="100px">
														<source src="GujaratAboardWebService/video/<?php echo $row['video_thumbnill'];?>">
													</video>
                                                </td>
												<?php echo "<td>$row[date]</td>
												<td><a href='video.php?id=$row[0]'><i class='fa fa-pencil-square fa-2x' aria-hidden='true'></i></a></td>";
												?>
												<td><a onClick="return confirm('are You sure to delete?')" href="video.php?did=<?php echo $row[0];?>"><i class="fa fa-trash-o fa-2x" aria-hidden="true"></i></a></td>
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
