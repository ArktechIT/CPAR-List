<?php
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
	//$path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
	$javascriptLib = "../Common Data/Libraries/Javascript/";
	$templates = "../Common Data/Libraries/Javascript/";
	set_include_path($path);    
	include('PHP Modules/mysqliConnection.php');
	include('PHP Modules/anthony_wholeNumber.php');
	include('PHP Modules/anthony_retrieveText.php');
	include('PHP Modules/gerald_functions.php');
	include('PHP Modules/rose_prodfunctions.php');
	ini_set("display_errors", "on");
	
	$allStatus = (isset($_POST['allStatus'])) ? $_POST['allStatus'] : '' ;
	$allParts = (isset($_POST['allParts'])) ? $_POST['allParts'] : '' ;
	
	$cparTypeArray = (isset($_POST['cparTypeArray'])) ? $_POST['cparTypeArray'] : array('CPAR-CUS','CPAR-INT','CPAR-SUB','CPAR-SUP','CPAR-SYS') ;
	
    $dateStart = isset($_POST['dateStart']) ? $_POST['dateStart'] : date('Y-m-01');
    $dateEnd = isset($_POST['dateEnd']) ? $_POST['dateEnd'] : date('Y-m-d');
	
    $cparId = isset($_POST['cparId']) ? $_POST['cparId'] : "";
    $cparInfoSource = isset($_POST['cparInfoSource']) ? $_POST['cparInfoSource'] : "";
    $cparDetails = isset($_POST['cparDetails']) ? $_POST['cparDetails'] : "";
    $cparMoreDetails = isset($_POST['cparMoreDetails']) ? $_POST['cparMoreDetails'] : "";
    $cparDisposition = isset($_POST['cparDisposition']) ? $_POST['cparDisposition'] : "";
    $cparAnalysis = isset($_POST['cparAnalysis']) ? $_POST['cparAnalysis'] : "";
    $cparSection = isset($_POST['cparSection']) ? $_POST['cparSection'] : "";
    $cparSourcePerson = isset($_POST['cparSourcePerson']) ? $_POST['cparSourcePerson'] : "";
    $cparDetectProcess = isset($_POST['cparDetectProcess']) ? $_POST['cparDetectProcess'] : "";
    $cparDetectPerson = isset($_POST['cparDetectPerson']) ? $_POST['cparDetectPerson'] : "";
    $cparIssueDate = isset($_POST['cparIssueDate']) ? $_POST['cparIssueDate'] : "";
    $cparDueDate = isset($_POST['cparDueDate']) ? $_POST['cparDueDate'] : "";
    $cparStatus = isset($_POST['cparStatus']) ? $_POST['cparStatus'] : "";
    $alarmFilter = isset($_POST['alarmFilter']) ? $_POST['alarmFilter'] : "";
    $interimFilter = isset($_POST['interimFilter']) ? $_POST['interimFilter'] : "";
    $correctiveFilter = isset($_POST['correctiveFilter']) ? $_POST['correctiveFilter'] : "";
    $verificationFilter = isset($_POST['verificationFilter']) ? $_POST['verificationFilter'] : "";
    $customerId = isset($_POST['customerId']) ? $_POST['customerId'] : "";
	
	$sqlFilterArray = array();
	if($cparId!='')	$sqlFilterArray[] = "cparId LIKE '".$cparId."'";
	if($allParts!=1)	$sqlFilterArray[] = "viewFlag = 0";
	if(count($cparTypeArray)>0)
	{
		foreach($cparTypeArray as $cparType)
		{
			$sqlFilterCparTypeArray[] = "cparId LIKE '".$cparType."-%'";
		}
		$sqlFilterArray[] = "(".implode(" OR ",$sqlFilterCparTypeArray).")";
	}
	
	$sqlFilterCparArray = array();
	
	if($dateStart!='' AND $dateEnd!='')	$sqlFilterCparArray[] = "cparIssueDate BETWEEN '".$dateStart."' AND '".$dateEnd."'";
	if($allStatus!=1)	$sqlFilterCparArray[] = "cparStatus IN('Verified','Issued','Answered')";
	if($cparInfoSource!='')	$sqlFilterCparArray[] = "cparInfoSource LIKE '".$cparInfoSource."'";
	if($cparDetails!='')	$sqlFilterCparArray[] = "cparDetails LIKE '".$cparDetails."'";
	if($cparMoreDetails!='')	$sqlFilterCparArray[] = "cparMoreDetails LIKE '".$cparMoreDetails."'";
	if($cparDisposition!='')	$sqlFilterCparArray[] = "cparDisposition LIKE '".$cparDisposition."'";
	if($cparAnalysis!='')	$sqlFilterCparArray[] = "cparAnalysis LIKE '".$cparAnalysis."'";
	if($cparSection!='')	$sqlFilterCparArray[] = "cparSection LIKE '".$cparSection."'";
	if($cparSourcePerson!='')	$sqlFilterCparArray[] = "cparSourcePerson LIKE '".$cparSourcePerson."'";
	if($cparDetectProcess!='')	$sqlFilterCparArray[] = "cparDetectProcess LIKE '".$cparDetectProcess."'";
	if($cparDetectPerson!='')	$sqlFilterCparArray[] = "cparDetectPerson LIKE '".$cparDetectPerson."'";
	if($cparIssueDate!='')	$sqlFilterCparArray[] = "cparIssueDate LIKE '".$cparIssueDate."'";
	if($cparDueDate!='')	$sqlFilterCparArray[] = "cparDueDate LIKE '".$cparDueDate."'";
	if($cparStatus!='')	$sqlFilterCparArray[] = "cparStatus LIKE '".$cparStatus."'";
	
	if($alarmFilter == 'Red')			$sqlFilterCparArray[] = "(cparDueDate <= DATE_SUB(CURDATE(), INTERVAL 5 DAY))";
	else if($alarmFilter == 'Orange')	$sqlFilterCparArray[] = "(cparDueDate <= DATE_SUB(CURDATE(), INTERVAL 1 DAY) and cparDueDate > DATE_SUB(CURDATE(), INTERVAL 5 DAY))";
	else if($alarmFilter == 'Yellow')	$sqlFilterCparArray[] = "(cparDueDate = CURDATE())";
	else if($alarmFilter == 'Green')	$sqlFilterCparArray[] = "(cparDueDate >= DATE_ADD(CURDATE(), INTERVAL 1 DAY) and cparDueDate < DATE_ADD(CURDATE(), INTERVAL 3 DAY))";
	else if($alarmFilter == 'Blue')		$sqlFilterCparArray[] = "(cparDueDate >= DATE_ADD(CURDATE(), INTERVAL 3 DAY))";
	
	if($interimFilter == 'Red')			$sqlFilterCparArray[] = "(cparIssueDate <= DATE_SUB(CURDATE(), INTERVAL 5 DAY)) AND cparInterimAction LIKE ''";
	else if($interimFilter == 'Orange')	$sqlFilterCparArray[] = "(cparIssueDate <= DATE_SUB(CURDATE(), INTERVAL 1 DAY) and cparDueDate > DATE_SUB(CURDATE(), INTERVAL 5 DAY)) AND cparInterimAction LIKE ''";
	else if($interimFilter == 'Yellow')	$sqlFilterCparArray[] = "(cparIssueDate = CURDATE()) AND cparInterimAction LIKE ''";
	else if($interimFilter == 'Green')	$sqlFilterCparArray[] = "(cparIssueDate >= DATE_ADD(CURDATE(), INTERVAL 1 DAY) and cparDueDate < DATE_ADD(CURDATE(), INTERVAL 3 DAY)) AND cparInterimAction LIKE ''";
	else if($interimFilter == 'Blue')	$sqlFilterCparArray[] = "(cparIssueDate >= DATE_ADD(CURDATE(), INTERVAL 3 DAY)) AND cparInterimAction LIKE ''";
	
	if($correctiveFilter == 'Red')			$sqlFilterCparArray[] = "(cparIssueDate <= DATE_SUB(CURDATE(), INTERVAL 5 DAY)) AND cparCorrectiveProcess LIKE ''";
	else if($correctiveFilter == 'Orange')	$sqlFilterCparArray[] = "(cparIssueDate <= DATE_SUB(CURDATE(), INTERVAL 1 DAY) and cparDueDate > DATE_SUB(CURDATE(), INTERVAL 5 DAY)) AND cparCorrectiveProcess LIKE ''";
	else if($correctiveFilter == 'Yellow')	$sqlFilterCparArray[] = "(cparIssueDate = CURDATE()) AND cparCorrectiveProcess LIKE ''";
	else if($correctiveFilter == 'Green')	$sqlFilterCparArray[] = "(cparIssueDate >= DATE_ADD(CURDATE(), INTERVAL 1 DAY) and cparDueDate < DATE_ADD(CURDATE(), INTERVAL 3 DAY)) AND cparCorrectiveProcess LIKE ''";
	else if($correctiveFilter == 'Blue')	$sqlFilterCparArray[] = "(cparIssueDate >= DATE_ADD(CURDATE(), INTERVAL 3 DAY)) AND cparCorrectiveProcess LIKE ''";
	
	if($verificationFilter == 'Red')			$sqlFilterCparArray[] = "(cparIssueDate <= DATE_SUB(CURDATE(), INTERVAL 5 DAY)) AND cparVerification LIKE ''";
	else if($verificationFilter == 'Orange')	$sqlFilterCparArray[] = "(cparIssueDate <= DATE_SUB(CURDATE(), INTERVAL 1 DAY) and cparDueDate > DATE_SUB(CURDATE(), INTERVAL 5 DAY)) AND cparVerification LIKE ''";
	else if($verificationFilter == 'Yellow')	$sqlFilterCparArray[] = "(cparIssueDate = CURDATE()) AND cparVerification LIKE ''";
	else if($verificationFilter == 'Green')	$sqlFilterCparArray[] = "(cparIssueDate >= DATE_ADD(CURDATE(), INTERVAL 1 DAY) and cparDueDate < DATE_ADD(CURDATE(), INTERVAL 3 DAY)) AND cparVerification LIKE ''";
	else if($verificationFilter == 'Blue')	$sqlFilterCparArray[] = "(cparIssueDate >= DATE_ADD(CURDATE(), INTERVAL 3 DAY)) AND cparVerification LIKE ''";
	
	if($customerId != '')
	{
		$lotNumberArray = array();
		$sql = "
			SELECT d.lotNumber FROM sales_customer as a
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
				$lotNumberArray[] = $resultParts['lotNumber'];
			}
		}
		
		$sqlFilterArray[] = "lotNumber IN('".implode("','",$lotNumberArray)."')";
	}
	
	if(count($sqlFilterCparArray) > 0)
	{
		$cparIdArray = array();
		$sql = "SELECT cparId FROM qc_cpar WHERE ".implode(' AND ',$sqlFilterCparArray)."";
		$queryCpar = $db->query($sql);
		if($queryCpar AND $queryCpar->num_rows > 0)
		{
			while($resultCpar = $queryCpar->fetch_assoc())
			{
				$cparIdArray[] = $resultCpar['cparId'];
			}
		}
		
		$sqlFilterArray[] = "cparId IN('".implode("','",$cparIdArray)."')";
	}
	
	$orderBy = "ORDER BY listId DESC";
	$sqlFilter = " WHERE status != 2 AND cparId LIKE 'CPAR-%'";
    if(count($sqlFilterArray) > 0)
    {
        $sqlFilter .= " AND ".implode(' AND ',$sqlFilterArray );
    }
    
    $totalRecords = 0;
	$sql = "SELECT * FROM qc_cparlotnumber ".$sqlFilter." ".$orderBy;//gerald payables
	$query = $db->query($sql);
	if($query AND $query->num_rows > 0)
	{
		$totalRecords = $query->num_rows;
	}
	
	//~ $sqlData = $sql;
	$sqlData = trim(preg_replace('/\s+/', ' ', $sql));
	
	$cparCUSCount = $cparINTCount = $cparSUBCount = $cparSUPCount = $cparSYSCount = 0;
	$sql = "SELECT SUBSTRING_INDEX(cparId,'-',2) as cparType, COUNT(lotNumber) as cparCount FROM qc_cparlotnumber ".$sqlFilter." GROUP BY cparType";
	$queryCpar = $db->query($sql);
	if($queryCpar AND $queryCpar->num_rows > 0)
	{
		while($resultCpar = $queryCpar->fetch_assoc())
		{
			$cparType = $resultCpar['cparType'];
			$cparCount = $resultCpar['cparCount'];
			
			if($cparType=='CPAR-CUS')		$cparCUSCount = $cparCount;
			else if($cparType=='CPAR-INT')	$cparINTCount = $cparCount;
			else if($cparType=='CPAR-SUB')	$cparSUBCount = $cparCount;
			else if($cparType=='CPAR-SUP')	$cparSUPCount = $cparCount;
			else if($cparType=='CPAR-SYS')	$cparSYSCount = $cparCount;
		}
	}
	//~ $sql = "SELECT cparInfoSource FROM qc_cparlotnumber as a";
	
	$totalQuantity = 0;
	$sql = "SELECT SUM(quantity) as totalQuantity FROM qc_cparlotnumber ".$sqlFilter;
	$query = $db->query($sql);
	if($query AND $query->num_rows > 0)
	{
		$result = $query->fetch_assoc();
		$totalQuantity = $result['totalQuantity'];
	}	
	
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo displayText('6-4','utf8',0,1,1);?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../Common Data/Templates/Bootstrap/w3css/w3.css">
    <link rel="stylesheet" type="text/css" href="../Common Data/Libraries/Javascript/Super Quick Table/datatables.min.css">
	<link rel="stylesheet" href="../Common Data/Templates/Bootstrap/Bootstrap 3.3.7/css/bootstrap.css">
	<link rel="stylesheet" href="../Common Data/Templates/Bootstrap/Font Awesome/css/font-awesome.css">
	<link rel="stylesheet" href="../Common Data/Templates/Bootstrap/Bootstrap 3.3.7/Roboto Font/roboto.css">
	<script type="text/javascript" src="../Common Data/Libraries/Javascript/Tiny Box/tinybox.js"></script>
	<link rel="stylesheet" href="../Common Data/Libraries/Javascript/Tiny Box/stylebox.css" />
	<style>
        .dataTables_wrapper .dataTables_filter {
			position: absolute;
			text-align: right;
			visibility: hidden;
		}
        
        body
		{
			font-size: 11px;
			font-family: Roboto;
			margin:0px;
			padding:0px;
			background-color:whitesmoke;
        }
        
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content, .dropdown-content-filter {
            display: none;
            position: absolute;
            background-color:white;
            z-index: 9999999;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
	</style>
</head>
<body>
<?php
	$previousLink = "/".v."/6-14%20QA%20&%20QC%20Software/raymond_QAQCSoftware.php";
	createHeader('6-4', '', $previousLink);
?>
	<form id='exportFormId' action='gerald_cparListAjax.php' method='POST'></form>
	<form action='' method='post' id='formFilter'></form>
	<input type='hidden' name='sqlData' value="<?php echo $sqlData;?>" form='exportFormId'>
    <div class="container-fluid">
			<div class="row w3-padding-top"></div>
			<div class="row w3-padding-top">
				<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
					<span class='w3-padding-12'>
						<span class='w3-padding w3-border w3-round w3-white'><b><?php echo strtoupper(displayText("L8")); // LEGEND ?></b></span>
					</span>
					<span class='w3-padding-12 w3-round' style='border: 3px solid black'>
						&emsp;
						<span class='w3-padding w3-border w3-round  w3-pink'><b><?php echo strtoupper(displayText('L1232'))." : ".$cparCUSCount;?></b></span>
						<span class='w3-padding w3-border w3-round  w3-light-blue'><b><?php echo strtoupper(displayText('L1228'))." : ".$cparINTCount;?></b></span>
						<span class='w3-padding w3-border w3-round  w3-lime'><b><?php echo strtoupper(displayText('L91'))." : ".$cparSUBCount;?></b></span>
						<span class='w3-padding w3-border w3-round  w3-orange'><b><?php echo strtoupper(displayText('L367'))." : ".$cparSUPCount; ?></b></span>
						<span class='w3-padding w3-border w3-round  w3-yellow'><b><?php echo strtoupper(displayText('L1233'))." : ".$cparSYSCount; ?></b></span>
						&emsp;
					</span>
				</div>
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12" style='text-align:right;'>
					<button class='w3-btn w3-round w3-small w3-green' onclick="location.href='../Section%20Work%20Schedule%20Graph/raymond_nonConformaceGraph.php'"><i class='fa fa-bookmark'></i>&emsp;<b><?php echo displayText('1-11', 'utf8', 0, 2); ?></b></button>
					<button class='w3-btn w3-round w3-small w3-green' onclick="location.href='../6-10%20Quality%20Alert%20Software/raymond_qualityAlertInputForm.php'"><i class='fa fa-bookmark'></i>&emsp;<b><?php echo displayText("6-10",'utf8', 0,2); ?></b></button>
					<button class='w3-btn w3-tiny w3-pink w3-round' id='filterData'><i class='fa fa-list'></i> &emsp;<b><?php echo displayText('B7');?></b></button>
					<div class="dropdown">
						<button class='w3-btn w3-tiny w3-indigo w3-round'><i class='fa fa-cog'></i> &emsp;<b><?php echo displayText('L435');?></b></button>
						<div class="dropdown-content">
							<div class='w3-padding-top'></div>
							<button style='width:150px;' class='functionButton w3-btn w3-tiny w3-green w3-round' onclick="location.href='anthony_inputForm.php';"><i class='fa fa-plus'></i> &emsp;<b><?php echo displayText('B4')." CPAR";?></b></button>
							<div class='w3-padding-top'></div>
							<button style='width:150px;' class='functionButton w3-btn w3-tiny w3-purple w3-round' name='exportFlag' value='1' form='exportFormId'><i class='fa fa-excel-o'></i> &emsp;<b><?php echo strtoupper(displayText('L487'));?></b></button>
						</div>
					</div>
					<button class='w3-btn w3-tiny w3-round w3-green' onclick="location.href='';"><i class='fa fa-refresh'></i>&emsp;<b><?php echo displayText('L436');?></b></button>
				</div>
			</div>
        <div class="row">
            <div class="col-md-12 w3-padding-top">
                <?php
                echo "<label>".strtoupper(displayText('L41'))." : ".$totalRecords."</label>";
                if($rfqNumber!='')
                {
					echo "&nbsp; Copy Referer : <input type='text' class='w3-pale-green' style='width:425px;' readonly value='\\\SERVER\www\html\V3\2-7 Request For Quotation List V2\CAD File\\".$rfqNumber."'>";
				}
                ?>
                <table class='table table-bordered table-condensed table-striped' id="mainTableId">
                    <thead class='w3-indigo thead' style='text-transform:uppercase;'>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L334');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L24');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L28');?></th>
						<!--th style='vertical-align:middle;' class='w3-center'><?php //echo displayText('L566');?></th-->
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L45');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L31');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L351');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L352');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L353');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L267');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L354');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L355');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L336');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L1330');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L348');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L337');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L338');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L340');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L341');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L342');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L113');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L344');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L356');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L357');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L172');?></th>
						<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L1120');?></th>
                    </thead>
                    <tbody class='tbody'>
                    
                    </tbody>
                    <tfoot class='w3-indigo thead'>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'><?php echo $totalQuantity;?></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                        <th style='vertical-align:middle;' class='w3-center'></th>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div id='modal-izi-filter'><span class='izimodal-content-filter'></span></div>
    <div id='modal-izi-function'><span class='izimodal-content-function'></span></div>
    <div id='modal-izi-help'><span class='izimodal-content-help'></span></div>
</body>
<script src="../Common Data/Libraries/Javascript/jQuery 3.1.1/jquery-3.1.1.js"></script>
<script src="../Common Data/Libraries/Javascript/jQuery 3.1.1/jquery-ui.js"></script>
<script src="../Common Data/Libraries/Javascript/jQuery 3.1.1/bootstrap.min.js"></script>
<link rel="stylesheet" href="../Common Data/Libraries/Javascript/jquery-date-range-picker-master/dist/daterangepicker.min.css">
<script type="text/javascript" src="../Common Data/Libraries/Javascript/jquery-date-range-picker-master/moment.min.js"></script>
<script type="text/javascript" src="../Common Data/Libraries/Javascript/jquery-date-range-picker-master/dist/jquery.daterangepicker.min.js"></script>
<script src="../Common Data/Libraries/Javascript/Super Quick Table/datatables.min.js"></script>
<link rel="stylesheet" href="../Common Data/Libraries/Javascript/Bootstrap Multi-Select JS/dist/css/bootstrap-multiselect.css" type="text/css" media="all" />
<script src="../Common Data/Libraries/Javascript/Bootstrap Multi-Select JS/dist/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="../Common Data/Libraries/Javascript/iziModal-master/css/iziModal.css" />
<script src="../Common Data/Libraries/Javascript/iziModal-master/js/iziModal.js"></script>
<link rel="stylesheet" href="../Common Data/Libraries/Javascript/iziToast-master/dist/css/iziToast.css" />
<script src="../Common Data/Libraries/Javascript/iziToast-master/dist/js/iziToast.js"></script>
<script type="text/javascript">	
	
$(document).ready(function(event){
    var sql = "<?php echo $sqlData; ?>";
    var totalRecords = "<?php echo $totalRecords; ?>";
    var dateFrom = "<?php echo $dateFrom; ?>";
    var assySheetWorksId = "<?php echo $assySheetWorksId; ?>";
    var dataTable = $('#mainTableId').DataTable({
        "processing"    : true,
        "ordering"      : false,
        "serverSide"    : true,
        "bInfo"         : false,
        "ajax"          :{
            url     : "gerald_cparListAjax.php", // json datasource
            type    : "POST",  // method  , by default get
            data    : {
                        "sqlData"                   : sql,
                        "totalRecords"              : totalRecords,
                        "dateFrom"					: dateFrom
                      },
            error   : function(){  // error handling
                $(".mainTableId-error").html("");
                $("#mainTableId").append('<tbody class="mainTableId-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#mainTableId_processing").css("display","none");
            }
        },
        "createdRow": function( row, data, index ) {
            var cparId = $('td:eq(0)', row).text();
            
            if(cparId.indexOf("CPAR-CUS")!=-1)
            {
				$('td:eq(0)', row).css('background-color','pink');
			}
            else if(cparId.indexOf("CPAR-INT")!=-1)
            {
				$('td:eq(0)', row).css('background-color','lightblue');
			}
			else if(cparId.indexOf("CPAR-SUB")!=-1)
            {
				$('td:eq(0)', row).css('background-color','yellowgreen');
			}
			else if(cparId.indexOf("CPAR-SUP")!=-1)
            {
				$('td:eq(0)', row).css('background-color','orange');
			}
			else if(cparId.indexOf("CPAR-SYS")!=-1)
            {
				$('td:eq(0)', row).css('background-color','yellow');
			}
        },
        "initComplete": function(settings, json) {
            $('body').find('.dataTables_scrollBody').addClass("scrollbar");
        },
        "columnDefs": [
                        // {
                        //     "targets"       : [ 1, 2, 3, hiddenIndex, packHidden],
                        //     "visible"       : false,
                        //     "searchable"    : true
                        // }
                        // {
                        //     targets: -1,
                        //     className: 'dt-body-right'
                        // }
                        {
                            "targets" 		: [ 0 ],
                            "width"			: "1%"
                        }
        ],
        language    : {
                    processing  : ""
        },
        fixedColumns:   {
                leftColumns: 0
        },
        scrollX         : true,
        scrollY         : 570,
        scrollCollapse  : false,
        scroller        : {
            loadingIndicator    : true
        },
        stateSave       : false
    });
    
    $("#chkAll").change(function(){
		$(".chkbox").not(':disabled').prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
	});
    
    $("#filterData").click(function(){
        $("#modal-izi-filter").iziModal({
            title                   : '<i class="fa fa-flash"></i> <?php echo strtoupper(displayText("B7"));?>',
            headerColor             : '#1F4788',
            subtitle                : '<b><?php echo strtoupper(date('F d, Y'));?></b>',
            width                   : 1200,
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
                                            url         : 'gerald_cparListFilter.php',
                                            type        : 'POST',
                                            data        : {
                                                            sqlData      : sql,
                                                            postVariable : "<?php echo str_replace('"',"'",json_encode($_POST));?>"
                                            },
                                            success     : function(data){
                                                            $( ".izimodal-content-filter" ).html(data);
                                                            modal.stopLoading();
                                            }
                                        });
                                    },
            onClosed                : function(modal){
                                        $("#modal-izi-filter").iziModal("destroy");
                        } 
        });

        $("#modal-izi-filter").iziModal("open");
    });
    
    $(".functionButton").click(function(){
		var thisId = $(this).attr('id');
		
		if(thisId=='extractZip' || thisId=='importExcel')
		{
			var url = (thisId=='extractZip') ? 'gerald_extractZip.php' : 'gerald_importExcel.php';
			
			$("#modal-izi-function").iziModal({
				title                   : '<i class="fa fa-flash"></i> <?php echo strtoupper(displayText("B7"));?>',
				headerColor             : '#1F4788',
				subtitle                : '<b><?php echo strtoupper(date('F d, Y'));?></b>',
				width                   : 500,
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
												url         : url,
												type        : 'POST',
												data        : {
																//~ sqlData      : sql,
																//~ postVariable : "<?php echo str_replace('"',"'",json_encode($_POST));?>"
												},
												success     : function(data){
																$( ".izimodal-content-function" ).html(data);
																modal.stopLoading();
												}
											});
										},
				onClosed                : function(modal){
											$("#modal-izi-function").iziModal("destroy");
							} 
			});
		}
		else if(thisId=='editSchedule')
		{
			$("#modal-izi-function").iziModal({
				title                   : '<i class="fa fa-flash"></i> <?php echo strtoupper(displayText("B7"));?>',
				headerColor             : '#1F4788',
				subtitle                : '<b><?php echo strtoupper(date('F d, Y'));?></b>',
				width                   : 1200,
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
												url         : 'gerald_rfqListInputForm.php',
												type        : 'POST',
												data        : {
																//~ sqlData      : sql,
																//~ postVariable : "<?php echo str_replace('"',"'",json_encode($_POST));?>"
												},
												success     : function(data){
																$( ".izimodal-content-function" ).html(data);
																modal.stopLoading();
												}
											});
										},
				onClosed                : function(modal){
											$("#modal-izi-function").iziModal("destroy");
							} 
			});			
		}

        $("#modal-izi-function").iziModal("open");
    });
    
	$("#helpBtn").click(function(){
		$("#modal-izi-help").iziModal({
			title                   : '<i class="fa fa-info"></i>&emsp;<?php echo strtoupper(displayText("L3586"));?>',
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
																displayId   : '4-4'
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
