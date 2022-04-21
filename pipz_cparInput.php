<?php
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
set_include_path($path);    
include('PHP Modules/mysqliConnection.php');
ini_set("display_errors", "on");
include('PHP Modules/anthony_retrieveText.php');

if(isset($_POST['submit']))
{
	$uploadFlag = $uploadCounter = $cparLotCount = 0;
	$sql = "SELECT lotNumber FROM qc_cparlotnumber WHERE cparId LIKE '".$_POST['cparId']."' AND status != 2";
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
	}
    
    if($uploadCounter==$cparLotCount)
    {
		$uploadFlag = 1;
	}
	else
	{
		echo "Some files are not uploaded";
	}
    
    if($uploadFlag==1)
    {
		$sql = "UPDATE qc_cpar SET  cparCauseProcess = '".$db->real_escape_string($_POST['causeProcess'])."',
									cparCauseFlowOut = '".$db->real_escape_string($_POST['causeFlow'])."',
									cparCorrectiveProcess = '".$db->real_escape_string($_POST['correctiveProcess'])."',
									cparCorrectiveFlowOut = '".$db->real_escape_string($_POST['correctiveFlow'])."',
									cparCorrectiveProcessDate = '".$_POST['correctiveProcessDate']."',
									cparCorrectiveFlowOutDate = '".$_POST['correctiveFlowDate']."',
									cparCorrectiveProcessIncharge = '".$_POST['correctiveProcessInCharge']."',
									cparCorrectiveFlowOutIncharge = '".$_POST['correctiveFlowInCharge']."',
									cparVerification = '".$db->real_escape_string($_POST['verification'])."',
									cparVerificationIncharge = '".$_POST['verifiedBy']."',
									cparVerificationDate = '".$_POST['verifiedDate']."',
									cparStatus = '".$_POST['status']."' WHERE cparId = '".$_POST['cparId']."'";
		$sql = $db->query($sql);

		$sql2 = "SELECT notificationId FROM system_notificationdetails WHERE notificationKey = '".$_POST['cparId']."'";
		$queryNotificationId=$db->query($sql2);
		if($queryNotificationId AND $queryNotificationId->num_rows>0)
		{
			$resultNotificationId=$queryNotificationId->fetch_assoc();
			$notificationId = $resultNotificationId['notificationId'];
		}

		$sql3 = "UPDATE system_notification SET  notificationStatus = '1' WHERE notificationId = '".$notificationId."'";
		$queryNotificationStatus=$db->query($sql3);
	}

    header('location:'.$_SERVER['PHP_SELF'].'?cparId='.$_POST['cparId']);
    exit(0);
}


$cparCauseProcess = $cparCauseFlowOut = $cparCorrectiveProcess = $cparCorrectiveFlowOut = $cparCorrectiveProcessDate = $cparCorrectiveFlowOutDate = $cparCorrectiveProcessIncharge = $cparCorrectiveFlowOutIncharge = $cparVerification = $cparVerificationIncharge = $cparVerificationDate = $cparStatus = '';
$sql = "SELECT cparCauseProcess, cparCauseFlowOut, cparCorrectiveProcess, cparCorrectiveFlowOut, cparCorrectiveProcessDate, cparCorrectiveFlowOutDate, cparCorrectiveProcessIncharge, cparCorrectiveFlowOutIncharge, cparVerification, cparVerificationIncharge, cparVerificationDate, cparStatus FROM qc_cpar WHERE cparId LIKE '".$_GET['cparId']."'";
$queryCpar = $db->query($sql);
if($queryCpar AND $queryCpar->num_rows > 0)
{
	$resultCpar = $queryCpar->fetch_assoc();
	$cparCauseProcess = $resultCpar['cparCauseProcess'];
	$cparCauseFlowOut = $resultCpar['cparCauseFlowOut'];
	$cparCorrectiveProcess = $resultCpar['cparCorrectiveProcess'];
	$cparCorrectiveProcessDate = $resultCpar['cparCorrectiveProcessDate'];
	$cparCorrectiveProcessIncharge = $resultCpar['cparCorrectiveProcessIncharge'];
	$cparCorrectiveFlowOut = $resultCpar['cparCorrectiveFlowOut'];
	$cparCorrectiveFlowOutDate = $resultCpar['cparCorrectiveFlowOutDate'];
	$cparCorrectiveFlowOutIncharge = $resultCpar['cparCorrectiveFlowOutIncharge'];
	$cparVerification = $resultCpar['cparVerification'];
	$cparVerificationIncharge = $resultCpar['cparVerificationIncharge'];
	$cparVerificationDate = $resultCpar['cparVerificationDate'];
}

?>

<html>
    <title>
        Input CPAR
    </title>
    <body>
        <style>
            input[type=text], select {
                width: 100%;
                padding: 12px 20px;
                margin: 8px 0;
                display: inline-block;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }
            input[type=date], select {
                width: 100%;
                padding: 12px 20px;
                margin: 8px 0;
                display: inline-block;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }

            input[type=submit] {
                width: 100%;
                background-color: #4CAF50;
                color: white;
                padding: 14px 20px;
                margin: 8px 0;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }

            input[type=submit]:hover {
                background-color: #45a049;
            }

            input[type=reset] {
                width: 100%;
                background-color: #4CAF50;
                color: white;
                padding: 14px 20px;
                margin: 8px 0;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }


            div {
                border-radius: 5px;
                background-color: #f2f2f2;
                padding: 20px;
            }
            table {
                border-collapse: collapse;
                width: 80%;
            }

            th, td {
                /* text-align: center; */
                padding: 8px;
            }

            tr:nth-child(even){background-color: #f2f2f2}

            th {
                background-color: #4CAF50;
                color: white;
            }
        </style>
        <!-- <h3>Information</h3> -->
        <!-- <div> -->

        <form action="pipz_cparInput.php" method = "POST" enctype = 'multipart/form-data'>
            <center>
                <table border = "2"> 
                    <tr><td colspan="2"><b>CAUSE OF DEFFECT:<b></td></tr>
                    <tr>
                        <td width="418">
                            PROCESS CAUSE:<br>
                            <textarea name = "causeProcess" rows = "3" cols = "30" id = "message" style = "width:100%"><?php echo $cparCauseProcess;?></textarea>
                        </td>
                        <td width="500">
                            FLOW-OUT CAUSE:<br>
                            <textarea name = "causeFlow" rows = "3" cols = "30" id = "message" style = "width:100%"><?php echo $cparCauseFlowOut;?></textarea>
                        </td>  
                    </tr>
                </table>


                <table border = "2">
                    <tr><td colspan="2"><b>CORRECTIVE ACTION:<b></td></tr>

                    <tr>
                    <td width="330">
                        PROCESS:<br>
                        <textarea name = "correctiveProcess" rows = "3" cols = "30"  style = "width:100%;"><?php echo $cparCorrectiveProcess;?></textarea><br>
                <table>
                    <tr>
                    <td>Implementation Date:<input type = "date" name = "correctiveProcessDate" id = "text" value = "<?php echo $cparCorrectiveProcessDate;?>"></td>
                    <td>In-Charge:<br><input type="text" name="correctiveProcessInCharge" value="<?php echo $cparCorrectiveProcessIncharge;?>" style = "border-radius:5px; width:100%;" />
                    </td>
                    </tr>
                </table>
                    </td>
                    <td width="390">
                    FLOW-OUT:<br>
                    <textarea name = "correctiveFlow" rows = "3" cols = "30"  style = "width:100%;"><?php echo $cparCorrectiveFlowOut;?></textarea>
                <table>
                    <tr>
                    <td width="200">Implementation Date:<input type = "date" name = "correctiveFlowDate" id = "text" value = "<?php echo $cparCorrectiveFlowOutDate;?>"></td>
                    <td width="200">In-Charge:<input type="text" name="correctiveFlowInCharge" value="<?php echo $cparCorrectiveFlowOutIncharge;?>" style = "border-radius:5px; width:100%;" />
                    </td>
                    </tr>
                </table>
                    </td>  
                    </tr>
                </table>
                <table border = "2" width="728">
            </center>
                <tr>
                <td>
                    <b>Verification of Effectiveness</b><i> (1month monitoring)</i><br>
                    <textarea name = "verification" rows = "5" cols = "30"  style = "width:100%;"><?php echo $cparVerification;?></textarea>
                </td>
                <td>
                Verified by:<br><input type="text" name="verifiedBy"  value="<?php echo $cparVerificationIncharge;?>" style = "border-radius:5px; width:100%" />
            <table>
                <tr>
                    <td width="194">
                        Date:<input type = "date" name = "verifiedDate" style = "border-radius:5px; width:100%" value = "<?php echo $cparVerificationDate;?>">
                    </td>
                </tr>
            </table>
                </td>
                <td>
                    <center><?php echo (displayText('L172'));?></center><br>
                    <input type = "radio" name = "status" value = "Issued"> ISSUED <br>   		
                    <input type = "radio" name = "status" value = "Answered"> ANSWERED <br>
                    <input type = "radio" name = "status" value = "Closed" > VERIFIED <br>
                    <input type="hidden" name="cparId" value="<?php echo $_GET['cparId']; ?>">
                </td>
                </tr>
            </table>
            <table border='1'>
			<?php
				$sql = "SELECT lotNumber FROM qc_cparlotnumber WHERE cparId LIKE '".$_GET['cparId']."' AND status != 2";
				$queryCparLotNumber = $db->query($sql);
				if($queryCparLotNumber AND $queryCparLotNumber->num_rows > 0)
				{
					while($resultCparLotNumber = $queryCparLotNumber->fetch_assoc())
					{
						$lotNumber = $resultCparLotNumber['lotNumber'];
						
						echo "
							<tr>
								<td>".$lotNumber."</td>
								<td><input type = 'file' name = 'pdfFile".$lotNumber."'></td>
							</tr>
						";
					}
				}
			?> 
			</table>
            <center>
                <input type ="submit" name = "submit" style = "width:10%">&nbsp;&nbsp;&nbsp;<input type ="reset"  style = "width:10%"></center>
            </center>				
        </form>
    </body>
</html>
