<?php
    require_once("cconstants.inc.php");
	
	class cConstantsPeer
	{   
		function getConstants($constantsid)
		{
    		$query_sql = "select constantsid,constants,type,create_time from constants where constantsid=";
    		$sql = $query_sql.$constantsid;
    		global $db;
    	
    		$result=$db->query($sql);
    		$query_num = $db->num_rows($result);
    		if ($query_num == 0) {
    			return false;
    		}
    	
    		if ($row = $db->fetch_object($result)) {
    			return $this->Load($row);
    		}
    	}
    	
		function getNewConstants($type)
		{
    		$query_sql = "select constants from constants where type=$type order by constantsid desc limit 1";
    		$sql = $query_sql;
    		global $db;
    	
    		$result=$db->query($sql);
    		$query_num = $db->num_rows($result);
    		if ($query_num == 0) {
    			return false;
    		}
    	
    		if ($row = $db->fetch_object($result)) {
    			return $row->constants;
    		}
    	}
    	
		function getConstantsCount($type)
		{
			if ($type == 0) {
				$sql = "select count(constantsid) as total from constants";
			} else {
				$sql = "select count(constantsid) as total from constants where type=".$type;
			}
			global $db;
				
		  	$result=$db->query($sql);
    		if ($row = $db->fetch_array($result)) {
    			return $row['total'];
    		}
		}
		
		function getConstantslist($type,$startIndex,$numResults)
		{		
			if ($type == 0) {
				$sql = "select constantsid,constants,type,create_time from constants order by constantsid desc";
			} else {
				$sql = "select constantsid,constants,type,create_time from constants where type=$type order by constantsid desc";
			}

			global $db;
			
			$result=$db->query($sql);
    		$query_num = $db->num_rows($result);
    		if ($query_num == 0) {
    			return false;
    		}
    		$i = 0;
    		$j = 0;
  			$rslist = array();
    		while ($row = $db->fetch_object($result)) {
    			if ($i < $startIndex) {
    				$i++;
    				continue;
    			}
    			if ($j < $numResults) {
          			$rslist[] = $this->load($row);
          			$j++;
    			}
    		}
    		return $rslist;
		}
		
		function create($constants)
		{
			$sql = "insert into constants(constants,type,create_time) values (".$constants->getConstants().",".$constants->getCType().",now())";

			global $db;
			$db->query($sql);
			if (mysqli_affected_rows($db->link_id) > 0) {
				return true;
			} else {
				return false;
			}
		}
		
		function update($constantsid,$constantsVal,$type)
		{
			$sql = "update constants set constants=$constantsVal,type=$type where constantsid=$constantsid";

			global $db;
			$db->query($sql);
			if (mysqli_affected_rows($db->link_id) > 0) {
				return true;
			} else {
				return false;
			}
		}
		
		function delete($constantsid)
		{
			$sql = "delete from constants where constantsid=$constantsid";

			global $db;
			$db->query($sql);
			if (mysqli_affected_rows($db->link_id) > 0) {
				return true;
			} else {
				return false;
			}
		}
    	
		function Load($row)
		{
    		$constants = new cConstants;
    		$constants->setConstantsid($row->constantsid);
    		$constants->setConstants($row->constants);
    		$constants->setCType($row->type);
    		$constants->setCreatetime($row->create_time);
    		return $constants;
    	}
    }
?>