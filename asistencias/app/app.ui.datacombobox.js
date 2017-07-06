/**
 * @author Eespiritu
 */

(function ($){
	$.fn.search=function(_json){
		
		//console.debug($('#'+$(this).attr('id')+' > option'));
		console.debug(this);
		this.loadData(this);

		
		
		return false;
		var setting={
			minchar:3
		}
		if(typeof json!='undefined'){
			setting=json;
		}
		
			var __tmp=[];
			var __list=[];
			var __row=0;
			var __first=false;
			var __selected=null;
			var __item=0;
			var __html='';
			var __pos=0;
			
			__select=$(this).attr('id');
			console.info(__select);
			$('#'+__select).hide().attr('size',5);
			console.debug($('#c_sucursal').html());
			//$("#c_sucursal ").find("option");
			//$.each($(this),function(key,field){
			//console.debug($('#'+__select).find('option'));
			//console.debug($('#'+__select+' > option'));
			//var $options = $('option', $(this));
			//$('#c_sucursal').find(':nth-child(n)');

			console.debug($(this).find(':nth-child(n)'));
			$.each($('#'+__select).find('option'),function(key,field){
			  __tmp[__row]={value:this.value,text:this.text};
			  __row++;
			});

		 	return this.each( function(){
		 	/*
			var __tmp=[];
			var __list=[];
			var __row=0;
			var __first=false;
			var __selected=null;
			var __item=0;
			var __html='';
			var __pos=0;
			
			__select=$(this).attr('id');
			$('#'+__select).hide().attr('size',5);
			//console.debug($('#c_sucursal option'));
			//$("#c_sucursal ").find("option");
			//$.each($(this),function(key,field){
			//console.debug($('#'+__select).find('option'));
			//console.debug($('#'+__select+' > option'));
			//var $options = $('option', $(this));
			//$('#c_sucursal').find(':nth-child(n)');
			
			console.debug($('#'+__select).find(':nth-child(n)'));
			$.each($('#'+__select).find('options'),function(key,field){
			  __tmp[__row]={value:this.value,text:this.text};
			  __row++;
			});
*/
			//return false;
			//$('<input type="text" name="t_search" id="t_search" />').prependTo('#'+__select);
			//$('#'+__select).prepend('<input type="text" name="t_search" id="t_search" />');
			__search=__select+'_search';
			$('<input type="text" name="'+__search+'" id="'+__search+'" size="50" autocomplete="off" /><br/>').insertBefore('#'+__select);
									
			$('#'+__search).keyup(function(e){
			  if(e.keyCode==40 || e.keyCode==38){
			    $('#'+__select).focus();
			    $('#'+__select+' option:eq(0)').attr('selected','selected');
			  }else{
			    if($('#'+__search).val().length >= setting.minchar){
			      __list=[];
			      __item=0;
			      __html='';
			      for(__pos=0;__pos < __tmp.length; __pos++){
			          var searchPattern = "^"+$('#'+__search).val();
			          var re = new RegExp(searchPattern,"gi");
			          if(__tmp[__pos].text.search(re)!= -1){
			             __list[__item]=__tmp[__pos];
			             __item++;
			          }
			      }

			      for(__pos=0;__pos < __list.length; __pos++){
			        __html+='<option value="'+__list[__pos].value+'">'+__list[__pos].text+'</option>';
			      }
			
			      $('#'+__select).empty().append(__html).show();
			      $('#'+__select+' option:eq(0)').attr('selected','selected');
			    }
			  }
			});
						
			$('#'+__select+' option').dblclick(function(){
			 $('#'+__search).val(this.text);
			 $('#'+__select).hide();
			});
			
			$('#'+__select).keydown(function(e){
			 if(e.keyCode==13){
			   $('#'+__search).val($('#'+__select+' :selected').text());
			   $('#'+__select).hide();
			 }
			});
		});
	};
	
	
	$.fn.loadData=function(obj){
		$.each($('#'+$(obj).attr('id')+' option'),function(){
			console.info(this);
		});
	}
})(jQuery);

/*
var __tmp=[];
var __list=[];
var __row=0;
var __first=false;
var __selected=null;
var __item=0;
var __html='';
var __pos=0;
$('#c_sucursal').val('00').hide();
$('#c_sucursal').attr('size',5).hide();
$.each($('#c_sucursal option'),function(){
  __tmp[__row]={value:this.value,text:this.text};
  __row++;
});

$('#t_search').keyup(function(e){
  if(e.keyCode==40 || e.keyCode==38){
    $('#c_sucursal').focus();
    $('#c_sucursal option:eq(0)').attr('selected','selected');
  }else{
    if($('#t_search').val().length >= 3){
      __list=[];
      __item=0;
      __html='';
      for(__pos=0;__pos < __tmp.length; __pos++){
          var searchPattern = "^"+$(this).val();
          var re = new RegExp(searchPattern,"gi");
          if(__tmp[__pos].text.search(re)!= -1){
             __list[__item]=__tmp[__pos];
             __item++;
          }
      }
      console.debug(__list);
      for(__pos=0;__pos < __list.length; __pos++){
        __html+='<option value="'+__list[__pos].value+'">'+__list[__pos].text+'</option>';
      }

      $('#c_sucursal').empty().append(__html).show();
      $('#c_sucursal option:eq(0)').attr('selected','selected');
    }
  }
});

$('#c_sucursal option').dblclick(function(){
 $('#t_search').val(this.text);
 $('#c_sucursal').hide();
});

$('#c_sucursal').keydown(function(e){
 if(e.keyCode==13){
   $('#t_search').val($('#c_sucursal :selected').text());
   $('#c_sucursal').hide();
 }
});

*/


/*
(function($) {
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
		});
  };
})(jQuery);
*/