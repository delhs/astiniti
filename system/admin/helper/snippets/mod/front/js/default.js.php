<?
$text="var mod_".$modName." = {
	
	/* initialize */
	init:function(){
	
	}
}

$(function(){
	if(typeof(plug_ajax)!=\"undefined\"){
		plug_ajax.ready(function(){
			mod_".$modName.".init();
		});
	}else{
		mod_".$modName.".init();
	}
});";
?>