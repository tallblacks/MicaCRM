<?php
	require_once("ccomborder.inc.php");
	
	class cComborderPeer{   
    	function getComborder($comborderid){
    		$query_sql = "select comborderid,name,address,mobile,memo,logisticsid,logisticscode,type,status,create_time from comborder where comborderid=";
    		$sql = $query_sql.$comborderid;
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
    	
    	function getComborderCount(){
			$sql = "select count(comborderid) as total from comborder where status=0";
			global $db;
				
		  	$result=$db->query($sql);
    		if($row = $db->fetch_array($result)){
    			return $row['total'];
    		}
		}
		
		function getComborderlist($startIndex,$numResults){
			$sql = "select comborderid,name,address,mobile,memo,logisticsid,logisticscode,type,status,create_time from comborder where status=0 order by comborderid desc";
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
		
		function getComborderlistbyStEdSa($startdate,$enddate){
			$sql = "select comborderid,name,address,mobile,memo,logisticsid,logisticscode,type,status,create_time from comborder where status=0 and create_time between '$startdate 00:00:00' and '$enddate 23:59:59'";
			global $db;
			
			$result=$db->query($sql);
    		$query_num = $db->num_rows($result);

    		if($query_num == 0){
    			return false;
    		}
  			$rslist = array();
  			require_once("../include/cconsumerpeer.inc.php");
    		while($row = $db->fetch_object($result)){
    		  $rslist[] = $this->load($row);
    		}
    		return $rslist;
		}
		
		function create($comborder){
			//$sql = "insert into comborder(name,address,mobile,memo,weight,freight,pfreight,price,pprice,logisticsid,logisticscode,cfreight,cpfreight,paystatus,type,status,create_time) values ('".$comborder->getCname()."','".$comborder->getAddress()."','".$comborder->getMobile()."','".$comborder->getMemo()."',".$comborder->getWeight().",".$comborder->getFreight().",".$comborder->getPfreight().",".$comborder->getPrice().",".$comborder->getPprice().",".$comborder->getLogisticsid().",'".$comborder->getLogisticscode()."',".$comborder->getCfreight().",".$comborder->getCpfreight().",".$comborder->getPaystatus().",".$comborder->getCType().",".$comborder->getPstatus().",now())";
            $sql = "insert into comborder(memo,type,status,create_time) values ('".$comborder->getMemo()."',".$comborder->getCType().",".$comborder->getPstatus().",now())";
            //echo $sql;
			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			}else{
				return false;
			}
		}
		
		function update($comborder){
			$sql = "update comborder set name='".$comborder->getCname()."',address='".$comborder->getAddress()."',mobile='".$comborder->getMobile()."',memo='".$comborder->getMemo()."' where comborderid=".$comborder->getComborderid();

			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			}else{
				return false;
			}
		}
		
		function delete($comborderid){
			$sql = "update comborder set status=4 where comborderid=$comborderid";
			//profit目前就不动了,全是0

			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			}else{
				return false;
			}
		}
		
		function updatePaystatus($comborderid,$paystatus){
		    if(!$this->getComborder($comborderid)){
		    	return false;
		    }
		    $sql = "update comborder set paystatus=$paystatus where comborderid=$comborderid";

			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			}else{
				return false;
			}
		}
		
		function updateLogistics($comborderid,$logisticsid,$logisticscode){
			$sql = "update comborder set logisticsid=$logisticsid,logisticscode='$logisticscode' where comborderid=$comborderid";

			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			}else{
				return false;
			}
		}
		
		/*function updatepfreight($orderid,$pfreight){
			if($this->orderTriggerPfreight($orderid,$pfreight)){
				return true;
			}else{
				return false;
			}
		}
		
		function updateprice($orderid,$discount){
			if($this->orderTriggerPrice($orderid,$discount)){
				return true;
			}else{
				return false;
			}
		}*/
    	
    	function Load($row){
    		$comborder = new cComborder;
    		$comborder->setComborderid($row->comborderid);
    		$comborder->setCname($row->name);
    		$comborder->setAddress($row->address);
    		$comborder->setMobile($row->mobile);
    		$comborder->setMemo($row->memo);
    		$comborder->setLogisticsid($row->logisticsid);
    		$comborder->setLogisticscode($row->logisticscode);
    		$comborder->setCType($row->type);
    		$comborder->setPstatus($row->status);
    		$comborder->setCreatetime($row->create_time);
    		return $comborder;
    	}
    	
    	/*function orderTriggerPfreight($orderid,$pfreight){
    		global $db;
    		$order = $this->getOrder($orderid);//获得本订单句柄
    		$thisFreight = $order->getFreight();//当前实收运费
    		$thisPfreight = $order->getPfreight();//当前实际运费
    		
    		//修改了真实运费，进货真实总价是要改变的，代理看到的总价是不变的
    		$thisPprice = $order->getPprice();//当前真实总进货价格
    		$thisPfreight = $order->getPfreight();//当前实际运费
    		$newPprice = $thisPprice-$thisPfreight+$pfreight;
    		//新进货真实总价=进货真实总价-原真实运费+新修改的运费
  			
  			$sql_order = "update crmorder set pfreight=$pfreight,pprice=$newPprice,cpfreight=1 where orderid=$orderid";
			$db->query($sql_order);
			if(mysql_affected_rows() <= 0 ){
				return false;
			}
    		
    		//更新profit表
    		require_once("../include/cprofitpeer.inc.php");
    		$profitManager = new cProfitPeer;    		
    		$thisSevenProfit = $profitManager->getProfit($orderid,7);
    		$thisJProfit = $thisSevenProfit->getProfit();
    		$newJProfit = $thisJProfit-($thisFreight-$thisPfreight)+($thisFreight-$pfreight);
    		//新净利润：原净利润-(原实收运费-原实际运费)+(原实收运费-现实际运费)

    		$sql_profit = "update profit set profit=$newJProfit where orderid=$orderid and type=7";
    		$db->query($sql_profit);
			return true;
    	}
    	
    	function orderTriggerPrice($orderid,$discount){
    		global $db;
    		
    		//更新crmorder表
    		$order = $this->getOrder($orderid);//获得本订单句柄
    		$thisPrice = $order->getPrice();//原总价
    		$thisDiscount = $order->getDiscount();//原优惠
    		
    		if($thisDiscount > 0){//已经给过优惠了
    		  $newPrice = $thisPrice+$thisDiscount-$discount;//新总价：原总价+原优惠-优惠，人民币
    		}else{
    		  $newPrice = $thisPrice-$discount;//新总价：原总价-优惠，人民币
    		}
    		
  			$sql_order = "update crmorder set price=$newPrice,discount=$discount where orderid=$orderid";
			$db->query($sql_order);
			if(mysql_affected_rows() <= 0 ){
				return false;
			}

    		//更新profit表
    		require_once("../include/cconsumerpeer.inc.php");
    		require_once("../include/cprofitpeer.inc.php");
    		$profitManager = new cProfitPeer;
    		
    		$consumerid = $order->getConsumerid();
    		$consumerManager = new cConsumerPeer;
    		$consumer = $consumerManager->getConsumer($consumerid);
    		$sAngentid = $consumer->getAgentid();//检查有无代理人
    		
    		$thisOneProfit = $profitManager->getProfit($orderid,1);
    		$thisTotalProfit = $thisOneProfit->getProfit();//原实际总利润
    		if($thisDiscount > 0){//已经给过优惠了
    		  $newTotalProfit = $thisTotalProfit+$thisDiscount-$discount;//实际总利润：原总利润+原优惠-优惠
    		}else{
    		  $newTotalProfit = $thisTotalProfit-$discount;//实际总利润：原总利润-优惠
    		}
    		  
    		$thisTwoProfit = $profitManager->getProfit($orderid,2);
    		$thisSTotalProfit = $thisTwoProfit->getProfit();//代理总利润
    		if($thisDiscount > 0){//已经给过优惠了
    		  $newSTotalProfit = $thisSTotalProfit+$thisDiscount-$discount;//代理总利润：原代理总利润+原优惠-优惠
    		}else{
    		  $newSTotalProfit = $thisSTotalProfit-$discount;//代理总利润：原代理总利润-优惠
    		}
    		
    		$thisThreeProfit = $profitManager->getProfit($orderid,3);
    		$thisSagentProfit = $thisThreeProfit->getProfit();//原代理人利润
    		$thisSixProfit = $profitManager->getProfit($orderid,6);
    		$thisYzProfit = $thisSixProfit->getProfit();//YZ利润
    		if($sAngentid > 0){//有代理
    		  if($thisDiscount > 0){//已经给过优惠了
    		    $newSagentProfit = $thisSagentProfit+($thisDiscount/2)-($discount/2);//代理总利润：原代理总利润+原优惠/2-优惠/2
    		  }else{
    		    $newSagentProfit = $thisSagentProfit-($discount/2);//代理总利润：原代理总利润-优惠/2
    	      }
    	      $newYzProfit = $newSagentProfit;
    		}else{
    		  $newSagentProfit = 0;
    		  if($thisDiscount > 0){//已经给过优惠了
    		    $newYzProfit = $thisYzProfit+$thisDiscount-$discount;//Yz承担所有优惠
    		  }else{
    		    $newYzProfit = $thisYzProfit-$discount;//Yz承担所有优惠
    		  }
    		}
    		  
    		$thisSevenProfit = $profitManager->getProfit($orderid,7);
    		$thisJProfit = $thisSevenProfit->getProfit();//净利润
    		//if($thisDiscount > 0){//已经给过优惠了
    		//  $newJProfit = $thisJProfit+$thisDiscount-$discount;//净利润：+原优惠-优惠
    		//}else{
    		//  $newJProfit = $thisJProfit-$discount;//净利润：-优惠
    		//}
    		$newJProfit = $thisJProfit-($thisTotalProfit-$thisSagentProfit)+($newTotalProfit-$newSagentProfit);//
    		
    		$sql_profit_1 = "update profit set profit=$newTotalProfit where orderid=$orderid and type=1";
    		$sql_profit_2 = "update profit set profit=$newSTotalProfit where orderid=$orderid and type=2";
    		$sql_profit_3 = "update profit set profit=$newSagentProfit where orderid=$orderid and type=3";
    		$sql_profit_6 = "update profit set profit=$newYzProfit where orderid=$orderid and type=6";
    		$sql_profit_7 = "update profit set profit=$newJProfit where orderid=$orderid and type=7";
    		
    		$db->query($sql_profit_1);
    		$db->query($sql_profit_2);
    		$db->query($sql_profit_3);
    		$db->query($sql_profit_6);
    		$db->query($sql_profit_7);
			return true;
    	}*/
    }
?>