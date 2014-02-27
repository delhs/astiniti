<?php /* Smarty version 2.6.27, created on 2014-01-24 17:59:59
         compiled from order_details.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'order_details.tpl', 19, false),)), $this); ?>
<div class="order_details form">
	<span>Карточка заказа. ID <?php echo $this->_tpl_vars['order']['id']; ?>
</span>

	<div class="block">

		<div class="field">
			<label>Дата и время заказа:</label><i><?php echo $this->_tpl_vars['order']['order_date']; ?>
&nbsp;г. <span class="order_time"><?php echo $this->_tpl_vars['order']['order_time']; ?>
</span></i>
		</div>

		<div class="field">
			<label>Сумма заказа:</label><i><?php echo $this->_tpl_vars['order']['order_cost']; ?>
&nbsp;<?php echo $this->_tpl_vars['order']['currency']; ?>
</i>
		</div>

		<div class="field">
			<label>Всего товаров:</label><i><b><?php echo $this->_tpl_vars['order']['items_count']; ?>
 шт.</b>&nbsp;&nbsp;&nbsp;<a id="get_order_items_data" href="#">Открыть перечень</a></i>
		</div>

		<div class="field">
			<label>Заказчик и адрес доставки:</label><i><a id="get_order_user_data" href="#"><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['user_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></i>
		</div>

		<div class="field">
			<label>Требуется доставка:</label><i><input type="checkbox" name="order_delivery_self" value="1" <?php if ($this->_tpl_vars['order']['order_delivery_self'] == '0'): ?>checked="checked"<?php endif; ?>/></i>
		</div>

		<div class="field">
			<label>Статус:</label>
			<div class="padding">
				<select name="order_status">
					<?php $_from = $this->_tpl_vars['orderStatuses']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['statusId'] => $this->_tpl_vars['statusText']):
?>
						<option value="<?php echo $this->_tpl_vars['statusId']; ?>
" <?php if ($this->_tpl_vars['order']['order_status'] == $this->_tpl_vars['statusId']): ?>selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['statusText'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
					<?php endforeach; endif; unset($_from); ?>

				</select>
			</div>
		</div>

		<div class="field">
			<label>Комментарий заказчика:</label><textarea readonly="readonly" name="comment"><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['order_comment'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea>
		</div>

		<div class="field">
			<label>Комментарий менеджера:</label><textarea name="manager_comment"><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['order_manager_comment'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea>
		</div>

	</div>


	<div class="block">
		<button type="button" name="save_order_details">Сохранить и закрыть</button>
		<div class="clear"></div>
	</div>

</div>