<div class="panel_words">
	<h1>Словари</h1>
	<form name="panel_words" {if $developer}class="full" {/if}>
		
		<div class="block words">
			
			<p>Словари - это все слова и словосочетания, которые &laquo;вшиты&raquo; в шаблон сайта. Например: номера телефонов и адреса электронной почты, различные надписи и заголовки у форм заказов ( Имя:, Фамилия: ), тексты сообщений об ошибках и успешно принятых заказов.</p>
			
			<span class="desc">Описание<span class="hlp" title="Это описание необходимо для того, чтобы понять в какой части сайта выводится то или иное слово или словосочетание"></span></span>
			{if $developer}<span class="key">Ключ</span>{/if}
			<span class="value">Значение<span class="hlp" title="Это именно то слово или словосочетание, которое должно выводиться в назначенном месте сайта"></span></span>
			<div class="clear"></div>
			{if $wordsArray }
				{foreach from=$wordsArray item=word}
				<div class="field">
					<input type="text" name="word_desc[]" value="{$word.word_desc|escape}" /><!--
					--><input {if $developer}type="text"{else}type="hidden"{/if} name="word_key[]" value="{$word.word_key|escape}" /><!--
					--><input type="text" name="word_value[]" value="{$word.word_value|escape}" /><!--
					-->{if $developer}<a href="#" class="del"></a>{/if}
				</div>
				{/foreach}
			{/if}
		</div>
		
		
		<div class="block">
			<button type="button" name="save">Сохранить изменения</button>
			{if $developer}<button type="button" name="add">Добавить ключ</button>{/if}
			<div class="clear"></div>
		</div>
		
	</form>
	
</div>

{literal}
<script type="text/javascript">
	/* save button event */
	$("form[name='panel_words'] button[name='save']").click(function(e){
		e.preventDefault();
		admin.block();

		var data = $("form[name='panel_words']").serialize();
		
		admin.ajax("words", data, function(){
			admin.reloadPanel();
		});
	});
	
	$(document).unbind('keydown');
	$("form[name='panel_words']").bind('keydown', function(e){
		if(e.keyCode == 13){
			e.preventDefault();
			$("form[name='panel_words'] button[name='save']").click();
		}
	});
</script>
{/literal}


{if $developer}
	{literal}
		<script type="text/javascript">
			/* add key button event */
			$("form[name='panel_words'] button[name='add']").click(function(e){
				e.preventDefault();
				var	field = $("<div>", {class:"field"}),
					desc = $("<input>", {type:"text", name:"word_desc[]"}),
					key = $("<input>", {type:"text", name:"word_key[]"}),
					value = $("<input>", {type:"text", name:"word_value[]"}),
					delBtn = $("<a>", {href:"#", class:"del"});
				field.append( desc ).append( key ).append( value ).append( delBtn );
				$("form[name='panel_words'] .block.words").append(field);
				field.hide().slideDown(200);	
			});
			
			
			/* delete button event */
			$("form[name='panel_words'] a.del").click(function(e){
				e.preventDefault();
				$(this).parents(".field").slideUp(200, function(){
					$(this).remove();
				});
			});
		</script>
	{/literal}
{/if}



