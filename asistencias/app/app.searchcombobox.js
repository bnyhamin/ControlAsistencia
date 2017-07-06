/**
 * @author mcabada
 */

(function($) {
  $.fn.searchbox = function(objbox,objtext){
		var __tmp=[];
		var __row=0;
		var __first=false;
		var __selected=null;
		$('#'+objbox).val('00').hide();
		$('#'+objbox).attr('size',5).hide();
		$.each($('#'+objbox+' option'),function(){
		  __tmp[__row]={value:this.value,text:this.text};
		  __row++;
		});
		$('#'+objtext).keyup(function(e){
		  if(e.keyCode==37 || e.keyCode==39 || e.keyCode==20 || e.keyCode==17 || e.keyCode==18 || e.keyCode==9 || e.keyCode==144){
		   return false;
		  }
		
		  $('#'+objbox).show();
		  if(e.keyCode==40 || e.keyCode==38){
		      //console.info($('#c_sucursal option:selected'));
		      $('#'+objbox).focus();
		      $('#'+objbox+' option:selected').attr('selected',true).focus();
		  }else{
		      $('#'+objbox+' option').show();
		      __selected=null;
		      $('#'+objbox+' option').hide();
		      for(__pos=0;__pos < __tmp.length; __pos++){
		          var searchPattern = "^"+$(this).val();
		          var re = new RegExp(searchPattern,"gi");
		          if(__tmp[__pos].text.search(re)!= -1){
		                //console.info('#c_sucursal option[value="'+__tmp[__pos].value+'"]');
		                $('#'+objbox+' option[value^="'+__tmp[__pos].value+'"]').show();
		                if(__selected==null){
		                    __selected=__tmp[__pos].value;
		                }
		          }
		      }
		      $('#'+objbox).val(__selected).attr('selected',true);
		  }
		});
		
		$('#'+objbox+' option').dblclick(function(){
			$('#'+objtext).val(this.text);
			$('#'+objbox).hide();
		});
		
		$('#'+objbox).keydown(function(e){
			if(e.keyCode==13){
			  $('#'+objtext).val($('#'+objbox+' :selected').text());
			  $('#'+objbox).hide();
			}
	    if(e.keyCode==40){
	      if($('#'+objbox+' option:selected').next().css('display')=='none'){
	          $('#'+objbox+' option:selected').prev().attr('selected',true).focus();
	          return false;
	      }
	    }
	    if(e.keyCode==38){
	      if($('#'+objbox+' option:selected').prev().css('display')=='none'){
	          $('#'+objbox+' option:selected').next().attr('selected',true).focus();
	          return false;
	      }
	    }
		});		
		
/*
		var tempList=[]
		var row=0;
		var objselect=this;
		// return obj.each( function(){		 	
		$(this).css('display','none');
		$(objselect).attr('size','10');

		$(objselect).parent().prepend('<input type="text" name="__search" id="__search" />');				
		
		$.each($(objselect),function(key,select){
			$.each(select,function(key,objoption){
		    tempList[row]={
		        value:objoption.value,
		        text:objoption.text
		    }
		    row++;				
			})
		});
		
		
		var numShown=0;
		$('#__search').keyup(function(){
			var searchPattern = "^"+$('#__search').val();
			var re = new RegExp(searchPattern,"gi");
			console.info(searchPattern);
			if($.trim(this.value)==''){
				$('#__lsttmp').empty();
			}
	    for(i = 0; i < tempList.length; i++)
	    {
				reg=tempList[i];
					console.info(reg);
					console.info(reg.text.search(re));
	        if(reg.text.search(re) != -1)
	        {
	            //selectObj[numShown] = new Option(functionlist[i],"");
	            //numShown++;
	        }
	    }
			
		});
		//});
  };*/
})(jQuery);