<?php
  require_once("../include/config.inc.php");
  require_once("../include/mysql.inc.php");
  require_once("../include/function.inc.php");
  require_once("../include/user.inc.php");
  CheckCookies();

	//require_once("../include/config.inc.php");
    session_start();
    if ((!isset($_SESSION["SESS_USERID"])) || ($_SESSION['SESS_TYPE'] != ADMIN)){
    	Header("Location:../phpinc/main.php");
		exit();
    }
    
ini_set("memory_limit","1024M");

/** Include PHPExcel */
require_once '../phpexcel/PHPExcel.php';

/////Set Excel Information===================
//setup文档文本
$sheettitle = "新西兰姚竹_CRM_全部货品";
$dateStr = date('Y-m-d');
$filename = "新西兰姚竹_CRM_全部货品.xls";

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Mica Yao")
							 ->setLastModifiedBy("Mica Yao")
							 ->setTitle("Mica Yao Product List $dateStr")
							 ->setSubject("Product List $dateStr")
							 ->setDescription("Mica Yao (Tel:0211752625) Product List created at $dateStr")
							 ->setKeywords("Mica Yao NZ product")
							 ->setCategory("Mica Yao 0211752625 $dateStr");
/////=========================================


/////Add Excel Data===========================
require_once("../include/config.inc.php");
require_once("../include/mysql.inc.php");
require_once("../include/cproductpeer.inc.php");

$db = new cDatabase;
//mysql_query('set names utf8');
$productManager = new cProductPeer;
$productCount = 0;
$productList = $productManager->getProductlist(0,0,3000,"");
$productCount = sizeof($productList);

if($productCount == 0) exit;

$objPHPExcel->getDefaultStyle()->getFont()->setName('宋体')
                                          ->setSize(12);
$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);        

$objPHPExcel->getActiveSheet(0)->getColumnDimension('A')->setWidth(50);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('B')->setWidth(50);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('C')->setWidth(15);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('F')->setWidth(20);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('G')->setWidth(15);                          

$hCount = 1;

// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$hCount, '英文名')
            ->setCellValue('B'.$hCount, '中文名')
            ->setCellValue('C'.$hCount, '规格')
            ->setCellValue('D'.$hCount, '单位')
            ->setCellValue('E'.$hCount, '成本NZ')
            ->setCellValue('F'.$hCount, '售价CN')
            ->setCellValue('G'.$hCount, '重量g');

$objStyle = $objPHPExcel->getActiveSheet(0)->getStyle('A'.$hCount.':G'.$hCount);
$objFill = $objStyle->getFill();
$objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
$objFill->getStartColor()->setARGB('FFEEEEEE');

$hArray = array("A$hCount","B$hCount","C$hCount","D$hCount","E$hCount","F$hCount","G$hCount");
foreach($hArray as $cellValue){
  $objStyle = $objPHPExcel->getActiveSheet(0)->getStyle($cellValue);
  $objBorder = $objStyle->getBorders();
  $objBorder->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
  $objBorder->getTop()->getColor()->setARGB('FF000000');//FFDDDDDD
  $objBorder->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
  $objBorder->getBottom()->getColor()->setARGB('FF000000');  
  $objBorder->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
  $objBorder->getLeft()->getColor()->setARGB('FF000000'); // color  
  $objBorder->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
  $objBorder->getRight()->getColor()->setARGB('FF000000'); // color 
}

// iterate through users, show info
  $rowColor = 0;
  $bgcolor = "";
  
  foreach($productList as $product){
    $product = (object)$product;
  	$productid = $product->getProductid();
  	$ename = $product->getEname();
  	$cname = $product->getCname();
  	$spec = $product->getSpec();
  	$unit = $product->getUnit();
  	$unitnum = $product->getUnitnum();
  	$pprizenz = $product->getPpricenz();
  	$sprizecn = $product->getSpricecn();
  	$weight = $product->getWeight();
  	$supermarket = $product->getSupermarket();
    $createtime = $product->getCreatetime();
    
    switch($spec){
       case 1:
         $specStr = "盒";
         break;
	   case 2:
	     $specStr = "瓶";
         break;
	   case 3:
	     $specStr = "罐";
         break;
	   case 4:
	     $specStr = "箱";
         break;
       case 5:
	     $specStr = "块";
         break;
       case 6:
	     $specStr = "只";
         break;
       case 7:
	     $specStr = "袋";
         break;
	   default:
	     $specStr = "无";
         break;
     }
     switch($unit){
       case 1:
         $unitStr = "克";
         break;
	   case 2:
	     $unitStr = "毫升";
         break;
	   case 3:
	     $unitStr = "粒";
         break;
	   case 4:
	     $unitStr = "天";
         break;
       case 5:
	     $unitStr = "块";
         break;
       case 6:
	     $unitStr = "只";
         break;
       case 7:
	     $unitStr = "袋";
         break;
       case 8:
	     $unitStr = "片";
         break;
	   default:
	     $unitStr = "无";
         break;
     }
  
     if( $rowColor%2 == 0 ) {
	   $bgcolor = "FF00EEEE";
     }else{
	   $bgcolor = "#eeeeee";
     }
     
     $hCount++;
            
	 $objPHPExcel->setActiveSheetIndex(0)
            	 ->setCellValue('A'.$hCount, $ename)
            	 ->setCellValue('B'.$hCount, $cname)
            	 ->setCellValue('C'.$hCount, $specStr)
            	 ->setCellValue('D'.$hCount, $unitStr)
            	 ->setCellValue('E'.$hCount, $pprizenz)
            	 ->setCellValue('F'.$hCount, $sprizecn)
            	 ->setCellValue('G'.$hCount, $weight);
     $objStyle = $objPHPExcel->getActiveSheet(0)->getStyle('A'.$hCount.':G'.$hCount);
	 $objFill = $objStyle->getFill();     
	 $objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
	 $objFill->getStartColor()->setARGB($bgcolor);

	 $hArray = array("A$hCount","B$hCount","C$hCount","D$hCount","E$hCount","F$hCount","G$hCount");
	 foreach($hArray as $cellValue){
  	   $objStyle = $objPHPExcel->getActiveSheet(0)->getStyle($cellValue);
  	   $objBorder = $objStyle->getBorders();
  	   $objBorder->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
  	   $objBorder->getTop()->getColor()->setARGB('FF000000');//FFDDDDDD
  	   $objBorder->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
  	   $objBorder->getBottom()->getColor()->setARGB('FF000000');  
  	   $objBorder->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
  	   $objBorder->getLeft()->getColor()->setARGB('FF000000'); // color  
  	   $objBorder->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
  	   $objBorder->getRight()->getColor()->setARGB('FF000000'); // color 
	 }
    
    $rowColor++;
  }
  
  $hCount++;
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$hCount, '')
              ->setCellValue('B'.$hCount, '')
              ->setCellValue('C'.$hCount, '')
              ->setCellValue('D'.$hCount, '')
              ->setCellValue('E'.$hCount, '')
              ->setCellValue('F'.$hCount, '总计')
              ->setCellValue('G'.$hCount, $productCount.'个产品');
  $objStyle = $objPHPExcel->getActiveSheet(0)->getStyle('A'.$hCount.':G'.$hCount);
  $objFill = $objStyle->getFill();     
  $objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
  $objFill->getStartColor()->setARGB('FFEEEEEE');

$hArray = array("A$hCount","B$hCount","C$hCount","D$hCount","E$hCount","F$hCount","G$hCount");
foreach($hArray as $cellValue){
  $objStyle = $objPHPExcel->getActiveSheet(0)->getStyle($cellValue);
  $objBorder = $objStyle->getBorders();
  $objBorder->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
  $objBorder->getTop()->getColor()->setARGB('FF000000');//FFDDDDDD
  $objBorder->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
  $objBorder->getBottom()->getColor()->setARGB('FF000000');  
  $objBorder->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
  $objBorder->getLeft()->getColor()->setARGB('FF000000'); // color  
  $objBorder->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
  $objBorder->getRight()->getColor()->setARGB('FF000000'); // color 
}

unset($productList);

/////Output Excel=============================
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle("$sheettitle");

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$filename.'');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

unset($objPHPExcel);
unset($objWriter);
exit;

?>