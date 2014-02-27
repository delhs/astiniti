<?php /* Smarty version 2.6.27, created on 2014-01-24 17:43:07
         compiled from order_user_details.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'order_user_details.tpl', 9, false),)), $this); ?>
<form name="order_user_data">
	<span>Карточка заказчика</span>

	<div class="block">

		<div class="field">
			<label>Имя:</label>
			<div class="padding">
				<input type="text" name="name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['user']['user_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
			</div>
		</div>

		<div class="field">
			<label>Телефон:</label>
			<div class="padding">
				<input type="text" name="phone" value="<?php echo $this->_tpl_vars['user']['user_phone']; ?>
"/>
			</div>
		</div>

		<div class="field">
			<label>E-mail:</label>
			<div class="padding">
				<input type="text" name="email" value="<?php echo $this->_tpl_vars['user']['user_email']; ?>
" />
			</div>
		</div>

		<div class="field">
			<label>Регион:</label>
			<div class="padding">
				<select name="region">
					<option value="0">--Не указано--</option>
					<?php $_from = $this->_tpl_vars['regionsArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['regionData']):
?>
						<option <?php if ($this->_tpl_vars['regionData']['region_id'] == $this->_tpl_vars['user']['region_id']): ?>selected="selected"<?php endif; ?> value="<?php echo $this->_tpl_vars['regionData']['region_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['regionData']['region_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
					<?php endforeach; endif; unset($_from); ?>
				</select>
			</div>
		</div>

		<div class="field">
			<label>Город:</label>
			<div class="padding">
				<select name="city">
					<option value="0">--Не указано--</option>
					<?php $_from = $this->_tpl_vars['citiesArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cityData']):
?>
						<option <?php if ($this->_tpl_vars['cityData']['city_id'] == $this->_tpl_vars['user']['city_id']): ?>selected="selected"<?php endif; ?> value="<?php echo $this->_tpl_vars['cityData']['city_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['cityData']['city_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
					<?php endforeach; endif; unset($_from); ?>
				</select>
			</div>
		</div>

		<div class="field">
			<label>Улица:</label>
			<div class="padding">
				<input type="text" name="street" value="<?php echo $this->_tpl_vars['user']['address_street']; ?>
"/>
			</div>
		</div>

		<div class="field address">
			<label>Дом:</label><input type="text" name="build" maxlength="4" value="<?php echo $this->_tpl_vars['user']['address_build']; ?>
"/>
			<label>Корпус:</label><input type="text" name="liter" maxlength="2" value="<?php echo $this->_tpl_vars['user']['address_liter']; ?>
"/>
			<label>Подъезд:</label><input type="text" name="entrance" maxlength="1" value="<?php echo $this->_tpl_vars['user']['address_entrance']; ?>
"/>
			<label>Этаж:</label><input type="text" name="floor" maxlength="2" value="<?php echo $this->_tpl_vars['user']['address_floor']; ?>
"/>
			<label>Квартира:</label><input type="text" name="room" maxlength="5" value="<?php echo $this->_tpl_vars['user']['address_room']; ?>
"/>
		</div>

	</div>


	<div class="block">
		<button type="button" name="save_user_data">Сохранить и закрыть</button>
		<div class="clear"></div>
	</div>

</form>