<?php
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
//$path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
$javascriptLib = "../Common Data/Libraries/Javascript/";
$templates = "../Common Data/Templates/";
set_include_path($path);	
include('PHP Modules/mysqliConnection.php');
ini_set("display_errors", "on");
require('classes/fpdf.php');
include('PHP Modules/gerald_functions.php');

$idNumber = $_SESSION["idNumber"];

$sql2 = "SELECT lotNumber,quantity,cparId FROM qc_cparlotnumber WHERE listId='".$_GET['listId']."'";
$queryCparLotNumber=$db->query($sql2);
if($queryCparLotNumber AND $queryCparLotNumber->num_rows>0)
{
    $resultCparLotNumber=$queryCparLotNumber->fetch_assoc();
    $cparId = $resultCparLotNumber['cparId'];
    $lotNumber = $resultCparLotNumber['lotNumber'];
    $cparLotNumberQuantity = $resultCparLotNumber['quantity'];
}

$sql = "SELECT cparType,cparOwner,cparSection,cparIssueDate,cparDueDate,cparMaker,cparInfoSource,cparInfoSourceSubcon,cparDetails,cparMoreDetails,cparDisposition,
cparCause,cparSourcePerson,cparDetectProcess,cparDetectPerson,cparDetectDate,cparItemPrice,cparAction,cparInterimAction,cparAnalysis,cparCauseProcess,cparCauseFlowOut,
cparCorrectiveProcess,cparCorrectiveProcessDate,cparCorrectiveProcessIncharge,cparCorrectiveFlowOut,cparCorrectiveFlowOutDate,cparCorrectiveFlowOutIncharge,cparStatus 
FROM qc_cpar WHERE cparId='".$cparId."'";

$queryCpar=$db->query($sql);
if($queryCpar AND $queryCpar->num_rows>0)
{
    $resultCpar=$queryCpar->fetch_assoc();
    $cparType = $resultCpar['cparType'];
    $cparOwner = $resultCpar['cparOwner'];
    $cparSection = $resultCpar['cparSection'];
    $cparIssueDate = $resultCpar['cparIssueDate'];
    $cparDueDate = $resultCpar['cparDueDate'];
    $cparMaker = $resultCpar['cparMaker'];
    $cparInfoSource = $resultCpar['cparInfoSource'];
    $cparInfoSourceSubcon = $resultCpar['cparInfoSourceSubcon'];
    $cparDetails = $resultCpar['cparDetails'];
    $cparMoreDetails = $resultCpar['cparMoreDetails'];
    $cparDisposition = $resultCpar['cparDisposition'];
    $cparCause = $resultCpar['cparCause'];
    $cparSourcePerson = $resultCpar['cparSourcePerson'];
    $cparDetectProcess = $resultCpar['cparDetectProcess'];
    $cparDetectPerson = $resultCpar['cparDetectPerson'];
    $cparDetectDate = $resultCpar['cparDetectDate'];
    $cparItemPrice = $resultCpar['cparItemPrice'];
    $cparAction = $resultCpar['cparAction'];
    $cparInterimAction = $resultCpar['cparInterimAction'];
    $cparAnalysis = $resultCpar['cparAnalysis'];
    $cparCauseProcess = $resultCpar['cparCauseProcess'];
    $cparCauseFlowOut = $resultCpar['cparCauseFlowOut'];
    $cparCorrectiveProcess = $resultCpar['cparCorrectiveProcess'];
    $cparCorrectiveProcessDate = $resultCpar['cparCorrectiveProcessDate'];
    $cparCorrectiveProcessIncharge = $resultCpar['cparCorrectiveProcessIncharge'];
    $cparCorrectiveFlowOut = $resultCpar['cparCorrectiveFlowOut'];
    $cparCorrectiveFlowOutDate = $resultCpar['cparCorrectiveFlowOutDate'];
    $cparCorrectiveFlowOutIncharge = $resultCpar['cparCorrectiveFlowOutIncharge'];
    $cparStatus = $resultCpar['cparStatus'];
}

//variables use for 1st row --------- 1st column-------------------------------------------------------------------------------------------
$cparProduct = 0;
$cparSystem = 0;
if($cparType == 'Product Nonconformance')
{
    $cparProduct = 1;
}
elseif($cparType == 'System Nonconformance')
{
    $cparSystem = 1;
}
//-----------------------------------------------------------------------------------------------------------------------------------------

//variables use for 2nd row --------- 1st column-------------------------------------------------------------------------------------------
$cparSupplier = 0;
$cparInternal = 0;
$cparCustomer = 0;
$cparAutdit = 0;
$cparOthers = 0;
if($cparInfoSource == 'Supplier')
{
    $cparSupplier = 1;
}
elseif($cparInfoSource == 'Subcon')
{
    $cparSupplier = 1;
}
elseif($cparInfoSource == 'Internal')
{
    $cparInternal = 1;
}
elseif($cparInfoSource == 'Customer Claim')
{
    $cparCustomer = 1;
}
elseif($cparInfoSource == 'Internal Audit')
{
    $cparAudit = 1;
}
else
{
    $cparOthers = 1;
}
//-----------------------------------------------------------------------------------------------------------------------------------------

// used for 2nd row for cparMaker --------- 2nd column-------------------------------------------------------------------------------------
if($cparMaker != '')
{
    $preparedBy = $cparMaker;
}
else
{
    $preparedBy = $idNumber;
}
//-----------------------------------------------------------------------------------------------------------------------------------------

//variables use for 3rd row --------- 3rd column-------------------------------------------------------------------------------------------
$cparRework = 0;
$cparDisposal = 0;
$cparReturnSupplier = 0;
$cparSpecialAcceptance = 0;
$cparOthersDisposition = 0;
if($cparDisposition == 'Rework')
{
    $cparRework = 1;
}
elseif($cparDisposition == 'Scrap/Disposal/Replacement')
{
    $cparDisposal = 1;
}
elseif($cparDisposition == 'Return to Supplier/Subcon')
{
    $cparReturnSupplier = 1;
}
elseif($cparDisposition == 'Special Acceptance')
{
    $cparSpecialAcceptance = 1;
}
elseif($cparDisposition == 'Others')
{
    $cparOthersDisposition = 1;
}
//-----------------------------------------------------------------------------------------------------------------------------------------

//variables use for 3rd row --------- 3rd column-------------------------------------------------------------------------------------------
$cparCorrective = 0;
$cparInformation = 0;
if($cparAction == 'Need Corrective Action')
{
    $cparCorrective = 1;
}
elseif($cparAction == 'Information Only')
{
    $cparInformation = 1;
}
//----------------------------------------------------------------------------------------------------------------------------------------

//variables use for 5th row --------- 2nd column-------------------------------------------------------------------------------------------
$cparMan = 0;
$cparMachine = 0;
$cparMethod = 0;
$cparMaterial = 0;
//~ if($cparAnalysis == 'Man')
//~ {
    //~ $cparMan = 1;
//~ }
//~ elseif($cparAnalysis == 'Machine')
//~ {
    //~ $cparMachine = 1;
//~ }
//~ elseif($cparAnalysis == 'Method and Measurement')
//~ {
    //~ $cparMethod = 1;
//~ }
//~ elseif($cparAnalysis == 'Material')
//~ {
    //~ $cparMaterial = 1;
//~ }
if(stristr($cparAnalysis,'Man') !== FALSE)
{
    $cparMan = 1;
}
if(stristr($cparAnalysis,'Machine') !== FALSE)
{
    $cparMachine = 1;
}
if(stristr($cparAnalysis,'Method and Measurement') !== FALSE)
{
    $cparMethod = 1;
}
if(stristr($cparAnalysis,'Material') !== FALSE)
{
    $cparMaterial = 1;
}
//------------------------------------------------------------------------------------------------------------------------------------------

//variables use for font size in 6th row --------- 1st column-------------------------------------------------------------------------------
$cparCauseProcessLength = strlen($cparCauseProcess);

if($cparCauseProcessLength >'300')
	{
		$cparCauseProcessFontSize = 5;
	}
else
	{
		$cparCauseProcessFontSize = 8; 
	}
//-----------------------------------------------------------------------------------------------------------------------------------------

//variables use for font size in 6th row --------- 2nd column-------------------------------------------------------------------------------
$cparCauseFlowOutLength = strlen($cparCauseFlowOut);

if($cparCauseFlowOutLength >'300')
	{
		$cparCauseFlowOutFontSize = 5;
	}
else
	{
		$cparCauseFlowOutFontSize = 8; 
	}
//-----------------------------------------------------------------------------------------------------------------------------------------

//variables use for font size in 7th row --------- 1st column-------------------------------------------------------------------------------
$cparCorrectiveProcessLength = strlen($cparCorrectiveProcess);

if($cparCorrectiveProcessLength >'300')
	{
		$cparCorrectiveProcessFontSize = 5;
	}
else
	{
		$cparCorrectiveProcessFontSize = 8; 
	}
//-----------------------------------------------------------------------------------------------------------------------------------------

//variables use for font size in 7th row --------- 2n column-------------------------------------------------------------------------------
$cparCorrectiveFlowOutLength = strlen($cparCorrectiveFlowOut);

if($cparCorrectiveFlowOutLength >'300')
	{
		$cparCorrectiveFlowOutFontSize = 5;
	}
else
	{
		$cparCorrectiveFlowOutFontSize = 8; 
	}
//-----------------------------------------------------------------------------------------------------------------------------------------

//variables use for 8th row --------- 3rd column-------------------------------------------------------------------------------------------
$cparIssued = 0;
$cparVerified = 0;
$cparClosed = 0;
$cparRepeat = 0;
if($cparStatus == 'Issued')
{
    $cparIssued = 1;
}
elseif($cparStatus == 'Verified' OR $cparStatus == 'Answered')
{
    $cparVerified = 1;
}
elseif($cparStatus == 'Closed')
{
    $cparClosed = 1;
}
else
{
    $cparRepeat = 1;
}
//-----------------------------------------------------------------------------------------------------------------------------------------


$sql3 = "SELECT partId,poId,workingQuantity FROM ppic_lotlist WHERE lotNumber='".$lotNumber."'";
$queryPpicLotList=$db->query($sql3);
if($queryPpicLotList AND $queryPpicLotList->num_rows>0)
{
    $resultPpicLotList=$queryPpicLotList->fetch_assoc();
    $partId = $resultPpicLotList['partId'];
    $poId = $resultPpicLotList['poId'];
    $workingQuantity = $resultPpicLotList['workingQuantity'];
}

$sql4 = "SELECT partNumber,partName,customerId,materialSpecId FROM cadcam_parts WHERE partId='".$partId."' AND partId > 0";
$queryCadcamParts=$db->query($sql4);
if($queryCadcamParts AND $queryCadcamParts->num_rows>0)
{
    $resultCadcamParts=$queryCadcamParts->fetch_assoc();
    $partNumber = $resultCadcamParts['partNumber'];
    $partName = $resultCadcamParts['partName'];
    $customerId = $resultCadcamParts['customerId'];
    $materialSpecId = $resultCadcamParts['materialSpecId'];
}

$sql5 = "SELECT poNumber, customerDeliveryDate FROM sales_polist WHERE poId='".$poId."'";
$querySalesPoList=$db->query($sql5);
if($querySalesPoList AND $querySalesPoList->num_rows>0)
{
    $resultSalesPoList=$querySalesPoList->fetch_assoc();
    $poNumber = $resultSalesPoList['poNumber'];
    $drIssue = $resultSalesPoList['customerDeliveryDate'];
}

$sql6 = "SELECT customerAlias FROM sales_customer WHERE customerId='".$customerId."'";
$queryCustomer=$db->query($sql6);
if($queryCustomer AND $queryCustomer->num_rows>0)
{
    $resultCustomer=$queryCustomer->fetch_assoc();
    $customerAlias = $resultCustomer['customerAlias'];
}

$sql8 = "SELECT metalThickness,materialTypeId FROM cadcam_materialspecs WHERE materialSpecId='".$materialSpecId."'";
$queryMaterialSpecs=$db->query($sql8);
if($queryMaterialSpecs AND $queryMaterialSpecs->num_rows>0)
{
    $resultMaterialSpecs=$queryMaterialSpecs->fetch_assoc();
    $metalThickness = $resultMaterialSpecs['metalThickness'];
    $materialTypeId = $resultMaterialSpecs['materialTypeId'];
}

$sql9 = "SELECT materialType FROM engineering_materialtype WHERE materialTypeId='".$materialTypeId."'";
$queryMaterialType=$db->query($sql9);
if($queryMaterialType AND $queryMaterialType->num_rows>0)
{
    $resultMaterialType=$queryMaterialType->fetch_assoc();
    $materialType = $resultMaterialType['materialType'];
}

if(strstr($cparId,'CPAR-CUS')!==FALSE)
{
	$sql7 = "SELECT drNumber FROM purchasing_drcustomer WHERE lotNumber='".$lotNumber."'";
	$queryDrCustomer=$db->query($sql7);
	if($queryDrCustomer AND $queryDrCustomer->num_rows>0)
	{
		$resultDrCustomer=$queryDrCustomer->fetch_assoc();
		$drNumber = $resultDrCustomer['drNumber'];
	}

	$sql10 = "SELECT issue FROM purchasing_drdetails WHERE drNumber='".$drNumber."'";
	$queryDrDetails=$db->query($sql10);
	if($queryDrDetails AND $queryDrDetails->num_rows>0)
	{
		$resultDrDetails=$queryDrDetails->fetch_assoc();
		$drIssue = $resultDrDetails['issue'];
	}
}

$sql11 = "SELECT firstName, surName, middleName FROM hr_employee WHERE idNumber = '".$preparedBy."'";
$queryEmployee=$db->query($sql11);
if($queryEmployee AND $queryEmployee->num_rows>0)
{
    $resultEmployee=$queryEmployee->fetch_assoc();
    $firstName = $resultEmployee['firstName'];
    $middleName = $resultEmployee['middleName'];
    $lastName = $resultEmployee['surName'];
}


$signatureId = '';
$positionId = '';
$sql12 = "SELECT employeeId,position FROM hr_employee WHERE idNumber LIKE '".$preparedBy."' ";	
$queryEmployeeId=$db->query($sql12);
if($queryEmployeeId AND $queryEmployeeId->num_rows>0)
{
    $resultEmployeeId=$queryEmployeeId->fetch_assoc();
    $signatureId = $resultEmployeeId['employeeId'];
    $positionId = $resultEmployeeId['position'];
}

$position = '';
$sql13 = "SELECT positionName FROM hr_positions WHERE positionId='".$positionId."' ";	
$queryPosition=$db->query($sql13);
if($queryPosition AND $queryPosition->num_rows>0)
{
    $resultPosition=$queryPosition->fetch_assoc();
    $position = $resultPosition['positionName'];
}

$nGLotArray = array();
$lastIssueDate = $lastCparType = $firstNGLot = $secondNGLot = '';
$sql = "
	SELECT SUBSTRING_INDEX(a.cparId,'-',2) as cparType, a.lotNumber, c.cparIssueDate FROM qc_cparlotnumber as a
	INNER JOIN ppic_lotlist as b ON b.lotNumber = a.lotNumber
	INNER JOIN qc_cpar as c ON c.cparId = a.cparId
	WHERE b.partId = ".$partId." AND b.identifier = 1 AND c.cparDetails LIKE '".$cparDetails."' ORDER BY c.cparIssueDate
";
$queryFirstNgLot = $db->query($sql);
if($queryFirstNgLot AND $queryFirstNgLot->num_rows > 0)
{
	while($resultFirstNgLot = $queryFirstNgLot->fetch_assoc())
	{
		if($lastIssueDate!=$resultFirstNgLot['cparIssueDate'] AND ($lastCparType==$resultFirstNgLot['cparType'] OR $lastCparType==''))
		{
			$nGLotArray[] = $resultFirstNgLot['lotNumber'];
		}
		
		$lastIssueDate = $resultFirstNgLot['cparIssueDate'];
		$lastCparType = $resultFirstNgLot['cparType'];
		if(count($nGLotArray) > 2)	break;
	}
	$firstNGLot = $nGLotArray[0];
	$secondNGLot = $nGLotArray[1];
}

//Used to convert date in  1st row --------- 3rd column-----------------------------------------------------------------------------------
if($drIssue == "0000-00-00" OR $drIssue == "")
{
    $drIssue = "";
}
else
{
    $drIssue = date('F d, Y',strtotime($drIssue));
}
//-----------------------------------------------------------------------------------------------------------------------------------------

//Used to convert date in  1st row --------- 3rd column------------------------------------------------------------------------------------
if($cparIssueDate == "0000-00-00")
{
    $cparIssueDate = "";
}
else
{
    $cparIssueDate = date('F d, Y',strtotime($cparIssueDate));
}
//-----------------------------------------------------------------------------------------------------------------------------------------


//Used to convert date in  2nd row --------- 1st column------------------------------------------------------------------------------------
if($cparDueDate == "0000-00-00")
{
    $cparDueDate = "";
}
else
{
    $cparDueDate = date('F d, Y',strtotime($cparDueDate));
}

//-----------------------------------------------------------------------------------------------------------------------------------------

//Used to convert date in  7th row --------- 1st column------------------------------------------------------------------------------------
if($cparCorrectiveProcessDate == "0000-00-00")
{
    $cparCorrectiveProcessDate = "";
}
else
{
    $cparCorrectiveProcessDate = date('F d, Y',strtotime($cparCorrectiveProcessDate));
}

//-----------------------------------------------------------------------------------------------------------------------------------------

//Used to convert date in  7th row --------- 2nd column------------------------------------------------------------------------------------
if($cparCorrectiveFlowOutDate == "0000-00-00")
{
    $cparCorrectiveFlowOutDate = "";
}
else
{
    $cparCorrectiveFlowOutDate = date('F d, Y',strtotime($cparCorrectiveFlowOutDate));
}

//-----------------------------------------------------------------------------------------------------------------------------------------


$pdf = new FPDF('P','mm','A4');
$pdf->SetLeftMargin(17);
$pdf->SetTopMargin(10);
$pdf->SetAutoPageBreak(off);
$pdf->AddPage();

//image logo of arktech----------------------------------------------------------------------------------------------------------------------
$pdf->Image('../Common Data/Templates/images/Ared.jpg',17, 15, 10, 7);
$pdf->Cell(45,5,"",'',0,'');
//-------------------------------------------------------------------------------------------------------------------------------------------

$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

//title-----------------------------------------------------------------------------------------------------------------------
$pdf->SetFont('Arial','B',15);

$pdf->Cell(180,6,"CORRECTIVE ACTION REPORT",'TLR',0,'C');
$pdf->Ln();
//-----------------------------------------------------------------------------------------------------------------------------

//1st row borders--------------------------------------------------------------------------------------------------------------
$pdf->SetFont('Arial','B',8);
$pdf->Cell(45,10,"",'TLB',0,'');
$pdf->Cell(70,10,"",'TLRB',0,'');
$pdf->Cell(65,10,"",'TLRB',0,'');
$pdf->Ln();
//-----------------------------------------------------------------------------------------------------------------------------

//2nd row borders--------------------------------------------------------------------------------------------------------------
$pdf->Cell(115,4,"",'L',0,'');
$pdf->SetFont('Arial','I',7);
$pdf->Cell(65,4,"To be filled-up by issuing party",'LRB',0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B',8);
$pdf->Cell(115,16,"",'LRB',0,'');
$pdf->Cell(65,16,"",'LRB',0,'');
$pdf->Ln();
//------------------------------------------------------------------------------------------------------------------------------

//3rd row borders---------------------------------------------------------------------------------------------------------------
$pdf->Cell(48,4,"DESCRIPTION OF THE PROBLEM:",'BL',0,'L');
$pdf->Cell(32,4,"",'R',0,'L');
$pdf->Cell(20,4,"DISPOSITION:",'B',0,'L');
$pdf->Cell(15,4,"",'',0,'L');
$pdf->SetFont('Arial','I',7);
$pdf->Cell(65,4,"To be filled-up by issuing party.",'TLR',0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B',8);
$pdf->Cell(80,25,"",'LRB',0,'');
$pdf->Cell(35,25,"",'RB',0,'');
$pdf->Cell(65,25,"",'TLRB',0,'');
$pdf->Ln();
//-----------------------------------------------------------------------------------------------------------------------------

//4th row borders--------------------------------------------------------------------------------------------------------------
$pdf->Cell(50,4,"DETAILS OF NON-CONFORMANCE:",'LB',0,'');
$pdf->Cell(30,4,"",'',0,'');
$pdf->Cell(34,4,"RECOVERY SCHEDULE:",'LB',0,'');
$pdf->SetFont('Arial','I',5.5);
$pdf->Cell(26,4,"(Planning Department Use)",'',0,'');

$pdf->SetFont('Arial','B',8);
$pdf->Cell(16,4,"REMARKS:",'L',0,'');
$pdf->SetFont('Arial','I',5.5);
$pdf->Cell(24,4,"(Planning in Charge)",'R',0,'');
$pdf->Ln();
$pdf->Cell(80,20,"",'LRB',0,'');
$pdf->Cell(60,20,"",'RB',0,'');
$pdf->Cell(40,20,"",'TRB',0,'');
$pdf->Ln();
//-----------------------------------------------------------------------------------------------------------------------------

//5th row borders--------------------------------------------------------------------------------------------------------------
$pdf->SetFont('Arial','B',8);
$pdf->Cell(24.5,6,"INTERIM ACTION:",'L',0,'');
$pdf->SetFont('Arial','I',5.5);
$pdf->Cell(120.5,6,"(Containment/Sorting Result/Attendance Record)",'R',0,'');
$pdf->SetFont('Arial','U',8);
$pdf->Cell(35,6,"4M ANALYSIS:",'LR',0,'C');
$pdf->Ln();
$pdf->Cell(145,30,"",'TLRB',0,'');
$pdf->Cell(35,30,"",'LRB',0,'');
$pdf->Ln();
//-----------------------------------------------------------------------------------------------------------------------------

//6th row borders--------------------------------------------------------------------------------------------------------------
$pdf->SetFont('Arial','B',8);
$pdf->Cell(28,6,"CAUSE OF DEFECT:",'L',0,'L');
$pdf->SetFont('Arial','I',5.5);
$pdf->Cell(152,6,"(Concern Section)",'R',0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','I',8);
$pdf->Cell(26,6,"PROCESS CAUSE:",'TL',0,'L');
$pdf->SetFont('Arial','I',5.5);
$pdf->Cell(64,6,"(Why/How defect was made?)",'T',0,'L');
$pdf->SetFont('Arial','I',8);
$pdf->Cell(27,6,"FLOW OUT CAUSE:",'TL',0,'L');
$pdf->SetFont('Arial','I',5.5);
$pdf->Cell(63,6,"(Why/How defect Leaked out?)",'TR',0,'L');
$pdf->Ln();
$pdf->Cell(90,35,"",'TLRB',0,'');
$pdf->Cell(90,35,"",'TRB',0,'');
$pdf->Ln();
//----------------------------------------------------------------------------------------------------------------------------

//7th row borders--------------------------------------------------------------------------------------------------------------
$pdf->SetFont('Arial','B',8);
$pdf->Cell(31.5,6,"CORRECTIVE ACTION:",'LB',0,'L');
$pdf->SetFont('Arial','I',5.5);
$pdf->Cell(148.5,6,"(For improvement of the Organization)",'RB',0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','I',8);
$pdf->Cell(27,6,"PROCESS ACTION:",'L',0,'L');
$pdf->SetFont('Arial','I',5.5);
$pdf->Cell(63,6,"(Evidence/Result Attendance Record)",'R',0,'L');
$pdf->SetFont('Arial','I',8);
$pdf->Cell(28,6,"FLOW OUT ACTION:",'',0,'L');
$pdf->SetFont('Arial','I',5.5);
$pdf->Cell(62,6,"(Evidence/Result Attendance Record)",'R',0,'L');
$pdf->Ln();
$pdf->Cell(90,35,"",'TLRB',0,'');
$pdf->Cell(90,35,"",'TRB',0,'');
$pdf->Ln();
//-----------------------------------------------------------------------------------------------------------------------------

//8th row borders--------------------------------------------------------------------------------------------------------------
$pdf->SetFont('Arial','B',8);
$pdf->Cell(64,6,"CLOSURE/VERIFICATION OF EFFECTIVENESS",'L',0,'');
$pdf->SetFont('Arial','I',5.5);
$pdf->Cell(51,6,"(2 consecutive LOT NUMBER)",'R',0,'');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,6,"VERIFIED BY:",'LRB',0,'C');
$pdf->Cell(30,6,"STATUS:",'LRB',0,'C');
$pdf->Ln();
$pdf->Cell(115,20,"",'LRB',0,'');
$pdf->Cell(35,20,"",'TLRB',0,'');
$pdf->Cell(30,20,"",'TLRB',0,'');
$pdf->Ln();
//----------------------------------------------------------------------------------------------------------------------------

//Footer----------------------------------------------------------------------------------------------------------------------
$pdf->SetFont('Arial','I',5.5);
$pdf->Cell(180,3.5,"NOTE:     Please use additional sheet for evidence/attachment",'',0,'');
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(130,3.5,"QC-001-6",'',0,'');
$pdf->Cell(30,3.5,"EFFECTIVITY DATE:SEPTEMBER 19,2018",'',0,'');
//--------------------------------------------------------------------------------------------------------------------------


//Logo----------------------------------------------------------------------------------------------------------------------
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(27.5, 15);
$pdf->Cell(5,5,"Arktech Philippines, Incorporated",'',0,'');
$pdf->SetFont('Arial','',7);
$pdf->SetXY(27.5, 18);
$pdf->Cell(5,5,"Quality Assurance Section",'',0,'');
//------------------------------------------------------------------------------------------------------------------------------------

//Cpar Id Number----------------------------------------------------------------------------------------------------------------------
$pdf->SetFont('Arial','U',8);
$pdf->SetXY(167, 20);
$pdf->Cell(27,5,"$cparId",'',0,'C');
//---------------------------------------------------------------------------------------------------------------------------

//1st row ---- 1st column
$pdf->SetFont('Arial','',7); //-------------------------------FONT DETAILS----------------------------------------------------
$pdf->SetXY(21, 32.5);
$pdf->Cell(3,2.5,"",'TLRB',0,'',$cparProduct);
$pdf->SetXY(26, 31);
$pdf->Cell(30,6,"Product Nonconformance",'',0,'');

$pdf->SetXY(21, 36.5);
$pdf->Cell(3,2.5,"",'TLRB',0,'',$cparSystem);
$pdf->SetXY(26, 35);
$pdf->Cell(30,6,"System Nonconformance",'',0,'');
//---------------------------------------------------------------------------------------------------------------------

//1st row ---- 2nd column----------------------------------------------------------------------------------------------
$pdf->SetXY(65, 31);
$pdf->Cell(20,6,"Attention To:",'',0,'');
$pdf->SetXY(95, 31);
$pdf->Cell(37,5,"",'B',0,'C');
$pdf->SetXY(95, 32);
$pdf->Cell(37,5,"$cparOwner",'',0,'C');

$pdf->SetXY(65, 35);
$pdf->Cell(20,6,"Section:",'',0,'');
$pdf->SetXY(95, 35);
$pdf->Cell(37,5,"",'B',0,'C');
$pdf->SetXY(95, 36);
$pdf->Cell(37,5,"$cparSection",'',0,'C');
//---------------------------------------------------------------------------------------------------------------------

//1st row ---- 3rd column---------------------------------------------------------------------------------------------
$pdf->SetXY(133,31);
$pdf->Cell(20,6,"Issued Date:",'',0,'');
$pdf->SetXY(170,31);
$pdf->Cell(27,5,"",'B',0,'C');
$pdf->SetXY(170,32);
$pdf->Cell(27,5,"$cparIssueDate",'',0,'C');

$pdf->SetXY(133,35);
$pdf->Cell(20,6,"Reply Due Date:",'',0,'');
$pdf->SetXY(170,35);
$pdf->Cell(27,5,"",'B',0,'C');
$pdf->SetXY(170,36);
$pdf->Cell(27,5,"$cparDueDate",'',0,'C');
//---------------------------------------------------------------------------------------------------------------------

//2nd row ---- 1st column----------------------------------------------------------------------------------------------
$pdf->SetXY(17,40);
$pdf->Cell(30,6,"Source of Information:",'',0,'');

$pdf->SetXY(45,42);
$pdf->Cell(3,2.5,"",'TLRB',0,'',$cparSupplier);
$pdf->SetXY(49, 40.5);
$pdf->Cell(30,6,"SUPPLIER / SUB-CON",'',0,'');

$pdf->SetXY(45,46);
$pdf->Cell(3,2.5,"",'TLRB',0,'',$cparInternal);
$pdf->SetXY(49, 44.5);
$pdf->Cell(30,6,"INTERNAL",'',0,'');

$pdf->SetFont('Arial','',6);

$pdf->SetXY(62, 44.2);
$pdf->Cell(30,6,"( In-process / Outgoing Inspection / Incoming Inspection)",'',0,'');

$pdf->SetFont('Arial','',7);

$pdf->SetXY(45,50);
$pdf->Cell(3,2.5,"",'TLRB',0,'',$cparCustomer);
$pdf->SetXY(49, 48.5);
$pdf->Cell(30,6,"CUSTOMER CLAIM",'',0,'');

$pdf->SetXY(45,54);
$pdf->Cell(3,2.5,"",'TLRB',0,'',$cparAudit);
$pdf->SetXY(49, 52.5);
$pdf->Cell(30,6,"INTERNAL AUDIT",'',0,'');

$pdf->SetXY(45,58);
$pdf->Cell(3,2.5,"",'TLRB',0,'',$cparOthers);
$pdf->SetXY(49, 56.5);
$pdf->Cell(30,6,"OTHERS",'',0,'');

$pdf->SetFont('Arial','',7);

$pdf->SetXY(110, 41);
$pdf->SetXY(95, 40);
$pdf->Cell(30,6,"Del. Date:",'',0,'');
$pdf->SetXY(110, 41);
$pdf->Cell(22,5,"$drIssue",'',0,'C');
$pdf->SetXY(110, 40);
$pdf->Cell(22,5,"",'B',0,'');
//---------------------------------------------------------------------------------------------------------------------

//2nd row ---- 2nd column----------------------------------------------------------------------------------------------
$pdf->SetXY(133, 45);
$pdf->Cell(30,6,"Prepared by:",'',0,'');
$pdf->SetXY(156, 45);
$pdf->Cell(31,5,"$firstName"." $middleName[0]. "."$lastName",'',0,'L');
$pdf->SetXY(156, 44);
$pdf->Cell(31,5,"",'B',0,'');
$pdf->SetXY(156, 49);
$pdf->Cell(23,5,"$position",'',0,'C');
$pdf->SetXY(156, 48);
$pdf->Cell(31,5,"",'B',0,'');

if(file_exists('../11-A Employee List/esignatures/'.$signatureId.'.jpg'))
{
    $pdf->Image('../11-A Employee List/esignatures/'.$signatureId.'.jpg',188, 46, 6.5, 6.5);
}


$pdf->SetXY(133,53);
$pdf->Cell(30,6,"Checked by:",'',0,'');
$pdf->SetXY(156,53);
$pdf->Cell(31,5,"Roldan H. Macalindro",'',0,'L');
$pdf->SetXY(156,52);
$pdf->Cell(31,5,"",'B',0,'');
$pdf->SetXY(156,57);
$pdf->Cell(25.5,5,"QA/QC Supervisor",'',0,'C');
//---------------------------------------------------------------------------------------------------------------------

//3rd row ---- 1st column----------------------------------------------------------------------------------------------
$pdf->SetXY(17, 63.5);
$pdf->Cell(30,6,"CUSTOMER:",'',0,'');
$pdf->SetXY(49, 65);
$pdf->Cell(48,4,"$customerAlias",'',0,'C');
$pdf->SetXY(49, 64);
$pdf->Cell(48,4,"",'B',0,'');

$pdf->SetXY(17, 66.5);
$pdf->Cell(30,6,"SUPPLIER/SUBCON:",'',0,'');
$pdf->SetXY(49, 68);
$pdf->Cell(48,4,"$cparInfoSourceSubcon",'',0,'C');
$pdf->SetXY(49, 67);
$pdf->Cell(48,4,"",'B',0,'');

$pdf->SetXY(17, 69.5);
$pdf->Cell(30,6,"PART NAME:",'',0,'');
$pdf->SetXY(49, 71);
$pdf->Cell(48,4,"$partName",'',0,'C');
$pdf->SetXY(49, 70);
$pdf->Cell(48,4,"",'B',0,'');

$pdf->SetXY(17, 72.5);
$pdf->Cell(30,6,"PART NUMBER:",'',0,'');
$pdf->SetXY(49, 74);
$pdf->Cell(48,4,"$partNumber",'',0,'C');
$pdf->SetXY(49, 73);
$pdf->Cell(48,4,"",'B',0,'');

$pdf->SetXY(17, 75.5);
$pdf->Cell(30,6,"PO # / DR #:",'',0,'');
$pdf->SetXY(49, 77);
$pdf->Cell(48,4,"$poNumber",'',0,'C');
$pdf->SetXY(49, 76);
$pdf->Cell(48,4,"",'B',0,'');

$pdf->SetXY(17, 78.5);
$pdf->Cell(30,6,"LOT No. . Lot Qty.:",'',0,'');
$pdf->SetXY(49, 80);
$pdf->Cell(48,4,"$lotNumber"." / "."$workingQuantity",'',0,'C');
$pdf->SetXY(49, 79);
$pdf->Cell(48,4,"",'B',0,'');

$pdf->SetXY(17, 81.5);
$pdf->Cell(30,6,"NG QTY.:",'',0,'');
$pdf->SetXY(49, 83);
$pdf->Cell(48,4,"$cparLotNumberQuantity",'',0,'C');
$pdf->SetXY(49, 82);
$pdf->Cell(48,4,"",'B',0,'');
//---------------------------------------------------------------------------------------------------------------------

//3rd row -----2nd column----------------------------------------------------------------------------------------------
$pdf->SetXY(98,68);
$pdf->Cell(3,2.5,"",'TLRB',0,'',$cparRework);
$pdf->SetXY(101,67.5);
$pdf->Cell(48,4,"Rework",'',0,'');

$pdf->SetXY(98,72);
$pdf->Cell(3,2.5,"",'TLRB',0,'',$cparDisposal);
$pdf->SetXY(101,71.5);
$pdf->Cell(48,4,"Disposal",'',0,'');

$pdf->SetXY(98,76);
$pdf->Cell(3,2.5,"",'TLRB',0,'',$cparReturnSupplier);
$pdf->SetXY(101,75.5);
$pdf->Cell(48,4,"Return to Supplier",'',0,'');

$pdf->SetXY(98,80);
$pdf->Cell(3,2.5,"",'TLRB',0,'',$cparSpecialAcceptance);
$pdf->SetXY(101,79.5);
$pdf->Cell(48,4,"Special Acceptance",'',0,'');

$pdf->SetXY(98,84);
$pdf->Cell(3,2.5,"",'TLRB',0,'',$cparOthersDisposition);
$pdf->SetXY(101,83.5);
$pdf->Cell(48,4,"Others:",'',0,'');
$pdf->SetXY(111,83);
$pdf->Cell(21,4,"",'B',0,'');
//---------------------------------------------------------------------------------------------------------------------

//3rd row ---- 3rd column----------------------------------------------------------------------------------------------
$pdf->SetXY(132, 64);
$pdf->Cell(30,6,"Process cause of N.G.:",'',0,'');
$pdf->SetXY(158, 63.5);
$pdf->Cell(39,5,"",'B',0,'C');
$pdf->SetXY(158, 64.5);
$pdf->Cell(39,5,"$cparCause",'',0,'C');

$pdf->SetXY(132, 67);
$pdf->Cell(30,6,"Concerned Person:",'',0,'');
$pdf->SetXY(158, 66.5);
$pdf->Cell(39,5,"",'B',0,'C');
$pdf->SetXY(158, 67.5);
$pdf->Cell(39,5,"$cparSourcePerson",'',0,'C');

$pdf->SetXY(132, 73);
$pdf->Cell(30,6,"Detecting Process:",'',0,'');
$pdf->SetXY(158, 72.5);
$pdf->Cell(39,5,"",'B',0,'C');
$pdf->SetXY(158, 73.5);
$pdf->Cell(39,5,"$cparDetectProcess",'',0,'C');

$pdf->SetXY(132, 76);
$pdf->Cell(30,6,"Detecting Person:",'',0,'');
$pdf->SetXY(158, 75.5);
$pdf->Cell(39,5,"",'B',0,'C');
$pdf->SetXY(158, 76.5);
$pdf->Cell(39,5,"$cparDetectPerson",'',0,'C');

$pdf->SetXY(132, 79);
$pdf->Cell(30,6,"Date Detected:",'',0,'');
$pdf->SetXY(158, 78.5);
$pdf->Cell(39,5,"",'B',0,'C');
$pdf->SetXY(158, 79.5);
$pdf->Cell(39,5,"$cparDetectDate",'',0,'C');

$pdf->SetXY(132, 85);
$pdf->Cell(30,6,"Item Price:($/Php/Yen)",'',0,'');
$pdf->SetXY(158, 84.5);
$pdf->Cell(12,5,"$cparItemPrice",'B',0,'C');

$pdf->SetFont('Arial','',4.4);
$pdf->SetXY(171,84);
$pdf->Cell(2.5,2.5,"",'TLRB',0,'',$cparCorrective);
$pdf->SetXY(173.5,84.5);
$pdf->Cell(2,2,"NEED CORRECTIVE ACTION",'',0,'');

$pdf->SetXY(171,87);
$pdf->Cell(2.5,2.5,"",'TLRB',0,'',$cparInformation);
$pdf->SetXY(173.5,87.5);
$pdf->Cell(2,3,"INFORMATION ONLY",'',0,'');
//---------------------------------------------------------------------------------------------------------------------

//4th row ---- 1st column----------------------------------------------------------------------------------------------
$pdf->SetFont('Arial','',7);
$cparDetails .= ($cparMoreDetails!='') ? "\n".$cparMoreDetails : '';
$pdf->SetXY(20, 95);
$pdf->MultiCell(80,4,"$cparDetails",'','L',0);
//---------------------------------------------------------------------------------------------------------------------

//4th row ---- 2nd column----------------------------------------------------------------------------------------------
$pdf->SetFont('Arial','',7);

$pdf->SetXY(97,95);
$pdf->Cell(20,4,"Return Date:",'',0,'');
$pdf->SetXY(132, 94.5);
$pdf->Cell(25,4,"",'B',0,'');

$pdf->SetXY(97, 98);
$pdf->Cell(20,4,"Lot Number:",'',0,'');
$pdf->SetXY(132, 97.5);
$pdf->Cell(25,4,"",'B',0,'');

$pdf->SetXY(97,101);
$pdf->Cell(20,4,"Material Specs.:",'',0,'');
$pdf->SetXY(132, 101.4);
$pdf->Cell(25,4,"$materialType"." t "."$metalThickness",'',0,'C');
$pdf->SetXY(132, 100.5);
$pdf->Cell(25,4,"",'B',0,'');

$pdf->SetXY(97,104);
$pdf->Cell(20,4,"Prodn. Sched.:",'',0,'');
$pdf->SetXY(132, 103.5);
$pdf->Cell(25,4,"",'B',0,'');

$pdf->SetXY(97,107);
$pdf->Cell(20,4,"Deliv. To Subcon/Supplier:",'',0,'');
$pdf->SetXY(132, 106.5);
$pdf->Cell(25,4,"",'B',0,'');

$pdf->SetXY(97,110.5);
$pdf->Cell(20,4,"Delivery to Customer:",'',0,'');
$pdf->SetXY(132, 110);
$pdf->Cell(25,4,"",'',0,'');
//---------------------------------------------------------------------------------------------------------------------

//5th row ---- 1st column----------------------------------------------------------------------------------------------
$pdf->SetFont('Arial','',7);
$pdf->SetXY(20, 122);
$pdf->MultiCell(100,4,"$cparInterimAction",'','L',0);
//---------------------------------------------------------------------------------------------------------------------

//5th row ---- 2nd column----------------------------------------------------------------------------------------------
$pdf->SetFont('Arial','',6);
$pdf->SetXY(164,122);
$pdf->Cell(3,2.5,"",'TLRB',0,'',$cparMan);
$pdf->SetXY(168,122);
$pdf->Cell(25,3,"MAN",'',0,'C');

$pdf->SetXY(164,129);
$pdf->Cell(3,2.5,"",'TLRB',0,'',$cparMachine);
$pdf->SetXY(168,129);
$pdf->Cell(25,3,"MACHINE",'',0,'C');

$pdf->SetXY(164,135);
$pdf->Cell(3,2.5,"",'TLRB',0,'',$cparMethod);
$pdf->SetXY(168,135);
$pdf->Cell(2,3,"METHOD/MEASUREMENT",'',0,'');

$pdf->SetXY(164,142);
$pdf->Cell(3,2.5,"",'TLRB',0,'',$cparMaterial);
$pdf->SetXY(168,142);
$pdf->Cell(25,3,"MATERIAL",'',0,'C');
//---------------------------------------------------------------------------------------------------------------------

//6th row ---- 1st column----------------------------------------------------------------------------------------------
$pdf->SetFont('Arial','',$cparCauseProcessFontSize);
$pdf->SetXY(20, 162);
$pdf->MultiCell(88,2.9,"$cparCauseProcess",'','L',0);
//---------------------------------------------------------------------------------------------------------------------

//6th row ---- 2nd column----------------------------------------------------------------------------------------------
$pdf->SetFont('Arial','',$cparCauseFlowOutFontSize);
$pdf->SetXY(110,162);
$pdf->MultiCell(88,2.9,"$cparCauseFlowOut",'','L',0);
//---------------------------------------------------------------------------------------------------------------------

//7th row ---- 1st column----------------------------------------------------------------------------------------------
$pdf->SetFont('Arial','',$cparCorrectiveProcessFontSize);
$pdf->SetXY(20, 210);
$pdf->MultiCell(88,2.9,"$cparCorrectiveProcess",'','L',0);

$pdf->SetFont('Arial','I',6);

$pdf->SetXY(17,237.5);
$pdf->Cell(20,4,"Implementation Date:",'',0,'');
$pdf->SetXY(40,238);
$pdf->Cell(20,4,"$cparCorrectiveProcessDate",'',0,'C');
$pdf->SetXY(40,237);
$pdf->Cell(20,4,"",'B',0,'');

$pdf->SetXY(68,237.5);
$pdf->Cell(20,4,"In charge:",'',0,'');
$pdf->SetXY(82,238);
$pdf->Cell(20,4,"$cparCorrectiveProcessIncharge",'',0,'C');
$pdf->SetXY(82,237);
$pdf->Cell(20,4,"",'B',0,'');


//---------------------------------------------------------------------------------------------------------------------

//7th row ---- 2nd column----------------------------------------------------------------------------------------------
$pdf->SetFont('Arial','',$cparCorrectiveFlowOutFontSize);

$pdf->SetXY(110,210);
$pdf->MultiCell(88,2.9,"$cparCorrectiveFlowOut",'','L',0);

$pdf->SetFont('Arial','I',6);

$pdf->SetXY(107,237.5);
$pdf->Cell(20,4,"Implementation Date:",'',0,'');
$pdf->SetXY(130,238);
$pdf->Cell(20,4,"$cparCorrectiveFlowOutDate",'',0,'C');
$pdf->SetXY(130,237);
$pdf->Cell(20,4,"",'B',0,'');

$pdf->SetXY(160,237.5);
$pdf->Cell(20,4,"In charge:",'',0,'');
$pdf->SetXY(175,238);
$pdf->Cell(20,4,"$cparCorrectiveFlowOutIncharge",'',0,'C');
$pdf->SetXY(175,237);
$pdf->Cell(20,4,"",'B',0,'');
//---------------------------------------------------------------------------------------------------------------------

//8th row ---- 1st column----------------------------------------------------------------------------------------------
$pdf->SetXY(17,250);
$pdf->Cell(20,4,"LOT 1",'',0,'');
$pdf->SetXY(28,249.5);
$pdf->Cell(20,4,$firstNGLot,'B',0,'');

$pdf->SetXY(17,254);
$pdf->Cell(20,4,"LOT 2",'',0,'');
$pdf->SetXY(28,253.5);
$pdf->Cell(20,4,$secondNGLot,'B',0,'');

$pdf->SetXY(17,261);
$pdf->Cell(13,2,"REMARKS:",'B',0,'');
$pdf->SetFont('Arial','I',5.7);
$pdf->SetXY(30,261);
$pdf->Cell(12,2,"(How was the claim handled?Is the Root Cause was determine and measure after corrective action was made?)",'',0,'');
//---------------------------------------------------------------------------------------------------------------------

//8th row ---- 2nd column----------------------------------------------------------------------------------------------
$pdf->SetFont('Arial','I',6);

$pdf->SetXY(140,253);
$pdf->Cell(12,2,"QA/QC MANAGER",'',0,'');

$pdf->SetXY(138.5,261);
$pdf->Cell(12,2,"QA/QC SUPERVISOR",'',0,'');

$pdf->SetXY(145,268);
$pdf->Cell(12,2,"DATE:",'',0,'');
//---------------------------------------------------------------------------------------------------------------------

//8th row ---- 3rd column-----------------------------------------------------------------------------------------------
$pdf->SetFont('Arial','',6);

$pdf->SetXY(170,253);
$pdf->Cell(2.5,2,"",'TLRB',0,'',$cparIssued);
$pdf->SetXY(176,253);
$pdf->Cell(2.5,2,"ISSUED",'',0,'');

$pdf->SetXY(170,256);
$pdf->Cell(2.5,2,"",'TLRB',0,'',$cparVerified);
$pdf->SetXY(176,256);
$pdf->Cell(2.5,2,"VERIFIED",'',0,'');

$pdf->SetXY(170,259);
$pdf->Cell(2.5,2,"",'TLRB',0,'',$cparClosed);
$pdf->SetXY(176,259);
$pdf->Cell(2.5,2,"CLOSED",'',0,'');

$pdf->SetXY(170,262);
$pdf->Cell(2.5,2,"",'TLRB',0,'',$cparRepeat);
$pdf->SetXY(176,262);
$pdf->Cell(2.5,2,"REPEAT",'',0,'');
//---------------------------------------------------------------------------------------------------------------------


$pdf->Output();
?>
