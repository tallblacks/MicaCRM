<?php
	require_once("cprofit.inc.php");
	
	class cProfitPeer{
    	function getProfit($orderid,$type){
    		$sql = "select profitid,orderid,profit,type,create_time from profit where orderid=$orderid and type=$type";
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
    	
    	function getProfitCount($orderid){
			if($orderid == 0){
				$sql = "select count(profitid) as total from profit";
			}else{
				$sql = "select count(profitid) as total from profit where orderid=".$orderid;
			}
			global $db;
				
		  	$result=$db->query($sql);
    		if($row = $db->fetch_array($result)){
    			return $row['total'];
    		}
		}
		
		function getProfitCountByAgentid($agentid){
		  global $db;
		  
		  //get all consumerid
		  $consumeridStr = "";
		  require_once("../include/cconsumerpeer.inc.php");
		  $consumerManager = new cConsumerPeer;
	      $consumerList = $consumerManager->getConsumerlist(0,10000,"", $agentid);
	      if(!empty($consumerList)){
	        $consumeridCount = 0;
	        $consumeridStr = " and consumerid in (";
	        foreach($consumerList as $consumer){
	          $consumer = (object)$consumer;
	          $consumerid = $consumer->getConsumerid();
	          if($consumeridCount == 0){
	            $consumeridStr = $consumeridStr.$consumerid;
	          }else{
	            $consumeridStr = $consumeridStr.",".$consumerid;
	          }
	          $consumeridCount++;
	        }
	        $consumeridStr = $consumeridStr.")";
	      }

          $orderidStr = "";
          $orderidCount = 0;
		  $sql1 = "select orderid from crmorder where status=0".$consumeridStr;
		  $result=$db->query($sql1);
    	  while($row = $db->fetch_object($result)){
    	    if($orderidCount == 0){
    	      $orderidStr = " where orderid in (".$row->orderid;
    	    }else{
    		  $orderidStr = $orderidStr.",".$row->orderid;
    		}
    		$orderidCount++;
    	  }
    	  if($orderidStr != ""){
    	    $orderidStr = $orderidStr.")";
    	  }
    	  
		  $sql2 = "select count(profitid) as total from profit".$orderidStr;
		  $result=$db->query($sql2);
    	  if($row = $db->fetch_array($result)){
    	    return $row['total'];
    	  }
		}
		
		function getProfitCountForSecretary(){
		  global $db;

          $orderidStr = "";
          $orderidCount = 0;
		  $sql1 = "select orderid from crmorder where (status=0 and create_time between '2014-06-15 00:00:00' and '2014-09-09 23:59:59') || (status=0 and create_time >= '2014-09-10 00:00:00' and operatorid in (select userid from user where type=3 or type=5))";
		  $result=$db->query($sql1);
    	  while($row = $db->fetch_object($result)){
    	    if($orderidCount == 0){
    	      $orderidStr = " where orderid in (".$row->orderid;
    	    }else{
    		  $orderidStr = $orderidStr.",".$row->orderid;
    		}
    		$orderidCount++;
    	  }
    	  if($orderidStr != ""){
    	    $orderidStr = $orderidStr.")";
    	  }
    	  
		  $sql2 = "select count(profitid) as total from profit".$orderidStr;
		  $result=$db->query($sql2);
    	  if($row = $db->fetch_array($result)){
    	    return $row['total'];
    	  }
		}
		
		function getProfitCountForXiaomi($xiaomid){
		  global $db;

          $orderidStr = "";
          $orderidCount = 0;
		  $sql1 = "select orderid from crmorder where status=0 and operatorid=$xiaomid";
		  $result=$db->query($sql1);
    	  while($row = $db->fetch_object($result)){
    	    if($orderidCount == 0){
    	      $orderidStr = " where orderid in (".$row->orderid;
    	    }else{
    		  $orderidStr = $orderidStr.",".$row->orderid;
    		}
    		$orderidCount++;
    	  }
    	  if($orderidStr != ""){
    	    $orderidStr = $orderidStr.")";
    	  }else{
    	    return 0;
    	  }
    	  
		  $sql2 = "select count(profitid) as total from profit".$orderidStr;
		  $result=$db->query($sql2);
    	  if($row = $db->fetch_array($result)){
    	    return $row['total'];
    	  }
		}
		
		function getProfitlist($orderid,$startIndex,$numResults){		
			if($orderid == 0){
				$sql = "select profitid,orderid,profit,type,create_time from profit order by profitid desc";
			}else{
				$sql = "select profitid,orderid,profit,type,create_time from profit where orderid=$orderid order by profitid desc";
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
		
		function getProfitbyDate($day,$month,$year,$orderid,$type){
			$sql = "select profitid,orderid,profit,type,create_time from profit where orderid=$orderid and type=$type and create_time like '$year-$month-$day%'";

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
		
		function create($profit){
			$sql = "insert into profit(orderid,profit,type,create_time) values (".$profit->getOrderid().",".$profit->getProfit().",".$suborder->getCType().",now())";

			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			}else{
				return false;
			}
		}
		
		function getOrderidlist($startIndex,$numResults){
			global $db;
			$sql = "select orderid from profit group by orderid desc";

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
          	    $rslist[] = $row->orderid;
          		$j++;
    		  }
    		}
    		
    		return $rslist;
		}
		
		function getOrderidlistByAgentid($agentid,$startIndex,$numResults){
		  global $db;
			
		  //get all consumerid
		  $consumeridStr = "";
		  require_once("../include/cconsumerpeer.inc.php");
		  $consumerManager = new cConsumerPeer;
	      $consumerList = $consumerManager->getConsumerlist(0,10000,"", $agentid);
	      if(!empty($consumerList)){
	        $consumeridCount = 0;
	        $consumeridStr = " and consumerid in (";
	        foreach($consumerList as $consumer){
	          $consumer = (object)$consumer;
	          $consumerid = $consumer->getConsumerid();
	          if($consumeridCount == 0){
	            $consumeridStr = $consumeridStr.$consumerid;
	          }else{
	            $consumeridStr = $consumeridStr.",".$consumerid;
	          }
	          $consumeridCount++;
	        }
	        $consumeridStr = $consumeridStr.")";
	      }

          $orderidStr = "";
          $orderidCount = 0;
		  $sql1 = "select orderid from crmorder where status=0".$consumeridStr;
		  $result=$db->query($sql1);
    	  while($row = $db->fetch_object($result)){
    	    if($orderidCount == 0){
    	      $orderidStr = " where orderid in (".$row->orderid;
    	    }else{
    		  $orderidStr = $orderidStr.",".$row->orderid;
    		}
    		$orderidCount++;
    	  }
    	  if($orderidStr != ""){
    	    $orderidStr = $orderidStr.")";
    	  }
			
		  $sql2 = "select orderid from profit $orderidStr group by orderid desc";
          $result=$db->query($sql2);
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
          	  $rslist[] = $row->orderid;
          	  $j++;
    		}
    	  }
    		
    	  return $rslist;
		}
		
		function getOrderidlistForSecretary($startIndex,$numResults){
		  global $db;

          $orderidStr = "";
          $orderidCount = 0;
		  $sql1 = "select orderid from crmorder where (status=0 and create_time between '2014-06-15 00:00:00' and '2014-09-09 23:59:59') || (status=0 and create_time >= '2014-09-10 00:00:00' and operatorid in (select userid from user where type=3 or type=5))";
		  $result=$db->query($sql1);
    	  while($row = $db->fetch_object($result)){
    	    if($orderidCount == 0){
    	      $orderidStr = " where orderid in (".$row->orderid;
    	    }else{
    		  $orderidStr = $orderidStr.",".$row->orderid;
    		}
    		$orderidCount++;
    	  }
    	  if($orderidStr != ""){
    	    $orderidStr = $orderidStr.")";
    	  }
			
		  $sql2 = "select orderid from profit $orderidStr group by orderid desc";
          $result=$db->query($sql2);
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
          	  $rslist[] = $row->orderid;
          	  $j++;
    		}
    	  }
    		
    	  return $rslist;
		}
		
		function getOrderidlistForXiaomi($xiaomid,$startIndex,$numResults){
		  global $db;

          $orderidStr = "";
          $orderidCount = 0;
		  $sql1 = "select orderid from crmorder where status=0 and operatorid=$xiaomid";
		  $result=$db->query($sql1);
    	  while($row = $db->fetch_object($result)){
    	    if($orderidCount == 0){
    	      $orderidStr = " where orderid in (".$row->orderid;
    	    }else{
    		  $orderidStr = $orderidStr.",".$row->orderid;
    		}
    		$orderidCount++;
    	  }
    	  if($orderidStr != ""){
    	    $orderidStr = $orderidStr.")";
    	  }else{
    	    return false;
    	  }

		  $sql2 = "select orderid from profit $orderidStr group by orderid desc";
          $result=$db->query($sql2);
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
          	  $rslist[] = $row->orderid;
          	  $j++;
    		}
    	  }
    		
    	  return $rslist;
		}
    	
    	function Load($row){
    		$profit = new cProfit;
    		$profit->setProfitid($row->profitid);
    		$profit->setOrderid($row->orderid);
    		$profit->setProfit($row->profit);
    		$profit->setCType($row->type);
    		$profit->setCreatetime($row->create_time);
    		return $profit;
    	}
    }
?>