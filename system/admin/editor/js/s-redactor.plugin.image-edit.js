if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.imageEdit = {

	init: function(){
		var that = this;

		$(this.$editor).on("click", "img", function(){
			that.setEvent( $(this) );
		});

		$(document).off("click.imageeditmode");
		$(document).on("click.imageeditmode", function(e){

			if( $(e.target).closest(".redactor-imagebox-edit").length ) return;
			that.unsetEvent();
			
		});

		$(that.$editor).off("click.openeditbox");
		$(that.$editor).on("click.openeditbox", ".redactor-imagebox-edit>.ui-wrapper", function(e){
			var img = $(this).find("img");
			that.imageEdit(img);
		});

	},
	setEvent:function( image ){
		var that = this;		
		var $image = image;
		var uiWrapperCss = {};
		if( $image.parent(".redactor-imagebox-edit").length ) return;

		$(that.$editor).on("keypress.blockimageedit", function(e){
			e.preventDefault();
		});

		$image.wrap('<span class="redactor-imagebox-edit"></span>');

		$image.parent(".redactor-imagebox-edit").css({
			position: "relative",
			display: ($image.css("display")=='block') ? 'block' : "inline-block",
			float: $image.css("float")
		});



		if( $image.get(0).style.margin=="0px auto" && $image.get(0).style.display=="block" ){
			uiWrapperCss.margin = "0px auto";
		}	

		$image.resizable();

		$image.parent(".ui-wrapper").css(uiWrapperCss);


		
	},
	unsetEvent:function( callback ){
		var that = this;
		$(that.$editor).off("keypress.blockimageedit");
		$(that.$editor).find(".redactor-imagebox-edit").find("img").each(function(){
			if( $(this).parent(".ui-wrapper").get(0).style.margin=="0px auto"){
				$(this).css({margin:"0px auto", display:"block"});
			}	
			
			try{
				$(this).resizable('destroy');
				$(this).unwrap();
			}catch(e){
				$(this).removeClass("ui-resizable");
			};
			that.sync();
			if( callback && typeof( callback )=="function" ) callback();
		});
	}	
};