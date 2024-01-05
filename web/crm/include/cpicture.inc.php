<?php
    class cPicture
    {
        var $pictureid;
	    var $orderid;
        var $suborderid;
        var $productid;
        var $userid;
        var $operatorid;
        var $name;
	    var $type;
        var $status;
        var $picture_url;
        var $spicture_url;
	    var $create_time;
		
        function getPictureid()
        {
            return $this->pictureid;
	    }
        
        function setPictureid($pictureid)
        {
            $this->pictureid = $pictureid;
	    }
		
        function getOrderid()
        {
            return $this->orderid;
	    }
		
        function setOrderid($orderid)
        {
            $this->orderid = $orderid;
	    }
        
        function getSuborderid()
        {
            return $this->suborderid;
	    }
		
        function setSuborderid($suborderid)
        {
            $this->suborderid = $suborderid;
	    }
        
        function getProductid()
        {
            return $this->productid;
	    }
		
        function setProductid($productid)
        {
            $this->productid = $productid;
	    }
        
        function getUserid()
        {
            return $this->userid;
	    }
		
        function setUserid($userid)
        {
            $this->userid = $userid;
        }
        
        function getOperatorid()
        {
            return $this->operatorid;
	    }
		
        function setOperatorid($operatorid)
        {
            $this->operatorid = $operatorid;
        }
        
        function getName()
        {
            return $this->name;
	    }
		
        function setName($name)
        {
            $this->name = $name;
	    }
        
        function getType()
        {
            return $this->type;
	    }
		
        function setType($type)
        {
            $this->type = $type;
	    }
        
        function getStatus()
        {
            return $this->status;
	    }
		
        function setStatus($status)
        {
            $this->status = $status;
	    }
        
        function getPictureUrl()
        {
            return $this->picture_url;
	    }
		
        function setPictureUrl($picture_url)
        {
            $this->picture_url = $picture_url;
	    }
        
        function getSpictureUrl()
        {
            return $this->spicture_url;
	    }
		
        function setSpictureUrl($spicture_url)
        {
            $this->spicture_url = $spicture_url;
	    }
		
        function getCreatetime()
        {
            return $this->create_time;
	    }
		
        function setCreatetime($createtime)
        {
            $this->create_time = $createtime;
	    }
    }
?>