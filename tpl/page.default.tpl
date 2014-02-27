{strip}<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		{if $page.title neq ''}<title>{$page.title|escape}</title>{/if}
		{if $page.meta_robots_all eq '1'}<meta name="robots" content="all"/>
		{/if}
		
		{if $page.meta_robots_noindex eq '1'}<meta name="robots" content="noindex"/>
		{/if}
		
		{if $page.meta_robots_nofollow eq '1'}<meta name="robots" content="nofollow"/>
		{/if}
		
		{if $page.meta_robots_noarchive eq '1'}<meta name="robots" content="noarchive"/>
		{/if}
		
		{if $page.skype_block eq '1'}<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
		{/if}
		
		<meta http-equiv="content-type" content="text/html; charset={$page.charset}"/>
		<meta content="{$page.author|escape}" name="author"/>
		<meta content="{$page.copyright|escape}" name="dcterms.rights"/>
		<!--<meta http-equiv="content-language" content="{$page.language}"/>-->
		<meta name="description" content="{$page.description|escape}"/>
		<meta name="keywords" content="{$page.keywords|escape}"/>

		<link rel="icon" href="/img/favicon.ico" type="image/x-icon"/> 
		<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon"/> 

		{if $page.unite_css neq '1' }{foreach from=$metaBufferCssArray item=href}<link rel="stylesheet" type="text/css" href="{$href}">
		{/foreach}{else}<link rel="stylesheet" type="text/css" href="/css/styles.css"/>{/if}
		<link rel="stylesheet" type="text/css" href="/core.css"/>

		{foreach from=$metaBufferLessArray item=href}<link rel="stylesheet/less" type="text/css" href="{$href}">
		{/foreach}
		
		{if $page.unite_js neq '1' }{foreach from=$metaBufferJsArray item=src}<script type="text/javascript" src="{$src}"></script>
		{/foreach}{else}<script type="text/javascript" src="/js/scripts.js"></script>{/if}
		<script type="text/javascript" src="/core.js"></script>
		{if $editedFromAdmin}<script type="text/javascript" src="/system/admin/editor/ckeditor/ckeditor.js"></script>{/if}

		{$page.extra_meta}
		{$page.super_meta}
	</head>

	<body>
		{include file="$body"}
	</body>
</html>{/strip}