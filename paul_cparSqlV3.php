<?php
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
	//$path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
	$javascriptLib = "../Common Data/Libraries/Javascript/";
	$templates = "../Common Data/Templates/";
	set_include_path($path);	
	include('PHP Modules/mysqliConnection.php');
	ini_set("display_errors", "on");

	//new code;
	if($_POST['Submit']=='Assign') //1; leader;
	{
		$cparId = $_POST['cparId'];
		
		$extraQuery='';
		if($_SESSION['idNumber']=='0215') //sir roldan;
		{
			$interim = $db->real_escape_string($_POST['interim']);
			$extraQuery = " cparInterimAction = '".$interim."', ";
		}
		
		$_POST['pcause'] = $db->real_escape_string($_POST['pcause']);
		$_POST['focause'] = $db->real_escape_string($_POST['focause']);
		$_POST['process'] = $db->real_escape_string($_POST['process']);
		$_POST['fo'] = $db->real_escape_string($_POST['fo']);
		$_POST['prevaction'] = $db->real_escape_string($_POST['prevaction']);
		$_POST['verif'] = $db->real_escape_string($_POST['verif']);
		
		$sql = "UPDATE qc_cpar SET
					".$extraQuery."
					
					assignEmployee = '".$_POST['assignEmployee']."',
					cparSourcePerson = '".$_POST['inputName1']."',
					
					cparCauseProcess = '".$_POST['pcause']."',
					cparCauseFlowOut = '".$_POST['focause']."',
					cparCorrectiveProcess = '".$_POST['process']."',
					cparCorrectiveFlowOut = '".$_POST['fo']."',
					cparCorrectiveProcessDate = '".$_POST['impdate1']."',
					cparCorrectiveFlowOutDate = '".$_POST['impdate2']."',
					cparCorrectiveProcessIncharge = '".$_POST['inputName4']."',
					cparCorrectiveFlowOutIncharge = '".$_POST['inputName5']."',
					cparPreventiveAction = '".$_POST['prevaction']."',
					cparPreventiveActionIncharge = '".$_POST['inputName6']."',
					cparPreventiveActionDate = '".$_POST['impdate3']."',
					cparVerification = '".$_POST['verif']."',
					cparVerificationIncharge = '".$_POST['inputName7']."',
					cparVerificationDate = '".$_POST['verifdate']."',
					cparStatus = '".$_POST['status']."',
				WHERE cparId LIKE '".$cparId."' LIMIT 1";
		$queryUpdateDetails = $db->query($sql);		
		
		if($_POST['assignEmployee']!='')
		{
			$notificationIdArray = array();
			$sql = "SELECT notificationId FROM system_notificationdetails WHERE notificationKey LIKE '".$cparId."'";
			$queryUp = $db->query($sql);
			if($queryUp->num_rows > 0)
			{
				while($resultUp = $queryUp->fetch_assoc())
				{
					$notificationIdArray[] = $resultUp['notificationId'];
				}
				
				$sql = "UPDATE system_notification SET notificationStatus = 1 WHERE notificationId IN (".implode(",", $notificationIdArray).") AND notificationStatus = 0";
				$queryUpdate = $db->query($sql);
				
				$sql = "INSERT INTO system_notificationdetails
						(notificationDetail, notificationKey, notificationLink, notificationType)
						VALUES
						('You have an unanswered CPAR', '".$cparId."', '../6-4 CPAR List/paul_cparInputForm.php', 12)";
				$queryInsertOne = $db->query($sql);
				
				$sql = "SELECT MAX(notificationId) AS maxNotificationId FROM system_notificationdetails LIMIT 1";
				$queryMaxId = $db->query($sql);
				if($queryMaxId AND $queryMaxId->num_rows > 0)
				{
					$resultMaxId = $queryMaxId->fetch_assoc();
					$maxNotificationId = $resultMaxId['maxNotificationId'];
					
					$sql = "INSERT INTO system_notification
									(notificationId, notificationTarget, targetType)
							VALUES
									(".$maxNotificationId.", '".$_POST['assignEmployee']."', 2)";
					$queryInsertTwo = $db->query($sql);
					
					$sql = "UPDATE qc_cpar SET status = 2 WHERE cparId LIKE '".$cparId."' LIMIT 1";
					$queryUpdateStatus = $db->query($sql);
					header("location: ../dashboard.php");
				}
			}
		}			
	}
	
	if($_POST['Submit']=='Submit') //2; assign employee;
	{
		$cparId = $_POST['cparId'];	
		
		$extraQuery='';
		if($_SESSION['idNumber']=='0215') //sir roldan;
		{
			$interim = $db->real_escape_string($_POST['interim']);
			$extraQuery = " cparInterimAction = '".$interim."', ";
		}
		
		$_POST['pcause'] = $db->real_escape_string($_POST['pcause']);
		$_POST['focause'] = $db->real_escape_string($_POST['focause']);
		$_POST['process'] = $db->real_escape_string($_POST['process']);
		$_POST['fo'] = $db->real_escape_string($_POST['fo']);
		$_POST['prevaction'] = $db->real_escape_string($_POST['prevaction']);
		$_POST['verif'] = $db->real_escape_string($_POST['verif']);
		
		$sql = "UPDATE qc_cpar SET
					".$extraQuery."
					cparCauseProcess = '".$_POST['pcause']."',
					cparCauseFlowOut = '".$_POST['focause']."',
					cparCorrectiveProcess = '".$_POST['process']."',
					cparCorrectiveFlowOut = '".$_POST['fo']."',
					cparCorrectiveProcessDate = '".$_POST['impdate1']."',
					cparCorrectiveFlowOutDate = '".$_POST['impdate2']."',
					cparCorrectiveProcessIncharge = '".$_POST['inputName4']."',
					cparCorrectiveFlowOutIncharge = '".$_POST['inputName5']."',
					cparPreventiveAction = '".$_POST['prevaction']."',
					cparPreventiveActionIncharge = '".$_POST['inputName6']."',
					cparPreventiveActionDate = '".$_POST['impdate3']."',
					cparVerification = '".$_POST['verif']."',
					cparVerificationIncharge = '".$_POST['inputName7']."',
					cparVerificationDate = '".$_POST['verifdate']."',
					cparStatus = '".$_POST['status']."'
				WHERE cparId LIKE '".$cparId."' LIMIT 1";
		$queryUpdateDetails = $db->query($sql);		
		
		$notificationIdArray = array();
		$sql = "SELECT notificationId FROM system_notificationdetails WHERE notificationKey LIKE '".$cparId."'";
		$queryUp = $db->query($sql);
		if($queryUp->num_rows > 0)
		{
			while($resultUp = $queryUp->fetch_assoc())
			{
				$notificationIdArray[] = $resultUp['notificationId'];
			}
			
			$sql = "UPDATE system_notification SET notificationStatus = 1 WHERE notificationId IN (".implode(",", $notificationIdArray).") AND notificationStatus = 0";
			$queryUpdate = $db->query($sql);		

			$sql = "SELECT cparSection FROM qc_cpar WHERE cparId LIKE '".$cparId."' LIMIT 1";
			$query = $db->query($sql);
			if($query->num_rows > 0)
			{
				$result = $query->fetch_assoc();
				$_POST['inputSection'] = $result['cparSection'];
			
				$sql = "INSERT INTO system_notificationdetails
								(notificationDetail, notificationKey, notificationLink, notificationType)
						VALUES
								('You have an CPAR waiting for checking', '".$cparId."', '../6-4 CPAR List/paul_cparInputForm.php', 12)
				";
				$queryInsert = $db->query($sql);
				
				$sql = "SELECT MAX(notificationId) AS maxNotificationId FROM system_notificationdetails LIMIT 1";
				$queryMaxID = $db->query($sql);
				if($queryMaxID->num_rows > 0)
				{
					$resultMaxID = $queryMaxID->fetch_assoc();
					$maxNotificationId = $resultMaxID['maxNotificationId'];
					
					$extendQuery = '';
					if(in_array($_POST['inputSection'], array('Engineering')))
					{
						$extendQuery = "(".$maxNotificationId.", '0231', 2)"; //sir ryan;
					}
					else if(in_array($_POST['inputSection'], array('TPP'))) //tpp blanking (sir roldan);
					{
						$extendQuery = "(".$maxNotificationId.", '0300', 2)"; //sir roldan t;
					}
					else if(in_array($_POST['inputSection'], array('Bending'))) //Bending (sir nestor);
					{
						$extendQuery = "(".$maxNotificationId.", '0049', 2)"; //sir nestor;
					}			
					else if(in_array($_POST['inputSection'], array('Laser')))
					{
						$extendQuery = "(".$maxNotificationId.", '0291', 2)"; //sir john;
					}
					else if(in_array($_POST['inputSection'], array('Welding Assembly')))
					{
						$extendQuery = "(".$maxNotificationId.", '0083', 2)"; //sir junie;
					}
					else if(in_array($_POST['inputSection'], array('Press')))
					{
						$extendQuery = "(".$maxNotificationId.", '0009', 2)"; //sir rhodel;
					}
					else if(in_array($_POST['inputSection'], array('Powder-Type Painting', 'Wet-Type Painting')))
					{
						$extendQuery = "(".$maxNotificationId.", '0266', 2)"; //sir raymond b;
					}
					else if(in_array($_POST['inputSection'], array('Warehouse')))
					{
						$extendQuery = "(".$maxNotificationId.", '0207', 2)"; //mam ivan;
					}				
					else if(in_array($_POST['inputSection'], array('QC/FVI'))) //QC (rachelle rabo);
					{
						$extendQuery = "(".$maxNotificationId.", '0326', 2)"; //mam mabel;
					}
					else if(in_array($_POST['inputSection'], array('IT')))
					{
						$extendQuery = "(".$maxNotificationId.", '0276', 2)"; //mam rose;
					}
					else if(in_array($_POST['inputSection'], array('Purchasing')))
					{
						$extendQuery = "(".$maxNotificationId.", '0331', 2)"; //sir demer;
					}
					else if(in_array($_POST['inputSection'], array('Sales')))
					{
						$extendQuery = "(".$maxNotificationId.", '0228', 2)"; //mam jane;
					}
					else if(in_array($_POST['inputSection'], array('Planning')))
					{
						$extendQuery = "(".$maxNotificationId.", '0352', 2)"; //mam isabel;
					}				
					
					$sql = "INSERT INTO system_notification
									(notificationId, notificationTarget, targetType) 
							VALUES
									".$extendQuery."";
					$queryInsert1 = $db->query($sql);
											
					$sql = "UPDATE qc_cpar SET status = 3 WHERE cparId LIKE '".$cparId."' LIMIT 1";
					$queryUpdate1 = $db->query($sql);
					header("location: ../dashboard.php");
					exit(0);			
				}			
			}
		}
	}
	
	if($_POST['Submit']=='Confirm') //3 leader;
	{
		$cparId = $_POST['cparId'];
		
		$extraQuery='';
		if($_SESSION['idNumber']=='0215') //sir roldan;
		{
			$interim = $db->real_escape_string($_POST['interim']);
			$extraQuery = " cparInterimAction = '".$interim."', ";
		}
		
		$_POST['pcause'] = $db->real_escape_string($_POST['pcause']);
		$_POST['focause'] = $db->real_escape_string($_POST['focause']);
		$_POST['process'] = $db->real_escape_string($_POST['process']);
		$_POST['fo'] = $db->real_escape_string($_POST['fo']);
		$_POST['prevaction'] = $db->real_escape_string($_POST['prevaction']);
		$_POST['verif'] = $db->real_escape_string($_POST['verif']);
		
		$sql = "UPDATE qc_cpar SET
					".$extraQuery."
					cparCauseProcess = '".$_POST['pcause']."',
					cparCauseFlowOut = '".$_POST['focause']."',
					cparCorrectiveProcess = '".$_POST['process']."',
					cparCorrectiveFlowOut = '".$_POST['fo']."',
					cparCorrectiveProcessDate = '".$_POST['impdate1']."',
					cparCorrectiveFlowOutDate = '".$_POST['impdate2']."',
					cparCorrectiveProcessIncharge = '".$_POST['inputName4']."',
					cparCorrectiveFlowOutIncharge = '".$_POST['inputName5']."',
					cparPreventiveAction = '".$_POST['prevaction']."',
					cparPreventiveActionIncharge = '".$_POST['inputName6']."',
					cparPreventiveActionDate = '".$_POST['impdate3']."',
					cparVerification = '".$_POST['verif']."',
					cparVerificationIncharge = '".$_POST['inputName7']."',
					cparVerificationDate = '".$_POST['verifdate']."',
					cparStatus = '".$_POST['status']."'
				WHERE cparId LIKE '".$cparId."' LIMIT 1";
		$queryUpdateDetails = $db->query($sql);
		
		$notificationIdArray = array();
		$sql = "SELECT notificationId FROM system_notificationdetails WHERE notificationKey LIKE '".$cparId."'";
		$query = $db->query($sql);
		if($query AND $query->num_rows > 0)
		{
			while($result = $query->fetch_assoc())
			{
				$notificationIdArray[] = $result['notificationId'];
			}
			
			$sql = "UPDATE system_notification SET notificationStatus = 1 WHERE notificationId IN (".implode(",", $notificationIdArray).") AND notificationStatus = 0";
			$queryUpdate = $db->query($sql);
		}
		
		$sql = "INSERT INTO system_notificationdetails
							(notificationDetail, notificationKey, notificationLink, notificationType)
					VALUES
							('You have an CPAR waiting for confirmation', '".$cparId."', '../6-4 CPAR List/paul_cparInputForm.php', 12)
				";
		$queryInsert = $db->query($sql);
		
		$sql = "SELECT MAX(notificationId) AS maxNotificationId FROM system_notificationdetails LIMIT 1";
		$queryMaxID = $db->query($sql);
		if($queryMaxID->num_rows > 0)
		{
			$resultMaxID = $queryMaxID->fetch_assoc();
			$maxNotificationId = $resultMaxID['maxNotificationId'];
			
			$sql = "SELECT cparSection FROM qc_cpar WHERE cparId LIKE '".$cparId."' LIMIT 1";
			$query = $db->query($sql);
			if($query->num_rows > 0)
			{
				$result = $query->fetch_assoc();
				$cparSection = $result['cparSection'];			
			
				$extendQuery = '';
				if($cparSection=='Engineering') //sir mar and mam mers;
				{
					$extendQuery = "(".$maxNotificationId.", '0063', 2), (".$maxNotificationId.", '0145', 2)";
				}
				else if($cparSection=='Warehouse') //mam susan;
				{
					$extendQuery = "(".$maxNotificationId.", '0282', 2)";
				}
				else if($cparSection=='IT') //sir ace;
				{
					$extendQuery = "(".$maxNotificationId.", '0280', 2)";
				}
				else //sir john and roldan;
				{
					$extendQuery = "(".$maxNotificationId.", '0291', 2), (".$maxNotificationId.", '0215', 2)";
				}		
			
				$sql = "INSERT INTO system_notification
								(notificationId, notificationTarget, targetType)
						VALUES
								".$extendQuery."";
				$queryInsert1 = $db->query($sql);
					
				$sql = "UPDATE qc_cpar SET status = 4 WHERE cparId LIKE '".$cparId."' LIMIT 1";
				$queryUpdate1 = $db->query($sql);
				header("location: ../dashboard.php");
				exit(0);
			}				
		}
	}
	
	if($_POST['Submit']=='Check') //4; head leader;
	{
		$cparId = $_POST['cparId'];
		
		$extraQuery='';
		if($_SESSION['idNumber']=='0215') //sir roldan;
		{
			$interim = $db->real_escape_string($_POST['interim']);
			$extraQuery = " cparInterimAction = '".$interim."', ";
		}
		
		$_POST['pcause'] = $db->real_escape_string($_POST['pcause']);
		$_POST['focause'] = $db->real_escape_string($_POST['focause']);
		$_POST['process'] = $db->real_escape_string($_POST['process']);
		$_POST['fo'] = $db->real_escape_string($_POST['fo']);
		$_POST['prevaction'] = $db->real_escape_string($_POST['prevaction']);
		$_POST['verif'] = $db->real_escape_string($_POST['verif']);
		
		$sql = "UPDATE qc_cpar SET
					".$extraQuery."
					cparCauseProcess = '".$_POST['pcause']."',
					cparCauseFlowOut = '".$_POST['focause']."',
					cparCorrectiveProcess = '".$_POST['process']."',
					cparCorrectiveFlowOut = '".$_POST['fo']."',
					cparCorrectiveProcessDate = '".$_POST['impdate1']."',
					cparCorrectiveFlowOutDate = '".$_POST['impdate2']."',
					cparCorrectiveProcessIncharge = '".$_POST['inputName4']."',
					cparCorrectiveFlowOutIncharge = '".$_POST['inputName5']."',
					cparPreventiveAction = '".$_POST['prevaction']."',
					cparPreventiveActionIncharge = '".$_POST['inputName6']."',
					cparPreventiveActionDate = '".$_POST['impdate3']."',
					cparVerification = '".$_POST['verif']."',
					cparVerificationIncharge = '".$_POST['inputName7']."',
					cparVerificationDate = '".$_POST['verifdate']."',
					cparStatus = '".$_POST['status']."'
				WHERE cparId LIKE '".$cparId."' LIMIT 1";
		$queryUpdateDetails = $db->query($sql);	
		
		$notificationIdArray = array();
		$sql = "SELECT notificationId FROM system_notificationdetails WHERE notificationKey LIKE '".$cparId."'";
		$query = $db->query($sql);
		if($query AND $query->num_rows > 0)
		{
			while($result = $query->fetch_assoc())
			{
				$notificationIdArray[] = $result['notificationId'];
			}
			
			$sql = "UPDATE system_notification SET notificationStatus = 1 WHERE notificationId IN (".implode(",", $notificationIdArray).") AND notificationStatus = 0";
			$queryUpdate = $db->query($sql);

			$sql = "INSERT INTO system_notificationdetails
							(notificationDetail, notificationKey, notificationLink, notificationType)
					VALUES
							('You have an CPAR waiting for verification', '".$cparId."', '../6-4 CPAR List/paul_cparInputForm.php', 12)
					";
			$queryInsert = $db->query($sql);
			
			$sql = "SELECT MAX(notificationId) AS maxNotificationId FROM system_notificationdetails LIMIT 1";
			$queryMaxID = $db->query($sql);
			if($queryMaxID AND $queryMaxID->num_rows > 0)
			{
				$resultMaxID = $queryMaxID->fetch_assoc();
				$maxNotificationId = $resultMaxID['maxNotificationId'];
					
				$sql = "INSERT INTO system_notification
								(notificationId, notificationTarget, targetType) 
						VALUES
								(".$maxNotificationId.", '0239', 2), (".$maxNotificationId.", '0206', 2)";
				$queryInsert1 = $db->query($sql);
				
				$sql = "UPDATE qc_cpar SET checkedBy = '".$_SESSION['idNumber']."', status = 5 WHERE cparId LIKE '".$cparId."' LIMIT 1";
				$queryUpdate1 = $db->query($sql);
				header("location: ../dashboard.php");
				exit(0);	
			}
		}
	}	
	
	if($_POST['Submit']=='Verify') //5; mam nabel && megs;
	{
		$cparId = $_POST['cparId'];
		
		$arrayNewControlId = explode("_", $_POST['sourceInfo']);
		$dispo = implode(',', $_POST['dispo']);
		$analysis = implode(',', $_POST['4m']);
		$_POST['message1'] = $db->real_escape_string($_POST['message1']);
		$_POST['interim'] = $db->real_escape_string($_POST['interim']);	
		$_POST['pcause'] = $db->real_escape_string($_POST['pcause']);
		$_POST['focause'] = $db->real_escape_string($_POST['focause']);
		$_POST['process'] = $db->real_escape_string($_POST['process']);
		$_POST['fo'] = $db->real_escape_string($_POST['fo']);
		$_POST['prevaction'] = $db->real_escape_string($_POST['prevaction']);
		$_POST['verif'] = $db->real_escape_string($_POST['verif']);
		
		$sql = "UPDATE qc_cpar SET
					cparType = '".$_POST['action']."',
					cparOwner = '".$_POST['inputName']."',
					cparSection = '".$_POST['inputSection']."',
					cparDueDate = '".$_POST['replyDueDate']."',
					cparInfoSource = '".$arrayNewControlId[1]."',
					cparInfoSourceSubcon = '".$_POST['inputSupplierSubCon']."',
					cparInfoSourceRemarks = '".$_POST['customerHide']."',
					cparMaker = '".$_SESSION['idNumber']."',
					cparDetails = '".$_POST['message1']."',
					cparDisposition = '".$dispo."',
					cparDispositionDetails = '".$_POST['hideText']."',
					cparCause = '".$_POST['prong']."',
					cparSourcePerson = '".$_POST['inputName1']."',
					cparDetectProcess = '".$_POST['detectProcess']."',
					cparDetectPerson = '".$_POST['inputName2']."',
					cparDetectDate = '".$_POST['dateDetect']."',
					cparItemPrice = '".$_POST['itemPrice']."',
					cparAction = '".$_POST['choices']."',
					cparInterimAction = '".$_POST['interim']."',
					cparAnalysis = '".$analysis."',
					cparReturnDate = '".$_POST['retdate']."',
					cparProductionSchedule = '".$_POST['prodnsched']."',
					cparSubconSchedule = '".$_POST['delivsub']."',
					cparCustomerSchedule = '".$_POST['delivcust']."',
					cparRecoveryIncharge = '".$_POST['inputName3']."',
					
					cparCauseProcess = '".$_POST['pcause']."',
					cparCauseFlowOut = '".$_POST['focause']."',
					cparCorrectiveProcess = '".$_POST['process']."',
					cparCorrectiveFlowOut = '".$_POST['fo']."',
					cparCorrectiveProcessDate = '".$_POST['impdate1']."',
					cparCorrectiveFlowOutDate = '".$_POST['impdate2']."',
					cparCorrectiveProcessIncharge = '".$_POST['inputName4']."',
					cparCorrectiveFlowOutIncharge = '".$_POST['inputName5']."',
					cparPreventiveAction = '".$_POST['prevaction']."',
					cparPreventiveActionIncharge = '".$_POST['inputName6']."',
					cparPreventiveActionDate = '".$_POST['impdate3']."',
					cparVerification = '".$_POST['verif']."',
					cparVerificationIncharge = '".$_POST['inputName7']."',
					cparVerificationDate = '".$_POST['verifdate']."',
					cparStatus = '".$_POST['status']."',
					assignEmployee = '".$_POST['assignEmployee']."'
				WHERE cparId LIKE '".$cparId."' LIMIT 1";
		$queryUpdateDetails = $db->query($sql);		
		
		$notificationIdArray = array();
		$sql = "SELECT notificationId FROM system_notificationdetails WHERE notificationKey LIKE '".$cparId."'";
		$query = $db->query($sql);
		if($query AND $query->num_rows > 0)
		{
			while($result = $query->fetch_assoc())
			{
				$notificationIdArray[] = $result['notificationId'];
			}
			
			$sql = "UPDATE system_notification SET notificationStatus = 1 WHERE notificationId IN (".implode(",", $notificationIdArray).")";
			$queryUpdate = $db->query($sql);
		}					
		
		$sql = "INSERT INTO system_notificationdetails
						(notificationDetail, notificationKey, notificationLink, notificationType)
				VALUES
						('You have an CPAR waiting for approval', '".$cparId."', '../6-4 CPAR List/paul_cparInputForm.php', 12)
				";
		$queryInsert = $db->query($sql);
		
		$sql = "SELECT MAX(notificationId) AS maxNotificationId FROM system_notificationdetails LIMIT 1";
		$queryMaxID = $db->query($sql);
		if($queryMaxID AND $queryMaxID->num_rows > 0)
		{
			$resultMaxID = $queryMaxID->fetch_assoc();
			$maxNotificationId = $resultMaxID['maxNotificationId'];
				
			$sql = "INSERT INTO system_notification
							(notificationId, notificationTarget, targetType) 
					VALUES
							(".$maxNotificationId.", '0048', 2)
			";
			$queryInsert1 = $db->query($sql);

			$sql = "UPDATE qc_cpar SET verifyBy = '".$_SESSION['idNumber']."', status = 6 WHERE cparId LIKE '".$cparId."' LIMIT 1";
			$queryUpdate1 = $db->query($sql);
			header("location: ../dashboard.php");
			exit(0);
		}
	}
	
	if($_POST['Submit']=='Approved') //6; sir ariel;
	{
		$cparId = $_POST['cparId'];
		$notificationIdArray = array();
		$sql = "SELECT notificationId FROM system_notificationdetails WHERE notificationKey LIKE '".$cparId."'";
		$query = $db->query($sql);
		if($query AND $query->num_rows > 0)
		{
			while($result = $query->fetch_assoc())
			{
				$notificationIdArray[] = $result['notificationId'];
			}
			
			$sql = "UPDATE system_notification SET notificationStatus = 1 WHERE notificationId IN (".implode(",", $notificationIdArray).")";
			$queryUpdate = $db->query($sql);
			header("location: ../dashboard.php");
			exit(0);
		}
	}

	//------------------------------------ REQUEST SQL Query -----------------------------------------------------//
	if($_POST['Disapproved']=='Disapproved') //sir ariel
	{
		$cparId = $_POST['cparId'];
		
		$notificationIdArray = array();
		$sql = "SELECT notificationId FROM system_notificationdetails WHERE notificationKey LIKE '".$cparId."' AND notificationType = 12";
		$query = $db->query($sql);
		if($query->num_rows > 0)
		{
			while($result = $query->fetch_assoc())
			{
				$notificationIdArray[] = $result['notificationId'];
			}
			
			$sql = "UPDATE system_notification SET notificationStatus =  1 WHERE notificationId IN (".implode(",", $notificationIdArray).") AND notificationStatus = 0";
			$queryUpdate = $db->query($sql);
			
			$sql = "INSERT INTO system_notificationdetails
					(notificationDetail, notificationKey, notificationLink, notificationType)
			VALUES
					('You have an Disapproved CPAR', '".$cparId."', '../6-4 CPAR List/paul_cparInputForm.php', 12)
			";
			$queryInsert = $db->query($sql);
			
			$sql = "SELECT MAX(notificationId) AS maxNotificationId FROM system_notificationdetails LIMIT 1";
			$queryMaxID = $db->query($sql);
			if($queryMaxID->num_rows > 0)
			{
				$resultMaxID = $queryMaxID->fetch_assoc();
				$maxNotificationId = $resultMaxID['maxNotificationId'];
				
				$sql = "INSERT INTO system_notification
								(notificationId, notificationTarget, targetType)
						VALUES
								(".$maxNotificationId.", '0239', 2), (".$maxNotificationId.", '0206', 2)";
				$queryInsert2 = $db->query($sql);
				
				$sql = "UPDATE qc_cpar SET verifyBy = '', status = 5 WHERE cparId LIKE '".$cparId."' LIMIT 1";
				$queryUpdate2 = $db->query($sql);
				header("location: ../dashboard.php");
				exit(0);
			}
		}
	}
	
	if($_POST['Return']=='Return') //mam nabel && megs
	{
		$cparId = $_POST['cparId'];
		
		$sql = "SELECT cparSection FROM qc_cpar WHERE cparId LIKE '".$cparId."' LIMIT 1";
		$querySection = $db->query($sql);
		if($querySection->num_rows > 0)
		{
			$resultSection = $querySection->fetch_assoc();
			$cparSection = $resultSection['cparSection'];
			
			$notificationIdArray = array();
			$sql = "SELECT notificationId FROM system_notificationdetails WHERE notificationKey LIKE '".$cparId."' AND notificationType = 12";
			$query = $db->query($sql);
			if($query->num_rows > 0)
			{
				while($result = $query->fetch_assoc())
				{
					$notificationIdArray[] = $result['notificationId'];
				}
				
				$sql = "UPDATE system_notification SET notificationStatus =  1 WHERE notificationId IN (".implode(",", $notificationIdArray).") AND notificationStatus = 0";
				$queryUpdate = $db->query($sql);
				
				$sql = "INSERT INTO system_notificationdetails
						(notificationDetail, notificationKey, notificationLink, notificationType)
				VALUES
						('You have an Return CPAR', '".$cparId."', '../6-4 CPAR List/paul_cparInputForm.php', 12)
				";
				$queryInsert = $db->query($sql);
				
				$sql = "SELECT MAX(notificationId) AS maxNotificationId FROM system_notificationdetails LIMIT 1";
				$queryMaxID = $db->query($sql);
				if($queryMaxID->num_rows > 0)
				{
					$resultMaxID = $queryMaxID->fetch_assoc();
					$maxNotificationId = $resultMaxID['maxNotificationId'];
					
					$extendQuery = '';
					if($cparSection=='Engineering') //sir mar and mam mers;
					{
						$extendQuery = "(".$maxNotificationId.", '0063', 2), (".$maxNotificationId.", '0145', 2)";
					}
					else if($cparSection=='Warehouse') //mam susan;
					{
						$extendQuery = "(".$maxNotificationId.", '0282', 2)";
					}
					else if($cparSection=='IT') //sir ace;
					{
						$extendQuery = "(".$maxNotificationId.", '0280', 2)";
					}
					else //sir john and roldan;
					{
						$extendQuery = "(".$maxNotificationId.", '0291', 2), (".$maxNotificationId.", '0215', 2)";
					}
					
					$sql = "INSERT INTO system_notification
									(notificationId, notificationTarget, targetType) 
							VALUES
									".$extendQuery."
					";
					$queryInsert1 = $db->query($sql);
						
					$sql = "UPDATE qc_cpar SET status = 4 WHERE cparId LIKE '".$cparId."' LIMIT 1";
					$queryUpdate1 = $db->query($sql);
					header("location: ../dashboard.php");	
					exit(0);
				}
			}
		}
	}
	
	if($_POST['Assign']=='SUBMIT') //sir ryan;
	{
		$cparId = $_POST['cparId'];
		$assignEmployee = $_POST['assignEmployee'];
		
		$notificationIdArray = array();
		$sql = "SELECT notificationId FROM system_notificationdetails WHERE notificationKey LIKE '".$cparId."' AND notificationType = 12";
		$query = $db->query($sql);
		if($query AND $query->num_rows > 0)
		{
			while($result = $query->fetch_assoc())
			{
				$notificationIdArray[] = $result['notificationId'];
			}
			
			$sql = "UPDATE system_notification SET notificationStatus = 1 WHERE notificationId IN (".implode(",", $notificationIdArray).") AND notificationStatus = 0";
			$queryUpdate = $db->query($sql);
			
			$sql = "INSERT INTO system_notificationdetails
							(notificationDetail, notificationKey, notificationLink, notificationType)
					VALUES
							('You have a unanswered CPAR', '".$cparId."', '../6-4 CPAR List/paul_cparInputForm.php', 12)";
			$queryInsert = $db->query($sql);
			
			$sql = "SELECT MAX(notificationId) AS maxNotificationId FROM system_notificationdetails LIMIT 1";
			$queryMaxID = $db->query($sql);
			if($queryMaxID->num_rows > 0)
			{
				$resultMaxID = $queryMaxID->fetch_assoc();
				$maxNotificationId = $resultMaxID['maxNotificationId'];
				
				$sql = "INSERT INTO system_notification
								(notificationId, notificationTarget, targetType)
						VALUES
								(".$maxNotificationId." ,'".$assignEmployee."', 2)";
				$queryInsert2 = $db->query($sql);
				
				$sql = "SELECT CONCAT(firstName,' ', surName) AS fullName FROM hr_employee WHERE idNumber LIKE '".$assignEmployee."' AND status = 1";
				$queryName = $db->query($sql);
				if($queryName->num_rows > 0)
				{
					$resultName = $queryName->fetch_assoc();
					$cparSourcePerson = $resultName['fullName'];
				}
				
				$sql = "UPDATE qc_cpar SET assignEmployee = '".$assignEmployee."', cparSourcePerson = '".$cparSourcePerson."', status = 2 WHERE cparId LIKE '".$cparId."' LIMIT 1";
				$queryUpdate2 = $db->query($sql);
				header("location: ../dashboard.php");
				exit(0);
			}
		}
	}
?>
