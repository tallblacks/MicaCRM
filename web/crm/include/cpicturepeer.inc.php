<?php
    require_once("cpicture.inc.php");
	
    class cPicturePeer
    {
        function getPictureByPictureid($pictureid)
        {
            $sql = "select pictureid,orderid,suborderid,productid,userid,operatorid,name,type,status,picture_url,spicture_url,create_time from picture where pictureid=$pictureid";
            global $db;
    	
            $result=$db->query($sql);
            $query_num = $db->num_rows($result);
            if ($query_num == 0) return false;
            if ($row = $db->fetch_object($result)) return $this->Load($row);
        }
        
        function getPictureListByUserid($userid)
        {
            $sql = "select pictureid,orderid,suborderid,productid,userid,operatorid,name,type,status,picture_url,spicture_url,create_time from picture where userid=$userid and status=0";
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
        
        function create($picture)
        {
            $sql = "insert into picture(orderid,suborderid,productid,userid,operatorid,name,type,status,picture_url,spicture_url,create_time) values (".$picture->getOrderid().",".$picture->getSuborderid().",".$picture->getProductid().",".$picture->getUserid().",".$picture->getOperatorid().",'".$picture->getName()."',".$picture->getType().",".$picture->getStatus().",'".$picture->getPictureUrl()."','".$picture->getSpictureUrl()."',now())";

            global $db;
            $db->query($sql);
            if (mysqli_affected_rows($db->link_id) > 0 ) {
                return true;
            } else {
		        return false;
            }
        }
        
        function delPicture($pictureid)
        {
            $sql = "update picture set status=4 where pictureid=$pictureid";
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
            $picture = new cPicture;
            $picture->setPictureid($row->pictureid);
            $picture->setOrderid($row->orderid);
            $picture->setProductid($row->productid);
            $picture->setUserid($row->userid);
            $picture->setOperatorid($row->operatorid);
            $picture->setName($row->name);
            $picture->setType($row->type);
            $picture->setStatus($row->status);
            $picture->setPictureUrl($row->picture_url);
            $picture->setSpictureUrl($row->spicture_url);
            $picture->setCreatetime($row->create_time);

            return $picture;
    	}
    }
?>