<?php
	include("connectionpool.php");
	
	$data_back = json_decode(file_get_contents('php://input'));	
	
	$details=array();
	
			$queryuex = "select cat_id,cat_name,date from tbl_category";
			$stmtuex = $conn->prepare($queryuex);
														
			if ($stmtuex)
			{
				$stmtuex->execute();
				$stmtuex->bind_result($cat_id,$cat_name,$cat_date);
				$stmtuex->store_result();
				$count1=$stmtuex->num_rows;
				
				if($count1!=0)
				{			
					while($stmtuex->fetch())
					{
						$tbl_category[]=array('cat_id'=>"$cat_id",'cat_name'=>"$cat_name",'date'=>"$cat_date");
						
					}
					$details = array('status'=>"1",'message'=>"Success",'tbl_category'=>$tbl_category);
				}
				else
				{
					$details = array('status'=>"0",'message'=>"No Category Found");
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