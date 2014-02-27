if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.wmode = {

	init: function(){

		this.buttonAddAfter( 'html', 'wmode', 'Выделить параграфы', function(buttonName, buttonDOM, buttonObj, e){
			
			if( !$( this.$editor ).hasClass("redactor_editor_wym") ){
				$( this.$editor ).addClass("redactor_editor_wym");
				this.buttonActive('wmode');
				
			}else{
				$( this.$editor ).removeClass("redactor_editor_wym");
				this.buttonInactive('wmode');
			}

		});

	}	
};