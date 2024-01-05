<?php

class user{
    var $userid;
	var $realname;
 	var $username;
 	var $password;
 	var $mobile;
 	var $email;
 	var $type;
 	var $create_time;
 	var $column_id;
 	
	var $min_password_length;
	var $max_password_length;
	var $min_username_length;
	var $max_username_length;
	
    function user()
    {
    	global $strings, $db;
        $this->IS_LOGGED_IN = false;
        $this->min_password_length = MIN_PASSWORD_LENGTH;
        $this->max_password_length = MAX_PASSWORD_LENGTH;
        $this->min_username_length = MIN_USERNAME_LENGTH;
        $this->max_username_length = MAX_USERNAME_LENGTH;
    }

    function authenticate($username, $password)
    {
        global $strings, $db;

        if ($this->verify_password($username, $password)) {
        	$sql="select * from user where username = '$username'";
            $row = $db->fetch_object($db->query($sql));
            $this->userid = $row->userid;
            $this->realname = $row->realname;
            $this->mobile = $row->mobile;
            $this->email = $row->email;
            $this->type = $row->type;
            $this->create_time = $row->create_time;
            $this->IS_LOGGED_IN = true;
            return true;
        } else {
            return false;
        }
    }

    function error($message)
    {
        $this->error_message = $message;
        return true;
    }

    // makes a random password
    function make_password($length = 7)
    {
        mt_srand((double) microtime() * 1000000);

        for ($i=0; $i < $length; $i++) {
            $which = rand(1, 3);
            // character will be a digit 2-9
            if ( $which == 1 ) $password .= mt_rand(0,10);
            // character will be a lowercase letter
            elseif ( $which == 2 ) $password .= chr(mt_rand(65, 90));
            // character will be an uppercase letter
            elseif ( $which == 3 ) $password .= chr(mt_rand(97, 122));
        }

        return $password;
    }

    function add($username, $password, $password1, $realname, $email, $mobile, $type)
    {
        global $strings, $use_mysql_encryption;
        global $db;

        //用户名非必填项
        if ($username != null || $username != "") {
        	if ((strlen($username) < $this->min_username_length) || (strlen($username) > $this->max_username_length)) {
            	$this->error($strings['ERROR_USERNAME_TOO_SHORT']);
            	return false;
        	}
        }
        
        //密码非必填项
        if (($password != null || $password != "") && ($password1 != null || $password1 != "")) {
        	if ($password != $password1) {
            	$this->error($strings['ERROR_PASSWORDS']);
            	return false;
        	}
        
        	if ((strlen($password1) < $this->min_password_length) || (strlen($password1) > $this->max_password_length)) {
            	$this->error($strings['ERROR_PASSWORD_TOO_SHORT']);
            	return false;
        	}
        }

        $sql="select * from user where realname = '$realname'";
        $result = $db->query($sql);

        if ($db->num_rows($result)) {
            $this->error($strings['ERROR_USERNAME_TAKEN']);
            return false;
        }

        //$password=MD5($password);
        $sql= "insert into user (realname, username, password, mobile, email, type, create_time) VALUES ('$realname', '$username', '$password', '$mobile', '$email', '$type', now())";
        $db->query($sql);
        $user_id=$db->insert_id();
       
        return $user_id;
    }

    function update($userid, $username, $password, $password1, $realname, $email, $mobile, $type)
    {
        global $strings;
        global $db;
        
        //用户名非必填项
        if ($username != null || $username != "") {
        	if ((strlen($username) < $this->min_username_length) || (strlen($username) > $this->max_username_length)) {
            	$this->error($strings['ERROR_USERNAME_TOO_SHORT']);
            	return false;
        	}
        }
        
        //密码非必填项
        if (($password != null || $password != "") && ($password1 != null || $password1 != "")) {
        	if ($password != $password1) {
            	$this->error($strings['ERROR_PASSWORDS']);
            	return false;
        	}
        
        	if ((strlen($password1) < $this->min_password_length) || (strlen($password1) > $this->max_password_length)) {
            	$this->error($strings['ERROR_PASSWORD_TOO_SHORT']);
            	return false;
        	}
        }
        
        if ($username != "" || $username != null) {
            $result = $db->query("SELECT userid FROM user WHERE username = '$username'");
            
            if ($row = $db->fetch_object($result)) {
    		    $selectUserid = $row->userid;
    		    if ($selectUserid != $userid) {
    	            $this->error("不能使用已有用户名！");
                    return false;
    	        }
    	    }
        }
         
        $sql = "update user set username = '$username', realname = '$realname', password = '$password', email = '$email', mobile = '$mobile', type = '$type' where userid = $userid";
        $query = $db->query($sql);
        return true;
    }

    function verify_password($username, $password)
    {
        global $db, $strings, $use_mysql_encryption;

        $sql="select password from user where username = '$username'";
        $query = $db->query($sql);

        if (!$db->num_rows($query)) {
            $this->error($strings['ERROR_PASSWORD_INCORRECT']);
            return false;
        }

        $sql = "select * from user where username = '$username' and password = '$password'";
        $result = $db->query($sql);

        if ($db->num_rows($result)) {
            return true;
        } else {
            $this->error($strings['ERROR_PASSWORD_INCORRECT']);
            return false;
        }
    }

    function getUserbyId($userid)
    {
        global $db;

        $sql = "select userid,realname,username,password,mobile,email,type,create_time from user where userid = $userid";
        $query = $db->query($sql);

    	$query_num = $db->num_rows($query);
    	if ($query_num == 0) {
    		return false;
    	}
    	
    	if ($row = $db->fetch_object($query)) {
    		$this->userid = $row->userid;
            $this->realname = $row->realname;
            $this->username = $row->username;
            $this->password = $row->password;
            $this->mobile = $row->mobile;
            $this->email = $row->email;
            $this->type = $row->type;
            $this->create_time = $row->create_time;
    		return $this;
    	}
    }
    
    function getUserbyRealname($realname)
    {
        global $db;

        $sql = "select userid,realname,username,password,mobile,email,type,create_time from user where realname = '$realname'";
        $query = $db->query($sql);
    	$query_num = $db->num_rows($query);
    	if ($query_num == 0) {
    		return false;
    	}
    	
    	if ($row = $db->fetch_object($query)) {
    		$this->userid = $row->userid;
            $this->realname = $row->realname;
            $this->username = $row->username;
            $this->password = $row->password;
            $this->mobile = $row->mobile;
            $this->email = $row->email;
            $this->type = $row->type;
            $this->create_time = $row->create_time;
    		return $this;
    	}
    }
    
    function getSecretaryUser()
    {
        global $db;

        $sql = "select userid,realname,username,password,mobile,email,type,create_time from user where type = 5";
        $query = $db->query($sql);
    	$query_num = $db->num_rows($query);
    	if ($query_num == 0) {
    		return false;
    	}
    	
    	if ($row = $db->fetch_object($query)) {
    		$this->userid = $row->userid;
            $this->realname = $row->realname;
            $this->username = $row->username;
            $this->password = $row->password;
            $this->mobile = $row->mobile;
            $this->email = $row->email;
            $this->type = $row->type;
            $this->create_time = $row->create_time;
    		return $this;
    	}
    }
    
}
