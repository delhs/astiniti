<?php /* Smarty version 2.6.27, created on 2014-02-09 12:53:54
         compiled from items_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'items_list.tpl', 11, false),)), $this); ?>
<div class="items_list">
	
	<div class="block filter">
		
		<?php if (isset ( $this->_tpl_vars['catList'] )): ?>
		<div class="field">	
			<label>Фильтр по категории:</label>
			<div class="padding">
				<select name="cat_filter" data-type="select_filter">
					<option value="0">--Все категории--</option>
					<?php $_from = $this->_tpl_vars['catList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cat']):
?><option value="<?php echo $this->_tpl_vars['cat']['id']; ?>
"<?php if (isset ( $this->_tpl_vars['cat']['active'] )): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['cat']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option><?php endforeach; endif; unset($_from); ?>
				</select>
			</div>
		</div>
		<?php endif; ?>

		<?php if (isset ( $this->_tpl_vars['brandsList'] )): ?>
		<div class="field">	
			<label>Фильтр по бренду:</label>
			<div class="padding">
				<select name="brands_filter" data-type="select_filter">
					<option value="0">--Все бренды--</option>
					<?php $_from = $this->_tpl_vars['brandsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['brand']):
?><option value="<?php echo $this->_tpl_vars['brand']['id']; ?>
"<?php if (isset ( $this->_tpl_vars['brand']['active'] )): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['brand']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option><?php endforeach; endif; unset($_from); ?>
				</select>
			</div>
		</div>
		<?php endif; ?>

		<div class="field">
			<label>Фильтр по влагам:</label>
			<div class="padding filter_checkboxes">
				<label for="filter_is_all">Все</label><input <?php if (isset ( $this->_tpl_vars['filterArray']['filter_is'] ) && $this->_tpl_vars['filterArray']['filter_is'] == 'is_all'): ?>checked="checked"<?php endif; ?> type="radio" id="filter_is_all" checked="checked" name="filter_is" value="is_all" data-type="check_filter" />
				<label for="filter_is_new_filter">Новинки</label><input <?php if (isset ( $this->_tpl_vars['filterArray']['filter_is'] ) && $this->_tpl_vars['filterArray']['filter_is'] == 'is_new_filter'): ?>checked="checked"<?php endif; ?> type="radio" id="filter_is_new_filter" name="filter_is" value="is_new_filter" data-type="check_filter" />
				<label for="filter_is_sale_filter">Распродажные</label><input <?php if (isset ( $this->_tpl_vars['filterArray']['filter_is'] ) && $this->_tpl_vars['filterArray']['filter_is'] == 'is_sale_filter'): ?>checked="checked"<?php endif; ?> type="radio" id="filter_is_sale_filter" name="filter_is" value="is_sale_filter" data-type="check_filter" />
				<label for="filter_is_best_filter">Лидеры продаж</label><input <?php if (isset ( $this->_tpl_vars['filterArray']['filter_is'] ) && $this->_tpl_vars['filterArray']['filter_is'] == 'is_best_filter'): ?>checked="checked"<?php endif; ?> type="radio" id="filter_is_best_filter" name="filter_is" value="is_best_filter" data-type="check_filter" />
				<label for="filter_is_markdown_filter">Уценка</label><input <?php if (isset ( $this->_tpl_vars['filterArray']['filter_is'] ) && $this->_tpl_vars['filterArray']['filter_is'] == 'is_markdown_filter'): ?>checked="checked"<?php endif; ?> type="radio" id="filter_is_markdown_filter" name="filter_is" value="is_markdown_filter" data-type="check_filter" />
			</div>

		</div>

		<div class="field">
			<label>Фильтр по видимости:</label>
			<div class="padding filter_checkboxes">
				<label for="in_stock_filter">В наличии</label><input <?php if (isset ( $this->_tpl_vars['filterArray']['in_stock_filter'] )): ?>checked="checked"<?php endif; ?> type="checkbox" id="in_stock_filter" name="in_stock_filter" value="1" data-type="check_filter" />
				<label for="hide_in_list_filter">Скрытые</label><input <?php if (isset ( $this->_tpl_vars['filterArray']['hide_in_list_filter'] )): ?>checked="checked"<?php endif; ?> type="checkbox" id="hide_in_list_filter" name="hide_in_list_filter" value="1" data-type="check_filter" />
				<label for="disabled_filter">Не доступные</label><input <?php if (isset ( $this->_tpl_vars['filterArray']['disabled_filter'] )): ?>checked="checked"<?php endif; ?> type="checkbox" id="disabled_filter" name="disabled_filter" value="1" data-type="check_filter" />
			</div>
		</div>		

	</div>


	<?php if (isset ( $this->_tpl_vars['itemsArray'] )): ?>
	<p>Список товаров</p>

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
					<td>ID</td>
					<td>ID&nbsp;в&nbsp;1С</td>
					<td>Артикул</td>
					<td>Создан/Обновлен</td>
					<td>Название</td>
					<td>Цена</td>
					<td>Категория</td>
					<td>Бренд</td>
					<td class="small">В наличии</td>
					<td class="small">Распродажа</td>
					<td class="small">Новинка</td>
					<td class="small">Лучший товар</td>
					<td class="small">Уценка</td>
					<td class="small">Скрыт</td>
					<td class="small">Не доступен</td>
					<td class="small">&nbsp;</td>
				</tr>
			</thead>
			<tbody>
				<?php $_from = $this->_tpl_vars['itemsArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
				<tr data-id="<?php echo $this->_tpl_vars['item']['id']; ?>
">
					<td><?php if (isset ( $this->_tpl_vars['item']['full_logo_src'] )): ?><img src="<?php echo $this->_tpl_vars['item']['full_logo_src']; ?>
" /><?php else: ?><div class="no_photo"></div><?php endif; ?></td>
					<td><?php echo $this->_tpl_vars['item']['id']; ?>
</td>
					<td><?php echo $this->_tpl_vars['item']['external_id']; ?>
</td>
					<td><?php echo $this->_tpl_vars['item']['articul']; ?>
</td>
					<td><?php echo $this->_tpl_vars['item']['create_date']; ?>
<br/><?php echo $this->_tpl_vars['item']['update_date']; ?>
</td>
					<td><?php echo $this->_tpl_vars['item']['name']; ?>
</td>
					<td><?php echo $this->_tpl_vars['item']['price']; ?>
</td>
					<td><?php echo $this->_tpl_vars['item']['category_name']; ?>
</td>
					<td><?php echo $this->_tpl_vars['item']['brand_name']; ?>
</td>
					<td data-action="in_stock"><span class="icon yellowcheck<?php if ($this->_tpl_vars['item']['in_stock'] == 1): ?> on<?php endif; ?>"></span></td>	
					<td data-action="is_sale"><span class="icon greencheck popular<?php if ($this->_tpl_vars['item']['is_sale'] == 1): ?> on<?php endif; ?>"></span></td>					
					<td data-action="is_new"><span class="icon greencheck popular<?php if ($this->_tpl_vars['item']['is_new'] == 1): ?> on<?php endif; ?>"></span></td>					
					<td data-action="is_best"><span class="icon greencheck popular<?php if ($this->_tpl_vars['item']['is_best'] == 1): ?> on<?php endif; ?>"></span></td>	
					<td data-action="is_markdown"><span class="icon greencheck popular<?php if ($this->_tpl_vars['item']['is_markdown'] == 1): ?> on<?php endif; ?>"></span></td>	
					<td data-action="hide_in_list"><span class="icon redcheck<?php if ($this->_tpl_vars['item']['hide_in_list'] == 1): ?> on<?php endif; ?>"></span></td>					
					<td data-action="disabled"><span class="icon redcheck<?php if ($this->_tpl_vars['item']['disabled'] == 1): ?> on<?php endif; ?>"></span></td>					
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
	<?php else: ?>
	<div class="block"><p>Товаров не найдено</p></div>
	<?php endif; ?>
	
	
</div>

<script type="text/javascript">catalog.itemsListTplInit();</script>