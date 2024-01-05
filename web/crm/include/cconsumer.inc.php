<?php
	class cConsumer{
		var $consumerid;
		var $name;
		var $address;
		var $memo;
		var $mobile;
		var $telephone;
		var $wechat;
		var $agentid;
		var $xiaomid;
		var $type;
		var $status;
		var $create_time;
		
		function getConsumerid(){
			return $this->consumerid;
		}
		
		function setConsumerid($consumerid){
			$this->consumerid = $consumerid;
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
		
		function getMemo(){
			return $this->memo;
		}
		
		function setMemo($memo){
			$this->memo = $memo;
		}
		
		function getMobile(){
			return $this->mobile;
		}
		
		function setMobile($mobile){
			$this->mobile = $mobile;
		}
		
		function getTelephone(){
			return $this->telephone;
		}
		
		function setTelephone($telephone){
			$this->telephone = $telephone;
		}
		
		function getWechat(){
			return $this->wechat;
		}
		
		function setWechat($wechat){
			$this->wechat = $wechat;
		}
		
		function getAgentid(){
			return $this->agentid;
		}
		
		function setAgentid($agentid){
			$this->agentid = $agentid;
		}
		
		function getXiaomid(){
			return $this->xiaomid;
		}
		
		function setXiaomid($xiaomid){
			$this->xiaomid = $xiaomid;
		}
		
		function getCtype(){
			return $this->type;
		}
		
		function setCtype($type){
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