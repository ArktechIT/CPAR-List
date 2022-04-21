	<?php
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
	set_include_path($path);	
	include('PHP Modules/mysqliConnection.php');
	ini_set("display_errors", "on");
	
	$_GET['cparId'] = (isset($_GET['cparId'])) ? $_GET['cparId'] : $_POST['cparId'];
	
	$uploadFlag = $uploadCounter = $cparLotCount = 0;
	$sql = "SELECT lotNumber FROM qc_cparlotnumber WHERE cparId LIKE '".$_POST['cparId']."' AND status != 2 AND cparId!=''";
	$queryCparLotNumber = $db->query($sql);
	if($queryCparLotNumber AND $queryCparLotNumber->num_rows > 0)
	{
		$cparLotCount = $queryCparLotNumber->num_rows;
		while($resultCparLotNumber = $queryCparLotNumber->fetch_assoc())
		{
			$lotNumber = $resultCparLotNumber['lotNumber'];
			
			if(isset($_FILES["pdfFile".$lotNumber]))
			{
				$target_dir = "../../Document Management System/CPAR Folder/";
				$target_file = $target_dir . basename($_FILES["pdfFile".$lotNumber]["name"]);
				$uploadOk = 1;
				$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
				
				$target_file = $target_dir.$_POST['cparId']."(".$lotNumber.").".$imageFileType;
				// Check if file already exists
				if (file_exists($target_file))
				{
					echo "Sorry, file already exists.";
					$uploadOk = 0;
				}
				
				// Allow certain file formats
				//~ if($imageFileType!='pdf' OR $imageFileType!='png' OR $imageFileType!='jpg')
				if(!in_array($imageFileType,array('pdf','png','jpg','gif','jpeg')))
				{
					echo "Sorry, only PDF or image files are allowed.";
					$uploadOk = 0;
				}
				
				// Check if $uploadOk is set to 0 by an error
				if($uploadOk == 0)
				{
					echo "Sorry, your file was not uploaded.";
					exit(0);
				}
				else // if everything is ok, try to upload file
				{
					if(move_uploaded_file($_FILES["pdfFile".$lotNumber]["tmp_name"], $target_file))
					{
						$uploadCounter++;
					}
				}
			}			
		}
		
		$location = "../../Document Management System/CPAR Folder/".$_POST['cparId'].".jpg";
		$location1 = "../../Document Management System/CPAR Folder/".$_POST['cparId'].".pdf";
		if(file_exists($location))
		{
			unlink($location);
		}
		else if(file_exists($location1))
		{
			unlink($location1);
		}
	}
	
	header('location:anthony_cparEdit.php?cparId='.$_GET['cparId']);
	?>
