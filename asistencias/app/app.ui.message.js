(function($) {
	temporizador=false;
	clearTimeout(temporizador); 
	$.fn.information=function(_text){
            
		$.fn.showMessage(this,{type:'information',text:_text});
	};

	$.fn.error=function(_text){
            
		$.fn.showMessage(this,{type:'error',text:_text});
	};

	$.fn.success=function(_text){
		$.fn.showMessage(this,{type:'success',text:_text});
	};

	$.fn.warning=function(_text){
		$.fn.showMessage(this,{type:'warning',text:_text});
	};

  $.fn.showMessage = function(obj,options){
		 return obj.each( function(){		 	
			$(this).removeClass($(this).attr('className')).hide();
			$(this).html(options.text);
			$(this).addClass(options.type).fadeOut(600).fadeIn(600).fadeOut(300).fadeIn(300);
			temporizador=setTimeout("$('div.information,div.error,div.success,div.warning').fadeOut('slow');",10000);
		});
  };
})(jQuery);
