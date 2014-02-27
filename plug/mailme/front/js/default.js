var plug_mailme = {
	init:function(){
		$("a.mailme").click(function(e){
			e.preventDefault();
			plug_mailme.showForm();
		});	
	},
	showForm:function(){
		core.blockScreen();
		core.plug.ajax("mailme", {action:"getForm"}, function( html ){
			core.unblockScreen();
			$.splash( html , {
				cssClass:"mailme",
				closeToEscape:true,
				openCallback:function(){
					var $form = $(".splash_splash form[name='mailme']");

					$form.find("select").styler()

					//set focus
					$form.find("input[name='name']").focus();
					
					//mailme button event
					$form.find("button[name='mailme']").click(function(e){
						e.preventDefault();
						core.blockScreen();
						var data = $form.serialize() + "&action=mailme";
						core.plug.ajax("mailme", data, $form, function( json ){
							$.splashClose();
							var response = $.parseJSON( json );
							core.messageBox( response.message, 3000, function(){
								$.splashClose();
							});
						});
					});

					//keydon event on ENTER keyboard button
					$form.off("keydown.oninput");
					$form.on("keydown", "input[type='text']", function(e){
						if(e.keyCode == 13 ){
							e.preventDefault();
							$form.find("button[name='mailme']").click();
						}
					});

					//image click event
					$form.find("img[src ^= '/captcha.php']").click(function(){
						plug_mailme.updateCaptcha();
					});

				},
				durationOpen:0
			});
		});
	},
	updateCaptcha:function(){
		var $form = $(".splash_splash form[name='mailme']");
		var $captcha = $form.find("img[src ^= '/captcha.php']");
		var captchaSrc = $captcha.attr("src");
		captchaSrc = captchaSrc.replace(/nocache=.+$/gi, 'nocache='+Math.floor(Math.random()*999999));
		$captcha.attr("src", captchaSrc);

	}
}

$(function(){
	if(typeof(plug_ajax)!="undefined"){
		plug_ajax.ready(function(){
			plug_mailme.init();
		});
	}else{
		plug_mailme.init();
	}
});