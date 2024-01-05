<?php
    require_once("ccrmpicture.inc.php");
	
    class cCrmPicturePeer
    {
        function getCrmPictureByPictureid($pictureid)
        {
            $sql = "select pictureid,orderid,suborderid,productid,userid,name,type,status,picture_url,spicture_url,create_time from crmpicture where pictureid=$pictureid";
            global $db;
    	
            $result=$db->query($sql);
            $query_num = $db->num_rows($result);
            if ($query_num == 0) return false;
            if ($row = $db->fetch_object($result)) return $this->Load($row);
        }
        
        function getCrmPictureListByOrderid($orderid)
        {
            $sql = "select pictureid,orderid,suborderid,productid,userid,name,type,status,picture_url,spicture_url,create_time from crmpicture where orderid=$orderid and status=0";
            global $db;
    	
            $result=$db->query($sql);
            $query_num = $db->num_rows($result);
            if ($query_num == 0) return false;
            $rslist = array();
            while ($row = $db->fetch_object($result)) {
          	    $rslist[] = $this->load($row);
            }
            return $rslist;
        }
        
        function create($crmpicture)
        {
            $sql = "insert into crmpicture(orderid,suborderid,productid,userid,name,type,status,picture_url,spicture_url,create_time) values (".$crmpicture->getOrderid().",".$crmpicture->getSuborderid().",".$crmpicture->getProductid().",".$crmpicture->getUserid().",'".$crmpicture->getName()."',".$crmpicture->getType().",".$crmpicture->getStatus().",'".$crmpicture->getPictureUrl()."','".$crmpicture->getSpictureUrl()."',now())";

            global $db;
            $db->query($sql);
            if (mysqli_affected_rows($db->link_id) > 0 ) {
                return true;
            } else {
		        return false;
            }
        }
        
        function delCrmPicture($pictureid)
        {
            $sql = "update crmpicture set status=4 where pictureid=$pictureid";
            global $db;
            $db->query($sql);
            if (mysqli_affected_rows($db->link_id) > 0 ) {
		        return true;
            } else {
		        return false;
            }
        }

        function Load($row)
        {
            $crmpicture = new cCrmPicture;
            $crmpicture->setPictureid($row->pictureid);
            $crmpicture->setOrderid($row->orderid);
            $crmpicture->setProductid($row->productid);
            $crmpicture->setUserid($row->userid);
            $crmpicture->setName($row->name);
            $crmpicture->setType($row->type);
            $crmpicture->setStatus($row->status);
            $crmpicture->setPictureUrl($row->picture_url);
            $crmpicture->setSpictureUrl($row->spicture_url);
            $crmpicture->setCreatetime($row->create_time);

            return $crmpicture;
    	}
    }
?>