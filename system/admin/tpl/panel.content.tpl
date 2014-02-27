<div class="panel_content">
	<h1>Контент раздела</h1>
	<form name="content">
		<div class="block">
			<p>Модули этого раздела</p>
			<div class="mod_list">
				<ul class="mod_list_ul">
					{foreach from=$attachedModArray key=index item=modDataArray}
						<li data-id="{$modDataArray.id}">
							
								<span class="dot"></span>
								<span class="modname">
									{$modDataArray.mod_name_ru}
								</span>
								<span class="modregion">
									<select name="moduleRegion" data-modruname="{$modDataArray.mod_name_ru}" data-modid="{$modDataArray.id}">
										<option value="0">--Не указан--</option>
										{foreach from=$allRegionsArray item=regionDataArray}
										<option value="{$regionDataArray.name}" {if $modDataArray.region eq $regionDataArray.name} selected="selected"{/if}>{$regionDataArray.runame}</option>
										{/foreach}
									</select>
									
								</span>
								<span class="hlp" title="Регион модуля - это специально отмеченное в шаблонном файле место, в которое может встроиться модуль. Вы можете сменить регион с помощью этого выпадающего списка. Данные изменения будут применены немедленно"></span>
								<a class="delete" data-id="{$modDataArray.id}" data-modnameru="{$modDataArray.mod_name_ru}" data-modname="{$modDataArray.mod_name}" href="#" title="Отключить этот модуль от раздела"></a>
								<a class="edit" data-id="{$modDataArray.id}" data-modname="{$modDataArray.mod_name}" href="#" title="Перейти к настройкам этого модуля"></a>
							
						</li>
					{/foreach}
				</ul>
				{if $attachedModArray}
				<i>Для того, чтобы подключить новый модуль, перейдите в панель <a class="gotomodulespanel" href="#">"Модули"</a></i>
				{else}
				<i>К данному разделу не подключен ни один модуль. Чтобы подключить новый модуль, перейдите в панель <a class="gotomodulespanel" href="#">"Модули"</a></i>
				{/if}
				<i class="inf"></i>
			</div>
			
			<div class="clear"></div>
		</div>
		
		<div class="block">
			<p>Заголовок страницы<span class="hlp" title="Это тэг &laquo;H1&raquo;, который выводится перед основным контентом страницы"></span></p>
			<input type="text" name="title" value="{$page.title|escape}" />
		</div>
		
		<div class="block foreditor">
			<p>Контент страницы<span class="hlp" title="Спомощью редактора контента вы можете разместить любую необходимую текстовую информацию на данной странице сайта. Вы можете вставлять изображения, видеофайлы, файлы документов, таблицы, оформлять текст и пр."></span></p>
			<textarea name="content">{$page.content}</textarea>	
			<div class="clear"></div>
		</div>

		<button type="button" name="save">Сохранить</button>
		<div class="clear"></div>
	</form>

	<div class="clear"></div>
</div>

<div class="clear"></div>
{literal}
<script type="text/javascript">

	var redactorResizeInerval = setTimeout(function(){
		if( $(".panel_content .redactor_box").length==1 && $(".panel_content .redactor_box").hasClass("ui-resizable") ){
			var foreditorblockHeight = $(".foreditor").outerHeight(true);
			$(".panel_content .redactor_box").find(".redactor_.redactor_editor").css({height: + (foreditorblockHeight - $(this).find(".redactor_toolbar").outerHeight(true) - 17)+"px"});
			$(".panel_content textarea[name='content']").css({height:  (foreditorblockHeight - $(this).find(".redactor_toolbar").outerHeight(true)  )+"px"});
			clearInterval( window.redactorResizeInerval );
		}
	}, 100);
	
	/* sortable modules list */
	if( $(".panel_content .mod_list ul.mod_list_ul>li").size()>1 ){
		$(".panel_content .mod_list ul.mod_list_ul").sortable({
			revert: 150,
			placeholder: "hold",
			start: function( event, ui ){
				var holdHeight = $(ui.item).outerHeight(true);
				$(".panel_parts .mmenu .hold").css({height: holdHeight + "px"});
			},
			stop: function(event, ui)
			{
				var dataArray = {};
				var range = 0;
				$(this).children("li").each(function(){	
					var id = $(this).attr("data-id");
					dataArray[ range ] = id;
					range++;
				});
	
				dataArray['action'] = 'rangeChange';
				admin.ajax("modules", dataArray, function(){
					admin.infotip('Порядок следования модулей успешно изменен');
				});
			}	
		});
		$(".panel_content .mod_list i.inf").text("Чтобы изменить порядок следования модулей, просто перемещайте их внутри списка между собой ухватив левой кнопкой мыши.");
		$(".panel_content .mod_list>ul.mod_list_ul>li").css({cursor:"move"});
	}


	/* change module region */
	$(".panel_content .mod_list select[name='moduleRegion']").change(function(){
		var regionName = $(this).val();
		var moduleName = $(this).attr("data-modruname");
		var moduleId = $(this).attr("data-modid");
		admin.ajax("modules", {action:"regionChange", regionName:regionName, moduleId:moduleId}, function(){
			admin.infotip('Регион модуля &laquo;'+moduleName+'&raquo; успешно изменен');
		});
	});


	/* delete module button event */
	$(".panel_content .mod_list a.delete").click(function(e){
		e.preventDefault();
		var moduleId = $(this).attr("data-id");
		var moduleName = $(this).attr("data-modname");
		var moduleNameRu = $(this).attr("data-modnameru");
		admin.confirmBox('Отключение модуля &laquo;'+moduleNameRu+'&raquo; от данного раздела. Все настройки модуля будут утеряны.<br/>Продолжить?',
			function(){
				admin.block();
				admin.ajax("modules", {action:"deleteModule", moduleId:moduleId, moduleName:moduleName }, function(){
					admin.reloadPanel();
				});
			}
		);
	});

	/* view module button event */
	$(".panel_content .mod_list a.edit").click(function(e){
		e.preventDefault();
		admin.block();
		var moduleId = $(this).attr("data-id");
		admin.loadPanel("modules", function(){
			$(".panel_modules .left .mod_list a.edit[data-id='"+moduleId+"']").click();
		});
	});
		
	/* go to modules panel button event */
	$(".panel_content a.gotomodulespanel").click(function(e){
		e.preventDefault();
		admin.loadPanel("modules");
	});
	
	/* save button event */
	$("form[name='content'] button[name='save']").click(function(){
		var dataArray = $("form[name='content']").serialize();
		admin.block();
		admin.ajax( 'content', dataArray, function( r ){
			admin.reloadPanel(r);
		});
	});
	
	$(document).unbind('keydown');
	$("form[name='content'] input").bind('keydown', function(e){
		if(e.keyCode == 13){
			e.preventDefault();
			$("form[name='content'] button[name='save']").click();
		}
	});
	
	admin.insertEditor("textarea[name='content']", function(){
		//$("textarea[name='content']").redactor('buttonAdd', 'save', 'Сохранить контент',function(buttonName, buttonDOM, buttonObj, e){
		//	$("form[name='content'] button[name='save']").click();
		//});
	});
	
</script>
{/literal}