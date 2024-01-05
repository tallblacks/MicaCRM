<?php
	include_once ("include/ihaomy/init.php");
	include_once DOC_ROOT.'conf/group.config.php';

	$linenum = 0;
	$i = 0;
	$same_flag = false;
	$email_array = null;
	$file_handle = fopen("member_basic.txt", "r");

	while(!feof($file_handle)){
		$email = trim(fgets($file_handle));
		if($email_array != null){
        	foreach ($email_array as $email_temp) {
				if($email_temp == $email){
					$same_flag = true;
				}
			}
		}
		if(!$same_flag){
        	$email_array[$i] = $email;
        	$i++;
        }
        $same_flag = false;
		$linenum++;
	}
	fclose($file_handle);
	echo "Total number: ".$linenum."\n";
	echo "Array number: ".count($email_array)."\n";
	
	foreach ($email_array as $email_insert) {
		$sql = "insert into import_data_users (email,status,fromid) values('".$email_insert."',0,3)";
  		echo $sql."\n";
  		$mysql = mysql_connect(SW_DB_WRITE_HOSTS,SW_DB_WRITE_USER,SW_DB_WRITE_PASS);
  		if (!mysql_ping($mysql)){
    		echo "mysql_ping; \n";
			mysql_close($mysql);
  		}
  		mysql_select_db("user");
  		mysql_query($sql);
  	}
  	mysql_close($mysql);
?> 