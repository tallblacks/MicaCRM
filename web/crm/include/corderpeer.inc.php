<?php
	require_once("corder.inc.php");
	
	class cOrderPeer{   
    	function getOrder($orderid){
    		$query_sql = "select orderid,idcode,operatorid,consumerid,rate,weight,freight,pfreight,price,pprice,spprice,logisticsid,logisticscode,discount,cfreight,cpfreight,paystatus,memo,type,status,create_time from crmorder where orderid=";
    		$sql = $query_sql.$orderid;
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
    	
    	function getOrderByIdcode($idcode){
    		$query_sql = "select orderid,idcode,operatorid,consumerid,rate,weight,freight,pfreight,price,pprice,spprice,logisticsid,logisticscode,discount,cfreight,cpfreight,paystatus,memo,type,status,create_time from crmorder where idcode=";
    		$sql = $query_sql.$idcode;
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
    	
    	function getOrderCount($consumerid){
			if($consumerid == 0){
				$sql = "select count(orderid) as total from crmorder where status=0";
			}else{
				$sql = "select count(orderid) as total from crmorder where status=0 and consumerid=".$consumerid;
			}
			global $db;
				
		  	$result=$db->query($sql);
    		if($row = $db->fetch_array($result)){
    			return $row['total'];
    		}
		}
		
		function getOrderCountByIdCode($ordercode){
		  $sql = "select count(orderid) as total from crmorder where status=0 and (orderid=$ordercode or idcode=$ordercode)";
          global $db;
				
          $result=$db->query($sql);
          if($row = $db->fetch_array($result)){
            return $row['total'];
          }
		}
		
		function getOrderCountForSecretary($consumerid){
			if($consumerid == 0){
				$sql = "select count(orderid) as total from crmorder where (status=0 and create_time between '2014-06-15 00:00:00' and '2014-09-09 23:59:59') || (status=0 and create_time >= '2014-09-10 00:00:00' and operatorid in (select userid from user where type=3 or type=5))";
			}else{
				$sql = "select count(orderid) as total from crmorder where (status=0 and consumerid=$consumerid and create_time between '2014-06-15 00:00:00' and '2014-09-09 23:59:59') || (status=0 and consumerid=$consumerid and create_time >= '2014-09-10 00:00:00' and operatorid in (select userid from user where type=3 or type=5))";
			}
			global $db;
				
		  	$result=$db->query($sql);
    		if($row = $db->fetch_array($result)){
    			return $row['total'];
    		}
		}
		
		function getOrderCountForSecretaryForSearch($consumerid,$search){
			if($consumerid == 0){
				$sql = "select count(orderid) as total from crmorder where (status=0 and idcode=$search and create_time between '2014-06-15 00:00:00' and '2014-09-09 23:59:59') || (status=0 and idcode=$search and create_time >= '2014-09-10 00:00:00' and operatorid in (select userid from user where type=3 or type=5))";
			}else{
				$sql = "select count(orderid) as total from crmorder where (status=0 and idcode=$search and consumerid=$consumerid and create_time between '2014-06-15 00:00:00' and '2014-09-09 23:59:59') || (status=0 and idcode=$search and consumerid=$consumerid and create_time >= '2014-09-10 00:00:00' and operatorid in (select userid from user where type=3 or type=5))";
			}
			global $db;
				
		  	$result=$db->query($sql);
    		if($row = $db->fetch_array($result)){
    			return $row['total'];
    		}
		}
		
		function getOrderCountForXiaomi($xiaomid){
			$sql = "select count(orderid) as total from crmorder where status=0 and operatorid=$xiaomid";
			global $db;
				
		  	$result=$db->query($sql);
    		if($row = $db->fetch_array($result)){
    			return $row['total'];
    		}
		}
		
		function getOrderCountForXiaomiForSearch($xiaomid,$search){
			$sql = "select count(orderid) as total from crmorder where status=0 and operatorid=$xiaomid and idcode=$search";
			global $db;
				
		  	$result=$db->query($sql);
    		if($row = $db->fetch_array($result)){
    			return $row['total'];
    		}
		}
		
		function getOrderCountByAgentid($agentid){
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

		  $sql = "select count(orderid) as total from crmorder where status=0".$consumeridStr;

		  $result=$db->query($sql);
    	  if($row = $db->fetch_array($result)){
    		return $row['total'];
    	  }
		}
		
		function getOrderCountByAgentidAndIdcode($agentid,$idcode){
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

		  $sql = "select count(orderid) as total from crmorder where status=0 and idcode=$idcode".$consumeridStr;

		  $result=$db->query($sql);
    	  if($row = $db->fetch_array($result)){
    		return $row['total'];
    	  }
		}
		
		function getOrderlist($consumerid,$startIndex,$numResults){		
			if($consumerid == 0){
				$sql = "select orderid,idcode,operatorid,consumerid,rate,weight,freight,pfreight,price,pprice,spprice,logisticsid,logisticscode,discount,cfreight,cpfreight,paystatus,memo,type,status,create_time from crmorder where status=0 order by orderid desc";
			}else{
				$sql = "select orderid,idcode,operatorid,consumerid,rate,weight,freight,pfreight,price,pprice,spprice,logisticsid,logisticscode,discount,cfreight,cpfreight,paystatus,memo,type,status,create_time from crmorder where status=0 and consumerid=$consumerid order by orderid desc";
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
		
		function getOrderlistByIdCode($ordercode){
          $sql = "select orderid,idcode,operatorid,consumerid,rate,weight,freight,pfreight,price,pprice,spprice,logisticsid,logisticscode,discount,cfreight,cpfreight,paystatus,memo,type,status,create_time from crmorder where status=0 and (orderid=$ordercode or idcode=$ordercode) order by orderid desc";
          global $db;
			
		  $result=$db->query($sql);
    	  $query_num = $db->num_rows($result);
          if($query_num == 0){
    		return false;
    	  }

  		  $rslist = array();
    	  while($row = $db->fetch_object($result)){
          	$rslist[] = $this->load($row);
    	  }
    	  return $rslist;
		}
		
		function getOrderlistForSecretary($consumerid,$startIndex,$numResults){		
			if($consumerid == 0){
				$sql = "select orderid,idcode,operatorid,consumerid,rate,weight,freight,pfreight,price,pprice,spprice,logisticsid,logisticscode,discount,cfreight,cpfreight,paystatus,memo,type,status,create_time from crmorder where (status=0 and create_time between '2014-06-15 00:00:00' and '2014-09-09 23:59:59') || (status=0 and create_time >= '2014-09-10 00:00:00' and operatorid in (select userid from user where type=3 or type=5)) order by orderid desc";
			}else{
				$sql = "select orderid,idcode,operatorid,consumerid,rate,weight,freight,pfreight,price,pprice,spprice,logisticsid,logisticscode,discount,cfreight,cpfreight,paystatus,memo,type,status,create_time from crmorder where (status=0 and consumerid=$consumerid and create_time between '2014-06-15 00:00:00' and '2014-09-09 23:59:59') || (status=0 and consumerid=$consumerid and create_time >= '2014-09-10 00:00:00' and operatorid in (select userid from user where type=3 or type=5)) order by orderid desc";
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
		
		function getOrderlistForSecretaryForSearch($consumerid,$search,$startIndex,$numResults){		
			if($consumerid == 0){
				$sql = "select orderid,idcode,operatorid,consumerid,rate,weight,freight,pfreight,price,pprice,spprice,logisticsid,logisticscode,discount,cfreight,cpfreight,paystatus,memo,type,status,create_time from crmorder where (status=0 and idcode=$search and create_time between '2014-06-15 00:00:00' and '2014-09-09 23:59:59') || (status=0 and idcode=$search and create_time >= '2014-09-10 00:00:00' and operatorid in (select userid from user where type=3 or type=5)) order by orderid desc";
			}else{
				$sql = "select orderid,idcode,operatorid,consumerid,rate,weight,freight,pfreight,price,pprice,spprice,logisticsid,logisticscode,discount,cfreight,cpfreight,paystatus,memo,type,status,create_time from crmorder where (status=0 and idcode=$search and consumerid=$consumerid and create_time between '2014-06-15 00:00:00' and '2014-09-09 23:59:59') || (status=0 and idcode=$search and consumerid=$consumerid and create_time >= '2014-09-10 00:00:00' and operatorid in (select userid from user where type=3 or type=5)) order by orderid desc";
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
		
		function getOrderlistForXiaomi($xiaomid,$startIndex,$numResults){
		  $sql = "select orderid,idcode,operatorid,consumerid,rate,weight,freight,pfreight,price,pprice,spprice,logisticsid,logisticscode,discount,cfreight,cpfreight,paystatus,memo,type,status,create_time from crmorder where status=0 and operatorid=$xiaomid order by orderid desc";

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
		
		function getOrderlistForXiaomiForSearch($xiaomid,$search,$startIndex,$numResults){
		  $sql = "select orderid,idcode,operatorid,consumerid,rate,weight,freight,pfreight,price,pprice,spprice,logisticsid,logisticscode,discount,cfreight,cpfreight,paystatus,memo,type,status,create_time from crmorder where status=0 and operatorid=$xiaomid and idcode=$search order by orderid desc";

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
		
		function getOrderlistByAgentid($agentid,$startIndex,$numResults){
		  global $db;
		  
		  //get all consumerid
		  $consumeridStr = "";
		  require_once("../include/cconsumerpeer.inc.php");
		  $consumerManager = new cConsumerPeer;
	      $consumerList = $consumerManager->getConsumerlist(0,10000,"", $agentid);
	      if(!empty($consumerList)){
	        $consumeridCount = 0;
	        $consumeridStr = "and consumerid in (";
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
			
		  $sql = "select orderid,idcode,operatorid,consumerid,rate,weight,freight,pfreight,price,pprice,spprice,logisticsid,logisticscode,discount,cfreight,cpfreight,paystatus,memo,type,status,create_time from crmorder where status=0 $consumeridStr order by orderid desc";

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
		
		function getOrderlistByAgentidAndIdcode($agentid,$idcode,$startIndex,$numResults){
		  global $db;
		  
		  //get all consumerid
		  $consumeridStr = "";
		  require_once("../include/cconsumerpeer.inc.php");
		  $consumerManager = new cConsumerPeer;
	      $consumerList = $consumerManager->getConsumerlist(0,10000,"", $agentid);
	      if(!empty($consumerList)){
	        $consumeridCount = 0;
	        $consumeridStr = "and consumerid in (";
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
			
		  $sql = "select orderid,idcode,operatorid,consumerid,rate,weight,freight,pfreight,price,pprice,spprice,logisticsid,logisticscode,discount,cfreight,cpfreight,paystatus,memo,type,status,create_time from crmorder where status=0 and idcode=$idcode $consumeridStr order by orderid desc";

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
		
		function getOrderByTypeCount($type){
			$sql = "select count(orderid) as total from crmorder where status=0 and type=$type";
			global $db;
				
		  	$result=$db->query($sql);
    		if($row = $db->fetch_array($result)){
    			return $row['total'];
    		}
		}
		
		function getOrderByTypeList($type, $startIndex, $numResults){
			$sql = "select orderid,idcode,operatorid,consumerid,rate,weight,freight,pfreight,price,pprice,spprice,logisticsid,logisticscode,discount,cfreight,cpfreight,paystatus,memo,type,status,create_time from crmorder where status=0 and type=$type";
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
		
		function getOrderlistbyStEdSa($startdate,$enddate,$sagentid,$paystatus){
		  if($paystatus == 0){
		    $sql = "select orderid,idcode,operatorid,consumerid,rate,weight,freight,pfreight,price,pprice,spprice,logisticsid,logisticscode,discount,cfreight,cpfreight,paystatus,memo,type,status,create_time from crmorder where paystatus=0 and status=0 and create_time between '$startdate 00:00:00' and '$enddate 23:59:59'";
		  }else{
			$sql = "select orderid,idcode,operatorid,consumerid,rate,weight,freight,pfreight,price,pprice,spprice,logisticsid,logisticscode,discount,cfreight,cpfreight,paystatus,memo,type,status,create_time from crmorder where status=0 and create_time between '$startdate 00:00:00' and '$enddate 23:59:59'";
          }
			global $db;
			
			$result=$db->query($sql);
    		$query_num = $db->num_rows($result);
    		if($query_num == 0){
    			return false;
    		}
  			$rslist = array();
  			require_once("../include/cconsumerpeer.inc.php");
    		while($row = $db->fetch_object($result)){
    			if($sagentid == 0){
    				$rslist[] = $this->load($row);
    			}else{
    		    	$consumerid = $row->consumerid;
    		    		$consumerManager = new cConsumerPeer;
  	      				$consumer = $consumerManager->getConsumer($consumerid);
  	      				$rowsagentid = $consumer->getAgentid();
  	      			if($rowsagentid == $sagentid){
          		  		$rslist[] = $this->load($row);
          			}
          		}
    		}
    		return $rslist;
		}
		
		function getOrderlistbyStEdMi($startdate,$enddate,$xiaomid){
		  global $db;
		
		  require_once("../include/user.inc.php");
          $user = new user;
          $sql_user = "select type from user where userid=$xiaomid";
          $result=$db->query($sql_user);
    	  if($row = $db->fetch_array($result)){
    	    $type = $row['type'];
    	  }else{
    	    return false;
    	  }
          
          if($type == 5){
            $sql = "select orderid,idcode,operatorid,consumerid,rate,weight,freight,pfreight,price,pprice,spprice,logisticsid,logisticscode,discount,cfreight,cpfreight,paystatus,memo,type,status,create_time from crmorder where ((status=0 and create_time between '2014-06-15 00:00:00' and '2014-09-09 23:59:59') || (status=0 and create_time >= '2014-09-10 00:00:00' and operatorid in (select userid from user where type=3 or type=5))) and create_time between '$startdate 00:00:00' and '$enddate 23:59:59'";
          }else{
            $sql = "select orderid,idcode,operatorid,consumerid,rate,weight,freight,pfreight,price,pprice,spprice,logisticsid,logisticscode,discount,cfreight,cpfreight,paystatus,memo,type,status,create_time from crmorder where status=0 and operatorid=$xiaomid and create_time between '$startdate 00:00:00' and '$enddate 23:59:59'";
          }
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
		
		/*function getOrderCountbyDate($day, $month, $year){
			if($day == 0){
				$sql = "select count(orderid) as total from crmorder where status=0 and create_time like '$year-$month%'";
			}else{
				$sql = "select count(orderid) as total from crmorder where status=0 and create_time like '$year-$month-$day%'";
			}
			global $db;
				
		  	$result=$db->query($sql);
    		if($row = $db->fetch_array($result)){
    			return $row['total'];
    		}
		}
		
		function getOrderlistbyDate($day, $month, $year){		
			if($day == 0){
				$sql = "select orderid,idcode,operatorid,consumerid,rate,weight,freight,pfreight,price,pprice,spprice,logisticsid,logisticscode,discount,cfreight,cpfreight,paystatus,memo,type,status,create_time from crmorder where status=0 and create_time like '$year-$month%'";
			}else{
				$sql = "select orderid,idcode,operatorid,consumerid,rate,weight,freight,pfreight,price,pprice,spprice,logisticsid,logisticscode,discount,cfreight,cpfreight,paystatus,memo,type,status,create_time from crmorder where status=0 and create_time like '$year-$month-$day%'";
			}

			global $db;
			
			$result=$db->query($sql);
    		$query_num = $db->num_rows($result);
    		if($query_num == 0){
    			return false;
    		}
  			$rslist = array();
    		while($row = $db->fetch_object($result)){
          		$rslist[] = $this->load($row);
    		}
    		return $rslist;
		}*/
		
		function create($order){
		  global $db;
		  date_default_timezone_set("Asia/Shanghai");
		
		  $idcode_num = 0;
		  //$idcode = date('Ymd',time());
		  $idcode = date('Ymd');
		  $operatorid = $order->getOperatorid();
		  //$today = date('Y-m-d',time());
		  $today = date('Y-m-d');
		  $sql_idcode = "select count(orderid) as total from crmorder where operatorid=$operatorid and create_time between '$today 00:00:00' and '$today 23:59:59'";
		  $result=$db->query($sql_idcode);
    	  if($row = $db->fetch_array($result)){
    	    $idcode_num = $row['total'];
    	  }
    	  if($idcode_num == 0){
    	    $idcode = $idcode.$operatorid."001";
          }else if($idcode_num > 0 && $idcode_num < 9){
            $idcode_num++;
    	    $idcode = $idcode.$operatorid."00".$idcode_num;
    	  }else if($idcode_num >= 9 && $idcode_num < 100){
    	    $idcode_num++;
    	    $idcode = $idcode.$operatorid."0".$idcode_num;
    	  }else{
    	    $idcode_num++;
    	    $idcode = $idcode.$operatorid.$idcode_num;
    	  }
    	  /*
    	  if($idcode_num == 0){
    	    $idcode = $idcode.$operatorid."001";
          }else{
            $sql_maxidcode = "select max(idcode) as max_idcode from crmorder";
		    $result = $db->query($sql_maxidcode);
    	    if($row = $db->fetch_array($result)){
    	        $max_idcode = $row['max_idcode'];
    	    }
            $idcode = $max_idcode + 1;
          }
          */
		
		  $sql = "insert into crmorder(idcode,operatorid,consumerid,rate,weight,freight,pfreight,price,pprice,spprice,logisticsid,logisticscode,discount,cfreight,cpfreight,paystatus,memo,type,status,create_time) values ('".$idcode."',".$operatorid.",".$order->getConsumerid().",".$order->getNRate().",".$order->getWeight().",".$order->getFreight().",".$order->getPfreight().",".$order->getPrice().",".$order->getPprice().",".$order->getSpprice().",".$order->getLogisticsid().",'".$order->getLogisticscode()."',".$order->getDiscount().",".$order->getCfreight().",".$order->getCpfreight().",".$order->getPaystatus().",'".$order->getMemo()."',".$order->getCType().",".$order->getPstatus().",now())";
          $db->query($sql);
		  if(mysqli_affected_rows($db->link_id) > 0 ){
		    return true;
		  }else{
			return false;
		  }
		}
		
		function update($orderid,$rate){
			$sql = "update crmorder set rate=$rate where orderid=$orderid";

			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			}else{
				return false;
			}
		}
		
		function delete($orderid){
			$sql = "update crmorder set status=4 where orderid=$orderid";
			//profit目前就不动了,全是0

			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			}else{
				return false;
			}
		}
		
		function updatePaystatus($orderid,$type){
		    if(!$this->getOrder($orderid)){
		    	return false;
		    }
		    if($type == 1){//0->1,已经付款了
		    	$sql = "update crmorder set paystatus=1 where orderid=$orderid";
		    }else{//1->0,其实没付款
		    	$sql = "update crmorder set paystatus=0 where orderid=$orderid";
		    }

			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			}else{
				return false;
			}
		}
		
		function updateMemo($orderid,$memo){
		    if(!$this->getOrder($orderid)){
		    	return false;
		    }
		    	
		    $sql = "update crmorder set memo='$memo' where orderid=$orderid";
			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			}else{
				return false;
			}
		}
		
		function updateLogistics($orderid,$logisticsid,$logisticscode){
			$sql = "update crmorder set logisticsid=$logisticsid,logisticscode='$logisticscode' where orderid=$orderid";

			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			}else{
				return false;
			}
		}
		
		function updateFreight($orderid,$freight){
			if($this->orderTriggerFreight($orderid,$freight)){
				return true;
			}else{
				return false;
			}
		}
		
		function updatepfreight($orderid,$pfreight){
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
		}
		
		function updatecombotype($orderid,$type){
			if($this->orderTriggerCombotype($orderid,$type)){
				return true;
			}else{
				return false;
			}
		}
    	
    	function Load($row){
    		$order = new cOrder;
    		$order->setOrderid($row->orderid);
    		$order->setIdcode($row->idcode);
    		$order->setOperatorid($row->operatorid);
    		$order->setConsumerid($row->consumerid);
    		$order->setNRate($row->rate);
    		$order->setWeight($row->weight);
    		$order->setFreight($row->freight);
    		$order->setPfreight($row->pfreight);
    		$order->setPrice($row->price);
    		$order->setPprice($row->pprice);
    		$order->setSpprice($row->spprice);
    		$order->setLogisticsid($row->logisticsid);
    		$order->setLogisticscode($row->logisticscode);
    		$order->setDiscount($row->discount);
    		$order->setCfreight($row->cfreight);
    		$order->setCpfreight($row->cpfreight);
    		$order->setPaystatus($row->paystatus);
    		$order->setMemo($row->memo);
    		$order->setCType($row->type);
    		$order->setPstatus($row->status);
    		$order->setCreatetime($row->create_time);
    		return $order;
    	}
    	
    	function orderTriggerFreight($orderid,$freight){
    		global $db;
    		$order = $this->getOrder($orderid);//获得本订单句柄
    		$thisFreight = $order->getFreight();//当前实收运费
    		$thisPfreight = $order->getPfreight();//当前实际运费
    		
    		//修改了实收运费，订单总价和代理看到的总价要改变的
    		$thisPrice = $order->getPrice();//当前真实总进货价格
    		$thisSpprice = $order->getSpprice();
    		$newPrice = $thisPrice-$thisFreight+$freight;
    		$newSpprice = $thisSpprice-$thisFreight+$freight;
  			
  			$sql_order = "update crmorder set freight=$freight,price=$newPrice,spprice=$newSpprice,cfreight=1 where orderid=$orderid";
			$db->query($sql_order);
			if(mysqli_affected_rows($db->link_id) <= 0 ){
				return false;
			}
			
			$thisType = $order->getCType();
			if($thisType == 2 || $thisType == 3) return true;//拼单修改实收运费不改变净利润
    		
    		//更新profit表
    		require_once("../include/cprofitpeer.inc.php");
    		$profitManager = new cProfitPeer;    		
    		$thisSevenProfit = $profitManager->getProfit($orderid,7);
    		$thisJProfit = $thisSevenProfit->getProfit();
    		$newJProfit = $thisJProfit-($thisFreight-$thisPfreight)+($freight-$thisPfreight);
    		//新净利润：原净利润-(原实收运费-原实际运费)+(原实收运费-现实际运费)

    		$sql_profit = "update profit set profit=$newJProfit where orderid=$orderid and type=7";
    		$db->query($sql_profit);
			return true;
    	}
    	
    	function orderTriggerPfreight($orderid,$pfreight){
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
			if(mysqli_affected_rows($db->link_id) <= 0 ){
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
			if(mysqli_affected_rows($db->link_id) <= 0 ){
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
    		/*if($thisDiscount > 0){//已经给过优惠了
    		  $newJProfit = $thisJProfit+$thisDiscount-$discount;//净利润：+原优惠-优惠
    		}else{
    		  $newJProfit = $thisJProfit-$discount;//净利润：-优惠
    		}*/
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
    	}
    	
    	function orderTriggerCombotype($orderid,$type){
    	  if($type == 1){
    	    global $db;
    		require_once("../include/cconstantspeer.inc.php");
    		$constantsManager = new cConstantsPeer;
    		$rrate = $constantsManager->getNewConstants(1);//当前利率
    		$packageZhiWeightG = $constantsManager->getNewConstants(3);//直邮包装重，克
    		
    		//更新crmorder表
    		$orderManager = new cOrderPeer;
    		$order = $orderManager->getOrder($orderid);//获得本订单句柄

    		$newWeight = $order->getWeight();
    		$thisRate = $order->getNRate();
    		
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

    		$thisPrice = $order->getPrice();//原总价
    		$thisFreight = $order->getFreight();//当前实收运费
    		$newPrice = $thisPrice-$thisFreight+$newFreight;//新总价：原总价-原总运费+新总运费，人民币
    		
  			$thisPprice = $order->getPprice();//当前真实总进货价格
  			$thisSpprice = $order->getSpprice();//当前代理看到总进货价格
  			$thisPfreight = $order->getPfreight();//当前实际运费
  			$newPprice = $thisPprice-$thisPfreight+$newPfreight;//新进货真实总价:原进货真实总价-当前实际运费+新实付运费
  			$newSpprice = $thisSpprice-$thisFreight+$newFreight;//新进货代理总价:原进货代理总价-当前实收运费+新实收总运费
  			
  			$sql_order = "update crmorder set freight=$newFreight,pfreight=$newPfreight,price=$newPrice,pprice=$newPprice,spprice=$newSpprice,type=$type where orderid=$orderid";
			$db->query($sql_order);
			if(mysqli_affected_rows($db->link_id) <= 0 ){
				return false;
			}
    		
    		//更新profit表
    		require_once("../include/cprofitpeer.inc.php");
    		$profitManager = new cProfitPeer;
    		
    		$thisSevenProfit = $profitManager->getProfit($orderid,7);
    		$thisJProfit = $thisSevenProfit->getProfit();//净利润
    		$newJProfit = $thisJProfit-($thisFreight-$thisPfreight)+($newFreight-$newPfreight);
    		//echo $newJProfit."$thisJProfit-($thisFreight-$thisPfreight)+($newFreight-$newPfreight)";
    		//净利润：原净利润-原运费收入+新运费收入

    		$sql_profit_7 = "update profit set profit=$newJProfit where orderid=$orderid and type=7";
    		$db->query($sql_profit_7);
			return true;
    	  }else if($type == 2){//normal -> combo
    	    global $db;
    	    require_once("../include/cconstantspeer.inc.php");
    		$constantsManager = new cConstantsPeer;
    		$rrate = $constantsManager->getNewConstants(1);//当前利率
    		$rcombototalweight = $constantsManager->getNewConstants(2);//当前拼单后产品总重
    		$packagePinWeightG = $constantsManager->getNewConstants(4);//拼单包装重，克
    		
    		//更新crmorder表
    		$orderManager = new cOrderPeer;
    		$order = $orderManager->getOrder($orderid);//获得本订单句柄

	        //weight不变
	        $thisWeight = $order->getWeight();
    		$thisRate = $order->getNRate();
    		
    		//运费公式：(weight/total)*[(total+0.7)*8*5.2]
    		if($thisWeight <= 0){
    		  $newFreight = 0;
    		  $newPfreight = 0;
    		}else{
    		  $wzhanbi = round($thisWeight/$rcombototalweight,2);
    		  if($wzhanbi < $thisWeight/$rcombototalweight) $wzhanbi = $wzhanbi+0.01;
    		  $newFreight = $wzhanbi*((($rcombototalweight+$packagePinWeightG)/1000)*8*$thisRate);//新实收总运费，人民币
    		  $newFreight = round($newFreight,2);
    		  $newPfreight = $wzhanbi*((($rcombototalweight+$packagePinWeightG)/1000)*7*($rrate-0.1));//新实收总运费，人民币
    		  $newPfreight = round($newPfreight,2);
    		}
    		
    		$thisPrice = $order->getPrice();//原总价
    		$thisFreight = $order->getFreight();//当前实收运费
    		$newPrice = $thisPrice-$thisFreight+$newFreight;//新总价，原总价-原总运费+新总运费，人民币

  			$thisPprice = $order->getPprice();//当前真实总进货价格
  			$thisSpprice = $order->getSpprice();//当前代理看到总进货价格
  			$thisPfreight = $order->getPfreight();//当前实际运费
  			$newPprice = $thisPprice-$thisPfreight+$newPfreight;//新进货真实总价:原进货真实总价-当前实际运费+新实付运费
  			$newSpprice = $thisSpprice-$thisFreight+$newFreight;//新进货代理总价:原进货代理总价-当前实收运费+新实收总运费

  			$sql_order = "update crmorder set freight=$newFreight,pfreight=$newPfreight,price=$newPrice,pprice=$newPprice,spprice=$newSpprice,type=$type where orderid=$orderid";
			$db->query($sql_order);
			if(mysqli_affected_rows($db->link_id) <= 0 ){
				return false;
			}
    		
    		//更新profit表
    		require_once("../include/cprofitpeer.inc.php");
    		$profitManager = new cProfitPeer;
		
		    $thisSevenProfit = $profitManager->getProfit($orderid,7);
    		$thisJProfit = $thisSevenProfit->getProfit();//净利润
    		$newJProfit = $thisJProfit-($thisFreight-$thisPfreight)+($newFreight-$newPfreight);
    		//echo $newJProfit."---"."$thisJProfit-($thisFreight-$thisPfreight)+($newFreight-$newPfreight)";
    		//净利润：原净利润-原运费收入+新运费收入
    		
    		$sql_profit_7 = "update profit set profit=$newJProfit where orderid=$orderid and type=7";
    		$db->query($sql_profit_7);
			return true;
    	  }
    	}
    	
    	function orderTriggerPrice_backup($orderid,$discount){
    		global $db;
    		
    		//更新crmorder表
    		$order = $this->getOrder($orderid);//获得本订单句柄
    		$thisPrice = $order->getPrice();//原总价
    		$newPrice = $thisPrice-$discount;//新总价：原总价-优惠，人民币
    		
  			$sql_order = "update crmorder set price=$newPrice,discount=$discount where orderid=$orderid";
			$db->query($sql_order);
			if(mysqli_affected_rows($db->link_id) <= 0 ){
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
    		$newTotalProfit = $thisTotalProfit-$discount;//实际总利润：原总利润-优惠
    		  
    		$thisTwoProfit = $profitManager->getProfit($orderid,2);
    		$thisSTotalProfit = $thisTwoProfit->getProfit();//代理总利润
    		$newSTotalProfit = $thisSTotalProfit-$discount;//代理总利润：原代理总利润-优惠
    		
    		$thisThreeProfit = $profitManager->getProfit($orderid,3);
    		$thisSagentProfit = $thisThreeProfit->getProfit();//原代理人利润
    		$thisSixProfit = $profitManager->getProfit($orderid,6);
    		$thisYzProfit = $thisSixProfit->getProfit();//YZ利润
    		if($sAngentid > 0){//有代理
    		  $newSagentProfit = $thisSagentProfit-($discount/2);//代理总利润：原代理总利润-优惠/2
    	      $newYzProfit = $newSagentProfit;
    		}else{
    		  $newSagentProfit = 0;
    		  $newYzProfit = $thisYzProfit-$discount;//Yz承担所有优惠
    		}
    		  
    		$thisSevenProfit = $profitManager->getProfit($orderid,7);
    		$thisJProfit = $thisSevenProfit->getProfit();//净利润
    		$newJProfit = $thisJProfit-$discount;//净利润：-运费
    		
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
    	}
    }
?>