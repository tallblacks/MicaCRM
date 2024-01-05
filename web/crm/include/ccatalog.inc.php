<?php
  class cCatalog{
    var $catalogid;
    var $name;
		
	function getCatalogid(){
      return $this->catalogid;
	}
        
	function setCatalogid($catalogid){
      $this->catalogid = $catalogid;
	}
		
	function getName(){
      return $this->name;
	}
		
	function setName($name){
      $this->name = $name;
	}
	
  }
?>