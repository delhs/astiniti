var plugBackground = {
		
	init:function(){
		$("#cap").on("click", "#plug_background_menu a[data-action]", function(e){
			e.preventDefault();
			plugBackground.viewTemplate($(this).attr("data-action"));
			var dataAction = $(this).attr("data-action");
		});
	},
	viewTemplate:function( templateName, dataArray, callback ){
		admin.block();
		
		if( typeof dataArray =="function" ){
			callback = dataArray;
			dataArray = undefined;
		}
		
		var dataObj = {};
		dataObj.action = templateName;
		if( dataArray && ( typeof dataArray =="array" || typeof dataArray =="object")){
			$.each(dataArray, function(i, v){
				dataObj[ i ] = v;
			});
		}

		$("#plug_background_menu a[data-action].act").removeClass("act");
		$(".plug_background").html( "" );
		admin.plug.ajax( "background", dataObj, function( html ){
			$(".plug_background").html( html );
			$("#plug_background_menu a[data-action='"+templateName+"']").addClass("act");
			admin.rebuild();
			if( typeof callback == "function" ) callback();
			admin.unblock();
		});
	}
}

$(function(){
	plugBackground.init();
})