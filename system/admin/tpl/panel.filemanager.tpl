<h1>Файловый менеджер</h1>

<div id="elfinder" class="static"></div>


<input type="hidden" name="connector_url" value="{$url}" />


{literal}
<script type="text/javascript">
	var opts = {
			url : "{/literal}{$url}{literal}",
			lang: 'ru',
			getFileCallback:function(file){
				return;
			}
	}	

	var elf = $('#elfinder').elfinder(opts).elfinder('instance');
	$( "#elfinder" ).resizable("destroy");
	$(window).resize(function(){
		$("#elfinder.static").css({ height: $(".state").outerHeight(true) - 52+ "px"})
		$("#elfinder.static .elfinder-workzone").css({height: $(".state").outerHeight(true) -57 - 52 + "px"})
	});
</script>
{/literal}
