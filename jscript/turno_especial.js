Ext.ns('com.atento.gap');
Ext.BLANK_IMAGE_URL='../../extjs/resources/images/s.gif';
com.atento.gap.TurnoEspecial={
    id:'frmturnoespecial',
    url:'../controllers/turno_especial_controller.php',
    Quitar:function(codigo){
        if(Ext.get('esadmin').getValue()!='OK'){
            Ext.MessageBox.alert('Validacion','No esta Autorizado Eliminar Turno Programado, Comuniquese con el Administrador del Sistema');
            return;
        }
         Ext.MessageBox.confirm('Mensaje', 'Seguro de Quitar el Turno del Empleado?', function(opt){
             if(opt == "yes"){
                 Ext.Ajax.request({
                    url:com.atento.gap.TurnoEspecial.url,
                    method: 'POST',
                    params:{
                        action:'quitarTurno',
                        hddcodigos:codigo,
                        fecha:Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-fecha').getValue().format('d/m/Y')
                    },success: function(response, opts) {
                        var respuesta = Ext.decode(response.responseText);
                        if(respuesta.success){
                            Ext.MessageBox.alert('Exito',respuesta.mensaje);
                            Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-listado_empleados').getStore().load();
                        }else{
                            Ext.Msg.show({
                                title: 'Error',
                                msg: respuesta.mensaje,
                                buttons: Ext.Msg.OK,
                                icon: Ext.Msg.ERROR
                            });  
                        }
                    }
                });
             }
         },this);
        
    },asignarTurno:function(){
        var codigos='';
        if(Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-h_inicio').getValue()*1==0){
            Ext.MessageBox.alert('Validacion', 'Seleccione Hora de Inicio',function(){
                Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-h_inicio').focus(true,10);
            });
            return;
        }
        if(Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-t_tiempo').getValue()*1==0){
            Ext.MessageBox.alert('Validacion', 'Seleccione Tiempo en Horas',function(){
                Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-t_tiempo').focus(true,10);
            });
            return;
        }
        var check = document.getElementsByName('check[]');
        var checkLength = check.length;
        for(var i=0; i < checkLength; i++){
            if(check[i].checked){
                    if (codigos=='') codigos=check[i].value;
                    else codigos+= ',' + check[i].value;
            }
        }
        if (codigos==''){
            Ext.MessageBox.alert('Validacion', 'Seleccione Algun Registros de Empleados');
            return;
        }
	
        Ext.MessageBox.confirm('Mensaje', 'Asignar Turno a Empleados Seleccionados?', function(opt){
                if(opt == "yes"){
                    Ext.Ajax.request({
                        url:com.atento.gap.TurnoEspecial.url,
                        method: 'POST',
                        params:{
                            action:'asignarTurno',
                            hddcodigos:codigos,
                            fecha:Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-fecha').getValue().format('d/m/Y')
                            ,h_inicio:Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-h_inicio').getValue()
                            ,m_inicio:Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-m_inicio').getValue()
                            ,t_tiempo:Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-t_tiempo').getValue()
                            ,empleado_codigo_registro:Ext.get('empleado_codigo').getValue()
                        },success: function(response, opts) {
                            var respuesta = Ext.decode(response.responseText);
                            if(respuesta.success){
                                /*if(respuesta.procesados!=''){//hubieron procesados
                                        Ext.MessageBox.alert('Exito',respuesta.mensaje);
                                        Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-listado_empleados').getStore().load();
                                }else{
                                        var dnis=respuesta.noprocesados.split(',');
                                        var newHtml='';

                                        limpia_Desaparece(Ext.get('no_procesados'));
                                        for(var j=0; j < dnis.length; j++){
                                                newHtml+=' <p> Solo puede asignar turno especial 30 minutos despues de su fin de jornada ';
                                                newHtml+='<span style="color: #DE473B;">'+dnis[j]+'</span> </p>';
                                        }
                                        Ext.DomHelper.append('no_procesados',newHtml,true);
                                        aplica_Efecto(Ext.get('no_procesados'));
                                }*/
                                
                                if(respuesta.procesados!=''){//hubieron procesados
                                        Ext.MessageBox.alert('Exito',respuesta.mensaje);
                                        Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-listado_empleados').getStore().load();
                                }
                                
                                if(respuesta.noprocesados!=''){//hubieron procesados
                                        var dnis=respuesta.noprocesados.split(',');
                                        var newHtml='';

                                        limpia_Desaparece(Ext.get('no_procesados'));
                                        for(var j=0; j < dnis.length; j++){
                                                newHtml+=' <p> Solo puede asignar turno especial 30 minutos despues de su fin de jornada ';
                                                newHtml+='<span style="color: #DE473B;">'+dnis[j]+'</span> </p>';
                                        }
                                        Ext.DomHelper.append('no_procesados',newHtml,true);
                                        aplica_Efecto(Ext.get('no_procesados'));
                                }
                                
                            }else{
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: respuesta.mensaje,
                                    buttons: Ext.Msg.OK,
                                    icon: Ext.Msg.ERROR
                                });  
                            }
                        }
                    });
                }
        },this);
                
    },desactiva_aplicar:function(bandera){
        if(bandera==0) Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-cmdAgrupar').disable();
        if(bandera==1) Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-cmdAgrupar').enable();
    },valida_fecha:function(){
        var fecha_inicio=Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-fecha').getValue().format('d/m/Y').split('/');
        var now=new Date();
        var dia=now.getDate();
        var mes=now.getMonth() + 1;
        var anio=now.getFullYear();
        var tmpmes='';var tmpdia='';
        var atmpmes='';var atmpdia='';
        if (mes*1<=9){tmpmes = '0'+mes*1;}else{tmpmes = mes;}
        if (dia*1<=9){tmpdia = '0'+dia*1;}else{tmpdia = dia;}
        if (fecha_inicio[1]*1<=9){atmpmes = '0'+fecha_inicio[1]*1;}else{atmpmes = fecha_inicio[1];}
        if (fecha_inicio[0]*1<=9){atmpdia = '0'+fecha_inicio[0]*1;}else{atmpdia = fecha_inicio[0];}
        var f_actual = anio+''+tmpmes+''+tmpdia;
        var f_inicio = fecha_inicio[2]+''+atmpmes+''+atmpdia;
        if (f_actual*1>f_inicio*1) return 0;
        else return 1;
    },showImage: function(value, metaData, record, rowIndex, colIndex, store){
        var cadena='';
        if(value=='1'){
                metaData.attr = 'style="white-space:normal;text-align:center;background-color:#F4FA58;"';
                cadena+="<img src='../images/ico/del_app.png' style='cursor:pointer' border='0' title='"+record.data['turno_codigo']+"' ";
                cadena+=" onclick='javascript:com.atento.gap.TurnoEspecial.Quitar("+record.data['empleado_codigo']+")'>";
        }
        return cadena;
    },changeColor: function(value, metaData, record, rowIndex, colIndex, store){
        metaData.attr = 'style="color:#0000FF;"';
        return value;
    },makeCheckBox:function(value, metaData, record, rowIndex, colIndex, store){	
        return '<input type="checkbox" id="chk'+value+'" name="check[]" value="'+value+'_'+record.data["turno_minutos"]+'_'+record.data["empleado_dni"]+'" title="Cod: '+value+'"/>';
    },init:function(){
        Ext.QuickTips.init();
        //Ext.form.Field.prototype.msgTarget = 'under';
        Ext.form.Field.prototype.msgTarget = 'side';
        //variables store grilla
        var cargo_codigo=0;var area_codigo=0;
        var nombres='';var h_inicio=0;
        var m_inicio=0;var responsable_codigo=0;//la session_empleado o el valor del combo
        var esadmin='';var empleado_dni='';
        var fecha='';
		
        var dataAreas = new Ext.data.JsonStore({//COMBO AREA
            url: com.atento.gap.TurnoEspecial.url
            ,root: 'data'
            ,fields: ['codigo', 'descripcion']
            ,autoLoad: true
            ,baseParams: {action:'dataarea',empleado_codigo_registro:Ext.get('empleado_codigo').getValue()}
        });
        dataAreas.on('load',function(store){
                Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-cmbarea').setValue(store.getAt(0).get('codigo'));
        });
		
        var dataCargos = new Ext.data.JsonStore({//COMBO CARGO
            url: com.atento.gap.TurnoEspecial.url
            ,root: 'data'
            ,fields: ['codigo', 'descripcion']
            ,autoLoad: true
            ,baseParams: {action:'datacargo',empleado_codigo_registro:Ext.get('empleado_codigo').getValue()}
        });	
        dataCargos.on('load',function(store){
                Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-cmbcargo').setValue(store.getAt(0).get('codigo'));
        });
				
        var dataResponsable = new Ext.data.JsonStore({//COMBO RESPONSABLE
            url: com.atento.gap.TurnoEspecial.url
            ,root: 'data'
            ,fields: ['codigo', 'descripcion']
            ,autoLoad: true
            ,baseParams: {action:'dataresponsable',empleado_codigo_registro:Ext.get('empleado_codigo').getValue()}
        });	
        dataResponsable.on('load',function(store){
            Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-responsable_codigo').setValue(Ext.get('empleado_codigo').getValue());
        });
		
        var dataHora = new Ext.data.JsonStore({//COMBO HORA
            url: com.atento.gap.TurnoEspecial.url
            ,root: 'data'
            ,fields: ['codigo', 'descripcion']
            ,autoLoad: true
            ,baseParams: {action:'datahora'}
        });
        dataHora.load({
                callback:function(){
                        Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-h_inicio').setValue(dataHora.getAt(0).get('codigo'));
                }
        });
		
        var dataMinuto = new Ext.data.JsonStore({//COMBO MINUTO
            url: com.atento.gap.TurnoEspecial.url
            ,root: 'data'
            ,fields: ['codigo', 'descripcion']
            ,autoLoad: true
            ,baseParams: {action:'dataminuto'}
        });	
        dataMinuto.on('load',function(store){
                Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-m_inicio').setValue(store.getAt(0).get('codigo'));
        });
		
        var dataTiempoHora = new Ext.data.JsonStore({//COMBO TIEMPO
            url: com.atento.gap.TurnoEspecial.url
            ,root: 'data'
            ,fields: ['codigo', 'descripcion']
            ,autoLoad: true
            ,baseParams: {action:'datatiempohoras'}
        });	
        dataTiempoHora.on('load',function(store){
                Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-t_tiempo').setValue(store.getAt(0).get('codigo'));
        });
		
        //--recoger parametros y enviarlos para traer informacion--
        responsable_codigo=Ext.get('empleado_codigo').getValue();
        esadmin=Ext.get('esadmin').getValue();
        fecha=Ext.get('fecha').getValue();
		
        var datosEmpleados = new Ext.data.JsonStore({//STORE GRILLA
            url: com.atento.gap.TurnoEspecial.url,
            root: 'data',
            //totalProperty: 'totales',
            fields: ['fila','empleado_codigo','turno_minutos','empleado_dni','empleado_nombres','empleado_area',
                    'turno_descripcion','turno_especial','empleado_entrada','empleado_salida','eliminar_turno','turno_codigo'],
            autoLoad: true
            //,baseParams: {action:'dataempleados'}
        });
	
        datosEmpleados.on('beforeload', function(store, options) {
            options.params.action = 'dataempleados';
            options.params.cargo_codigo = cargo_codigo;
            options.params.area_codigo = area_codigo;
            options.params.nombres = nombres;
            options.params.h_inicio = h_inicio;
            options.params.m_inicio = m_inicio;
            options.params.responsable_codigo = responsable_codigo;
            options.params.esadmin = esadmin;
            options.params.empleado_dni = empleado_dni;
            options.params.fecha = fecha;
            return true;
        });
	
        //definicion de la grilla
        var grid=new Ext.grid.GridPanel({
            title:'Listado de Ejecutivos para Asignar Turno Especial',
            id:com.atento.gap.TurnoEspecial.id+'-listado_empleados',
            store:datosEmpleados,
            width:1030,
            stripeRows:true,
            animCollapse:true,
            loadMask: {msg: 'Cargando Datos'},
            height: 380,
            autoScroll:true,
            columnLines:true,
            columns:[
                {id:'fila',header:'N°',width:35,sortable:true,dataIndex:'fila'},
                {id:'selecciona',header:'Sel.',width:35,sortable:false,dataIndex:'empleado_codigo',renderer : this.makeCheckBox},
                {id:'dni',header:'DNI',width:60,sortable:true,dataIndex:'empleado_dni'},
                {id:'nombres',header:'Nombres',width:250,sortable:true,dataIndex:'empleado_nombres',renderer:this.changeColor},
                {id:'area',header:'Area',width:260,sortable:true,dataIndex:'empleado_area'},
                {id:'turno_descripcion',header:'T Progr.',width:100,sortable:true,dataIndex:'turno_descripcion'},
                {id:'turno_especial',header:'T Espec.',width:95,sortable:true,dataIndex:'turno_especial'},
                {id:'entrada',header:'Entrada',width:70,sortable:true,dataIndex:'empleado_entrada'},
                {id:'salida',header:'Salida',width:70,sortable:true,dataIndex:'empleado_salida'},
                {id:'quitar',header:'Quitar',width:50,sortable:true,dataIndex:'eliminar_turno',renderer: this.showImage}
            ]
        });
        var table_fillter=new Ext.Panel({
            title:'Asignar Turno Especial'
            ,height:700
            ,renderTo: Ext.getBody()
            ,width:'1070'
            ,layout:'fit'
            ,items:[{
                xtype:'panel',
                layout:'table',
                cls: 'x-table-layout-cell-top-align',
                layoutConfig: {
                    "columns": 2
                },
                defaults: {
                    autoHeight:true
                },		
                items:[{
                    xtype:'panel',
                    colspan:2,
                    collapsible:true,
                    layout:'table',
                    baseCls: 'x-plain',
                    cls:"my-header",
                    title:'<div style="text-align:center;">Filtro de Empleados</div>',
                    layoutConfig: {
                        "columns": 2
                    },
                    defaults: {
                        autoHeight:true,
                        bodyStyle:'padding:5px 5px 5px 5px;'
                    },
                    items:[{//OBJETO 1 [NOMBRE DNI AREA]
                    xtype:'panel',
                    width:"580",
                    layout:'table',
                    baseCls: 'x-plain',
                    layoutConfig: {
                        "columns": 2
                    },items:[{
                        xtype:'panel',
                        layout:'form',
                        labelAlign: 'right',
                        labelWidth: 80,
                        baseCls: 'x-plain',
                        items:[{
                            xtype: 'textfield',
                            fieldLabel: 'Por Nombre',
                            id:com.atento.gap.TurnoEspecial.id+'-nombres',
                            width :200,
                            //labelStyle:labelStyle,
                            //style:boxStyleActive,
                            height:aheight,
                            msgTarget : 'firstNameError',
                            msgDisplay: 'block',
                            listeners: {
                                    render: function(c) {
                                      Ext.QuickTips.register({
                                            target: c.getEl(),
                                            text: 'Ingrese Nombre a Buscar'
                                      });
                                    }
                            }
                        }]
                    },{
                        xtype:'panel',
                        layout:'form',
                        baseCls: 'x-plain',
                        labelAlign: 'right',
                        labelWidth: 28,
                        items:[{
                            xtype:'textfield',
                            fieldLabel:'&nbsp;Dni',
                            id:com.atento.gap.TurnoEspecial.id+'empleado_dni',
                            width :70,
                            height:aheight,
                            vtype:'dni'
                        }]
						
                    },{
                        xtype:'panel',
                        layout:'form',
                        baseCls: 'x-plain',
                        labelWidth: 80,
                        labelAlign: 'right',
                        colspan:2,
                        items:[{
                                xtype:'combo',
                                name:'cmbarea',
                                id:com.atento.gap.TurnoEspecial.id+'-cmbarea',
                                fieldLabel:'Area',
                                emptyText:'Seleccione...',
                                store:dataAreas,
                                displayField:'descripcion',
                                valueField:'codigo',
                                triggerAction:'all',
                                mode:'local',
                                selectOnFocus: true,
                                width: 305,
                                typeAhead: true,
                                editable: false
                        }]
                    }]
                },{//OBJETO 02 [CARGO RESPONSABLE]
                    xtype:'panel',
                    baseCls: 'x-plain',
                    layout:'form',
                    width:"480",
                    labelWidth: 71,
                    labelAlign: 'right',
                    items:[{						
                            xtype:'combo',
                            name:'cmbcargo',
                            id:com.atento.gap.TurnoEspecial.id+'-cmbcargo',
                            fieldLabel:'Cargo',
                            store:dataCargos,
                            displayField:'descripcion',
                            valueField:'codigo',
                            triggerAction:'all',
                            mode:'local',
                            selectOnFocus: true,
                            width: 305,
                            typeAhead: true,
                            editable: false
                    },{						
                            xtype:'combo',
                            id:com.atento.gap.TurnoEspecial.id+'-responsable_codigo',
                            fieldLabel:'Responsable',
                            store:dataResponsable,
                            displayField:'descripcion',
                            valueField:'codigo',
                            triggerAction:'all',
                            mode:'local',
                            selectOnFocus: true,
                            width: 305,
                            typeAhead: true,
                            editable: false
                    }]
                },{//LAYOUT TABLE FILA 2
                    xtype:'panel',
                    colspan:2,
                    baseCls: 'x-plain',
                    items:[{//OBJETO 03
                        layout:'hbox',
                        baseCls: 'x-plain',
                        layoutConfig:{
                                padding:'2',
                                pack:'center',
                                align:'middle'
                        },
                        items:[{
                            baseCls: 'x-plain',
                            layout:'form',
                            labelWidth: 42,
                            labelAlign: 'right',
                            width: 180,//160
                            items:[{
                                xtype:'datefield',
                                fieldLabel:'Fecha',
                                format:'d/m/Y',
                                allowBlank:true,
                                listeners: {
                                    select: function(dtpIssueDate, date) {
                                        com.atento.gap.TurnoEspecial.desactiva_aplicar(0);
                                    }
                                },
                                id:com.atento.gap.TurnoEspecial.id+'-fecha',
                                editable: false,
                                value:new Date()
                            }]
                        },{
                            baseCls: 'x-plain',
                            layout:'form',
                            labelWidth: 72,
                            labelAlign: 'right', 
                            width: 140,
                            items:[{
                                xtype:'combo',
                                name:'h_inicio',
                                id:com.atento.gap.TurnoEspecial.id+'-h_inicio',
                                fieldLabel:'Inicio Hora',
                                store:dataHora,
                                displayField:'descripcion',
                                valueField:'codigo',
                                triggerAction:'all',
                                mode:'local',
                                selectOnFocus: true,
                                width: 40,
                                typeAhead: true,
                                editable: false,
                                listeners: {
                                    select: function(combo, record, index) {
                                        com.atento.gap.TurnoEspecial.desactiva_aplicar(0);
                                    }
                                }
                            }]
                        },{
                            layout:'form',
                            baseCls: 'x-plain', 
                            labelWidth: 60,
                            labelAlign: 'right',
                            width: 120,
                            items:[{
                                    xtype:'combo',
                                    name:'m_inicio',
                                    id:com.atento.gap.TurnoEspecial.id+'-m_inicio',
                                    fieldLabel:'Minutos',
                                    store:dataMinuto,
                                    displayField:'descripcion',
                                    valueField:'codigo',
                                    triggerAction:'all',
                                    mode:'local',
                                    selectOnFocus: true,
                                    width: 40,
                                    typeAhead: true,
                                    editable: false,
                                    listeners: {
                                        select: function(combo, record, index) {
                                            com.atento.gap.TurnoEspecial.desactiva_aplicar(0);
                                        }
                                    }
                            }]
                        },{
                            layout:'form',
                            baseCls: 'x-plain',
                            labelWidth: 95,
                            labelAlign: 'right',
                            width: 160,
                            items:[{
                                    xtype:'combo',
                                    name:'t_tiempo',
                                    id:com.atento.gap.TurnoEspecial.id+'-t_tiempo',
                                    fieldLabel:'Tiempo Horas',
                                    store:dataTiempoHora,
                                    displayField:'descripcion',
                                    valueField:'codigo',
                                    triggerAction:'all',
                                    mode:'local',
                                    selectOnFocus: true,
                                    width: 40,
                                    typeAhead: true,
                                    editable: false
                            }]
                        },{
                            layout:'form',
                            baseCls: 'x-plain',
                            width: 130,
                            items:[{
                                xtype:'button',
                                text:'Buscar',
                                width:90,
                                style: 'align:right;',
                                icon: '../images/ico/search16.png',              
                                handler: function() {
                                        var bandera_fecha=com.atento.gap.TurnoEspecial.valida_fecha();
                                        limpia_Desaparece(Ext.get('no_procesados'));
                                        if(bandera_fecha==0){
                                            Ext.MessageBox.alert('Informacion', 'Atencion La fecha no puede ser anterior a la fecha actual',function(){
                                                Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-fecha').focus(true,10);
                                            });
                                        }else{
                                            Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-listado_empleados').getStore().removeAll();
                                            cargo_codigo=Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-cmbcargo').getValue();
                                            area_codigo=Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-cmbarea').getValue();
                                            nombres=Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-nombres').getValue();
                                            h_inicio=Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-h_inicio').getValue();
                                            m_inicio=Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-m_inicio').getValue();
                                            responsable_codigo=Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-responsable_codigo').getValue();
                                            esadmin=Ext.get('esadmin').getValue();
                                            empleado_dni=Ext.getCmp(com.atento.gap.TurnoEspecial.id+'empleado_dni').getValue();
                                            fecha=Ext.getCmp(com.atento.gap.TurnoEspecial.id+'-fecha').getValue().format('d/m/Y');
                                            
                                            datosEmpleados. load({
                                                    params:{
                                                            action : 'dataempleados',
                                                            cargo_codigo : cargo_codigo,
                                                            area_codigo : area_codigo,
                                                            nombres : nombres,
                                                            h_inicio : h_inicio,
                                                            m_inicio : m_inicio,
                                                            responsable_codigo : responsable_codigo,
                                                            esadmin : esadmin,
                                                            empleado_dni : empleado_dni,
                                                            fecha : fecha
                                                    }
                                            });
                                            com.atento.gap.TurnoEspecial.desactiva_aplicar(1);
                                        }
                                    }
                                }]
                            }]
                        }]
                    },{//LYT [ASIGNAR CERRAR]  
                        xtype:'panel',
                        baseCls: 'x-plain',
                        colspan:2,
                        items:[{
                                layout:'hbox',
                                baseCls: 'x-plain',
                                layoutConfig:{
                                        padding:'5',
                                        pack:'center',
                                        align:'middle'
                                },
                                items: [{
                                    layout:'form',
                                    baseCls: 'x-plain',
                                    width:120,
                                    items:[{
                                        xtype:'button',
                                        text:'Asignar',
                                        icon: '../images/ico/save.png',
                                        id:com.atento.gap.TurnoEspecial.id+'-cmdAgrupar',
                                        width:90,scope:this,
                                        handler:this.asignarTurno
                                    }]
                                },{
                                    layout:'form',
                                    baseCls: 'x-plain',
                                    width:120,
                                    items:[{
                                            xtype:'button',
                                            text:'Cerrar',
                                            icon: '../images/ico/cancel.png',
                                            width:90,
                                            handler: function() {
                                                
                                                self.location.href='../menu.php';
                                            }
                                    }]
                                }]					
                            }]	
                        }
                    ]
                },{
                    xtype:'panel',
                    colspan:2,
                    baseCls: 'x-plain',
                    items:[{
                            layout:'hbox',
                            baseCls: 'x-plain',
                            layoutConfig:{
                                    padding:'5',
                                    pack:'center',
                                    align:'middle'
                            },
                            items:[grid]			
                    }]
                }]
            }]
        });
    }
}
Ext.onReady(com.atento.gap.TurnoEspecial.init,com.atento.gap.TurnoEspecial);