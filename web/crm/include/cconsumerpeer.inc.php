<?php
	require_once("cconsumer.inc.php");
	
	class cConsumerPeer{   
    	function getConsumer($consumerid){
    		$query_sql = "select consumerid,name,address,memo,mobile,telephone,wechat,agentid,xiaomid,type,status,create_time from consumer where status=0 and consumerid=";
    		$sql = $query_sql.$consumerid;
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
    	
    	function getAnyConsumer($consumerid){
    		$query_sql = "select consumerid,name,address,memo,mobile,telephone,wechat,agentid,xiaomid,type,status,create_time from consumer where consumerid=";
    		$sql = $query_sql.$consumerid;
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
    	
    	function getConsumerByName($name){
    		$sql = "select consumerid,name,address,memo,mobile,telephone,wechat,agentid,xiaomid,type,status,create_time from consumer where status=0 and name='".$name."'";
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
    	
    	function getConsumerCount($search="", $agentid){
    		if ($search != ""){
		  		$search = "where status=0 and name like '%$search%'";
	  		}else{
	   			$search = "where status=0";
	  		}
	  		if($agentid != 0){
	  		  $search = $search." and agentid=$agentid";
	  		}
			$sql = "select count(consumerid) as total from consumer $search";
			global $db;
				
		  	$result=$db->query($sql);
    		if($row = $db->fetch_array($result)){
    			return $row['total'];
    		}
		}
		
		function getConsumerPlusmemoCount($search="", $agentid){
    		if ($search != ""){
		  		$search = "where status=0 and (name like '%$search%' or memo like '%$search%')";
	  		}else{
	   			$search = "where status=0";
	  		}
	  		if($agentid != 0){
	  		  $search = $search." and agentid=$agentid";
	  		}
			$sql = "select count(consumerid) as total from consumer $search";
			global $db;
				
		  	$result=$db->query($sql);
    		if($row = $db->fetch_array($result)){
    			return $row['total'];
    		}
		}
		
		function getConsumerXiaomiCount($search="", $xiaomid){
    		if ($search != ""){
		  		//$search = "where status=0 and name like '%$search%'";
		  		$search = "where status=0 and (name like '%$search%' or memo like '%$search%')";
	  		}else{
	   			$search = "where status=0";
	  		}
	  		$search = $search." and xiaomid=$xiaomid";

			$sql = "select count(consumerid) as total from consumer $search";
			global $db;

		  	$result=$db->query($sql);
    		if($row = $db->fetch_array($result)){
    			return $row['total'];
    		}
		}
		
		function getConsumerlist($startIndex,$numResults,$search="", $agentid){
			if ($search != ""){
		  		$search = "where status=0 and (name like '%$search%' or memo like '%$search%')";
	  		}else{
	   			$search = "where status=0";
	  		}
	  		if($agentid != 0){
	  		  $search = $search." and agentid=$agentid";
	  		}
			$sql = "select consumerid,name,address,memo,mobile,telephone,wechat,agentid,xiaomid,type,status,create_time from consumer $search order by consumerid desc";

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
		
		function getConsumerXiaomilist($startIndex,$numResults,$search="", $xiaomid){
			if ($search != ""){
		  		$search = "where status=0 and (name like '%$search%' or memo like '%$search%')";
	  		}else{
	   			$search = "where status=0";
	  		}
	  		$search = $search." and xiaomid=$xiaomid";

			$sql = "select consumerid,name,address,memo,mobile,telephone,wechat,agentid,xiaomid,type,status,create_time from consumer $search order by consumerid desc";

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
		
		function create($consumer){
			$sql = "insert into consumer(name,address,memo,mobile,telephone,wechat,agentid,xiaomid,type,status,create_time) values ('".$consumer->getCname()."','".$consumer->getAddress()."','".$consumer->getMemo()."','".$consumer->getMobile()."','".$consumer->getTelephone()."','".$consumer->getWechat()."',".$consumer->getAgentid().",".$consumer->getXiaomid().",".$consumer->getCtype().",".$consumer->getPstatus().",now())";

			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			}else{
				return false;
			}
		}
		
		function update($consumer){
			$sql = "update consumer set name='".$consumer->getCname()."',address='".$consumer->getAddress()."',memo='".$consumer->getMemo()."',mobile='".$consumer->getMobile()."',telephone='".$consumer->getTelephone()."',wechat='".$consumer->getWechat()."',agentid=".$consumer->getAgentid().",type=".$consumer->getCtype().",status=".$consumer->getPstatus()." where consumerid=".$consumer->getConsumerid();

			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			}else{
				return false;
			}
		}
		
		function updateXiaomid($xiaomid,$consumerid){
			$sql = "update consumer set xiaomid=$xiaomid where consumerid=$consumerid";

			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			}else{
				return false;
			}
		}
		
		function delete($consumerid){
			$sql = "update consumer set status=4 where consumerid=$consumerid";

			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			}else{
				return false;
			}
		}
    	
    	function Load($row){
    		$consumer = new cConsumer;
    		$consumer->setConsumerid($row->consumerid);
    		$consumer->setCname($row->name);
    		$consumer->setAddress($row->address);
    		$consumer->setMemo($row->memo);
    		$consumer->setMobile($row->mobile);
    		$consumer->setTelephone($row->telephone);
    		$consumer->setWechat($row->wechat);
    		$consumer->setAgentid($row->agentid);
    		$consumer->setXiaomid($row->xiaomid);
    		$consumer->setCtype($row->type);
    		$consumer->setPstatus($row->status);
    		$consumer->setCreatetime($row->create_time);
    		return $consumer;
    	}
    }
?>