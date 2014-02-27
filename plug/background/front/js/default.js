var plugBackground = {
	
	/* initialize */
	init:function(){
	
	}
}

$(function(){
	if(typeof(plug_ajax)!="undefined"){
		plug_ajax.ready(function(){
			plugBackground.init();
		});
	}else{
		plugBackground.init();
	}
});