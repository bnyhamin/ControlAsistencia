<?php header("Expires: 0"); ?>
<?php
require_once("../../Includes/Connection.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/clsIncidencias.php");

$cods_emp=0;

$o = new incidencias();
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
if(isset($_GET["cods_emp"])) $cods_emp= $_GET["cods_emp"];

?>

<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?> - Seleccionar Incidencias</title>
<meta http-equiv='pragma' content='no-cache' />
<link rel='stylesheet' type='text/css' href='../style/tstyle.css' />
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
var cods_emp=document.frm.cods_emp.value;
if(Reg()!=true) return false;
var cod=obtener_codigos();
//lblDescripcion.innerHTML = cod;
if (cod=='0') return false;
var valor = window.showModalDialog(server + "Gap/generar.jsp?anio=" + anio + "&mes=" + mes + "&fecha_inicio=0&fecha_fin=0&area_codigo=" + area + "&campana=" + cod_campana + "&usuario_id=" + usuario + "&opcion=" + opcion + "&codigos=" + cod + "&cods_emp=" + cods_emp + "&responsable=0", "Reporte","dialogWidth:400px; dialogHeight:150px");
cerrar();
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
             alert('Seleccione Incidencias');
			 return false;
          }
          return true;
}


function obtener_codigos(){
var elementos="";
var c=0;
var r = document.getElementsByTagName('input');
   for (var i = 0;i< r.length ; i++){
    var o = r[i];
     if (o.id == 'chk'){
       if(o.checked){
          if (elementos== ''){
            elementos = o.value;
            c+=1;
          }
          else{
           elementos += ',' + o.value;
           c+=1;
          }
      }
   }
  }
  if (c>5){
    alert('Solo puede seleccionar hasta 5 Incidencias.');
    return '0';
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
 var c=0;
  var r = document.getElementsByTagName('input');
   for (var i = 0;i< r.length ; i++){
    var o = r[i];
     if (o.id == 'chk'){
       if(o.checked) c+=1;
     }
  }
  if (c>5){
    alert('Solo puede seleccionar hasta 5 Incidencias.');

  }
}

function cerrar(){
	window.close();
}


</script>
<body class="PageBODY">
<center class="FormHeaderFont">Seleccione Incidencia(s)</center>
<form name='frm' id='frm' action="<?php echo $_SERVER['PHP_SELF']  ?>" method='post'>
<p><font color=red> <b>Nota:</b><br>Por razones de rendimiento del servidor sólo puede seleccionar hasta 5 incidencias.<br>
Si requiere mas de 5, utilice el reporte Nro. 1.</font></p>
<?php
$o->area_codigo=$area;
$o->opcion = $opcion;
$cadena=$o->Lista_Incidencias();
echo $cadena;
?>
<br>
<table border="0" align="center" >
	<tr align="center">
		<td>
			<input type="button" class="Button" id="cmdg" name="cmdg" value="Generar" style="width:80px" onClick="ok()" />
			<input type="button" class="Button" id="cmdg" name="cmdg" value="Cancelar" style="width:80px"  onClick="cerrar()" />
		</td>
	</tr>
</table>
<font size=2 color=DarkSlateBlue id=lblDescripcion name=lblDescripcion>&nbsp;</font>
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