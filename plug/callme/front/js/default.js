var plug_callme = {
	
	init:function(){
		var elements = document.querySelectorAll("a.callme");
		Array.prototype.forEach.call(elements, function(el, i){
			el.addEventListener('click', function(e){
				e.preventDefault();
				plug_callme.showForm();
			}, false);
		});
	},
	showForm:function(){
		core.blockScreen();
		core.plug.ajax("callme", {action:"getForm"}, function( html ){
			core.unblockScreen();
			$.splash( html , {
				cssClass:"callme",
				closeToEscape:true,
				openCallback:function(){
					var $form = $(".splash_splash form[name='callme']");

					//set focus
					$form.find("input[name='name']").focus();
					
					//input mask
					$form.find("input[name='phone']").mask("+7(999)-999-99-99");
					
					//callme button event
					$form.find("button[name='callme']").click(function(e){
						e.preventDefault();
						core.blockScreen();
						var data = $form.serialize() + "&action=callme";
						core.plug.ajax("callme", data, $form, function( json ){
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
							$form.find("button[name='callme']").click();
						}
					});

					//image click event
					$form.find("img[src ^= '/captcha.php']").click(function(){
						plug_callme.updateCaptcha();
					});

				},
				durationOpen:0
			});
		});
	},
	updateCaptcha:function(){
		var $form = $(".splash_splash form[name='callme']");
		var $captcha = $form.find("img[src ^= '/captcha.php']");
		var captchaSrc = $captcha.attr("src");
		captchaSrc = captchaSrc.replace(/nocache=.+$/gi, 'nocache='+Math.floor(Math.random()*999999));
		$captcha.attr("src", captchaSrc);

	}
}

$(function(){
	if(typeof(plug_ajax)!="undefined"){
		plug_ajax.ready(function(){
			plug_callme.init();
		});
	}else{
		plug_callme.init();
	}
});