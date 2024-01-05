<?php
  require_once("ccatalog.inc.php");
  
  class cCatalogPeer{
  	function getCatalog($catalogid){
  		$sql = "select catalogid,name from catalog where catalogid = $catalogid";
  		global $db;
  		 
  		$result=$db->query($sql);
  		$query_num = $db->num_rows($result);
  		if($query_num == 0) return false;
  		if($row = $db->fetch_object($result)) return $this->Load($row);
  	}
  	
      function getCatalogList(){
        $sql = "select catalogid,name from catalog";
        global $db;
    	
        $result=$db->query($sql);
        $query_num = $db->num_rows($result);
        if($query_num == 0) return false;
            
        $rslist = array();
        while($row = $db->fetch_object($result)){
          $rslist[] = $this->load($row);
        }
        return $rslist;
      }
      
      function update($catalogid,$name){
      	$sql = "update catalog set name='$name' where catalogid=$catalogid";
      
      	global $db;
      	$db->query($sql);
      	if(mysqli_affected_rows($db->link_id) > 0 ){
      		return true;
      	}else{
      		return false;
      	}
      }

      function Load($row){
        $catalog = new cCatalog;
        $catalog->setCatalogid($row->catalogid);
        $catalog->setName($row->name);
        
        return $catalog;
      }
  }
?>