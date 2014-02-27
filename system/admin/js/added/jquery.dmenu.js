
(function( $ ){

	$.fn.dmenu = function( options ) {  
	
		var settings = 
		{
		
		};
			
			
		return this.each(function() {
		
		
		
			if ( options )  $.extend( settings, options );	
			
			$(this).addClass("dmenu");
			
			//$(this).find("a.act").parents(".dmenu>li").find("a:first").addClass("act");
			
			
			
			$(this).find("li").each(function(){
			
				if( $(this).find("ul").size()>0 && $(this).find("ul").html().trim() != ""  ){
					$(this).find("a:first").append('<div class="arrow-right"></div>');
				}
			
			});
			
			
			//$(this).find("li:first.arrow-right").removeClass("arrow-right").addClass("arrow-down");
				
			
			
/* 			$(this).find("li").hover(
				function(){
					var w = 0;
					var h = 0;
					
					if(  $(this).parent("ul").hasClass("dmenu")   ){
						h = $(this).outerHeight();
					}else {
						w = $(this).outerWidth();
					}
					
					$(this).find("ul:first").css({top:h+"px", left:w+"px"}).stop(true,true).delay(100).fadeIn(400);
				},
				function(){
					
		
					$(this).find("ul:first").fadeOut(200);
				}
				
			); */
		
			$(this).find("li").hover(
				function(){
					var w = 0;
					var h = 0;
					
/* 					if(  $(this).parent("ul").hasClass("dmenu")   ){
						h = $(this).outerHeight();
					}else {
						w = $(this).outerWidth();
					}
					
					 */
					h = $(this).outerHeight(true);
					w = $(this).outerWidth(true);
					
					
/* 					if(  $(this).parent("ul").hasClass("dmenu")   ){
						h = h - $(this).outerHeight(true);
					}else{
						h = h + $(this).outerHeight(true);
					} */
					
					
					//$(this).find("ul:first").css({top:h+"px", left:w+"px"}).stop(true,true).delay(100).fadeIn(400);
					$(this).find("ul:first").css({top:"0px", left:w+"px"}).stop(true,true).delay(100).fadeIn(400);
				},
				function(){
					
		
					$(this).find("ul:first").delay(400).fadeOut(200);
				}
				
			);
		
		
		});
		
		
		}

})( jQuery );