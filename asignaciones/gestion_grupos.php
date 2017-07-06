<?php header("Expires: 0"); ?>
<?php
  ini_set('display_errors', 'On');
    error_reporting(E_ALL);  
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/MyCombo.php");
require_once("../includes/clsCA_Asignaciones.php");
require_once("../includes/clsCA_Asignacion_Empleados.php");
require_once("../includes/clsCA_Usuarios.php");
require_once("../includes/MyGrillaEasyUI.php");



$responsable_codigo=0;

$combo = new MyCombo();
$combo->setMyUrl(db_host());
$combo->setMyUser(db_user());
$combo->setMyPwd(db_pass());
$combo->setMyDBName(db_name());

$o = new ca_asignaciones();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

$ae = new ca_asignacion_empleados();
$ae->MyUrl = db_host();
$ae->MyUser= db_user();
$ae->MyPwd = db_pass();
$ae->MyDBName= db_name();

$u = new ca_usuarios();
$u->MyUrl = db_host();
$u->MyUser= db_user();
$u->MyPwd = db_pass();
$u->MyDBName= db_name();

$nombre_usuario="";
$area_descripcion="";
$cargo_codigo="";
$hddbuscar="";
$nombre="";
$jefe = ""; // responsable area
$fecha = "";
$tipo=1;
$area ="";
$arr="";

$u->empleado_codigo = $_SESSION["empleado_codigo"];
$r = $u->Identificar();
$nombre_usuario  	= $u->empleado_nombre;
$area      			= $u->area_codigo;
$area_descripcion 	= $u->area_nombre;
$jefe 				= $u->empleado_jefe; // responsable area
$fecha     			= $u->fecha_actual;

//areas de su responsable
$areas_subordinadas=$u->obtener_areas_responsable();
//echo $areas_subordinadas;
if (isset($_GET["cargo"])) $cargo_codigo = $_GET["cargo"];
if (isset($_GET["t"])) $tipo = $_GET["t"];

if (isset($_POST['hddaccion'])){
	if ($_POST['hddaccion']=='SVE'){
		//--obtener numero maximo de operadores en grupo
        //echo "<br>responsable grupos:".$_POST["responsable_codigo"];
		$ae->responsable_codigo= $_POST["responsable_codigo"];
		$rpta=$ae->Numero_maximo_operadores();
		$rpta=$ae->Total_operadores_grupo();
		//echo '<br>total actual: ' . $ae->total_operadores_grupo;
		//echo '<br>Maximo permitido: ' . $ae->maximo_operadores;
		//-- agrupar
		$o->responsable_codigo =$_POST["responsable_codigo"];
		$o->empleado_codigo_asigna = $_SESSION["empleado_codigo"];
		$rpta = $o->Addnew();
	    if($rpta=='OK'){
			//-- se creo cabecera, ahora crear hijos
			$arr = split(',',$_POST["hddcodigos"]);
			for($i=0; $i<sizeof($arr); $i++){
			    //echo "<br>datos:".$ae->total_operadores_grupo."+".$ae->maximo_operadores."<br>";  
				if (($ae->total_operadores_grupo + ($i+1)) <= $ae->maximo_operadores){
                                    $ae->empleado_codigo=$arr[$i];
                                    $ae->responsable_codigo = $_POST["responsable_codigo"];
                                    $ae->empleado_codigo_asigna = $_SESSION["empleado_codigo"];
                                    
                                    if ($ae->es_mismo_responsable()!='OK') {
                                        //--desactivar en otros grupos
      					$rptad = $ae->Desactivar_asignacion();
      					if ($rptad=='OK'){
                                            $rptaa = $ae->Addnew();
          						//echo $sql;
                                            if ($rptaa!='OK'){
                                                $rpta = "Error al asignar empleados.";
                                                echo $rpta;
                                            }else{
                                                $rpta= "OK";
                                            }
       					}
                                    }
				}else{
					if ($i>0){
						echo "Solo se registraron " . ($i) . " personas, no se permite más de " . $ae->maximo_operadores . " personas en el grupo";
					}else{
						echo "No se permiten más de " . $ae->maximo_operadores . " personas en el grupo";
					}
					break;
				}
			} //exit for
		}
		if($rpta=='OK'){
		?><script language='javascript'>
		   alert('Asignacion satisfactoria!');
		  </script>
		<?php
		}

	}
	if ($_POST['hddaccion']=='DLT'){
		//--Desactivar empleado de grupo
            
		$ae->responsable_codigo = $_SESSION["empleado_codigo"];
		$ae->empleado_codigo = $_POST["hddcodigos"];
		$rpta= $ae->Desactivar_empleado_grupo();
		if ($rpta!='OK') echo "<br><b>" . $rpta . "</b>";
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Formar Grupo</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscript.js"></script>
<script language="JavaScript" src="../no_teclas.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
<?php  require_once('../includes/librerias_easyui.php');?>

</head>
<script language="JavaScript">

function Consultar_equipo(){
 	//self.location.href="mi_equipo.php";
 	if (document.frm.responsable_codigo.value==0){
		alert('Seleccione Responsable');
		document.frm.responsable_codigo.focus();
		return false;
	}
	 pagina="ver_equipo.php?codigo=" + document.frm.responsable_codigo.value;
	 Ext_Dialogo.dialogCenter(800,600,'Equipo',pagina, "div_ver");

}


function BuscarCargo(){
 	var valor = window.showModalDialog("cargos.php?nombre=" + document.frm.cargo_descripcion.value , "Cargos","dialogWidth:500px; dialogHeight:500px");
	if (valor == "" || valor == "0" ){
		 return false;
	}
	arr_valor = valor.split("¬");
	document.frm.cargo_codigo.value = arr_valor[0];
	document.frm.cargo_descripcion.value =  arr_valor[1];
 }
 function Buscar(){
  var cargo_codigo=document.frm.cargo_codigo.value;
   document.frm.hddbuscar.value='OK';
   document.frm.action +="?t=" + document.frm.hddtipo.value + "&cargo=" + cargo_codigo;
   document.frm.submit();
 }

 function ver_supervisor(codigo){
  var valor = window.showModalDialog("ver_supervisor.php?codigo=" + codigo, "Equipo","dialogWidth:350px; dialogHeight:350px");
 }
 function Quitar(codigo){
	if (confirm('Seguro de quitar al empleado del grupo')==false) return false;
	document.frm.hddaccion.value='DLT';
	document.frm.hddcodigos.value= codigo;
	document.frm.submit();
}
function cerrar(){
	Ext_Dialogo.close_Dialog('div_ver');
}
</script>
<body class="PageBODY" onLoad="return WindowResize(10,20,'center')">
<form name='frm' id='frm' action='<?php echo $_SERVER['PHP_SELF'] ?>' method='post' onSubmit='javascript:return ok();'>

<br>
<table class='DataTD' align=center  width='53%' border='0' cellpadding='1' cellspacing='0'>
	<tr>
      <td class='CA_FieldCaptionTD' align="center">
		Seleccionar Supervisor&nbsp;
	   </td>
	</tr>
	<tr>
		<td align=center class="CA_DataTD" >
			<?php

			$ssql="SELECT  CA_Empleado_Rol.Empleado_codigo,  ";
			$ssql.= "     Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres AS empleado ";
			$ssql.= " FROM  CA_Empleado_Rol INNER JOIN ";
			$ssql.= "       Empleado_Area ON CA_Empleado_Rol.Empleado_codigo = Empleado_Area.Empleado_Codigo INNER JOIN ";
			$ssql.= "       Empleados ON Empleado_Area.Empleado_Codigo = Empleados.Empleado_Codigo ";
			$ssql.= " WHERE (Empleado_Area.Empleado_Area_Activo = 1) ";
			$ssql.= " 	AND (CA_Empleado_Rol.Rol_Codigo = 1) ";
			$ssql.= " 	and area_codigo in (" . $areas_subordinadas . ") ";
			$ssql.= " 	and empleados.estado_codigo=1 AND CA_Empleado_Rol.EMPLEADO_ROL_ACTIVO=1";
			$ssql.= " order by 2 ";

			$combo->query = $ssql;
			$combo->name = "responsable_codigo";
			$combo->value = $responsable_codigo."";
			$combo->more = "class=select style='width:90%''";
			$rpta = $combo->Construir();
			echo $rpta;
		    ?>
		</td>

  </tr>
  <tr align="center" >
    <td class="DataTD">
	 <input class=buttons type="button" id="cmdAgrupar" value="Asignar"  style="width:80px">
     <input class=buttons type="button" id="cmdVer" onClick="Consultar_equipo()" value="Ver Grupo" style="width:80px">
	 <input class=buttons type="button" id="cmdCerrar" onClick="self.location.href='../menu.php'" value="Cerrar" style="width:80px">
	</td>
<tr>
</table>

<br>
<?php
	$npag = 1;
	$orden = "2";
	$buscam = "";
	$torder="ASC";



    $objr = new MyGrilla;
    $objr->setDriver_Coneccion(db_name());
    $objr->setUrl_Coneccion(db_host());
    $objr->setUser(db_user());
    $objr->setPwd(db_pass());
    $objr->setOrder($orden);
    $objr->setFindm($buscam);
    $objr->setNoSeleccionable(true);
    $objr->setFont("color=#000000");
    $objr->setFormatoBto("class=button");
    $objr->setFormaTabla(" class=FormTABLE style='width:100%' cellspacing='1' cellpadding='0' border='0' ");
    $objr->setFormaCabecera(" class=ColumnTD ");
    $objr->setFormaItems(" class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'");
    $objr->setTOrder($torder);
	
	$from= "Empleado_Area(nolock) INNER JOIN Empleados(nolock) ON ";
	$from.= " Empleado_Area.Empleado_Codigo = Empleados.Empleado_Codigo INNER JOIN vw_empleado_area_cargo(nolock) ON ";
	$from.= " vw_empleado_area_cargo.Empleado_Codigo = Empleados.Empleado_Codigo  LEFT OUTER JOIN";
	$from.= " (SELECT a.empleado_codigo, a.responsable_codigo, e.empleado_apellido_paterno + ' ' + e.empleado_apellido_materno + ' ' + e.empleado_nombres AS Responsable, a.asignacion_activo";
	$from.= " FROM CA_Asignacion_Empleados a INNER JOIN empleados e ON  a.responsable_codigo = e.empleado_codigo";
    $from.= " WHERE asignacion_activo = 1) ca_ae ON Empleados.Empleado_Codigo = ca_ae.empleado_codigo";
	$objr->setFrom($from);

	$where = "Empleados.Estado_Codigo = 1 AND ";
	$where.= " 	Empleado_Area.Empleado_Area_Activo = 1 AND ";
	if($tipo ==1 ) $where.=  " Empleado_Area.Area_Codigo in (" . $areas_subordinadas .")";
	if($cargo_codigo !=0) $where .= " and vw_empleado_area_cargo.cargo_codigo=" . $cargo_codigo;
	if($nombre!='') $where .= " and Empleados.Empleado_Apellido_Paterno + ' '  + Empleados.Empleado_Apellido_Materno  + ' '  +   Empleados.Empleado_Nombres like '%" . $nombre . "%'";
	

	$objr->setWhere($where);
	$objr->setSize(200);
    $objr->opcionesComboPaginador = true;  // Activa el combo de paginacion
	$objr->setArraypagination(array(200,300,400));  // es el array del combo de paginacion ejemplo 50, 100, 150 
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(true);
    // Arreglo de Alias de los Campos de la consulta
    $arrAlias[0] = "root";
    $arrAlias[1] = "DNI";
    $arrAlias[2] = "Empleado";
    $arrAlias[3] = "Area";
    $arrAlias[4] = "Cargo";
    $arrAlias[5] = "Asignado";


    // Arreglo de los Campos de la consulta
    $arrCampos[0] = "Empleados.Empleado_Codigo";
    $arrCampos[1] = "Empleados.Empleado_Dni";
    $arrCampos[2] = "Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno     + ' ' + Empleados.Empleado_Nombres"; 
    $arrCampos[3] = "vw_empleado_area_cargo.area_descripcion"; 
    $arrCampos[4] = "vw_empleado_area_cargo.Cargo_Descripcion"; 
    $arrCampos[5] = "ca_ae.Responsable"; 
	// echo $objr->getmssql();

    $objr->setAlias($arrAlias);
    $objr->setCampos($arrCampos);

	$body = $objr->Construir();  //ejecutar
	$objr = null;
	echo $body;
?>

<br>
<input type="hidden" id="hddarea" name="hddarea" value="<?php echo $area ?>">
<input type="hidden" id="hddgrupo" name="hddgrupo" value="">
<input type="hidden" id="hddrol" name="hddrol" value="">
<input type="hidden" id="hddcodigos" name="hddcodigos" value="">
<input type="hidden" id="hddaccion" name="hddaccion" value="">
<input type="hidden" id="hddbuscar" name="hddbuscar" value="">
<input type='hidden' id='hddtipo' name='hddtipo' value="1" >
<div id="div_ver"></div>
</form>
<?php 
        echo "<br>";
        echo Menu("../menu.php");
 ?>
<script type="text/javascript">
$(document).ready(function(){


	$('#cmdAgrupar').click(function(){
		var codigos='';

		if (document.frm.responsable_codigo.value==0){
			alert('Seleccione Responsable');
			document.frm.responsable_codigo.focus();
			return false;
		}
		codigos=PooGrilla.SeleccionMultiple()
		if (codigos==''){
			alert('Seleccione registros de Empleados');
			return false
		}
		if (confirm('Enviar empleados seleccionados al Responsable?')==false) return false;
		document.frm.hddaccion.value='SVE';
		document.frm.hddcodigos.value= codigos;
		document.frm.submit();

	});

});
</script>

</body>
</html>