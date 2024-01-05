<?php
    require_once("../include/config.inc.php");
    require_once("../include/mysql.inc.php");
    require_once("../include/user.inc.php");

    $username = $_POST["username"];
    $password = $_POST["password"];
    $autologin = $_POST["autologin"];
  
    if (is_null($username) || $username == "" || is_null($password) || $password == "") {
        $feedback = "用户名或密码不能为空！";
	    Header("Location:/index.php?feedback=$feedback");
	    exit();
    }

    $db = new cDatabase;
    $user = new user;
    if (!$user->authenticate($username,$password)) {
        $feedback=$user->error_message;
	    Header("Location:/index.php?feedback=$feedback");
	    exit();
    } else {
	    session_start();
	    $_SESSION['SESS_USERID']=$user->userid;
	    $_SESSION['SESS_REALNAME']=$user->realname;
	    $_SESSION['SESS_TYPE']=$user->type;
	
	    $userString = md5($user->userid.COOKIESTRING);
	    if ($autologin) {
	        setcookie('userid',$user->userid,time()+864000,'/','crm.nzshop.cn');
            setcookie('userstring',$userString,time()+864000,'/','crm.nzshop.cn');
        } else {
            setcookie('userid',$user->userid,'','/','crm.nzshop.cn');
            setcookie('userstring',$userString,'','/','crm.nzshop.cn');
        }
	
	    Header("Location:../index.php");
	    exit();
    }
?>