<?php header("Expires: 0"); ?>
<?php
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/librerias.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Usuarios.php");
require_once("../includes/clsGmenu.php");
require_once("../includes/Seguridad.php");
//session_start();
/*if ($msconnect = mssql_pconnect(db_host(),db_user(),db_pass()) or die("No puedo conectarme a servidor")){
    $cnn = mssql_select_db(db_name(),$msconnect) or die("No puedo seleccionar BD");
} else {
    echo "Error al tratar de conectarse a la bd.";
}*/
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

//realiza el select que forma el treeview (desarrollo pendiente))

/*
$cadena = "";
$ssql = "";

$ssql = "select gmenu_codigo, gmenu_padre, gmenu_descripcion
 from ca_gestion_menu where gmenu_activo=1 order by gmenu_padre";

$result = mssql_query($ssql);
$cadena .= "Resul_Data = [\n";

while($rs = mssql_fetch_row($result)){
  $cadena .= "{\ngmenu_codigo:$rs[0],\ngmenu_padre:$rs[1],\ngmenu_descripcion:'$rs[2]'\n},\n";
}
$cadena = substr($cadena,0,strlen($cadena) - 2);
$cadena .= "\n]\n";
*/
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
<!-- llama funciones generales -->
<script language="JavaScript" src="../jscript.js"></script>
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript">


var opcion='1.1'  //opcion x default

function lanzar(opcion){
   var f=window.parent.frames[0].document.frm.txtFecha.value;
   var a=window.parent.frames[0].document.frm.area_codigo.value;
   //alert(window.parent.frames[0].document.frm.area_codigo.value)
   /*if (<?php echo $area_id ?>*1!= window.parent.frames[0].document.frm.area_codigo.value) {   
   		var r=window.parent.frames[0].document.frm.responsable_codigo.value;
   } else {
      	var r=<?php echo $empleado_id ?>;
        <?php $responsable_codigo = $empleado_id ?>;
   }*/
   if (<?php echo $empleado_id ?>*1!= window.parent.frames[0].document.frm.responsable_codigo.value) {   
   		var r=window.parent.frames[0].document.frm.responsable_codigo.value;
   } else {
      	var r=<?php echo $empleado_id ?>;
        <?php $responsable_codigo = $empleado_id ?>;
   }
   
   var t=window.parent.frames[0].document.frm.turno_codigo.value;
   
   if (a*1==0){
    	alert('Seleccione Area');
    	return false;
   }
   
   var urlpagina="gestion_right.php?opcion=" + opcion + "&fecha="+f+"&area_codigo="+a+"&responsable_codigo="+r+"&turno_codigo="+t;
  
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

/*
	isc.TreeGrid.create({
	ID: "GestionTree",
	width: 500,
	height: 600,
	folderIcon:"folder_out.png",
	showOpenIcons:false,
	showDropIcons:false,
	dropIconSuffix:"into",
	closedIconSuffix:"",
	alternateRecordStyles:false, 
	  data: isc.Tree.create({
	   	 modelType: "parent",
	   	 rootValue: "0",
	   	 nameProperty: "gmenu_descripcion",
	   	 idField: "gmenu_codigo",
	   	 parentIdField: "gmenu_padre",
	   	 data: Resul_Data,
	   	 fields: [
       		{name: "gmenu_codigo"},
	    	{name: "gmenu_descripcion"}
         ],
	     dataProperties: {openProperty: "isOpen"}
	  })
//	nodeClick: "itemList.fetchData({category: node.gmenu_codigo})",
	});
	GestionTree.getData().openAll();
*/

</script>

</body>
</noframes>
</html>