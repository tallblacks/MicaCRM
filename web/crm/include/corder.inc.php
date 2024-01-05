<?php
	class cOrder{
		var $orderid;
		var $idcode;
		var $operatorid;
		var $consumerid;
		var $rate;
		var $weight;
		var $freight;
		var $pfreight;
		var $price;
		var $pprice;
		var $spprice;
		var $logisticsid;
		var $logisticscode;
		var $discount;
		var $cfreight;
		var $cpfreight;
		var $paystatus;
		var $memo;
		var $type;
		var $status;
		var $create_time;
		
		function getOrderid(){
			return $this->orderid;
		}
		
		function setOrderid($orderid){
			$this->orderid = $orderid;
		}
		
		function getIdcode(){
			return $this->idcode;
		}
		
		function setIdcode($idcode){
			$this->idcode = $idcode;
		}
		
		function getOperatorid(){
			return $this->operatorid;
		}
		
		function setOperatorid($operatorid){
			$this->operatorid = $operatorid;
		}
		
		function getConsumerid(){
			return $this->consumerid;
		}
		
		function setConsumerid($consumerid){
			$this->consumerid = $consumerid;
		}
		
		function getNRate(){
			return $this->rate;
		}
		
		function setNRate($rate){
			$this->rate = $rate;
		}
		
		function getWeight(){
			return $this->weight;
		}
		
		function setWeight($weight){
			$this->weight = $weight;
		}
		
		function getFreight(){
			return $this->freight;
		}
		
		function setFreight($freight){
			$this->freight = $freight;
		}
		
		function getPfreight(){
			return $this->pfreight;
		}
		
		function setPfreight($pfreight){
			$this->pfreight = $pfreight;
		}
		
		function getPrice(){
			return $this->price;
		}
		
		function setPrice($price){
			$this->price = $price;
		}
		
		function getPprice(){
			return $this->pprice;
		}
		
		function setPprice($pprice){
			$this->pprice = $pprice;
		}
		
		function getSpprice(){
			return $this->spprice;
		}
		
		function setSpprice($spprice){
			$this->spprice = $spprice;
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
		
		function getDiscount(){
			return $this->discount;
		}
		
		function setDiscount($discount){
			$this->discount = $discount;
		}
		
		function getCfreight(){
			return $this->cfreight;
		}
		
		function setCfreight($cfreight){
			$this->cfreight = $cfreight;
		}
		
		function getCpfreight(){
			return $this->cpfreight;
		}
		
		function setCpfreight($cpfreight){
			$this->cpfreight = $cpfreight;
		}
		
		function getPaystatus(){
			return $this->paystatus;
		}
		
		function setPaystatus($paystatus){
			$this->paystatus = $paystatus;
		}
		
		function getMemo(){
			return $this->memo;
		}
		
		function setMemo($memo){
			$this->memo = $memo;
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