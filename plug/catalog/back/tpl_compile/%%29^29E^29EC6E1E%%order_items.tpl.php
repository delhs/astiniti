<?php /* Smarty version 2.6.27, created on 2014-01-27 15:08:55
         compiled from order_items.tpl */ ?>
<div class="order_items">
	<span>Перечень товаров</span>
	<div class="block">
		<table cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<td>ID в каталоге</td>
					<td>ID в 1С</td>
					<td>Артикул</td>
					<td>Изображение</td>
					<td>Название</td>
					<td>Цена</td>
					<td>Количество</td>
				</tr>
			</thead>
			<tbody>
				<?php $_from = $this->_tpl_vars['itemsArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
				<tr>
					<td><?php echo $this->_tpl_vars['item']['item_id']; ?>
</td>
					<td><?php echo $this->_tpl_vars['item']['item_external_id']; ?>
</td>
					<td><?php echo $this->_tpl_vars['item']['item_articul']; ?>
</td>
					<td>
						<?php if (isset ( $this->_tpl_vars['item']['not_exist'] )): ?>
							<span>Товара больше не существует</span>
						<?php elseif (isset ( $this->_tpl_vars['item']['full_logo_src'] )): ?>
							<img class="photo" width="50" height="50" src="<?php echo $this->_tpl_vars['item']['full_logo_src']; ?>
" />
						<?php else: ?>
							<div class="no_photo"></div>
						<?php endif; ?>
					</td>
					<td><?php echo $this->_tpl_vars['item']['item_name']; ?>
</td>
					<td><?php echo $this->_tpl_vars['item']['item_price']; ?>
</td>
					<td><?php echo $this->_tpl_vars['item']['item_count']; ?>
</td>
				</tr>
				<?php endforeach; endif; unset($_from); ?>
			</tbody>
		</table>
	</div>
</div>