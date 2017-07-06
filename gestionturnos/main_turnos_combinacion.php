<?php header("Expires: 0");?>
<?php

  require_once("../includes/Seguridad.php");
  require_once("../../Includes/Connection.php");
  require_once("../../Includes/Constantes.php"); 
  //require_once("../../includes/Seguridad.php");
  // require_once("../../Includes/MyGrilla.php");
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
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Catálogo de Combinación de Turnos</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css"/>
<?php  require_once('../includes/librerias_easyui.php');?>

<script>
var url_jr="<?php echo $url_jreportes ?>";

function cmdAdicionar_onclick() {
    self.location.href="turnos_combinacion_job.php?Turno_codigo=&pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
}
function cmdModificar_onclick() {
    var rpta=Registro();
    if (rpta != '' ) {
    self.location.href="turnos_combinacion_job.php?tc_codigo=" + rpta + "&pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
    }
}
function cmdConsultar_onclick(obj) {
	marcar_reg(obj);
    var rpta=obj;
    if (rpta != '' ) {
		CenterWindow("turnos_combinacion_con.php?tc_codigo=" + rpta , "DetComb",800,450,"yes","center");
    }
}
function cmdConsultar_Rdo_onclick() {
    var rpta=Registro();
    if (rpta != '' ) {
		CenterWindow("turnos_combinacion_con.php?tc_codigo=" + rpta , "DetComb",800,450,"yes","center");
    }
}
function cmdExportar_onclick(){
	CenterWindow(url_jr + "Gap/exportar_combinacion.jsp","Combinaciones",400,200,"yes","center");
//    frames['ifr_procesos'].location.href = "procesos.php?opcion=Ex_Com";
}

function cmdExportar_turno_onclick(){
	CenterWindow("../gestionturnos/reporte_catalogo.php","Reporte_C",400,200,"yes","center");
}

function marcar_reg(obj){ 
	var r=document.getElementsByTagName('input');
	for (var i=0; i < r.length; i++){
		var o=r[i];
		if (o.name=='rdo'){
			if (o.value*1==obj*1){
				o.checked=true;
			}
		}
	}
}

</script>

</head>


<body class="PageBODY" onLoad="return WindowResize(10,20,'center')" >

<CENTER class="FormHeaderFont">Catálogo de Combinación de Turnos</CENTER>
<form id=frm name=frm method=post action="<?php echo $_SERVER["PHP_SELF"];?>">
<TABLE class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    <TR>
        <TD class='ColumnTD'>
            <INPUT class=buttons type='button' value='Adicionar' id='cmdAdicionar' name='cmdAdicionar' LANGUAGE=javascript onclick='return cmdAdicionar_onclick()' style='width:80px;'>
<!--            <INPUT class=button type='button' value='Modificar' id='cmdModificar' name='cmdModificar'  LANGUAGE=javascript onclick='return cmdModificar_onclick()' style='width:80px;'>-->
            <INPUT class=buttons type='button' value='Exportar Combinacion' id='cmdExportar' name='cmdExportar'  LANGUAGE=javascript onclick='return cmdExportar_onclick()' style='width:140px;'>
            <INPUT class=buttons type='button' value='Exportar Turnos' id='cmdExportart' name='cmdExportart'  LANGUAGE=javascript onclick='return cmdExportar_turno_onclick()' style='width:100px;'>
        </TD>
    </TR>
</TABLE>

<?php
	
	$objr = new MyGrilla;
	$objr->setDriver_Coneccion(db_name());
    $objr->setUrl_Coneccion(db_host());
    $objr->setUser(db_user());
    $objr->setPwd(db_pass());
    $objr->setOrder($orden);
	$objr->setFindm($buscam);
	// $objr->setEvento(" onclick='return cmdConsultar_Rdo_onclick()' title='Seleccione para ver detalle' ");
	$objr->setNoSeleccionable(false);
	$objr->setFont("color=#000000");
	$objr->setFormatoBto("class=button");
	$objr->setFormaTabla(" class=FormTABLE style='width:100%' cellspacing='1' cellpadding='0' border='0' ");
	$objr->setFormaCabecera(" class=ColumnTD ");
	$objr->setFormaItems(" class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'");
	$objr->setTOrder($torder);
	
	$from = " vca_turnos_combinacionthf ";
/*	$from = " CA_Turnos_Combinacion tc left join CA_Turnos t1 on tc.turno_dia1=t1.turno_codigo ";
	$from .= " left join CA_Turnos t2 on tc.turno_dia2=t2.turno_codigo  ";
	$from .= " left join CA_Turnos t3 on tc.turno_dia3=t3.turno_codigo  ";
	$from .= " left join CA_Turnos t4 on tc.turno_dia4=t4.turno_codigo  ";
	$from .= " left join CA_Turnos t5 on tc.turno_dia5=t5.turno_codigo  ";
	$from .= " left join CA_Turnos t6 on tc.turno_dia6=t6.turno_codigo  ";
	$from .= " left join CA_Turnos t7 on tc.turno_dia7=t7.turno_codigo  ";
*/	
	
	$objr->setFrom($from);
	$where= "";
	$objr->setWhere($where);
	$objr->setSize(20);
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(false);
	// Arreglo de Alias de los Campos de la consulta
    $arrAlias[0] = "root";
    $arrAlias[1] = "Codigo";
    $arrAlias[2] = "Cod_Comb";
	$arrAlias[3] = "Lun";
	$arrAlias[4] = "Mar";
	$arrAlias[5] = "Mie";
	$arrAlias[6] = "Jue";
	$arrAlias[7] = "Vie";
	$arrAlias[8] = "Sab";
	$arrAlias[9] = "Dom";
	$arrAlias[10] = "THor";
	$arrAlias[11] = "TRef";
	$arrAlias[12] = "TEfe";
	$arrAlias[13] = "Act";
	// Arreglo de los Campos de la consulta
    $arrCampos[0] = "Tc_Codigo";
    $arrCampos[1] = "Tc_Codigo"; 
    $arrCampos[2] = "'<font id='+cast(Tc_Codigo as varchar)+' style=cursor:hand onclick=cmdConsultar_onclick(this.id) title=ver_detalle><b>'+Tc_Codigo_Sap+'</b></font>'";
	$arrCampos[3] = "tturno_dia1";
	$arrCampos[4] = "tturno_dia2";
	$arrCampos[5] = "tturno_dia3";
	$arrCampos[6] = "tturno_dia4";
	$arrCampos[7] = "tturno_dia5";
	$arrCampos[8] = "tturno_dia6";
	$arrCampos[9] = "tturno_dia7";
	$arrCampos[10] = "case when ttotal_horas<=9 then '0'+cast(ttotal_horas as varchar) else cast(ttotal_horas as varchar) end+':'+case when ttotal_minutos<=9 then '0'+cast(ttotal_minutos as varchar) else cast(ttotal_minutos as varchar) end";	
	$arrCampos[11] = "case when horas_refrigerio<=9 then '0'+cast(horas_refrigerio as varchar) else cast(horas_refrigerio as varchar) end+':'+case when minutos_refrigerio<=9 then '0'+cast(minutos_refrigerio as varchar) else cast(minutos_refrigerio as varchar) end";
	$arrCampos[12] = "case when total_horas<=9 then '0'+cast(total_horas as varchar) else cast(total_horas as varchar) end +':'+case when total_minutos<=10 then '0'+cast(total_minutos as varchar) else cast(total_minutos as varchar) end";
	$arrCampos[13] = "CASE WHEN tc_activo = 1 then 'Si' else 'No' end ";
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
<div style='position:absolute;left:100px;top:500px;display:none' id='div_procesos'>
    <iframe id='ifr_procesos' name='ifr_procesos' width='700px' height='100px' src=''></iframe>
</div>
</body>
</html>