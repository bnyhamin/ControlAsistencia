function lanzarflujo(f,g,cod_empleado){
    var div='';
    var v_movimiento = "";
    var dialogo='';
    var padre='';
    var codigo_emplea=0;
    if(g!="-1") v_movimiento="&movimiento="+ g;
    
    if ( !$("#div_ventanas_modales").length ) {//modal unico
        if($("#hddTipoHistorico").val()=="1") div='dialog_historico_detalle_movimiento';
        else div='dialog_historico_detalle';
        //alert('unico@div'+div);
    }else{//modal multiple
        
        if($("#hddTipoHistorico").val()=="1") padre='div_ventanas_modales_historico_detalle_movimiento';
        else padre='div_ventanas_modales_historico_detalle';
        
        if(typeof cod_empleado!="undefined") codigo_emplea=cod_empleado;
        
        $("#"+padre).empty().append("");
        
        dialogo='dialog_multiple_historico_detalle_movimiento_'+codigo_emplea+"_"+f+"_"+g;
        if ( !$("#"+dialogo).length ) {
            $(document.createElement('div')).attr({
                'id':''+dialogo
            }).appendTo('#'+padre);
        }
        div=dialogo;
    }
    //alert("../Admin/historico_movimientos_flujo.php?codigo=" + f + "@"+ v_movimiento+"c@"+cod_empleado+"@"+padre);
    Ext_Dialogo.genera_Dialog(800,400,'Flujo',"../Admin/historico_movimientos_flujo.php?codigo=" + f + ""+ v_movimiento, ''+div,20,20);
    
}


function lanzarver(em,e,m){
    var div='';
    var dialogo='';
    var padre='';
    
    if ( !$("#div_ventanas_modales").length ) {//modal unico
        
        if($("#hddTipoHistorico").val()=="1") div='dialog_historico_detalle_movimiento';
        else div='dialog_historico_detalle';
        //alert('unico@div'+div);
    }else{//modal multiple
        
        if($("#hddTipoHistorico").val()=="1") padre='div_ventanas_modales_historico_detalle_movimiento';
        else padre='div_ventanas_modales_historico_detalle';
        
        $("#"+padre).empty().append("");
        dialogo='dialog_multiple_historico_detalle_movimiento_'+e+"_"+em+"_"+m;
        if ( !$("#"+dialogo).length ) {
            $(document.createElement('div')).attr({
                'id':''+dialogo
            }).appendTo('#'+padre);
        }
        div=dialogo;
    }
    
    //alert("em@"+em+"e@"+e+"m@"+m+"div@"+div);
    
    Ext_Dialogo.genera_Dialog(600,550,'Detalle_Movimiento',"../Movimientos/movimiento_detalles.php?emp_mov_codigo="  + em + "&codigo=" + e + "&mov_codigo=" +m, ''+div,20,20);
}

function historico(num){//Historico Mov. Utl. Movimientos
    var registro="";
    var regs='';
    var flag_tipo_movimiento=0;
    var n=num;
    //alert('histcom');
    //alert('historico');
    if ( $("#hddRegistro").length ) {//modal unico - existe hddRegistro
        registro = Registro();
        if (registro =="") return;
        registro=registro.split('@')[1];
        flag_tipo_movimiento=1;
        n=2;
        //alert('1');
    }else if ( !$("#div_ventanas_modales").length ) {//modal unico - no existe div_ventanas_modales
        registro = PooGrilla.Registro();
        flag_tipo_movimiento=1;
        //alert('2');
        if ($("#dialog_historico").length ) {
            //alert('@!existe div@dialog_historico');
            $("#dialog_historico").empty().append("");
        }
    }else if ( !$("#hddEmpleado").length ) {//seleccion multiple - no existe hidden
    //alert("hola"); return;
        if ( $("#hddNuevaGrilla").length ) {//seleccion multiple - no existe hidden
            //alert("1");
            registro = PooGrilla.SeleccionMultiple();
        }else{
            //alert("2");
            registro = SeleccionMultiple();
        }
        //registro = SeleccionMultiple();
        //alert('3@1@'+registro);
    }else{
        registro= $.trim($("#hddEmpleado").val());
        flag_tipo_movimiento=1;
        //alert('4');
        //alert('4@'+registro+"@");return;
    }
        
    if (registro =="") return;
    arr = registro.split(',');
    $("#div_ventanas_modales").empty().append("");
    var pixeles=0;
    //alert(arr.length);
    for (i=0; i<arr.length;i++){
            regs= arr[i];
            //alert('3@2@'+regs);
            if ( $("#hddMoviEjecucion").length ) {//si estoy en la opcion Movi Ejecucion
                regs=regs.split('@')[1];
                //alert('3@3@'+regs);
            }
            
            if ( !$("#dialog_"+regs).length ) {
                $(document.createElement('div')).attr({
                    'id':'dialog_'+regs
                }).appendTo('#div_ventanas_modales');
            }

            if(flag_tipo_movimiento==1){
                //alert(n);
                if (n==1) Ext_Dialogo.genera_Dialog(600,500,'Historico', '../Admin/historico_movimientos.php?codigo=' + regs, 'dialog_historico',10,10);
                if (n==2) Ext_Dialogo.genera_Dialog(1000,500,'Historico','../Admin/historico_movimientos.php?codigo=' + regs +'&tipo=2', 'dialog_historico',10,10);
            }else{

                Ext_Dialogo.genera_Dialog(600,500,'Historico',"../Admin/historico_movimientos.php?codigo=" + regs ,'dialog_'+regs,10+pixeles,10+pixeles);
                pixeles=pixeles+10;
            }
    }
    
}

function cmdHistorial_onClick(cod){    
    var rpta='';
    var codigo_encriptar=0;
    //alert('x');
    if(typeof cod=="undefined"){
        rpta=PooGrilla.SeleccionMultiple();
        codigo_encriptar=rpta.split("@")[1];
    }else{
        rpta=cod;
        codigo_encriptar=cod;
    }
    //alert('ccc'+rpta);
    var codigo='';
    if(rpta!=''){
    
        $.ajax({
          url:'../controllers/encriptar.controller.php',
          type:'post',
          dataType:'json',
          data:{
            registro:codigo_encriptar
          },
          success:function(result){
              if(typeof cod!="undefined"){
                  codigo='@'+result;
                  //alert(codigo);
              }else if ( !$("#hddrenovaadm").length ) {
                  codigo=rpta.split("@")[0]+'@'+result+'@'+rpta.split("@")[2]+'@'+rpta.split("@")[3]+'@'+rpta.split("@")[4]+'@'+rpta.split("@")[5];
                  
              }else{//opcion Adm. Renovacion - 6 parametros
                  codigo=rpta.split("@")[0]+'@'+result+'@'+rpta.split("@")[2]+'@'+rpta.split("@")[3]+'@'+rpta.split("@")[4]+'@'+rpta.split("@")[5]+'@'+rpta.split("@")[6];
                  
              }
              
              window.open("../contratos/historial_contratos.php?codigo="+codigo,"listar", "menubar=no,location=no,resizable=yes,scrollbars=yes,toolbar=no,status=yes,width=900px, height=450px" );
            
          }
        });
        
    }
    
}
