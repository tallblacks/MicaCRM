<?php
include_once ("include/ihaomy/init.php");
include_once DOC_ROOT.'conf/group.config.php';

/////////
//ShangHai
$monthDay_count = 31;//month 1
$cityId = 101;//Shanghai
for($dayCount = 1; $dayCount <= $monthDay_count; $dayCount++){
  if($dayCount < 10){
    $dayCount_str = "2015-01-0".$dayCount;
  }else{
    $dayCount_str = "2015-01-".$dayCount;
  }
  //首页
  $sql = "insert into html_schedule (cityId,showDate,groupIds,type) values(".$cityId.",'".$dayCount_str."','246',1)";
  echo $sql."\n";
  $mysql = mysql_connect(SW_DB_WRITE_HOSTS,SW_DB_WRITE_USER,SW_DB_WRITE_PASS);
  if (!mysql_ping($mysql)){
    echo "mysql_ping; \n";
    mysql_close($mysql);
  }
  mysql_select_db("product");
  mysql_query($sql);
  mysql_close($mysql);
  
  //全部
  $sql = "insert into html_schedule (cityId,showDate,groupIds,type) values(".$cityId.",'".$dayCount_str."','246',2)";
  echo $sql."\n";
  $mysql = mysql_connect(SW_DB_WRITE_HOSTS,SW_DB_WRITE_USER,SW_DB_WRITE_PASS);
  if (!mysql_ping($mysql)){
    echo "mysql_ping; \n";
    mysql_close($mysql);
  }
  mysql_select_db("product");
  mysql_query($sql);
  mysql_close($mysql);
}

/////////
//Beijing
$monthDay_count = 31;//month 1
$cityId = 100;//Beijing
for($dayCount = 1; $dayCount <= $monthDay_count; $dayCount++){
  if($dayCount < 10){
    $dayCount_str = "2015-01-0".$dayCount;
  }else{
    $dayCount_str = "2015-01-".$dayCount;
  }
  //首页
  $sql = "insert into html_schedule (cityId,showDate,groupIds,type) values(".$cityId.",'".$dayCount_str."','571,489,395',1)";
  echo $sql."\n";
  $mysql = mysql_connect(SW_DB_WRITE_HOSTS,SW_DB_WRITE_USER,SW_DB_WRITE_PASS);
  if (!mysql_ping($mysql)){
    echo "mysql_ping; \n";
    mysql_close($mysql);
  }
  mysql_select_db("product");
  mysql_query($sql);
  mysql_close($mysql);
  
  //全部
  //$sql = "insert into html_schedule (cityId,showDate,groupIds,type) values(".$cityId.",'".$dayCount_str."','497,518,496,517,519,516,382,473,245,488,491,492,391,512,478,495,509,489,466,368,285,366,345,369,371,337,392,394,467,468,465,408,393,338,461,513,490,456,374,363,383',2)";
  $sql = "insert into html_schedule (cityId,showDate,groupIds,type) values(".$cityId.",'".$dayCount_str."','573,572,571,570,489,395,508,554,553,556,560,558,557,559,382,516,509,473,465,491,245',2)";
  echo $sql."\n";
  $mysql = mysql_connect(SW_DB_WRITE_HOSTS,SW_DB_WRITE_USER,SW_DB_WRITE_PASS);
  if (!mysql_ping($mysql)){
    echo "mysql_ping; \n";
    mysql_close($mysql);
  }
  mysql_select_db("product");
  mysql_query($sql);
  mysql_close($mysql);
  
  //普洱
  /*$sql = "insert into html_schedule (cityId,showDate,groupIds,type) values(".$cityId.",'".$dayCount_str."','285',3)";
  echo $sql."\n";
  $mysql = mysql_connect(SW_DB_WRITE_HOSTS,SW_DB_WRITE_USER,SW_DB_WRITE_PASS);
  if (!mysql_ping($mysql)){
    echo "mysql_ping; \n";
    mysql_close($mysql);
  }
  mysql_select_db("product");
  mysql_query($sql);
  mysql_close($mysql);
  
  //吃喝
  $sql = "insert into html_schedule (cityId,showDate,groupIds,type) values(".$cityId.",'".$dayCount_str."','513,490,461,456,408,393,337,338,345,363,383,369,374,371',5)";
  echo $sql."\n";
  $mysql = mysql_connect(SW_DB_WRITE_HOSTS,SW_DB_WRITE_USER,SW_DB_WRITE_PASS);
  if (!mysql_ping($mysql)){
    echo "mysql_ping; \n";
    mysql_close($mysql);
  }
  mysql_select_db("product");
  mysql_query($sql);
  mysql_close($mysql);
  
  //美容美发
  $sql = "insert into html_schedule (cityId,showDate,groupIds,type) values(".$cityId.",'".$dayCount_str."','497,518,496,519,512,478,473,368,392,394',7)";
  echo $sql."\n";
  $mysql = mysql_connect(SW_DB_WRITE_HOSTS,SW_DB_WRITE_USER,SW_DB_WRITE_PASS);
  if (!mysql_ping($mysql)){
    echo "mysql_ping; \n";
    mysql_close($mysql);
  }
  mysql_select_db("product");
  mysql_query($sql);
  mysql_close($mysql);
  
  //消费
  $sql = "insert into html_schedule (cityId,showDate,groupIds,type) values(".$cityId.",'".$dayCount_str."','366',8)";
  echo $sql."\n";
  $mysql = mysql_connect(SW_DB_WRITE_HOSTS,SW_DB_WRITE_USER,SW_DB_WRITE_PASS);
  if (!mysql_ping($mysql)){
    echo "mysql_ping; \n";
    mysql_close($mysql);
  }
  mysql_select_db("product");
  mysql_query($sql);
  mysql_close($mysql);*/
  
  //阅读
  $sql = "insert into html_schedule (cityId,showDate,groupIds,type) values(".$cityId.",'".$dayCount_str."','573,245',11)";
  echo $sql."\n";
  $mysql = mysql_connect(SW_DB_WRITE_HOSTS,SW_DB_WRITE_USER,SW_DB_WRITE_PASS);
  if (!mysql_ping($mysql)){
    echo "mysql_ping; \n";
    mysql_close($mysql);
  }
  mysql_select_db("product");
  mysql_query($sql);
  mysql_close($mysql);
  
  //母婴
  /*$sql = "insert into html_schedule (cityId,showDate,groupIds,type) values(".$cityId.",'".$dayCount_str."','517,466,382',14)";
  echo $sql."\n";
  $mysql = mysql_connect(SW_DB_WRITE_HOSTS,SW_DB_WRITE_USER,SW_DB_WRITE_PASS);
  if (!mysql_ping($mysql)){
    echo "mysql_ping; \n";
    mysql_close($mysql);
  }
  mysql_select_db("product");
  mysql_query($sql);
  mysql_close($mysql);
  
  //绝对日本
  $sql = "insert into html_schedule (cityId,showDate,groupIds,type) values(".$cityId.",'".$dayCount_str."','497,518,496,519,512,478,368,392,394',20)";
  echo $sql."\n";
  $mysql = mysql_connect(SW_DB_WRITE_HOSTS,SW_DB_WRITE_USER,SW_DB_WRITE_PASS);
  if (!mysql_ping($mysql)){
    echo "mysql_ping; \n";
    mysql_close($mysql);
  }
  mysql_select_db("product");
  mysql_query($sql);
  mysql_close($mysql);*/
  
  //新西兰
  //$sql = "insert into html_schedule (cityId,showDate,groupIds,type) values(".$cityId.",'".$dayCount_str."','517,516,509,495,489,488,473,382,467,468,465,466,391,491,492',22)";
  $sql = "insert into html_schedule (cityId,showDate,groupIds,type) values(".$cityId.",'".$dayCount_str."','573,572,571,570,489,395,508,554,553,556,560,558,557,559,382,516,509,473,465,491',22)";
  echo $sql."\n";
  $mysql = mysql_connect(SW_DB_WRITE_HOSTS,SW_DB_WRITE_USER,SW_DB_WRITE_PASS);
  if (!mysql_ping($mysql)){
    echo "mysql_ping; \n";
    mysql_close($mysql);
  }
  mysql_select_db("product");
  mysql_query($sql);
  mysql_close($mysql);
  
  //海鲜
  /*$sql = "insert into html_schedule (cityId,showDate,groupIds,type) values(".$cityId.",'".$dayCount_str."','513,490,461,456,408,393,337,338,345,363,383,369,374,371',23)";
  $sql = "insert into html_schedule (cityId,showDate,groupIds,type) values(".$cityId.",'".$dayCount_str."','513,490,461,456,408,393,337,338,345,363,383,369,374,371',23)";
  echo $sql."\n";
  $mysql = mysql_connect(SW_DB_WRITE_HOSTS,SW_DB_WRITE_USER,SW_DB_WRITE_PASS);
  if (!mysql_ping($mysql)){
    echo "mysql_ping; \n";
    mysql_close($mysql);
  }
  mysql_select_db("product");
  mysql_query($sql);
  mysql_close($mysql);*/
}
?>
