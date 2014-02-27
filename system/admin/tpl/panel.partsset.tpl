<div class="panel_partsett">
	<h1>Настройка раздела</h1>

	<form name="partsett" data-part_id="{$part.id}">
	
		<div class="block">
			<p>Основные параметры</p>
			
			<div class="field fullurl">
				<label>Полная ссылка раздела</label><a title="Открыть текущую страницу в новой вкладке браузера" href="{$protocol}://{$hostname}{$part.url}" target="_blank"><span>{$protocol}://</span>{$hostname}<i>{$part.url}</i></a>
			</div>
			
			<div class="field partid">
				<label>ID раздела</label><span>{$part.id}</span>
			</div>
			
			<div class="field">
				<label>Название раздела<span class="hlp" title="Это название, которое будет отображаться в главном меню разделов сайта."></span></label>
				<div class="padding">
					<input type="text" name="name" value="{$part.name|escape}" />
				</div>
			</div>
			
			<div class="field">
				<label>Краткое описание<span class="hlp" title="Этот текст будет выводиться в атрибуте &laquo;title&raquo; главного меню разделов сайта"></span></label>
				<div class="padding">
					<input type="text" name="quick_desc" value="{$part.quick_desc|escape}" />
				</div>
			</div>	
			
			{if $part.id neq 1}
				<div class="field">
					<label>Линк страницы<span class="hlp" title="Это адрес, по которому данная страниц будет доступна. В данный момент полная ссылка на страницу выглядит так: &laquo;{$hostname}{$part.url}&raquo;, где &laquo;{$part.link}&raquo; и есть линк этой страницы. Линк страницы может содержать только латинские буквы, цифры, символ &laquo;_&raquo; и символ &laquo;-&raquo;"></span></label>
					<div class="padding">
						<input type="text" name="link" value="{$part.link}" />
					</div>
				</div>
			{/if}
			
			
			<input type="hidden" name="pid" value="{$part.pid}" />
			{*
			{if $part.id neq 1}
			<div class="field">
				<label>Родитель страницы<span class="hlp" title="Определяет, какой раздел является родительским данной страницы"></span></label>
				<div class="padding">
					<select name="pid">
						<option value="0">--Самостоятельный раздел--</option>
						{assign var="level" value=0}
						{include file="panel.partsset.mmenu.tpl"}
					</select>
				</div>
			</div>
			{/if}
			*}
	

			{if $templatFileArray}
			<div class="field">
				<label>Шаблон страницы<span class="hlp" title="В данном проекте доступно несколько различных шаблонов. Выберите шаблон, который будет использоваться данным разделом"></span></label>
				<div class="padding">
					<select name="template">
						{foreach from=$templatFileArray item=dataArray}
						<option value="{$dataArray}" {if $part.template eq $dataArray} selected="selected" {/if}>{$dataArray}</option>
						{/foreach}
					</select>
				</div>
			</div>
			{/if}
			
		</div>
		{*
		<div class="block">
			<p>Задний фон<span class="hlp" title="Если в вашем проекте нет поддержки изменения заднего фона страницы, то данная функция для вас будет бесполезной"></span></p>
			<div class="field">
				
				{literal}
				<input type="file" name="fileicon" id="fileupload" onchange="partssettFileUploadIcon();return false" />
				{/literal}
				
				<a href="#" class="removeicons" title="Удалить данные иконки"></a>
				
				<div class="icons">
					<span class="loader40x40"></span>
					{if $part.icon40x40 neq ''}
						<img class="icon40x40" src="/files/images/pages/{$part.id}/{$part.icon40x40}" />
					{else}
						<div class="icon40x40"></div>
					{/if}
				</div>
				
				<input type="hidden" name="icon40x40" value="{$part.icon40x40}" />
				
			</div>
		</div>
		*}
		
		<div class="block">
			<p>Параметры видимости</p>
			
			<div class="field">
				<label>Показывать в главном меню<span class="hlp" title="Если отмечен этот пункт, то раздел будет показываться в главном меню разделов сайта"></span></label>
				<input type="radio" name="in_menu" {if $part.in_menu==1} checked="checked" {/if} value="1" />
			</div>
			
			<div class="field">
				<label>Скрыть из главного меню<span class="hlp" title="Если отмечен этот пункт, то раздел не будет показываться в главном меню разделов сайта"></span></label>
				<input type="radio" name="in_menu" {if $part.in_menu==0} checked="checked" {/if} value="0" />
			</div>
		</div>
		
		<div class="block">
		
			<p>Параметры доступа</p>
			
			<div class="field">
				<label>Не доступен<span class="hlp" title="Если отмечен этот пункт, то при попытке перейти на данный раздел будет сгенерирована ошибка 404, которая означает, что такой страницы не существует. Помимо этого, раздел будет скрыт из главного меню разделов сайта"></span></label>
				<input type="radio" name="off" {if $part.off==1} checked="checked" {/if} value="1" />
			</div>
			
			<div class="field">
				<label>Доступен<span class="hlp" title="Если отмечен данный пункт, то раздел будет доступен для проссмотра"></span></label>
				<input type="radio" name="off" {if $part.off==0} checked="checked" {/if} value="0" />
			</div>
			
		</div>	
			
		<div class="block">
			<p>Параметры открытия</p>
			
			<div class="field">
				<label>В текущей вкладке<span class="hlp" title="Открывает страницу в этой же вкладке браузера"></span></label>
				<input type="radio" name="target" {if $part.target=='_self'} checked="checked" {/if} value="_self" />
			</div>
			
			<div class="field">
				<label>В новой вкладке<span class="hlp" title="Открывает раздел в новой вкладке браузера"></span></label>
				<input type="radio" name="target" {if $part.target=='_blank'} checked="checked" {/if} value="_blank" />
			</div>
		</div>
		
		<div class="spoiler">
			<div class="spoiler-head">Изображения и иконки</div>
			<div class="spoiler-body">
			
				<div class="block">
					<p>Иконка раздела</p>
					<div class="field">
						<div class="icons {if $part.icon neq ''}exist{/if}"><img src="{if $part.icon neq ''}{$part.icon}{/if}" /></div>
					
						<input type="file" name="icon" id="icon" />
					</div>
				</div>
		</div>
		
		<div class="spoiler">
			<div class="spoiler-head">Дополнительные параметры</div>
			<div class="spoiler-body">
			

				<div class="block">
					<p>Параметры индексирования</p>
					<div class="field">
						<label>Разрешено индексировать<span class="hlp" title="Разрешает поисковым системам индексировать эту страницу. На страницу будет вставлен специальный мета-тег &lt;&nbsp;meta&nbsp;name=&laquo;Robots&raquo; content=&laquo;all&raquo;&nbsp;&gt;"></span></label>
						<input type="checkbox" name="meta_robots_all" {if $part.meta_robots_all=='1'} checked="checked" {/if} value="1" />
					</div>
					
					<div class="field">
						<label>Запрещено индексировать<span class="hlp" title="Запрещает поисковым системам индексировать эту страницу. На страницу будет вставлен специальный мета-тег &lt;&nbsp;meta&nbsp;name=&laquo;Robots&raquo; content=&laquo;noindex&raquo;&nbsp;&gt;"></span></label>
						<input type="checkbox" name="meta_robots_noindex" {if $part.meta_robots_noindex=='1'} checked="checked" {/if} value="1" />
					</div>	
					
					<div class="field">
						<label>Не переходить по ссылкам<span class="hlp" title="Запрещает поисковым системам переходить по ссылкам с этой страницы. На страницу будет вставлен специальный мета-тег &lt;&nbsp;meta&nbsp;name=&laquo;Robots&raquo; content=&laquo;nofollow&raquo;&nbsp;&gt;"></span></label>
						<input type="checkbox" name="meta_robots_nofollow" {if $part.meta_robots_nofollow=='1'} checked="checked" {/if} value="1" />
					</div>		
					
					<div class="field">
						<label>Не показывать сохраненную копию<span class="hlp" title="Запрещает поисковым системам показывать ссылку на сохраненную копию на странице результатов поиска. На страницу будет вставлен специальный мета-тег &lt;&nbsp;meta&nbsp;name=&laquo;Robots&raquo; content=&laquo;noarchive&raquo;&nbsp;&gt;"></span></label>
						<input type="checkbox" name="meta_robots_noarchive" {if $part.meta_robots_noarchive=='1'} checked="checked" {/if} value="1" />
					</div>
				</div>
				
				<div class="block">
					
					<p>Skype</p>
					
					<div class="field">
						<label>Блокировать "Skype"<span class="hlp" title="Предотвращает встраивание панелей &laquo;Skype&raquo; для номеров телефонов, размещенных на странице"></span></label>
						<input type="radio" name="skype_block" {if $part.skype_block=='1'} checked="checked" {/if} value="1" />
					</div>
					
					<div class="field">
						<label>Разрешить "Skype"<span class="hlp" title="Разрешает &laquo;Skype&raquo; встраиваться на страницу, выделяя номера телефонов"></span></label>
						<input type="radio" name="skype_block" {if $part.skype_block=='0'} checked="checked" {/if} value="0" />
					</div>
					
				</div>
		
				
				<div class="block">
					
					<p>Заголовки</p>
					
					<div class="field">
						<label>Pragma<span class="hlp" title="Данная опция предназначена для SEO оптимизаторов"></span></label>
						<div class="padding">
							<select name="pragma">
								{foreach from=$pragmaArray key=key item=item}
									<option value="{$key}" {if $key eq $part.pragma}selected="selected"{/if}>{$item}</option>
								{/foreach}
							</select>
						</div>
					</div>
					
					<div class="field">
						<label>Cache-Control<span class="hlp" title="Данная опция предназначена для SEO оптимизаторов"></span></label>
						<div class="padding">
							<select name="cache_control">
								{foreach from=$cacheControlArray key=key item=item}
									<option value="{$key}" {if $key eq $part.cache_control}selected="selected"{/if}>{$item}</option>
								{/foreach}
							</select>
						</div>
					</div>			
		
					<div class="field">
						<label>Last-Modified<span class="hlp" title="Данная опция предназначена для SEO оптимизаторов"></span></label>
						<div class="padding">
							<input type="text" name="edit_date" value="{$part.edit_date}" />
						</div>
					</div>
					
					<div class="field">
						<label>Expires<span class="hlp" title="Данная опция предназначена для SEO оптимизаторов"></span></label>
						<div class="padding">
							<input type="text" name="expires_date" value="{$part.expires_date}" />
						</div>
					</div>
					
				</div>
				
				<div class="block meta">
					
					<p>Мета теги</p>
					
					<div class="field">
						<label>Язык страницы<span class="hlp" title="Язык, на котором написан текст страницы"></span></label>
						<div class="padding">
							<select name="meta_lang">
								{foreach from=$langsArray key=lang item=langDesc}
								<option value="{$lang}" {if $part.meta_lang eq $lang} selected="selected" {/if}>{$langDesc}&nbsp;({$lang})</option>
								{/foreach}
							</select>
						</div>	
					</div>
					
					<div class="field">
						<label>Мета тег "TITLE"<span class="hlp" title="Это мета тег &laquo;Title&raquo;, текст которого выводится на вкладке страницы в браузере"></span></label>
						<div class="padding">
							<input type="text" name="meta_title" value="{$part.meta_title|escape}"/>
						</div>
					</div>
					<div class="field">
						<label>Мета тег "KEYWORDS"<span class="hlp" title="Это ключевые слова, которые отражают общую суть того, что представлено на странице"></span></label>
						<div class="padding">
							<textarea class="highlight" name="meta_keywords">{$part.meta_keywords}</textarea>
						</div>
					</div>
					
					<div class="field">
						<label>Мета тег "DESCRIPTION"<span class="hlp" title="Это описание страницы, которое видит поисковая система"></span></label>
						<div class="padding">
							<textarea class="highlight" name="meta_description">{$part.meta_description}</textarea>
						</div>
					</div>
		
					<div class="field">
						<label>Прочие мета данные<span class="hlp" title="Данное поле позволяет добавить любой html, css или javascript код в секцию &laquo;HEAD&raquo; на данной странице"></span></label>
						<div class="padding">
							<textarea class="highlight" name="extra_meta">{$part.extra_meta}</textarea>
						</div>
					</div>			
					
					<div class="clear"></div>
				</div>
		
			</div>
		</div>
		
		<div class="block">
			<button type="button" name="save">Сохранить изменения</button>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</form>
</div>
	




{literal}
<script type="text/javascript">

	admin.jcropApi = undefined;

	$("input[name='expires_date']").datetimepicker({timeFormat:"HH:mm:ss",dateFormat:'dd.mm.yy'});
	$("input[name='edit_date']").datetimepicker({timeFormat:"HH:mm:ss",dateFormat:'dd.mm.yy'});

	$("form[name='partsett'] button[name='save']").click(function(){
		admin.block();
		
		//get crop object
		if( admin.jcropApi != undefined ){
			var cropObj = admin.jcropApi.tellSelect();
			//if crop width or height is zero, then set new width and height
			if( cropObj.w==0 || cropObj.h==0 ){
				cropObj.w = 40;
				cropObj.h = 40;
				cropObj.x = 0;
				cropObj.y = 0;
			}
			//floor the values and compile url string
			var urlStr = "";
			$.each( cropObj, function(i, v){
				urlStr += "&" + i + "=" + Math.floor(v);
			});
			var partIcon = $(".icons>img").attr("src");
		}else{
			var partIcon = "";
			var urlStr = "";
		}		
			
		var form = $("form[name='partsett']");
		var partId = form.attr("data-part_id");
		var data = form.serialize()+"&action=editPart&partId=" + partId + "&partIcon="+partIcon + urlStr;
		

		admin.ajax( 'partsett', data, form, function(){
			admin.reloadPanel(function(){
				var form = $("form[name='partsett']");
				var url = form.find(".fullurl a>i").text();
				$("#toppanel .currentpageblock a").attr("href", url).children("span").text(url);
			});
		});
	});
	
	
	$("input[type='file'][name='icon']").change(function(){
		admin.block();
		admin.upload( "icon", "partsett", "uploadIcon", function( filename, errorMessage ){
			if( filename.trim()=="failed" || filename.trim()=="" ){
				if( errorMessage!="" )
				admin.errorBox("<p>"+errorMessage+"</p>");
				else
				admin.errorBox("<p>Во время загрузки изображения произошла ошибка.</p><p>Повторите попытку позже или выберите другой файл</p>");
				return;
			}
			
			$(".icons img").error(function(){
				admin.errorBox("<p>Во время загрузки изображения произошла ошибка.</p><p>Повторите попытку позже или выберите другой файл</p>");
				return;
			});
			
			if( admin.jcropApi!=undefined ) admin.jcropApi.destroy();
			$(".icons.exist").removeClass("exist");
			$(".icons img").attr("src", '/system/admin/temp/'+filename.trim()).show(0, function(){
				$(".icons img").css({height:"auto"}).Jcrop({
						aspectRatio:  335 / 335,
					}, function(){
					admin.jcropApi = this;
					var x = $(".icons img").outerWidth(true);
					var y = $(".icons img").outerHeight(true);
					admin.jcropApi.animateTo([ 0, 0,  335, 335 ]);
				});
			});
			admin.unblock();

		});
		
	});

	
	
	$(document).unbind('keydown');
	$("form[name='partsett'] input[type='text']").bind('keydown', function(e){
		if(e.keyCode == 13 ){
			$("form[name='partsett'] button[name='save']").click();
		}
	});
</script>
{/literal}