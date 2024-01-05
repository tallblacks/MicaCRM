<?php
	class cProduct{
		var $productid;
		var $catalogid;
		var $brandid;
		var $ename;
		var $cname;
		var $spec;
		var $unit;
		var $unitnum;
		var $ppricenz;
		var $spricecn;
		var $weight;
		var $supermarket;
		var $agentid;
		var $status;
		var $create_time;
		
		function getProductid(){
			return $this->productid;
		}
		
		function setProductid($productid){
			$this->productid = $productid;
		}
		
		function getCatalogid(){
			return $this->catalogid;
		}
		
		function setCatalogid($catalogid){
			$this->catalogid = $catalogid;
		}
		
		function getBrandid(){
			return $this->brandid;
		}
		
		function setBrandid($brandid){
			$this->brandid = $brandid;
		}
		
		function getEname(){
			return $this->ename;
		}
		
		function setEname($ename){
			$this->ename = $ename;
		}
		
		function getCname(){
			return $this->cname;
		}
		
		function setCname($cname){
			$this->cname = $cname;
		}
		
		function getSpec(){
			return $this->spec;
		}
		
		function setSpec($spec){
			$this->spec = $spec;
		}
		
		function getUnit(){
			return $this->unit;
		}
		
		function setUnit($unit){
			$this->unit = $unit;
		}
		
		function getUnitnum(){
			return $this->unitnum;
		}
		
		function setUnitnum($unitnum){
			$this->unitnum = $unitnum;
		}
		
		function getPpricenz(){
			return $this->ppricenz;
		}
		
		function setPpricenz($ppricenz){
			$this->ppricenz = $ppricenz;
		}
		
		function getSpricecn(){
			return $this->spricecn;
		}
		
		function setSpricecn($spricecn){
			$this->spricecn = $spricecn;
		}
		
		function getWeight(){
			return $this->weight;
		}
		
		function setWeight($weight){
			$this->weight = $weight;
		}
		
		function getSupermarket(){
			return $this->supermarket;
		}
		
		function setSupermarket($supermarket){
			$this->supermarket = $supermarket;
		}
		
		function getAgentid(){
			return $this->agentid;
		}
		
		function setAgentid($agentid){
			$this->agentid = $agentid;
		}
		
		function getPstatus(){
			return $this->status;
		}
		
		function setPstatus($status){
			$this->status = $status;
		}
		
		function getCreatetime(){
			return $this->create_time;
		}
		
		function setCreatetime($createtime){
			$this->create_time = $createtime;
		}
	}
?>