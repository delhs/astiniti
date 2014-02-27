<?php /* Smarty version 2.6.27, created on 2014-02-10 20:46:06
         compiled from edit_brand.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'edit_brand.tpl', 18, false),)), $this); ?>
<div class="catalog">
	<form name="edit_brand">
		<p>Редактирование бренда</p>
		
		<div class="block">
			<p>Основные параметры</p>
			
			<div class="field inf">
				<label>ID бренда</label>
				<div class="padding">
					<span><?php echo $this->_tpl_vars['brand']['id']; ?>
</span>
				</div>
			</div>	
			
			<div class="field inf">
				<label>ID бренда в 1С</label>
				<div class="padding">
					<span><?php if ($this->_tpl_vars['brand']['external_id'] != ''): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['brand']['external_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php else: ?>&ndash;<?php endif; ?></span>
				</div>
			</div>
			
			<p></p>
				

			<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['brand']['id']; ?>
" />
			
			<div class="field">
				<label>Название бренда<span class="hlp" title="Название бренда, которое будет отбражаться в списке брендов"></span></label>
				<div class="padding">
					<input type="text" name="name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['brand']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
				</div>
			</div>
			
			<div class="field">
				<label>Линк бренда</label>
				<div class="padding">
					<input type="text" name="link" value="<?php echo $this->_tpl_vars['brand']['link']; ?>
" />
				</div>
			</div>		
			
			<div class="field">
				<label>Официальная страница<span class="hlp" title="Ссылка на официальную страницу бренда"></span></label>
				<div class="padding">
					<input type="text" name="offsite" value="<?php echo $this->_tpl_vars['brand']['offsite']; ?>
" />
				</div>
			</div>

			<div class="field">
				<label>Краткое описание</label>
				<div class="padding">
					<textarea name="brand_quick_desc"><?php echo $this->_tpl_vars['brand']['brand_quick_desc']; ?>
</textarea>
				</div>
			</div>	
			
			<div class="field">
				<label>Полное описание</label>
				<div class="padding">
					<textarea name="brand_descr"><?php echo $this->_tpl_vars['brand']['brand_descr']; ?>
</textarea>
				</div>
			</div>			
			
		</div>
		
		<div class="spoiler">
			<div class="spoiler-head -opened">Изображение</div>
			<div class="spoiler-body">
			
				<div class="block">
					<div class="field">
						<p>Изображение бренда<br/><i>Для загрузки принимаются файлы изображений(gif, png, jpg) с соотношением сторон 2:1. Рекомендуемый размер <?php echo $this->_tpl_vars['brandLogosSizer'][0][0]; ?>
х<?php echo $this->_tpl_vars['brandLogosSizer'][0][1]; ?>
</i></p>
						<div class="brand_logo<?php if (isset ( $this->_tpl_vars['brand']['full_logo_src'] )): ?> exist<?php endif; ?>"><img src="<?php if (isset ( $this->_tpl_vars['brand']['full_logo_src'] )): ?><?php echo $this->_tpl_vars['brand']['full_logo_src']; ?>
<?php endif; ?>" /></div>
						<button name="removeBrandLogo" <?php if (! isset ( $this->_tpl_vars['brand']['full_logo_src'] )): ?>class="hidden"<?php endif; ?>>Удалить</button>
						<input type="file" id="brand_logo" name="brand_logo" />
					</div>
				</div>
			
			</div>
		</div>
		
		<div class="spoiler">
			<div class="spoiler-head">Параметры видимости</div>
			<div class="spoiler-body">
			
				<div class="block">
				
					<div class="field">
						<label>Не доступен<span class="hlp" title="Если отмечен этот пункт, то при попытке перейти на страницу бренда, будет сгенерирована ошибка 404, которая означает, что такой страницы не существует. Помимо этого, бренд будет скрыт из списка брендов"></span></label>
						<div class="padding">
							<input type="checkbox" name="disabled" value="1" <?php if ($this->_tpl_vars['brand']['disabled'] == '1'): ?>checked="checked"<?php endif; ?>/>
						</div>
					</div>		
							
					<div class="field">
						<label>Скрыть из списка брендов<span class="hlp" title="Если отмечен этот пункт, то бренд будет скрыт из списка брендов, но будет доступен при обращении к нему"></span></label>
						<div class="padding">
							<input type="checkbox" name="hide_in_list" value="1" <?php if ($this->_tpl_vars['brand']['hide_in_list'] == '1'): ?>checked="checked"<?php endif; ?>/>
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
						<label>ID бренда в 1C</label>
						<div class="padding">
							<input type="text" name="external_id" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['brand']['external_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"/>
						</div>
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
							<input type="text" name="meta_title" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['brand']['meta_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"/>
						</div>
					</div>
					<div class="field">
						<label>Мета тег "KEYWORDS"<span class="hlp" title="Это ключевые слова, которые отражают общую суть того, что представлено на странице"></span></label>
						<div class="padding">
							<textarea class="highlight" name="meta_keywords"><?php echo $this->_tpl_vars['brand']['meta_keywords']; ?>
</textarea>
						</div>
					</div>
					
					<div class="field">
						<label>Мета тег "DESCRIPTION"<span class="hlp" title="Это описание страницы, которое видит поисковая система"></span></label>
						<div class="padding">
							<textarea class="highlight" name="meta_description"><?php echo $this->_tpl_vars['brand']['meta_description']; ?>
</textarea>
						</div>
					</div>
		
					<div class="field">
						<label>Прочие мета данные<span class="hlp" title="Данное поле позволяет добавить любой html, css или javascript код в секцию &laquo;HEAD&raquo; на данной странице"></span></label>
						<div class="padding">
							<textarea class="highlight" name="extra_meta"><?php echo $this->_tpl_vars['brand']['extra_meta']; ?>
</textarea>
						</div>
					</div>			
					
					<div class="clear"></div>
				</div>
			
			</div>
		</div>
		
		<div class="block">
			<button type="button" name="saveEditedBrand">Сохранить</button>
			<div class="clear"></div>
		</div>
		
	</form>
</div>
<script type="text/javascript"><?php echo 'catalog.editBrandTplInit();'; ?>
</script>