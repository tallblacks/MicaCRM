<?php
/*

”√ªß±Ì ˝æ›Ω·ππ

 CREATE TABLE CMS_USER
(
	USER_ID          INT UNSIGNED			 NOT NULL,
  USERNAME			   VARCHAR(30)			 	NOT NULL,
  PASSWORD			   VARCHAR(30)	      NOT NULL,
  NICKNAME			   VARCHAR(30),
  TYPE             SMALLINT UNSIGNED NOT NULL DEFAULT 3,
  EMAIL            VARCHAR(50)       NOT NULL,
  TELEPHONE        VARCHAR(30),
  MOBILE			  	 VARCHAR(30),
  CREATE_TIME			 DATETIME          NOT NULL DEFAULT 'sysdate',
  PRIMARY KEY (USER_ID)
)TYPE=MyISAM;
COMMENT = "CMSœµÕ≥”√ªß±Ì£¨”√ªß»œ÷§;type 1◊‹±‡£¨2÷˜±‡£¨3±‡º≠";

”√ªß»®œﬁ±Ì
CREATE TABLE CMS_PERMISSION
(
	USER_ID         INT UNSIGNED			NOT NULL,
	COLUMN_ID				INT UNSIGNED			NOT NULL,
	PRIMARY KEY (USER_ID,COLUMN_ID)
)TYPE=MyISAM;
COMMENT = "CMS”√ªß»®œﬁ±Ì£¨COLUMN_IDŒ™œ‡”¶∆µµ¿µƒID";

”√ªß∂Ø◊˜±Ì
CREATE TABLE CMS_AUDIT
(
	USER_ID         INT UNSIGNED			NOT NULL AUTO_INCREMENT,
	ACTION					VARCHAR(255)			NOT NULL,
	IP							VARCHAR(100),
	LOG_TIME				DATETIME          NOT NULL DEFAULT 'sysdate',
)TYPE=MyISAM;
COMMENT = "CMS”√ªß––Œ™º«¬º±Ì";

CREATE INDEX IDX_AUDIT_USERID ON CMS_AUDIT
(
   USER_ID
);
*/

/***********************************************/
/*                                             */
/*  ¿‡√˚:class user()                          */
/*  ◊˜”√:”√ªßµƒ∏˜÷÷≤Ÿ◊˜                        */
/*   π”√∑Ω∑®:œÍº˚∏˜∫Ø ˝Àµ√˜                    */
/*                                             */
/***********************************************/

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
	
    function user(){
    	global $strings, $db;
        $this->IS_LOGGED_IN = false;
        $this->min_password_length = MIN_PASSWORD_LENGTH;
        $this->max_password_length = MAX_PASSWORD_LENGTH;
        $this->min_username_length = MIN_USERNAME_LENGTH;
        $this->max_username_length = MAX_USERNAME_LENGTH;
    }

/***************************************/
/*                                     */
/*  函数: authenticate()   	           */
/*  ◊˜”√:—È÷§”√ªßµ«¬Ω                  */
/*   π”√∑Ω∑®:authenticate(”√ªß√˚,√‹¬Î) */
/*                                     */
/***************************************/   
    function authenticate($username, $password){
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
            //$this->column = $this->get_column();
            return true;
        } else {
            return false;
        }
    }

/****************************************/
/*                                      */
/*  权限表: get_column()  目前未用        */
/*  ◊˜”√:»°µ√¿∏ƒø ˝◊È                   */
/*   π”√∑Ω∑®:get_column() */
/*  ∑µªÿ£∫¿∏ƒøID ˝◊È                    */
/*                                      */
/****************************************/


    function get_column()
    {
    	global $strings, $db;
    	
      $user_id=$this->user_id;

    	if ($this->type == ADMIN){
    		$sql="SELECT COLUMN_ID,CNAME,ENAME FROM CMS_COLUMN";
      }else{
        $sql="SELECT CMS_COLUMN.COLUMN_ID,CMS_COLUMN.CNAME,CMS_COLUMN.ENAME FROM CMS_COLUMN,CMS_PERMISSION WHERE CMS_PERMISSION.USER_ID=$user_id AND CMS_PERMISSION.COLUMN_ID=CMS_COLUMN.COLUMN_ID";
        
      }
      
      $query = $db->query($sql);
      $column_id=array();
      $ccolumn=array();
      $ecolumn=array();
      while($data = $db->fetch_array($query)){
      	$column_id[]=$data['COLUMN_ID'];
      	$ccolumn[]=$data['CNAME'];
      	$ecolumn[]=$data['ENAME'];
      }
      $column=array("COLUMN_ID"=>$column_id,"CNAME"=>$ccolumn,"ENAME"=>$ecolumn);
      return $column;
    }



/****************************************/
/*                                      */
/*  ∫Ø ˝√˚: get_column_id()             */
/*  ◊˜”√:»°µ√¿∏ƒø ˝◊È                   */
/*   π”√∑Ω∑®:get_column_id() */
/*  ∑µªÿ£∫¿∏ƒøID ˝◊È                    */
/*                                      */
/****************************************/
/*

    function get_column_id()
    {
    	global $strings, $db;
    	
      $user_id=$this->user_id;

    	if ($this->type == ADMIN){
    		$sql="SELECT COLUMN_ID FROM CMS_COLUMN";
      }else{
        $sql="SELECT COLUMN_ID FROM CMS_PERMISSION WHERE USER_ID=$user_id";
      }
      
      $query = $db->query($sql);
      $column_id=array();
      while($data = $db->fetch_array($query)){
      	$column_id[]=$data['COLUMN_ID'];
      
      }

      return $column_id;
    }

*/

/************************************************************/
/*                                                          */
/*  ∫Ø ˝√˚: change_password()                               */
/*  ◊˜”√:–ﬁ∏ƒ”√ªß√‹¬Î                                       */
/*   π”√∑Ω∑®:change_password(”√ªß√˚,æ…√‹¬Î,–¬√‹¬Î,÷ÿ∏¥√‹¬Î) */
/*                                                          */
/************************************************************/

    function change_password($user_id, $old_password, $password1, $password2)
    {
        global $strings, $db;

        if ($this->verify_password($username, $old_password)) {

            if ($password1 != $password2) {
                $this->error($strings['ERROR_PASSWORDS']);
                return false;
            }

            if ((strlen($password1) < $this->min_password_length) || (strlen($password1) > $this->max_password_length)) {
                $this->error($strings['ERROR_PASSWORD_TOO_SHORT']);
                return false;
            }

            $password=MD5($password1);

            $sql = "UPDATE CMS_USER SET PASSWORD = '$password' WHERE USERNAME = $username";
            $db->query($sql);
            return true;
        } else {
            // user put in wrong password
            $this->error($strings['ERROR_PASSWORD_INCORRECT']);
            return false;
        }

    }

/*******************************/
/*                             */
/*  函数: error()               */
/*  ◊˜”√:¥ÌŒÛ–≈œ¢              */
/*                             */
/*******************************/

    function error($message){
        $this->error_message = $message;
        return true;
    }

/*******************************/
/*                             */
/*  ∫Ø ˝√˚: logout()           */
/*  ◊˜”√:◊¢œ˙ªÚÕÀ≥ˆœµÕ≥        */
/*   π”√∑Ω∑®:logout()          */
/*                             */
/*******************************/

    function logout()
    {
            $this->user_id = '';
            $this->username = '';
            $this->real_name = '';
            $this->type = '';
            $this->email = '';
            $this->telephone = '';
            $this->mobile = '';
            $this->create_time = '';
            $this->IS_LOGGED_IN = false;
            $this->column_id = '';
        return true;
    }

/*******************************/
/*                             */
/*  ∫Ø ˝√˚: mail_password()    */
/*  ◊˜”√:∞—√‹¬Î∑¢ÀÕ∏√”√ªß      */
/*   π”√∑Ω∑®:add(”√ªß√˚,√‹¬Î)  */
/*  ∑µªÿ÷µ:true,false          */
/*                             */
/*******************************/

    // mails lost password to user
    function mail_password($username, $email)
    {
        global $use_mysql_encryption;
        global $db;
        $result = $db->query("SELECT email FROM users WHERE username = '$username' AND email = '$email'");

        if (!$db->num_rows($result)) {
            $this->error("The username or email address is incorrect.");
            return false;
        } else {
            $password = $this->make_password();
            list($email) = $db->fetch_array($result);
            $subject = "Your lost password.";
            $message = "Your password that you requested had to be reset.  Your new password is:\n" .
                       "$password";
            $header = "From: Password reset <noreply@site.com>\r\n";

            if (!mail($email, $subject, $message, $header)) {
                $this->error("Failed to send email.");
                return false;
            } else {
                $password=MD5($password);
                $query = "UPDATE users SET password = '$password' WHERE username = '$username' LIMIT 1";
                $db->query($query);
            }

        }

    }


/**********************************/
/*                                */
/*  ∫Ø ˝√˚:make_password()        */
/*  ◊˜”√:÷∆◊˜ÀÊª˙√‹¬Î             */
/*   π”√∑Ω∑®:make_password(Œª ˝)  */
/*  ∑µªÿ÷µ:√‹¬Î                   */
/*                                */
/**********************************/

    // makes a random password
    function make_password($length = 7)
    {
        // thanks to benjones@superutility.net for this code
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


/******************************************************************************/
/*                                                                            */
/*  ∫Ø ˝√˚:add()                                                              */
/*  ◊˜”√:‘ˆº”–¬”√ªß                                                           */
/*   π”√∑Ω∑®:add(”√ªß√˚,√‹¬Î,÷ÿ∏¥√‹¬Î,Í«≥∆,µÁ◊”” º˛,µÁª∞, ÷ª˙,»®œﬁ)  */
/*  ∑µªÿ÷µ:true,false                                                         */
/*                                                                            */
/******************************************************************************/

    function add($username, $password, $password1, $realname, $email, $mobile, $type)
    {
        global $strings, $use_mysql_encryption;
        global $db;

        //用户名非必填项
        if($username != null || $username != ""){
        	if ((strlen($username) < $this->min_username_length) || (strlen($username) > $this->max_username_length)) {
            	$this->error($strings['ERROR_USERNAME_TOO_SHORT']);
            	return false;
        	}
        }
        
        //密码非必填项
        if(($password != null || $password != "") && ($password1 != null || $password1 != "")){
        	if ($password != $password1) {
            	$this->error($strings['ERROR_PASSWORDS']);
            	return false;
        	}
        
        	if ((strlen($password1) < $this->min_password_length) || (strlen($password1) > $this->max_password_length)) {
            	$this->error($strings['ERROR_PASSWORD_TOO_SHORT']);
            	return false;
        	}
        }

        //ºÏ≤È”√ªß «∑Ò“—æ≠¥Ê‘⁄ø™ º
        $sql="select * from user where realname = '$realname'";
        $result = $db->query($sql);

        if ($db->num_rows($result)) {
            $this->error($strings['ERROR_USERNAME_TAKEN']);
            return false;
        }
        //ºÏ≤È”√ªß «∑Ò“—æ≠¥Ê‘⁄Ω· ¯

        //Ω´”√ªß◊ ¡œ¥Ê»Î”√ªß±Ì
        //$password=MD5($password);
        $sql= "insert into user (realname, username, password, mobile, email, type, create_time) VALUES ('$realname', '$username', '$password', '$mobile', '$email', '$type', now())";
        $db->query($sql);
        $user_id=$db->insert_id();
       
        return $user_id;
    }
    


/******************************************************************************/
/*                                                                            */
/*  ∫Ø ˝√˚:add_permission()                                                   */
/*  ◊˜”√:‘ˆº”–¬”√ªß                                                           */
/*   π”√∑Ω∑®:add(”√ªßID,”√ªß»®œﬁ,»®œﬁ’Û¡–)  */
/*  ∑µªÿ÷µ:true,false                                                         */
/*                                                                            */
/******************************************************************************/

    function add_permission($user_id,$type,$permission)
    {
        global $strings, $use_mysql_encryption;
        global $db;

        //»Áπ˚≤ª «÷˜±‡(π‹¿Ì‘±)≤Â»Î»®œﬁ±Ìø™ º
        if ($type !== ADMIN){
        	foreach($permission as $key => $value){
			    $sql="SELECT * FROM CMS_PERMISSION WHERE USER_ID=$user_id AND COLUMN_ID=$value";
                $result = $db->query($sql);
                if (!$db->num_rows($result)) {
        		 $sql= "INSERT INTO CMS_PERMISSION (USER_ID,COLUMN_ID) VALUE ($user_id,$value)";
        		 $db->query($sql);
				}
            }
        }
        //»Áπ˚≤ª «÷˜±‡(π‹¿Ì‘±)≤Â»Î»®œﬁ±ÌΩ· ¯
        
        return true;
    }
        
/*****************************************************************/
/*                                                               */
/*  函数:update()                                                 */
/*  ◊˜”√:∏¸–¬”√ªßª˘±æ–≈œ¢                                        */
/*   π”√∑Ω∑®:update(”√ªßID,”√ªß√˚,Í«≥∆,µÁ◊”” º˛,µÁª∞, ÷ª˙,»®œﬁ)  */
/*  ∑µªÿ÷µ:true,false                                            */
/*                                                               */
/*****************************************************************/

    function update($userid, $username, $password, $password1, $realname, $email, $mobile, $type)
    {
        global $strings;
        global $db;
        
        //用户名非必填项
        if($username != null || $username != ""){
        	if ((strlen($username) < $this->min_username_length) || (strlen($username) > $this->max_username_length)) {
            	$this->error($strings['ERROR_USERNAME_TOO_SHORT']);
            	return false;
        	}
        }
        
        //密码非必填项
        if(($password != null || $password != "") && ($password1 != null || $password1 != "")){
        	if ($password != $password1) {
            	$this->error($strings['ERROR_PASSWORDS']);
            	return false;
        	}
        
        	if ((strlen($password1) < $this->min_password_length) || (strlen($password1) > $this->max_password_length)) {
            	$this->error($strings['ERROR_PASSWORD_TOO_SHORT']);
            	return false;
        	}
        }
        
        /*if($username == "admin"){
          $this->error("不能使用admin！");
          return false;
        }*/
        
        if($username != "" || $username != null){
          $result = $db->query("SELECT userid FROM user WHERE username = '$username'");
          /*if ($db->num_rows($result)) {
              $this->error("不能使用已有用户名！");
              return false;
          }*/
          if($row = $db->fetch_object($result)){
    		$selectUserid = $row->userid;
    		
    		if($selectUserid != $userid){
    		  //echo $selectUserid."-".$userid;
    	      $this->error("不能使用已有用户名！");
              return false;
    	    }
    	  }
        }
         
        $sql="update user set username = '$username', realname = '$realname', password = '$password', email = '$email', mobile = '$mobile', type = '$type' where userid = $userid";
        $query = $db->query($sql);
        return true;
    }

/***********************************************/
/*                                             */
/*  函数:verify_password()                      */
/*  ◊˜”√:ºÏ≤È”√ªß ‰»Îµƒ√‹¬Î «∑Ò’˝»∑            */
/*   π”√∑Ω∑®:verify_password(”√ªß√˚,√‹¬Î)      */
/*  ∑µªÿ÷µ:true,false                          */
/*                                             */
/***********************************************/

    function verify_password($username, $password){
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
    
/***********************************************/
/*                                             */
/*  函数:getUserbyRealname()                   */
/***********************************************/

    function getUserbyId($userid){
        global $db;

        $sql="select userid,realname,username,password,mobile,email,type,create_time from user where userid = $userid";
        $query = $db->query($sql);

    	$query_num = $db->num_rows($query);
    	if($query_num == 0){
    		return false;
    	}
    	
    	if($row = $db->fetch_object($query)){
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
    
    function getUserbyRealname($realname){
        global $db;

        $sql="select userid,realname,username,password,mobile,email,type,create_time from user where realname = '$realname'";
        $query = $db->query($sql);
    	$query_num = $db->num_rows($query);
    	if($query_num == 0){
    		return false;
    	}
    	
    	if($row = $db->fetch_object($query)){
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
    
    function getSecretaryUser(){
        global $db;

        $sql="select userid,realname,username,password,mobile,email,type,create_time from user where type = 5";
        $query = $db->query($sql);
    	$query_num = $db->num_rows($query);
    	if($query_num == 0){
    		return false;
    	}
    	
    	if($row = $db->fetch_object($query)){
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
	
/*	
		USER_ID         INT UNSIGNED			NOT NULL AUTO_INCREMENT,
	ACTION					VARCHAR(255)			NOT NULL,
	IP							VARCHAR(100),
	LOG_TIME				DATETIME          NOT NULL DEFAULT 'sysdate',
*/
}
?>
