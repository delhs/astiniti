<?php /* Smarty version 2.6.27, created on 2014-01-20 12:10:55
         compiled from types_attr_vals.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'types_attr_vals.tpl', 11, false),)), $this); ?>
<div class="types_attr_vals">
	<form name="plug_catalog_types_attr_vals">
		<p>Значения атрибутов
			<br/><i>Чтобы добавить новый тип, перейдите в раздел <a class="goto_item_types" href="#">Список типов</a></i>
			<br/><i>Чтобы добавить типу атрибут, перейдите в раздел <a class="goto_types_attr" href="#">Атрибуты типов</a></i>
		</p>
		<div class="block">
			<?php if (isset ( $this->_tpl_vars['typesArray'] )): ?>
				<?php $_from = $this->_tpl_vars['typesArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type']):
?>
				<div class="spoiler">
					<div class="spoiler-head">Тип&nbsp;&ndash;&nbsp;&laquo;<?php echo ((is_array($_tmp=$this->_tpl_vars['type']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&raquo;</div>
					<div class="spoiler-body">
						<div class="block" data-type="<?php echo $this->_tpl_vars['type']['id']; ?>
">
							<div class="field">
								<?php if (isset ( $this->_tpl_vars['type']['attributes'] )): ?>
								<?php $_from = $this->_tpl_vars['type']['attributes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['attribute']):
?>
								<div class="spoiler">
								<div class="spoiler-head">Атрибут&nbsp;&ndash;&nbsp;&laquo;<?php echo ((is_array($_tmp=$this->_tpl_vars['attribute']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&raquo;<?php if ($this->_tpl_vars['attribute']['units'] != ''): ?>&nbsp;(<?php echo ((is_array($_tmp=$this->_tpl_vars['attribute']['units'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
)<?php endif; ?></div>
									<div class="spoiler-body">
										<div class="block" data-attr-id="<?php echo $this->_tpl_vars['attribute']['id']; ?>
">
											<p>Возможные значения:</p>
											<?php $_from = $this->_tpl_vars['attribute']['values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['valueId'] => $this->_tpl_vars['value']):
?>
											<div class="field">
												<div class="nopadding">
													<input type="text" name="name" data-value="<?php echo $this->_tpl_vars['valueId']; ?>
" value="<?php echo $this->_tpl_vars['value']; ?>
" /><a href="#" title="Удалить значение атрибута" class="delete"></a>
												</div>
											</div>
											<?php endforeach; endif; unset($_from); ?>
										</div>
										
										<div class="block">
											<button type="button" type-id="<?php echo $this->_tpl_vars['type']['id']; ?>
" attr-id="<?php echo $this->_tpl_vars['attribute']['id']; ?>
" name="save_attr_vals">Сохранить значения для данного атрибута</button>
											<button type="button" type-id="<?php echo $this->_tpl_vars['type']['id']; ?>
" attr-id="<?php echo $this->_tpl_vars['attribute']['id']; ?>
" name="add_att_val">Добавить значение</button>
											<div class="clear"></div>
										</div>

									</div>
								</div>
								<?php endforeach; endif; unset($_from); ?>
								<?php else: ?>
									<p>У данного типа нет ни одного атрибута. Прежде чем добавить значение, необходимо создать хотя бы один атрибут. Сделать это можно перейдя в раздел <a class="goto_types_attr" href="#">Атрибуты типов</a></p>
								<?php endif; ?>
							</div>
						</div>
						

					</div>
				</div>
				<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>	
		</div>
	</form>
</div>
<script type="text/javascript"><?php echo 'catalog.editTypesAttrValsInit();'; ?>
</script>