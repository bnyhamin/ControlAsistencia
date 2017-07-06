<?php header("Expires: 0"); ?>
<?php
require_once("../../Includes/Connection.php");
require_once("../../Includes/librerias.php");
require_once("../includes/clsCA_Empleados.php");
$o = new ca_empleados();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();
$cod_campana='';
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
<title><?php echo tituloGAP() ?> - Seleccionar Empleados del Area</title>
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
if(Reg()!=true) return false;
var cod=obtener_codigos();
//alert(cod.length);
//lblDescripcion.innerHTML = cod;
if(opcion==12 || opcion==15) var valor = window.showModalDialog("lista_incidencias.php?anio=" + anio + "&mes=" + mes + "&area=" + area + "&usuario=" + usuario + "&campana=" + cod_campana +  "&opcion=" + opcion + "&cods_emp=" + cod, "Seleccion Incidencias","dialogWidth:600px; dialogHeight:400px");
else{
  //alert('aqui');
  if(opcion==16) var valor = window.showModalDialog(server + "Gap/generar.jsp?anio=" + anio + "&mes=" + mes + "&fecha_inicio=0&fecha_fin=0&area_codigo=" + area + "&campana=0&usuario_id=" + usuario + "&opcion=" + opcion + "&cods_emp=" + cod + "&responsable=0", "Reporte","dialogWidth:400px; dialogHeight:150px");
 }
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
          if ( valor =='' ){
             alert('Seleccione Empleados');
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
<center class="FormHeaderFont">Seleccione Empleados(s)</center>
<form name='frm' id='frm' action="<?php echo $_SERVER['PHP_SELF'] ?>" method='post'>
<table border="0" align="center" >
	<tr align="center">
		<td>
			<input type="button" class="Button" id="cmdg" name="cmdg" value="Aceptar" style="width:80px" onclick="ok()" />
			<input type="button" class="Button" id="cmdg" name="cmdg" value="Cancelar" style="width:80px"  onclick="cerrar()" />
		</td>
	</tr>
</table>
<?php
$o->area_codigo=$area;
if($opcion==16){
    $cadena=$o->Lista_Empleados_con_compensacion($cod_campana,$anio,$mes);
    echo "1";
}else{
    echo "2";
    $cadena=$o->Lista_Empleados($cod_campana,$anio,$mes);
}


echo $cadena;
?>
<br>
<font size=2 color=DarkSlateBlue id=lblDescripcion name=lblDescripcion>&nbsp;</font>
<input type='hidden' id='anio' name='anio' value='<?php echo $anio ?>' />
<input type='hidden' id='mes' name='mes' value='<?php echo $mes ?>' />
<input type='hidden' id='usuario' name='usuario' value='<?php echo $usuario ?>' />
<input type='hidden' id='area' name='area' value='<?php echo $area ?>' />
<input type='hidden' id='campana' name='campana' value='<?php echo $campana ?>' />
<input type='hidden' id='opcion' name='opcion' value='<?php echo $opcion?>' />
<input type='hidden' id='hddcodigos' name='hddcodigos' value='' />
</form>
</body>
</html>
