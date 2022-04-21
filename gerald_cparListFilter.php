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
	ini_set("display_errors", "on");

	$sqlData = (isset($_POST['sqlData'])) ? $_POST['sqlData'] : '';
	$postVariable = (isset($_POST['postVariable'])) ? $_POST['postVariable'] : '';
	if($postVariable!='')
	{
		$postVariable = str_replace("'",'"',$postVariable);
		$_POST = json_decode($postVariable,true);
	}
	
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
	
	$fromSql = strstr($sqlData,'FROM');
	$fromSql = strstr($fromSql,'ORDER BY',true);
	
	$cparIdArray = array();
	$sql = "SELECT DISTINCT cparId ".$fromSql." ORDER BY cparId";
	$queryCpar = $db->query($sql);
	if($queryCpar AND $queryCpar->num_rows > 0)
	{
		while($resultCpar = $queryCpar->fetch_assoc())
		{
			$cparIdArray[] = $resultCpar['cparId'];
		}
	}
	
	$cparFilter = "";
	if(count($cparIdArray) > 0)
	{
		$cparFilter = " WHERE cparId IN('".implode("','",$cparIdArray)."')";
	}
	
	$cparCUSCount = $cparINTCount = $cparSUBCount = $cparSUPCount = 0;
	//~ echo $sql = "SELECT SUBSTRING_INDEX(cparId,'-',2) as cparType, COUNT(lotNumber) as cparCount FROM qc_cparlotnumber ".$cparFilter." GROUP BY cparType";
	$sql = "SELECT SUBSTRING_INDEX(cparId,'-',2) as cparType, COUNT(lotNumber) as cparCount ".$fromSql." GROUP BY cparType";
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
	
	echo "<div class='row'>";
		echo "<div class='col-md-12'>";
			?>
<!--
				<span class='w3-padding-12 w3-round' style='border: 3px solid black'>
-->
					&emsp;
					<span class='w3-padding w3-border w3-round  w3-pink'><input type='checkbox' name='cparTypeArray[]' value='CPAR-CUS' class='cparTypeClass' <?php if(in_array('CPAR-CUS',$cparTypeArray)) echo "checked";?> form='formFilter'>&emsp;<b><?php echo strtoupper(displayText('L1232'))." : ".$cparCUSCount;?></b></span>
					<span class='w3-padding w3-border w3-round  w3-light-blue'><input type='checkbox' name='cparTypeArray[]' value='CPAR-INT' class='cparTypeClass' <?php if(in_array('CPAR-INT',$cparTypeArray)) echo "checked";?> form='formFilter'>&emsp;<b><?php echo strtoupper(displayText('L1228'))." : ".$cparINTCount;?></b></span>
					<span class='w3-padding w3-border w3-round  w3-lime'><input type='checkbox' name='cparTypeArray[]' value='CPAR-SUB' class='cparTypeClass' <?php if(in_array('CPAR-SUB',$cparTypeArray)) echo "checked";?> form='formFilter'>&emsp;<b><?php echo strtoupper(displayText('L91'))." : ".$cparSUBCount;?></b></span>
					<span class='w3-padding w3-border w3-round  w3-orange'><input type='checkbox' name='cparTypeArray[]' value='CPAR-SUP' class='cparTypeClass' <?php if(in_array('CPAR-SUP',$cparTypeArray)) echo "checked";?> form='formFilter'>&emsp;<b><?php echo strtoupper(displayText('L367'))." : ".$cparSUPCount; ?></b></span>
					<span class='w3-padding w3-border w3-round  w3-yellow'><input type='checkbox' name='cparTypeArray[]' value='CPAR-SYS' class='cparTypeClass' <?php if(in_array('CPAR-SYS',$cparTypeArray)) echo "checked";?> form='formFilter'>&emsp;<b><?php echo strtoupper(displayText('L1233'))." : ".$cparSYSCount; ?></b></span>
					&emsp;
<!--
				</span>
-->
			<?php
		echo "</div>";
	echo "</div>";	
	echo "<div class='w3-padding-top'></div>";
	
	echo "<div class='row'>";
		echo "<div class='col-md-2'>";
			echo "<label class='w3-tiny'>".displayText('L334')."</label>";
			echo "<input type='text' class='w3-input w3-border' name='cparId' list='cparId' form='formFilter'>";
			echo "<datalist id='cparId'>";
				$sql = "SELECT DISTINCT cparId ".$fromSql." ORDER BY cparId";
				$query = $db->query($sql);
				if($query AND $query->num_rows > 0)
				{
					while($result = $query->fetch_assoc())
					{
						$selected = ($cparId==$result['cparId']) ? 'selected' : '';
						echo "<option value='".$result['cparId']."' ".$selected.">".$result['cparId']."</option>";
					}
				}
			echo "</datalist>";
		echo "</div>";
		
		//~ echo "<div class='col-md-2'>";
			//~ echo "<label class='w3-tiny'>".displayText('L335')."</label>";
			//~ echo "<select class='w3-input w3-border' id='cparInfoSource' name='cparInfoSource' form='formFilter'>";
				//~ echo "<option></option>";
				//~ $sql = "SELECT DISTINCT cparInfoSource FROM qc_cpar ".$cparFilter." ORDER BY cparInfoSource";
				//~ $query = $db->query($sql);
				//~ if($query AND $query->num_rows > 0)
				//~ {
					//~ while($result = $query->fetch_assoc())
					//~ {
						//~ $selected = ($cparInfoSource==$result['cparInfoSource']) ? 'selected' : '';
						
						//~ echo "<option value='".$result['cparInfoSource']."' ".$selected.">".$result['cparInfoSource']."</option>";
					//~ }
				//~ }
			//~ echo "</select>";
		//~ echo "</div>";

		echo "<div class='col-md-2'>";
			echo "<label class='w3-tiny'>".displayText('L336')."</label>";
			echo "<select class='w3-input w3-border' id='cparDetails' name='cparDetails' form='formFilter'>";
				echo "<option></option>";
				$sql = "SELECT DISTINCT cparDetails FROM qc_cpar ".$cparFilter." ORDER BY cparDetails";
				$query = $db->query($sql);
				if($query AND $query->num_rows > 0)
				{
					while($result = $query->fetch_assoc())
					{
						$selected = ($cparDetails==$result['cparDetails']) ? 'selected' : '';
						
						echo "<option value='".$result['cparDetails']."' ".$selected.">".$result['cparDetails']."</option>";
					}
				}
			echo "</select>";
		echo "</div>";
		
		echo "<div class='col-md-2'>";
			echo "<label class='w3-tiny'>More ".displayText('L336')."</label>";
			echo "<select class='w3-input w3-border' id='cparMoreDetails' name='cparMoreDetails' form='formFilter'>";
				echo "<option></option>";
				$sql = "SELECT DISTINCT cparMoreDetails FROM qc_cpar ".$cparFilter." ORDER BY cparMoreDetails";
				$query = $db->query($sql);
				if($query AND $query->num_rows > 0)
				{
					while($result = $query->fetch_assoc())
					{
						$selected = ($cparMoreDetails==$result['cparMoreDetails']) ? 'selected' : '';
						
						echo "<option value='".$result['cparMoreDetails']."' ".$selected.">".$result['cparMoreDetails']."</option>";
					}
				}
			echo "</select>";
		echo "</div>";
		
		echo "<div class='col-md-2'>";
			echo "<label class='w3-tiny'>".displayText('L348')."</label>";
			echo "<select class='w3-input w3-border' id='cparDisposition' name='cparDisposition' form='formFilter'>";
				echo "<option></option>";
				$sql = "SELECT DISTINCT cparDisposition FROM qc_cpar ".$cparFilter." ORDER BY cparDisposition";
				$query = $db->query($sql);
				if($query AND $query->num_rows > 0)
				{
					while($result = $query->fetch_assoc())
					{
						$selected = ($cparDisposition==$result['cparDisposition']) ? 'selected' : '';
						
						echo "<option value='".$result['cparDisposition']."' ".$selected.">".$result['cparDisposition']."</option>";
					}
				}
			echo "</select>";
		echo "</div>";
		
		echo "<div class='col-md-2'>";
			echo "<label class='w3-tiny'>".displayText('L337')."</label>";
			echo "<input type='text' class='w3-input w3-border' name='cparAnalysis' list='cparAnalysis' form='formFilter'>";
			echo "<datalist id='cparAnalysis'>";
				$sql = "SELECT DISTINCT cparAnalysis FROM qc_cpar ".$cparFilter." ORDER BY cparAnalysis";
				$query = $db->query($sql);
				if($query AND $query->num_rows > 0)
				{
					while($result = $query->fetch_assoc())
					{
						$selected = ($cparAnalysis==$result['cparAnalysis']) ? 'selected' : '';
						echo "<option value='".$result['cparAnalysis']."' ".$selected.">".$result['cparAnalysis']."</option>";
					}
				}
			echo "</datalist>";
		echo "</div>";
	echo "</div>";
	
	echo "<div class='w3-padding-top'></div>";
	
	echo "<div class='row'>";
		echo "<div class='col-md-2'>";
			echo "<label class='w3-tiny'>".displayText('L338')."</label>";
			echo "<select class='w3-input w3-border' id='cparSection' name='cparSection' form='formFilter'>";
				echo "<option></option>";
				$sql = "SELECT DISTINCT cparSection FROM qc_cpar ".$cparFilter." ORDER BY cparSection";
				$query = $db->query($sql);
				if($query AND $query->num_rows > 0)
				{
					while($result = $query->fetch_assoc())
					{
						$selected = ($cparSection==$result['cparSection']) ? 'selected' : '';
						
						echo "<option value='".$result['cparSection']."' ".$selected.">".$result['cparSection']."</option>";
					}
				}
			echo "</select>";
		echo "</div>";
		
		echo "<div class='col-md-2'>";
			echo "<label class='w3-tiny'>".displayText('L339')."</label>";
			echo "<select class='w3-input w3-border' id='cparSourcePerson' name='cparSourcePerson' form='formFilter'>";
				echo "<option></option>";
				$sql = "SELECT DISTINCT cparSourcePerson FROM qc_cpar ".$cparFilter." ORDER BY cparSourcePerson";
				$query = $db->query($sql);
				if($query AND $query->num_rows > 0)
				{
					while($result = $query->fetch_assoc())
					{
						$selected = ($cparSourcePerson==$result['cparSourcePerson']) ? 'selected' : '';
						
						echo "<option value='".$result['cparSourcePerson']."' ".$selected.">".$result['cparSourcePerson']."</option>";
					}
				}
			echo "</select>";
		echo "</div>";
		
		echo "<div class='col-md-2'>";
			echo "<label class='w3-tiny'>".displayText('L340')."</label>";
			echo "<select class='w3-input w3-border' id='cparDetectProcess' name='cparDetectProcess' form='formFilter'>";
				echo "<option></option>";
				$sql = "SELECT DISTINCT cparDetectProcess FROM qc_cpar ".$cparFilter." ORDER BY cparDetectProcess";
				$query = $db->query($sql);
				if($query AND $query->num_rows > 0)
				{
					while($result = $query->fetch_assoc())
					{
						$selected = ($cparDetectProcess==$result['cparDetectProcess']) ? 'selected' : '';
						
						echo "<option value='".$result['cparDetectProcess']."' ".$selected.">".$result['cparDetectProcess']."</option>";
					}
				}
			echo "</select>";
		echo "</div>";
		
		echo "<div class='col-md-2'>";
			echo "<label class='w3-tiny'>".displayText('L341')."</label>";
			echo "<select class='w3-input w3-border' id='cparDetectPerson' name='cparDetectPerson' form='formFilter'>";
				echo "<option></option>";
				$sql = "SELECT DISTINCT cparDetectPerson FROM qc_cpar ".$cparFilter." ORDER BY cparDetectPerson";
				$query = $db->query($sql);
				if($query AND $query->num_rows > 0)
				{
					while($result = $query->fetch_assoc())
					{
						$selected = ($cparDetectPerson==$result['cparDetectPerson']) ? 'selected' : '';
						
						echo "<option value='".$result['cparDetectPerson']."' ".$selected.">".$result['cparDetectPerson']."</option>";
					}
				}
			echo "</select>";
		echo "</div>";
		
		echo "<div class='col-md-2'>";
			echo "<label class='w3-tiny'>".displayText('L342')."</label>";
			echo "<select class='w3-input w3-border' id='cparIssueDate' name='cparIssueDate' form='formFilter'>";
				echo "<option></option>";
				$sql = "SELECT DISTINCT cparIssueDate FROM qc_cpar ".$cparFilter." ORDER BY cparIssueDate";
				$query = $db->query($sql);
				if($query AND $query->num_rows > 0)
				{
					while($result = $query->fetch_assoc())
					{
						$selected = ($cparIssueDate==$result['cparIssueDate']) ? 'selected' : '';
						
						echo "<option value='".$result['cparIssueDate']."' ".$selected.">".$result['cparIssueDate']."</option>";
					}
				}
			echo "</select>";
		echo "</div>";
		
		echo "<div class='col-md-2'>";
			echo "<label class='w3-tiny'>".displayText('L343')."</label>";
			echo "<select class='w3-input w3-border' id='cparDueDate' name='cparDueDate' form='formFilter'>";
				echo "<option></option>";
				$sql = "SELECT DISTINCT cparDueDate FROM qc_cpar ".$cparFilter." ORDER BY cparDueDate";
				$query = $db->query($sql);
				if($query AND $query->num_rows > 0)
				{
					while($result = $query->fetch_assoc())
					{
						$selected = ($cparDueDate==$result['cparDueDate']) ? 'selected' : '';
						
						echo "<option value='".$result['cparDueDate']."' ".$selected.">".$result['cparDueDate']."</option>";
					}
				}
			echo "</select>";
		echo "</div>";
		
	echo "</div>";
	
	echo "<div class='w3-padding-top'></div>";
	
	echo "<div class='row'>";
		echo "<div class='col-md-2'>";
			echo "<label class='w3-tiny'>".displayText('L113')."</label>";
			echo "<select class='w3-input w3-border' id='alarmFilter' name='alarmFilter' form='formFilter'>";
				echo "<option value=''>ALL</option>";
				echo "<option style='background-color:Red;' value='Red' "; if($alarmFilter=="Red") { echo "selected"; } echo ">Red</option>";					
				echo "<option style='background-color:Orange;' value='Orange' "; if($alarmFilter=="Orange") { echo "selected"; } echo ">Orange</option>";					
				echo "<option style='background-color:Yellow;' value='Yellow' "; if($alarmFilter=="Yellow") { echo "selected"; } echo ">Yellow</option>";					
				echo "<option style='background-color:Lightgreen;' value='Green' "; if($alarmFilter=="Green") { echo "selected"; } echo ">Green</option>";					
				echo "<option style='background-color:Lightblue;' value='Blue' "; if($alarmFilter=="Blue") { echo "selected"; } echo ">Blue</option>";					
			echo "</select>";
		echo "</div>";
		
		echo "<div class='col-md-2'>";
			echo "<label class='w3-tiny'>".displayText('L172')."</label>";
			echo "<select class='w3-input w3-border' id='cparStatus' name='cparStatus' form='formFilter'>";
				echo "<option></option>";
				$sql = "SELECT DISTINCT cparStatus FROM qc_cpar ".$cparFilter." ORDER BY cparStatus";
				$query = $db->query($sql);
				if($query AND $query->num_rows > 0)
				{
					while($result = $query->fetch_assoc())
					{
						$selected = ($cparStatus==$result['cparStatus']) ? 'selected' : '';
						
						echo "<option value='".$result['cparStatus']."' ".$selected.">".$result['cparStatus']."</option>";
					}
				}
			echo "</select>";
		echo "</div>";
		
		echo "<div class='col-md-2'>";
			echo "<label class='w3-tiny'>".displayText('L344')."</label>";
			echo "<select class='w3-input w3-border' id='interimFilter' name='interimFilter' form='formFilter'>";
				echo "<option value=''>ALL</option>";
				echo "<option style='background-color:Red;' value='Red' "; if($interimFilter=="Red") { echo "selected"; } echo ">Red</option>";					
				echo "<option style='background-color:Orange;' value='Orange' "; if($interimFilter=="Orange") { echo "selected"; } echo ">Orange</option>";					
				echo "<option style='background-color:Yellow;' value='Yellow' "; if($interimFilter=="Yellow") { echo "selected"; } echo ">Yellow</option>";					
				echo "<option style='background-color:Lightgreen;' value='Green' "; if($interimFilter=="Green") { echo "selected"; } echo ">Green</option>";					
				echo "<option style='background-color:Lightblue;' value='Blue' "; if($interimFilter=="Blue") { echo "selected"; } echo ">Blue</option>";					
			echo "</select>";
		echo "</div>";
		
		echo "<div class='col-md-2'>";
			echo "<label class='w3-tiny'>".displayText('L345')."</label>";
			echo "<select class='w3-input w3-border' id='correctiveFilter' name='correctiveFilter' form='formFilter'>";
				echo "<option value=''>ALL</option>";
				echo "<option style='background-color:Red;' value='Red' "; if($correctiveFilter=="Red") { echo "selected"; } echo ">Red</option>";					
				echo "<option style='background-color:Orange;' value='Orange' "; if($correctiveFilter=="Orange") { echo "selected"; } echo ">Orange</option>";					
				echo "<option style='background-color:Yellow;' value='Yellow' "; if($correctiveFilter=="Yellow") { echo "selected"; } echo ">Yellow</option>";					
				echo "<option style='background-color:Lightgreen;' value='Green' "; if($correctiveFilter=="Green") { echo "selected"; } echo ">Green</option>";					
				echo "<option style='background-color:Lightblue;' value='Blue' "; if($correctiveFilter=="Blue") { echo "selected"; } echo ">Blue</option>";					
			echo "</select>";
		echo "</div>";
		
		echo "<div class='col-md-2'>";
			echo "<label class='w3-tiny'>".displayText('L346')."</label>";
			echo "<select class='w3-input w3-border' id='verificationFilter' name='verificationFilter' form='formFilter'>";
				echo "<option value=''>ALL</option>";
				echo "<option style='background-color:Red;' value='Red' "; if($verificationFilter=="Red") { echo "selected"; } echo ">Red</option>";					
				echo "<option style='background-color:Orange;' value='Orange' "; if($verificationFilter=="Orange") { echo "selected"; } echo ">Orange</option>";					
				echo "<option style='background-color:Yellow;' value='Yellow' "; if($verificationFilter=="Yellow") { echo "selected"; } echo ">Yellow</option>";					
				echo "<option style='background-color:Lightgreen;' value='Green' "; if($verificationFilter=="Green") { echo "selected"; } echo ">Green</option>";					
				echo "<option style='background-color:Lightblue;' value='Blue' "; if($verificationFilter=="Blue") { echo "selected"; } echo ">Blue</option>";					
			echo "</select>";
		echo "</div>";
	
		echo "<div class='col-md-2'>";
			echo "<label class='w3-tiny'>".displayText('L24')."</label>";
			echo "<select class='w3-input w3-border' id='customerId' name='customerId' form='formFilter'>";
				echo "<option></option>";
				
				$lotNumberArray = array();
				$sql = "SELECT lotNumber ".$fromSql." ";
				$queryCpar = $db->query($sql);
				if($queryCpar AND $queryCpar->num_rows > 0)
				{
					while($resultCpar = $queryCpar->fetch_assoc())
					{
						$lotNumberArray[] = $resultCpar['lotNumber'];
					}
				}
				$sql = "
					SELECT e.customerId, e.customerAlias FROM qc_cparlotnumber as b
					INNER JOIN ppic_lotlist as c ON c.lotNumber = b.lotNumber AND c.identifier = 1
					INNER JOIN cadcam_parts as d ON d.partId = c.partId
					INNER JOIN sales_customer as e ON e.customerId = d.customerId
					WHERE b.lotNumber IN('".implode("','",$lotNumberArray)."')
					GROUP BY e.customerId ORDER BY e.customerAlias
				";
				$query = $db->query($sql);
				if($query AND $query->num_rows > 0)
				{
					while($result = $query->fetch_assoc())
					{
						$selected = ($customerId==$result['customerId']) ? 'selected' : '';
						
						echo "<option value='".$result['customerId']."' ".$selected.">".$result['customerAlias']."</option>";
					}
				}
			echo "</select>";
		echo "</div>";
	
	echo "</div>";
	
	echo "<div class='w3-padding-top'></div>";
	
	echo "<div class='row'>";
		echo "<div class='col-md-2'>";
				echo "<label class='w3-tiny'>".displayText('L134')."</label>";
				echo "<input type='date' class='w3-input w3-border' name = 'dateStart'  value = '".$dateStart."' required form='formFilter'>";
		echo "</div>";
		
		echo "<div class='col-md-2'>";
				echo "<label class='w3-tiny'>".displayText('L135')."</label>";
				echo "<input type='date' class='w3-input w3-border' name = 'dateEnd'  value = '".$dateEnd."' required form='formFilter'>";
		echo "</div>";
		
		echo "<div class='col-md-2'>";
			echo "<br>";
			$allStatusChecked = ($allStatus==1) ? 'checked' : '';
			echo "<label><input type='checkbox' onchange=\"this.form.submit()\" name='allStatus' value='1' ".$allStatusChecked." form='formFilter'>".displayText('L3793')."</label>";
		echo "</div>";
		
		echo "<div class='col-md-2'>";
			echo "<br>";
			$allPartsChecked = ($allParts==1) ? 'checked' : '';
			echo "<label><input type='checkbox' onchange=\"this.form.submit()\" name='allParts' value='1' ".$allPartsChecked." form='formFilter'>".displayText("L490")."</label>";
		echo "</div>";
		
	echo "</div>";
	
	echo "<div class='w3-padding-top'></div>";
	echo "<div class='row w3-padding'>";
		echo "<div class='col-md-12 w3-center'>";
			echo "<button class='w3-btn w3-round w3-small w3-indigo' form='formFilter'><i class='fa fa-search'></i>&emsp;<b>".strtoupper(displayText("B5"))."</b></button>"; // SEARCH
		echo "</div>";
	echo "</div>";
?>
<script>
	$(document).ready(function(){
        $("input.cparTypeClass").dblclick(function(){
            var thisCheck = $(this)
            if(thisCheck.is(':checked'))
            {			
                if(confirm("Check only this?"))
                {
                    $("input.cparTypeClass").prop('checked',false);
                    thisCheck.prop('checked',true);
                }
            }
            else
            {
                if(confirm("Check all status?"))
                {
                    $("input.cparTypeClass").prop('checked',true);
                }
            }
        });
	});
</script>
