<?php header("Expires: 0"); ?>
<?php
require_once("../../Includes/Connection.php");
require_once("../../Includes/librerias.php");
require_once("../includes/clsCA_Eventos.php");

$cods_emp=0;

$o = new ca_eventos();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();
$anio = $_GET["anio"];
$mes = $_GET["mes"];
$area = $_GET["area"];
$campana = $_GET["campana"];
$usuario = $_GET["usuario"];
$opcion = $_GET["opcion"];


?>

<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?> - Seleccionar Incidencias</title>
<meta http-equiv='pragma' content='no-cache'>
<link rel='stylesheet' type='text/css' href='../style/tstyle.css'>
<script language="JavaScript" src="../../default.js"></script>
</head>
<script language="JavaScript">
var server="<?php echo $url_jreportes ?>"
function ok(){
var anio=document.frm.anio.value;
var mes=document.frm.mes.value;
var area=document.frm.area.value;
var cod_campana=document.frm.campana.value;
var usuario=document.frm.usuario.value;
var opcion=document.frm.opcion.value;
if(Reg()!=true) return false;

var cod=obtener_codigos();
//alert(cod);
var valor = window.showModalDialog(server + "Gap/generar.jsp?anio=" + anio + "&mes=" + mes + "&fecha_inicio=0&fecha_fin=0&area_codigo=" + area + "&campana=" + cod_campana + "&usuario_id=" + usuario + "&opcion=" + opcion + "&codigos=" + cod + "&responsable=0", "Reporte","dialogWidth:400px; dialogHeight:150px");

}

function checkear(){
if(document.frm.chk_todos.checked){
   checkear_todos_empleados(true);
}
else{
   checkear_todos_empleados(false);
  }
}

function Reg(){
  valor='';
  var r=document.getElementsByTagName('input');
  for (var i=0; i< r.length; i++) {
          	var o=r[i];
          	if (o.id=='chk' || o.id=='chk_todos') {
          		if (o.checked) {
          			valor= o.value;
          		}
          	}
          }
		  //alert(valor);
		  //return false;
          if ( valor =='' ){
             alert('Seleccione Eventos');
			 return false;
          }
          return true;
}


function obtener_codigos(){
var elementos="";
var r = document.getElementsByTagName('input');
           for (var i = 0;i< r.length ; i++){
            var o = r[i];
               if (o.id == 'chk'){
                 if(o.checked){
                    if (elementos== ''){
                      elementos = o.value;
                    }
                    else{
                     elementos += ',' + o.value;
                    }
                }
             }
          }
    return elementos;
}

function checkear_todos_empleados(flag){
  var r=document.getElementsByTagName('input');
  for (var i=0; i< r.length; i++) {
          	var o=r[i];
          	if (o.id=='chk') {
                      o.checked=flag;
          	}
          }
  }


function check(){
 if(document.frm.chk_todos.checked) document.frm.chk_todos.checked=false;
}

function cerrar(){
	window.close();
}


</script>
<body class="PageBODY">
<center class="FormHeaderFont">Seleccione Eventos(s)</center>
<form name='frm' id='frm' action="<?php echo $_SERVER['PHP_SELF'] ?>" method='post'>
<?php
$o->area_codigo=$area;
$cadena=$o->Lista_Eventos();
echo $cadena;
?>
<br>
<table border="0" align="center" >
	<tr align="center">
		<td>
			<input type="button" class="Button" id="cmdg" name="cmdg" value="Generar" style="width:80px" onclick="ok()" />
			<input type="button" class="Button" id="cmdg" name="cmdg" value="Cancelar" style="width:80px"  onclick="cerrar()" />
		</td>
	</tr>
</table>
<input type='hidden' id='anio' name='anio' value='<?php echo $anio ?>' />
<input type='hidden' id='mes' name='mes' value='<?php echo $mes ?>' />
<input type='hidden' id='usuario' name='usuario' value='<?php echo $usuario ?>' />
<input type='hidden' id='area' name='area' value='<?php echo $area ?>' />
<input type='hidden' id='campana' name='campana' value='<?php echo $campana ?>' />
<input type='hidden' id='opcion' name='opcion' value='<?php echo $opcion?>' />
<input type='hidden' id='cods_emp' name='cods_emp' value='<?php echo $cods_emp?>' />
<input type='hidden' id='hddcodigos' name='hddcodigos' value='' />
</form>
</body>
</html>