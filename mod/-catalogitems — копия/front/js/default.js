var modCatalogitems = {
	
	/* initialize */
	init:function(){

		$(".mod_catalogitems>ul.list_items").carouFredSel({
			width: '100%',
			height: 'auto',
			prev: '.mod_catalogitems .prev',
			next: '.mod_catalogitems .next',
			auto: true,
			scroll:{
				duration:800,
				items:1
			},
			pagination: ".mod_catalogitems .pager>div",
			mousewheel: false,
			swipe: {
				onMouse: true,
				onTouch: true
			}
		});	

		$(".mod_catalogitems").hover(
			function(){
				$(this).find(".controls").animate({opacity:1}, 150);
			},
			function(){
				$(this).find(".controls").animate({opacity:0}, 150);
			}
		);
	}
}

$(function(){
	if(typeof(plug_ajax)!="undefined"){
		plug_ajax.ready(function(){
			modCatalogitems.init();
		});
	}else{
		modCatalogitems.init();
	}
});