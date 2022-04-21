<?php
SESSION_START();
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
set_include_path($path);	
include('PHP Modules/mysqliConnection.php');
ini_set("display_errors", "on");
include('classes/fpdf.php');
include('PHP Modules/gerald_functions.php');
include('PHP Modules/rose_prodfunctions.php');
include('PHP Modules/anthony_retrieveText.php');
include('PHP Modules/anthony_wholeNumber.php');
$queryLimit = 50;

if($_POST)
{
	// -------------------------------- Initialize -----------------------------
	$cparId = $_POST['cparId'];
	$cparInfoSource = $_POST['cparInfoSource'];
	$cparDetails = $_POST['cparDetails'];
	$analysis = $_POST['analysis'];
	$cparSection = $_POST['cparSection'];
	$cparSourcePerson = $_POST['cparSourcePerson'];
	$cparDetectProcess = $_POST['cparDetectProcess'];
	$cparDetectPerson = $_POST['cparDetectPerson'];
	$cparIssueDate = $_POST['cparIssueDate'];
	$cparDueDate = $_POST['cparDueDate'];
	$cparStatus = $_POST['cparStatus'];
	$alarmFilter = $_POST['alarmFilter'];
	$interimFilter = $_POST['interimFilter'];
	$correctiveFilter = $_POST['correctiveFilter'];
	$verificationFilter = $_POST['verificationFilter'];
	$cparDisposition = $_POST['cparDisposition'];
	$customerId = $_POST['customerId'];
	$dateStart = trim($_POST['dateStart']);
	$dateEnd = trim($_POST['dateEnd']);
	$advance = $_POST['advance'];
	$today = $_POST['today'];
	$delay = $_POST['delay'];
	$month = '';
	$year = '';
	//~ $month = $_POST['month'];
	//~ $year = $_POST['year'];
	
	// -------------------------------- Advance Today Delay -------------------------------------
	$advanceTodayDelay = '';
	if(isset($advance) AND $advance == 1)
	{
		$advanceTodayDelay = "AND cparDueDate > '".date('Y-m-d')."' AND cparStatus = 'Open'";
	}
	else if(isset($today) AND $today == 1)
	{
		$advanceTodayDelay = "AND cparDueDate = '".date('Y-m-d')."' AND cparStatus = 'Open'";
	}
	else if(isset($delay) AND $delay == 1)
	{
		$advanceTodayDelay = "AND cparDueDate < '".date('Y-m-d')."' AND cparStatus = 'Open'";
	}
	
	// -------------------------------- Filter Date -------------------------------------
	//~ if($month < 10 AND strlen($month) == 1)
	//~ {
		//~ $month = "0".$month;
	//~ }
		//~ 
	//~ if(isset($month) OR isset($year))
	//~ {
		//~ $filterIssueDate = "AND cparIssueDate LIKE '%".$year."-".$month."%' ";
	//~ }
	//~ else
	//~ {
		//~ $filterIssueDate = "AND cparIssueDate LIKE '%".$year."-".$month."%' ";
	//~ }
	
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
	
	$extraQuery = "";
	if($cparDisposition != '') $extraQuery = "AND cparDisposition LIKE '".$cparDisposition."'";	
	
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
	
	$mergeQuery = "WHERE cparId LIKE '%".$cparId."%' AND cparInfoSource LIKE '%".$cparInfoSource."%' AND cparDetails LIKE '%".$cparDetails."%' AND cparAnalysis LIKE '%".$analysis."%' AND cparSection LIKE '%".$cparSection."%' AND cparIssueDate LIKE '%".$cparIssueDate."%' AND cparDueDate LIKE '%".$cparDueDate."%' AND cparSourcePerson LIKE '%".$cparSourcePerson."%' AND cparDetectProcess LIKE '%".$cparDetectProcess."%' AND cparDetectPerson LIKE '%".$cparDetectPerson."%' AND cparStatus LIKE '%".$cparStatus."%' ".$advanceTodayDelay." ".$alarmFilterClause." ".$interimFilterClause." ".$correctiveFilterClause." ".$verificationFilterClause." ".$filterIssueDate." ".$extraQuery." ".$cparQuery;// AND cparIssueDate LIKE '%".date("Y-m-")."%' 
	
	//sanitize post value
	$group_number = filter_var($_POST["group_no"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	
	//throw HTTP error if group number is not valid
	if(!is_numeric($group_number))
	{
		header('HTTP/1.1 500 Invalid number!');
		exit();
	}
	
	//get current starting point of records
	$queryPosition = ($group_number * $queryLimit);
		
	//Limit our results within a specified range.
	
	$i=0;	
	//$daysOfMonth = ("t");
	// ----------------------------------------------------- Execute Query --------------------------------------------------
	$sql = "SELECT listId, cparId, lotNumber, cparQuantity, cparSection, cparIssueDate, cparDueDate, cparInfoSource, cparMaker, cparDetails, cparMoreDetails,cparDisposition, cparCause, cparSourcePerson, cparDetectProcess, cparDetectPerson, cparInterimAction, cparAnalysis, cparCorrectiveProcess, cparVerification, cparStatus FROM qc_cpar ".$mergeQuery." ORDER BY cparIssueDate DESC LIMIT ".$queryPosition.", ".$queryLimit;
	//echo $sql."<br>";
	$getCPAR = $db->query($sql);

	while($getCPARResult = $getCPAR->fetch_assoc())
	{
		$cparDisposition = $getCPARResult['cparDisposition'];
		
		$extendQuery = '';
		$sql = "SELECT listId,cparId, lotNumber, quantity, prsNumber FROM qc_cparlotnumber WHERE cparId LIKE '".$getCPARResult['cparId']."' AND status != 2 ".$cparLotQuery."";
		if($customerId!='')
		{
			$sql = "
				SELECT d.cparId, d.lotNumber, d.quantity, d.prsNumber,d.listId FROM sales_customer as a
				INNER JOIN cadcam_parts as b ON b.customerId = a.customerId
				INNER JOIN ppic_lotlist as c ON c.partId = b.partId AND identifier = 1
				INNER JOIN qc_cparlotnumber as d ON d.lotNumber = c.lotNumber AND d.cparId LIKE '".$getCPARResult['cparId']."' AND d.status != 2 
				WHERE a.customerId = ".$customerId."
			";
		}
		$getCPARLotNumber = $db->query($sql);
		while($getCPARLotNumberResult = $getCPARLotNumber->fetch_array())
		{
			
			$cparId = $getCPARLotNumberResult['cparId'];
			$cparLotNumber = $getCPARLotNumberResult['lotNumber'];
			$quantity = $getCPARLotNumberResult['quantity'];
			$prsNumber = $getCPARLotNumberResult['prsNumber'];
			$cparLotListId = $getCPARLotNumberResult['listId'];

			//----------------------------- PAUL ----------------------------------------------------------//
			if(strstr($cparId, "CPAR-CUS") !== FALSE) $extendQuery = "AND updateStatus != 2";
			else if(strstr($cparId, "CPAR-SUB") !== FALSE) $extendQuery = "AND updateStatus = 2";
			else $extendQuery = '';
			
			$totalDeliveredQty = $totalPercentage = $dppm = 0;
			$sql = "SELECT SUM(Quantity) AS sumQty FROM purchasing_drcustomer WHERE lotNumber LIKE '".$getCPARLotNumberResult['lotNumber']."' ".$extendQuery."";
			$queryDr = $db->query($sql);
			if($queryDr AND $queryDr->num_rows > 0)
			{
				$resultDr = $queryDr->fetch_assoC();
				$totalDeliveredQty = $resultDr['sumQty']; //total delivered qty;
				
				if($quantity > 0 AND $totalDeliveredQty > 0) $dppm = (($quantity * 1000000) / $totalDeliveredQty);
			}
			
			//------------------------------------ PERCENTAGE -------------------------------------------------//
			if(strstr($cparId, "CPAR-CUS") !== FALSE OR strstr($cparId, "CPAR-SUB") !== FALSE)
			{
				if($quantity > 0 AND $totalDeliveredQty > 0) $totalPercentage = ($quantity / $totalDeliveredQty);
			}
			else if(strstr($cparId, "CPAR-INT") !== FALSE)
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
							if($quantity > 0 AND $workingQuantityPercentage > 0) $totalPercentage = ($quantity / $workingQuantityPercentage);
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
							if($quantity > 0 AND $sumWorkingQty > 0) $totalPercentage = ($quantity / $sumWorkingQty);
						}							
					}
				}				
			}
			//------------------------------------ END of PERCENTAGE -------------------------------------------------//
			
			//-------------------------- DPPM ---------------------//
			//~ if($quantity > 0 AND $totalDeliveredQty > 0) $dppm = (($quantity * 1000000) / $totalDeliveredQty);
			//-------------------- END of DPPM ---------------------//
			
			//----------------------------- END of PAUL ----------------------------------------------------------//
			if($getCPARResult['cparId'] AND $getCPARLotNumberResult['cparId'])
			{
				if(($i++)%2==0)
				{
					echo "<tr class='odd'>";
				}
				else
				{
					echo "<tr>";
				}
					$customerAlias = "N/A";
					// --------------------------------- Ace: Retrieve Customer Data -----------------------------------------
					$sql = "SELECT poId, partId FROM ppic_lotlist WHERE lotNumber = '".$getCPARLotNumberResult['lotNumber']."'";
					$poIdQuery = $db->query($sql);
					if($poIdQuery->num_rows > 0)
					{					
						$poIdQueryResult = $poIdQuery->fetch_assoc();
						$partId = $poIdQueryResult['partId'];					
						$sql = "SELECT customerId FROM sales_polist WHERE poId = ".$poIdQueryResult['poId'];
						$customerIdQuery = $db->query($sql);
						if($customerIdQuery->num_rows > 0)
						{
							$customerIdQueryResult = $customerIdQuery->fetch_assoc();		
							$sql = "SELECT customerAlias FROM sales_customer WHERE customerId = ".$customerIdQueryResult['customerId'];
							$customerAliasQuery = $db->query($sql);
							$customerAliasQueryResult = $customerAliasQuery->fetch_assoc();	
							
							$customerAlias = $customerAliasQueryResult['customerAlias'];
							
						}
					}
				
				
					$totalAmount = 0;
					$totalPercentage=round($totalPercentage,2);
					$dppm=number_format($dppm);
					if($totalPercentage == 0) $totalPercentage = '';
					if($dppm == 0) $dppm = '';
					
					echo "<td width='auto'>".$getCPARLotNumberResult['cparId']."</td>";
					echo "<td width='auto'>".$customerAlias."</td>";
					if($_GET['country'] == 2 OR $_SESSION['idNumber'] == '0412')
					{
						$sql = "SELECT partNumber FROM cadcam_parts WHERE partId = ".$partId;
						$queryPartNumber = $db->query($sql);
						if($queryPartNumber AND $queryPartNumber->num_rows > 0)
						{
							$resultPartNumber =$queryPartNumber->fetch_assoc();
							$partNumber = $resultPartNumber['partNumber'];
						}
						echo "<td width='auto'>".$partNumber."</td>";
					}
					//echo "<td width='auto'></a></td>";
					//yannie 05-04-18
					echo "<td width='auto'>
					<a target ='_blank' href='../16 Lot Details Management Software/ace_lotDetails.php?submitButton=SUBMIT&inputLot=".$getCPARLotNumberResult['lotNumber']."'>".$getCPARLotNumberResult['lotNumber']."</a>";
					if(strstr($cparId, "CPAR-CUS") !== FALSE)
					{
					echo "
					<br>
					<a target ='_blank' href='../16 Lot Details Management Software/ace_lotDetails.php?submitButton=SUBMIT&inputLot=".$getCPARLotNumberResult['prsNumber']."'>".$getCPARLotNumberResult['prsNumber']."</a>";
					}
					echo "</td>";
					//end
					echo "<td width='50' align=right>".$getCPARLotNumberResult['quantity']."</td>";
					echo "<td width='auto' align=right>".$totalDeliveredQty."</td>";
					echo "<td width='auto' align=right>".$totalPercentage."</td>";
					echo "<td  width='150' align=right>".$dppm."</td>";
					
					// --------------------- Price and Output ----------------------------
					$price = 0; 
					$currency = 0;
					$sql ="SELECT * FROM ppic_lotlist WHERE lotNumber LIKE '".$getCPARLotNumberResult['lotNumber']."' and identifier = 1";
					$tableQueryb1 = $db->query($sql);
					if($tableQueryb1->num_rows > 0)
					{
						$tableQueryResultb1 = $tableQueryb1->fetch_array();
												  
						$partId = $tableQueryResultb1['partId'];
						$poId = $tableQueryResultb1['poId'];
						
						$sql = "SELECT * FROM sales_pricelist where arkPartId = ".$partId;
						$tableQueryb4 = $db->query($sql);
						$tableQueryResultb4 = $tableQueryb4->fetch_array();
						$price = $tableQueryResultb4['price'];
						$currency = $tableQueryResultb4['currency'];
						
						
						if($price <= 0)
						{
							
							$sql ="SELECT * FROM sales_polist WHERE poId = ".$poId;
							$tableQueryb2 = $db->query($sql);
							$tableQueryResultb2 = $tableQueryb2->fetch_array();
							 
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
							$tableQueryResultb3 = $tableQueryb3->fetch_array();
							$currency = $tableQueryResultb3['currency'];
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
						$price2 = "P";  
						$price3 = ($price/40); 
					}
					else if($currency == 3)
					{
						$price2 = "Y"; 
						$price3 = ($price/120); 
					}
					else
					{
						$price2 = "$"; 
						$price3 = ($price*1);
					}
					
					$totalAmount = ($price3 * $getCPARLotNumberResult['quantity']);
					// --------------------- End Price and Output ----------------------------
					
					//echo "<td width='150' align=right>".$subpartPrice."~".$ChildareaSumStr."=".$ExactChildarea."/".$ChildareaSum."~".$priceB.($price2."".$price)."</td>";
					//echo "<td width='150' align=right>".$subpartPrice."~".$priceB.($price2."".$price)."</td>";
					//echo "<td width='150' align=right>".$subpartPrice.($price2."".$price)."</td>";
					echo "<td width='150' align=right>".($price2."".$price)."</td>";
					echo "<td width='150' align=right>$".number_format($totalAmount,4,'.',',')."</td>";
					echo "<td width='auto'>".$getCPARResult['cparInfoSource']."</td>";
					echo "<td width='auto'>".$getCPARResult['cparDetails']."</td>";
					echo "<td width='auto'>".$getCPARResult['cparMoreDetails']."</td>";
					
						if(strpos($cparDisposition, 'Scrap') !== false)
						{
						echo "<td width='auto'>Scrap</td>";
						}
						else if(strpos($cparDisposition, 'Return') !== false)
						{
						echo "<td width='auto'>RTS</td>";
						}
						else
						{
						echo "<td width='auto'>".$cparDisposition."</td>";
						}
					//echo "<td width='auto'>".$getCPARResult['cparAnalysis']."</td>";
						if(strpos($getCPARResult['cparAnalysis'], 'Method') !== false)
						{
						echo "<td width='auto'>Method</td>";
						}
						else
						{
						echo "<td width='auto'>".$getCPARResult['cparAnalysis']."</td>";
						}
					echo "<td width='auto'>".$getCPARResult['cparSection']."</td>";
					//echo "<td width=50 align=right>".$getCPARResult['cparSourcePerson']."</td>";
					echo "<td width='auto'>".$getCPARResult['cparDetectProcess']."</td>";
					echo "<td width='auto'>".$getCPARResult['cparDetectPerson']."</td>";
					echo "<td width='auto' align=right>".$getCPARResult['cparIssueDate']."</td>";
					//echo "<td width=50 align=right>".$getCPARResult['cparDueDate']."</td>";
					
					$dateToday = date_create(date('Y-m-d'));
					$dueDate = date_create($getCPARResult['cparDueDate']);
					$diff = date_diff($dateToday, $dueDate);
					$value = $diff->format('%R%a');
					if($value == 0)
					{
						if($getCPARResult['cparStatus'] == 'Closed')
						{
							echo "<td width='auto' align='center'>N / A</td>";
							//echo "<td width='50'  align='center'>N / A</td>";
						}
						else
						{
							echo "<td width='auto' align='center' style = 'background-color:lightyellow;'>Yellow</td>";
							//echo "<td width='50'  align='center'>".$value."</td>";
						}
					}
					else if($value > 2)
					{
						if($getCPARResult['cparStatus'] == 'Closed')
						{
							echo "<td width='auto' align='center'>N / A</td>";
							//echo "<td width='50'  align='center'>N / A</td>";
						}
						else
						{
							echo "<td width='auto' align='center' style = 'background-color:lightblue;'>Blue</td>";
							//echo "<td width='50'  align='center'>".$value."</td>";
						}
					}
					else if($value >= 1)
					{
						if($getCPARResult['cparStatus'] == 'Closed')
						{
							echo "<td width='auto' align='center'>N / A</td>";
							//echo "<td width='50'  align='center'>N / A</td>";
						}
						else
						{
							echo "<td width='auto' align='center' style = 'background-color:lightgreen;'>Green</td>";
							//echo "<td width='50'  align='center'>".$value."</td>";
						}
					}
					else if($value < 0 && $value >= -4)
					{
						if($getCPARResult['cparStatus'] == 'Closed')
						{
							echo "<td width='auto' align='center'>N / A</td>";
							//echo "<td width='50'  align='center'>N / A</td>";
						}
						else
						{
							echo "<td width='auto' align='center' style = 'background-color:orange;'>Orange</td>";
							//echo "<td width='50'  align='center'>".$value."</td>";
						}
					}
					else
					{				
						if($getCPARResult['cparStatus'] == 'Closed')
						{
							echo "<td width='auto' align='center'>N / A</td>";
							//echo "<td width='50'  align='center'>N / A</td>";
						}
						else
						{
							echo "<td width='auto' align='center' style = 'background-color:pink;'>Red</td>";
							//echo "<td width='50'  align='center'>".$value."</td>";
						}
					}
					
					$detectDate = date_create($getCPARResult['cparIssueDate']);
					$diff1 = date_diff($dateToday, $detectDate);
					$value1 = $diff1->format('%R%a');
					
					// ************************************* Interim Alert ****************************************
					if($getCPARResult['cparInterimAction'] == '')
					{
						if($value1 == 0)
						{
							echo "<td width='auto' align=center style = 'background-color:lightyellow;'>".$value1."</td>";
						}
						else if($value1 > 2)
						{
							echo "<td width='auto' align=center style = 'background-color:lightblue;'>".$value1."</td>";
						}
						else if($value1 >= 1)
						{
							echo "<td width='auto' align=center style = 'background-color:lightgreen;'>".$value1."</td>";
						}
						else if($value1 < 0 && $value1 >= -4)
						{
							echo "<td width='auto' align=center style = 'background-color:orange;'>".$value1."</td>";
						}
						else
						{
							echo "<td width='auto' align=center style = 'background-color:pink;'>".$value1."</td>";
						}
					}
					else
					{
						echo "<td width='auto' align=center>Ok</td>";
					}
					
					// ************************************* Corrective Alert ****************************************
					if($getCPARResult['cparCorrectiveProcess'] == '')
					{
						if($value1 <= 0 && $value1 >= -7)
						{
							echo "<td width='50' align=center style = 'background-color:lightyellow;'>".$value1."</td>";
						}
						else if($value1 > 2)
						{
							echo "<td width='50' align=center style = 'background-color:lightblue;'>".$value1."</td>";
						}
						else if($value1 >= 1)
						{
							echo "<td width='50' align=center style = 'background-color:lightgreen;'>".$value1."</td>";
						}
						else if($value1 <= -8 && $value1 >= -11)
						{
							echo "<td width='50' align=center style = 'background-color:orange;'>".$value1."</td>";
						}
						else
						{
							echo "<td width='50' align=center style = 'background-color:pink;'>".$value1."</td>";
						}
					}
					else
					{
						echo "<td width='50' align=center>Ok</td>";
					}
					
					// ************************************* Verification Alert ****************************************
					if($getCPARResult['cparVerification'] == '')
					{
						if($value1 <= 0 && $value1 >= -30)
						{
							echo "<td width='auto' align=center style = 'background-color:lightyellow;'>".$value1."</td>";
						}
						else if($value1 > 2)
						{
							echo "<td width='auto' align=center style = 'background-color:lightblue;'>".$value1."</td>";
						}
						else if($value1 >= 1)
						{
							echo "<td width='auto' align=center style = 'background-color:lightgreen;'>".$value1."</td>";
						}
						else if($value1 <= -31 && $value1 >= -34)
						{
							echo "<td width='auto' align=center style = 'background-color:orange;'>".$value1."</td>";
						}
						else
						{
							echo "<td width='auto' align=center style = 'background-color:pink;'>".$value1."</td>";
						}
					}
					else
					{
						echo "<td width='auto' align=center>Ok</td>";
					}
					
					echo "<td width='auto' align = center>".$getCPARResult['cparStatus']."</td>";
					echo "<td width='auto' align=right>";
						if($_SESSION['userID'] == 'ariel' OR $_SESSION['userID'] == 'isabel' OR $_SESSION['userID'] == 'freya' OR $_SESSION['userType'] == '0' OR $_SESSION['userType'] == '10' AND (isset($_SESSION['userID'])))
						{
							echo "<a href = 'anthony_cparEdit.php?cparId=".$getCPARResult['listId']."'><img src='images/edit1.png' width='18' height='18' alt='Edit' title='Edit'></a>";
						}
						if ($getCPARResult['cparIssueDate'] < '2018-05-03')
						{
							echo "<a onclick = \"window.open('anthony_converter.php?cparId=".$getCPARResult['listId']."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/print.png' width='18' height='18' alt='Print' title='Print'></a>";
						}
						elseif ($getCPARResult['cparIssueDate'] >= '2018-05-03' AND  $getCPARResult['cparIssueDate']<= '2018-10-04')
						{
							echo "<a onclick = \"window.open('anthony_converterV3.php?cparId=".$getCPARResult['listId']."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/print.png' width='18' height='18' alt='Print' title='Print'></a>";
						}
						else
						{
							echo "<a onclick = \"window.open('pipz_correctiveActionReportPdf.php?listId=".$cparLotListId."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/print.png' width='18' height='18' alt='Print' title='Print'></a>";
						}
							
						$location = $_SERVER['DOCUMENT_ROOT']."/Document Management System/CPAR Folder/".$getCPARResult['cparId'].".pdf";
						if(file_exists($location))
						{
							echo "<a onclick = \"window.open('anthony_viewPDF.php?cparId=".$getCPARResult['listId']."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/view1.png' width='18' height='18' alt='Print' title='Print'></a>";
						}
						else
						{
							$location = "../../Document Management System/CPAR Folder/".$getCPARResult['cparId'].".jpg";
							if(file_exists($location))
							{
								echo "<a onclick = \"window.open('".$location."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/view1.png' width='18' height='18' alt='Print' title='Print'></a>";
							}
							else
							{
								$location = "../../Document Management System/CPAR Folder/".$getCPARResult['cparId']."(".$cparLotNumber.")".".pdf";
								if(file_exists($location))
								{
									echo "<a onclick = \"window.open('".$location."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/view1.png' width='18' height='18' alt='Print' title='Print'></a>";
								}
								else
								{
									$location = "../../Document Management System/CPAR Folder/".$getCPARResult['cparId']."(".$cparLotNumber.")".".jpg";
									if(file_exists($location))
									{
										echo "<a onclick = \"window.open('".$location."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/view1.png' width='18' height='18' alt='Print' title='Print'></a>";
									}
								}
							}							
						}

					echo "</td>";
				echo "</tr>";
			}
		}
	}	
	// --------------------------------------------------- End of Execute Query ---------------------------------------------			
}
?>
