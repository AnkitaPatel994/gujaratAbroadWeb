<?php
	include("connectionpool.php");
	
	$data_back = json_decode(file_get_contents('php://input'));	
	
	$details=array();
	
	if(isset($data_back->{"news_id"}))
	{
		if(!empty($data_back->{"news_id"}))
		{
			$news_id=$data_back->{"news_id"};
			
			$queryuex = "select id,news_img from tbl_news_img where news_id='".$news_id."'";
			$stmtuex = $conn->prepare($queryuex);
														
			if ($stmtuex)
			{
				$stmtuex->execute();
				$stmtuex->bind_result($id,$news_img);
				$stmtuex->store_result();
				$count1=$stmtuex->num_rows;
				
				if($count1!=0)
				{			
					while($stmtuex->fetch())
					{
						$tbl_news_img[]=array('id'=>"$id",'news_img'=>"$news_img");
					}
					$details = array('status'=>"1",'message'=>"Success",'tbl_news_img'=>$tbl_news_img);
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