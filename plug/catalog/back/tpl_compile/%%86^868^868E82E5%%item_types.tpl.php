<?php /* Smarty version 2.6.27, created on 2014-01-20 12:09:53
         compiled from item_types.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'item_types.tpl', 12, false),)), $this); ?>
<div class="items_types">
	
	<p>Типы товаров</p>
	
	<form name="item_types">
		<div class="block">
				<p>Список типов</p>
			<?php if (isset ( $this->_tpl_vars['typesArray'] )): ?>
				<?php $_from = $this->_tpl_vars['typesArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['types']):
?>
				<div class="field">
					<div class="nopadding">
						<input type="text" data_t="type" name="<?php echo $this->_tpl_vars['types']['atr_name']; ?>
" data-id="<?php echo $this->_tpl_vars['types']['id']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['types']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /><a href="#" title="Удалить тип товара" class="delete"></a>
					</div>
				</div>
				<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
			
		</div>
		
		<div class="block">
			<button type="button" name="save">Сохранить изменения</button>
			<button type="button" name="add_item_type">Добавить новый тип</button>
			<div class="clear"></div>
		</div>
	</form>
	
</div>
<?php echo '<script type="text/javascript">catalog.itemTypesTplInit();</script>'; ?>