<?php header("Expires: 0"); ?>
<?php
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/MyCombo.php");
require_once("../includes/clsCA_Asistencias.php");
require_once("../includes/clsCA_Asistencia_Incidencias.php");
require_once("../includes/clsCA_Asistencia_Responsables.php"); 
require_once("../includes/clsCA_Usuarios.php");

$empleado="";
$area="";
$fecha="";
$area_codigo="";
$area_descripcion="";
$responsable_codigo="";
$responsable_codigo_registrador="";
$responsable="";
$cadena="";
$codigos="";
$incidencia_codigo="";
$cod_campana="";
$mensaje="Ok";
$ip_entrada="";

$usr = new ca_usuarios();
$usr->MyUrl = db_host();
$usr->MyUser= db_user();
$usr->MyPwd = db_pass();
$usr->MyDBName= db_name();

$o=new ca_asistencia_incidencias();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

$ar=new ca_asistencia_responsables();
$ar->MyUrl = db_host();
$ar->MyUser= db_user();
$ar->MyPwd = db_pass();
$ar->MyDBName= db_name();

$ip_entrada=$_SERVER['REMOTE_ADDR'];
$fecha = $_GET["fecha"];
$area_codigo=$_GET["area_codigo"];
$responsable_codigo=$_GET["responsable_codigo"];

if (isset($_POST["responsable_codigo"])) $responsable_codigo = $_POST["responsable_codigo"];
if (isset($_POST["fecha"])) $fecha = $_POST["fecha"];

$usr->empleado_codigo = $responsable_codigo;
$r = $usr->Identificar();
$responsable= $usr->empleado_nombre;
$area= $usr->area_codigo;
$area_descripcion= $usr->area_nombre;


if (isset($_POST['hddaccion'])){
  if ($_POST['hddaccion']=='JUSTIFICAR'){
                $codigos =$_POST["codigos"];
                $arr=split(',',$codigos);
		//echo count($arr);
		for($i=0;$i<count($arr);$i++){
        	$cad=explode('_',$arr[$i]);
			$cod_campana=$_POST["cod_campana_" . $cad[0] . "_" . $cad[1]];
                        $responsable_codigo_registrador=$_POST["responsable_" . $cad[0] . "_" . $cad[1]];;
	        
			$o->empleado_codigo = $cad[0];
			$o->asistencia_codigo = $cad[1];
			$o->cod_campana = $cod_campana;
			$o->responsable_codigo = $responsable_codigo;
			$o->incidencia_codigo = 151;
			//$o->incidencia_codigo = 11;
			$o->incidencia_hh_dd=0;
                        $o->horas =0;
                        $o->minutos=0;
			$o->fecha=$fecha;
			$o->ip_registro=$ip_entrada;
			if($responsable_codigo!=$responsable_codigo_registrador){
				$ar->empleado_codigo = $cad[0];
				$ar->asistencia_codigo = $cad[1];
				$ar->responsable_codigo=$responsable_codigo;
				$r=$ar->validar_responsable_asistencia(); //si responsable existe en la asistencia
				if($ar->codigo==0) $r=$ar->registrar_responsable_asistencia(); //no existe, registrar responsable
				if($r=='OK') $mensaje = $o->registrar_incidencia(1,$ip_entrada,'');
			}else{
				$mensaje = $o->registrar_incidencia(1,$ip_entrada,'');	
			}	
		}
		   
		if($mensaje=='OK'){
		?>
		<script language='javascript'>
		 window.opener.document.forms['frm'].submit();
		 window.opener.document.frm.cmdx.click();
		 window.close();
		</script>
		<?php
		}else{
		  echo "error: " . $mensaje;
		}
	}
}

?>
<HTML>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv='pragma' content='no-cache'>
<title><?php echo tituloGAP() ?>-Justificación de Asistencia Masiva</title>
<META NAME=author CONTENT='TUMI Solutions S.A.C.'>
<link rel='stylesheet' type='text/css' href='../style/tstyle.css'>
<script language="JavaScript" src="../tecla_f5.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscript.js"></script>

<script language="JavaScript" >
function cancelar(){
window.close();
}
function escribirFecha(){
campoDeRetorno.value = dia + "/" + mes + "/" + ano;
document.frm.txtFecha.value=campoDeRetorno.value; 	
window.frames.lst.location.href="lista_justificable.php?fecha=" + document.frm.txtFecha.value + "&area_codigo=<?php echo $area_codigo ?>";
}

function listar(){
document.frm.submit();		
}	

function pedirFecha(campoTexto,nombreCampo) {
  ano = anoHoy();
  mes = mesHoy();
  dia = diaHoy();
  campoDeRetorno = campoTexto;
  titulo = nombreCampo;
  dibujarMes(ano,mes);
}

function cmdJustificar_onclick(){
if(Reg()!=true) return false;
document.frm.codigos.value=obtener_codigos();
if(confirm('confirme los datos')== false) return false;
	document.frm.hddaccion.value="JUSTIFICAR"; 	
 	document.frm.submit(); 
}

function obtener_codigos(){
var elementos="";
var r = document.getElementsByTagName('input');
           for (var i = 0; i< r.length ; i++){
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

function Reg(){
  valor='';
  var r=document.getElementsByTagName('input');
  for (var i=0; i< r.length; i++) {
          	var o=r[i];
          	if (o.id=='chk') {
          		if (o.checked) {
          			valor= o.value;
          		}
          	} 
          }
          if ( valor =='' ){
             alert('Seleccione Registros');
			 return false;
          }
          return true;
}

function checkear_todos(flag){	
if(verificar()!=true){
   alert('No existen eventos a seleccionar!!');
   document.frm.chk_todos.checked=false;
   return false;
}		
var r=document.getElementsByTagName('input');
for (var i=0; i< r.length; i++) {
          	var o=r[i];
          	if (o.id=='chk') {
                      o.checked=flag;
      }
   }                
}
  
function checkear(){
if(document.frm.chk_todos.checked){
   checkear_todos(true);
}
else{
   checkear_todos(false);
  }
}  

function check(){
 if(document.frm.chk_todos.checked) document.frm.chk_todos.checked=false;
}  

function verificar(){
  valor=false;
  var r=document.getElementsByTagName('input');
      for (var i=0; i< r.length; i++) {
          	var o=r[i];
          	if (o.id=='chk') {
          	    valor=true;
                    break;
          	}
          }
   return valor;
}
</script>

</HEAD>

<body class="PageBODY"  >
<form id=frm name=frm action=<?php echo $_SERVER['PHP_SELF'] ?> method=post>
<center class=FormHeaderFont>Justificación de Asistencias-Empleados con Registro de Falta</center>
<table align='center' width="50%" border="0">
<tr>
    <td align="right" width='45%'><b>Supervisor&nbsp;:</b></td>
	<td align="left" ><font color=#3366CC><b><?php echo $responsable?></b></font></td>
  </tr>
   <TR>
    <td  align='right'><b>&nbsp;Fecha&nbsp;:</b></td>
    <TD >&nbsp;
		<input class="CA_Input"  style="TEXT-ALIGN: center; WIDTH: 90px" name="txtFecha" id="txtFecha" readOnly value='<?php echo $fecha ?>'>
	 </TD>
  </TR>
</table>
<br>
<table class='FormTable' align="center" border="0" cellPadding="0" cellSpacing="1" style="width:100%">
<tr align="center" >
    <td class="ColumnTD" bgcolor="#33CC33" width='4%'>
    <INPUT type=CHECKBOX align=center id='chk_todos' name='chk_todos' value='0' onclick='checkear()'><b>TODOS</b>
    </td>
    <td class="ColumnTD" width='4%'>Código
    </td>
    <td class="ColumnTD" width='20%'>Empleado
    </td>
	<td class="ColumnTD" width='5%'>Fecha
    </td>
	<td class="ColumnTD" width='20%'>Falta Registrada por
    </td>
	<td class="ColumnTD" width='20%'>Area
    </td>
</tr>
<?php
    
	$o->fecha=$fecha;
	$o->area_codigo=$area_codigo;
	$o->responsable_codigo=$responsable_codigo;
        $cadena=$o->Lista_justificable();
	echo $cadena;
?>
</table>
<br>
<table width='900px' align='center' cellspacing='0' cellpadding='0' border='0'>
<tr align='center'>
 <td colspan=2  >
 <input name='cmdGuardar' id='cmdGuardar' type='button' value='Aceptar'  class='Button' onclick="cmdJustificar_onclick()" style='WIDTH: 80px;'>
 <input name='cmdCancelar' id='cmdCancelar' type='button' value='Cancelar'  class='Button' onclick="cancelar()"style='WIDTH: 80px;'>
 </td>
</tr>
</table>
<input type='hidden' id='codigos' name='codigos' value="">
<input type='hidden' id='hddaccion' name='hddaccion' value="">
<input type='hidden' id='fecha' name='fecha' value="<?echo $fecha ?>">
<input type='hidden' id='responsable_codigo' name='responsable_codigo' value="<?echo $responsable_codigo ?>">

</form>
</body>
</HTML>
<!-- TUMI Solutions S.A.C.-->