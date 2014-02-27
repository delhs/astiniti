
(function( $ ){

  $.validate = function( options ) {
  
   var settings = 	{
						form:'',										/* хендл формы */
						res:'',
						messageClass: 'error_mess',						/* класс сообщения об ошибке */
						triangleClass: 'arrow',							/* класс CSS треугольника сообщения об ошибке */
						durationFlowIn: 300,							/* время анимации появления мс. */
						durationFlowOut: 300,							/* время анимации затухания мс. */
						durationDelay: 3000,							/* время показа мс. */
						marginTop: -12,									/* аналог свойству css margin-top */
						callback: null,									/* callback функция */
						success: null									/* функция, которая выполняется при удачной валидации формы, по умолчанию обновление страницы */
					}
   
	if ( options ) {     $.extend( settings, options );    }
 
	if( settings.callback!=null ) settings.callback();
	
	if( settings.success == null ) settings.success = function(){  window.location.reload();  }

	var d = settings.res;
		
	var d = $.parseJSON(d);
		
		if( d.validate == 'error' )
		{
			$(".error_mess").hide();

			if(  settings.form=='' ){
				$(".validate-error").removeClass("validate-error");
			}else{
				settings.form.find(".validate-error").removeClass("validate-error");
			}
	

			$.each(d, function(k,v){
			
				if(v!="" && k!="validate" ) 
				{
					var selector = ( settings.form=='' ) ? $("form [name='"+k+"']") : settings.form.find("[name='"+k+"']");
					
					var id=Math.floor(Math.random()*10000);
					
					$("body").after('<div id="'+id+'" class="'+settings.messageClass+'">'+v+'</div>');

					selector.addClass("validate-error");

					$("#"+id)
					.append('<div class="'+settings.triangleClass+'"></div>')
					.show()
					.css({opacity:0, top:  selector.offset().top + settings.marginTop   +"px", left:  selector.offset().left +"px"})
					.animate({marginTop:"-"+$("#"+id).outerHeight()+"px",opacity:1	},settings.durationFlowIn)
					.delay( settings.durationDelay )
					.animate({	opacity:0	},settings.durationFlowOut,function(){
						$("#"+id).remove() 
					}) ;
					
					
					var interv = setInterval(function(){
						$("#"+id).css({ top:  selector.offset().top + settings.marginTop +"px", left:  selector.offset().left +"px"});
						if( $("#"+id).size()==0 ){
							clearInterval( interv );
						}
					
					}, 100);
					
				}
				
			});
			
		
		}else settings.success();
  };
  
})( jQuery );
