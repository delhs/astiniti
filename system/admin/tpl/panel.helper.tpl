<div class="panel_helper">
{if $developer}

	<h1>Разработка</h1>
	<form name="panel_helper">
		
		<div class="block">

			{if $createplugin}<p>Создание плагина</p>{/if}
			{if $createmodule}<p>Создание модуля</p>{/if}

			{if $createplugin}<input type="hidden" name="action" value="createplugin" />{/if}
			{if $createmodule}<input type="hidden" name="action" value="createmodule" />{/if}

			<div class="field">
				{if $createplugin}<label>Русское имя плагина:</label>{/if}
				{if $createmodule}<label>Русское имя модуля:</label>{/if}
				<div class="padding">
					<input type="text" name="name" />
				</div>
			</div>

			<div class="field">
				{if $createplugin}<label>Имя класса плагина:</label>{/if}
				{if $createmodule}<label>Имя класса модуля:</label>{/if}
				<div class="padding">
					<input type="text" name="class_name" />
				</div>
			</div>

		</div>
		
		
		<div class="block">
			{if $createplugin}<button type="button" name="save">Создать плагин</button>{/if}
			{if $createmodule}<button type="button" name="save">Создать модуль</button>{/if}
			<div class="clear"></div>
		</div>
		
	</form>

{/if}
</div>

{if $developer}
	{literal}
		<script type="text/javascript">
			var $form = $("form[name='panel_helper']");

			$form.find("button[name='save']").click(function(){
				admin.block();
				var data = $form.serialize();
				admin.ajax('helper', data, $form, function(){
					admin.reload();
				});
			});


			//keydon event on ENTER keyboard button
			$form.off("keydown");
			$form.on("keydown", "input[type='text']", function(e){
				if(e.keyCode == 13 ){
					e.preventDefault();
					$form.find("button[name='save']").click();
				}
			});

			$form.find("input[type='text']:first").focus();

		</script>
	{/literal}
{/if}



