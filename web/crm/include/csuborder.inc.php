<?php
	class cSuborder{
		var $suborderid;
		var $orderid;
		var $productid;
		var $price;
		var $ordernum;
		var $weight;
		var $supermarket;
		var $type;
		var $status;
		var $create_time;
		
		function getSuborderid(){
			return $this->suborderid;
		}
		
		function setSuborderid($suborderid){
			$this->suborderid = $suborderid;
		}
		
		function getOrderid(){
			return $this->orderid;
		}
		
		function setOrderid($orderid){
			$this->orderid = $orderid;
		}
		
		function getProductid(){
			return $this->productid;
		}
		
		function setProductid($productid){
			$this->productid = $productid;
		}
		
		function getPrice(){
			return $this->price;
		}
		
		function setPrice($price){
			$this->price = $price;
		}
		
		function getOrdernum(){
			return $this->ordernum;
		}
		
		function setOrdernum($ordernum){
			$this->ordernum = $ordernum;
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