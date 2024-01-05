<?php
	class cComborder{
		var $comborderid;
		var $name;
		var $address;
		var $mobile;
		var $memo;
		var $logisticsid;
		var $logisticscode;
		var $type;
		var $status;
		var $create_time;
		
		function getComborderid(){
			return $this->comborderid;
		}
		
		function setComborderid($comborderid){
			$this->comborderid = $comborderid;
		}
		
		function getCname(){
			return $this->name;
		}
		
		function setCname($name){
			$this->name = $name;
		}
		
		function getAddress(){
			return $this->address;
		}
		
		function setAddress($address){
			$this->address = $address;
		}
		
		function getMobile(){
			return $this->mobile;
		}
		
		function setMobile($mobile){
			$this->mobile = $mobile;
		}
		
		function getMemo(){
			return $this->memo;
		}
		
		function setMemo($memo){
			$this->memo = $memo;
		}
		
		function getLogisticsid(){
			return $this->logisticsid;
		}
		
		function setLogisticsid($logisticsid){
			$this->logisticsid = $logisticsid;
		}
		
		function getLogisticscode(){
			return $this->logisticscode;
		}
		
		function setLogisticscode($logisticscode){
			$this->logisticscode = $logisticscode;
		}
		
		function getCType(){
			return $this->type;
		}
		
		function setCType($type){
			$this->type = $type;
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