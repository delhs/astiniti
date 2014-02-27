var plugAdaptivemenu = {
	
	/* initialize */
	init:function(){

		$("#mmenu-btn").click(function(e){
			e.preventDefault();
			$("ul.dmenu").css({
					top: '-10px',
					opacity: 0,
					display:'block'
			})
			.animate({
				top: 0,
				opacity: 1
			}, 680, "easeOutExpo");


			$("#mmenu-btn-back").css({
				bottom:'10px',
				opacity: 0,
				display:'block'
			})
			.animate({
				bottom:0,
				opacity:1
			}, 680, "easeOutExpo");

		});


		$("#mmenu-btn-back").click(function(e){
			e.preventDefault();
			$("ul.dmenu")
				.animate({
					top: '-10px',
					opacity: 0
				}, 240, "easeOutExpo", function(){
					$(this).css({display: 'none'});
			});

			$(this)
			.animate({
				bottom:'10px',
				opacity:0
			}, 240, "easeOutExpo", function(){
					$(this).css({display: 'none'});

			});			
		});

	}
}

$(function(){
	if(typeof(plug_ajax)!="undefined"){
		plug_ajax.ready(function(){
			plugAdaptivemenu.init();
		});
	}else{
		plugAdaptivemenu.init();
	}
});