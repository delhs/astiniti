{if $allModulesArray}
	<div class="panel_modules">
		<h1>Модули</h1>
			<div class="left">
				<p>Модули этого раздела</p>
				
				<div class="mod_list">
					{if $attachedModArray}
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
					{else}
					<i>К данному разделу не подключен ни один модуль. Чтобы подключить новый модуль, выберите из списка доступных модулей необходимый и нажмите на иконку ввиде плюса "<span class="justplusicon"></span>" напротив названия модуля.</i>
					{/if}
					<i class="inf"></i>
					<div class="clear"></div>
				</div>
				
			</div>
			
			
			
			<div class="right">
				<p>Все доступнеые модули</p>
				<form>
				<div class="mod_list block">
					<ul>
					{foreach from=$allModulesArray key=index item=modDataArray}
						<li><span></span>{$modDataArray.mod_name_ru}<a href="#" class="add" data-modname="{$modDataArray.mod_name}" title="Подключить модуль &laquo;{$modDataArray.mod_name_ru}&raquo; к этому разделу"></a></li>
					{/foreach}
					</ul>
				</div>
				</form>
			</div>
		
	
		
		<div class="clear"></div>
	</div>

	<div class="clear"></div>
	{literal}
	<script type="text/javascript">
		$(window).resize(function(){
			$(".panel_modules .left").css({minHeight: ( admin.workHeight - $("#toppanel").outerHeight(true) - 120) + "px"});
		});
		$(window).resize();
		
		/* add button event*/
		$(".panel_modules .right .mod_list a.add").click(function(){
			admin.block();
			admin.ajax("modules", {action:"addModule", moduleName:$(this).attr("data-modname")}, function(){
				admin.reloadPanel();
			});
			return false;
		});
		
		/* sortable modules list */
		if( $(".panel_modules .left .mod_list ul.mod_list_ul>li").size()>1 ){
			$(".panel_modules .left .mod_list ul.mod_list_ul").sortable({
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
			$(".panel_modules .left .mod_list i.inf").text("Чтобы изменить порядок следования модулей, просто перемещайте их внутри списка между собой ухватив левой кнопкой мыши.");
			$(".panel_modules .left .mod_list>ul.mod_list_ul>li").css({cursor:"move"});
		}
		
		/* change module region */
		$(".panel_modules .left .mod_list select[name='moduleRegion']").change(function(){
			var regionName = $(this).val();
			var moduleName = $(this).attr("data-modruname");
			var moduleId = $(this).attr("data-modid");
			admin.ajax("modules", {action:"regionChange", regionName:regionName, moduleId:moduleId}, function(){
				admin.infotip('Регион модуля &laquo;'+moduleName+'&raquo; успешно изменен');
			});
		});
		
		
		/* delete module button event */
		$(".panel_modules .left .mod_list a.delete").click(function(e){
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
		$(".panel_modules .left .mod_list a.edit").click(function(e){
			e.preventDefault();
			admin.block();
			var moduleName = $(this).attr("data-modname");
			var moduleId = $(this).attr("data-id");
			admin.block();
			admin.ajax('modules', {action:"viewModule", moduleName:moduleName, moduleId:moduleId}, function( html ){
				$(".panel_modules").html( html );
				admin.unblock();
			});
		});
		
	</script>
	{/literal}
{else}
	<div class="panel_modules">
		<h1>Модули</h1>
		<i>В данном проекте нет ни одного подключаемого модуля.</i>
	</div>
{/if}