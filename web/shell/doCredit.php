<?php
include_once ("include/ihaomy/init.php");
include_once DOC_ROOT.'conf/group.config.php';

/////////
//Add money
$monthDay_count = 31;//month 10
$insert_time = time();
$sql = "insert into user_trade_log (orderId,userId,tradeDesc,tradeTargetId,tradeType,tradeMoney,balanceMoney,tradeTime) values(19418,10015584,'运费退款 - 日式烤鳗鱼，直供香格里拉、万豪、希尔顿',350,1,30,30,$insert_time);";
echo $sql."\n";
$mysql = mysql_connect(SW_DB_WRITE_HOSTS,SW_DB_WRITE_USER,SW_DB_WRITE_PASS);
if (!mysql_ping($mysql)){
	echo "mysql_ping; \n";
	mysql_close($mysql);
}
mysql_select_db("user");
mysql_query("set names UTF8");  
mysql_query($sql);
mysql_close($mysql);
?>
