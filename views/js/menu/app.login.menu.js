$(document).ready(function(){
    $('#empleado_password').keyup(function(e){
        var tecla=(document.all)?e.keyCode:e.which;
        if(tecla==13){
            Ext_Sistema.Ext_Login();
        }}
    );
        
    $('#b_login').click(Ext_Sistema.Ext_Login);
    
    $("#empleado_usuario").keyup(function(e){
       var tecla=(document.all)?e.keyCode:e.which;
       if(tecla==37 || tecla==39 || tecla==46){//37:left arrow 39:right arrow 46:delete
       }else Ext_Sistema.validaN();
   });

    $('#empleado_usuario').keyup(function(e){
        var tecla=(document.all)?e.keyCode:e.which;
        if(tecla==13){
            if (this.value.length ==8){
                $('#empleado_password').focus();
            }
        }}
    );

});

function launchchange(){
            window.showModalDialog("cambioclave/index.php", "CambioClave", "dialogHeight:600px;dialogWidth:700px");
}

Ext_Sistema={
	pathCont:'controllers/login.controller.php',
	path:'../libs/FrontController.php',
        validaN:function(){
		if ($("#empleado_usuario").val()!=""){
			$("#empleado_usuario").val($("#empleado_usuario").val().replace(/[^0-9\.]/g, ""));
		}
	},
        Ext_Login:function(){
            
            if($.trim($("#empleado_usuario").val())==""){
                $('#MensajeCarga').information('Debe Ingresar Usuario');
                $('#empleado_usuario').focus();
                return;
            }
            
            if ($.trim($("#empleado_usuario").val()).length <8){
		//alert('Numero de caracteres de DNI incorrecto');
                $('#MensajeCarga').information('Numero de caracteres de DNI incorrecto');
		$('#empleado_usuario').focus();
                $('#empleado_usuario').select();
		return;
            }
            
            if($.trim($("#empleado_password").val())==""){
                $('#MensajeCarga').information('Debe Ingresar Clave');
                $('#empleado_password').focus();
                return;
            }
            
            $.ajax({
                url:Ext_Sistema.pathCont,
                type:'post',
                dataType:'json',
                data:{
                        action:'getLogin',
                        controller:'Usuario',
                        vp_usuario:$.trim($('#empleado_usuario').val()),
                        vp_clave:$.trim($('#empleado_password').val())
                },
                success:function(result){
                    if(parseInt(result.status)==1){
                        
                        $("#mensaje_cargando").empty().append("<img src='views/imagenes/cargas/108.gif' border='0' align='middle' />");
                        
                        setTimeout(function(){
                            Ext_Sistema.Ext_Ir();
                        },1000);
                        
                        
                    }else{  
                        if (result.data.indexOf('Cesado') > 0) {
                           cambio_clave_msg = '';


                        }else{

                           cambio_clave_msg = '&nbsp;<a href="javascript:launchchange()" id="changepass">Cambiar clave</a>'
                        }
                        $('#mensaje_valig').error(result.data+cambio_clave_msg);
                    }
                }
            });


        // $('#changepass').click(function(){
        //     window.showModalDialog("cambioclave/index.php", "CambioClave", "dialogHeight:600px;dialogWidth:700px");
        //     return false;
        // })
            
	},Ext_Ir:function(){
            window.location.href="views/ui.menu.php";
	},ExtFormNewPass:function(){
            window.location.href="views/ui.cambiopassword.php";
        },
	Ext_CambioPass:function(){
	   if($.trim($('#tx_login').val())=='' || $.trim($('#tx_pass').val())=='' || $.trim($('#t_newpass').val())=='' || $.trim($('#t_reppass').val())==''){
		    $('#message').information('DEBE INGRESAR LOS DATOS CORRECTOS');
		return false;
	   }else if($.trim($('#t_newpass').val())!= $.trim($('#t_reppass').val())){
	   		$('#message').information('REPITA BIEN LA NUEVA CONTRASE�A');
		return false;
	   }else{
		$.ajax({
			url:Ext_Sistema.path,
			type:'post',
			dataType:'json',
			data:{
				action:'getCambioPass',controller:'Usuario',
				vp_nomuser:$.trim($('#tx_login').val()),
				vp_pass:$.trim($('#tx_pass').val()),
				vp_newpass:$.trim($('#t_newpass').val())
			},
			success:function(result){
				if(parseInt(result.status)==1){
					$('#message').success('SE CAMBIO CORRECTAMENTE EL PASSWORD');
					window.location.href="../index.html";
				}else{$('#message').error('ERROR DATOS INCORRECTOS');}
			}
		});
      }
     },Ext_CancelPass:function(){
         window.location.href="../index.html";

 }}
