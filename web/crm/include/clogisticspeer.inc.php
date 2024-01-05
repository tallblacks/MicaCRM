<?php
	require_once("clogistics.inc.php");
	
	class cLogisticsPeer{   
    	function getLogistics($logisticsid){
    		$query_sql = "select logisticsid,name,description,preurl,type,create_time from logistics where logisticsid=";
    		$sql = $query_sql.$logisticsid;
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
    	
    	function getLogisticsCount($type){
			if($type == 0){
				$sql = "select count(logisticsid) as total from logistics";
			}else{
				$sql = "select count(logisticsid) as total from logistics where type=".$type;
			}
			global $db;
				
		  	$result=$db->query($sql);
    		if($row = $db->fetch_array($result)){
    			return $row['total'];
    		}
		}
		
		function getLogisticslist($type,$startIndex,$numResults){		
			if($type == 0){
				$sql = "select logisticsid,name,description,preurl,type,create_time from logistics order by logisticsid desc";
			}else{
				$sql = "select logisticsid,name,description,preurl,type,create_time from logistics where type=$type order by logisticsid desc";
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
		
		function update($logisticsid,$name,$description,$preurl){
			$sql = "update logistics set name='$name',description='$description',preurl='$preurl' where logisticsid=$logisticsid";

			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			}else{
				return false;
			}
		}
		
		function delete($logisticsid){
			$sql = "delete from logistics where logisticsid=$logisticsid";
			//$sql = "update logistics set type=4 where logisticsid=$logisticsid";

			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			}else{
				return false;
			}
		}
		
		function create($logistics){
			$sql = "insert into logistics(name,description,preurl,type,create_time) values ('".$logistics->getCName()."','".$logistics->getDescription()."','".$logistics->getPreurl()."',".$logistics->getCType().",now())";

			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			}else{
				return false;
			}
		}
    	
    	function Load($row){
    		$logistics = new cLogistics;
    		$logistics->setLogisticsid($row->logisticsid);
    		$logistics->setCName($row->name);
    		$logistics->setDescription($row->description);
    		$logistics->setPreurl($row->preurl);
    		$logistics->setCType($row->type);
    		$logistics->setCreatetime($row->create_time);
    		return $logistics;
    	}
    }
?>