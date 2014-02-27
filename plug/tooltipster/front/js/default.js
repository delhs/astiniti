var plugTooltipster = {
	
	/* initialize */
	init:function(){
		
		$("[title]").each(function(){
			if($(this).attr("title")=="") return;
			$(this).tooltipster({
				//functionInit:function(){},
				//functionBefore:function(){},
				//functionReady:function(){},
				//functionAfter:function(){},
				animation: 'fade',//fade, grow, swing, slide, fall
				arrow:true,
				arrowColor:'#FFF6D8',//hex code / rgb
				autoClose:true,
				content:null, //string, jQuery object
				contentAsHTML:false,
				contentCloning:true,
				fixedWidth:0, //integer
				maxWidth:0, //integer
				icon:'(?)', //string, jQuery object
				iconTouch:false,
				interactive:false,
				interactiveTolerance:350, //integer
				offsetX:0,//integer
				offsetY:0,//integer
				onlyOne:false,
				position:'bottom-left', //right, left, top, top-right, top-left, bottom, bottom-right, bottom-left
				positionTracker:false,
				speed:350, //integer
				timer:0, //integer
				touchDevices:true,
				updateAnimation:true,
				iconCloning:true,
				iconDesktop:false,
				delay: 600,
				trigger: 'hover' //hover, click, custom			
			});
		});
	}
}

$(function(){
	if(typeof(plug_ajax)!="undefined"){
		plug_ajax.ready(function(){
			plugTooltipster.init();
		});
	}else{
		plugTooltipster.init();
	}
});