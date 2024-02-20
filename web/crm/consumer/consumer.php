<?php
    require_once("../include/config.inc.php");
    require_once("../include/mysql.inc.php");
    require_once("../include/function.inc.php");
    require_once("../include/user.inc.php");
    CheckCookies();

    if (!session_id()) session_start();
	  if (!isset($_SESSION["SESS_USERID"])) {
		    Header("Location:index.php");
		    exit();
	  }

	  require_once("../include/cconsumerpeer.inc.php");
	  require_once("../include/user.inc.php");
	
    $agentid = 0;
    #如果不是管理员，不是超级小蜜，不是小蜜，那么就是代理，设置代理ID
    if ($_SESSION['SESS_TYPE'] != ADMIN && $_SESSION['SESS_TYPE'] != SECRETARY && $_SESSION['SESS_TYPE'] != XIAOMI) {
        $agentid = $_SESSION['SESS_USERID'];
    }
	
    // get parameters
    $status = trim(@$_GET["status"]);
    $start = trim(@$_GET["start"]);
    $range = trim(@$_GET["range"]);
    $msg = trim(@$_GET["msg"]);
    $status = 0;

    if (empty($range)) {
      $range = 15;
    }

    if (empty($start)) {
	$start = 0;
    }
  
    $doSearch = trim(@$_POST["dosearch"]);
    if ($doSearch) {
        $search = trim(@$_POST["search"]);
        $start = trim(@$_POST["start"]);
    } else {//非第一搜索页
        $doSearch = trim(@$_GET["dosearch"]);
        if ($doSearch) {//搜索＋翻页
            $search = trim(@$_GET["search"]);
        } else {//非搜索情况
            $search = "";
        }
    }
?>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel=stylesheet type=text/css href="/style/global.css">
    </head>
<BODY BGCOLOR=#ffffff LINK=#000099 ALINK=#cc0000 VLINK=#000099 TOMARGIN=8>
<?php
    $titlebars = array("消费者管理"=>"consumer.php");
    $operations = array("添加消费者"=>"createconsumer.php");
    $jumptarget = "cmsright";

    include("../phpinc/titlebar.php");
    echo "<form name=searchconsumer method=post action=>
          <input type=hidden name=dosearch value=1>
          <input type=hidden name=start value=0>
          <table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>
          <tr class= itm bgcolor='#dddddd'>
          <td>输入消费者姓名</td>
          <td><input type=type name=search value=$search></td>
          <td><input type=submit name=submit value=模糊搜索></td>
          </tr>
          </table>
          </form>";

    $db = new cDatabase;
    //mysql_query('set names utf8mb4');
    $consumerManager = new cConsumerPeer;
    
    $consumerCount = 0;
    if ($_SESSION['SESS_TYPE'] == ADMIN || $agentid > 0) {//如果是管理员或者代理
        $total = $consumerManager->getConsumerPlusmemoCount($search, $agentid);
	      $consumerList = $consumerManager->getConsumerlist($start, $range, $search, $agentid);
	      if($consumerList){
	          $consumerCount = sizeof($consumerList);
	      }else{
	          $consumerCount = 0;
	      }
	  } else if ($_SESSION['SESS_TYPE'] == SECRETARY || $_SESSION['SESS_TYPE'] == XIAOMI) {//小蜜或超级小蜜
	      $xiaomid = $_SESSION['SESS_USERID'];
	      $total = $consumerManager->getConsumerXiaomiCount($search, $xiaomid);
	      $consumerList = $consumerManager->getConsumerXiaomilist($start, $range, $search, $xiaomid);
	      $consumerCount = sizeof($consumerList);
	  } else {
	      Header("Location:index.php");
	      exit();
	  }

	  if (!empty($msg)) {
  	    echo "<span class=cur>$msg</span>";
    }

    //翻页导航
    if ($consumerCount > 0) {
        $table ="<table cellpadding=1 cellspacing=1 border=0 width=100%>";
        $table .=" <tr>";
        $table .="  <td width=50% align=left nowrap class=line>";
        echo $table;
             
        if (($start-$range) >= 0) {
    	      $starts=$start-$range;
            echo "&laquo; <a href=\"consumer.php?range=$range&start=$starts&dosearch=$doSearch&search=$search\">前$range</a>";
        } else {
            echo "&nbsp;";
        }
        echo "</td>
              <td width=50% align=right nowrap class=line>";
        if (($start+$range) < $total) {
 	          $starts=$start+$range;
            echo "<a href=\"consumer.php?range=$range&start=$starts&dosearch=$doSearch&search=$search\">后$range</a> &raquo;";
        } else {
            echo " &nbsp;";
        }
        echo "</td></tr></table>";
    }

    //表头
    $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
    $table .=" <tr bgcolor=#dddddd class=tine>";
    if ($_SESSION['SESS_TYPE'] == ADMIN) {
        $table .=" <td align=center width='5%'>客户编号</td>";
    }
    $table .=" <td align=center width='8%'>姓名</td>";
    $table .="  <td align=center width='40%'>地址</td>";
    $table .="  <td align=center width='10%'>备注</td>";
    if ($_SESSION['SESS_TYPE'] == ADMIN) {
        $table .="  <td align=center width='10%'>微信号</td>";
    }
    $table .="  <td align=center width='5%'>手机</td>";
    if ($_SESSION['SESS_TYPE'] != XIAOMI) {//超级小蜜、管理员、代理，都能看到代理
        $table .="  <td align=center width='5%'>代理</td>";
    }
    if ($_SESSION['SESS_TYPE'] == ADMIN) {
        $table .="  <td align=center width='5%'>指定小蜜</td>";
    }
    $table .="  <td align=center width='5%'>建立时间</td>";
    $table .="  <td align=center width='4%'>新订单</td>";
    $table .="  <td align=center width='3%'>编辑</td>";
    /*if ($_SESSION['SESS_TYPE'] == ADMIN) {
        $table .="  <td align=center width='4%'>删除</td>";
    }*/
    $table .="  </tr>";

    //遍历，显示消费者信息
    $rowColor = 0;
    $bgcolor = "";

    if (!empty($consumerList)) {
        $rowColor=1;
        foreach ($consumerList as $consumer) {
  	        $consumer = (object)$consumer;
  	        $consumerid = $consumer->getConsumerid();
  	        $name = $consumer->getCname();
  	        $address = $consumer->getAddress();
  	        $memo = $consumer->getMemo();
  	        $mobile = $consumer->getMobile();
  	        $telephone = $consumer->getTelephone();
            $wechat = $consumer->getWechat();
            
  	        $user = new user;
  	        $agentname = @$user->getUserbyId($consumer->getAgentid())->realname;
            $xiaominame = @$user->getUserbyId($consumer->getXiaomid())->realname;
            
            $createtime = $consumer->getCreatetime();
      
            if ( $rowColor%2 == 0 ) {
	              $bgcolor = "#ffffcc";
            } else {
	              $bgcolor = "#eeeeee";
            }
      
            //for IE 无内容无边框
            if ($address == null || $address == '') $address = "&nbsp;";
            if ($memo == null || $memo == '') $memo = "&nbsp;";
            if ($mobile == null || $mobile == '') $mobile = "&nbsp;";
            if ($telephone == null || $telephone == '') $telephone = "&nbsp;";
            if ($agentname == null || $agentname == '') $agentname = "&nbsp;";

            $table .=" <tr bgcolor=$bgcolor class=line>";
            if ($_SESSION['SESS_TYPE'] == ADMIN) {
                $table .="  <td align=center>$consumerid</td>";
            }
            if ($_SESSION['SESS_TYPE'] == ADMIN) {
                $table .="  <td align=left><a href=/order/order.php?range=15&start=0&dosearch=1&consumerid=$consumerid target=_ablank>$name</a>&nbsp;|&nbsp;<a href=/order/puborder.php?range=15&start=0&dosearch=1&search=$consumerid target=_ablank>出</a></td>";
            } else {
                $table .="  <td align=left>$name</td>";
            }
            $table .="  <td align=left>$address</td>";
            $table .="  <td align=left>$memo</td>";
            if ($_SESSION['SESS_TYPE'] == ADMIN) {
                $table .="  <td align=left>$wechat</td>";
            }
            $table .="  <td align=center>$mobile</td>";
            if ($_SESSION['SESS_TYPE'] != XIAOMI) {
                $table .="  <td align=center>$agentname</td>";
            }
            if ($_SESSION['SESS_TYPE'] == ADMIN) {
                if ($xiaominame) {
                    $table .="  <td align=center>$xiaominame|<a href=\"/consumer/editxiaomi.php?consumerid=$consumerid&start=$start&range=$range\">改</a></td>";
                } else {
                    $table .="  <td align=center>无|<a href=\"/consumer/editxiaomi.php?consumerid=$consumerid&start=$start&range=$range\">指定</a></td>";
                }
            }
            $table .="  <td align=center>".substr($createtime,0,10)."</td>";
            $table .="  <td align=center><a href=\"/order/createorder.php?consumerid=$consumerid\">新订单</a></td>";
            $table .="  <td align=center>";
            $table .="   <a href=\"/consumer/editconsumer.php?consumerid=$consumerid&start=$start&range=$range\"><img src=\"../images/edit.gif\" align=\"bottom\" border=0></a></td>";
            /*if ($_SESSION['SESS_TYPE'] == ADMIN) {
                $table .="  <td align=center >";
                $table .="   <a href=\"/consumer/delconsumer.php?consumerid=$consumerid&start=$start&range=$range\"><img src=\"../images/del.gif\" align=\"bottom\" border=0></a></td>";
            }*/
            $table .="  </tr>";
            $rowColor++;
        }
    }
  
    $table .="</table></center>";
    $table .="</BODY></html>";
    
    echo $table;
?>
