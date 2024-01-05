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

//接收参数
$startdate = trim($_GET["startdate"]);
$enddate = trim($_GET["enddate"]);
$disp_ppricenz = trim($_GET["disp_ppricenz"]);
$disp_ppricenof = trim($_GET["disp_ppricenof"]);

/////Set Excel Information===================
//setup文档文本
$sheettitle = $startdate." --- ".$enddate;
$dateStr = date('Y-m-d');
$filename = "新西兰姚竹0211752625拼单订单_".$dateStr.".xls";

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Mica Yao")
							 ->setLastModifiedBy("Mica Yao")
							 ->setTitle("Mica Yao 0211752625 $dateStr")
							 ->setSubject("Order List $dateStr")
							 ->setDescription("Mica Yao (Tel:0211752625) Order List created at $dateStr")
							 ->setKeywords("Mica Yao NZ order")
							 ->setCategory("Mica Yao 0211752625 $dateStr");
/////=========================================


/////Add Excel Data===========================
require_once("../include/config.inc.php");
require_once("../include/mysql.inc.php");
require_once("../include/ccomborderpeer.inc.php");
require_once("../include/ccombologpeer.inc.php");
require_once("../include/corderpeer.inc.php");
require_once("../include/csuborderpeer.inc.php");
require_once("../include/cconsumerpeer.inc.php");
require_once("../include/cproductpeer.inc.php");
require_once("../include/cconstantspeer.inc.php");
	
$db = new cDatabase;
//mysql_query('set names utf8');
$comborderManager = new cComborderPeer;
$comborderCount = 0;
$comborderList = $comborderManager->getComborderlistbyStEdSa($startdate,$enddate);
if($comborderList){
  $comborderCount = sizeof($comborderList);
}

if($comborderCount == 0) exit;

$objPHPExcel->getDefaultStyle()->getFont()->setName('宋体')
                                          ->setSize(12);
$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);        

$objPHPExcel->getActiveSheet(0)->getColumnDimension('A')->setWidth(15);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('B')->setWidth(30);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('C')->setWidth(50);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('D')->setWidth(140);
//$objPHPExcel->getActiveSheet(0)->getColumnDimension('D')->setWidth(60);
//$objPHPExcel->getActiveSheet(0)->getColumnDimension('E')->setWidth(10);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('E')->setWidth(15);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('F')->setWidth(80);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('H')->setWidth(15);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('I')->setWidth(100);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('J')->setWidth(20);
if($disp_ppricenz) $objPHPExcel->getActiveSheet(0)->getColumnDimension('K')->setWidth(15);
if($disp_ppricenof) $objPHPExcel->getActiveSheet(0)->getColumnDimension('L')->setWidth(30);
if($disp_consumer) $objPHPExcel->getActiveSheet(0)->getColumnDimension('M')->setWidth(15);
//if($disp_weight) $objPHPExcel->getActiveSheet(0)->getColumnDimension('K')->setWidth(20);
//if($disp_pfreight) $objPHPExcel->getActiveSheet(0)->getColumnDimension('L')->setWidth(30);
//if($disp_pprice) $objPHPExcel->getActiveSheet(0)->getColumnDimension('M')->setWidth(10);                              

$hCount = 1;

// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$hCount, '拼单单号')
            ->setCellValue('B'.$hCount, '订单识别码')
            ->setCellValue('C'.$hCount, '发货人')
            ->setCellValue('D'.$hCount, '发货明细（格式：拼单客人姓名，产品英文名, 产品中文名, 订货数量）')
            ->setCellValue('E'.$hCount, '收件人姓名')
            ->setCellValue('F'.$hCount, '收件人地址')
            ->setCellValue('G'.$hCount, '收件人电话')
            ->setCellValue('H'.$hCount, '标记')
            ->setCellValue('I'.$hCount, '子订单备注')
            ->setCellValue('J'.$hCount, '订单备注');
if($disp_ppricenz) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$hCount, '进价单价NZ');
if($disp_ppricenof) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$hCount, '产品总价NZ（不包括运费）');
if($disp_consumer) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$hCount, '消费者');

$objStyle = $objPHPExcel->getActiveSheet(0)->getStyle('A'.$hCount.':L'.$hCount);
$objFill = $objStyle->getFill();
$objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
$objFill->getStartColor()->setARGB('FFEEEEEE');

$hArray = array("A$hCount","B$hCount","C$hCount","D$hCount","E$hCount","F$hCount","G$hCount","H$hCount","I$hCount","J$hCount","K$hCount","L$hCount");
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


$orderManager = new cOrderPeer;
$orderCount = 0;
$orderList = $orderManager->getOrderlistbyStEdSa($startdate,$enddate,0,9);
$orderCount = sizeof($orderList);

// iterate through users, show info
  $rowColor = 0;
  $bgcolor = "";
  
  $orderCount = 0;
  $totalPpricenof = 0;
  $totalWeight = 0;
  $totalPfreight = 0;
  $totalPprice = 0;
  $cpfreightFlag = false;
  
  //常量利率
  $constantsManager = new cConstantsPeer;
  $rrate = $constantsManager->getNewConstants(1);
  
  foreach($comborderList as $comborder){
    if( $rowColor%2 == 0 ) {
	  $bgcolor = "FF00EEEE";
    }else{
	  $bgcolor = "#eeeeee";
    }
    
    $comborder = (object)$comborder;
  	$comborderid = $comborder->getComborderid();
  	$name = $comborder->getCname();
  	$memo = $comborder->getMemo();
  	$address = $comborder->getAddress();
  	$mobile = $comborder->getMobile();
  	$logisticsid = $comborder->getLogisticsid();
    $logisticscode = $comborder->getLogisticscode();
  	$Createtime = $comborder->getCreatetime();
  	  
  	$combologManager = new cCombologPeer;
  	$combologCount = 0;
    $combologList = $combologManager->getCombologList($comborderid);
    $combologCount = sizeof($combologList);
    if(!empty($combologList)){
      $orderManager = new cOrderPeer;
      foreach($combologList as $combolog){
  	    $combolog = (object)$combolog;
  	    $combologid = $combolog->getCombologid();
  	    $orderid = $combolog->getOrderid();
  	    $order = $orderManager->getOrder($orderid);
        $type = $order->getCType();
        $weight = $order->getWeight();
        $ordermemo = $order->getMemo();
        $orderIdcode = $order->getIdcode();
        /*$weightKG = $weight/1000;
        if($weightKG > 0 && $weightKG < 0.3){
          $weightKG = 1;
        }else if($weightKG >= 0.3){
          $weightKG = $weightKG + 0.7;
        }*/
        $consumerid = $order->getConsumerid();
  	    $consumerManager = new cConsumerPeer;
  	    $consumer = $consumerManager->getConsumer($consumerid);
  	    $consumerName = $consumer->getCname();
            
        $suborderManager = new cSuborderPeer;
        $suborderCount = $suborderManager->getSuborderCount($orderid);
        if($suborderCount > 0){
          $suborderList = $suborderManager->getSuborderlist($orderid,0,100);
          foreach($suborderList as $suborder){
            $hCount++;
  	        $suborder = (object)$suborder;
  	        $productid = $suborder->getProductid();
  	          
  	        $productManager = new cProductPeer;
	        $product = $productManager->getProduct($productid);
	        $ename = $product->getEname();
            $cname = $product->getCname();
            $ppricenz = $product->getPpricenz();
              $ppricecn = $ppricenz*($rrate-0.1);
            $productweight = $product->getWeight();
            $sellSingleProductNumber = $suborder->getOrdernum();
            $productTotalWeightKG = ($productweight*$sellSingleProductNumber)/1000;
            $productTotalPrice = $ppricenz*$sellSingleProductNumber;
            /*$productTotalPriceCN = $ppricecn*$sellSingleProductNumber;
            if($productTotalWeightKG == 0){
              $productPfreight = 0;
            }else if($productTotalWeightKG < 0.3 && $productTotalWeightKG > 0){
              $productPfreight = 1*7*($rrate-0.1);
            }else{
              $productPfreight = ($productTotalWeightKG+0.7)*7*($rrate-0.1);
            }
            $productPprice = $productTotalPriceCN+$productPfreight;*/
  	  
  	        $objPHPExcel->setActiveSheetIndex(0)
            			->setCellValue('A'.$hCount, $comborderid)
            			->setCellValue('B'.$hCount, $orderIdcode)
            			->setCellValue('C'.$hCount, 'Mica Yao '.$memo)
            			->setCellValue('D'.$hCount, '（'.$consumerName.'） '.$ename.', '.$cname.', *'.$sellSingleProductNumber)
            			->setCellValue('E'.$hCount, $name)
            			->setCellValue('F'.$hCount, $address)
            			->setCellValue('G'.$hCount, $mobile)
            			->setCellValue('H'.$hCount, '新西兰报纸')
            			->setCellValue('I'.$hCount, $ordermemo)
            			->setCellValue('J'.$hCount, $memo);
            if($disp_ppricenz) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$hCount, $ppricenz);
            if($disp_ppricenof) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$hCount, $productTotalPrice);
            if($disp_consumer) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$hCount, $consumerName);
        
            $objStyle = $objPHPExcel->getActiveSheet(0)->getStyle('A'.$hCount.':M'.$hCount);
			$objFill = $objStyle->getFill();     
			$objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
			$objFill->getStartColor()->setARGB($bgcolor);

			$hArray = array("A$hCount","B$hCount","C$hCount","D$hCount","E$hCount","F$hCount","G$hCount","H$hCount","I$hCount","J$hCount","K$hCount","L$hCount","M$hCount");
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
  	      }
        }else{
          continue;
        }
      }
    }
    
    $orderCount++;
    $rowColor++;
  }
  	  
  $hCount++;
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$hCount, '')
              ->setCellValue('B'.$hCount, '')
              ->setCellValue('C'.$hCount, '')
              ->setCellValue('D'.$hCount, '')
              ->setCellValue('E'.$hCount, '')
              ->setCellValue('F'.$hCount, '')
              ->setCellValue('G'.$hCount, '')
              ->setCellValue('H'.$hCount, '')
              ->setCellValue('I'.$hCount, '总计')
              ->setCellValue('J'.$hCount, $orderCount.'单');
   if($disp_ppricenz) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$hCount, '');
   if($disp_ppricenof) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$hCount, '');
   if($disp_consumer) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$hCount, '');
   
$objStyle = $objPHPExcel->getActiveSheet(0)->getStyle('A'.$hCount.':L'.$hCount);
$objFill = $objStyle->getFill();     
$objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
$objFill->getStartColor()->setARGB('FFEEEEEE');

$hArray = array("A$hCount","B$hCount","C$hCount","D$hCount","E$hCount","F$hCount","G$hCount","H$hCount","I$hCount","J$hCount","K$hCount","L$hCount","M$hCount");
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

unset($comborderList);

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