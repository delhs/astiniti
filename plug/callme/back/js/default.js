var callme = {
		
	init:function(){
		$("#cap").on("click", "#plug_callme_menu a[data-action]", function(e){
			var dataAction = $(this).attr("data-action");
			e.preventDefault();
			admin.block();
			admin.plug.ajax("callme", {action:dataAction}, function( html ){
				$(".plug_callme").html( html );
				admin.rebuild();
				admin.unblock();
			});
		});
	},
	viewTemplate:function( templateName, dataArray, callback ){
		admin.block();
		
		if( dataArray && typeof( dataArray )=="function" ){
			callback = dataArray;
			dataArray = undefined;
		}
		
		var dataObj = {};
		dataObj.action = templateName;
		if( dataArray && ( typeof( dataArray )=="array" || typeof( dataArray )=="object")){
			$.each(dataArray, function(i, v){
				dataObj[ i ] = v;
			});
		}

		$("#plug_callme_menu a[data-action].act").removeClass("act");
		$(".plug_callme").html( "" );
		admin.plug.ajax( "callme", dataObj, function( html ){
			$(".plug_callme").html( html );
			$("#plug_callme_menu a[data-action='"+templateName+"']").addClass("act");
			admin.rebuild();
			if( callback && typeof(callback)=="function" ) callback();
			admin.unblock();
		});
	},
	recipientSmsTplInit:function(){
		
		var $form = $("form[name='plug_call_me_recipient_sms']");

		//mask input
		$form.find("input[name='phone']").mask("+7(999)-999-99-99");

		//add new recipient button event
		$form.find("button[name='add']").click(function(e){
			e.preventDefault();
			var $table = $form.find("table"),
			appendHtml = 	'<tr>';
			appendHtml +=	 	'<td><input type="text" name="phone" class="phone" value="" /></td>';
			appendHtml +=	 	'<td><input type="text" name="name" class="name" value="" /></td>';
			appendHtml +=	 	'<td class="delete"><span class="icon" title="Удалить получателя"></span></td>';
			appendHtml +=	'</tr>';

			$table.append( appendHtml );
			$form.find("input[name='phone']:last").mask("+7(999)-999-99-99");
		});

		//remove recipient button event
		$form.on("click.remove_recipient", "td.delete", function(e){
			e.preventDefault();
			$(this).parent("tr").remove();
		});


		//save button event
		$form.find("button[name='save']").click(function(e){
			e.preventDefault();
			admin.block();
			var dataPbj = {},
			i = 0;

			$form.find("tbody").find("tr").each(function(){
				if( dataPbj[ i ]==undefined ) dataPbj[ i ] = {};
				dataPbj[ i ].phone = $(this).find("input[name='phone']").val();
				dataPbj[ i ].name = $(this).find("input[name='name']").val();
				i++;
			});

			dataPbj.action = "recipientSmsSave";

			var dataStr = decodeURIComponent($.param(dataPbj));
			admin.plug.ajax('callme', dataStr, function(){
				callme.viewTemplate("recipient_sms_tpl");
			});
		});

		$(document).unbind('keydown');
		$form.find("input[type='text']").bind('keydown', function(e){
			if(e.keyCode == 13 ){
				e.preventDefault();
				$form.find("button[name='save']").click();
			}
		});

	},
	recipientEmailTplInit:function(){
		
		var $form = $("form[name='plug_call_me_recipient_email']");

		//add new recipient button event
		$form.find("button[name='add']").click(function(e){
			e.preventDefault();
			var $table = $form.find("table"),
			appendHtml = 	'<tr>';
			appendHtml +=	 	'<td><input type="text" name="email" class="email" value="" /></td>';
			appendHtml +=	 	'<td><input type="text" name="name" class="name" value="" /></td>';
			appendHtml +=	 	'<td class="delete"><span class="icon" title="Удалить получателя"></span></td>';
			appendHtml +=	'</tr>';

			$table.append( appendHtml );
		});

		//remove recipient button event
		$form.on("click.remove_recipient", "td.delete", function(e){
			e.preventDefault();
			$(this).parent("tr").remove();
		});


		//save button event
		$form.find("button[name='save']").click(function(e){
			e.preventDefault();
			admin.block();
			var dataPbj = {},
			i = 0;

			$form.find("tbody").find("tr").each(function(){
				if( dataPbj[ i ]==undefined ) dataPbj[ i ] = {};
				dataPbj[ i ].email = $(this).find("input[name='email']").val();
				dataPbj[ i ].name = $(this).find("input[name='name']").val();
				i++;
			});

			dataPbj.action = "recipientEmailSave";

			var dataStr = decodeURIComponent($.param(dataPbj));
			admin.plug.ajax('callme', dataStr, function(){
				callme.viewTemplate("recipient_email_tpl");
			});
		});

		$(document).unbind('keydown');
		$form.find("input[type='text']").bind('keydown', function(e){
			if(e.keyCode == 13 ){
				e.preventDefault();
				$form.find("button[name='save']").click();
			}
		});

	},
	emailSettTplInit:function(){
		
		var $form = $("form[name='plug_call_me_recipient_email_sett']");

		//save button event
		$form.find("button[name='save']").click(function(e){
			e.preventDefault();
			var data = $form.serialize() + "&action=emailTemplateSave";
			admin.plug.ajax("callme", data, $form, function(){
				callme.viewTemplate("email_sett_tpl");
			});
		});

		$(document).unbind('keydown');
		$form.find("input[type='text']").bind('keydown', function(e){
			if(e.keyCode == 13 ){
				e.preventDefault();
				$form.find("button[name='save']").click();
			}
		});
	},
	smsSettTplInit:function(){
		
		var $form = $("form[name='plug_call_me_recipient_sms_sett']");

		//save button event
		$form.find("button[name='save']").click(function(e){
			e.preventDefault();
			admin.block();
			var data = $form.serialize() + "&action=smsTemplateSave";
			admin.plug.ajax("callme", data, function(){
				callme.viewTemplate("sms_sett_tpl");
			});
		});


		$form.find("textarea[name='text']").on("keydown change", function(){
			var text = $(this).val();
			var textLength = Math.ceil( text.length / 70 );
			if( textLength > 5 ){
				text = text.substr(0, 335);
				$(this).val( text ).trigger("change");
			}

			$form.find(".charcount b").text( textLength );
		});



		$(document).unbind('keydown');
		$form.find("input[type='text']").bind('keydown', function(e){
			if(e.keyCode == 13 ){
				e.preventDefault();
				$form.find("button[name='save']").click();
			}
		});

		$form.find("textarea[name='text']").trigger("change");
	},
	smsCenterTplInit:function(){
		
		var $form = $("form[name='plug_call_me_sms_center']");

		//save button event
		$form.find("button[name='save']").click(function(e){
			e.preventDefault();
			var data = $form.serialize() + "&action=smsCenterSave";
			admin.plug.ajax("callme", data, function(){
				callme.viewTemplate("sms_center_sett_tpl");
			});
		});


		//balance button event
		$form.find("button[name='balance']").click(function(e){
			e.preventDefault();
			$("#sms_center_balance").html( "Проверка баланса..." );
			admin.plug.ajax("callme", {action:"getBalance"}, function( string ){
				$("#sms_center_balance").html( string );
			});
		});

		$form.find("button[name='balance']").trigger("click");


		//limit button event
		$form.find("button[name='limit']").click(function(e){
			e.preventDefault();
			$("#sms_center_limit").html( "Проверка состояния..." );
			admin.plug.ajax("callme", {action:"getLimit"}, function( string ){
				$("#sms_center_limit").html( string );
			});
		});

		$form.find("button[name='limit']").trigger("click");



		$(document).unbind('keydown');
		$form.find("input[type='text']").bind('keydown', function(e){
			if(e.keyCode == 13 ){
				e.preventDefault();
				$form.find("button[name='save']").click();
			}
		});
	},
	historyTplInit:function(){
		$(".plug_callme button[name='clear']").click(function(e){
			e.preventDefault();
			admin.block();
			admin.plug.ajax("callme", {action:"clearHistory"}, function(){
				callme.viewTemplate("history");
			});
		});
	},
	securityTplInit:function(){
		//save button
		var $form = $("form[name='plug_call_me_security']");

		$form.find("button[name='save']").click(function(e){
			e.preventDefault();
			admin.block();
			var data = $form.serialize() + "&action=saveSecurity";
			admin.plug.ajax("callme", data, function(){
				callme.viewTemplate("security_tpl");
			});
		});	

		//change captcha type checker
		$form.find("input[name='captcha_is_on']").change(function(){
			if( $(this).prop("checked") ){
				$form.find("input[name='captcha_type']:disabled").removeAttr("disabled").trigger("refresh");
			}else{
				$form.find("input[name='captcha_type']").attr("disabled", "disabled").trigger("refresh");
			}
		});

	}
	
}





$(function(){
	callme.init();
})