<?php header("Expires: 0"); ?>
<?php
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/librerias.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Usuarios.php");
require_once("../includes/clsGmenu.php");
require_once("../includes/Seguridad.php");

$emp=0;
$fecha = "";
$area_codigo = "0";
$area_id = "0";
$responsable_codigo = "0";
$turno_codigo = "0";
$empleado_id=$_SESSION["empleado_codigo"];
if (isset($_GET["area_codigo"])) $area_codigo = $_GET["area_codigo"];
if (isset($_GET["area_id"])) $area_id = $_GET["area_id"];
if (isset($_GET["fecha"])) $fecha = $_GET["fecha"];
if (isset($_GET["responsable_codigo"])) $responsable_codigo = $_GET["responsable_codigo"];
if (isset($_GET["turno_codigo"])) $turno_codigo = $_GET["turno_codigo"];

$gm = new gmenu();
$gm->setMyUrl(db_host());
$gm->setMyUser(db_user());
$gm->setMyPwd(db_pass());
$gm->setMyDBName(db_name());
$gm->empleado_codigo=$empleado_id;
$gm->area_id=$area_id;
$rpta=$gm->query_supervisor();
$area_id = $gm->area_id; 

//CONTROLAR PPP Y ADMINISTRADOR VACACIONES Y MOVIMIENTOS CAMBIO MODALIDAD
$controlar=1;
if($gm->getVerificaRol($empleado_id,3)==1){//EXISTE 3. ROL Administrador
    $controlar=0;
}else if($gm->getVerificaRol($empleado_id,15)==1){//EXISTE 15. ROL PPP
    $controlar=0;
}else{//DEFAULT CUALQUIER OTRO ROL NO SERA VERIFICADO
    $controlar=1;
}

?>
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">

    <SCRIPT>var isomorphicDir="../isomorphic/";</SCRIPT>
    <SCRIPT SRC='../isomorphic/system/modules/ISC_Core.js'></SCRIPT>
    <SCRIPT SRC='../isomorphic/system/modules/ISC_Foundation.js'></SCRIPT>
    <SCRIPT SRC='../isomorphic/system/modules/ISC_Containers.js'></SCRIPT>
    <SCRIPT SRC='../isomorphic/system/modules/ISC_Grids.js'></SCRIPT>
    <SCRIPT SRC='../isomorphic/system/modules/ISC_Forms.js'></SCRIPT>
    <SCRIPT SRC='../isomorphic/system/modules/ISC_DataBinding.js'></SCRIPT>
    <SCRIPT SRC='../isomorphic/skins/SmartClient/load_skin.js'></SCRIPT>
<script>
   <?php if(isset($cadena)) echo $cadena; ?>
</script>
</head>
<body class="PageBODY">
<div id="popCalF" style="BORDER-BOTTOM: 2px ridge; Z-INDEX:200; BORDER-LEFT: 2px ridge; BORDER-RIGHT: 2px ridge; BORDER-TOP: 2px ridge; POSITION: absolute; VISIBILITY: hidden; WIDTH: 10px">
	<iframe name="popFrameF" id="popFrameF" src="../popcj.htm" frameborder="1" scrolling="no" width="183" height="188"></iframe>
</div>

<script language="JavaScript" src="../jscript.js"></script>
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript">


var opcion='1.1'  //opcion x default

function lanzar(opcion){
   var f=window.parent.frames[0].document.frm.txtFecha.value;
   var a=window.parent.frames[0].document.frm.area_codigo.value;
   var controlar='<?php echo $controlar;?>';
   controlar=parseInt(controlar);
   //alert(controlar);
   
   if (<?php echo $empleado_id ?>*1!= window.parent.frames[0].document.frm.responsable_codigo.value) {   
        var r=window.parent.frames[0].document.frm.responsable_codigo.value;
   } else {
      	var r=<?php echo $empleado_id ?>;
        <?php $responsable_codigo = $empleado_id ?>;
   }
   
   var t=window.parent.frames[0].document.frm.turno_codigo.value;
   
   if(controlar===1){
        if (a*1==0){
            alert('Seleccione Area');
            return false;
        }    
   }
   
   
   var urlpagina="gestion_right.php?opcion=" + opcion + "&fecha="+f+"&area_codigo="+a+"&responsable_codigo="+r+"&turno_codigo="+t+"&controlar="+controlar;
  
   if (opcion*1>0) window.parent.frames[2].location=urlpagina;

}

isc.Tree.create({
    ID:"dataTree",
    root: 
    {name:"Gestion/", 
   	children:[
    	{name:"MENU/", value:"0.0" , icon:"folder_out.png", 
		children:[{name:"EMPLEADOS/", value:"1.1", children:[],UID:"EMPLEADOS" , icon:"folder_document.png"},
		          {name:"MARCACIONES/", value:"1.2", children:[],UID:"MARCACIONES" , icon:"folder_document.png"},
        		  {name:"INCIDENCIAS/", value:"1.3", children:[],UID:"INCIDENCIAS" , icon:"folder_document.png"},
                          {name:"VACACIONES/", value:"1.6", children:[],UID:"VACACIONES" , icon:"folder_document.png"},
                          {name:"MOV. CAMBIO MODALIDAD/", value:"1.7", children:[],UID:"MOV. CAMBIO MODALIDAD" , icon:"folder_document.png"},
                          {name:"AUSENTISMO PROGRAMADO/", value:"1.8", children:[],UID:"AUSENTISMO PROGRAMADO" , icon:"folder_document.png"},
        		  {name:"HR. ACUMULADAS X SEMANA/", value:"1.5", children:[],UID:"HR. ACUMULADAS X SEMANA" , icon:"folder_document.png"},
        {name:"EVENTOS/", 
         children:[
    		{name:"ABIERTOS/", value:"2.1", children:[], UID:"ABIERTOS", icon:"folder_document.png"},
    		{name:"CERRADOS/", value:"2.2", children:[], UID:"CERRADOS", icon:"folder_document.png"}],
    	UID:"EVENTOS" , icon:"folder_out.png"},
    	{name:"TIEMPO PARCIAL/", 
    	children:[
    		{name:"EN POSICION/", value:"3.1", children:[], UID:"EN POSICION" , icon:"folder_document.png"},
    		{name:"HRS. ACUMULADAS x SEMANA/", value:"3.2", children:[], UID:"HRS. ACUMULADAS x SEMANA", icon:"folder_document.png"}],
    	UID:"TIEMPO PARCIAL" , icon:"folder_out.png"}
    ]} ]}
});


isc.TreeGrid.create({
    ID: "GestionTree",
    data:dataTree,
    width: 500,
	height: 600,
	setNodeIcon: function (node,icon){
    	node.setIcon("folder_out.png");
    },
    showHeader:false,
    font: "arial",
    showAllRecords:true,
    canDragRecordsOut:true,
    canAcceptDroppedRecords:true, 
    folderIcon:"folder_out.png",
    showOpenIcons:true,
    showDropIcons:false,
    dropIconSuffix:"into",
    closedIconSuffix:"",
    alternateRecordStyles:false, 
    nodeClick: function(viewer, node, recordNum) {
    var x= node.value;
    switch (x)
    	{ case "0.0" : lanzar (0.0);
                 break;
          
		  case "1.1" : lanzar (1.1);
                 break;
          		         
   		  case "1.2" : lanzar (1.2);
                 break;
    
		  case "1.3" : lanzar (1.3);
                 break;
    
		  case "1.4" : lanzar (1.4);
                 break;
    
		  case "1.5" : lanzar (1.5);
                 break;
                 case "1.6" : lanzar (1.6);
                 break;
                 
                 case "1.7" : lanzar (1.7);
                 break;
                 
                 case "1.8" : lanzar (1.8);
                 break;
                 
	  	 case "2.1" : lanzar (2.1);
                 break;
                 
      	  case "2.2" : lanzar (2.2);
                 break;
                 
      	  case "3.1" : lanzar (3.1);
                 break;
                 
   		  case "3.2" : lanzar (3.2);
                 break;
    
       }
    }
});

GestionTree.getData().openAll();
</script>

</body>
</noframes>
</html>