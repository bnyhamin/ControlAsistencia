(function($){
  $.fn.jTime = function(o) {
    var d = {x:'time-capa',ma:new Date(),i:0};
    var o = $.extend(d, o);
    o.ma = new Date(o.ma);
    m=parseInt($('#minutos_refrescar').val());//CANTIDAD DE MINUTOS
    
    var mHF = function (){
      var ma = new Date(o.ma.getTime() + o.i * 1000);
      s = ma.getSeconds();
      
      if (s<=9) s = '0'+s;
      if(s=='59'){
      	if(parseInt(m)>0) m=m-1;
      	else m=0;
      }
      if(m==0 && s=='00'){
          m=parseInt($('#minutos_refrescar').val());//CANTIDAD DE MINUTOS
          $.ajax({
            url:'../controllers/controller_nro_dias.php',
            type:'post',
            dataType:'json',
            data:{
                action:'load_nro_dias',                              
                area:$('#area').val()
            },
            beforeSend:function(){
            },
            success:function(result){
                $('#'+o.x).val(result.data);
            }
         });  
      }
      o.i += 1;
    }
    return this.each(function(){
      o.x = $(this).attr('id');
      setInterval(mHF,1000);
    });
};
})(jQuery);
