$.fn.extend({     
   rollimg:function(options){
	var auto=null;
	var obj=$(this);
	count=$("img",obj).size();
	n=0;
	var settings={timer:5000,menu:"#play_text"};
	options = options || {};
    $.extend(settings, options);
	var ulcontent="";
	for(i=1;i<=count;i++){ulcontent=ulcontent+"<img src='"+SW_URL_IMG+"new/icon5.png' width='8' height='8' id='"+i+"' style='padding:5px 5px 5px 5px;cursor:pointer;'/>";}
	$(settings.menu).html(ulcontent);
    $("img:not(:first-child)",this).hide();
	$(settings.menu+" img").eq(0).attr({"src":SW_URL_IMG+"new/icon6.png"});
	$(settings.menu+" img").mouseover(function() {
		i = $(this).attr("id")-1;
		n=i;
		if (n >= count) return;
		$("img",obj).filter(":visible").fadeOut(200,function(){$(this).parent().children().eq(n).fadeIn(300);});
		$(this).attr({"src":SW_URL_IMG+"new/icon6.png"}).siblings().attr({"src":SW_URL_IMG+"new/icon5.png"});
	});
	auto = setInterval(showAuto, settings.timer);
	obj.hover(function(){clearInterval(auto)}, function(){auto = setInterval(showAuto, settings.timer);});
	function showAuto(){
		 n = n >= (count - 1) ? 0 : ++n;
		$(settings.menu+" img").eq(n).trigger('mouseover');
	}
	function leftAuto(){
		--n;
		if (n <= -1) n=count-1;
		if (n <= 0) n=0;
		$(settings.menu+" img").eq(n).trigger('mouseover');
	}
	$("#l_flash").hover(function(){clearInterval(auto);}, function(){auto = setInterval(showAuto, settings.timer);});
	$("#l_flash").click(function(){leftAuto();});
	$("#r_flash").hover(function(){clearInterval(auto);}, function(){auto = setInterval(showAuto, settings.timer);});
	$("#r_flash").click(function(){showAuto();});
}
});
