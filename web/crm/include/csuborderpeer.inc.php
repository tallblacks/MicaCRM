<?php
	require_once("csuborder.inc.php");
	
	class cSuborderPeer{   
    	function getSuborder($orderid,$productid){
    		$sql = "select suborderid,orderid,productid,price,ordernum,weight,supermarket,type,status,create_time from suborder where orderid=$orderid and productid=$productid";
    		global $db;
    	
    		$result=$db->query($sql);
    		$query_num = $db->num_rows($result);
    		if($query_num == 0){
    			return false;
    		}
    	
    		if($row = $db->fetch_object($result)){
    			return $this->Load($row);
    		}
    	}
    	
    	function checkSuborder($orderid,$productid){
			$sql = "select count(suborderid) as total from suborder where orderid=$orderid and productid=$productid";
			global $db;
				
		  	$result=$db->query($sql);
    		if($row = $db->fetch_array($result)){
    			return $row['total'];
    		}
    	}
    	
    	function getSuborderCount($orderid){
			if($orderid == 0){
				$sql = "select count(suborderid) as total from suborder";
			}else{
				$sql = "select count(suborderid) as total from suborder where orderid=".$orderid;
			}
			global $db;
				
		  	$result=$db->query($sql);
    		if($row = $db->fetch_array($result)){
    			return $row['total'];
    		}
		}
		
		function getSuborderlist($orderid,$startIndex,$numResults){		
			if($orderid == 0){
				$sql = "select suborderid,orderid,productid,price,ordernum,weight,supermarket,type,status,create_time from suborder order by suborderid desc";
			}else{
				$sql = "select suborderid,orderid,productid,price,ordernum,weight,supermarket,type,status,create_time from suborder where orderid=$orderid order by suborderid desc";
			}

			global $db;
			
			$result=$db->query($sql);
    		$query_num = $db->num_rows($result);
    		if($query_num == 0){
    			return false;
    		}
    		$i = 0;
    		$j = 0;
  			$rslist = array();
    		while($row = $db->fetch_object($result)){
    			if($i < $startIndex){
    				$i++;
    				continue;
    			}
    			if($j < $numResults) {
          			$rslist[] = $this->load($row);
          			$j++;
    			}
    		}
    		return $rslist;
		}
		
		function getSuborderCountByProductid($productid){
			if($productid == 0){
				$sql = "select count(suborderid) as total from suborder";
			}else{
				$sql = "select count(suborderid) as total from suborder where productid=".$productid;
			}
			global $db;
				
		  	$result=$db->query($sql);
    		if($row = $db->fetch_array($result)){
    			return $row['total'];
    		}
		}
		
		function getSuborderlistByProductid($productid,$startIndex,$numResults){		
			if($productid == 0){
				$sql = "select suborderid,orderid,productid,price,ordernum,weight,supermarket,type,status,create_time from suborder order by suborderid desc";
			}else{
				$sql = "select suborderid,orderid,productid,price,ordernum,weight,supermarket,type,status,create_time from suborder where productid=$productid order by suborderid desc";
			}

			global $db;
			
			$result=$db->query($sql);
    		$query_num = $db->num_rows($result);
    		if($query_num == 0){
    			return false;
    		}
    		$i = 0;
    		$j = 0;
  			$rslist = array();
    		while($row = $db->fetch_object($result)){
    			if($i < $startIndex){
    				$i++;
    				continue;
    			}
    			if($j < $numResults) {
          			$rslist[] = $this->load($row);
          			$j++;
    			}
    		}
    		return $rslist;
		}
		
		function create($suborder){
			$sql = "insert into suborder(orderid,productid,price,ordernum,weight,supermarket,type,status,create_time) values (".$suborder->getOrderid().",".$suborder->getProductid().",".$suborder->getPrice().",".$suborder->getOrdernum().",".$suborder->getWeight().",".$suborder->getSupermarket().",".$suborder->getCType().",".$suborder->getPstatus().",now())";

			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				if($this->orderTriggerAdd($suborder)){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		
		function update($suborder){
			global $db;
			$orderid = $suborder->getOrderid();
			$productid = $suborder->getProductid();
			
			$oldSuborder = $this->getSuborder($orderid,$productid);
			$oldPrice = $oldSuborder->getPrice();
			$oldOrdernum = $oldSuborder->getOrdernum();
			//echo $oldPrice."-".$oldOrdernum;
			
			$sql = "update suborder set price=".$suborder->getPrice().",ordernum=".$suborder->getOrdernum()." where orderid=$orderid and productid=$productid";
			
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				if($this->orderTriggerEdit($suborder,$oldPrice,$oldOrdernum)){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		
		function delete($orderid,$productid){
			global $db;
			
			$suborder = $this->getSuborder($orderid,$productid);
			
			$sql = "delete from suborder where orderid=$orderid and productid=$productid";
			
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				if($this->orderTriggerDel($suborder)){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
    	
    	function Load($row){
    		$suborder = new cSuborder;
    		$suborder->setSuborderid($row->suborderid);
    		$suborder->setOrderid($row->orderid);
    		$suborder->setProductid($row->productid);
    		$suborder->setPrice($row->price);
    		$suborder->setOrdernum($row->ordernum);
    		$suborder->setWeight($row->weight);
    		$suborder->setSupermarket($row->supermarket);
    		$suborder->setCType($row->type);
    		$suborder->setPstatus($row->status);
    		$suborder->setCreatetime($row->create_time);
    		return $suborder;
    	}
    	
    	function orderTriggerAdd($suborder){
    		global $db;
    		require_once("../include/cconstantspeer.inc.php");
    		$constantsManager = new cConstantsPeer;
    		$rrate = $constantsManager->getNewConstants(1);
    		$rcombototalweight = $constantsManager->getNewConstants(2);//当前拼单后产品总重
    		$packageZhiWeightG = $constantsManager->getNewConstants(3);//直邮包装重，克
    		$packagePinWeightG = $constantsManager->getNewConstants(4);//拼单包装重，克
    		
    		//更新crmorder表
    		require_once("../include/corderpeer.inc.php");
    		require_once("../include/cproductpeer.inc.php");
    		require_once("../include/cconsumerpeer.inc.php");
    		$orderManager = new cOrderPeer;
    		$thisOrderid = $suborder->getOrderid();
    		$thisOrder = $orderManager->getOrder($thisOrderid);

			$subWeight = $suborder->weight;
			$subOrdernum = $suborder->ordernum;
    		$subTotalWeight = $subWeight*$subOrdernum;//本产品总重量
    		$thisOrderWeight = $thisOrder->getWeight();
    		$newWeight = $thisOrderWeight+$subTotalWeight;//本订单新的重量
    		
    		$thisRate = $thisOrder->getNRate();
    		$thisType = $thisOrder->getCType();
    		
    		if($thisType == 1){//normal order
    		  if($newWeight == 0){//没有运费
    		    $newFreight = 0;
    		    $newPfreight = 0;
    		  }else if(($newWeight+$packageZhiWeightG) < 1000){//不到1公斤，按1公斤收费
    		    $newFreight = 1*8*$thisRate;//新实收总运费，(总KG+0.7KG)*8新西兰元*本单汇率，人民币
    		    $newPfreight = 1*7*($rrate-0.1);//新实付运费，(总KG+0.7KG}*7新西兰元*(真实汇率-0.1)，人民币
    		  }else{
    		    $newFreight = (($newWeight+$packageZhiWeightG)/1000)*8*$thisRate;//新实收总运费，(总KG+0.7KG)*8新西兰元*本单汇率，人民币
    		    $newPfreight = (($newWeight+$packageZhiWeightG)/1000)*7*($rrate-0.1);//新实付运费，(总KG+0.7KG}*7新西兰元*(真实汇率-0.1)，人民币
    		  }
    		}else{//combo
    		  //运费公式：(weight/total)*[(total+0.7)*8*5.2]
    		  if($newWeight <= 0){
    		    $newFreight = 0;
    		    $newPfreight = 0;
    		  }else{
    		    $wzhanbi = round($newWeight/$rcombototalweight,2);
    		    if($wzhanbi < $newWeight/$rcombototalweight) $wzhanbi = $wzhanbi+0.01;
    		    $newFreight = $wzhanbi*((($rcombototalweight+$packagePinWeightG)/1000)*8*$thisRate);//新实收总运费，人民币
    		    $newFreight = round($newFreight,2);
    		    $newPfreight = $wzhanbi*((($rcombototalweight+$packagePinWeightG)/1000)*7*($rrate-0.1));//新实收总运费，人民币
    		    $newPfreight = round($newPfreight,2);
    		  }
    		}
    		
    		$thisPrice = $thisOrder->getPrice();//原总价
    		$thisFreight = $thisOrder->getFreight();//当前实收运费
    		$subPrice = $suborder->price;
    		$subPriceTotal = $subPrice*$subOrdernum;//本产品总价，人民币
    		$newPrice = $thisPrice+$subPriceTotal-$thisFreight+$newFreight;//新总价：原总价＋本产品总价－原总运费＋新总运费，人民币
    		
    		$subProductid = $suborder->productid;
    		$productManager = new cProductPeer;
  			$product = $productManager->getProduct($subProductid);
  			$subPpricenz = $product->getPpricenz();
  			$subPprice = $subPpricenz*$subOrdernum*($rrate-0.1);//真实的进货总价，用真实汇率
  			$subSpprice = $subPpricenz*$subOrdernum*$thisRate;//代理看到的进货总价，用订单汇率
  			$thisPprice = $thisOrder->getPprice();//当前真实总进货价格
  			$thisSpprice = $thisOrder->getSpprice();//当前代理看到总进货价格
  			$thisPfreight = $thisOrder->getPfreight();//当前实际运费
  			$newPprice = $thisPprice+$subPprice-$thisPfreight+$newPfreight;//新进货真实总价，人民币，用的是实际运费，也就是实际进货总价
  			$newSpprice = $thisSpprice+$subSpprice-$thisFreight+$newFreight;//新进货代理总价
  			
  			$sql_order = "update crmorder set weight=$newWeight,freight=$newFreight,pfreight=$newPfreight,price=$newPrice,pprice=$newPprice,spprice=$newSpprice where orderid=$thisOrderid";
			$db->query($sql_order);
			if(mysqli_affected_rows($db->link_id) <= 0 ){
				return false;
			}
    		
    		//更新profit表
    		require_once("../include/cprofitpeer.inc.php");
    		$profitManager = new cProfitPeer;
    		$thisProfitCount = $profitManager->getProfitCount($thisOrderid);
    		
    		$pAgentid = $product->getAgentid();//检查有无代购人
    		
    		$consumerid = $thisOrder->getConsumerid();
    		$consumerManager = new cConsumerPeer;
    		$consumer = $consumerManager->getConsumer($consumerid);
    		$sAngentid = $consumer->getAgentid();//检查有无代理人
    		
    		if($thisProfitCount == 0){//无记录
    		  $subTotalProfit = $subPriceTotal-($subPpricenz*$subOrdernum*($rrate-0.1));
    		  $newTotalProfit = $subTotalProfit;//实际总利润＝本产品实际总利润
    		  
    		  $subSTotalProfit = $subPriceTotal-($subPpricenz*$subOrdernum*$thisRate);//当前产品的代理总利润，使用的是订单汇率
    		  $newSTotalProfit = $subSTotalProfit;//代理总利润：原代理总利润+本产品代理总利润
    		  
    		  if($pAgentid > 0){//有代购
    		    $newPagentProfit = $subTotalProfit/2;
    		  }else{
    		    $newPagentProfit = 0;
    		  }
    		  
    		  if($pAgentid > 0){//有代购
    		    $newSPagentProfit = $subSTotalProfit/2;
    		    $subSPagentProfit = $subSTotalProfit/2;//本产品伪代购利润
    		  }else{
    		    $newSPagentProfit = 0;
    		    $subSPagentProfit = 0;//本产品伪代购利润
    		  }
    		  
    		  if($sAngentid > 0){//有代理
    		    $subSagentProfit = ($subSTotalProfit-$subSPagentProfit)/2;//本产品代理利润
    		    $newSagentProfit = $subSagentProfit;//代理总利润：本产品新代理总利润
    	        $newYzProfit = $newSagentProfit;
    		  }else{
    		    $newSagentProfit = 0;
    		    $newYzProfit = $newSTotalProfit-$newSPagentProfit;
    		  }
    		}else{//有记录
    		  $thisOneProfit = $profitManager->getProfit($thisOrderid,1);
    		  $thisTotalProfit = $thisOneProfit->getProfit();//原实际总利润
    		  $subTotalProfit = $subPriceTotal-($subPpricenz*$subOrdernum*($rrate-0.1));//当前产品的实际总利润，使用的是系统汇率
    		  $newTotalProfit = $thisTotalProfit+$subTotalProfit;//实际总利润：原总利润+本产品实际总利润
    		  
    		  $thisTwoProfit = $profitManager->getProfit($thisOrderid,2);
    		  $thisSTotalProfit = $thisTwoProfit->getProfit();//代理总利润
    		  $subSTotalProfit = $subPriceTotal-($subPpricenz*$subOrdernum*$thisRate);//当前产品的代理总利润，使用的是订单汇率
    		  $newSTotalProfit = $thisSTotalProfit+$subSTotalProfit;//代理总利润：原代理总利润+本产品代理总利润
    		  
    		  $thisFourProfit = $profitManager->getProfit($thisOrderid,4);
    		  $thisPagentProfit = $thisFourProfit->getProfit();//原代购人利润
    		  if($pAgentid > 0){//有代购
    		    $newPagentProfit = $thisPagentProfit+($subTotalProfit/2);  //原代购人利润+本产品真实总利润/2
    		  }else{
    		    $newPagentProfit = $thisPagentProfit;
    		  }
    		  
    		  $thisFiveProfit = $profitManager->getProfit($thisOrderid,5);
    		  $thisSPagentProfit = $thisFiveProfit->getProfit();//原伪代购人利润
    		  if($pAgentid > 0){//有代购
    		    $newSPagentProfit = $thisSPagentProfit+($subSTotalProfit/2);
    		    $subSPagentProfit = $subSTotalProfit/2;//本产品伪代购利润
    		  }else{
    		    $newSPagentProfit = $thisSPagentProfit;
    		    $subSPagentProfit = 0;//本产品伪代购利润
    		  }
    		  
    		  $thisThreeProfit = $profitManager->getProfit($thisOrderid,3);
    		  $thisSagentProfit = $thisThreeProfit->getProfit();//原代理人利润
    		  if($sAngentid > 0){//有代理
    		    $subSagentProfit = ($subSTotalProfit-$subSPagentProfit)/2;//本产品代理利润
    		    $newSagentProfit = $thisSagentProfit+$subSagentProfit;
    		    //代理总利润：原代理总利润+本产品新代理总利润
    	        $newYzProfit = $newSagentProfit;
    		  }else{
    		    $newSagentProfit = 0;
    		    $newYzProfit = $newSTotalProfit-$newSPagentProfit;
    		  }
    		}
    		
    		$newJProfit = ($newTotalProfit-$newPagentProfit-$newSagentProfit)+($newFreight-$newPfreight);
    		//净利润：（总利润-代购人利润-代理利润)+运费
    		
    		if($thisProfitCount == 0){//无记录
    		  $sql_profit_1 = "insert into profit(orderid,profit,type,create_time) values ($thisOrderid,$newTotalProfit,1,now())"; //总利润
    		  $sql_profit_2 = "insert into profit(orderid,profit,type,create_time) values ($thisOrderid,$newSTotalProfit,2,now())"; //代理看到总利润
    		  $sql_profit_3 = "insert into profit(orderid,profit,type,create_time) values ($thisOrderid,$newSagentProfit,3,now())";   //代理利润
    		  $sql_profit_4 = "insert into profit(orderid,profit,type,create_time) values ($thisOrderid,$newPagentProfit,4,now())";  //代购利润
    		  $sql_profit_5 = "insert into profit(orderid,profit,type,create_time) values ($thisOrderid,$newSPagentProfit,5,now())";  //伪代购利润
    		  $sql_profit_6 = "insert into profit(orderid,profit,type,create_time) values ($thisOrderid,$newYzProfit,6,now())";   //姚竹利润
    		  $sql_profit_7 = "insert into profit(orderid,profit,type,create_time) values ($thisOrderid,$newJProfit,7,now())";   //净利润
    		}else{
    		  $sql_profit_1 = "update profit set profit=$newTotalProfit where orderid=$thisOrderid and type=1";
    		  $sql_profit_2 = "update profit set profit=$newSTotalProfit where orderid=$thisOrderid and type=2";
    		  $sql_profit_3 = "update profit set profit=$newSagentProfit where orderid=$thisOrderid and type=3";
    		  $sql_profit_4 = "update profit set profit=$newPagentProfit where orderid=$thisOrderid and type=4";
    		  $sql_profit_5 = "update profit set profit=$newSPagentProfit where orderid=$thisOrderid and type=5";
    		  $sql_profit_6 = "update profit set profit=$newYzProfit where orderid=$thisOrderid and type=6";
    		  $sql_profit_7 = "update profit set profit=$newJProfit where orderid=$thisOrderid and type=7";
    		}
    		
    		$db->query($sql_profit_1);
    		$db->query($sql_profit_2);
    		$db->query($sql_profit_3);
    		$db->query($sql_profit_4);
    		$db->query($sql_profit_5);
    		$db->query($sql_profit_6);
    		$db->query($sql_profit_7);
			return true;
    	}
    	
    	function orderTriggerEdit($suborder,$oldPrice,$oldOrdernum){
    		global $db;
    		require_once("../include/cconstantspeer.inc.php");
    		$constantsManager = new cConstantsPeer;
    		$rrate = $constantsManager->getNewConstants(1);//当前利率
    		$rcombototalweight = $constantsManager->getNewConstants(2);//当前拼单后产品总重
    		$packageZhiWeightG = $constantsManager->getNewConstants(3);//直邮包装重，克
    		$packagePinWeightG = $constantsManager->getNewConstants(4);//拼单包装重，克
    		
    		//更新crmorder表
    		require_once("../include/corderpeer.inc.php");
    		require_once("../include/cproductpeer.inc.php");
    		require_once("../include/cconsumerpeer.inc.php");
    		$orderManager = new cOrderPeer;
    		$thisOrderid = $suborder->getOrderid();
    		$thisOrder = $orderManager->getOrder($thisOrderid);//获得本订单句柄

			$subWeight = $suborder->weight;
			$subOrdernum = $suborder->ordernum;
    		$subTotalWeight = $subWeight*$subOrdernum;//本产品新总重量
    		$OldsubTotalWeight = $subWeight*$oldOrdernum;//本产品旧总重量
    		$thisOrderWeight = $thisOrder->getWeight();
    		$newWeight = $thisOrderWeight-$OldsubTotalWeight+$subTotalWeight;//本订单新的重量：原订单重量-原产品重量+新产品重量
    		
    		$thisRate = $thisOrder->getNRate();
    		$thisType = $thisOrder->getCType();
    		
    		if($thisType == 1){//normal order
    		  if(($newWeight+$packageZhiWeightG) < 1000 && $newWeight > 0){//不到1公斤，按1公斤收费
    		    $newFreight = 1*8*$thisRate;//新实收总运费，(总KG+0.7KG)*8新西兰元*本单汇率，人民币
    		    $newPfreight = 1*7*($rrate-0.1);//新实付运费，(总KG+0.7KG}*7新西兰元*(真实汇率-0.1)，人民币
    		  }else if($newWeight <= 0){
    		    $newFreight = 0;
    		    $newPfreight = 0;
    		  }else{
    		    $newFreight = (($newWeight+$packageZhiWeightG)/1000)*8*$thisRate;//新实收总运费，(总KG+0.7KG)*8新西兰元*本单汇率，人民币
    		    $newPfreight = (($newWeight+$packageZhiWeightG)/1000)*7*($rrate-0.1);//新实付运费，(总KG+0.7KG}*7新西兰元*(真实汇率-0.1)，人民币
    		  }
    		}else{//combo
    		  //运费公式：(weight/total)*[(total+0.7)*8*5.2]
    		  if($newWeight <= 0){
    		    $newFreight = 0;
    		    $newPfreight = 0;
    		  }else{
    		    $wzhanbi = round($newWeight/$rcombototalweight,2);
    		    if($wzhanbi < $newWeight/$rcombototalweight) $wzhanbi = $wzhanbi+0.01;
    		    $newFreight = $wzhanbi*((($rcombototalweight+$packagePinWeightG)/1000)*8*$thisRate);//新实收总运费，人民币
    		    $newFreight = round($newFreight,2);
    		    $newPfreight = $wzhanbi*((($rcombototalweight+$packagePinWeightG)/1000)*7*($rrate-0.1));//新实收总运费，人民币
    		    $newPfreight = round($newPfreight,2);
    		  }
    		}
    		
    		$thisPrice = $thisOrder->getPrice();//原总价
    		$thisFreight = $thisOrder->getFreight();//当前实收运费
    		$subPrice = $suborder->price;
    		$subPriceTotal = $subPrice*$subOrdernum;//本产品新总价，人民币
    		$OldsubPriceTotal = $oldPrice*$oldOrdernum;//本产品旧总价，人民币
    		$newPrice = $thisPrice-$OldsubPriceTotal+$subPriceTotal-$thisFreight+$newFreight;//新总价：原总价-本产品旧总价+本产品新总价-原总运费+新总运费，人民币
    		
    		$subProductid = $suborder->productid;
    		$productManager = new cProductPeer;
  			$product = $productManager->getProduct($subProductid);
  			$subPpricenz = $product->getPpricenz();
  			$subPprice = $subPpricenz*$subOrdernum*($rrate-0.1);//真实的进货总价，用真实汇率
  			$OldsubPprice = $subPpricenz*$oldOrdernum*($rrate-0.1);//旧真实的进货总价，用真实汇率
  			$subSpprice = $subPpricenz*$subOrdernum*$thisRate;//代理看到的进货总价，用订单汇率
  			$OldsubSpprice = $subPpricenz*$oldOrdernum*$thisRate;//旧代理看到的进货总价，用订单汇率
  			$thisPprice = $thisOrder->getPprice();//当前真实总进货价格
  			$thisSpprice = $thisOrder->getSpprice();//当前代理看到总进货价格
  			$thisPfreight = $thisOrder->getPfreight();//当前实际运费
  			$newPprice = $thisPprice-$OldsubPprice+$subPprice-$thisPfreight+$newPfreight;//新进货真实总价，人民币，用的是实际运费，也就是实际进货总价
  			//新进货真实总价:原进货真实总价-原本产品真实的进货总价+本产品真实的进货总价-当前实际运费+新实付运费
  			$newSpprice = $thisSpprice-$OldsubSpprice+$subSpprice-$thisFreight+$newFreight;//新进货代理总价
  			//新进货代理总价:原进货代理总价-原本产品代理看到的进货总价+本产品代理看到的进货总价-当前实收运费+新实收总运费
  			
  			$sql_order = "update crmorder set weight=$newWeight,freight=$newFreight,pfreight=$newPfreight,price=$newPrice,pprice=$newPprice,spprice=$newSpprice where orderid=$thisOrderid";
			$db->query($sql_order);
			if(mysqli_affected_rows($db->link_id) <= 0 ){
				return false;
			}
    		
    		//更新profit表
    		require_once("../include/cprofitpeer.inc.php");
    		$profitManager = new cProfitPeer;
    		//$thisProfitCount = $profitManager->getProfitCount($thisOrderid);如果是更新，一定有利润纪录
    		
    		$pAgentid = $product->getAgentid();//检查有无代购人
    		
    		$consumerid = $thisOrder->getConsumerid();
    		$consumerManager = new cConsumerPeer;
    		$consumer = $consumerManager->getConsumer($consumerid);
    		$sAngentid = $consumer->getAgentid();//检查有无代理人
    		
    		$thisOneProfit = $profitManager->getProfit($thisOrderid,1);
    		$thisTotalProfit = $thisOneProfit->getProfit();//原实际总利润
    		$subTotalProfit = $subPriceTotal-($subPpricenz*$subOrdernum*($rrate-0.1));//当前产品的实际总利润，使用的是系统汇率
    		$OldsubTotalProfit = $OldsubPriceTotal-($subPpricenz*$oldOrdernum*($rrate-0.1));//旧当前产品的实际总利润，使用的是系统汇率
    		$newTotalProfit = $thisTotalProfit-$OldsubTotalProfit+$subTotalProfit;//实际总利润：原总利润-旧本产品实际总利润+本产品实际总利润
    		  
    		$thisTwoProfit = $profitManager->getProfit($thisOrderid,2);
    		$thisSTotalProfit = $thisTwoProfit->getProfit();//代理总利润
    		$subSTotalProfit = $subPriceTotal-($subPpricenz*$subOrdernum*$thisRate);//当前产品的代理总利润，使用的是订单汇率
    		$OldsubSTotalProfit = $OldsubPriceTotal-($subPpricenz*$oldOrdernum*$thisRate);//旧当前产品的代理总利润，使用的是订单汇率
    		$newSTotalProfit = $thisSTotalProfit-$OldsubSTotalProfit+$subSTotalProfit;//代理总利润：原代理总利润-旧本产品代理总利润+本产品代理总利润
    		  
    		$thisFourProfit = $profitManager->getProfit($thisOrderid,4);
    		$thisPagentProfit = $thisFourProfit->getProfit();//原代购人利润
    		if($pAgentid > 0){//有代购
    		  $newPagentProfit = $thisPagentProfit-($OldsubTotalProfit/2)+($subTotalProfit/2);  //原代购人利润-原本产品真实总利润/2+本产品真实总利润/2
    		}else{
    		  $newPagentProfit = $thisPagentProfit;
    		}
    		  
    		$thisFiveProfit = $profitManager->getProfit($thisOrderid,5);
    		$thisSPagentProfit = $thisFiveProfit->getProfit();//原伪代购人利润
    		if($pAgentid > 0){//有代购
    		  $newSPagentProfit = $thisSPagentProfit-($OldsubSTotalProfit/2)+($subSTotalProfit/2);
    		  $subSPagentProfit = $subSTotalProfit/2;//本产品伪代购利润
    		  $OldsubSPagentProfit = $OldsubSTotalProfit/2;//本产品旧伪代购利润
    		}else{
    		  $newSPagentProfit = $thisSPagentProfit;
    		  $subSPagentProfit = 0;//本产品伪代购利润
    		  $OldsubSPagentProfit = 0;//本产品伪代购利润
    		}
    		
    		$thisThreeProfit = $profitManager->getProfit($thisOrderid,3);
    		$thisSagentProfit = $thisThreeProfit->getProfit();//原代理人利润
    		if($sAngentid > 0){//有代理
    		  $subSagentProfit = ($subSTotalProfit-$subSPagentProfit)/2;//本产品代理利润
    		  $OldsubSagentProfit = ($OldsubSTotalProfit-$OldsubSPagentProfit)/2;//本产品原代理利润
    		  $newSagentProfit = $thisSagentProfit-$OldsubSagentProfit+$subSagentProfit;
    		  //代理总利润：愿代理总利润-本产品原代理总利润+本产品新代理总利润(本产品利润-本产品代购利润一半)
    	      $newYzProfit = $newSagentProfit;
    		}else{
    		  $newSagentProfit = 0;
    		  $newYzProfit = $newSTotalProfit-$newSPagentProfit;
    		}
    		  
    		$newJProfit = ($newTotalProfit-$newPagentProfit-$newSagentProfit)+($newFreight-$newPfreight);
    		//净利润：（总利润-代购人利润-代理利润)+运费
    		
    		$sql_profit_1 = "update profit set profit=$newTotalProfit where orderid=$thisOrderid and type=1";
    		$sql_profit_2 = "update profit set profit=$newSTotalProfit where orderid=$thisOrderid and type=2";
    		$sql_profit_3 = "update profit set profit=$newSagentProfit where orderid=$thisOrderid and type=3";
    		$sql_profit_4 = "update profit set profit=$newPagentProfit where orderid=$thisOrderid and type=4";
    		$sql_profit_5 = "update profit set profit=$newSPagentProfit where orderid=$thisOrderid and type=5";
    		$sql_profit_6 = "update profit set profit=$newYzProfit where orderid=$thisOrderid and type=6";
    		$sql_profit_7 = "update profit set profit=$newJProfit where orderid=$thisOrderid and type=7";
    		
    		$db->query($sql_profit_1);
    		$db->query($sql_profit_2);
    		$db->query($sql_profit_3);
    		$db->query($sql_profit_4);
    		$db->query($sql_profit_5);
    		$db->query($sql_profit_6);
    		$db->query($sql_profit_7);
			return true;
    	}
    	
    	function orderTriggerDel($suborder){
    		global $db;
    		require_once("../include/cconstantspeer.inc.php");
    		$constantsManager = new cConstantsPeer;
    		$rrate = $constantsManager->getNewConstants(1);//当前利率
    		$rcombototalweight = $constantsManager->getNewConstants(2);//当前拼单后产品总重
    		$packageZhiWeightG = $constantsManager->getNewConstants(3);//直邮包装重，克
    		$packagePinWeightG = $constantsManager->getNewConstants(4);//拼单包装重，克
    		
    		//更新crmorder表
    		require_once("../include/corderpeer.inc.php");
    		require_once("../include/cproductpeer.inc.php");
    		require_once("../include/cconsumerpeer.inc.php");
    		$orderManager = new cOrderPeer;
    		$thisOrderid = $suborder->getOrderid();
    		$thisOrder = $orderManager->getOrder($thisOrderid);//获得本订单句柄

			$subWeight = $suborder->weight;
			$subOrdernum = $suborder->ordernum;
    		$subTotalWeight = $subWeight*$subOrdernum;//本产品总重量
    		$thisOrderWeight = $thisOrder->getWeight();
    		$newWeight = $thisOrderWeight-$subTotalWeight;//本订单新的重量：原订单重量-产品重量
    		
    		$thisRate = $thisOrder->getNRate();
    		$thisType = $thisOrder->getCType();
    		
    		if($thisType == 1){//normal order
    		  if(($newWeight+$packageZhiWeightG) < 1000 && $newWeight > 0){//不到1公斤，按1公斤收费
    		    $newFreight = 1*8*$thisRate;//新实收总运费，(总KG+0.7KG)*8新西兰元*本单汇率，人民币
    		    $newPfreight = 1*7*($rrate-0.1);//新实付运费，(总KG+0.7KG}*7新西兰元*(真实汇率-0.1)，人民币
    		  }else if($newWeight <= 0){
    		    $newFreight = 0;
    		    $newPfreight = 0;
    		  }else{
    		    $newFreight = (($newWeight+$packageZhiWeightG)/1000)*8*$thisRate;//新实收总运费，(总KG+0.7KG)*8新西兰元*本单汇率，人民币
    		    $newPfreight = (($newWeight+$packageZhiWeightG)/1000)*7*($rrate-0.1);//新实付运费，(总KG+0.7KG}*7新西兰元*(真实汇率-0.1)，人民币
    		  }
    		}else{//combo
    		  //运费公式：(weight/total)*[(total+0.7)*8*5.2]
    		  if($newWeight <= 0){
    		    $newFreight = 0;
    		    $newPfreight = 0;
    		  }else{
    		    $wzhanbi = round($newWeight/$rcombototalweight,2);
    		    if($wzhanbi < $newWeight/$rcombototalweight) $wzhanbi = $wzhanbi+0.01;
    		    $newFreight = $wzhanbi*((($rcombototalweight+$packagePinWeightG)/1000)*8*$thisRate);//新实收总运费，人民币
    		    $newFreight = round($newFreight,2);
    		    $newPfreight = $wzhanbi*((($rcombototalweight+$packagePinWeightG)/1000)*7*($rrate-0.1));//新实收总运费，人民币
    		    $newPfreight = round($newPfreight,2);
    		  }
    		}
    		
    		$thisPrice = $thisOrder->getPrice();//原总价
    		$thisFreight = $thisOrder->getFreight();//当前实收运费
    		$subPrice = $suborder->price;
    		$subPriceTotal = $subPrice*$subOrdernum;//本产品总价，人民币
    		$newPrice = $thisPrice-$subPriceTotal-$thisFreight+$newFreight;//新总价：原总价-本产品总价-原总运费+新总运费，人民币
    		
    		$subProductid = $suborder->productid;
    		$productManager = new cProductPeer;
  			$product = $productManager->getProduct($subProductid);
  			$subPpricenz = $product->getPpricenz();
  			$subPprice = $subPpricenz*$subOrdernum*($rrate-0.1);//真实的进货总价，用真实汇率
  			$subSpprice = $subPpricenz*$subOrdernum*$thisRate;//代理看到的进货总价，用订单汇率
  			$thisPprice = $thisOrder->getPprice();//当前真实总进货价格
  			$thisSpprice = $thisOrder->getSpprice();//当前代理看到总进货价格
  			$thisPfreight = $thisOrder->getPfreight();//当前实际运费
  			$newPprice = $thisPprice-$subPprice-$thisPfreight+$newPfreight;//新进货真实总价，人民币，用的是实际运费，也就是实际进货总价
  			//新进货真实总价:原进货真实总价-本产品真实的进货总价-当前实际运费+新实付运费
  			$newSpprice = $thisSpprice-$subSpprice-$thisFreight+$newFreight;//新进货代理总价
  			//新进货代理总价:原进货代理总价-本产品代理看到的进货总价-当前实收运费+新实收总运费
  			
  			$sql_order = "update crmorder set weight=$newWeight,freight=$newFreight,pfreight=$newPfreight,price=$newPrice,pprice=$newPprice,spprice=$newSpprice where orderid=$thisOrderid";
			$db->query($sql_order);
			if(mysqli_affected_rows($db->link_id) <= 0 ){
				return false;
			}
    		
    		//更新profit表
    		require_once("../include/cprofitpeer.inc.php");
    		$profitManager = new cProfitPeer;
    		//$thisProfitCount = $profitManager->getProfitCount($thisOrderid);如果是删除，一定有利润纪录
    		
    		$pAgentid = $product->getAgentid();//检查有无代购人
    		
    		$consumerid = $thisOrder->getConsumerid();
    		$consumerManager = new cConsumerPeer;
    		$consumer = $consumerManager->getConsumer($consumerid);
    		$sAngentid = $consumer->getAgentid();//检查有无代理人
    		
    		$thisOneProfit = $profitManager->getProfit($thisOrderid,1);
    		$thisTotalProfit = $thisOneProfit->getProfit();//原实际总利润
    		$subTotalProfit = $subPriceTotal-($subPpricenz*$subOrdernum*($rrate-0.1));//当前产品的实际总利润，使用的是系统汇率
    		$newTotalProfit = $thisTotalProfit-$subTotalProfit;//实际总利润：原总利润-本产品实际总利润
    		  
    		$thisTwoProfit = $profitManager->getProfit($thisOrderid,2);
    		$thisSTotalProfit = $thisTwoProfit->getProfit();//代理总利润
    		$subSTotalProfit = $subPriceTotal-($subPpricenz*$subOrdernum*$thisRate);//当前产品的代理总利润，使用的是订单汇率
    		$newSTotalProfit = $thisSTotalProfit-$subSTotalProfit;//代理总利润：原代理总利润-本产品代理总利润
    		  
    		$thisFourProfit = $profitManager->getProfit($thisOrderid,4);
    		$thisPagentProfit = $thisFourProfit->getProfit();//原代购人利润
    		if($pAgentid > 0){//有代购
    		  $newPagentProfit = $thisPagentProfit-($subTotalProfit/2);  //原代购人利润-本产品真实总利润/2
    		}else{
    		  $newPagentProfit = $thisPagentProfit;
    		}
    		  
    		$thisFiveProfit = $profitManager->getProfit($thisOrderid,5);
    		$thisSPagentProfit = $thisFiveProfit->getProfit();//原伪代购人利润
    		if($pAgentid > 0){//有代购
    		  $newSPagentProfit = $thisSPagentProfit-($subSTotalProfit/2);
    		  $subSPagentProfit = $subSTotalProfit/2;//本产品伪代购利润
    		}else{
    		  $newSPagentProfit = $thisSPagentProfit;
    		  $subSPagentProfit = 0;//本产品伪代购利润
    		}
    		
    		$thisThreeProfit = $profitManager->getProfit($thisOrderid,3);
    		$thisSagentProfit = $thisThreeProfit->getProfit();//原代理人利润
    		if($sAngentid > 0){//有代理
    		  $subSagentProfit = ($subSTotalProfit-$subSPagentProfit)/2;//本产品代理利润
    		  $newSagentProfit = $thisSagentProfit-$subSagentProfit;
    		  //代理总利润：愿代理总利润-本产品代理总利润(本产品利润-本产品代购利润一半)
    	      $newYzProfit = $newSagentProfit;
    		}else{
    		  $newSagentProfit = 0;
    		  $newYzProfit = $newSTotalProfit-$newSPagentProfit;
    		}
    		  
    		$newJProfit = ($newTotalProfit-$newPagentProfit-$newSagentProfit)+($newFreight-$newPfreight);
    		//净利润：（总利润-代购人利润-代理利润)+运费
    		
    		$sql_profit_1 = "update profit set profit=$newTotalProfit where orderid=$thisOrderid and type=1";
    		$sql_profit_2 = "update profit set profit=$newSTotalProfit where orderid=$thisOrderid and type=2";
    		$sql_profit_3 = "update profit set profit=$newSagentProfit where orderid=$thisOrderid and type=3";
    		$sql_profit_4 = "update profit set profit=$newPagentProfit where orderid=$thisOrderid and type=4";
    		$sql_profit_5 = "update profit set profit=$newSPagentProfit where orderid=$thisOrderid and type=5";
    		$sql_profit_6 = "update profit set profit=$newYzProfit where orderid=$thisOrderid and type=6";
    		$sql_profit_7 = "update profit set profit=$newJProfit where orderid=$thisOrderid and type=7";
    		
    		$db->query($sql_profit_1);
    		$db->query($sql_profit_2);
    		$db->query($sql_profit_3);
    		$db->query($sql_profit_4);
    		$db->query($sql_profit_5);
    		$db->query($sql_profit_6);
    		$db->query($sql_profit_7);
			return true;
    	}
    }
?>