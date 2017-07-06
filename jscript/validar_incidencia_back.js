Ext.ns('com.atento.gap');
Ext.BLANK_IMAGE_URL='../../extjs/resources/images/s.gif';
Ext.chart.Chart.CHART_URL = '../../extjs/resources/charts.swf';
com.atento.gap.ValidarIncidencia={
    id:'frmvalidaincidencia',
    url:'../controllers/validar_incidencia_controller.php',
    columnas : ['empleado']
    ,recarga:function(fecha){
        var fec='';
        if(typeof fecha!="undefined"){
            fec=fecha;
        }
        
        this.datosEjecutivos.load({
            params:{
                start:0,
                limit:10,
                find:Ext.get('buscam').getValue(),
                f:fec
            }
        });
    },cargaEjecutivosxFecha:function(fecha_c){//Store
        
        this.datosEjecutivos=new Ext.data.JsonStore({
            url:com.atento.gap.ValidarIncidencia.url,
            root:'data',
            totalProperty:'total',
            fields: ['radio','empleado','asistencia_entrada'
                ,'asistencia_salida','tiempo_minutos','extension_tiempo'
                ,'tipo_extension'],
            baseParams : { action : 'datagrupo',responsable_codigo:Ext.get("empleado_codigo").getValue(),fecha:fecha_c}
        });
        
    },cargarStorePivot:function(extension,tardanza,refrigerio,refrigerio1){//pivot
        dat. load({
            params:{
                action : 'resumenrac',
                empleado_codigo : "4"
            }
        });
    },init:function(){
        var responsable_nombre=Ext.get("empleado_nombre").getValue();
        com.atento.gap.ValidarIncidencia.cargaEjecutivosxFecha(Ext.get("hdd_fecha").getValue());//Cargar Store
        
        this.datosEjecutivos.load({
            params:{start:0,limit:10}
        });
        
        fecha_desde=com.atento.gap.Valida_Fecha.fecha_actual(Ext.get('hdd_fecha').getValue());//obtener la fecha actual
        
        var paginador = new Ext.PagingToolbar({
            store : this.datosEjecutivos,
            pageSize : 10,
            displayInfo : true,
            displayMsg : 'Mostrando {0}-{1} de {2} Registros'
        });
        
        this.grilla = new Ext.grid.GridPanel({
            id:com.atento.gap.ValidarIncidencia.id+'-listado_grupo',
            name:'listado_grupo',
            store : this.datosEjecutivos,
            animCollapse:true,
            height:420,
            border:false,
            stripeRows:true,
            bbar : paginador,
            loadMask: {msg: 'Cargando Datos'},
            listeners: {
                'Afterrender':function(grid,columnIndex,e){
                    var item1=Ext.DomQuery.select('div[class=x-grid3-header-offset] table thead tr td div[class=x-grid3-hd-inner x-grid3-hd-empleado]')[0];
                    var el1=Ext.Element.get(item1);
                    
                    el1.set({
                        onmouseover : 'javascript:com.atento.gap.Grilla.aparece("empleado")',
                        onmouseout : 'javascript:com.atento.gap.Grilla.desaparece("empleado")',
                        unselectable: ''
                    });
                    
                    if(Ext.isIE){
                        var itemIE1=Ext.DomQuery.select('div[class=x-grid3-hd-inner x-grid3-hd-empleado]',this.dom);
                        var elIE1=Ext.Element.get(itemIE1);

                        elIE1.addListener('mouseover',function(event,element,options){
                            var letra=element.className;
                            var pos_ini=letra.lastIndexOf('empleado');
                            var pos_fin=letra.length;
                            var value=letra.substring(pos_ini, pos_fin);
                            com.atento.gap.Grilla.aparece(value);
                        },this,{param1:'the mouse is '});

                        elIE1.on('mouseout',function(event,element,options){
                            var letra=element.className;
                            var pos_ini=letra.lastIndexOf('empleado');
                            var pos_fin=letra.length;
                            var value=letra.substring(pos_ini, pos_fin);
                            com.atento.gap.Grilla.desaparece(value);
                        },this);

                    }
                },'mouseout': function(grid,columnIndex,e){
                    window.x=grid.xy[0];
                    window.y=grid.xy[1];
                    com.atento.gap.Grilla.criterio();
                },'mouseover': function(grid,columnIndex,e){
                   window.x=grid.xy[0];
                   window.y=grid.xy[1];
                }
            },columns  : [
                new Ext.grid.RowNumberer(),
                {id:'radio',header:'Sel.',dataIndex:'radio',width:33},
                {id:'empleado',header:'Empleado',sortable:true,dataIndex: 'empleado',width:250},
                {id:'ingreso',header:'Ingreso',width:60,sortable:true,dataIndex:'asistencia_entrada'},
                {id:'salida',header:'Salida',width:60,sortable:true,dataIndex:'asistencia_salida'},
                {id:'salida',header:'Tardanza',width:60,sortable:true,dataIndex:'tiempo_minutos'},
                {id:'salida',header:'Ext. Turno',width:60,sortable:true,dataIndex:'extension_tiempo'},
                {id:'salida',header:'Tip. Ext.',width:80,sortable:true,dataIndex:'tipo_extension'}
            ]
        });
        
        var filtros=com.atento.gap.Grilla.generaFiltros(com.atento.gap.ValidarIncidencia.columnas);//generar Filtros en divs
        var div_filtro=Ext.get('div_filtros');
        div_filtro.insertHtml('afterBegin',''+filtros+'');
        
        dat = new Ext.data.JsonStore({//STORE GRILLA
            url: com.atento.gap.ValidarIncidencia.url,
            root: 'data',
            fields: ['season','total'],
            autoLoad: true
            ,baseParams: {action:'resumenrac',empleado_codigo:'1'}
        });
        
        var boxarea = new Ext.BoxComponent({
            autoEl: {
                id:com.atento.gap.ValidarIncidencia.id+'-imgareas',
                name:com.atento.gap.ValidarIncidencia.id+'-imgareas',
                tag: 'img',
                src: '../images/ico/door_in.png'
            },listeners: {
                render: function(component){
                    component.getEl().on('click', function(e){
                      com.atento.gap.Valida_Fecha.salir();
                    });
                }
             },cls: 'mano'
        });
        console.info("hola");
        /*Maquetacion*/
        new Ext.Panel({
                title:'Validacion de Asistencia de Personal'
                ,height:800
                ,renderTo: Ext.getBody()
                ,width:1010
                ,layout:'fit'
                ,items:[{
                    xtype:'panel',
                    layout:'table',
                    cls: 'x-table-layout-cell-top-align',
                    layoutConfig: {
                        "columns": 2
                    },
                    /*defaults: {
                        autoHeight:true
                    },*/    
                    items:[{/*Zona 01*/
                        xtype:'panel',
                        colspan:2,
                        collapsible:true,
                        layout:'table',
                        baseCls: 'x-plain',
                        cls:"my-header",
                        width:1000,
                        title:'<div style="text-align:center;">Supervisor :  '+responsable_nombre+' </div>',
                        layoutConfig: {
                            "columns": 2
                        },
                        defaults: {
                            autoHeight:true,
                            bodyStyle:'padding:0px 0px 0px 0px;'
                        },
                        items:[{
                            xtype:'panel',
                            layout:'table',
                            baseCls: 'x-plain',
                            layoutConfig: {
                                "columns": 2
                            },items:[{
                                xtype:'panel',
                                layout:'form',
                                baseCls: 'x-plain',
                                labelWidth: 80,
                                labelAlign: 'right',
                                //->colspan:2,
                                items:[{
                                    xtype:'datefield',
                                    fieldLabel:'Fecha',
                                    format:'d/m/Y',
                                    allowBlank:true,
                                    listeners: {
                                        select: function(dtpIssueDate, date) {
                                            var valor=com.atento.gap.Valida_Fecha.validaFecha(Ext.get('hdd_fecha').getValue(),date.format('d/m/Y'),Ext.get('hdd_dias').getValue());//
                                            if(parseInt(valor)==1){
                                                Ext.MessageBox.alert('Alerta!!', 'Fecha NO VALIDA!!.\nNo puede validar fechas menores a  '+Ext.get('hdd_dias').getValue()+' dias.',function(){
                                                });
                                            }else if(parseInt(valor)==2){
                                                Ext.MessageBox.alert('Alerta!!', 'Fecha NO VALIDA!!.\nElija una fecha anterior o igual al actual.',function(){
                                                });
                                            }
                                            if(parseInt(valor)!=0){    
                                                //--setear fecha previa
                                                var _fecha_prev=Ext.getCmp(com.atento.gap.ValidarIncidencia.id+'-hdd_fecha_selected').getValue();
                                                Ext.getCmp(com.atento.gap.ValidarIncidencia.id+'-txt_fecha').setValue(com.atento.gap.Valida_Fecha.fecha_actual(_fecha_prev));
                                            }else{//es 0=OK
                                                //--setear fecha seleccionada y refrescar store
                                                Ext.getCmp(com.atento.gap.ValidarIncidencia.id+'-hdd_fecha_selected').setValue(date.format('d/m/Y'));
                                                com.atento.gap.ValidarIncidencia.recarga(Ext.getCmp(com.atento.gap.ValidarIncidencia.id+'-hdd_fecha_selected').getValue());
                                            }
                                            
                                        }
                                    },
                                    id:com.atento.gap.ValidarIncidencia.id+'-txt_fecha',
                                    editable: false,
                                    value:fecha_desde
                                },{
                                    xtype:'hidden',
                                    name:com.atento.gap.ValidarIncidencia.id+'-hdd_fecha_selected',
                                    id:com.atento.gap.ValidarIncidencia.id+'-hdd_fecha_selected',
                                    value:Ext.get("hdd_fecha").getValue()
                                }]
                            },{
                               xtype:'panel',
                               layout:'form',
                               baseCls: 'x-plain',
                               bodyStyle:'margin:0px 0px 0px 10px',
                               items:[boxarea]
                            }]
                        }]/*Zona 01*/
                    },{/*Zona 02 C1*/
                        xtype:'panel',
                        //layout:'table',
                        rowspan:2,
                        baseCls: 'x-plain',
                        /*layoutConfig: {
                            "columns": 1
                        },*/items:[{
                            closable: false,
                            width   : 700,
                            height  : 700,
                            tbar:com.atento.gap.Menu.generarMenu()
                            ,layout  : "border",
                            items   : [{
                                xtype:"panel",
                                region:"north",
                                height: 40,
                                html:com.atento.gap.Grilla.generaBuscarOrden(),//buscar y orden
                                margins:{top:3,bottom:3,left:3,right:3}
                            },{
                                xtype:"panel",
                                region:"center",
                                items:[
                                    this.grilla
                                ]
                            }]
                        }]
                        /*Zona 02 C1*/
                    },{/*Zona 02 C2*/
                        xtype:'panel',
                        collapsible:true,
                        layout:'table',
                        baseCls: 'x-plain',
                        height: 50,
                        //cls:"my-header",
                        cls: 'x-table-layout-cell-top-align',
                        width:300,
                        title:'<div style="text-align:center;">&nbsp;Resumen Rac Supervisor </div>',
                        /*layoutConfig: {
                            "columns": 1
                        },*/
                        /*defaults: {
                            autoHeight:true
                        },*/
                        items:[{
                            xtype:'panel',
                            layout:'table',
                            //baseCls: 'x-plain',
                            html:'hola1'
                            /*layoutConfig: {
                                "columns": 1
                            },items:[{
                                xtype   : 'tabpanel',
                                height  : 290,
                                id      : 'tab_requerimientos',
                                activeTab   : 0,
                                plain   :true,
                                items   : [{
                                    layout:'form',
                                    title:'Rac',
                                    id:'tab_rac',
                                    bodyStyle:'padding: 5px',
                                    labelAlign:'top',
                                    items: [{
                                        xtype:'panel',
                                        width: 250,
                                        height: 250,
                                        items: {
                                            //store: this.store_rac,
                                            xtype: 'piechart',
                                            store:dat,
                                            id:com.atento.gap.ValidarIncidencia.id+'-pie_rac',
                                            dataField: 'total',
                                            categoryField:'season',
                                            tips: {
                                                trackMouse: true,
                                                width: 140,
                                                height: 28,
                                                renderer: function(storeItem, item) {
                                                //calculate and display percentage on hover
                                                var total = 0;
                                                dat.each(function(rec) {
                                                total += rec.get('data1');
                                                });
                                                this.setTitle(storeItem.get('name') + ': ' + Math.round(storeItem.get('data1') / total * 100) + '%');
                                                }
                                            },
                                            highlight: {
                                                segment: {
                                                    margin: 20
                                                }
                                            },
                                            label: {//pi
                                                field: 'season',
                                                display: 'outside',
                                                contrast: true,
                                                font: '18px Arial'
                                            },showInLegend: true,
                                            seriesStyles: {
                                                 colors:['#F8869D','#25CDF2',
                                                         '#FFAA3C','#DEFE39'//,
                                                         //*'#AB63F6'] //eSprit 80s
                                            },
                                            extraStyle:{
                                                legend:{
                                                    display: 'bottom',
                                                    padding: 5,
                                                    font:{
                                                        family: 'Tahoma',
                                                        size: 13
                                                    },border:{
                                                        color: '#CBCBCB',
                                                        size: 1
                                                    }
                                                    
                                                    display: 'right',
                                                    padding: 10,
                                                    border:{
                                                        color: '#CBCBCB',
                                                        size: 1
                                                    }
                                                }
                                            }
                                        }
                                    }]
                                },{
                                    layout:'form',
                                    title:'Supervision',
                                    id:'tab_supervisor',
                                    bodyStyle:'padding: 5px',
                                    labelAlign:'top',
                                    items: [{
                                            xtype:'panel',
                                            html:'pivot supervisor'
                                    }]
                                }]
                            }]*/
                        }]
                    },{/*Jornadas*/
                        xtype:'panel',
                        collapsible:true,
                        layout:'table',
                        baseCls: 'x-plain',
                        //cls:"my-header",
                        width:300,
                        cls: 'x-table-layout-cell-top-align',
                        title:'<div style="text-align:center;">&nbsp;Resumen Rac Supervisor </div>',
                        /*layoutConfig: {
                            "columns": 1
                        },*/
                        /*defaults: {
                            autoHeight:true
                        },*/
                        items:[{
                            xtype:'panel',
                            layout:'table',
                            //baseCls: 'x-plain',
                            layoutConfig: {
                                "columns": 1
                            },items:[{
                                xtype   : 'panel',
                                html:'hola'
                            }]
                        }]
                    }/*Jornadas*/
                    ]
                    
                    
                }]
            });
    }
}
Ext.onReady(com.atento.gap.ValidarIncidencia.init,com.atento.gap.ValidarIncidencia);



/*
**
*VALIDAR EL CALENDARIO DE LA FECHA (OK)
*setear la fecha actual cuando halla error en las fechas(OK)
*agregar el icono salir al costado de la fecha
*enlaces java
*http://www.javahispano.org/certificacion/2011/8/11/guia-de-certificacion-java.html
*certificaciones java oracle 2013
*http://josedeveloper.com/tag/scjp/
*http://www.isil.edu.py/index.php?option=com_content&task=view&id=114&Itemid=320
*http://megazine.co/obtener-la-certificaci%C3%B3n-scjp---la-clave-para-convertirse-en-el-mejor-en-java_2ebcf.html
*http://es.prmob.net/java/certificaci%C3%B3n-profesional/sun-certified-profesional-375795.html
*http://preparandoscjp.wordpress.com/2011/04/08/16/
*http://preparandoscjp.wordpress.com/2011/04/08/16/
*certificacion antes de ocjp
*http://preparandoscjp.wordpress.com/guia-rapida/
*tiempo de entrega de certificacion java
*/


/*
**
*refresh piechart extjs 3
*http://technopaper.blogspot.com/2010/05/working-with-extjs-pie-charts.html
*http://hellowahab.wordpress.com/2012/03/22/adding-label-to-extjs-pie-chart-other-than-legend/
*http://www.objis.com/formationextjs/lib/extjs-4.0.0/docs/api/Ext.chart.series.Pie.html
*http://try.sencha.com/extjs/4.0.7/examples/charts/pie/
*http://stackoverflow.com/questions/13230159/extjs-4-1-pie-chart-refresh
*http://stackoverflow.com/questions/13331559/ext-js-update-chart-pie-after-filter
*http://stackoverflow.com/questions/13331559/ext-js-update-chart-pie-after-filter
*http://jlorenzen.blogspot.com/2010/10/how-to-change-extjs-piechart-colors.html
*http://www.sencha.com/forum/showthread.php?148467-How-to-reload-a-Pie-Chart
*http://www.packtpub.com/article/menus-toolbars-and-buttons-ext-js-32
*http://localhost:8100/peru/SisPersonal01/ControlAsistencia/validacion/validar_incidencia.php
*/
