<?php
	//$filename = fopen("1", "r");
	include_once ("include/ihaomy/init.php");
	include_once DOC_ROOT.'conf/group.config.php';
	
	$file_handle = fopen("55000数据_ihaomy.txt", "r");
	$result = input_csv($file_handle);
	$linenum = count($result);
	$same_count = 0;
	$same_flag = false;
	echo "行数：".$linenum."\n";
	if($linenum==0){ 
        echo '没有任何数据！'; 
        exit; 
    } 
    for ($i = 0; $i < $linenum; $i++) { //循环获取各字段值 
        //$email = iconv('gb2312', 'utf-8', $result[$i][0]); //中文转码
        $email = trim($result[$i][0]);
        $email = str_replace("^@^M^@","",$email);
        $email = str_replace("^@","",$email);
        //比较
        if($email_array != null){
        	foreach ($email_array as $email_temp) {
				if($email_temp == $email){
					$same_count++;
					$same_flag = true;
				}
			}
		}
		if(!$same_flag){
        	$email_array[$i] = $email;
        }
        $same_flag = false;
    }
    echo count($email_array)."\n";
	echo "相同的个数：".$same_count."\n";
	fclose($file_handle);
	
	//insert into DB
	foreach ($email_array as $email_insert) {
		$sql = "insert into import_data_users (email,status,fromid) values('".$email_insert."',0,1)";
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
	
	function input_csv($handle) { 
    	$out = array (); 
    	$n = 0; 
   		while ($data = fgetcsv($handle, 10000)) { 
        	$num = count($data); 
        	for ($i = 0; $i < $num; $i++) { 
            	$out[$n][$i] = $data[$i]; 
        	} 
        	$n++; 
    	} 
    	return $out; 
    }
?> 