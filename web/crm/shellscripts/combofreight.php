<?
  exit();
  
	require_once("../include/config.inc.php");
    session_start();
    if ((!session_is_registered("SESS_USERID")) || ($_SESSION['SESS_TYPE'] != ADMIN)){
    	Header("Location:../phpinc/main.php");
		exit();
    }

  require_once("../include/mysql.inc.php");
  require_once("../include/corderpeer.inc.php");
  
  $db = new cDatabase;
    $orderManager = new cOrderPeer;
    
	/*$orderCount = 0;
	$orderList = $orderManager->getOrderByTypeList(2,0,1000);
	$orderCount = sizeof($orderList);
	echo "type = 2 <br>";
	echo "orderCount:".$orderCount."<br>";
	 if(!empty($orderList)){
        $rowColor=1;
        foreach($orderList as $order){
  	      $order = (object)$order;
  	      $orderid = $order->getOrderid();
  	      $cfreight = $order->getCfreight();
  	      echo "orderid:".$orderid." - ".$cfreight."<br>";
		}
	}*/
	
	$orderCount = 0;
	$orderList = $orderManager->getOrderByTypeList(3,0,1000);
	$orderCount = sizeof($orderList);
	$modiCount = 0;
	echo "type = 3 <br>";
	echo "orderCount:".$orderCount."<br>";
	 if(!empty($orderList)){
        $rowColor=1;
        foreach($orderList as $order){
  	      $order = (object)$order;
  	      $orderid = $order->getOrderid();
  	      $cfreight = $order->getCfreight();
  	      if($cfreight == 1){
  	        echo "orderid:".$orderid." - ".$cfreight."<br>";
  	        $modiCount++;
  	        $retrunFlag = becomeNormalCombo($order);
  	      }
		}
	}
	echo "modiCount:".$modiCount."<br>";
	
	
	function becomeNormalCombo($order){
	  global $db;
	
	  require_once("../include/cconstantspeer.inc.php");
      $constantsManager = new cConstantsPeer;
      $rrate = $constantsManager->getNewConstants(1);//当前利率
      $rcombototalweight = $constantsManager->getNewConstants(2);//当前拼单后产品总重
	  
	  $orderid = $order->getOrderid();
	  $thisFreight = $order->getFreight();//当前实收运费
	  $thisPfreight = $order->getPfreight();//当前实际运费
	  $thisWeight = $order->getWeight();
	  $thisRate = $order->getNRate();
	  
	  //运费公式：(weight/total)*[(total+0.7)*8*5.2]
      if($thisWeight <= 0){
        $newFreight = 0;
    	$newPfreight = 0;
      }else{
    	$wzhanbi = round($thisWeight/$rcombototalweight,2);
    	if($wzhanbi < $thisWeight/$rcombototalweight) $wzhanbi = $wzhanbi+0.01;
    	$newFreight = $wzhanbi*((($rcombototalweight/1000)+0.7)*8*$thisRate);//新实收总运费，人民币
    	$newFreight = round($newFreight,2);
    	$newPfreight = $wzhanbi*((($rcombototalweight/1000)+0.7)*7*$thisRate);//新实收总运费，人民币，注意没有用$rrate！
    	$newPfreight = round($newPfreight,2);
      }
	
	  //更新profit表
      require_once("../include/cprofitpeer.inc.php");
      $profitManager = new cProfitPeer;
		
	  $thisSevenProfit = $profitManager->getProfit($orderid,7);
      $thisJProfit = $thisSevenProfit->getProfit();//净利润
      $newJProfit = $thisJProfit-($thisFreight-$thisPfreight)+($newFreight-$newPfreight);

      echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$thisJProfit."-".$newJProfit."<br>";
      $sql_profit_7 = "update profit set profit=$newJProfit where orderid=$orderid and type=7";
      $db->query($sql_profit_7);
	  return true;
	}
?>