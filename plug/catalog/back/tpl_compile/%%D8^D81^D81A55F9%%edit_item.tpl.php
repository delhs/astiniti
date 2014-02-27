<?php /* Smarty version 2.6.27, created on 2014-02-19 12:23:50
         compiled from edit_item.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'edit_item.tpl', 22, false),)), $this); ?>
<div class="edit_item">
	<form name="plug_catalog_edit_item">
		<p>Редактирование товара</p>
		
		<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['item']['id']; ?>
" />
		<input type="hidden" name="raiting" value="<?php echo $this->_tpl_vars['item']['raiting']; ?>
" />
		

		<div class="block">
			<p>Основные параметры</p>
			
			<div class="field inf">
				<label>ID товара</label>
				<div class="padding">
					<span><?php echo $this->_tpl_vars['item']['id']; ?>
</span>
				</div>
			</div>	
			
			<div class="field inf">
				<label>ID товара в 1С</label>
				<div class="padding">
					<span><?php if ($this->_tpl_vars['item']['external_id'] != ''): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['external_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php else: ?>&ndash;<?php endif; ?></span>
				</div>
			</div>
			
			<p></p>
			
			<div class="field">
				<label>Название товара<span class="hlp" title="Название товара, которое будет отбражаться в списке товаров"></span></label>
				<div class="padding">
					<input type="text" name="name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
				</div>
			</div>
			
			<div class="field">
				<label>Линк товара<span class="hlp" title="Это адрес, по которому данный товар будет доступен. В данный момент полная ссылка на товар выглядит так: &laquo;<?php echo $this->_tpl_vars['fulllink']; ?>
&raquo;, где &laquo;<?php echo $this->_tpl_vars['item']['link']; ?>
&raquo; и есть линк этого товара. Линк товара может содержать только латинские буквы, цифры, символ &laquo;_&raquo; и символ &laquo;-&raquo;"></span></label>
				<div class="padding">
					<input type="text" name="link" value="<?php echo $this->_tpl_vars['item']['link']; ?>
" />
				</div>
			</div>		
			
			<div class="field">
				<label>Артикул</label>
				<div class="padding">
					<input type="text" name="articul" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['articul'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
				</div>
			</div>

			<div class="field">
				<label>Цена</label>
				<div class="padding">
					<input type="text" name="price" value="<?php echo $this->_tpl_vars['item']['price']; ?>
" />
				</div>
			</div>	
			
			<div class="field">
				<label>Старая цена<span class="hlp" title="Это значение используется, если в разделе &laquo;Популярность товара&raquo; отмечен пункт &laquo;Распродажа&raquo;"></span></label>
				<div class="padding">
					<input type="text" name="old_price" value="<?php echo $this->_tpl_vars['item']['old_price']; ?>
" />
				</div>
			</div>				
			
			<div class="field">
				<label>Скидка в процентах<span class="hlp" title="Это значение используется, если в разделе &laquo;Популярность товара&raquo; отмечен пункт &laquo;Распродажа&raquo;"></span></label>
				<div class="padding">
					<input type="text" name="discount" value="<?php echo $this->_tpl_vars['item']['discount']; ?>
" <?php if ($this->_tpl_vars['item']['discount_calc_auto'] == '1'): ?>disabled="disabled"<?php endif; ?> />
				</div>
			</div>	

			<div class="field">
				<label>Расчитывать скидку автоматически<span class="hlp" title=" Если отметить этот пункт, то процент скидки будет расчитываться автоматически руководствуясь старой и новой ценой. Это значение используется, если в разделе &laquo;Популярность товара&raquo; отмечен пункт &laquo;Распродажа&raquo;"></span></label>
				<div class="padding">
					<input type="checkbox" name="discount_calc_auto" value="1" <?php if ($this->_tpl_vars['item']['discount_calc_auto'] == '1'): ?>checked="checked"<?php endif; ?> />
				</div>
			</div>

			<?php if (isset ( $this->_tpl_vars['categoriesArray'] )): ?>
			<div class="field">
				<label>Категория</label>
				<div class="padding">
					<select name="cat_id">
						<option value="0">--Вне категории--</option>
						<?php $_from = $this->_tpl_vars['categoriesArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['catData']):
?>
						<option <?php if ($this->_tpl_vars['item']['cat_id'] == $this->_tpl_vars['catData']['id']): ?>selected="selected"<?php endif; ?> value="<?php echo $this->_tpl_vars['catData']['id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['catData']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
						<?php endforeach; endif; unset($_from); ?>
					</select>
				</div>
			</div>					
			<?php endif; ?>
			
			<?php if (isset ( $this->_tpl_vars['brandsArray'] )): ?>
			<div class="field">
				<label>Бренд</label>
				<div class="padding">
					<select name="brand_id">
						<option value="0">--Не имеет бренда--</option>
						<?php $_from = $this->_tpl_vars['brandsArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['brandData']):
?>
						<option <?php if ($this->_tpl_vars['item']['brand_id'] == $this->_tpl_vars['brandData']['id']): ?>selected="selected"<?php endif; ?> value="<?php echo $this->_tpl_vars['brandData']['id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['brandData']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
						<?php endforeach; endif; unset($_from); ?>
					</select>
				</div>
			</div>					
			<?php endif; ?>					
			
			<?php if (isset ( $this->_tpl_vars['typesArray'] )): ?>
			<div class="field">
				<label>Тип</label>
				<div class="padding">
					<select name="type_id">
						<option value="0">--Не имеет типа--</option>
						<?php $_from = $this->_tpl_vars['typesArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['typeData']):
?>
						<option <?php if ($this->_tpl_vars['item']['type_id'] == $this->_tpl_vars['typeData']['id']): ?>selected="selected"<?php endif; ?> value="<?php echo $this->_tpl_vars['typeData']['id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['typeData']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
						<?php endforeach; endif; unset($_from); ?>
					</select>
				</div>
			</div>	
			<?php endif; ?>
			
			<div class="field">
				<label>Краткое описание</label>
				<div class="padding">
					<textarea name="item_quick_desc"><?php echo $this->_tpl_vars['item']['item_quick_desc']; ?>
</textarea>
				</div>
			</div>	
			
			<div class="field">
				<label>Полное описание</label>
				<div class="padding">
					<textarea name="item_desc"><?php echo $this->_tpl_vars['item']['item_desc']; ?>
</textarea>
				</div>
			</div>			
			
			<div class="field">
				<label>Товар есть в наличии</label>
				<div class="padding">
					<input <?php if ($this->_tpl_vars['item']['in_stock'] == '1'): ?>checked="checked"<?php endif; ?> type="radio" name="in_stock" value="1"/>
				</div>
			</div>	
			
			<div class="field">
				<label>Товар отсутствует</label>
				<div class="padding">
					<input <?php if ($this->_tpl_vars['item']['in_stock'] == '0'): ?>checked="checked"<?php endif; ?> type="radio" name="in_stock" value="0"/>
				</div>
			</div>	
			
			<div class="field">
				<label>Только под закз</label>
				<div class="padding">
					<input <?php if ($this->_tpl_vars['item']['in_stock'] == '2'): ?>checked="checked"<?php endif; ?> type="radio" name="in_stock" value="2"/>
				</div>
			</div>	

			<div class="field">
				<label>Дата и время создания</label>
				<div class="padding">
					<input type="text" name="create_date_time" value="<?php echo $this->_tpl_vars['item']['create_date_time']; ?>
"/>
				</div>
			</div>

			<div class="field">
				<label>Дата и время последнего обновления<span class="hlp" title="Дата и время обновления устанавливается автоматически после нажатия на кнопку &laquo;Сохранить&raquo;, либо после обновления из &laquo;1С&raquo;. Данное значение отправляется в заголовок страницы &laquo;Last-Modified&raquo;(данная информация актуальна для SEO специалистов)"></span></label>
				<div class="padding">
					<span style="margin-top: 7px;display: inline-block;"><?php echo $this->_tpl_vars['item']['update_date_time']; ?>
</span>
				</div>
			</div>

			<div class="field">
				<label>Expires<span class="hlp" title="Днная информация актуальна для SEO специалистов. Заголовок &laquo;Expires&raquo; будет сформирован и отправлен следующим образом: Дата последнего обновления + 1 месяц."></span></label>
				<div class="padding">
					<span style="margin-top: 7px;display: inline-block;"><?php echo $this->_tpl_vars['item']['expires_date_time']; ?>
</span>
				</div>
			</div>

		</div>
		

		<div class="spoiler">
			<div class="spoiler-head -opened">Дополнительные атрибуты товара</div>
			<div class="spoiler-body">

				<div class="block attributes">
					<?php if (isset ( $this->_tpl_vars['itemAttr'] )): ?>
						<?php $_from = $this->_tpl_vars['itemAttr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['attr']):
?>
						<div class="field">
							<label><?php echo ((is_array($_tmp=$this->_tpl_vars['attr']['attribute_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;<?php if ($this->_tpl_vars['attr']['attribute_units'] != ''): ?>(<?php echo $this->_tpl_vars['attr']['attribute_units']; ?>
)<?php endif; ?></label>
															<div class="padding">
									<select data-id="<?php echo $this->_tpl_vars['attr']['attr_id']; ?>
" name="attribute_<?php echo $this->_tpl_vars['attr']['attr_id']; ?>
">
									<option value="0">--Не выбрано--</option>
									<?php $_from = $this->_tpl_vars['attr']['attributesValues']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['attr_id'] => $this->_tpl_vars['attrData']):
?>
										<option <?php if ($this->_tpl_vars['attr']['value_id'] == $this->_tpl_vars['attrData']['id']): ?>selected="selected"<?php endif; ?> value="<?php echo $this->_tpl_vars['attrData']['id']; ?>
"><?php echo $this->_tpl_vars['attrData']['value']; ?>
</option>
									<?php endforeach; endif; unset($_from); ?>
									</select>
								</div>
														
														
														
														
						</div>
						<?php endforeach; endif; unset($_from); ?>
					<?php else: ?>
					<span>Атрибутов нет</span>
					<?php endif; ?>
				</div>
				

			</div>
		</div>		
		
		<div class="spoiler">
			<div class="spoiler-head -opened">Изображения</div>
			<div class="spoiler-body">
			
				<div class="block">
					<div class="field">
						<p>Основное изображение товара<br/><i>Для загрузки принимаются файлы изображений(gif, png, jpg) с соотношением сторон 1:1. Рекомендуемый размер <?php echo $this->_tpl_vars['itemLogosSizer'][0][0]; ?>
х<?php echo $this->_tpl_vars['itemLogosSizer'][0][1]; ?>
</i></p>
						<div class="item_logo<?php if (isset ( $this->_tpl_vars['item']['full_logo_src'] )): ?> exist<?php endif; ?>"><img src="<?php if (isset ( $this->_tpl_vars['item']['full_logo_src'] )): ?><?php echo $this->_tpl_vars['item']['full_logo_src']; ?>
<?php endif; ?>" /></div>
						<button name="removeItemLogo" <?php if (! isset ( $this->_tpl_vars['item']['full_logo_src'] )): ?>class="hidden"<?php endif; ?>>Удалить</button>
						<input type="file" id="item_logo" name="item_logo" />
					</div>
				</div>
				
				
				<div class="block">
					<div class="field">
						<p>Другие изображения товара<br/><i>Для загрузки принимаются файлы изображений(gif, png, jpg) с любым соотношением сторон</i></p>
						<div class="item_other_images">
							<?php $_from = $this->_tpl_vars['item']['other_images']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['imgData']):
?>
								<div>
									<img class="exist" src="<?php echo $this->_tpl_vars['imgData']['filename']; ?>
"/>
									<a href="#" data-id="<?php echo $this->_tpl_vars['imgData']['id']; ?>
" class="remove_other_image exist"></a>
								</div>
							<?php endforeach; endif; unset($_from); ?>
						</div>
						<div class="clear"></div>						
						<input type="file" id="item_other_images" name="item_other_images" />
					</div>
				</div>				
				
			
			</div>
		</div>
		
	
		<div class="spoiler">
			<div class="spoiler-head">Аналоги<?php if (isset ( $this->_tpl_vars['analogsCount'] )): ?>&nbsp;<b class="count">(<?php echo $this->_tpl_vars['analogsCount']; ?>
)</b><?php endif; ?></div>
			<div class="spoiler-body">
			
				<div class="block">
					
					<div class="field">
						<ul class="analogs">
						<?php if (isset ( $this->_tpl_vars['analogs'] )): ?><?php $_from = $this->_tpl_vars['analogs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['analog']):
?>
							<li><a href="#" item-id="<?php echo $this->_tpl_vars['analog']['analog_id']; ?>
"><?php echo $this->_tpl_vars['analog']['name']; ?>
</a><a href="#" class="delete" title="Удалить из списка аналогов"></a></li>
						<?php endforeach; endif; unset($_from); ?><?php endif; ?>
						</ul>
					</div>	
					
					<div class="field">
						<button type="button" name="add_analog">Добавить аналог</button>
						<div class="clear"></div>
					</div>						
				</div>
			
			</div>
		</div>			
		
		<div class="spoiler">
			<div class="spoiler-head">Сопутствующие товары<?php if (isset ( $this->_tpl_vars['accompanyingCount'] )): ?>&nbsp;<b class="count">(<?php echo $this->_tpl_vars['accompanyingCount']; ?>
)</b><?php endif; ?></div>
			<div class="spoiler-body">
			
				<div class="block">
					
					<div class="field">
						<ul class="accompanying">
						<?php if (isset ( $this->_tpl_vars['accompanying'] )): ?><?php $_from = $this->_tpl_vars['accompanying']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['accomp']):
?>
							<li><a href="#" item-id="<?php echo $this->_tpl_vars['accomp']['accompanying_id']; ?>
"><?php echo $this->_tpl_vars['accomp']['name']; ?>
</a><a href="#" class="delete" title="Удалить из списка супутствующих товаров"></a></li>
						<?php endforeach; endif; unset($_from); ?><?php endif; ?>
						</ul>
					</div>	
					
					<div class="field">
						<button type="button" name="add_accomp">Добавить сопутствующий товар</button>
						<div class="clear"></div>
					</div>						
				</div>
			
			</div>
		</div>	
	
	
	
		<div class="spoiler">
			<div class="spoiler-head">Условия доставки и способы оплаты</div>
			<div class="spoiler-body">
				
				<div class="block">
				
					<div class="field">
						<label>Возможность доставки по городу</label>
						<div class="padding">
							<input type="checkbox" name="delivery_city" <?php if ($this->_tpl_vars['item']['delivery_city'] == '1'): ?>checked="checked"<?php endif; ?> value="1"/>
						</div>
					</div>
					
					<div class="field">
						<label>Возможность доставки по области</label>
						<div class="padding">
							<input type="checkbox" name="delivery_region" <?php if ($this->_tpl_vars['item']['delivery_region'] == '1'): ?>checked="checked"<?php endif; ?> value="1"/>
						</div>
					</div>
					
					<div class="field">
						<label>Возможность доставки в другие регионы</label>
						<div class="padding">
							<input type="checkbox" name="delivery_out_region" <?php if ($this->_tpl_vars['item']['delivery_out_region'] == '1'): ?>checked="checked"<?php endif; ?> value="1"/>
						</div>
					</div>
				</div>
				
				<div class="block">
				
					<div class="field">
						<label>Оплата наличными при получении</label>
						<div class="padding">
							<input type="checkbox" name="pay_cash_person" <?php if ($this->_tpl_vars['item']['pay_cash_person'] == '1'): ?>checked="checked"<?php endif; ?> value="1"/>
						</div>
					</div>		
					
					<div class="field">
						<label>Оплата картой при получении</label>
						<div class="padding">
							<input type="checkbox" name="pay_card_person" <?php if ($this->_tpl_vars['item']['pay_card_person'] == '1'): ?>checked="checked"<?php endif; ?> value="1"/>
						</div>
					</div>	
					
					<div class="field">
						<label>Оплата картой на сайте</label>
						<div class="padding">
							<input type="checkbox" name="pay_card_web" <?php if ($this->_tpl_vars['item']['pay_card_web'] == '1'): ?>checked="checked"<?php endif; ?> value="1"/>
						</div>
					</div>
					
					<div class="field">
						<label>Оплата электронными деньгами на сайте</label>
						<div class="padding">
							<input type="checkbox" name="pay_web_money" <?php if ($this->_tpl_vars['item']['pay_web_money'] == '1'): ?>checked="checked"<?php endif; ?> value="1"/>
						</div>
					</div>
					
					<div class="field">
						<label>Безналичный расчет(Юр. лица)</label>
						<div class="padding">
							<input type="checkbox" name="pay_entity" <?php if ($this->_tpl_vars['item']['pay_entity'] == '1'): ?>checked="checked"<?php endif; ?> value="1"/>
						</div>
					</div>

				</div>
				
			</div>
		</div>
	
		<div class="spoiler">
			<div class="spoiler-head">Популярность товара</div>
			<div class="spoiler-body">
				
				<div class="block">
				
					<div class="field">
						<label>Обычный товар</label>
						<div class="padding">
							<input type="radio" name="popular" <?php if ($this->_tpl_vars['item']['is_best'] == '0' && $this->_tpl_vars['item']['is_sale'] == '0' && $this->_tpl_vars['item']['is_new'] == '0' && $this->_tpl_vars['item']['is_markdown'] == '0'): ?>checked="checked"<?php endif; ?> value="null"/>
						</div>
					</div>

					<div class="field">
						<label>Лидер продаж</label>
						<div class="padding">
							<input type="radio" name="popular" <?php if ($this->_tpl_vars['item']['is_best'] == '1'): ?>checked="checked"<?php endif; ?> value="is_best"/>
						</div>
					</div>
					
					<div class="field">
						<label>Распродажа</label>
						<div class="padding">
							<input type="radio" name="popular" <?php if ($this->_tpl_vars['item']['is_sale'] == '1'): ?>checked="checked"<?php endif; ?> value="is_sale"/>
						</div>
					</div>	
					
					<div class="field">
						<label>Новинка</label>
						<div class="padding">
							<input type="radio" name="popular" <?php if ($this->_tpl_vars['item']['is_new'] == '1'): ?>checked="checked"<?php endif; ?> value="is_new"/>
						</div>
					</div>	
					
					<div class="field">
						<label>Уцененный</label>
						<div class="padding">
							<input type="radio" name="popular" <?php if ($this->_tpl_vars['item']['is_markdown'] == '1'): ?>checked="checked"<?php endif; ?> value="is_markdown"/>
						</div>
					</div>
					
					<div class="field">
						<label>Рейтинг</label>
						<div class="padding">
							<a class="raitingnull" title="Установить нулевой рейтинг" href="#"></a>
							<ul class="raiting">
								<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['start'] = (int)1;
$this->_sections['i']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['i']['loop'] = is_array($_loop=6) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
								<li <?php if ($this->_tpl_vars['item']['raiting'] >= $this->_sections['i']['index']): ?>class="set"<?php endif; ?>><a href="#"></a></li>
								<?php endfor; endif; ?>
							</ul>
							<div class="clear"></div>
						</div>
					</div>					
					
				</div>
				
			</div>
		</div>


		<div class="spoiler">
			<div class="spoiler-head">Параметры видимости</div>
			<div class="spoiler-body">
			
				<div class="block">
				
					<div class="field">
						<label>Не доступен<span class="hlp" title="Если отмечен этот пункт, то при попытке перейти на страницу товара, будет сгенерирована ошибка 404, которая означает, что такой страницы не существует. Помимо этого, товар будет скрыт из списка товаров"></span></label>
						<div class="padding">
							<input type="checkbox" name="disabled" value="1" <?php if ($this->_tpl_vars['item']['disabled'] == '1'): ?>checked="checked"<?php endif; ?>/>
						</div>
					</div>		
							
					<div class="field">
						<label>Скрыть из списка товаров<span class="hlp" title="Если отмечен этот пункт, то товар будет скрыт из списка товаров, но будет доступен при обращении к нему"></span></label>
						<div class="padding">
							<input type="checkbox" name="hide_in_list" value="1" <?php if ($this->_tpl_vars['item']['hide_in_list'] == '1'): ?>checked="checked"<?php endif; ?>/>
						</div>
					</div>	
				</div>
			
			</div>
		</div>		
		
		
		<div class="spoiler">
			<div class="spoiler-head">Дополнительные параметры</div>
			<div class="spoiler-body">
				<div class="block">
					<div class="field">
						<label>ID товара в 1C</label>
						<div class="padding">
							<input type="text" name="external_id" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['external_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"/>
						</div>
					</div>
				</div>
			</div>
		</div>		
		

		<div class="spoiler">
			<div class="spoiler-head">Комментарии</div>
			<div class="spoiler-body">
				<div class="block">
					
					<div class="field">
						<a href="#" id="newComment">Написать комментарий</a>
					</div>

					<div class="field">
						<?php if (isset ( $this->_tpl_vars['comments'] )): ?>
							<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "comments_list.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
						<?php else: ?>
							<p>Комментариев нет</p>
						<?php endif; ?>
					</div>

				</div>
			</div>
		</div>


		<div class="spoiler">
			<div class="spoiler-head">Meta теги</div>
			<div class="spoiler-body">
			
				<div class="block">
					<div class="field">
						<label>Мета тег "TITLE"<span class="hlp" title="Это мета тег &laquo;Title&raquo;, текст которого выводится на вкладке страницы в браузере"></span></label>
						<div class="padding">
							<input type="text" name="meta_title" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['meta_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"/>
						</div>
					</div>
					<div class="field">
						<label>Мета тег "KEYWORDS"<span class="hlp" title="Это ключевые слова, которые отражают общую суть того, что представлено на странице"></span></label>
						<div class="padding">
							<textarea class="highlight" name="meta_keywords"><?php echo $this->_tpl_vars['item']['meta_keywords']; ?>
</textarea>
						</div>
					</div>
					
					<div class="field">
						<label>Мета тег "DESCRIPTION"<span class="hlp" title="Это описание страницы, которое видит поисковая система"></span></label>
						<div class="padding">
							<textarea class="highlight" name="meta_description"><?php echo $this->_tpl_vars['item']['meta_description']; ?>
</textarea>
						</div>
					</div>
		
					<div class="field">
						<label>Прочие мета данные<span class="hlp" title="Данное поле позволяет добавить любой html, css или javascript код в секцию &laquo;HEAD&raquo; на данной странице"></span></label>
						<div class="padding">
							<textarea class="highlight" name="extra_meta"><?php echo $this->_tpl_vars['item']['extra_meta']; ?>
</textarea>
						</div>
					</div>			
					
					<div class="clear"></div>
				</div>
			
			</div>
		</div>
		
		<div class="block">
			<button type="button" name="saveEditedItem">Сохранить</button>
			<div class="clear"></div>
		</div>
		
	</form>
</div>
<script type="text/javascript"><?php echo 'catalog.editItemTplInit();'; ?>
</script>