	<!--don't remove this code from here-->
	<ul class="addon_menu_back">
		<li><a href="#">Назад</a></li>
		<li class="separator"></li>
	</ul>
	<!--end-->
	
	
	<!--your plugin menu code-->
	<ul id="plug_catalog_menu" class="addon_menu">
		<li>
			<a href="#">Категории</a>
			<ul>
				<li><a href="#" data-action="cat_list">Список категорий</a></li>
				<li><a href="#" data-action="add_cat">Добавить корневую категорию</a></li>
			</ul>
		</li>
		<li>
			<a href="#">Бренды</a>
			<ul>
				<li><a href="#" data-action="brands_list">Список брендов</a></li>
				<li><a href="#" data-action="add_brand">Добавить бренд</a></li>
			</ul>
		</li>
		<li>
			<a href="#">Товары</a>
			<ul>
				<li><a href="#" data-action="items_list">Список товаров</a></li>
				<li><a href="#" data-action="add_item">Добавить товар</a></li>
			</ul>
		</li>
		<li>
			<a href="#">Типы товаров</a>
			<ul>
				<li><a href="#" data-action="item_types">Список типов</a></li>
				<li><a href="#" data-action="types_attr">Атрибуты типов</a></li>
				<li><a href="#" data-action="types_attr_vals">Значения атрибутов</a></li>
			</ul>
		</li>
		<li>
			<a href="#">Импорт/Экспорт</a>
			<ul>
				<li><a href="#" data-action="import">Импорт из 1С</a></li>
			</ul>
		</li>
		<li>
			<a href="#">Заказы</a>
			<ul>
				<li><a href="#" data-action="orders_list">Список заказов</a></li>
			</ul>
		</li>
		<li>
			<a href="#">Операции</a>
			<ul>
				<li><a href="#" data-action="clear_catalog">Очистка каталога</a></li>
			</ul>
		</li>
		<li>
			<a href="#">Настройки</a>
			<ul>
				<li><a href="#" data-action="catalog_settings_main">Основные настройки</a></li>
				<li><a href="#" data-action="catalog_settings_templates">Шаблон E-mail сообщения администратору</a></li>
				<li><a href="#" data-action="catalog_settings_client_templates">Шаблон E-mail сообщения пользователю</a></li>
				<li><a href="#" data-action="catalog_settings_oferta">Договор оферта</a></li>
			</ul>
		</li>
	</ul>
	<!--end-->


	<div class="plug_catalog">
		{$welcomescreen}
	</div>