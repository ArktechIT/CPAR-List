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


$lotNumber = $_POST['lotNumber'];
$tagNumber = strtoupper($_POST['ptag']);

$sql = "SELECT productionTag, groupTag, workingQuantity, identifier, status FROM ppic_lotlist WHERE lotNumber LIKE '".$lotNumber."' LIMIT 1";
$queryLotList = $db->query($sql);
if($queryLotList AND $queryLotList->num_rows > 0)
{
	$resultLotList = $queryLotList->fetch_array();
	$productionTag = $resultLotList['productionTag'];
	$groupTag = $resultLotList['groupTag'];
	$workingQuantity = $resultLotList['workingQuantity'];
	$identifier = $resultLotList['identifier'];
	$lotStatus = $resultLotList['status'];

	if($groupTag!='')
	{
		//header("Location: gerald_tagLinkingInputForm.php?error=7");
		//exit(0);
		echo "Lot Number has Group Tag!<br>Input Group Tag as Lot Number instead";
	}

	if($identifier==1 OR $identifier==2)		$pattern = '/^[pP]{1}\\d+$/';
		else if($identifier==3)	$pattern = '/^[rR]{1}\\d+$/';
		else if($identifier==4)
		{
			$pattern = '/^[rRpP]{1}\\d+$/';
			if($lotStatus==1)
			{
				$pattern = '/^[mM]{1}\\d+$/';
			}
		}
		
		//~ if(!preg_match('/^[a-zA-Z]{1}+[0-9]+/', $tagNo))
		if(!preg_match($pattern, $tagNumber))
		{
			echo "Invalid Tag Number!";
			return false;
		}

		$tagType = '';
		if($tagNumber[0]=='P')	$tagType = '0';
		else if($tagNumber[0]=='M')	$tagType = '1';
		else if($tagNumber[0]=='R')	$tagType = '2';
		
		if($tagType!='')
		{
			$lastNumber = 0;
			$sql = "SELECT end FROM system_print1v4TagLog WHERE tagType = ".$tagType." ORDER BY end DESC LIMIT 1";
			$queryLastNumber = $db->query($sql);
			if($queryLastNumber AND $queryLastNumber->num_rows > 0)
			{
				$resultLastNumber = $queryLastNumber->fetch_assoc();
				$lastNumber = $resultLastNumber['end'];
			}
			
			if(substr($tagNumber, 1) > $lastNumber)
			{
				echo "Invalid Tag Number!";	
				return false;			
			}
		}

		if($_GET['country']=="1")
		{
			// -------------------------- Check If Lot Number Has TPP -------------------------------------------------
			$sql = "SELECT id FROM view_workschedule WHERE lotNumber LIKE '".$lotNumber."' AND processCode = 86 AND status = 0 LIMIT 1";
			$queryCheckTPPProcess = $db->query($sql);
			if($queryCheckTPPProcess AND $queryCheckTPPProcess->num_rows > 0)
			{
				//~ header("Location: gerald_tagLinkingInputForm1.php?error=5");
				echo "Please use TPP Software to link a Tag in this Lot Number!";
				return false;
			}
			// -------------------------- End Of Check If Lot Number Has TPP ------------------------------------------------
		}

		$currentProcess = '';
		$sql = "SELECT processCode FROM view_workschedule WHERE lotNumber LIKE '".$lotNumber."' AND status = 0 AND processCode NOT IN(141,174,95,366,367,364,437,438) ORDER BY processOrder LIMIT 1";
		$queryCurrentProcess = $db->query($sql);
		if($queryCurrentProcess AND $queryCurrentProcess->num_rows > 0)
		{
			$resultCurrentProcess = $queryCurrentProcess->fetch_array();
			$currentProcess = $resultCurrentProcess['processCode'];
		}
		
		if($productionTag=='')
		{
			if($workingQuantity > 0)
			{
				// ------------------------------- Check if Tag Number Is Same With Lot Number ----------------------------------
				$sql = "SELECT lotNumber FROM ppic_lotlist WHERE lotNumber LIKE '".$tagNumber."' LIMIT 1";
				$queryCheckTag = $db->query($sql);
				// -------------------- Execute This Block If Tag Number Is Same With Lot Number -----------------------------------
				if($queryCheckTag AND $queryCheckTag->num_rows > 0)
				{
					echo "Invalid Tag Number!";
					return false;
				}
				// ------------------- Execute This Block If Tag Number Is Not Same With Lot Number --------------------------------
				else
				{
					$sql = "SELECT lotNumber FROM ppic_lotlist WHERE (productionTag LIKE '".$tagNumber."' OR groupTag LIKE '".$tagNumber."') LIMIT 1";
					$queryCheckTag = $db->query($sql);
					// -------------- Execute This Block If Tag Number Already Used ------------------------------------
					if($queryCheckTag AND $queryCheckTag->num_rows > 0)
					{
						echo "Tag Number Already Exist!";
						return false;
					}
					// -------------- Execute This Block If Tag Number Is Not Yet Used ------------------------------------
					else
					{
						$sql = "UPDATE ppic_lotlist SET productionTag = '".strtoupper($tagNumber)."' WHERE lotNumber LIKE '".$lotNumber."' LIMIT 1";
						//$queryUpdate = $db->query($sql);
						
						$sql = "INSERT INTO `system_productiontaglog`
										(	`productionTag`,	`lotNumber`,		`processCode`,			`linkDate`, 	`employeeId`)
								VALUES	(	'".$tagNumber."',	'".$lotNumber."',	'".$currentProcess."',	NOW(),	'".$_SESSION['idNumber']."')";
						//$queryInsert = $db->query($sql);
						
						header("Location: gerald_tagLinkingInputForm.php?error=0&asd=1&tagNumber=".$tagNumber);
					}
				}
			}
			else
			{
				echo "Working quantity should be greater than 0!";
				return false;
			}
		}
		else
		{
			// -------------------------- Check If Lot Already Has DR ------------------------------------------------------------
			$sql = "SELECT drNumber FROM purchasing_drcustomer WHERE lotNumber LIKE '".$lotNumber."' AND updateStatus != 2";
			$queryDrCustomer = $db->query($sql);
			if($queryDrCustomer AND $queryDrCustomer->num_rows > 0)
			{
				$resultDrCustomer = $queryDrCustomer->fetch_assoc();
				$drNumber = $resultDrCustomer['drNumber'];
				
				$controlNumber = '';
				$sql = "SELECT controlNumber FROM impex_itemlistdetails WHERE drNumber LIKE '".$drNumber."' LIMIT 1";
				$queryItemListDetails = $db->query($sql);
				if($queryItemListDetails AND $queryItemListDetails->num_rows > 0)
				{
					$resultItemListDetails = $queryItemListDetails->fetch_assoc();
					$controlNumber = $resultItemListDetails['controlNumber'];
				}
				
				$sql = "SELECT controlNumber FROM impex_itemlist WHERE controlNumber LIKE '".$controlNumber."' AND boxNumber LIKE 'ADVANCE' LIMIT 1";
				$queryItemList = $db->query($sql);
				if($queryItemList AND $queryItemList->num_rows == 0)
				{
					echo "Lot Number has DR already!";
					return false;
				}
			}
			// -------------------------- End Of Check If Lot Already Has DR ------------------------------------------------------------
			
			// -------------------------------- Ace Sandoval ------------------------------------
			$sql = "SELECT lotNumber FROM ppic_lotlist WHERE (productionTag LIKE '".$tagNumber."' OR groupTag LIKE '".$tagNumber."') LIMIT 1";
			$queryCheckTag = $db->query($sql);
			// -------------- Execute This Block If Tag Number Already Used ------------------------------------
			if($queryCheckTag->num_rows > 0 or $identifier==2)
			{
				echo "Tag Number Already Exist!";
				return false;
			}
			// -------------- Execute This Block If Tag Number Is Not Yet Used ------------------------------------
			// -------------------------------- Ace Sandoval ------------------------------------
			else
			{
				$sql = "UPDATE ppic_lotlist SET productionTag = '".strtoupper($tagNumber)."' WHERE lotNumber LIKE '".$lotNumber."' LIMIT 1";
				//$queryUpdate = $db->query($sql);				
				
				$sql = "INSERT INTO `system_productiontaglog`
								(	`productionTag`,	`lotNumber`,		`processCode`,			`linkDate`, 	`employeeId`)
						VALUES	(	'".$tagNumber."',	'".$lotNumber."',	'".$currentProcess."',	NOW(),	'".$_SESSION['idNumber']."')";
				//$queryInsert = $db->query($sql);
				
			}
		}
}
else
{
	$groupTag = $lotNumber;
		// -------------------------------------------------- Group Tag -------------------------------------------------- //
		$sql = "SELECT lotNumber, groupTag FROM ppic_lotlist WHERE groupTag LIKE '".$groupTag."' AND groupTag != '' LIMIT 1";
		$queryLotList = $db->query($sql);
		if($queryLotList AND $queryLotList->num_rows > 0)
		{
			$resultLotList = $queryLotList->fetch_array();
			$lote = $resultLotList['lotNumber'];
			
			$pattern = '/^[pP]{1}\\d+$/';
			if(!preg_match($pattern, $tagNumber))
			{
				echo "Invalid Tag Number!";
				return false;

			}
			
			$currentProcess = '';
			$sql = "SELECT processCode FROM view_workschedule WHERE lotNumber LIKE '".$lote."' AND status = 0 AND processCode NOT IN(141,174,95,366,367,364,437,438) ORDER BY processOrder LIMIT 1";
			$queryCurrentProcess = $db->query($sql);
			if($queryCurrentProcess AND $queryCurrentProcess->num_rows > 0)
			{
				$resultCurrentProcess = $queryCurrentProcess->fetch_array();
				$currentProcess = $resultCurrentProcess['processCode'];
			}
			
			$sql = "SELECT lotNumber FROM ppic_lotlist WHERE (productionTag LIKE '".$tagNumber."' OR groupTag LIKE '".$tagNumber."') LIMIT 1";
			$queryCheckTag = $db->query($sql);
			// -------------- Execute This Block If Tag Number Already Used ------------------------------------
			if($queryCheckTag AND $queryCheckTag->num_rows > 0)
			{
				echo "Tag Number Already Exist!";
				return false;
			}
			// -------------- Execute This Block If Tag Number Is Not Yet Used ------------------------------------
			else
			{
				$sql = "UPDATE ppic_lotlist SET groupTag = '".strtoupper($tagNumber)."' WHERE groupTag LIKE '".$groupTag."'";
				//$queryUpdate = $db->query($sql);
				
				$sql = "INSERT INTO `system_productiontaglog`
								(	`productionTag`,	`lotNumber`,		`processCode`,			`linkDate`, 	`employeeId`)
						VALUES	(	'".$tagNumber."',	'".$groupTag."',	'".$currentProcess."',	NOW(),	'".$_SESSION['idNumber']."')";
				//$queryInsert = $db->query($sql);
				
			}
		}
		// ------------------------------------------------ END Group Tag ------------------------------------------------ //
		
		echo "Lot Number ".$lotNumber." Not Exist!";
		return false;
}