<?php 
	$url = "http://news.skykiwi.com/na/"; 
	$contents = file_get_contents($url); 
	//如果出现中文乱码使用下面代码 
	$getcontent = iconv("gb2312","utf-8//IGNORE",$contents); 
	//echo $getcontent;
	
	$news_html_in = '';
	$getLinkFlag = false;
	$getLinkCount = 0;
	
	$links = pc_link_extractor($getcontent);
	foreach($links as $link){
	  if($getLinkFlag && $getLinkCount < 10){
	    if(strlen($link[1]) > 32){
	      $newstopic = subUTF8str($link[1],0,36).'...';
	    }else{
	      $newstopic = $link[1];
	    }
	    $news_html_in .= '<li><a href='.$link[0].' target="_blank">'.$newstopic.'</a></li>';
	    //print $link[0]."-".$link[1]."\n";
	    $getLinkCount++;
	  }
	  if($link[0] == 'http://news.skykiwi.com/na' && $link[1] == '纽澳新闻'){
	    $getLinkFlag = true;
	  }
	}
	
	$news_html  = '<div class="right_box">
				     <span class="top_r"></span>
		             <h4>新西兰快讯</h4>
		             <div class="right_box_2">
			           <ul>
					     '.$news_html_in.'
					   </ul>
					 </div>
        			<span class="bo_r"></span>
					</div>';
	
	//echo $news_html;
    file_put_contents("/data/ihaomy/web/www/statichtml/index_nz_news_10.html",$news_html);
	
	function pc_link_extractor($html){
	  $links = array();
	  preg_match_all('/<a\s+.*?href=[\"\']?([^\"\'>]*)[\"\']?[^>]*>(.*?)<\/a>/i',$html,$matches,PREG_SET_ORDER);
	  foreach($matches as $match){
	    $links[] = array($match[1],$match[2]);
	  }
	  return $links;
	}
	
	function subUTF8str($str, $start=0, $length=80){ 
    	$str_length = strlen($str);//传入字符串的字节长度 
    	$end_length = $start + $length; //8 //预计结束字节 
    	$cut_length = 0; //截取的字节长度 
    	$cut_end = $cut_begin = false; //初始化截取状态 
 
    	if($start >= $str_length){ //如果字符串开始位置大于字符串总长度 那么返回空 
        	return null; 
    	} 
    	if( ($start == 0) && ($length >= $str_length) ){ //如果起始位置是0 截取长度大于等于总长度 直接返回 
        	return $str; 
    	} 
    	for($i=0; $i <= $str_length; $i++){ 
       		if( ($start <= $i) && ($cut_begin === false) ){//如果这一个判断在第二个if后面则取下一个汉字为开头 
            	$cut_begin = $i; 
        	} 
        	if (ord($str[$i]) > 127){ //如果当前字符的ASCII 值大于127 则是认为是汉字 
            	$i += 2; //UTF-8汉字是24位三个字节 如果是汉字那么增加两位 
            	//$cut_begin?$cut_length += 3:null; 
            	$cut_length += 3;
        	}else{ 
            	//$cut_begin?$cut_length += 1:null; 
            	$cut_length += 1;
        	} 
        	if( ($end_length <= $i) && ($cut_end === false) ){ 
            	break; 
        	} 
    	} 
    	return substr($str, $cut_begin, $cut_length);
	} 
?> 