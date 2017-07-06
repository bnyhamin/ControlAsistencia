<?php header("Expires: 0");
  //require_once("../includes/Seguridad.php");
  require_once("../../Includes/Connection.php");
  require_once("../../Includes/mantenimiento.php");
  require_once("../../Includes/Constantes.php");
  require_once("../../Includes/MyGrilla.php");
  require_once("../includes/clsCA_Empleados.php");
  require_once("../../Includes/clswfm_empleado_restricciones.php"); 
  
//$empleado_codigo="";
//$empleado_nombre="";

$emp = new ca_empleados();
$emp->MyUrl = db_host();
$emp->MyUser= db_user();
$emp->MyPwd = db_pass();
$emp->MyDBName= db_name();

$er = new wfm_empleado_restricciones();
$er->MyUrl = db_host();
$er->MyUser= db_user();
$er->MyPwd = db_pass();
$er->MyDBName= db_name();

 ?>
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
	<link rel="stylesheet" type="text/css" href="../style/tstyle.css">
	<script language="JavaScript" src="../../default.js"></script>
    <title>Lista de Restricciones</title>
</head>

<?php
$body="";
$npag = 1;
$orden = "codigo";
$buscam = "";
$torder="DESC";

if (isset($_POST["pagina"]))	$npag = $_POST["pagina"];
elseif (isset($_GET["pagina"])) $npag = $_GET["pagina"];
if (isset($_POST["orden"])) $orden = $_POST["orden"];
elseif (isset($_GET["orden"])) $orden = $_GET["orden"];
if (isset($_POST["buscam"]))	$buscam = $_POST["buscam"];
elseif (isset($_GET["buscam"]))	$buscam = $_GET["buscam"];
if (isset($_POST["cboTOrden"])) $torder = $_POST["cboTOrden"];
elseif (isset($_GET["cboTOrden"])) $torder = $_GET["cboTOrden"];

if (isset($_POST["hddempleado_codigo"])) $empleado_codigo = $_POST["hddempleado_codigo"];
elseif (isset($_GET["empleado_codigo"])) $empleado_codigo = $_GET["empleado_codigo"];

if (isset($_POST["hddsemana"])) $semana = $_POST["hddsemana"];

if (isset($_POST["hddanio"])) $anio = $_POST["hddanio"];

if (isset($_POST["hddrestriccion_codigo"])) $restriccion_codigo = $_POST["hddrestriccion_codigo"];

$emp->empleado_codigo=$empleado_codigo;
$rpta=$emp->Query();
$empleado_nombre=$emp->empleado_nombre;

if ($_POST["hddaccion"] == "DEL"){
	
	$er->empleado_codigo=$empleado_codigo;
	$er->semana=$semana;
	$er->anio=$anio;
	$er->restriccion_codigo=$restriccion_codigo;
	
	$er->anular();
}

?>
<script language=javascript>

function cmdAdicionar_onclick(){
	var valor= <?php echo $empleado_codigo;?>

	CenterWindow("empleado_restricciones.php?empleado_codigo=" + valor , "ModalChild",450,250,"yes","center");

}

function cmdEliminar_onclick(){
	valor=Registro();
	
	if (valor =="" ){
        return false;
 	}
 	arr_valor = valor.split("@");
	frm.hddempleado_codigo.value = arr_valor[0];
	frm.hddsemana.value =  arr_valor[1];
	frm.hddanio.value =  arr_valor[2];
	frm.hddrestriccion_codigo.value =  arr_valor[3];
 	
 	if (confirm('Confirme Eliminar registro?')==false) return false;
	
	document.frm.hddaccion.value="DEL";
    document.frm.submit();

}

function cmdCerrar_onclick() {
	self.returnValue = 0
	self.close();
}
</script>
<body class="PageBODY">
<center class="FormHeaderFont">Lista de Restricciones</center>
<center class="CA_FormHeaderFont"><?php echo $empleado_nombre ?></center>
<form name=frm id=frm action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">

<table class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%'>
<tr>
	<td class='ColumnTD' align="left">
		<INPUT class=button type="button" value="Adicionar" id='cmdAdicionar' name='cmdAdicionar' LANGUAGE=javascript onclick="return cmdAdicionar_onclick()">
		<INPUT class=button type="button" value="Eliminar" id='cmdEliminar' name='cmdEliminar' LANGUAGE=javascript onclick="return cmdEliminar_onclick()">
	</td>

		<td class='ColumnTD' align="right">
		<INPUT class=button type="button" value="Cerrar" id='cmdCerrar' name='cmdCerrar' LANGUAGE=javascript onclick="return cmdCerrar_onclick()">
	</td>
</tr>
</table>
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
	

	$from = " wfm_empleado_restricciones ";
	$from .= " inner join wfm_restriccion on wfm_empleado_restricciones.restriccion_codigo = wfm_restriccion.restriccion_codigo ";
    $objr->setFrom($from);
	$where= " wfm_empleado_restricciones.empleado_codigo=" . $empleado_codigo;
	$objr->setWhere($where);
	$objr->setSize(20);
	$objr->setUrl($_SERVER['PHP_SELF']);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(false);
	// Arreglo de Alias de los Campos de la consulta
    $arrAlias[0] = "root";
    $arrAlias[1] = "Codigo";
    $arrAlias[2] = "Restriccion";
	$arrAlias[3] = "Fecha";
	//$arrAlias[4] = "Fecha_Fin";
	$arrAlias[4] = "Semana";
	$arrAlias[5] = "Año";
	// Arreglo de los Campos de la consulta
    $arrCampos[0] = "cast (wfm_empleado_restricciones.empleado_codigo as varchar ) + '@' + cast (wfm_empleado_restricciones.semana as varchar)+ '@' + cast(wfm_empleado_restricciones.anio as varchar) + '@' + cast( wfm_restriccion.restriccion_codigo as varchar)  ";
    $arrCampos[1] = "wfm_restriccion.restriccion_codigo";
    $arrCampos[2] =	"wfm_restriccion.restriccion_descripcion";
	$arrCampos[3] = "convert (varchar(10),wfm_empleado_restricciones.fecha,103)  ";
	//$arrCampos[4] = "convert (varchar(10),wfm_empleado_restricciones.fecha_fin,103) ";
	$arrCampos[4] = "wfm_empleado_restricciones.semana ";
	$arrCampos[5] = "wfm_empleado_restricciones.anio ";
	//$arrCampos[5] = "sugerencias";
	$objr->setAlias($arrAlias);
	$objr->setCampos($arrCampos);
	$body = $objr->Construir();  //ejecutar
	//echo $objr->getmssql();
	$objr = null;
	echo $body;
?>

<br><br><br>

<input type='hidden' id="hddaccion" name="hddaccion" value=''>
<input type='hidden' name='hddempleado_codigo' id='hddempleado_codigo' value='<?php echo $empleado_codigo?>'>
<input type='hidden' name='hddsemana' id='hddsemana' value=''>
<input type='hidden' name='hddanio' id='hddanio' value=''>
<input type='hidden' name='hddrestriccion_codigo' id='hddrestriccion_codigo' value=''>
</form>
</body>
</html>