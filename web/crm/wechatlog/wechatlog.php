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
	
	// get parameters
  	$start = trim($_GET["start"]);
  	$range = trim($_GET["range"]);
  	$msg = trim($_GET["msg"]);

  	if(empty($range)){
      $range = 15;
  	}
	
	$type = 0;
	$db = new cDatabase;
	//mysql_query('set names utf8');
    $wechatlogManager = new cWechatlogPeer;
	$total = $wechatlogManager->getWechatlogCount($type);
	$wechatlogCount = 0;
	$wechatlogList = $wechatlogManager->getWechatloglist($start,$range);
	$wechatlogCount = sizeof($wechatlogList);
	
	$wechatlogdescManager = new cWechatlogdescPeer;
?>
<html><head>
<title></title>
<link rel=stylesheet type=text/css href="/style/global.css">
</head>
<BODY BGCOLOR=#ffffff LINK=#000099 ALINK=#cc0000 VLINK=#000099 TOMARGIN=8>
<center>
<?php
  $titlebars = array("公众号管理"=>"wechatlog.php",$name=>"");
  $operations = array("信息管理"=>"wechatlog.php");
  $jumptarget = "cmsright";

  include("../phpinc/titlebar.php");
  
  if(!empty($msg)) {
    echo "<span class=cur>$msg</span>";
  }

  if($wechatlogCount > 0) {
    $table ="<table cellpadding=1 cellspacing=1 border=0 width=100%>";
    $table .=" <tr>";
    $table .="  <td width=50% align=left nowrap class=line>";
    echo $table;
             
    if( ($start-$range) >= 0 ) {
      $starts=$start-$range;
      echo "&laquo; <a href=\"wechatlog.php?range=$range&start=$starts\">前$range</a>";
    } else {
      echo "&nbsp;";
    }
    echo "</td>
          <td width=50% align=right nowrap class=line>";
    if(($start+$range) < $total ) {
 	  //$range=(($start+$range-$total)<range)?($total-$start):$range;
 	  $starts=$start+$range;
      echo "<a href=\"wechatlog.php?range=$range&start=$starts\">后$range</a> &raquo;";
    } else {
      echo " &nbsp;";
    }
 
    echo "</td></tr></table>";
  }

  $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
  $table .=" <tr bgcolor=#dddddd class=tine>";
  $table .="  <td align=center width='7%'>消息编号</td>";
  $table .="  <td align=center width='15%'>微信消息编号</td>";
  $table .="  <td align=center width='15%'>开发者接收微信号（姚竹公众号）</td>";
  $table .="  <td align=center width='19%'>发送方帐号（一个OpenID）</td>";
  $table .="  <td align=center width='13%'>消息核心内容</td>";
  $table .="  <td align=center width='8%'>消息类型</td>";
  $table .="  <td align=center width='13%'>消息创建时间</td>";
  $table .="  <td align=center width='5%'>详情</td>";
  $table .="  <td align=center width='5%'>回复</td></tr>";

  // iterate through users, show info
  //$rowColor = 0;
  $bgcolor = "";

  if(!empty($wechatlogList)){
    $rowColor=1;
    foreach($wechatlogList as $wechatlog){
  	  $wechatlog = (object)$wechatlog;
  	  $wechatlogid = $wechatlog->getWechatlogid();
  	  $tousername = $wechatlog->getTousername();
  	  $fromusername = $wechatlog->getFromusername();
  	  $createtime = $wechatlog->getCreatetime();
  	    $createtime_display = date('Y-m-d H:i:s',$createtime);
  	  $msgtype = $wechatlog->getMsgtype();
      $msgid = $wechatlog->getMsgid();
      $create_time = $wechatlog->getCreate_time();
      
      $contentStr = '';
      switch($msgtype){
        case "text":
          $wechatlogdesc = $wechatlogdescManager->getWechatlogdescByLogidAndName($wechatlogid,'Content');
          if($wechatlogdesc) $contentStr = $wechatlogdesc->getParametervalue();
          break;
        case "image":
          $contentStr = '图片消息';
          break;
        case "voice":
          $contentStr = '语音消息';
          break;
        case "video":
          $contentStr = '视频消息';
          break;
        case "location":
          $contentStr = '地理位置消息';
          break;
        case "link":
          $contentStr = '链接消息';
          break;
        case "event":
          $wechatlogdesc = $wechatlogdescManager->getWechatlogdescByLogidAndName($wechatlogid,'Event');
          if($wechatlogdesc) $Event = $wechatlogdesc->getParametervalue();
          switch($Event){
            case "subscribe":
              $contentStr = '事件.订阅';
              break;
            case "unsubscribe":
              $contentStr = '事件.取消订阅';
              break;
            case "SCAN":
              $contentStr = '事件.已关注.扫描';
              break;
            case "LOCATION":
              $contentStr = '事件.上报地理位置';
              break;
            case "CLICK":
              $contentStr = '事件.点击菜单拉取消息';
              break;
            case "VIEW":
              $contentStr = '事件.点击菜单跳转链接';
              break;
            default:
              $contentStr = '事件.未定义';
              break;
          }
          break;
        default:
          $contentStr = '未定义';
          break;
      }
      
      if( $rowColor%2 == 0 ) {
	    $bgcolor = "#ffffcc";
      } else {
	    $bgcolor = "#eeeeee";
      }

      $table .=" <tr bgcolor=$bgcolor class=line>";
      $table .="  <td align=center>$wechatlogid</td>";
      $table .="  <td align=center>$msgid</td>";
      $table .="  <td align=center>$tousername</td>";
      $table .="  <td align=center>$fromusername</td>";
      $table .="  <td align=center>$contentStr</td>";
      $table .="  <td align=center>$msgtype</td>";
      $table .="  <td align=center>$createtime_display</td>";
      $table .="  <td align=center>";
      $table .="   <a href=\"wechatlogdesc.php?wechatlogid=$wechatlogid\" target=\"_ablank\">详情</a></td>";
      $table .="  <td align=center >";
      $table .="   回复</td></tr>";
      $rowColor++;
    }
  }
  

$table .="</table></center>";
$table .="</BODY></html>";

echo $table;