<?php /* Smarty version 2.6.27, created on 2014-02-12 21:18:00
         compiled from catalog_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'catalog_settings.tpl', 8, false),)), $this); ?>
<form name="catalog_settings_main">
	<div class="block">
		<p>Валюта</p>
		
		<div class="field">
			<label>Сокращение в 2-3 символа:</label>
			<div class="padding">
				<input type="text" name="currency_quick" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['catalogSettings']['currency_quick'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"/>
			</div>
		</div>

		<div class="field">
			<label>Сокращение в 1 символ:</label>
			<div class="padding">
				<input type="text" name="currency_symbol" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['catalogSettings']['currency_symbol'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"/>
			</div>
		</div>
		
		<div class="field">
			<label>Ед. число Именительный падеж (один...):</label>
			<div class="padding">
				<input type="text" name="currency_nom" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['catalogSettings']['currency_nom'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"/>
			</div>
		</div>
		
		<div class="field">
			<label>Мн. число Именительный падеж (два...):</label>
			<div class="padding">
				<input type="text" name="currency_acc" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['catalogSettings']['currency_acc'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"/>
			</div>
		</div>
		
		<div class="field">
			<label>Мн. число Родительный падеж (пять...):</label>
			<div class="padding">
				<input type="text" name="currency_nomp" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['catalogSettings']['currency_nomp'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"/>
			</div>
		</div>

	</div>

	<div class="block">
		<p>Товары</p>

		<div class="field">
			<label>Ед. число Именительный падеж (один...):</label>
			<div class="padding">
				<input type="text" name="item_nom" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['catalogSettings']['item_nom'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"/>
			</div>
		</div>
		
		<div class="field">
			<label>Мн. число Иминительный падеж (два...):</label>
			<div class="padding">
				<input type="text" name="item_acc" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['catalogSettings']['item_acc'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"/>
			</div>
		</div>

		<div class="field">
			<label>Мн. число Родительный падеж (пять...):</label>
			<div class="padding">
				<input type="text" name="item_nomp" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['catalogSettings']['item_nomp'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"/>
			</div>
		</div>

	</div>

	<div class="block">
		<p>Комментарии</p>

		<div class="field">
			<label>Показывать комментарии</label>
			<div class="padding">
				<input type="radio" name="show_comments" value="1" <?php if ($this->_tpl_vars['catalogSettings']['show_comments'] == '1'): ?>checked="checked"<?php endif; ?>/>
			</div>
		</div>

		<div class="field">
			<label>Не показывать комментарии</label>
			<div class="padding">
				<input type="radio" name="show_comments" value="0" <?php if ($this->_tpl_vars['catalogSettings']['show_comments'] == '0'): ?>checked="checked"<?php endif; ?>/>
			</div>
		</div>

	</div>

	<div class="block">
		<p>Отображение</p>

		<div class="field">
			<label>Страница вывода каталога:</label>
			<div class="padding">
				<select name="catalog_page_id">
					<option value="0">--Не указано--</option>
					<?php $this->assign('level', 0); ?>
					<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_list.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				</select>
			</div>
		</div>

	</div>
			



	<div class="block">
		<button type="button" name="save_settings">Сохранить изменения</button>
		<div class="clear"></div>
	</div>
</form>

<?php echo '<script type="text/javascript">catalog.catalogSettingsTplInit();</script>'; ?>