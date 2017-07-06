$(document).ready(function(){
  
});

Ext_Mac_Autorizados={
  pathController:'../controllers/controller.macs.autorizados.php',
  Salir:function(){
   self.location.href="../menu.php";   
  },levantaModalMacs:function(accion){
    
    myDialogGrupo=$('#dialog_agrupacion_macs').load('main_mac_autorizados_face.php',{
        accion:accion
    },function(){
      
      if($.trim($("#hdd_accion").val())==="U"){
        Ext_Mac_Autorizados.buscar();
        $('#txt_mac_numero').attr('disabled',true);
      }

    }).dialog({
        title:'Series Autorizadas'
        ,width:300
        ,height:170
        ,modal:true
        ,resizable:true
        ,cache: false
    });

    myDialogGrupo.dialog('open');
    
  },buscar:function(){
    var mac_numero="";
    mac_numero = PooGrilla.Registro();
    if (mac_numero ==="") return;

    $.ajax({
      url:Ext_Mac_Autorizados.pathController,
      type:'post',
      dataType:'json',
      contentType: "application/x-www-form-urlencoded; charset=iso-8859-1",
      data:{
        opcion:'2'
        ,param_mac_numero:mac_numero
      },
      beforeSend:function(){
        var newHtml='';
        newHtml='<img src="../images/cargas/108.gif" border="0">';
        $("#div_carga").empty().append(newHtml);
        
      },
      success:function(result){
        
        if(parseInt(result.status)===1){
            $.each(result.data,function(key,obj){
                $("#txt_mac_numero").val($.trim(obj['mac_numero']));
                if (parseInt(obj['mac_activo'])===1) $("#chk_mac_activo").attr('checked', true);
                else $("#chk_mac_activo").attr('checked', false);
            });
            setTimeout(function(){ $("#div_carga").empty().append(""); }, 1000);
            
        }else{
            alert('No existe');
        }

      }
    });

  },registrarMacs:function(){
    var accion=$.trim($("#hdd_accion").val());
    
    if($.trim($('#txt_mac_numero').val())===""){
      alert("INGRESE SERIE");
      $('#txt_mac_numero').focus();
      return;
    }
    var mac_estado = "0";
    if ($("#chk_mac_activo").is(':checked')) mac_estado="1";
    else mac_estado="0";
    if (confirm('¿Confirme guardar registro?')===false) return;

    $.ajax({
      url:Ext_Mac_Autorizados.pathController,
      type:'post',
      dataType:'json',
      data:{
        opcion:'1'
        ,accion:accion
        ,param_mac_numero:$.trim($('#txt_mac_numero').val())
        ,param_mac_activo:mac_estado
      },
      beforeSend:function(){
      },
      success:function(result){
        
        if(result.respuesta==="1"){
            alert("Grabacion exitosa");
            PooGrilla.recarga();
        }else if(result.respuesta==="2"){
            alert("Serie ya existe");
        }else if(result.respuesta==="0"){
            alert("Ocurrio un error en la grabacion "+result.respuesta);
        }
        
      }
    });

  }

}