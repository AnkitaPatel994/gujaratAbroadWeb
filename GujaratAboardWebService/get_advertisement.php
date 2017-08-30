<?php	

	include("connectionpool.php");

	$data_back = json_decode(file_get_contents('php://input'));	

	$details=array();

	if(isset($data_back->{"type"}))
	{
		if(!empty($data_back->{"type"}))
		{
			$type=$data_back->{"type"};
			$query = "select add_id,add_link,add_banner,add_thumbnill from tbl_advertice where $type='1'";
			$stm = $conn->prepare($query);
			if ($stm)
			{
				$stm->execute();
				$stm->bind_result($add_id,$add_link,$add_banner,$add_thumbnill);
				$stm->store_result();
				$count=$stm->num_rows;
				
				if($count!=0)
				{			
					while($stm->fetch())
					{
						$data[]=array('add_id'=>"$add_id",'add_link'=>"$add_link",'add_banner'=>"$add_banner",'add_thumbnill'=>"$add_thumbnill");
					}
					shuffle($data);
					$details = array('status'=>"1",'message'=>"Success",'data'=>$data);
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
			$stm->close();
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