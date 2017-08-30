<?php
	include("connectionpool.php");
	
	$data_back = json_decode(file_get_contents('php://input'));	
	
	$details=array();
	
	if(isset($data_back->{"lang"}) && isset($data_back->{"news_cat"}) && isset($data_back->{"news_subcat"}))
	{
		if(!empty($data_back->{"lang"}) && !empty($data_back->{"news_cat"}) && !empty($data_back->{"news_cat"}))
		{
			$lang=$data_back->{"lang"};
			$news_cat=$data_back->{"news_cat"};
			$news_subcat=$data_back->{"news_subcat"};
			
			$queryuex = "select news_id,news_title,news_desc,news_img,date from tbl_news where lang='".$lang."' AND news_cat='".$news_cat."' AND news_subcat='".$news_subcat."'";
			
			$stmtuex = $conn->prepare($queryuex);
														
			if ($stmtuex)
			{
				$stmtuex->execute();
				$stmtuex->bind_result($news_id,$news_title,$news_desc,$news_img,$date);
				$stmtuex->store_result();
				$count1=$stmtuex->num_rows;
				
				if($count1!=0)
				{			
					while($stmtuex->fetch())
					{
						$tbl_news[]=array('news_id'=>$news_id, 'news_title'=>$news_title, 'news_desc'=>$news_desc, 'news_img'=>$news_img, 'date'=>$date);
					}
					$details = array('status'=>"1", 'message'=>"Success", 'tbl_news'=>$tbl_news);
				}
				else
				{
					$details = array('status'=>"0",'message'=>"News Not Found");
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