<?php /* Smarty version 2.6.27, created on 2013-12-20 15:17:15
         compiled from security_tpl.tpl */ ?>
<div class="security">
		<p>Безопасность</p>
	<form name="plug_call_me_security">
		<div class="block">
			<p>Каптча</p>
			<div class="field">
				<label>Запрашивать защитный код "каптча"</label>
				<div class="padding">
					<input type="checkbox" name="captcha_is_on" value="1" <?php if ($this->_tpl_vars['security']['captcha_is_on'] == '1'): ?>checked="checked"<?php endif; ?> />
				</div>
			</div>
		</div>

		<div class="block">
			<p>Тип Каптчи</p>
			<div class="field">
				<label>Строковая каптча<span class="hlp" title="Каптча будет выводиться ввиде строки из пяти букв английского алфавита"></span></label>
				<div class="padding">
					<input type="radio" name="captcha_type" value="str" <?php if ($this->_tpl_vars['security']['captcha_type'] == 'str'): ?>checked="checked"<?php endif; ?> <?php if ($this->_tpl_vars['security']['captcha_is_on'] != '1'): ?>disabled="disabled"<?php endif; ?> />
				</div>
			</div>

			<div class="field">
				<label>Числовая каптча<span class="hlp" title="Каптча будет выводиться ввиде натурального целого числа из пяти символов"></span></label>
				<div class="padding">
					<input type="radio" name="captcha_type" value="num" <?php if ($this->_tpl_vars['security']['captcha_type'] == 'num'): ?>checked="checked"<?php endif; ?> <?php if ($this->_tpl_vars['security']['captcha_is_on'] != '1'): ?>disabled="disabled"<?php endif; ?>/>
				</div>
			</div>

			<div class="field">
				<label>Математическая каптча<span class="hlp" title="Каптча будет выводиться ввиде математического действия(сложения или вычитания) двух целых натуральных простых чисел"></span></label>
				<div class="padding">
					<input type="radio" name="captcha_type" value="math" <?php if ($this->_tpl_vars['security']['captcha_type'] == 'math'): ?>checked="checked"<?php endif; ?> <?php if ($this->_tpl_vars['security']['captcha_is_on'] != '1'): ?>disabled="disabled"<?php endif; ?>/>
				</div>
			</div>

			<div class="field">
				<label>В произвольном порядке<span class="hlp" title="Каптча будет выводиться в одном из предыдущих вариантов. Порядок - произвольный"></span></label>
				<div class="padding">
					<input type="radio" name="captcha_type" value="rnd" <?php if ($this->_tpl_vars['security']['captcha_type'] == 'rnd'): ?>checked="checked"<?php endif; ?> <?php if ($this->_tpl_vars['security']['captcha_is_on'] != '1'): ?>disabled="disabled"<?php endif; ?>/>
				</div>
			</div>

		</div>

		<div class="block">
			<button type="button" name="save">Сохранить</button>
			<div class="clear"></div>
		</div>

	</form>
</div>
<?php echo '<script type="text/javascript">callme.securityTplInit();</script>'; ?>