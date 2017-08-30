<?php 
	session_start();
	
	include "config.php";

	$username = $_POST['txtUsername'];
	$password = md5($_POST['txtPassword']);
	
	echo $q = "select * from tbl_login where uname = '".$username."' and password = '".$password."'";
	$exe_data=mysqli_query($conn,$q);
	
	echo $count = mysqli_num_rows($exe_data);
	if($count == 1)
	{ 
		$_SESSION['login_user'] = $username;
		echo $_SESSION['login_user'];
		header("location: NewsCat.php");
	}
	else
	{
		header("location: index.php?err=invalid");
	}
	
?>
