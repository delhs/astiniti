{strip}
<div class="catalog_item_page">

	<div class="topblock"></div>


	<h1 class="item_title">{$item.name|escape}</h1>


	<div class="leftblock">
		<div class="photo">
			{if isset($item.full_logo_src)}
				<img alt="{$item.name|escape}" title="{$item.name|escape}" width="400" src="{$item.full_logo_src}"/>
			{else}
				<div class="no_photo"></div>
			{/if}
		</div>


	</div>
	
	<div class="rightblock">
		{if isset($itemImages) && isset($item.full_logo_src)}
			<ul class="otherimages">
			{foreach from=$itemImages item=img}
				<li><a href="#"><img src="{$img.filename}" /></a></li>
			{/foreach}
			</ul>

		{/if}




		{if isset($attrArray)}
		<div class="attributes">
			<h2>Характеристики:</h2>
			<ul>
				{foreach from=$attrArray item=attr}
				<li>
					{$attr.attribute_name|escape}:&nbsp;
					<strong>{$attr.attribute_value|escape}</strong>
					{if $attr.attribute_units neq ''}<b>{$attr.attribute_units|escape}</b>{/if}
				</li>
				{/foreach}
			</ul>
		</div>
		{/if}

		<div class="clear"></div>

	</div>	


	<div class="tabs">

		<div class="tabcontrols">
			{if $item.item_desc neq ''}
				<a href="#" class="ctrl">
					<h3>Описание</h3>
				</a>
			{/if}

			{if $catalog.show_comments eq '1'}
				<a href="#" class="ctrl">
					<h3>Комментарии</h3>
				</a>
			{/if}

			{if isset($analogsArray)}
				<a href="#" class="ctrl">
					<h3>Похожие товары</h3>
				</a>	
			{/if}

			{if isset($accompanyingArray)}
				<a href="#" class="ctrl">
					<h3>Сопутствующие товары</h3>
				</a>	
			{/if}
		</div>




		<div class="tabcontents">
			{if $item.item_desc neq ''}
				<div class="tab">
					<div class="desc">{$item.item_desc}</div>
					<button type="button" name="buy" class="buy" data-id="{$item.id}">Купить</button>
				</div>
			{/if}


			{if $catalog.show_comments eq '1'}
				<div class="tab">
					
					<div class="comments">
			
						{if isset($itemCommentsArray)}
							{include file="item_comments.tpl"}
						{/if}
	
						<div class="editorblock">
							<a name="editor_block"></a>
							<form name="comment" item-id="{$item.id}">
								<label>Ваше имя:</label>
								<div class="inputwrapper">
									<input type="text" name="username" maxlength="50"/>
								</div>
								<div class="clear"></div>
								<div class="reply_to">
									<span>Ответить: </span><apan class="reply_list"></span>
								</div>
								<textarea class="wisibb_editor"></textarea>
								<textarea name="text" class="wisibb_editor_pseudo"></textarea>
								<label>Защита от спама:</label><img src="/captcha.php?to=catalogComments&type=rnd" /><input type="text" name="captcha" maxlength="5"/>
							</form>
						</div>
			
						<div class="clear"></div>
						<button type="button" name="send_comment">Отправить</button>
						<div class="clear"></div>
			
					</div>
					
				</div>
			{/if}

			{if isset($analogsArray)}
				<div class="tab">
					<ul>
					{foreach from=$analogsArray item=analog}
						<li>
							<a href="{$analog.href}" title="{$analog.name|escape}" >
								{if $analog.item_logo neq ''}
									<img src="{$analog.item_logo}" alt="{$analog.name|escape}"/>
								{else}
									<div class="no_photo"><i></i></div>
								{/if}
							</a>
						</li>
					{/foreach}	
					</ul>
				</div>
			{/if}

			{if isset($accompanyingArray)}
				<div class="tab">
					<ul>
					{foreach from=$accompanyingArray item=ccompanying}
						<li>
							<a href="{$ccompanying.href}" title="{$ccompanying.name|escape}" >
								{if $ccompanying.item_logo neq ''}
									<img src="{$ccompanying.item_logo}" alt="{$ccompanying.name|escape}"/>
								{else}
									<div class="no_photo"><i></i></div>
								{/if}
							</a>
						</li>
					{/foreach}	
					</ul>
				</div>
			{/if}

		</div>

	</div>


		
		
		








</div>

<div class="clear"></div>
<script type="text/javascript">
	{literal}
	$(document).ready(function(){
		plug_catalog.itemPage( {/literal}{$settings}{literal} );
	});
	{/literal}
</script>
{/strip}