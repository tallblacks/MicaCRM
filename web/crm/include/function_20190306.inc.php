<?php
/**************************************************/
/*  函数make_link()                                */
/*   π”√∑Ω∑®£∫make_link(URLµÿ÷∑£¨¡¥Ω”Œƒ◊÷)        */
/*  制作页面HTML可点击的连接                         */
/**************************************************/
	function make_link($url,$name,$target="",$onclick=""){
		if ($onclick)$onclick=" onclick='$onclick' ";
 		if ($target)$target=" target='$target' ";
 		$link="<a href=\"$url\" $target $onclick>$name</a>";
 
		return $link;
	}

/***************************************************/
/*                                                 */
/*  ∫Ø ˝√˚: get_column()                           */
/*  ◊˜”√:»°µ√¿∏ƒø ˝◊È                              */
/*   π”√∑Ω∑®:get_column(”√ªßID,”√ªß»®œﬁ)           */
/*  ∑µªÿ£∫¿∏ƒø ˝◊È(¿∏ƒøID£¨¿∏ƒø÷–Œƒ√˚£¨¿∏ƒø”¢Œƒ√˚  */
/*                                                 */
/***************************************************/


    function get_column($user_id=0,$type=0)
    {
    	global $strings, $db;
    	
    	if ($type == ADMIN){
    		$sql="SELECT COLUMN_ID,CNAME,ENAME FROM CMS_COLUMN WHERE STATUS = 0";
      }else{
        $sql="SELECT CMS_PERMISSION.COLUMN_ID,CNAME,ENAME FROM CMS_COLUMN,CMS_PERMISSION WHERE CMS_PERMISSION.USER_ID=$user_id AND CMS_PERMISSION.COLUMN_ID=CMS_COLUMN.COLUMN_ID";
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

/***************************************************/
/*                                                 */
/*  ∫Ø ˝√˚: add_type()                           */
/*  ◊˜”√:‘ˆº””√ªß»®œﬁ                              */
/*   π”√∑Ω∑®:add_type(”√ªßID,”√ªß»®œﬁ)           */
/*  ∑µªÿ£∫Œﬁ  */
/*                                                 */
/***************************************************/

    function add_type($user_id,$column_id){
    	global $strings, $db;
    	$sql="SELECT * FROM CMS_PERMISSION WHERE USER_ID=$user_id AND COLUMN_ID=$column_id LIMIT 1";
    	$query=$db->query($sql);
    	$num=$db->num_rows($query);
    	if (!$num){
    		$sql="INSERT INTO CMS_PERMISSION (USER_ID,COLUMN_ID) VALUES ($user_id,$column_id)";
    		$query=$db->query($sql);
    		return true;
    	}
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

    function get_column_id($user_id,$type)
    {
    	global $strings, $db;
    	
    	if ($type == ADMIN){
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


    
/****************************************/
/*                                      */
/*  ∫Ø ˝√˚: msg()                       */
/*  ◊˜”√:»°µ√¿∏ƒø ˝◊È                   */
/*   π”√∑Ω∑®:msg(–≈œ¢,∑µªÿ“≥√ÊŒƒ◊÷,∑µªÿ“≥√Ê¡¨Ω”) */
/*  ∑µªÿ£∫¥¯∏Ò Ωµƒ–≈œ¢Ã· æ              */
/*                                      */
/****************************************/    

    function msg($string,$home="",$homelink="",$onlick=""){
    	 global $strings;
        $back=make_link("#",$strings['RETURN'],"",$strings['RETURN_CLICK']);
       if (!$home){
       	$link=$back;
       }else{
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


/****************************************/
/*                                      */
/*  ∫Ø ˝√˚: multi_play()                       */
/*  ◊˜”√:≤•∑≈∫Ø ˝                   */
/*   π”√∑Ω∑®:multi_play(∏ﬂ,øÌ,¡¥Ω”,≤•∑≈√ΩÃÂ¥˙¬Î) */
/*  ∑µªÿ£∫≤•∑≈¥˙¬Î              */
/*                                      */
/****************************************/   
   function multi_play($height,$width,$url,$multi){
   	 $url=WEB_URL.$url;
   	 $multi=str_replace(MULTIMEDIA_HEIGHT,$height,$multi);
     $multi=str_replace(MULTIMEDIA_WIDTH,$width,$multi);
     $multi=str_replace(MULTIMEDIA_URL,$url,$multi);
   	 return $multi;
   }


  function replace_all($string){
      $string=trim($string);
			$string=trim(str_replace("\n","",$string));
      $string=trim(str_replace("\r","",$string));
			$string=trim(nl2br(str_replace("\"","",$string)));
		return $string;
  }

/****************************************/
/*                                      */
/*  ∫Ø ˝√˚: found_type()                       */
/*  ◊˜”√:∞—◊÷∑˚¥Æ÷–µƒ√ΩÃÂ°¢Õº∆¨¿‡–Õ»°≥ˆ¿¥                   */
/*   π”√∑Ω∑®:found_type(◊÷∑˚¥¥) */
/*  ∑µªÿ£∫ ˝◊È              */
/*                                      */
/****************************************/

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

/****************************************/
/*                                      */
/*  ∫Ø ˝√˚: get_type()                       */
/*  ◊˜”√:∞—◊÷∑˚¥Æ÷–µƒ√ΩÃÂ°¢Õº∆¨¿‡–Õ»°≥ˆ¿¥                   */
/*   π”√∑Ω∑®:get_type(◊÷∑˚¥¥) */
/*  ∑µªÿ£∫ ˝◊È              */
/*                                      */
/****************************************/
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
/****************************************/
/*                                      */
/*  ∫Ø ˝√˚: put_type()                       */
/*  ◊˜”√:∞—◊÷∑˚¥Æ÷–µƒ√ΩÃÂ°¢Õº∆¨¿‡–Õ»°≥ˆ¿¥£¨*/
/*    »Áπ˚”–√ΩÃÂ¿‡–Õ∑µªÿµ⁄“ª∏ˆ√ΩÃÂ¿‡–Õ∫Õ¿‡–Õ*/
/*    √ΩÃÂID∫≈£¨»Áπ˚√ª”–√ΩÃÂ¿‡–Õ∑µªÿµ⁄“ª∏ˆÕº*/
/*    ∆¨¿‡–Õ∫ÕÕº∆¨ID∫≈                   */
/*   π”√∑Ω∑®:multi_play(◊÷∑˚¥¥) */
/*  ∑µªÿ£∫ ˝◊È              */
/*                                      */
/****************************************/

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

	function GetMultiMediaType($mulmediaid){
  	global $db;
  	$sql = "SELECT TYPE FROM CMS_MULTIMEDIA WHERE MULTIMEDIA_ID = $mulmediaid";
  	$result=$db->query($sql);
  	$query_num = $db->num_rows($result);
  	if($query_num == 0){
    	return 1;
  	}
  	$row = $db->fetch_array($result);
    $mediatype = $row['TYPE'];
    if($mediatype == 1){
    	return 4;
    }else if($mediatype == 3 || $mediatype == 4 || $mediatype == 8 || $mediatype == 16){
      return 2;
  	}else{
  		return 1;
  	}
  }
  
  function GetColumnStr($columnid){
  	global $db;
  	$sql = "SELECT CNAME FROM CMS_COLUMN WHERE COLUMN_ID = $columnid";

  	$result=$db->query($sql);
  	$query_num = $db->num_rows($result);
  	if($query_num == 0){
    	return "";
  	}
  	$row = $db->fetch_array($result);
    return $row['CNAME'];
  }
  
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