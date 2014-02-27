<?
$text = "var mod".ucfirst($modName)." = {
		
	init:function(){
		\$(\"#cap\").on(\"click\", \"#mod_".$modName."_menu a[data-action]\", function(e){
			e.preventDefault();
			mod".ucfirst($modName).".viewTemplate( \$(this).attr(\"data-action\") );
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
		if( typeof dataArray ==\"array\" || typeof dataArray ==\"object\")){
			\$.each(dataArray, function(i, v){
				dataObj[ i ] = v;
			});
		}

		\$(\"#mod_".$modName."_menu a[data-action].act\").removeClass(\"act\");
		\$(\".mod_".$modName."\").html( \"\" );
		admin.mod.ajax( \"".$modName."\", dataObj, function( html ){
			\$(\".mod_".$modName."\").html( html );
			\$(\"#mod_".$modName."_menu a[data-action='\"+templateName+\"']\").addClass(\"act\");
			admin.rebuild();
			if( typeof callback == \"function\" ) callback();
			admin.unblock();
		});
	}
	
}


\$(function(){
	mod".ucfirst($modName).".init();
})";
?>