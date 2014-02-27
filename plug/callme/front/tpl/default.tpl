{strip}{if isset($not_install)}
	<p>Плагин не настроен<p>
{else}
	<form name="callme">
		<div class="callme-cap">
			<p>{pasteWord name='plug_callme_title'}</p>
			<i>{pasteWord name='plug_callme_title_desc'}</i>
		</div>
	
		<div class="field">
			<label>{pasteWord name='plug_callme_label_name'}</label>
			<div class="padding">
				<input type="text" name="name" maxlength="50"/>
			</div>
		</div>
	
		<div class="field">
			<label>{pasteWord name='plug_callme_label_phone'}</label>
			<div class="padding">
				<input type="text" name="phone" maxlength="16"/>
			</div>
		</div>
		

		<div class="field">
			<label>{pasteWord name='plug_callme_label_comment'}</label>
			<div class="padding">
				<textarea name="comment" maxlength="100" ></textarea>
			</div>
		</div>


		{if $security.captcha_is_on eq '1'}
		<div class="field">
			<label>{pasteWord name='plug_callme_label_captcha'}</label>
			<div class="padding">
				<img title="{pasteWord name='plug_callme_label_captcha_update'}" src="/captcha.php?to=callme&type={$security.captcha_type}&nocache={$uniqid}" /><input type="text" autocomplete="off" name="captcha" maxlength="5"/>
			</div>
		</div>
		{/if}
	
		<div class="field forbutton">
			<div class="padding">
				<button type="button" name="callme">{pasteWord name='plug_callme_label_button'}</button>
			</div>
		</div>
	</form>
{/if}{/strip}