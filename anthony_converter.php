<?php 
session_start();
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
//$path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
$javascriptLib = "/V3/Common Data/Libraries/Javascript/";
$templates = "/V3/Common Data/Templates/";
set_include_path($path);	
include('PHP Modules/mysqliConnection.php');
ini_set("display_errors", "on");
include('classes/fpdf.php');
include('PHP Modules/gerald_functions.php');
require('Libraries/PHP/FPDF/fpdf.php');
require('Libraries/PHP/FPDI/fpdi.php');


//-----------------ROSEMIE NUNEZ
function em($word){

    $word = str_replace("@","%40",$word);
    $word = str_replace("`","%60",$word);
    $word = str_replace("¢","%A2",$word);
    $word = str_replace("£","%A3",$word);
    $word = str_replace("¥","%A5",$word);
    $word = str_replace("|","%A6",$word);
    $word = str_replace("«","%AB",$word);
    $word = str_replace("¬","%AC",$word);
    $word = str_replace("¯","%AD",$word);
    $word = str_replace("°","%B0",$word);
    $word = str_replace("±","%B1",$word);
    $word = str_replace("ª","%B2",$word);
    $word = str_replace("µ","%B5",$word);
    $word = str_replace("»","%BB",$word);
    $word = str_replace("¼","%BC",$word);
    $word = str_replace("½","%BD",$word);
    $word = str_replace("¿","%BF",$word);
    $word = str_replace("À","%C0",$word);
    $word = str_replace("Á","%C1",$word);
    $word = str_replace("Â","%C2",$word);
    $word = str_replace("Ã","%C3",$word);
    $word = str_replace("Ä","%C4",$word);
    $word = str_replace("Å","%C5",$word);
    $word = str_replace("Æ","%C6",$word);
    $word = str_replace("Ç","%C7",$word);
    $word = str_replace("È","%C8",$word);
    $word = str_replace("É","%C9",$word);
    $word = str_replace("Ê","%CA",$word);
    $word = str_replace("Ë","%CB",$word);
    $word = str_replace("Ì","%CC",$word);
    $word = str_replace("Í","%CD",$word);
    $word = str_replace("Î","%CE",$word);
    $word = str_replace("Ï","%CF",$word);
    $word = str_replace("Ð","%D0",$word);
    $word = str_replace("Ñ","%D1",$word);
    $word = str_replace("Ò","%D2",$word);
    $word = str_replace("Ó","%D3",$word);
    $word = str_replace("Ô","%D4",$word);
    $word = str_replace("Õ","%D5",$word);
    $word = str_replace("Ö","%D6",$word);
    $word = str_replace("Ø","%D8",$word);
    $word = str_replace("Ù","%D9",$word);
    $word = str_replace("Ú","%DA",$word);
    $word = str_replace("Û","%DB",$word);
    $word = str_replace("Ü","%DC",$word);
    $word = str_replace("Ý","%DD",$word);
    $word = str_replace("Þ","%DE",$word);
    $word = str_replace("ß","%DF",$word);
    $word = str_replace("à","%E0",$word);
    $word = str_replace("á","%E1",$word);
    $word = str_replace("â","%E2",$word);
    $word = str_replace("ã","%E3",$word);
    $word = str_replace("ä","%E4",$word);
    $word = str_replace("å","%E5",$word);
    $word = str_replace("æ","%E6",$word);
    $word = str_replace("ç","%E7",$word);
    $word = str_replace("è","%E8",$word);
    $word = str_replace("é","%E9",$word);
    $word = str_replace("ê","%EA",$word);
    $word = str_replace("ë","%EB",$word);
    $word = str_replace("ì","%EC",$word);
    $word = str_replace("í","%ED",$word);
    $word = str_replace("î","%EE",$word);
    $word = str_replace("ï","%EF",$word);
    $word = str_replace("ð","%F0",$word);
    $word = str_replace("ñ","%F1",$word);
    $word = str_replace("ò","%F2",$word);
    $word = str_replace("ó","%F3",$word);
    $word = str_replace("ô","%F4",$word);
    $word = str_replace("õ","%F5",$word);
    $word = str_replace("ö","%F6",$word);
    $word = str_replace("÷","%F7",$word);
    $word = str_replace("ø","%F8",$word);
    $word = str_replace("ù","%F9",$word);
    $word = str_replace("ú","%FA",$word);
    $word = str_replace("û","%FB",$word);
    $word = str_replace("ü","%FC",$word);
    $word = str_replace("ý","%FD",$word);
    $word = str_replace("þ","%FE",$word);
    $word = str_replace("ÿ","%FF",$word);
    return $word;
}

function characterReplace($string)
{
	return str_replace("?", "-  ",$string);
}

$SJIS_widths = array(' '=>278,'!'=>299,'"'=>353,'#'=>614,'$'=>614,'%'=>721,'&'=>735,'\''=>216,
	'('=>323,')'=>323,'*'=>449,'+'=>529,','=>219,'-'=>306,'.'=>219,'/'=>453,'0'=>614,'1'=>614,
	'2'=>614,'3'=>614,'4'=>614,'5'=>614,'6'=>614,'7'=>614,'8'=>614,'9'=>614,':'=>219,';'=>219,
	'<'=>529,'='=>529,'>'=>529,'?'=>486,'@'=>744,'A'=>646,'B'=>604,'C'=>617,'D'=>681,'E'=>567,
	'F'=>537,'G'=>647,'H'=>738,'I'=>320,'J'=>433,'K'=>637,'L'=>566,'M'=>904,'N'=>710,'O'=>716,
	'P'=>605,'Q'=>716,'R'=>623,'S'=>517,'T'=>601,'U'=>690,'V'=>668,'W'=>990,'X'=>681,'Y'=>634,
	'Z'=>578,'['=>316,'\\'=>614,']'=>316,'^'=>529,'_'=>500,'`'=>387,'a'=>509,'b'=>566,'c'=>478,
	'd'=>565,'e'=>503,'f'=>337,'g'=>549,'h'=>580,'i'=>275,'j'=>266,'k'=>544,'l'=>276,'m'=>854,
	'n'=>579,'o'=>550,'p'=>578,'q'=>566,'r'=>410,'s'=>444,'t'=>340,'u'=>575,'v'=>512,'w'=>760,
	'x'=>503,'y'=>529,'z'=>453,'{'=>326,'|'=>380,'}'=>326,'~'=>387);

class PDF extends FPDI
{
	function AutoFitCell($w='',$h='',$font='',$style='',$fontSize='',$string='',$border='',$ln='',$align='',$fill='',$link='') 
	{
		$decrement = 0.1;
		$limit = round($w)-(round($w)/3);
		
		$this->SetFont($font, $style, $fontSize);
		if(strlen($string)>$limit)
		{
			$string = substr($string,0,$limit);
			$string .= '...';
		}
		
		while($this->GetStringWidth($string) > $w)
		{
			$this->SetFontSize($fontSize -= $decrement);
		}
		
		return $this->Cell($w,$h,$string,$border,$ln,$align,$fill,$link);
	}	
	
	///---JAPANESE
	function AddCIDFont($family, $style, $name, $cw, $CMap, $registry)
	{
		$fontkey=strtolower($family).strtoupper($style);
		if(isset($this->fonts[$fontkey]))
			$this->Error("CID font already added: $family $style");
		$i=count($this->fonts)+1;
		$this->fonts[$fontkey]=array('i'=>$i,'type'=>'Type0','name'=>$name,'up'=>-120,'ut'=>40,'cw'=>$cw,'CMap'=>$CMap,'registry'=>$registry);
	}

	function AddCIDFonts($family, $name, $cw, $CMap, $registry)
	{
		$this->AddCIDFont($family,'',$name,$cw,$CMap,$registry);
		$this->AddCIDFont($family,'B',$name.',Bold',$cw,$CMap,$registry);
		$this->AddCIDFont($family,'I',$name.',Italic',$cw,$CMap,$registry);
		$this->AddCIDFont($family,'BI',$name.',BoldItalic',$cw,$CMap,$registry);
	}

	function AddSJISFont($family='SJIS')
	{
		// Add SJIS font with proportional Latin
		$name='KozMinPro-Regular-Acro';
		$cw=$GLOBALS['SJIS_widths'];
		$CMap='90msp-RKSJ-H';
		$registry=array('ordering'=>'Japan1','supplement'=>2);
		$this->AddCIDFonts($family,$name,$cw,$CMap,$registry);
	}

	function AddSJIShwFont($family='SJIS-hw')
	{
		// Add SJIS font with half-width Latin
		$name='KozMinPro-Regular-Acro';
		for($i=32;$i<=126;$i++)
			$cw[chr($i)]=500;
		$CMap='90ms-RKSJ-H';
		$registry=array('ordering'=>'Japan1','supplement'=>2);
		$this->AddCIDFonts($family,$name,$cw,$CMap,$registry);
	}

	function GetStringWidth($s)
	{
		if($this->CurrentFont['type']=='Type0')
			return $this->GetSJISStringWidth($s);
		else
			return parent::GetStringWidth($s);
	}

	function GetSJISStringWidth($s)
	{
		// SJIS version of GetStringWidth()
		$l=0;
		$cw=&$this->CurrentFont['cw'];
		$nb=strlen($s);
		$i=0;
		while($i<$nb)
		{
			$o=ord($s[$i]);
			if($o<128)
			{
				// ASCII
				$l+=$cw[$s[$i]];
				$i++;
			}
			elseif($o>=161 && $o<=223)
			{
				// Half-width katakana
				$l+=500;
				$i++;
			}
			else
			{
				// Full-width character
				$l+=1000;
				$i+=2;
			}
		}
		return $l*$this->FontSize/1000;
	}

	function MultiCell($w, $h, $txt, $border=0, $align='L', $fill=false)
	{
		if($this->CurrentFont['type']=='Type0')
			$this->SJISMultiCell($w,$h,$txt,$border,$align,$fill);
		else
			parent::MultiCell($w,$h,$txt,$border,$align,$fill);
	}

	function SJISMultiCell($w, $h, $txt, $border=0, $align='L', $fill=false)
	{
		// Output text with automatic or explicit line breaks
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 && $s[$nb-1]=="\n")
			$nb--;
		$b=0;
		if($border)
		{
			if($border==1)
			{
				$border='LTRB';
				$b='LRT';
				$b2='LR';
			}
			else
			{
				$b2='';
				if(is_int(strpos($border,'L')))
					$b2.='L';
				if(is_int(strpos($border,'R')))
					$b2.='R';
				$b=is_int(strpos($border,'T')) ? $b2.'T' : $b2;
			}
		}
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
			// Get next character
			$c=$s[$i];
			$o=ord($c);
			if($o==10)
			{
				// Explicit line break
				$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				if($border && $nl==2)
					$b=$b2;
				continue;
			}
			if($o<128)
			{
				// ASCII
				$l+=$cw[$c];
				$n=1;
				if($o==32)
					$sep=$i;
			}
			elseif($o>=161 && $o<=223)
			{
				// Half-width katakana
				$l+=500;
				$n=1;
				$sep=$i;
			}
			else
			{
				// Full-width character
				$l+=1000;
				$n=2;
				$sep=$i;
			}
			if($l>$wmax)
			{
				// Automatic line break
				if($sep==-1 || $i==$j)
				{
					if($i==$j)
						$i+=$n;
					$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
				}
				else
				{
					$this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
					$i=($s[$sep]==' ') ? $sep+1 : $sep;
				}
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				if($border && $nl==2)
					$b=$b2;
			}
			else
			{
				$i+=$n;
				if($o>=128)
					$sep=$i;
			}
		}
		// Last chunk
		if($border && is_int(strpos($border,'B')))
			$b.='B';
		$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
		$this->x=$this->lMargin;
	}

	function Write($h, $txt, $link='')
	{
		if($this->CurrentFont['type']=='Type0')
			$this->SJISWrite($h,$txt,$link);
		else
			parent::Write($h,$txt,$link);
	}

	function SJISWrite($h, $txt, $link)
	{
		// SJIS version of Write()
		$cw=&$this->CurrentFont['cw'];
		$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
			// Get next character
			$c=$s[$i];
			$o=ord($c);
			if($o==10)
			{
				// Explicit line break
				$this->Cell($w,$h,substr($s,$j,$i-$j),0,2,'',0,$link);
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				if($nl==1)
				{
					// Go to left margin
					$this->x=$this->lMargin;
					$w=$this->w-$this->rMargin-$this->x;
					$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
				}
				$nl++;
				continue;
			}
			if($o<128)
			{
				// ASCII
				$l+=$cw[$c];
				$n=1;
				if($o==32)
					$sep=$i;
			}
			elseif($o>=161 && $o<=223)
			{
				// Half-width katakana
				$l+=500;
				$n=1;
				$sep=$i;
			}
			else
			{
				// Full-width character
				$l+=1000;
				$n=2;
				$sep=$i;
			}
			if($l>$wmax)
			{
				// Automatic line break
				if($sep==-1 || $i==$j)
				{
					if($this->x>$this->lMargin)
					{
						// Move to next line
						$this->x=$this->lMargin;
						$this->y+=$h;
						$w=$this->w-$this->rMargin-$this->x;
						$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
						$i+=$n;
						$nl++;
						continue;
					}
					if($i==$j)
						$i+=$n;
					$this->Cell($w,$h,substr($s,$j,$i-$j),0,2,'',0,$link);
				}
				else
				{
					$this->Cell($w,$h,substr($s,$j,$sep-$j),0,2,'',0,$link);
					$i=($s[$sep]==' ') ? $sep+1 : $sep;
				}
				$sep=-1;
				$j=$i;
				$l=0;
				if($nl==1)
				{
					$this->x=$this->lMargin;
					$w=$this->w-$this->rMargin-$this->x;
					$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
				}
				$nl++;
			}
			else
			{
				$i+=$n;
				if($o>=128)
					$sep=$i;
			}
		}
		// Last chunk
		if($i!=$j)
			$this->Cell($l/1000*$this->FontSize,$h,substr($s,$j,$i-$j),0,0,'',0,$link);
	}

	function _putType0($font)
	{
		// Type0
		$this->_newobj();
		$this->_out('<</Type /Font');
		$this->_out('/Subtype /Type0');
		$this->_out('/BaseFont /'.$font['name'].'-'.$font['CMap']);
		$this->_out('/Encoding /'.$font['CMap']);
		$this->_out('/DescendantFonts ['.($this->n+1).' 0 R]');
		$this->_out('>>');
		$this->_out('endobj');
		// CIDFont
		$this->_newobj();
		$this->_out('<</Type /Font');
		$this->_out('/Subtype /CIDFontType0');
		$this->_out('/BaseFont /'.$font['name']);
		$this->_out('/CIDSystemInfo <</Registry (Adobe) /Ordering ('.$font['registry']['ordering'].') /Supplement '.$font['registry']['supplement'].'>>');
		$this->_out('/FontDescriptor '.($this->n+1).' 0 R');
		$W='/W [1 [';
		foreach($font['cw'] as $w)
			$W.=$w.' ';
		$this->_out($W.'] 231 325 500 631 [500] 326 389 500]');
		$this->_out('>>');
		$this->_out('endobj');
		// Font descriptor
		$this->_newobj();
		$this->_out('<</Type /FontDescriptor');
		$this->_out('/FontName /'.$font['name']);
		$this->_out('/Flags 6');
		$this->_out('/FontBBox [0 -200 1000 900]');
		$this->_out('/ItalicAngle 0');
		$this->_out('/Ascent 800');
		$this->_out('/Descent -200');
		$this->_out('/CapHeight 800');
		$this->_out('/StemV 60');
		$this->_out('>>');
		$this->_out('endobj');
	}	
}

if($_GET['country'] != 1)
{
	$pdf=new PDF('P','mm','A4');
	$pdf->SetLeftMargin(12);
	$pdf->SetTopMargin(2);
	$pdf->SetAutoPageBreak(off);
	$pdf->AddSJISFont();
	$pdf->SetFont('SJIS');
	$pdf->SetFontSize(10);

	$cparId = $_GET['cparId'];

	// ******************* Japanese Translator *******************
	$sql = "SET NAMES sjis ";
	$getJapaneseCharacters = $db->query($sql);
	// ******************* End of Japanese Translator *******************

	$sql5 = $db->query("SELECT * FROM qc_cpar WHERE listId = ".$cparId);
	$sql5Result = $sql5->fetch_array();

	$lotNumberArray = $quantityArray = array();
	$sql = "SELECT cparId, lotNumber, quantity FROM qc_cparlotnumber WHERE cparId LIKE '".$sql5Result['cparId']."' ";
	$getCPARLotNumber = $db->query($sql);
	if($getCPARLotNumber->num_rows > 0)
	{
		while($getCPARLotNumberResult = $getCPARLotNumber->fetch_array())
		{
			$lotNumberArray[] = $getCPARLotNumberResult['lotNumber'];
			$quantityArray[] = $getCPARLotNumberResult['quantity'];
		}
	}

	for ($a = 0; $a < count($lotNumberArray); $a++)
	{
		$pdf->AddPage();
		//-----------------ROSEMIE NUNEZ
		// ------------------------------------------- Ace Sandoval ---------------------------------------------
		if(preg_match("/MAT/", $lotNumberArray[$a]))
		{
			$sql = "SELECT inventoryId, dataOne, dataTwo, dataThree, dataFour FROM warehouse_inventory WHERE inventoryId LIKE '".$lotNumberArray[$a]."' ";
			$getInventory = $db->query($sql);
			$getInventoryResult = $getInventory->fetch_array();
			
			$partName = $getInventoryResult['dataOne'];
			$partNumber = $getInventoryResult['dataTwo']."x".$getInventoryResult['dataThree']."x".$getInventoryResult['dataFour'];
		}
		else if(preg_match("/ACC/", $lotNumberArray[$a]))
		{
			$sql = "SELECT inventoryId, dataOne, dataTwo, dataThree, dataFour FROM warehouse_inventory WHERE inventoryId LIKE '".$lotNumberArray[$a]."' ";
			$getInventory = $db->query($sql);
			$getInventoryResult = $getInventory->fetch_array();
			
			$partName = $getInventoryResult['dataTwo'];
			$partNumber = $getInventoryResult['dataOne']."x".$getInventoryResult['dataThree']."x".$getInventoryResult['dataFour'];
		}
		else
		{
			//~ if($cparId)
			//~ if(preg_match("/CPAR-SYS/", $sql5Result['cparId'])===FALSE)
			if(preg_match("/CPAR-SYS/", $sql5Result['cparId'])==0)//Return 0 if not found FALSE if error occured
			{ 
				$sql = "SELECT poId, partId, workingQuantity, identifier, status FROM ppic_lotlist where lotNumber like '".$lotNumberArray[$a]."'";
				$lotListQuery=$db->query($sql);
				$lotListQueryResult = $lotListQuery->fetch_array();

				if($lotListQueryResult['partId']>0)
				{
					if($lotListQueryResult['identifier']==1)
					{				
						$sql = "SELECT partNumber, partName FROM cadcam_parts where partId = ".$lotListQueryResult['partId'];
						$partListQuery=$db->query($sql);
						$partListQueryResult = $partListQuery->fetch_array();
						
						$partName = $partListQueryResult['partName'];
						$partNumber = $partListQueryResult['partNumber'];
					}
					else if($lotListQueryResult['identifier']==2)
					{
						$sql = "SELECT accessoryNumber, accessoryName FROM cadcam_accessories where accessoryId = ".$lotListQueryResult['partId'];
						$partListQuery=$db->query($sql);
						$partListQueryResult = $partListQuery->fetch_array();
						
						$partName = $partListQueryResult['accessoryName'];
						$partNumber = $partListQueryResult['accessoryNumber'];
					}
					///*
					else if($lotListQueryResult['identifier']==4)
					{
						if($lotListQueryResult['status']==2)
						{
						//purchasing_ subconmaterial
							$sql = "SELECT materialId FROM purchasing_materialtreatment where materialTreatmentId = ".$lotListQueryResult['partId'];
							$materialIdQuery=$db->query($sql);
							$materialIdQueryResult = $materialIdQuery->fetch_array();						
							
								$sql = "SELECT materialTypeId,thickness FROM purchasing_material where materialId = ".$materialIdQueryResult['materialId'];
								$materialTypeIdQuery=$db->query($sql);
								$materialTypeIdResult = $materialTypeIdQuery->fetch_array();
								
								$sql = "SELECT materialType FROM purchasing_materialtype where suppliermaterialID = ".$materialTypeIdResult['materialTypeId'];
								$materialTypeQuery=$db->query($sql);
								$materialTypeResult = $materialTypeQuery->fetch_array();
								
								$partNumber = $materialTypeResult['materialType']." ".$materialTypeIdResult['thickness'];
								$partName = "";
						}
						else
						{
						$partName ="";
						$partNumber ="";
						}
					}
					//*/
					else
					{
						$partName ="";
						$partNumber ="";
					}
					
					$customerAlias = '';
					if($lotListQueryResult['identifier']==1 OR $lotListQueryResult['identifier']==2)
					{
						$sql = "SELECT customerId, poNumber FROM sales_polist where poId = ".$lotListQueryResult['poId'];
						$poListQuery=$db->query($sql);
						$poListQueryResult = $poListQuery->fetch_array();
						
						$sql = "SELECT customerAlias FROM sales_customer where customerId = ".$poListQueryResult['customerId'];
						$customerListQuery=$db->query($sql);
						if($customerListQuery->num_rows > 0)
						{
							$customerListQueryResult = $customerListQuery->fetch_array();
							$customerAlias = $customerListQueryResult['customerAlias'];
						}
					}
				}
				// ---------------------------------------------------------------------------------------------------------

				$sql1 = $db->query("SELECT a.price
									 FROM sales_pricelist AS a,
										  ppic_lotlist AS b
									 WHERE a.arkPartId = b.partId
									 AND b.lotNumber = '".$lotNumberArray[$a]."'");
				$result1 = $sql1->fetch_array();

				//~ $sql2 = $db->query("SELECT a.metalType, a.metalThickness
									 //~ FROM cadcam_materialspecs AS a,
										  //~ cadcam_parts AS b,
										  //~ ppic_lotlist AS c
									 //~ WHERE a.materialSpecId = b.materialSpecId
									   //~ AND b.partId = c.partId
									   //~ AND c.lotNumber = '".$lotNumberArray[$a]."'");
				//~ $result2 = $sql2->fetch_array();
				
				//;cadcam_materialspecs;
				$partId = '';
				$sql = "SELECT partId FROM ppic_lotlist WHERE lotNumber LIKE '".$lotNumberArray[$a]."' LIMIT 1";
				$queryLotList = $db->query($sql); 
				if($queryLotList AND $queryLotList->num_rows > 0)
				{
					$resultLotList = $queryLotList->fetch_assoc();
					$partId = $resultLotList['partId'];
				}
				
				$materialSpecId = '';
				$sql = "SELECT materialSpecId FROM cadcam_parts WHERE partId = ".$partId." LIMIT 1";
				$queryParts = $db->query($sql);
				if($queryParts AND $queryParts->num_rows > 0)
				{
					$resultParts = $queryParts->fetch_assoc();
					$materialSpecId = $resultParts['materialSpecId'];
				}
				
				$materialTypeId = '';
				$sql = "SELECT materialTypeId, metalThickness FROM cadcam_materialspecs WHERE materialSpecId = ".$materialSpecId." LIMIT 1";
				$queryMaterialSpecs = $db->query($sql);
				if($queryMaterialSpecs AND $queryMaterialSpecs->num_rows > 0)
				{
					$resultMaterialSpecs = $queryMaterialSpecs->fetch_assoc();
					$materialTypeId = $resultMaterialSpecs['materialTypeId'];
					$metalThickness = $resultMaterialSpecs['metalThickness'];
				}
				
				$materialType = '';
				$sql = "SELECT materialType FROM engineering_materialtype WHERE materialTypeId = ".$materialTypeId." LIMIT 1";
				$queryMaterialType = $db->query($sql);
				if($queryMaterialType AND $queryMaterialType->num_rows > 0)
				{
					$resultMaterialType = $queryMaterialType->fetch_assoc();
					$materialType = $resultMaterialType['materialType'];
				}
				//;cadcam_materialspecs;
				
				$sql = "SELECT a.issue FROM purchasing_drdetails AS a, purchasing_drcustomer AS b WHERE a.drNumber = b.drNumber AND b.lotNumber = '".$lotNumberArray[$a]."'";
				$sql4 = $db->query($sql);
				$result4 = $sql4->fetch_array();
			}
			//rosemie nunez
			// ------------------------------------ Customer Claim ---------------------------------------------------
			else
			{
				// --------------------------------- Copied By Ace Sandoval From Above : Retrieve Delivery Date ------------------------------------------------
				$sql = "SELECT a.issue FROM purchasing_drdetails AS a, purchasing_drcustomer AS b WHERE a.drNumber = b.drNumber AND b.lotNumber = '".$lotNumberArray[$a]."'";
				$sql4 = $db->query($sql);
				$result4 = $sql4->fetch_array();
				
			$sql = "SELECT poId, partId, workingQuantity, identifier, status FROM ppic_lotlist where lotNumber like '".$lotNumberArray[$a]."'";
				$lotListQuery=$db->query($sql);
				$lotListQueryResult = $lotListQuery->fetch_array();

				if($lotListQueryResult['partId']>0 and trim($lotNumberArray[$a])!="")
				{
					if($lotListQueryResult['identifier']==1)
					{				
						$sql = "SELECT partNumber, partName FROM cadcam_parts where partId = ".$lotListQueryResult['partId'];
						$partListQuery=$db->query($sql);
						$partListQueryResult = $partListQuery->fetch_array();
						
						$partName = $partListQueryResult['partName'];
						$partNumber = $partListQueryResult['partNumber'];
					}
					else if($lotListQueryResult['identifier']==2)
					{
						$sql = "SELECT accessoryNumber, accessoryName FROM cadcam_accessories where accessoryId = ".$lotListQueryResult['partId'];
						$partListQuery=$db->query($sql);
						$partListQueryResult = $partListQuery->fetch_array();
						
						$partName = $partListQueryResult['accessoryName'];
						$partNumber = $partListQueryResult['accessoryNumber'];
					}
					else if($lotListQueryResult['identifier']==4)
					{
						if($lotListQueryResult['status']==2)
						{
						//purchasing_ subconmaterial
							$sql = "SELECT materialId FROM purchasing_materialtreatment where materialTreatmentId = ".$lotListQueryResult['partId'];
							$materialIdQuery=$db->query($sql);
							$materialIdQueryResult = $materialIdQuery->fetch_array();						
							
								$sql = "SELECT materialTypeId,thickness, length, width FROM purchasing_material where materialId = ".$materialIdQueryResult['materialId'];
								$materialTypeIdQuery=$db->query($sql);
								$materialTypeIdResult = $materialTypeIdQuery->fetch_array();
								
								$sql = "SELECT materialType FROM purchasing_materialtype where suppliermaterialID = ".$materialTypeIdResult['materialTypeId'];
								$materialTypeQuery=$db->query($sql);
								$materialTypeResult = $materialTypeQuery->fetch_array();
								
								$partNumber = $materialTypeResult['materialType']." ".$materialTypeIdResult['thickness'];
								$partName = $materialTypeIdResult['length']." X ".$materialTypeIdResult['width'];
						}
						else
						{
						$partName ="";
						$partNumber ="";
						}
					}
					else
					{
						$partName ="";
						$partNumber ="";
					}
					//$sql = "SELECT partNumber, partName FROM cadcam_parts where partId = ".$lotListQueryResult['partId'];
					//$partListQuery=$db->query($sql);
					//$partListQueryResult = $partListQuery->fetch_array();
					
					//$partName = $partListQueryResult['partName'];
					//$partNumber = $partListQueryResult['partNumber'];
					
					$customerAlias = '';
					if($lotListQueryResult['identifier']==1 OR $lotListQueryResult['identifier']==2)
					{				
						$sql = "SELECT customerId, poNumber FROM sales_polist where poId = ".$lotListQueryResult['poId'];
						$poListQuery=$db->query($sql);
						$poListQueryResult = $poListQuery->fetch_array();

						$sql = "SELECT customerAlias FROM sales_customer where customerId = ".$poListQueryResult['customerId'];
						$customerListQuery=$db->query($sql);
						if($customerListQuery->num_rows > 0)
						{
							$customerListQueryResult = $customerListQuery->fetch_array();
							$customerAlias = $customerListQueryResult['customerAlias'];
						}
					}
				}
			}
			//rosemie nunez
		}
		$newAnalysis = explode(',',$sql5Result['cparAnalysis']);

		$newDisposition = explode(',',$sql5Result['cparDisposition']);

		// --------------------------------------- Ace Sandoval ---------------------------------------------
		// ---------------------------- Detect Subcon and Supplier Non Conformance --------------------------
		if(preg_match('/SUB/',$sql5Result['cparId']) or preg_match('/SUP/',$sql5Result['cparId']))
		{
		//$pdf->Line(134,57,202,102);
		}
		
		$pdf->Image('images/CPAR Format Japan.png',0,0,206,298);

		# LOTNUMBER
		$pdf->SetXY(145,32);
		$pdf->Cell(20,4.5,$lotNumberArray[$a],0,0,'L');

		# DATE DETECTED 
		if($sql5Result['cparDetectDate']=="0000-00-00")
		{
			$outputDate = "";
		}
		else
		{
			$outputDate = date('F d, Y',strtotime($sql5Result['cparDetectDate']));
		}

		$month = date("F", strtotime($outputDate)); 
		$day = date("d", strtotime($outputDate)); 
		$year = date("Y", strtotime($outputDate)); 

		$pdf->SetXY(38.3,21.5);
		$pdf->Cell(20,4.5,$month,0,0,'L');
		$pdf->SetXY(65,21.5);
		$pdf->Cell(20,4.5,$day,0,0,'L');
		$pdf->SetXY(79.5,21.5);
		$pdf->Cell(20,4.5,$year,0,0,'L');

		# DETAILS OF NON-CONFORMANCE
		$pdf->SetXY(40,55.5);
		$pdf->Cell(20,4.5,$sql5Result['cparDetails'],0,0,'L');

		# LOT QUANTITY
		$pdf->SetXY(75,95.5);
		$pdf->Cell(20,4.5,$lotListQueryResult['workingQuantity'],0,0,'L');

		# DETECTING PERSON
		$pdf->SetXY(175,33.5);
		$pdf->Cell(20,4.5,$sql5Result['cparDetectPerson'],0,0,'L');

		# INTERIM ACTION
		$pdf->SetXY(20,162.5);
		$pdf->Cell(20,4.5,characterReplace($sql5Result['cparInterimAction']),0,0,'L');

		# ITEM PRICE
		$price = em($sql5Result['cparItemPrice']); $price = urldecode($price);
		$pdf->SetXY(105,95.5);
		$pdf->Cell(20,4.5,$price,0,0,'L');

		#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~CAUSE OF DEFECT~~~~~~~~~~~~~~~~~~~~~~~~~~
		# PROCESS CAUSE
		$pdf->SetXY(30,110.5);
		$pdf->Cell(20,4.5,characterReplace($sql5Result['cparCauseProcess']),0,0,'L');

		# FLOW-OUT CAUSE
		$pdf->SetXY(30,135.5);
		$pdf->Cell(20,4.5,characterReplace($sql5Result['cparCauseFlowOut']),0,0,'L');

		#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~CAUSE OF DEFECT~~~~~~~~~~~~~~~~~~~~~~~~~~

		#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~CORRECTIVE ACTION~~~~~~~~~~~~~~~~~~~~~~~~~~
		# PROCESS
		$pdf->SetXY(25,205.5);
		$pdf->Cell(20,4.5,characterReplace($sql5Result['cparCorrectiveProcess']),0,0,'L');

		# FLOW-OUT
		$pdf->SetXY(25,220.5);
		$pdf->Cell(20,4.5,characterReplace($sql5Result['cparCorrectiveFlowOut']),0,0,'L');

		#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~CORRECTIVE ACTION~~~~~~~~~~~~~~~~~~~~~~~~~~

		# IMPLEMENTATION DATE (PROCESS)
		if($sql5Result['cparCorrectiveProcessDate']=="0000-00-00")
		{
			$outputDate = "";
		}
		else
		{
			$outputDate = date('M d, Y',strtotime($sql5Result['cparCorrectiveProcessDate']));
		}

		$pdf->SetFont('SJIS','',9);
		$pdf->SetXY(157.6,205.5);
		$pdf->Cell(20,4.5,$outputDate,0,0,'L');

		$pdf->SetXY(177.6,225.5);
		$pdf->Cell(20,4.5,$sql5Result['cparCorrectiveProcessIncharge'],0,0,'L');

		# PREVENTIVE ACTION
		$pdf->SetXY(25,242.5);
		$pdf->Cell(20,4.5,characterReplace($sql5Result['cparPreventiveAction']),0,0,'L');

		# PREVENTIVE ACTION IN-CHARGE
		$pdf->SetXY(138,244.5);
		$pdf->Cell(20,4.5,$sql5Result['cparPreventiveActionIncharge'],0,0,'L');

		# PREVENTIVE ACTION IMPLEMENTATION DATE
		if($sql5Result['cparPreventiveActionDate']=="0000-00-00")
		{
			$outputDate = "";
		}
		else
		{
			$outputDate = date('M d, Y',strtotime($sql5Result['cparPreventiveActionDate']));
		}

		$pdf->SetXY(157.6,244.5);
		$pdf->Cell(20,4.5,$outputDate,0,0,'L');

		# PRODUCTION SCHED.
		if($sql5Result['cparProductionSchedule']=="0000-00-00")
		{
			$outputDate = "";
		}
		else
		{
			$outputDate = date('M d, Y',strtotime($sql5Result['cparProductionSchedule']));
		}
		
		$pdf->SetXY(61,188.5);
		$pdf->Cell(20,4.5,$outputDate,0,0,'L');

		# VERIFICATION OF EFFECTIVENESS
		$pdf->SetXY(20,264.5);
		$pdf->Cell(20,4.5,characterReplace($sql5Result['cparVerification']),0,0,'L');

		# VERIFIED BY
		$pdf->SetXY(178,264.5);
		$pdf->Cell(20,4.5,$sql5Result['cparVerificationIncharge'],0,0,'L');

		if($sql5Result['cparVerificationDate']=="0000-00-00")
		{
			$outputDate = "";
		}
		else
		{
			$outputDate = date('M d, Y',strtotime($sql5Result['cparVerificationDate']));
		}

		# VERIFICATION DATE
		$pdf->SetXY(157.6,264.5);
		$pdf->Cell(20,4.5,$outputDate,0,0,'L');



		# DETECTING PROCESS
		$pdf->SetXY(175,63.5);
		$pdf->Cell(20,4.5,$sql5Result['cparDetectProcess'],0,0,'L');

		# PROCESS CAUSE OF N.G
		$pdf->SetXY(174,83.5);
		$pdf->Cell(20,4.5,$sql5Result['cparCause'],0,0,'L');

		if(strlen($sql5Result['cparInfoSourceSubcon'])>20)
		{
			$pdf->SetFont('SJIS','',7);
		}
		else
		{
			$pdf->SetFont('SJIS','',9);
		}

		# CONCERNED PERSON
		$pdf->SetXY(131,100.5);
		$pdf->Cell(20,4.5,$sql5Result['cparSourcePerson'],0,0,'L');

	}
}
else
{

	$pdf=new PDF('P','mm','A4');
	$pdf->SetLeftMargin(12);
	$pdf->SetTopMargin(2);
	$pdf->SetAutoPageBreak(off);
	$pdf->AddSJISFont();
	$pdf->SetFont('SJIS');
	$pdf->SetFontSize(9);

	$cparId = $_GET['cparId'];

	// ******************* Japanese Translator *******************
	$sql = "SET NAMES sjis ";
	$getJapaneseCharacters = $db->query($sql);
	// ******************* End of Japanese Translator *******************

	$sql5 = $db->query("SELECT * FROM qc_cpar WHERE listId = ".$cparId);
	$sql5Result = $sql5->fetch_array();

	$lotNumberArray = $quantityArray = array();
	$sql = "SELECT cparId, lotNumber, quantity FROM qc_cparlotnumber WHERE cparId LIKE '".$sql5Result['cparId']."' ";
	$getCPARLotNumber = $db->query($sql);
	if($getCPARLotNumber->num_rows > 0)
	{
		while($getCPARLotNumberResult = $getCPARLotNumber->fetch_array())
		{
			$lotNumberArray[] = $getCPARLotNumberResult['lotNumber'];
			$quantityArray[] = $getCPARLotNumberResult['quantity'];
		}
	}

	for ($a = 0; $a < count($lotNumberArray); $a++)
	{
		$pdf->AddPage();
		//-----------------ROSEMIE NUNEZ
		// ------------------------------------------- Ace Sandoval ---------------------------------------------
		if(preg_match("/MAT/", $lotNumberArray[$a]))
		{
			$sql = "SELECT inventoryId, dataOne, dataTwo, dataThree, dataFour FROM warehouse_inventory WHERE inventoryId LIKE '".$lotNumberArray[$a]."' ";
			$getInventory = $db->query($sql);
			$getInventoryResult = $getInventory->fetch_array();
			
			$partName = $getInventoryResult['dataOne'];
			$partNumber = $getInventoryResult['dataTwo']."x".$getInventoryResult['dataThree']."x".$getInventoryResult['dataFour'];
		}
		else if(preg_match("/ACC/", $lotNumberArray[$a]))
		{
			$sql = "SELECT inventoryId, dataOne, dataTwo, dataThree, dataFour FROM warehouse_inventory WHERE inventoryId LIKE '".$lotNumberArray[$a]."' ";
			$getInventory = $db->query($sql);
			$getInventoryResult = $getInventory->fetch_array();
			
			$partName = $getInventoryResult['dataTwo'];
			$partNumber = $getInventoryResult['dataOne']."x".$getInventoryResult['dataThree']."x".$getInventoryResult['dataFour'];
		}
		else
		{
			//~ if($cparId)
			//~ if(preg_match("/CPAR-SYS/", $sql5Result['cparId'])===FALSE)
			if(preg_match("/CPAR-SYS/", $sql5Result['cparId'])==0)//Return 0 if not found FALSE if error occured
			{ 
				$sql = "SELECT poId, partId, workingQuantity, identifier, status FROM ppic_lotlist where lotNumber like '".$lotNumberArray[$a]."'";
				$lotListQuery=$db->query($sql);
				$lotListQueryResult = $lotListQuery->fetch_array();

				if($lotListQueryResult['partId']>0)
				{
					if($lotListQueryResult['identifier']==1)
					{				
						$sql = "SELECT partNumber, partName FROM cadcam_parts where partId = ".$lotListQueryResult['partId'];
						$partListQuery=$db->query($sql);
						$partListQueryResult = $partListQuery->fetch_array();
						
						$partName = $partListQueryResult['partName'];
						$partNumber = $partListQueryResult['partNumber'];
					}
					else if($lotListQueryResult['identifier']==2)
					{
						$sql = "SELECT accessoryNumber, accessoryName FROM cadcam_accessories where accessoryId = ".$lotListQueryResult['partId'];
						$partListQuery=$db->query($sql);
						$partListQueryResult = $partListQuery->fetch_array();
						
						$partName = $partListQueryResult['accessoryName'];
						$partNumber = $partListQueryResult['accessoryNumber'];
					}
					///*
					else if($lotListQueryResult['identifier']==4)
					{
						if($lotListQueryResult['status']==2)
						{
						//purchasing_ subconmaterial
							$sql = "SELECT materialId FROM purchasing_materialtreatment where materialTreatmentId = ".$lotListQueryResult['partId'];
							$materialIdQuery=$db->query($sql);
							$materialIdQueryResult = $materialIdQuery->fetch_array();						
							
								$sql = "SELECT materialTypeId,thickness FROM purchasing_material where materialId = ".$materialIdQueryResult['materialId'];
								$materialTypeIdQuery=$db->query($sql);
								$materialTypeIdResult = $materialTypeIdQuery->fetch_array();
								
								$sql = "SELECT materialType FROM purchasing_materialtype where suppliermaterialID = ".$materialTypeIdResult['materialTypeId'];
								$materialTypeQuery=$db->query($sql);
								$materialTypeResult = $materialTypeQuery->fetch_array();
								
								$partNumber = $materialTypeResult['materialType']." ".$materialTypeIdResult['thickness'];
								$partName = "";
						}
						else
						{
						$partName ="";
						$partNumber ="";
						}
					}
					//*/
					else
					{
						$partName ="";
						$partNumber ="";
					}
					
					$customerAlias = '';
					if($lotListQueryResult['identifier']==1 OR $lotListQueryResult['identifier']==2)
					{
						$sql = "SELECT customerId, poNumber FROM sales_polist where poId = ".$lotListQueryResult['poId'];
						$poListQuery=$db->query($sql);
						$poListQueryResult = $poListQuery->fetch_array();
						
						$sql = "SELECT customerAlias FROM sales_customer where customerId = ".$poListQueryResult['customerId'];
						$customerListQuery=$db->query($sql);
						if($customerListQuery->num_rows > 0)
						{
							$customerListQueryResult = $customerListQuery->fetch_array();
							$customerAlias = $customerListQueryResult['customerAlias'];
						}
					}
				}
				// ---------------------------------------------------------------------------------------------------------

				$sql1 = $db->query("SELECT a.price
									 FROM sales_pricelist AS a,
										  ppic_lotlist AS b
									 WHERE a.arkPartId = b.partId
									 AND b.lotNumber = '".$lotNumberArray[$a]."'");
				$result1 = $sql1->fetch_array();

				//~ $sql2 = $db->query("SELECT a.metalType, a.metalThickness
									 //~ FROM cadcam_materialspecs AS a,
										  //~ cadcam_parts AS b,
										  //~ ppic_lotlist AS c
									 //~ WHERE a.materialSpecId = b.materialSpecId
									   //~ AND b.partId = c.partId
									   //~ AND c.lotNumber = '".$lotNumberArray[$a]."'");
				//~ $result2 = $sql2->fetch_array();
				
				//;cadcam_materialspecs;
				$partId = '';
				$sql = "SELECT partId FROM ppic_lotlist WHERE lotNumber LIKE '".$lotNumberArray[$a]."' LIMIT 1";
				$queryLotList = $db->query($sql); 
				if($queryLotList AND $queryLotList->num_rows > 0)
				{
					$resultLotList = $queryLotList->fetch_assoc();
					$partId = $resultLotList['partId'];
				}
				
				$materialSpecId = '';
				$sql = "SELECT materialSpecId FROM cadcam_parts WHERE partId = ".$partId." LIMIT 1";
				$queryParts = $db->query($sql);
				if($queryParts AND $queryParts->num_rows > 0)
				{
					$resultParts = $queryParts->fetch_assoc();
					$materialSpecId = $resultParts['materialSpecId'];
				}
				
				$materialTypeId = '';
				$sql = "SELECT materialTypeId, metalThickness FROM cadcam_materialspecs WHERE materialSpecId = ".$materialSpecId." LIMIT 1";
				$queryMaterialSpecs = $db->query($sql);
				if($queryMaterialSpecs AND $queryMaterialSpecs->num_rows > 0)
				{
					$resultMaterialSpecs = $queryMaterialSpecs->fetch_assoc();
					$materialTypeId = $resultMaterialSpecs['materialTypeId'];
					$metalThickness = $resultMaterialSpecs['metalThickness'];
				}
				
				$materialType = '';
				$sql = "SELECT materialType FROM engineering_materialtype WHERE materialTypeId = ".$materialTypeId." LIMIT 1";
				$queryMaterialType = $db->query($sql);
				if($queryMaterialType AND $queryMaterialType->num_rows > 0)
				{
					$resultMaterialType = $queryMaterialType->fetch_assoc();
					$materialType = $resultMaterialType['materialType'];
				}
				//;cadcam_materialspecs;
				
				$sql = "SELECT a.issue FROM purchasing_drdetails AS a, purchasing_drcustomer AS b WHERE a.drNumber = b.drNumber AND b.lotNumber = '".$lotNumberArray[$a]."'";
				$sql4 = $db->query($sql);
				$result4 = $sql4->fetch_array();
			}
			//rosemie nunez
			// ------------------------------------ Customer Claim ---------------------------------------------------
			else
			{
				// --------------------------------- Copied By Ace Sandoval From Above : Retrieve Delivery Date ------------------------------------------------
				$sql = "SELECT a.issue FROM purchasing_drdetails AS a, purchasing_drcustomer AS b WHERE a.drNumber = b.drNumber AND b.lotNumber = '".$lotNumberArray[$a]."'";
				$sql4 = $db->query($sql);
				$result4 = $sql4->fetch_array();
				
			$sql = "SELECT poId, partId, workingQuantity, identifier, status FROM ppic_lotlist where lotNumber like '".$lotNumberArray[$a]."'";
				$lotListQuery=$db->query($sql);
				$lotListQueryResult = $lotListQuery->fetch_array();

				if($lotListQueryResult['partId']>0 and trim($lotNumberArray[$a])!="")
				{
					if($lotListQueryResult['identifier']==1)
					{				
						$sql = "SELECT partNumber, partName FROM cadcam_parts where partId = ".$lotListQueryResult['partId'];
						$partListQuery=$db->query($sql);
						$partListQueryResult = $partListQuery->fetch_array();
						
						$partName = $partListQueryResult['partName'];
						$partNumber = $partListQueryResult['partNumber'];
					}
					else if($lotListQueryResult['identifier']==2)
					{
						$sql = "SELECT accessoryNumber, accessoryName FROM cadcam_accessories where accessoryId = ".$lotListQueryResult['partId'];
						$partListQuery=$db->query($sql);
						$partListQueryResult = $partListQuery->fetch_array();
						
						$partName = $partListQueryResult['accessoryName'];
						$partNumber = $partListQueryResult['accessoryNumber'];
					}
					else if($lotListQueryResult['identifier']==4)
					{
						if($lotListQueryResult['status']==2)
						{
						//purchasing_ subconmaterial
							$sql = "SELECT materialId FROM purchasing_materialtreatment where materialTreatmentId = ".$lotListQueryResult['partId'];
							$materialIdQuery=$db->query($sql);
							$materialIdQueryResult = $materialIdQuery->fetch_array();						
							
								$sql = "SELECT materialTypeId,thickness, length, width FROM purchasing_material where materialId = ".$materialIdQueryResult['materialId'];
								$materialTypeIdQuery=$db->query($sql);
								$materialTypeIdResult = $materialTypeIdQuery->fetch_array();
								
								$sql = "SELECT materialType FROM purchasing_materialtype where suppliermaterialID = ".$materialTypeIdResult['materialTypeId'];
								$materialTypeQuery=$db->query($sql);
								$materialTypeResult = $materialTypeQuery->fetch_array();
								
								$partNumber = $materialTypeResult['materialType']." ".$materialTypeIdResult['thickness'];
								$partName = $materialTypeIdResult['length']." X ".$materialTypeIdResult['width'];
						}
						else
						{
						$partName ="";
						$partNumber ="";
						}
					}
					else
					{
						$partName ="";
						$partNumber ="";
					}
					//$sql = "SELECT partNumber, partName FROM cadcam_parts where partId = ".$lotListQueryResult['partId'];
					//$partListQuery=$db->query($sql);
					//$partListQueryResult = $partListQuery->fetch_array();
					
					//$partName = $partListQueryResult['partName'];
					//$partNumber = $partListQueryResult['partNumber'];
					
					$customerAlias = '';
					if($lotListQueryResult['identifier']==1 OR $lotListQueryResult['identifier']==2)
					{				
						$sql = "SELECT customerId, poNumber FROM sales_polist where poId = ".$lotListQueryResult['poId'];
						$poListQuery=$db->query($sql);
						$poListQueryResult = $poListQuery->fetch_array();

						$sql = "SELECT customerAlias FROM sales_customer where customerId = ".$poListQueryResult['customerId'];
						$customerListQuery=$db->query($sql);
						if($customerListQuery->num_rows > 0)
						{
							$customerListQueryResult = $customerListQuery->fetch_array();
							$customerAlias = $customerListQueryResult['customerAlias'];
						}
					}
				}
			}
			//rosemie nunez
		}
		$newAnalysis = explode(',',$sql5Result['cparAnalysis']);

		$newDisposition = explode(',',$sql5Result['cparDisposition']);

		// --------------------------------------- Ace Sandoval ---------------------------------------------
		// ---------------------------- Detect Subcon and Supplier Non Conformance --------------------------
		if(preg_match('/SUB/',$sql5Result['cparId']) or preg_match('/SUP/',$sql5Result['cparId']))
		{
		//$pdf->Line(134,57,202,102);
		}


		//----------------- IMAGE ---------------------
		$pdf->Image('images/arkLogo.jpg',13,4,33);
		$pdf->Ln(12);

		//$pdf->MultiCell(63, 10,"asdasd", 1);

		//----------------- CORRECTIVE AND PREVENTIVE ACTION REPORT ---------------------
		$pdf->SetFont('SJIS', 'B', 15);
		$pdf->Cell(1,5,"",0,0);
		$pdf->Cell(130,5,"CORRECTIVE AND PREVENTIVE ACTION REPORT",0,0);
		$pdf->SetFont('SJIS', '', 9);
		$pdf->Cell(16,4,"",0,0);
		$pdf->Cell(40,4,$sql5Result['cparId'],'B',0);
		$pdf->Ln(6);

		//----------------- PRODUCT NONCONFORMANCE ---------------------
		$pdf->Cell(2,6,"",'LT',0,'L');
		$pdf->Cell(43,6,"     Product Nonconformance",'TR',0);
		$pdf->Cell(18,6," Attention To:",'T',0);
		$pdf->Cell(52,5,"  ".$sql5Result['cparOwner'],'TB',0);
		$pdf->Cell(28,6," Issue Date:    ",'LT',0);
		// -------------- Ace Sandoval --------------------------
		// -------------- Change for Reprint --------------------
		$pdf->Cell(33,5,date("F d, Y",strtotime($sql5Result['cparIssueDate'])),'TB',0);
		//~ $pdf->Cell(33,5,date("F d, Y"),'TB',0);
		$pdf->Cell(14,6,"",'TR',0);
		$pdf->Ln();

		//----------------- SYSTEM NONCONFORMANCE ---------------------
		$pdf->Cell(2,6,"",'L',0,'L');
		$pdf->Cell(43,6,"     System Nonconformance",'R',0);
		$pdf->Cell(18,6," Section:",0,0);
		$pdf->Cell(52,5,$sql5Result['cparSection'],'RB',0);
		$pdf->Cell(28,6," Reply Due Date:    ",'L',0);

		// ---------------------- Ace Sandoval ------------------------------
		if($sql5Result['cparDueDate']=="0000-00-00")
		{
		$outputDate = "";
		}
		else
		{
		$outputDate = date('F d, Y',strtotime($sql5Result['cparDueDate']));
		}

		$pdf->Cell(33,5,$outputDate,'B',0);
		$pdf->Cell(14,6,"",'R',0);
		$pdf->Ln();

		//----------------- EMPTY BOX ---------------------
		$pdf->Cell(190,1.2,"",1,0,'L');
		$pdf->Ln();

		//----------------- SOURCE OF INFORMATION ---------------------
		$pdf->Cell(33,4.5,"Source of Information:",'LT',0,'L');
		$pdf->Cell(46,6,"       SUPPLIER/SUB-CON",0,0);
		$pdf->Cell(18,6,"Del. Date:",0,0);
		$pdf->Cell(22,4.5,"",'B',0);
		$pdf->Cell(3.2,4.5,"",0,0);
		$pdf->Cell(67.8,4.5,"To be filled-up by issuing party.",1,0,'C');
		$pdf->Ln();

		//----------------- SOURCE OF INFORMATION 1 ---------------------
		$pdf->Cell(33,4.5,"",'L',0,'L');
		$pdf->Cell(23,6,"       INTERNAL",0,0);
		$pdf->SetFont('SJIS','',7.2);
		$pdf->Cell(66.2,4.5,"( In-process / Outgoing Inspection / Incoming Inspection )",0,0);
		$pdf->SetFont('SJIS','',9);
		$pdf->Cell(20.4,4.5,"Prepared By: ",1,0,'L');

		// ---------------------------- Ace Sandoval
		if($_SESSION['userID']=="jenelyn")
		{
			$userName = "Jenelyn Garganta";
		}
		else if($_SESSION['userID']=="arlyn")
			{
		$userName = "Arlyn Silva";
		}
		else if($sql5Result['cparMaker'] != '')
		{
			$userName = $sql5Result['cparMaker'];
		}
		else
		{
			$userName = $_SESSION['userID'];
		}

		$pdf->Cell(47.4,4.5,$userName,'TRB',0,'L');
		$pdf->Ln();

		//----------------- SOURCE OF INFORMATION 2 ---------------------
		$pdf->Cell(33,4.5,"",'L',0,'L');
		$pdf->Cell(37,6,"       CUSTOMER CLAIM",0,0);
		$pdf->Cell(52.2,6,"",0,0);
		$pdf->Cell(20.4,4.5,"Checked By: ",1,0,'L');
		$pdf->Cell(47.4,4.5,"Ariel Yason",'TRB',0,'L');
		$pdf->Ln();

		//----------------- SOURCE OF INFORMATION 3 ---------------------
		$pdf->Cell(33,5.5,"",'L',0,'L');
		$pdf->Cell(37,6,"       INTERNAL AUDIT",0,0);
		$pdf->Cell(52.2,6,"",0,0);
		$pdf->Cell(20.4,4.5,"Approved By: ",1,0,'L');
		$pdf->Cell(47.4,4.5,"Kazumasa Nagano",'TRB',0,'L');
		$pdf->Ln();

		//----------------- SOURCE OF INFORMATION 4 ---------------------
		$pdf->Cell(33,4.5,"",'L',0,'L');
		$pdf->Cell(37,6,"       OTHERS",0,0);
		$pdf->Cell(52.2,6,"",0,0);
		$pdf->Cell(20.4,4.5,"",1,0,'L');
		$pdf->Cell(47.4,4.5,"",'TRB',0,'L');
		$pdf->Ln();

		//----------------- EMPTY BOX ---------------------
		$pdf->Cell(190,1.2,"",1,0,'L');
		$pdf->Ln();

		//----------------- DESCRIPTION OF THE PROBLEM ---------------------
		$pdf->Cell(71.5,5,"Description of the Problem:",'L',0,'L');
		$pdf->Cell(50.7,5,"",'LR',0);
		$pdf->Cell(67.8,5,"",'LR',0);
		$pdf->Ln(4.5);

		//----------------- CUSTOMER ---------------------
		$pdf->Cell(33.5,4.5,"CUSTOMER:",'L',0,'L');
		if(strlen($customerAlias)>25){
			$pdf->SetFont('SJIS','',6);
		}else{
			$pdf->SetFont('SJIS','',9);
		}

		if($poListQueryResult['customerId']!="")
		{
			$pdf->Cell(38,4.5,$customerAlias,'RB',0,'L');
		}
		else if($sql5Result['cparInfoSourceSubcon'] == 'METAL WEB')
		{
			$pdf->Cell(38,4.5,'B/E AEROSPACE','RB',0,'L');
		}
		else
		{
			$pdf->Cell(38,4.5,$lotNumberArray[$a],'RB',0,'L');
		}

		$pdf->SetFont('SJIS','',9);
		$pdf->Cell(3,4.5,"",0,0,'L');
		$pdf->SetFont('SJIS', 'BU');
		$pdf->Cell(47.7,4.5,"Disposition:",0,0,'L');
		$pdf->SetFont('SJIS', '');
		$pdf->Cell(33.3,4.5,"Process cause of N.G.:",'L',0,'L');
		$pdf->Cell(34.5,4.5,$sql5Result['cparCause'],'RB',0,'L');
		$pdf->Ln(4.5);

		//----------------- SUPPLIER/SUBCON ---------------------
		$pdf->Cell(33.5,4.5,"SUPPLIER/SUBCON:",'L',0,'L');
		if(strlen($sql5Result['cparInfoSourceSubcon'])>20){
			$pdf->SetFont('SJIS','',7);
		}else{
			$pdf->SetFont('SJIS','',9);
		}
		$pdf->Cell(38,4.5,$sql5Result['cparInfoSourceSubcon'],'RB',0,'L');
		$pdf->SetFont('SJIS','',9);
		$pdf->Cell(50.7,4.5,"",0,0,'L');
		$pdf->Cell(33.3,4.5,"Concerned Person:",'L',0,'L');
		if(strlen($sql5Result['cparSourcePerson'])>20){
			$pdf->SetFont('SJIS','',7);
		}else{
			$pdf->SetFont('SJIS','',9);
		}
		$pdf->Cell(34.5,4.5,$sql5Result['cparSourcePerson'],'RB',0,'L');
		$pdf->SetFont('SJIS','',9);
		$pdf->Ln(4.5);

		//----------------- PART NAME ---------------------
		$pdf->Cell(33.5,4.5,"PART NAME:",'L',0,'L');

		// ------------------ Ace Sandoval ----------------------
		//$pdf->Cell(38,4.5,$partListQueryResult['partName'],'RB',0,'L');
		$pdf->AutoFitCell(38,4.5,'SJIS','',9,$partName,'RB',0,'L');
		$pdf->SetFont('SJIS','',9);
		$pdf->Cell(50.7,4.5,"",0,0,'L');
		$pdf->Cell(67.8,4.5,"",'LR',0);
		$pdf->Ln();

		//----------------- PART NUMBER ---------------------
		$pdf->Cell(33.5,4.5,"PART NUMBER:",'L',0,'L');
		$pdf->Cell(38,4.5,$partNumber,'RB',0,'L');
		$pdf->Cell(50.7,4.5,"         Sort / Re-inspect",0,0,'L');
		$pdf->Cell(33.3,4.5,"Detecting Process:",'L',0,'L');
		$pdf->Cell(34.5,4.5,$sql5Result['cparDetectProcess'],'RB',0,'L');
		$pdf->Ln();

		//----------------- PO # / DR # ---------------------
		$pdf->Cell(33.5,4.5,"PO # / DR #:",'L',0,'L');
		$pdf->Cell(38,4.5,$poListQueryResult['poNumber'],'RB',0,'L');
		$pdf->Cell(50.7,4.5,"         Rework",0,0,'L');
		$pdf->Cell(33.3,4.5,"Detecting Person:",'L',0,'L');
		$pdf->Cell(34.5,4.5,$sql5Result['cparDetectPerson'],'RB',0,'L');
		$pdf->Ln();

		//----------------- LOT NO. / LOT QTY. ---------------------
		$pdf->Cell(33.5,4.5,"LOT No. / Lot Qty:",'L',0,'L');
		//$pdf->Cell(38,4.5,$lotNumberArray[$a]. " / " .($lotListQueryResult['workingQuantity']+$sql5Result['cparQuantity']),'RB',0,'L');
		$pdf->Cell(38,4.5,$lotNumberArray[$a]. " / " .($lotListQueryResult['workingQuantity']),'RB',0,'L');
		$pdf->Cell(50.7,4.5,"         Scrap/Disposal/Replacement",0,0,'L');
		$pdf->Cell(33.3,4.5,"Date Detected:",'L',0,'L');

		// ---------------------- Ace Sandoval ------------------------------
		if($sql5Result['cparDetectDate']=="0000-00-00")
		{
		$outputDate = "";
		}
		else
		{
		$outputDate = date('F d, Y',strtotime($sql5Result['cparDetectDate']));
		}

		$pdf->Cell(34.5,4.5,$outputDate,'RB',0,'L');
		$pdf->Ln();

		//----------------- NG QTY. ---------------------
		$pdf->Cell(33.5,4.5,"NG QTY.:",'L',0,'L');
		$pdf->Cell(38,4.5,$quantityArray[$a],'RB',0,'L');
		$pdf->Cell(50.7,4.5,"         Return to Supplier/SubCon",0,0,'L');
		$pdf->Cell(67.8,4.5,"",'LR',0);
		$pdf->Ln();

		//----------------- DETAILS OF NON-CONFORMANCE ---------------------
		$price = em($sql5Result['cparItemPrice']); $price = urldecode($price);
		$pdf->Cell(33.5,4.5,"DETAILS OF NON-CONFORMANCE:",'L',0,'L');
		$pdf->Cell(38,4.5,"",'R',0,'L');
		$pdf->Cell(50.7,4.5,"         Special Acceptance",0,0,'L');
		$pdf->Cell(33.3,4.5,"Item Price:",'L',0,'L');
		$pdf->Cell(34.5,4.5,$price,'RB',0,'L');
		//$pdf->Cell(34.5,4.5,number_format($sql5Result['cparItemPrice'],4,'.',','),'RB',0,'L');
		$pdf->SetFont('SJIS', '', 9);
		$pdf->Ln();

		//----------------- OTHERS ---------------------
		$currentY = $pdf->GetY();
		$messages = strlen($sql5Result['cparDetails']);
		if($messages>145)
		{
			$pdf->SetFont('SJIS', '',5.5);
		}
		else
		{
			$pdf->SetFont('SJIS', '',9);
		}
		// ------------------ Ace Sandoval -----------------
		$pdf->MultiCell(71.5,2.5,$sql5Result['cparDetails'],'LR');
		$pdf->SetXY($pdf->GetX()+71.5,$currentY);
		$pdf->SetFont('SJIS', '',9);
		$pdf->Cell(20,4.5,"         Others:",0,0,'L');
		$pdf->Cell(24,4.5,"",0,0,'L');
		$pdf->Cell(6.7,4.5,"",0,0,'L');
		$pdf->Cell(67.8,4.5,"",'LRB',0,'L');
		$pdf->Ln();

		//----------------- BLANK 1 ---------------------
		$pdf->Cell(71.5,4.5,"",'LR',0,'L');
		$currentY1 = $pdf->GetY();
		if(strlen($sql5Result['cparDispositionDetails'])>64)
		{
			$pdf->SetFont('SJIS', '',7);
		}
		else
		{
			$pdf->SetFont('SJIS', '',9);
		}
		$pdf->MultiCell(50.7,4.5,$sql5Result['cparDispositionDetails'],0);
		$pdf->SetXY($pdf->GetX()+122.2,$currentY1);
		$pdf->SetFont('SJIS', '',9);
		$pdf->Cell(67.8,4.5,"",'LR',0,'L');
		$pdf->Ln();

		//----------------- BLANK 3 ---------------------
		$pdf->Cell(71.5,4.5,"",'LR',0,'L');
		$pdf->Cell(50.7,4.5,"",'R',0);
		$pdf->SetFont('SJIS','',6);
		$pdf->Cell(35,4.5,"         NEED CORRECTIVE ACTION",0,0);
		$pdf->Cell(32.8,4.5,"                INFORMATION ONLY",'R',0);
		$pdf->SetFont('SJIS','',9);
		$pdf->Ln();
		//----------------- EMPTY BOX ---------------------
		$pdf->Cell(190,1.2,"",1,0,'L');
		$pdf->Ln();

		//----------------- INTERIM ACTION ---------------------
		$pdf->SetFont('SJIS','B');
		$pdf->Cell(71.5,4.5,"INTERIM ACTION:",'LR',0,'L');
		$pdf->Cell(44,4.5,"   4M ANALYSIS:",'LR',0,'L');
		$pdf->Cell(6,4.5,"",0,0);
		$pdf->SetFont('SJIS','');
		$pdf->SetFont('SJIS','BU');
		$pdf->Cell(45,4.5,"Recovery Schedule:",'R',0,'L');
		$pdf->SetFont('SJIS','');
		$pdf->Cell(23.5,4.5,"In-Charge",'RB',0,'C');
		$pdf->Ln();

		//----------------- INTERIM ACTION BLANK 1 ---------------------
		$currentY2 = $pdf->GetY();
		$currentY3 = $pdf->GetY();
		$currentY4 = $pdf->GetY();
		$currentY5 = $pdf->GetY();
		$currentY6 = $pdf->GetY();
		$currentY7 = $pdf->GetY();
		$currentY8 = $pdf->GetY();
		$pdf->SetFont('SJIS', '', 10);
		$pdf->MultiCell(71.5,4.5,characterReplace($sql5Result['cparInterimAction']),'LR');
		$pdf->SetXY($pdf->GetX()+71.5,$currentY2);
		$pdf->SetFont('SJIS','',7);
		$pdf->Cell(44,4.5,"             MAN",0,0);
		$pdf->Cell(27,4.5,"Return Date:",'L',0);

		// ---------------------- Ace Sandoval ------------------------------
		if($sql5Result['cparReturnDate']=="0000-00-00")
		{
		$outputDate = "";
		}
		else
		{
		$outputDate = date('F d, Y',strtotime($sql5Result['cparReturnDate']));
		}
		$pdf->Cell(24,4.5,$outputDate,'RB',0);
		$pdf->Cell(23.5,4.5,"",'R',0);
		$pdf->Ln();

		//----------------- INTERIM ACTION BLANK 2 ---------------------
		$pdf->SetXY($pdf->GetX()+71.5,$currentY3+4.5);
		$pdf->Cell(44,4.5,"",'L',0);
		$pdf->Cell(27,4.5,"PRS #:",'L',0);
		$pdf->Cell(24,4.5,$sql5Result['cparPRSNumber'],'RB',0);
		$pdf->Cell(23.5,4.5,"",'R',0);
		$pdf->Ln();

		//----------------- INTERIM ACTION BLANK 3 ---------------------
		$pdf->SetXY($pdf->GetX()+71.5,$currentY4+9);
		$pdf->Cell(44,4.5,"             MACHINE",'L',0);
		$pdf->Cell(27,4.5,"Material Specs.:",'L',0);
		//~ $pdf->Cell(24,4.5,$result2['metalType']." t".$result2['metalThickness'],'RB',0);//;cadcam_materialspecs;
		$pdf->Cell(24,4.5,$materialType." t".$metalThickness,'RB',0);
		$pdf->Cell(23.5,4.5,"",'R',0);
		$pdf->Ln();

		//----------------- INTERIM ACTION BLANK 4 ---------------------
		$pdf->SetXY($pdf->GetX()+71.5,$currentY5+13.5);
		$pdf->Cell(44,4.5,"",'L',0);
		$pdf->Cell(27,4.5,"Prodn. Sched.:",'L',0);

		// ---------------------- Ace Sandoval ------------------------------
		if($sql5Result['cparProductionSchedule']=="0000-00-00")
		{
		$outputDate = "";
		}
		else
		{
		$outputDate = date('F d, Y',strtotime($sql5Result['cparProductionSchedule']));
		}

		$pdf->Cell(24,4.5,$outputDate,'RB',0);
		if(strlen($sql5Result['cparRecoveryIncharge'])>19){
			$pdf->SetFont('SJIS','',6);
		}else{
			$pdf->SetFont('SJIS','',7);
		}
		$pdf->Cell(23.5,4.5,$sql5Result['cparRecoveryIncharge'],'R',0,'C');
		$pdf->SetFont('SJIS','',7);
		$pdf->Ln();

		//----------------- INTERIM ACTION BLANK 5 ---------------------
		//$pdf->Cell(71.5,4.5,"",'LR',0,'L');
		$pdf->SetXY($pdf->GetX()+71.5,$currentY6+18);
		$pdf->Cell(44,4.5,"             METHOD/MEASUREMENT",'L',0);
		$pdf->Cell(27,4.5,"Deliv. To Subcon/Supp:",'L',0);

		// ---------------------- Ace Sandoval ------------------------------
		if($sql5Result['cparSubconSchedule']=="0000-00-00")
		{
		$outputDate = "";
		}
		else
		{
		$outputDate = date('F d, Y',strtotime($sql5Result['cparSubconSchedule']));
		}

		$pdf->Cell(24,4.5,$outputDate,'RB',0);
		$pdf->Cell(23.5,4.5,"",'R',0);
		$pdf->Ln();

		//----------------- INTERIM ACTION BLANK 6 ---------------------
		//$pdf->Cell(71.5,4.5,"",'LR',0,'L');
		$pdf->SetXY($pdf->GetX()+71.5,$currentY7+22.5);
		$pdf->Cell(44,4.5,"",'L',0);
		$pdf->Cell(27,4.5,"Delivery To Customer:",'L',0);

		// ---------------------- Ace Sandoval ------------------------------
		if($sql5Result['cparCustomerSchedule']=="0000-00-00")
		{
		$outputDate = "";
		}
		else
		{
		$outputDate = date('F d, Y',strtotime($sql5Result['cparCustomerSchedule']));
		}


		$pdf->Cell(24,4.5,$outputDate,'RB',0);
		$pdf->Cell(23.5,4.5,"",'R',0);
		$pdf->Ln();

		//----------------- INTERIM ACTION BLANK 7 ---------------------
		//$pdf->Cell(71.5,4.5,"",'LRB',0,'L');
		$pdf->SetXY($pdf->GetX()+71.5,$currentY8+27);
		$pdf->Cell(44,4.5,"             MATERIAL",'LB',0);
		$pdf->Cell(27,4.5,"",'LB',0);
		$pdf->Cell(24,4.5,"",'B',0);
		$pdf->Cell(23.5,4.5,"",'LRB',0);
		$pdf->SetFont('SJIS','',9);
		$pdf->Ln();

		//----------------- EMPTY BOX ---------------------
		$pdf->Cell(190,1.2,"",1,0,'L');
		$pdf->Ln();

		//----------------- CAUSE OF DEFECT ---------------------
		$pdf->SetFont('SJIS','B');
		$pdf->Cell(190,4.5,"CAUSE OF DEFECT:",1,0,'L');
		$pdf->SetFont('SJIS','');
		$pdf->Ln();

		//----------------- PROCESS CAUSE ---------------------
		$pdf->SetFont('SJIS','I');
		$pdf->Cell(71.5,4.5,"PROCESS CAUSE:",'LR',0,'L');
		$pdf->Cell(118.5,4.5,"FLOW-OUT CAUSE:",'LR',0,'L');
		$pdf->SetFont('SJIS','');
		$pdf->Ln();

		//----------------- PROCESS CAUSE BLANK 1 ---------------------
		$currentY9 = $pdf->GetY();
		$currentY10 = $pdf->GetY();
		$pdf->MultiCell(71.5,4.5,characterReplace($sql5Result['cparCauseProcess']),'LR');
		$pdf->SetXY($pdf->GetX()+71.5,$currentY9);
		$pdf->MultiCell(118.5,4.5,characterReplace($sql5Result['cparCauseFlowOut']),'LR');
		$pdf->SetXY($pdf->GetX()+118.5,$currentY10);
		$pdf->Ln();

		//----------------- PROCESS CAUSE BLANK 2 ---------------------
		$pdf->Cell(71.5,4.5,"",'LR',0,'L');
		$pdf->Cell(118.5,4.5,"",'LR',0,'L');
		$pdf->Ln();

		//----------------- PROCESS CAUSE BLANK 3 ---------------------
		$pdf->Cell(71.5,4.5,"",'LR',0,'L');
		$pdf->Cell(118.5,4.5,"",'LR',0,'L');
		$pdf->Ln();

		//----------------- PROCESS CAUSE BLANK 2 ---------------------
		$pdf->Cell(71.5,4.5,"",'LR',0,'L');
		$pdf->Cell(118.5,4.5,"",'LR',0,'L');
		$pdf->Ln();

		//----------------- PROCESS CAUSE BLANK 2 ---------------------
		$pdf->Cell(71.5,4.5,"",'LR',0,'L');
		$pdf->Cell(118.5,4.5,"",'LR',0,'L');
		$pdf->Ln();

		//----------------- PROCESS CAUSE BLANK 2 ---------------------
		$pdf->Cell(71.5,4.5,"",'LR',0,'L');
		$pdf->Cell(118.5,4.5,"",'LR',0,'L');
		$pdf->Ln();

		//----------------- PROCESS CAUSE BLANK 4 ---------------------
		$pdf->Cell(71.5,4.5,"",'LRB',0,'L');
		$pdf->Cell(118.5,4.5,"",'LRB',0,'L');
		$pdf->Ln();

		//----------------- EMPTY BOX ---------------------
		$pdf->Cell(190,1.2,"",1,0,'L');
		$pdf->Ln();

		//----------------- CORRECTIVE ACTION ---------------------
		$pdf->SetFont('SJIS','B');
		$pdf->Cell(190,4.5,"CORRECTIVE ACTION:",1,0,'L');
		$pdf->SetFont('SJIS','');
		$pdf->Ln();

		//----------------- PROCESS ---------------------
		$pdf->SetFont('SJIS','I');
		$pdf->Cell(71.5,4.5,"PROCESS:",'LR',0,'L');
		$pdf->Cell(118.5,4.5,"FLOW-OUT:",'LR',0,'L');
		$pdf->SetFont('SJIS','');
		$pdf->Ln();

		//----------------- PROCESS BLANK 1 ---------------------
		$currentY11 = $pdf->GetY();
		$currentY12 = $pdf->GetY();
		$pdf->MultiCell(71.5,4.5,characterReplace($sql5Result['cparCorrectiveProcess']),'LR');
		$pdf->SetXY($pdf->GetX()+71.5,$currentY11);
		$pdf->MultiCell(118.5,4.5,characterReplace($sql5Result['cparCorrectiveFlowOut']),'LR');
		$pdf->SetXY($pdf->GetX()+118.5,$currentY12);
		$pdf->Ln();

		//----------------- PROCESS BLANK 2 ---------------------
		$pdf->Cell(71.5,4.5,"",'LR',0,'L');
		$pdf->Cell(118.5,4.5,"",'LR',0,'L');
		$pdf->Ln();

		//----------------- PROCESS BLANK 3 ---------------------
		$pdf->Cell(71.5,4.5,"",'LR',0,'L');
		$pdf->Cell(118.5,4.5,"",'LR',0,'L');
		$pdf->Ln();

		//----------------- PROCESS BLANK 3 ---------------------
		$pdf->Cell(71.5,4.5,"",'LR',0,'L');
		$pdf->Cell(118.5,4.5,"",'LR',0,'L');
		$pdf->Ln();

		//----------------- PROCESS BLANK 4 ---------------------
		$pdf->Cell(71.5,4.5,"",'LR',0,'L');
		$pdf->Cell(118.5,4.5,"",'LR',0,'L');
		$pdf->Ln();

		//----------------- PROCESS BLANK 5 ---------------------
		$pdf->SetFont('SJIS','',7);
		$pdf->Cell(24.5,4.5,"Implementation Date:",'L',0,'L');

		// ---------------------- Ace Sandoval ------------------------------
		if($sql5Result['cparCorrectiveProcessDate']=="0000-00-00")
		{
		$outputDate = "";
		}
		else
		{
		$outputDate = date('F d, Y',strtotime($sql5Result['cparCorrectiveProcessDate']));
		}

		if(strlen($outputDate)>14)
		{
			$pdf->SetFont('SJIS','',6);
		}else{
			$pdf->SetFont('SJIS','',7);
		}
		$pdf->Cell(16.5,4,$outputDate,'B',0,'L');
		$pdf->SetFont('SJIS','',7);
		$pdf->Cell(13.3,4.5,"   Incharge:",0,0,'L');
		if(strlen($sql5Result['cparCorrectiveProcessIncharge'])>12){
			$pdf->SetFont('SJIS','',6);
		}else{
			$pdf->SetFont('SJIS','',7);
		}
		$pdf->Cell(16,4,$sql5Result['cparCorrectiveProcessIncharge'],'B',0,'L');
		$pdf->SetFont('SJIS','',7);
		$pdf->Cell(1.2,4.5,"",0,'L');
		$pdf->Cell(24.5,4.5,"Implementation Date:",'L',0,'L');

		// ---------------------- Ace Sandoval ------------------------------
		if($sql5Result['cparCorrectiveFlowOutDate']=="0000-00-00")
		{
		$outputDate = "";
		}
		else
		{
		$outputDate = date('F d, Y',strtotime($sql5Result['cparCorrectiveFlowOutDate']));
		}

		$pdf->Cell(35,4,$outputDate,'B',0,'L');
		$pdf->Cell(18.8,4.5,"           Incharge:",0,0,'L');
		$pdf->Cell(35,4,$sql5Result['cparCorrectiveFlowOutIncharge'],'B',0,'L');
		$pdf->Cell(5.2,4.5,"",'R',0,'L');
		$pdf->SetFont('SJIS','',9);
		$pdf->Ln();

		//----------------- EMPTY BOX ---------------------
		$pdf->Cell(190,1.2,"",1,0,'L');
		$pdf->Ln();

		//----------------- PREVENTIVE ACTION ---------------------
		$pdf->SetFont('SJIS','B');
		$pdf->Cell(100,4.5,"PREVENTIVE ACTION:",1,0,'L');
		$pdf->SetFont('SJIS','');
		$pdf->Cell(58,4.5,"In-Charge",'RB',0,'C');
		$pdf->Cell(32,4.5,"Implementation Date",'RB',0,'C');
		$pdf->Ln();




		//----------------- PREVENTIVE ACTION BLANK 1 ---------------------
		$currentY13 = $pdf->GetY();
		$pdf->MultiCell(100,4.5,characterReplace($sql5Result['cparPreventiveAction']),'LR');
		$pdf->SetXY($pdf->GetX()+100,$currentY13);
		$pdf->Cell(58,4.5,"",'R',0,'C');
		$pdf->Cell(32,4.5,"",'R',0,'C');
		$pdf->Ln();

		//----------------- PREVENTIVE ACTION BLANK 2 ---------------------
		$pdf->Cell(100,4.5,"",'LR',0,'L');
		$pdf->Cell(58,4.5,"",'R',0,'C');
		$pdf->Cell(32,4.5,"",'R',0,'C');
		$pdf->Ln();

		//----------------- PREVENTIVE ACTION BLANK 3 ---------------------
		$pdf->Cell(100,4.5,"",'LR',0,'L');
		$pdf->Cell(58,4.5,"",'R',0,'C');
		$pdf->Cell(32,4.5,"",'R',0,'C');
		$pdf->Ln();

		//----------------- PREVENTIVE ACTION BLANK 3 ---------------------
		$pdf->Cell(100,4.5,"",'LR',0,'L');
		$pdf->Cell(58,4.5,$sql5Result['cparPreventiveActionIncharge'],'R',0,'C');

		// ---------------------- Ace Sandoval ------------------------------
		if($sql5Result['cparPreventiveActionDate']=="0000-00-00")
		{
		$outputDate = "";
		}
		else
		{
		$outputDate = date('F d, Y',strtotime($sql5Result['cparPreventiveActionDate']));
		}



		$pdf->Cell(32,4.5,$outputDate,'R',0,'C');
		$pdf->Ln();

		//----------------- PREVENTIVE ACTION BLANK 3 ---------------------
		$pdf->Cell(100,4.5,"",'LR',0,'L');
		$pdf->Cell(58,4.5,"",'R',0,'C');
		$pdf->Cell(32,4.5,"",'R',0,'C');
		$pdf->Ln();

		//----------------- PREVENTIVE ACTION BLANK 4 ---------------------
		$pdf->Cell(100,4.5,"",'LR',0,'L');
		$pdf->Cell(58,4.5,"",'R',0,'C');
		$pdf->Cell(32,4.5,"",'R',0,'C');
		$pdf->Ln();

		//----------------- PREVENTIVE ACTION BLANK 5 ---------------------
		$pdf->Cell(100,4.5,"",'LRB',0,'L');
		$pdf->Cell(58,4.5,"",'RB',0,'C');
		$pdf->Cell(32,4.5,"",'RB',0,'C');
		$pdf->Ln();

		//----------------- EMPTY BOX ---------------------
		$pdf->Cell(190,1.2,"",1,0,'L');
		$pdf->Ln();

		//----------------- VERIFICATION OF EFFECTIVENESS ---------------------
		$pdf->SetFont('SJIS','B');
		$pdf->Cell(43,4.5,"Verification of Effectiveness",'L',0,'L');
		$pdf->SetFont('SJIS','');
		$pdf->SetFont('SJIS','I');
		$pdf->Cell(57,4.5,"(1 month monitoring)",'R',0,'L');
		$pdf->SetFont('SJIS','');
		$pdf->Cell(58,4.5,"Verified by:",'RB',0,'C');
		$pdf->Cell(32,4.5,"Status",'RB',0,'C');
		$pdf->Ln();

		//----------------- VERIFICATION OF EFFECTIVENESS BLANK 1 ---------------------
		$currentY14 = $pdf->GetY();
		$pdf->MultiCell(100,4.5,characterReplace($sql5Result['cparVerification']),'LR');
		$pdf->SetXY($pdf->GetX()+100,$currentY14);
		$pdf->Cell(58,4.5,"",'L',0,'C');
		$pdf->Cell(32,4.5,"",'LR',0,'C');
		$pdf->Ln();

		//----------------- VERIFICATION OF EFFECTIVENESS BLANK 2 ---------------------
		$pdf->Cell(100,4.5,"",'L',0,'L');
		$pdf->Cell(58,4.5,$sql5Result['cparVerificationIncharge'],'L',0,'C');
		$pdf->Cell(32,4.5,"         ISSUED",'LR',0,'L');
		$pdf->Ln();
		//----------------- VERIFICATION OF EFFECTIVENESS BLANK 3 ---------------------
		$pdf->Cell(100,4.5,"",'L',0,'L');
		$pdf->Cell(58,4.5,"",'LB',0,'C');
		$pdf->Cell(32,4.5,"         ANSWERED",'LR',0,'L');
		$pdf->Ln(4.5);

		//----------------- VERIFICATION OF EFFECTIVENESS BLANK 5 ---------------------
		$pdf->Cell(100,4.5,"",'L',0,'L');
		
		// ---------------------- Ace Sandoval ------------------------------
		if($sql5Result['cparVerificationDate']=="0000-00-00")
		{
			$outputDate = "";
		}
		else
		{
			$outputDate = date('F d, Y',strtotime($sql5Result['cparVerificationDate']));
		}


		$pdf->Cell(58,4.5,"Date: " .$outputDate,'L',0,'L');
		$pdf->Cell(32,4.5,"         VERIFIED",'LR',0,'L');
		$pdf->Ln(4.5);

		//----------------- EMPTY BOX ---------------------
		$pdf->Cell(190,1.2,"",1,0,'L');
		$pdf->Ln();

		//----------------- PRODUCT NONCONFORMANCE SMALL BOX ---------------------
		if($sql5Result['cparType'] == "Product Nonconformance")
		{
		$pdf->SetXY(14,21.2);
		$pdf->Cell(4,3,"",1,0,'',true);
		$pdf->SetFillColor(0,0,0);

		$pdf->SetXY(14,27.2);
		$pdf->Cell(4,3,"",1,0);
		}
		else
		//----------------- SYSTEM NONCONFORMANCE SMALL BOX ---------------------
		{
		$pdf->SetXY(14,27.2);
		$pdf->Cell(4,3,"",1,0,'',true);
		$pdf->SetFillColor(0,0,0);

		$pdf->SetXY(14,21.2);
		$pdf->Cell(4,3,"",1,0);
		}	

		if($sql5Result['cparInfoSource'] == "Supplier/Subcon")
			{
			//----------------- SUPPLIER/SUB-CON SMALL BOX ---------------------
			$pdf->SetXY(46,34.3);
			$pdf->Cell(4,3,"",1,0,'',true);
			$pdf->SetFillColor(0,0,0);

			//----------------- INTERNAL SMALL BOX ---------------------
			$pdf->SetXY(46,38.8);
			$pdf->Cell(4,3,"",1,0);

			//----------------- CUSTOMER CLAIM SMALL BOX ---------------------
			$pdf->SetXY(46,43.3);
			$pdf->Cell(4,3,"",1,0);

			//----------------- INTERNAL AUDIT BOX ---------------------
			$pdf->SetXY(46,48);
			$pdf->Cell(4,3,"",1,0);

			//----------------- OTHERS SMALL BOX ---------------------
			$pdf->SetXY(46,52.5);
			$pdf->Cell(4,3,"",1,0);

			$pdf->SetXY(45.5,66);
			$pdf->Cell(20,4.5,$sql5Result['cparInfoSourceSubcon']);
		}
		elseif($sql5Result['cparInfoSource'] == "Internal")
		{
			//----------------- INTERNAL SMALL BOX ---------------------
			$pdf->SetXY(46,38.8);
			$pdf->Cell(4,3,"",1,0,'',true);
			$pdf->SetFillColor(0,0,0);

			//----------------- SUPPLIER/SUB-CON SMALL BOX ---------------------
			$pdf->SetXY(46,34.3);
			$pdf->Cell(4,3,"",1,0);

			//----------------- CUSTOMER CLAIM SMALL BOX ---------------------
			$pdf->SetXY(46,43.3);
			$pdf->Cell(4,3,"",1,0);
			
			//----------------- INTERNAL AUDIT BOX ---------------------
			$pdf->SetXY(46,48);
			$pdf->Cell(4,3,"",1,0);
			
			//----------------- OTHERS SMALL BOX ---------------------
			$pdf->SetXY(46,52.5);
			$pdf->Cell(4,3,"",1,0);
		}
		elseif($sql5Result['cparInfoSource'] == "Customer Claim"){
			//----------------- CUSTOMER CLAIM SMALL BOX ---------------------
			$pdf->SetXY(46,43.3);
			$pdf->Cell(4,3,"",1,0,'',true);
			$pdf->SetFillColor(0,0,0);

			//----------------- SUPPLIER/SUB-CON SMALL BOX ---------------------
			$pdf->SetXY(46,34.3);
			$pdf->Cell(4,3,"",1,0);

			//----------------- INTERNAL SMALL BOX ---------------------
			$pdf->SetXY(46,38.8);
			$pdf->Cell(4,3,"",1,0);
			
			//----------------- INTERNAL AUDIT BOX ---------------------
			$pdf->SetXY(46,48);
			$pdf->Cell(4,3,"",1,0);
			
			//----------------- OTHERS SMALL BOX ---------------------
			$pdf->SetXY(46,52.5);
			$pdf->Cell(4,3,"",1,0);

			$pdf->SetXY(82,42.8);
			$pdf->Cell(10,4.5,$sql5Result['cparInfoSourceRemarks'],0,0);

			$pdf->SetXY(108,33.5);
			
			// ---------------------- Ace Sandoval ------------------------------
			if($result4['issue']=="0000-00-00")
			{
				$outputDate = "";
			}
			else
			{
				$outputDate = date('F d, Y',strtotime($result4['issue']));
			}
			
			
			$pdf->Cell(10,4.5,$outputDate,0,0);
		}
		elseif($sql5Result['cparInfoSource'] == "Internal Audit"){
			//----------------- INTERNAL AUDIT BOX ---------------------
			$pdf->SetXY(46,48);
			$pdf->Cell(4,3,"",1,0,'',true);
			$pdf->SetFillColor(0,0,0);

			//----------------- SUPPLIER/SUB-CON SMALL BOX ---------------------
			$pdf->SetXY(46,34.3);
			$pdf->Cell(4,3,"",1,0);

			//----------------- INTERNAL SMALL BOX ---------------------
			$pdf->SetXY(46,38.8);
			$pdf->Cell(4,3,"",1,0);

			//----------------- CUSTOMER CLAIM SMALL BOX ---------------------
			$pdf->SetXY(46,43.3);
			$pdf->Cell(4,3,"",1,0);

			//----------------- OTHERS SMALL BOX ---------------------
			$pdf->SetXY(46,52.5);
			$pdf->Cell(4,3,"",1,0);
		}
		elseif($sql5Result['cparInfoSource'] == "Others")
		{
			//----------------- OTHERS SMALL BOX ---------------------
			$pdf->SetXY(46,52.5);
			$pdf->Cell(4,3,"",1,0,'',true);
			$pdf->SetFillColor(0,0,0);

			//----------------- SUPPLIER/SUB-CON SMALL BOX ---------------------
			$pdf->SetXY(46,34.3);
			$pdf->Cell(4,3,"",1,0);

			//----------------- INTERNAL SMALL BOX ---------------------
			$pdf->SetXY(46,38.8);
			$pdf->Cell(4,3,"",1,0);

			//----------------- CUSTOMER CLAIM SMALL BOX ---------------------
			$pdf->SetXY(46,43.3);
			$pdf->Cell(4,3,"",1,0);

			//----------------- INTERNAL AUDIT BOX ---------------------
			$pdf->SetXY(46,48);
			$pdf->Cell(4,3,"",1,0);
		}

			//----------------- SORT/RE_INSPECT SMALL BOX ---------------------
			$pdf->SetXY(87.5,75.4);
			$pdf->Cell(4,3,"",1,0);

			//----------------- REWORK SMALL BOX ---------------------
			$pdf->SetXY(87.5,80);
			$pdf->Cell(4,3,"",1,0);

			//----------------- SCRAP/DISPOSAL/REPLACEMENT SMALL BOX ---------------------
			$pdf->SetXY(87.5,84.4);
			$pdf->Cell(4,3,"",1,0);

			//----------------- RETURN TO SUPPLIER/SUBCON SMALL BOX ---------------------
			$pdf->SetXY(87.5,88.9);
			$pdf->Cell(4,3,"",1,0);

			//----------------- SPECIAL ACCEPTANCE SMALL BOX ---------------------
			$pdf->SetXY(87.5,93.4);
			$pdf->Cell(4,3,"",1,0);

			//----------------- OTHERS SMALL BOX ---------------------
			$pdf->SetXY(87.5,98);
			$pdf->Cell(4,3,"",1,0);




		for($j=0; $j<=count($newDisposition); $j++)
		{
			if($newDisposition[$j]=="Sort/Re-inspect"){
				//----------------- SORT/RE_INSPECT SMALL BOX ---------------------
				$pdf->SetXY(87.5,75.4);
				$pdf->Cell(4,3,"",1,0,'',true);
				$pdf->SetFillColor(0,0,0);
			}



			if($newDisposition[$j]=="Rework"){
				//----------------- REWORK SMALL BOX ---------------------
				$pdf->SetXY(87.5,80);
				$pdf->Cell(4,3,"",1,0,'',true);
				$pdf->SetFillColor(0,0,0);
			}



			if($newDisposition[$j]=="Scrap/Disposal/Replacement"){
				//----------------- SCRAP/DISPOSAL/REPLACEMENT SMALL BOX ---------------------
				$pdf->SetXY(87.5,84.4);
				$pdf->Cell(4,3,"",1,0,'',true);
				$pdf->SetFillColor(0,0,0);
			}



			if($newDisposition[$j]=="Return to Supplier/Subcon"){
				//----------------- RETURN TO SUPPLIER/SUBCON SMALL BOX ---------------------
				$pdf->SetXY(87.5,88.9);
				$pdf->Cell(4,3,"",1,0,'',true);
				$pdf->SetFillColor(0,0,0);
			}



			if($newDisposition[$j]=="Special Acceptance"){
				//----------------- SPECIAL ACCEPTANCE SMALL BOX ---------------------
				$pdf->SetXY(87.5,93.4);
				$pdf->Cell(4,3,"",1,0,'',true);
				$pdf->SetFillColor(0,0,0);
			}



			if($newDisposition[$j]=="Others"){
				//----------------- OTHERS SMALL BOX ---------------------
				$pdf->SetXY(87.5,98);
				$pdf->Cell(4,3,"",1,0,'',true);
				$pdf->SetFillColor(0,0,0);
			}
		}


		if($sql5Result['cparAction'] == "Need Corrective Action"){
			//----------------- NEED CORRECTIVE ACTION SMALL BOX ---------------------
			$pdf->SetXY(136,107);
			$pdf->Cell(4,3,"",1,0,'', true);
			$pdf->SetFillColor(0,0,0);

			$pdf->SetXY(175,107);
			$pdf->Cell(4,3,"",1,0);
		}
		else{
			//----------------- INFORMATION ONLY SMALL BOX ---------------------
			$pdf->SetXY(175,107);
			$pdf->Cell(4,3,"",1,0,'',true);
			$pdf->SetFillColor(0,0,0);

			$pdf->SetXY(136,107);
			$pdf->Cell(4,3,"",1,0);
		}

		//----------------- MAN SMALL BOX ---------------------
		$pdf->SetXY(87.5,117.3);
		$pdf->Cell(4,3,"",1,0);

		//----------------- MACHINE SMALL BOX ---------------------
		$pdf->SetXY(87.5,126.2);
		$pdf->Cell(4,3,"",1,0);

		//----------------- METHOD/MEASUREMENT SMALL BOX ---------------------
		$pdf->SetXY(87.5,135.2);
		$pdf->Cell(4,3,"",1,0);

		//----------------- MATERIAL SMALL BOX ---------------------
		$pdf->SetXY(87.5,144.2);
		$pdf->Cell(4,3,"",1,0);

		for($i=0; $i<=count($newAnalysis); $i++)
		{
			if($newAnalysis[$i] == "Man"){
				//----------------- MAN SMALL BOX ---------------------
				$pdf->SetXY(87.5,117.3);
				$pdf->Cell(4,3,"",1,0,'',true);
				$pdf->SetFillColor(0,0,0);		
			}

			if($newAnalysis[$i] == "Machine"){
				//----------------- MACHINE SMALL BOX ---------------------
				$pdf->SetXY(87.5,126.2);
				$pdf->Cell(4,3,"",1,0,'',true);
				$pdf->SetFillColor(0,0,0);		
			}

			if($newAnalysis[$i] == "Method and Measurement"){
				//----------------- METHOD/MEASUREMENT SMALL BOX ---------------------
				$pdf->SetXY(87.5,135.2);
				$pdf->Cell(4,3,"",1,0,'',true);
				$pdf->SetFillColor(0,0,0);
			}

			if($newAnalysis[$i] == "Material"){
				//----------------- MATERIAL SMALL BOX ---------------------
				$pdf->SetXY(87.5,144.2);
				$pdf->Cell(4,3,"",1,0,'',true);
				$pdf->SetFillColor(0,0,0);
			}
		}

		// ---------------------------- Create Box Status -----------------------
		$pdf->SetXY(173,275);
		$pdf->Cell(4,3,"",1,0);
		
		$pdf->SetXY(173,279.5);
		$pdf->Cell(4,3,"",1,0);
		
		$pdf->SetXY(173,284);
		$pdf->Cell(4,3,"",1,0);
		// ---------------------------- End Of Create Box Status -----------------------

		if($sql5Result['cparStatus'] == "Issued")
		{
			//----------------- CLOSED SMALL BOX ---------------------
			$pdf->SetXY(173,275);
			$pdf->Cell(4,3,"",1,0,'',true);		
		}
		else if($sql5Result['cparStatus'] == "Answered")
		{
			//----------------- OPEN SMALL BOX ---------------------
			$pdf->SetXY(173,279.5);
			$pdf->Cell(4,3,"",1,0,'',true);
			$pdf->SetFillColor(0,0,0);	
		}
		else
		{
			$pdf->SetXY(173,284);
			$pdf->Cell(4,3,"",1,0,'',true);
		}

		//----------------- VERTICAL LINE1 ---------------------
		$pdf->SetXY(173,284);
		$pdf->Line(12,150, 12,120); 
		$pdf->Ln(6);

		$pdf->SetFont('SJIS','', 5);
		$pdf->Cell(95,3.5,'QC-001-4',0,0,'L');
		$pdf->SetFont('SJIS','I', 5);
		$pdf->Cell(95,3.5,'Effectivity Date: August 22, 2006',0,0,'R');
	}
}
$pdf->Output();
?>
