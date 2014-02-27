{strip}
<div class="page-wrapper">
	<div class="page-buffer">
		<!--cap block-->
		<div class="cap{if $page.id eq 1} main-page{/if}">
			<div class="centerpos">
			
				<a href="/" class="logo" title='{pasteWord name='logo_tag_title'}'><img src="/img/logo.png" alt='{pasteWord name='logo_tag_alt'}' /></a>

				<div class="contacts">
					<span>+7(343)-253-01-00</span>
					<a href="#" class="callme">Заказать звонок</a>
					<a href="#" class="mailme">Написать письмо</a>
				</div>

				<div class="cart">
					<a href="#" class="order">
						<div class="icon"></div>
						<div class="data">
							<span class="count-label" data-type="count"></span>
							<span>Всего на:&nbsp;<b><span data-type="cost"></span>&nbsp;<span data-type="currency_symbol"></span></b></span>
							<span class="info">Нажмите сюда, чтобы оформить заказ</span>
						</div>
					</a>
				</div>

				<nav>
					{include file="mmenu.tpl" }
				</nav>
			</div>
		</div>
		<!--END cap block--> 
		
		<!--content block-->
		<div class="content">
			<div class="centerpos">
				{if isset($breadcrumbs) && count($breadcrumbs)>2}
					<ul class="breadcrumbs">
					{foreach from=$breadcrumbs item=crumb}
						<li>
							{if isset($crumb.url)}
								<a href="{$crumb.url}">{$crumb.name|escape}</a>
							{else}
								<span>{$crumb.name|escape}</span>
							{/if}
						</li>
					{/foreach}
					</ul>
				{/if}

				{if $page.id eq 1}
					<div class="border"></div>
				{/if}

				{if $page.h1 neq ''}
					<h1 class="page-title">{$page.h1}</h1>
				{/if}

				<div class="text">
					{plugin name="catalog"}
					{region name="beforecontent" runame="До контента"}
					{$page.content}
					{region name="aftercontent" runame="После контента"}
				</div>

				{if isset($breadcrumbs) && count($breadcrumbs)>2}
					<ul class="breadcrumbs bottom">
					{foreach from=$breadcrumbs item=crumb}
						<li>
							{if isset($crumb.url)}
								<a href="{$crumb.url}">{$crumb.name|escape}</a>
							{else}
								<span>{$crumb.name|escape}</span>
							{/if}
						</li>
					{/foreach}
					</ul>
				{/if}

			</div>
		</div>
		<!--END content block-->
		
		<!--push for footer-->
		<div class="page-push"></div>
		<!--END push for footer-->

	</div>
</div>

<div class="page-footer">
	<div class="centerpos"></div>
</div>

<a href="#" id="mmenu-btn"></a>
<a href="#" id="mmenu-btn-back">Назад</a>
{/strip}