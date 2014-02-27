/**
* JQuery splash v.2.0
* Example 1: 	$.splash('<p>html</p>');
* Example 2: 	$.splash('<p>html</p>', {closeBtn:false, closeOutClick:true, closeToEscape:true});
* Example 4:	$.splash('<p>html</p>', function(){ console.log('callback') });
* Example 5: 	$.splash($(".mysplash"));
* Example 6: 	$.splash($(".mysplash"), {closeBtn:false, closeOutClick:true, closeToEscape:true});
* Example 3: 	$.splash($(".mysplash"), function(){ console.log('callback') });
* Example 7: 	$(".mysplash").splash();
* Example 8: 	$(".mysplash").splash({closeBtn:false, closeOutClick:true, closeToEscape:true});
* Example 9: 	$(".mysplash").splash(function(){ console.log('callback') });
* Example 10:	$(".mysplash").splash({closeBtn:false}, function(){ console.log('callback') });
*
*/

(function( $ ){

	$.fn.splash = function( options, openCallback ) {  
		return this.each(function(){
			if( !options ) options = {};
			options.html = $(this);
			$.splash( options, openCallback );
		});
	}
	
	$.splash = function( options, openCallback ) {
  
	var settings  = {
		html:"",					//html for splash window
		closeBtn:true,				//view close button
		closeOutClick:false,		//close the window by clicking outside of it
		closeToEscape:false,		//close the window by push escape key	
		fullscreen:false,			//fullscreen mode
		durationOpen:300,			//the speed of opening windows
		durationClose:300,			//closing speed window
		opacity: 0.8,				//background opacity
		openCallback: undefined,	//function, which is performed at the opening
		closeCallback: undefined,	//function, which is performed at the time of closing		
		cssClass: undefined			//css class for splash window		
	}
   
	if ( options )  $.extend( settings, options );
	
	if( openCallback && typeof( openCallback )=="function" ) settings.openCallback = openCallback;
	
	if ( options && typeof( options )=="object" && options.selector===undefined){
		$.each( options, function(i, v){
			settings[i] = v;
		});
		
		$.extend( settings, options );
		
	}else if ( options && typeof( options )=="string" ){
		if( openCallback && typeof( openCallback )=="object" ){ 
			$.each( openCallback, function(i, v){
				settings[i] = v;
			});
		}
		settings.html = options; 

	}else if( options && typeof( options )=="object" && options.selector!=undefined ){
		if( openCallback && typeof( openCallback )=="object" ){
			$.each( openCallback, function(i, v){
				settings[i] = v;
			});
		}
		settings.html = options;
	}else{
		if( options && typeof( options )=="function" ) settings.openCallback = options;
	}
	
	
	
	if( settings.html=="" ) return false;
		
		var rnd = Math.floor( Math.random() * 9999 ),
		backIdPrefix = "back_",
		splashIdPrefix = "splash_",
		backId = backIdPrefix + rnd,
		splashId  = splashIdPrefix  + rnd,
		backsplash = $(document.createElement('div')).attr("data-splash-id", backId).attr("class","splash_back");
		
		if( typeof(settings.html)=="object"){
			var splash = settings.html;
			splash.after('<splash_phantom phantom-id="phantom_'+splashId+'"></splash_phantom>');
			splash.addClass("splash_splash").attr("data-splash-id", splashId );
			splash.off("click");
		}else{
			var splash = $("<div>", {"data-splash-id": splashId, "class": "splash_splash"});
		}
		
		if( settings.cssClass != undefined ) splash.addClass( settings.cssClass );
		//create and show background if is not exist
		$("body").append(  backsplash );
		backsplash.css({
			zIndex: 888
		}).fadeTo( 100, settings.opacity );

		//create splash
		backsplash.after( splash );	
		
		
		//if close out splash then close splash
		if( settings.closeOutClick ){
			backsplash.click(function(e){
				e.preventDefault();
 				$("[data-splash-id='"+splashId+"']").fadeOut(settings.durationClose, function(){
					$("[data-splash-id='"+backId+"']").fadeOut(100, function(){
						$(this).remove();
					});
					if( typeof(settings.html)!="object" ){ 
						$(this).remove();
					}else{
						$(this).removeClass("splash_splash").removeAttr("data-splash-id").removeAttr("style");
						$(this).find("a.splash_close_btn").remove();
						$("splash_phantom[phantom-id='phantom_"+splashId+"']").after( splash );
						$("splash_phantom[phantom-id='phantom_"+splashId+"']").remove();
					}
					if( settings.closeCallback!=undefined && typeof( settings.closeCallback=="function" ) ){
						settings.closeCallback();
					}
				});
			});
		};
		
		//set css for splash
		if( typeof(settings.html)=="object"){
			if( settings.fullscreen ){
				splash.addClass("fullscreen").css({
					position: "fixed",
					width: "auto",
					height: "auto",
					left: "0",
					right: "0",
					top: "0",
					bottom: "0",
					zIndex: 888
				}).fadeTo(0,0);
			}else{
				splash.css({
					position: "absolute",
					left: "0",
					top: "0",
					zIndex: 888
				}).fadeTo(0,0);
			}

		}else{
			if( settings.fullscreen ){
				splash.addClass("fullscreen").html( settings.html ).css({
					position: "fixed",
					width: "auto",
					height: "auto",
					left: "0",
					right: "0",
					top: "0",
					bottom: "0",
					zIndex: 888
				}).fadeTo(0,0);
			}else{
				splash.html( settings.html ).css({
					position: "absolute",
					left: "50%",
					top: "50%",
					zIndex: 888
				}).fadeTo(0,5);
			}
		}

		//if close button is enabled
		if( settings.closeBtn ){
			var closeBtn = $(document.createElement('a')).attr('href', '#').attr('class', 'splash_close_btn');
			//create close button
			splash.append( closeBtn );
			
			//set close event for close button
			closeBtn.click(function(e){
				e.preventDefault();
				$("[data-splash-id='"+splashId+"']").fadeOut(settings.durationClose, function(){
					if( typeof(settings.html)!="object" ){ 
						$(this).remove();
					}else{
						$(this).removeClass("splash_splash").removeAttr("data-splash-id").removeAttr("style");
						$(this).find("a.splash_close_btn").remove();
						$("splash_phantom[phantom-id='phantom_"+splashId+"']").after( splash );
						$("splash_phantom[phantom-id='phantom_"+splashId+"']").remove();
					}
					$("[data-splash-id='"+backId+"']").fadeOut(0, function(){
						$(this).remove();
					});
					if( settings.closeCallback!=undefined && typeof( settings.closeCallback=="function" ) ){
						settings.closeCallback();
					}
				});
			}).fadeIn(0);
		}
		
		//set splash css
		if( !settings.fullscreen ){
			var w = splash.outerWidth(true);
			var h = splash.outerHeight(true);
			
			splash.css({
				marginLeft: - (Math.floor( w/2 )) + "px",
				marginTop: - (Math.floor( h/2 )) + "px",
				left:"50%",
				top:"50%"
			});
			
			if( splash.offset().top<0 ){
				splash.css({marginTop: -( $(window).height()/2 ) + 10 + "px"});
			}
			
		}

	
		splash.animate({opacity:1}, settings.durationOpen, function(){

			if( settings.openCallback!=undefined && typeof( settings.openCallback=="function" ) ){
				settings.openCallback();
			}		
		});
		
		
		
		
		//if close to escape key
		if( settings.closeToEscape ){
			$( document ).keyup(function(e){
				if(e.keyCode == 27) $.splashClose();
			}); 
		}
		
		$.extend({ splashClose : function(){
			$(".splash_splash:last").fadeOut(settings.durationClose, function(){
				var id = $(this).attr("data-splash-id").replace( splashIdPrefix, backIdPrefix);
				$("[data-splash-id='"+id+"']").fadeOut(100, function(){
					$(this).remove();
				});
				if( typeof(settings.html)!="object" ){ 
					$(this).remove();
				}else{
					$(this).removeClass("splash_splash").removeAttr("data-splash-id").removeAttr("style");
				}
				if( settings.closeCallback!=undefined && typeof( settings.closeCallback=="function" ) ){
					settings.closeCallback();
				}
			});
		} });
		
	};
  
})( jQuery );