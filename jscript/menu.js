Ext.ns('com.atento.gap');
Ext.BLANK_IMAGE_URL='../../extjs/resources/images/s.gif';
com.atento.gap.Menu={
    id:'frmmenu',
    url:'../controllers/validar_incidencia_controller.php',
    init:function(){
    },_obtener_registro_Empleado:function(){
        var empleadoCodigo = '';
        var check = document.getElementsByName('rdo_emp[]');
        var checkLength = check.length;
        for(var i=0; i < checkLength; i++){
            if(check[i].checked){
                empleadoCodigo=check[i].value;    
            }
        }
        return empleadoCodigo;
    },_registrar_diario:function (){
        CenterWindow("CA_DiarioGestion.php","ModalChild",600,400,"yes","center");
    },_registrar_posicion:function(){
        CenterWindow("PosicionesDia.php","ModalChild",600,400,"yes","center");
    },_cmdRegistrosBatch:function(){
        var _fecha=Ext.getCmp(com.atento.gap.ValidarIncidencia.id+'-hdd_fecha_selected').getValue();
        var _responsable_codigo=Ext.get("empleado_codigo").getValue();
        var _area_codigo=Ext.get("hdd_area").getValue();
        CenterWindow("registro_incidencias_batch.php?responsable_codigo=" +  _responsable_codigo + "&fecha=" + _fecha + "&area=" + _area_codigo,"Batch",600,650,"yes","center");
    },_cmdExtensionTurno:function(){
        var _fecha=Ext.getCmp(com.atento.gap.ValidarIncidencia.id+'-hdd_fecha_selected').getValue();
        var _responsable_codigo=Ext.get("empleado_codigo").getValue();
        var _area_codigo=Ext.get("hdd_area").getValue();
        CenterWindow("extension_turno.php?responsable_codigo=" +  _responsable_codigo + "&fecha=" + _fecha + "&area=" + _area_codigo,"Batch",700,650,"yes","center");
    },_cmdAprobar_Evento:function(){
        var _fecha=Ext.getCmp(com.atento.gap.ValidarIncidencia.id+'-hdd_fecha_selected').getValue();
        var _responsable_codigo=Ext.get("empleado_codigo").getValue();
        var _area_codigo=Ext.get("hdd_area").getValue();
        CenterWindow("../asistencias/eventos_dia_empleado.php?responsable_codigo=" +  _responsable_codigo + "&fecha=" + _fecha + "&area_codigo=" + _area_codigo + "&incidencia_codigo=0","ModalChild",990,700,"yes","center");
    },_cmdVerPermisosPlataformas:function (){
        var registro=com.atento.gap.Menu._obtener_registro_Empleado();
        if (registro==''){
            Ext.MessageBox.alert('Alerta!!','Seleccione algun registro de empleado',function(){
            });
            return;
 	}
        var empleadoCodigo=registro.split("_")[1];
        
        CenterWindow("../asignaciones/bio_plataforma_empleado_asignado.php?empleadoCodigo="+empleadoCodigo,"ModalChild",850,350,"yes","center");

    },_cmdIncidencias:function(){
        //obtener todos los valores
        var registro=com.atento.gap.Menu._obtener_registro_Empleado();
        var responsable_codigo=Ext.get("empleado_codigo").getValue();//--el supervisor que ingresa a la opcion
        var fecha=Ext.getCmp(com.atento.gap.ValidarIncidencia.id+'-hdd_fecha_selected').getValue();
        var area_responsable=Ext.get("hdd_area").getValue();
        
        if(registro !=""){
            var empleado_codigo=registro.split("_")[1];//--el empleado seleccionado
            var num=registro.split("_")[3];//--1=Asistencia 0=Sin Asistencia
            var ivac=registro.split("_")[4];//--1=Vacaciones 0=Sin vacaciones
            if(num!=0){ //--registro de incidencias de asistencia
                var asistencia=registro.split("_")[2];
                var responsable_asistencia=registro.split("_")[5];
                if(ivac!=1){
                    if(responsable_asistencia==1) CenterWindow("registro_incidencias_new.php?asistencia=" + asistencia + "&responsable=" + responsable_codigo + "&empleado=" + empleado_codigo + "&num=" + num + "&fecha=" + fecha + "&area=" + area_responsable,"ModalChild",650,400,"yes","center");     
                    else alert('No ha sido elegido en la asistencia, agreguese como responsable');
                }else{
                    alert('No puede registrar otra incidencia si existe una incidencia de vacaciones!!');
                }
            }else{
                //registro de incidencias sin asistencia definida
                CenterWindow("registro_incidencias_new.php?empleado=" + empleado_codigo + "&responsable=" + responsable_codigo + "&num=" + num + "&fecha=" + fecha + "&area=" + area_responsable,"ModalChild",650,400,"yes","center");
            }
        }else{
            Ext.MessageBox.alert('Alerta!!','Seleccione algun registro de empleado',function(){
            });
        } 
    },hide_Panel:function(_id){
        Ext.getCmp(com.atento.gap.ValidarIncidencia.id+_id).setVisible(false);
    },show_Panel:function(){
        Ext.getCmp(com.atento.gap.ValidarIncidencia.id+'panel-pivot-rac').setVisible(true);
    },generarMenu:function(){//--Menu
        var toolbar=new Ext.Toolbar({
            defaults:{
                iconAlign: 'left'
            },
            items:[{//--Menu 1
                text:'Registro Diario'
                ,iconCls:'cls_registro_diario'
                ,split:true
                ,menu:{
                    items:[{
                       text:'Diario Gestion',
                       handler:function(){
                           com.atento.gap.Menu._registrar_diario();
                       }.createDelegate(this)
                   },{
                       text:'Posiciones del Dia',
                       handler:function(){
                           com.atento.gap.Menu._registrar_posicion();
                       }.createDelegate(this)
                   }]
                }
            },'-',{//--Menu 2
                text:'Asistencias'
                ,iconCls:'cls_registro_asistencias'
                ,split:true
                ,menu:{
                    items:[{
                       text:'Registro Masivo',
                       handler:function(){
                           com.atento.gap.Menu._cmdRegistrosBatch();
                       }.createDelegate(this)
                    },{
                       text:'Extension De Turno',
                       handler:function(){
                           com.atento.gap.Menu._cmdExtensionTurno();
                       }.createDelegate(this)
                    }]
                }
            },'-',{//--Menu 3
                text:'Eventos'
                ,iconCls:'cls_eventos'
                ,split:true
                ,menu:{
                    items:[{
                       text:'Aprobar',
                       handler:function(){
                           com.atento.gap.Menu._cmdAprobar_Evento();
                       }.createDelegate(this)
                    }]
                }
            },'-',{//--Menu 4
                text:'Biometrico Permisos'
                ,iconCls:'cls_biometrico'
                ,split:true
                ,menu:{
                    items:[{
                       text:'Ver Permisos',
                       handler:function(){
                           com.atento.gap.Menu._cmdVerPermisosPlataformas();
                       }.createDelegate(this)
                    }]
                }
            },'-',{//--Menu 5
                text:'Registrar'
                ,iconCls:'cls_registro_incidencias'
                ,split:true
                ,menu:{
                    items:[{
                       text:'Incidencias',
                       handler:function(){
                           com.atento.gap.Menu._cmdIncidencias();
                       }.createDelegate(this)
                    }]
                }
            }]
        });
        return toolbar;
    }
}
Ext.onReady(com.atento.gap.Menu.init,com.atento.gap.Menu);