<?php /* Smarty version 2.6.27, created on 2014-02-04 13:39:35
         compiled from panel.settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'panel.settings.tpl', 154, false),)), $this); ?>
<div class="panel_settings">
	<h1>Настройки сайта</h1>
	<form name="settings">
		
		<div class="spoiler">
			<div class="spoiler-head -opened">Блокировка сайта</div>
			<div class="spoiler-body">
				<div class="block">
					
					<p>Блокировка сайта<span class="hlp" title="Сайт можно заблокировать, например, на время ведения профилактических работ отметив соответствующий пункт. В этом случае, все пользователи, зашедшие на сайт увидят специальное сообщение о том, что данный ресурс временно недоступен"></span></p>
		
					<div class="field">
						<label>Не использовать блокировку</label>
						<input type="radio" name="closed" value="0" <?php if ($this->_tpl_vars['settings']['closed'] == '0'): ?>checked="checked"<?php endif; ?> />
					</div>		
					
					<div class="field">
						<label>Профилактические работы</label>
						<input type="radio" name="closed" value="1" <?php if ($this->_tpl_vars['settings']['closed'] == '1'): ?>checked="checked"<?php endif; ?> />
					</div>		

					<div class="field">
						<label>Реконструкция сайта</label>
						<input type="radio" name="closed" value="2" <?php if ($this->_tpl_vars['settings']['closed'] == '2'): ?>checked="checked"<?php endif; ?> />
					</div>	
					
					<div class="field">
						<label>Обновление товаров</label>
						<input type="radio" name="closed" value="3" <?php if ($this->_tpl_vars['settings']['closed'] == '3'): ?>checked="checked"<?php endif; ?> />
					</div>		
		
					<p>Разрешенные IP адреса<span class="hlp" title="Разрешенные IP адреса - это список IP адресов, владельцы которых смогут зайти на сайт даже если он заблокирован настройкой из пункта &laquo;Блокировка&nbsp;сайта&raquo;."></span></p>
					<div class="ips">
						<?php $_from = $this->_tpl_vars['excetionIpArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ipArray']):
?>
						<div class="field">
							<input type="text" name="exception_ip[]" value="<?php echo $this->_tpl_vars['ipArray']['ip']; ?>
" <?php if ($this->_tpl_vars['ipArray']['ip'] == $this->_tpl_vars['myIp']): ?>ismyip<?php endif; ?> /><a href="#" class="del_ip"></a>
						</div>
						<?php endforeach; endif; unset($_from); ?>
					</div>
					
					<button type="button" name="add_ip">Добавить IP адрес</button>
					<button type="button" name="add_my_ip" data-ip="<?php echo $this->_tpl_vars['myIp']; ?>
">Добавить мой IP адрес</button><span class="hlp" title="Данная функция добавит в список разрешенных IP адресов IP адрес вашего компьютера"></span>
				</div>
			</div>
		</div>
		
		
		
		<div class="spoiler">
			<div class="spoiler-head">Файл &laquo;robots.txt&raquo;</div>
			<div class="spoiler-body">
				<div class="block">
					<p>Файл &laquo;robots.txt&raquo;<span class="hlp" title="Файл robots.txt – это текстовый файл, находящийся в корневой директории сайта, в котором записываются специальные инструкции для поисковых роботов"></span></p>
					<?php if ($this->_tpl_vars['robotsTxt']): ?>
					<div class="field">
						<textarea name="robots_txt" class="highlight"><?php echo $this->_tpl_vars['robotsTxt']; ?>
</textarea>
					</div>
					
					<div class="field">
						<button name="recreate_robots_txt">Пересоздать файл &laquo;robots.txt&raquo; с командами по умолчанию</button><span class="hlp" title="Данная функция удалит существующий файл &laquo;robots.txt&raquo;, а затем создаст его вновь, и запишет в него минимальный список команд, которые необходимы для правильной индексации сайта поисковыми системами"></span>
					</div>
					<?php else: ?>
					<div class="field">
						<span>В настоящий момент файла &laquo;robots.txt&raquo; не существует</span>
					</div>
					<div class="field">
						<button name="recreate_robots_txt">Создать файл &laquo;robots.txt&raquo; с командами по умолчанию</button><span class="hlp" title="Данная функция создаст файл &laquo;robots.txt&raquo; и запишет в него минимальный список команд, которые необходимы для правильной индексации сайта поисковыми системами"></span>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		
		
		<div class="spoiler">
			<div class="spoiler-head">Файл &laquo;sitemap.xml&raquo;&nbsp;(Карта сайта)</div>
			<div class="spoiler-body">
				<div class="block">
					<p>Файл &laquo;sitemap.xml&raquo;<span class="hlp" title="Файл &laquo;sitemap.xml&raquo – это текстовый файл формата &laquo;XML&raquo;, находящийся в корневой директории сайта, содержащий в себе список всех разделов сайта. Данный файл предназначен для поисковых систем"></span></p>
					<?php if ($this->_tpl_vars['sitemapXml']): ?>
					<div class="field">
						<textarea name="sitemap_xml" class="highlight"><?php echo $this->_tpl_vars['sitemapXml']; ?>
</textarea>
					</div>

					<p><i>Важно! В файл &laquo;sitemap.xml&raquo; не попадут разделы в настройках которых установлен пункт &laquo;заблокирован&raquo;, &laquo;не активен&raquo; или &laquo;выключен&raquo;</i></p>

					<div class="field">
						<button name="recreate_sitemap_xml">Пересоздать файл &laquo;sitemap.xml&raquo; </button><span class="hlp" title="Данная функция удалит существующий файл &laquo;sitemap.xml&raquo;, а затем создаст его вновь, и запишет в него перечень всех разделов сайта"></span>
					</div>
					<?php else: ?>
					<div class="field">
						<span>В настоящий момент файла &laquo;sitemap.xml&raquo; не существует</span>
					</div>

					<p><i>Важно! В файл &laquo;sitemap.xml&raquo; не попадут разделы в настройках которых установлен пункт &laquo;заблокирован&raquo;, &laquo;не активен&raquo; или &laquo;выключен&raquo;</i></p>
					
					<div class="field">
						<button name="recreate_sitemap_xml">Создать файл &laquo;sitemap.xml&raquo;</button><span class="hlp" title="Данная функция создаст файл &laquo;sitemap.xml&raquo; и запишет в перечень всех разделов сайта"></span>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="spoiler">
			<div class="spoiler-head">Оптимизация</div>
			<div class="spoiler-body">
				<div class="block">
					<div class="field">
						<label>Объединять файлы &laquo;Javascript&raquo; в один<span class="hlp" title="При включенной опции, во время загрузки страницы, все файлы javascript будут объединены в один файл, который будет генерироваться по адресу &laquo;<?php echo $this->_tpl_vars['protocol']; ?>
://<?php echo $this->_tpl_vars['host']; ?>
/js/scripts.js&raquo;"></span></label>
						<div class="padding">
							<input type="checkbox" name="unite_js" value="1" <?php if ($this->_tpl_vars['settings']['unite_js'] == '1'): ?>checked="checked"<?php endif; ?> />
						</div>
					</div>
					
					<div class="field">
						<label>Объединять файлы &laquo;CSS&raquo; в один<span class="hlp" title="При включенной опции, во время загрузки страницы, все файлы css будут объединены в один файл, который будет генерироваться по адресу &laquo;<?php echo $this->_tpl_vars['protocol']; ?>
://<?php echo $this->_tpl_vars['host']; ?>
css/styles.css&raquo;"></span></label>
						<div class="padding">
							<input type="checkbox" name="unite_css" value="1" <?php if ($this->_tpl_vars['settings']['unite_css'] == '1'): ?>checked="checked"<?php endif; ?> />
						</div>
					</div>
					
					<div class="field">
						<label>Использовать &laquo;gzip&raquo; сжатие<span class="hlp" title="gzip позволяет сжимать данные, которые передаются от сервера к браузеру, что увеличивает скорость загрузки сайта. Сжатию будут подвергнуты файлы Javascript, СSS и основное тело документа(HTML)"></span></label>
						<div class="padding">
							<input type="checkbox" name="gzip" value="1" <?php if ($this->_tpl_vars['settings']['gzip'] == '1'): ?>checked="checked"<?php endif; ?> />
						</div>
					</div>
					
					<div class="field gzip_level">
						<label class="gzip_level <?php if ($this->_tpl_vars['settings']['gzip'] == '0'): ?>disabled<?php endif; ?>">Уровень сжатия &laquo;gzip&raquo;<span class="hlp" title="Степень сжатия gzip"></span></label>
						<div class="padding">
							<?php unset($this->_sections['bar']);
$this->_sections['bar']['name'] = 'bar';
$this->_sections['bar']['loop'] = is_array($_loop=10) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['bar']['max'] = (int)10;
$this->_sections['bar']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['bar']['show'] = true;
if ($this->_sections['bar']['max'] < 0)
    $this->_sections['bar']['max'] = $this->_sections['bar']['loop'];
$this->_sections['bar']['start'] = $this->_sections['bar']['step'] > 0 ? 0 : $this->_sections['bar']['loop']-1;
if ($this->_sections['bar']['show']) {
    $this->_sections['bar']['total'] = min(ceil(($this->_sections['bar']['step'] > 0 ? $this->_sections['bar']['loop'] - $this->_sections['bar']['start'] : $this->_sections['bar']['start']+1)/abs($this->_sections['bar']['step'])), $this->_sections['bar']['max']);
    if ($this->_sections['bar']['total'] == 0)
        $this->_sections['bar']['show'] = false;
} else
    $this->_sections['bar']['total'] = 0;
if ($this->_sections['bar']['show']):

            for ($this->_sections['bar']['index'] = $this->_sections['bar']['start'], $this->_sections['bar']['iteration'] = 1;
                 $this->_sections['bar']['iteration'] <= $this->_sections['bar']['total'];
                 $this->_sections['bar']['index'] += $this->_sections['bar']['step'], $this->_sections['bar']['iteration']++):
$this->_sections['bar']['rownum'] = $this->_sections['bar']['iteration'];
$this->_sections['bar']['index_prev'] = $this->_sections['bar']['index'] - $this->_sections['bar']['step'];
$this->_sections['bar']['index_next'] = $this->_sections['bar']['index'] + $this->_sections['bar']['step'];
$this->_sections['bar']['first']      = ($this->_sections['bar']['iteration'] == 1);
$this->_sections['bar']['last']       = ($this->_sections['bar']['iteration'] == $this->_sections['bar']['total']);
?>
								<span class="gzip_level"><?php echo $this->_sections['bar']['index']; ?>
</span><input <?php if ($this->_sections['bar']['index'] == $this->_tpl_vars['settings']['gzip_level']): ?>checked="checked"<?php endif; ?> type="radio" name="gzip_level" value="<?php echo $this->_sections['bar']['index']; ?>
" <?php if ($this->_tpl_vars['settings']['gzip'] == '0'): ?>disabled="disabled"<?php endif; ?>/>
							<?php endfor; endif; ?>		
						</div>
					</div>
					
					
				</div>
			</div>
		</div>
		
		
		<div class="spoiler">
			<div class="spoiler-head">Прочие настройки (Локализация, префикс TITLE, Extra meta )</div>
			<div class="spoiler-body">
				
				<div class="block">	
					<div class="field">
						<p>Глобальный префикс мета тега &laquo;TITLE&raquo;</p>
						<label>Префикс<span class="hlp" title="Значение, указанное в данном поле, будет подставляться к началу тега &laquo;TITLE&raquo;, который выводится в названии вкладки браузера. Данное правило будет применено ко всем страницам без исключения"></span></label>
						<div class="padding">
							<input type="text" name="global_meta_title_prefix" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['settings']['global_meta_title_prefix'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
						</div>
					</div>
					
					<div class="field">
						<label>Инвертировать префикс в постфикс<span class="hlp" title="Если данное значение отмечено, то префикс будет подставляться не к началу тега &laquo;TITLE&raquo;, а к его концу"></span></label>
						<div class="padding">
							<input type="checkbox" name="invert_title_prefix" value="1" <?php if ($this->_tpl_vars['settings']['invert_title_prefix'] == '1'): ?>checked="checked"<?php endif; ?> />
						</div>
					</div>
				</div>
				
				<div class="block">
					<div class="field">
						<p>Локализация</p>
						<label>Временная зона (часовой пояс)</label>
						<div class="padding">
							<select name="timezone">
								<option value="0">--Не установлено--</option>
								<?php $_from = $this->_tpl_vars['timeZoneArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['timegroup'] => $this->_tpl_vars['zoneArray']):
?>
									<optgroup label="<?php echo $this->_tpl_vars['timegroup']; ?>
">
										<?php $_from = $this->_tpl_vars['zoneArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['zoneName']):
?>
										<option value="<?php echo $this->_tpl_vars['zoneName']; ?>
" <?php if ($this->_tpl_vars['zoneName'] == $this->_tpl_vars['settings']['timezone']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['zoneName']; ?>
</option>
										<?php endforeach; endif; unset($_from); ?>
									</optgroup>
								<?php endforeach; endif; unset($_from); ?>
							</select>
						</div>
					</div>
				</div>
				
				<div class="block">
					<p>Extra meta<span class="hlp" title="Данное поле позволяет добавить любой html, css или javascript код в секцию &laquo;HEAD&raquo; на всех страницах сайта без исключения. Например, для вставки кода счетчика или мета тега поисковой системы для подтверждения прав владельца сайта"></span></p>
					<div class="field">
						<textarea name="super_meta" class="highlight"><?php echo $this->_tpl_vars['settings']['super_meta']; ?>
</textarea>
					</div>
				</div>
				
				
			</div>
		</div>
		

		<div class="block">
			<button type="button" name="save">Сохранить изменения</button>
			<div class="clear"></div>
		</div>
		
	</form>
	
</div>
<?php echo '
	<script type="text/javascript">
		
		/* gzip compression checkbox change event */
		$("input[name=\'gzip\']").change(function(){
			if( $(this).is(":checked") ){
				$("input[name=\'gzip_level\']").removeAttr("disabled").trigger("refresh");
				$(".field label.gzip_level").removeClass("disabled");
			}else{
				$("input[name=\'gzip_level\']").attr("disabled", "disabled").trigger("refresh");
				$(".field label.gzip_level").addClass("disabled");
			}
		});
		
		
		
		if( $(".panel_settings input[ismyip]").length>0 ){
			$(".panel_settings button[name=\'add_my_ip\']").attr("disabled", "disabled");
		}
		
		/* input exception ip event */
		$(".inner").on("change", ".panel_settings input[name=\'exception_ip[]\']", function(){
			var myipV = \''; ?>
<?php echo $this->_tpl_vars['myIpV']; ?>
<?php echo '\';

			var valIp = \'\';
			var tmpArray = [];
			var newArray = [];
			
			tmpArray = $(this).val().split(\'.\');
			$.each(tmpArray, function(index, value){
				if( value.length==1 ){
					newArray.push( \'00\' + value );
				}else if( value.length==2 ){
					newArray.push( \'0\' + value );
				}else{
					newArray.push( value );
				}
			});

			valIp = ( ( newArray instanceof Array ) ? newArray.join ( \'.\' ) : newArray );
			
			
			if( myipV==valIp ){
				$(this).attr("ismyip", "");
				$(".panel_settings button[name=\'add_my_ip\']").attr("disabled", "disabled");
			}else if( $(this).is("[ismyip]") ){
				$(this).removeAttr("ismyip");
				$(".panel_settings button[name=\'add_my_ip\']").removeAttr("disabled");
			}
		});
		
		/* add ip button event */
		$(".panel_settings button[name=\'add_ip\']").click(function(e){
			e.preventDefault();
			var field = $("<div>", {class:"field"}),
				input = $("<input>", {type:"text", name:"exception_ip[]"}),
				delBtn = $("<a>", {href:"#", class:"del_ip"});
			field.append( input ).append( delBtn );
			$(".panel_settings .ips").append( field );
			field.hide().slideDown(200);
		});
		
		/* add my ip button event */
		$(".panel_settings button[name=\'add_my_ip\']").click(function(e){
			e.preventDefault();
			var myIp = $(this).attr("data-ip"),
				field = $("<div>", {class:"field"}),
				input = $("<input>", {type:"text", name:"exception_ip[]", ismyip:"", value: myIp}),
				delBtn = $("<a>", {href:"#", class:"del_ip"});
			field.append( input ).append( delBtn );
			$(".panel_settings .ips").append( field );
			field.hide().slideDown(200);
			$(this).attr("disabled", "disabled");
		});
		
		/* del ip button event */
		$(".inner").on("click", ".panel_settings a.del_ip", function(e){
			e.preventDefault();
			$(this).parent(".field").slideUp(200, function(){
				$(this).remove();
				if( $(".panel_settings input[ismyip]").length==0 ){
					$(".panel_settings button[name=\'add_my_ip\'][disabled]").removeAttr("disabled");
				}
			});
		});
		
		/* add ip button event */
		$(".panel_settings button[name=\'save\']").click(function(e){
			e.preventDefault();
			admin.block();
			var data = $(".panel_settings form[name=\'settings\']").serialize();

			admin.ajax(\'settings\', data, function(){
				admin.reloadPanel();
			});
		});	

		/* recreate robots.txt button event */
		$(".panel_settings button[name=\'recreate_robots_txt\']").click(function(e){
			e.preventDefault();
			admin.block();
			admin.ajax(\'settings\', {action:"recreateRobotsTxt"}, function(){
				admin.reloadPanel();
			});
		});
		
		/* recreate sitemap.xml button event */
		$(".panel_settings button[name=\'recreate_sitemap_xml\']").click(function(e){
			e.preventDefault();
			admin.block();
			admin.ajax(\'settings\', {action:"createSitemapXml"}, function(){
				admin.reloadPanel();
			});
		});
		

		$(".inner").on("keydown", "form[name=\'settings\'] input[type=\'text\']", function(e){
			if(e.keyCode == 13){
				e.preventDefault();
				$("form[name=\'settings\'] button[name=\'save\']").click();
			}
		});	

	</script>
'; ?>
