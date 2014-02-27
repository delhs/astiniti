if(typeof RedactorPlugins === 'undefined') var RedactorPlugins = {};
RedactorPlugins.html = {
	mixedMode:{
				name: "htmlmixed",
				scriptTypes: [{matches: /\/x-handlebars-template|\/x-mustache/i,
								mode: null},
								{matches: /(text|application)\/(x-)?vb(a|script)/i,
								mode: "vbscript"}]
	},
	open:false,

	init: function(){
 		this.$toolbar.find("a.redactor_btn_html:first").parent("li").remove();
		var id = "e_"+Math.floor(Math.random()*9999);
		
		$( this.$editor ).next().attr("id", id );
		var cm = CodeMirror.fromTextArea( document.getElementById( id ), {mode: RedactorPlugins.html.mixedMode, tabMode: "indent", lineNumbers: true});
		$( this.$editor ).next().next().hide();
		cm.refresh();
		
		this.buttonAddFirst( 'html', 'Исходный код', function(buttonName, buttonDOM, buttonObj, e)
		{
			if( $( this.$editor ).is(":hidden") ){
				this.hideHtml( cm );
			}else{
				this.viewHtml( cm );
			}
		});


		$( this.$editor ).parent(".redactor_box").find( ".CodeMirror-scroll" ).css({width: $( this.$editor ).outerWidth() - 10 + "px"});



	},
	viewHtml:function( cm ){
		this.buttonInactiveVisual();
		var html = this.get();
		
		html = this.cleanReConvertProtected(html);
		
		this.sync();

		cm.setValue( html );
		$( this.$editor ).next().next().css({overflow:"hidden"});
		$( this.$editor ).hide();
		$( this.$editor ).next().next().show();	
		cm.refresh();
		RedactorPlugins.html.open = true;
	},
	hideHtml:function( cm ){
		this.buttonActiveVisual();
		var html = cm.getValue( html );
		
		html = this.cleanConvertProtected(html);
		
		this.set( html);
		$( this.$editor ).next().next().css({overflow:"auto"});
		$( this.$editor ).show();
		$( this.$editor ).next().next().hide();
			this.observeImages();
			this.sync();
		RedactorPlugins.html.open = false;
	}
	
	
	
	
	
	
};