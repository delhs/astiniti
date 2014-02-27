<?
$text = "<!--don't remove this code from here-->
<ul class=\"addon_menu_back\">
	<li><a href=\"#\">Назад</a></li>
	<li class=\"separator\"></li>
</ul>
<!--end-->


<!--your module menu code-->
<ul id=\"plug_".$plugName."_menu\" class=\"addon_menu\">
	<li>
		<a href=\"#\">item one</a>
		<ul>
			<li><a href=\"#\" data-action=\"get_tpl_one\">subitem one from one</a></li>
			<li><a href=\"#\" data-action=\"get_tpl_one\">subitem one from one</a></li>
			<li><a href=\"#\" data-action=\"get_tpl_one\">subitem one from one</a></li>
		</ul>
	</li>
	<li>
		<a href=\"#\">item two</a>
		<ul>
			<li><a href=\"#\" data-action=\"get_tpl_one\">subitem one from one</a></li>
			<li><a href=\"#\" data-action=\"get_tpl_one\">subitem one from one</a></li>
			<li><a href=\"#\" data-action=\"get_tpl_one\">subitem one from one</a></li>
		</ul>
	</li>
	<li>
		<a href=\"#\">item tree</a>
		<ul>
			<li><a href=\"#\" data-action=\"get_tpl_one\">subitem one from one</a></li>
			<li><a href=\"#\" data-action=\"get_tpl_one\">subitem one from one</a></li>
			<li><a href=\"#\" data-action=\"get_tpl_one\">subitem one from one</a></li>
		</ul>
	</li>
</ul>
<!--end-->


<div class=\"plug_".$plugName."\">
	{\$welcomescreen}
</div>";
?>