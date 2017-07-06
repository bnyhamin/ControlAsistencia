<?php
require_once("../Includes/Connection.php");
require_once("../Includes/Seguridad.php");
?>
<!DOCTYPE html>
<html>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    <title><?php echo SistemaNombre();?></title>
    <meta http-equiv='pragma' content='no-cache'>
    <link rel="stylesheet" type="text/css" href="js/librerias/extjs/resources/css/ext-all.css"/>
    <script type="text/javascript" src="js/librerias/extjs/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="js/librerias/extjs/ext-all.js"></script>
    <script type="text/javascript" src="js/librerias/extjs/src/locale/ext-lang-es.js"></script>
    <link  href="css/general.css" rel="stylesheet" type="text/css"/>
    <style type="text/css">
        .ceo-icon{background:transparent url(imagenes/user_gray.png) 0 0 no-repeat !important;}  
        .vice-icon{background:transparent url(imagenes/user_suit.png) 0 0 no-repeat !important;}
        .manager-icon{background:transparent url(imagenes/user_orange.png) 0 0 no-repeat !important;}  
        .hc-icon{background:transparent url(imagenes/user_female.png) 0 0 no-repeat !important;}  
        .pm-icon{background:transparent url(imagenes/user_red.png) 0 0 no-repeat !important;}  
        .developer-icon{background:transparent url(imagenes/user.png) 0 0 no-repeat !important;}  
        .tester-icon{background:transparent url(imagenes/user_green.png) 0 0 no-repeat !important;}
        .maestros-icon{background:transparent url(imagenes/contenido.png) 0 0 no-repeat !important; width: 10px; height: 10px;}
        .comite-icon{background:transparent url(imagenes/Direccion.png) 0 0 no-repeat !important;width: 10px; height: 10px;}
        .requerimientos-icon{background:transparent url(imagenes/organizacion.png) 0 0 no-repeat !important;}
        .capacitacion-icon{background:transparent url(imagenes/snct_antecedentes.png) 0 0 no-repeat !important;}
        .bienestar-icon{background:transparent url(imagenes/images.jpg) 0 0 no-repeat !important;}
        .cargos-icon{background:transparent url(imagenes/ico_organizacion.gif) 0 0 no-repeat !important;}
        .personal-icon{background:transparent url(imagenes/Consejo.png) 0 0 no-repeat !important;}
        .datos-icon{background:transparent url(imagenes/monedas.png) 0 0 no-repeat !important;}
        .referidos-icon{background:transparent url(imagenes/user_green.png) 0 0 no-repeat !important;}
        .encuesta-icon{background:transparent url(imagenes/snct_sala_prensa.png) 0 0 no-repeat !important;}
        .reportes-icon{background:transparent url(imagenes/reportes_icon.jpg) 0 0 no-repeat !important;}
        .salir-icon{background:transparent url(imagenes/desconectar_.png) 0 0 no-repeat !important;}
        .seguridad-icon{background:transparent url(imagenes/config.png) 0 0 no-repeat !important;width: 11px;height: 12px;}
        .money-icon{background:transparent url(imagenes/nomina.png) 0 0 no-repeat !important;width: 11px;height: 12px;}
        .cargas-icon{background:transparent url(imagenes/notepad.png) 0 0 no-repeat !important;width: 11px;height: 12px;}
        .estudios-icon{background:transparent url(imagenes/estudios.png) 0 0 no-repeat !important;width: 11px;height: 12px;}
    </style>
    
    
    <script type="text/javascript">
    Ext.BLANK_IMAGE_URL = 'js/librerias/extjs/resources/images/default/s.gif';
    
    operacion = {
    verManual:function(){
      var manual=''; 
      manual='../manuales/Instructivo_Zeus.pdf';       
      var x=window.open(manual);
    },
    init: function(){
        Ext.QuickTips.init();
        var tree = new Ext.tree.TreePanel({
            title	: 'Menu',
            id		: 'tree-menu',
            autoScroll:true, 
            rootVisible: false,
            lines: false,
            enableDD: true,	
            useArrows: true,
            loader: new Ext.tree.TreeLoader({
                dataUrl:'../controllers/user.controller.php'
            }),
            root: new Ext.tree.AsyncTreeNode()
        });
        
        var newHtm='';
        newHtm='<a href="javascript:operacion.verManual()" border="0" style="color: #000000;" title="Manual Zeus">';
        newHtm+='<img src="imagenes/page_white_acrobat.png"/>';
        newHtm+='</a>';
        
	        var documentacion = new Ext.Panel({
	        	title	: 'Documentación',
	        	html	: '<br/>&nbsp;Manual de Usuario Zeus &nbsp;&nbsp; '+newHtm
                //html	: '<br/>&nbsp;Manual de Usuario Zeus &nbsp;&nbsp; <img src="imagenes/page_word.png"/>&nbsp;&nbsp;<img src="imagenes/page_white_acrobat.png"/>'
	        })
	        
	     
	        
			var cabecera = new Ext.BoxComponent({
				margins	: '0 0 0 0',
				region	: 'north',
				height	: 45,
				contentEl : 'contenedor' 
			});
			
			var pie = new Ext.BoxComponent({
				height	: 30,
				region	: 'south',
				align	: 'center',
				contentEl : 'pie_pagina'
			})
			
			var menu = new Ext.Panel({
				title	: 'Accesos',
				margins	: '0 0 5 15',
				region	: 'west',
				layout	: 'accordion',
				width	: 200,
				
                                items	: [tree, documentacion],
				collapsible : true,
				split	: true,
				minWidth: 200,
				maxWidth: 300
			});
			
			var panelInicio = new Ext.Panel({
				title	: 'Inicio',
				closable: false,
                                html: '<iframe id="container" src="../mensajes.php" style="width:100%;height:100%;border:none"></iframe>'
                                //html: '<iframe id="container" src="../reloj.php" style="width:100%;height:100%;border:none"></iframe>'
			});
			
			this.contenido = new Ext.TabPanel({
				id	: 'panel-principal',
				title	: 'Contenido',
				margins	: '0 15 5 5',
				region	: 'center',
                                border  : false,
				activeTab: 0,
                                enableTabScroll:true,
				items	: [panelInicio]
                                
			});
                        
                        
                        this.tabertab = new Ext.Window({
                            title:'Tabs example',
                            width:600,
                            height:500,
                            //bodyStyle: 'background-color:#fff;',
                            items: this.contenido
                        });
			
			var contenedor = new Ext.Viewport({
				layout 	: 'border',
				items	: [cabecera,menu, this.contenido, pie]		
			});	
			
			tree.on('click', function(n){
                                
				if(n.leaf){
					this.ir(n);
				}
		    }, this);
				
		},
		
		ir: function(n){
                        //alert(n.attributes.pagina_url);
			var tab = Ext.get('tab-' + n.id);
			if(!tab){
				tab = new Ext.Panel({
					id 		: 'tab-' + n.id,
					title :  n.text,
					closable : true,
                                        //enableTabScroll:true,
                                        html: '<iframe id="container" src="'+n.attributes.pagina_url+'" style="width:100%;height:100%;border:none"></iframe>'
				});
				this.contenido.add(tab);
				this.contenido.doLayout();
			}
			this.contenido.activate('tab-' + n.id);
		}
	};
	Ext.onReady(operacion.init, operacion);
    </script>
</head>
    <body>
        
        
        <div id="contenidos" style="display: none;">
		
		<div id="contenedor" style="background: url('imagenes/layout-browser-hd-bg.gif')">
                    <div id="nombre_sistema1">
			    <table width="400" cellpadding="0" cellspacing="0" border="0">
			        <tr>
			            <td style="text-align: center;"><img src="imagenes/logo_atento.gif" alt="logo atento"/></td>
			            <td style="text-align: left;"><p class="texto_14 blanco"><?php echo SistemaNombre()?></p></td>
			        </tr>
			    </table>
		    </div>
		    
                    <div id="login1">
	        	<table border="0" align="right" style="padding-right: 20px;">
	        		<tr>
			          
                                    <td><p class="blanco arriba_5"><?php echo $_SESSION['empleado_nombrecompleto'];?></p></td>
                                    
			            <td><p class="arriba_5">&nbsp;|&nbsp;</p></td>
                                    <td><a href="../cerrarc.php"><img class="click" src="imagenes/desconectar.png" height="20" alt="cerrar sesion" title="cerrar sesion"/></a>&nbsp;&nbsp;</td>
                                    
                                    
		            </tr>
	            </table>
	    	</div>
		</div>
		<p id="pie_pagina" style="text-align:center;font-family:verdana;font-size:12px;">
			Copyright © 2012 Atento Perú
		</p>
	</div>        
    </body>
</html>

