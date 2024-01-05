<?php
    require_once("../include/config.inc.php");
    require_once("../include/mysql.inc.php");
    require_once("../include/function.inc.php");
    require_once("../include/user.inc.php");
    CheckCookies();

	//require_once("../include/config.inc.php");
	session_start();
	if ($_SESSION['SESS_TYPE'] != ADMIN && $_SESSION['SESS_TYPE'] != SECRETARY && $_SESSION['SESS_TYPE'] != XIAOMI){
		Header("Location:index.php");
		exit();
	}

	$productid = trim(@$_GET["id"]);
	
	require_once("../include/cconstantspeer.inc.php");
	require_once("../include/cproductpeer.inc.php");
	
	$db = new cDatabase;
    $productManager = new cProductPeer;
    $product = $productManager->getProduct($productid);
    $productName = $product->getCname();
    $ppricenz = $product->getPpricenz();
  	$spricecn = $product->getSpricecn();
  	
  	$constantsManager = new cConstantsPeer;
  	$rate = $constantsManager->getNewConstants(1);
  	
  	$jingjiaRMB = $ppricenz*$rate;
  	$lirunAll = $spricecn - $jingjiaRMB;
  	$profit = $spricecn - $jingjiaRMB;
  	
  	if ($lirunAll <= 10) {
  		//全部为: 人民币进价+(人民币售价-人民币进价)/2 
  		$greenAgent = round($jingjiaRMB + $lirunAll/2);
  		$putongAgent = round($jingjiaRMB + $lirunAll/2);
  		$seniorAgent = round($jingjiaRMB + $lirunAll/2);
  	} else {
  	    //绿叶代理价格 = 人民币进价+(人民币售价-人民币进价)/2 
  	    $greenAgent = round($jingjiaRMB + $lirunAll/2);
  	    //普通代理价格 = 绿叶代理价格+(人民币售价-人民币进价)/4 
  	    $putongAgent = round($greenAgent + $lirunAll/4);
  	    //高级代理价格 = 绿叶代理价格-(人民币售价-人民币进价)/5
  	    $seniorAgent = round($greenAgent - $lirunAll/5);
  	}

	echo "产品中文名称：".$productName."<br>";
	echo "=======<br>";
	echo "进价RMB/NZ：".$jingjiaRMB."/".$ppricenz."<br>";
	echo "售价RMB：".$spricecn."<br>";
	echo "利润RMB：".$profit."<br>";
	echo "=======<br>";
	echo "普通代理价格：".$putongAgent."<br>";
	echo "绿叶代理价格：".$greenAgent."<br>";
	echo "高级代理价格：".$seniorAgent."<br>";
?>