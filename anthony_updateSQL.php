<?php
SESSION_START();
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
//$path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
$javascriptLib = "../Common Data/Libraries/Javascript/";
$templates = "../Common Data/Templates/";
set_include_path($path);	
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/gerald_functions.php');
$analysis = implode(',',$_POST['4m']);
$dispo = implode(',',$_POST['dispo']);

$cparListId = $_GET['cparId'];

$sesLOG=$_SESSION['userID']; 
$Users_IP_address = $_SERVER['REMOTE_ADDR'];

$oldName = $_POST['inputName'];
$newName = explode(', ',$oldName);
$sqlCombine = $db->query("SELECT a.departmentName FROM hr_department AS a, hr_employee AS b WHERE a.departmentId = b.departmentId AND b.surName = '".$newName[0]."' AND b.firstName = '".$newName[1]."'");
$sqlCombineResult = $sqlCombine->fetch_array();

$sql = "SELECT cparId FROM qc_cpar WHERE listId = ".$_GET['cparId']." ";
$getCparId = $db->query($sql);
$getCparIdResult = $getCparId->fetch_array();

$sql = "UPDATE qc_cparlotnumber SET cparId = '".$_POST['cparNo']."' WHERE cparId LIKE '".$getCparIdResult['cparId']."' ";
$update = $db->query($sql);

$sqloldValue = $db->query("SELECT cparId, lotNumber FROM qc_cpar WHERE listId = ".$_GET['cparId']." ");
$sqloldValueResult = $sqloldValue->fetch_array();
//cparSection = '".$sqlCombineResult['departmentName']."',

//cparInfoSource = '".$_POST['sourceInfo']."', ********************** remove from available space below after cparDueDate **********************

$updateSQL = "UPDATE qc_cpar SET cparId = '".$_POST['cparNo']."',
								 cparType = '".$_POST['action']."',
								 cparQuantity = '".$_POST['NG']."',
								 cparOwner = '".$_POST['inputName']."',
								 cparSection = '".$_POST['cparSection']."',
								 cparDueDate = '".$_POST['replyDueDate']."',
								 
								 cparInfoSourceSubcon = '".$_POST['union']."',
								 cparInfoSourceRemarks = '".$_POST['customerHide']."',
								 cparDetails = '".$db->real_escape_string($_POST['message1'])."',
								 cparMoreDetails = '".$db->real_escape_string($_POST['cparMoreDetails'])."',
								 detailsOfNonConformance = '".$db->real_escape_string($_POST['detailsOfNonConformance'])."',
								 cparDisposition = '".$dispo."',
								 cparDispositionDetails = '".$_POST['hideText']."',
								 cparCause = '".$_POST['prong']."',
								 cparSourcePerson = '".$_POST['inputName1']."',
								 cparDetectProcess = '".$_POST['detectProcess']."',
								 cparDetectPerson = '".$_POST['inputName2']."',
								 cparDetectDate = '".$_POST['dateDetect']."',
								 cparItemPrice = '".$_POST['itemPrice']."',
								 cparAction = '".$_POST['choices']."',
								 cparInterimAction = '".$db->real_escape_string($_POST['interim'])."',
								 cparAnalysis = '".$analysis."',
								 cparReturnDate = '".$_POST['retdate']."',
								 cparProductionSchedule = '".$_POST['prodnsched']."',
								 cparSubconSchedule = '".$_POST['delivsub']."',
								 cparCustomerSchedule = '".$_POST['delivcust']."',
								 cparRecoveryIncharge = '".$_POST['inputName3']."',
								 cparCauseProcess = '".$db->real_escape_string($_POST['pcause'])."',
								 cparCauseFlowOut = '".$db->real_escape_string($_POST['focause'])."',
								 cparCorrectiveProcess = '".$db->real_escape_string($_POST['process'])."',
								 cparCorrectiveFlowOut = '".$db->real_escape_string($_POST['fo'])."',
								 cparCorrectiveProcessDate = '".$_POST['impdate1']."',
								 cparCorrectiveFlowOutDate = '".$_POST['impdate2']."',
								 cparCorrectiveProcessIncharge = '".$_POST['inputName4']."',
								 cparCorrectiveFlowOutIncharge = '".$_POST['inputName5']."',
								 cparPreventiveAction = '".$db->real_escape_string($_POST['prevaction'])."',
								 cparPreventiveActionIncharge = '".$_POST['inputName6']."',
								 cparPreventiveActionDate = '".$_POST['impdate3']."',
								 cparVerification = '".$db->real_escape_string($_POST['verif'])."',
								 cparVerificationIncharge = '".$_POST['inputName7']."',
								 cparVerificationDate = '".$_POST['verifdate']."',
								 cparStatus = '".$_POST['status']."' WHERE listId = '".$_GET['cparId']."'";
//~ if($_SESSION['idNumber']=='0346')
//~ {
	//~ echo $updateSQL;
	//~ exit(0);
//~ }
$sqlUPDATE = $db->query($updateSQL);

//-start------if old cpar#="cus" and new cpar#="cus"
if (preg_match("/CPAR-CUS/i", $sqloldValueResult['cparId']) and preg_match("/CPAR-CUS/i", $_POST['cparNo']))
{ $lot= explode("-",$sqloldValueResult['lotNumber']);
	$insertSQL2 = "UPDATE sales_rtv SET quantity = ".$_POST['NG']." , remarks = '".$_POST['cparNo']."' where lotNumber= '".$lot[0]."-".$lot[1]."-".$lot[2]."' and remarks like '%".$sqloldValueResult['cparId']."%'";
	$insertSQLResult2 = $db->query($insertSQL2);
}
//-end------if old cpar# = cus and new cpar#=cus

//-start-if new cpar# = cus and old cpar#!=cus
if ((preg_match("/CPAR-CUS/i", $_POST['cparNo'])) and (!preg_match("/CPAR-CUS/i", $sqloldValueResult['cparId'])))
{
	$lot= explode("-",$sqloldValueResult['lotNumber']);
	$insertSQL2 = "INSERT INTO sales_rtv (rtv, lotNumber, quantity, date, remarks, user, ip) VALUES (2,'".$lot[0]."-".$lot[1]."-".$lot[2]."',".$_POST['NG'].",now(),'".$_POST['cparNo']."','".$sesLOG."','".$Users_IP_address."')";
	$insertSQLResult2 = $db->query($insertSQL2);
	
	$sql = "SELECT poId, partId FROM ppic_lotlist WHERE lotNumber LIKE '".$lot[0]."-".$lot[1]."-".$lot[2]."' ";
	$getLotList = $db->query($sql);
	$getLotListResult = $getLotList->fetch_array();
	
	$sql = "SELECT poId FROM system_polist WHERE poId = ".$getLotListResult['poId']." ";
	$getPOID = $db->query($sql);
	if($getPOID->num_rows < 1)
	{	
		$sql = "SELECT partNumber, partName, revisionId, customerId FROM cadcam_parts WHERE partId = ".$getLotListResult['partId']." ";
		$getParts = $db->query($sql);
		$getPartsResult = $getParts->fetch_array();
		
		$sql = "SELECT poNumber, poQuantity, deliveryDate, receiveDate, price FROM sales_polist WHERE poId = ".$getLotListResult['poId']." ";
		$getPOList = $db->query($sql);
		$getPOListResult = $getPOList->fetch_array();
		
		$sql = "SELECT customerAlias FROM sales_customer WHERE customerId = ".$getPartsResult['customerId']." ";
		$getCustomer = $db->query($sql);
		$getCustomerResult = $getCustomer->fetch_array();
		
		$sql = "INSERT INTO system_polist (poId, customerAlias, poNumber, partNumber, revisionId, partName, poQuantity, receiveDate, deliveryDate, poBalance, recoveryDate, remarks, price)
								   VALUES (".$getLotListResult['poId'].", '".$getCustomerResult['customerAlias']."', '".$getPOListResult['poNumber']."', '".$getPartsResult['partNumber']."', '".$getPartsResult['revisionId']."', '".$getPartsResult['partName']."', ".$getPOListResult['poQuantity'].", '".$getPOListResult['receiveDate']."', '".$getPOListResult['deliveryDate']."', ".$getPOListResult['poQuantity'].", '".$getPOListResult['deliveryDate']."', '0', ".$getPOListResult['price'].") ";
		$insert = $db->query($sql);
	}

}
//-end------if new cpar# = cus and old cpar#!=cus

//-start-if old cpar#="cus" and new cpar# !="cus"
if ((preg_match("/CPAR-CUS/i", $sqloldValueResult['cparId'])) and (!preg_match("/CPAR-CUS/i", $_POST['cparNo'])))
{ $lot= explode("-",$sqloldValueResult['lotNumber']);
	$insertSQL2 = "UPDATE sales_rtv SET quantity = 0 , remarks = '".$sqloldValueResult['cparId']."to".$_POST['cparNo']."' where lotNumber= '".$lot[0]."-".$lot[1]."-".$lot[2]."' and remarks like '%".$sqloldValueResult['cparId']."%'";
	$insertSQLResult2 = $db->query($insertSQL2);
}
//-end------if old cpar# = cus and new cpar#=cus

// ---------------------------------------- Reschedule Code 2020-05-14 ---------------------------------------- //
if($_GET['country']==2)
{
	$sql = "SELECT cparId, cparDisposition, cparcparCustomerSchedule FROM qc_cpar WHERE listId = ".$cparListId." LIMIT 1";
	$queryCpar = $db->query($sql);
	if($queryCpar AND $queryCpar->num_rows > 0)
	{
		$resultCpar = $queryCpar->fetch_assoc();
		$cparId = $resultCpar['cparId'];
		$cparDisposition = $resultCpar['cparDisposition'];
		$cparCustomerSchedule = $resultCpar['cparCustomerSchedule'];
		
		if($cparDisposition=='Scrap/Disposal/Replacement' AND $cparCustomerSchedule!='' AND $cparCustomerSchedule!='0000-00-00')
		{
			$lotNumberArray = array();
			$sql = "SELECT lotNumber FROM qc_cparlotnumber WHERE cparId LIKE '".$cparId."' AND status != 2";
			$queryCparLotNumber = $db->query($sql);
			if($queryCparLotNumber AND $queryCparLotNumber->num_rows > 0)
			{
				while($resultCparLotNumber = $queryCparLotNumber->fetch_assoc())
				{
					$lotNumberArray[] = $resultCparLotNumber['lotNumber'];
				}
			}
			
			$dataArray = array();
			$sql = "SELECT DISTINCT poId FROM ppic_lotlist WHERE lotNumber IN('".implode("','",$lotNumberArray)."') AND identifier = 1 AND partLevel = 1";
			$queryLotList = $db->query($sql);
			if($queryLotList AND $queryLotList->num_rows > 0)
			{
				while($resultLotList = $queryLotList->fetch_assoc())
				{
					$dataArray[] = $resultLotList['poId'];
				}
			}
			
			if(count($dataArray) > 0)
			{
				$poIdArray = $dataArray;
				
				$startDate = date('Y-m-d');
				$newDueDate = $cparCustomerSchedule;
				$remarks = 'Reschedule on CPAR EDIT';
				
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
					}
				}
				
				$sql = "UPDATE ppic_workschedule SET recoveryDate = '".$newDueDate."' WHERE lotNumber IN('".implode("','",$lotNumberArray)."')";
				$queryUpdate = $db->query($sql);
			}
		}
	}
}
// ---------------------------------------- Reschedule Code 2020-05-14 ---------------------------------------- //

header("Location: anthony_cparSummary.php");
?>
