<?php
	session_start();
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
	$javascriptLib = "../Common Data/Libraries/Javascript/";
	$templates = "../Common Data/Templates/";
	set_include_path($path);	
	include('PHP Modules/mysqliConnection.php');
	include('PHP Modules/anthony_retrieveText.php');
	
	if(isset($_GET['cparId']) AND $_GET['cparId'] != '' AND $_SESSION['idNumber'] != '')
	{
		if($_SESSION['userType']!='') //new code;
		{
			$sql = "SELECT * FROM qc_cpar WHERE cparId LIKE '".$_GET['cparId']."' LIMIT 1";
			$query = $db->query($sql);
			if($query AND $query->num_rows > 0)
			{
				$resultMain = $query->fetch_assoc();
				
				$status = $resultMain['status'];
				$cparType = $resultMain['cparType'];
				$cparOwner = $resultMain['cparOwner'];
				$cparSection = $resultMain['cparSection'];
				$cparDueDate = $resultMain['cparDueDate'];
				$cparInfoSource = $resultMain['cparInfoSource'];
				$cparInfoSourceSubcon = $resultMain['cparInfoSourceSubcon'];
				$cparInfoSourceRemarks = $resultMain['cparInfoSourceRemarks'];
				$cparMaker = $resultMain['cparMaker'];
				$cparDetails = $resultMain['cparDetails'];
				$cparDisposition = $resultMain['cparDisposition'];
				$cparDispositionDetails = $resultMain['cparDispositionDetails'];
				$cparCause = $resultMain['cparCause'];
				$cparSourcePerson = $resultMain['cparSourcePerson'];
				$cparDetectProcess = $resultMain['cparDetectProcess'];
				$cparDetectPerson = $resultMain['cparDetectPerson'];
				$cparDetectDate = $resultMain['cparDetectDate'];
				$cparItemPrice = $resultMain['cparItemPrice'];
				$cparAction = $resultMain['cparAction'];
				$cparInterimAction = $resultMain['cparInterimAction'];
				$cparAnalysis = $resultMain['cparAnalysis'];
				$cparReturnDate = $resultMain['cparReturnDate'];
				$cparPRSNumber = $resultMain['cparPRSNumber'];
				$cparProductionSchedule = $resultMain['cparProductionSchedule'];
				$cparSubconSchedule = $resultMain['cparSubconSchedule'];
				$cparCustomerSchedule = $resultMain['cparCustomerSchedule'];
				$cparRecoveryIncharge = $resultMain['cparRecoveryIncharge'];
				
				//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~// 

				$cparCauseProcess = $resultMain['cparCauseProcess'];
				$cparCauseFlowOut = $resultMain['cparCauseFlowOut'];
				$cparCorrectiveProcess = $resultMain['cparCorrectiveProcess'];
				$cparCorrectiveFlowOut = $resultMain['cparCorrectiveFlowOut'];
				$cparCorrectiveProcessDate = $resultMain['cparCorrectiveProcessDate'];
				$cparCorrectiveFlowOutDate = $resultMain['cparCorrectiveFlowOutDate'];
				$cparCorrectiveProcessIncharge = $resultMain['cparCorrectiveProcessIncharge'];
				$cparCorrectiveFlowOutIncharge = $resultMain['cparCorrectiveFlowOutIncharge'];
				$cparPreventiveAction = $resultMain['cparPreventiveAction'];
				$cparPreventiveActionIncharge = $resultMain['cparPreventiveActionIncharge'];
				$cparPreventiveActionDate = $resultMain['cparPreventiveActionDate'];
				$cparVerification = $resultMain['cparVerification'];
				$cparVerificationIncharge = $resultMain['cparVerificationIncharge'];
				$cparVerificationDate = $resultMain['cparVerificationDate'];
				$cparStatus = $resultMain['cparStatus'];
				$checkedBy = $resultMain['checkedBy'];
				$verifyBy = $resultMain['verifyBy'];
				$assignEmployee = $resultMain['assignEmployee'];
				
				$submitButton='';
				if($_SESSION['idNumber']=='0407')
				{	
					if($status==1) //leader
					{
						$submitButton="<input type='Submit' name='Submit' value='Assign' class='submitButton' onsubmit=\"return confirm('Are you sure?')\">";
					}
					else if($status==2) //employee
					{
						$submitButton="<input type='Submit' name='Submit' value='Submit' class='submitButton' onsubmit=\"return confirm('Are you sure?')\">";
					}
					else if($status==3) //leader
					{
						$submitButton="<input type='Submit' name='Submit' value='Confirm' class='submitButton' onsubmit=\"return confirm('Are you sure?')\">";
					}
					else if($status==4) //header leader
					{
						$submitButton="<input type='Submit' name='Submit' value='Check' class='submitButton' onsubmit=\"return confirm('Are you sure?')\">";
					}
					else if($status==5)//mam nabel & megs;
					{
						$submitButton="<input type='Submit' name='Submit' value='Verify' class='submitButton' onsubmit=\"return confirm('Are you sure?')\">";
					}
					else if($status==6)
					{
						$submitButton="<input type='Submit' name='Submit' value='Approved' class='submitButton' onsubmit=\"return confirm('Are you sure?')\">";
					}
				}
				else
				{
					if($status==1) //concerned person;
					{
						$submitButton="<input type='Submit' name='Submit' value='Submit' class='submitButton' onsubmit=\"return confirm('Are you sure?')\">";
					}
					else if($status==2) //leader;
					{
						$submitButton="<input type='Submit' name='Submit' value='Check' class='submitButton' onsubmit=\"return confirm('Are you sure?')\">";
					}
					else if($status==3) //head leader;
					{
						$submitButton="<input type='Submit' name='Submit' value='Confirm' class='submitButton' onsubmit=\"return confirm('Are you sure?')\">";
					}
					else if($status==4) //mam nabel && megs;
					{
						$submitButton="<input type='Submit' name='Submit' value='Verify' class='submitButton' onsubmit=\"return confirm('Are you sure?')\">";
					}
					else if($status==5)//manager;
					{
						$submitButton="<input type='Submit' name='Submit' value='Approved' class='submitButton' onsubmit=\"return confirm('Are you sure?')\">";
					}
				}
				
				$disabled='';
				//~ if(!in_array($_SESSION['idNumber'], array('0239', '0206'))) //If session is not mam nabel & megs;
				if(!in_array($_SESSION['idNumber'], array('0215', '0239', '0377'))) //If session is not sir roldan, ma'am nabel freya;
				{
					$disabled='disabled'; //The upper data will be disabled;
				}
				
				$cparPDF="../../Document Management System/CPAR Folder/".$_GET['cparId'].".pdf";
			}
		}
		else //old code;
		{
			$sql = "SELECT * FROM qc_cpar WHERE cparId LIKE '".$_GET['cparId']."' LIMIT 1";
			$query = $db->query($sql);
			if($query AND $query->num_rows > 0)
			{
				$resultMain = $query->fetch_assoc();
				
				$cparType = $resultMain['cparType'];
				$cparOwner = $resultMain['cparOwner'];
				$cparSection = $resultMain['cparSection'];
				$cparDueDate = $resultMain['cparDueDate'];
				$cparInfoSource = $resultMain['cparInfoSource'];
				$cparInfoSourceSubcon = $resultMain['cparInfoSourceSubcon'];
				$cparInfoSourceRemarks = $resultMain['cparInfoSourceRemarks'];
				$cparMaker = $resultMain['cparMaker'];
				$cparDetails = $resultMain['cparDetails'];
				$cparDisposition = $resultMain['cparDisposition'];
				$cparDispositionDetails = $resultMain['cparDispositionDetails'];
				$cparCause = $resultMain['cparCause'];
				$cparSourcePerson = $resultMain['cparSourcePerson'];
				$cparDetectProcess = $resultMain['cparDetectProcess'];
				$cparDetectPerson = $resultMain['cparDetectPerson'];
				$cparDetectDate = $resultMain['cparDetectDate'];
				$cparItemPrice = $resultMain['cparItemPrice'];
				$cparAction = $resultMain['cparAction'];
				$cparInterimAction = $resultMain['cparInterimAction'];
				$cparAnalysis = $resultMain['cparAnalysis'];
				$cparReturnDate = $resultMain['cparReturnDate'];
				$cparPRSNumber = $resultMain['cparPRSNumber'];
				$cparProductionSchedule = $resultMain['cparProductionSchedule'];
				$cparSubconSchedule = $resultMain['cparSubconSchedule'];
				$cparCustomerSchedule = $resultMain['cparCustomerSchedule'];
				$cparRecoveryIncharge = $resultMain['cparRecoveryIncharge'];
				$assignEmployee = $resultMain['assignEmployee'];
				
				$approvedBtn = $cparCauseProcess = $cparCauseFlowOut = $cparCorrectiveProcess = $cparCorrectiveFlowOut = $cparCorrectiveProcessDate = $cparCorrectiveFlowOutDate = '';
				$cparCorrectiveProcessIncharge = $cparCorrectiveFlowOutIncharge = $cparPreventiveAction = $cparPreventiveActionIncharge = $cparPreventiveActionDate = '';
				$cparVerification = $cparVerificationIncharge = $cparVerificationDate = $cparStatus = '';
				if(in_array($_SESSION['idNumber'], array('0215', '0291', '0239', '0206', '0048',  '0145', '0063', '0282', '0276', '0331', '0228'))) //roldan m., john p.; / nabel, megs / sir ariel;
				{
					$cparCauseProcess = $resultMain['cparCauseProcess'];
					$cparCauseFlowOut = $resultMain['cparCauseFlowOut'];
					$cparCorrectiveProcess = $resultMain['cparCorrectiveProcess'];
					$cparCorrectiveFlowOut = $resultMain['cparCorrectiveFlowOut'];
					$cparCorrectiveProcessDate = $resultMain['cparCorrectiveProcessDate'];
					$cparCorrectiveFlowOutDate = $resultMain['cparCorrectiveFlowOutDate'];
					$cparCorrectiveProcessIncharge = $resultMain['cparCorrectiveProcessIncharge'];
					$cparCorrectiveFlowOutIncharge = $resultMain['cparCorrectiveFlowOutIncharge'];
					$cparPreventiveAction = $resultMain['cparPreventiveAction'];
					$cparPreventiveActionIncharge = $resultMain['cparPreventiveActionIncharge'];
					$cparPreventiveActionDate = $resultMain['cparPreventiveActionDate'];
					$cparVerification = $resultMain['cparVerification'];
					$cparVerificationIncharge = $resultMain['cparVerificationIncharge'];
					$cparVerificationDate = $resultMain['cparVerificationDate'];
					$cparStatus = $resultMain['cparStatus'];
					
					//CHECKING;
					if(!in_array($_SESSION['idNumber'], array('0239', '0206', '0048')))
					{
						$approvedBtn = "<input type = 'Submit' style='font-size:20px;width:10vw;height:5vh;' name = 'Checked' value = 'Checked' class = 'art-button' onsubmit=\"return confirm('Are you sure?')\">";
					}
				}
				
				//--------------------------------- SUBMIT --------------------------//
				$submitBtn = $disabled = '';
				if(in_array($_SESSION['idNumber'], array('0083', '0300', '0049', '0266', '0207', '0231', '0174', '0009'))) //leaders;
				{
					$submitBtn = "<input type = 'Submit' style='font-size:20px;width:10vw;height:5vh;' name = 'Submit' value = 'Submit' class = 'art-button'>";
					$disabled='disabled';
				}
				//------------------------------- END of SUBMIT --------------------------//
				
				if(in_array($_SESSION['idNumber'], array('0215', '0291', '0145', '0063', '0282', '0276'))) $disabled='disabled';
				
				//---------------------------------------- VERIFY -------------------------------//
				$verifyBtn = $displayApprVerf = '';
				if(in_array($_SESSION['idNumber'], array('0239', '0206'))) //mam nabel,megs;
				{
					$checkedBy = $resultMain['checkedBy'];
					$sql = "SELECT CONCAT(firstName,' ',surName) AS fullName FROM hr_employee WHERE idNumber LIKE '".$checkedBy."' AND status = 1 LIMIT 1";
					$queryName = $db->query($sql);
					if($queryName AND $queryName->num_rows > 0)
					{
						$resultName = $queryName->fetch_assoc();
						$fullName = $resultName['fullName'];
						$displayApprVerf="<small>Checked by: </small><font color='blue'><u>".$fullName."</u></font>";
					}
					
					$verifyBtn = "<input type = 'Submit' style='font-size:20px;width:10vw;height:5vh;' name = 'Verify' value = 'Verify' class = 'art-button' onsubmit=\"return confirm('Are you sure?')\">";
				}
				//-------------------------------------- END of VERIFY -------------------------------//
				
				//OK;
				$okBtn = $disapprovedBtn = '';
				if(in_array($_SESSION['idNumber'], array('0048'))) //sir ariel;
				{
					$verifyBy = $resultMain['verifyBy'];
					$sql = "SELECT CONCAT(firstName,' ',surName) AS fullName FROM hr_employee WHERE idNumber LIKE '".$verifyBy."' AND status = 1 LIMIT 1";
					$queryName = $db->query($sql);
					if($queryName AND $queryName->num_rows > 0)
					{
						$resultName = $queryName->fetch_assoc();
						$fullName = $resultName['fullName'];
						$displayApprVerf="<small>Verify by: </small><font color='blue'><u>".$fullName."</u></font>";
						$okBtn = "<input type = 'Submit' style='font-size:20px;width:10vw;height:5vh;' name = 'Ok' value = 'Approved' class = 'art-button' onclick=\"return confirm('Are you sure?')\">";
					}
				}
			}
		}
	}
?>

<style>
	#text
	{
		border-radius: 5px;
	}

	#text1
	{
		width: 53.5%;
		border-radius: 5px;
		margin-left: 3%;
	}

	#text2
	{
		width: 53.5%;
		border-radius: 5px;
		margin-left: 11.5%;
	}

	#text3
	{
		width: 53.5%;
		border-radius: 5px;
	}

	#message
	{
		border-radius: 5px;
	}

	label, textarea
	{
	  display:inline-block;
	  vertical-align:middle;
	 }

	#cHide
	{
		width: 48%;
		border-radius: 5px;
	}

	#container
	{
		width: 100%;
	}

	#leftContainer
	{
		float: left;
		width: 63%;
	}

	#rightContainer
	{
		float: right;
		width: 25%;
		margin-right: 100px;
	}

	div.height_separator
	{
		height:25px;
	}
</style>

<link type="text/css" href="../css_tina/cupertino/jquery-ui-1.8.14.custom.css" rel="stylesheet" />  
    <link rel="stylesheet" type="text/css" href="../jo_css/jquery.autocomplete.css" />
    <script type="text/javascript" src="../js_tina/jquery-1.5.1.min.js"></script>
    <script type="text/javascript" src="../js_tina/jquery-ui-1.8.14.custom.min.js"></script>
    <script type="text/javascript" src="jo_js/jquery.autocomplete.js"></script>
    <script type="text/javascript">
      $(function(){
        // FORMAT DATEPICKER TO 10-Jan-11
        $( ".pickerClass" ).datepicker({ 
			dateFormat: 'dd/mm/y' ,
			showOn: "both",
			buttonImage: "../icons/calendar.png",
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true 
        });
        // Datepicker
        $('input.pickerClass').datepicker({
          inline: true
        });
        
        $("#inputName").autocomplete("queries_auto.php", {
                selectFirst: true
    
               });

        $("#inputName1").autocomplete("queries_auto.php", {
                selectFirst: true
    
               });

        $("#inputName2").autocomplete("queries_auto.php", {
                selectFirst: true
    
               });
        
        $("#inputName3").autocomplete("queries_auto.php", {
                selectFirst: true
    
               });

        $("#inputName4").autocomplete("queries_auto.php", {
                selectFirst: true
    
               });

        $("#inputName5").autocomplete("queries_auto.php", {
                selectFirst: true
    
               });

        $("#inputName6").autocomplete("queries_auto.php", {
                selectFirst: true
    
               });

        $("#inputName7").autocomplete("queries_auto.php", {
                selectFirst: true
    
               });

        //hover states on the static widgets
        $('#dialog_link, ul#icons li').hover(
          function() { $(this).addClass('ui-state-hover'); }, 
          function() { $(this).removeClass('ui-state-hover'); }
        );
        
        
        var i = $('input.field').size() + 1;
              
    
    $('#add').click(function() {
    //alert("test");
    if(i <= 5){ 
        $('<div><input type="text" class="field" name="dynamic[]" value="" size="10" /> &nbsp <input type="text" class="field2" name="dynamic2[]" value="" size="10" onkeypress="validateNum(event)" />&nbsp&nbsp <select name="dynamic3[]" class="field3" ><option value=""></option><option value="0.02">0.02</option><option value="0.01">0.01</option></select></div>').fadeIn('slow').appendTo('.inputs');
        i++;
         }
         else
        {
        alert('Reached Maximum No. of Invoice...'); 
      }
    });
   
    
     
    $('#remove').click(function() {
    if(i > 1) {
        $('.field:last').remove();
        $('.field2:last').remove();
        i--;
    }
    });
     
    $('#reset').click(function() {
    while(i > 2) {
        $('.field:last').remove();
        $('.field2:last').remove();
        i--;
    }
    });
     
    // here's our click function for when the forms submitted
 
    $('.art-button').live('click', function (e) {
    if($('#inputDate').val()=="")
      {
    alert("Please enter check date.");
    $('#inputDate').focus();
    return false;
      }
      if($('#inputInvoiceDate').val()=="")
      {
    alert("Please enter APV date.");
    $('#inputInvoiceDate').focus();
    return false;
      }
      if($('#checkNumber').val()=="")
      {
    alert("Please enter check number.");
    $('#checkNumber').focus();
    return false;
      }
      if($('#inputNumber').val()=="")
      {
    alert("Please enter voucher number.");
    $('#inputNumber').focus();
    return false;
      }
		if($('#inputName').val()=="")
      {
    alert("Please input supplier name.");
    $('#inputName').focus();
    return false;
      }
  
      var answersInvoiceNumber = [];
        var answersAmount = [];
        var answersWTax = [];
   
        $.each($('.field'), function() {
        answersInvoiceNumber.push($(this).val());
        });
    
        $.each($('.field2'), function() {
        answersAmount.push($(this).val());
        });
        
        $.each($('.field3'), function() {
        answersWTax.push($(this).val());
        });
     
        if(answersInvoiceNumber.length == 0) {
        answersInvoiceNumber = "none";
        answersAmount = "none";
        } 
     
        if(answersInvoiceNumber[0] == "") {
        alert("Please input Invoice Number.");
        return false;
        }
       
        if(answersAmount[0] == "") {
        alert("Please input Amount.");
        return false;
        }
        
         
      if (document.getElementById("inputCheckbox").checked) {
           inputCheckbox1 = 1;
        }
        else
        {
       inputCheckbox1 = 0;
    }
    
      $.ajax({
                url: "setDataSession.php",
                type:'POST',
                data: { invoiceNumberArray : answersInvoiceNumber,amountArray : answersAmount,inputDate : $('#inputDate').val(),inputInvoiceDate : $('#inputInvoiceDate').val(),amount : $('#amount').val(),inputName : $('#inputName').val(),inputNumber : $('#inputNumber').val(),inputCheckbox : inputCheckbox1,answersWTaxArray : answersWTax },
                success: function(data){
                        window.open(
                            'anthony_inputForm.php?source=landholding',
                            'fenster',
                            'width=600,height=600,location=_blank'
                        );
                    } // End of success function of ajax form
        }); // End of ajax call
                                
    });
        
        
        
        
      });
      
	function validateNum(evt) 
	{
	  var theEvent = evt || window.event;
	  var key = theEvent.keyCode || theEvent.which;
	  key = String.fromCharCode( key );
	  var regex = /[0-9]|\./;
	  if( !regex.test(key) ) {
		theEvent.returnValue = false;
		if(theEvent.preventDefault) theEvent.preventDefault();
	  }
	} 
    </script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US" xml:lang="en">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Arktech Philippines Incorporated</title>
	<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
</head>
<div id = 'container'>
	<div id = 'leftContainer'>
		<div align="left" style="border:2px solid;border-radius:25px;width:730px;padding: 10px">
			<table>
				<tr>
					<td>
						<?php
							if($_SESSION['idNumber']=='0407') //new code;
							{
								if($status==5) //mam nabel && megs;
								{
									$sql = "SELECT CONCAT(firstName,' ', surName) AS checkName FROm hr_employee WHERE idNumber LIKE '".$checkedBy."' AND status = 1 LIMIT 1";
									$queryCheck = $db->query($sql);
									if($queryCheck->num_rows > 0)
									{
										$resultCheck = $queryCheck->fetch_assoc();
										$checkedBy = $resultCheck['checkName'];
										?>
										<label>Checked by:</label>
										<span style='color:green;'><?php echo $checkedBy; ?></span>
										<?php
									}
								}
								else if($status==6) //manager;
								{
									$sql = "SELECT CONCAT(firstName,' ', surName) AS checkName FROm hr_employee WHERE idNumber LIKE '".$verifyBy."' AND status = 1 LIMIT 1";
									$queryCheck = $db->query($sql);
									if($queryCheck->num_rows > 0)
									{
										$resultCheck = $queryCheck->fetch_assoc();
										$verifyBy = $resultCheck['checkName'];
										?>
										<label>Verify by:</label>
										<span style='color:green;'><?php echo $verifyBy; ?></span>
										<?php
									}										
								}								
							}
							else
							{
								if($status==4) //mam nabel && megs;
								{
									$sql = "SELECT CONCAT(firstName,' ', surName) AS checkName FROm hr_employee WHERE idNumber LIKE '".$checkedBy."' AND status = 1 LIMIT 1";
									$queryCheck = $db->query($sql);
									if($queryCheck->num_rows > 0)
									{
										$resultCheck = $queryCheck->fetch_assoc();
										$checkedBy = $resultCheck['checkName'];
										?>
										<label>Checked by:</label>
										<span style='color:green;'><?php echo $checkedBy; ?></span>
										<?php
									}
								}
								else if($status==5) //manager;
								{
									$sql = "SELECT CONCAT(firstName,' ', surName) AS checkName FROm hr_employee WHERE idNumber LIKE '".$verifyBy."' AND status = 1 LIMIT 1";
									$queryCheck = $db->query($sql);
									if($queryCheck->num_rows > 0)
									{
										$resultCheck = $queryCheck->fetch_assoc();
										$verifyBy = $resultCheck['checkName'];
										?>
										<label>Verify by:</label>
										<span style='color:green;'><?php echo $verifyBy; ?></span>
										<?php
									}										
								}
							}
						?>
					</td>
				</tr>
			</table>
		<div class="art-post-inner art-article">
			<h2 class="art-postheader"><center>CPAR Management Software</center></h2>
		<div class="art-postcontent">
		<p>
		
		<form id='formUpload' method='POST' action='anthony_uploadFile.php' enctype="multipart/form-data"></form>
		<label style = "float: right;">
			<input type = 'hidden' name = 'cparId' value='<?php echo $_GET['cparId']; ?>' form = 'formUpload'>
			<?php
			if($_SESSION['userType']!='') //new code;
			{
				if(file_exists($cparPDF))
				{
					?>
					<input type='image' onclick="TINY.box.show({iframe:'<?php echo $cparPDF; ?>',width:999,height:600,opacity:50,topsplit:6,animate:false,close:true})" src='../Common Data/Templates/images/view1.png' width=55 height=50 title='View PDF'>
					<?php
				}
				else
				{
					?>
					<input type="file" name="fileToUpload" id="fileToUpload" form='formUpload' style='background-color:whitesmoke;border-radius:5px;box-shadow:2px 2px 2px 2px grey;'>
					<input type="Submit" name="submit" value="Upload" form='formUpload'>
					<?php
				}				
			}
			else //old code;
			{
				?>
				<input type = 'file' name = 'pdfFile' form = 'formUpload' style='background-color:whitesmoke;border-radius:5px;box-shadow:2px 2px 2px 2px grey;'>&nbsp;&nbsp;
				<input type = 'Submit' name = 'upload_pdf' form = 'formUpload' id = 'upload_file'>
				<?php
			}
			?>
		</label>
		
		<?php
			if(isset($_GET['cparId']))
			{
				echo "<label style='float:left;'>CPAR ID: ".$_GET['cparId']."</label>";				
				
				if($_SESSION['idNumber']=='0407')
				{
					if($status==5) //return;
					{
						echo "<br>";
						echo "<form id='returnForm' method='POST' action='paul_cparSqlV2.php'></form>";
						echo "<input type='hidden' name='cparId' value='".$_GET['cparId']."' form='returnForm'>
						<input type='Submit' name='Return' value='Return' form='returnForm' class = 'art-button' onclick=\"return confirm('Are you sure you want to return?')\" style='font-weight:Bold;background-color:#ff6961;width:15vw;height:5vh;'>";
					}
					else if($status==6) //disapproved;
					{
						echo "<form id='disapprovedForm' method='POST' action='paul_cparSqlV2.php'></form>";
						echo "<br>
						<input type='hidden' name='cparId' value='".$_GET['cparId']."' form='disapprovedForm'>
						<input type='Submit' name='Disapproved' value='Disapproved' form='disapprovedForm' class = 'art-button' onclick=\"return confirm('Are you sure?')\" style='font-weight:Bold;background-color:#ff6961;width:15vw;height:5vh;'>";
					}					
				}
				else
				{
					if($status==4) //return;
					{
						echo "<br>";
						echo "<form id='returnForm' method='POST' action='paul_cparSqlV2.php'></form>";
						echo "<input type='hidden' name='cparId' value='".$_GET['cparId']."' form='returnForm'>
						<input type='Submit' name='Return' value='Return' form='returnForm' class = 'art-button' onclick=\"return confirm('Are you sure you want to return?')\" style='font-weight:Bold;background-color:#ff6961;width:15vw;height:5vh;'>";
					}
					else if($status==5) //disapproved;
					{
						echo "<form id='disapprovedForm' method='POST' action='paul_cparSqlV2.php'></form>";
						echo "<br>
						<input type='hidden' name='cparId' value='".$_GET['cparId']."' form='disapprovedForm'>
						<input type='Submit' name='Disapproved' value='Disapproved' form='disapprovedForm' class = 'art-button' onclick=\"return confirm('Are you sure?')\" style='font-weight:Bold;background-color:#ff6961;width:15vw;height:5vh;'>";
					}
				}
			}
		?>
		
		<br>
		<br>
		
			<?php
				if($_SESSION['idNumber']=='0407')
				{
					echo "<form action = 'paul_cparSqlV3.php' method = 'POST' id = 'insertCpart'>";
				}
				else
				{
					echo "<form action = 'paul_cparSqlV2.php' method = 'POST' id = 'insertCpart'>";
				}
			?>
			<label>Assign:</label>
			<select name="assignEmployee" id="assignEmployee" <?php echo $disabled; ?> style = "color:black;border-radius:5px;background-color:#fdfd96;">
				<option></option>
				<?php
					$selectedEmployee='';
					$sql = "SELECT idNumber, CONCAT(firstName,' ',surName) AS pangalan FROM hr_employee WHERE status = 1 AND departmentId != 17 ORDER BY firstName";
					$queryPangalan = $db->query($sql);
					if($queryPangalan->num_rows > 0)
					{
						while($resultPangalan = $queryPangalan->fetch_assoc())
						{
							$aydiNumber = $resultPangalan['idNumber'];
							$pangalan = $resultPangalan['pangalan'];
							
							$selectedEmployee = ($assignEmployee==$aydiNumber) ? 'selected' : '';
							echo "<option value='".$aydiNumber."' ".$selectedEmployee.">".$pangalan."</option>";
						}
					}
				?>
			</select>			
			<?php
			
			echo "<input type = 'hidden' name = 'cparId' value = '".$_GET['cparId']."'>";
			$selectedActionProduct = ($cparType == 'Product Nonconformance') ? "checked" : "";
			$selectedActionSystem = ($cparType == 'System Nonconformance') ? "checked" : "";
			
			$sql = "SELECT CONCAT(firstName,' ',surName) AS fullNeym FROM hr_employee WHERE idNumber LIKE '".$cparOwner."' AND status = 1 LIMIT 1";
			$queryEmployee = $db->query($sql);
			if($queryEmployee->num_rows > 0)
			{
				$resultEmployee = $queryEmployee->fetch_assoc();
				$cparOwner = $resultEmployee['fullNeym'];
			}
		?> 
		<table border = '2'>
			<tr>
			<td width="200">
				<input <?php echo $disabled; ?> type = "radio" name = "action" id = "radio" value = "Product Nonconformance" <?php echo $selectedActionProduct; ?> >Product Nonconformance<br>
				<input <?php echo $disabled; ?> type = "radio" name = "action" id = "radio" value = "System Nonconformance" <?php echo $selectedActionSystem; ?> >System Nonconformance
				
			</td>
			<td width="300">
			Attention To: <input <?php echo $disabled; ?> type="text" name="inputName" id="inputName" style = "border-radius:5px; width:70%;" value = '<?php echo $cparOwner; ?>' /><br>
			
			
			<!-- Section: <input <?php echo $disabled; ?> list = "listsection" name = "inputSection" id="inputName" style = "border-radius:5px; width:70%;" value = '<?php echo $cparSection; ?>' />
				<datalist id = 'listsection'>
					<?php
						$sql = "SELECT sectionName FROM ppic_section ORDER BY sectionName";
						$querySection = $db->query($sql);
						if($querySection AND $querySection->num_rows > 0)
						{
							while($resultSection = $querySection->fetch_assoc())
							{
								echo "<option value = '".$resultSection['sectionName']."'>".$resultSection['sectionName']."</option>";
							} 
						}
					?>
				</datalist> -->
				
				<?php
					$selectedEngr=$selectedBending=$selectedBlanking=$selectedLaser=$selectedWelding=$selectedPress=$selectedPowderType=$selectedWetType='';
					$selectedWarehouse=$selectedQCFVI=$selectedIT='';
					if($cparSection=='Engineering') $selectedEngr='selected';
					else if($cparSection=='Bending') $selectedBending='selected';
					else if($cparSection=='TPP') $selectedBlanking='selected';
					else if($cparSection=='Laser') $selectedLaser='selected';
					else if($cparSection=='Welding Assembly') $selectedWelding='selected';
					else if($cparSection=='Press') $selectedPress='selected';
					else if($cparSection=='Powder-Type Painting') $selectedPowderType='selected';
					else if($cparSection=='Wet-Type Painting') $selectedWetType='selected';
					else if($cparSection=='Warehouse') $selectedWarehouse='selected';
					else if($cparSection=='QC/FVI') $selectedQCFVI='selected';
					else if($cparSection=='IT') $selectedIT='selected';
				?>
				
				Section: <select name = "inputSection" id="inputName" style = "border-radius:5px;width:70%;" <?php echo $disabled; ?> required>
					<option></option>
					<option value='IT' <?php echo $selectedIT; ?>>IT</option>
					<option value='Engineering' <?php echo $selectedEngr; ?>>Engineering</option>
					<option value='Bending' <?php echo $selectedBending; ?>>Bending</option>
					<option value='TPP' <?php echo $selectedBlanking; ?>>TPP</option>
					<option value='Laser' <?php echo $selectedLaser; ?>>Laser</option>
					<option value='Welding Assembly' <?php echo $selectedWelding; ?>>Welding Assembly</option>
					<option value='Press' <?php echo $selectedPress; ?>>Press</option>
					<option value='Powder-Type Painting' <?php echo $selectedPowderType; ?>>Powder-Type Painting</option>
					<option value='Wet-Type Painting' <?php echo $selectedWetType; ?>>Wet-Type Painting</option>
					<option value='Warehouse' <?php echo $selectedWarehouse; ?>>Warehouse</option>
					<option value='QC/FVI' <?php echo $selectedQCFVI; ?>>QC/FVI</option>
				</select>
				
				<br>
			</td>
			<td width="220">
				Reply Due Date:<br>	<input <?php echo $disabled; ?> type = 'date' name = 'replyDueDate' style = "border-radius:5px;" value = '<?php echo $cparDueDate ?>'>
			</td>
			</tr>
		</table>
		<table border="2">
			<tr>
			<td width="800">
				<?php
					$selectedSourceInfo1 = ($cparInfoSource == 'Internal') ? "checked" : "";
					$selectedSourceInfo2 = ($cparInfoSource == 'Supplier') ? "checked" : "";
					$selectedSourceInfo3 = ($cparInfoSource == 'Subcon') ? "checked" : "";
					$selectedSourceInfo4 = ($cparInfoSource == 'Customer') ? "checked" : "";
					$selectedSourceInfo5 = ($cparInfoSource == 'System') ? "checked" : "";
				?>
				<b>Source of Information:</b><br>
				<input <?php echo $disabled; ?> <?php echo $selectedSourceInfo1; ?> type = "radio" name = "sourceInfo" class = "radios" value = "CPAR-INT_Internal" >INTERNAL<font size = "1.5"> ( In-process / Outgoing Inspection / Incoming Inspection )</font><br>
				
				<input <?php echo $disabled; ?> <?php echo $selectedSourceInfo2; ?> type = "radio" name = "sourceInfo" class = "radios" value = "CPAR-SUP_Supplier" onclick="selectShow();" >
				
				SUPPLIER &nbsp; <input <?php echo $disabled; ?> list = "list" name = "inputSupplierSubCon" id="inputName" style = "border-radius:5px; width:50%;" value = '<?php echo $cparInfoSourceSubcon; ?>' />
				 <datalist id = 'list'>
					<?php
						$sql = "SELECT supplierAlias AS Supplier FROM purchasing_supplier UNION ALL SELECT subconAlias AS Supplier FROM purchasing_subcon ORDER BY Supplier ASC";
						$querySupplier = $db->query($sql);
						if($querySupplier AND $querySupplier->num_rows > 0)
						{
							while($resultSupplier = $querySupplier->fetch_array())
							{                                                                   
								echo "<option value = '".$resultSupplier['Supplier']."'>".$resultSupplier['Supplier']."</option>";
							} 
						}
					?>
				 </datalist>
				 
				 <br>
				 
				<input <?php echo $disabled; ?> <?php echo $selectedSourceInfo3; ?> type = "radio" name = "sourceInfo" class = "radios" value = "CPAR-SUB_Subcon" >SUBCON<br>
				
				<input <?php echo $disabled; ?> <?php echo $selectedSourceInfo4; ?> type = "radio" name = "sourceInfo" class = "radios" value = "CPAR-CUS_Customer Claim" onclick="hideShow();" >
				CUSTOMER CLAIM&nbsp;&nbsp;&nbsp;&nbsp;<input type = 'text' name = 'customerHide' id = 'cHide' style = "display:none; width:20%;" value = '<?php echo $cparInfoSourceRemarks; ?>'><br>
				
				<input <?php echo $disabled; ?> <?php echo $selectedSourceInfo5; ?> type = "radio" name = "sourceInfo" class = "radios" value = "CPAR-SYS_System" id = "system" >SYSTEM
			</td>
			</tr>
		</table>

		<table border = "2">
			<tr>
			<td width="230">
				<center><b>Description of the Problem:</b><br></center>
				<!-- N.G. Quantity:<input type = "number" min = "1" name = "NG" id = "text" style = "width:58%;" required><br> -->
				Details for Nonconformance:<br><textarea <?php echo $disabled; ?> name = "message1" rows = "7" cols = "30" id = "message" style = "width:100%" ><?php echo $cparDetails; ?></textarea><br>
			</td>
			<td width="180">
				<?php
					$cbox1 = ($cparDisposition == 'Sort/Re-inspect') ? "checked" : "";
					$cbox2 = ($cparDisposition == 'Rework') ? "checked" : "";
					$cbox3 = ($cparDisposition == 'Scrap/Disposal/Replacement') ? "checked" : "";
					$cbox4 = ($cparDisposition == 'Return to Supplier/Subcon') ? "checked" : "";
					$cbox5 = ($cparDisposition == 'Special Acceptance') ? "checked" : "";
					$cbox6 = ($cparDisposition == 'Others') ? "checked" : "";
				?>
				<center><b><u>Disposition:</u></b><br></center>
				<input <?php echo $disabled; ?> type = "checkbox" name = "dispo[]" value = "Sort/Re-inspect" <?php echo $cbox1; ?>>Sort / Re-inspect<br>
				<input <?php echo $disabled; ?> type = "checkbox" name = "dispo[]" value = "Rework" <?php echo $cbox2; ?>>Rework<br>
				<input <?php echo $disabled; ?> type = "checkbox" name = "dispo[]" value = "Scrap/Disposal/Replacement" <?php echo $cbox3; ?>><font size = "1.5">Scrap/Disposal/Replacement</font><br>
				<input <?php echo $disabled; ?> type = "checkbox" name = "dispo[]" value = "Return to Supplier/Subcon" <?php echo $cbox4; ?>>Return to Supplier/SubCon<br>
				<input <?php echo $disabled; ?> type = "checkbox" name = "dispo[]" value = "Special Acceptance" <?php echo $cbox5; ?>>Special Acceptance<br>
				<input <?php echo $disabled; ?> type = "checkbox" name = "dispo[]" value = "Others" id = "chkBox" onclick = "showHide();" <?php echo $cbox6; ?>>Others
				<input <?php echo $disabled; ?> type = "text" name = "hideText" id = "txtBox" style="display:none;">
			</td>
			<td width="305">
				Process of N.G.:<input <?php echo $disabled; ?> type = "text" name = "prong" id = "text" style = "margin-left:5.3%;" value = '<?php echo $cparCause; ?>'><br>
				
				<?php
					$sql = "SELECT CONCAT(firstName,' ', surName) AS concernedPerson FROM hr_employee WHERE idNumber LIKE '".$cparSourcePerson."' AND status = 1 LIMIT 1";
					$queryPerson = $db->query($sql);
					if($queryPerson->num_rows > 0)
					{
						$resultPerson = $queryPerson->fetch_assoc();
						$cparSourcePerson = $resultPerson['concernedPerson'];
					}
				?>
				Concerned Person:<input <?php echo $disabled; ?> type="text" name="inputName1" id="inputName1" value = "<?php echo $cparSourcePerson; ?>" style = "border-radius:5px;" placeholder = " example (Dela Cruz, J.)"><br>
				
				Detecting Process:<input <?php echo $disabled; ?> type = "text" name = "detectProcess" id = "text" value = '<?php echo $cparDetectProcess; ?>'><br>
				Detecting Person:<input <?php echo $disabled; ?> type="text" name="inputName2" id="inputName2" value = '<?php echo $cparDetectPerson; ?>' style = "border-radius:5px; margin-left:3%" placeholder = " example (Dela Cruz, J.)"><br>
				Date Detected:<input  <?php echo $disabled; ?> type = "date" name = "dateDetect" id = "text" style = "margin-left:9%;" value = '<?php echo $cparDetectDate; ?>'><br>
			  Item Price:<input  <?php echo $disabled; ?> type = "text" name = "itemPrice" min = "0" style = "border-radius:5px; margin-left:16.8%" value ='<?php echo $cparItemPrice; ?>'><br>    	
				
				<input <?php echo $disabled; ?> <?php if($cparAction == 'Need Corrective Action') echo "checked"; ?> type = "radio" name = "choices" id = "radios" value = "Need Corrective Action" >Need Corrective Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<br>
				<input <?php echo $disabled; ?> <?php if($cparAction == 'Information Only') echo "checked"; ?> type = "radio" name = "choices" id = "radios" value = "Information Only" >Information Only
			</td>
			</tr>
			
			<tr>
			<td>
				<b>INTERIM ACTION:</b><br>
				<textarea <?php if(!in_array($_SESSION['idNumber'], array('0215', '0239', '0377'))) echo 'disabled'; ?> name = "interim" rows = "10" cols = "30" id = "message" style = "width:100%"><?php echo $cparInterimAction; ?></textarea>
			</td>
			<td>	
				<b>4M ANALYSIS:</b><br>
				<input <?php echo $disabled; ?> <?php if($cparAnalysis == 'Man') echo "checked"; ?> type = "checkbox" name = "4m[]" value = "Man">Man<br>
				<input <?php echo $disabled; ?> <?php if($cparAnalysis == 'Machine') echo "checked"; ?> type = "checkbox" name = "4m[]" value = "Machine">Machine<br>
				<input <?php echo $disabled; ?> <?php if($cparAnalysis == 'Method and Measurement') echo "checked"; ?> type = "checkbox" name = "4m[]" value = "Method and Measurement">Method and Measurement<br>
				<input <?php echo $disabled; ?> <?php if($cparAnalysis == 'Material') echo "checked"; ?> type = "checkbox" name = "4m[]" value = "Material">Material
			</td>
			<td>
				<center><b><u>Recovery Schedule:</u></b></center>
				Return Date:<input <?php echo $disabled; ?> type = "date" name = "retdate" style = "margin-left:27%;" value = '<?php echo $cparReturnDate; ?>'><br>
				PRS #:<br>
				Material Specs.:<br>
				Production Sched.:<input <?php echo $disabled; ?> type = "date" name = "prodnsched" style = "margin-left:14.5%;" value = '<?php echo $cparProductionSchedule; ?>'><br>
				Delivery To Subcon/Supp.:<input <?php echo $disabled; ?> type = "date" name = "delivsub" value = '<?php echo $cparSubconSchedule; ?>'><br>
				Delivery to Customer:<input <?php echo $disabled; ?> type = "date" name = "delivcust" style = "margin-left:10%;" value = '<?php echo $cparCustomerSchedule; ?>'><br>
				In-Charge:<input <?php echo $disabled; ?> type="text" name="inputName3" id="inputName3" value="" style = "margin-left:31.5%; border-radius:5px; width:47%;" value = '<?php echo $cparRecoveryIncharge; ?>' /><br>
			</td>
			</tr>
		</table>

		<table border = "2"> 
			<tr><td colspan="2"><b>CAUSE OF DEFECT:<b></td></tr>
			<tr>
				<td width="418">
					PROCESS CAUSE:<br>
					<textarea name = "pcause" rows = "3" cols = "30" id = "message" style = "width:100%" required><?php echo $cparCauseProcess; ?></textarea>
				</td>
				<td width="500">
					FLOW-OUT CAUSE:<br>
					<textarea name = "focause" rows = "3" cols = "30" id = "message" style = "width:100%" required><?php echo $cparCauseFlowOut; ?></textarea>
				</td>  
			</tr>
		</table>

		<table border = "2">
			<tr><td colspan="2"><b>CORRECTIVE ACTION:<b></td></tr>

			<tr>
			<td width="330">
				PROCESS:<br>
				<textarea name = "process" rows = "3" cols = "30" id = "message" style = "width:100%;" required><?php echo $cparCorrectiveProcess; ?></textarea><br>
		<table>
			<tr>
			<td>Implementation Date:<input type = "date" name = "impdate1" id = "text" value = '<?php echo $cparCorrectiveProcessDate; ?>' required></td>
			<td>Incharge:<br><input type="text" name="inputName4" id="inputName4" value = '<?php echo $cparCorrectiveProcessIncharge; ?>' style = "border-radius:5px; width:100%;" />
			</td>
			</tr>
		</table>
			</td>
			<td width="390">
				FLOW-OUT:<br>
				<textarea name = "fo" rows = "3" cols = "30" id = "message" style = "width:100%;" required><?php echo $cparCorrectiveFlowOut; ?></textarea>
		<table>
			<tr>
			<td width="200">Implementation Date:<input type = "date" name = "impdate2" id = "text" value = '<?php echo $cparCorrectiveFlowOutDate; ?>' required></td>
			<td width="200">Incharge:<input type="text" name="inputName5" id="inputName5" value = '<?php echo $cparCorrectiveFlowOutIncharge; ?>' style = "border-radius:5px; width:100%;" required />
			</td>
			</tr>
		</table>
			</td>  
			</tr>
		</table>
		<table border = "2" width="728">
			<tr>
			<td width="328">
				<b>PREVENTIVE ACTION:</b><br>
				<textarea name = "prevaction" rows = "3" cols = "30" id = "message" style = "width:100%" required><?php echo $cparPreventiveAction; ?></textarea>
			</td>
			<td><br>
			In-Charge:<br><input type="text" name="inputName6" id="inputName6" value = '<?php echo $cparPreventiveActionIncharge; ?>' style = "border-radius:5px; width:100%" required />
			</td>
			<td><br>
				Implementation Date:<br><input type = "date" name = "impdate3" style = "border-radius:5px;" value = '<?php echo $cparPreventiveActionDate; ?>' required>
			</td>
			</tr>

			<tr>
			<td>
				<b>Verification of Effectiveness</b><i> (1month monitoring)</i><br>
				<textarea name = "verif" rows = "5" cols = "30" id = "message" style = "width:100%;"><?php echo $cparVerification; ?></textarea>
			</td>
			<td>
			Verified by:<br><input type="text" name="inputName7" id="inputName7" value = '<?php echo $cparVerificationIncharge; ?>' style = "border-radius:5px; width:100%" />
		<table>
			<tr>
			<td width="194">
				Date:<input type = "date" name = "verifdate" style = "border-radius:5px; width:100%" value = '<?php echo $cparVerificationDate; ?>'>
			</td>
			</tr>
		</table>
			</td>
			<td>
				<center>Status</center><br>
				<input <?php if($cparStatus == 'Verified') echo "checked"; ?> type = "radio" name = "status" value = "Verified"> VERIFIED<br><br>
				<input <?php if($cparStatus == 'Issued') echo "checked"; ?> type = "radio" name = "status" value = "Issued" checked> ISSUED
			</td>
			</tr>
		</table>
			
		<br>
		
		<center>
			<?php
				echo $submitButton;
				//~ echo $submitBtn;
				//~ echo $approvedBtn;
				//~ echo $verifyBtn;
				//~ echo $okBtn;
			?> 
		</center>
		
		</div>
		</span>
		</form>
		</p>
		</div>
		</div>
	</div>
	
	<div id = 'rightContainer'>
		<?php
		if(isset($_GET['cparId']))
		{
			$sql = "SELECT lotNumber, quantity FROM qc_cparlotnumber WHERE cparId LIKE '".$_GET['cparId']."'";
			$queryCparlot = $db->query($sql);
			if($queryCparlot AND $queryCparlot->num_rows > 0)
			{
				echo "<table border=1 align='center'>";
					echo "<thead align='center'>
						<td>Lot Number</td>
						<td>Qty</td>
						<td>ACTION</td>
					</thead>";
					
					echo "<tbody>";
						while($resultCparlot = $queryCparlot->fetch_assoc())
						{
							$lotCpar = $resultCparlot['lotNumber'];
							$quantityCpar = $resultCparlot['quantity'];
							echo "<tr>";
								echo "<td><input readonly type = 'text' name = 'multiLot[]' class = 'multiLot' form = 'insertCpart' style = 'width:90px;' value='".$lotCpar."'></td>";
								echo "<td><input readonly type = 'number' name = 'quantity[]' class = 'quantity' form = 'insertCpart' style = 'width:40px;' value='".$quantityCpar."'></td>";
								echo "<td align='center'><input type='image' src='../Common Data/Templates/images/view1.png' width=20 height=20 class='viewLots' title='".$lotCpar."'></td>";
							echo "</tr>";
						}
					echo "</tbody>";
				echo "</table>";
				
				echo "<br>";
				
				$notificationIdArray = array();
				$sql = "SELECT notificationId FROM system_notificationdetails WHERE notificationKey = '".$_GET['cparId']."' AND notificationType = 12";
				$queryAll = $db->query($sql);
				if($queryAll AND $queryAll->num_rows > 0)
				{
					while($resultAll = $queryAll->fetch_assoc())
					{
						$notificationIdArray[] = $resultAll['notificationId'];
					}
					
					$notificationIdArray1 = array();
					$sql = "SELECT notificationId FROM system_notification WHERE notificationId IN (".implode(",", $notificationIdArray).") AND notificationTarget LIKE '".$_SESSION['idNumber']."' AND targetType = 2 AND notificationStatus = 0 ORDER BY notificationId ASC";
					$queryAlls = $db->query($sql);
					if($queryAlls AND $queryAlls->num_rows > 0)
					{
						while($resultAlls = $queryAlls->fetch_assoc())
						{
							$notificationIdArray1[] = $resultAlls['notificationId'];
						}
						
						$sql = "SELECT notificationKey FROM system_notificationdetails WHERE notificationId IN (".implode(",", $notificationIdArray1).")";
						$queryKey = $db->query($sql);
						if($queryKey AND $queryKey->num_rows > 0)
						{
							echo "<div style='overflow:auto;height:80vh;'>";
								echo "<table border=1 align='center' style='width:100%;'>";
									echo "<thead style='color:red;background-color:lightgrey;'>";
										echo "<td align='center'>All CPAR</td>";
										if($cparSection=='Engineering' AND ($_SESSION['idNumber']=='0231' OR $_SESSION['idNumber']=='0412')) 
										{
											echo "<td align='center'>ACTION</td>";
										}
									echo "</thead>";
									
									while($resultKey = $queryKey->fetch_assoc())
									{
										$notificationKey = $resultKey['notificationKey'];
										
										echo "<tr>";
											echo "<td align='center' style='color:Blue;cursor:pointer;' onclick='window.open(\"paul_cparInputForm.php?cparId=".$notificationKey."\")'><u>".$notificationKey."</u></td>";
											if($cparSection=='Engineering' AND ($_SESSION['idNumber']=='0231' OR $_SESSION['idNumber']=='0412'))
											{
												$sql = "SELECT listId FROM qc_cpar WHERE cparId LIKE '".$notificationKey."' AND assignEmployee!='' LIMIT 1";
												$queryCpar = $db->query($sql);
												if($queryCpar->num_rows == 0)
												{
													echo "<td align='center'> <input type='image' onclick=\"TINY.box.show({url:'paul_cparTinyBox.php',post:'cparId=".$notificationKey."',width:350,height:100,opacity:10,topsplit:6,animate:false,close:true})\" src='../Common Data/Templates/images/add1.png' width=18 height=18> </td>";
												}
												else
												{
													echo "<td></td>";	
												}
											}
										echo "</tr>";
									}
									
								echo "</table>";
							echo "</div>";
						}
					}
				}
			}
		}
		?>	
	</div>
</div>
</html>
<script src = '../../Common Data/Templates/jquery.js'></script>
<script type="text/javascript">
	function showHide(){
		var chkBox = document.getElementById("chkBox");
		var txtBox = document.getElementById("txtBox");

		if (chkBox.checked){
			txtBox.style.display = "inline";
		} else {
			txtBox.style.display = "none";
		}
	}

	function hideShow(){
		document.getElementById("cHide").style.display = 'inline';
	}

	function selectShow(){
		document.getElementById('sHide').style.display = 'inline';
	}

  function redirect() {
    var myWindow = window.open("anthony_converter.php?cparId=<?php echo $sqlResult['maxId']; ?>", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=500, width=400, height=400");
  }
  
	$(function(){
		//CSS;
		$('input.submitButton').css({'width':'10vw','height':'7vh','font-weight':'Bold','background-color':'cornFlowerBlue'});
		$('input[type=text] , input[type=date] , input[type=number] , textarea').css('background-color', '#fdfd96');
		$('select').css({'background-color':'#fdfd96'});
		$('input[list]').css('background-color', '#ffd1dc');
		
		var sessionIdNumber = '<?php echo $_SESSION['idNumber']; ?>';
		if(sessionIdNumber=='0407')
		{
			var statusValue = '<?php echo $status; ?>';
			if(statusValue==1)
			{
				$('#assignEmployee').attr({'disabled':false, 'required':true});
				$('#inputName1').attr({'disabled':false, 'required':true});
				
				$('#assignEmployee').change(function(){
					var idValue = $(this).val();
					if(idValue!='')
					{
						$.ajax({
							url:'paul_cparAjax.php',
							type:'POST',
							data:{idValue:idValue},
							success:function(data){
								$('#inputName1').val(data).attr('readonly', true).css({'cursor':'not-allowed'});
							}
						});
					}
					else
					{
						$('#inputName1').val('').attr('readonly', true).css({'cursor':''}).focus();
						//~ $('#inputName1').val('').attr('readonly', false).css({'cursor':''}).focus();
					}
				});
			}
			else if(statusValue==2 || statusValue==3)
			{
				$('select[name=inputSection]').attr({'disabled':false, 'readonly':true, 'required':true});
			}
		}
		
		$('.viewLots').click(function(){
			var lote = $(this).attr('title');
			window.open('../16 Lot Details Management Software/ace_lotDetails.php?inputLot='+lote);
		});
		
		<?php
			if($_SESSION['idNumber'] == '0048') //sir ariel y.;
			{
				?>
				$('input[type=text] , input[type=date] , input[list] , input[type=radio] , input[type=checkbox] , input[type=number] , select , textarea').prop('disabled', 'true');
				<?php
			}
		?>
		
		$(".multiLot").change(function(){
			var sourceInfo = $("input.radios:checked").val();
			var thisObj = $(this);
			var index = $(".multiLot").index(this);
			$.ajax({
				url		: "anthony_cparInputFormAjax.php",
				type	: "post",
				data	: {
							lotNumber:$(this).val(),
							type:'processName',
							sourceInfo:sourceInfo
						  },
				success	: function(data){
					//~ alert(data);
					if(data.indexOf("|")==-1)
					{
						var array = data.split("`");
						if(array[1]!='')
						{
							$(".lotProcess:eq("+index+")").html(array[0]);
							$(".quantity:eq("+index+")")
								.val(array[1])
								.attr('min','1')
								.attr('max',array[1])
								.attr('required',true);
						}
						else
						{
							$(".lotProcess:eq("+index+")").html("<option>-Process-</option>");
							$(".quantity:eq("+index+")")
								.val('')
								.attr('min','')
								.attr('max','')
								.attr('required',false);
						}
					}
					else
					{
						alert(data.replace("|", ""));
						$(thisObj).val("").focus();
					}
				}
			});
		});		
		
		$(".radios").change(function(){
			if($(this).val() == 'CPAR-SYS_System')
			{
				$(".multiLot").prop("required", false);
			}
			else
			{
				$(".multiLot").prop("required", true);
			}
		});	
		
		$(".multiLot").click(function(){
			var sourceInfo = $("input.radios:checked").val();
			if(!sourceInfo)
			{
				alert("Please Select source information first!");
				$("input.radios:eq(0)").focus();
			}
		});
		
		$("input.multiLot").keypress(function(e){
			if(e.which==13)
			{
				var index = $("input.multiLot").index(this);
				if(e.which==13)
				{
					$("input.multiLot:eq("+(index+1)+")").focus();
				}
				e.preventDefault();
			}
		});
		
		$("input.quantity").keypress(function(e){
			if(e.which==13)
			{
				var index = $("input.quantity").index(this);
				if(e.which==13)
				{
					$("input.quantity:eq("+(index+1)+")").focus();
				}
				e.preventDefault();
			}
		});
		
		$("input.radios").change(function(){
			$("input.multiLot").val('');
		});
		
		$("#insertCpart").submit(function(){
			$("#button").attr('disabled',true);
		});
	});
</script>
<script type="text/javascript" src="../Common Data/Libraries/Javascript/Tiny Box/tinybox.js"></script>
<link rel="stylesheet" href="../Common Data/Libraries/Javascript/Tiny Box/stylebox.css" />
