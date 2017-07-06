/**
 * @author mcabada
 */
// JavaScript Document
botonera=function(botones){
	var path='css/ico/';
	$.each(botones,function(button,state){
		if(state==true){
			$('#b_'+button).removeClass('negative');
			$('#b_'+button).removeAttr('disabled');
			$('#b_'+button).addClass('positive');
			$('#b_'+button+' img').attr('src',path+($('#b_'+button+' img').attr('alt'))+'.png');
		}else{
			$('#b_'+button).removeClass('positive');
			$('#b_'+button).attr('disabled',true);
			$('#b_'+button).addClass('negative');
			$('#b_'+button+' img').attr('src',path+($('#b_'+button+' img').attr('alt'))+'_grey.png');			
		}
	});
};