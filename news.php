<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<?php
session_start();
error_reporting( E_ALL );
include "config.php";

function getSubCat($subcatID,$conn){
	$q1 = "select * from tbl_subcategory where subcat_id = ".$subcatID;
	$exe_data=mysqli_query($conn,$q1);
	$row1=mysqli_fetch_array($exe_data);
	return $row1["subcat_name"];
}

function getCat($catID,$conn){
	$q1 = "select * from tbl_category where cat_id = ".$catID;
	$exe_data=mysqli_query($conn,$q1);
	$row1=mysqli_fetch_array($exe_data);
	return $row1["cat_name"];
}

if(!$_SESSION['login_user']) 
{
    header("location:index.php");
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
                 
                <div class="row">
                    <div class="col-md-6">
                        <h3 align="left"> News List </h3><hr style="border-top: 1px solid #ddd !important;padding:5px;">
                    </div>
                     <div class="col-md-6"> 
                        <button type="button" onclick="window.location.href='addNews.php'" name="addBtn" class="submit" id="addBtn">Add News</button>
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
								
								$q = "select * from tbl_news order by date desc";
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
								$q = "select * from tbl_news order by date desc LIMIT $start_from, $num_rec_per_page";
								//echo $q;	
								$result = mysqli_query($conn,$q);
								//echo mysqli_error();
												
								?>
								<table class='table table-bordered table-hover'>
										<thead>
											<tr>
												<!--<th width="5%">Id</th>-->
												<th width="20%">Category</th>
												<!--<th>SubCat</th> -->
												<!--<th>Language</th> --> 
												<th width="55%">Title</th>
												<!--<th>Description</th>-->
												<th width="10%">Date</th>
												<th colspan='3' align='center' width="15%" style="text-align: center;">Action</th>
											</tr>
										</thead><tbody>
								<?php while($row = mysqli_fetch_array($result)) { ?>
								<tr>
									<!-- <td><?php echo $row['news_id']; ?></td> -->
									<td><?php echo getCat($row['news_cat'],$conn); ?><?php if($row['news_subcat']!=0){ ?> > <?php echo getSubCat($row['news_subcat'],$conn); ?> <?php } ?></td>
									<!-- <td><?php echo $row['news_subcat']; ?></td> -->
									<!--
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
									-->
									<td><?php echo $row['news_title']; ?></td>
									<!--<td><?php echo substr(strip_tags($row['news_desc']),0,200); ?>...</td>-->
									<td style="font-size:12px;"><?php echo date("F j, Y", strtotime($row['date'])); ?></td>
									<td style="text-align: center;"><a href='newsView.php?id=<?php echo $row[0];?>'><i class='fa fa-eye fa-2x' aria-hidden='true'></i></a></td>
									<td style="text-align: center;"><a href='editNews.php?id=<?php echo $row[0];?>'><i class='fa fa-pencil-square fa-2x' aria-hidden='true'></i></a></td>
									<td style="text-align: center;"><a onClick="return confirm('Delete this news?')" href="news.php?did=<?php echo $row[0];?>"><i class="fa fa-trash-o fa-2x" aria-hidden="true"></i></a></td>
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