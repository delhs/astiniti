<?php /* Smarty version 2.6.27, created on 2014-01-24 20:29:47
         compiled from orders_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'orders_list.tpl', 22, false),)), $this); ?>
<div class="orders_list">
	<p>Список заказов</p>

	<?php if (isset ( $this->_tpl_vars['ordersArray'] )): ?>
		<div class="block">
			<table cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td>Статус</td>
						<td>ID заказа</td>
						<td>Дата и время&nbsp;заказа</td>
						<td>Сумма</td>
						<td>Доставка</td>
						<td>Заказчик</td>
						<td>Телефон заказчика</td>
						<td>&nbsp;</td>
					</tr>
				</thead>
				<tbody>
					<?php $_from = $this->_tpl_vars['ordersArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['order']):
?>
					<tr data-id="<?php echo $this->_tpl_vars['order']['id']; ?>
">
						<td><?php $_from = $this->_tpl_vars['orderStatuses']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['status'] => $this->_tpl_vars['statusText']):
?><?php if ($this->_tpl_vars['status'] == $this->_tpl_vars['order']['order_status']): ?><span class="status_<?php echo $this->_tpl_vars['status']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['statusText'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span><?php endif; ?><?php endforeach; endif; unset($_from); ?></td>				
						<td><?php echo $this->_tpl_vars['order']['id']; ?>
</td>				
						<td><?php echo $this->_tpl_vars['order']['order_date']; ?>
&nbsp;г. <span class="order_time"><?php echo $this->_tpl_vars['order']['order_time']; ?>
</span></td>				
						<td><?php echo $this->_tpl_vars['order']['order_cost']; ?>
</td>				
						<td><?php if ($this->_tpl_vars['order']['order_delivery_self'] == '1'): ?>Не требуется<?php else: ?>Требуется<?php endif; ?></td>				
						<td><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['user_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>			
						<td><?php echo $this->_tpl_vars['order']['user_phone']; ?>
</td>
						<td class="remove"><span class="icon reddelete" title="Удалить заказ"></span></td>			
					</tr>
					<?php endforeach; endif; unset($_from); ?>
				</tbody>
			</table>
		</div>

		<div class="block">
				
			<select class="nav" name="countonpage" title="Количество результатов, выводимых на страницу">
				<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['start'] = (int)10;
$this->_sections['i']['step'] = ((int)10) == 0 ? 1 : (int)10;
$this->_sections['i']['loop'] = is_array($_loop=101) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
if ($this->_sections['i']['start'] < 0)
    $this->_sections['i']['start'] = max($this->_sections['i']['step'] > 0 ? 0 : -1, $this->_sections['i']['loop'] + $this->_sections['i']['start']);
else
    $this->_sections['i']['start'] = min($this->_sections['i']['start'], $this->_sections['i']['step'] > 0 ? $this->_sections['i']['loop'] : $this->_sections['i']['loop']-1);
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = min(ceil(($this->_sections['i']['step'] > 0 ? $this->_sections['i']['loop'] - $this->_sections['i']['start'] : $this->_sections['i']['start']+1)/abs($this->_sections['i']['step'])), $this->_sections['i']['max']);
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?> 
				<option <?php if ($this->_tpl_vars['countonpage'] == $this->_sections['i']['index']): ?>selected="selected"<?php endif; ?> value="<?php echo $this->_sections['i']['index']; ?>
"><?php echo $this->_sections['i']['index']; ?>
</	option>
				<?php endfor; endif; ?>
			</select>
			
			<?php if (isset ( $this->_tpl_vars['navArray'] )): ?>
				<ul class="nav">
				<?php $_from = $this->_tpl_vars['navArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
					<li>
						<a href="#" data-num="<?php echo $this->_tpl_vars['item']['num']; ?>
" class="<?php echo $this->_tpl_vars['item']['class']; ?>
" title="<?php echo $this->_tpl_vars['item']['title']; ?>
"><?php echo $this->_tpl_vars['item']['text']; ?>
</a>
					</li>
				<?php endforeach; endif; unset($_from); ?>
				</ul>
			<?php endif; ?>
			<div class="clear"></div>
		</div>

	<?php else: ?>
		<div class="block"><p>Заказов не найдено</p></div>	
	<?php endif; ?>
	

</div>
<?php echo '<script type="text/javascript">catalog.ordersListTplInit();</script>'; ?>