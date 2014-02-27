<?php /* Smarty version 2.6.27, created on 2014-02-11 01:49:26
         compiled from index_items_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'index_items_list.tpl', 11, false),)), $this); ?>
<div class="form">
	<div class="block">
		<p>Товары на главной странице</p>
		<div class="field">
			<a href="#" class="add_items">Добавить товары</a>
		</div>
		<ul class="index_list">
			<?php if (isset ( $this->_tpl_vars['itemsArray'] )): ?>
				<?php $_from = $this->_tpl_vars['itemsArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
					<li>
						<a href="#" title="Перейти в каталог и открыть страницу товара" item-id="<?php echo $this->_tpl_vars['item']['id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
						<a href="#" class="delete" title="Удалить из списка"></a>
					</li>
				<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
		</ul>
	</div>

	<div class="block">
		<button type="button" name="save">Сохранить</button>
		<div class="clear"></div>
	</div>
</div>

<?php echo '<script type="text/javascript">modCatalogitems.indexItemsListTplInit();</script>'; ?>