<?php
  if ($_POST){
    foreach($_POST as $key => $val) {
	  $$key=$val;
    }

    if($orderid <= 10000000){
      exit;
    }
  }
  
  require_once("../include/config.inc.php");
  require_once("../include/mysql.inc.php");
  require_once("../include/corderpeer.inc.php");
  
  $db = new cDatabase;
  //mysql_query('set names utf8');
  $orderManager = new cOrderPeer;
  $returnFlag = $orderManager->updatePaystatus($orderid,$paystatus);
  if ($returnFlag){//ok
    echo $orderid;
  }else{
	exit;
  }
?>