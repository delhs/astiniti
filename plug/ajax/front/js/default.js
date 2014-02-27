var plug_ajax = {
	
	cache:true,
	preloadingPagesInCache:true,
	preloadingPagesInCacheTimeOut:100,

	histAPI:undefined,
	meta:undefined,
	firstRun:true,
	readyStorage:{},
	loading:false,

	init:function(){
		if( this.cache && this.preloadingPagesInCache) plug_ajax.preloadPages.scan();
		this.histAPI=!!(window.history && history.pushState);
		if( this.histAPI ){
			window.onload=function(){ 
				window.setTimeout( 
					function(){ 
						window.addEventListener("popstate", function(e) { 
							plug_ajax.loadPage(document.location.pathname, false);
							e.preventDefault(); 
						},false	); 
				},1); 
			}
		}
		$(document).on("click.ajax", "a", function(e){
			e.preventDefault();
	

			if(
				$(this).attr("rel")=="tofancy" 
				|| 
				$(this).attr("href")==undefined
				||
				$(this).attr("data-type")=="click"
			) return;

			var url = $(this).attr("href");

			if(url.match('#(.+)')!=null){
				if( plug_ajax.histAPI ) history.pushState(null, null, document.location.pathname + url);
				var name = url.match('#(.+)')[1];
				if( $("[name='"+name+"']").length )	$.scrollTo( "[name='"+name+"']", 800);
				return;
			}

			var urlPatterns = [
				'^#',
				'^!#',
				'^javascript'
			];	
			for( var i = 0; i < urlPatterns.length; i++ ){
				if( url.match(urlPatterns[i])!=null ) return;
			}

			if( url.match(/\./gi) && url.match(document.location.host)==null ){
				if( $(this).attr("target")!=undefined && $(this).attr("target")=="_blank" ){
					window.open(this.href,'_blank');
				}else{
					document.location.href = this.href;
				}
				return;
			}
			plug_ajax.loadPage( url );

		});


		if(this.firstRun) $(document).ready(function(){ 
			plug_ajax.readyRun(); 
		});

	},
	loadPage:function(url, addToHistory){

		this.loading = true;
		if( !plug_ajax.cache || plug_ajax.storage.getItem(url)==null ) core.blockScreen();
		
		if( addToHistory==undefined ) var addToHistory = true;
		if( this.histAPI && addToHistory ) history.pushState(null, null, document.location.pathname);
		plug_ajax.animationStart(url, function(){
			if( plug_ajax.cache &&  plug_ajax.storage.getItem(url)!=null ){
				plug_ajax.loadPageResponse(url, plug_ajax.storage.getItem(url));
			}else{
				core.plug.ajax("ajax", {action:"loadPage", url:url}, function(html){
					if( plug_ajax.cache ) plug_ajax.storage.setItem(url, html);
					plug_ajax.loadPageResponse(url, html);
				});
			}
		});
	},
	loadPageResponse:function(url, html){
		if( plug_ajax.histAPI )  history.replaceState(null, null, url);

		var errorMessage = undefined;
		if( html.match(/^301\s/) ){
			document.location.href = html.split(' ')[1];
			return;
		}

		switch( html )
		{
			case '503 1':
				errorMessage = 'Сайт закрыт на профилактические работы.';
			break;
	
			case '503 2':
				errorMessage = 'Сайт закрыт на реконструкцию.';
			break;
	
			case '503 3':
				errorMessage = 'Сайт закрыт на время обновления товаров.';
			break;
	
			case '404':
				errorMessage = '<p style="font-size:50px;font-weight:bold;margin:0;">404</p>Запрашиваемый вами раздел не найден.';
			break;	
		}

		if( errorMessage != undefined){
			core.unblockScreen();
			$.splash( errorMessage, {
				fullscreen:false,
				closeBtn:false,
				cssClass:'splash_fullwidth'
			});
			return;
		}

		plug_ajax.animationEnd(url, html,  function(){
			
			$("head frameset[name='extra_meta']").remove();
			$("head frameset[name='super_meta']").remove();
			
			if( plug_ajax.meta!=undefined ){
				document.title = plug_ajax.meta.title;
				$("meta[name='description']").attr("content", plug_ajax.meta.description);
				$("meta[name='description']").attr("keywords", plug_ajax.meta.keywords);

				
				if( plug_ajax.meta.extra_meta!=undefined ){
					$("head").append('<frameset rows="*" framespacing="0" border="0" name="extra_meta" frameborder="no"><frame>'+plug_ajax.meta.extra_meta+'</frameset>');
				}

				
				if( plug_ajax.meta.super_meta!=undefined ){
					$("head").append('<frameset rows="*" framespacing="0" border="0" name="super_meta" frameborder="no"><frame>'+plug_ajax.meta.super_meta+'</frameset>');
				}

			}else{
				document.title = "";
				$("meta[name='description']").attr("content", "");
				$("meta[name='description']").attr("keywords", "");	
			}
			if( plug_ajax.cache && plug_ajax.preloadingPagesInCache) plug_ajax.preloadPages.scan();
			core.init();
			plug_ajax.firstRun = false;
			plug_ajax.loading = true;
			plug_ajax.readyRun();
			core.unblockScreen();
		});		
	},
	animationStart:function(url, callback){
		document.title = "Загрузка...";
		core.blockScreen();
		if( callback && typeof(callback)=="function" ) callback();
		
	},
	animationEnd:function(url, html, callback){
		$("body").html( html );	
		core.unblockScreen();
		if( callback && typeof(callback)=="function" ) callback();
	},
	preloadPages:{
		pages:[],
		scanPages:[],
		scan:function(){
			$("a[href!='javascript:void(null);'][href!='^http'][href!='^//'][href!='!#'][href!='#'][href!='javascript:;'][rel!='tofancy'][data-type!='click'][href]").each(function(){
				var url = $(this).attr("href");
				if( url.match(/\./gi) && url.match(document.location.host)==null ) return;
				if( plug_ajax.preloadPages.scanPages[url] ==undefined ) plug_ajax.preloadPages.pages.push( url );

			});
			this.load();
		},
		load:function(){
			if( this.pages.length==0 ) return;
			if(plug_ajax.loading) return;
			var url = this.pages[ this.pages.length-1 ];
			this.pages.splice(this.pages.length-1,1);

			core.plug.ajax("ajax", {action:"loadPage", url:url}, function(html){
				if( html!='404'){
					plug_ajax.storage.setItem(url, html);
					plug_ajax.preloadPages.scanPages[url] = url;
				}
				setTimeout(function(){
					plug_ajax.preloadPages.load();
				}, plug_ajax.preloadingPagesInCacheTimeOut);
			});
		}
	},
	storage:{
		data:[],
		setItem:function(key, value){
			this.data[key] =  value;
		},
		getItem:function(key){
			return (this.data[key]==undefined) ? null : this.data[key];
		}
	},
	ready:function(func){
		this.readyStorage[ func.toString().md5()] = func;
	},
	readyRun:function(){
		$.each(this.readyStorage, function(index, func){
			if( typeof(func)=="function" ) func();
		});
	}
}

$(document).ready(function(){
	if( core.browser.name==null) core.browser.detect();

	if( (core.browser.name=="msie" && ( core.browser.version<9 )) || core.adminAirMode ){
		window.plug_ajax = undefined;
		return;
	}else{
		plug_ajax.init();
	}
});