<?php /* Smarty version 2.6.27, created on 2014-01-31 12:03:52
         compiled from panel.helper.tpl */ ?>
<div class="panel_helper">
<?php if ($this->_tpl_vars['developer']): ?>

	<h1>Разработка</h1>
	<form name="panel_helper">
		
		<div class="block">

			<?php if ($this->_tpl_vars['createplugin']): ?><p>Создание плагина</p><?php endif; ?>
			<?php if ($this->_tpl_vars['createmodule']): ?><p>Создание модуля</p><?php endif; ?>

			<?php if ($this->_tpl_vars['createplugin']): ?><input type="hidden" name="action" value="createplugin" /><?php endif; ?>
			<?php if ($this->_tpl_vars['createmodule']): ?><input type="hidden" name="action" value="createmodule" /><?php endif; ?>

			<div class="field">
				<?php if ($this->_tpl_vars['createplugin']): ?><label>Русское имя плагина:</label><?php endif; ?>
				<?php if ($this->_tpl_vars['createmodule']): ?><label>Русское имя модуля:</label><?php endif; ?>
				<div class="padding">
					<input type="text" name="name" />
				</div>
			</div>

			<div class="field">
				<?php if ($this->_tpl_vars['createplugin']): ?><label>Имя класса плагина:</label><?php endif; ?>
				<?php if ($this->_tpl_vars['createmodule']): ?><label>Имя класса модуля:</label><?php endif; ?>
				<div class="padding">
					<input type="text" name="class_name" />
				</div>
			</div>

		</div>
		
		
		<div class="block">
			<?php if ($this->_tpl_vars['createplugin']): ?><button type="button" name="save">Создать плагин</button><?php endif; ?>
			<?php if ($this->_tpl_vars['createmodule']): ?><button type="button" name="save">Создать модуль</button><?php endif; ?>
			<div class="clear"></div>
		</div>
		
	</form>

<?php endif; ?>
</div>

<?php if ($this->_tpl_vars['developer']): ?>
	<?php echo '
		<script type="text/javascript">
			var $form = $("form[name=\'panel_helper\']");

			$form.find("button[name=\'save\']").click(function(){
				admin.block();
				var data = $form.serialize();
				admin.ajax(\'helper\', data, $form, function(){
					admin.reload();
				});
			});


			//keydon event on ENTER keyboard button
			$form.off("keydown");
			$form.on("keydown", "input[type=\'text\']", function(e){
				if(e.keyCode == 13 ){
					e.preventDefault();
					$form.find("button[name=\'save\']").click();
				}
			});

			$form.find("input[type=\'text\']:first").focus();

		</script>
	'; ?>

<?php endif; ?>


