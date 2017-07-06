<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1');
  require_once("../includes/Seguridad.php");
  require_once("../../Includes/Connection.php");
  require_once("../../Includes/Constantes.php"); 
  require_once("../../Includes/mantenimiento.php");
  //require_once("../../includes/Seguridad.php");
  require_once("../includes/clsCA_Asistencia_Incidencias.php");
  require_once("../../Includes/MyGrillaEasyUI.php");
  
 $o = new ca_asistencia_incidencias();
 $o->setMyUrl(db_host());
 $o->setMyUser(db_user());
 $o->setMyPwd(db_pass());
 $o->setMyDBName(db_name());

  
	$body="";
	$npag = 1;
	$orden = "Empleado";
	$buscam = "";
	$torder="ASC";
	
	if (isset($_POST["pagina"])) $npag = $_POST["pagina"];
	if (isset($_POST["orden"])) $orden = $_POST["orden"];
	if (isset($_POST["buscam"])){
	  $buscam = $_POST["buscam"]; 
	}else{
	   $buscam = "EMPLEADO|Ninguno ";
	} 
	
	if (isset($_GET["pagina"])) $npag = $_GET["pagina"];
	if (isset($_GET["orden"])) $orden = $_GET["orden"];
	
    //if (isset($_GET["buscam"])) $buscam = $_GET["buscam"];
	
	if (isset($_POST["cboTOrden"])) $torder = $_POST["cboTOrden"];
    
     //echo $_SESSION["empleado_codigo"];
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Catálogo de Incidencias</title>
<meta http-equiv="pragma" content="no-cache"/>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
<?php  require_once('../includes/librerias_easyui.php');?>

<script>

function cmdAdicionar_onclick() {
    self.location.href="incidencias_job.php?incidencia_codigo=&pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
}
function cmdModificar_onclick() {
    var rpta=Registro();
    if (rpta != '' ) {
    self.location.href="incidencias_job.php?incidencia_codigo=" + rpta + "&pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
    }
}

</script>

</head>


<body class="PageBODY" onload="return WindowResize(10,20,'center')" >

<center class="FormHeaderFont">Consulta Asistencia Programada</center>
<form id="frm" name="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<!--<table class='FormTable' border="0" cellpadding="1" cellspacing="1" width='100%' id='tblOpciones'>
    <tr>
        <td class='ColumnTD'>
            <input class="button" type='button' value='Adicionar' id='cmdAdicionar' name='cmdAdicionar'   onclick='return cmdAdicionar_onclick()' style='width:80px;'>
            <input class="button" type='button' value='Modificar' id='cmdModificar' name='cmdModificar'  onclick='return cmdModificar_onclick()' style='width:80px;'>
        </td>
    </tr>
</table>
-->
<?php
	$usuario = $_SESSION["empleado_codigo"];
    $tiene_rol_administrador = $o->Tiene_Rol_Necesario($_SESSION["empleado_codigo"],3);
    
	$objr = new MyGrilla;
	$objr->setDriver_Coneccion(db_name());
    $objr->setUrl_Coneccion(db_host());
    $objr->setUser(db_user());
    $objr->setPwd(db_pass());
    $objr->setOrder($orden);
	$objr->setFindm($buscam);
	$objr->setNoSeleccionable(false);
	$objr->setFont("color=#000000");
	$objr->setFormatoBto("class = buttons");
	$objr->setFormaTabla("class=FormTABLE style='width:130%' cellspacing='1' cellpadding='0' border='0' ");
	$objr->setFormaCabecera("class=ColumnTD");
	$objr->setFormaItems("class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'");
	$objr->setTOrder($torder);
	
	$from = " ca_asistencia_programada cap	inner join vdatos v on cap.empleado_codigo = v.Empleado_Codigo	inner join ca_turnos t on t.Turno_Codigo = cap.turno_codigo ";
	
	$objr->setFrom($from);
	$where= " cap.asistencia_fecha = CONVERT(datetime,convert(varchar(10),GETDATE(),103),103)";
    if($tiene_rol_administrador == "NO"){
        $where.= "and v.Area_Codigo in (select area_codigo from CA_Controller where Empleado_Codigo = ".$usuario." and activo = 1)";    
    }
    
    
	$objr->setWhere($where);
	$objr->setSize(25);
	$objr->setUrl($_SERVER['PHP_SELF']);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(false);
	// Arreglo de Alias de los Campos de la consulta
        $arrAlias[0] = "root";
        $arrAlias[1] = "DNI";
        $arrAlias[2] = "Empleado";
        $arrAlias[3] = "Area";
        $arrAlias[4] = "Cargo";
        $arrAlias[5] = "Turno";
        $arrAlias[6] = "Cod_Turno";
        $arrAlias[7] = "Refrigerio";
        $arrAlias[8] = "R_Entrada";
        $arrAlias[9] = "R_Salida";
        $arrAlias[10] = "Extension";
        $arrAlias[11] = "F_Extension";
//        $arrAlias[12] = " Duo";
        $arrAlias[12] = "SinControl";
        $arrAlias[13] = "Incidencia";
	
        // Arreglo de los Campos de la consulta
        $arrCampos[0]  = "cap.empleado_codigo";
        $arrCampos[1]  = "cap.empleado_dni";
        $arrCampos[2]  = "v.empleado";
        $arrCampos[3]  = "v.Area_Descripcion";
        $arrCampos[4]  = "v.Cargo_descripcion";
        $arrCampos[5]  = "t.Turno_Descripcion";
        $arrCampos[6]  = "t.Turno_id";
        $arrCampos[7]  = "t.Turno_Refrigerio";
        $arrCampos[8]  = "isnull(CONVERT(varchar(10), cap.asistencia_entrada,108),'')";
        $arrCampos[9]  = "isnull(CONVERT(varchar(10), cap.asistencia_salida, 108),'')";
        $arrCampos[10]  = "case when extension_turno = 1 then  extension_tiempo  end";
        $arrCampos[11]  = "case when extension_turno = 1 then  convert(varchar(10),UPD_EXTENSION,103) + ' ' + convert(varchar(10),UPD_EXTENSION,108) end ";
        //$arrCampos[12] = " case when cap.turno_duo = 1 then 'SI' else 'NO' end";
        $arrCampos[12] = "case when cap.marca_sin_control = 1 then 'SI' else 'NO' end";
        $arrCampos[13] = "isnull(cap.detalle_inasistencia,'')";
        
	
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