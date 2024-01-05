<?php
	class cLogistics{
		var $logisticsid;
		var $name;
		var $description;
		var $preurl;
		var $type;
		var $create_time;
		
		function getLogisticsid(){
			return $this->logisticsid;
		}
		
		function setLogisticsid($logisticsid){
			$this->logisticsid = $logisticsid;
		}
		
		function getCName(){
			return $this->name;
		}
		
		function setCName($name){
			$this->name = $name;
		}
		
		function getDescription(){
			return $this->description;
		}
		
		function setDescription($description){
			$this->description = $description;
		}
		
		function getPreurl(){
			return $this->preurl;
		}
		
		function setPreurl($preurl){
			$this->preurl = $preurl;
		}
		
		function getCType(){
			return $this->type;
		}
		
		function setCType($type){
			$this->type = $type;
		}
		
		function getCreatetime(){
			return $this->create_time;
		}
		
		function setCreatetime($createtime){
			$this->create_time = $createtime;
		}
	}
?>