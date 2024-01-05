<?php
	class cProfit{
		var $profitid;
		var $orderid;
		var $profit;
		var $type;
		var $create_time;
		
		function getProfitid(){
			return $this->profitid;
		}
		
		function setProfitid($profitid){
			$this->profitid = $profitid;
		}
		
		function getOrderid(){
			return $this->orderid;
		}
		
		function setOrderid($orderid){
			$this->orderid = $orderid;
		}
		
		function getProfit(){
			return $this->profit;
		}
		
		function setProfit($profit){
			$this->profit = $profit;
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