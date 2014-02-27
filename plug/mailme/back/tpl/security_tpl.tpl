<div class="security">
		<p>Безопасность</p>
	<form name="plug_mail_me_security">
		<div class="block">
			<p>Каптча</p>
			<div class="field">
				<label>Запрашивать защитный код "каптча"</label>
				<div class="padding">
					<input type="checkbox" name="captcha_is_on" value="1" {if $security.captcha_is_on eq '1'}checked="checked"{/if} />
				</div>
			</div>
		</div>

		<div class="block">
			<p>Тип Каптчи</p>
			<div class="field">
				<label>Строковая каптча<span class="hlp" title="Каптча будет выводиться ввиде строки из пяти букв английского алфавита"></span></label>
				<div class="padding">
					<input type="radio" name="captcha_type" value="str" {if $security.captcha_type eq 'str'}checked="checked"{/if} {if $security.captcha_is_on neq '1'}disabled="disabled"{/if} />
				</div>
			</div>

			<div class="field">
				<label>Числовая каптча<span class="hlp" title="Каптча будет выводиться ввиде натурального целого числа из пяти символов"></span></label>
				<div class="padding">
					<input type="radio" name="captcha_type" value="num" {if $security.captcha_type eq 'num'}checked="checked"{/if} {if $security.captcha_is_on neq '1'}disabled="disabled"{/if}/>
				</div>
			</div>

			<div class="field">
				<label>Математическая каптча<span class="hlp" title="Каптча будет выводиться ввиде математического действия(сложения или вычитания) двух целых натуральных простых чисел"></span></label>
				<div class="padding">
					<input type="radio" name="captcha_type" value="math" {if $security.captcha_type eq 'math'}checked="checked"{/if} {if $security.captcha_is_on neq '1'}disabled="disabled"{/if}/>
				</div>
			</div>

			<div class="field">
				<label>В произвольном порядке<span class="hlp" title="Каптча будет выводиться в одном из предыдущих вариантов. Порядок - произвольный"></span></label>
				<div class="padding">
					<input type="radio" name="captcha_type" value="rnd" {if $security.captcha_type eq 'rnd'}checked="checked"{/if} {if $security.captcha_is_on neq '1'}disabled="disabled"{/if}/>
				</div>
			</div>

		</div>

		<div class="block">
			<button type="button" name="save">Сохранить</button>
			<div class="clear"></div>
		</div>

	</form>
</div>
{literal}<script type="text/javascript">mailme.securityTplInit();</script>{/literal}