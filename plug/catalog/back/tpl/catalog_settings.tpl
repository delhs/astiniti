<form name="catalog_settings_main">
	<div class="block">
		<p>Валюта</p>
		
		<div class="field">
			<label>Сокращение в 2-3 символа:</label>
			<div class="padding">
				<input type="text" name="currency_quick" value="{$catalogSettings.currency_quick|escape}"/>
			</div>
		</div>

		<div class="field">
			<label>Сокращение в 1 символ:</label>
			<div class="padding">
				<input type="text" name="currency_symbol" value="{$catalogSettings.currency_symbol|escape}"/>
			</div>
		</div>
		
		<div class="field">
			<label>Ед. число Именительный падеж (один...):</label>
			<div class="padding">
				<input type="text" name="currency_nom" value="{$catalogSettings.currency_nom|escape}"/>
			</div>
		</div>
		
		<div class="field">
			<label>Мн. число Именительный падеж (два...):</label>
			<div class="padding">
				<input type="text" name="currency_acc" value="{$catalogSettings.currency_acc|escape}"/>
			</div>
		</div>
		
		<div class="field">
			<label>Мн. число Родительный падеж (пять...):</label>
			<div class="padding">
				<input type="text" name="currency_nomp" value="{$catalogSettings.currency_nomp|escape}"/>
			</div>
		</div>

	</div>

	<div class="block">
		<p>Товары</p>

		<div class="field">
			<label>Ед. число Именительный падеж (один...):</label>
			<div class="padding">
				<input type="text" name="item_nom" value="{$catalogSettings.item_nom|escape}"/>
			</div>
		</div>
		
		<div class="field">
			<label>Мн. число Иминительный падеж (два...):</label>
			<div class="padding">
				<input type="text" name="item_acc" value="{$catalogSettings.item_acc|escape}"/>
			</div>
		</div>

		<div class="field">
			<label>Мн. число Родительный падеж (пять...):</label>
			<div class="padding">
				<input type="text" name="item_nomp" value="{$catalogSettings.item_nomp|escape}"/>
			</div>
		</div>

	</div>

	<div class="block">
		<p>Комментарии</p>

		<div class="field">
			<label>Показывать комментарии</label>
			<div class="padding">
				<input type="radio" name="show_comments" value="1" {if $catalogSettings.show_comments eq '1'}checked="checked"{/if}/>
			</div>
		</div>

		<div class="field">
			<label>Не показывать комментарии</label>
			<div class="padding">
				<input type="radio" name="show_comments" value="0" {if $catalogSettings.show_comments eq '0'}checked="checked"{/if}/>
			</div>
		</div>

	</div>

	<div class="block">
		<p>Отображение</p>

		<div class="field">
			<label>Страница вывода каталога:</label>
			<div class="padding">
				<select name="catalog_page_id">
					<option value="0">--Не указано--</option>
					{assign var="level" value=0}
					{include file="page_list.tpl"}
				</select>
			</div>
		</div>

	</div>
			



	<div class="block">
		<button type="button" name="save_settings">Сохранить изменения</button>
		<div class="clear"></div>
	</div>
</form>

{literal}<script type="text/javascript">catalog.catalogSettingsTplInit();</script>{/literal}