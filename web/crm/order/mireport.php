<?php
    require_once("../include/config.inc.php");
    require_once("../include/mysql.inc.php");
    require_once("../include/function.inc.php");
    require_once("../include/user.inc.php");
    CheckCookies();

    session_start();
    if ((!isset($_SESSION["SESS_USERID"])) || ($_SESSION['SESS_TYPE'] != ADMIN)) {
    	  Header("Location:../phpinc/main.php");
		    exit();
    }

	  require_once("../include/corderpeer.inc.php");
	  require_once("../include/cprofitpeer.inc.php");
	  require_once("../include/cconsumerpeer.inc.php");
	
    $dosearch = trim(@$_POST["dosearch"]);
    if ($dosearch) {
        $startdate = trim($_POST["startdate"]);
        $enddate = trim($_POST["enddate"]);
        $xiaomid = trim($_POST["xiaomid"]);
   
        //验证数据 
        $wrongInputFlag = false;
        $wrongInputMessage = false;
        if (strlen($startdate) != 10 || strlen($enddate) != 10) {
            $wrongInputFlag = true;
            $wrongInputMessage = "日期输入错误";
        }
        if (!$wrongInputFlag) {
            if (substr($startdate,4,1) != "-" || substr($startdate,7,1) != "-" || substr($enddate,4,1) != "-" || substr($enddate,7,1) != "-") {
                $wrongInputFlag = true;
                $wrongInputMessage = "日期连接符'-'输入错误";
            }
        }
        if (!$wrongInputFlag) {
            if (substr($startdate,0,4) < 2013 || substr($startdate,0,4) > 2100 || substr($enddate,0,4) < 2013 || substr($enddate,0,4) > 2100) {
                $wrongInputFlag = true;
                $wrongInputMessage = "年份输入错误";
            }
        }
        if (!$wrongInputFlag) {
            if (substr($startdate,5,2) < 1 || substr($startdate,5,2) > 12 || substr($enddate,5,2) < 1 || substr($enddate,5,2) > 12) {
                $wrongInputFlag = true;
                $wrongInputMessage = "月份输入错误";
            }
        }
        if (!$wrongInputFlag) {
            if (substr($startdate,8,2) < 1 || substr($startdate,8,2) > 31 || substr($enddate,8,2) < 1 || substr($enddate,8,2) > 31) {
                $wrongInputFlag = true;
                $wrongInputMessage = "日期输入错误";
            }
        }
    
        //后台查询
        if (!$wrongInputFlag) {
            $db = new cDatabase;
            $orderManager = new cOrderPeer;
            $orderCount = 0;
            $orderList = $orderManager->getOrderlistbyStEdMi($startdate,$enddate,$xiaomid);
            $orderCount = sizeof($orderList);
        } else {
            $msg = $wrongInputMessage;
        }
    }
?>
<html><head>
<title></title>
<link rel=stylesheet type=text/css href="/style/global.css">
<script type="text/javascript" src="/ajaxjs/crm_paystatus.js"></script>
</head>
<BODY BGCOLOR=#ffffff LINK=#000099 ALINK=#cc0000 VLINK=#000099 TOMARGIN=8>
<center>
<?php
    $titlebars = array("报表管理"=>"report.php"); 
    $operations = array("小蜜统计"=>"mireport.php");
    $jumptarget = "cmsright";

    include("../phpinc/titlebar.php");
  
    if (@$wrongInputFlag || @$dosearch != 1) {
        $year = date('Y');
        $month = date('m');
        $day = date('d');
  
        $xiaomidList = array();
        $db = new cDatabase;
        $user = new user;
        $sql = "select userid,realname,type from user where type=5 or type=6";
        $query = $db->query($sql);
        while ($data=$db->fetch_array($query)) {
            $xiaomidList[] = $data['userid'];
            if ($data['type'] == 5) {
                $xiaominameList[] = $data['realname']."(超级小蜜)";
            } else {
                $xiaominameList[] = $data['realname'];
            }
        }
  
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
                <td>选择小蜜</td>
                <td><select name=xiaomid>";
        $xiaomidCount = 0;
        foreach ($xiaomidList as $xiaomid) {
            echo "<option value=$xiaomid>$xiaominameList[$xiaomidCount]</option>";
            $xiaomidCount++;
        }
        echo "</select></td>
                </tr>
                <tr class= itm bgcolor='#dddddd'>
                <td></td>
                <td><input type=submit name=submit value=生成报告><font color=red><b>注意：所有订单详情将在一页中显示，如果所选时间跨度较大，请耐心等候！</b></font></td>
                </tr>
                </table>
                </form>";
    }
        
    if (!empty($msg)) {
        echo "<span class=cur>$msg</span>";
    }
  
    if (!@$wrongInputFlag && @$dosearch == 1) {
        if ($orderCount == 0) {
            $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
            $table .=" <tr bgcolor=#dddddd class=tine><td align=center>从$startdate 至 $enddate</td></tr>";

            $user = new user;
            $xiaomiName = $user->getUserbyId($xiaomid)->realname;
            $table .=" <tr bgcolor=#dddddd class=tine><td align=center colspan=5>小蜜：$xiaomiName</td></tr>";

            $table .=" <tr bgcolor=#dddddd class=tine><td align=center colspan=5>没有订单</td></tr>";
        } else {
            $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
            $table .=" <tr bgcolor=#dddddd class=tine><td align=center colspan=11>从$startdate 至 $enddate</td></tr>";

            $user = new user;
            $xiaomiName = $user->getUserbyId($xiaomid)->realname;
  
            $table .=" <tr bgcolor=#dddddd class=tine><td align=center colspan=11>小蜜：$xiaomiName</td></tr>";
        }

        $table .=" <tr bgcolor=#dddddd class=tine>";
        $table .=" <td align=center width='9%'>订单号</td>";
        $table .="  <td align=center width='11%'>订货人</td>";
        $table .="  <td align=center width='9%'>代理人</td>";
        $table .="  <td align=center width='9%'>销售总额</td>";
        $table .="  <td align=center width='9%'>代理利润</td>";
        $table .="  <td align=center width='9%'>代理应付</td>";
        $table .="  <td align=center width='9%'>Mica利润</td>";
        $table .="  <td align=center width='9%'>Mica利润率</td>";
        $table .="  <td align=center width='9%'>净利润</td>";
        $table .="  <td align=center width='9%'>订购日期</td>";
        $table .="  <td align=center width='8%'>付款</td>";
        $table .="  </tr>";
  
        $rowColor = 0;
        $bgcolor = "";
  
        $orderCount = 0;
        $totalPrice = 0;
        $totalSagentprofit = 0;
        $totalSagentpay = 0;
        $totalMicaprofit = 0;
        $totalJprofit = 0;
  
        $profitManager = new cProfitPeer;
        $user = new user;
    
        if ($orderList) {
            foreach ($orderList as $order) {
                $order = (object)$order;
                $orderid = $order->getOrderid();
                $consumerid = $order->getConsumerid();
                $consumerManager = new cConsumerPeer;
                $consumer = $consumerManager->getConsumer($consumerid);
                if ($consumer) {
                    $consumerName = $consumer->getCname();
                    $consumerSagentid = $consumer->getAgentid();
                    $sagentName = $user->getUserbyId($consumerSagentid)->realname;
                }
                $price = $order->getPrice();
                $paystatus = $order->getPaystatus();
                $create_time = $order->getCreatetime();
                $create_time = substr($create_time,0,10);
  	
                $sagentprofit = 0;
                $sagentprofitObj = $profitManager->getProfit($orderid,3);
                if (!empty($sagentprofitObj)) {
                    $sagentprofit = $sagentprofitObj->getProfit();
                }
                if ($sagentid > 0 || $consumerSagentid > 0) {
                    $sagentpay = $price - $sagentprofit;
                } else {
                    $sagentpay = 0;
                }
    
                $micaprofit = 0;
                $micaprofitObj = $profitManager->getProfit($orderid,6);
                if (!empty($micaprofitObj)) {
                    $micaprofit = $micaprofitObj->getProfit();
                }
    
                $Jprofit = 0;
                $JprofitObj = $profitManager->getProfit($orderid,7);
                if (!empty($JprofitObj)) {
                    $Jprofit = $JprofitObj->getProfit();
                }
    
                $orderCount++;
                $totalPrice = $totalPrice + $price;
                $totalSagentprofit = $totalSagentprofit + $sagentprofit;
                $totalSagentpay = $totalSagentpay + $sagentpay;
                $totalMicaprofit = $totalMicaprofit + $micaprofit;
                $totalJprofit = $totalJprofit + $Jprofit;

                if ($price == 0) {
                    $micaprofitrate = "总价为0";
                } else {
                    $micaprofitrate = round(($micaprofit/$price)*100, 2)."%";
                }
      
                if ($rowColor%2 == 0) {
                    $bgcolor = "#ffffcc";
                } else {
                    $bgcolor = "#eeeeee";
                }

                $table .=" <tr bgcolor=$bgcolor class=line>";
                $table .="  <td align=center>$orderid</td>";
                $table .="  <td align=center>$consumerName</td>";
                if ($sagentName) {
                    $table .="  <td align=center>$sagentName</td>";
                } else {
                    $table .="  <td align=center>&nbsp;&nbsp;</td>";
                }
                $table .="  <td align=center>$price&nbsp;元</td>";
                $table .="  <td align=center>$sagentprofit&nbsp;元</td>";
                $table .="  <td align=center>$sagentpay&nbsp;元</td>";
                $table .="  <td align=center>$micaprofit&nbsp;元</td>";
                $table .="  <td align=center>$micaprofitrate</td>";
                $table .="  <td align=center>$Jprofit&nbsp;元</td>";
                $table .="  <td align=center>$create_time</td>";
                if ($paystatus == 0) {
                    $table .="  <td align=center>
                                <form name=\"$orderid\" action=\"javascript:void%200\" onsubmit=\"sendData($orderid);return false\">
                                <input type=\"hidden\" name=\"orderid\" value=\"$orderid\">
                                <div id=\"$orderid\"><button type=\"submit\">确认付</button></div>
                                </form>
                                </td>";
                } else {
                    $table .="  <td align=center>已付款</td>";
                }
                $table .="  </tr>";
      
                $rowColor++;
            }
        }//判断orderList是否空

        $table .=" <tr bgcolor=$bgcolor class=line>";
        $table .="  <td align=center>总计</td>";
        $table .="  <td align=center>$orderCount&nbsp;单</td>";
        $table .="  <td align=center></td>";
        $table .="  <td align=center>$totalPrice&nbsp;元</td>";
        $table .="  <td align=center>$totalSagentprofit&nbsp;元</td>";
        $table .="  <td align=center>$totalSagentpay&nbsp;元</td>";
        $table .="  <td align=center>$totalMicaprofit&nbsp;元</td>";
        $table .="  <td align=center></td>";
        $table .="  <td align=center>$totalJprofit&nbsp;元</td>";
        $table .="  <td align=center></td>";
        $table .="  <td align=center></td>";
        $table .="  </tr>";

        $table .="</table></center>";
        $table .="</BODY></html>";

        echo $table;
    }
?>