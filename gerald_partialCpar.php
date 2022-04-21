<?php
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
	//$path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
	set_include_path($path);
	include('PHP Modules/mysqliConnection.php');
    include('PHP Modules/gerald_functions.php');
    include('PHP Modules/anthony_retrieveText.php');
	include('PHP Modules/gerald_payablesFunction.php');
	ini_set("display_errors", "on");
	
	function changePurchaseOrderMakingProcess($lotNumber)
	{
		include('PHP Modules/mysqliConnection.php');
		
		$geraldFlag = 1;
		
		$sql = "SELECT id, lotNumber FROM ppic_workschedule WHERE processCode = 461 AND status = 0 AND lotNumber LIKE '".$lotNumber."'";
		$queryWorkschedule = $db->query($sql);
		if($queryWorkschedule AND $queryWorkschedule->num_rows > 0)
		{
			while($resultWorkschedule = $queryWorkschedule->fetch_assoc())
			{
				$id = $resultWorkschedule['id'];
				$lotNumber = $resultWorkschedule['lotNumber'];
				
				$partId = '';
				$sql = "SELECT lotNumber, partId FROM ppic_lotlist WHERE lotNumber LIKE '".$lotNumber."' AND identifier = 1 LIMIT 1";
				$queryLotList = $db->query($sql);
				if($queryLotList AND $queryLotList->num_rows > 0)
				{
					$resultLotList = $queryLotList->fetch_assoc();
					$partId =$resultLotList['partId'];
				}
				else
				{
					//~ echo "<br>Not parts";
					continue;
				}
				
				$subconProcessArray = array();
				
				$sql = "SELECT processCode, subconOrder FROM cadcam_subconlist WHERE partId = ".$partId." AND active = 0 ORDER BY subconOrder DESC";
				$querySubconlist = $db->query($sql);
				if($querySubconlist AND $querySubconlist->num_rows > 0)
				{
					//~ if($_GET['country']==2 OR $_SESSION['idNumber']=='0346')
					//~ {
						$subconProcessArray = array();
						while($resultSubconlist = $querySubconlist->fetch_assoc())
						{
							$sql = "SELECT treatmentName FROM engineering_treatment WHERE treatmentId = ".$resultSubconlist['processCode']." LIMIT 1";
							$queryTreatmentProcess = $db->query($sql);
							if($queryTreatmentProcess AND $queryTreatmentProcess->num_rows > 0)
							{
								$resultTreatmentProcess = $queryTreatmentProcess->fetch_assoc();
								$subconProcessArray[] = $resultTreatmentProcess['treatmentName'];
							}
						}
					//~ }
				}
				
				/*
				$sql = "SELECT processRemarks, status FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNumber."' AND processSection = 10 ORDER BY processOrder";
				$querySubconProcess = $db->query($sql);
				if($querySubconProcess AND $querySubconProcess->num_rows > 0)
				{
					while($resultSubconProcess = $querySubconProcess->fetch_assoc())
					{
						$processCode = $resultSubconProcess['processCode'];
						$status = $resultSubconProcess['status'];
						
						if($processCode==153)	$processCode = 490;
						
						if($status==1)
						{
							echo "<br>Subcon Process Already Finished";
							continue 2;
						}
						
						$sql = "SELECT treatmentName FROM engineering_treatment WHERE treatmentId = ".$processCode." LIMIT 1";
						$queryTreatment = $db->query($sql);
						if($queryTreatment AND $queryTreatment->num_rows > 0)
						{
							$resultTreatment = $queryTreatment->fetch_assoc();
							$treatmentName = $resultTreatment['treatmentName'];
							
							$subconProcessArray[] = $treatmentName;
						}
						else
						{
							echo "<br>Unknown Subcon Process";
							continue 2;
						}
					}
				}*/
				
				$insertFlag = 0;
				
				if(count($subconProcessArray) > 0)
				{
					$idArray = array();
					$sql = "SELECT id FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNumber."' AND processOrder > 0 ORDER BY processOrder";
					$queryWorkschedule2 = $db->query($sql);
					if($queryWorkschedule2 AND $queryWorkschedule2->num_rows > 0)
					{
						while($resultWorkschedule2 = $queryWorkschedule2->fetch_assoc())
						{
							$idArray[] = $resultWorkschedule2['id'];
						}
					}
					$processCountPreparation = 0;
					$processOrder = 1;
					foreach($subconProcessArray as $subconProcess)
					{
						$sql = "SELECT id FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNumber."' AND processCode = 597 AND processRemarks LIKE '".$subconProcess."' LIMIT 1";
						$queryWorkSchedule = $db->query($sql);
						if($queryWorkSchedule AND $queryWorkSchedule->num_rows > 0)
						{
							$processCountPreparation++;
						}
						else
						{
							$sql = "
									INSERT INTO `ppic_workschedule`
											(	`poId`, `customerId`, `poNumber`, `lotNumber`, `partNumber`, `revisionId`,	`processCode`,	`processOrder`, 	 `processSection`, 	`processRemarks`, 		`targetStart`, `targetFinish`, `standardTime`,	`receiveDate`, `deliveryDate`, `recoveryDate`, `availability`, `urgentFlag`, `subconFlag`, `partLevelFlag`, `status`)
									SELECT		`poId`, `customerId`, `poNumber`, `lotNumber`, `partNumber`, `revisionId`,	'597',			'".$processOrder."', '5', 				'".$subconProcess."', 	`targetStart`, `targetFinish`, '0', 			`receiveDate`, `deliveryDate`, `recoveryDate`, `availability`, `urgentFlag`, `subconFlag`, `partLevelFlag`, `status`
									FROM	`ppic_workschedule` WHERE id = ".$id." LIMIT 1";
							if($geraldFlag == 1) $queryInsert = $db->query($sql);
							if($queryInsert OR $geraldFlag == 0)
							{
								$processOrder++;
							}
						}
					}
					$processCountPrinting = 0;
					foreach($subconProcessArray as $subconProcess)
					{
						$sql = "SELECT id FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNumber."' AND processCode = 598 AND processRemarks LIKE '".$subconProcess."' LIMIT 1";
						$queryWorkSchedule = $db->query($sql);
						if($queryWorkSchedule AND $queryWorkSchedule->num_rows > 0)
						{
							$processCountPrinting++;
						}
						else
						{
							$sql = "
									INSERT INTO `ppic_workschedule`
											(	`poId`, `customerId`, `poNumber`, `lotNumber`, `partNumber`, `revisionId`,	`processCode`,	`processOrder`, 	 `processSection`, 	`processRemarks`, 		`targetStart`, `targetFinish`, `standardTime`,	`receiveDate`, `deliveryDate`, `recoveryDate`, `availability`, `urgentFlag`, `subconFlag`, `partLevelFlag`, `status`)
									SELECT		`poId`, `customerId`, `poNumber`, `lotNumber`, `partNumber`, `revisionId`,	'598',			'".$processOrder."', '5', 				'".$subconProcess."', 	`targetStart`, `targetFinish`, '0', 			`receiveDate`, `deliveryDate`, `recoveryDate`, `availability`, `urgentFlag`, `subconFlag`, `partLevelFlag`, `status`
									FROM	`ppic_workschedule` WHERE id = ".$id." LIMIT 1";
							if($geraldFlag == 1) $queryInsert = $db->query($sql);
							if($queryInsert OR $geraldFlag == 0)
							{
								$processOrder++;
							}
						}
					}
					
					if(count($subconProcessArray)!=$processCountPreparation)
					{
						//~ echo "Not Equal";
						
						$sql = "DELETE FROM ppic_workschedule WHERE id = ".$id." LIMIT 1";
						if($geraldFlag == 1) $queryDelete = $db->query($sql);
						
						$sql = "SET @newProcessOrder = ".($processOrder-1);
						if($geraldFlag == 1) $query = $db->query($sql);
						
						$sql = "UPDATE ppic_workschedule SET processOrder = @newProcessOrder := ( @newProcessOrder +1 ) WHERE id IN(".implode(",",$idArray).") ORDER BY FIELD(id,'".implode("','",$idArray)."')";
						if($geraldFlag == 1) $queryUpdate = $db->query($sql);	
						
						$insertFlag = 1;
					}
				}
				
				if($insertFlag==1)
				{
					$itemCount++;
					//~ echo "<br>Item Count : ".$itemCount;
				}			
			}
		}		
	}	
	
	function lotNumberTree(&$lotNumberArray,$recursiveLot)
	{
		include('PHP Modules/mysqliConnection.php');
		
		$sql = "SELECT lotNumber FROM ppic_lotlist WHERE parentLot LIKE '".$recursiveLot."'";
		$queryLotList = $db->query($sql);
		if($queryLotList AND $queryLotList->num_rows > 0)
		{
			while($resultLotList = $queryLotList->fetch_assoc())
			{
				$lotNumber = $resultLotList['lotNumber'];
				$lotNumberArray[] = $lotNumber;
				lotNumberTree($lotNumberArray,$lotNumber);
			}
		}
	}	
	
	function updateSchedule($poIdArray)	
	{
		include('PHP Modules/mysqliConnection.php');
		
		$targetFinishArray = array();
		
		foreach($poIdArray as $poId)
		{
			$poNumber = $customerDeliveryDate = '';
			$sql = "SELECT poNumber, customerId, customerDeliveryDate, receiveDate FROM sales_polist WHERE poId = ".$poId." LIMIT 1";
			$queryPoList = $db->query($sql);
			if($queryPoList AND $queryPoList->num_rows > 0)
			{
				$resultPoList = $queryPoList->fetch_assoc();
				$poNumber = $resultPoList['poNumber'];
				$customerId = $resultPoList['customerId'];
				$customerDeliveryDate = $resultPoList['customerDeliveryDate'];
				$receiveDate = $resultPoList['receiveDate'];
				
				$sql = "SELECT answerDate FROM system_lotlist WHERE poId = ".$poId." AND answerDate != '0000-00-00' LIMIT 1";
				$queryLotList = $db->query($sql);
				if($queryLotList AND $queryLotList->num_rows > 0)
				{
					$resultLotList = $queryLotList->fetch_assoc();
					$customerDeliveryDate = $resultLotList['answerDate'];
				}
			}
			
			$mainLotNumber = '';
			$sql = "SELECT lotNumber FROM ppic_lotlist WHERE poId = ".$poId." AND parentLot = '' AND partLevel = 1 AND identifier = 1 LIMIT 1";
			$queryLotList = $db->query($sql);
			if($queryLotList AND $queryLotList->num_rows > 0)
			{
				$resultLotList = $queryLotList->fetch_assoc();
				$mainLotNumber = $resultLotList['lotNumber'];
				
				$lotNumberArray = array();
				$lotNumberArray[] = $mainLotNumber;
				
				lotNumberTree($lotNumberArray,$mainLotNumber);
				
				$lotCount = count($lotNumberArray);
			}
			else
			{
				continue;
			}
			
			$deliveryInterval = 2;
			
			$lastProcessDataArray = array();
			
			if($lotCount > 0)
			{
				//~ $sqlFilter = "AND ROUND((LENGTH(lotNumber)-LENGTH(REPLACE(lotNumber,'-','')))/LENGTH('-')) = 2";
				$sql = "SELECT lotNumber, partId, parentLot, partLevel, workingQuantity, identifier, patternId FROM ppic_lotlist WHERE poId = ".$poId." AND identifier IN(1,2) AND workingQuantity > 0 ORDER BY FIELD(lotNumber,'".implode("','",$lotNumberArray)."')";
				$queryLotList = $db->query($sql);
				if($queryLotList AND $queryLotList->num_rows > 0)
				{
					while($resultLotList = $queryLotList->fetch_assoc())
					{
						$lotNumber = $resultLotList['lotNumber'];
						$partId = $resultLotList['partId'];
						$parentLot = $resultLotList['parentLot'];
						$partLevel = $resultLotList['partLevel'];
						$workingQuantity = $resultLotList['workingQuantity'];
						$identifier = $resultLotList['identifier'];
						$patternId = $resultLotList['patternId'];
						
						$recentSectionId = $recentMotherSectionId = $recentProcessCode = $recentDepartmentId = '';
						if($partLevel == 1)
						{
							$targetFinish = $customerDeliveryDate;
						}
						else if($partLevel > 1)
						{
							$targetFinish = $lastProcessDataArray[$parentLot]['targetFinish'];
							$recentProcessCode = $lastProcessDataArray[$parentLot]['processCode'];
							$recentSectionId = $lastProcessDataArray[$parentLot]['processSection'];
						}
						
						$tableTable = '';
						//~ $sql = "SELECT id, processOrder, processCode, processSection, targetFinish, actualFinish, status FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNumber."' AND processCode NOT IN(460,459,324) ORDER BY processOrder DESC";
						$sql = "SELECT id, processOrder, processCode, processSection, targetFinish, actualFinish, status FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNumber."' AND processCode NOT IN(460,459,324) AND status = 0 ORDER BY processOrder DESC";
						$queryWorkSchedule = $db->query($sql);
						if($queryWorkSchedule AND $queryWorkSchedule->num_rows > 0)
						{
							while($resultWorkSchedule = $queryWorkSchedule->fetch_assoc())
							{
								$id = $resultWorkSchedule['id'];
								$processOrder = $resultWorkSchedule['processOrder'];
								$processCode = $resultWorkSchedule['processCode'];
								$processSection = $resultWorkSchedule['processSection'];
								$oldTargetFinish = $resultWorkSchedule['targetFinish'];
								$actualFinish = $resultWorkSchedule['actualFinish'];
								$status = $resultWorkSchedule['status'];
								
								$processName = '';
								$sql = "SELECT processName FROM cadcam_process WHERE processCode = ".$processCode." LIMIT 1";
								$queryProcess = $db->query($sql);
								if($queryProcess AND $queryProcess->num_rows > 0)
								{
									$resultProcess = $queryProcess->fetch_assoc();
									$processName = $resultProcess['processName'];
								}
								
								$sectionName = $motherSectionId = $departmentId = '';
								$sql = "SELECT sectionName, motherSectionId, departmentId FROM ppic_section WHERE sectionId = ".$processSection." LIMIT 1";
								$querySection = $db->query($sql);
								if($querySection AND $querySection->num_rows > 0)
								{
									$resultSection = $querySection->fetch_assoc();
									$sectionName = $resultSection['sectionName'];
									$motherSectionId = $resultSection['motherSectionId'];
									$departmentId = $resultSection['departmentId'];
								}
								
								if($identifier==1)
								{
									$interval = 0;
									if($processCode!=144)
									{
										if($recentProcessCode==144)
										{
											$interval = $deliveryInterval;
										}
										else if($recentProcessCode==518)
										{
											$interval = 1;
										}
										else if(in_array($processCode,array(145,148)))
										{
											$interval = 3;
										}
										else if($recentProcessCode!=162)
										{
											if($processSection!=$recentSectionId OR $processCode==162)
											{
												$interval = 1;
												if($recentMotherSectionId==36 AND $recentMotherSectionId==$motherSectionId)
												{
													$interval = 0;
												}
												
												if(in_array($recentProcessCode,array(184,496)) OR in_array($processCode,array(496,312,430,431,432,358)))	$interval = 0;
												
												//~ if(in_array($recentSectionId,array(12,48)))
												//~ {
													//~ $interval = 3;
												//~ }
												//~ else if(in_array($recentSectionId,array(6)))
												//~ {
													//~ $interval = 2;
												//~ }
												
												if(in_array($recentSectionId,array(6,12,48)))
												{
													$interval = 2;
												}
											}
										}
									}
									
									if($interval > 0)
									{
										$targetFinish = addDays(-$interval,$targetFinish);
									}
									
									if($processCode==461)
									{
										$targetFinishTemp = $targetFinish;
										$targetFinish = addDays(+1,$receiveDate);
									}
								}
								
								if(strtotime($targetFinish) < strtotime(date('Y-m-d')))
								{
									$targetFinish = date('Y-m-d');
								}
								
								$processDataArray = array();
								$processDataArray['processCode'] = $processCode;
								$processDataArray['processSection'] = $processSection;
								$processDataArray['targetFinish'] = $targetFinish;
								
								$scheduleDataArray[] = $processDataArray;
								
								$color = ($status==1) ? 'lightgreen' : '';
								$color1 = ($targetFinish==$oldTargetFinish) ? 'lightblue' : 'pink';
								
								if($color1=='pink')
								{
									if(!isset($targetFinishArray[$targetFinish])) $targetFinishArray[$targetFinish] = array();
									$targetFinishArray[$targetFinish][] = $id;
								}
								
								//~ $tableTable .= "
									//~ <tr>
										//~ <td>".$lotNumber."</td>
										//~ <td>".$processOrder--."</td>
										//~ <td>".$processName."</td>
										//~ <td>".$sectionName."</td>
										//~ <td style='background-color:".$color1.";'>".$targetFinish."</td>
										//~ <td style='background-color:".$color.";'>".$oldTargetFinish."</td>
										//~ <td style='background-color:".$color.";'>".$actualFinish."</td>
										//~ <td>".$color1."</td>
									//~ </tr>
								//~ ";							
								
								if($processCode==461)
								{
									$targetFinish = $targetFinishTemp;
									$processSection = $recentSectionId;
									$motherSectionId = $recentMotherSectionId;
									$processCode = $recentProcessCode;
								}								
								
								$recentSectionId = $processSection;
								$recentMotherSectionId = $motherSectionId;
								$recentProcessCode = $processCode;								
								
								$lotNumberPart = explode("-",$lotNumber);
								$mainLot = $lotNumberPart[0]."-".$lotNumberPart[1]."-".$lotNumberPart[2];
								$lastProcessDataArray[$mainLot]['targetFinish'] = $targetFinish;
								$lastProcessDataArray[$mainLot]['processCode'] = $processCode;
								$lastProcessDataArray[$mainLot]['processSection'] = $processSection;
							}
						}
						
						//~ echo "<table border='1'>".$tableTable."</table>";
					}
				}
			}
		}
		
		if(count($targetFinishArray) > 0)
		{
			foreach($targetFinishArray as $targetFinish => $idArray)
			{
				$sqlUpdate = "UPDATE ppic_workschedule SET targetStart = '".$targetFinish."', targetFinish = '".$targetFinish."' WHERE id IN(".implode(",",$idArray).")";
				$queryUpdate = $db->query($sqlUpdate);
			}
		}
	}
	
	$cparId = $_GET['cparId'];
	$testFlag = (isset($_GET['testFlag'])) ? $_GET['testFlag'] : '';
	
	$cparDetails = $cparDisposition = $cparInterimAction = $idNumber = $cparCustomerSchedule = '';
	$sql = "SELECT cparDetails, cparDisposition, cparInterimAction, cparMaker, cparCustomerSchedule FROM qc_cpar WHERE cparId LIKE '".$cparId."' LIMIT 1";
	$queryCpar = $db->query($sql);
	if($queryCpar->num_rows > 0)
	{
		$resultCpar = $queryCpar->fetch_array();
		$cparDetails = $resultCpar['cparDetails'];
		$cparDisposition = $resultCpar['cparDisposition'];
		$cparInterimAction = $resultCpar['cparInterimAction'];
		$idNumber = $resultCpar['cparMaker'];
		$cparCustomerSchedule = $resultCpar['cparCustomerSchedule'];
	}
	
	$poIdArray = $loteArray = $dataArray = array();
	
	$sql = "SELECT lotNumber, quantity, prsNumber FROM qc_cparlotnumber WHERE cparId LIKE '".$cparId."' AND status = 0";
	if($testFlag=='gerald')	$sql = "SELECT lotNumber, quantity, prsNumber FROM qc_cparlotnumber WHERE cparId LIKE '".$cparId."' AND status = 1 AND listId = 2631";
	$queryCparLotNumber = $db->query($sql);
	if($queryCparLotNumber->num_rows > 0)
	{
		while($resultCparLotNumber = $queryCparLotNumber->fetch_array())
		{
			$lotNumber = $resultCparLotNumber['lotNumber'];
			$quantity = $resultCparLotNumber['quantity'];
			$prsNumber = $resultCparLotNumber['prsNumber'];
		
			$lotPatternId = 0;
			$poId = $partId = $identifier = $partLevel = '';
			$sql = "SELECT poId, partId, identifier, patternId, partLevel FROM ppic_lotlist WHERE lotNumber LIKE '".$lotNumber."' LIMIT 1";
			$queryLotList = $db->query($sql);
			if($queryLotList->num_rows > 0)
			{
				$resultLotList = $queryLotList->fetch_array();
				$poId = $resultLotList['poId'];
				$partId = $resultLotList['partId'];
				$identifier = $resultLotList['identifier'];
				$lotPatternId = $resultLotList['patternId'];
				$partLevel = $resultLotList['partLevel'];
			}
			
			if($identifier==1)
			{
				$sql = "SELECT DISTINCT patternId FROM cadcam_partprocess WHERE partId = ".$partId."";
				$queryPartProcess = $db->query($sql);
				if($queryPartProcess->num_rows > 0)
				{
					while($resultPartProcess = $queryPartProcess->fetch_assoc())
					{
						if($partLevel == 1)
						{
							$sql = "SELECT partId FROM cadcam_partprocess WHERE partId = ".$partId." AND patternId = ".$resultPartProcess['patternId']." AND processCode = 144 LIMIT 1";
							$queryCheckDeliveryProcess = $db->query($sql);
							if($queryCheckDeliveryProcess->num_rows > 0)
							{
								$lotPatternId = $resultPartProcess['patternId'];
								break;
							}
						}
						else if($partLevel > 1)
						{
							$sql = "SELECT partId FROM cadcam_partprocess WHERE partId = ".$partId." AND patternId = ".$resultPartProcess['patternId']." AND processCode = 144 LIMIT 1";
							$queryCheckDeliveryProcess = $db->query($sql);
							if($queryCheckDeliveryProcess->num_rows == 0)
							{
								$lotPatternId = $resultPartProcess['patternId'];
								break;
							}
						}
					}
				}
			}
			
			$processCode1 = $sectionId1 = array();
			
			$rtvCparId = '';
			
			if($identifier==1)
			{
				$partNumber = $revisionId = $customerId = '';
				$sql = "SELECT partNumber, revisionId, customerId FROM cadcam_parts WHERE partId = ".$partId." LIMIT 1";
				$queryParts = $db->query($sql);
				if($queryParts->num_rows > 0)
				{
					$resultParts = $queryParts->fetch_array();
					$partNumber = $resultParts['partNumber'];
					$revisionId = $resultParts['revisionId'];
					$customerId = $resultParts['customerId'];
				}
				
				if($customerId==28)
				{
					if(strstr($cparId,'CPAR-CUS')!==FALSE)
					{
						$rtvCparId = $cparId;
					}
					else
					{
						$rtvCparId = checkRTV($lotNumber);
					}
				}
				
				//~ $patternId = '0';
				//~ $sql = "SELECT patternId FROM ppic_schedule WHERE poId = ".$poId." LIMIT 1";
				//~ $querySchedule = $db->query($sql);
				//~ if($querySchedule->num_rows > 0)
				//~ {
					//~ $resultSchedule = $querySchedule->fetch_array();
					//~ $patternId = $resultSchedule['patternId'];
				//~ }
				
				$patternId = $lotPatternId;
				
				$processCode1 = isset($_POST['processCode1']) ? $_POST['processCode1'] : array();
				$sectionId1 = isset($_POST['sectionId1']) ? $_POST['sectionId1'] : array();
				
				$processCode1 = array_values(array_filter($processCode1));
				$sectionId1 = array_values(array_filter($sectionId1));
				
				$finishedArray = array();
				
				if($_GET['country']=='1')//Philippines
				{
					$blankingProcesses = array(86,52,381);//Blanking (TPP) AND Blanking (Press) 1,Blanking (Laser)
				}
				else if($_GET['country']=='2')//Japan
				{
					$blankingProcesses = array(86,314,328,378,381,382,383,385,401,403,478,479,372,499);//Blanking (TPP),Laser,Cutting,Machining
				}
				
				if($patternId=='-1')
				{
					$fgFlag = 0;
					$sql = "SELECT inventoryId, inventoryQuantity FROM warehouse_inventory WHERE type = 5 AND dataOne LIKE '".$partNumber."' AND dataTwo LIKE '".$revisionId."' ORDER BY stockDate";
					$queryInventory = $db->query($sql);
					if($queryInventory->num_rows > 0)
					{
						while($resultInventory = $queryInventory->fetch_array())
						{
							$inventoryId = $resultInventory['inventoryId'];
							$inventoryQuantity = $resultInventory['inventoryQuantity'];
							
							$totalWithdrawal = 0;
							$sql = "SELECT IFNULL(SUM(finishGoodWithdrawalQuantity),0) AS totalWithdrawal FROM warehouse_finishgoodwithdrawal WHERE finishGoodWithdrawalId LIKE '".$inventoryId."'";
							$queryFinishWithdrawal = $db->query($sql);
							if($queryFinishWithdrawal->num_rows > 0)
							{
								$resultFinishWithdrawal = $queryFinishWithdrawal->fetch_array();
								$totalWithdrawal = $resultFinishWithdrawal['totalWithdrawal'];
							}
							
							$totalBooking = 0;
							$sql = "SELECT IFNULL(SUM(bookQuantity),0) AS totalBooking FROM system_finishedgoodbooking WHERE inventoryId LIKE '".$inventoryId."'";
							$queryFinishGoodBooking = $db->query($sql);
							if($queryFinishGoodBooking->num_rows > 0)
							{
								$resultFinishGoodBooking = $queryFinishGoodBooking->fetch_array();
								$totalBooking = $resultFinishGoodBooking['totalBooking'];
							}
							
							$availableStock = $inventoryQuantity - ($totalWithdrawal+$totalBooking+$inventoryQuantityArray[$inventoryId]);
							
							if($availableStock >= $workingQuantity)
							{
								$inventoryQuantityArray[$inventoryId] += $workingQuantity;
								$fgFlag = 1;
								break;
							}
						}
						
						if($fgFlag==1)
						{
							echo "<input type='hidden' name='inventoryId' value='".$inventoryId."' form='formId'>";
							
							$processCode1 = array(254);
							$sectionId1 = array(36);
							
							$patId = '';
							$sql = "SELECT patternId FROM cadcam_partprocess WHERE partId = ".$partId." AND processCode = 144 LIMIT 1";
							$queryPartProcess = $db->query($sql);
							if($queryPartProcess->num_rows > 0)
							{
								$resultPartProcess = $queryPartProcess->fetch_array();
								$patId = $resultPartProcess['patternId'];
							}
							
							$firstProcessOrder = '';
							$sql = "SELECT processOrder FROM cadcam_partprocess WHERE partId = ".$partId." AND patternId = ".$patId." AND processCode IN(91,92,93,168,197,205,220,230,238,241,242,342,343,346) ORDER BY processOrder DESC LIMIT 1";
							$queryPartProcess = $db->query($sql);
							if($queryPartProcess->num_rows > 0)
							{
								$resultPartProcess = $queryPartProcess->fetch_array();
								$firstProcessOrder = $resultPartProcess['processOrder'];
							}
							
							$sql = "SELECT processCode, processSection FROM cadcam_partprocess WHERE partId = ".$partId." AND patternId = ".$patId." AND processOrder >= ".$firstProcessOrder." ORDER BY processOrder";				
							$queryPartProcess = $db->query($sql);
							if($queryPartProcess->num_rows > 0)
							{
								while($resultPartProcess = $queryPartProcess->fetch_array())
								{
									$processCode1[] = $resultPartProcess['processCode'];
									$sectionId1[] = $resultPartProcess['processSection'];
								}
							}
						}
					}
				}
			}
			else if($identifier==2)
			{
				if($_GET['country']=='2')//Japan
				{
					$processCode1[] = 343;
					$sectionId1[] = 52;
					$processCode1[] = 344;
					$sectionId1[] = 4;
				}
				else
				{
					if(in_array($partId,array(1577,1631)))
					{
						$processCode1[] = 254;
						$sectionId1[] = 36;
						$processCode1[] = 91;
						$sectionId1[] = 4;
						$processCode1[] = 117;
						$sectionId1[] = 6;
						$processCode1[] = 87;
						$sectionId1[] = 12;
					}
					else
					{
						$sql = "SELECT accessoryId FROM `cadcam_accessories` WHERE accessoryId = ".$partId." AND `accessoryName` LIKE '%packaging%' LIMIT 1";
						$queryPackagingMaterial = $db->query($sql);
						if($queryPackagingMaterial AND $queryPackagingMaterial->num_rows > 0 OR in_array($partId,array(1646,1818,1647)))
						{
							$processCode1[] = 254;
							$sectionId1[] = 36;
						}
						else
						{
							$scheduleDataArray[] = array('processCode'=>192,'processSection'=>30);
							$scheduleDataArray[] = array('processCode'=>91,'processSection'=>4);
							$scheduleDataArray[] = array('processCode'=>254,'processSection'=>36);
							
							$processCode1[] = 254;
							$sectionId1[] = 36;
							$processCode1[] = 91;
							$sectionId1[] = 4;
							$processCode1[] = 192;
							$sectionId1[] = 30;
						}
					}
				}
			}
			else if($identifier==4)
			{
				$processCode1[] = 137;
				$sectionId1[] = 33;
				$processCode1[] = 163;
				$sectionId1[] = 4;
				$processCode1[] = 437;
				$sectionId1[] = 37;
				$processCode1[] = 353;
				$sectionId1[] = 31;
			}
			
			$countRows = 1;
			if(count($processCode1) == 0 AND count($sectionId1) == 0)
			{
				$subconCount = 0;
				$insertSubconPoFlag = 0;
				$sql = "SELECT subconOrder FROM cadcam_subconlist WHERE partId = ".$partId." AND active = 0 GROUP BY subconOrder ORDER BY subconOrder";
				$querySubconlist = $db->query($sql);
				if($querySubconlist->num_rows > 0)
				{
					if(strstr($cparId,'CPAR-INT')!==FALSE OR strstr($cparId,'CPAR-SUP')!==FALSE)
					{
						$lotNo = $lotNumber;
						$stopFlag = 0;
						while($stopFlag == 0)
						{
							$stopFlag = 1;
							
							$sql = "SELECT lotNumber FROM `ppic_prslog` WHERE lotNumber LIKE '".$lotNo."' AND type = 4 LIMIT 1";//Return from subcon
							$queryReturnFromSubcon = $db->query($sql);
							if($queryReturnFromSubcon->num_rows == 0)
							{
								$sql = "SELECT listId FROM system_returnfromsubcon WHERE lotNumber LIKE '".$lotNo."' LIMIT 1";
								$queryReturnFromSubcon = $db->query($sql);
								if($queryReturnFromSubcon AND $queryReturnFromSubcon->num_rows == 0)
								{
									$sql = "SELECT status FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNo."' AND processCode = 145 LIMIT 1";
									$queryWorkschedule = $db->query($sql);
									if($queryWorkschedule->num_rows > 0)
									{
										$resultWorkschedule = $queryWorkschedule->fetch_array();
										$status = $resultWorkschedule['status'];
										if($status==1)	$insertSubconPoFlag = 1;
									}
									else
									{
										$sql = "SELECT sourceLotNumber FROM ppic_prslog WHERE lotNumber LIKE '".$lotNo."' LIMIT 1";
										$queryPrsLog = $db->query($sql);
										if($queryPrsLog->num_rows > 0)
										{
											$resultPrsLog = $queryPrsLog->fetch_array();
											$lotNo = $resultPrsLog['sourceLotNumber'];
											$stopFlag = 0;
										}
									}
								}
							}
						}
						
						if($cparDisposition!='Scrap/Disposal/Replacement')	$insertSubconPoFlag = 0;
					}
					else if(strstr($cparId,'CPAR-CUS')!==FALSE)
					{
						$insertSubconPoFlag = 1;
					}
					
					if($insertSubconPoFlag==0)
					{
						$sql = "SELECT id FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNumber."' AND processCode = 461 AND status = 0 LIMIT 1";
						$queryWorkSchedule = $db->query($sql);
						if($queryWorkSchedule AND $queryWorkSchedule->num_rows > 0)
						{
							$insertSubconPoFlag = 1;
						}
					}
					
					if($insertSubconPoFlag==1)
					{
						$processCode1[] = 461;
						$sectionId1[] = '5';
					}
				}
				
				if($cparDisposition=='Scrap/Disposal/Replacement')
				{
					$patternIdArray = array();
					$sql = "SELECT DISTINCT patternId FROM cadcam_partprocess WHERE partId = ".$partId." ORDER BY patternId";
					$queryPartProcessPattern = $db->query($sql);
					if($queryPartProcessPattern->num_rows > 0)
					{
						while($resultPartProcessPattern = $queryPartProcessPattern->fetch_array())
						{
							$patternIdArray[] = $resultPartProcessPattern['patternId'];
						}
					}
					
					if(!in_array($patternId,$patternIdArray))	$patternId = $patternIdArray[0];
					
					if($_GET['country']=='2')//Japan
					{
						$firstBlankingProcessCode = '';
						$sql = "SELECT processCode FROM cadcam_partprocess WHERE partId = ".$partId." AND patternId = ".$patternId." ANd processCode IN(".implode(",",$blankingProcesses).") ORDER BY processOrder LIMIT 1";
						$queryBlankingProcess = $db->query($sql);
						if($queryBlankingProcess AND $queryBlankingProcess->num_rows > 0)
						{
							$resultBlankingProcess = $queryBlankingProcess->fetch_assoc();
							$firstBlankingProcessCode = $resultBlankingProcess['processCode'];
						}
					}
					
					$packagingProcessArray = array();
					$sql = "SELECT processCode FROM cadcam_process WHERE processName LIKE '%packaging%' AND status = 0";
					$queryProcess = $db->query($sql);
					if($queryProcess AND $queryProcess->num_rows > 0)
					{
						while($resultProcess = $queryProcess->fetch_assoc())
						{
							$packagingProcessArray[] = $resultProcess['processCode'];
						}
					}
					
					$packagingProcessCode = '';
					$sql = "SELECT processCode FROM cadcam_partprocess WHERE partId = ".$partId." AND patternId = ".$patternId." AND processCode = 94 ORDER BY processOrder DESC LIMIT 1";
					$queryDocumentation = $db->query($sql);
					if($queryDocumentation AND $queryDocumentation->num_rows > 0)
					{
						$sql = "SELECT processCode FROM cadcam_partprocess WHERE partId = ".$partId." AND patternId = ".$patternId." AND processCode IN(".implode(",",$packagingProcessArray).") ORDER BY processOrder DESC LIMIT 1";
						$queryPackaging = $db->query($sql);
						if($queryPackaging AND $queryPackaging->num_rows > 0)
						{
							$resultPackaging = $queryPackaging->fetch_assoc();
							$packagingProcessCode = $resultPackaging['processCode'];
						}
					}
					
					$sql = "SELECT processOrder, processCode, processSection, itemHandlingFlag FROM cadcam_partprocess WHERE partId = ".$partId." AND patternId = ".$patternId." ORDER BY processOrder";
					$queryPartProcess = $db->query($sql);
					if($queryPartProcess->num_rows > 0)
					{
						//~ $countRows = $queryPartProcess->num_rows;
						while($resultPartProcess = $queryPartProcess->fetch_array())
						{
							if($rtvCparId!='')//If RTV
							{
								if(in_array($resultPartProcess['processCode'],array(496,94,518,144)))
								{
									continue;
								}
							}
							
							if((in_array($resultPartProcess['processCode'],$blankingProcesses)) OR ($resultPartProcess['processOrder']==1 AND in_array($resultPartProcess['processCode'],array(98,392))))
							{
								if($_GET['country']==2)
								{
									if($firstBlankingProcessCode==$resultPartProcess['processCode'])
									{
										if(in_array($resultPartProcess['processCode'],array(381,382,401,403)))
										{
											$processCode1[] = '312';
											//~ $sectionId1[] = '28';
											$sectionId1[] = '40';
											
											$processCode1[] = '136';
											$sectionId1[] = '23';
										}
										else if(in_array($resultPartProcess['processCode'],array(314,378)))
										{
											$processCode1[] = '430';
											//~ $sectionId1[] = '28';
											$sectionId1[] = '40';
											
											$processCode1[] = '136';
											$sectionId1[] = '23';
										}
									}
								}
								else
								{
									if($resultPartProcess['processCode']==86)
									{
										$processCode1[] = '312';
										//~ $sectionId1[] = '28';
										$sectionId1[] = '40';
									}
									else if($resultPartProcess['processCode']==381)
									{
										$processCode1[] = '430';
										//~ $sectionId1[] = '43';
										$sectionId1[] = '40';
									}
									else if($resultPartProcess['processCode']==52)
									{
										$processCode1[] = '431';
										//~ $sectionId1[] = '44';
										$sectionId1[] = '40';
									}
									else if(in_array($resultPartProcess['processCode'],array(328,98,392)))
									{
										$processCode1[] = '432';
										//~ $sectionId1[] = '45';
										$sectionId1[] = '40';
									}
									else
									{
										$processCode1[] = '312';
										//~ $sectionId1[] = '28';
										$sectionId1[] = '40';
									}
									
									$processCode1[] = '136';
									$sectionId1[] = '23';
								}
							}
							else if($resultPartProcess['processCode'] == $packagingProcessCode)
							{
								//~ if(in_array($customerId,array(28,37,45,49)))
								//~ {
									$processCode1[] = '358';//2021-03-18
									$sectionId1[] = '34';
								//~ }								
							}
							else if($resultPartProcess['processCode'] == 94 AND in_array($customerId,array(28,37,45,49)))//Documentation
							{
								//rosemie
								if(in_array($customerId,array(28,37)))
								{
								//~ $processCode1[] = '358';//2021-02-27
								//~ $sectionId1[] = '34';
								//~ $sectionId1[] = '29';
								}
								else
								{
								//~ $processCode1[] = '358';
								//~ $sectionId1[] = '34';
								//~ $sectionId1[] = '29';
									
								}
								//rosemie
							}
							
							$processCode1[] = $resultPartProcess['processCode'];
							$sectionId1[] = $resultPartProcess['processSection'];
							
							if($resultPartProcess['processCode']==163)
							{
								$processCode1[] = 437;	//163 - Incoming Inspection, 437 - Payment
								$sectionId1[] = 37;
							}
							if($resultPartProcess['processCode']==424)
							{
								$processCode1[] = 438;	//424 - Incoming Inspection, 438 - Payment
								$sectionId1[] = 37;
							}
							//~ if($resultPartProcess['itemHandlingFlag']==1)
							//~ {
								//~ if(!in_array($processCode,array(144,94,96,86,381,353,496,136,312,430,431,432,137,162,358,461,192)))
								//~ {
									//~ if($processSection!=11)
									//~ {
										//~ $processCode1[] = 496;	//496 - Item Handling
										//~ $sectionId1[] = 50;
									//~ }
								//~ }
							//~ }
						}
						if($rtvCparId!='')//If RTV
						{
							if($processCode1[count($processCode1)-1]==496)
							{
								$processCode1[count($processCode1)-1] = 353;//353 - Warehouse Storage
								$sectionId1[count($sectionId1)-1] = 31;//Storage
							}
							else
							{
								$processCode1[] = 353;	//353 - Warehouse Storage
								$sectionId1[] = 31;//Storage
							}
						}
					}
				}
			}
			
			if($cparDisposition=='Scrap/Disposal/Replacement')
			{
				$customerDeliveryDate = '';
				$sql = "SELECT customerDeliveryDate FROM sales_polist WHERE poId = ".$poId." LIMIT 1";
				$queryPoList = $db->query($sql);
				if($queryPoList AND $queryPoList->num_rows > 0)
				{
					$resultPoList = $queryPoList->fetch_assoc();
					$customerDeliveryDate = $resultPoList['customerDeliveryDate'];
				}
				
				if(strtotime($customerDeliveryDate) >= strtotime(date('Y-m-d')))
				{
					if(!in_array($poId,$poIdArray))
					{
						$poIdArray[] = $poId;
					}
				}
				
				// abang na code
				//~ if(strstr($cparId,'-CUS')!==FALSE)
				//~ {
					//~ $sql = "UPDATE qc_cparlotnumber SET prsNumber = '".$newLotNumber."', status = 1 WHERE cparId LIKE '".$cparId."' AND lotNumber LIKE '".$lotNumber."' AND prsNumber = '' AND status = 0 LIMIT 1";
					//~ $queryUpdate = $db->query($sql);
					//~ continue;
				//~ }
				// abang na code				
				
				//~ echo "
					//~ <table border='1'>
						//~ <tr>
							//~ <th>Lot Number</th>
							//~ <th>Order</th>
							//~ <th>Process</th>
						//~ </tr>
				//~ ";
				
				if(count($processCode1) > 0)
				{
					if($prsNumber=='')
					{
						$dashPosition = strrpos($lotNumber, "-");
						if($dashPosition>=9)
						{
							$originalLotNumber=substr($lotNumber,0,$dashPosition);
						}
						else
						{
							$originalLotNumber = $lotNumber;
						}
						
						$sql = "SELECT MAX( CAST(SUBSTRING(lotNumber,LOCATE('-',lotNumber,10)+1) AS SIGNED) ) as max FROM ppic_lotlist WHERE lotNumber LIKE '".$originalLotNumber."-%'";
						$lotQuery = $db->query($sql);
						if($lotQuery->num_rows>0)
						{
							$lotQueryResult = $lotQuery->fetch_assoc();
							$newLotNumber = $originalLotNumber."-".($lotQueryResult['max']+1);
						}
						else
						{
							$newLotNumber = $originalLotNumber."-1";
						}
						
						$sql = "	INSERT INTO `ppic_lotlist`
											(	poId , partId, parentLot, partLevel, identifier, status, bookingStatus, poContentId, deliveryDate, partialBatchId, lotNumber,				workingQuantity,	patternId, 			dateGenerated)
									SELECT		poId , partId, parentLot, partLevel, identifier, status, bookingStatus, poContentId, deliveryDate, partialBatchId,	'".$newLotNumber."',	'".$quantity."',	'".$patternId."',	NOW()
									FROM	ppic_lotlist
									WHERE	lotNumber LIKE '".$lotNumber."' LIMIT 1";
						if($testFlag=='') $queryInsert = $db->query($sql);
						
						$sql = "UPDATE ppic_lotlist SET workingQuantity = (workingQuantity - ".$quantity.") WHERE lotNumber LIKE '".$lotNumber."' LIMIT 1";
						if($testFlag=='') $queryUpdate = $db->query($sql);
					}
					else
					{
						$newLotNumber = $prsNumber;
					}
					
					$sql = "SELECT poId, customerId, poNumber, partNumber, revisionId, receiveDate, deliveryDate, recoveryDate, urgentFlag, subconFlag, partLevelFlag FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNumber."' AND processCode NOT IN(141,174) LIMIT 1";
					$queryWorkschedule = $db->query($sql);
					if($queryWorkschedule->num_rows > 0)
					{
						$resultWorkschedule = $queryWorkschedule->fetch_array();
						$poId = $resultWorkschedule['poId'];
						$customerId = $resultWorkschedule['customerId'];
						$poNumber = $resultWorkschedule['poNumber'];
						$partNumber = $resultWorkschedule['partNumber'];
						$revisionId = $resultWorkschedule['revisionId'];
						$receiveDate = $resultWorkschedule['receiveDate'];
						$deliveryDate = $resultWorkschedule['deliveryDate'];
						$recoveryDate = $resultWorkschedule['recoveryDate'];
						$urgentFlag = $resultWorkschedule['urgentFlag'];
						$subconFlag = $resultWorkschedule['subconFlag'];
						$partLevelFlag = $resultWorkschedule['partLevelFlag'];
					}
					
					$targetFinish = date('Y-m-d');
					
					$sqlMain = "INSERT INTO ppic_workschedule (poId, customerId, poNumber, lotNumber, partNumber, revisionId, processCode , processOrder, processSection, processRemarks, targetFinish, receiveDate, deliveryDate, recoveryDate, availability, urgentFlag, subconFlag, partLevelFlag, poContentIds) VALUES ";
					$sqlValueArray = array();
					$counter = 0;
					$withPaymentFlag = 0;
					foreach($processCode1 as $key => $processCode)
					{
						$processOrder = $key+1;
						$processSection = $sectionId1[$key];
						
						$pName = '';
						$sql = "SELECT processName FROM cadcam_process WHERE processCode = ".$processCode." LIMIT 1";
						$queryProcess = $db->query($sql);
						if($queryProcess->num_rows > 0)
						{
							$resultProcess = $queryProcess->fetch_array();
							$pName = $resultProcess['processName'];
						}
						
						if(in_array($processCode,array(461)))
						{
							$sql = "UPDATE ppic_lotlist SET poContentId = '' WHERE lotNumber LIKE '".$newLotNumber."' LIMIT 1";
							if($testFlag=='') $queryUpdate = $db->query($sql);
							
							$withPaymentFlag = 1;						
						}
						
						$processRemarks = "";
						if(in_array($processCode,array(137,138,229,163,424,425)))
						{
							$sql = "SELECT processRemarks FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNumber."' AND processCode = ".$processCode." LIMIT 1";
							$queryProcessRemarks = $db->query($sql);
							if($queryProcessRemarks->num_rows > 0)
							{
								$resultProcessRemarks = $queryProcessRemarks->fetch_array();
								$processRemarks = $resultProcessRemarks['processRemarks'];
							}
						}
						
						$targetFinish = '0000-00-00';
						$poContentIds = "";
						
						$loopLotNo = $lotNumber;
						$loopFlag = 1;
						while($loopFlag==1)
						{
							$loopFlag = 0;
							$sql = "SELECT targetFinish, poContentIds FROM ppic_workschedule WHERE lotNumber LIKE '".$loopLotNo."' AND processCode = ".$processCode." LIMIT 1";
							$queryWorkSchedule = $db->query($sql);
							if($queryWorkSchedule AND $queryWorkSchedule->num_rows > 0)
							{
								$resultWorkSchedule = $queryWorkSchedule->fetch_assoc();
								$targetFinish = $resultWorkSchedule['targetFinish'];
								$poContentIds = $resultWorkSchedule['poContentIds'];
							}
							else
							{
								$sql = "SELECT sourceLotNumber FROM ppic_prslog WHERE lotNumber LIKE '".$loopLotNo."' AND type != 7 AND sourceLotNumber != '' LIMIT 1";
								$queryPrsLog = $db->query($sql);
								if($queryPrsLog AND $queryPrsLog->num_rows > 0)
								{
									$resultPrsLog = $queryPrsLog->fetch_assoc();
									$loopLotNo = $resultPrsLog['sourceLotNumber'];
									$loopFlag = 1;
								}
							}
						}
						
						if($targetFinish=='0000-00-00')
						{
							$targetFinish = $prevTargetFinish;
						}
						
						$prevTargetFinish = $targetFinish;
						
						$availability = (in_array($processCode,array(312,430,431,432))) ? 1 : 0;
						
						$sqlValue = "(".$poId." ,".$customerId.", '".$poNumber."' , '".$newLotNumber."', '".$partNumber."' , '".$revisionId."', '".$processCode."' , ".$processOrder.", ".$processSection.", '".$processRemarks."', '".$targetFinish."', '".$receiveDate."', '".$deliveryDate."', '".$recoveryDate."', ".$availability.", ".$urgentFlag.", ".$subconFlag.", ".$partLevelFlag.", '".$poContentIds."')";
						
						if($counter < 50)
						{
							$sqlValueArray[] = $sqlValue;
							$counter++;
						}
						else
						{
							$insertSql = $sqlMain." ".implode(",",$sqlValueArray);
							if($testFlag=='gerald')	echo "<br>".$insertSql;
							if($testFlag=='') $queryInsert = $db->query($insertSql);
							$sqlValueArray = array();
							$counter = 0;
							$sqlValueArray[] = $sqlValue;
							$counter++;
						}
						
						//~ echo "
							//~ <tr>
								//~ <td>".$lotNumber."</td>
								//~ <td>".$processOrder."</td>
								//~ <td>".$pName."</td>
								//~ <td>".$sqlValue."</td>
							//~ </tr>
						//~ ";
					}
					if($counter > 0)
					{
						$insertSql = $sqlMain." ".implode(",",$sqlValueArray);
						if($testFlag=='gerald')	echo "<br>".$insertSql;
						if($testFlag=='') $queryInsert = $db->query($insertSql);
					}
					
					if($testFlag=='') changePurchaseOrderMakingProcess($newLotNumber);
					
					$currentProcessCode = '';
					$sql = "SELECT processCode, processOrder FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNumber."' AND status = 0 AND processCode NOT IN (141,174,95,313,366,367,364,437,438,496,368) ORDER BY processOrder ASC LIMIT 1 ";
					$queryWorkSchedule = $db->query($sql);
					if($queryWorkSchedule AND $queryWorkSchedule->num_rows > 0)
					{
						$resultWorkSchedule = $queryWorkSchedule->fetch_assoc();
						$currentProcessCode = $resultWorkSchedule['processCode'];
					}
					
					$sql = "SELECT lotNumber FROM ppic_prslog WHERE lotNumber LIKE '".$newLotNumber."' LIMIT 1";
					$queryLotList = $db->query($sql);
					if($queryLotList AND $queryLotList->num_rows == 0)
					{
						$sql = "INSERT INTO	ppic_prslog
										(	lotNumber,				employeeId,			date,	remarks,			type,	cparNumber,		sourceLotNumber,	partialQuantity,	sourceLastProcess)
								VALUES	(	'".$newLotNumber."',	'".$idNumber."',	now(),	'".$cparDetails."',	5,		'".$cparId."',	'".$lotNumber."',	'".$quantity."',	'".$currentProcessCode."')";
						if($testFlag=='') $queryInsert = $db->query($sql);
					}
					
					partialPayables($lotNumber,$newLotNumber);
					
					$sql = "UPDATE qc_cparlotnumber SET prsNumber = '".$newLotNumber."', status = 1 WHERE cparId LIKE '".$cparId."' AND lotNumber LIKE '".$lotNumber."' AND prsNumber = '' AND status = 0 LIMIT 1";
					if($testFlag=='') $queryUpdate = $db->query($sql);
					
					$sql = "SELECT lotNumber FROM ppic_lotlist WHERE lotNumber LIKE '".$lotNumber."' AND workingQuantity <= 0 LIMIT 1";
					$queryLotList = $db->query($sql);
					if($queryLotList->num_rows > 0)
					{
						$deleteExcept = "141,174";
						// ----- Commented By Gerald 2016-11-16 ----- //
						//~ if($withPaymentFlag==1)
						//~ {
							//~ if($currentProcessCode == 163)	$deleteExcept = "141,174,437";
							//~ elseif($currentProcessCode == 424)	$deleteExcept = "141,174,438";
						//~ }
						// ----- Commented By Gerald 2016-11-16 ----- //

						if($currentProcessCode == 163)	$deleteExcept = "141,174,437";
						elseif($currentProcessCode == 424)	$deleteExcept = "141,174,438";
						
						$sql = "DELETE FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNumber."' AND processCode NOT IN(".$deleteExcept.") AND status = 0";
						if($testFlag=='') $queryDelete = $db->query($sql);
					}
					
					$sql = "UPDATE system_lotOnHold SET status = 1 WHERE lotNumber LIKE '".$lotNumber."' AND status = 0 LIMIT 1";
					if($testFlag=='') $queryUpdate = $db->query($sql);
					
					$sql = "SELECT groupTag FROM ppic_lotlist WHERE lotNumber LIKE '".$lotNumber."' AND workingQuantity <= 0 AND identifier = 1 AND groupTag != '' LIMIT 1";
					$queryLotList = $db->query($sql);
					if($queryLotList AND $queryLotList->num_rows > 0)
					{
						$resultLotList = $queryLotList->fetch_assoc();
						$groupTag = $resultLotList['groupTag'];
						
						$mergeId = '';
						$sql = "SELECT mergeId FROM ppic_mergecommand WHERE groupTag LIKE '".$groupTag."' AND mergeStatus = 1 LIMIT 1";
						$queryMergeCommand = $db->query($sql);
						if($queryMergeCommand AND $queryMergeCommand->num_rows > 0)
						{
							$resultMergeCommand = $queryMergeCommand->fetch_assoc();
							$mergeId = $resultMergeCommand['mergeId'];
						}
						
						$sql = "UPDATE ppic_mergecommanddetails SET unmergeDate = NOW(), unmergeIncharge = '".$_SESSION['idNumber']."', unmergeProcess = '".$currentProcessCode."' WHERE mergeId = ".$mergeId." AND lotNumber LIKE '".$lotNumber."' AND status = 2 LIMIT 1";
						if($testFlag=='') $queryUpdate = $db->query($sql);
						
						$sql = "UPDATE ppic_lotlist SET groupTag = '', viewFlag = 0 WHERE lotNumber LIKE '".$lotNumber."' LIMIT 1";
						if($testFlag=='') $queryUpdate = $db->query($sql);
						
						$sql = "UPDATE ppic_lotlist SET viewFlag = 0 WHERE groupTag LIKE '".$groupTag."' LIMIT 1";
						if($testFlag=='') $queryUpdate = $db->query($sql);
					}
					
					$loteArray[] = $newLotNumber;
					
					if($_GET['country']==2)
					{
						if($identifier==1 AND $partLevel==1)
						{
							$dataArray[] = $poId;
						}
					}
				}
				//~ echo "</table>";
			}
		}
	}
	
	//~ if($_SESSION['idNumber']=='0346')
	//~ {
		if(count($poIdArray) > 0)
		{
			//~ updateSchedule($poIdArray);//Commented by gerald 2020-09-07 co Leslie
		}
		if($_GET['country']==1)
		{
			if(count($loteArray) > 0)
			{
				foreach($loteArray as $lote)
				{
					insertItemHandlingProcess($lote);
				}
			}
		}
		else
		{
			if($cparCustomerSchedule!='' AND $cparCustomerSchedule!='0000-00-00' AND count($dataArray) > 0)
			{
				$poIdArray = $dataArray;
				
				$startDate = date('Y-m-d');
				$newDueDate = $cparCustomerSchedule;
				$remarks = 'Reschedule on CPAR';
				
				$sql = "
					INSERT INTO system_duedate
							(	poId,	dueDate, 				userId,							dateChange,	oldDueDate,		remarks,		startDate)
					SELECT 		poId,	'".$newDueDate."',		'".$_SESSION['idNumber']."',	NOW(),		recoveryDate,	'".$remarks."',	'".$startDate."'
					FROM 		system_lotlist WHERE poId IN(".implode(",",$poIdArray).") GROUP BY poId
				";
				$queryInsert = $db->query($sql);//Add startdate 2019-12-16
				
				$sql = "UPDATE system_lotlist SET recoveryDate = '".$newDueDate."', recoveryFlag = 1 WHERE poId IN(".implode(",",$poIdArray).")";
				$queryUpdate = $db->query($sql);
				
				$lotNumberArray = array();
				$sql = "SELECT lotNumber FROM ppic_lotlist WHERE poId IN(".implode(",",$poIdArray).") AND identifier IN(1,2,5)";
				$queryLotList = $db->query($sql);
				if($queryLotList AND $queryLotList->num_rows > 0)
				{
					while($resultLotList = $queryLotList->fetch_assoc())
					{
						$lotNumberArray[] = $resultLotList['lotNumber'];
					}
				}

				if(count($poIdArray) > 0)
				{
					foreach($poIdArray as $poId)
					{
						generateScheduleItems($poId,array('start'=>$startDate,'dueDate'=>$newDueDate),1,0);
						//~ generateScheduleItems($poId,array('start'=>'receiveDate','dueDate'=>$newDueDate),1,0);
						//~ generateScheduleItems($poId,array('start'=>'2019-09-19','dueDate'=>$newDueDate),1,0);
					}
				}
				
				$sql = "UPDATE ppic_workschedule SET recoveryDate = '".$newDueDate."' WHERE lotNumber IN('".implode("','",$lotNumberArray)."')";
				$queryUpdate = $db->query($sql);
				
				//~ $sql = "SELECT targetFinish, lotNumber FROM ppic_workschedule WHERE lotNumber IN('".implode("','",$lotNumberArray)."') AND processCode = 518";
				//~ $query = $db->query($sql);
				//~ if($query AND $query->num_rows > 0)
				//~ {
					//~ while($result = $query->fetch_assoc())
					//~ {
						//~ $lotNumber = $result['lotNumber'];
						//~ $targetFinish = $result['targetFinish'];

						//~ $sql = "UPDATE system_lotlist SET recoveryDate = '".$targetFinish."' WHERE lotNumber = '".$lotNumber."'";
						//~ $queryUpdate = $db->query($sql);
						
						//~ $sql = "UPDATE ppic_workschedule SET recoveryDate = '".$targetFinish."' WHERE lotNumber = '".$lotNumber."'";
						//~ $queryUpdate = $db->query($sql);
					//~ }
				//~ }
			}
		}
    //~ }
    
	$typeData = isset($_GET['typeData']) ? $_GET['typeData'] : '';
    $employeeIdStart = isset($_GET['employeeIdStart']) ? $_GET['employeeIdStart'] : '';
    
    if($typeData == 'new')
    {
        echo "<script>";
            echo "parent.location.href='../17 Lot Progress Input Software V2/paul_lotProgressInputForm.php?employeeIdStart=".$employeeIdStart."'";
        echo "</script>";
        exit();
    }
    else
    {
        if($testFlag=='') header("location:anthony_inputForm.php");
    }
?>
