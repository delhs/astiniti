{strip}{if isset($not_install)}
	<p>Плагин не настроен<p>
{else}
	<form name="mailme">
		<div class="mailme-cap">
			<p>{pasteWord name='plug_mailme_title'}</p>
			<i>{pasteWord name='plug_mailme_title_desc'}</i>
		</div>

	
		<div class="field">
			<label>{pasteWord name='plug_mailme_label_departament'}</label>
			<div class="padding">
				<select name="departament">
					{if isset( $departaments )}
						{foreach from=$departaments item=departament}
							<option value="{$departament.id}" {if $recipient.departament_id eq $departament.id}selected="selected"{/if}>{$departament.name|escape}</option>
						{/foreach}
					{/if}
				</select>
			</div>
		</div>

		<div class="field">
			<label>{pasteWord name='plug_mailme_label_name'}</label>
			<div class="padding">
				<input type="text" name="name" maxlength="50"/>
			</div>
		</div>
	
		<div class="field">
			<label>{pasteWord name='plug_mailme_label_email'}</label>
			<div class="padding">
				<input type="text" name="email" maxlength="256"/>
			</div>
		</div>
	
		<div class="field">
			<label>{pasteWord name='plug_mailme_label_text'}</label>
			<div class="padding">
				<textarea name="text" maxlength="900" ></textarea>
			</div>
		</div>
	
		{if $security.captcha_is_on eq '1'}
		<div class="field">
			<label>{pasteWord name='plug_mailme_label_captcha'}</label>
			<div class="padding">
				<img title="{pasteWord name='plug_mailme_label_captcha_update'}" src="/captcha.php?to=mailme&type={$security.captcha_type}&nocache={$uniqid}" /><input type="text" autocomplete="off" name="captcha" maxlength="5"/>
			</div>
		</div>
		{/if}
	
		<div class="field forbutton">
			<div class="padding">
				<button type="button" name="mailme">{pasteWord name='plug_mailme_label_button'}</button>
			</div>
		</div>
	</form>
{/if}{/strip}