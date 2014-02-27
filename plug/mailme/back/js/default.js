var mailme = {
		
	init:function(){
		$("#cap").on("click", "#plug_mailme_menu a[data-action]", function(e){
			var dataAction = $(this).attr("data-action");
			e.preventDefault();
			admin.block();
			admin.plug.ajax("mailme", {action:dataAction}, function( html ){
				$(".plug_mailme").html( html );
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

		$("#plug_mailme_menu a[data-action].act").removeClass("act");
		$(".plug_mailme").html( "" );
		admin.plug.ajax( "mailme", dataObj, function( html ){
			$(".plug_mailme").html( html );
			$("#plug_mailme_menu a[data-action='"+templateName+"']").addClass("act");
			admin.rebuild();
			if( callback && typeof(callback)=="function" ) callback();
			admin.unblock();
		});
	},
	recipientEmailTplInit:function( departamentsList ){

		var $form = $("form[name='plug_mail_me_recipient_email']");

		//add new recipient button event
		$form.find("button[name='add']").click(function(e){
			e.preventDefault();

			var selectOptHtml = '<option value="0">--Не указано--</option>';
			$.each(departamentsList, function(i, dep){
				selectOptHtml += '<option value="'+dep.id+'">'+dep.name+'</option>';
			});

			var $table = $form.find("table"),
			appendHtml = 	'<tr>';
			appendHtml +=	 	'<td><select name="departament_id">'+selectOptHtml+'</select>';
			appendHtml +=	 	'<td><input type="text" name="email" class="email" value="" /></td>';
			appendHtml +=	 	'<td><input type="text" name="name" class="name" value="" /></td>';
			appendHtml +=	 	'<td class="delete"><span class="icon" title="Удалить получателя"></span></td>';
			appendHtml +=	'</tr>';

			$table.append( appendHtml );
			admin.rebuild()
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
				dataPbj[ i ].departament_id = $(this).find("select[name='departament_id']").val();
				dataPbj[ i ].email = $(this).find("input[name='email']").val();
				dataPbj[ i ].name = $(this).find("input[name='name']").val();
				i++;
			});

			dataPbj.action = "recipientEmailSave";

			var dataStr = decodeURIComponent($.param(dataPbj));
			admin.plug.ajax('mailme', dataStr, function(){
				mailme.viewTemplate("recipient_email_tpl");
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
	departamentsEmailTplInit:function(){
		
		var $form = $("form[name='plug_mail_me_departaments']");

		//add new recipient button event
		$form.find("button[name='add']").click(function(e){
			e.preventDefault();
			var $table = $form.find("table"),
			appendHtml = 	'<tr>';
			appendHtml +=	 	'<td><input type="text" name="name" class="name" value="" /></td>';
			appendHtml +=	 	'<td class="delete"><span class="icon" title="Удалить отдел"></span></td>';
			appendHtml +=	'</tr>';

			$table.append( appendHtml );
		});

		//remove recipient button event
		$form.on("click.remove_depart", "td.delete", function(e){
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
				dataPbj[ i ].name = $(this).find("input[name='name']").val();
				i++;
			});

			dataPbj.action = "departamentsSave";

			var dataStr = decodeURIComponent($.param(dataPbj));
			admin.plug.ajax('mailme', dataStr, function(){
				mailme.viewTemplate("departaments_tpl");
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
		
		var $form = $("form[name='plug_mail_me_recipient_email_sett']");

		//save button event
		$form.find("button[name='save']").click(function(e){
			e.preventDefault();
			var data = $form.serialize() + "&action=emailTemplateSave";
			admin.plug.ajax("mailme", data, $form, function(){
				mailme.viewTemplate("email_sett_tpl");
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
	historyTplInit:function(){
		$(".plug_mailme button[name='clear']").click(function(e){
			e.preventDefault();
			admin.block();
			admin.plug.ajax("mailme", {action:"clearHistory"}, function(){
				mailme.viewTemplate("history");
			});
		});
	},
	securityTplInit:function(){
		//save button
		var $form = $("form[name='plug_mail_me_security']");

		$form.find("button[name='save']").click(function(e){
			e.preventDefault();
			admin.block();
			var data = $form.serialize() + "&action=saveSecurity";
			admin.plug.ajax("mailme", data, function(){
				mailme.viewTemplate("security_tpl");
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
	mailme.init();
})