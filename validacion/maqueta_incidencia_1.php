<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" >
    <HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
    <link rel="stylesheet" type="text/css" href="../../extjs/resources/css/ext-all.css"/>
    <script type="text/javascript" src="../../extjs/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="../../extjs/ext-all.js"></script>
    <script type="text/javascript" src="../../extjs/src/locale/ext-lang-es.js"></script>
    <link rel="stylesheet" type="text/css" href="../style/style_incidencia.css"/>
    <script type="text/javascript">
    Ext.ns('com.atento.gap');
    Ext.BLANK_IMAGE_URL='../../extjs/resources/images/s.gif';
    com.atento.gap.ValidarIncidencia={
        id:'frmvalidaincidencia',
        url:'../controllers/validar_incidencia_controller.php',
        init:function(){
            
            
            
            
            
            
            
            new Ext.Panel({
                title:'Jornadas pasivas'
                ,height:700
                ,renderTo: Ext.getBody()
                ,width:700
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
                    items:[{///begin tag 1
                        xtype:'panel',
                        colspan:2,
                        collapsible:true,
                        layout:'table',
                        //baseCls: 'x-plain',
                        //cls:"my-header",
                        width:600,
                        title:'<div style="text-align:center;">Filtro de Empleados</div>',
                        layoutConfig: {
                            "columns": 2
                        },
                        defaults: {
                            autoHeight:true,
                            bodyStyle:'padding:5px 5px 5px 5px;'
                        },
                        items:[{
                            //OBJETO 1 [NOMBRE DNI AREA]
                            xtype:'panel',
                            //width:600,
                            layout:'table',
                            //baseCls: 'x-plain',
                            layoutConfig: {
                                "columns": 2
                            },items:[{
                                xtype:'panel',
                                layout:'form',
                                //baseCls: 'x-plain',
                                labelWidth: 80,
                                labelAlign: 'right',
                                colspan:2,
                                items:[{
                                    xtype:'datefield',
                                    name:'cmbarea',
                                    id:'-cmbarea',
                                    fieldLabel:'Area'
                                }]
                            }]
                        }]/*fin tag1*/
                    },{//crear dos columnas
                        xtype:'panel',
                        collapsible:true,
                        layout:'table',
                        //baseCls: 'x-plain',
                        //cls:"my-header",
                        width:400,
                        title:'<div style="text-align:center;">Filtro de Empleados</div>',
                        layoutConfig: {
                            "columns": 1
                        },
                        defaults: {
                            autoHeight:true//,
                            //bodyStyle:'padding:5px 5px 5px 5px;'
                        },
                        items:[{
                            //OBJETO 1 [NOMBRE DNI AREA]
                            xtype:'panel',
                            //width:600,
                            layout:'table',
                            //baseCls: 'x-plain',
                            layoutConfig: {
                                "columns": 1
                            },items:[{
                                title   : 'Maestro de Empresa',
                                closable: false,
                                width   : 300,
                                height  : 300,
                                tbar    : [
                                    '-',
                                    {xtype: 'button', id: 'nuevo', text: 'Nuevo', iconCls : 'btnnuevo',iconAlign: 'top',scope:this},
                                    '-',
                                    {xtype: 'button', id: 'editar', text: 'Editar', iconCls : 'btneditar',iconAlign: 'top',scope:this}
                                ],
                                layout  : "border",
                                items   : [{
                                    xtype:"panel",
                                    region:"north",
                                    height: 40,
                                    html:'txtxtx',//buscar y orden
                                    margins:{top:3,bottom:3,left:3,right:3}
                                },{
                                    xtype:"panel",
                                    region:"center",
                                    html:'jfjfjf'
                                }]
                                /*xtype:'panel',
                                layout:'form',
                                //baseCls: 'x-plain',
                                labelWidth: 80,
                                labelAlign: 'right',
                                colspan:1,
                                items:[{
                                    xtype:'datefield',
                                    name:'cmbarea1',
                                    id:'-cmbarea1',
                                    fieldLabel:'Area'
                                }]*/
                            }]
                        }]
                    },{//crear dos columnas
                        xtype:'panel',
                        collapsible:true,
                        layout:'table',
                        //baseCls: 'x-plain',
                        //cls:"my-header",
                        width:200,
                        title:'<div style="text-align:center;">Filtro de Empleados</div>',
                        layoutConfig: {
                            "columns": 1
                        },
                        defaults: {
                            autoHeight:true//,
                            //bodyStyle:'padding:5px 5px 5px 5px;'
                        },
                        items:[{
                            //OBJETO 1 [NOMBRE DNI AREA]
                            xtype:'panel',
                            //width:600,
                            layout:'table',
                            //baseCls: 'x-plain',
                            layoutConfig: {
                                "columns": 1
                            },items:[{
                                xtype:'panel',
                                layout:'form',
                                //baseCls: 'x-plain',
                                labelWidth: 80,
                                labelAlign: 'right',
                                colspan:1,
                                items:[{
                                    xtype:'datefield',
                                    name:'cmbarea2',
                                    id:'-cmbarea2',
                                    fieldLabel:'Area'
                                }]
                            }]
                        }]
                    }]
                    
                    
                }]
            });
            
            
            
            
        }
    }
    Ext.onReady(com.atento.gap.ValidarIncidencia.init,com.atento.gap.ValidarIncidencia);
    </script>
</head>
<body>
    
    
    
</body>
</html>