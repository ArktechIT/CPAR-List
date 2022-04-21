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
	include('PHP Modules/rose_prodfunctions.php');
	include('PHP Modules/anthony_retrieveText.php');
	include('PHP Modules/anthony_wholeNumber.php');
	header("Location:rhay_cparSummary.php");
?> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>CPAR Summary</title>
	<link href="../../Common Data/Templates/tableDesign.css" rel="stylesheet" media="screen" />
	<link rel = 'stylesheet' type = 'text/css' href = '../../Common Data/anthony.css'>
	<style>
	input.textboxFilter, select
	{
		width: 8em;
		height: 2.5em;
	}
	
	#filterHeader tr td
	{
		border: none;
		vertical-align: middle;
		text-align: right;
	}
	
	#filterHeader tr .t_head
	{
		text-align: left;
	}
	
	.fancyTable tr td, .fancyTable tr th
	{
		vertical-align: middle;
	}
	
	</style>
	
	<script src="../../Common Data/Libraries/Javascript/Table with Fixed Header/jquery.min.js"></script>
	<script type="text/javascript" src="../../Common Data/Libraries/Javascript/Quick Table/jquery-1.9.0.min.js"></script>
	
	<?php
	$queryLimit = 50;
	$cparId = (isset($_POST['cparId'])) ? $_POST['cparId'] : '' ;
	$cparInfoSource = (isset($_POST['cparInfoSource'])) ? $_POST['cparInfoSource'] : '' ;
	$cparDetails = (isset($_POST['cparDetails'])) ? $_POST['cparDetails'] : '' ;
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
	$dateStart = (isset($_POST['dateStart']) AND $_POST['dateStart'] != '') ? $_POST['dateStart'] : "";
	$dateEnd = (isset($_POST['dateEnd']) AND $_POST['dateEnd'] != '') ? $_POST['dateEnd'] : "";
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
	
	// -------------------------------- Filter Date -------------------------------------
	//~ if($month < 10 AND strlen($month) == 1)
	//~ {
		//~ $month = "0".$month;
	//~ }
	
	//~ $filterIssueDate = '';
	//~ if(isset($month) OR isset($year))
	//~ {
		//~ $filterIssueDate = "AND cparIssueDate LIKE '%".$year."-".$month."%' ";
	//~ }
	//~ else
	//~ {
		//~ $filterIssueDate = "AND cparIssueDate LIKE '%".$year."-".$month."%' ";
	//~ }
	
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
	
	$mergeQuery = "WHERE cparId LIKE '%".$cparId."%' AND cparInfoSource LIKE '%".$cparInfoSource."%' AND cparDetails LIKE '%".$cparDetails."%' AND cparAnalysis LIKE '%".$analysis."%' AND cparSection LIKE '%".$cparSection."%' AND cparIssueDate LIKE '%".$cparIssueDate."%' AND cparDueDate LIKE '%".$cparDueDate."%' AND cparSourcePerson LIKE '%".$cparSourcePerson."%' AND cparDetectProcess LIKE '%".$cparDetectProcess."%' AND cparDetectPerson LIKE '%".$cparDetectPerson."%' AND cparStatus LIKE '%".$cparStatus."%' ".$advanceTodayDelay." ".$alarmFilterClause." ".$interimFilterClause." ".$correctiveFilterClause." ".$verificationFilterClause." ".$filterIssueDate." AND cparId NOT LIKE '' ".$extraQuery." ".$cparQuery;// AND cparIssueDate LIKE '%".date("Y-m-")."%' 
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
		$total_groups = ceil($getTotalRecordsResult['totalRecords']/$queryLimit); 
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
			$total_pricez = ceil($getTotalPriceResult['totalRecords2']/$queryLimit); 
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
	//echo "rose".$totalPRICE;
	//Rose
	// -------------------------------------------------------------------- End Count Number of Rows -----------------------------------------------------------------------
	?>
	
	<script type="text/javascript">
	$(document).ready(function() 
	{
		var track_load = 0; //total loaded record group(s)
		var loading  = false; //to prevents multipal ajax loads
		var total_groups = <?php echo $total_groups; ?>; //total record group(s)
		var cparId = '<?php echo $cparId; ?>';
		var cparInfoSource = '<?php echo $cparInfoSource; ?>';
		var cparDetails = '<?php echo mysqli_real_escape_string($db, $cparDetails); ?>';
		
		var analysis = '<?php echo $analysis; ?>';
		var cparSection = '<?php echo $cparSection; ?>';
		var cparSourcePerson = '<?php echo $cparSourcePerson; ?>';
		var cparDetectProcess = '<?php echo $cparDetectProcess; ?>';
		var cparDetectPerson = '<?php echo $cparDetectPerson; ?>';
		var cparIssueDate = '<?php echo $cparIssueDate; ?>';
		var cparDueDate = '<?php echo $cparDueDate; ?>';
		var cparStatus = '<?php echo $cparStatus; ?>';
		var alarmFilter = '<?php echo $alarmFilter; ?>';	
		var interimFilter = '<?php echo $interimFilter; ?>';
		var correctiveFilter = '<?php echo $correctiveFilter; ?>';
		var verificationFilter = '<?php echo $verificationFilter; ?>';
		
		var cparDisposition = '<?php echo $cparDispositions; ?>';
		var customerId = '<?php echo $customerId; ?>';
		var dateStart = '<?php echo $dateStart; ?>';
		var dateEnd = '<?php echo $dateEnd; ?>';
		
		var advance = '<?php echo $advance; ?>';
		var today = '<?php echo $today; ?>';
		var delay = '<?php echo $delay; ?>';
		var month = '<?php echo $month; ?>';
		var year = '<?php echo $year; ?>';
		
		
		// ---------------------------------------------------- Load First Group -----------------------------------------------------
		$('#results').load("anthony_cparSummaryAJAX.php", {'group_no':track_load,'cparId':cparId,'cparInfoSource':cparInfoSource,'cparDetails':cparDetails,'analysis':analysis,'cparSection':cparSection,'cparSourcePerson':cparSourcePerson,'cparDetectProcess':cparDetectProcess,'cparDetectPerson':cparDetectPerson,'cparIssueDate':cparIssueDate,'cparDueDate':cparDueDate,'cparStatus':cparStatus,'alarmFilter':alarmFilter,'interimFilter':interimFilter,'correctiveFilter':correctiveFilter,'verificationFilter':verificationFilter,'advance':advance,'today':today,'delay':delay,'month':month,'year':year, 'cparDisposition':cparDisposition, 'customerId':customerId, 'dateStart':dateStart, 'dateEnd':dateEnd}, function() {track_load++;}); 
		// ---------------------------------------------------- Detect Page Scroll ---------------------------------------------------
		$('.fht-tbody').scroll(function() 
		{ 
			//alert($('.fht-tbody').scrollTop()+" : "+$('.fht-tbody').height()+" : "+$(document).height())
			if($(window).scrollTop() + $(window).height() == $(document).height())  //user scrolled to bottom of the page?
			{
				if(track_load <= total_groups && loading==false) //there's more data to load
				{
					loading = true; //prevent further ajax loading
					$('.animation_image').show(); //show loading image
					
					//load data from the server using a HTTP POST request
					$.post('anthony_cparSummaryAJAX.php',{'group_no':track_load,'cparId':cparId,'cparInfoSource':cparInfoSource,'cparDetails':cparDetails,'analysis':analysis,'cparSection':cparSection,'cparSourcePerson':cparSourcePerson,'cparDetectProcess':cparDetectProcess,'cparDetectPerson':cparDetectPerson,'cparIssueDate':cparIssueDate,'cparDueDate':cparDueDate,'cparStatus':cparStatus,'alarmFilter':alarmFilter,'interimFilter':interimFilter,'correctiveFilter':correctiveFilter,'verificationFilter':verificationFilter,'advance':advance,'today':today,'delay':delay,'month':month,'year':year, 'cparDisposition':cparDisposition, 'customerId':customerId, 'dateStart':dateStart, 'dateEnd':dateEnd}, function(data)
					{
						$("#results").append(data); //append received data into the element

						//hide loading image
						$('.animation_image').hide(); //hide loading image once data is received
						
						track_load++; //loaded group increment
						loading = false; 
					
					}).fail(function(xhr, ajaxOptions, thrownError) { //any errors?
						
						alert(thrownError); //alert with HTTP error
						$('.animation_image').hide(); //hide loading image
						loading = false;
					
					});
					
				}
			}
		});
	});
	</script>
	<style>
	body,td,th {font-family: Georgia, Times New Roman, Times, serif;font-size: 15px;}
	.animation_image {background: #F9FFFF;border: 1px solid #E1FFFF;padding: 10px;width: 500px;margin-right: auto;margin-left: auto;}
	#results{width: 500px;margin-right: auto;margin-left: auto;}
	#resultst ol{margin: 0px;padding: 0px;}
	#results li{margin-top: 20px;border-top: 1px dotted #E1FFFF;padding-top: 20px;}
	</style>	
</head>
<div align="left" style="border:2px solid;border-radius:25px;width:98%;height:600px;padding:10px;">
<table style = "position: absolute;">
		<!-- Main Menu --> 
	<tr>
		<td height="40" width="100">
			<?php echo mainMenu(0); ?>
		</td>
		<td width='800' align = 'center'>
			<font size = 5 color=green><b><?php echo displayText('6-4','utf8',0,1,1); ?></b></font><br>
		</td>
			
			<a href = "<?php echo $_SERVER['PHP_SELF']; ?>"><img src="../Common Data/Templates/systemImages/refreshIcon.png" align='right' width='60' height='60'/></a>
			
			<?php
			
			if($_GET['country']=='2')
			{
				$permissionIds = "201, 202, 203, 204, 205, 206, 207, 208, 209, 210, 221, 222, 223, 224, 226, 227, 228, 229";//Leaders
				$sql = "SELECT permissionId FROM system_userpermission WHERE idNumber LIKE '".$_SESSION['idNumber']."' AND permissionId IN(".$permissionIds.")";
				$queryUserPermission = $db->query($sql);
				if(($queryUserPermission AND $queryUserPermission->num_rows > 0) OR in_array($_SESSION['userType'], array(0, 10))) //qc,fvi & IT;
				{
					?>
					<a href = "anthony_inputForm.php"><img src="../Common Data/Templates/systemImages/addIcon.png" align='right' width='60' height='60'/></a>
					<?php
				}				
			}
			else
			{
				if(in_array($_SESSION['userType'], array(0, 10))) //qc,fvi & IT;
				{
					?>
					<a href = "anthony_inputForm.php"><img src="../Common Data/Templates/systemImages/addIcon.png" align='right' width='60' height='60'/></a>
					<?php
				}
			}
			?>
			
			<img src="../Common Data/Templates/images/spacer.gif" align="right" width='8' height='35' />
			<a href = "anthony_cparSummary.php?delay=1"><img src="../Common Data/Templates/images/delay.png" alt="delay" align="right" width='45' height='55'/></a>
			<img src="../Common Data/Templates/images/spacer.gif" align="right" width='10' height='35' />
			<a href = "anthony_cparSummary.php?today=1"><img src="../Common Data/Templates/images/now.png" alt="today" align="right" width='45' height='55'/></a>
			<img src="../Common Data/Templates/images/spacer.gif" align="right" width='10' height='35' />
			<a href = "anthony_cparSummary.php?advance=1"><img src="../Common Data/Templates/images/advance.png" alt="advance" align="right" width='45' height='55'/></a>
			<img src="../Common Data/Templates/images/spacer.gif" align="right" width='10' height='35' />
			
			<form id='exportId' method='POST' action='anthony_excel.php'></form>
			<input form='exportId' type='hidden' name='mergeQuery' value="<?php echo $mergeQuery; ?>" />
			<input form='exportId' type='hidden' name='dateStart' value="<?php echo $dateStart; ?>" />
			<input form='exportId' type='hidden' name='dateEnd' value="<?php echo $dateEnd; ?>" />
			<input form='exportId' onclick='this.form.submit();' type = 'submit' name = 'excelExport' value = '<?php echo displayText('L487'); ?>' title='EXPORT' style='float:right;'/>
			
	</tr>
</table>
	<div class="art-post-inner art-article">
		<div class="art-postcontent">
		<p>
			<body>
				<br><br>				
				<form name="customerFilter" action="anthony_cparSummary.php" method="POST">
				
				<table id = 'filterHeader' border = 1>
				<tr>
					<td class = 't_head'><?php echo displayText('L334'); ?></td>
					<td class = 't_head'><?php echo displayText('L335'); ?></td>
					<td class = 't_head'><?php echo displayText('L336'); ?></td>
					<td class = 't_head'><?php echo displayText('L337'); ?></td>
					<td class = 't_head'><?php echo displayText('L338'); ?></td>
					<td class = 't_head'><?php echo displayText('L339'); ?></td>
					<td class = 't_head'><?php echo displayText('L340'); ?></td>
					<td class = 't_head'><?php echo displayText('L341'); ?></td>
					<td class = 't_head'><?php echo displayText('L342'); ?></td>
					<td class = 't_head'><?php echo displayText('L343'); ?></td>
					<td class = 't_head'><?php echo displayText('L113'); ?></td>
				</tr>	
					
				<tr>
				<?php
				// ------------------------------------------ Filter CPAR Id ------------------------------------------------
				//~ $sql = "SELECT cparId FROM qc_cpar ".$mergeQuery." ORDER BY listId DESC ";
				//~ $getcparId = $db->query($sql);
					//~ echo "<td>CPAR Id</td>";
					//~ echo "<td><select name = 'cparId' onchange='this.form.submit()'>";
						//~ echo "<option value=''>All</option>";
					//~ while($getcparIdResult = $getcparId->fetch_assoc())
					//~ {
						//~ if($getcparIdResult['cparId'] != '')
						//~ {
							//~ echo "<option value='".$getcparIdResult['cparId']."' "; if($_POST['cparId'] == $getcparIdResult['cparId']){ echo "selected"; } echo ">".$getcparIdResult['cparId']."</option>";
						//~ }
					//~ }
				//~ echo "</select></td>";
				
				
				echo "<td>";
					echo "<input type = 'text' list = 'cparId' name = 'cparId' class = 'textboxFilter' value = '".$cparId."' onchange = 'this.form.submit();'>";
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
					echo "<td><select name = 'cparInfoSource' onchange='this.form.submit()'>";
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
					echo "<td><select name = 'cparDetails' onchange='this.form.submit()'>";
						echo "<option value=''>All</option>";
					while($getDetailsResult = $getDetails->fetch_assoc())
					{
						if($getDetailsResult['cparDetails'] != '')
						{
							echo "<option value='".$getDetailsResult['cparDetails']."' "; if($_POST['cparDetails'] == $getDetailsResult['cparDetails']){ echo "selected"; } echo ">".$getDetailsResult['cparDetails']."</option>";					
						}
					}
				echo "</select></td>";
				//~ echo $sql;
				// ------------------------------------------ Filter Analysis ------------------------------------------------
				echo "<td><input type = 'text' name = 'analysis' class = 'textboxFilter' value = '".$analysis."' onchange = 'this.form.submit();'></td>";
								
				// ------------------------------------------ Filter Concern Section ------------------------------------------------
				$sql = "SELECT DISTINCT cparSection FROM qc_cpar ".$mergeQuery." ";
				$getCPARSection = $db->query($sql);
					echo "<td><select name = 'cparSection' onchange='this.form.submit()'>";
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
					echo "<td><select name = 'cparSourcePerson' onchange='this.form.submit()'>";
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
					echo "<td><select name = 'cparDetectProcess' onchange='this.form.submit()'>";
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
					echo "<td><select name = 'cparDetectPerson' onchange='this.form.submit()'>";
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
					echo "<td><select name = 'cparIssueDate' onchange='this.form.submit()'>";
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
				echo "<td><select name = 'cparDueDate' onchange='this.form.submit()'>";
					echo "<option value=''>All</option>";
				while($getDueDateResult = $getDueDate->fetch_assoc())
				{
					if($getDueDateResult['cparDueDate'] != '')
					{
						echo "<option value='".$getDueDateResult['cparDueDate']."' "; if($_POST['cparDueDate'] == $getDueDateResult['cparDueDate']){ echo "selected"; } echo ">".$getDueDateResult['cparDueDate']."</option>";					
					}
				}
				echo "</select></td>";
				
				// --------------------------------------------- Alarm Color -----------------------------------------------
				echo "<td><select name='alarmFilter' class='resizedTextbox' onchange='this.form.submit()'>";
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
				echo "</tr>";
				
				echo "<tr>
					<td class = 't_head'>".displayText('L172')."</td>
					<td class = 't_head'>".displayText('L344')."</td>
					<td class = 't_head'>".displayText('L345')."</td>
					<td class = 't_head'>".displayText('L346')."</td>
					<td class = 't_head'>".displayText('L347')."</td>
					<td align = 'right'>".displayText('L250')."</td>
					<td></td>
					<td class = 't_head'>".displayText('L348')."</td>
					<td class = 't_head'>".displayText('L24')."</td>
				</tr>";
				
				echo "<tr>";
				// ------------------------------------------ Filter Status ------------------------------------------------
				$sql = "SELECT DISTINCT cparStatus FROM qc_cpar ".$mergeQuery." ";
				$getStatus = $db->query($sql);
					echo "<td><select name = 'cparStatus' onchange='this.form.submit()'>";
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
				echo "<td><select name='interimFilter' class='resizedTextbox' onchange='this.form.submit()'>";
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
				echo "<td><select name='correctiveFilter' class='resizedTextbox' onchange='this.form.submit()'>";
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
				echo "<td><select name='verificationFilter' class='resizedTextbox' onchange='this.form.submit()'>";
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
				
				// ------------------------------------------ Filter Date ------------------------------------------------
				?>
					<td>
						<input type = 'date' name = 'dateStart' onblur = 'this.form.submit();' value = '<?php echo $dateStart; ?>'>
					</td>
					
					<td>
						<input type = 'date' name = 'dateEnd' onblur = 'this.form.submit();' value = '<?php echo $dateEnd; ?>'>
					</td>
					<!-- <td style = 'width: 100%; text-align: left;'>
						<select name="month" onchange="this.form.submit()">
							<?php for ($x=1;$x<=12;$x++)
							{ 
								if(isset($_POST['month']))
								{ 
									?> <option value="<?php echo $x; ?>" <?php if($_POST['month'] == $x){ echo 'selected'; } ?> ><?php echo date( 'F', mktime(0, 0, 0, $x, 1) ); ?></option> <?php
								} 
								else if(!isset($_POST['month']))
								{
									?> <option value="<?php echo $x; ?>" <?php if($x == date('n')){ echo 'selected'; } ?> ><?php echo date( 'F', mktime(0, 0, 0, $x, 1) ); ?></option> <?php
								} 
							} ?>
						</select>
					</td>
					<td style = 'width: 100%; text-align: left;'>
						<select name="year" onchange="this.form.submit()">
							<?php
							for($i = 2013; $i < date("Y")+1; $i++)
							{
								if(isset($_POST['year']))
								{
									?> <option value="<?php echo $i; ?>" <?php if($_POST['year'] == $i){ echo 'selected'; } ?> ><?php echo $i; ?></option> <?php 
								} 
								else
								{ 
									?> <option value="<?php echo $i; ?>" <?php if($i == date('Y')){ echo 'selected'; } ?> ><?php echo $i; ?></option> <?php
								}
							} ?>
						</select>
					</td> -->
					
					<td></td>
					
					<td>
						<select name="cparDisposition" onchange="this.form.submit()">
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
								<select name="customerId" onchange="this.form.submit()">
									<option></option>
									<?php echo $optionString;?>
								</select>
								<?php
							//~ }
						?>
						
					</td>
				</tr>
				</table>
				
				</form>
						<?php echo 	displayText('L41')/*records*/.": <b>".$getTotalRecordsResult['totalRecords']; ?>
						<div class="grid_8 height400">
							<table class="fancyTable" id="myTable02" cellpadding="0" cellspacing="0">
								<?php								
								// --------------------------------------- Table Header ------------------------------------------
								echo "<thead>";
										echo "<tr>";
											echo "<th width='100'>".displayText('L334')."</th>";
											echo "<th width='100'>".displayText('L24')."</th>";
											if($_GET['country'] == 2 OR $_SESSION['idNumber'] == '0412')
											{
												echo "<th width='100'>Part Number</th>";
											}
											echo "<th width='100'>".displayText('L45')."</th>";
											echo "<th width='50'>".displayText('L31')."</th>";
											echo "<th width='100'>".displayText('L351')."</th>";
											echo "<th width='100'>".displayText('L352')."</th>";
											echo "<th width='150'>".displayText('L353')."</th>";
											echo "<th width='100'>".displayText('L267')."</th>";
											echo "<th width='100'>".displayText('L354')."</th>";
											echo "<th width='100'>".displayText('L355')."</th>";
											echo "<th width='100'>".displayText('L336')."</th>";
											echo "<th width='100'>".displayText('L1330')."</th>";
											echo "<th width='100'>".displayText('L348')."</th>";
											echo "<th width='100'>".displayText('L337')."</th>";
											echo "<th width='100'>".displayText('L338')."</th>";
											//echo "<th width=50>Source Person</th>";											
											echo "<th width='100'>".displayText('L340')."</th>";
											echo "<th width='100'>".displayText('L341')."</th>";
											echo "<th width='100'>".displayText('L342')."</th>";
											//echo "<th width=50>Due Date</th>";
											echo "<th width='100'>".displayText('L113')."</th>";
											echo "<th width='100'>".displayText('L344')."</th>";
											echo "<th width='50'>".displayText('L356')."</th>";
											echo "<th width='100'>".displayText('L357')."</th>";
											//echo "<th width=50>Date Diff</th>";
											echo "<th width='100'>".displayText('L172')."</th>";
											echo "<th width='100'>".displayText('L1120')."</th>";
										echo "</tr>";
								echo "</thead>";
								// ------------------------------------- End of Table Header ------------------------------
								
								// ------------------------------------- Table Content ------------------------------------								
								echo "<tbody id='results'>";
										
								echo "</tbody>";						
								
								echo "<tfoot>";
										echo "<tr>";											
											echo "<th colspan = 2></th>";
											echo "<th width='50'>".$totalNGQuantity."</th>"; //qty;
											echo "<th>".$totalQuantity."</th>"; //total qty delivered;
											echo "<th>".round($totalPercentage, 2)."</th>"; //%;
											echo "<th width='150'>".number_format($totalDppm)."</th>"; //dppm
											echo "<th width='150'>$".round($totalAmount, 2)."</th>"; //price;
											echo "<th colspan = 18></th>";
										echo "</tr>";
								echo "</tfoot>";
								
								// ------------------------------------- End of Table Content ------------------------------------
								?>
							</table>
						</div>
						<div class="clear"></div>
									
			</body>
		</p>	
		</div>
	</div>
</div>
</html>

<!-- -----------------------------------START SMALL BOX------------------------------------------------------------- -->
<script type="text/javascript" src="../Common Data/Libraries/Javascript/Tiny Box/tinybox.js"></script>
<link rel="stylesheet" href="../Common Data/Libraries/Javascript/Tiny Box/stylebox.css" />
<script type="text/javascript">
function openJS(){alert('loaded')}
function closeJS(){alert('closed')}
</script>   
<!-- -----------------------------------END SMALL BOX----------------------------------------------------------------> 

<script src="../../Common Data/Libraries/Javascript/Table with Fixed Header/jquery.fixedheadertable.js"></script>
<script>
	$('#myTable02').fixedHeaderTable({
		footer: true,
		altClass: 'odd',		
	});	
</script>
