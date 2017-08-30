<?php
	include("connectionpool.php");
	
	$data_back = json_decode(file_get_contents('php://input'));	
	
	$details=array();
	
	if(isset($data_back->{"subcat_cat"}))
	{
		if(!empty($data_back->{"subcat_cat"}))
		{
			$subcat_cat=$data_back->{"subcat_cat"};
			
			$queryuex = "select subcat_id,subcat_name,date from tbl_subcategory where subcat_cat='".$subcat_cat."'";
			$stmtuex = $conn->prepare($queryuex);
														
			if ($stmtuex)
			{
				$stmtuex->execute();
				$stmtuex->bind_result($subcat_id,$subcat_name,$date);
				$stmtuex->store_result();
				$count1=$stmtuex->num_rows;
				
				if($count1!=0)
				{			
					while($stmtuex->fetch())
					{
						$tbl_subcategory[]=array('subcat_id'=>"$subcat_id",'subcat_name'=>"$subcat_name",'date'=>"$date");
					}
					$details = array('status'=>"1",'message'=>"Success",'tbl_subcategory'=>$tbl_subcategory);
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
		}
		else
		{
			$details = array('status'=>"0",'message'=>"Parameter is Empty");
		}
	}
	else
	{
		$details = array('status'=>"0",'message'=>"parameter missing");
	}
		
	echo json_encode($details);
	$conn->close();
	
?>