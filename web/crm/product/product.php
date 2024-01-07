<?php
    require_once("../include/config.inc.php");
    require_once("../include/mysql.inc.php");
    require_once("../include/function.inc.php");
    require_once("../include/user.inc.php");
    CheckCookies();

	session_start();
	if (!isset($_SESSION["SESS_USERID"])) {
		Header("Location:index.php");
		exit();
	}
	require_once("../include/cproductpeer.inc.php");
	require_once("../include/user.inc.php");
	
	$dosearch = trim(@$_POST["dosearch"]);
    $start = trim(@$_GET["start"]);
    $range = trim(@$_GET["range"]);
    $msg = trim(@$_GET["msg"]);
    $status = 0;

    if (empty($range)) {
        $range = 10;
    }

    if (empty($start)) {
	$start = 0;
    }
  
    if ($dosearch) {
        $search = trim($_POST["search"]);
        $start = trim($_POST["start"]);
    } else {//非第一搜索页
        $dosearch = trim(@$_GET["dosearch"]);
        if ($dosearch) {//搜索＋翻页
            $search = trim($_GET["search"]);
        } else {//非搜索情况
            $search = "";
        }
    }
?>
<html><head>
<title></title>
<link rel=stylesheet type=text/css href="/style/global.css">
</head>
<BODY BGCOLOR=#ffffff LINK=#000099 ALINK=#cc0000 VLINK=#000099 TOMARGIN=8>
<center>
<?php
    $titlebars = array("产品管理"=>"product.php");
    if ($_SESSION['SESS_TYPE'] == ADMIN) {
        $operations = array("产品导出"=>"allexcel.php");
    }
    $jumptarget = "cmsright";

    include("../phpinc/titlebar.php");
  
    echo "<form name=searchproduct method=post action=>
          <input type=hidden name=dosearch value=1>
          <input type=hidden name=start value=0>
          <table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>
          <tr class= itm bgcolor='#dddddd'>
          <td>输入产品名称</td>
          <td><input type=type name=search value=$search><font color=red><b>注意，搜索区分大小写！</b></font></td>
          <td><input type=submit name=submit value=模糊搜索></td>
          </tr>
          </table>
          </form>";

    $db = new cDatabase;
    
    if ($search) {
        $search = str_replace("'", "\'", $search);
        $search = str_replace('"', '\"', $search);
    }
    
    $productManager = new cProductPeer;
	$total = $productManager->getProductCount($status, $search);
	$productCount = 0;
	$productList = $productManager->getProductlist($status, $start, $range, $search);
	$productCount = sizeof($productList);

	if (!empty($msg)) {
  	    echo "<span class=cur>$msg</span>";
    }

    //关于搜索分页的设置
    if ($productCount > 0) {
        $table ="<table cellpadding=1 cellspacing=1 border=0 width=100%>";
        $table .=" <tr>";
        $table .="  <td width=40% align=left nowrap class=line>";
        echo $table;
             
       if ( ($start-$range) >= 0 ) {
           $starts = $start-$range;
           echo "&laquo; <a href=\"product.php?status=$status&range=$range&start=$starts&dosearch=$dosearch&search=$search\">前$range</a>";
       } else {
           echo "&nbsp;";
       }
       echo "</td>";
       
       if ($doSearch) {
           echo "<td width=20% align=center nowrap class=line>共搜索出&nbsp;$total&nbsp;个产品</td>";
       }else{
           echo "<td width=20% align=center nowrap class=line>共有&nbsp;$total&nbsp;款产品</td>";
       }
       
       echo "<td width=40% align=right nowrap class=line>";
       if (($start+$range) < $total ) {
 	       $starts = $start+$range;
           echo "<a href=\"product.php?status=$status&range=$range&start=$starts&dosearch=$dosearch&search=$search\">后$range</a> &raquo;";
       } else {
           echo " &nbsp;";
       }
 
       echo "</td></tr></table>";
    }

	//不同用户看页面权限的设置
    $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
    $table .=" <tr bgcolor=#dddddd class=tine>";
    if ($_SESSION['SESS_TYPE'] == ADMIN) {
        $table .=" <td align=center width='29%'>英文名</td>";
        $table .="  <td align=center width='27%'>中文名</td>";
        $table .="  <td align=center width='3%'>规格</td>";
        $table .="  <td align=center width='3%'>单位</td>";
        $table .="  <td align=center width='5%'>成本NZ</td>";
        $table .="  <td align=center width='5%'>售价CN</td>";
        //$table .="  <td align=center width='3%'>批发计算</td>";
        //$table .="  <td align=center width='3%'>常规批价</td>";
        //$table .="  <td align=center width='4%'>小批价</td>";
        //$table .="  <td align=center width='4%'>大批价</td>";
        //$table .="  <td align=center width='4%'>重量g</td>";
        $table .="  <td align=center width='4%'>利润率</td>";
        $table .="  <td align=center width='4%'>货源</td>";
        $table .="  <td align=center width='3%'>编辑</td>";
        $table .="  <td align=center width='3%'>删除</td>";
    } else {
        $table .=" <td align=center width='43%'>英文名</td>";
        $table .="  <td align=center width='34%'>中文名</td>";
        $table .="  <td align=center width='6%'>规格</td>";
        $table .="  <td align=center width='6%'>单位</td>";
        $table .="  <td align=center width='6%'>售价CN</td>";
        $table .="  <td align=center width='5%'>重量g</td>";
    }
    $table .="  </tr>";

    $bgcolor = "";

    if ($_SESSION['SESS_TYPE'] == ADMIN) {
        require_once("../include/cconstantspeer.inc.php");
        $constantsManager = new cConstantsPeer;
        $exchangerate = $constantsManager->getNewConstants(1);
    }

    if (!empty($productList)) {
        $rowColor=1;
        foreach ($productList as $product) {
  	        $product = (object)$product;
  	        $productid = $product->getProductid();
  	        $ename = $product->getEname();
  	        $cname = $product->getCname();
  	        $spec = $product->getSpec();
  	        $unit = $product->getUnit();
  	        $unitnum = $product->getUnitnum();
  	        $pprizenz = $product->getPpricenz();
  	        $sprizecn = $product->getSpricecn();
  	        $weight = $product->getWeight();
  	        $supermarket = $product->getSupermarket();
              
            $profitrate = round((($sprizecn - $pprizenz*$exchangerate)/$sprizecn)*100, 2);

  	        $user = new user;
  	        $agentname = @$user->getUserbyId($product->getAgentid())->realname;
            $createtime = $product->getCreatetime();
      
            if ( $rowColor%2 == 0 ) {
	            $bgcolor = "#ffffcc";
            } else {
	            $bgcolor = "#eeeeee";
            }

            $table .=" <tr bgcolor=$bgcolor class=line>";
            if ($_SESSION['SESS_TYPE'] == ADMIN) {
                $table .="  <td align=left><a href=/order/productinorder.php?productid=$productid>$ename</a></td>";
       			$table .="  <td align=left><a href=/order/productinorder.php?productid=$productid>$cname</a></td>";
     		} else {
       			$table .="  <td align=left>$ename</td>";
       			$table .="  <td align=left>$cname</td>";
     		}
     		
     		switch ($spec) {
       			case 1:
         			$table .="  <td align=center>盒</td>";
         			break;
	   			case 2:
	     			$table .="  <td align=center>瓶</td>";
         			break;
	   			case 3:
	     			$table .="  <td align=center>罐</td>";
         			break;
	            case 4:
	                $table .="  <td align=center>箱</td>";
                    break;
                case 5:
	     			$table .="  <td align=center>块</td>";
         			break;
       			case 6:
	     			$table .="  <td align=center>只</td>";
         			break;
       			case 7:
	     			$table .="  <td align=center>袋</td>";
                    break;
	            default:
	                $table .="  <td align=center>无</td>";
                    break;
            }
            switch ($unit) {
                case 1:
                    $table .="  <td align=center>克</td>";
                    break;
	            case 2:
	                $table .="  <td align=center>毫升</td>";
                    break;
	            case 3:
	                $table .="  <td align=center>粒</td>";
                    break;
	            case 4:
	                $table .="  <td align=center>天</td>";
                    break;
                case 5:
	                $table .="  <td align=center>块</td>";
                    break;
                case 6:
	                $table .="  <td align=center>只</td>";
                    break;
                case 7:
	                $table .="  <td align=center>袋</td>";
                    break;
                case 8:
	                $table .="  <td align=center>片</td>";
                    break;
	            default:
	                $table .="  <td align=center>无</td>";
                    break;
            }
         	if ($_SESSION['SESS_TYPE'] == ADMIN) {
                $table .="  <td align=center>$pprizenz</td>";
            }
            //管理员和超级小蜜可以查看代理价格
            if ($_SESSION['SESS_TYPE'] == ADMIN || $_SESSION['SESS_TYPE'] == SECRETARY) {
                $table .="  <td align=center onClick=agentPriceOpen(".$productid.")>$sprizecn</td>";
            }else{
                $table .="  <td align=center>$sprizecn</td>";
            }
            //批发计算、常规批价、小批价、大批价，现在都没了，去掉
            /*if ($_SESSION['SESS_TYPE'] == ADMIN) {
                $table .="  <td align=center></td>";
                $table .="  <td align=center></td>";
                $table .="  <td align=center></td>";
                $table .="  <td align=center></td>";
            }*/
            if ($_SESSION['SESS_TYPE'] == ADMIN) {
                $table .="  <td align=center>$profitrate%</td>";
            }else{
                $table .="  <td align=center>$weight</td>";
            }
            if ($_SESSION['SESS_TYPE'] == ADMIN) {
                if ($supermarket == 0) {
                    $table .="  <td align=center>非超市</td>";
        		} else if ($supermarket == 2) {
         			$table .="  <td align=center>HK</td>";
                } else {
                    $table .="  <td align=center>超市货</td>";
                }
                $table .="  <td align=center>";
                $table .="   <a href=\"/product/editproduct.php?productid=$productid&start=$start&range=$range\"><img src=\"../images/edit.gif\" align=\"bottom\" border=0></a></td>";
                $table .="  <td align=center >";
                //$table .="   <a href=\"/product/delproduct.php?productid=$productid&start=$start&range=$range\"><img src=\"../images/del.gif\" align=\"bottom\" border=0></a></td></tr>";
                $table .="   <img src=\"../images/del.gif\" align=\"bottom\" border=0></td></tr>";
            }
            $rowColor++;
   		}
  	}
    $table .="</table></center>";

    $table .="<script type=\"text/javascript\">";
    $table .="function agentPriceOpen(id){";
    $table .="window.open('http://crm.nzshop.cn/product/agentprice.php?id='+id,'_blank','width=500,height=300,menubar=no,toolbar=no,status=no,scrollbars=yes')";
    $table .="}";
    $table .="</script>";

    $table .="</BODY></html>";

    echo $table;
?>
