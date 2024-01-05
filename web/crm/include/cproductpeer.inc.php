<?php
	require_once("cproduct.inc.php");
	
	class cProductPeer{   
    	function getProduct($productid){
    		$query_sql = "select productid,catalogid,brandid,ename,cname,spec,unit,unitnum,ppricenz,spricecn,weight,supermarket,agentid,status,create_time from product where productid=";
    		$sql = $query_sql.$productid;
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
    	
    	function getProductCount($status,$search=""){
    	    /*if ($search != ""){
		  		$search = "where cname like '%$search%' or ename like '%$search%'";
		  		$search1 = "and (cname like '%$search%' or ename like '%$search%')";
	  		}else{
	   			$search = "";
	   			$search1 = "";
	  		}
    	
			if($status == 0){
				$sql = "select count(productid) as total from product $search";
			}else{
				$sql = "select count(productid) as total from product where status=$status $search1";
			}*/
			global $db;
			if ($search != ""){
		  		$search = "and (cname like '%$search%' or ename like '%$search%')";
	  		}else{
	   			$search = "";
	  		}
			$sql = "select count(productid) as total from product where status=$status $search";
			
		  	$result=$db->query($sql);
    		if($row = $db->fetch_array($result)){
    			return $row['total'];
    		}
		}
		
		function getProductlist($status,$startIndex,$numResults,$search=""){
		    /*if ($search != ""){
		  		$search = "where cname like '%$search%' or ename like '%$search%'";
		  		$search1 = "and (cname like '%$search%' or ename like '%$search%')";
	  		}else{
	   			$search = "";
	   			$search1 = "";
	  		}
				
			if($status == 0){
				$sql = "select productid,catalogid,brandid,ename,cname,spec,unit,unitnum,ppricenz,spricecn,weight,ffree,agentid,status,create_time from product $search order by productid desc";
			}else{
				$sql = "select productid,catalogid,brandid,ename,cname,spec,unit,unitnum,ppricenz,spricecn,weight,ffree,agentid,status,create_time from product where status=$status $search1 order by productid desc";
			}*/
			global $db;
			if ($search != ""){
		  		$search = "and (cname like '%$search%' or ename like '%$search%')";
	  		}else{
	   			$search = "";
	  		}
			$sql = "select productid,catalogid,brandid,ename,cname,spec,unit,unitnum,ppricenz,spricecn,weight,supermarket,agentid,status,create_time from product where status=$status $search order by productid desc";
			
			
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
		
		function create($product){
			$sql = "insert into product(catalogid,brandid,ename,cname,spec,unit,unitnum,ppricenz,spricecn,weight,supermarket,agentid,status,create_time) values (".$product->getCatalogid().",".$product->getBrandid().",'".$product->getEname()."','".$product->getCname()."',".$product->getSpec().",".$product->getUnit().",".$product->getUnitnum().",".$product->getPpricenz().",".$product->getSpricecn().",".$product->getWeight().",".$product->getSupermarket().",".$product->getAgentid().",".$product->getPstatus().",now())";

			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			}else{
				return false;
			}
		}
		
		function update($product){
			$sql = "update product set catalogid=".$product->getCatalogid().",brandid=".$product->getBrandid().",ename=\"".$product->getEname()."\",cname=\"".$product->getCname()."\",spec=".$product->getSpec().",unit=".$product->getUnit().",unitnum=".$product->getUnitnum().",ppricenz=".$product->getPpricenz().",spricecn=".$product->getSpricecn().",weight=".$product->getWeight().",supermarket=".$product->getSupermarket().",agentid=".$product->getAgentid().",status=".$product->getPstatus()." where productid=".$product->getProductid();

			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			}else{
				return false;
			}
		}
		
		function delete($productid){
			$sql = "update product set status=4 where productid=$productid";

			global $db;
			$db->query($sql);
			if(mysqli_affected_rows($db->link_id) > 0 ){
				return true;
			}else{
				return false;
			}
		}
    	
    	function Load($row){
    		$product = new cProduct;
    		$product->setProductid($row->productid);
    		$product->setCatalogid($row->catalogid);
    		$product->setBrandid($row->brandid);
    		$product->setEname($row->ename);
    		$product->setCname($row->cname);
    		$product->setSpec($row->spec);
    		$product->setUnit($row->unit);
    		$product->setUnitnum($row->unitnum);
    		$product->setPpricenz($row->ppricenz);
    		$product->setSpricecn($row->spricecn);
    		$product->setWeight($row->weight);
    		$product->setSupermarket($row->supermarket);
    		$product->setAgentid($row->agentid);
    		$product->setPstatus($row->status);
    		$product->setCreatetime($row->create_time);
    		return $product;
    	}
    }
?>