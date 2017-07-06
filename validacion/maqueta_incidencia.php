<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" >
    <HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
    <link rel="stylesheet" type="text/css" href="../../extjs/resources/css/ext-all.css"/>
    <script type="text/javascript" src="../../extjs/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="../../extjs/ext-all.js"></script>
    <script type="text/javascript" src="../../extjs/src/locale/ext-lang-es.js"></script>
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
                    layout:'border',
                    items:[{
                        xtype:"panel",
                        region:"center",
                        width:150,
                        autoScroll:true,
                        html:"<div id='zona_dinamica'>xxx</div>"
                    },{
                        xtype:"panel",
                        region:"west",
                        width:271,
                        html:"Mi Grupo"
                    },{
                        xtype:"panel",
                        region:"north",
                        height: 50,
                        //baseCls: 'x-plain',
                        //title:'<div style="text-align:center;">Validacion de Asistencia de Personal</div>',
                        //html:"Validacion de Asistencia de Personal",
                        //collapsible:true,
                        items:[{
                            xtype:'panel',
                            colspan:1,
                            collapsible:true,
                            layout:'table',
                            baseCls: 'x-plain',
                            cls:"my-header",
                            title:'<div style="text-align:center;">Filtro de Empleados</div>',
                            layoutConfig: {
                                "columns": 1
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
                                    "columns": 1
                                },items:[{
                                    xtype:'panel',
                                    layout:'form',
                                    baseCls: 'x-plain',
                                    labelWidth: 80,
                                    labelAlign: 'right',
                                    colspan:1,
                                    items:[{
                                        xtype:'combo',
                                        name:'cmbarea',
                                        id:'-cmbarea',
                                        fieldLabel:'Area',
                                        emptyText:'Seleccione...',
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
                            }]
                          }
                        
                        ],
                        margins:{top:3,bottom:3,left:3,right:3}
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