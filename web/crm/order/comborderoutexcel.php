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

	//require_once("../include/mysql.inc.php");
	require_once("../include/ccomborderpeer.inc.php");
	require_once("../include/ccombologpeer.inc.php");
	require_once("../include/corderpeer.inc.php");
	require_once("../include/csuborderpeer.inc.php");
	require_once("../include/cconsumerpeer.inc.php");
	require_once("../include/cproductpeer.inc.php");
	require_once("../include/cconstantspeer.inc.php");
	
  $dosearch = trim(@$_POST["dosearch"]);
  if($dosearch){
    $startdate = trim($_POST["startdate"]);
    $enddate = trim($_POST["enddate"]);
   
    //验证数据 
    $wrongInputFlag = false;
    $wrongInputMessage = false;
    if(strlen($startdate) != 10 || strlen($enddate) != 10){
      $wrongInputFlag = true;
      $wrongInputMessage = "日期输入错误";
    }
    if(!$wrongInputFlag){
      if(substr($startdate,4,1) != "-" || substr($startdate,7,1) != "-" || substr($enddate,4,1) != "-" || substr($enddate,7,1) != "-"){
        $wrongInputFlag = true;
        $wrongInputMessage = "日期连接符'-'输入错误";
      }
    }
    if(!$wrongInputFlag){
      if(substr($startdate,0,4) < 2013 || substr($startdate,0,4) > 2100 || substr($enddate,0,4) < 2013 || substr($enddate,0,4) > 2100){
        $wrongInputFlag = true;
        $wrongInputMessage = "年份输入错误";
      }
    }
    if(!$wrongInputFlag){
      if(substr($startdate,5,2) < 1 || substr($startdate,5,2) > 12 || substr($enddate,5,2) < 1 || substr($enddate,5,2) > 12){
        $wrongInputFlag = true;
        $wrongInputMessage = "月份输入错误";
      }
    }
    if(!$wrongInputFlag){
      if(substr($startdate,8,2) < 1 || substr($startdate,8,2) > 31 || substr($enddate,8,2) < 1 || substr($enddate,8,2) > 31){
        $wrongInputFlag = true;
        $wrongInputMessage = "日期输入错误";
      }
    }
    
    //后台查询
    if(!$wrongInputFlag){
      $db = new cDatabase;
      //mysql_query('set names utf8');
      $comborderManager = new cComborderPeer;
      $comborderCount = 0;
      $comborderList = $comborderManager->getComborderlistbyStEdSa($startdate,$enddate);
      if($comborderList){
        $comborderCount = sizeof($comborderList);
      }
      $disp_ppricenz = trim($_POST["disp_ppricenz"]);
      $disp_ppricenof = trim($_POST["disp_ppricenof"]);
      $disp_consumer = trim($_POST["disp_consumer"]);
    }else{
      $msg = $wrongInputMessage;
    }
  }
?>
<html><head>
<title></title>
<link rel=stylesheet type=text/css href="/style/global.css">
</head>
<BODY BGCOLOR=#ffffff LINK=#000099 ALINK=#cc0000 VLINK=#000099 TOMARGIN=8>
<center>
<?php
  if(@$wrongInputFlag || @$dosearch != 1){
  
  $titlebars = array("拼单管理"=>"comborder.php"); 
  $operations = array("拼单表格"=>"comborderoutexcel.php");
  $jumptarget = "cmsright";
  include("../phpinc/titlebar.php");

  echo "<form name=searchreport method=post action=>
        <input type=hidden name=dosearch value=1>
        <table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>
          <tr class= itm bgcolor='#dddddd'>
            <td>起始日期</td>
            <td><input type=type name=startdate><font color=red><b>输入：2014-01-01，注意“-”是英文的不是中文的</b></font></td>
          </tr>
          <tr class= itm bgcolor='#dddddd'>
            <td>结束日期</td>
            <td><input type=type name=enddate><font color=red><b>输入：2014-02-28，注意“-”是英文的不是中文的</b></font></td>
          </tr>
          <tr class= itm bgcolor='#dddddd'>
            <td>进价单价（纽币）</td>
            <td>
              <input type=radio name=disp_ppricenz value=1>显示
              <input type=radio name=disp_ppricenz value=0 checked>不显示
            </td>
          </tr>
          <tr class= itm bgcolor='#dddddd'>
            <td>产品总价（不包括运费，纽币）</td>
            <td>
              <input type=radio name=disp_ppricenof value=1>显示
              <input type=radio name=disp_ppricenof value=0 checked>不显示
            </td>
          </tr>
           <tr class= itm bgcolor='#dddddd'>
            <td>实际购买者</td>
            <td>
              <input type=radio name=disp_consumer value=1>显示
              <input type=radio name=disp_consumer value=0 checked>不显示
            </td>
          </tr>
          <tr class= itm bgcolor='#dddddd'>
            <td></td>
            <td><input type=submit name=submit value=拼单数据查询></td>
          </tr>
        </table>
        </form>";
        
  }
        
  if(!empty($msg)) {
    echo "<span class=cur>$msg</span>";
  }
  
  if(!@$wrongInputFlag && @$dosearch == 1){

  if($comborderCount == 0){
    $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
    $table .=" <tr bgcolor=#dddddd class=tine><td align=center>从$startdate 至 $enddate</td></tr>";
    $table .=" <tr bgcolor=#dddddd class=tine><td align=center colspan=5>没有拼单数据</td></tr>";
  }else{
    $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
    $table .=" <tr bgcolor=#dddddd class=tine><td align=center colspan=12>从$startdate 至 $enddate</td></tr>";
    $table .=" <tr bgcolor=#dddddd class=tine><td align=center colspan=12>
               <a href=comborderexcel.php?startdate=$startdate&enddate=$enddate&disp_ppricenz=$disp_ppricenz&disp_ppricenof=$disp_ppricenof&disp_consumer=$disp_consumer>Excel表格下载</a>
               </td></tr>";
    
    $table .=" <tr bgcolor=#dddddd class=tine>";
    $table .=" <td align=center width='5%'>拼单单号</td>";
    $table .=" <td align=center width='8%'>订单识别码</td>";
    $table .="  <td align=center width='10%'>发货人</td>";
    $table .="  <td align=center width='28%'>发货明细（格式：拼单客人姓名，产品英文名, 产品中文名, 订货数量）</td>";
    //$table .="  <td align=center width='15%'>发货明细中文</td>";
    //$table .="  <td align=center width='8%'>订货数量</td>";
    $table .="  <td align=center width='6%'>收件人</td>";
    $table .="  <td align=center width='10%'>收件人地址</td>";
    $table .="  <td align=center width='10%'>收件人电话</td>";
    $table .="  <td align=center width='7%'>标记</td>";
    $table .="  <td align=center width='10%'>子订单备注</td>";
    $table .="  <td align=center width='8%'>订单备注</td>";
    if($disp_ppricenz) $table .="  <td align=center width='10%'>进价单价NZ</td>";
    if($disp_ppricenof) $table .="  <td align=center width='10%'>产品总价NZ（不包括运费）</td>";
    if($disp_consumer) $table .="  <td align=center width='10%'>消费者</td>";
    //if($disp_weight) $table .="  <td align=center width='10%'>订单重（含包装）</td>";
    //if($disp_pfreight) $table .="  <td align=center width='10%'>实收运费CN,NZ</td>";
    //if($disp_pprice) $table .="  <td align=center width='15%'>本单总价</td>";
    $table .="  </tr>";
  
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
  
  if(!empty($comborderList)){
    
    foreach($comborderList as $comborder){
      if( $rowColor%2 == 0 ) {
	    $bgcolor = "#ffffcc";
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
          //$freight = $order->getFreight();
            //$freightnz = $freight/$rrate;
          //$pfreight = $order->getPfreight();
          //$pprice = $order->getPprice();
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
  
              $table .=" <tr bgcolor=$bgcolor class=line>";
              $table .="  <td align=center>$comborderid</td>";
              $table .="  <td align=center>$orderIdcode</td>";
              $table .="  <td align=center>Mica Yao $memo</td>";
              $table .="  <td align=center>($consumerName) $ename, $cname, *$sellSingleProductNumber</td>";
              //$table .="  <td align=center>$cname</td>";
              //$table .="  <td align=center>$sellSingleProductNumber</td>";
              $table .="  <td align=center>$name</td>";
              $table .="  <td align=center>$address</td>";
              $table .="  <td align=center>$mobile</td>";
              $table .="  <td align=center>新西兰报纸</td>";
              $table .="  <td align=center>$ordermemo</td>";
              $table .="  <td align=center>$memo</td>";
              if($disp_ppricenz) $table .="  <td align=center>$ppricenz</td>";
              if($disp_ppricenof) $table .="  <td align=center>$productTotalPrice</td>";
              if($disp_consumer) $table .="  <td align=center>$consumerName</td>";
              $table .="  </tr>";
            }
          }else{
            continue;
          }//$suborderCount > 0
        }//foreach($combologList as $combolog){
      }//if(!empty($combologList)){
    
      $orderCount++;
      $rowColor++;
    }//foreach($comborderList as $comborder){
  
    $table .=" <tr bgcolor=$bgcolor class=line>";
    $table .="  <td align=center></td>";
    $table .="  <td align=center></td>";
    $table .="  <td align=center></td>";
    $table .="  <td align=center></td>";
    $table .="  <td align=center></td>";
    $table .="  <td align=center></td>";
    $table .="  <td align=center>总计</td>";
    $table .="  <td align=center>$orderCount&nbsp;单</td>";
    if($disp_ppricenz) $table .="  <td align=center></td>";
    if($disp_ppricenof) $table .="  <td align=center></td>";
    if($disp_consumer) $table .="  <td align=center></td>";
    $table .="  </tr>";
  
  }//if(!empty($comborderList)){
  
  $table .="</table></center>";
  $table .="</BODY></html>";
}
echo $table;
}
?>