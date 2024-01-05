<?php
    require_once("../include/config.inc.php");
    require_once("../include/mysql.inc.php");
    require_once("../include/function.inc.php");
    require_once("../include/user.inc.php");
    CheckCookies();

    session_start();
    if (!isset($_SESSION["SESS_USERID"])) {
		    Header("Location:index.php");
		    exit();
	  }

	  require_once("../include/corderpeer.inc.php");
	  require_once("../include/cconsumerpeer.inc.php");
	
    // get parameters
    $consumerid = 0;
    $start = trim(@$_GET["start"]);
    $range = trim(@$_GET["range"]);
    $msg = trim(@$_GET["msg"]);

    if (empty($range)) {
        $range = 15;
    }
  
    $doSearch = trim(@$_POST["dosearch"]);
  
    if ($doSearch) {
        $search = trim($_POST["search"]);
        $start = trim($_POST["start"]);
    } else {//非第一搜索页
        $doSearch = trim(@$_GET["dosearch"]);
        if ($doSearch) {//搜索＋翻页
            $search = trim($_GET["search"]);
        } else {//非搜索情况
            $search = "";
        }
    }
  
    if ($search != "" || $search != null) {
        if ($search < 10000000 || $search > 99999999) {
            $search = "";
            $doSearch = 0;
        } else {
            $consumerid = $search;
        }
    }
?>
<html><head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type=text/css href="/style/global.css">
</head>
<BODY BGCOLOR=#ffffff LINK=#000099 ALINK=#cc0000 VLINK=#000099 TOMARGIN=8>
<center>
<?php
    $titlebars = array("订单管理"=>"order.php","出单视图"=>"puborder.php"); 
    $operations = array("出单视图"=>"puborder.php");
    $jumptarget = "cmsright";
    $orderIDHtmlStr = "编号/订单号";

    include("../phpinc/titlebar.php");
  
    $db = new cDatabase;
    //mysql_query('set names utf8');
    $orderManager = new cOrderPeer;
    if ($_SESSION['SESS_TYPE'] == ADMIN) {
	      $total = $orderManager->getOrderCount($consumerid);
	  } else if ($_SESSION['SESS_TYPE'] == SECRETARY) {
	      $total = $orderManager->getOrderCountForSecretary($consumerid);
	  } else if ($_SESSION['SESS_TYPE'] == XIAOMI) {
	      $total = $orderManager->getOrderCountForXiaomi($_SESSION['SESS_USERID']);
	  } else {
	      $total = $orderManager->getOrderCountByAgentid($_SESSION['SESS_USERID']);
	  }
	  $orderCount = 0;
	  if ($_SESSION['SESS_TYPE'] == ADMIN) {
	      $orderList = $orderManager->getOrderlist($consumerid, $start, $range);
	  } else if ($_SESSION['SESS_TYPE'] == SECRETARY) {
	      $orderList = $orderManager->getOrderlistForSecretary($consumerid, $start, $range);
	  } else if ($_SESSION['SESS_TYPE'] == XIAOMI) {
	      $orderList = $orderManager->getOrderlistForXiaomi($_SESSION['SESS_USERID'], $start, $range);
	  } else {
	      $orderList = $orderManager->getOrderlistByAgentid($_SESSION['SESS_USERID'], $start, $range);
	  }
	  $orderCount = sizeof($orderList);

	  if (!empty($msg)) {
  	    echo "<span class=cur>$msg</span>";
    }

    if ($orderCount > 0) {
        $table ="<table cellpadding=1 cellspacing=1 border=0 width=100%>";
        $table .=" <tr>";
        $table .="  <td width=50% align=left nowrap class=line>";
        echo $table;
             
        if (($start-$range) >= 0) {
    	      $starts=$start-$range;
            echo "&laquo; <a href=\"puborder.php?consumerid=$consumerid&range=$range&start=$starts&dosearch=$doSearch&search=$search\">前$range</a>";
        } else {
            echo "&nbsp;";
        }
        echo "</td>
              <td width=50% align=right nowrap class=line>";
        if (($start+$range) < $total) {
 	          $starts = $start+$range;
            echo "<a href=\"puborder.php?consumerid=$consumerid&range=$range&start=$starts&dosearch=$doSearch&search=$search\">后$range</a> &raquo;";
        } else {
            echo " &nbsp;";
        }
 
        echo "</td></tr></table>";
    }

    $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
    $table .=" <tr bgcolor=#dddddd class=tine>";
    if ($_SESSION['SESS_TYPE'] == ADMIN) {
        $table .= " <td align=center width='7%'>订单编号</td>";
        $table .= " <td align=center width='11%'>".$orderIDHtmlStr."</td>";
        $table .= "  <td align=center width='10%'>消费者</td>";
        $table .= "  <td align=center width='7%'>实收运费</td>";
        $table .= "  <td align=center width='7%'>订单价格</td>";
        $table .= "  <td align=center width='7%'>订单优惠</td>";
        $table .= "  <td align=center width='7%'>订货订单</td>";
        $table .= "  <td align=center width='7%'>客户订单</td>";
        $table .= "  <td align=center width='15%'>代理订单</td>";
        $table .= "  <td align=center width='7%'>批发订单</td>";
        $table .= "  <td align=center width='7%'>订单详情</td>";
        $table .= "  <td align=center width='10%'>订单时间</td>";
    } else if ($_SESSION['SESS_TYPE'] == XIAOMI) {
        $table .= " <td align=center width='14%'>".$orderIDHtmlStr."</td>";
        $table .= "  <td align=center width='12%'>消费者</td>";
        $table .= "  <td align=center width='12%'>运费</td>";
        $table .= "  <td align=center width='12%'>订单价格</td>";
        $table .= "  <td align=center width='12%'>订单优惠</td>";
        $table .= "  <td align=center width='12%'>客户订单</td>";
        $table .= "  <td align=center width='12%'>批发订单</td>";
        $table .= "  <td align=center width='14%'>订单时间</td>";
    } else if ($_SESSION['SESS_TYPE'] == SECRETARY) {
        $table .= " <td align=center width='12%'>".$orderIDHtmlStr."</td>";
        $table .= "  <td align=center width='12%'>消费者</td>";
        $table .= "  <td align=center width='10%'>运费</td>";
        $table .= "  <td align=center width='10%'>订单价格</td>";
        $table .= "  <td align=center width='10%'>订单优惠</td>";
        $table .= "  <td align=center width='10%'>客户订单</td>";
        $table .= "  <td align=center width='12%'>代理订单</td>";
        $table .= "  <td align=center width='10%'>批发订单</td>";
        $table .= "  <td align=center width='14%'>订单时间</td>";
    } else {
        $table .= " <td align=center width='12%'>".$orderIDHtmlStr."</td>";
        $table .= "  <td align=center width='12%'>消费者</td>";
        $table .= "  <td align=center width='12%'>运费</td>";
        $table .= "  <td align=center width='12%'>订单价格</td>";
        $table .= "  <td align=center width='12%'>订单优惠</td>";
        $table .= "  <td align=center width='12%'>客户订单</td>";
        $table .= "  <td align=center width='12%'>代理订单</td>";
        $table .= "  <td align=center width='16%'>订单时间</td>";
    }
    $table .="  </tr>";

    // iterate through users, show info
    $rowColor = 0;
    $bgcolor = "";

    if (!empty($orderList)) {
        $rowColor=1;
        foreach ($orderList as $order) {
  	        $order = (object)$order;
  	        $orderid = $order->getOrderid();
  	        $idcode = $order->getIdcode();
  	        $consumerid = $order->getConsumerid();
  	        $consumerManager = new cConsumerPeer;
  	        $consumer = $consumerManager->getConsumer($consumerid);
  	        $consumerName = $consumer->getCname();
  	        $sagentid = $consumer->getAgentid();
  	        $rate = $order->getNRate();
  	        $weight = $order->getWeight();
  	        $weightKG = $weight/1000;
  	        $freight = $order->getFreight();
  	        $pfreight = $order->getPfreight();
  	        $price = $order->getPrice();
  	        $pprice = $order->getPprice();
  	        $logisticsid = $order->getLogisticsid();
  	        $logisticscode = $order->getLogisticscode();
  	        $discount = $order->getDiscount();
  	        $type = $order->getCType();
  	        $status = $order->getPstatus();
  	        $create_time = $order->getCreatetime();
      
  	        if ($rowColor%2 == 0) {
                $bgcolor = "#ffffcc";
            } else {
                $bgcolor = "#eeeeee";
            }

  	        $table .= " <tr bgcolor=$bgcolor class=line>";
  	        if ($_SESSION['SESS_TYPE'] == ADMIN) {
                $table .= "  <td align=center>$orderid</td>";
                if (!$idcode) {
                    $table .= "  <td align=center>&nbsp;&nbsp;</td>";
                } else {
                    $table .= "  <td align=center>$idcode</td>";
                }
            } else {
                if ($idcode) {
                    $table .= "  <td align=center>$idcode</td>";
                } else{
                    $table .= "  <td align=center>$orderid</td>";
                }
            }
            $table .= "  <td align=center>$consumerName</td>";
            $table .= "  <td align=center>$freight</td>";
            $table .= "  <td align=center>$price</td>";
            $table .= "  <td align=center>$discount</td>";
            if ($_SESSION['SESS_TYPE'] == ADMIN) {
                if ($type > 1) {
                    $table .= "  <td align=center>拼单</td>";
                } else {
                    $table .= "  <td align=center><a href=\"/order/pub2order.php?orderid=$orderid\" target=\"_ablank\">订货订单</a></td>";
                }
            }
            if ($idcode) {
                $table .= "  <td align=center><a href=\"/order/pub2consumer.php?idcode=$idcode\" target=\"_ablank\">客户订单</a></td>";
            } else {
                $table .= "  <td align=center><a href=\"/order/pub2consumer.php?orderid=$orderid\" target=\"_ablank\">客户订单</a></td>";
            }
            if ($_SESSION['SESS_TYPE'] == ADMIN) {
                if ($sagentid > 0) {
                    $table .= "  <td align=center><a href=\"/order/pub2sagent.php?orderid=$orderid\" target=\"_ablank\">代理订单</a></td>";
                } else {
                    $table .= "  <td align=center>无代理|非看<a href=\"/order/pub2sagent.php?orderid=$orderid\" target=\"_ablank\">代理订单</a></td>";
                }
            } else if ($_SESSION['SESS_TYPE'] == SECRETARY) {
                if ($sagentid > 0) {
                    if ($idcode) {
                        $table .= "  <td align=center><a href=\"/order/pub2sagent.php?idcode=$idcode\" target=\"_ablank\">代理订单</a></td>";
                    } else {
                        $table .= "  <td align=center><a href=\"/order/pub2sagent.php?orderid=$orderid\" target=\"_ablank\">代理订单</a></td>";
                    }
                } else {
                    $table .= "  <td align=center>无代理订单</td>";
                }
                if ($idcode) {
                    $table .= "  <td align=center><a href=\"/order/wholesale.php?idcode=$idcode\" target=\"_ablank\">批发订单</a></td>";
                } else {
                    $table .= "  <td align=center><a href=\"/order/wholesale.php?orderid=$orderid\" target=\"_ablank\">批发订单</a></td>";
                }
            } else if ($_SESSION['SESS_TYPE'] == XIAOMI) {
                if ($idcode) {
                    $table .= "  <td align=center><a href=\"/order/wholesale.php?idcode=$idcode\" target=\"_ablank\">批发订单</a></td>";
                } else {
                    $table .= "  <td align=center><a href=\"/order/wholesale.php?orderid=$orderid\" target=\"_ablank\">批发订单</a></td>";
                }
            } else if ($_SESSION['SESS_TYPE'] == AGENT) {
                if ($idcode) {
                    $table .= "  <td align=center><a href=\"/order/pub2sagent.php?idcode=$idcode\" target=\"_ablank\">代理订单</a></td>";
                } else {
                    $table .="  <td align=center><a href=\"/order/pub2sagent.php?orderid=$orderid\" target=\"_ablank\">代理订单</a></td>";
                }
            }
            if ($_SESSION['SESS_TYPE'] == ADMIN) $table .= "  <td align=center><a href=\"/order/wholesale.php?idcode=$idcode\" target=\"_ablank\">批发订单</a></td>";
            if ($_SESSION['SESS_TYPE'] == ADMIN) $table .= "  <td align=center><a href=\"/order/orderview.php?orderid=$orderid\" target=\"_ablank\">订单详情</a></td>";
            if ($_SESSION['SESS_TYPE'] == ADMIN) {
                $table .= "  <td align=center>".substr($create_time,0,10)."</td></tr>";
            } else {
                $table .="  <td align=center>$create_time</td></tr>";
            }
            $rowColor++;
        }
    }
  
    $table .="</table></center>";
    $table .="</BODY></html>";

    echo $table;
?>
