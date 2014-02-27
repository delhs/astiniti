<?php /* Smarty version 2.6.27, created on 2014-02-05 13:50:26
         compiled from panel.words.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'panel.words.tpl', 16, false),)), $this); ?>
<div class="panel_words">
	<h1>Словари</h1>
	<form name="panel_words" <?php if ($this->_tpl_vars['developer']): ?>class="full" <?php endif; ?>>
		
		<div class="block words">
			
			<p>Словари - это все слова и словосочетания, которые &laquo;вшиты&raquo; в шаблон сайта. Например: номера телефонов и адреса электронной почты, различные надписи и заголовки у форм заказов ( Имя:, Фамилия: ), тексты сообщений об ошибках и успешно принятых заказов.</p>
			
			<span class="desc">Описание<span class="hlp" title="Это описание необходимо для того, чтобы понять в какой части сайта выводится то или иное слово или словосочетание"></span></span>
			<?php if ($this->_tpl_vars['developer']): ?><span class="key">Ключ</span><?php endif; ?>
			<span class="value">Значение<span class="hlp" title="Это именно то слово или словосочетание, которое должно выводиться в назначенном месте сайта"></span></span>
			<div class="clear"></div>
			<?php if ($this->_tpl_vars['wordsArray']): ?>
				<?php $_from = $this->_tpl_vars['wordsArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['word']):
?>
				<div class="field">
					<input type="text" name="word_desc[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['word']['word_desc'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /><!--
					--><input <?php if ($this->_tpl_vars['developer']): ?>type="text"<?php else: ?>type="hidden"<?php endif; ?> name="word_key[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['word']['word_key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /><!--
					--><input type="text" name="word_value[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['word']['word_value'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /><!--
					--><?php if ($this->_tpl_vars['developer']): ?><a href="#" class="del"></a><?php endif; ?>
				</div>
				<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
		</div>
		
		
		<div class="block">
			<button type="button" name="save">Сохранить изменения</button>
			<?php if ($this->_tpl_vars['developer']): ?><button type="button" name="add">Добавить ключ</button><?php endif; ?>
			<div class="clear"></div>
		</div>
		
	</form>
	
</div>

<?php echo '
<script type="text/javascript">
	/* save button event */
	$("form[name=\'panel_words\'] button[name=\'save\']").click(function(e){
		e.preventDefault();
		admin.block();

		var data = $("form[name=\'panel_words\']").serialize();
		
		admin.ajax("words", data, function(){
			admin.reloadPanel();
		});
	});
	
	$(document).unbind(\'keydown\');
	$("form[name=\'panel_words\']").bind(\'keydown\', function(e){
		if(e.keyCode == 13){
			e.preventDefault();
			$("form[name=\'panel_words\'] button[name=\'save\']").click();
		}
	});
</script>
'; ?>



<?php if ($this->_tpl_vars['developer']): ?>
	<?php echo '
		<script type="text/javascript">
			/* add key button event */
			$("form[name=\'panel_words\'] button[name=\'add\']").click(function(e){
				e.preventDefault();
				var	field = $("<div>", {class:"field"}),
					desc = $("<input>", {type:"text", name:"word_desc[]"}),
					key = $("<input>", {type:"text", name:"word_key[]"}),
					value = $("<input>", {type:"text", name:"word_value[]"}),
					delBtn = $("<a>", {href:"#", class:"del"});
				field.append( desc ).append( key ).append( value ).append( delBtn );
				$("form[name=\'panel_words\'] .block.words").append(field);
				field.hide().slideDown(200);	
			});
			
			
			/* delete button event */
			$("form[name=\'panel_words\'] a.del").click(function(e){
				e.preventDefault();
				$(this).parents(".field").slideUp(200, function(){
					$(this).remove();
				});
			});
		</script>
	'; ?>

<?php endif; ?>


