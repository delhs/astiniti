(function( $ ){

	$.fn.dmenu = function( options ) {  
	
		var settings = 
		{
		
		};
			
			
		return this.each(function() {
		
		
		
			if ( options )  $.extend( settings, options );	
			
			$(this).addClass("dmenu");
			
			$(this).find("a.act").parents(".dmenu>li").find("a:first").addClass("act");
			
			$(this).find("li").parent("ul").prev("a").append('<span class="arrow"></span>');
			
			
			$(this).find("li").hover(
				function(){
					var w = 0;
					var h = 0;
					
					if(  $(this).parent("ul").hasClass("dmenu")   )
						h = $(this).outerHeight();
							else w = $(this).outerWidth();
		
					$(this).find("ul:first").css({top:h+"px", left:w+"px"}).stop(true,true).delay(100).fadeIn(400);
				},
				function(){
					
		
					$(this).find("ul:first").fadeOut(200);
				}
				
			);
		
			
		
		
		});
		
		
		}

})( jQuery );