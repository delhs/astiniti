<?
$text="var plug".ucfirst($plugName)." = {
	
	/* initialize */
	init:function(){
	
	}
}

$(function(){
	if(typeof(plug_ajax)!=\"undefined\"){
		plug_ajax.ready(function(){
			plug".ucfirst($plugName).".init();
		});
	}else{
		plug".ucfirst($plugName).".init();
	}
});";
?>

