<?php
	include("connectionpool.php");
	
	$data_back = json_decode(file_get_contents('php://input'));	
	
	$details=array();
	
	if(isset($data_back->{"lang"}))
	{
		if(!empty($data_back->{"lang"}))
		{
			$lang=$data_back->{"lang"};
			
			$queryuex = "select video_id,video_cat,video_title,video_link,video_thumbnill,date from tbl_video where lang=".$lang." ORDER BY video_id DESC";
			$stmtuex = $conn->prepare($queryuex);
														
			if ($stmtuex)
			{
				$stmtuex->execute();
				$stmtuex->bind_result($video_id,$video_cat,$video_title,$video_link,$video_img,$video_date);
				$stmtuex->store_result();
				$count1=$stmtuex->num_rows;
				
				if($count1!=0)
				{			
				
				while($stmtuex->fetch())
				{
						$tbl_videolist[]=array('video_id'=>"$video_id", 'video_cat'=>"$video_cat", 'video_title'=>"$video_title", 'video_link'=>"$video_link", 'video_thumb'=>"$video_img", 'video_date'=>"$video_date");
				}
				
				$details = array('status'=>"1",'message'=>"Success",'tbl_newslist'=>$tbl_videolist);
			
				}else{
					$details = array('status'=>"0",'message'=>"No Video Found");
				}
			}
			else 
			{
				$details = array('status'=>"0",'message'=>"connection error exist");
			}
			$stmtuex->close();
		}
		else
			$details = array('status'=>"0",'message'=>"Parameter is Empty");
	}
	else
		$details = array('status'=>"0",'message'=>"parameter missing");
		
	echo json_encode($details);
	$conn->close();
	
?>