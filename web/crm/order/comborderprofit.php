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
	require_once("../include/cprofitpeer.inc.php");
	require_once("../include/corderpeer.inc.php");
	require_once("../include/cconsumerpeer.inc.php");
	require_once("../include/ccomborderpeer.inc.php");
	require_once("../include/ccombologpeer.inc.php");

  // get parameters
  $start = trim(@$_GET["start"]);
  $range = trim(@$_GET["range"]);
  $msg = trim(@$_GET["msg"]);

  if(empty($range)){
    $range = 15;
  }
?>
<html><head>
<title></title>
<link rel=stylesheet type=text/css href="/style/global.css">
</head>
<BODY BGCOLOR=#ffffff LINK=#000099 ALINK=#cc0000 VLINK=#000099 TOMARGIN=8>
<center>
<?php
  $titlebars = array("拼单管理"=>"comborder.php","拼单利润"=>"comborderprofit.php"); 
  $operations = array("拼单出单"=>"pubcomborder.php");
  $jumptarget = "cmsright";

  include("../phpinc/titlebar.php");

  $db = new cDatabase;
  //mysql_query('set names utf8');
  $comborderManager = new cComborderPeer;
  $total = $comborderManager->getComborderCount();
  $comborderCount = 0;
  $comborderList = $comborderManager->getComborderlist($start, $range);
  $comborderCount = sizeof($comborderList);
    
    
    
    
    
    
    

  if(!empty($msg)) {
    echo "<span class=cur>$msg</span>";
  }

  if($comborderCount > 0) {
    $table ="<table cellpadding=1 cellspacing=1 border=0 width=100%>";
    $table .=" <tr>";
    $table .="  <td width=50% align=left nowrap class=line>";
      echo $table;
             
     if( ($start-$range) >= 0 ) {
    	$starts=$start-$range;
        echo "&laquo; <a href=\"profit.php?range=$range&start=$starts\">前$range</a>";
     } else {
       echo "&nbsp;";
     }
     echo "</td>
           <td width=50% align=right nowrap class=line>";
     if(($start+$range) < $total ) {
 	   //$range=(($start+$range-$total)<range)?($total-$start):$range;
 	   $starts=$start+$range;
       echo "<a href=\"profit.php?range=$range&start=$starts\">后$range</a> &raquo;";
     } else {
       echo " &nbsp;";
     }
 
     echo "</td></tr></table>";
  }

  $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
  $table .=" <tr bgcolor=#dddddd class=tine>";
  $table .=" <td align=center width='7%'>拼单编号</td>";
  $table .="  <td align=center width='8%'>总利润</td>";
  $table .="  <td align=center width='10%'>代理看总利润</td>";
  $table .="  <td align=center width='8%'>代理利润</td>";
  $table .="  <td align=center width='8%'>代购利润</td>";
  $table .="  <td align=center width='11%'>代理看代购利润</td>";
  $table .="  <td align=center width='7%'>Mica利润</td>";
  $table .="  <td align=center width='7%'>净利润</td>";
  $table .="  <td align=center width='15%'>拼单备注</td>";
  $table .="  <td align=center width='7%'>拼单时间</td>";
  $table .="  <td align=center width='12%'>出单与详情</td></tr>";

  // iterate through users, show info
  $rowColor = 0;
  $bgcolor = "";
  
  if(!empty($comborderList)){
    $rowColor=1;
    foreach($comborderList as $comborder){
  	  $comborder = (object)$comborder;
  	  $comborderid = $comborder->getComborderid();
  	  $name = $comborder->getCname();
  	  $memo = $comborder->getMemo();
  	  $Createtime = $comborder->getCreatetime();
  	  $totalProfit = 0;
  	  $totalSProfit = 0;
  	  $sagentProfit = 0;
  	  $pagentProfit = 0;
  	  $pagentSProfit = 0;
  	  $micaProfit = 0;
  	  $jProfit = 0;
        $combologManager = new cCombologPeer;
  	    $combologCount = 0;
        $combologList = $combologManager->getCombologList($comborderid);
        $combologCount = sizeof($combologList);
        if(!empty($combologList)){
          $orderManager = new cOrderPeer;
          $profitManager = new cProfitPeer; 
          foreach($combologList as $combolog){
            $combolog = (object)$combolog;
  	        $combologid = $combolog->getCombologid();
  	        $orderid = $combolog->getOrderid();
  	        $order = $orderManager->getOrder($orderid);
  	        $discount = $order->getDiscount();
  	        //$create_time = $order->getCreatetime();
  	          $profitOne = $profitManager->getProfit($orderid,1);
  			  $totalProfit = $totalProfit+$profitOne->getProfit();
  			  $profitTwo = $profitManager->getProfit($orderid,2);
  			  $totalSProfit = $totalSProfit+$profitTwo->getProfit();
  			  $profitThree = $profitManager->getProfit($orderid,3);
  			  $sagentProfit = $sagentProfit+$profitThree->getProfit();
  			  $profitFour = $profitManager->getProfit($orderid,4);
  			  $pagentProfit = $pagentProfit+$profitFour->getProfit();
  			  $profitFive = $profitManager->getProfit($orderid,5);
  			  $pagentSProfit = $pagentSProfit+$profitFive->getProfit();
  			  $profitSix = $profitManager->getProfit($orderid,6);
  			  $micaProfit = $micaProfit+$profitSix->getProfit();
  			  $profitSeven = $profitManager->getProfit($orderid,7);
  			  $jProfit = $jProfit+$profitSeven->getProfit();
  		  }
  		}
      
      if( $rowColor%2 == 0 ) {
	    $bgcolor = "#ffffcc";
      }else{
	    $bgcolor = "#eeeeee";
      }

      $table .=" <tr bgcolor=$bgcolor class=line>";
      $table .="  <td align=center>$comborderid</td>";
      $table .="  <td align=center>$totalProfit</td>";
      $table .="  <td align=center>$totalSProfit</td>";
      $table .="  <td align=center>$sagentProfit</td>";
      $table .="  <td align=center>$pagentProfit</td>";
      $table .="  <td align=center>$pagentSProfit</td>";
      $table .="  <td align=center>$micaProfit</td>";
      $table .="  <td align=center>$jProfit</td>";
      $table .="  <td align=center>$memo</td>";
      $table .="  <td align=center>".substr($Createtime,0,10)."</td>";
      $table .="  <td align=center><a href=\"combopub2order.php?comborderid=$comborderid\" target=\"_ablank\">订货出单</a>&nbsp;|&nbsp;<a href=\"editcomborderlog.php?comborderid=$comborderid&start=$starts&range=$range\" target=\"_ablank\">详情</a></td></tr>";
     
      $rowColor++;
    }
  }
  

$table .="</table></center>";
$table .="</BODY></html>";

echo $table;
?>