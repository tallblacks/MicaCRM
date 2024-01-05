<?
    require_once("../include/config.inc.php");
    require_once("../include/mysql.inc.php");
    require_once("../include/function.inc.php");
    require_once("../include/user.inc.php");
  
    $db=new cDatabase;
    $user=new user;

    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";

    $sql="select * from user";
    $query=$db->query($sql);
    while($data=$db->fetch_array($query)){
	$userid=$data['userid'];
        $realname=$data['realname'];
        $username=$data['username'];
        $password=$data['password'];
        $mobile=$data['mobile'];
        $email=$data['email'];
        $type=$data['type'];
        $create_time=$data['create_time'];
        echo $userid."-".$realname."-".$username."-".$password."-".$mobile."-".$email."-".$type."-".$create_time."<br>";
    }
?>
