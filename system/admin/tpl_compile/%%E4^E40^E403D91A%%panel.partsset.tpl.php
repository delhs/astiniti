<?php /* Smarty version 2.6.27, created on 2014-01-31 15:31:23
         compiled from panel.partsset.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'panel.partsset.tpl', 20, false),)), $this); ?>
<div class="panel_partsett">
	<h1>Настройка раздела</h1>

	<form name="partsett" data-part_id="<?php echo $this->_tpl_vars['part']['id']; ?>
">
	
		<div class="block">
			<p>Основные параметры</p>
			
			<div class="field fullurl">
				<label>Полная ссылка раздела</label><a title="Открыть текущую страницу в новой вкладке браузера" href="<?php echo $this->_tpl_vars['protocol']; ?>
://<?php echo $this->_tpl_vars['hostname']; ?>
<?php echo $this->_tpl_vars['part']['url']; ?>
" target="_blank"><span><?php echo $this->_tpl_vars['protocol']; ?>
://</span><?php echo $this->_tpl_vars['hostname']; ?>
<i><?php echo $this->_tpl_vars['part']['url']; ?>
</i></a>
			</div>
			
			<div class="field partid">
				<label>ID раздела</label><span><?php echo $this->_tpl_vars['part']['id']; ?>
</span>
			</div>
			
			<div class="field">
				<label>Название раздела<span class="hlp" title="Это название, которое будет отображаться в главном меню разделов сайта."></span></label>
				<div class="padding">
					<input type="text" name="name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['part']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
				</div>
			</div>
			
			<div class="field">
				<label>Краткое описание<span class="hlp" title="Этот текст будет выводиться в атрибуте &laquo;title&raquo; главного меню разделов сайта"></span></label>
				<div class="padding">
					<input type="text" name="quick_desc" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['part']['quick_desc'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
				</div>
			</div>	
			
			<?php if ($this->_tpl_vars['part']['id'] != 1): ?>
				<div class="field">
					<label>Линк страницы<span class="hlp" title="Это адрес, по которому данная страниц будет доступна. В данный момент полная ссылка на страницу выглядит так: &laquo;<?php echo $this->_tpl_vars['hostname']; ?>
<?php echo $this->_tpl_vars['part']['url']; ?>
&raquo;, где &laquo;<?php echo $this->_tpl_vars['part']['link']; ?>
&raquo; и есть линк этой страницы. Линк страницы может содержать только латинские буквы, цифры, символ &laquo;_&raquo; и символ &laquo;-&raquo;"></span></label>
					<div class="padding">
						<input type="text" name="link" value="<?php echo $this->_tpl_vars['part']['link']; ?>
" />
					</div>
				</div>
			<?php endif; ?>
			
			
			<input type="hidden" name="pid" value="<?php echo $this->_tpl_vars['part']['pid']; ?>
" />
				

			<?php if ($this->_tpl_vars['templatFileArray']): ?>
			<div class="field">
				<label>Шаблон страницы<span class="hlp" title="В данном проекте доступно несколько различных шаблонов. Выберите шаблон, который будет использоваться данным разделом"></span></label>
				<div class="padding">
					<select name="template">
						<?php $_from = $this->_tpl_vars['templatFileArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['dataArray']):
?>
						<option value="<?php echo $this->_tpl_vars['dataArray']; ?>
" <?php if ($this->_tpl_vars['part']['template'] == $this->_tpl_vars['dataArray']): ?> selected="selected" <?php endif; ?>><?php echo $this->_tpl_vars['dataArray']; ?>
</option>
						<?php endforeach; endif; unset($_from); ?>
					</select>
				</div>
			</div>
			<?php endif; ?>
			
		</div>
				
		<div class="block">
			<p>Параметры видимости</p>
			
			<div class="field">
				<label>Показывать в главном меню<span class="hlp" title="Если отмечен этот пункт, то раздел будет показываться в главном меню разделов сайта"></span></label>
				<input type="radio" name="in_menu" <?php if ($this->_tpl_vars['part']['in_menu'] == 1): ?> checked="checked" <?php endif; ?> value="1" />
			</div>
			
			<div class="field">
				<label>Скрыть из главного меню<span class="hlp" title="Если отмечен этот пункт, то раздел не будет показываться в главном меню разделов сайта"></span></label>
				<input type="radio" name="in_menu" <?php if ($this->_tpl_vars['part']['in_menu'] == 0): ?> checked="checked" <?php endif; ?> value="0" />
			</div>
		</div>
		
		<div class="block">
		
			<p>Параметры доступа</p>
			
			<div class="field">
				<label>Не доступен<span class="hlp" title="Если отмечен этот пункт, то при попытке перейти на данный раздел будет сгенерирована ошибка 404, которая означает, что такой страницы не существует. Помимо этого, раздел будет скрыт из главного меню разделов сайта"></span></label>
				<input type="radio" name="off" <?php if ($this->_tpl_vars['part']['off'] == 1): ?> checked="checked" <?php endif; ?> value="1" />
			</div>
			
			<div class="field">
				<label>Доступен<span class="hlp" title="Если отмечен данный пункт, то раздел будет доступен для проссмотра"></span></label>
				<input type="radio" name="off" <?php if ($this->_tpl_vars['part']['off'] == 0): ?> checked="checked" <?php endif; ?> value="0" />
			</div>
			
		</div>	
			
		<div class="block">
			<p>Параметры открытия</p>
			
			<div class="field">
				<label>В текущей вкладке<span class="hlp" title="Открывает страницу в этой же вкладке браузера"></span></label>
				<input type="radio" name="target" <?php if ($this->_tpl_vars['part']['target'] == '_self'): ?> checked="checked" <?php endif; ?> value="_self" />
			</div>
			
			<div class="field">
				<label>В новой вкладке<span class="hlp" title="Открывает раздел в новой вкладке браузера"></span></label>
				<input type="radio" name="target" <?php if ($this->_tpl_vars['part']['target'] == '_blank'): ?> checked="checked" <?php endif; ?> value="_blank" />
			</div>
		</div>
		
		<div class="spoiler">
			<div class="spoiler-head">Изображения и иконки</div>
			<div class="spoiler-body">
			
				<div class="block">
					<p>Иконка раздела</p>
					<div class="field">
						<div class="icons <?php if ($this->_tpl_vars['part']['icon'] != ''): ?>exist<?php endif; ?>"><img src="<?php if ($this->_tpl_vars['part']['icon'] != ''): ?><?php echo $this->_tpl_vars['part']['icon']; ?>
<?php endif; ?>" /></div>
					
						<input type="file" name="icon" id="icon" />
					</div>
				</div>
		</div>
		
		<div class="spoiler">
			<div class="spoiler-head">Дополнительные параметры</div>
			<div class="spoiler-body">
			

				<div class="block">
					<p>Параметры индексирования</p>
					<div class="field">
						<label>Разрешено индексировать<span class="hlp" title="Разрешает поисковым системам индексировать эту страницу. На страницу будет вставлен специальный мета-тег &lt;&nbsp;meta&nbsp;name=&laquo;Robots&raquo; content=&laquo;all&raquo;&nbsp;&gt;"></span></label>
						<input type="checkbox" name="meta_robots_all" <?php if ($this->_tpl_vars['part']['meta_robots_all'] == '1'): ?> checked="checked" <?php endif; ?> value="1" />
					</div>
					
					<div class="field">
						<label>Запрещено индексировать<span class="hlp" title="Запрещает поисковым системам индексировать эту страницу. На страницу будет вставлен специальный мета-тег &lt;&nbsp;meta&nbsp;name=&laquo;Robots&raquo; content=&laquo;noindex&raquo;&nbsp;&gt;"></span></label>
						<input type="checkbox" name="meta_robots_noindex" <?php if ($this->_tpl_vars['part']['meta_robots_noindex'] == '1'): ?> checked="checked" <?php endif; ?> value="1" />
					</div>	
					
					<div class="field">
						<label>Не переходить по ссылкам<span class="hlp" title="Запрещает поисковым системам переходить по ссылкам с этой страницы. На страницу будет вставлен специальный мета-тег &lt;&nbsp;meta&nbsp;name=&laquo;Robots&raquo; content=&laquo;nofollow&raquo;&nbsp;&gt;"></span></label>
						<input type="checkbox" name="meta_robots_nofollow" <?php if ($this->_tpl_vars['part']['meta_robots_nofollow'] == '1'): ?> checked="checked" <?php endif; ?> value="1" />
					</div>		
					
					<div class="field">
						<label>Не показывать сохраненную копию<span class="hlp" title="Запрещает поисковым системам показывать ссылку на сохраненную копию на странице результатов поиска. На страницу будет вставлен специальный мета-тег &lt;&nbsp;meta&nbsp;name=&laquo;Robots&raquo; content=&laquo;noarchive&raquo;&nbsp;&gt;"></span></label>
						<input type="checkbox" name="meta_robots_noarchive" <?php if ($this->_tpl_vars['part']['meta_robots_noarchive'] == '1'): ?> checked="checked" <?php endif; ?> value="1" />
					</div>
				</div>
				
				<div class="block">
					
					<p>Skype</p>
					
					<div class="field">
						<label>Блокировать "Skype"<span class="hlp" title="Предотвращает встраивание панелей &laquo;Skype&raquo; для номеров телефонов, размещенных на странице"></span></label>
						<input type="radio" name="skype_block" <?php if ($this->_tpl_vars['part']['skype_block'] == '1'): ?> checked="checked" <?php endif; ?> value="1" />
					</div>
					
					<div class="field">
						<label>Разрешить "Skype"<span class="hlp" title="Разрешает &laquo;Skype&raquo; встраиваться на страницу, выделяя номера телефонов"></span></label>
						<input type="radio" name="skype_block" <?php if ($this->_tpl_vars['part']['skype_block'] == '0'): ?> checked="checked" <?php endif; ?> value="0" />
					</div>
					
				</div>
		
				
				<div class="block">
					
					<p>Заголовки</p>
					
					<div class="field">
						<label>Pragma<span class="hlp" title="Данная опция предназначена для SEO оптимизаторов"></span></label>
						<div class="padding">
							<select name="pragma">
								<?php $_from = $this->_tpl_vars['pragmaArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
									<option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['key'] == $this->_tpl_vars['part']['pragma']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['item']; ?>
</option>
								<?php endforeach; endif; unset($_from); ?>
							</select>
						</div>
					</div>
					
					<div class="field">
						<label>Cache-Control<span class="hlp" title="Данная опция предназначена для SEO оптимизаторов"></span></label>
						<div class="padding">
							<select name="cache_control">
								<?php $_from = $this->_tpl_vars['cacheControlArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
									<option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['key'] == $this->_tpl_vars['part']['cache_control']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['item']; ?>
</option>
								<?php endforeach; endif; unset($_from); ?>
							</select>
						</div>
					</div>			
		
					<div class="field">
						<label>Last-Modified<span class="hlp" title="Данная опция предназначена для SEO оптимизаторов"></span></label>
						<div class="padding">
							<input type="text" name="edit_date" value="<?php echo $this->_tpl_vars['part']['edit_date']; ?>
" />
						</div>
					</div>
					
					<div class="field">
						<label>Expires<span class="hlp" title="Данная опция предназначена для SEO оптимизаторов"></span></label>
						<div class="padding">
							<input type="text" name="expires_date" value="<?php echo $this->_tpl_vars['part']['expires_date']; ?>
" />
						</div>
					</div>
					
				</div>
				
				<div class="block meta">
					
					<p>Мета теги</p>
					
					<div class="field">
						<label>Язык страницы<span class="hlp" title="Язык, на котором написан текст страницы"></span></label>
						<div class="padding">
							<select name="meta_lang">
								<?php $_from = $this->_tpl_vars['langsArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['lang'] => $this->_tpl_vars['langDesc']):
?>
								<option value="<?php echo $this->_tpl_vars['lang']; ?>
" <?php if ($this->_tpl_vars['part']['meta_lang'] == $this->_tpl_vars['lang']): ?> selected="selected" <?php endif; ?>><?php echo $this->_tpl_vars['langDesc']; ?>
&nbsp;(<?php echo $this->_tpl_vars['lang']; ?>
)</option>
								<?php endforeach; endif; unset($_from); ?>
							</select>
						</div>	
					</div>
					
					<div class="field">
						<label>Мета тег "TITLE"<span class="hlp" title="Это мета тег &laquo;Title&raquo;, текст которого выводится на вкладке страницы в браузере"></span></label>
						<div class="padding">
							<input type="text" name="meta_title" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['part']['meta_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"/>
						</div>
					</div>
					<div class="field">
						<label>Мета тег "KEYWORDS"<span class="hlp" title="Это ключевые слова, которые отражают общую суть того, что представлено на странице"></span></label>
						<div class="padding">
							<textarea class="highlight" name="meta_keywords"><?php echo $this->_tpl_vars['part']['meta_keywords']; ?>
</textarea>
						</div>
					</div>
					
					<div class="field">
						<label>Мета тег "DESCRIPTION"<span class="hlp" title="Это описание страницы, которое видит поисковая система"></span></label>
						<div class="padding">
							<textarea class="highlight" name="meta_description"><?php echo $this->_tpl_vars['part']['meta_description']; ?>
</textarea>
						</div>
					</div>
		
					<div class="field">
						<label>Прочие мета данные<span class="hlp" title="Данное поле позволяет добавить любой html, css или javascript код в секцию &laquo;HEAD&raquo; на данной странице"></span></label>
						<div class="padding">
							<textarea class="highlight" name="extra_meta"><?php echo $this->_tpl_vars['part']['extra_meta']; ?>
</textarea>
						</div>
					</div>			
					
					<div class="clear"></div>
				</div>
		
			</div>
		</div>
		
		<div class="block">
			<button type="button" name="save">Сохранить изменения</button>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</form>
</div>
	




<?php echo '
<script type="text/javascript">

	admin.jcropApi = undefined;

	$("input[name=\'expires_date\']").datetimepicker({timeFormat:"HH:mm:ss",dateFormat:\'dd.mm.yy\'});
	$("input[name=\'edit_date\']").datetimepicker({timeFormat:"HH:mm:ss",dateFormat:\'dd.mm.yy\'});

	$("form[name=\'partsett\'] button[name=\'save\']").click(function(){
		admin.block();
		
		//get crop object
		if( admin.jcropApi != undefined ){
			var cropObj = admin.jcropApi.tellSelect();
			//if crop width or height is zero, then set new width and height
			if( cropObj.w==0 || cropObj.h==0 ){
				cropObj.w = 40;
				cropObj.h = 40;
				cropObj.x = 0;
				cropObj.y = 0;
			}
			//floor the values and compile url string
			var urlStr = "";
			$.each( cropObj, function(i, v){
				urlStr += "&" + i + "=" + Math.floor(v);
			});
			var partIcon = $(".icons>img").attr("src");
		}else{
			var partIcon = "";
			var urlStr = "";
		}		
			
		var form = $("form[name=\'partsett\']");
		var partId = form.attr("data-part_id");
		var data = form.serialize()+"&action=editPart&partId=" + partId + "&partIcon="+partIcon + urlStr;
		

		admin.ajax( \'partsett\', data, form, function(){
			admin.reloadPanel(function(){
				var form = $("form[name=\'partsett\']");
				var url = form.find(".fullurl a>i").text();
				$("#toppanel .currentpageblock a").attr("href", url).children("span").text(url);
			});
		});
	});
	
	
	$("input[type=\'file\'][name=\'icon\']").change(function(){
		admin.block();
		admin.upload( "icon", "partsett", "uploadIcon", function( filename, errorMessage ){
			if( filename.trim()=="failed" || filename.trim()=="" ){
				if( errorMessage!="" )
				admin.errorBox("<p>"+errorMessage+"</p>");
				else
				admin.errorBox("<p>Во время загрузки изображения произошла ошибка.</p><p>Повторите попытку позже или выберите другой файл</p>");
				return;
			}
			
			$(".icons img").error(function(){
				admin.errorBox("<p>Во время загрузки изображения произошла ошибка.</p><p>Повторите попытку позже или выберите другой файл</p>");
				return;
			});
			
			if( admin.jcropApi!=undefined ) admin.jcropApi.destroy();
			$(".icons.exist").removeClass("exist");
			$(".icons img").attr("src", \'/system/admin/temp/\'+filename.trim()).show(0, function(){
				$(".icons img").css({height:"auto"}).Jcrop({
						aspectRatio:  335 / 335,
					}, function(){
					admin.jcropApi = this;
					var x = $(".icons img").outerWidth(true);
					var y = $(".icons img").outerHeight(true);
					admin.jcropApi.animateTo([ 0, 0,  335, 335 ]);
				});
			});
			admin.unblock();

		});
		
	});

	
	
	$(document).unbind(\'keydown\');
	$("form[name=\'partsett\'] input[type=\'text\']").bind(\'keydown\', function(e){
		if(e.keyCode == 13 ){
			$("form[name=\'partsett\'] button[name=\'save\']").click();
		}
	});
</script>
'; ?>