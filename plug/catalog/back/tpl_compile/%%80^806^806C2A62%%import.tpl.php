<?php /* Smarty version 2.6.27, created on 2014-01-20 10:46:13
         compiled from import.tpl */ ?>
<div class="import">
	<p>Импорт данных из 1С</p>
	
	<div class="form">

		<div class="block">
			<p><i>Выберите файл импорта для загрузки. Формат файла - XML документ<br/>XML файл с описанием полей можно скачать по <a target="_blank" href="/download/plug/catalog/xmlman">этой ссылке</a></i></p>
			<label>XML файл обмена:</label>
			<div class="padding">
				<input type="file" name="importFile" id="importFile" />
			</div>
		</div>

		<div class="block information">
			<div class="import_file_date hide">Дата создания файла импорта: <span></span></div>
			<div class="import_file_time hide">Время создания файла импорта: <span></span></div>
			
			<p class="add_title_add hide">После импорта будет обновлено или добавлено:</p>
			<div class="import_file_add_types hide"><b></b> <span></span></div>
			<div class="import_file_add_items hide"><b></b> <span></span></div>
			<div class="import_file_add_categories hide"><b></b> <span></span></div>
			<div class="import_file_add_brands hide"><b></b> <span></span></div>

			<p class="remove_title hide">После импорта будет удалено:</p>
			<div class="import_file_remove_types hide"><b></b> <span></span></div>
			<div class="import_file_remove_items hide"><b></b> <span></span></div>
			<div class="import_file_remove_categories hide"><b></b> <span></span></div>
			<div class="import_file_remove_brands hide"><b></b> <span></span></div>
		</div>

		<div class="block">
			<p><i>Выберите Архив с изображениями. Тип архива - ZIP с любой степенью сжатия</i></p>
			<label>ZIP архив с изображениями:</label>
			<div class="padding">
				<input type="file" name="importArchive" id="importArchive" />
			</div>
		</div>

		<div class="block">
			<button type="button" disabled="disabled" name="startImport">Начать импорт</button>
		</div>
	</div>

</div>
<script type="text/javascript"><?php echo 'catalog.importTplInit();'; ?>
</script>