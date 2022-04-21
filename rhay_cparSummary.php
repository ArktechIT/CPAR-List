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
	include('PHP Modules/anthony_retrieveText.php');
	include('PHP Modules/anthony_wholeNumber.php');
	include('PHP Modules/rose_prodfunctions.php');
	$rhayFlag = 0;
	if($_GET['country'] == 2 OR $_SESSION['idNumber'] == '0412')
	{
		$rhayFlag = 1;
	}
	
	$allStatus = (isset($_POST['allStatus'])) ? $_POST['allStatus'] : '' ;
	$cparId = (isset($_POST['cparId'])) ? $_POST['cparId'] : '' ;
	$cparInfoSource = (isset($_POST['cparInfoSource'])) ? $_POST['cparInfoSource'] : '' ;
	$cparDetails = (isset($_POST['cparDetails'])) ? $_POST['cparDetails'] : '' ;
	$cparMoreDetails = (isset($_POST['cparMoreDetails'])) ? $_POST['cparMoreDetails'] : '' ;
	$analysis = (isset($_POST['analysis'])) ? $_POST['analysis'] : '' ;
	$cparSection = (isset($_POST['cparSection'])) ? $_POST['cparSection'] : '' ;
	$cparSourcePerson = (isset($_POST['cparSourcePerson'])) ? $_POST['cparSourcePerson'] : '' ;
	$cparDetectProcess = (isset($_POST['cparDetectProcess'])) ? $_POST['cparDetectProcess'] : '' ;
	$cparDetectPerson = (isset($_POST['cparDetectPerson'])) ? $_POST['cparDetectPerson'] : '' ;
	$cparIssueDate = (isset($_POST['cparIssueDate'])) ? $_POST['cparIssueDate'] : '' ;
	$cparDueDate = (isset($_POST['cparDueDate'])) ? $_POST['cparDueDate'] : '' ;
	$cparStatus = (isset($_POST['cparStatus'])) ? $_POST['cparStatus'] : '' ;
	$alarmFilter = (isset($_POST['alarmFilter'])) ? $_POST['alarmFilter'] : '' ;
	$interimFilter = (isset($_POST['interimFilter'])) ? $_POST['interimFilter'] : '' ;
	$correctiveFilter = (isset($_POST['correctiveFilter'])) ? $_POST['correctiveFilter'] : '' ;
	$verificationFilter = (isset($_POST['verificationFilter'])) ? $_POST['verificationFilter'] : '' ;
	$cparDispositions = (isset($_POST['cparDisposition'])) ? trim($_POST['cparDisposition']) : '' ;
	$customerId = (isset($_POST['customerId'])) ? $_POST['customerId'] : '' ;
	$advance = (isset($_GET['advance'])) ? $_GET['advance'] : '' ;
	$today = (isset($_GET['today'])) ? $_GET['today'] : '' ;
	$delay = (isset($_GET['delay'])) ? $_GET['delay'] : '' ;
	$dateStart = (isset($_POST['dateStart']) AND $_POST['dateStart'] != '') ? $_POST['dateStart'] : date("Y-m")."-01";
	$dateEnd = (isset($_POST['dateEnd']) AND $_POST['dateEnd'] != '') ? $_POST['dateEnd'] : date("Y-m-d");
	$month = '';
	$year = '';
		//~ $month = (isset($_POST['month'])) ? $_POST['month'] : date("m");
	//~ $year = (isset($_POST['year'])) ? $_POST['year'] : date("Y");
	// --------------------------------------------------------------------------------- Alarm Filter ------------------------------------------------------------------------------
	$date = strtotime("-5 day", strtotime(date("Y-m-d")));
	$redAlarmDate = date("Y-m-d", $date);
	
	$date = strtotime("-1 day", strtotime(date("Y-m-d")));
	$orangeAlarmDate = date("Y-m-d", $date);
	
	$yellowAlarmDate = date("Y-m-d");
	
	$date = strtotime("+1 day", strtotime(date("Y-m-d")));
	$greenAlarmDate = date("Y-m-d", $date);
	
	$date = strtotime("+3 day", strtotime(date("Y-m-d")));
	$blueAlarmDate = date("Y-m-d", $date);
	
	if(isset($alarmFilter))
	{
		if($alarmFilter == 'Red')
		{
			$alarmFilterClause = "AND cparDueDate <= '".$redAlarmDate."'";
		}
		else if($alarmFilter == 'Orange')
		{
			$alarmFilterClause = "AND cparDueDate <= '".$orangeAlarmDate."' AND cparDueDate > '".$redAlarmDate."'";
		}
		else if($alarmFilter == 'Yellow')
		{
			$alarmFilterClause = "AND cparDueDate = '".$yellowAlarmDate."'";
		}
		else if($alarmFilter == 'Green')
		{
			$alarmFilterClause = "AND cparDueDate >= '".$greenAlarmDate."' AND cparDueDate < '".$blueAlarmDate."'";
		}
		else if($alarmFilter == 'Blue')
		{
			$alarmFilterClause = "AND cparDueDate >= '".$blueAlarmDate."'";
		}
		else
		{
			$alarmFilterClause = "";
		}
	}
	else
	{
		$alarmFilterClause = "";
	}
	
	$dateToday = date("Y-m-d");
	// *************************************** Interim Filter ********************************************
	$redInterimFilterDate = date("Y-m-d", strtotime($dateToday." -5 days"));
	$orangeInterimFilterDate = date("Y-m-d", strtotime($dateToday." -1 days"));
	$yellowInterimFilterDate = $dateToday;
	$greenInterimFilterDate = date("Y-m-d", strtotime($dateToday." +1 days"));
	$blueInterimFilterDate = date("Y-m-d", strtotime($dateToday." +3 days"));

	if(isset($interimFilter))
	{
		if($interimFilter == 'Red')
		{
			$interimFilterClause = "AND cparIssueDate <= '".$redInterimFilterDate."' AND cparInterimAction LIKE ''";
		}
		else if($interimFilter == 'Orange')
		{
			$alarmFilterClause = "AND cparIssueDate <= '".$orangeInterimFilterDate."' AND cparIssueDate > '".$redInterimFilterDate."' AND cparInterimAction LIKE ''";
		}
		else if($interimFilter == 'Yellow')
		{
			$interimFilterClause = "AND cparIssueDate = '".$yellowInterimFilterDate."' AND cparInterimAction LIKE ''";
		}
		else if($interimFilter == 'Green')
		{
			$interimFilterClause = "AND cparIssueDate >= '".$greenInterimFilterDate."' AND cparIssueDate < '".$blueInterimFilterDate."' AND cparInterimAction LIKE ''";
		}
		else if($interimFilter == 'Blue')
		{
			$interimFilterClause = "AND cparIssueDate >= '".$blueInterimFilterDate."' AND cparInterimAction LIKE ''";
		}
		else
		{
			$interimFilterClause = "";
		}
	}
	// *************************************** Corrective Filter ********************************************
	$redCorrectiveFilterDate = date("Y-m-d", strtotime($dateToday." -12 days"));
	$orangeCorrectiveFilterDate = date("Y-m-d", strtotime($dateToday." -8 days"));
	$yellowCorrectiveFilterDate = date("Y-m-d", strtotime($dateToday." -7 days"));
	$greenCorrectiveFilterDate = date("Y-m-d", strtotime($dateToday." +1 days"));
	$blueCorrectiveFilterDate = date("Y-m-d", strtotime($dateToday." +3 days"));

	if(isset($correctiveFilter))
	{
		if($correctiveFilter == 'Red')
		{
			$correctiveFilterClause = "AND cparIssueDate <= '".$redCorrectiveFilterDate."' AND cparCorrectiveProcess LIKE ''";
		}
		else if($correctiveFilter == 'Orange')
		{
			$correctiveFilterClause = "AND cparIssueDate <= '".$orangeCorrectiveFilterDate."' AND cparIssueDate > '".$redCorrectiveFilterDate."' AND cparCorrectiveProcess LIKE ''";
		}
		else if($correctiveFilter == 'Yellow')
		{
			$correctiveFilterClause = "AND cparIssueDate >= '".$yellowCorrectiveFilterDate."' AND cparIssueDate < '".$greenCorrectiveFilterDate."' AND cparCorrectiveProcess LIKE ''";
		}
		else if($correctiveFilter == 'Green')
		{
			$correctiveFilterClause = "AND cparIssueDate >= '".$greenCorrectiveFilterDate."' AND cparIssueDate < '".$blueCorrectiveFilterDate."' AND cparCorrectiveProcess LIKE ''";
		}
		else if($correctiveFilter == 'Blue')
		{
			$correctiveFilterClause = "AND cparIssueDate >= '".$blueCorrectiveFilterDate."' AND cparCorrectiveProcess LIKE ''";
		}
		else
		{
			$correctiveFilterClause = "";
		}
	}
	// *************************************** Verification Filter ********************************************
	$redVerificationFilterDate = date("Y-m-d", strtotime($dateToday." -35 days"));
	$orangeVerificationFilterDate = date("Y-m-d", strtotime($dateToday." -31 days"));
	$yellowVerificationFilterDate = date("Y-m-d", strtotime($dateToday." -30 days"));
	$greenVerificationFilterDate = date("Y-m-d", strtotime($dateToday." +1 days"));
	$blueVerificationFilterDate = date("Y-m-d", strtotime($dateToday." +3 days"));

	if(isset($verificationFilter))
	{
		if($verificationFilter == 'Red')
		{
			$verificationFilterClause = "AND cparIssueDate <= '".$redVerificationFilterDate."' AND cparVerification LIKE ''";
		}
		else if($verificationFilter == 'Orange')
		{
			$verificationFilterClause = "AND cparIssueDate <= '".$orangeVerificationFilterDate."' AND cparIssueDate > '".$redVerificationFilterDate."' AND cparVerification LIKE ''";
		}
		else if($verificationFilter == 'Yellow')
		{
			$verificationFilterClause = "AND cparIssueDate >= '".$yellowVerificationFilterDate."' AND cparIssueDate < '".$greenVerificationFilterDate."' AND cparVerification LIKE ''";
		}
		else if($verificationFilter == 'Green')
		{
			$verificationFilterClause = "AND cparIssueDate >= '".$greenVerificationFilterDate."' AND cparIssueDate < '".$blueVerificationFilterDate."' AND cparVerification LIKE ''";
		}
		else if($verificationFilter == 'Blue')
		{
			$verificationFilterClause = "AND cparIssueDate >= '".$blueVerificationFilterDate."' AND cparVerification LIKE ''";
		}
		else
		{
			$verificationFilterClause = "";
		}
	}
	$defaultStart = date('Y-m');
	$defaultEnd = date('Y-m');
	$extraQuery = '';
	if($cparDispositions != '') $extraQuery = " AND cparDisposition LIKE '".$cparDispositions."' ";

	$cparQuery = $cparLotQuery = "";
	$cparIdArray = $lotNumberArray = array();
	if($customerId != '')
	{
		$sql = "
			SELECT d.cparId FROM sales_customer as a
			INNER JOIN cadcam_parts as b ON b.customerId = a.customerId
			INNER JOIN ppic_lotlist as c ON c.partId = b.partId AND identifier = 1
			INNER JOIN qc_cparlotnumber as d ON d.lotNumber = c.lotNumber
			WHERE a.customerId = ".$customerId."
		";
		$queryParts = $db->query($sql);
		if($queryParts AND $queryParts->num_rows > 0)
		{
			while($resultParts = $queryParts->fetch_assoc())
			{
				$cparIdArray[] = $resultParts['cparId'];
			}
		}
		
		$cparQuery = " AND cparId IN('".implode("','",$cparIdArray)."')";
		
		//~ $sql = "
			//~ SELECT d.lotNumber FROM sales_customer as a
			//~ INNER JOIN cadcam_parts as b ON b.customerId = a.customerId
			//~ INNER JOIN ppic_lotlist as c ON c.partId = b.partId AND identifier = 1
			//~ INNER JOIN qc_cparlotnumber as d ON d.lotNumber = c.lotNumber
			//~ WHERE a.customerId = ".$customerId."
		//~ ";
		//~ $queryParts = $db->query($sql);
		//~ if($queryParts AND $queryParts->num_rows > 0)
		//~ {
			//~ while($resultParts = $queryParts->fetch_assoc())
			//~ {
				//~ $lotNumberArray[] = $resultParts['lotNumber'];
			//~ }
		//~ }
		
		//~ $cparLotQuery = " AND lotNumber IN('".implode("','",$lotNumberArray)."')";
	}	
		$m = date("Y-m");
	$filterIssueDate = "AND cparIssueDate LIKE '".$m."%' ";
	if($dateStart != '' AND $dateEnd == '')
	{
		$filterIssueDate = " AND cparIssueDate >= '".$dateStart."' ";
	}
	else if($dateStart != '' AND $dateEnd != '')
	{
		$filterIssueDate = " AND cparIssueDate BETWEEN '".$dateStart."' AND '".$dateEnd."' ";
	}
	
	//~ if($openOnly=='')	$openOnly = 1;
	
	$openCparStatusFilter = ($allStatus==1) ? "" : "AND cparStatus IN('Verified','Issued','Answered')";
	
	$mergeQuery = "WHERE cparId LIKE '%".$cparId."%' AND cparInfoSource LIKE '%".$cparInfoSource."%' AND cparDetails LIKE '%".$cparDetails."%' AND cparMoreDetails LIKE '%".$cparMoreDetails."%' AND cparAnalysis LIKE '%".$analysis."%' AND cparSection LIKE '%".$cparSection."%' AND cparIssueDate LIKE '%".$cparIssueDate."%' AND cparDueDate LIKE '%".$cparDueDate."%' AND cparSourcePerson LIKE '%".$cparSourcePerson."%' AND cparDetectProcess LIKE '%".$cparDetectProcess."%' AND cparDetectPerson LIKE '%".$cparDetectPerson."%' AND cparStatus LIKE '%".$cparStatus."%' ".$openCparStatusFilter." ".$advanceTodayDelay." ".$alarmFilterClause." ".$interimFilterClause." ".$correctiveFilterClause." ".$verificationFilterClause." ".$filterIssueDate." AND cparId NOT LIKE '' ".$extraQuery." ".$cparQuery;// AND cparIssueDate LIKE '%".date("Y-m-")."%' 
	// -------------------------------------------------------------------- Count Number of Rows -----------------------------------------------------------------------
	// ******************* Old Code *********************
	/*
	$sql = "SELECT cparId, COUNT(listId) AS totalRecords FROM qc_cpar ".$mergeQuery." ";
	$getTotalRecords = $db->query($sql);
	$getTotalRecordsResult = $getTotalRecords->fetch_assoc();
	$total_groups = ceil($getTotalRecordsResult['totalRecords']/$queryLimit); 
	*/

	$cparIdArray = array();
	$totalNGQuantity = $totalAmount = $totalQuantity = $totalDppm = 0;
	$sql = "SELECT DISTINCT cparId FROM qc_cpar ".$mergeQuery." ";
	$getCparId = $db->query($sql);
	if($getCparId AND $getCparId->num_rows > 0)
	{
		while($getCparIdResult = $getCparId->fetch_assoc())
		{
            $cparIdArray[] = "'".$getCparIdResult['cparId']."'";
		}
	}
	
	//$cparIdArray = array_values(array_filter(array_unique($cparIdArray)));
	$sql = "SELECT COUNT(listId) AS totalRecords FROM qc_cparlotnumber WHERE cparId IN (".implode(',',$cparIdArray).") AND status != 2 ".$cparLotQuery;
	if($customerId!='')
	{
		$sql = "
			SELECT COUNT(d.listId) AS totalRecords FROM sales_customer as a
			INNER JOIN cadcam_parts as b ON b.customerId = a.customerId
			INNER JOIN ppic_lotlist as c ON c.partId = b.partId AND identifier = 1
			INNER JOIN qc_cparlotnumber as d ON d.lotNumber = c.lotNumber AND d.cparId IN (".implode(',',$cparIdArray).") AND d.status != 2 
			WHERE a.customerId = ".$customerId."
		";
	}	
	$getTotalRecords = $db->query($sql);
	if($getTotalRecords->num_rows > 0)
	{
		$getTotalRecordsResult = $getTotalRecords->fetch_assoc();
		$total_groups = $getTotalRecordsResult['totalRecords']; 
	}
	else
	{
		$getTotalRecordsResult['totalRecords'] = 0;
	}
	
		//Rose
		$sqlRose = "SELECT COUNT(listId) AS totalRecords2 FROM qc_cparlotnumber WHERE cparId IN (".implode(',',$cparIdArray).") AND status != 2 ".$cparLotQuery;
		if($customerId!='')
		{
			$sql = "
				SELECT COUNT(d.listId) AS totalRecords2 FROM sales_customer as a
				INNER JOIN cadcam_parts as b ON b.customerId = a.customerId
				INNER JOIN ppic_lotlist as c ON c.partId = b.partId AND identifier = 1
				INNER JOIN qc_cparlotnumber as d ON d.lotNumber = c.lotNumber AND d.cparId IN (".implode(',',$cparIdArray).") AND d.status != 2 
				WHERE a.customerId = ".$customerId."
			";
		}
		$getTotalPrice = $db->query($sqlRose);
		if($getTotalPrice->num_rows > 0)
		{
			$getTotalPriceResult = $getTotalPrice->fetch_assoc();
			$total_pricez = $getTotalPriceResult['totalRecords2']; 
		}
		
		//echo "rose".$getTotalPriceResult['totalRecords2']."_".$sqlRose;
		$totalPRICE=0;
		$extendQuery = '';
		$totalQuantity = $totalPercentage = 0;
		$sql = "SELECT cparId, lotNumber, quantity FROM qc_cparlotnumber WHERE cparId IN (".implode(',',$cparIdArray).") AND status != 2 ".$cparLotQuery;
		if($customerId!='')
		{
			$sql = "
				SELECT d.cparId, d.lotNumber, d.quantity FROM sales_customer as a
				INNER JOIN cadcam_parts as b ON b.customerId = a.customerId
				INNER JOIN ppic_lotlist as c ON c.partId = b.partId AND identifier = 1
				INNER JOIN qc_cparlotnumber as d ON d.lotNumber = c.lotNumber AND d.cparId IN (".implode(',',$cparIdArray).") AND d.status != 2 
				WHERE a.customerId = ".$customerId."
			";
        }
		$getCPARLotNumber = $db->query($sql);
		if($getCPARLotNumber AND $getCPARLotNumber->num_rows > 0)
		{
			while($getCPARLotNumberResult = $getCPARLotNumber->fetch_assoc())
			{
				$cparIds = $getCPARLotNumberResult['cparId'];
				$cparLotNumber = $getCPARLotNumberResult['lotNumber'];
                $quantity = $getCPARLotNumberResult['quantity'];
                	
				
				if(strstr($cparIds, "CPAR-CUS") !== FALSE) $extendQuery = "AND updateStatus != 2";
				else if(strstr($cparIds, "CPAR-SUB") !== FALSE) $extendQuery = "AND updateStatus = 2";
				else $extendQuery = '';
				
				$sql = "SELECT Quantity FROM purchasing_drcustomer WHERE lotNumber LIKE '".$getCPARLotNumberResult['lotNumber']."' ".$extendQuery."";
				$queryDr = $db->query($sql);
				if($queryDr AND $queryDr->num_rows > 0)
				{
					while($resultDr = $queryDr->fetch_assoc())
					{
						$totalQtyDelivered = $resultDr['Quantity']; //total qty delivered;
						$totalQuantity += $resultDr['Quantity']; //total qty delivered;
					}
					if($totalQtyDelivered > 0) $totalDppm += (($quantity * 1000000) / $totalQtyDelivered);
				}

				//------------------------------------ PERCENTAGE -------------------------------------------------//
				if(strstr($cparIds, "CPAR-CUS") !== FALSE OR strstr($cparIds, "CPAR-SUB") !== FALSE)
				{
					if($quantity > 0 AND $totalQtyDelivered > 0) $totalPercentage += ($quantity / $totalQtyDelivered);
				}
				else if(strstr($cparIds, "CPAR-INT") !== FALSE)
				{
					$sql = "SELECT poId FROM ppic_lotlist WHERE lotNumber LIKE '".$cparLotNumber."' AND identifier = 1 LIMIT 1";
					$queryLot = $db->query($sql);
					if($queryLot AND $queryLot->num_rows > 0)
					{
						$resultLot = $queryLot->fetch_assoc();
						$poIdLot = $resultLot['poId']; 
						
						$sql = "SELECT lotNumber, partId FROM ppic_lotlist WHERE poId = ".$poIdLot." AND identifier = 1 GROUP BY partId";
						$queryLot1 = $db->query($sql);
						$resultLot1 = $queryLot1->num_rows;
						
						if($resutLot1 == 1) //without subparts;
						{
							$sql = "SELECT workingQuantity FROM ppic_lotlist WHERE poId = ".$poIdLot." AND identifier = 1 LIMIT 1";
							$queryQty = $db->query($sql);
							if($queryQty AND $queryQty->num_rows > 0)
							{
								$resultQty = $queryQty->fetch_assoc();
								$workingQuantityPercentage = $resultQty['workingQuantity'];
								if($quantity > 0 AND $workingQuantityPercentage > 0) $totalPercentage += ($quantity / $workingQuantityPercentage);
							}	
						}
						else if($resultLot1 > 1) //with subparts;
						{
							$sql = "SELECT SUM(workingQuantity) AS sumWorkingQty FROM ppic_lotlist WHERE poId = ".$poIdLot." AND identifier = 1 GROUP BY partId";
							$queryQty = $db->query($sql);
							if($queryQty AND $queryQty->num_rows > 0)
							{
								$resultQty = $queryQty->fetch_assoc();
								$sumWorkingQty = $resultQty['sumWorkingQty'];
								if($quantity > 0 AND $sumWorkingQty > 0) $totalPercentage += ($quantity / $sumWorkingQty);
							}							
						}
					}				
				}
				//------------------------------------ END of PERCENTAGE -------------------------------------------------//
				
				// --------------------- Price and Output ----------------------------
				$price = 0; 
				$currency = 0;
				$sql ="SELECT * FROM ppic_lotlist WHERE lotNumber LIKE '".$getCPARLotNumberResult['lotNumber']."' and identifier = 1";
				$tableQueryb1 = $db->query($sql);
				if($tableQueryb1->num_rows > 0)
				{
					$tableQueryResultb1 = $tableQueryb1->fetch_assoc();
										  
					$partId = $tableQueryResultb1['partId'];
					$poId = $tableQueryResultb1['poId'];				
					/*
					$sql = "SELECT * FROM sales_pricelist where arkPartId = ".$partId;
					$tableQueryb4 = $db->query($sql);
					while($tableQueryResultb4 = $tableQueryb4->fetch_assoc())
					{ 
						$price = $tableQueryResultb4['price'];
						$currency = $tableQueryResultb4['currency'];
					}
					*/
					//if($price < 1)
					//{
					
					$sql = "SELECT * FROM sales_pricelist WHERE arkPartId = ".$partId;
					$tableQueryb3 = $db->query($sql);
					$tableQueryResultb3 = $tableQueryb3->fetch_assoc();
					$price = $tableQueryResultb3['price'];
					$currency = $tableQueryResultb3['currency'];
					
					if($price <= 0)
					{
						$sql ="SELECT * FROM sales_polist WHERE poId = ".$poId;
						$tableQueryb2 = $db->query($sql);
						if($tableQueryb2 AND $tableQueryb2)
						{
							$tableQueryResultb2 = $tableQueryb2->fetch_assoc();
								 
							$price = $tableQueryResultb2['price'];
							$currency = $tableQueryResultb2['currency'];
							$poIdpartID = $tableQueryResultb2['partId'];
							//get AREA of Ka subparts START
							$ChildareaSum=0;$ExactChildarea=0;$ChildareaCounter=0; $ChildareaError=0;
							$subpartPrice="";$ChildareaSumStr="";
							$sql3 = "SELECT childId FROM cadcam_subparts where parentId = ".$poIdpartID." and identifier=1";
							$ppicQuery3 = $db->query($sql3);
							if($ppicQuery3->num_rows > 0)
							{	
								while($ppicQueryResult3 = $ppicQuery3->fetch_assoc())
								{
									$childId=$ppicQueryResult3['childId'];
									list($partNumber_A,$partName_A,$revisionId_A,$thick_A,$item_x_A,$item_y_A,$metalType_A,$customerId_A,$treatmentId_A,$itemWeight_A,$partNote_A,$itemLength_A,$itemWidth_A,$itemHeight_A,$treatmentName_A,$matL_A,$matW_A)=identifierdetails($childId,1,1);
									if($item_x_A>0){ $childMin=$item_x_A; }
									if($item_y_A>0){ $childMax=$item_y_A; }
									if($childMin==0 or $childMax==0)
									{
										if($itemLength_A>0){ $childMin=$itemLength_A; }
										if($itemWidth_A>0){ $childMax=$itemWidth_A; }
									}
									if($childMin==0 or $childMax==0)
									{
										$ChildareaError=1;
									}
									else
									{
										$ChildareaSum=($ChildareaSum+($childMin*$childMax));
										$ChildareaSumStr=$ChildareaSumStr."+".($childMin*$childMax);
										if($ppicQueryResult3['childId']==$partId)
										{
											$ExactChildarea=($childMin*$childMax);
										}
									}	
									$ChildareaCounter++;
								}	
								
							}
							if($ExactChildarea==0 or $ChildareaSum==0)
							{
								$ChildareaError=1;
							}
							if($ChildareaError==0 and $price>0)
							{	
								$priceB=($price*($ExactChildarea/$ChildareaSum));
								$price=number_format(($price*($ExactChildarea/$ChildareaSum)), 2, '.', ',');
								$subpartPrice="<font color=green>sub</font>";
							}
							//get AREA of Ka subparts END
							if($currency==0)
							{
								$sql = "SELECT * FROM sales_pricelist WHERE arkPartId = ".$poIdpartID;
								$tableQueryb3 = $db->query($sql);
								if($tableQueryb3 AND $tableQueryb3->num_rows > 0)
								{
									$tableQueryResultb3 = $tableQueryb3->fetch_array();
									$currency = $tableQueryResultb3['currency'];
								}
							}
						}
					}
				}			
				
				if($currency == 1)
				{
					$price2 = "$";  
					$price3 = ($price*1);
				}
				else if($currency == 2)
				{
					$price2 = "Php";  
					$price3 = ($price/40); 
				}
				else if($currency == 3)
				{
					$price2 = "Yen"; 
					$price3 = ($price/120); 
				}
				else
				{
					$price2 = "$"; 
					$price3 = ($price*1);
				}
				
				$totalNGQuantity += $getCPARLotNumberResult['quantity'];
				$totalAmount += ($price3 * $getCPARLotNumberResult['quantity']);
				//~ $totalPRICE=$totalPRICE+$price3;
			}
		}
	$sqlData = "SELECT listId, cparId, lotNumber, cparQuantity, cparSection, cparIssueDate, cparDueDate, cparInfoSource, cparMaker, cparDetails, cparMoreDetails,cparDisposition, cparCause, cparSourcePerson, cparDetectProcess, cparDetectPerson, cparInterimAction, cparAnalysis, cparCorrectiveProcess, cparVerification, cparStatus FROM qc_cpar ".$mergeQuery." ORDER BY listId DESC LIMIT "
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo displayText('6-4','utf8',0,1,1);?></title>
	<meta charset="UTF-8">
	<meta name="description" content="Task List">
	<meta name="author" content="RG">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../Common Data/Templates/Bootstrap/Font Awesome/css/font-awesome.css">
	<link rel="stylesheet" href="../Common Data/Templates/Bootstrap/Bootstrap 3.3.7/Roboto Font/roboto.css">
	<link rel="stylesheet" href="../Common Data/Templates/Bootstrap/Bootstrap 3.3.7/css/bootstrap.css">
	<link rel="stylesheet" href="../Common Data/Templates/Bootstrap/Bootstrap 3.3.7/css/bootstrap-theme.css">
	<link rel="stylesheet" href="../Common Data/Templates/Bootstrap/w3css/w3.css"> 
	<!-- <link rel="stylesheet" type="text/css" href="../Common Data/Libraries/Javascript/Super Quick Table/datatables.css"> -->
	 <link rel="stylesheet" href="../Common Data/Libraries/Javascript/Bootstrap Multi-Select JS/dist/css/bootstrap-multiselect.css" type="text/css" media="all" />
	<link rel="stylesheet" href="/Common Data/Libraries/Javascript/Tiny Box/stylebox.css"/>
	<link rel="stylesheet" type="text/css" href="../Common Data/Libraries/Javascript/sweetAlert2/dist/sweetalert2.css">
	<style type="text/css">
	body
	{
		/*background: linear-gradient(to top,#128deb, white);*/
	}
	.row
	{
		margin-left: 0;
		margin-right: 0;
	}
    #myNav
    {
        background: linear-gradient(to right,#0099CC, #128deb);
        color:white;
    }
    .navbar-brand
    {
    	font-size: 35px;
    	font-weight: bold;
    }
    .zooms {
  zoom: 2;
  transform: scale(1);
  -ms-transform: scale(2);
  -webkit-transform: scale(2);
  -o-transform: scale(2);
  -moz-transform: scale(2);
  transform-origin: 0 0;
  -ms-transform-origin: 0 0;
  -webkit-transform-origin: 0 0;
  -o-transform-origin: 0 0;
  -moz-transform-origin: 0 0;
  -webkit-transform-origin: 0 0;
}
<?php
	include('rhay_style.php');
?>
</style>
</head>
<body>
	<div class="row" id="myNav" style="margin-bottom: 2px;">
		<div class="col-md-4 col-lg-4 hidden-sm hidden-xs">
			<ul class="nav navbar-nav">
             	<li><a href="../dashboard.php" style="border-radius: 5px;font-size:40px;font-family: arial" title="Home"><i class="fa fa-home"></i> </a></li>
        	</ul>
		</div>
		<div class="col-md-4 col-lg-4">
			<center><p style="color:white;font-size: 35px;font-weight: bold;font-family: arial;margin-top: 10px;"><?php echo displayText('6-4','utf8',0,1,1);?></p></center>
		</div>
		<div class="col-md-4 col-lg-4 hidden-sm hidden-xs">
			<div class="pull-right">
				<ul class="nav navbar-nav">
					<li><a href = "../6-10%20Quality%20Alert%20Software/raymond_qualityAlertInputForm.php" title="Add CPAR"><button class='w3-btn w3-round w3-green'><?php echo displayText('6-10','utf8',0,1,1); ?></button></a></li>
					<li><a href = "anthony_inputForm.php" id="addbtn" style="border-radius: 5px;font-size:40px;" title="Add CPAR"><i class="fa fa-plus" aria-hidden="true"></i></a></li>
					<li>
						<form id='exportId' method='POST' action='anthony_excel.php'></form>
						<input form='exportId' type='hidden' name='mergeQuery' value="<?php echo $mergeQuery; ?>" />
						<input form='exportId' type='hidden' name='dateStart' value="<?php echo $dateStart; ?>" />
						<input form='exportId' type='hidden' name='dateEnd' value="<?php echo $dateEnd; ?>" />
						<input form='exportId' type='hidden' name='excelExport' value="<?php echo "TRUEE"; ?>" />
						<a href = "#" id="exlBtn" style="border-radius: 5px;font-size:40px;" title="Export to Excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a></li>
             		<li><a href = "<?php echo $_SERVER['PHP_SELF']; ?>" id="refreshbtn" style="border-radius: 5px;font-size:40px;" title="Refresh"><i class="fa fa-refresh" aria-hidden="true"></i></a></li>
             		<li><button class='w3-btn w3-round w3-small w3-green' id='helpBtn'><i class='fa fa-question'></i>&emsp;<b><?php echo displayText("L3586","utf8",0,0,1); ?></b></button></li>
        		</ul>
			</div>
		</div>
		<div class="hidden-lg hidden-md">
		<center><div class="row">
				<ul class="nav navbar-nav">
					<div class="col-md-1 col-sm-1 col-xs-1"></div>
					<div class="col-md-3 col-sm-1 col-xs-1">
						<center><li><a href="../dashboard.php" style="border-radius: 5px;font-size:40px;" title="Home"><i class="fa fa-home"></i> </a></li></center>
					</div>
					<div class="col-md-3 col-sm-1 col-xs-1">
						<center><li><a href = "rhay_inputForm.php" id="addbtn" style="border-radius: 5px;font-size:40px;" title="Add CPAR"><i class="fa fa-plus" aria-hidden="true"></i></a></li></center>
					</div>
					<div class="col-md-3 col-sm-1 col-xs-1">
						<form id='exportId1' method='POST' action='anthony_excel.php'></form>
						<input form='exportId1' type='hidden' name='mergeQuery' value="<?php echo $mergeQuery; ?>" />
						<input form='exportId1' type='hidden' name='dateStart' value="<?php echo $dateStart; ?>" />
						<input form='exportId1' type='hidden' name='dateEnd' value="<?php echo $dateEnd; ?>" />
						<input form='exportId1' type='hidden' name='excelExport' value="<?php echo "TRUEE"; ?>" />
						<center><li><a href = "#" id="exlBtn1" style="border-radius: 5px;font-size:40px;" title="Export to Excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a></li></center>
					</div>
					<div class="col-md-3 col-sm-1 col-xs-1">
	             		<center><li><a href = "<?php echo $_SERVER['PHP_SELF']; ?>" id="refreshbtn" style="border-radius: 5px;font-size:40px;" title="Refresh"><i class="fa fa-refresh" aria-hidden="true"></i></a></li></center>
					</div>
					<div class="col-md-1 col-sm-1 col-xs-1"></div>
				</ul>
			</div></center>
		</div>
	</div>
	<form name="customerFilter" id="filterForm" action="" method="POST" ></form>
	<div class="row">
		<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
			<div class=" table-responsive">
				<table class="table table-bordered"  style="margin-bottom: 2px;">
					<thead  style='font-size:12px;padding:5px;'><!--background-color: #0099CC;color:white; -->
						<th class = 't_head' style="text-align: center;"><?php echo displayText('L334'); ?></th>
						<th class = 't_head' style="text-align: center;"><?php echo displayText('L335'); ?></th>
						<th class = 't_head' style="text-align: center;"><?php echo displayText('L336'); ?></th>
						<th class = 't_head' style="text-align: center;"><?php echo "More ".displayText('L336'); ?></th>
						<th class = 't_head' style="text-align: center;"><?php echo displayText('L337'); ?></th>
						<th class = 't_head' style="text-align: center;"><?php echo displayText('L338'); ?></th>
						<th class = 't_head' style="text-align: center;"><?php echo displayText('L339'); ?></th>
						<th class = 't_head' style="text-align: center;"><?php echo displayText('L340'); ?></th>
						<th class = 't_head' style="text-align: center;"><?php echo displayText('L341'); ?></th>
						<th class = 't_head' style="text-align: center;"><?php echo displayText('L342'); ?></th>
						<th class = 't_head' style="text-align: center;"><?php echo displayText('L343'); ?></th>
					</thead>
					<tbody style="font-size:12px">
						<?php
						echo "<td style='text-align: center;'>";
							echo "<input type = 'text' list = 'cparId' name = 'cparId' class = 'textboxFilter' form='filterForm' value = '".$cparId."' onchange = 'this.form.submit();' style='width:100px;'>";
							echo "<datalist id = 'cparId'>";
							$sql = "SELECT cparId FROM qc_cpar ".$mergeQuery." ORDER BY listId DESC ";
							$getcparId = $db->query($sql);
							while($getcparIdResult = $getcparId->fetch_assoc())
							{
								if($getcparIdResult['cparId'] != '')
								{
									echo "<option value = '".$getcparIdResult['cparId']."'>";
								}
							}
							echo "</datalist>";
						echo "</td>";
						// ------------------------------------------ Filter Source of Information ------------------------------------------------
						$sql = "SELECT DISTINCT cparInfoSource FROM qc_cpar ".$mergeQuery." ";
						$getInfoSource = $db->query($sql);
							echo "<td style='text-align: center;'><select name = 'cparInfoSource' onchange='this.form.submit()' form='filterForm'  style='width:100px;'>";
								echo "<option value=''>All</option>";
							while($getInfoSourceResult = $getInfoSource->fetch_assoc())
							{
								if($getInfoSourceResult['cparInfoSource'] != '')
								{
									echo "<option value='".$getInfoSourceResult['cparInfoSource']."' "; if($_POST['cparInfoSource'] == $getInfoSourceResult['cparInfoSource']){ echo "selected"; } echo ">".$getInfoSourceResult['cparInfoSource']."</option>";
								}
							}
						echo "</select></td>";
						// ------------------------------------------ Filter CPAR Details ------------------------------------------------
						$sql = "SELECT DISTINCT cparDetails FROM qc_cpar ".$mergeQuery." ";
						$getDetails = $db->query($sql);
							echo "<td style='text-align: center;'><select name = 'cparDetails' onchange='this.form.submit()' form='filterForm' style='width:100px;'>";
								echo "<option value=''>All</option>";
							while($getDetailsResult = $getDetails->fetch_assoc())
							{
								if($getDetailsResult['cparDetails'] != '')
								{
									echo "<option value='".$getDetailsResult['cparDetails']."' "; if($_POST['cparDetails'] == $getDetailsResult['cparDetails']){ echo "selected"; } echo ">".$getDetailsResult['cparDetails']."</option>";					
								}
							}
						echo "</select></td>";
						// ------------------------------------------ Filter CPAR More Details ------------------------------------------------
						$sql = "SELECT DISTINCT cparMoreDetails FROM qc_cpar ".$mergeQuery." ";
						$getDetails = $db->query($sql);
							echo "<td style='text-align: center;'><select name = 'cparMoreDetails' onchange='this.form.submit()' form='filterForm' style='width:100px;'>";
								echo "<option value=''>All</option>";
							while($getDetailsResult = $getDetails->fetch_assoc())
							{
								if($getDetailsResult['cparMoreDetails'] != '')
								{
									echo "<option value='".$getDetailsResult['cparMoreDetails']."' "; if($_POST['cparMoreDetails'] == $getDetailsResult['cparMoreDetails']){ echo "selected"; } echo ">".$getDetailsResult['cparMoreDetails']."</option>";					
								}
							}
						echo "</select></td>";
						// ------------------------------------------ Filter Analysis ------------------------------------------------
						echo "<td style='text-align: center;'><input type = 'text' name = 'analysis' class = 'textboxFilter' form='filterForm' value = '".$analysis."' onchange = 'this.form.submit();'  style='width:100px;'></td>";
					// ------------------------------------------ Filter Concern Section ------------------------------------------------
						$sql = "SELECT DISTINCT cparSection FROM qc_cpar ".$mergeQuery." ";
						$getCPARSection = $db->query($sql);
							echo "<td style='text-align: center;'><select name = 'cparSection' onchange='this.form.submit()' form='filterForm'  style='width:100px;'>";
								echo "<option value=''>All</option>";
							while($getCPARSectionResult = $getCPARSection->fetch_assoc())
							{
								if($getCPARSectionResult['cparSection'] != '')
								{
									echo "<option value='".$getCPARSectionResult['cparSection']."' "; if($_POST['cparSection'] == $getCPARSectionResult['cparSection']){ echo "selected"; } echo ">".$getCPARSectionResult['cparSection']."</option>";					
								}
							}
						echo "</select></td>";
						// ------------------------------------------ Filter Source Person ------------------------------------------------
						$sql = "SELECT DISTINCT cparSourcePerson FROM qc_cpar ".$mergeQuery." ";
						$getSourcePerson = $db->query($sql);
							echo "<td style='text-align: center;'><select name = 'cparSourcePerson' onchange='this.form.submit()' form='filterForm'  style='width:100px;'>";
								echo "<option value=''>All</option>";
							while($getSourcePersonResult = $getSourcePerson->fetch_assoc())
							{
								if($getSourcePersonResult['cparSourcePerson'] != '')
								{
									echo "<option value='".$getSourcePersonResult['cparSourcePerson']."' "; if($_POST['cparSourcePerson'] == $getSourcePersonResult['cparSourcePerson']){ echo "selected"; } echo ">".$getSourcePersonResult['cparSourcePerson']."</option>";					
								}
							}
						echo "</select></td>";
						
						// ------------------------------------------ Filter Process Detected ------------------------------------------------
						$sql = "SELECT DISTINCT cparDetectProcess FROM qc_cpar ".$mergeQuery." ";
						$getDetectProcess = $db->query($sql);
							echo "<td style='text-align: center;'><select name = 'cparDetectProcess' onchange='this.form.submit()' form='filterForm'  style='width:100px;'>";
								echo "<option value=''>All</option>";
							while($getDetectProcessResult = $getDetectProcess->fetch_assoc())
							{
								if($getDetectProcessResult['cparDetectProcess'] != '')
								{
									echo "<option value='".$getDetectProcessResult['cparDetectProcess']."' "; if($_POST['cparDetectProcess'] == $getDetectProcessResult['cparDetectProcess']){ echo "selected"; } echo ">".$getDetectProcessResult['cparDetectProcess']."</option>";					
								}
							}
						echo "</select></td>";
						
						// ------------------------------------------ Filter Person Detected ------------------------------------------------
						$sql = "SELECT DISTINCT cparDetectPerson FROM qc_cpar ".$mergeQuery." ";
						$getDetectPerson = $db->query($sql);
							echo "<td style='text-align: center;'><select name = 'cparDetectPerson' onchange='this.form.submit()' form='filterForm'  style='width:100px;'>";
								echo "<option value=''>All</option>";
							while($getDetectPersonResult = $getDetectPerson->fetch_assoc())
							{
								if($getDetectPersonResult['cparDetectPerson'] != '')
								{
									echo "<option value='".$getDetectPersonResult['cparDetectPerson']."' "; if($_POST['cparDetectPerson'] == $getDetectPersonResult['cparDetectPerson']){ echo "selected"; } echo ">".$getDetectPersonResult['cparDetectPerson']."</option>";					
								}
							}
						echo "</select></td>";
							// ------------------------------------------ Filter Issue Date ------------------------------------------------
					$sql = "SELECT DISTINCT cparIssueDate FROM qc_cpar ".$mergeQuery." ";
					$getIssueDate = $db->query($sql);
						echo "<td style='text-align: center;'><select name = 'cparIssueDate' onchange='this.form.submit()' form='filterForm' style='width:100px;'>";
							echo "<option value=''>All</option>";
						while($getIssueDateResult = $getIssueDate->fetch_assoc())
						{
							if($getIssueDateResult['cparIssueDate'] != '')
							{
								echo "<option value='".$getIssueDateResult['cparIssueDate']."' "; if($_POST['cparIssueDate'] == $getIssueDateResult['cparIssueDate']){ echo "selected"; } echo ">".$getIssueDateResult['cparIssueDate']."</option>";					
							}
						}
					echo "</select></td>";
					
					// ------------------------------------------ Filter Issue Date ------------------------------------------------
					$sql = "SELECT DISTINCT cparDueDate FROM qc_cpar ".$mergeQuery." ";
					$getDueDate = $db->query($sql);
					echo "<td style='text-align: center;'><select name = 'cparDueDate' onchange='this.form.submit()' form='filterForm' style='width:100px;'>";
						echo "<option value=''>All</option>";
					while($getDueDateResult = $getDueDate->fetch_assoc())
					{
						if($getDueDateResult['cparDueDate'] != '')
						{
							echo "<option value='".$getDueDateResult['cparDueDate']."' "; if($_POST['cparDueDate'] == $getDueDateResult['cparDueDate']){ echo "selected"; } echo ">".$getDueDateResult['cparDueDate']."</option>";					
						}
					}
					echo "</select></td>";
						?>
					</tbody>
				</table>
			</div>
			<div class="table table-responsive">
				<table class="table table-bordered">
					<thead style='font-size:12px;padding:2px;'>
						<?php
						echo "
						<th class = 't_head' style='text-align: center;''>".displayText('L113')."</th>
						<th class = 't_head'style='text-align: center;'>".displayText('L172')."</th>
						<th class = 't_head'style='text-align: center;'>".displayText('L344')."</th>
						<th class = 't_head'style='text-align: center;'>".displayText('L345')."</th>
						<th class = 't_head'style='text-align: center;'>".displayText('L346')."</th>
						<th class = 't_head'style='text-align: center;'></th>
						<th class = 't_head'style='text-align: center;'>".displayText('L3635')."</th>
						<th class = 't_head'style='text-align: center;'>".displayText('L348')."</th>
						<th class = 't_head'style='text-align: center;'>".displayText('L24')."</th>";
						?>
					</thead>
					<tbody style="font-size: 12px;">
						<?php
					echo "<td style='text-align: center;'><select name='alarmFilter' class='resizedTextbox' onchange='this.form.submit()' form='filterForm' style='width:100px;'>";
						echo "<option value=''>All</option>";					
					if($delay=='delay' or $delay=="")
					{
						echo "<option style='background-color:Red;' value='Red' "; if($_POST['alarmFilter']=="Red") { echo "selected"; } echo ">Red</option>";					
						echo "<option style='background-color:Orange;' value='Orange' "; if($_POST['alarmFilter']=="Orange") { echo "selected"; } echo ">Orange</option>";					
					}
					if($today=='today' or $today=="")
					{
						echo "<option style='background-color:Yellow;' value='Yellow' "; if($_POST['alarmFilter']=="Yellow") { echo "selected"; } echo ">Yellow</option>";					
					}
					if($advance=='advance' or $advance=="")
					{
						echo "<option style='background-color:Lightgreen;' value='Green' "; if($_POST['alarmFilter']=="Green") { echo "selected"; } echo ">Green</option>";					
						echo "<option style='background-color:Lightblue;' value='Blue' "; if($_POST['alarmFilter']=="Blue") { echo "selected"; } echo ">Blue</option>";					
					}
					echo "</select></td>";
					
						$sql = "SELECT DISTINCT cparStatus FROM qc_cpar ".$mergeQuery." ";
						$getStatus = $db->query($sql);
							echo "<td><select name = 'cparStatus' onchange='this.form.submit()'  onchange = 'this.form.submit();' style='width:100%' form='filterForm'>";
								echo "<option value=''>All</option>";
							while($getStatusResult = $getStatus->fetch_assoc())
							{
								if($getStatusResult['cparStatus'] != '')
								{
									echo "<option value='".$getStatusResult['cparStatus']."' "; if($_POST['cparStatus'] == $getStatusResult['cparStatus']){ echo "selected"; } echo ">".$getStatusResult['cparStatus']."</option>";					
								}
							}
						echo "</select></td>";
						// --------------------------------------------- Interim Color -----------------------------------------------
						echo "<td><select name='interimFilter' class='resizedTextbox' onchange='this.form.submit()' style='width:100%' form='filterForm'>";
							echo "<option value=''>All</option>";					
						if($delay=='delay' or $delay=="")
						{
							echo "<option style='background-color:Red;' value='Red' "; if($_POST['interimFilter']=="Red") { echo "selected"; } echo ">Red</option>";					
							echo "<option style='background-color:Orange;' value='Orange' "; if($_POST['interimFilter']=="Orange") { echo "selected"; } echo ">Orange</option>";					
						}
						if($today=='today' or $today=="")
						{
							echo "<option style='background-color:Yellow;' value='Yellow' "; if($_POST['interimFilter']=="Yellow") { echo "selected"; } echo ">Yellow</option>";					
						}
						if($advance=='advance' or $advance=="")
						{
							echo "<option style='background-color:Lightgreen;' value='Green' "; if($_POST['interimFilter']=="Green") { echo "selected"; } echo ">Green</option>";					
							echo "<option style='background-color:Lightblue;' value='Blue' "; if($_POST['interimFilter']=="Blue") { echo "selected"; } echo ">Blue</option>";					
						}
						echo "</select></td>";
						// --------------------------------------------- Corrective Color -----------------------------------------------
						echo "<td><select name='correctiveFilter' class='resizedTextbox' onchange='this.form.submit()' style='width:100%' form='filterForm'>";
							echo "<option value=''>All</option>";					
						if($delay=='delay' or $delay=="")
						{
							echo "<option style='background-color:Red;' value='Red' "; if($_POST['correctiveFilter']=="Red") { echo "selected"; } echo ">Red</option>";					
							echo "<option style='background-color:Orange;' value='Orange' "; if($_POST['correctiveFilter']=="Orange") { echo "selected"; } echo ">Orange</option>";					
						}
						if($today=='today' or $today=="")
						{
							echo "<option style='background-color:Yellow;' value='Yellow' "; if($_POST['correctiveFilter']=="Yellow") { echo "selected"; } echo ">Yellow</option>";					
						}
						if($advance=='advance' or $advance=="")
						{
							echo "<option style='background-color:Lightgreen;' value='Green' "; if($_POST['correctiveFilter']=="Green") { echo "selected"; } echo ">Green</option>";					
							echo "<option style='background-color:Lightblue;' value='Blue' "; if($_POST['correctiveFilter']=="Blue") { echo "selected"; } echo ">Blue</option>";					
						}
						echo "</select></td>";
						// --------------------------------------------- Verification Color -----------------------------------------------
						echo "<td><select name='verificationFilter' class='resizedTextbox' onchange='this.form.submit()' style='width:100%' form='filterForm'>";
							echo "<option value=''>All</option>";					
						if($delay=='delay' or $delay=="")
						{
							echo "<option style='background-color:Red;' value='Red' "; if($_POST['verificationFilter']=="Red") { echo "selected"; } echo ">Red</option>";					
							echo "<option style='background-color:Orange;' value='Orange' "; if($_POST['verificationFilter']=="Orange") { echo "selected"; } echo ">Orange</option>";					
						}
						if($today=='today' or $today=="")
						{
							echo "<option style='background-color:Yellow;' value='Yellow' "; if($_POST['verificationFilter']=="Yellow") { echo "selected"; } echo ">Yellow</option>";					
						}
						if($advance=='advance' or $advance=="")
						{
							echo "<option style='background-color:Lightgreen;' value='Green' "; if($_POST['verificationFilter']=="Green") { echo "selected"; } echo ">Green</option>";					
							echo "<option style='background-color:Lightblue;' value='Blue' "; if($_POST['verificationFilter']=="Blue") { echo "selected"; } echo ">Blue</option>";					
						}
						echo "</select></td>";
						?>
						<td style="text-align: center;">
							<label><input type='checkbox' onchange="this.form.submit()" name='allStatus' value='1' <?php if($allStatus==1) echo 'checked';?> form='filterForm'><?php echo displayText('L3793'); ?> </label> <!-- Show All Status -->
						</td>
						<td style="text-align: center;">
							<span><p><input type = 'date' name = 'dateStart'  value = '<?php echo $dateStart; ?>' style='font-size: 10px;' form='filterForm'> TO 
							<input type = 'date' name = 'dateEnd' style='font-size: 10px;' form='filterForm' value = '<?php echo $dateEnd; ?>'>
							<button form="filterForm" onclick="this.form.submit()" class="btn-info" title="Filter"><i class="fa fa-search"></i></button></p></span>
						</td>
						<td>
							<select name="cparDisposition" onchange="this.form.submit()" style='width:100%' form='filterForm'>
								<option></option>
								<?php
									$selectedCparDispositions = '';
									$sql = "SELECT DISTINCT(cparDisposition) FROM qc_cpar ".$mergeQuery."";
									$query = $db->query($sql);
									if($query AND $query->num_rows > 0)
									{
										while($result = $query->fetch_assoc())
										{
											$cparDisposition = $result['cparDisposition'];
											$selectedCparDispositions = ($cparDispositions == $cparDisposition) ? "selected" : "";
											echo "<option value = '".$cparDisposition."' ".$selectedCparDispositions.">".$cparDisposition."'</option>";
										}
									}
								?>
							</select>
						</td>
										<td>
							<?php
								//~ if($_SESSION['idNumber']=='0346')
								//~ {
									$cparIdArray = array();
									$sql = "SELECT cparId FROM qc_cpar ".$mergeQuery."";
									$queryCpar = $db->query($sql);
									if($queryCpar AND $queryCpar->num_rows > 0)
									{
										while($resultCpar = $queryCpar->fetch_assoc())
										{
											$cparIdArray[] = $resultCpar['cparId'];
										}
										
										/*
										$lotNumberArray = array();
										$sql = "SELECT lotNumber FROM qc_cparlotnumber WHERE cparId IN('".implode("','",$cparIdArray)."')";
										$queryCparLotNumber = $db->query($sql);
										if($queryCparLotNumber AND $queryCparLotNumber->num_rows > 0)
										{
											while($resultCparLotNumber = $queryCparLotNumber->fetch_assoc())
											{
												$lotNumberArray[] = $resultCparLotNumber['lotNumber'];
											}
										}
										
										$partIdArray = array();
										$sql = "SELECT DISTINCT partId FROM ppic_lotlist WHERE lotNumber IN('".implode("','",$lotNumberArray)."') AND identifier = 1";
										$queryLotList = $db->query($sql);
										if($queryLotList AND $queryLotList->num_rows > 0)
										{
											while($resultLotList = $queryLotList->fetch_assoc())
											{
												$partIdArray[] = $resultLotList['partId'];
											}
										}
										
										$customerIdArray = array();
										$sql = "SELECT DISTINCT customerId FROM cadcam_parts WHERE partId IN(".implode(",",$partIdArray).")";
										$queryParts = $db->query($sql);
										if($queryParts AND $queryParts->num_rows > 0)
										{
											while($resultParts = $queryParts->fetch_assoc())
											{
												$customerIdArray[] = $resultParts['customerId'];
											}
										}
										
										echo $sql = "SELECT customerId, customerAlias FROM sales_customer WHERE customerId IN(".implode(",",$customerIdArray).") ORDER BY customerAlias";
										*/
										
										$optionString = "";
										$sql = "
											SELECT e.customerId, e.customerAlias FROM qc_cparlotnumber as b
											INNER JOIN ppic_lotlist as c ON c.lotNumber = b.lotNumber AND c.identifier = 1
											INNER JOIN cadcam_parts as d ON d.partId = c.partId
											INNER JOIN sales_customer as e ON e.customerId = d.customerId
											WHERE b.cparId IN('".implode("','",$cparIdArray)."')
											GROUP BY e.customerId ORDER BY e.customerAlias
										";
										$queryCustomer = $db->query($sql);
										if($queryCustomer AND $queryCustomer->num_rows > 0)
										{
											while($resultCustomer = $queryCustomer->fetch_assoc())
											{
												$selectedCustomer = ($customerId == $resultCustomer['customerId']) ? "selected" : "";
												$optionString .= "<option value = '".$resultCustomer['customerId']."' ".$selectedCustomer.">".$resultCustomer['customerAlias']."</option>";
											}
										}									
									}
									?>
									<select name="customerId" onchange="this.form.submit()" style='width:100%' form='filterForm'>
										<option></option>
										<?php echo $optionString;?>
									</select>
						</td>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="row">
	<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
		<p style="margin-top:-40px; font-size: 13px;"><?php echo "".displayText('L41').": ".$getTotalRecordsResult['totalRecords']; ?></p> 
		<div class=" table-responsive" style="margin-top:-10px;">
		<table class='mainTableId table-bordered table-striped'>
			<thead  style='color:white;background-color: #0099CC;font-size:11px;padding:2px;'>
			<?php
				echo "<th style='text-align:center'>".displayText('L334')."</th>";
				echo "<th style='text-align:center'>".displayText('L24')."</th>";
				if($rhayFlag == 1)
				{
					echo "<th style='text-align:center'>".displayText('L28')."</th>";
                }
                echo "<th style='text-align:center'>".displayText('L566')."</th>";
				echo "<th style='text-align:center'>".displayText('L45')."</th>";
				echo "<th style='text-align:center'>".displayText('L31')."</th>";
				echo "<th  style='text-align:center'>".displayText('L351')."</th>";
				echo "<th  style='text-align:center'>".displayText('L352')."</th>";
				echo "<th  style='text-align:center'>".displayText('L353')."</th>";
				echo "<th style='text-align:center'>".displayText('L267')."</th>";
				echo "<th  style='text-align:center'>".displayText('L354')."</th>";
				echo "<th  style='text-align:center'>".displayText('L355')."</th>";
				echo "<th style='text-align:center'>".displayText('L336')."</th>";
				echo "<th  style='text-align:center'>".displayText('L1330')."</th>";
				echo "<th  style='text-align:center'>".displayText('L348')."</th>";
				echo "<th  style='text-align:center'>".displayText('L337')."</th>";
				echo "<th  style='text-align:center'>".displayText('L338')."</th>";
				//echo "<th width=50>Source Person</th>";											
				echo "<th  style='text-align:center'>".displayText('L340')."</th>";
				echo "<th  style='text-align:center'>".displayText('L341')."</th>";
				echo "<th style='text-align:center'>".displayText('L342')."</th>";
				//echo "<th width=50>Due Date</th>";
				echo "<th style='text-align:center'>".displayText('L113')."</th>";
				echo "<th  style='text-align:center'>".displayText('L344')."</th>";
				echo "<th  style='text-align:center'>".displayText('L356')."</th>";
				echo "<th style='text-align:center'>".displayText('L357')."</th>";
				//echo "<th width=50>Date Diff</th>";
				echo "<th style='text-align:center'>".displayText('L172')."</th>";
				echo "<th style='text-align:center'>".displayText('L1120')."</th>";
			?>
			</thead>
				<tbody style="font-size:10px;">
				</tbody>
				<tfoot  style='color:white;background-color: #0099CC;font-size:10px;'>
					<th></th>
					<th></th>
					<th></th>
					<?php
						if($rhayFlag == 1)
					{
						echo "<th></th>";
					}
					?>
					<th></th>
					<th><?php echo $totalNGQuantity; //qty;?> </th>
					<th><?php echo $totalQuantity; //total qty delivered;?> </th>
					<th><?php echo round($totalPercentage, 2); //%;?> </th>
					<th><?php echo number_format($totalDppm); //dppm?> </th>
					<th>$<?php echo round($totalAmount, 2); //price;?> </th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tfoot>
		</table>
		</div>
	</div>
	<form action ="rhay_systemFieldlinkEXEL.php" method="POST" id="excelForm">
		<input type="hidden" name="sqlData" value = "<?php echo $sqlExcel ?>" form="excelForm">
	</form>
	</div>
	<div id='modal-izi-help'><span class='izimodal-content-help'></span></div>
</body>
<script src="../Common Data/Libraries/Javascript/jQuery 3.1.1/jquery-3.1.1.js"></script>
<script src="../Common Data/Libraries/Javascript/jQuery 3.1.1/bootstrap.min.js"></script>
<script src="../Common Data/Libraries/Javascript/sweetAlert2/dist/sweetalert2.min.js"></script>
<script type="text/javascript" src="/Common Data/Libraries/Javascript/Tiny Box/tinybox.js"></script>
<script src="../Common Data/Libraries/Javascript/Bootstrap Multi-Select JS/dist/js/bootstrap-multiselect.js"></script>
<script src="../Common Data/Libraries/Javascript/Super Quick Table/datatables.min.js"></script>
<link rel="stylesheet" href="../Common Data/Libraries/Javascript/iziModal-master/css/iziModal.css" />
<script src="../Common Data/Libraries/Javascript/iziModal-master/js/iziModal.js"></script>
<link rel="stylesheet" href="../Common Data/Libraries/Javascript/iziToast-master/dist/css/iziToast.css" />
<script src="../Common Data/Libraries/Javascript/iziToast-master/dist/js/iziToast.js"></script>
<script>
	$(document).ready(function(){
		var sqlData = "<?php echo $sqlData?>";
		var totalRecords = "<?php echo $getTotalRecordsResult['totalRecords']?>";
		console.log(sqlData);
		var rhayFlag = "<?php echo $rhayFlag?>";
		var dataTable = $(".mainTableId").DataTable({
			"processing"    : true,
			"ordering"      : true,
			"serverSide"    : true,
			"searching"     : false,
			"bInfo" 		: false,
			"bLengthChange": false,
			"ajax" 			: {
				url		: 	"rhay_cparSummaryAJAX.php",
				method 	: 	"post",
				data 	: 	{
					"sqlData"		: 	sqlData,
					"totalRecords"	: 	totalRecords
				},
				error 	: function(data)
				{
					$(".mainTableId-error").html("");
					$(".mainTableId").append('<tbody class="mainTableId-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
					$(".mainTableId_processing").css("display","none");
					console.log(data);
				}
			},			
			"createdRow": function( row, data, index ) {
				$('td:eq(20)', row).css('text-align','center');
				if(rhayFlag == 1)
				{
					if(data[19] == "Yellow")
					{
						$('td:eq(19)', row).css('background-color','lightyellow');
					}
					if(data[19] == "Blue")
					{
						$('td:eq(19)', row).css('background-color','lightblue');
					}
					if(data[19] == "Green")
					{
						$('td:eq(19)', row).css('background-color','lightgreen');
					}
					if(data[19] == "Orange")
					{
						$('td:eq(19)', row).css('background-color','orange');
					}
					if(data[19] == "Red")
					{
						$('td:eq(19)', row).css('background-color','pink');
					}
					if(data[20] == 0)
					{
						$('td:eq(20)', row).css('background-color','lightyellow');
					}
					else if(data[20] > 2)
					{
						$('td:eq(20)', row).css('background-color','lightblue');
					}
					else if(data[20] >= 1)
					{
						$('td:eq(20)', row).css('background-color','lightgreen');
					}
					else if(data[20] < 0 && data[20] >= -4 )
					{
						$('td:eq(20)', row).css('background-color','orange');
					}

					if(data[21] <= 0 && data[21]  >= -70)
					{
						$('td:eq(21)', row).css('background-color','lightyellow');
					}
					else if(data[21] > 2)
					{
						$('td:eq(21)', row).css('background-color','lightblue');
					}
					else if(data[21] >= 1)
					{
						$('td:eq(21)', row).css('background-color','lightgreen');
					}
					else if(data[21]  <= -8 && data[21]  >= -11)
					{
						$('td:eq(21)', row).css('background-color','orange');
					}
					else
					{
						$('td:eq(21)', row).css('background-color','pink');
					}

					if(data[22] <= 0 && data[22] >= -30)
					{
						$('td:eq(22)', row).css('background-color','lightyellow');
					}
					else if(data[22] > 2)
					{
						$('td:eq(22)', row).css('background-color','lightblue');
					}
					else if(data[22] >= 1)
					{
						$('td:eq(22)', row).css('background-color','lightgreen');
					}
					else if(data[22]<= -31 && data[22] >= -34)
					{
						$('td:eq(22)', row).css('background-color','orange');
					}
					else
					{
						$('td:eq(22)', row).css('background-color','red');
					}
				}
				else
				{
					if(data[18] == "Yellow")
					{
						$('td:eq(18)', row).css('background-color','lightyellow');
					}
					if(data[18] == "Blue")
					{
						$('td:eq(18)', row).css('background-color','lightblue');
					}
					if(data[18] == "Green")
					{
						$('td:eq(18)', row).css('background-color','lightgreen');
					}
					if(data[18] == "Orange")
					{
						$('td:eq(18)', row).css('background-color','orange');
					}
					if(data[18] == "Red")
					{
						$('td:eq(18)', row).css('background-color','pink');
					}
					if(data[19] == 0)
					{
						$('td:eq(19)', row).css('background-color','lightyellow');
					}
					else if(data[19] > 2)
					{
						$('td:eq(19)', row).css('background-color','lightblue');
					}
					else if(data[19] >= 1)
					{
						$('td:eq(19)', row).css('background-color','lightgreen');
					}
					else if(data[19] < 0 && data[19] >= -4 )
					{
						$('td:eq(19)', row).css('background-color','orange');
					}

					if(data[20] <= 0 && data[20]  >= -7)
					{
						$('td:eq(20)', row).css('background-color','lightyellow');
					}
					else if(data[20] > 2)
					{
						$('td:eq(20)', row).css('background-color','lightblue');
					}
					else if(data[20] >= 1)
					{
						$('td:eq(20)', row).css('background-color','lightgreen');
					}
					else if(data[20]  <= -8 && data[20]  >= -11)
					{
						$('td:eq(20)', row).css('background-color','orange');
					}
					else
					{
						$('td:eq(20)', row).css('background-color','pink');
					}

					if(data[21] <= 0 && data[21] >= -30)
					{
						$('td:eq(21)', row).css('background-color','lightyellow');
					}
					else if(data[21] > 2)
					{
						$('td:eq(21)', row).css('background-color','lightblue');
					}
					else if(data[21] >= 1)
					{
						$('td:eq(21)', row).css('background-color','lightgreen');
					}
					else if(data[21]<= -31 && data[21] >= -34)
					{
						$('td:eq(21)', row).css('background-color','orange');
					}
					else
					{
						$('td:eq(21)', row).css('background-color','red');
					}
				}
				},
			language	: {
				processing	: "<div class='spinner-border' role='status'><span class='sr-only'>Loading...</span></div>"
			},	
			scrollY     	: 400,
            scrollX     	: false,
            scrollCollapse	: false,
             autoWidth:         true,
            scroller    	: {
            	loadingIndicator    : true
            },
            stateSave   	: false
        });

        $('#exlBtn').on('click',function(){
        	$('#exportId').submit();
        })
       $('#exlBtn1').on('click',function(){
        	$('#exportId1').submit();
        })
        
		$("#helpBtn").click(function(){
			$("#modal-izi-help").iziModal({
				title                   : '<i class="fa fa-info"></i>&emsp;<?php echo displayText("L3586","utf8",0,0,1);?>',
				headerColor             : '#1F4788',
				subtitle                : '<b><?php echo strtoupper(date('F d, Y'));?></b>',
				width                   : 800,
				fullscreen              : false,
				transitionIn            : 'comingIn',
				transitionOut           : 'comingOut',
				padding                 : 20,
				radius                  : 0,
				top                     : 10,
				restoreDefaultContent   : true,
				closeOnEscape           : true,
				closeButton             : true,
				overlayClose            : false,
				onOpening               : function(modal){
											modal.startLoading();
											// alert(assignedTo);
											$.ajax({
												url         : '../Common Software/raymond_softwareHelpInfo.php',
												type        : 'POST',
												data        : {
																	type      : 1,
																	displayId   : '6-4'
												},
												success     : function(data){
																$( ".izimodal-content-help" ).html(data);
																modal.stopLoading();
												}
											});
										},
				onClosed                : function(modal){
											$("#modal-izi-help").iziModal("destroy");
							} 
			});

			$("#modal-izi-help").iziModal("open");
		});
        
         });
</script>
