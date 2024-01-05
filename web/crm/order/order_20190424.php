<?php
  require_once("../include/config.inc.php");
  require_once("../include/mysql.inc.php");
  require_once("../include/function.inc.php");
  require_once("../include/user.inc.php");
  CheckCookies();

	//require_once("../include/config.inc.php");
	session_start();
	if (!isset($_SESSION["SESS_USERID"])){
		Header("Location:index.php");
		exit();
	}
	//require_once("../include/mysql.inc.php");
	require_once("../include/corderpeer.inc.php");
	require_once("../include/cconsumerpeer.inc.php");
	require_once("../include/user.inc.php");
	require_once("../include/clogisticspeer.inc.php");
	require_once("../include/cprofitpeer.inc.php");
	
  // get parameters
  $consumerid = trim(@$_GET["consumerid"]);
  $start = trim(@$_GET["start"]);
  $range = trim(@$_GET["range"]);
  $msg = trim(@$_GET["msg"]);

  if(empty($range)){
    $range = 15;
  }
  
  $doSearch = trim(@$_POST["dosearch"]);
  
  if($doSearch){
    $search = trim(@$_POST["search"]);
    $start = trim(@$_POST["start"]);
  }else{//非第一搜索页
    $doSearch = trim(@$_GET["dosearch"]);
    if($doSearch){//搜索＋翻页
      $search = trim(@$_GET["search"]);
    }else{//非搜索情况
      $search = "";
    }
  }

  /*if($search != "" || $search != null){
    if($search < 10000000 || $search > 99999999){
      $search = "";
      $doSearch = 0;
    }else{
      $consumerid = $search;
    }
  }*/
?>
<html><head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type=text/css href="/style/global.css">
<script type="text/javascript" src="/ajaxjs/crm_paystatus.js"></script>
</head>
<BODY BGCOLOR=#ffffff LINK=#000099 ALINK=#cc0000 VLINK=#000099 TOMARGIN=8>
<center>
<?php
  //$titlebars = array("订单管理"=>"order.php",$name=>""); 
  $titlebars = array("订单管理"=>"order.php"); 
  $operations = array("订单管理"=>"order.php");
  $jumptarget = "cmsright";

  include("../phpinc/titlebar.php");
  
  if($_SESSION['SESS_TYPE'] == ADMIN){
  echo "<form name=searchorder method=post action=>
          <input type=hidden name=dosearch value=1>
          <input type=hidden name=start value=0>
          <table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>
          <tr class= itm bgcolor='#dddddd'>
          <td>按订单的“编号/识别码”搜索订单</td>
          <td><input type=type name=search value=$search></td>
          <td><input type=submit name=submit value=查询></td>
          </tr>
          </table>
          </form>";
  }else{
  echo "<form name=searchorder method=post action=>
          <input type=hidden name=dosearch value=1>
          <input type=hidden name=start value=0>
          <table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>
          <tr class= itm bgcolor='#dddddd'>
          <td>按订单的“识别码”搜索订单</td>
          <td><input type=type name=search value=$search></td>
          <td><input type=submit name=submit value=查询></td>
          </tr>
          </table>
          </form>";
  }

    $db = new cDatabase;
    //mysql_query('set names utf8');
    $orderManager = new cOrderPeer;

    if($_SESSION['SESS_TYPE'] == ADMIN && $doSearch && ($consumerid == null || $consumerid == "" || $consumerid == 0)){
      $total = $orderManager->getOrderCountByIdCode($search);
      $orderCount = 0;
      $orderList = $orderManager->getOrderlistByIdCode($search);
    }else{
      if($_SESSION['SESS_TYPE'] == ADMIN){
	    $total = $orderManager->getOrderCount($consumerid);
	  }else if($_SESSION['SESS_TYPE'] == SECRETARY){
	    if($doSearch){
	      $total = $orderManager->getOrderCountForSecretaryForSearch($consumerid,$search);
	    }else{
	      $total = $orderManager->getOrderCountForSecretary($consumerid);
	    }
	  }else if($_SESSION['SESS_TYPE'] == XIAOMI){
	    if($doSearch){
	      $total = $orderManager->getOrderCountForXiaomiForSearch($_SESSION['SESS_USERID'],$search);
	    }else{
	      $total = $orderManager->getOrderCountForXiaomi($_SESSION['SESS_USERID']);
	    }
	  }else{
	    if($doSearch){
	      $total = $orderManager->getOrderCountByAgentidAndIdcode($_SESSION['SESS_USERID'],$search);
	    }else{
	      $total = $orderManager->getOrderCountByAgentid($_SESSION['SESS_USERID']);
	    }
	  }
	  $orderCount = 0;
	  if($_SESSION['SESS_TYPE'] == ADMIN){
	    $orderList = $orderManager->getOrderlist($consumerid, $start, $range);
	  }else if($_SESSION['SESS_TYPE'] == SECRETARY){
	    if($doSearch){
	      $orderList = $orderManager->getOrderlistForSecretaryForSearch($consumerid, $search, $start, $range);
	    }else{
	      $orderList = $orderManager->getOrderlistForSecretary($consumerid, $start, $range);
	    }
	  }else if($_SESSION['SESS_TYPE'] == XIAOMI){
	    if($doSearch){
	      $orderList = $orderManager->getOrderlistForXiaomiForSearch($_SESSION['SESS_USERID'], $search, $start, $range);
	    }else{
	      $orderList = $orderManager->getOrderlistForXiaomi($_SESSION['SESS_USERID'], $start, $range);
	    }
	  }else{
	    if($doSearch){
	      $orderList = $orderManager->getOrderlistByAgentidAndIdcode($_SESSION['SESS_USERID'], $search, $start, $range);
	    }else{
	      $orderList = $orderManager->getOrderlistByAgentid($_SESSION['SESS_USERID'], $start, $range);
	    }
	  }
	}
	$orderCount = sizeof($orderList);

	if(!empty($msg)) {
  	  echo "<span class=cur>$msg</span>";
    }

    if($orderCount > 0) {
      $table ="<table cellpadding=1 cellspacing=1 border=0 width=100%>";
      $table .=" <tr>";
      $table .="  <td width=40% align=left nowrap class=line>";
      echo $table;
             
     if( ($start-$range) >= 0 ) {
    	$starts=$start-$range;
        echo "&laquo; <a href=\"order.php?consumerid=$consumerid&range=$range&start=$starts&dosearch=$doSearch&search=$search\">前$range</a>";
     } else {
       echo "&nbsp;";
     }
     echo "</td>";
     echo "<td width=20% align=center nowrap class=line>共有&nbsp;$total&nbsp;个有效订单</td>";
     echo "<td width=40% align=right nowrap class=line>";
     if(($start+$range) < $total ) {
 	   //$range=(($start+$range-$orderCount)<range)?($orderCount-$start):$range;
 	   $starts=$start+$range;
       echo "<a href=\"order.php?consumerid=$consumerid&range=$range&start=$starts&dosearch=$doSearch&search=$search\">后$range</a> &raquo;";
     } else {
       echo " &nbsp;";
     }
 
     echo "</td></tr></table>";
   }

  $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
  $table .=" <tr bgcolor=#dddddd class=tine>";
  if($_SESSION['SESS_TYPE'] == ADMIN){
    $table .=" <td align=center width='6%'>编号</td>";
    $table .=" <td align=center width='11%'>识别码/日期</td>";
    $table .="  <td align=center width='5%'>消费者</td>";
    //$table .="  <td align=center width='4%'>汇率</td>";
    $table .="  <td align=center width='5%'>代理人</td>";
    $table .="  <td align=center width='5%'>下单人</td>";
    $table .="  <td align=center width='6%'>实收运</td>";
    $table .="  <td align=center width='6%'>实际运</td>";
    $table .="  <td align=center width='5%'>单价</td>";
    $table .="  <td align=center width='5%'>代理</td>";
    $table .="  <td align=center width='5%'>优惠</td>";
    $table .="  <td align=center width='11%'>物流单号</td>";
    $table .="  <td align=center width='4%'>实收</td>";
    $table .="  <td align=center width='4%'>实际</td>";
    $table .="  <td align=center width='4%'>拼单</td>";
    $table .="  <td align=center width='8%'>操作</td>";
    //$table .="  <td align=center width='4%'>时间</td>";
    $table .="  <td align=center width='4%'>付款</td>";
    $table .="  <td align=center width='2%'>备</td>";
    $table .="  <td align=center width='2%'>编</td>";
    $table .="  <td align=center width='2%'>删</td>";
  }else if($_SESSION['SESS_TYPE'] == SECRETARY || $_SESSION['SESS_TYPE'] == XIAOMI){
    $table .=" <td align=center width='11%'>订单编号/识别码</td>";
    $table .="  <td align=center width='8%'>消费者</td>";
    $table .="  <td align=center width='6%'>产品Kg</td>";
    $table .="  <td align=center width='6%'>运费</td>";
    $table .="  <td align=center width='7%'>订单价格</td>";
    $table .="  <td align=center width='6%'>优惠</td>";
    $table .="  <td align=center width='13%'>物流单号</td>";
    $table .="  <td align=center width='8%'>改收取运费</td>";
    $table .="  <td align=center width='6%'>拼单</td>";
    $table .="  <td align=center width='10%'>本单操作</td>";
    $table .="  <td align=center width='7%'>时间</td>";
    $table .="  <td align=center width='8%'>付款</td>";
    $table .="  <td align=center width='4%'>备注</td>";
    //$table .="  <td align=center width='4%'>编辑</td>";
  }else{
    $table .=" <td align=center width='11%'>订单编号/识别码</td>";
    $table .="  <td align=center width='8%'>消费者</td>";
    $table .="  <td align=center width='7%'>产品Kg</td>";
    $table .="  <td align=center width='8%'>运费</td>";
    $table .="  <td align=center width='8%'>订单价格</td>";
    $table .="  <td align=center width='8%'>优惠</td>";
    $table .="  <td align=center width='13%'>物流单号</td>";
    $table .="  <td align=center width='6%'>拼单</td>";
    $table .="  <td align=center width='10%'>本单操作</td>";
    $table .="  <td align=center width='9%'>时间</td>";
    $table .="  <td align=center width='8%'>付款</td>";
    $table .="  <td align=center width='4%'>备注</td>";
    //$table .="  <td align=center width='4%'>编辑</td>";
  }
  $table .="  </tr>";

  // iterate through users, show info
  $rowColor = 0;
  $bgcolor = "";

  if(!empty($orderList)){
    $rowColor=1;
    foreach($orderList as $order){
  	  $order = (object)$order;
  	  $orderid = $order->getOrderid();
  	  $idcode = $order->getIdcode();
  	  $operatorid = $order->getOperatorid();
  	    if($_SESSION['SESS_TYPE'] == XIAOMI && $operatorid != $_SESSION['SESS_USERID']) continue;
  	  $consumerid = $order->getConsumerid();
  	    $consumerManager = new cConsumerPeer;
  	    $consumer = $consumerManager->getConsumer($consumerid);
  	    $consumerName = $consumer->getCname();
  	    
  	    //计算代理应付需要添加
  	    $consumerSagentid = $consumer->getAgentid();
  	    $user = new user;
        @$sagentName = $user->getUserbyId($consumerSagentid)->realname;
        
        //下单人
        $operatorName = $user->getUserbyId($operatorid)->realname;
  	    
  	    if(($_SESSION['SESS_USERID'] != $consumer->getAgentid()) && ($_SESSION['SESS_TYPE'] != ADMIN) && ($_SESSION['SESS_TYPE'] != SECRETARY) && ($_SESSION['SESS_TYPE'] != XIAOMI)) continue;
  	  $rate = $order->getNRate();
  	  $weight = $order->getWeight();
  	  $weightKG = $weight/1000;
  	  $freight = $order->getFreight();
  	  $pfreight = $order->getPfreight();
      $price = $order->getPrice();
      $pprice = $order->getPprice();
      $spprice = $order->getSpprice();
      $logisticsid = $order->getLogisticsid();
      $logisticscode = $order->getLogisticscode();
      
      $paystatus = $order->getPaystatus();//为锁定修改
      
      $cfreight = $order->getCfreight();
      $cpfreight = $order->getCpfreight();
      if($cpfreight == 0){
        if($paystatus == 1){
          $cpfreightStr = "未|锁";
        }else{
          $cpfreightStr = "未|<a href=\"/order/editpfreight.php?orderid=$orderid&start=$start&range=$range\">改</a>";
        }
      }else{
        if($paystatus == 1){
          $cpfreightStr = "已|锁";
        }else{
          $cpfreightStr = "已|<a href=\"/order/editpfreight.php?orderid=$orderid&start=$start&range=$range\">改</a>";
        }
      }
      $discount = $order->getDiscount();
      if($_SESSION['SESS_TYPE'] == ADMIN || $_SESSION['SESS_TYPE'] == SECRETARY || $_SESSION['SESS_TYPE'] == XIAOMI){
        if($paystatus == 1){
          $discountStr = "$discount|锁";
        }else{
          $discountStr = "$discount|<a href=\"/order/editprice.php?orderid=$orderid&start=$start&range=$range\">改</a>";
        }
      }else{
        $discountStr = "$discount";
      }
      $memo = $order->getMemo();
      $type = $order->getCType();
      if($type == 1){
        if($_SESSION['SESS_TYPE'] == ADMIN || $_SESSION['SESS_TYPE'] == SECRETARY || $_SESSION['SESS_TYPE'] == XIAOMI){
          if($paystatus == 1){
            $typeStr = "未|锁";
          }else{
            if($idcode){
              $typeStr = "未|<a href=\"/order/editcombotype.php?idcode=$idcode&start=$start&range=$range\">改</a>";
            }else{
              $typeStr = "未|<a href=\"/order/editcombotype.php?orderid=$orderid&start=$start&range=$range\">改</a>";
            }
          }
        }else{
          $typeStr = "未";
        }
      }else{//type=2,3 均为拼单
        if($_SESSION['SESS_TYPE'] == ADMIN || $_SESSION['SESS_TYPE'] == SECRETARY || $_SESSION['SESS_TYPE'] == XIAOMI){
          if($paystatus == 1){
            $typeStr = "拼|锁";
          }else{
            if($idcode){
              $typeStr = "拼|<a href=\"/order/editcombotype.php?idcode=$idcode&start=$start&range=$range\">改</a>";
            }else{
              $typeStr = "拼|<a href=\"/order/editcombotype.php?orderid=$orderid&start=$start&range=$range\">改</a>";
            }
          }
        }else{
          $typeStr = "拼";
        }
      }
      if($type == 1){
        $cfreightStr = "禁";
      }else{
        if($cfreight == 0){
          if($paystatus == 1){
            $cfreightStr = "未|锁";
          }else{
            if($idcode){
              $cfreightStr = "未|<a href=\"/order/editfreight.php?idcode=$idcode&start=$start&range=$range\">改</a>";
            }else{
              $cfreightStr = "未|<a href=\"/order/editfreight.php?orderid=$orderid&start=$start&range=$range\">改</a>";
            }
          }
        }else{
          if($paystatus == 1){
            $cfreightStr = "已|锁";
          }else{
            if($idcode){
              $cfreightStr = "已|<a href=\"/order/editfreight.php?idcode=$idcode&start=$start&range=$range\">改</a>";
            }else{
              $cfreightStr = "已|<a href=\"/order/editfreight.php?orderid=$orderid&start=$start&range=$range\">改</a>";
            }
          }
        }
      }
      $status = $order->getPstatus();
      $create_time = $order->getCreatetime();
      
      //计算代理应付
      $profitManager = new cProfitPeer;
      $sagentprofit = 0;
      $sagentprofitObj = $profitManager->getProfit($orderid,3);
      if(!empty($sagentprofitObj)){
        $sagentprofit = $sagentprofitObj->getProfit();
      }
      if(@$sagentid > 0 || $consumerSagentid > 0){
        $sagentpay = $price - $sagentprofit;
      }else{
        $sagentpay = 0;
      }
      
      if( $rowColor%2 == 0 ) {
	    $bgcolor = "#ffffcc";
      } else {
	    $bgcolor = "#eeeeee";
      }
      //if($paystatus == 1) $bgcolor = "#FF9999";

     $table .=" <tr bgcolor=$bgcolor class=line>";
     if($_SESSION['SESS_TYPE'] == ADMIN){
       $table .="  <td align=center>$orderid</td>";
     }else{
       if($idcode){
         $table .="  <td align=center>$idcode</td>";
       }else{
         $table .="  <td align=center>$orderid</td>";
       }
     }
     if($_SESSION['SESS_TYPE'] == ADMIN){
       if(!$idcode){
         $table .="  <td align=center>".substr($create_time,0,10)."</td>";
       }else{
         $table .="  <td align=center>$idcode</td>";
       }
     }
     $table .="  <td align=center>$consumerName</td>";
     //$table .="  <td align=center>$rate</td>";
     if($_SESSION['SESS_TYPE'] == ADMIN){
       if($sagentName){
         $table .="  <td align=center>$sagentName</td>";
       }else{
         $table .="  <td align=center>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
       }
     }
     if($_SESSION['SESS_TYPE'] != ADMIN) $table .="  <td align=center>$weightKG</td>";
     if($_SESSION['SESS_TYPE'] == ADMIN){
       if($operatorName){
         $table .="  <td align=center>$operatorName</td>";
       }else{
         $table .="  <td align=center>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
       }
     }
     $table .="  <td align=center>$freight</td>";
     if($_SESSION['SESS_TYPE'] == ADMIN) $table .="  <td align=center>$pfreight</td>";
     $table .="  <td align=center>$price</td>";
     if($_SESSION['SESS_TYPE'] == ADMIN) $table .="  <td align=center>$sagentpay</td>";
     $table .="  <td align=center>$discountStr</td>";
     if($logisticscode == null || $logisticscode == ""){
       $table .="  <td align=center>";
       if($_SESSION['SESS_TYPE'] == ADMIN){
         $table .="  <a href=\"/order/createlogisticscode.php?orderid=$orderid&start=$start&range=$range\">加物流</a>";
       }else{
         $table .="&nbsp;&nbsp;&nbsp;&nbsp;";
       }
       $table .="  </td>";
     }else{//有单号
       $logisticsManager = new cLogisticsPeer;
       $logistics = $logisticsManager->getLogistics($logisticsid);
       $preurl = $logistics->getPreurl();
       if($preurl == null || $preurl == ""){
         if($_SESSION['SESS_TYPE'] == ADMIN){
           $table .="  <td align=center>$logisticscode|<a href=\"/order/editlogisticscode.php?orderid=$orderid&start=$start&range=$range\">改</a></td>";
         }else{
           $table .="  <td align=center>$logisticscode</td>";
         }
       }else if($preurl == "chinz56"){
         if($_SESSION['SESS_TYPE'] == ADMIN){
           $table .="  <td align=center><a href=../logistics/prechinz56.php?logisticscode=".$logisticscode." target=_ablank>$logisticscode</a>|<a href=\"/order/editlogisticscode.php?orderid=$orderid&start=$start&range=$range\">改</a></td>";
         }else{
           $table .="  <td align=center><a href=../logistics/prechinz56.php?logisticscode=".$logisticscode." target=_ablank>$logisticscode</a></td>";
         }
       }else if($preurl == "800bestex"){
         if($_SESSION['SESS_TYPE'] == ADMIN){
           $table .="  <td align=center><a href=../logistics/pre800bestex.php?orderid=".$orderid." target=_ablank>$logisticscode</a>|<a href=\"/order/editlogisticscode.php?orderid=$orderid&start=$start&range=$range\">改</a></td>";
         }else{
           $table .="  <td align=center><a href=../logistics/pre800bestex.php?orderid=".$orderid." target=_ablank>$logisticscode</a></td>";
         }
       }else{
         if(strlen($logisticscode) > 20){
           $logisticscodeDsp = substr($logisticscode,0,13)."...";
         }else{
           $logisticscodeDsp = $logisticscode;
         }
         if($_SESSION['SESS_TYPE'] == ADMIN){
           $table .="  <td align=center><a href=$preurl".$logisticscode." target=_ablank>$logisticscodeDsp</a>|<a href=\"/order/editlogisticscode.php?orderid=$orderid&start=$start&range=$range\">改</a></td>";
         }else{
           $table .="  <td align=center><a href=$preurl".$logisticscode." target=_ablank>$logisticscodeDsp</a></td>";
         }
       }
     }
     if($_SESSION['SESS_TYPE'] == ADMIN || $_SESSION['SESS_TYPE'] == SECRETARY || $_SESSION['SESS_TYPE'] == XIAOMI) $table .="  <td align=center>$cfreightStr</td>";
     if($_SESSION['SESS_TYPE'] == ADMIN) $table .="  <td align=center>$cpfreightStr</td>";
     $table .="  <td align=center>$typeStr</td>";
     if($_SESSION['SESS_TYPE'] == ADMIN){
       if($paystatus == 1){
         $table .="  <td align=center>加|改";
       }else{
         $table .="  <td align=center><a href=\"/order/createsuborder.php?orderid=$orderid\">加</a>|<a href=\"/order/suborderlist.php?orderid=$orderid\">改</a>";
       }
     }else{
       if($paystatus == 1){
         $table .="  <td align=center>加产品|改产品";
       }else{
         if($idcode){
           $table .="  <td align=center><a href=\"/order/createsuborder.php?idcode=$idcode\">加产品</a>|<a href=\"/order/suborderlist.php?idcode=$idcode\">改产品</a>";
         }else{
           $table .="  <td align=center><a href=\"/order/createsuborder.php?orderid=$orderid\">加产品</a>|<a href=\"/order/suborderlist.php?orderid=$orderid\">改产品</a>";
         }
       }
     }
     if($_SESSION['SESS_TYPE'] == ADMIN) $table .="|<a href=\"/order/orderview.php?orderid=$orderid\" target=\"_ablank\">详</a>|<a href=\"/order/pub2sagent.php?orderid=$orderid\" target=\"_ablank\">出</a></td>";
     if($_SESSION['SESS_TYPE'] == ADMIN) {
       //$table .="  <td align=center>".substr($create_time,5,5)."</td>";
       //删除了时间
     }else{
       $table .="  <td align=center>".substr($create_time,0,10)."</td>";
     }
     if($paystatus == 0){
       if($_SESSION['SESS_TYPE'] == ADMIN) {
         $table .="  <td align=center>
                       <form name=\"$orderid\" action=\"javascript:void%200\" onsubmit=\"sendData($orderid,1);return false\">
                       <input type=\"hidden\" name=\"orderid\" value=\"$orderid\">
                       <input type=\"hidden\" name=\"paystatus\" value=\"1\">
                         <div id=\"$orderid\"><button style=\"color:red;\" type=\"submit\" onclick=\"javascript: return confirm('是否确认 $consumerName 已付款?')\">未</button></div>
                       </form>
                     </td>";
       }else{
         $table .="  <td align=center><font color=red>未付款</font></td>";
       }
     }else{
       if($_SESSION['SESS_TYPE'] == ADMIN) {
         $table .="  <td align=center valign=middle>
                       <form name=\"$orderid\" action=\"javascript:void%200\" onsubmit=\"sendData($orderid,0);return false\">
                       <input type=\"hidden\" name=\"orderid\" value=\"$orderid\">
                       <input type=\"hidden\" name=\"paystatus\" value=\"0\">
                         <div id=\"$orderid\"><button type=\"submit\" onclick=\"javascript: return confirm('将 $consumerName 的已付款订单改为未付款？这将解除订单锁定！')\">付</button></div>
                       </form>
                     </td>";
       }else{
         $table .="  <td align=center>已付/锁定</td>";
       }
     }
     $table .="  <td align=center>";
     if($idcode){
       $table .="   <a href=\"/order/editmemo.php?idcode=$idcode&start=$start&range=$range\"><img src=\"../images/memo.png\" align=\"bottom\" border=0></a></td>";
     }else{
       $table .="   <a href=\"/order/editmemo.php?orderid=$orderid&start=$start&range=$range\"><img src=\"../images/memo.png\" align=\"bottom\" border=0></a></td>";
     }
     if($_SESSION['SESS_TYPE'] == ADMIN){
       if($paystatus == 1){
         $table .="  <td align=center>";
         $table .="   锁</td>";
         $table .="  <td align=center >";
         $table .="   锁</td></tr>";
       }else{
         $table .="  <td align=center>";
         $table .="   <a href=\"/order/editorder.php?orderid=$orderid&start=$start&range=$range\"><img src=\"../images/edit.gif\" align=\"bottom\" border=0></a></td>";
         $table .="  <td align=center >";
         $table .="   <a href=\"/order/delorder.php?orderid=$orderid&start=$start&range=$range\"><img src=\"../images/del.gif\" align=\"bottom\" border=0></a></td></tr>";
       }
     }
     $rowColor++;
   }
  }
  

$table .="</table></center>";
$table .="</BODY></html>";

echo $table;
?>