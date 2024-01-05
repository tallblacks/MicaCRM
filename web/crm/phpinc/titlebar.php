<?php
    if (!empty($titlebars)) {
        echo "<table class=line width=100% border=0 cellspacing=0 cellpadding=0>";
        echo "<tr bgcolor=#003366><td height=2 colspan=2><img src=../images/space.gif width=1 height=1></td></tr>";
        echo "<tr><td width=75% class=line>";
        $i = 0;
        while (sizeof($titlebars)>1 && $i<(sizeof($titlebars)-1) ) {
    	      list($key,$value)=each($titlebars);
            if (isset($jumptarget)) {
      	        echo "<a href=$value target=$jumptarget>";
            }else{
      	        echo "<a href=$value target=main>";
			      }
			      echo $key."</a>&gt;";
			      $i++;
        }
        if(sizeof($titlebars) >= 1 ) {
    	      list($key,$value)=each($titlebars);
            echo $key;
        }
        echo "</td><td width=25% align=right class=line>";
        if (!empty($operations)) {
            $i = 0;
            while (sizeof($operations)>0 && $i < sizeof($operations)) {
      	        list($key,$value)=each($operations);
                if (isset($jumptarget)) {
	      	          echo "<a href=$value target=$jumptarget>".$key."</a>&nbsp;";
	              } else {
	      	          echo "<a href=".$value.">".$key."</a>&nbsp;";
				        }
	 			        $i++;
            }
   	    }
   	    echo "</td></tr>";
   	    echo "<tr bgcolor=#003366><td colspan=2 height=1><img src=../images/space.gif width=1 height=1></td>";
   	    echo "</tr></table>";
    }
?>
