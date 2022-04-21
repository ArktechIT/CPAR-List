<?php
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

	$lotNo = $_POST['lotNumber'];
	$sourceInfo = $_POST['sourceInfo'];
	$mainAssyFlag = $_POST['mainAssyFlag'];
	$lotNumbers = $_POST['lotNumbers'];
	$workingQuantity = '';
	if(trim($lotNo)!='')
	{
		$sql = "SELECT lotNumber, poId, partId, partLevel, workingQuantity FROM ppic_lotlist WHERE lotNumber LIKE '".$lotNo."' OR productionTag LIKE '".$lotNo."' LIMIT 1";
		$queryLotList = $db->query($sql);
		if($queryLotList->num_rows > 0)
		{
			$resultLotList = $queryLotList->fetch_array();
			$lotNo = $resultLotList['lotNumber'];
			$poId = $resultLotList['poId'];
			$partId = $resultLotList['partId'];
			$partLevel = $resultLotList['partLevel'];
			$workingQuantity = $resultLotList['workingQuantity'];
			
			$workingQuantity = wholeNumber($workingQuantity);
		}
		
		//~ $withChildFlag = 0;
		//~ $sql = "SELECT lotNumber FROM ppic_lotlist WHERE poId = ".$poId." AND parentLot = SUBSTRING_INDEX('".$lotNo."','-',3) AND identifier IN(1,2) LIMIT 1";
		//~ $queryLotList = $db->query($sql);
		//~ if($queryLotList AND $queryLotList->num_rows == 0)
		//~ {
			//~ $withChildFlag = 1;
		//~ }
		
		if($mainAssyFlag==1 AND $partLevel > 1)
		//~ if($mainAssyFlag==1 AND $withChildFlag==1)
		{
			echo "|Error!. Please input Main Assy Lot Number!";
			exit(0);
		}
		else
		{
			if(count($lotNumbers) > 0)
			{
				$lotNumberArray = array();
				$sql = "SELECT lotNumber FROM ppic_lotlist WHERE poId = ".$poId." AND lotNumber IN('".implode("','",$lotNumbers)."') AND lotNumber != '".$lotNo."' AND identifier IN(1,2)";
				//~ $sql = "SELECT lotNumber FROM ppic_lotlist WHERE poId = ".$poId." AND lotNumber IN('".implode("','",$lotNumbers)."') AND lotNumber != '".$lotNo."' AND parentLot = SUBSTRING_INDEX('".$lotNo."','-',3) AND identifier IN(1,2)";
				$queryLotList = $db->query($sql);
				if($queryLotList AND $queryLotList->num_rows > 0)
				{
					while($resultLotList = $queryLotList->fetch_assoc())
					{
						$lotNumberArray[] = $resultLotList['lotNumber'];
					}
					
					echo "|Error!. You have inputed some subparts for this assy!|".implode(",",$lotNumberArray);
					exit(0);
				}
			}
		}
	}
	
	//~ $sql = "SELECT id FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNo."' AND processCode = 368 AND status = 0 LIMIT 1";
	//~ $queryWorkSchedule = $db->query($sql);
	//~ if($queryWorkSchedule AND $queryWorkSchedule->num_rows > 0)
	//~ {
		//~ echo "|Error!. Please use the new way for this lot!";
		//~ exit(0);
	//~ }
	
	$notProcessedBySubconFlag = 0;
	
	$sql = "SELECT drNumber FROM purchasing_drcustomer WHERE lotNumber LIKE '".$lotNo."' AND updateStatus != 2 LIMIT 1";
	$queryDrCustomer = $db->query($sql);
	if($queryDrCustomer->num_rows > 0)
	{
		if($sourceInfo!='CPAR-CUS_Customer Claim')
		{
			$sql = "SELECT id FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNo."' AND processCode = 144 AND status = 1 LIMIT 1";
			$queryWorkSchedule = $db->query($sql);
			if($queryWorkSchedule->num_rows > 0)
			{
				echo "|Error!. Lot Number Already Delivered!!";
				exit(0);
			}
		}
	}
	else
	{
		if($sourceInfo=='CPAR-INT_Internal')
		{
			//~ if($_SESSION['idNumber']=='0346')
			//~ {
				$sql = "SELECT processCode FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNo."' AND status = 0 AND processCode NOT IN(141,174,95,364,366,367,437,438,461,496) ORDER BY processOrder LIMIT 1";
				$queryWorkSchedule = $db->query($sql);
				if($queryWorkSchedule AND $queryWorkSchedule->num_rows > 0)
				{
					$resultWorkSchedule = $queryWorkSchedule->fetch_assoc();
					$currentProcess = $resultWorkSchedule['processCode'];
					
					if($currentProcess==368)
					{
						$sql = "SELECT processCode FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNo."' AND status = 1 AND processCode NOT IN(358,546) ORDER BY processOrder DESC LIMIT 1";
						$queryWorkSchedule = $db->query($sql);
						if($queryWorkSchedule AND $queryWorkSchedule->num_rows > 0)
						{
							$resultWorkSchedule = $queryWorkSchedule->fetch_assoc();
							$lastFinishProcess = $resultWorkSchedule['processCode'];
							
							if(in_array($lastFinishProcess,array(137,138,229)))
							{
								$notProcessedBySubconFlag = 1;
							}
						}
					}
				}
			//~ }
		}
		else if($sourceInfo=='CPAR-SUP_Supplier')
		{
			$sql = "SELECT processCode FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNo."' AND status = 0 AND processCode NOT IN(141,174,95,364,366,367,437,438,461,496) ORDER BY processOrder LIMIT 1";
			$queryWorkSchedule = $db->query($sql);
			if($queryWorkSchedule AND $queryWorkSchedule->num_rows > 0)
			{
				$resultWorkSchedule = $queryWorkSchedule->fetch_assoc();
				$currentProcess = $resultWorkSchedule['processCode'];
				
				if($currentProcess==368)
				{
					$sql = "SELECT processCode FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNo."' AND status = 1 AND processCode NOT IN(358,546) ORDER BY processOrder DESC LIMIT 1";
					$queryWorkSchedule = $db->query($sql);
					if($queryWorkSchedule AND $queryWorkSchedule->num_rows > 0)
					{
						$resultWorkSchedule = $queryWorkSchedule->fetch_assoc();
						$lastFinishProcess = $resultWorkSchedule['processCode'];
						
						if(in_array($lastFinishProcess,array(137,138,229)))
						{
							$notProcessedBySubconFlag = 1;
						}
					}
				}
			}			
		}
		else if($sourceInfo=='CPAR-SUB_Subcon')
		{
			//~ $sql = "SELECT partId FROM cadcam_partprocess WHERE partId = ".$partId." AND processCode = 145 LIMIT 1";
			//~ $queryPartProcess = $db->query($sql);
			//~ if($queryPartProcess->num_rows > 0)
			//~ {
				$recursiveLot = $lotNo;
				$repeatFlag = 1;
				while($repeatFlag == 1)
				{
					$repeatFlag = 0;				
					$sql = "SELECT drNumber FROM purchasing_drcustomer WHERE lotNumber LIKE '".$recursiveLot."' AND updateStatus = 2 LIMIT 1";
					$queryDrCustomer = $db->query($sql);
					if($queryDrCustomer->num_rows > 0)
					{
						//~ $sql = "SELECT processCode FROM ppic_workschedule WHERE lotNumber LIKE '".$recursiveLot."' AND processCode IN(145,137,163) AND status = 0 ORDER BY processOrder";
						$sql = "SELECT processCode FROM ppic_workschedule WHERE lotNumber LIKE '".$recursiveLot."' AND processCode = 137 AND status = 1 LIMIT 1";
						$queryRW = $db->query($sql);
						if($queryRW AND $queryRW->num_rows > 0)
						{
							$sql = "SELECT processCode FROM ppic_workschedule WHERE lotNumber LIKE '".$recursiveLot."' AND processCode IN(145,163) AND status = 0 ORDER BY processOrder";
							$queryWorkSchedule = $db->query($sql);
							if($queryWorkSchedule->num_rows > 0)
							{
								while($resultWorkSchedule = $queryWorkSchedule->fetch_assoc())
								{
									$processCode = $resultWorkSchedule['processCode'];
									
									if($processCode=='145')
									{
										echo "|Error!. Delivery to Subcon not finished yet!!";
										break;
										exit(0);
									}
									else if($processCode=='137')
									{
										//~ echo "|Error!. Please Receive item first!!";
										break;
										exit(0);
									}
									else if($processCode=='163')
									{
										//~ echo "|Error!. Incoming Inspection not finished yet!!".$recursiveLot;
										break;
									}
								}
							}
						}
					}
					else
					{
						$sql = "SELECT id FROM view_workschedule WHERE lotNumber LIKE '".$recursiveLot."' AND processCode = 145 LIMIT 1";
						$queryWorkSchedule = $db->query($sql);
						if($queryWorkSchedule AND $queryWorkSchedule->num_rows == 0)
						{
							$sql = "SELECT sourceLotNumber FROM ppic_prslog WHERE lotNumber LIKE '".$recursiveLot."' AND type != 7 AND sourceLotNumber != '' LIMIT 1";
							$queryPrsLogSourceLot = $db->query($sql);
							if($queryPrsLogSourceLot AND $queryPrsLogSourceLot->num_rows > 0)
							{
								$resultPrsLogSourceLot = $queryPrsLogSourceLot->fetch_assoc();
								$recursiveLot = $resultPrsLogSourceLot['sourceLotNumber'];
								$repeatFlag = 1;
							}
						}
						else
						{
							$sourceLotNumber = '';
							$sql = "SELECT sourceLotNumber FROM ppic_prslog WHERE lotNumber LIKE '".$recursiveLot."' AND type != 7 AND sourceLotNumber != '' LIMIT 1";
							$queryPrsLogSourceLot = $db->query($sql);
							if($queryPrsLogSourceLot AND $queryPrsLogSourceLot->num_rows > 0)
							{
								$resultPrsLogSourceLot = $queryPrsLogSourceLot->fetch_assoc();
								$sourceLotNumber = $resultPrsLogSourceLot['sourceLotNumber'];
							}
							
							$sql = "SELECT listId FROM system_returnfromsubcon WHERE lotNumber LIKE '".$sourceLotNumber."' LIMIT 1";
							$queryReturnFromSubcon = $db->query($sql);
							if($queryReturnFromSubcon AND $queryReturnFromSubcon->num_rows > 0)
							{
								$recursiveLot = $sourceLotNumber;
								$repeatFlag = 1;
							}
							else
							{
								echo "|Error!. No Subcon DR!!";
								exit(0);
							}
						}
					}
				}
			//~ }
			//~ else
			//~ {
				//~ echo "|Error!. No subcon!!";
				//~ exit(0);
			//~ }
		}
		else if($sourceInfo=='CPAR-CUS_Customer Claim' AND $partLevel == 1 AND $_GET['country']==1)
		{
			echo "|Error!. No Customer DR!!";
			exit(0);
		}
	}

	$sql = "SELECT processCode FROM ppic_workschedule WHERE lotNumber LIKE '".$lotNo."' ORDER BY processOrder ASC ";
	$getProcessCode = $db->query($sql);
	if($getProcessCode->num_rows > 0)
	{
		//$sql = "SELECT listId FROM qc_cparlotnumber WHERE lotNumber LIKE '".$lotNo."' AND prsNumber = '' AND lotNumber != ''";
		//rosemie
		$sql = "SELECT listId FROM qc_cparlotnumber WHERE lotNumber LIKE '".$lotNo."' AND prsNumber = '' AND lotNumber != '' AND status = 0";
		$queryCparLotNumber = $db->query($sql);
		if($queryCparLotNumber->num_rows > 0)
		{
			echo "|Lot Number have cpar already!";
			exit(0);
		}
		else
		{
			echo "<option value = '0'>Beginning</option>";
			while($getProcessCodeResult = $getProcessCode->fetch_array())
			{
				$processCode = 0;
				$processName = '';
				$sql = "SELECT processCode, processName FROM cadcam_process WHERE processCode = ".$getProcessCodeResult['processCode']." ";
				$getProcessName = $db->query($sql);
				$getProcessNameResult = $getProcessName->fetch_array();
				$processCode = $getProcessNameResult['processCode'];
				$processName = $getProcessNameResult['processName'];
				
				echo "<option value = '".$processCode."'>After ".$processName."</option>";
			}
			
			echo "`".$workingQuantity;
			if($notProcessedBySubconFlag==1) echo "`notProcessedBySubconFlag";
			exit(0);
		}
	}
	else
	{
		echo "|".$lotNo." is Invalid Lot Number!";
		exit(0);
	}
?>
