<?php

session_start();

if(!$_SESSION['login_user']) 
{
    header("location:index.php");
}

include "config.php";

$id = $_GET['id'];

if(isset($_GET['id']))
{

	$id=$_GET['id'];
	$q1 = "select * from tbl_news,tbl_category where news_id = $id and cat_id = news_cat";
	$exe_data=mysqli_query($conn,$q1);
	
	$row=mysqli_fetch_array($exe_data);

    $q2= "select * from tbl_news_img where news_id = $id";
    $exe_data2=mysqli_query($conn,$q2);
    $rowImgcount=mysqli_num_rows($exe_data2);
    $row2=mysqli_fetch_array($exe_data2);
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
	

    <style>
        .news-image-container {
            float: left;
            margin: 20px;
        }
        p {
            line-height: 25px;
            font-size: 16px;
        }
        .news-description p {
            font-size: 14px;
        }

        .news-cat-name {
            font-size: 13px;
            float: left;
            margin-right: 10px;
            color: #7d7d7d;
            border-right: 1px solid #bbbbbb;
            padding-right: 10px;
            
        }

        .news-date {
                font-size: 12px;
                color: #7d7d7d;
                line-height: 17px;
        }

        .news-description {
            margin-top: 10px;
        }
    </style>
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
                    <div class="col-lg-12" align="center">
                        <?php 
                        for($i=0; $i<$rowImgcount; $i++)
                        {?>
                        <div class="news-image-container">
                    	   
                           <?php 
                            for($i=0; $i<$rowImgcount; $i++)
                            {?>
                                <img src="GujaratAboardWebService/news_img/<?php if(isset($_GET['id'])){ echo $row2['news_img'];} ?>" width="100px" height="100px" />
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <!-- /.row -->
                
                <div class="row">
                    <div class="col-lg-12">
                    	<h3><b><?php echo $row['news_title']; ?></b></h3>
                    </div>
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="news-cat-name"><?php echo $row['cat_name'];?></div>
                        <div class="news-date"><?php echo date("F j, Y, g:i A T", strtotime($row['date'])); ?></div>
                    </div>
                </div>
				<div class="row">
                    <div class="col-lg-12">
                    	<div class="news-description"><?php echo $row['news_desc']; ?></div>
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
