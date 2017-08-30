<?php
	include("connectionpool.php");
	
	$data_back = json_decode(file_get_contents('php://input'));	
	
	$details=array();
	
			$queryuex = "select add_id,add_cat,add_title,add_link,add_thumbnill,id_type,date from tbl_advertice ORDER BY add_id DESC";
			$stmtuex = $conn->prepare($queryuex);
														
			if ($stmtuex)
			{
				$stmtuex->execute();
				$stmtuex->bind_result($add_id,$add_cat,$add_title,$add_link,$add_img,$id_type,$add_date);
				$stmtuex->store_result();
				$count1=$stmtuex->num_rows;
				
				if($count1!=0){			
				
				while($stmtuex->fetch())
				{
					$tbl_addlist[]=array('add_id'=>"$add_id",'add_cat'=>"$add_cat",'add_title'=>"$add_title",'add_link'=>"$add_link",'add_img'=>"$add_img",'id_type'=>"$id_type",'add_date'=>"$add_date");

				}
				$details = array('status'=>"1",'message'=>"Success",'tbl_addlist'=>$tbl_addlist);
			
				}
				else
				{
					$details = array('status'=>"0",'message'=>"No Advertice Found");
				}
			}
			else 
			{
				$details = array('status'=>"0",'message'=>"connection error exist");
			}
			$stmtuex->close();
	
		
	echo json_encode($details);
	$conn->close();
	
?>