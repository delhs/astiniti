(function( $ ){

	$.fn.dhloupe = function( options ) {  

		var settings = {
			zoom:150,		
			radius:200,
			css:'',
			die:false,
			callback:undefined,
			timeUpdate:10
		};

		return this.each(function() {
			
			if ( typeof options == "object" ) $.extend( settings, options );

			if( settings.die ){
				var intId = $(this).data("intId");
				var loupeId = $(this).data("loupe_id");
				clearInterval( intId );
				$(this).removeClass("-dhloupedone");
				$("#"+loupeId).remove();
				return;
			}

			if( $(this).hasClass("-dhloupedone") ) return;

			var $img = $(this),
				curX = 0,
				curY = 0,
				id = "loupe_" + Math.floor(Math.random()*10000),
				coef = settings.zoom / 100;
			
			settings.css = ( settings.css != "" )? 'class="'+settings.css+'"' : '';
			
			$("body").mousemove(function(e){
				curX = e.pageX;  
				curY = e.pageY; 
			}).prepend('<div id="'+id+'" '+settings.css+' ></div>');

			var $loupe = $("#"+id),	
				background = "url("+ $img.attr("src") +")";
			

			$img.data("loupe_id", id);

			if( settings.css =="" ){
				$loupe.css({
					width: settings.radius+"px",
					height: settings.radius+"px",
					borderRadius:(settings.radius/2)+"px",
					border:"2px solid gray",
					cursor:"none"
				});
				background = "#DADADA url("+ $img.attr("src") +")"
			}

			$loupe.css({
				position:"absolute",
				background:background,
				backgroundSize: ($img.width()*coef)+"px "+($img.height()*coef)+"px",
				backgroundRepeat:"no-repeat",
				zIndex:300,
				display:"none"
			});			
			
			var imStatic = ($img.parents().css("position") =="static")? true: false;
			
			$img.hover(function(){

				var intId = setInterval( function(){
					var inImageX = curX-$img.offset().left,
						inImageY = curY-$img.offset().top,
						BackPosX  =-( (inImageX * coef ) - ( $loupe.width()/2 ) ),
						BackPosY  =-( (inImageY * coef ) - ( $loupe.height()/2 ) ),
						loupeOffsetX = ( curX -  ( $loupe.width() / 2 ) ),
						loupeOffsetY = ( curY - ( $loupe.height() / 2 ) );

					if( curX < $img.offset().left ||  curY<$img.offset().top || curX>($img.offset().left+$img.outerWidth()) || curY>($img.offset().top+$img.outerHeight()) ){
						$loupe.fadeOut(100, function(){
								clearInterval(intId);
							if( typeof(settings.callback)=="function") settings.callback();
						});
			
					}else{
						$loupe.css({
							left: Math.ceil(loupeOffsetX) + "px",
							top:  Math.ceil(loupeOffsetY)+ "px",
							backgroundPosition:  Math.ceil(BackPosX)+"px " + Math.ceil(BackPosY) +"px"
						});
						if($loupe.is(":hidden")) $loupe.fadeIn(100);
					} 

				}, settings.timeUpdate );

				$loupe.data("intId", intId);
				$img.data("intId", intId);
			});
			
		});
	};
})( jQuery );