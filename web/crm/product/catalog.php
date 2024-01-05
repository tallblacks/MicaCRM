<?php
  require_once("../include/config.inc.php");
  require_once("../include/mysql.inc.php");
  require_once("../include/function.inc.php");
  require_once("../include/user.inc.php");
  CheckCookies();

  session_start();
  if ((!isset($_SESSION["SESS_USERID"])) || ($_SESSION['SESS_TYPE'] != ADMIN)){
  	$isadmin = false;
  }else{
  	$isadmin = true;
  }

  require_once("../include/ccatalogpeer.inc.php");
?>
<html>
  <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel=stylesheet type=text/css href="/style/global.css">
  </head>
  <BODY BGCOLOR=#ffffff LINK=#000099 ALINK=#cc0000 VLINK=#000099 TOMARGIN=8>
    <center>
<?php
  $titlebars = array("产品分类管理"=>"catalog.php"); 
  $jumptarget = "cmsright";

  include("../phpinc/titlebar.php");

  $db = new cDatabase;
  //mysql_query('set names utf8');
  $catalogManager = new cCatalogPeer;
  $catalogList = $catalogManager->getCatalogList();

  if(!empty($catalogList)) {
    $table ="<table cellpadding=1 cellspacing=1 border=0 width=100%>";
    $table .=" <tr>";
    $table .="  <td width=50% align=left nowrap class=line>";
    echo $table;
             
    echo "&nbsp;";
    echo "</td>
          <td width=50% align=right nowrap class=line>";
    echo " &nbsp;";
    echo "</td></tr></table>";
   }

  $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
  $table .=" <tr bgcolor=#dddddd class=tine>";
  $table .=" <td align=center width='100%'>分类名称</td></tr>";

  $rowColor = 0;
  $bgcolor = "";

  if(!empty($catalogList)){
    foreach($catalogList as $catalog){
      $catalog = (object)$catalog;
      $catalogid = $catalog->getCatalogid();
  	  $name = $catalog->getName();

      if( $rowColor%2 == 0 ) {
	    $bgcolor = "#ffffcc";
      } else {
	    $bgcolor = "#eeeeee";
      }

      $table .=" <tr bgcolor=$bgcolor class=line>";
      if($isadmin){
      	$table .="  <td align=center><a href=editcatalog.php?catalogid=$catalogid>$name</a></td></tr>";
      }else{
      	$table .="  <td align=center>$name</td></tr>";
      }
      $rowColor++;
    }
  }
  
  $table .="</table></center>";
  $table .="</BODY></html>";

  echo $table;
?>