<?php /* Smarty version 2.6.27, created on 2014-02-10 20:44:51
         compiled from edit_cat.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'edit_cat.tpl', 3, false),)), $this); ?>
<div class="plug_catalog">
	<form name="plug_catalog_edit_cat">
		<p>Редактирование категории &laquo;<?php echo ((is_array($_tmp=$this->_tpl_vars['cat']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&raquo;</p>
		
		<div class="block">
	
			<input type="hidden" name="pid" value="<?php echo $this->_tpl_vars['cat']['pid']; ?>
" />
			
			<div class="field">
				<label>Название категории<span class="hlp" title="Название категории, которое будет отбражаться в списке категорий"></span></label>
				<div class="padding">
					<input type="text" name="name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['cat']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
				</div>
			</div>
	
			<div class="field">
				<label>Линк категории</label>
				<div class="padding">
					<input type="text" name="link" value="<?php echo $this->_tpl_vars['cat']['link']; ?>
"/>
				</div>
			</div>
			
			<div class="field">
				<label>Не доступна<span class="hlp" title="Если отмечен этот пункт, то при попытке перейти в данную категорию будет сгенерирована ошибка 404, которая означает, что такой категории не существует. Помимо этого, категория будет скрыта из списка категорий"></span></label>
				<div class="padding">
					<input type="checkbox" name="off" value="1" <?php if ($this->_tpl_vars['cat']['off'] == '1'): ?>checked="checked"<?php endif; ?>/>
				</div>
			</div>	
			
			<div class="field">
				<label>Скрыть из списка категорий<span class="hlp" title="Если отмечен этот пункт, то категория будет скрыта из списка категорий, но будет доступна при обращении к ней"></span></label>
				<div class="padding">
					<input type="checkbox" name="hide" value="1" <?php if ($this->_tpl_vars['cat']['hide'] == '1'): ?>checked="checked"<?php endif; ?>/>
				</div>
			</div>	
			
		</div>
	
		<div class="block">
			<div class="field">
				<p>Изображение категории<br/><i>Для загрузки принимаются файлы изображений(gif, png, jpg) с соотношением сторон 1:1. Рекомендуемый размер <?php echo $this->_tpl_vars['categoryLogosSizer'][0][0]; ?>
х<?php echo $this->_tpl_vars['categoryLogosSizer'][0][1]; ?>
</i></p>
				<div class="cat_logo<?php if (isset ( $this->_tpl_vars['cat']['full_logo_src'] )): ?> exist<?php endif; ?>"><img src="<?php if (isset ( $this->_tpl_vars['cat']['full_logo_src'] )): ?><?php echo $this->_tpl_vars['cat']['full_logo_src']; ?>
<?php endif; ?>" /></div>
				<button name="removeCatLogo" <?php if (! isset ( $this->_tpl_vars['cat']['full_logo_src'] )): ?>class="hidden"<?php endif; ?>>Удалить</button>
				<input type="file" id="cat_logo" name="cat_logo" />
			</div>
		</div>

		<div class="block">
			<button type="button" name="saveEditedCat">Сохранить</button>
			<div class="clear"></div>
		</div>
		
	</form>

</div>

<script type="text/javascript"><?php echo 'catalog.editCatTplInit();'; ?>
</script>