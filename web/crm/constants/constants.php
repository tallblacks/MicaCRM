<?php
    require_once("../include/config.inc.php");
    require_once("../include/mysql.inc.php");
    require_once("../include/function.inc.php");
    require_once("../include/user.inc.php");
    require_once("../include/cconstantspeer.inc.php");
    CheckCookies();

	  session_start();
	  if ((!isset($_SESSION["SESS_USERID"])) || ($_SESSION['SESS_TYPE'] != ADMIN)) {
    	  Header("Location:../phpinc/main.php");
		    exit();
    }

    // get parameters
    $type = trim(@$_GET["type"]);
    $start = trim(@$_GET["start"]);
    $range = trim(@$_GET["range"]);
    $msg = trim(@$_GET["msg"]);

    if (empty($range)) {
        $range = 15;
    }

    if (empty($start)) {
	$start = 0;
    }
?>
<html><head>
<title></title>
<link rel=stylesheet type=text/css href="/style/global.css">
</head>
<BODY BGCOLOR=#ffffff LINK=#000099 ALINK=#cc0000 VLINK=#000099 TOMARGIN=8>
<center>
<?php
    $titlebars = array("常量管理"=>"constants.php"); 
    $operations = array("添加常量"=>"createconstants.php");
    $jumptarget = "cmsright";

    include("../phpinc/titlebar.php");
    if (!empty($msg)) {
  	    echo "<span class=cur>$msg</span>";
    }

    $db = new cDatabase;
    $constantsManager = new cConstantsPeer;
	  $total = $constantsManager->getConstantsCount(0);
	  $constantsCount = 0;
	  $constantsList = $constantsManager->getConstantslist(0, $start, $range);
	  $constantsCount = sizeof($constantsList);

    if ($constantsCount > 0) {
        $table ="<table cellpadding=1 cellspacing=1 border=0 width=100%>";
        $table .=" <tr>";
        $table .="  <td width=50% align=left nowrap class=line>";
        echo $table;
             
        if (($start-$range) >= 0) {
    	      $starts = $start-$range;
            echo "&laquo; <a href=\"constants.php?type=$type&range=$range&start=$starts\">前$range</a>";
        } else {
            echo "&nbsp;";
        }
        echo "</td>
              <td width=50% align=right nowrap class=line>";
        if (($start+$range) < $total) {
 	          $starts=$start+$range;
            echo "<a href=\"constants.php?type=$type&range=$range&start=$starts\">后$range</a> &raquo;";
        } else {
            echo " &nbsp;";
        }
 
        echo "</td></tr></table>";
    }

    $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
    $table .=" <tr bgcolor=#dddddd class=tine>";
    $table .=" <td align=center width='23%'>常量值</td>";
    $table .="  <td align=center width='20%'>常量类型</td>";
    $table .="  <td align=center width='30%'>设置时间</td>";
    $table .="  <td align=center width='8%'>编辑</td>";
    $table .="  <td align=center width='8%'>删除</td></tr>";

    $rowColor = 0;
    $bgcolor = "";

    if (!empty($constantsList)) {
        foreach ($constantsList as $constants) {
  	        $constants = (object)$constants;
  	        $constantsid = $constants->getConstantsid();
  	        $constantsVal = $constants->getConstants();
  	        $type = $constants->getCType();
  	        $createtime = $constants->getCreatetime();

  	        if ($rowColor%2 == 0) {
                $bgcolor = "#ffffcc";
            } else {
                $bgcolor = "#eeeeee";
            }

            $table .=" <tr bgcolor=$bgcolor class=line>";
            $table .="  <td align=center>$constantsVal</td>";
            if ($type == 1) {
                $table .="  <td align=center>汇率</td>";
            } else if ($type == 2) {
                $table .="  <td align=center>拼单货品总重（克）</td>";
            } else if ($type == 3) {
                $table .="  <td align=center>直邮订单包装重量（克）</td>";
            } else if ($type == 4) {
                $table .="  <td align=center>拼单订单包装重量（克）</td>";
            } else {
                $table .="  <td align=center>未知类型</td>";
            }
            $table .="  <td align=center>".$createtime."</td>";
            $table .="  <td align=center>";
            $table .="   <a href=\"/constants/editconstants.php?constantsid=$constantsid&start=$start&range=$range\"><img src=\"../images/edit.gif\" align=\"bottom\" border=0></a></td>";
            $table .="  <td align=center >";
            $table .="   <a href=\"/constants/delconstants.php?constantsid=$constantsid&start=$start&range=$range\"><img src=\"../images/del.gif\" align=\"bottom\" border=0></a></td></tr>";
            $rowColor++;
        }
    }

    $table .="</table></center>";
    $table .="</BODY></html>";

    echo $table;
?>
