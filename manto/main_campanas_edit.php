<?php header("Expires: 0"); ?>
<?php
require_once("../includes/Seguridad.php");
require_once('../../Includes/Connection.php');
?>
<html>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    <meta http-equiv='pragma' content='no-cache'/>
    <link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
    <link href="../../views/js/librerias/extjs/resources/css/ext-all.css" rel="stylesheet" type="text/css"/>
    <title>AFECTO IMPORTAR AUXILAR PLATAFORMA A GAP</title>
	<script src ="../../views/js/librerias/extjs/adapter/ext/ext-base.js" type="text/javascript" ></script>
	<script src ="../../views/js/librerias/extjs/ext-all.js" type="text/javascript" ></script>
    <style>
    .buscar{  
        background: url(../../Images/lupa4.png) no-repeat !important;  
    }  
    </style>
    <script>
    var url_controladores = '../controllers/campana_controller.php'
    operacion = {
        init: function(){
            var proxy = new Ext.data.HttpProxy({
    			api: {
    				read 	: url_controladores + '?operacion=LISTAR_CAMPANAS',
                    update	: url_controladores + '?operacion=GUARDAR_VALOR_TRASPASO'
    			}
		    });
		    
            this.store = new Ext.data.JsonStore({  
                root : 'records',  
                fields : ['value','label'],  
                url: url_controladores,
                baseParams  : { operacion : 'LISTAR_VALORES'}
            });  
            
            this.store.load(); 
            
            var comboGenre = new Ext.form.ComboBox({  
                triggerAction : 'all',  
                displayField : 'label',  
                valueField : 'value',  
                store : this.store  ,
                emptyText : 'Seleccionar'
            });  
            
    		var reader = new Ext.data.JsonReader({
    			totalProperty	: 'total',
    			successProperty	: 'success',	
    			messageProperty	: 'message',
    			idProperty		: 'Cod_Campana',
    			root			: 'data'		
    		},[
    				{name: 'Cod_Campana', allowBlank: false},
    				{name: 'Exp_NombreCorto', allowBlank: false},
    				{name: 'valor', allowBlank: false}
    		]);
    			
    		var writer = new Ext.data.JsonWriter({
    			encode			: true,
    			writeAllFields	: true	
    		});
    		
    		this.storeGrid = new Ext.data.Store({
    			id			: "Cod_Campana",
    			proxy		: proxy,
    			reader		: reader,
                baseParams  : {start:0, limit:25},
                totalProperty : 25,
    			writer		: writer,
    			autoSave	: true	
    		});
    		
            var paginador = new Ext.PagingToolbar({
                store : this.storeGrid,
                displayMsg : 'Mostrando {0}-{1} de {2} Registros ',
                emptyMsg   : 'No se encontraron registros',
                displayInfo : true,
                pageSize    : 25  
            })
            
    		this.storeGrid.load();
            
            
			textField = new Ext.form.TextField({allowBlank: false});
            
            var barraHerramientas = new Ext.Toolbar({  
                defaults:{  
                    iconAlign: 'top'  
                },  
                items: [  
                    
                    {  
                        xtype: 'buttongroup', // <--- grouping the buttons  
                        items:[  
                            {xtype:'label', text:'Buscar Unidad de Servicio '},
                            {xtype:'textfield', id:'filtro_txt', width:250, enableKeyEvents: true},  
                            {iconCls:'buscar', handler: this.buscar, scope: this}  
                        ]  
                    }
                ]  
            });  
		      
            var numberField = new Ext.form.NumberField({allowBlank:false});  
                  
    		this.grid = new Ext.grid.EditorGridPanel({
    			store		: this.storeGrid,
                tbar        : barraHerramientas,
                bbar        : paginador,
    			columns		: [
    				{header:'Codigo', dataIndex:'Cod_Campana',sortable: true,width:50},
    				{header:'Unidad de Servicio', dataIndex:'Exp_NombreCorto',sortable: true ,width:450 },
    				{header:'acción', dataIndex:'valor',sortable: true, editor:comboGenre ,width:100,renderer:this.genre,scope:this }
    			],
    			border		: false,
    			stripeRows	: true
    		});
    		
    		var panel = new Ext.Panel({
    			title	: "AFECTO IMPORTAR AUXILAR PLATAFORMA A GAP",
    			layout	: "fit",
    			width	: 630,
    			height	: 650,
    			items	: [this.grid],
                renderTo : 'panel'
    		});
    		
            //win.show();
            
            
            paginador.on('beforechange',function(bar,params){  
                params.filtro = Ext.getCmp('filtro_txt').getValue();  
            }); 
        },
        
        buscar : function(){
            var filtro = Ext.getCmp('filtro_txt').getValue();
            this.storeGrid.load({params:{filtro:filtro,start:0,limit:25}});  
        },
        
        genre: function(id){  
            var index = this.store.find('value',id);  
            if(index>-1){  
                var record = this.store.getAt(index);  
                return record.get('label');  
            }  
            return value;  
        } 
    }
    Ext.onReady(operacion.init, operacion)
    </script>
</head>
<body class="PageBODY">
<br /><br />
<center>
    <table>
        <tr><td><div id="panel"></div></td></tr>
        <tr><td align="right"><a class='NavigatorLink' href='../menu.php' title='Menu Principal' target='_self'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>[ Principal ]</font></a></td></tr>
    </table>

</center>

</body>
</html>