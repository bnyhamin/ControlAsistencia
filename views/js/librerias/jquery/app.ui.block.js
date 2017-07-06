/**
 * @author mcabada
 */
$.pathImageOverlay=null;

$.blockUI=function(msj){
	if(msj=='undefined'){
		msj='ESPERE POR FAVOR.'
	}
	if($('#tmp_overlay,#tmp_content').length==0){
		$('<div id="tmp_overlay"></div>').prependTo(document.body);
		$('<div id="tmp_content">'+msj+'</div>').prependTo(document.body);		
	}
	$('#tmp_overlay').addClass('black_overlay');
	$('#tmp_content').addClass('white_content');
	$('#tmp_overlay,#tmp_content').fadeIn(150)
};

$.unBlockUI = function(msj){
	$('#tmp_content,#tmp_overlay').fadeOut(150,function(){
		$('#tmp_content,#tmp_overlay').remove();
	});
}