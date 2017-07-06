<?php header("Expires: 0"); 
  require_once("../includes/Seguridad.php");
  require_once("../../Includes/Connection.php");
  require_once("../../Includes/Constantes.php"); 
  //require_once("../../includes/Seguridad.php");
require_once("../includes/MyGrillaEasyUI.php");
  
	$body="";
	$npag = 1;
	$orden = "Codigo";
	$buscam = "";
	$torder="ASC";
	
	if (isset($_POST["pagina"])) $npag = $_POST["pagina"];
	if (isset($_POST["orden"])) $orden = $_POST["orden"];
	if (isset($_POST["buscam"])) $buscam = $_POST["buscam"];
	
	if (isset($_GET["pagina"])) $npag = $_GET["pagina"];
	if (isset($_GET["orden"])) $orden = $_GET["orden"];
	if (isset($_GET["buscam"])) $buscam = $_GET["buscam"];
	
	if (isset($_POST["cboTOrden"])) $torder = $_POST["cboTOrden"];
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Catálogo de Turnos</title>
<meta http-equiv="pragma" content="no-cache"/>
<meta name="AUTHOR" content="TUMI Solutions S.A.C."/>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css"/>
<?php  require_once('../includes/librerias_easyui.php');?>

<script>

function cmdAdicionar_onclick() {
    self.location.href="turnos_job.php?Turno_codigo=&pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
}
function cmdModificar_onclick() {
    var rpta=PooGrilla.Registro();
    if (rpta != '' ) {
    self.location.href="turnos_job.php?turno_codigo=" + rpta + "&pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
    }
}

function cmdImprimir_onclick(){
	CenterWindow("pdf_turnos.php", "ModalChild",1000,680,"yes","center");
}

function cmdExportar_onclick(){
//	CenterWindow("lista_turnos.php","Reporte",900,600,"yes","center");
	CenterWindow("../gestionturnos/reporte_catalogo.php","Reporte_C",400,200,"yes","center");
}

</script>

</head>

<body class="PageBODY" onload="return WindowResize(10,20,'center')" >
<center class="FormHeaderFont">Catálogo de Turnos</center>
<form id="frm" name="frm" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
<table class='FormTable' border="0" cellpadding="1" cellspacing="1" width='100%' id='tblOpciones'>
    <tr>
        <td class='ColumnTD'>
            <input class="buttons" type='button' value='Adicionar' id='cmdAdicionar' name='cmdAdicionar' onclick='return cmdAdicionar_onclick()' style='width:80px;'>
            <input class="buttons" type='button' value='Modificar' id='cmdModificar' name='cmdModificar' onclick='return cmdModificar_onclick()' style='width:80px;'>
        	<input class="buttons" type='button' value='Exportar' id='cmdExportar' name='cmdExportar' onclick='return cmdExportar_onclick()' style='width:80px;'>
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
	
	$from = " CA_Turnos left join Items on Items.item_codigo=CA_Turnos.turno_modalidad ";
	$from .= " left join Items i on i.item_codigo=CA_Turnos.turno_horario ";

	$objr->setFrom($from);
	$where= "";
	$objr->setWhere($where);
	$objr->setSize(25);
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(false);
        // Arreglo de Alias de los Campos de la consulta
        $arrAlias[0] = "root";
        $arrAlias[1] = "Codigo";
        $arrAlias[2] = "Cod_turno";
        $arrAlias[3] = "Descripcion";
        $arrAlias[4] = "Tipo";
        /*$arrAlias[5] = "Lun";
        $arrAlias[6] = "Mar";
        $arrAlias[7] = "Mie";
        $arrAlias[8] = "Jue";
        $arrAlias[9] = "Vie";
        $arrAlias[10] = "Sab";
        $arrAlias[11] = "Dom";
        */$arrAlias[5] = "Refri";
        $arrAlias[6] = "Ini_Refri";
        $arrAlias[7] = "Desc1";
        $arrAlias[8] = "Desc2";
        $arrAlias[9] = "Duo";
        $arrAlias[10] = "Activo";
        // Arreglo de los Campos de la consulta
        $arrCampos[0] = "Turno_Codigo";
        $arrCampos[1] = "Turno_Codigo"; 
        $arrCampos[2] = "Turno_id"; 
        $arrCampos[3] =	"Turno_Descripcion";
        $arrCampos[4] =	"CASE WHEN tipo_area_codigo=1 then 'Operativo' else 'Administrativo' end ";
        
/*	$arrCampos[5] = "CASE WHEN Dia1 = 1 then 'Si' else 'No' end ";
	$arrCampos[6] = "CASE WHEN Dia2 = 1 then 'Si' else 'No' end ";
	$arrCampos[7] = "CASE WHEN Dia3 = 1 then 'Si' else 'No' end ";
	$arrCampos[8] = "CASE WHEN Dia4 = 1 then 'Si' else 'No' end ";
	$arrCampos[9] = "CASE WHEN Dia5 = 1 then 'Si' else 'No' end ";
	$arrCampos[10] = "CASE WHEN Dia6 = 1 then 'Si' else 'No' end ";
	$arrCampos[11] = "CASE WHEN Dia7 = 1 then 'Si' else 'No' end ";
        */    $arrCampos[5] = "Turno_Refrigerio";
        $arrCampos[6] = "case when turno_hora_refrigerio=-1 then '' else case when turno_hora_refrigerio is null then '' else cast(turno_hora_refrigerio as varchar)+':'+cast(turno_minuto_refrigerio as varchar) end end"; 
        $arrCampos[7] = "Turno_descanzo"; 
        $arrCampos[8] = "Turno_descanso2"; 
        $arrCampos[9] = "CASE WHEN Turno_Duo = 1 then 'Si' else 'No' end ";
        $arrCampos[10] = "CASE WHEN Turno_Activo = 1 then 'Si' else 'No' end ";
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