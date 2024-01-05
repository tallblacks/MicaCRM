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
	require_once("../include/cwechatlogpeer.inc.php");
	require_once("../include/cwechatlogdescpeer.inc.php");
	
	$wechatlogid = trim($_GET["wechatlogid"]);
	
	$bgcolor1 = $strings['TABLE_DARK_COLOR'].$over=$strings['DARK_OVER'];
    $bgcolor2 = $strings['TABLE_LIGHT_COLOR'].$over=$strings['LIGHT_OVER'];
	
	$db = new cDatabase;
	//mysql_query('set names utf8');
    $wechatlogdescManager = new cWechatlogdescPeer;
    $wechatlogdescCount = 0;
	$wechatlogdescList = $wechatlogdescManager->getWechatlogdesclist($wechatlogid);
	if($wechatlogdescList){
	  $wechatlogdescCount = sizeof($wechatlogdescList);
	}else{
	  $wechatlogdescCount = 0;
	}
    
    $parameterStr = '';
    $listBgCCount = 0;
    if($wechatlogdescCount > 0){
      foreach($wechatlogdescList as $wechatlogdesc){
  	    $wechatlogdesc = (object)$wechatlogdesc;
  	    $wechatlogdescid = $wechatlogdesc->getWechatlogdescid();
        $wechatlogid = $wechatlogdesc->getWechatlogid();
	    $parametername = $wechatlogdesc->getParametername();
	    $parametervalue = $wechatlogdesc->getParametervalue();
	    $type = $wechatlogdesc->getCtype();
	    $status = $wechatlogdesc->getPstatus();
	    $create_time = $wechatlogdesc->getCreate_time();
	    
	    if($listBgCCount%2 == 0){
          $listBgCStr = $bgcolor2;
        }else{
          $listBgCStr = $bgcolor1;
        }
  	    
  	    $parameterStr .="<tr $listBgCStr align=\"center\">";
   		$parameterStr .="<td height=\"27\">&nbsp;$wechatlogdescid</td>";
   		$parameterStr .="<td height=\"27\">&nbsp;$parametername</td>";
   		$parameterStr .="<td height=\"27\">&nbsp;$parametervalue</td>";
   		$parameterStr .="</tr>";
   		
   		$listBgCCount++;
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
  if(!empty($msg)) {	
    echo "<span class=cur>$msg</span>";
  }
  
   $table = "<meta charset=utf-8>";
   $table .= "<table>";
   $table .= "<tr>";
   $table .= "<td>";
   
   $table .= "<table width=\"600\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>";
   $table .=" <tr align=\"center\" >";
   $table .="<td height=\"30\" colspan=\"9\" class=hdr>编号 $wechatlogid 消息详情</td>";
   $table .="</tr>";
   $table .="</table>";
   
   $table .= "</td>";
   $table .= "</tr>";
   $table .= "<tr>";
   $table .= "<td>";
   $table .= "</td>";
   $table .= "</tr>";
   $table .= "<tr>";
   $table .= "<td>";
   
   $table .= "<table width=\"600\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>";
   $table .="<tr $bgcolor1 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;消息详情子编号</td>";
   $table .="<td height=\"27\">&nbsp;参数名</td>";
   $table .="<td height=\"27\">&nbsp;参数值</td>";
   $table .="</tr>";
   $table .= $parameterStr;
   $table .="</table>";
   
   $table .= "</td>";
   $table .= "</tr>";
   $table .= "<tr>";
   $table .= "<td>";
   $table .= "</td>";
   $table .= "</tr>";
   $table .= "<tr>";
   $table .= "<tr>";
   $table .= "<td>";
   
   $table .= "</td>";
   $table .= "</tr>";
   $table .="</table>";
  

$table .="</table></center>";
$table .="</BODY></html>";

echo $table;
?>