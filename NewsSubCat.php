
<?php

session_start();

if(!$_SESSION['login_user']) 
{
    header("location:index.php");
}

include "config.php";

if(isset($_POST['add']))
{
	echo $newsCatId = $_POST['newsCatId'];
	echo $newsSubCat = $_POST['NewsSubCat'];
						
	echo $q = "insert into tbl_subcategory(subcat_cat,subcat_name) values('$newsCatId','$newsSubCat')";
	
	$exe=mysqli_query($conn,$q);
	
	if($exe){
		?>
		<script>
		alert('Addes news successfully.');
		window.location='NewsSubCat.php';
		</script>
		
		<?php
	}
	else{
		?>
		<script>
		alert('Failed to add news!');
		window.location='NewsSubCat.php';
		</script>
		
		<?php
	}
}

if(isset($_GET['did']))
{
	$id = $_GET['did'];
	
	$q = "delete from tbl_subcategory where subcat_id='$id'";
	$exe_del=mysqli_query($conn,$q);
	
	if($exe_del){
		?>
		<script>
		alert('Deleted Record successfully.');
		window.location='NewsSubCat.php';
		</script>
		
		<?php
	}
	else{
		?>
		<script>
		alert('Failed to Delete news!');
		window.location='NewsSubCat.php';
		</script>
		
		<?php
	}
}

if(isset($_GET['id']))
{

	$id=$_GET['id'];
	$q1 = "select * from tbl_subcategory,tbl_category where subcat_id = $id and subcat_cat = cat_id";
	$exe_data=mysqli_query($conn,$q1);
	
	$row1=mysqli_fetch_array($exe_data);
}
	
if(isset($_POST['update']))
{
	$uid=$_POST['uid'];
	echo $newsCatId = $_POST['newsCatId'];
	echo $newsSubCat = $_POST['NewsSubCat'];
	
	$q = "update tbl_subcategory set subcat_cat = '$newsCatId', subcat_name = '$newsSubCat' where subcat_id = '".$uid."'";

	$exe_update=mysqli_query($conn,$q);	
	
	if($exe_update)
	{
		?>
		<script>
		alert('Updated Record successfully.');
		window.location='NewsSubCat.php';
		</script>
		
		<?php
	}
	else
	{
		?>
		<script>
		alert('Failed to Update news!');
		window.location='NewsSubCat.php';
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
					<li class="active">
                        <a href="NewsSubCat.php"> News Sub Category </a>
                    </li>
                    <li>
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
                            News Sub Category
                        </h1><hr style="border-top: 1px solid #ddd !important;padding:5px;">
                    </div>
                </div>
                <!-- /.row --> 
                
                <div class="row">
                    <div class="col-lg-12">
                    	<section id='contact'>
						
						
                            <form name='NewsCatAddForm' method='post' action='<?php echo $_SERVER['PHP_SELF']; ?>' id='contactform'>
								<input type="hidden" value="<?php if(isset($_GET['id'])){ echo $row1['subcat_id'];} ?>" name="uid">
								<div>
                                    <label>Category:</label>
									<select name = 'newsCatId'>
									<?php 
									if(isset($_GET['id'])){
										
									$id_news=$_GET['id'];
									
									?>
									<option value="<?php if(isset($_GET['id'])) 
															{
																echo $row1['subcat_cat']; 
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
									<input name='NewsSubCat' value="<?php if(isset($_GET['id'])){ echo $row1['subcat_name'];} ?>" type='text' id='NewsSubCat' placeholder='Enter News Sub Category Name' required='required' />
                                </div>
                                <br>
								<?php 
						if(isset($_GET['id']))
						{
						?>
                                <input type='submit' name="update" class='submit' id='submit' value='Update' />
						<?php } else {?>			
						<input type='submit' name="add" class='submit' id='submit' value='Submit' />
                                <input type='submit' class='cancel' id='cancel' value='Cancel' />
						<?php } ?>		
                            </form><br>
						
                        </section>
                        <h3 align="center"> News Sub Category Record </h3><hr style="border-top: 1px solid #ddd !important;padding:5px;">
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
								
								$q = "select * from tbl_subcategory";
								$result = mysqli_query($conn,$q);
								$total_records = mysqli_num_rows($result);  //count number of records
								$total_pages = ceil($total_records / $num_rec_per_page); ?>
								
                                <div style="background-color:#337ab7; margin-bottom:20px; border-radius:4px">
									<?php echo"<center><ul class='pagination'><li><a href='NewsSubCat.php?page=1'>First Page</a></li>";
                                    for ($i=1; $i<=$total_pages; $i++) { 
                                                echo "<li><a href='NewsSubCat.php?page=".$i."'>".$i."</a></li> "; 
                                    }; 
                                    echo "<li><a href='NewsSubCat.php?page=$total_pages'>".'Last Page'."</a></li></ul></center>"; ?>
								</div>
								
								<?php $q = "select * from tbl_subcategory,tbl_category where subcat_cat = cat_id LIMIT $start_from, $num_rec_per_page";
								$result = mysqli_query($conn,$q);
												
								echo"<table class='table table-bordered table-hover'>
										<thead>
											<tr>
												<th>Id</th>
												<th>Category</th>
												<th>Sub Category</th>
												<th>Date</th>
												<th colspan='2',align='center'>Action</th>
											</tr>
										</thead>";
								while($row = mysqli_fetch_array($result))
								{
									echo "<tbody>
											<tr>
												<td>$row[subcat_id]</td>
												<td>$row[cat_name]</td>
												<td>$row[subcat_name]</td>
												<td>$row[date]</td>
												<td><a href='NewsSubCat.php?id=$row[subcat_id]'><i class='fa fa-pencil-square fa-2x' aria-hidden='true'></i></a></td>";
												?>
												<td><a onClick="return confirm('are You sure to delete?')" href="NewsSubCat.php?did=<?php echo $row['subcat_id'];?>"><i class="fa fa-trash-o fa-2x" aria-hidden="true"></i></a></td>
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
