<?php
	class cConstants{
		var $constantsid;
		var $constants;
		var $type;
		var $create_time;
		
		function getConstantsid(){
			return $this->constantsid;
		}
		
		function setConstantsid($constantsid){
			$this->constantsid = $constantsid;
		}
		
		function getConstants(){
			return $this->constants;
		}
		
		function setConstants($constants){
			$this->constants = $constants;
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