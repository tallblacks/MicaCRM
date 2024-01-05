<?php
  exec('cd /data/ihaomy/web/crm/spic');

  for($day=1;$day<=31;$day++){
    if($day < 10){
      $d='0'.$day;
    }else{
      $d=$day;
    }
    mkdir("/data/ihaomy/web/crm/spic/$d", 0777);
    exec('cd $d');
    for($month=1;$month<=12;$month++){
      if($month < 10){
        $m='0'.$month;
      }else{
        $m=$month;
      }
      mkdir("/data/ihaomy/web/crm/spic/$d/$m", 0777);
      exec('cd $m');
      for($year=16;$year<=99;$year++){
        mkdir("/data/ihaomy/web/crm/spic/$d/$m/$year", 0777);
      }
    }
  }
  
  /*for($a=0;$a<=99;$a++){
    if($a < 10) $a = '0'.$a;
    mkdir("/data/lemon5tv/web/www/upload/$a", 0777);
    exec('cd $a');
    for($b=0;$b<=99;$b++){
      if($b < 10) $b = '0'.$b;
      mkdir("/data/lemon5tv/web/www/upload/$a/$b", 0777);
      exec('cd $b');
      for($c=0;$c<=99;$c++){
        if($c < 10) $c = '0'.$c;
        mkdir("/data/lemon5tv/web/www/upload/$a/$b/$c", 0777);
        exec('cd $c');
        for($d=0;$d<=99;$d++){
          if($d < 10) $d = '0'.$d;
          mkdir("/data/lemon5tv/web/www/upload/$a/$b/$c/$d", 0777);
        }
      }
    }
  }*/
?>