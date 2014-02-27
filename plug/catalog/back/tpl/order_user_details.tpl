<form name="order_user_data">
	<span>Карточка заказчика</span>

	<div class="block">

		<div class="field">
			<label>Имя:</label>
			<div class="padding">
				<input type="text" name="name" value="{$user.user_name|escape}" />
			</div>
		</div>

		<div class="field">
			<label>Телефон:</label>
			<div class="padding">
				<input type="text" name="phone" value="{$user.user_phone}"/>
			</div>
		</div>

		<div class="field">
			<label>E-mail:</label>
			<div class="padding">
				<input type="text" name="email" value="{$user.user_email}" />
			</div>
		</div>

		<div class="field">
			<label>Регион:</label>
			<div class="padding">
				<select name="region">
					<option value="0">--Не указано--</option>
					{foreach from=$regionsArray item=regionData}
						<option {if $regionData.region_id eq $user.region_id}selected="selected"{/if} value="{$regionData.region_id}">{$regionData.region_name|escape}</option>
					{/foreach}
				</select>
			</div>
		</div>

		<div class="field">
			<label>Город:</label>
			<div class="padding">
				<select name="city">
					<option value="0">--Не указано--</option>
					{foreach from=$citiesArray item=cityData}
						<option {if $cityData.city_id eq $user.city_id}selected="selected"{/if} value="{$cityData.city_id}">{$cityData.city_name|escape}</option>
					{/foreach}
				</select>
			</div>
		</div>

		<div class="field">
			<label>Улица:</label>
			<div class="padding">
				<input type="text" name="street" value="{$user.address_street}"/>
			</div>
		</div>

		<div class="field address">
			<label>Дом:</label><input type="text" name="build" maxlength="4" value="{$user.address_build}"/>
			<label>Корпус:</label><input type="text" name="liter" maxlength="2" value="{$user.address_liter}"/>
			<label>Подъезд:</label><input type="text" name="entrance" maxlength="1" value="{$user.address_entrance}"/>
			<label>Этаж:</label><input type="text" name="floor" maxlength="2" value="{$user.address_floor}"/>
			<label>Квартира:</label><input type="text" name="room" maxlength="5" value="{$user.address_room}"/>
		</div>

	</div>


	<div class="block">
		<button type="button" name="save_user_data">Сохранить и закрыть</button>
		<div class="clear"></div>
	</div>

</form>