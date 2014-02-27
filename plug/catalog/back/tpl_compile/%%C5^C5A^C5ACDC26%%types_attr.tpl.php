<?php /* Smarty version 2.6.27, created on 2014-01-20 12:10:47
         compiled from types_attr.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'types_attr.tpl', 9, false),)), $this); ?>
<div class="catalog_types_attr">
	<form name="plug_catalog_types_attr">
		<div class="block">
			<p>Атрибуты типов</p>
		
			<?php if (isset ( $this->_tpl_vars['typesArray'] )): ?>
				<?php $_from = $this->_tpl_vars['typesArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type']):
?>
				<div class="spoiler">
					<div class="spoiler-head"><?php echo ((is_array($_tmp=$this->_tpl_vars['type']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div>
					<div class="spoiler-body">
						<div class="block">
							<div class="field">
								<table type-id="<?php echo $this->_tpl_vars['type']['id']; ?>
" cellpadding="0" cellspacing="0">
									<thead>
										<tr>
											<td>Название</td>
											<td>Единицы измерения</td>
											<td class="small">Отображать у товаров</td>
											<td class="small">Отображать в фильтре</td>
											<td class="small">&nbsp;</td>
										</tr>
									</thead>
									<tbody>
									<?php $_from = $this->_tpl_vars['type']['attributes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['attribute']):
?>
										<tr data-attr="<?php echo $this->_tpl_vars['attribute']['id']; ?>
" data-type="<?php echo $this->_tpl_vars['type']['id']; ?>
">
											<td>
												<input type="text" name="name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['attribute']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
											</td>
											<td>
												<input type="text" name="units" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['attribute']['units'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
											</td>
											<td class="in_list">
												<span class="icon yellowcheck <?php if ($this->_tpl_vars['attribute']['in_list'] == '1'): ?>on<?php endif; ?>"></span>
											</td>
											<td class="in_filter">
												<span class="icon greencheck <?php if ($this->_tpl_vars['attribute']['in_filter'] == '1'): ?>on<?php endif; ?>"></span>
											</td>
											<td class="delete">
												<span class="icon reddelete hover"></span>
											</td>
										</tr>	
										
									<?php endforeach; endif; unset($_from); ?>
									</tbody>
								</table>
							</div>
						</div>
						
						<div class="block">
							<button type="button" type-id="<?php echo $this->_tpl_vars['type']['id']; ?>
" name="save_type">Сохранить атрибуты типа</button>
							<button type="button" type-id="<?php echo $this->_tpl_vars['type']['id']; ?>
" name="add_type">Добавить атрибут</button>
							<div class="clear"></div>
						</div>
					</div>
				</div>
				<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>	
		</div>
	</form>
</div>
<script type="text/javascript"><?php echo 'catalog.itemTypesAttrTplInit();'; ?>
</script>