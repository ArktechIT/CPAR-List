<?php
	session_start();
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
	//$path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
	$javascriptLib = "../Common Data/Libraries/Javascript/";
	$templates = "../Common Data/Templates/";
	set_include_path($path);	
	include('PHP Modules/mysqliConnection.php');
	ini_set("display_errors", "on");
	include('classes/fpdf.php');
    include('PHP Modules/gerald_functions.php');
    include('PHP Modules/gerald_payablesFunction.php');
    
    $noPartialFlag = 0;
    
    $typeData = isset($_POST['typeData']) ? $_POST['typeData'] : '';
    $employeeIdStart = isset($_POST['employeeIdStart']) ? $_POST['employeeIdStart'] : '';
    $subconNoPo = isset($_POST['subconNoPo']) ? $_POST['subconNoPo'] : '';
    $returnFromSubconFlag = isset($_POST['returnFromSubconFlag']) ? $_POST['returnFromSubconFlag'] : '';
	
				// ---------- START Create CPAR ID ---------- //
				$analysis = implode(',',$_POST['4m']);
				$dispo = implode(',',$_POST['dispo']);
				$lotNumber = $_POST['lotNum'];
				$newLOTzNumber2 = explode('-',$lotNumber);
				$newLOTzNumber = $newLOTzNumber2[0]."-".$newLOTzNumber2[1]."-".$newLOTzNumber2[2];

				$arrayNewControlId = explode("_",$_POST['sourceInfo']);
				//$controlId = $_POST['control'];
				$controlId = $arrayNewControlId[0];

				$sesLOG=$_SESSION['userID']; 
				$Users_IP_address = $_SERVER['REMOTE_ADDR'];
				
				$ym = $db->query("SELECT DATE_FORMAT(NOW(),'%y-%m') as ym");	
				$ym=$ym->fetch_array();
				$ym=$ym['ym'];
				$lot="wala"; 
				$result = $db->query("SELECT  MAX( CAST(SUBSTRING(cparId,LOCATE('-',cparId,14)+1) AS SIGNED) ) as max FROM  qc_cpar WHERE cparId LIKE '".$controlId."-".$ym."-%'"); 
				$rnum=$result->num_rows;
				if($rnum > 0) 
				{
					$rows=$result->fetch_array(); 
					if(!is_null($rows['max']))
					{
						$new=$rows['max']+1; 
						if(strlen($new)==1)
						{
							$new="00".$new;
						} 
						if(strlen($new)==2)
						{
							$new="0".$new;
						}
						$lot=$ym."-".$new; 
					}
					else
					{
						$lot=$ym."-001";
					}
				}
				else
				{
					$lot=$ym."-001";
				}

				$newCPARId = $controlId."-".$lot;
				// ---------- END Create CPAR ID ---------- //

				$oldName = $_POST['inputName'];
				$newName = explode(', ',$oldName);
				$sqlCombine = $db->query("SELECT a.departmentName FROM hr_department AS a, hr_employee AS b WHERE a.departmentId = b.departmentId AND b.surName = '".$newName[0]."' AND b.firstName = '".$newName[1]."'");
				$sqlCombineResult = $sqlCombine->fetch_array();

				//rosemie
				$_POST['interim']=str_replace('"',' ',$_POST['interim']);	$_POST['interim']=str_replace("'",' ',$_POST['interim']);
				$_POST['message1']=str_replace('"',' ',$_POST['message1']);	$_POST['message1']=str_replace("'",' ',$_POST['message1']);
				$_POST['pcause']=str_replace('"',' ',$_POST['pcause']);	$_POST['pcause']=str_replace("'",' ',$_POST['pcause']);
				$_POST['focause']=str_replace('"',' ',$_POST['focause']);	$_POST['focause']=str_replace("'",' ',$_POST['focause']);
				$_POST['process']=str_replace('"',' ',$_POST['process']);	$_POST['process']=str_replace("'",' ',$_POST['process']);
				$_POST['fo']=str_replace('"',' ',$_POST['fo']);	$_POST['fo']=str_replace("'",' ',$_POST['fo']);
				//~ $_POST['prevaction']=str_replace('"',' ',$_POST['prevaction']);	$_POST['prevaction']=str_replace("'",' ',$_POST['prevaction']);
				$_POST['verif']=str_replace('"',' ',$_POST['verif']);	$_POST['verif']=str_replace("'",' ',$_POST['verif']);
				//rosemie
				
				$oldCparIdArray = array();
				
				// ---------- START Insert CPAR Data ---------- //
				$insertSQL = "INSERT INTO qc_cpar (cparId,cparType,cparOwner,cparSection,cparIssueDate,cparDueDate,cparInfoSource,
												   cparInfoSourceSubcon,cparInfoSourceRemarks,cparMaker,cparDetails,cparMoreDetails,cparDisposition,cparDispositionDetails,cparCause,
												   cparSourcePerson,cparDetectProcess,cparDetectPerson,cparDetectDate,cparItemPrice,
												   cparAction,cparInterimAction,cparAnalysis,cparReturnDate,cparProductionSchedule,cparSubconSchedule,cparCustomerSchedule,
												   cparRecoveryIncharge,cparCauseProcess,cparCauseFlowOut,cparCorrectiveProcess,cparCorrectiveFlowOut,cparCorrectiveProcessDate,cparCorrectiveFlowOutDate,
												   cparCorrectiveProcessIncharge,cparCorrectiveFlowOutIncharge,cparVerification,cparVerificationIncharge,
												   cparVerificationDate,cparStatus,assignEmployee,detailsOfNonConformance)
							  VALUES
												( '".$newCPARId."','".$_POST['action']."','".$_POST['inputName']."','".$_POST['inputSection']."',NOW(),'".$_POST['replyDueDate']."','".$arrayNewControlId[1]."',
												  '".$_POST['inputSupplierSubCon']."','".$_POST['customerHide']."','".$_SESSION['idNumber']."','".$_POST['message1']."','".$_POST['cparMoreDetails']."','".$dispo."','".$_POST['hideText']."','".$_POST['prong']."',
												  '".$_POST['inputName1']."','".$_POST['detectProcess']."','".$_POST['inputName2']."','".$_POST['dateDetect']."','".$_POST['itemPrice']."',
												  '".$_POST['choices']."','".$_POST['interim']."','".$analysis."','".$_POST['retdate']."','".$_POST['prodnsched']."', '".$_POST['delivsub']."','".$_POST['delivcust']."',
												  '".$_POST['inputName3']."','".$_POST['pcause']."','".$_POST['focause']."','".$_POST['process']."', '".$_POST['fo']."','".$_POST['impdate1']."','".$_POST['impdate2']."',
												  '".$_POST['inputName4']."','".$_POST['inputName5']."','".$_POST['verif']."','".$_POST['inputName7']."',
												  '".$_POST['verifdate']."','".$_POST['status']."','".$_POST['assignEmployee']."','".$_POST['detailsOfNonconformance']."')";
                    $insertSQLResult = $db->query($insertSQL);
				// ---------- END Insert CPAR Data ---------- //
				if($insertSQLResult)
				{
					$sql = "
						SELECT b.cparId FROM qc_cpar as a
						LEFT JOIN qc_cpar as b
						ON
							b.cparId != a.cparId AND
							b.cparType = a.cparType AND 
							b.cparOwner = a.cparOwner AND 
							b.cparSection = a.cparSection AND 
							b.cparIssueDate = a.cparIssueDate AND 
							b.cparDueDate = a.cparDueDate AND 
							b.cparInfoSource = a.cparInfoSource AND 
							b.cparInfoSourceSubcon = a.cparInfoSourceSubcon AND 
							b.cparInfoSourceRemarks = a.cparInfoSourceRemarks AND 
							b.cparMaker = a.cparMaker AND 
							b.cparDetails = a.cparDetails AND 
							b.cparMoreDetails = a.cparMoreDetails AND 
							b.cparDisposition = a.cparDisposition AND 
							b.cparDispositionDetails = a.cparDispositionDetails AND 
							b.cparCause = a.cparCause AND 
							b.cparSourcePerson = a.cparSourcePerson AND 
							b.cparDetectProcess = a.cparDetectProcess AND 
							b.cparDetectPerson = a.cparDetectPerson AND 
							b.cparDetectDate = a.cparDetectDate AND 
							b.cparItemPrice = a.cparItemPrice AND 
							b.cparAction = a.cparAction AND 
							b.cparInterimAction = a.cparInterimAction AND 
							b.cparAnalysis = a.cparAnalysis AND 
							b.cparReturnDate = a.cparReturnDate AND 
							b.cparProductionSchedule = a.cparProductionSchedule AND 
							b.cparSubconSchedule = a.cparSubconSchedule AND 
							b.cparCustomerSchedule = a.cparCustomerSchedule AND 
							b.cparRecoveryIncharge = a.cparRecoveryIncharge AND 
							b.cparCauseProcess = a.cparCauseProcess AND 
							b.cparCauseFlowOut = a.cparCauseFlowOut AND 
							b.cparCorrectiveProcess = a.cparCorrectiveProcess AND 
							b.cparCorrectiveFlowOut = a.cparCorrectiveFlowOut AND 
							b.cparCorrectiveProcessDate = a.cparCorrectiveProcessDate AND 
							b.cparCorrectiveFlowOutDate = a.cparCorrectiveFlowOutDate AND 
							b.cparCorrectiveProcessIncharge = a.cparCorrectiveProcessIncharge AND 
							b.cparCorrectiveFlowOutIncharge = a.cparCorrectiveFlowOutIncharge AND 
							b.cparVerification = a.cparVerification AND 
							b.cparVerificationIncharge = a.cparVerificationIncharge AND 
							b.cparVerificationDate = a.cparVerificationDate AND 
							b.cparStatus = a.cparStatus AND 
							b.assignEmployee = a.assignEmployee AND 
							b.detailsOfNonConformance = a.detailsOfNonConformance
						WHERE a.cparId = '".$newCPARId."' AND IFNULL(b.cparId,'asd') != 'asd'
					";
					$queryCpar = $db->query($sql);
					if($queryCpar AND $queryCpar->num_rows > 0)
					{
						while($resultCpar = $queryCpar->fetch_assoc())
						{
							$oldCparIdArray[] = $resultCpar['cparId'];
						}
					}
				}
				
				if($controlId=='CPAR-INT' OR $controlId=='CPAR-SUB') //for internal;
				{				
					$sql = "INSERT INTO system_notificationdetails
							(notificationDetail, notificationKey, notificationLink, notificationType)
							VALUES
							('You have a unanswered CPAR', '".$newCPARId."', '6-4 CPAR List/pipz_cparInput.php', 12)";
					$queryInsertOne = $db->query($sql);

					$sql = "SELECT MAX(notificationId) AS maxNotificationId FROM system_notificationdetails LIMIT 1";
					$queryMaxId = $db->query($sql);
					if($queryMaxId AND $queryMaxId->num_rows > 0)
					{
						$resultMaxId = $queryMaxId->fetch_assoc();
						$maxNotificationId = $resultMaxId['maxNotificationId'];
						
						$sql = "INSERT INTO system_notification (notificationId, notificationTarget, targetType)
								VALUES (".$maxNotificationId.", '0215', 2),(".$maxNotificationId.", '0239', 2),(".$maxNotificationId.", '0377', 2)
								";
                               $queryInsertTwo = $db->query($sql);
						
						$sql = "UPDATE qc_cpar SET status = 1 WHERE cparId LIKE '".$cparId."' LIMIT 1";
						$queryUpdate = $db->query($sql);
					}
				}
				
				
				if($controlId=='CPAR-CUS' AND $dispo=='Others')
				{
					$noPartialFlag = 1;
				}
				
				/*
				//----------------------------- PAUL CPAR Notification -------------------------------------//
				if($_POST['sourceInfo'] == 'CPAR-INT_Internal' OR $arrayNewControlId[1] == 'Internal' AND $_POST['inputSection'] != '') //for internal;
				{
					if($_SESSION['userType']!='') //new code;
					{
						// ---------- START Insert Notification ---------- //
						$sql = "INSERT INTO system_notificationdetails
								(notificationDetail, notificationKey, notificationLink, notificationType)
								VALUES
								('You have a unanswered CPAR', '".$newCPARId."', '/6-4 CPAR List/paul_cparInputForm.php', 12)";
						$queryInsertOne = $db->query($sql);
						
						$sql = "SELECT MAX(notificationId) AS maxNotificationId FROM system_notificationdetails LIMIT 1";
						$queryMaxId = $db->query($sql);
						if($queryMaxId AND $queryMaxId->num_rows > 0)
						{
							$resultMaxId = $queryMaxId->fetch_assoc();
							$maxNotificationId = $resultMaxId['maxNotificationId'];
							
							$sql = "INSERT INTO system_notification
											(notificationId, notificationTarget, targetType)
									VALUES (".$maxNotificationId.", '".$_POST['assignEmployee']."', 2)"; //Concerned Person;
							$queryInsertTwo = $db->query($sql);
							
							$sql = "SELECT listId FROM qc_cpar WHERE cparId LIKE '".$newCPARId."' LIMIT 1";
							$queryCpar = $db->query($sql);
							if($queryCpar AND $queryCpar->num_rows > 0)
							{
								$sql = "UPDATE qc_cpar SET status = 1 WHERE cparId LIKE '".$newCPARId."' LIMIT 1";
								$queryUpdate = $db->query($sql);
							}
						}
						// ---------- END Insert Notification ---------- //
					}
					else //old code;
					{
						$sql = "INSERT INTO system_notificationdetails
								(notificationDetail, notificationKey, notificationLink, notificationType)
								VALUES
								('You have a unanswered CPAR', '".$newCPARId."', '/6-4 CPAR List/paul_cparInputForm.php', 12)";
						//~ $queryInsertOne = $db->query($sql);

						$sql = "SELECT MAX(notificationId) AS maxNotificationId FROM system_notificationdetails LIMIT 1";
						$queryMaxId = $db->query($sql);
						if($queryMaxId AND $queryMaxId->num_rows > 0)
						{
							$resultMaxId = $queryMaxId->fetch_assoc();
							$maxNotificationId = $resultMaxId['maxNotificationId'];
							
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
								$extendQuery = "(".$maxNotificationId.", '0049', 2)"; //sir nestor t;
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
							else if(in_array($_POST['inputSection'], array('QC/FVI'))) //QC (rachelle rabo); FVI (mabel);
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
										
							$sql = "INSERT INTO system_notification (notificationId, notificationTarget, targetType)
									VALUES ".$extendQuery."";
							//~ $queryInsertTwo = $db->query($sql);
							
							$sql = "UPDATE qc_cpar SET status = 1 WHERE cparId LIKE '".$cparId."' LIMIT 1";
							//~ $queryUpdate = $db->query($sql);
						}
					}
				}
				//--------------------------END of PAUL -------------------------------------//
				*/
				
				//------------------------- INSERT INTO qc_cparlotnumber ------------------------------//
				$multiLotArray = $_POST['multiLot'];
				$multiPtag = $_POST['multiPtag'];
				$assempblyNGLotArray = $_POST['assempblyNG'];
				$quantityArray = $_POST['quantity'];
				$lotProcessArray = $_POST['lotProcess'];
                $multiLotArray = array_filter($multiLotArray);
                $idNumber = $_SESSION['idNumber'];
				if(COUNT($multiLotArray) == 0)
				{
					//If CPAR-SYS not lot
					$sql = "INSERT INTO qc_cparlotnumber (cparId) VALUES ('".$newCPARId."') ";
					$insert = $db->query($sql);
				}
				else
				{
					$lotNoArray = array();
					for ($i = 0; $i < count($multiLotArray); $i++)
					{
						if($multiLotArray[$i] != '')
						{
							$loteNo = $multiLotArray[$i];
							$poId = $mainPartId = '';
							$partLevel = 1;
							$sql = "SELECT lotNumber, poId, partId, partLevel FROM ppic_lotlist WHERE lotNumber LIKE '".$loteNo."' OR productionTag LIKE '".$loteNo."' LIMIT 1";
                            $queryLotList = $db->query($sql);
							if($queryLotList->num_rows > 0)
							{
								$resultLotList = $queryLotList->fetch_array();
								$loteNo = $resultLotList['lotNumber'];
								$poId = $resultLotList['poId'];
								$mainPartId = $resultLotList['partId'];
								$partLevel = $resultLotList['partLevel'];
							}
							
							//rhay PTAG LINKING 09/10/2019
							$update = "UPDATE ppic_lotlist set productionTag = '".$multiPtag[$i]."' WHERE lotNumber = '".$loteNo."'";
		                	$processUpdate = $db->query($update);
		                	//get current process
		                	$currProcessQuery = "SELECT processCode FROM ppic_workschedule WHERE lotNumber = '".$multiLotArray[$counter]."' AND processCode NOT IN (496, 437) AND status = 0 ORDER BY processOrder ASC LIMIT 1";
		                	$processCurrProcess = $db->query($currProcessQuery);
		                	if($processCurrProcess->num_rows > 0)
		                	{
		                		$resultCurrProcess = $processCurrProcess->fetch_assoc();
		                		$currentProcess = $resultCurrProcess['processCode'];
		                		// insert into productiontaglog
		                		$insert = "INSERT INTO system_productiontaglog (productionTag,lotNumber,processCode,linkDate,employeeId)
		                		VALUES('".$multiPtag[$i]."','".$loteNo."',".$currentProcess.",now(),'".$idNumber."')";
		                		$processInsert = $db->query($insert);
		                	}
							//rhay END PTAG LINKNG 09/10/2019

							// --- to avoid duplication -- //
							if(!in_array($loteNo,$lotNoArray))
							{
								$lotNoArray[] = $loteNo;
							}
							else
							{
								continue;
							}
							// --- to avoid duplication -- //
							
							$cparLotNumberArray = array();
							$cparLotNumberArray[] = array('lotNo'=>$loteNo,'ngQty'=>$quantityArray[$i]);
							
							// START Include Subparts of Main Assy
							if(count($assempblyNGLotArray) > 0 AND in_array($loteNo,$assempblyNGLotArray) AND $partLevel == 1)
							{
								$sql = "SELECT lotNumber, partId, identifier FROM ppic_lotlist WHERE poId = ".$poId." AND identifier IN(1,2) AND workingQuantity > 0 AND partLevel > 1";
								$queryLotList = $db->query($sql);
								if($queryLotList AND $queryLotList->num_rows > 0)
								{
									while($resultLotList = $queryLotList->fetch_assoc())
									{
										$subLotNumber = $resultLotList['lotNumber'];
										$subPartId = $resultLotList['partId'];
										$subIdentifier = $resultLotList['identifier'];
										
										$sql = "SELECT quantity FROM cadcam_subparts WHERE parentId = ".$mainPartId." AND childId = ".$subPartId." AND identifier = ".$subIdentifier." LIMIT 1";
										$querySubParts = $db->query($sql);
										if($querySubParts AND $querySubParts->num_rows > 0)
										{
											$resultSubParts = $querySubParts->fetch_assoc();
											$subQuantity = $resultSubParts['quantity'];
											
											$ngQty = $quantityArray[$i] * $subQuantity;
											
											$cparLotNumberArray[] = array('lotNo'=>$subLotNumber,'ngQty'=>$ngQty);
										}
									}
								}
							}
							// END Include Subparts of Main Assy
							
							foreach($cparLotNumberArray as $cparLotData)
							{
								$lotNo = $cparLotData['lotNo'];
								$ngQty = $cparLotData['ngQty'];
								
								//Avoid duplcation 2019-10-04
								$sql = "SELECT cparId FROM qc_cparlotnumber WHERE lotNumber LIKE '".$lotNo."' AND quantity = ".$ngQty." AND status != 2 AND cparId IN('".implode("','",$oldCparIdArray)."') LIMIT 1";
								$queryCparLotNumber = $db->query($sql);
								if($queryCparLotNumber AND $queryCparLotNumber->num_rows > 0)
								{
									continue;
								}
								//Avoid duplcation 2019-10-04
								
								$select = $db->query("SELECT lotNumber FROM ppic_lotlist WHERE lotNumber LIKE '%".$lotNo."%' ORDER BY lotNumber DESC LIMIT 1");
								$selectResult = $select->fetch_array();
								$prsNumber = explode('-',$selectResult['lotNumber']);
								//~ $newPRSNumber = $prsNumber[0]."-".$prsNumber[1]."-".$prsNumber[2]."-".++$prsNumber[3];//Commented By Gerald 2015-09-25
								$newPRSNumber = "";
								
								if($noPartialFlag==0)
								{
									// By ma'am ivan 2018-08-01 They pause the items for NC Verification
									$sql = "UPDATE system_lotonpause SET unpauseTime = NOW() WHERE lotNumber LIKE '".$lotNo."' AND unpauseTime = '0000-00-00 00:00:00' ORDER BY pauseTime DESC LIMIT 1";
									$queryUpdate = $db->query($sql);
									// By ma'am ivan 2018-08-01 They pause the items for NC Verification
								
									$sql = "INSERT INTO qc_cparlotnumber (cparId, lotNumber, quantity, prsNumber, subconId) VALUES ('".$newCPARId."', '".$lotNo."', ".$ngQty.", '".trim($newPRSNumber)."','".$subconNoPo."') ";
									$insert = $db->query($sql);
								
									// abang na code
									//~ if(strstr($cparId,'-CUS')===FALSE)
									//~ {
										// -------------------------------------------------- Gerald Code Hold Item (2016-02-05) -------------------------------------------------- //
										$sql = "INSERT INTO `system_lotOnHold`(`lotNumber`, `date`, `remarks`, `employeeId`) VALUES ('".$lotNo."',NOW(),'CPAR Verification','".$_SESSION['idNumber']."')";
										$queryInsert = $db->query($sql);
										// ------------------------------------------------ End Gerald Code Hold Item (2016-02-05) ------------------------------------------------ //
									//~ }
									// abang na code
								}
								else
								{
									$sql = "INSERT INTO qc_cparlotnumber (cparId, lotNumber, quantity, prsNumber, subconId, status) VALUES ('".$newCPARId."', '".$lotNo."', ".$ngQty.", 'nolot','".$subconNoPo."',1) ";
									$insert = $db->query($sql);
								}
								
								// -------------------------------------------------- Gerald Code Notification (2016-06-03) -------------------------------------------------- //
								$cparDetails = $cparDisposition = $cparInterimAction = '';
								$sql = "SELECT cparDetails, cparDisposition, cparInterimAction FROM qc_cpar WHERE cparId LIKE '".$newCPARId."' LIMIT 1";
								$queryCpar = $db->query($sql);
								if($queryCpar->num_rows > 0)
								{
									$resultCpar = $queryCpar->fetch_array();
									$cparDetails = $resultCpar['cparDetails'];
									$cparDisposition = $resultCpar['cparDisposition'];
									$cparInterimAction = $resultCpar['cparInterimAction'];
								}
								
								if($cparDisposition=='Scrap/Disposal/Replacement')
								{
									$notificationTarget = 8;
								}
								else
								{
									$notificationTarget = 2;
								}
								
								$listId = '';
								$sql = "SELECT max(listId) AS max FROM qc_cparlotnumber";
								$query = $db->query($sql);
								$result = $query->fetch_array();
								$listId = $result['max'];
								
								if($notificationTarget==2)
								{
									if($noPartialFlag==0)
									{									
										// abang na code
										//~ if(strstr($cparId,'-CUS')===FALSE)
										//~ {
											$notificationDetail = 'You have lot number for rework/replacement that needs to be partialed';
											$notificationLink = '53 CPAR Notification Software/gerald_forPartial.php?listId='.$listId;
											
											$sql = "INSERT INTO `system_notificationdetails`
															(	`notificationDetail`,		`notificationKey`,	`notificationLink`,			`notificationType`)
													VALUES	(	'".$notificationDetail."',	'".$listId."',		'".$notificationLink."',	'3')";
											$queryInsert = $db->query($sql);
											
											$sql = "SELECT max(notificationId) AS max FROM system_notificationdetails";
											$query = $db->query($sql);
											$result = $query->fetch_array();
											$notificationId = $result['max'];
											
											if($_GET['country']==2)
											{
												$sql = "INSERT INTO `system_notification`
																(	`notificationId`,		`notificationTarget`,		`notificationStatus`,	`targetType`)
														VALUES	(	'".$notificationId."',	'0458',	'0',					'2'),
																(	'".$notificationId."',	'J018',	'0',					'2'),
																(	'".$notificationId."',	'0466',	'0',					'2'),
																(	'".$notificationId."',	'J014',	'0',					'2')
														";
												$queryInsert = $db->query($sql);
											}
											else
											{
												$sql = "INSERT INTO `system_notification`
																(	`notificationId`,		`notificationTarget`,		`notificationStatus`,	`targetType`)
														VALUES	(	'".$notificationId."',	'".$notificationTarget."',	'0',					'0')";
												$queryInsert = $db->query($sql);
											}
										//~ }
										// abang na code
									}
								}
								
								// ------------------------------------------------ End Gerald Code Notification (2016-06-03) ------------------------------------------------ //
								
								if($returnFromSubconFlag==1)
								{
									$sql = "INSERT INTO `system_returnfromsubcon`
													(	`lotNumber`,		`quantity`,		`status`)
											VALUES	(	'".$lotNo."',	'".$ngQty."',	'1')";
									$queryInsert = $db->query($sql);
								}
								
								$currentProcessCode = '';
								$sql = "SELECT id FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNo."' AND status = 0 AND processCode NOT IN(141,174,95,364,366,367,437,438,461,496,368) ORDER BY processOrder LIMIT 1";
								$queryWorkschedule = $db->query($sql);
								if($queryWorkschedule->num_rows > 0)
								{
									$resultWorkschedule = $queryWorkschedule->fetch_array();
									$currentWorkScheduleId = $resultWorkschedule['id'];
									
									$type = ($controlId == "CPAR-SUB") ? 2 : 1;
									
									if($returnFromSubconFlag==1 AND $type==1)	$type = 3;
									
									updatePaymentProcess($currentWorkScheduleId,$ngQty,$type,$newCPARId);
								}
								
								
								$sql = "SELECT * FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNo."' AND processCode LIKE '".$lotProcessArray[$i]."' ";
								$getWorkScheduleId = $db->query($sql);
								// ---------------------------------------------- Execute This Block if Choice is not Beginning -----------------------------------------
								if($getWorkScheduleId->num_rows > 0)
								{
									$getWorkScheduleIdResult = $getWorkScheduleId->fetch_array();
									
									$sql = "SELECT id FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNo."' AND processCode = 368 AND status = 0 LIMIT 1";
									$queryWorkSchedule = $db->query($sql);
									if($queryWorkSchedule AND $queryWorkSchedule->num_rows > 0)
									{
										$resultWorkSchedule = $queryWorkSchedule->fetch_assoc();
										$id = $resultWorkSchedule['id'];
										
										finishProcess("",$id, 0, $_SESSION['idNumber'],'');
									}
									else
									{
										$sql = "SELECT id, processOrder FROM  ppic_workschedule WHERE lotNumber LIKE '".$lotNo."' AND processOrder > ".$getWorkScheduleIdResult['processOrder']." ORDER BY processOrder ASC ";
										$getNextProcessOrders = $db->query($sql);
										if($getNextProcessOrders->num_rows > 0)
										{
											while($getNextProcessOrdersResult = $getNextProcessOrders->fetch_array())
											{
												$sql = "UPDATE ppic_workschedule SET processOrder = ".($getNextProcessOrdersResult['processOrder']+1)." WHERE id = ".$getNextProcessOrdersResult['id']." ";
												$update = $db->query($sql);
											}
										}
										
										$sql = "INSERT INTO ppic_workschedule (poId, 									customerId, 										poNumber, 										lotNumber, 										partNumber, 										revisionId, 						processCode, 						processOrder,								 processRemarks,						targetFinish,						receiveDate, 												deliveryDate, 											recoveryDate, 					actualStart, 		actualEnd, 			actualFinish, 				quantity, 			availability, 		status) 
													VALUES (".$getWorkScheduleIdResult['poId'].",		".$getWorkScheduleIdResult['customerId'].",		'".$getWorkScheduleIdResult['poNumber']."',		'".$getWorkScheduleIdResult['lotNumber']."',		'".$getWorkScheduleIdResult['partNumber']."',		'".$getWorkScheduleIdResult['revisionId']."',		'368',			".($getWorkScheduleIdResult['processOrder']+1).",				'".$_POST['message1']."',				now(),			'".$getWorkScheduleIdResult['receiveDate']."',			'".$getWorkScheduleIdResult['deliveryDate']."',			'".$getWorkScheduleIdResult['recoveryDate']."',			now(),			   now(),				now(),			".$ngQty.",			1,				   1) ";
										$insert = $db->query($sql);
									}
								}
								// ---------------------------------------------- End of Execute This Block if Choice is not Beginning ----------------------------------
								// ---------------------------------------------- Execute This Block if Choice is Beginning ---------------------------------------------
								else
								{
									$sql = "SELECT id FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNo."' AND processCode = 368 AND status = 0 LIMIT 1";
									$queryWorkSchedule = $db->query($sql);
									if($queryWorkSchedule AND $queryWorkSchedule->num_rows > 0)
									{
										$resultWorkSchedule = $queryWorkSchedule->fetch_assoc();
										$id = $resultWorkSchedule['id'];
										
										finishProcess("",$id, 0, $_SESSION['idNumber'],'');
									}
									else
									{									
										$sql = "SELECT id, processOrder FROM  ppic_workschedule WHERE lotNumber LIKE '".$lotNo."' AND processOrder >= 1 ORDER BY processOrder ASC ";
										$getNextProcessOrders = $db->query($sql);
										if($getNextProcessOrders->num_rows > 0)
										{
											while($getNextProcessOrdersResult = $getNextProcessOrders->fetch_array())
											{
												$sql = "UPDATE ppic_workschedule SET processOrder = ".($getNextProcessOrdersResult['processOrder']+1)." WHERE id = ".$getNextProcessOrdersResult['id']." ";
												$update = $db->query($sql);
											}
											
											$sql = "INSERT INTO ppic_workschedule (poId, 									customerId, 										poNumber, 										     lotNumber, 										partNumber, 										revisionId, 						    processCode, 							processOrder,					processRemarks,						targetFinish,						receiveDate, 												deliveryDate, 												recoveryDate, 							actualStart, 		actualEnd, 			actualFinish, 				quantity, 			availability, 		status) 
													VALUES (".$getNextProcessOrdersResult['poId'].",		".$getNextProcessOrdersResult['customerId'].",		'".$getNextProcessOrdersResult['poNumber']."',		'".$getNextProcessOrdersResult['lotNumber']."',		'".$getNextProcessOrdersResult['partNumber']."',		'".$getNextProcessOrdersResult['revisionId']."',		'368',									1,							'".$_POST['message1']."',			now(),			'".$getNextProcessOrdersResult['receiveDate']."',			'".$getNextProcessOrdersResult['deliveryDate']."',			'".$getNextProcessOrdersResult['recoveryDate']."',			now(),			   now(),				now(),			".$ngQty.",			1,				   1) ";
											$insert = $db->query($sql);
										}
									}
								}
								// --------------------------------------------- End Execute This Block if Choice is not Beginning -------------------------------
								
								// ---------------------------------- if CUS ---------------------------------------
								if($controlId == "CPAR-CUS")
								{
									$lotNumberArray = explode("-",$lotNo);
									$lotNumber = $lotNumberArray[0]."-".$lotNumberArray[1]."-".$lotNumberArray[2];
									
									$insertSQL2 = "INSERT INTO sales_rtv (rtv, lotNumber, quantity, date, remarks, user, ip) VALUES (2,'".trim($lotNumber)."',".$ngQty.",now(),'".$newCPARId."','".$sesLOG."','".$Users_IP_address."')";
									$insertSQLResult2 = $db->query($insertSQL2);
									//wala na -rose 2017-08-07 START
									// $sql = "SELECT poId, partId FROM ppic_lotlist WHERE lotNumber LIKE '".trim($lotNumber)."' ";
									// $getLotList = $db->query($sql);
									// $getLotListResult = $getLotList->fetch_array();
									
									// $sql = "SELECT poId FROM system_polist WHERE poId = ".$getLotListResult['poId']." ";
									// $getPOID = $db->query($sql);
									// if($getPOID->num_rows < 1)
									// {	
										// $sql = "SELECT partNumber, partName, revisionId, customerId FROM cadcam_parts WHERE partId = ".$getLotListResult['partId']." ";
										
										// $getParts = $db->query($sql);
										// $getPartsResult = $getParts->fetch_array();
										
										// $sql = "SELECT poNumber, poQuantity, deliveryDate, receiveDate, price FROM sales_polist WHERE poId = ".$getLotListResult['poId']." ";
										// $getPOList = $db->query($sql);
										// $getPOListResult = $getPOList->fetch_array();
										
										// $sql = "SELECT customerAlias FROM sales_customer WHERE customerId = ".$getPartsResult['customerId']." ";
										// $getCustomer = $db->query($sql);
										// $getCustomerResult = $getCustomer->fetch_array();
												
										// $sql = "INSERT INTO system_polist (poId, customerAlias, poNumber, partNumber, revisionId, partName, poQuantity, receiveDate, deliveryDate, poBalance, recoveryDate, remarks, price)
																   // VALUES (".$getLotListResult['poId'].", '".$getCustomerResult['customerAlias']."', '".$getPOListResult['poNumber']."', '".$getPartsResult['partNumber']."', '".$getPartsResult['revisionId']."', '".$getPartsResult['partName']."', ".$getPOListResult['poQuantity'].", '".$getPOListResult['receiveDate']."', '".$getPOListResult['deliveryDate']."', ".$getPOListResult['poQuantity'].", '".$getPOListResult['deliveryDate']."', '0', ".$getPOListResult['price'].") ";
										// $insert = $db->query($sql);
									// }
									//wala na -rose 2017-08-07 END
								}
								
								
								$sql = "
									SELECT DISTINCT b.poId FROM qc_cparlotnumber as a
									INNER JOIN ppic_lotlist as b ON b.lotNumber = a.lotNumber AND b.identifier = 1
									WHERE a.cparId LIKE 'CPAR%' AND a.status != 2 AND b.partLevel > 1 AND a.lotNumber LIKE '".$lotNo."'
								";
								$queryCparAssy = $db->query($sql);
								if($queryCparAssy AND $queryCparAssy->num_rows > 0)
								{
									$resultCparAssy = $queryCparAssy->fetch_assoc();
									$poId = $resultCparAssy['poId'];
									
									$sql = "SELECT lotNumber, poId FROM ppic_lotlist WHERE poId = ".$poId." AND partLevel = 1 AND identifier = 1";
									$queryLotList = $db->query($sql);
									if($queryLotList AND $queryLotList->num_rows > 0)
									{
										while($resultLotList = $queryLotList->fetch_assoc())
										{
											$mainLot = $resultLotList['lotNumber'];
											$poId = $resultLotList['poId'];
											
											$sql = "SELECT cparId FROM qc_cparlotnumber WHERE lotNumber LIKE '".$mainLot."' AND status != 2";
											$queryCpar = $db->query($sql);
											if($queryCpar AND $queryCpar->num_rows > 0)
											{
												$resultCpar = $queryCpar->fetch_assoc();
												$mainLotCparId = $resultCpar['cparId'];
												
												$lotNumArray = array();
												$sql = "SELECT lotNumber FROM ppic_lotlist WHERE poId = ".$poId."";
												$queryLote = $db->query($sql);
												if($queryLote AND $queryLote->num_rows > 0)
												{
													while($resultLote = $queryLote->fetch_assoc())
													{
														$lotNumArray[] = $resultLote['lotNumber'];
													}
												}
												
												$sql = "UPDATE qc_cparlotnumber SET viewFlag = 1 WHERE lotNumber IN('".implode("','",$lotNumArray)."') AND cparId LIKE '".$mainLotCparId."' AND lotNumber NOT LIKE '".$mainLot."'";
												$queryUpdate = $db->query($sql);
											}
										}
									}
								}
								
							}
						}
					}
					
					if($noPartialFlag==0)
					{					
						if($typeData == 'new')
						{
							if($dispo=='Scrap/Disposal/Replacement')
							{
								header("Location:gerald_partialCpar.php?cparId=".$newCPARId."&typeData=".$typeData."&employeeIdStart=".$employeeIdStart);
								exit(0);
							}
						}
						else
						{
							if($dispo=='Scrap/Disposal/Replacement')
							{
								header("Location:gerald_partialCpar.php?cparId=".$newCPARId);
								exit(0);
							}
						}
					}
				}


	// ------------------------- End of INSERT INTO qc_cparlotnumber ------------------------------//
    
    if($typeData == 'new')
    {
        // header("Location: /17 Lot Progress Input Software V2/paul_lotProgressInputForm.php");
        echo "<script>";
            echo "parent.location.href='../17 Lot Progress Input Software V2/paul_lotProgressInputForm.php?employeeIdStart=".$employeeIdStart."'";
        echo "</script>";
    }
    else
    {
        // ------------------------- End of INSERT INTO qc_cparlotnumber ------------------------------//
        //~ header("Location: anthony_cparSummary.php?source=print");
        header("Location: gerald_cparList.php");
    }
?>
