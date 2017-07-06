<?php header("Expires: 0"); ?>
<?php
session_start();
if (!isset($_SESSION["empleado_codigo"])){
?>	
    <script language="JavaScript">
        alert("Su sesión a caducado!!, debe volver a registrarse.");
        document.location.href = "../login.php";
    </script>
<?php
	exit;
}
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/MyCombo.php");
require_once("../includes/clsGmenu.php"); 
?>

<?php

/*if ($msconnect = mssql_pconnect(db_host(),db_user(),db_pass()) or die("No puedo conectarme a servidor")){
	$cnn = mssql_select_db(db_name(),$msconnect) or die("No puedo seleccionar BD");
} else {
  echo "Error al tratar de conectarse a la bd.";
}*/

$fecha = "";
$area_id = "0";
$area_codigo = "0";
$responsable_codigo = "0";
$turno_codigo = "0";
$opcion = "0";

$hoy = getdate();

$sql4= "";
$anio_codigo = $hoy['year'];
$mes_codigo =  $hoy['mon'];

$empleado_id=$_SESSION["empleado_codigo"];

if(isset($_GET["area_codigo"])) $area_codigo = $_GET["area_codigo"];
//if(isset($_GET["area_id"])) $area_id = $_GET["area_id"];
if(isset($_GET["fecha"])) $fecha = $_GET["fecha"];
if(isset($_GET["responsable_codigo"])) $responsable_codigo = $_GET["responsable_codigo"];
if(isset($_GET["turno_codigo"])) $turno_codigo = $_GET["turno_codigo"];
if(isset($_GET["opcion"])) $opcion = $_GET["opcion"];
if(isset($_GET["anio_codigo"])) $anio_codigo = $_GET["anio_codigo"];

if(isset($_POST["area_codigo"])) $area_codigo = $_POST["area_codigo"];
if(isset($_GET["area_id"])) $area_id = $_GET["area_id"];
if(isset($_POST["fecha"])) $fecha = $_POST["fecha"];
if(isset($_POST["responsable_codigo"])) $responsable_codigo = $_POST["responsable_codigo"];
if(isset($_POST["turno_codigo"])) $turno_codigo = $_POST["turno_codigo"];
if(isset($_POST["opcion"])) $opcion = $_POST["opcion"];
if(isset($_POST["anio_codigo"])) $anio_codigo = $_POST["anio_codigo"];
if(isset($_POST["mes_codigo"])) $mes_codigo = $_POST["mes_codigo"];

if (isset($_GET["lista_sel"])) $empleado_sel = $_GET["lista_sel"];
if (isset($_POST["empleado_sel"])) $empleado_sel = $_POST["empleado_sel"];
//echo $lista_sel;

$titulo='';
$gm = new gmenu();
$gm->MyUrl = db_host();
$gm->MyUser= db_user();
$gm->MyPwd = db_pass();
$gm->MyDBName= db_name();

	switch($opcion){
		case '1.1':
			$titulo = "Lista de Empleados";
			break;
		case '1.2':
			$titulo = "Marcaciones Realizadas";
			break;
		case '1.3':
			$titulo = "Incidencias Reportadas";
			break;
        case '1.4':
			$titulo = "Marcaciones Mensuales";
			break;
        case '1.5':
			$titulo = "Acumulado Mensual";
			break;
		case '2.1':
			$titulo = "Eventos Abiertos";
			break;
		case '2.2':
			$titulo = "Eventos Cerrados";
			break;
		case '3.1':
			$titulo = "Personal con Tiempo Parcial - En Posición";
			break;
		case '3.2':
			$titulo = "Personal con Tiempo Parcial - Horas Acumuladas por Semana";
			break;
	}

?>

<script language="JavaScript" src="../jscript.js"></script>
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript">


function cambiar_mes() {
 	document.frm.submit();
}

function ver_detalle(lista ) {
	
 	var f=window.document.frm.empleado_sel.value;
   	var a=window.document.frm.area_codigo.value;
    var r=window.document.frm.responsable_codigo.value;
   	var t=window.document.frm.turno_codigo.value;
    var o= 1.1;


		    //window.open("lista_empleados.php?lista_sel=" + f+ "&fecha="+lista+"&area_codigo="+a+"&responsable_codigo="+r+"&turno_codigo="+t+"&opcion="+o,"nombre","toolbar=0, menubar=0, status=1, location=0, hotkeys=0, scrollbars=1, resizable =1 width=700, height=480, center=yes")
		    
		    	var valor = window.showModalDialog("lista_empleados.php?lista_sel=" + f+ "&fecha="+lista+"&area_codigo="+a+"&responsable_codigo="+r+"&turno_codigo="+t+"&opcion="+o,"nombre",'dialogWidth:700px; dialogHeight:480px');
		



 
	
}

/*
function openCenterWin(url,theWidth,theHeight){
	var theTop=(screen.height/2)-(theHeight/2);
	var theLeft=(screen.width/2)-(theWidth/2);
	var features='height='+theHeight+',width='+theWidth+',top='+theTop+',left='+theLeft+",scrollbars=yes";
	theWin=window.open(url,'ModalChild',features);
}
*/





/*
function sel_anio(){
   borrar_options("semana_codigo");
   agregar_options("semana_codigo","0","---Seleccionar---");
   document.frames["ift"].document.location.href = "transaccion.php?tipo=3&area_codigo=" +  document.frm.area_codigo.value + "&anio_codigo=" + document.getElementById("anio_codigo").value;
}
*/

</script>

<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../../default.js"></script>
</head>

<body class="PageBODY" >
<div id="popCalF" style="BORDER-BOTTOM: 2px ridge; Z-INDEX:200; BORDER-LEFT: 2px ridge; BORDER-RIGHT: 2px ridge; BORDER-TOP: 2px ridge; POSITION: absolute; VISIBILITY: hidden; WIDTH: 10px">
	<iframe name="popFrameF" id="popFrameF" src="../popcj.htm" frameborder="1" scrolling="no" width="183" height="188"></iframe>
</div>

<form id="frm" name="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<center class="FormHeaderFont"><?php echo $titulo ?></center></b>
<p>
 
<?php 

  if ($opcion == '3.2' || $opcion == '1.4' ){ 
     if (isset($_POST["cboanio_codigo"])) $anio_codigo = $_POST["cboanio_codigo"];
     if (isset($_POST["cbomes_codigo"])) $mes_codigo = $_POST["cbomes_codigo"];
?>  
  <table border="0" cellspacing="1" cellpadding="2" align="center" >
  <tr>		
	<td width="100px" align="left" class="ColumnTD">Año :&nbsp;
       <?php
			$combo = new MyCombo();
			$combo->setMyUrl(db_host());
			$combo->setMyUser(db_user());
			$combo->setMyPwd(db_pass());
			$combo->setMyDBName(db_name());
         
			$sql4="select anio_codigo,anio_descripcion from anio WITH (nolock) ";
			$combo->query = $sql4;
			$combo->name = "cboanio_codigo";
			$combo->value = $anio_codigo."";
			$combo->more = "class=select";
			$rpta = $combo->Construir();
	        echo $rpta;
	
      ?>
	</td>     
  	<td width="100px" align="left" class="ColumnTD">
       <?php
		 echo "Mes :&nbsp;";
             //query del combo de seleccion de semana x año
		 $sql4="SELECT mes_codigo, mes_descripcion FROM meses WITH (nolock) ";
		 $sql4 .=" ORDER BY 1";
		 $combo->query = $sql4;
		 $combo->name = "cbomes_codigo";
		 $combo->value = $mes_codigo."";
		 $combo->more = "class=select onchange=cambiar_mes()";
		 $rpta = $combo->Construir();
		 echo $rpta;
		
	   ?>
	</td>
  	<td align="left" class="ColumnTD">
	  	<img src="../images/j_salida.png" style="cursor:hand" onclick="return cambiar_mes()" title='Actualizar'/>
		  </td>
  </tr>	
  </table>
	<img src="../images/excel_ico.GIF" style="cursor:hand" onclick="return exportarExcel('idTabla')" title='Exportar Excel'/>
<?php   
  
} 

$gm->opcion=$opcion;
$gm->fecha=$fecha;
$gm->area_codigo=$area_codigo;
$gm->responsable_codigo=$responsable_codigo;
$gm->turno_codigo=$turno_codigo; 
$gm->anio_codigo=$anio_codigo;
$gm->mes_codigo=$mes_codigo;
$gm->empleado_sel=$empleado_sel;  

$rpta=$gm->Consultar();
 
?>
<input type="hidden" id="opcion" name="opcion" value="<?php echo $opcion ?>" />
<input type="hidden" id="fecha" name="fecha" value="<?php echo $fecha ?>" />
<input type="hidden" id="area_codigo" name="area_codigo" value="<?php echo $area_codigo ?>" />
<input type="hidden" id="responsable_codigo" name="responsable_codigo" value="<?php echo $responsable_codigo ?>" />
<input type="hidden" id="turno_codigo" name="turno_codigo" value="<?php echo $turno_codigo ?>" />
<input type="hidden" id="anio_codigo" name="anio_codigo" value="<?php echo $anio_codigo ?>" />
<input type="hidden" id="mes_codigo" name="mes_codigo" value="<?php echo $mes_codigo ?>" />
<input type="hidden" id="empleado_sel" name="empleado_sel" value="<?php echo $empleado_sel ?>" />

</form>
<iframe id='ift' name='ift' style='width:600px;display:none;'></iframe>
</body>
</noframes>
</html>