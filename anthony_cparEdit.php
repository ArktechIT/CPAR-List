<?php
	session_start();
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
  $path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
	set_include_path($path);	
	include('PHP Modules/mysqliConnection.php');
	ini_set("display_errors", "on");
	include('classes/fpdf.php');
	include('PHP Modules/gerald_functions.php');
	include('PHP Modules/anthony_retrieveText.php');
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
                            'anthony_cparEdit.php?source=landholding',
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
<title>Edit CPAR</title>

<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="../../Common Data/anthony.css" type="text/css" media="screen" />

<?php //include ('htmlhead.php');?>
</head>
<?php //include('bodytop.php'); ?>
<table>
		<!-- Main Menu --> 
	<tr>
		<td bgcolor="LIGHTGRAY" height="40" width="100">
			<a href="../6-4 CPAR List/gerald_cparList.php"><center><?php echo displayText('L1724');//Previous Page?></center></a>
		</td>
	</tr>
</table>
<center>	<div align="left" style="border:2px solid;border-radius:25px;width:730px;padding: 10px">
	
<div class="art-post-inner art-article">
    <h2 class="art-postheader"><center><?php echo displayText('L1333');//Edit CPAR?></center></h2>
    <center><label style = "color: <?php if(isset($_GET['msg']) AND $_GET['msg'] == '1'){ echo 'green'; }else{ echo 'red'; } ?>;">
		<?php 
		if(isset($_GET['msg']) AND $_GET['msg'] == '0')
		{ 
			echo "File was not uploaded due to an error";
		}
		else if(isset($_GET['msg']) AND $_GET['msg'] == '1')
		{
			echo "File has been uploaded";
		}
		else if(isset($_GET['msg']) AND $_GET['msg'] == '2')
		{
			echo "There was an error uploading your file";
		}
		?>
	</label></center>
    <div class="art-postcontent">
    <p>
<?php
$sql = $db->query("SELECT firstName, surName FROM hr_employee WHERE status = 1");
$sql1 = $db->query("SELECT departmentName FROM hr_department");
$sql2 = $db->query("SELECT firstName, surName FROM hr_employee WHERE status = 1");
$sql3 = $db->query("SELECT firstName, surName FROM hr_employee WHERE status = 1");
$sql4 = $db->query("SELECT firstName, surName FROM hr_employee WHERE status = 1");
$sql5 = $db->query("SELECT firstName, surName FROM hr_employee WHERE status = 1");
$sql6 = $db->query("SELECT firstName, surName FROM hr_employee WHERE status = 1");
$sql7 = $db->query("SELECT firstName, surName FROM hr_employee WHERE status = 1");
$sql8 = $db->query("SELECT firstName, surName FROM hr_employee WHERE status = 1");
$sql9 = $db->query("SELECT supplierAlias AS Supplier FROM purchasing_supplier UNION ALL SELECT subconAlias AS Supplier FROM purchasing_subcon");
$sql9a = $db->query("SELECT sectionName FROM ppic_section");
//$sql10 = $db->query("SELECT * FROM qc_cpar WHERE cparId = '".$_GET['cparId']."'");
$sql10 = $db->query("SELECT * FROM qc_cpar WHERE listId = '".$_GET['cparId']."'");
$result10 = $sql10->fetch_array();
$analysis = explode(',',$result10['cparAnalysis']);
$dispo = explode(',',$result10['cparDisposition']);

$location = "../../Document Management System/CPAR Folder/".$result10['cparId'].".pdf";
if(file_exists($location))
{
	$loc = "Open";
}
else
{
	$loc = "Upload";
}

$required = 'required';
if(strstr($result10['cparId'],'CPAR-SYS')!==FALSE)
{
	$required = '';
}

?>
<form action = 'anthony_uploadFile.php?cparId=<?php echo $_GET['cparId']; ?>' method = 'POST' enctype = 'multipart/form-data' id = 'upload'></form>
<input type="hidden" name="cparId" value="<?php echo $result10['cparId']; ?>" form = 'upload'>
<form action = "anthony_updateSQL.php?cparId=<?php echo $_GET['cparId']; ?>" method = "POST" id = 'formId'>
<?php echo displayText('L1336');//CPAR?>:	<input type = 'text' name = 'cparNo' style = "border-radius:5px;" value = "<?php echo $result10['cparId']; ?>" readonly>
	<table border='1'>
		<tr>
			<th><?php echo displayText('L45');?></th>
			<th></th>
		</tr>
		<?php
			$sql = "SELECT lotNumber FROM qc_cparlotnumber WHERE cparId LIKE '".$result10['cparId']."' AND status != 2";
			$queryCparLotNumber = $db->query($sql);
			if($queryCparLotNumber AND $queryCparLotNumber->num_rows > 0)
			{
				while($resultCparLotNumber = $queryCparLotNumber->fetch_assoc())
				{
					$lotNumber = $resultCparLotNumber['lotNumber'];
					
					echo "
						<tr>
							<td>".$lotNumber."</td>
							<td><input type = 'file' name = 'pdfFile".$lotNumber."' required form = 'upload'></td>
						</tr>
					";
				}
			}
		?>
		<tr>
			<th colspan='2'><input type='submit' value='<?php echo displayText('B1');//Submit?>'name='UPLOAD' form='upload' class = 'anthony_submit'></th>
		</tr>
	</table>
<table border = '2'>
	<tr>
	<td width="200">
		<input type = "radio" name = "action" id = "radio" value = "Product Nonconformance" required <?php if($result10['cparType']=='Product Nonconformance'){ echo 'checked';} ?>><?php echo displayText('L1224');?><br>
		<input type = "radio" name = "action" id = "radio" value = "System Nonconformance" required <?php if($result10['cparType']=='System Nonconformance'){ echo 'checked';} ?>><?php echo displayText('L1225');?>
		
	</td>
	<td width="300">
		<?php echo displayText('L1226');?>:<input type="text" name="inputName" id="inputName" value="<?php echo $result10['cparOwner']; ?>" style = "border-radius:5px; width:70%; margin-left:2%;" /><br>
		<?php echo displayText('L1121');?>:
		<!--input type = "text" name = "cparSection" value = "<?php echo $result10['cparSection']; ?>" -->
		
		<?php
			if(strstr($result10['cparId'],'CPAR-CUS')!==FALSE)
			{
				?>
				<select name = "cparSection" id="inputName" style = "border-radius:5px; width:70%;" required>
					<option></option>
					<option <?php echo ($result10['cparSection']=='IT') ? 'selected':'';?> value='IT'>IT</option>
					<option <?php echo ($result10['cparSection']=='Engineering') ? 'selected':'';?> value='Engineering'>Engineering</option>
					<option <?php echo ($result10['cparSection']=='Bending') ? 'selected':'';?> value='Bending'>Bending</option>
					<option <?php echo ($result10['cparSection']=='Blanking') ? 'selected':'';?> value='Blanking'>TPP</option>
					<option <?php echo ($result10['cparSection']=='Laser') ? 'selected':'';?> value='Laser'>Laser</option>
					<option <?php echo ($result10['cparSection']=='Welding Assembly') ? 'selected':'';?> value='Welding Assembly'>Welding Assembly</option>
					<option <?php echo ($result10['cparSection']=='Benchwork') ? 'selected':'';?> value='Benchwork'>Benchwork</option>
					<option <?php echo ($result10['cparSection']=='Metal Finishing') ? 'selected':'';?> value='Metal Finishing'>Metal Finishing</option>
					<option <?php echo ($result10['cparSection']=='Powder-Type Painting') ? 'selected':'';?> value='Powder-Type Painting'>Powder-Type Painting</option>
					<option <?php echo ($result10['cparSection']=='Wet-Type Painting') ? 'selected':'';?> value='Wet-Type Painting'>Wet-Type Painting</option>
					<option <?php echo ($result10['cparSection']=='Warehouse') ? 'selected':'';?> value='Warehouse'>Warehouse</option>
					<option <?php echo ($result10['cparSection']=='QC') ? 'selected':'';?> value='QC'>QC</option>
					<option <?php echo ($result10['cparSection']=='Purchasing') ? 'selected':'';?> value='Purchasing'>Purchasing</option>
					<option <?php echo ($result10['cparSection']=='Sales') ? 'selected':'';?> value='Sales'>Sales</option>
					<option <?php echo ($result10['cparSection']=='Subcon') ? 'selected':'';?> value='Subcon'>Subcon</option>
					<option <?php echo ($result10['cparSection']=='Supplier') ? 'selected':'';?> value='Supplier'>Supplier</option>
					<option <?php echo ($result10['cparSection']=='Planning') ? 'selected':'';?> value='Planning'>Planning</option>
					<option <?php echo ($result10['cparSection']=='Management') ? 'selected':'';?> value='Management'>Management</option>
					<option <?php echo ($result10['cparSection']=='HR') ? 'selected':'';?> value='HR'>HR</option>
					<option <?php echo ($result10['cparSection']=='Accounting') ? 'selected':'';?> value='Accounting'>Accounting</option>
					<option <?php echo ($result10['cparSection']=='PCO') ? 'selected':'';?> value='PCO'>PCO</option>					
				</select>
				<?php
			}
			else
			{
				?>
				<input list = "listsection" name = "cparSection" id="inputName" style = "border-radius:5px; width:70%;" value = "<?php echo $result10['cparSection']; ?>" required/>
				<datalist id = 'listsection'>
				<?php
				while($sql9aResult = $sql9a->fetch_array())
				{                                                                   
					echo "<option value = '".$sql9aResult['sectionName']."'>".$sql9aResult['sectionName']."</option>";
				} 
				?>
				</datalist>				
				<?php
			}
		?>
	</td>
	<td width="220">
		<?php echo displayText('L1227');?>:<br>	<input type = 'date' name = 'replyDueDate' style = "border-radius:5px;" value = "<?php echo $result10['cparDueDate']; ?>">
	</td>
	</tr>
</table>
<table border="2">
	<tr>
	<td width="800">
		<b><?php echo displayText('L355');?>:</b><br>
		<input type = "radio" name = "sourceInfo" id = "radios" value = "CPAR-SUP_Supplier" onclick="selectShow();" required <?php if($result10['cparInfoSource']=='Supplier'){ echo 'checked'; } ?> disabled><?php echo strtoupper(displayText('L367'));?>&nbsp;<select style = "display:none; width:25%; border-radius:5px;" id = "sHide" name = "union">
																																		  <option></option>
                                                                    <?php while($sql9Result = $sql9->fetch_array()){ ?>
																																			<?php echo "<option value = '".$sql9Result['Supplier']."' "; if($sql9Result['Supplier']==$result10['cparInfoSourceSubcon']){ echo 'selected'; } echo ">".$sql9Result['Supplier']."</option>"; } ?>
																																		  </select><br>
		<input type = "radio" name = "sourceInfo" id = "radios" value = "CPAR-SUB_Subcon" required <?php if($result10['cparInfoSource']=='Subcon'){ echo 'checked'; } ?> disabled><?php echo strtoupper(displayText('L91'));?><br>
		<input type = "radio" name = "sourceInfo" id = "radios" value = "CPAR-INT_Internal" required <?php if($result10['cparInfoSource']=='Internal'){ echo 'checked'; } ?> disabled><?php echo displayText('L1228');?><font size = "1.5"> ( <?php echo displayText('L1229');?> / <?php echo displayText('L1230');?> / <?php echo displayText('L1231');?> )</font><br>
		<input type = "radio" name = "sourceInfo" id = "radios" value = "CPAR-CUS_Customer Claim" onclick="hideShow();" required <?php if($result10['cparInfoSource']=='Customer Claim'){ echo 'checked'; } ?> disabled><?php echo strtoupper(displayText('L1232'));?>&nbsp;&nbsp;&nbsp;&nbsp;<input type = 'text' name = 'customerHide' id = 'cHide' style = "display:none; width:20%;" value = "<?php echo $result10['cparInfoSourceRemarks']; ?>"><br>
		<input type = "radio" name = "sourceInfo" id = "radios" value = "CPAR-CUS_System" required <?php if($result10['cparInfoSource']=='System'){ echo 'checked'; } ?> disabled><?php echo strtoupper(displayText('L1233'));?>
    </td>
    </tr>
</table>

<table border = "2">
    <tr>
    <td width="230">
    	<center><b><?php echo displayText('L1234');?>:<br></center>
    	<!-- N.G. Quantity:<input type = "number" min = "1" name = "NG" id = "text" style = "width:58%;" value = "<?php// echo $result10['cparQuantity']; ?>" required><br> --><br>
    	<?php echo displayText('L1235');?>:<?php //<br><textarea name = "message1" rows = "7" cols = "30" id = "message" style = "width:100%" required></textarea><br>?>
							<select name = "message1" id = "message1" style = "border-radius:5px; height:40px;width:100%;background-color:#fdfd96;"
							<?php echo $required;?>>
								<option><?php echo $result10['cparDetails']; ?></option>
								<?php
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
						<select name = "cparMoreDetails" id="" style = "border-radius:5px; height:40px;width:100%;background-color:#fdfd96;" <?php echo $required;?>>
						<option><?php echo $result10['cparMoreDetails']; ?></option>
						</select>
						</span>
						<label>Details of Nonconformance</label>
						<textarea name = "detailsOfNonConformance" style="width: 100%;height:65px;"><?php echo $result10['detailsOfNonConformance']; ?></textarea>
																		
    </td>
    <td width="180">
    	<center><b><u><?php echo displayText('L1236');?>:</u></b><br></center>
    	<?php for($u=0; $u<=count($dispo); $u++){ ?>
   		<input type = "checkbox" name = "dispo[]" value = "Sort/Re-inspect" <?php if($dispo[$u]=='Sort/Re-inspect'){ echo "checked='checked'"; } } ?> ><?php echo displayText('L1237');?> / <?php echo displayText('L1238');?><br>
   		<?php for($v=0; $v<=count($dispo); $v++){ ?>
   		<input type = "checkbox" name = "dispo[]" value = "Rework" <?php if($dispo[$v]=='Rework'){ echo "checked='checked'"; } } ?> ><?php echo displayText('L1239');?><br>
   		<?php for($w=0; $w<=count($dispo); $w++){ ?>
   		<input type = "checkbox" name = "dispo[]" value = "Scrap/Disposal/Replacement" <?php if($dispo[$w]=='Scrap/Disposal/Replacement'){ echo "checked='checked'"; } } ?> ><font size = "1.5"><?php echo displayText('L1240');?></font><br>
   		<?php for($x=0; $x<=count($dispo); $x++){ ?>
   		<input type = "checkbox" name = "dispo[]" value = "Return to Supplier/Subcon" <?php if($dispo[$x]=='Return to Supplier/Subcon'){ echo "checked='checked'"; } } ?> ><?php echo displayText('L1241');?><br>
   		<?php for($y=0; $y<=count($dispo); $y++){ ?>
   		<input type = "checkbox" name = "dispo[]" value = "Special Acceptance" <?php if($dispo[$y]=='Special Acceptance'){ echo "checked='checked'"; } } ?> ><?php echo displayText('L865');?><br>
   		<?php for($z=0; $z<=count($dispo); $z++){ ?>
   		<input type = "checkbox" name = "dispo[]" value = "Others" id = "chkBox" onclick = "showHide();" <?php if($dispo[$z]=='Others'){ echo "checked='checked'"; } } ?> ><?php echo displayText('L1104');?>
   		<input type = "text" name = "hideText" id = "txtBox" style="display:none;">
    </td>
    <td width="305">
   		<?php echo displayText('L1242');?>:<input type = "text" name = "prong" id = "text" value = "<?php echo $result10['cparCause']; ?>" style = "margin-left:5.5%;"><br>
   		<?php echo displayText('L1243');?>:<input type="text" name="inputName1" id="inputName1" value="<?php echo $result10['cparSourcePerson']; ?>" style = "border-radius:5px;"><br>
   		<?php echo displayText('L1244');?>:<input type = "text" name = "detectProcess" id = "text" value = "<?php echo $result10['cparDetectProcess']; ?>"><br>
   		<?php echo displayText('L1245');?>:<input type="text" name="inputName2" id="inputName2" value="<?php echo $result10['cparDetectPerson']; ?>" style = "border-radius:5px; margin-left:3%"><br>
   		<?php echo displayText('L1246');?>:<input type = "date" name = "dateDetect" id = "text" style = "margin-left:9%;" value = "<?php echo $result10['cparDetectDate']; ?>"><br>
      <?php echo displayText('L1247');?>:<input type = "text" name = "itemPrice" min = "1" style = "border-radius:5px; margin-left:16.8%" value = "<?php echo $result10['cparItemPrice']; ?>"><br>    	
    	<input type = "radio" name = "choices" id = "radios" value = "Need Corrective Action" required <?php if($result10['cparAction']=='Need Corrective Action'){ echo 'checked'; } ?> ><?php echo displayText('L1248');?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type = "radio" name = "choices" id = "radios" value = "Information Only" required <?php if($result10['cparAction']=='Information Only'){ echo 'checked'; } ?> ><?php echo displayText('L1249');?>
	</td>
    </tr>
    
   	<tr>
   	<td>
   		<b><?php echo strtoupper(displayText('L1250'));?>:</b><br>
   		<textarea name = "interim" rows = "10" cols = "30" id = "message" style = "width:100%"><?php echo $result10['cparInterimAction']; ?></textarea>
   	</td>
   	<td>	
   		<b><?php echo strtoupper(displayText('L1251'));?>:</b><br>
   		<?php for($i=0; $i<=count($analysis); $i++){ ?>
   		<input type = "checkbox" name = "4m[]" value = "Man" <?php if($analysis[$i]=='Man'){ echo "checked= 'checked'"; } } ?> ><?php echo displayText('L1252');?><br>
   		<?php for($j=0; $j<=count($analysis); $j++){ ?>
   		<input type = "checkbox" name = "4m[]" value = "Machine" <?php if($analysis[$j]=='Machine'){ echo "checked= 'checked'"; } } ?> ><?php echo displayText('L229');?><br>
   		<?php for($k=0; $k<=count($analysis); $k++){ ?>
   		<input type = "checkbox" name = "4m[]" value = "Method and Measurement" <?php if($analysis[$k]=='Method and Measurement'){ echo "checked=' checked'"; } } ?> ><?php echo displayText('L1253');?><br>
   		<?php for($l=0; $l<=count($analysis); $l++){ ?>
   		<input type = "checkbox" name = "4m[]" value = "Material" <?php if($analysis[$l]=='Material'){ echo "checked= 'checked'"; } } ?> ><?php echo displayText('L174');?>
    </td>
    <td>
    	<center><b><u><?php echo displayText('L1254');?>:</u></b></center>
    	<?php echo displayText('L462');?>:<input type = "date" name = "retdate" style = "margin-left:27%;" value = "<?php echo $result10['cparReturnDate']; ?>"><br>
    	<?php echo displayText('L1255');?>:<br>
    	<?php echo displayText('L57');?>:<br>
    	<?php echo displayText('L1257');?>:<input type = "date" name = "prodnsched" style = "margin-left:14.5%;" value = "<?php echo $result10['cparProductionSchedule']; ?>"><br>
    	<?php echo displayText('L1258');?>:<input type = "date" name = "delivsub" value = "<?php echo $result10['cparSubconSchedule']; ?>"><br>
    	<?php echo displayText('L1259');?>:<input type = "date" name = "delivcust" style = "margin-left:10%;" value = "<?php echo $result10['cparCustomerSchedule']; ?>"><br>
    	<?php echo displayText('L241');?>:<input type="text" name="inputName3" id="inputName3" value="<?php echo $result10['cparRecoveryIncharge']; ?>" style = "margin-left:31.5%; border-radius:5px; width:47%;" /><br>
    </td>
    </tr>
</table>

<table border = "2"> 
    <tr><td colspan="2"><b><?php echo strtoupper(displayText('L1261'));?>:<b></td></tr>
    <tr>
   	<td width="418">
   		<?php echo strtoupper(displayText('L1262'));?>:<br>
   		<textarea name = "pcause" rows = "3" cols = "30" id = "message" style = "width:100%"><?php echo $result10['cparCauseProcess']; ?></textarea>
   	</td>
   	<td width="500">
   		<?php echo strtoupper(displayText('L1263'));?>:<br>
   		<textarea name = "focause" rows = "3" cols = "30" id = "message" style = "width:100%"><?php echo $result10['cparCauseFlowOut']; ?></textarea>
   	</td>  
   	</tr>
</table>

<table border = "2">
   	<tr><td colspan="2"><b><?php echo strtoupper(displayText('L1065'));?>:<b></td></tr>

   	<tr>
   	<td width="330">
   		<?php echo strtoupper(displayText('L59'));?>:<br>
   		<textarea name = "process" rows = "3" cols = "30" id = "message" style = "width:100%;"><?php echo $result10['cparCorrectiveProcess']; ?></textarea><br>
<table>
	<tr>
	<td><?php echo displayText('L1264');?>:<input type = "date" name = "impdate1" id = "text" value = "<?php echo $result10['cparCorrectiveProcessDate']; ?>"></td>
   	<td><?php echo displayText('L241');?>:<br><input type="text" name="inputName4" id="inputName4" value="<?php echo $result10['cparCorrectiveProcessIncharge']; ?>" style = "border-radius:5px; width:100%;" />
   	</td>
   	</tr>
</table>
   	</td>
   	<td width="390">
   		<?php echo strtoupper(displayText('L1273'));?>:<br>
   		<textarea name = "fo" rows = "3" cols = "30" id = "message" style = "width:100%;"><?php echo $result10['cparCorrectiveFlowOut']; ?></textarea>
<table>
	<tr>
	<td width="200"><?php echo displayText('L1264');?>:<input type = "date" name = "impdate2" id = "text" value = "<?php echo $result10['cparCorrectiveFlowOutDate']; ?>"></td>
   	<td width="200"><?php echo displayText('L241');?>:<input type="text" name="inputName5" id="inputName5" value="<?php echo $result10['cparCorrectiveFlowOutIncharge']; ?>" style = "border-radius:5px; width:100%;" />
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
-->
<!--
   		<b>:</b><br>
-->
		<input type="hidden" name="prevaction" id="message" value="<?php echo $result10['cparPreventiveAction']; ?>" style = "border-radius:5px; width:100%" />
   		
<!--
   	</td>
-->
<!--
   	<td><br>
-->

   	<input type="hidden" name="inputName6" id="inputName6" value="<?php echo $result10['cparPreventiveActionIncharge']; ?>" style = "border-radius:5px; width:100%" />
<!--
   	</td>
-->
<!--
   	<td><br>
-->
   		<input type = "hidden" name = "impdate3" style = "border-radius:5px;" value = "<?php echo $result10['cparPreventiveActionDate']; ?>">
<!--
   	</td>
-->
<!--
   	</tr>
-->

   	<tr>
   	<td>
   		<b><?php echo displayText('L1266');?></b><i> (<?php echo displayText('L1267');?>)</i><br>
   		<textarea name = "verif" rows = "5" cols = "30" id = "message" style = "width:100%;"><?php echo $result10['cparVerification']; ?></textarea>
   	</td>
   	<td>
   	<?php echo displayText('L1268');?>:<br><input type="text" name="inputName7" id="inputName7" value="<?php echo $result10['cparVerificationIncharge']; ?>" style = "border-radius:5px; width:100%" />
<table>
	<tr>
	<td width="194">
   		<?php echo displayText('L292');?>:<input type = "date" name = "verifdate" style = "border-radius:5px; width:100%" value = "<?php echo $result10['cparVerificationDate']; ?>">
	</td>
	</tr>
</table>
   	</td>
   	<td>
   		<center><?php echo (displayText('L172'));?></center><br>
		<input type = "radio" name = "status" value = "Issued" <?php if($result10['cparStatus']=='Issued'){ echo 'checked'; } ?>> <?php echo strtoupper(displayText('L1270'));?><br>   		
   		<input type = "radio" name = "status" value = "Answered" <?php if($result10['cparStatus']=='Answered'){ echo 'checked'; } ?>> <?php echo strtoupper(displayText('L1272'));?><br>
		<input type = "radio" name = "status" value = "Verified" <?php if($result10['cparStatus']=='Verified'){ echo 'checked'; } ?>> <?php echo strtoupper(displayText('L1269'));?><br>
		<input type = "radio" name = "status" value = "Closed" <?php if($result10['cparStatus']=='Closed'){ echo 'checked'; } ?>> <?php echo strtoupper(displayText('L1604'));?><br>
   		
   	</td>
   	</tr>
</table>
	<center><input type ="submit" name = "submit" value = "<?php echo displayText('L1054');//Update?>" id = "buton"  class = 'anthony_submit' style = "width:10%">&nbsp;&nbsp;&nbsp;<input type ="reset"  value="<?php echo displayText('L1337')//Reset;?>" class = 'anthony_submit' style = "width:10%"></center>
<!-- <span class="art-button-wrapper">
<span class="art-button-l"> </span>
<span class="art-button-r"> </span>
<div id="submitButton">
<label for = "submit"><input type ="submit" name = "submit" value = "Save" class="art-button"> -->
</div>
</span>
</form>
</p></div></center>											  
<?php //include('bodydown.php'); ?>
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
	
	$(function(){
		$('#upload_file').click(function(){
			if($(this).val()=="Open"){
				window.open("anthony_viewPDF.php?cparId=<?php echo $_GET['cparId']; ?>","_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=250, width=850, height=600");
				return false;
			}
		});
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
</script>

</html>
