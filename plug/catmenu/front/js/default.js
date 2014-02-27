var plugCatmenu = {

	init:function(){
		$(".left_list_menu>ul>li").hover(function(){
			var $menu = $(this).children("ul");
			
			if( $menu.html()=="" ) return;
			
			$menu.stop(true, true).css({
				marginTop:"-45px",
				opacity:0,
				display:"block"
			}).animate({
				marginTop:"-35px",
				opacity:1
			}, 1000, "easeOutExpo");
		
		},
		function(){
			$(this).children("ul:visible").stop(true, true).animate({
				marginTop:"-45px",
				opacity:0
			}, 350, "easeOutExpo", function(){
				$(this).css({display:"none"});
			});
		});	
	}
}








$(function(){
	if(typeof(plug_ajax)!="undefined"){
		plug_ajax.ready(function(){
			plugCatmenu.init();
		});
	}else{
		plugCatmenu.init();
	}
});