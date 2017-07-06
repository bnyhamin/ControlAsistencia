<?php header("Expires: 0"); 
  require_once("../includes/Seguridad.php");//comentario
  require_once("../../Includes/Connection.php");
  require_once("../../Includes/Constantes.php"); 
  require_once("../includes/MyGrillaEasyUI.php");
  
	$body="";
	$npag = 1;
	$orden = "Codigo";
	$buscam = "";
	$torder="ASC";
	
	if (isset($_POST["pagina"])) $npag = $_POST["pagina"];
	if (isset($_POST["orden"])) $orden = $_POST["orden"];
	if (isset($_POST["buscam"])) $buscam = $_POST["buscam"];
	
	if (isset($_GET["pagina"])) $npag = $_GET["pagina"];
	if (isset($_GET["orden"])) $orden = $_GET["orden"];
	if (isset($_GET["buscam"])) $buscam = $_GET["buscam"];
	
	if (isset($_POST["cboTOrden"])) $torder = $_POST["cboTOrden"];
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Cat&aacute;logo de Plataformas</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css">
<?php  require_once('../includes/librerias_easyui.php');?>

<!-- GC -->
<!-- LIBS -->
<link rel="stylesheet" type="text/css" href="../../extjs/resources/css/ext-all.css" />
<!--<link rel="stylesheet" type="text/css" title="gray"      href="../../extjs/resources/css/xtheme-gray.css" />-->
<script type="text/javascript" src="../../extjs/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="../../extjs/ext-all.js"></script>
<!-- ENDLIBS -->
 <script type="text/javascript">
     
    operacion = {
    
    init: function(){
        
    this.button = Ext.get('cmdModificar'); 
    this.button.on('click', operacion.btnClick, this);
  
    //Ext.MessageBox.confirm('Confirm', 'Are you sure you want to do that?', this.processRpta);   
    },
    
    nuevo : function(){
    	
        var storeLectores = new Ext.data.JsonStore({  
			        url : '../asignaciones/bio_plataforma_lector.php',
			        baseParams : { operacion : 'listar_lectores', plataforma_id :this.plataformaID},
			        root: 'data',                     
			        totalProperty: 'total',  
			        fields: [  'lector_id', 'lector_nombre']
                 
		});  

	    storeLectores.load(); 
        

        this.formularioNuevo = new Ext.form.FormPanel({
            id:'formularioNuevo',
            url : '../asignaciones/bio_plataforma_lector.php',
            autoHeight: true,
            border: false,
            items : [
                { xtype: 'combo' , fieldLabel:'Lector',  name:'lector', submitValue : true, allowBlank: false, forceSelection: true,  store: storeLectores, emptyText:'Selecciona un Lector...', triggerAction: 'all',  editable:false, 
                 valueField: 'lector_id', displayField: 'lector_nombre',id:'lector'},
                /*{ xtype: 'textfield' , fieldLabel:'IP', id: 'ip', name:'ip', allowBlank: false},
                { xtype: 'numberfield' , fieldLabel:'Puerto', id: 'puerto', name:'puerto', allowBlank: false, width: 40},
                { xtype: 'combo' , name:'acceso',  mode: 'local', triggerAction: 'all', fieldLabel:'TipoAcceso', id: 'tipoAcceso', editable:false, forceSelection: true, value:'E',
                 displayField:'name', width: 75, valueField: 'value', store:new Ext.data.JsonStore({
                                    fields : ['name', 'value'],
                                    data   : [
                                        {name : 'Entrada',   value: 'E'},
                                        {name : 'Salida',    value: 'S'}
                                        
                                    ]
                                })},*/
                { xtype: 'checkbox' , fieldLabel:'Activo en Plataforma', id: 'activado', name:'activado', checked: true}
            ]
        });
        var panelFormulario = new Ext.Window({
            title : 'Nueva Asignacion',
            width   : 326,
            modal   : true,
            height  : 150,
          
            items   : this.formularioNuevo,
            bodyStyle : 'padding:10px;background-color: #fff',
            buttons : [
                { text: 'Guardar', handler: this.guardarNuevo , scope: this},
                { text: 'Cancelar', handler: function(){
                        panelFormulario.hide(); 
                        panelFormulario.destroy();
                    } }
            ]
        });
        
        this.panelFormulario = panelFormulario;
        panelFormulario.show();
    },
    
    guardarNuevo : function(){
    
         var storeNuevo = this.store;
         var panelFormularioNuevo = this.panelFormulario;
         var lector_id = Ext.getCmp('lector').getValue();
         var plataformaIDNuevo =  this.plataformaID;
         var response = Ext.Ajax.request({

           url: '../asignaciones/bio_plataforma_lector.php',
           params: {
             lector_id: lector_id,
             plataforma_id: this.plataformaID,
             operacion: 'verificar_plataformas_asignadas'
           },
           success: function(response,store){
           
                 var plataformasAsignadas = response.responseText;
                       
                 if(typeof(plataformasAsignadas) != 'undefined'){ 
                     if(plataformasAsignadas.length>0){
                     
                         var arrrTmp  = plataformasAsignadas.split('|*|');
                         var  mensaje = "";
                          for(i=0; i<arrrTmp.length;i++){
                          
                              mensaje +="- "+ arrrTmp[i]+"\n" ;
                          }
                              mensaje += "\n";
                              mensaje += "Confirmas nueva asignacion?";  
                              
                               if(!confirm("El lector ya se ha asignado a las siguientes plataformas:\n\n"+mensaje)) {
    
                                   return false;
                           }
    
                     }
                 }                 
                 
                   Ext.getCmp('formularioNuevo').getForm().submit({
                           // this.formularioNuevo.getForm().submit({
                           params : { operacion: 'asignar_lector_plataforma',plataforma_id:plataformaIDNuevo, 
                            	       lector_id:Ext.getCmp('lector').getValue()},
                            success : function(form, action){
                                storeNuevo.load();
                                panelFormularioNuevo.hide();
                                panelFormularioNuevo.destroy();
                                Ext.MessageBox.alert('Mensaje', 'Se guardo correctamente');
                            },
                            failure : function(form, action){
                                Ext.MessageBox.alert('Mensaje', 'Ocurrio un Error');
                            },
                            scope: this
                             });
                 
                 
           }
        });
        

    },
    
    editar: function(grid,index,event){
        
         registro = this.sm.getSelected()
         var lector_id = registro.get('lector');

         var storeLectores2 = new Ext.data.JsonStore({  
			        url : '../asignaciones/bio_plataforma_lector.php',
			        baseParams : { operacion : 'listar_lectores', plataforma_id :this.plataformaID, lector_id:lector_id},
			        root: 'data',                     
			        fields: ['lector_id','lector_nombre'],  
			        autoLoad: true
		});  

               
        registro = this.grid.getStore().getAt(index);
        
         this.cmb_lector=new Ext.form.ComboBox({  
                fieldLabel      : 'Lector',
                id              : 'lector',  
                name 			: 'lector',
                forceSelection  : true,  
                store           : storeLectores2,   
                emptyText       :'Seleccionar...',  
                triggerAction   : 'all',  
                editable        : false,  
                mode 		: 'local',
                displayField    : 'lector_nombre',  
                valueField      : 'lector_id',
                //hiddenField     : 'lector_nombre',
                allowBlank      : false
        });    
        
        this.formularioEditar = new Ext.form.FormPanel({
            id:'formularioEditar',
            url : '../asignaciones/bio_plataforma_lector.php',
            autoHeight: true,
            border: false,
            items : [
                this.cmb_lector,
               /* { xtype: 'combo' , 
                fieldLabel:'Lector', 
                            
                  allowBlank: false, 
                  forceSelection: true,  
                  store: storeLectores2, 
                  emptyText:'Selecciona un Lector...', 
                  triggerAction: 'all', 
                   editable:false, 
                 valueField: 'lector_id', 
              
                 displayField: 'lector_nombre',
                 id:'lector'},
                 */
                   
            

                /*{ xtype: 'textfield' , fieldLabel:'IP', id: 'ip', name:'ip', allowBlank: false},
                { xtype: 'numberfield' , fieldLabel:'Puerto', id: 'puerto', name:'puerto', allowBlank: false, width: 40},
                { xtype: 'combo' , width: 75, name:'acceso',  mode: 'local', triggerAction: 'all', fieldLabel:'TipoAcceso', id: 'tipoAcceso', editable:false, forceSelection: true, value:'E',
                 displayField:'name',  valueField: 'value', store:new Ext.data.JsonStore({
                                    fields : ['name', 'value'],
                                    data   : [
                                        {name : 'Entrada',   value: 'E'},
                                        {name : 'Salida',    value: 'S'}
                                        
                                    ]
                                })},*/
                { xtype: 'checkbox' , fieldLabel:'Activo en Plataforma', id: 'activo', name:'activado'}
               
                
            ]
        });
        
   
        var panelFormulario = new Ext.Window({
            title : 'Editar Asignacion',
            width   : 326,
            modal   : true,
            height  : 150,
            items   : this.formularioEditar,
            bodyStyle : 'padding:10px;background-color: #fff',
            buttons : [
                { text: 'Guardar' , handler: this.guardarEditar, scope: this},
                { text: 'Cancelar', handler: function(){
                        panelFormulario.hide(); 
                        panelFormulario.destroy();
                    } }
            ]
        });
        
 

        this.panelFormulario = panelFormulario;
        panelFormulario.show();
        
        this.formularioEditar.getForm().loadRecord(registro);
        
        storeLectores2.load({
        	callback : function(){
        		Ext.getCmp('formularioEditar').getForm().loadRecord(registro);
        	}
        });

    },
    
    guardarEditar : function(){
    
    	   var storeNuevo = this.store;
         var panelFormularioNuevo = this.panelFormulario;
         var lector_id = Ext.getCmp('lector').getValue();
         var plataformaIDNuevo =  this.plataformaID;
         
         Ext.Ajax.request({

           url: '../asignaciones/bio_plataforma_lector.php',
           params: {
             lector_id: lector_id,
             plataforma_id: this.plataformaID,
             edicion: true,
             operacion: 'verificar_plataformas_asignadas'
           },
           success: function(response,store){
           
                 var plataformasAsignadas = response.responseText;
                       
                 if(plataformasAsignadas.length>0){
                 
                     var arrrTmp  = plataformasAsignadas.split('|*|');
                     var  mensaje = "";
                      for(i=0; i<arrrTmp.length;i++){
                      
                          mensaje +="- "+ arrrTmp[i]+"\n" ;
                      }
                          mensaje += "\n";
                          mensaje += "Confirmas nueva asignacion?";  
                          
                           if(!confirm("El lector ya se ha asignado a las siguientes plataformas:\n\n"+mensaje)) {

                               return false;
                       }

                 }
                 
                   
                Ext.getCmp('formularioEditar').getForm().submit({
                	
                    params : { operacion: 'editar_lector_plataforma', id:registro.get('codigo'), lector_id:Ext.getCmp('lector').getValue()},
                    success : function(form, action){
                        storeNuevo.load();
                        panelFormularioNuevo.hide();
                        panelFormularioNuevo.destroy();
                        Ext.MessageBox.alert('Mensaje', 'Actualizo correctamente');
                    },
                    failure : function(form, action){
                        Ext.MessageBox.alert('Mensaje', 'Ocurrio un Error');
                    },
                    scope: this
                });
                 
           }
        });
       
    },
    
    eliminar : function(){
        if( this.sm.getSelected()){
            registro = this.sm.getSelected()
            plataforma_lector_id = registro.get('codigo');
            Ext.Ajax.request({
                url:'../asignaciones/bio_plataforma_lector.php',
                params:{operacion: 'eliminar_lector_plataforma',id:plataforma_lector_id},
                success: function(form, action){
                    this.store.load();
                },
                failure:  function(form, action){
                    Ext.MessageBox.alert('Mensaje', 'Ocurrio un Error');
                },
                scope: this
            });
             
        }else{ 
            Ext.MessageBox.alert('Mensaje', ' Seleccionar registro')
        }
    },
    
    btnClick : function(){
    	

    	var arr_tmp = PooGrilla.Registro().split('@'); 
    	
    	this.plataformaID=arr_tmp[0];
    	this.Plataforma_Descrip=arr_tmp[1];
    	
    	//console.log(this.plataformaID);

    	 if (this.plataformaID != '') {
    	 	

		this.store = new Ext.data.JsonStore({
            url : '../asignaciones/bio_plataforma_lector.php',
            baseParams : { operacion : 'listar_lectores_asignados', plataforma_id : this.plataformaID},
            root    : 'data',
            totalProperty : 'total',
            fields: ['codigo', 'codigoEquipo', 'lector', 'ip', 'puerto', 'tipoAcceso', 'activo', 'lector_nombre'] 
        });
        
        this.store.load();    	   

	    this.sm = new Ext.grid.RowSelectionModel({singleSelect:'true'});
	    

		
		this.grid = new Ext.grid.GridPanel({
			        store: this.store,
			        columns: [
			            {header: 'CodigoAsignacion', sortable: true, dataIndex: 'codigo', width: 110},
			            {header: 'CodigoLector', sortable: true, dataIndex: 'codigoEquipo'},
			            {header: 'Lector', dataIndex: 'lector',  hidden:true},
			            {header: 'Lector', sortable: true, dataIndex: 'lector_nombre', width: 120},
			            {header: 'IP', sortable: true, dataIndex: 'ip'},
			            {header: 'Puerto', sortable: true,dataIndex: 'puerto'},
			            {header: 'TipoAcceso', sortable: true,  dataIndex: 'tipoAcceso'},
			            {header: 'Activo en Plataforma', sortable: true,  dataIndex: 'activo', align: 'center',
            					 width: 140,  xtype: 'booleancolumn', trueText: 'Si', falseText: 'No'}
			        ],
			        stripeRows: true,
			        width: 680,
			        height:320,
			        sm   : this.sm,
			        title: this.Plataforma_Descrip,
			        // config options for stateful behavior
			        stateful: true
			               
       });


         this.grid.on('rowdblclick',this.editar,this); 
          
         var barraHerramientas = [
                {text: 'Nueva Asignacion', handler: this.nuevo, scope: this}
                // {text: 'Nuevo', handler: this.nuevo, scope: this},
               // '-',
               // {text: 'Eliminar', handler: this.eliminar, scope:this}
        ];	
      
           this.win = new Ext.Window({
                	
				    title: 'Listado de lectores asignados a plataforma',
				    closable:true,
				    width: 800,
				    height: 320,
				    tbar : barraHerramientas,
				    plain:true,
				    modal:true,
				    layout: 'fit',
                	items: this.grid
            });

          this.win.show();   
    }
    
    	
    }
    
}
  
 Ext.onReady(operacion.init, operacion);    
     
  </script>

</head>


<body class="PageBODY" onLoad="return WindowResize(10,20,'center')" >

<CENTER class="FormHeaderFont">Cat&aacute;logos de Plataformas</CENTER>
<form id=frm name=frm method=post action="<?php echo $_SERVER["PHP_SELF"];?>">
<TABLE class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    <TR>
        <TD class='ColumnTD'>
            <INPUT class=buttons type='button' value='Asignar Lectores' id='cmdModificar' name='cmdModificar' >
        </TD>
    </TR>
</TABLE>
<?php
    
    $objr = new MyGrilla;
    $objr->setDriver_Coneccion(db_name());
    $objr->setUrl_Coneccion(db_host());
    $objr->setUser(db_user());
    $objr->setPwd(db_pass());
    $objr->setOrder($orden);
    $objr->setFindm($buscam);
    $objr->setNoSeleccionable(false);
    $objr->setFont("color=#000000");
    $objr->setFormatoBto("class=button");
    $objr->setFormaTabla(" class=FormTABLE style='width:100%' cellspacing='1' cellpadding='0' border='0' ");
    $objr->setFormaCabecera(" class=ColumnTD ");
    $objr->setFormaItems(" class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'");
    $objr->setTOrder($torder);
    $from = " [SAPLCCC320].MAPA_ACTIVO_PUESTOS.dbo.vPlataformas ";
	  $objr->setFrom($from);	
    $where= " local_id=3 or (Local_Id = 17 and (Plataforma_Id = 111 or Plataforma_Id = 110)) and plataforma_estado=1";
    //$where= "";
    $objr->setWhere($where);
    $objr->setSize(25);
    $objr->setUrl($_SERVER["PHP_SELF"]);
    $objr->setPage($npag);
    $objr->setMultipleSeleccion(false);
	$objr->ansi = 1;
    // Arreglo de Alias de los Campos de la consulta
    $arrAlias[0] = "root";
    $arrAlias[1] = "Codigo";
    $arrAlias[2] = "Descripcion";
  	$arrAlias[3] = "Local";
	  $arrAlias[4] = "Activo";

    // Arreglo de los Campos de la consulta
    $arrCampos[0] = "cast(Plataforma_Id as varchar)+'@'+Plataforma_Descrip";
    $arrCampos[1] = "Plataforma_Id";
    $arrCampos[2] =	"Plataforma_Descrip";
	  $arrCampos[3] =	"Local_Descrip";
	  $arrCampos[4] =	" CASE WHEN Plataforma_Estado = 1 then 'Si' else 'No' end ";

    
    $objr->setAlias($arrAlias);
    $objr->setCampos($arrCampos);
    $body = $objr->Construir();  //ejecutar
    //echo $objr->getmssql();
    $objr = null;
    echo $body;
    echo "<br>";
    echo Menu("../menu.php");
    
    
?>
</form>

</body>
</html>