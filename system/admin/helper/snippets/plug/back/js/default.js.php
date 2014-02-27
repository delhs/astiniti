<?
$text = "var plug".ucfirst($plugName)." = {
		
	init:function(){
		\$(\"#cap\").on(\"click\", \"#plug_".$plugName."_menu a[data-action]\", function(e){
			e.preventDefault();
			plug".ucfirst($plugName).".viewTemplate(\$(this).attr(\"data-action\"));
			var dataAction = \$(this).attr(\"data-action\");
		});
	},
	viewTemplate:function( templateName, dataArray, callback ){
		admin.block();
		
		if( typeof dataArray ==\"function\" ){
			callback = dataArray;
			dataArray = undefined;
		}
		
		var dataObj = {};
		dataObj.action = templateName;
		if( dataArray && ( typeof dataArray ==\"array\" || typeof dataArray ==\"object\")){
			\$.each(dataArray, function(i, v){
				dataObj[ i ] = v;
			});
		}

		\$(\"#plug_".$plugName."_menu a[data-action].act\").removeClass(\"act\");
		\$(\".plug_".$plugName."\").html( \"\" );
		admin.plug.ajax( \"".$plugName."\", dataObj, function( html ){
			\$(\".plug_".$plugName."\").html( html );
			\$(\"#plug_".$plugName."_menu a[data-action='\"+templateName+\"']\").addClass(\"act\");
			admin.rebuild();
			if( typeof callback == \"function\" ) callback();
			admin.unblock();
		});
	}
}

\$(function(){
	plug".ucfirst($plugName).".init();
})";
?>