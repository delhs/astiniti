<?php /* Smarty version 2.6.27, created on 2014-02-09 12:58:43
         compiled from brands_list.tpl */ ?>
<div class="brands_list">
	
	<?php if (! isset ( $this->_tpl_vars['empty'] )): ?>
	<p>Список брендов</p>

	<p><i>Чтобы изменить порядок следования брендов, просто перемещайте их внутри списка между собой ухватив левой кнопкой мыши</i></p>
	<?php else: ?>
	<p>Список брендов пуст</p>
	<?php endif; ?>

	<?php if (isset ( $this->_tpl_vars['brandsArray'] )): ?>
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
</option>
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
	
	<div class="block">
		<table cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<td>Изображение</td>
					<td>ID&nbsp;бренда</td>
					<td>ID&nbsp;бренда&nbsp;в&nbsp;1С</td>
					<td>Линк&nbsp;бренда</td>
					<td>Название</td>
					<td class="small">Скрыт</td>
					<td class="small">Не доступен</td>
					<td class="small">&nbsp;</td>
				</tr>
			</thead>
			<tbody>
				<?php $_from = $this->_tpl_vars['brandsArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['brand']):
?>
				<tr data-id="<?php echo $this->_tpl_vars['brand']['id']; ?>
">
					<td><?php if (isset ( $this->_tpl_vars['brand']['full_logo_src'] )): ?><img src="<?php echo $this->_tpl_vars['brand']['full_logo_src']; ?>
" /><?php else: ?><div class="no_photo"></div><?php endif; ?></td>
					<td><?php echo $this->_tpl_vars['brand']['id']; ?>
</td>
					<td><?php echo $this->_tpl_vars['brand']['external_id']; ?>
</td>
					<td><?php echo $this->_tpl_vars['brand']['link']; ?>
</td>
					<td><?php echo $this->_tpl_vars['brand']['name']; ?>
</td>
					<td data-action="hide_in_list"><span class="icon redcheck<?php if ($this->_tpl_vars['brand']['hide_in_list'] == 1): ?> on<?php endif; ?>"></span></td>
					<td data-action="disabled"><span class="icon redcheck<?php if ($this->_tpl_vars['brand']['disabled'] == 1): ?> on<?php endif; ?>"></span></td>	
					<td data-action="delete"><span class="icon reddelete"></span></td>	
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
</option>
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
	
	<?php endif; ?>
	

	
	
</div>

<script type="text/javascript">catalog.brandsListTplInit();</script>