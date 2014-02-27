<form name="catalog_settings_client_templates">
	<div class="block">
		<p>Шаблон письма администратора</p>
		
	
		<div class="field">
			<label>E-mail адрес отправителя:</label>
			<div class="padding">
				<input type="text" name="client_email_from" value="{$catalogSettings.client_email_from|escape}"/>
			</div>
		</div>		
			
		<div class="field">
			<label>Имя отправителя:</label>
			<div class="padding">
				<input type="text" name="client_from_name" value="{$catalogSettings.client_from_name|escape}"/>
			</div>
		</div>		
			
		<div class="field">
			<label>Тема письма:</label>
			<div class="padding">
				<input type="text" name="client_email_subject" value="{$catalogSettings.client_email_subject|escape}"/>
			</div>
		</div>		

		<div class="field">
			<label>Текст письма:</label>
			<div class="padding">
				<textarea name="client_email_template" data-type="editor">{$catalogSettings.client_email_template}</textarea>
			</div>
		</div>

		<div class="field">
			<label>Псевдокод:</label>
			<div class="padding">
				%name% - <i>Имя пользователя, сделавшего заказ (может достигать до 50 символов).</i>
			</div>
			<div class="padding">
				%phone% - <i>Телефон пользователя, сделавшего заказ.</i>
			</div>
			<div class="padding">
				%email% - <i>E-mail адрес пользователя, сделавшего заказ.</i>
			</div>
			<div class="padding">
				%count% - <i>Количество товаров в заказе (целое натуральное число).</i>
			</div>
			<div class="padding">
				%cost% - <i>Общая сумма заказа.</i>
			</div>
			<div class="padding">
				%id% - <i>ID заказа в базе данных (целое натуральное число).</i>
			</div>
			<div class="padding">
				%ordertable% - <i>Таблица товаров заказа.</i>
			</div>
		</div>

	</div>



	<div class="block">
		<button type="button" name="save_settings_client_templates">Сохранить изменения</button>
		<div class="clear"></div>
	</div>
</form>

{literal}<script type="text/javascript">catalog.catalogSettingsClientTemplatesTplInit();</script>{/literal}