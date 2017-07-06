$(document).ready(function(){
   
   $('#txtPWD').keyup(function(e){
        if(e.keyCode==13){
            Ext_Sistema.Ext_Login();
        }}
    );
   $('#b_login').click(Ext_Sistema.Ext_Login);
   //$("#txtUSR").keyup(Ext_Sistema.validaN);
   $("#txtUSR").keyup(function(e){
       var tecla=(document.all)?e.keyCode:e.which;
       if(tecla==37 || tecla==39 || tecla==46){//37:left arrow 39:right arrow 46:delete
       }else Ext_Sistema.validaN();
   });
   
   $('#txtUSR').keyup(function(e){
        if(e.keyCode==13){
            if (this.value.length ==8){
                $('#txtPWD').focus();
            }
        }}
    );

});

Ext_Sistema={
	pathCont:'controllers/login.controller.php',
        validaN:function(){
            if ($("#txtUSR").val()!=""){
                $("#txtUSR").val($("#txtUSR").val().replace(/[^0-9\.]/g, ""));
            }
	},
        Ext_Login:function(){
            if($.trim($("#txtUSR").val())==""){
                //alert('Indique numero de DNI');
                $('#MensajeCarga').information('Indique numero de DNI');
                $('#txtUSR').focus();
                return;
            }
            if ($.trim($("#txtUSR").val()).length <8){
		//alert('Numero de caracteres de DNI incorrecto');
                $('#MensajeCarga').information('Numero de caracteres de DNI incorrecto');
		$('#txtUSR').focus();
                $('#txtUSR').select();
		return;
            }
            
            if($.trim($("#txtPWD").val())==""){
                //alert('Indique clave de acceso');
                $('#MensajeCarga').information('Indique clave de acceso');
                $('#txtPWD').focus();
                return;
            }
            
            $.ajax({
                url:Ext_Sistema.pathCont,
                type:'post',
                dataType:'json',
                data:{
                        action:'getLogin',
                        vp_usuario:$.trim($('#txtUSR').val()),
                        vp_clave:$.trim($('#txtPWD').val())
                },
                success:function(result){
                    if(parseInt(result.status)==0){
                        alert(result.respuesta);
                        if(parseInt(result.entrada_salida)==1){
                            window.location.href="cerrarc.php";
                        }
                    }
                    
                    if(parseInt(result.status)==1){
                        
                        if(parseInt(result.caducidadporterminar)==1){
                            alert(result.respuesta);
                        }
                        
                        
                        if(parseInt(result.envia_python)==1){//ENVIA PYTHON
                            
                            $("#cod").val(result.dataform["cod"]);
                            $("#tip").val(result.dataform["tip"]);
                            $("#emp").val(result.dataform["emp"]);
                            $("#ip").val(result.dataform["ip"]);
                            
                            $("#mensaje_cargando").empty().append("<img src='images/cargas/108.gif' border='0' align='middle' />");
                        
                            setTimeout(function(){
                                document.formGapPython.action = result.rutaredireccion;
                                document.formGapPython.method = 'POST';
                                document.formGapPython.submit();
                            },1000);
                            
                        }
                        
                        if(parseInt(result.envia_python)==0){//NO ENVIA PYTHON
                            
                            var SmallSizeWidth  = parseInt(result.dimensiones["sizewidth"]);
                            var SmallSizeHeight = parseInt(result.dimensiones["sizeheight"]);
                            var SmallSizeX      = parseInt(result.dimensiones["sizex"]);
                            var SmallSizeY      = parseInt(result.dimensiones["sizey"]);
                            
                            $("#mensaje_cargando").empty().append("<img src='images/cargas/108.gif' border='0' align='middle' />");
                            
                            setTimeout(function(){
                                window.resizeTo(window.screen.availWidth/SmallSizeWidth, window.screen.availHeight/SmallSizeHeight);
                                window.moveTo(SmallSizeX , SmallSizeY);
                                document.location.href = ""+result.rutaredireccion+"";
                            },1000);
                            
                            
                        }
                        
                        
                    }
                    
                }
            });
            
	},//END FUNCTION
        cambiarclave:function(){
            window.showModalDialog("../cambioclave/index.php", "CambioClave", "dialogHeight:550px;dialogWidth:700px");
        }
}
