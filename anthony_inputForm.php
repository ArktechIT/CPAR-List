<?php
session_start();
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
//$path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
$javascriptLib = "../Common Data/Libraries/Javascript/";
$templates = "../Common Data/Libraries/Javascript/";
set_include_path($path);    
include('../Common Data/PHP Modules/mysqliConnection.php');
include('../Common Data/PHP Modules/anthony_retrieveText.php');
include('PHP Modules/gerald_functions.php');
ini_set("display_errors", "on");

	//~ if($_SESSION['idNumber']!='0346')
	//~ {
		//~ echo "Sorry we're under maintenance!";
		//~ exit(0);
    //~ }
$type = isset($_GET['type']) ? $_GET['type'] : '';
if($type == 'new')
{
    $lote = isset($_GET['lotNumber']) ? $_GET['lotNumber'] : '';
    $ngQuantity = isset($_GET['ngQuantity']) ? $_GET['ngQuantity'] : '';
    $originalQuantity = isset($_GET['originalQuantity']) ? $_GET['originalQuantity'] : '';
    $processName = isset($_GET['processName']) ? $_GET['processName'] : '';
    $employeeIdStart = isset($_GET['employeeIdStart']) ? $_GET['employeeIdStart'] : '';
    $newPTag = isset($_GET['newPTag']) ? $_GET['newPTag'] : '';
    // COMMENTED 2020-01-03
    /* if($ngQuantity > 0 AND $originalQuantity != $ngQuantity)
    {
        $sql = "SELECT processOrder FROM view_workschedule WHERE lotNumber = '".$lote."' AND processCode IN (368) AND status = 0 ORDER BY processOrder ASC LIMIT 1";
        $queryProcessOrder = $db->query($sql);
        if($queryProcessOrder AND $queryProcessOrder->num_rows > 0)
        {
            $resultProcessOrder = $queryProcessOrder->fetch_assoc();
            $processOrder = $resultProcessOrder['processOrder'];
        }
        // kwentong Chicha
        

        $newLote = partialLotNumber($lote,$ngQuantity,$processOrder,$employeeIdStart,1);

        if($newPTag!='')
        {
            echo $sql = "UPDATE ppic_lotlist SET productionTag = '".$newPTag."' WHERE lotNumber LIKE '".$newLote."'";
            $queryUpdateNewLot = $db->query($sql);
        }
    } */
}
?>

<style>
#text{
	border-radius: 5px;
}

#text1{
	width: 53.5%;
	border-radius: 5px;
	margin-left: 3%;
}

#text2{
	width: 53.5%;
	border-radius: 5px;
	margin-left: 11.5%;
}

#text3{
	width: 53.5%;
	border-radius: 5px;
}

#message{
	border-radius: 5px;
}

label, textarea{
  display:inline-block;
  vertical-align:middle;
 }

#cHide{
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
	width: 60%;
}

#rightContainer
{
	float: right;
	width: 25%;
	/* margin-right: 70px; */
	z-index:99999;
}

div.height_separator
{
	height:25px;
}

.subPartLotRed
{
	background-color:red!important;
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

<?php
$sql = $db->query("SELECT firstName, surName FROM hr_employee WHERE status = 1 ORDER BY surName ASC");
$sql1 = $db->query("SELECT departmentName FROM hr_department");
$sql2 = $db->query("SELECT firstName, surName FROM hr_employee WHERE status = 1 ORDER BY surName ASC");
$sql3 = $db->query("SELECT firstName, surName FROM hr_employee WHERE status = 1 ORDER BY surName ASC");
$sql4 = $db->query("SELECT firstName, surName FROM hr_employee WHERE status = 1 ORDER BY surName ASC");
$sql5 = $db->query("SELECT firstName, surName FROM hr_employee WHERE status = 1 ORDER BY surName ASC");
$sql6 = $db->query("SELECT firstName, surName FROM hr_employee WHERE status = 1 ORDER BY surName ASC");
$sql7 = $db->query("SELECT firstName, surName FROM hr_employee WHERE status = 1 ORDER BY surName ASC");
$sql8 = $db->query("SELECT firstName, surName FROM hr_employee WHERE status = 1 ORDER BY surName ASC");
$sql9 = $db->query("SELECT supplierAlias AS Supplier FROM purchasing_supplier UNION ALL SELECT subconAlias AS Supplier FROM purchasing_subcon ORDER BY Supplier ASC");
?>

<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>CPAR Input Form</title>
	<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../Common Data/Templates/api.css">
	<link rel="stylesheet" href="../Common Data/Libraries/Javascript/sweetalert2/sweetalert2.min.css">
	<script src="../Common Data/Libraries/Javascript/sweetalert2/sweetalert2.min.js"></script>
</head>
<body class='api-loading'>
<div id = 'container'>
	<div id = 'leftContainer'>
		<div align="left" style="border:2px solid;border-radius:25px;width:800px;padding: 10px">
			<table>
				<!-- Main Menu --> 
				<tr>
					<td bgcolor="LIGHTGRAY" height="40" width="100">
						<a href="gerald_cparList.php"><center><?php echo displayText('L1724');//Previous Page?></center></a>
					</td>
					<td width='500'>
						<h2 class="art-postheader"><center><?php echo displayText('L1271');?></center></h2>
					</td>
				</tr>
			</table>
			<div class="art-post-inner art-article">
				
				<div class="art-postcontent" style='height:77%;overflow-y:scroll;'>
				<p>
					<form action = 'anthony_insertCPAR.php' method = 'POST' id = 'insertCpart'>
					<?php 
					if(!in_array($_SESSION['idNumber'], array('0206', '0326', '0239', '0215')))
					{
					?>
						<label><?php echo displayText('L1302');//Assign?>:</label>
						<select name="assignEmployee" id="assignEmployee" value="" style = "border-radius:5px;background-color:#fdfd96;">
							<option></option>
							<?php
								$sql = "SELECT idNumber, CONCAT(firstName,' ',surName) AS pangalan FROM hr_employee WHERE idNumber != '".$_SESSION['idNumber']."' AND status = 1 AND departmentId != 17 ORDER BY firstName";
								$queryPangalan = $db->query($sql);
								if($queryPangalan->num_rows > 0)
								{
									while($resultPangalan = $queryPangalan->fetch_assoc())
									{
										$aydiNumber = $resultPangalan['idNumber'];
										$pangalan = $resultPangalan['pangalan'];
										echo "<option value='".$aydiNumber."'>".$pangalan."</option>";
									}
								}
							?>
						</select>
					<?php
					}
					?>
				
				
				<table border = '2'>
					<tr>
					<td width="200">
						<input type = "radio" name = "action" id = "radio" value = "Product Nonconformance" required><?php echo displayText('L1224');?><br>
						<input type = "radio" name = "action" id = "radio" value = "System Nonconformance" required><?php echo displayText('L1225');?>
						
					</td>
					<td width="300">
					<span><?php echo displayText('L1226');?>:</span>
					<input type="text" name="inputName" id="inputName" value=""  style = "border-radius:5px;width:70%;background-color:#fdfd96;" required /> 

					<br>

					<!-- Section: <input list = "listsection" name = "inputSection" id="inputName" style = "border-radius:5px; width:70%;" required/>
						<datalist id = 'listsection'>
						<?php
						$sql = "SELECT sectionName FROM ppic_section ORDER BY sectionName";
						$querySection = $db->query($sql);
						if($querySection AND $querySection->num_rows > 0)
						{
							while($sql10Result = $querySection->fetch_assoc())
							{
								echo "<option value = '".$sql10Result['sectionName']."'>".$sql10Result['sectionName']."</option>";
							}
						}
						?>
						</datalist> -->
						
						<?php echo displayText('L1121');?>: <select name = "inputSection" id="inputName" style = "border-radius:5px; width:70%;background-color:#fdfd96;" required>
							<option></option>
							<option value='IT'>IT</option>
							<option value='Engineering'>Engineering</option>
							<option value='Bending'>Bending</option>
							<option value='Blanking'>TPP</option>
							<option value='Laser'>Laser</option>
							<option value='Welding Assembly'>Welding Assembly</option>
							<option value='Benchwork'>Benchwork</option>
							<option value='Metal Finishing'>Metal Finishing</option>
							<option value='Powder-Type Painting'>Powder-Type Painting</option>
							<option value='Wet-Type Painting'>Wet-Type Painting</option>
							<option value='Warehouse'>Warehouse</option>
							<option value='QC'>QC</option>
							<option value='Purchasing'>Purchasing</option>
							<option value='Sales'>Sales</option>
							<option value='Subcon'>Subcon</option>
							<option value='Supplier'>Supplier</option>
							<option value='Planning'>Planning</option>
							<option value='Management'>Management</option>
							<option value='HR'>HR</option>
							<option value='Accounting'>Accounting</option>
							<option value='PCO'>PCO</option>
						</select>
						
						<br>
					</td>
					<td width="220">
						<?php echo displayText('L1227');?>:<br>	<input type = 'date' name = 'replyDueDate' style = "border-radius:5px;" required>
					</td>
					</tr>
				</table>
				<table border="2">
					<tr>
					<td width="800">
						<b><?php echo displayText('L355');?>:</b><br>
						<input type = "radio" name = "sourceInfo" id='sourceInfo' class = "radios" value = "CPAR-INT_Internal" required><?php echo displayText('L1228');?><font size = "1.5"> ( <?php echo displayText('L1229');?> / <?php echo displayText('L1230');?> / <?php echo displayText('L1231');?> )</font><br>
						<input type = "radio" name = "sourceInfo" class = "radios" value = "CPAR-SUP_Supplier" onclick="selectShow();" required><?php echo displayText('L367','utf8',0,0,1);?>&nbsp;<input list = "list" name = "inputSupplierSubCon" id="inputName" style = "border-radius:5px; width:50%;" required/>
							 <datalist id = 'list'>
								<?php
								echo "<option value = '-'>-na-</option>";
								while($sql9Result = $sql9->fetch_array())
								{                                                                   
									echo "<option value = '".$sql9Result['Supplier']."'>".$sql9Result['Supplier']."</option>";
								} 
								?>
							 </datalist><br>
						<input type = "radio" name = "sourceInfo" class = "radios" value = "CPAR-SUB_Subcon" required><?php echo displayText('L91','utf8',0,0,1);?><br>
						
<!--					Commented By Gerald 2018-06-04
-->
						<?php
						if($_GET['country']=="2")
						{
						?>							
						<input type = "radio" name = "sourceInfo" class = "radios" value = "CPAR-CUS_Customer Claim" onclick="hideShow();" required><?php echo displayText('L1232','utf8',0,0,1);?>&nbsp;&nbsp;&nbsp;&nbsp;<input type = 'text' name = 'customerHide' id = 'cHide' style = "display:none; width:20%;"><br>
						<?php
						}
						else if($_SESSION['idNumber']=='0346' OR $_SESSION['departmentId']==9)
						{
							?>
							<input type = "radio" name = "sourceInfo" class = "radios" value = "CPAR-CUS_Customer Claim" onclick="hideShow();" required checked><?php echo displayText('L1232');?>&nbsp;&nbsp;&nbsp;&nbsp;<input type = 'text' name = 'customerHide' id = 'cHide' style = "display:none; width:20%;"><br>
							<?php
						}
						
							if($_SESSION['idNumber']=='0346' OR $_SESSION['idNumber']=='0377' OR $_SESSION['idNumber']=='0239')
							{
								?>
								<input type = "radio" name = "sourceInfo" class = "radios" value = "CPAR-SYS_Internal Audit" required><?php echo displayText('L3458','utf8',0,0,1);?><br>
								<input type = "radio" name = "sourceInfo" class = "radios" value = "CPAR-SYS_External Audit" required><?php echo displayText('L4058','utf8',0,0,1);?><br>
								<input type = "radio" name = "sourceInfo" class = "radios" value = "CPAR-SYS_Others" required><?php echo displayText('L1104','utf8',0,0,1);?>
								<?php
							}
							else
							{
								?>
								<input type = "radio" name = "sourceInfo" class = "radios" value = "CPAR-SYS_System" id = "system" required><?php echo displayText('L1233','utf8',0,0,1);?>
								<?php
							}
						?>
					</td>
					</tr>
				</table>

				<table border = "2">
					<tr>
					<td width="230">
						<center><b><?php echo displayText('L1234');?>:</b><br></center>						
						<?php echo displayText('L1235');?>:<?php //<br><textarea name = "message1" rows = "7" cols = "30" id = "message" style = "width:100%" required></textarea><br>?>
							<select name = "message1" id = "message1" style = "border-radius:5px; height:40px;width:100%;background-color:#fdfd96;"
							>
								<option></option>
								<?php
									
									//~ $detailsArray = array ('N.G APPEARANCE-EXCESS PRIME','N.G APPEARANCE-ANODISE','N.G APPEARANCE-BRUSH FINISH'
									//~ ,'N.G APPEARANCE-DENT','N.G APPEARANCE-STAIN','INCOMPLETE PROCESS','LACKING QTY','N.G APPEARANCE-RUST','MATERIAL DEFECT',
									//~ 'DIMENSION PROBLEM','N.G DIMENSION-PERPENDICULARITY','N.G DIMENSION-TWISTED','DETACHED WELD','CRACK',
									//~ 'N.G APPEARANCE-MISCUT','MISSIPROCESS-CHAMFER','N.G APPEARANCE-STRIKE MARKS','WRONG PROCESS- ASSY','DIMENSION-THICK PAINT',
									//~ 'WRONG PRIME','WRONG PROCESS-HOLE CHAMFER','WRONG PROCESS','N.G APPEARANCE-TOOLMARK','INVERTED SF25',
									//~ 'FLAT SIZE (under development)','WRONG PROGRAM','MICRO JOINT','WRONG DRAWING','N.G APPEARANCE-PAINT','MISSING ITEM',
									//~ 'N.G APPEARANCE-OVERFILE','N.G APPEARANCE-RADIUS','MISPROCESS-WELDING','N.G APPEARANCE-GRINDING MARK','N.G APPEARANCE-WARP',
									//~ 'N.G APPEARANCE-DEFORMED','LASER SPARK','N.G APPEARANCE-HAIRLINE','N.G APPEARANCE-BURRS','N.G APPEARANCE-CHIPPED OFF','N.G APPEARANCE-LESS PRIME','N.G APPEARANCE-SCRATCH'
									//~ ,'WRONG BEND','N.G APPEARANCE-BENDING MARK','N.G APPEARANCE-WRINKLE','LOOSE THREAD','OFFSET HOLES','TIGHT THREAD','WELDING SPARK','RAW MATERIAL','WRONG ITEM');
									//~ sort($detailsArray);
									//~ 
									//~ foreach ($detailsArray as $key)
									//~ {
										//~ echo "<option>$key</option>";
									//~ }
									//~ 
									// Gerald 2018-03-24
									$sql = "SELECT nonConformance,listId FROM `qc_nonconformance` WHERE status = 0 ORDER BY listId ASC";
									$queryNonConformance = $db->query($sql);
									if($queryNonConformance AND $queryNonConformance->num_rows > 0)
									{
										while($resultNonConformance = $queryNonConformance->fetch_assoc())
										{
											$nonConformance = $resultNonConformance['nonConformance'];
											$listId = $resultNonConformance['listId'];
											echo "<option>".$nonConformance."</option>";
										}
									}
									// Gerald 2018-03-24
								?>
							</select>
							<?php echo displayText('L1330');//More Details?>
							<span id="nonconformancedetails">
							<select name = "cparMoreDetails" id="cparMoreDetails" style = "border-radius:5px; height:40px;width:100%;background-color:#fdfd96;" >
							<option></option>
							</select>
							</span>
							<!-- rhay -->
							<label>Details of Nonconformance</label>
							<textarea name="detailsOfNonconformance" style="width: 100%;height:65px;"></textarea>
					</td>
					<td width="180">
						<center><b><u><?php echo displayText('L1236');?>:</u></b><br></center>
						<div class="options">
							<input type = "checkbox" name = "dispo[]" value = "Sort/Re-inspect" required><?php echo displayText('L1237');?> / <?php echo displayText('L1238');?><br>
							<input type = "checkbox" name = "dispo[]" value = "Rework"required ><?php echo displayText('L1239');?><br>
							<input type = "checkbox" name = "dispo[]" id='replacementClass' value = "Scrap/Disposal/Replacement" required><font size = "1.5"><?php echo displayText('L1240');?></font><br>
							<input type = "checkbox" name = "dispo[]" value = "Return to Supplier/Subcon" required><?php echo displayText('L1241');?><br>
							<input type = "checkbox" name = "dispo[]" value = "Special Acceptance" required><?php echo displayText('L865');?><br>
							<input type = "checkbox" name = "dispo[]" value = "Others" id = "chkBox" onclick = "showHide();" required><?php echo displayText('L1104');?>
							<input type = "text" name = "hideText" id = "txtBox" style="display:none;">
						</div>					
					</td>
					<td width="305">
						<?php echo displayText('L1242');?>:<input type = "text" name = "prong" id = "text" style = "margin-left:5.3%;"><br>
						<?php echo displayText('L1243');?>:<input type="text" name="inputName1" id="inputName1" value="" style = "border-radius:5px;" placeholder = " example (Dela Cruz, J.)"><br> 
						
						<!-- <label for='inputName1'>Concerned Person:</label>
						<select name="inputName1" id="inputName1" style = "width:13vw;border-radius:5px;background-color:#fdfd96;">
							<option></option>
							<?php
								$sql = "SELECT idNumber, CONCAT(firstName,' ',surName) AS concernedPerson FROM hr_employee WHERE departmentId != 17 AND status = 1 ORDER BY firstName";
								$queryPerson = $db->query($sql);
								if($queryPerson->num_rows > 0)
								{
									while($resultPerson = $queryPerson->fetch_assoc())
									{
										$idNumber = $resultPerson['idNumber'];
										$concernedPerson = $resultPerson['concernedPerson'];
										?>
										<option value='<?php echo $idNumber; ?>'><?php echo $concernedPerson; ?></option>
										<?php
									}
								}
							?>
						</select> 
						<br>
						-->
						
						<?php echo displayText('L1244');?>:<input type = "text" name = "detectProcess" id = "text"><br>
						<?php echo displayText('L1245');?>:<input type="text" name="inputName2" id="inputName2" value="" style = "border-radius:5px; margin-left:3%" placeholder = " example (Dela Cruz, J.)"><br>
						<?php echo displayText('L1246');?>:<input type = "date" name = "dateDetect" id = "text" style = "margin-left:9%;"><br>
					 	<?php echo displayText('L1247');?>:<input type = "text" name = "itemPrice" min = "0" style = "border-radius:5px; margin-left:16.8%" value="$"><br>    	
						<input type = "radio" name = "choices" id = "radios" value = "Need Corrective Action" required><?php echo displayText('L1248');?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type = "radio" name = "choices" id = "radios" value = "Information Only" required><?php echo displayText('L1249');?>
					</td>
					</tr>
					
					<tr>
					<td>
						
						<b><?php echo displayText('L1250','utf8',0,0,1);?>:</b><br>
						<textarea name = "interim" rows = "10" cols = "30" id = "message" style = "width:100%"></textarea>
					
					</td>
					<td>	
						<b><?php echo displayText('L1251','utf8',0,0,1);?>:</b><br>
						<div class="option4m">
							<input type = "checkbox" name = "4m[]" value = "Man" required><?php echo displayText('L1252');?><br>
							<input type = "checkbox" name = "4m[]" value = "Machine" required><?php echo displayText('L229');?><br>
							<input type = "checkbox" name = "4m[]" value = "Method and Measurement"required><?php echo displayText('L1253');?><br>
							<input type = "checkbox" name = "4m[]" value = "Material"required><?php echo displayText('L174');?>
						</div>
					</td>
					<td>
						<center><b><u><?php echo displayText('L1254');?>:</u></b></center>
						<?php echo displayText('L462');?>:<input type = "date" name = "retdate" style = "margin-left:27%;"><br>
						<?php echo displayText('L1255');?>:<br>
						<?php echo displayText('L57');?>:<br>
						<?php echo displayText('L1257');?>:<input type = "date" name = "prodnsched" style = "margin-left:14.5%;"><br>
						<?php echo displayText('L1258');?>:<input type = "date" name = "delivsub"><br>
						<?php echo displayText('L1259');?>:<input type = "date" name = "delivcust" style = "margin-left:10%;"><br>
						<?php echo displayText('L241');?>:<input type="text" name="inputName3" id="inputName3" value="" style = "margin-left:31.5%; border-radius:5px; width:47%;" /><br>
					</td>
					</tr>
				</table>

				<table border = "2"> 
					<tr><td colspan="2"><b><?php echo displayText('L1261','utf8',0,0,1);?>:<b></td></tr>
					<tr>
					<td width="418">
						<?php echo displayText('L1262','utf8',0,0,1);?>:<br>
						<textarea name = "pcause" rows = "3" cols = "30" id = "message" style = "width:100%" required></textarea>
					</td>
					<td width="500">
						<?php echo displayText('L1263','utf8',0,0,1);?>:<br>
						<textarea name = "focause" rows = "3" cols = "30" id = "message" style = "width:100%"></textarea>
					</td>  
					</tr>
				</table>

				<table border = "2">
					<tr><td colspan="2"><b><?php echo displayText('L1065','utf8',0,0,1);?>:<b></td></tr>

					<tr>
					<td width="330">
						<?php echo displayText('L59','utf8',0,0,1);?>:<br>
						<textarea name = "process" rows = "3" cols = "30" id = "message" style = "width:100%;"></textarea><br>
				<table>
					<tr>
					<td><?php echo displayText('L1264');?>:<input type = "date" name = "impdate1" id = "text"></td>
					<td><?php echo displayText('L241');?>:<br><input type="text" name="inputName4" id="inputName4" value="" style = "border-radius:5px; width:100%;" />
					</td>
					</tr>
				</table>
					</td>
					<td width="390">
						<?php echo displayText('L1273','utf8',0,0,1);?>:<br>
						<textarea name = "fo" rows = "3" cols = "30" id = "message" style = "width:100%;"></textarea>
				<table>
					<tr>
					<td width="200"><?php echo displayText('L1264');?>:<input type = "date" name = "impdate2" id = "text"></td>
					<td width="200"><?php echo displayText('L241');?>:<input type="text" name="inputName5" id="inputName5" value="" style = "border-radius:5px; width:100%;" />
					</td>
					</tr>
				</table>
					</td>  
					</tr>
				</table>
				<table border = "2" width="728">
<!--
					<tr>
					<td width="328">
						<b><?php echo displayText('L1265','utf8',0,0,1);?>:</b><br>
						<textarea name = "prevaction" rows = "3" cols = "30" id = "message" style = "width:100%"></textarea>
					</td>
					<td><br>
					<?php echo displayText('L241');?>:<br><input type="text" name="inputName6" id="inputName6" value="" style = "border-radius:5px; width:100%" />
					</td>
					<td><br>
						<?php echo displayText('L1264');?>:<br><input type = "date" name = "impdate3" style = "border-radius:5px;">
					</td>
					</tr>
-->

					<tr>
					<td>
						<b><?php echo displayText('L1266');?></b><i> (<?php echo displayText('L1267');?>)</i><br>
						<textarea name = "verif" rows = "5" cols = "30" id = "message" style = "width:100%;"></textarea>
					</td>
					<td>
					<?php echo displayText('L1268');?>:<br><input type="text" name="inputName7" id="inputName7" value="" style = "border-radius:5px; width:100%" />
				<table>
					<tr>
					<td width="194">
						<?php echo displayText('L292');?>:<input type = "date" name = "verifdate" style = "border-radius:5px; width:100%">
					</td>
					</tr>
				</table>
					</td>
					<td>
						<center><?php echo displayText('L172');?></center><br>
						<input type = "radio" name = "status" value = "Verified"> <?php echo displayText(displayText('L1269','utf8',0,0,1),'utf8',0,1,1);?><br><br>
						<input type = "radio" name = "status" value = "Issued" checked> <?php echo displayText(displayText('L1270','utf8',0,0,1),'utf8',0,1,1);?>
					</td>
					</tr>
				</table>
					<p></p>
					<center><input type ="submit" name = "submit" value = "<?php echo displayText('L1052','utf8',0,0,1);?>" id = "buton" class="art-button"></center>			
				</div>
				</span>
				</form>
				</p>
			</div>
		</div>
	</div>
	<?php
    //$assempblyNG = $multiLot = $quantity = $lotProcess = Array();
    // echo $type;
    if($type == "new")
    {
	    ?>
	    <div id = 'rightContainer'>
				<!---- CHICHA ---->
				<!-- hidden text boxes for lot progress QC Verification NG process -->
				<?php
				echo "<label><input type='checkbox' name='returnFromSubconFlag' value='1' form='insertCpart'>Return from subcon (Not processed by subcon)</label>";
				
				echo "<div class = 'height_separator'>";
					echo "<input type='checkbox' name='assempblyNG[]' value='' class='assemblyNGClass' style='display:none;' title='Checked to include all subparts' form = 'insertCpart'>";
					echo "<input type = 'hidden' value='".$lote."' id='multiLotn' name = 'multiLot[]' class = 'multiLot' form = 'insertCpart' style = 'width:90px;' placeholder = 'Lot Number'>&emsp;";
					echo "<input type = 'hidden'  value=".$ngQuantity." name = 'quantity[]' class = 'quantity' form = 'insertCpart' style = 'width:40px;' placeholder = 'Qty'>&emsp;";
					
					echo "<input type='hidden' value='".$processName."' name = 'lotProcess[]' class = 'lotProcess' form = 'insertCpart' style = 'width:30%;'>";
					echo "<input type='hidden' value='".$type."' name = 'typeData' form = 'insertCpart' style = 'width:30%;'>";
					echo "<input type='hidden' value='".$employeeIdStart."' name = 'employeeIdStart' form = 'insertCpart' style = 'width:30%;'>";
					echo "<input type='hidden' value='".$newPTag."' name = 'newPTag' form = 'insertCpart' style = 'width:30%;'>";
					echo "<br>";
				echo "</div>";
				?>	
				<div class="sweg">
						
				</div>
				<img hidden id='add1' src='../../Common Data/Templates/buttons/addIcon.png' width='25' height='25'>
		</div>
	    <?php
    }
    else
    {
		?>
		<div id = 'rightContainer'>
			<div align="center" style="border:2px solid;border-radius:25px;width:auto;padding: 10px">
				
				<?php
				if($_GET['country']==1)
				{
					echo "<label><input type='checkbox' id='subconNoPo'>For Subcon but no need for PO</label>";
					echo "<select name='subconNoPo' form = 'insertCpart' style='display:none;'><option value=''>Select Subcon</option>";
					$sql = "SELECT subconId, subconAlias FROM purchasing_subcon WHERE status = 0 ORDER BY subconAlias";
					$querySubcon = $db->query($sql);
					if($querySubcon AND $querySubcon->num_rows > 0)
					{
						while($resultSubcon = $querySubcon->fetch_assoc())
						{
							$subconId = $resultSubcon['subconId'];
							$subconAlias = $resultSubcon['subconAlias'];
							
							echo "<option value='".$subconId."'>".$subconAlias."</option>";
						}
					}
					echo "</select>";
					//~ if($_SESSION['idNumber']=='0346')
					//~ {
						echo "<label><input type='checkbox' name='returnFromSubconFlag' value='1' form='insertCpart'>Return from subcon (Not processed by subcon)</label>";
					//~ }
				}
				echo "<div class = 'height_separator'>";
					echo "<input type='checkbox' name='assempblyNG[]' value='' class='assemblyNGClass' style='display:none;' title='Checked to include all subparts' form = 'insertCpart'>";
					echo "<input type = 'text' id='multiLotn' name = 'multiLot[]' class = 'multiLot' form = 'insertCpart' style = 'width:90px;' placeholder = 'Lot Number'>&emsp;";
					if($_GET['country']==1)
					{
						//rhay add PTAG INPUT TYPE
						echo "<input type = 'text' id='multiPtag' name='multiPtag[]' class='multiPtag' form='insertCpart'style = 'width:90px;' placeholder = 'PTAG'>&emsp;";
						//end rhay add PTAG INPUT TYPE
					}
					echo "<input type = 'number' name = 'quantity[]' class = 'quantity' form = 'insertCpart' style = 'width:40px;' placeholder = 'Qty'>&emsp;";
					
					echo "<select name = 'lotProcess[]' class = 'lotProcess' form = 'insertCpart' style = 'width:30%;'>";
						echo "<option>-Process-</option>";
					echo "</select><br>";
				echo "</div>";
				?>	
				<div class="sweg">
						
				</div>
				<img id='add1' src='../../Common Data/Templates/buttons/addIcon.png' width='25' height='25'>
			</div>
		</div>
	    <?php 
    }
    ?>
</div>
</form>
</body>
<script src="../Common Data/Libraries/Javascript/jQuery 3.1.1/jquery-3.1.1.js"></script>
<script src="../Common Data/Libraries/Javascript/jQuery 3.1.1/jquery-ui.js"></script>
<script src="../Common Data/Libraries/Javascript/jQuery 3.1.1/bootstrap.min.js"></script>
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
	function showHideradioButton(){
		var radioButton = document.getElementById("radioButton");
		var txtBox = document.getElementById("txtBox");

		if (radioButton.checked){
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
		$('input[type=text] , input[type=date] , input[type=number] , textarea').css('background-color', '#fdfd96');
		$('input[list]').css('background-color', '#ffd1dc');
		
		$("#subconNoPo").change(function(){
			if ($(this).is(":checked") )
			{
				$("select[name='subconNoPo']").show().attr('required',true).val('');
			}
			else
			{
				$("select[name='subconNoPo']").hide().attr('required',false).val('');
			}
		});
				
		$('input[name=sourceInfo]').click(function(){
			var sectionVal = $('select[name=inputSection]').val();
			var sourceInfoVal = $(this).val();
			if(sourceInfoVal=='CPAR-INT_Internal')
			{
				if(sectionVal!='Subcon' || sectionVal!='Supplier')
				{
					//~ if(sessionIdNumber!='0206' || sessionIdNumber!='0326')
					//~ {
						$('#assignEmployee').attr('required', true);
					//~ }
				}
			}
			else
			{				
				$('#assignEmployee').attr('required', false);
			}
		});

		$('#message1').change(function(){
			var nonConformance = $(this).val();
			if(nonConformance!='')
			{
				$.ajax({
					url:'pipz_cparNonconformanceDetailsAjax.php',
					type:'POST',
					data:{nonConformance:nonConformance},
					success:function(data){
						$('#nonconformancedetails').html(data);
					}
				});
			}
		});
		
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
				$('#inputName1').val('').attr('readonly', false).css({'cursor':''});
			}
		});
		
        $(".multiLot").change(function(){
			var sourceInfo = $("input.radios:checked").val();
			var thisObj = $(this);
			var index = $(".multiLot").index(this);
			var lotNumber = $(this).val();
			
			var mainAssyFlag = ($(".assemblyNGClass:eq("+index+")").is(':checked')) ? 1 : 0;
			
			var lotNumbers;
			if(mainAssyFlag==1)
			{
				lotNumbers = $(".multiLot").map(function(){
					if($(this).val().trim()!='')
					{
						return $(this).val();
					}
				}).get();
			}
			
			$.ajax({
				url		: "anthony_cparInputFormAjax.php",
				type	: "post",
				data	: {
							lotNumber:lotNumber,
							type:'processName',
							sourceInfo:sourceInfo,
							mainAssyFlag:mainAssyFlag,
							lotNumbers:lotNumbers
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
						
						$(".assemblyNGClass:eq("+index+")").val(lotNumber);
						
						if(array[2]=='notProcessedBySubconFlag')
						{
							if(!($("input[name=returnFromSubconFlag]").is(':checked')))
							{
								swal({
									title: "Return from subcon (Not processed by subcon)",
									//~ text: "Do you want to remove <?php echo $lotNumber;?> in the list?",
									//~ html:"<?php echo $htmlMessage;?>",
									type: 'warning',
									showCancelButton: true,
									confirmButtonColor: '#3085d6',
									cancelButtonColor: '#d33',
									confirmButtonText: 'Yes',
									cancelButtonText: 'No',
									allowOutsideClick: false
								}).then(function () {
									$("input[name=returnFromSubconFlag]").prop('checked',true);
								}, function (dismiss) {
									if (dismiss === 'cancel') {
										$("input[name=returnFromSubconFlag]").prop('checked',false);
									}
								})
							}
						}
					}
					else
					{
						var array = data.split("|");
						
						//~ alert(data.replace("|", ""));
						alert(array[1]);
						$(thisObj).val("").focus();
						
						$(".assemblyNGClass:eq("+index+")").val("");
						
						if($(thisObj).hasClass('subPartLotRed'))
						{
							$(thisObj).removeClass('subPartLotRed');
						}
						
						if(array[2]!='')
						{
							var subPartLotArray = array[2].split(",");
							
							$(".multiLot").each(function(){
								var lotVal = $(this).val();
								if($.inArray(lotVal,subPartLotArray) > -1)
								{
									$(this).addClass('subPartLotRed');
								}
							});
						}
					}
				}
			});
		});

        // rhay ptag error checking
        $(".multiPtag").change(function(){
        	var index = $(".multiPtag").index(this);
        	var lotNumber = $(".multiLot:eq("+index+")").val();
        	var ptag = $(".multiPtag:eq("+index+")").val();
        	$.ajax({
        		url 	: 	"rhay_ptagErrorCheckingAJAX.php",
        		method 	: 	"POST",
        		data 	: 	{ptag:ptag,lotNumber:lotNumber},
        		success : 	function(data)
        		{
        			if(data == "ok")
        			{

        			}
        			else
        			{
        				alert(data);
        				$(this).val("");
        				$(this).focuse;
        			}
        		}
        	})
        })	


		$(".radios").change(function(){
			if($(this).val() == 'CPAR-SYS_System' || $(this).val() == 'CPAR-SYS_Internal Audit' || $(this).val() == 'CPAR-SYS_External Audit' || $(this).val() == 'CPAR-SYS_Others')
			{
				$(".multiLot").prop("required", false);
				$("#message1").prop("required", false);
				$("#cparMoreDetails").prop("required", false);
				$("#message1").val("--");
				$("#cparMoreDetails").val("--");
			}
			else
			{
				$(".multiLot").prop("required", true);
				$("#message1").prop("required", true);
				$("#cparMoreDetails").prop("required", true);
				
				$(".multiLot").change(function(){
					var sourceInfo = $("input.radios:checked").val();
					var thisObj = $(this);
					var index = $(".multiLot").index(this);
					var lotNumber = $(this).val();
					
					var mainAssyFlag = ($(".assemblyNGClass:eq("+index+")").is(':checked')) ? 1 : 0;
					
					var lotNumbers;
					if(mainAssyFlag==1)
					{
						lotNumbers = $(".multiLot").map(function(){
							if($(this).val().trim()!='')
							{
								return $(this).val();
							}
						}).get();
					}
					
					$.ajax({
						url		: "anthony_cparInputFormAjax.php",
						type	: "post",
						data	: {
									lotNumber:lotNumber,
									type:'processName',
									sourceInfo:sourceInfo,
									mainAssyFlag:mainAssyFlag,
									lotNumbers:lotNumbers
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
								
								$(".assemblyNGClass:eq("+index+")").val(lotNumber);
								
								if(array[2]=='notProcessedBySubconFlag')
								{
									if(!($("input[name=returnFromSubconFlag]").is(':checked')))
									{
										swal({
											title: "Return from subcon (Not processed by subcon)",
											//~ text: "Do you want to remove <?php echo $lotNumber;?> in the list?",
											//~ html:"<?php echo $htmlMessage;?>",
											type: 'warning',
											showCancelButton: true,
											confirmButtonColor: '#3085d6',
											cancelButtonColor: '#d33',
											confirmButtonText: 'Yes',
											cancelButtonText: 'No',
											allowOutsideClick: false
										}).then(function () {
											$("input[name=returnFromSubconFlag]").prop('checked',true);
										}, function (dismiss) {
											if (dismiss === 'cancel') {
												$("input[name=returnFromSubconFlag]").prop('checked',false);
											}
										})
									}
								}
							}
							else
							{
								var array = data.split("|");
								
								//~ alert(data.replace("|", ""));
								alert(array[1]);
								$(thisObj).val("").focus();
								
								$(".assemblyNGClass:eq("+index+")").val("");
								
								if($(thisObj).hasClass('subPartLotRed'))
								{
									$(thisObj).removeClass('subPartLotRed');
								}
								
								if(array[2]!='')
								{
									var subPartLotArray = array[2].split(",");
									
									$(".multiLot").each(function(){
										var lotVal = $(this).val();
										if($.inArray(lotVal,subPartLotArray) > -1)
										{
											$(this).addClass('subPartLotRed');
										}
									});
								}
							}
						}
					});
				});
				
				<?php
				    if($type == "new")
					{
						?>
						var sourceInfo = $("input.radios:checked").val();
						var index = 0;
						var thisObj = $(".multiLot:eq("+index+")");
						var lotNumber = '<?php echo $lote;?>';
						
						var mainAssyFlag = ($(".assemblyNGClass:eq("+index+")").is(':checked')) ? 1 : 0;
						
						var lotNumbers;
						if(mainAssyFlag==1)
						{
							lotNumbers = $(".multiLot").map(function(){
								if($(this).val().trim()!='')
								{
									return $(this).val();
								}
							}).get();
						}
						
						$.ajax({
							url		: "anthony_cparInputFormAjax.php",
							type	: "post",
							data	: {
										lotNumber:lotNumber,
										type:'processName',
										sourceInfo:sourceInfo,
										mainAssyFlag:mainAssyFlag,
										lotNumbers:lotNumbers
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
									
									$(".assemblyNGClass:eq("+index+")").val(lotNumber);
									
									if(array[2]=='notProcessedBySubconFlag')
									{
										if(!($("input[name=returnFromSubconFlag]").is(':checked')))
										{
											swal({
												title: "Return from subcon (Not processed by subcon)",
												//~ text: "Do you want to remove <?php echo $lotNumber;?> in the list?",
												//~ html:"<?php echo $htmlMessage;?>",
												type: 'warning',
												showCancelButton: true,
												confirmButtonColor: '#3085d6',
												cancelButtonColor: '#d33',
												confirmButtonText: 'Yes',
												cancelButtonText: 'No',
												allowOutsideClick: false
											}).then(function () {
												$("input[name=returnFromSubconFlag]").prop('checked',true);
											}, function (dismiss) {
												if (dismiss === 'cancel') {
													$("input[name=returnFromSubconFlag]").prop('checked',false);
												}
											})
										}
									}
								}
								else
								{
									var array = data.split("|");
									
									//~ alert(data.replace("|", ""));
									alert(array[1]);
									$(thisObj).val("").focus();
									
									$(".assemblyNGClass:eq("+index+")").val("");
									
									if($(thisObj).hasClass('subPartLotRed'))
									{
										$(thisObj).removeClass('subPartLotRed');
									}
									
									if(array[2]!='')
									{
										var subPartLotArray = array[2].split(",");
										
										$(".multiLot").each(function(){
											var lotVal = $(this).val();
											if($.inArray(lotVal,subPartLotArray) > -1)
											{
												$(this).addClass('subPartLotRed');
											}
										});
									}
								}
							}
						});
						<?php
					}
				?>
			}
		});	
		
		$("#multiLotn").click(function(){
			var sourceInfo = $("input.radios:checked").val();
			if(!sourceInfo)
			{
				alert("Please Select source information first!");
				$("input.radios:eq(0)").focus();
			}

			return false;
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

		var typeData =  "<?php echo $type; ?>";
        if(typeData != "new")
        {
            $("input.radios").change(function(){
                $("input.multiLot").val('');
            });
        }
		
		$("#insertCpart").submit(function(){
			var response = confirm("Are you sure you want to proceed?");
			if(response)
			{
				$("#button").attr('disabled',true);
			}
			else
			{
				return false;
			}
		});
		
		<?php
			if($_SESSION['idNumber']=='0346')
			{
				?>
				$("#replacementClass").click(function(){
					if($(this).is(':checked'))
					{
						$("input.assemblyNGClass").show().attr('checked',false);
					}
					else
					{
						$("input.assemblyNGClass").hide();
					}
				});
				
				$("input.assemblyNGClass").click(function(){
					if($(this).is(':checked'))
					{					
						var index = $(".assemblyNGClass").index(this);
						
						$(".multiLot:eq("+index+")").val("");
						$(".quantity:eq("+index+")").val("");
						$(".lotProcess:eq("+index+")").html("<option>-Process-</option>");
					}
				});
				<?php
			}
		?>
			
		$('body').removeClass('api-loading');
		$(window).bind('beforeunload',function(){
			$('body').addClass('api-loading');
		});		
		
		<?php
			if($_SESSION['departmentId']==9 OR $_SESSION['idNumber']=='0346')
			{
				?>
				$("#chkBox").click();
				<?php
			}
		?>
	});
</script>
<script type="text/javascript">
$(document).ready(function(){
	$("#add1").click(function(){
		<?php
			if($_GET['country']==1)
			{
				?>
				var addRowValue = "<div class = 'height_separator'><input type='checkbox' name='assempblyNG[]' value='' class='assemblyNGClass' style='display:none;' title='Checked to include all subparts' form = 'insertCpart'><input type = 'text' name = 'multiLot[]' class = 'multiLot' form = 'insertCpart' style = 'width:90px;' placeholder = 'Lot Number'>&emsp;<input type = 'text' id='multiPtag' name='multiPtag[]' class='multiPtag' form='insertCpart'style = 'width:90px;' placeholder = 'PTAG'>&emsp;<input type = 'number' name = 'quantity[]' class = 'quantity' form = 'insertCpart' style = 'width:40px;' placeholder = 'Qty'>&emsp;<select name = 'lotProcess[]' class = 'lotProcess' form = 'insertCpart' style = 'width:30%;'><option>-Process-</option></select><br></div>";
				<?php
			}
			else
			{
				?>
				var addRowValue = "<div class = 'height_separator'><input type='checkbox' name='assempblyNG[]' value='' class='assemblyNGClass' style='display:none;' title='Checked to include all subparts' form = 'insertCpart'><input type = 'text' name = 'multiLot[]' class = 'multiLot' form = 'insertCpart' style = 'width:90px;' placeholder = 'Lot Number'>&emsp;<input type = 'number' name = 'quantity[]' class = 'quantity' form = 'insertCpart' style = 'width:40px;' placeholder = 'Qty'>&emsp;<select name = 'lotProcess[]' class = 'lotProcess' form = 'insertCpart' style = 'width:30%;'><option>-Process-</option></select><br></div>";
				<?php
			}
		?>
	        $("div .sweg").append(addRowValue);
	        $('input[type=text] , input[type=date] , input[type=number] , textarea').css('background-color', '#fdfd96');
		$('input[list]').css('background-color', '#ffd1dc');
		$(".multiLot").click(function(){
			var index = $(this).parent().index();
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
		
		$(".multiLot").change(function(){
			var sourceInfo = $("input.radios:checked").val();
			var thisObj = $(this);
			var index = $(".multiLot").index(this);
			var lotNumber = $(this).val();
			
			var mainAssyFlag = ($(".assemblyNGClass:eq("+index+")").is(':checked')) ? 1 : 0;
			
			var lotNumbers;
			if(mainAssyFlag==1)
			{
				lotNumbers = $(".multiLot").map(function(){
					if($(this).val().trim()!='')
					{
						return $(this).val();
					}
				}).get();
			}
			
			$.ajax({
				url		: "anthony_cparInputFormAjax.php",
				type	: "post",
				data	: {
							lotNumber:lotNumber,
							type:'processName',
							sourceInfo:sourceInfo,
							mainAssyFlag:mainAssyFlag,
							lotNumbers:lotNumbers
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
						
						$(".assemblyNGClass:eq("+index+")").val(lotNumber);
						
						if(array[2]=='notProcessedBySubconFlag')
						{
							if(!($("input[name=returnFromSubconFlag]").is(':checked')))
							{
								swal({
									title: "Return from subcon (Not processed by subcon)",
									//~ text: "Do you want to remove <?php echo $lotNumber;?> in the list?",
									//~ html:"<?php echo $htmlMessage;?>",
									type: 'warning',
									showCancelButton: true,
									confirmButtonColor: '#3085d6',
									cancelButtonColor: '#d33',
									confirmButtonText: 'Yes',
									cancelButtonText: 'No',
									allowOutsideClick: false
								}).then(function () {
									$("input[name=returnFromSubconFlag]").prop('checked',true);
								}, function (dismiss) {
									if (dismiss === 'cancel') {
										$("input[name=returnFromSubconFlag]").prop('checked',false);
									}
								})
							}
						}
					}
					else
					{
						var array = data.split("|");
						
						//~ alert(data.replace("|", ""));
						alert(array[1]);
						$(thisObj).val("").focus();
						
						$(".assemblyNGClass:eq("+index+")").val("");
						
						if($(thisObj).hasClass('subPartLotRed'))
						{
							$(thisObj).removeClass('subPartLotRed');
						}
						
						if(array[2]!='')
						{
							var subPartLotArray = array[2].split(",");
							
							$(".multiLot").each(function(){
								var lotVal = $(this).val();
								if($.inArray(lotVal,subPartLotArray) > -1)
								{
									$(this).addClass('subPartLotRed');
								}
							});
						}
					}
				}
			});
		});	
             // rhay ptag error checking
        $(".multiPtag").change(function(){
        	var index = $(".multiPtag").index(this);
        	var lotNumber = $(".multiLot:eq("+index+")").val();
        	var ptag = $(".multiPtag:eq("+index+")").val();
        	if(ptag != "")
        	{
        		$.ajax({
	        		url 	: 	"rhay_ptagErrorCheckingAJAX.php",
	        		method 	: 	"POST",
	        		data 	: 	{ptag:ptag,lotNumber:lotNumber},
	        		success : 	function(data)
	        		{
	        			if(data == "ok")
	        			{

	        			}
	        			else
	        			{
	        				alert(data);
	        				$(this).val("");
	        				$(this).focus();
	        			}
	        		}
	        	})
        	}
        	else
        	{
        		alert("PTAG CANNOT BE BLANK!");
        	}
        })	
	});
		$(function(){
		var requiredCheckboxes = $('.options :checkbox[required]');
		requiredCheckboxes.change(function(){		
        if(requiredCheckboxes.is(':checked')) {
            requiredCheckboxes.removeAttr('required');
        } else {
           requiredCheckboxes.attr('required', 'required');
        }
    });
	});
		$(function(){
		var requiredCheckboxes1 = $('.option4m :checkbox[required]');
		requiredCheckboxes1.change(function(){		
        if(requiredCheckboxes1.is(':checked')) {
            requiredCheckboxes1.removeAttr('required');
        } else {
           requiredCheckboxes1.attr('required', 'required');
        }
    });
	});
});
</script>

