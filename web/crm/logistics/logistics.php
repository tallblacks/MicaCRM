<?php
  require_once("../include/config.inc.php");
  require_once("../include/mysql.inc.php");
  require_once("../include/function.inc.php");
  require_once("../include/user.inc.php");
  CheckCookies();

	//require_once("../include/config.inc.php");
	session_start();
	if (!isset($_SESSION["SESS_USERID"]) || $_SESSION['SESS_TYPE'] != ADMIN){
		Header("Location:index.php");
		exit();
	}
	//require_once("../include/mysql.inc.php");
	require_once("../include/clogisticspeer.inc.php");
	
	// get parameters
  	$type = trim(@$_GET["type"]);
  	$start = trim(@$_GET["start"]);
  	$range = trim(@$_GET["range"]);
  	$msg = trim(@$_GET["msg"]);

  	if(empty($range)){
    	$range = 15;
  	}
	
	$type = 0;
	$db = new cDatabase;
	//mysql_query('set names utf8');
    $logisticsManager = new cLogisticsPeer;
	$total = $logisticsManager->getLogisticsCount($type);
	$logisticsCount = 0;
	$logisticsList = $logisticsManager->getLogisticslist($type, $start, $range);
	$logisticsCount = sizeof($logisticsList);
	
?>
<html><head>
<title></title>
<link rel=stylesheet type=text/css href="/style/global.css">
</head>
<BODY BGCOLOR=#ffffff LINK=#000099 ALINK=#cc0000 VLINK=#000099 TOMARGIN=8>
<center>
<?php
  $titlebars = array("物流管理"=>"logistics.php?type=1"); 
  if($type == 0){
    $operations = array("新增物流公司"=>"createlogistics.php");
  }else{
	$operations = array("新增物流公司"=>"createlogistics.php");
  }
  $jumptarget = "cmsright";

  include("../phpinc/titlebar.php");
  

	if(!empty($msg)) {
  	  echo "<span class=cur>$msg</span>";
    }

    if($logisticsCount > 0) {
      $table ="<table cellpadding=1 cellspacing=1 border=0 width=100%>";
      $table .=" <tr>";
      $table .="  <td width=50% align=left nowrap class=line>";
      echo $table;
             
      if( ($start-$range) >= 0 ) {
    	$starts=$start-$range;
        echo "&laquo; <a href=\"constants.php?type=$type&range=$range&start=$starts\">前$range</a>";
      } else {
       echo "&nbsp;";
      }
      echo "</td>
            <td width=50% align=right nowrap class=line>";
      if(($start+$range) < $total ) {
 	    //$range=(($start+$range-$total)<range)?($total-$start):$range;
 	    $starts=$start+$range;
        echo "<a href=\"constants.php?type=$type&range=$range&start=$starts\">后$range</a> &raquo;";
      } else {
        echo " &nbsp;";
      }
 
      echo "</td></tr></table>";
    }

  $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
  $table .=" <tr bgcolor=#dddddd class=tine>";
  $table .=" <td align=center width='30%'>物流公司名称</td>";
  $table .="  <td align=center width='40%'>物流公司简要说明</td>";
  $table .="  <td align=center width='14%'>单号查询地址前缀</td>";
  $table .="  <td align=center width='8%'>编辑</td>";
  $table .="  <td align=center width='8%'>删除</td></tr>";

  // iterate through users, show info
  //$rowColor = 0;
  $bgcolor = "";

  if(!empty($logisticsList)){
    $rowColor=1;
    foreach($logisticsList as $logistics){
  	  $logistics = (object)$logistics;
  	  $logisticsid = $logistics->getLogisticsid();
  	  $name = $logistics->getCName();
  	  $description = $logistics->getDescription();
  	  $preurl = $logistics->getPreurl();
  	    $preurlStr = "尚未填写";
  	    if($preurl != null || $preurl != "") $preurlStr = "已填写";
  	  $type = $logistics->getCtype();
      $createtime = $logistics->getCreatetime();
      
      if( $rowColor%2 == 0 ) {
	    $bgcolor = "#ffffcc";
      } else {
	    $bgcolor = "#eeeeee";
      }

     //for IE 无内容无边框
     if($description == null || $description == '') $description = "&nbsp;";

     $table .=" <tr bgcolor=$bgcolor class=line>";
     $table .="  <td align=center>$name</td>";
     $table .="  <td align=center>$description</td>";
     $table .="  <td align=center>$preurlStr</td>";
     $table .="  <td align=center>";
     $table .="   <a href=\"/logistics/editlogistics.php?logisticsid=$logisticsid&start=$start&range=$range\"><img src=\"../images/edit.gif\" align=\"bottom\" border=0></a></td>";
     $table .="  <td align=center >";
     $table .="   <a href=\"/logistics/dellogistics.php?logisticsid=$logisticsid&start=$start&range=$range\"><img src=\"../images/del.gif\" align=\"bottom\" border=0></a></td></tr>";
     $rowColor++;
   }
  }
  

$table .="</table></center>";
$table .="</BODY></html>";

echo $table;
?>