<?php
  require_once("../include/config.inc.php");
  require_once("../include/mysql.inc.php");
  require_once("../include/function.inc.php");
  require_once("../include/user.inc.php");
  CheckCookies();

	//require_once("../include/config.inc.php");
    session_start();
    if ((!session_is_registered("SESS_USERID")) || ($_SESSION['SESS_TYPE'] != ADMIN)){
    	Header("Location:../phpinc/main.php");
		exit();
    }
    
ini_set("memory_limit","512M");


/** Include PHPExcel */
require_once '../phpexcel/PHPExcel.php';

//接收参数
$startdate = trim($_GET["startdate"]);
$enddate = trim($_GET["enddate"]);
$disp_type = trim($_GET["disp_type"]);
$disp_ppricenz = trim($_GET["disp_ppricenz"]);
$disp_ppricenof = trim($_GET["disp_ppricenof"]);
$disp_weight = trim($_GET["disp_weight"]);
$disp_pfreight = trim($_GET["disp_pfreight"]);
$disp_pprice = trim($_GET["disp_pprice"]);

/////Set Excel Information===================
//setup文档文本
$sheettitle = $startdate." --- ".$enddate;
$dateStr = date('Y-m-d');
$filename = "新西兰姚竹0211752625订单_".$dateStr.".xls";

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
require_once("../include/corderpeer.inc.php");
require_once("../include/csuborderpeer.inc.php");
require_once("../include/cconsumerpeer.inc.php");
require_once("../include/cproductpeer.inc.php");
require_once("../include/cconstantspeer.inc.php");
	
$db = new cDatabase;
$orderManager = new cOrderPeer;
$orderCount = 0;
$orderList = $orderManager->getOrderlistbyStEdSa($startdate,$enddate,0,9);
$orderCount = sizeof($orderList);

if($orderCount == 0) exit;

$objPHPExcel->getDefaultStyle()->getFont()->setName('宋体')
                                          ->setSize(12);
$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);        

$objPHPExcel->getActiveSheet(0)->getColumnDimension('A')->setWidth(25);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('B')->setWidth(25);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('C')->setWidth(120);
//$objPHPExcel->getActiveSheet(0)->getColumnDimension('D')->setWidth(60);
//$objPHPExcel->getActiveSheet(0)->getColumnDimension('E')->setWidth(10);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('E')->setWidth(80);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('F')->setWidth(15);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('G')->setWidth(15);
if($disp_ppricenz) $objPHPExcel->getActiveSheet(0)->getColumnDimension('H')->setWidth(15);
if($disp_ppricenof) $objPHPExcel->getActiveSheet(0)->getColumnDimension('I')->setWidth(30);
if($disp_weight) $objPHPExcel->getActiveSheet(0)->getColumnDimension('J')->setWidth(20);
if($disp_pfreight) $objPHPExcel->getActiveSheet(0)->getColumnDimension('K')->setWidth(30);
if($disp_pprice) $objPHPExcel->getActiveSheet(0)->getColumnDimension('L')->setWidth(10);
if(!$disp_type) $objPHPExcel->getActiveSheet(0)->getColumnDimension('M')->setWidth(20);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('N')->setWidth(60);
$objPHPExcel->getActiveSheet(0)->getColumnDimension('O')->setWidth(15);                           

$hCount = 1;

// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$hCount, '识别码')
            ->setCellValue('B'.$hCount, '发货人')
            ->setCellValue('C'.$hCount, '发货明细（格式：产品英文名, 产品中文名, 订货数量）')
            ->setCellValue('D'.$hCount, '收件人姓名')
            ->setCellValue('E'.$hCount, '收件人地址')
            ->setCellValue('F'.$hCount, '收件人电话')
            ->setCellValue('G'.$hCount, '标记');
if($disp_ppricenz) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$hCount, '进价单价NZ');
if($disp_ppricenof) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$hCount, '产品总价NZ（不包括运费）');
if($disp_weight) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$hCount, '总重（含包装）');
if($disp_pfreight) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$hCount, '实收运费CN,NZ');
if($disp_pprice) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$hCount, '本单总价');
if(!$disp_type) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$hCount, '订单类型');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$hCount, '订单备注');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$hCount, '付款状态');

$objStyle = $objPHPExcel->getActiveSheet(0)->getStyle('A'.$hCount.':O'.$hCount);
$objFill = $objStyle->getFill();
$objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objFill->getStartColor()->setARGB('FFEEEEEE');

$hArray = array("A$hCount","B$hCount","C$hCount","D$hCount","E$hCount","F$hCount","G$hCount","H$hCount","I$hCount","J$hCount","K$hCount","L$hCount","M$hCount","N$hCount","O$hCount");
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
  
  $orderCount = 0;
  $totalPpricenof = 0;
  $totalWeight = 0;
  $totalPfreight = 0;
  $totalPprice = 0;
  $cpfreightFlag = false;
  
  //常量利率
  $constantsManager = new cConstantsPeer;
  $rrate = $constantsManager->getNewConstants(1);
  $packageZhiWeightG = $constantsManager->getNewConstants(3);//直邮包装重，克
  $packagePinWeightG = $constantsManager->getNewConstants(4);//拼单包装重，克
  
  foreach($orderList as $order){
    if( $rowColor%2 == 0 ) {
	  $bgcolor = "FF00EEEE";
    }else{
	  $bgcolor = "#eeeeee";
    }
  
    $order = (object)$order;
    $orderid = $order->getOrderid();
    $idcode = $order->getIdcode();
    $type = $order->getCType();
  	  if($disp_type == 1 && $type > 1) continue;
    $weight = $order->getWeight();
    $freight = $order->getFreight();
      $freightnz = $freight/$rrate;
    $pfreight = $order->getPfreight();
  	$pprice = $order->getPprice();
  	$memo = $order->getMemo();
  	$paystatus = $order->getPaystatus();
  	if($paystatus == 0){
  	  $paystatusStr = '未付款';
  	  $bgcolor = "#ef5b9c";
  	}else{
  	  $paystatusStr = '已付款';
  	}
  	
  	  if($type == 1){//直邮
        $weight = $weight + $packageZhiWeightG;
        if($weight < 1000) $weight = 1000;
      }else{
        $weight = $weight + $packagePinWeightG;
      }
      $weightKG = $weight/1000;
      /*if($weightKG > 0 && $weightKG < 0.3){
        $weightKG = 1;
      }else if($weightKG >= 0.3){
        $weightKG = $weightKG + 0.7;
      }*/
      $suborderManager = new cSuborderPeer;
      $suborderCount = $suborderManager->getSuborderCount($orderid);
      if($suborderCount > 0){
        $suborderList = $suborderManager->getSuborderlist($orderid,0,100);
        $consumerid = $order->getConsumerid();
  	      $consumerManager = new cConsumerPeer;
  	      $consumer = $consumerManager->getConsumer($consumerid);
  	      $consumerName = $consumer->getCname();
  	      $consumerAddress = $consumer->getAddress();
          $consumerMobile = $consumer->getMobile();
          //代理
  	      $consumerSagentid = $consumer->getAgentid();
  	      if($consumerSagentid > 0){
  	        $user = new user;
            $sagentName = $user->getUserbyId($consumerSagentid)->realname;
            $faHuoRen = "$sagentName, $orderid";
          }else{
            $faHuoRen = "Mica Yao, $orderid";
          }
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
            			->setCellValue('A'.$hCount, "NZ".$idcode)
            			->setCellValue('B'.$hCount, $faHuoRen)
            			->setCellValue('C'.$hCount, $ename.', '.$cname.', *'.$sellSingleProductNumber)
            			->setCellValue('D'.$hCount, $consumerName)
            			->setCellValue('E'.$hCount, $consumerAddress)
            			->setCellValue('F'.$hCount, $consumerMobile)
            			->setCellValue('G'.$hCount, '新西兰报纸');
            if($disp_ppricenz) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$hCount, $ppricenz);
            if($disp_ppricenof) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$hCount, $productTotalPrice);
        	if($disp_weight) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$hCount, $weightKG);
            if($disp_pfreight) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$hCount, $freight.', '.$freightnz);
            if($disp_pprice) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$hCount, $pprice);
            if(!$disp_type){
              if($type == 1){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$hCount, '普通订单');
              }else if($type == 2){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$hCount, '未分配拼单子订单');
              }else if($type == 3){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$hCount, '已分配拼单子订单');
              }else{
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$hCount, '警告！状态未知');
              }
            }
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$hCount, $memo);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$hCount, $paystatusStr);
            $objStyle = $objPHPExcel->getActiveSheet(0)->getStyle('A'.$hCount.':O'.$hCount);
			$objFill = $objStyle->getFill();     
			$objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
			$objFill->getStartColor()->setARGB($bgcolor);

			$hArray = array("A$hCount","B$hCount","C$hCount","D$hCount","E$hCount","F$hCount","G$hCount","H$hCount","I$hCount","J$hCount","K$hCount","L$hCount","M$hCount","N$hCount","O$hCount");
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
    
  	$cpfreight = $order->getCpfreight();
  	  if($cpfreight == 1) $cpfreightFlag = true;
  	$create_time = $order->getCreatetime();
  	
  	$productprice = $pprice - $pfreight;
    
    $orderCount++;
    $totalPpricenof = $totalPpricenof + $productprice;
    $totalWeight = $totalWeight + $weight;
    $totalPfreight = $totalPfreight + $pfreight;
    $totalPprice = $totalPprice + $pprice;
    
    $rowColor++;
  }
  
  if($cpfreightFlag){
      $KStr = "$totalPfreight&nbsp;已扣除运费优惠";
  }else{
      $KStr = "$totalPfreight";
  }
  
  $hCount++;
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$hCount, '')
              ->setCellValue('B'.$hCount, '')
              ->setCellValue('C'.$hCount, '')
              ->setCellValue('D'.$hCount, '')
              ->setCellValue('E'.$hCount, '')
              ->setCellValue('F'.$hCount, '总计')
              ->setCellValue('G'.$hCount, $orderCount.'单');
   if($disp_ppricenz) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$hCount, '');
   if($disp_ppricenof) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$hCount, '');
   if($disp_weight) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$hCount, '');
   if($disp_pfreight) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$hCount, '');
   if($disp_pprice) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$hCount, $totalPprice);
   if(!$disp_type) $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$hCount, '');
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$hCount, '');
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$hCount, '');
   
$objStyle = $objPHPExcel->getActiveSheet(0)->getStyle('A'.$hCount.':O'.$hCount);
$objFill = $objStyle->getFill();     
$objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
$objFill->getStartColor()->setARGB('FFEEEEEE');

$hArray = array("A$hCount","B$hCount","C$hCount","D$hCount","E$hCount","F$hCount","G$hCount","H$hCount","I$hCount","J$hCount","K$hCount","L$hCount","M$hCount","N$hCount","O$hCount");
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
  

// Add some data
/*
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '姚竹')
            ->setCellValue('B2', '代购!')
            ->setCellValue('C1', 'Hello')
            ->setCellValue('D2', 'world!');

// Miscellaneous glyphs, UTF-8
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'Miscellaneous glyphs')
            ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
            
$objStyle = $objPHPExcel->getActiveSheet(0)->getStyle('D2');
$objFill = $objStyle->getFill();     
$objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
$objFill->getStartColor()->setARGB('FF00EEEE');
$objBorder = $objStyle->getBorders();
$objBorder->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
$objBorder->getTop()->getColor()->setARGB('red');//FFDDDDDD
$objBorder->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
$objBorder->getBottom()->getColor()->setARGB('red');  
$objBorder->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
$objBorder->getLeft()->getColor()->setARGB('red'); // color  
$objBorder->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
$objBorder->getRight()->getColor()->setARGB('red'); // color 
            */
            
/////=========================================

unset($orderList);

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