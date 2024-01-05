<?php
	class cCombolog{
		var $combologid;
		var $comborderid;
		var $orderid;
		var $status;
		var $create_time;
		
		function getCombologid(){
			return $this->combologid;
		}
		
		function setCombologid($combologid){
			$this->combologid = $combologid;
		}
		
		function getComborderid(){
			return $this->comborderid;
		}
		
		function setComborderid($comborderid){
			$this->comborderid = $comborderid;
		}
		
		function getOrderid(){
			return $this->orderid;
		}
		
		function setOrderid($orderid){
			$this->orderid = $orderid;
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