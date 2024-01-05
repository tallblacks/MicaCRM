<?php
    function make_link($url,$name,$target="",$onclick="")
    {
		    if ($onclick)$onclick=" onclick='$onclick' ";
 		    if ($target)$target=" target='$target' ";
 		    $link="<a href=\"$url\" $target $onclick>$name</a>";
 
		    return $link;
	  } 

    function msg($string,$home="",$homelink="",$onlick=""){
        global $strings;
        $back=make_link("#",$strings['RETURN'],"",$strings['RETURN_CLICK']);
        if (!$home) {
       	    $link=$back;
        } else {
            $link=make_link($homelink,$home,"",$onlick)."&nbsp;&nbsp".$back;
        }

    	  $message = "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>";
        $message .= "           <tr width=\"100%\" {$strings['TABLE_TITLE_BKCOLOR']}>";
        $message .= "           <td height=\"30\" class=hdr>&nbsp;&nbsp;{$strings['MESSAGE_BOX']}</td>";
        $message .="           </tr>";
        $message .=" <tr width=\"100%\">";
        $message .="   <td height=\"100\" bgColor=\"#FFFFFF\" align=\"center\" valign=\"middle\" class=cur><BR>{$string}<BR><BR>{$link}<BR></td>";
        $message .="  </tr>";              
        $message .=" </table>";
        echo $message;
    }

    /*
    function multi_play($height,$width,$url,$multi)
    {
   	    $url=WEB_URL.$url;
   	    $multi=str_replace(MULTIMEDIA_HEIGHT,$height,$multi);
        $multi=str_replace(MULTIMEDIA_WIDTH,$width,$multi);
        $multi=str_replace(MULTIMEDIA_URL,$url,$multi);
   	    return $multi;
    }

    function replace_all($string)
    {
        $string=trim($string);
		    $string=trim(str_replace("\n","",$string));
        $string=trim(str_replace("\r","",$string));
		    $string=trim(nl2br(str_replace("\"","",$string)));
		    return $string;
    }

function found_type($string){
 global $strings;
 $string_array=array();
 $str_arr=split(":",$string);
 $str_name=$str_arr[0];
 $str_value=$str_arr[1];
 $types=$str_name.",".$str_value;
 if ($str_name=="pic"){$string_array[]=$types;
 }else{
   foreach ($strings['UPLOAD_TYPE_MULTI'] as $key => $value){
    if ($key==$str_name){$string_array[]=$types;}
   }
 }
 return $string_array;
}

function get_type($content){
 global $strings;
 $contents=htmlspecialchars($content);
 $maker=$strings['TYPE_MAKER'];
 //echo "maker - $maker <br>";
 $str_arr=split($maker,$contents);
 $articles_type=array();
 foreach($str_arr as $k => $v){
  $articles_type[]=found_type($v);
 }
 foreach($articles_type as $k => $v){
 	//echo "$k - $v <br>";
  foreach($v as $key => $value){
  	//echo "12$k - $v <br>";
    if ($value)$types[]=$value;
  }
 }

// print_array($types);

 return $types;
}

function put_type($types){
 global $strings;
 foreach($types as $k => $v){
  $str_arr=split(",",$v);
  $type_name=$str_arr[0];
  $type_value=$str_arr[1];
  foreach($strings['UPLOAD_TYPE_MULTI'] as $key => $value){
   if ($type_name==$key){
    $type["TYPE"]=$type_name;
    $type["VALUE"]=$type_value;
    return $type;
   }
  }
 }
 foreach($types as $k => $v){
  $str_arr=split(",",$v);
  $type_name=$str_arr[0];
  $type_value=$str_arr[1];
  if ($type_name=="pic"){
   $type["TYPE"]=$type_name;
   $type["VALUE"]=$type_value;
   return $type;
  }
 }
}

function print_array($string){
 echo "<PRE>";
 print_r($string);
 echo "</PRE>";
}
*/
    /////////////////////
    //Levin Add 20140730
    function CheckCookies()
    {
        if (isset($_COOKIE["userid"]) && isset($_COOKIE["userstring"])) {//有需要的cookies
          	$userString = md5($_COOKIE["userid"].COOKIESTRING);
          	if ($userString == $_COOKIE["userstring"]) {//cookies正确
              	session_start();
	          	if (!isset($_SESSION["SESS_USERID"])) {//没有sesssion，生成session
                  	$db = new cDatabase;
                  	$user = new user;
                  	$sql = "select realname,type from user where userid=".$_COOKIE["userid"];
	              	$query = $db->query($sql);
	      	      	$query_num = $db->num_rows($query);
    	          	if ($query_num == 0) return false;
          
                  	if ($row = $db->fetch_object($query)) {
                      	$_SESSION['SESS_REALNAME'] = $row->realname;
                      	$_SESSION['SESS_TYPE'] = $row->type;
		          	}
	              	$_SESSION['SESS_USERID'] = $_COOKIE["userid"];
	          	}
	          	return true;
           	}
        }
        return false;
    }
?>