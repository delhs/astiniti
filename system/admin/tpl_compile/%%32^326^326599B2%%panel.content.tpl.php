<?php /* Smarty version 2.6.27, created on 2014-02-03 15:49:13
         compiled from panel.content.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'panel.content.tpl', 44, false),)), $this); ?>
<div class="panel_content">
	<h1>Контент раздела</h1>
	<form name="content">
		<div class="block">
			<p>Модули этого раздела</p>
			<div class="mod_list">
				<ul class="mod_list_ul">
					<?php $_from = $this->_tpl_vars['attachedModArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['modDataArray']):
?>
						<li data-id="<?php echo $this->_tpl_vars['modDataArray']['id']; ?>
">
							
								<span class="dot"></span>
								<span class="modname">
									<?php echo $this->_tpl_vars['modDataArray']['mod_name_ru']; ?>

								</span>
								<span class="modregion">
									<select name="moduleRegion" data-modruname="<?php echo $this->_tpl_vars['modDataArray']['mod_name_ru']; ?>
" data-modid="<?php echo $this->_tpl_vars['modDataArray']['id']; ?>
">
										<option value="0">--Не указан--</option>
										<?php $_from = $this->_tpl_vars['allRegionsArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['regionDataArray']):
?>
										<option value="<?php echo $this->_tpl_vars['regionDataArray']['name']; ?>
" <?php if ($this->_tpl_vars['modDataArray']['region'] == $this->_tpl_vars['regionDataArray']['name']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['regionDataArray']['runame']; ?>
</option>
										<?php endforeach; endif; unset($_from); ?>
									</select>
									
								</span>
								<span class="hlp" title="Регион модуля - это специально отмеченное в шаблонном файле место, в которое может встроиться модуль. Вы можете сменить регион с помощью этого выпадающего списка. Данные изменения будут применены немедленно"></span>
								<a class="delete" data-id="<?php echo $this->_tpl_vars['modDataArray']['id']; ?>
" data-modnameru="<?php echo $this->_tpl_vars['modDataArray']['mod_name_ru']; ?>
" data-modname="<?php echo $this->_tpl_vars['modDataArray']['mod_name']; ?>
" href="#" title="Отключить этот модуль от раздела"></a>
								<a class="edit" data-id="<?php echo $this->_tpl_vars['modDataArray']['id']; ?>
" data-modname="<?php echo $this->_tpl_vars['modDataArray']['mod_name']; ?>
" href="#" title="Перейти к настройкам этого модуля"></a>
							
						</li>
					<?php endforeach; endif; unset($_from); ?>
				</ul>
				<?php if ($this->_tpl_vars['attachedModArray']): ?>
				<i>Для того, чтобы подключить новый модуль, перейдите в панель <a class="gotomodulespanel" href="#">"Модули"</a></i>
				<?php else: ?>
				<i>К данному разделу не подключен ни один модуль. Чтобы подключить новый модуль, перейдите в панель <a class="gotomodulespanel" href="#">"Модули"</a></i>
				<?php endif; ?>
				<i class="inf"></i>
			</div>
			
			<div class="clear"></div>
		</div>
		
		<div class="block">
			<p>Заголовок страницы<span class="hlp" title="Это тэг &laquo;H1&raquo;, который выводится перед основным контентом страницы"></span></p>
			<input type="text" name="title" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['page']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</div>
		
		<div class="block foreditor">
			<p>Контент страницы<span class="hlp" title="Спомощью редактора контента вы можете разместить любую необходимую текстовую информацию на данной странице сайта. Вы можете вставлять изображения, видеофайлы, файлы документов, таблицы, оформлять текст и пр."></span></p>
			<textarea name="content"><?php echo $this->_tpl_vars['page']['content']; ?>
</textarea>	
			<div class="clear"></div>
		</div>

		<button type="button" name="save">Сохранить</button>
		<div class="clear"></div>
	</form>

	<div class="clear"></div>
</div>

<div class="clear"></div>
<?php echo '
<script type="text/javascript">

	var redactorResizeInerval = setTimeout(function(){
		if( $(".panel_content .redactor_box").length==1 && $(".panel_content .redactor_box").hasClass("ui-resizable") ){
			var foreditorblockHeight = $(".foreditor").outerHeight(true);
			$(".panel_content .redactor_box").find(".redactor_.redactor_editor").css({height: + (foreditorblockHeight - $(this).find(".redactor_toolbar").outerHeight(true) - 17)+"px"});
			$(".panel_content textarea[name=\'content\']").css({height:  (foreditorblockHeight - $(this).find(".redactor_toolbar").outerHeight(true)  )+"px"});
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
	
				dataArray[\'action\'] = \'rangeChange\';
				admin.ajax("modules", dataArray, function(){
					admin.infotip(\'Порядок следования модулей успешно изменен\');
				});
			}	
		});
		$(".panel_content .mod_list i.inf").text("Чтобы изменить порядок следования модулей, просто перемещайте их внутри списка между собой ухватив левой кнопкой мыши.");
		$(".panel_content .mod_list>ul.mod_list_ul>li").css({cursor:"move"});
	}


	/* change module region */
	$(".panel_content .mod_list select[name=\'moduleRegion\']").change(function(){
		var regionName = $(this).val();
		var moduleName = $(this).attr("data-modruname");
		var moduleId = $(this).attr("data-modid");
		admin.ajax("modules", {action:"regionChange", regionName:regionName, moduleId:moduleId}, function(){
			admin.infotip(\'Регион модуля &laquo;\'+moduleName+\'&raquo; успешно изменен\');
		});
	});


	/* delete module button event */
	$(".panel_content .mod_list a.delete").click(function(e){
		e.preventDefault();
		var moduleId = $(this).attr("data-id");
		var moduleName = $(this).attr("data-modname");
		var moduleNameRu = $(this).attr("data-modnameru");
		admin.confirmBox(\'Отключение модуля &laquo;\'+moduleNameRu+\'&raquo; от данного раздела. Все настройки модуля будут утеряны.<br/>Продолжить?\',
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
			$(".panel_modules .left .mod_list a.edit[data-id=\'"+moduleId+"\']").click();
		});
	});
		
	/* go to modules panel button event */
	$(".panel_content a.gotomodulespanel").click(function(e){
		e.preventDefault();
		admin.loadPanel("modules");
	});
	
	/* save button event */
	$("form[name=\'content\'] button[name=\'save\']").click(function(){
		var dataArray = $("form[name=\'content\']").serialize();
		admin.block();
		admin.ajax( \'content\', dataArray, function( r ){
			admin.reloadPanel(r);
		});
	});
	
	$(document).unbind(\'keydown\');
	$("form[name=\'content\'] input").bind(\'keydown\', function(e){
		if(e.keyCode == 13){
			e.preventDefault();
			$("form[name=\'content\'] button[name=\'save\']").click();
		}
	});
	
	admin.insertEditor("textarea[name=\'content\']", function(){
		//$("textarea[name=\'content\']").redactor(\'buttonAdd\', \'save\', \'Сохранить контент\',function(buttonName, buttonDOM, buttonObj, e){
		//	$("form[name=\'content\'] button[name=\'save\']").click();
		//});
	});
	
</script>
'; ?>