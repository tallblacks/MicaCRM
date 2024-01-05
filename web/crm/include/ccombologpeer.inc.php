<?php
	require_once("ccombolog.inc.php");
	
	class cCombologPeer{
	    function getCombolog($combologid){
    		$query_sql = "select comborderid,orderid,status,create_time from combolog where combologid=";
    		$sql = $query_sql.$combologid;
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
    	
    	function getComborderidByOrderid($orderid){
    		$query_sql = "select comborderid from combolog where orderid=";
    		$sql = $query_sql.$orderid;
    		global $db;
    		
    		$result=$db->query($sql);
    		if($row = $db->fetch_array($result)){
    			return $row['comborderid'];
    		}
    		
    		return 0;
    	}
    	
    	function getOrderStatus($orderid){
    	  global $db;
    	  $sql1 = "select count(combologid) as total from combolog where orderid=$orderid";
    	  $sql2 = "select status from combolog where orderid=$orderid order by combologid desc";
			
		  $result=$db->query($sql1);
    	  if($row = $db->fetch_array($result)){
    	    $total = $row['total'];
    	    if($total == 0){
    	      return 0;
    	    }
    			
    	    $result=$db->query($sql2);
    	    if($row = $db->fetch_array($result)){
    	      $status = $row['status'];
    		  
    		  if($status == 4){
    		    return 0;//ok
    		  }else{
    		    return 1;//no
    		  }
    		}
    	  }
    	  return 0;
    	}
	    
    	function getCombologList($comborderid){
    		$query_sql = "select combologid,orderid,status,create_time from combolog where status=0 and comborderid=";
    		$sql = $query_sql.$comborderid;
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
    	
    	function getCombologMaxOrderid($comborderid){
			$sql = "select max(orderid) as max from combolog where comborderid=$comborderid";
			global $db;
				
		  	$result=$db->query($sql);
    		if($row = $db->fetch_array($result)){
    			return $row['max'];
    		}
		}
		
		function create($combolog){
			$sql1 = "insert into combolog(comborderid,orderid,status,create_time) values (".$combolog->getComborderid().",".$combolog->getOrderid().",".$combolog->getPstatus().",now())";
            $sql2 = "update crmorder set type=3 where orderid=".$combolog->getOrderid();
            global $db;
			
			$db->query($sql1);
			if(mysqli_affected_rows($db->link_id) > 0 ){
              $db->query($sql2);
			  if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			  }else{
			    return false;
			  }
			}else{
			  return false;
			}
		}
		
		function delete($combologid,$orderid){
			$sql1 = "update combolog set status=4 where combologid=$combologid";
			$sql2 = "update crmorder set type=2 where orderid=$orderid";
            global $db;
			
			$db->query($sql1);
			if(mysqli_affected_rows($db->link_id) > 0 ){
			  $db->query($sql2);
			  if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			  }else{
			    return false;
			  }
			}else{
				return false;
			}
		}
		
		/*function update($consumer){
			$sql = "update consumer set name='".$consumer->getCname()."',address='".$consumer->getAddress()."',memo='".$consumer->getMemo()."',mobile='".$consumer->getMobile()."',telephone='".$consumer->getTelephone()."',agentid=".$consumer->getAgentid().",type=".$consumer->getCtype().",status=".$consumer->getPstatus()." where consumerid=".$consumer->getConsumerid();

			global $db;
			$db->query($sql);
			if(mysql_affected_rows() > 0 ){
				return true;
			}else{
				return false;
			}
		}
		
		*/
    	
    	function Load($row){
    		$combolog = new cCombolog;
    		$combolog->setCombologid($row->combologid);
    		$combolog->setComborderid(@$row->comborderid);
    		$combolog->setOrderid($row->orderid);
    		$combolog->setPstatus($row->status);
    		$combolog->setCreatetime($row->create_time);
    		return $combolog;
    	}
    }
?>