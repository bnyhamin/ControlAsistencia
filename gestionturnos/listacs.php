<?php header("Expires: 0");

  require_once("../includes/Seguridad.php"); 
  require_once("../../Includes/Connection.php");
  require_once("../../Includes/Constantes.php"); 
  //require_once("../../includes/Seguridad.php");
  require_once("../../Includes/MyGrilla.php");

$tturno_dia1="";
$tturno_dia2="";
$tturno_dia3="";
$tturno_dia4="";
$tturno_dia5="";
$tturno_dia6="";
$tturno_dia7="";
  
	$body="";
	$npag = 1;
	$orden = "Codigo";
	$buscam = "";
	$torder="ASC";
	$tc_codigo_sap="";
	
	if (isset($_POST["pagina"])) $npag = $_POST["pagina"];
	if (isset($_POST["orden"])) $orden = $_POST["orden"];
	if (isset($_POST["buscam"])) $buscam = $_POST["buscam"];
	if (isset($_POST["cboTOrden"])) $torder = $_POST["cboTOrden"];
	
	if (isset($_GET["pagina"])) $npag = $_GET["pagina"];
	if (isset($_GET["orden"])) $orden = $_GET["orden"];
	if (isset($_GET["buscam"])) $buscam = $_GET["buscam"];
	if (isset($_GET["tc_codigo_sap"])) $tc_codigo_sap = $_GET["tc_codigo_sap"];
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Catálogo de Turnos Combinación</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css">
</head>
<script language=javascript>

function cmdEnviar(){
    var rpta=Registro();
    if (rpta != '' ){
        var arr= rpta.split("¬");
		window.opener.filtroCS(arr[0], arr[1], arr[2], arr[3], arr[4], arr[5], arr[6], arr[7], arr[8], arr[9],arr[10], arr[11],arr[12], arr[13],arr[14], arr[15],arr[16], arr[17],arr[18], arr[19],arr[20], arr[21],arr[22],arr[23],arr[24],arr[25]);
		window.close();
	}
}

function sel_fila(n){
    var arr = n.split("¬");
    //alert('DIAS    TURNOS ' + '\n' + ' ' + '\n' + 'LU  ' + arr[1] + ' a ' + arr[2] + '\n' + 'MA ' + arr[3] + ' a ' + arr[4] + '\n' + 'MI  ' + arr[5] + ' a ' + arr[6] + '\n' + 'JU  ' + arr[7] + ' a ' + arr[8] + '\n' + 'VI   ' + arr[9] + ' a ' + arr[10] + '\n' + 'SA  ' + arr[11] + ' a ' + arr[12] + '\n' + 'DO ' + arr[13] + ' a ' + arr[14]);
    //frames['ifr_procesos'].location.href = "detalle_turnos.php?tturno_dia1="+ arr[1]+' a '+arr[2] + "&tturno_dia2="+ arr[3]+' a '+arr[4] + "&tturno_dia3="+ arr[5]+' a '+arr[6] + "&tturno_dia4="+ arr[7]+' a '+arr[8] + "&tturno_dia5="+ arr[9]+' a '+arr[10] + "&tturno_dia6="+ arr[11]+' a '+arr[12] + "&tturno_dia7="+ arr[13]+' a '+arr[14] ;
	tturno_dia1.innerHTML = arr[1]+' a '+arr[2];
	tturno_dia2.innerHTML = arr[3]+' a '+arr[4];
	tturno_dia3.innerHTML = arr[5]+' a '+arr[6]; 
	tturno_dia4.innerHTML = arr[7]+' a '+arr[8]; 
	tturno_dia5.innerHTML = arr[9]+' a '+arr[10]; 
	tturno_dia6.innerHTML = arr[11]+' a '+arr[12]; 
	tturno_dia7.innerHTML = arr[13]+' a '+arr[14]; 
}

function cerrar(){
    window.close();
}

function sel_filan(){
    var rpta=Registro();
    if (rpta != '' ){
        var arr= rpta.split("¬");
		tturno_dia1.innerHTML = arr[9] +' a '+arr[10];
		tturno_dia2.innerHTML = arr[11]+' a '+arr[12];
		tturno_dia3.innerHTML = arr[13]+' a '+arr[14]; 
		tturno_dia4.innerHTML = arr[15]+' a '+arr[16]; 
		tturno_dia5.innerHTML = arr[17]+' a '+arr[18]; 
		tturno_dia6.innerHTML = arr[19]+' a '+arr[20]; 
		tturno_dia7.innerHTML = arr[21]+' a '+arr[22];
	} 
}

</script>

<body class="PageBODY" >
<!--onLoad="return WindowResize(10,20,'center')"-->
<center class="FormHeaderFont">Catálogo de Turnos Combinación</center>
<form id=frm name=frm method=post action="<?php echo $_SERVER["PHP_SELF"];?>">
<table class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    <tr>
        <td class='ColumnTD'>
			<input type="button" class="Button" id="cmde" name="cmde" value="Aceptar" style="width:80px"  onClick="cmdEnviar()">
			<input type="button" class="Button" id="cmdg" name="cmdg" value="Cancelar" style="width:80px"  onClick="cerrar()">
        </td>
    </tr>
</table>
<table class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblDetalles'>
 <tr>
  <td class='ColumnTD'>
<?php
	$objr = new MyGrilla;
	$objr->setDriver_Coneccion(db_name());
    $objr->setUrl_Coneccion(db_host());
    $objr->setUser(db_user());
    $objr->setPwd(db_pass());
    $objr->setOrder($orden);
	$objr->setFindm($buscam);
	//$objr->setEvento(" onclick='return cmdEnviar()' "); //this.id
	$objr->setEvento(" onclick='return sel_filan()' title='Seleccione para ver detalle' ");
	$objr->setNoSeleccionable(false);
	$objr->setFont("color=#000000");
	$objr->setFormatoBto("class=button");
	$objr->setFormaTabla(" class=FormTABLE style='width:100%' cellspacing='1' cellpadding='0' border='0' ");
	$objr->setFormaCabecera(" class=ColumnTD ");
	$objr->setFormaItems(" class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4' ");
	$objr->setTOrder($torder);
	 
	$from = " vCA_Turnos_CombinacionTHF ";
	$objr->setFrom($from);
	$where= " Tc_Activo = 1 and tc_codigo_sap like '%" . $tc_codigo_sap . "%'";
	$objr->setWhere($where);
	$objr->setSize(20);
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(false);
	// Arreglo de Alias de los Campos de la consulta
    $arrAlias[0] = "root";
    $arrAlias[1] = "Codigo";
    $arrAlias[2] = "ComSem";
	$arrAlias[3] = "Lun";
	$arrAlias[4] = "Mar";
	$arrAlias[5] = "Mie";
	$arrAlias[6] = "Jue";
	$arrAlias[7] = "Vie";
	$arrAlias[8] = "Sab";
	$arrAlias[9] = "Dom";
	$arrAlias[10] = "Tot_Hr";
	$arrAlias[11] = "Refri";
	$arrAlias[12] = "H_Efec";
	
	// Arreglo de los Campos de la consulta
    $arrCampos[0] = "cast(Tc_Codigo as varchar) + '¬' + Tc_Codigo_Sap + '¬' + cast(turno_dia1 as varchar) + '¬' + cast(turno_dia2 as varchar) + '¬' + cast(turno_dia3 as varchar) + '¬' + cast(turno_dia4 as varchar) + '¬' + cast(turno_dia5 as varchar) + '¬' + cast(turno_dia6 as varchar) + '¬' + cast(turno_dia7 as varchar) + '¬' + iturno_dia1 + '¬' + fturno_dia1 + '¬' + iturno_dia2 + '¬' + fturno_dia2 + '¬' + iturno_dia3 + '¬' + fturno_dia3 + '¬' + iturno_dia4 + '¬' + fturno_dia4 + '¬' + iturno_dia5 + '¬' + fturno_dia5 + '¬' + iturno_dia6 + '¬' + fturno_dia6 + '¬' + iturno_dia7 + '¬' + fturno_dia7 + '¬' + case when len(cast(Ttotal_Horas as varchar))<=1 then '0'+cast(Ttotal_Horas as varchar) else cast(Ttotal_Horas as varchar) end +':'+case when len(cast(ttotal_minutos as varchar))<=1 then '0'+cast(ttotal_minutos as varchar) else cast(ttotal_minutos as varchar) end + '¬' + case when len(cast(horas_refrigerio as varchar))<=1 then '0'+cast(horas_refrigerio as varchar) else cast(horas_refrigerio as varchar) end +':'+case when len(cast(minutos_refrigerio as varchar))<=1 then '0'+cast(minutos_refrigerio as varchar) else cast(minutos_refrigerio as varchar) end + '¬' + case when len(cast(Total_Horas as varchar))<=1 then '0'+cast(Total_Horas as varchar) else cast(Total_Horas as varchar) end +':'+case when len(cast(total_minutos as varchar))<=1 then '0'+cast(total_minutos as varchar) else cast(total_minutos as varchar) end ";
    $arrCampos[1] = "Tc_Codigo"; 
    $arrCampos[2] =	"Tc_Codigo_Sap";
	$arrCampos[3] = "turno_dia1";
	$arrCampos[4] = "turno_dia2";
	$arrCampos[5] = "turno_dia3";
	$arrCampos[6] = "turno_dia4";
	$arrCampos[7] = "turno_dia5";
	$arrCampos[8] = "turno_dia6";
	$arrCampos[9] = "turno_dia7";
	$arrCampos[10] = "case when len(cast(Ttotal_Horas as varchar))<=1 then '0'+cast(Ttotal_Horas as varchar) else cast(Ttotal_Horas as varchar) end +':'+case when len(cast(Ttotal_minutos as varchar))<=1 then '0'+cast(Ttotal_minutos as varchar) else cast(Ttotal_minutos as varchar) end";
	$arrCampos[11] = "case when len(cast(horas_refrigerio as varchar))<=1 then '0'+cast(horas_refrigerio as varchar) else cast(horas_refrigerio as varchar) end +':'+case when len(cast(minutos_refrigerio as varchar))<=1 then '0'+cast(minutos_refrigerio as varchar) else cast(minutos_refrigerio as varchar) end";
	$arrCampos[12] = "case when len(cast(Total_Horas as varchar))<=1 then '0'+cast(Total_Horas as varchar) else cast(Total_Horas as varchar) end +':'+case when len(cast(Total_minutos as varchar))<=1 then '0'+cast(Total_minutos as varchar) else cast(Total_minutos as varchar) end";
//	$arrCampos[11] = "'<font id=fnt_' + iturno_dia1 + '_' + fturno_dia1 + '_' + iturno_dia2 + '_' + fturno_dia2 + '_' + iturno_dia3 + '_' + fturno_dia3 + '_' + iturno_dia4 + '_' + fturno_dia4 + '_' + iturno_dia5 + '_' + fturno_dia5 + '_' + iturno_dia6 + '_' + fturno_dia6 + '_' + iturno_dia7 + '_' + fturno_dia7 + ' style=cursor:hand onclick=sel_fila(this.id) title=ver_detalle>Ver</font>'";
	
	$objr->setAlias($arrAlias);
	$objr->setCampos($arrCampos);
	
	$body = $objr->Construir();  //ejecutar
	//echo $objr->getmssql();
	$objr = null;
	echo $body;
	echo "<br>";
	//echo Menu("../menu.php");
?>
  </td>
  <td>
	<table  class='FormTable' width='98%' align='center' cellspacing='0' cellpadding='0' border='1'>
	<tr>
		<td class='FieldCaptionTD' align='center' width='35%' colspan="2"><b>Dias&nbsp;</td>
		<td class='FieldCaptionTD' align='center' width='70%'><b>Turnos</td>
	</tr>
	<tr align="center">
		<td class='FieldCaptionTD' align='right' colspan="2"><br>Lunes&nbsp;</td>
		<td class='DataTD' align='left' id='tturno_dia1'><?php echo $tturno_dia1?></td>
	</tr>
	<tr>
		<td class='FieldCaptionTD' align='right' colspan="2"><br>Martes&nbsp;</td>
		<td class='DataTD' align='left' id='tturno_dia2'><?php echo $tturno_dia2?></td>
	</tr>
	<tr>
		<td class='FieldCaptionTD' align='right' colspan="2"><br>Miercoles&nbsp;</td>
		<td class='DataTD' align='left' id='tturno_dia3'><?php echo $tturno_dia3?></td>
	</tr>
	<tr>
		<td class='FieldCaptionTD' align='right' colspan="2"><br>Jueves&nbsp;</td>
		<td class='DataTD' align='left' id='tturno_dia4'><?php echo $tturno_dia4?></td>
	</tr>
	<tr>
		<td class='FieldCaptionTD' align='right' colspan="2"><br>Viernes&nbsp;</td>
		<td class='DataTD' align='left' id='tturno_dia5'><?php echo $tturno_dia5?></td>
	</tr>
	<tr>
		<td class='FieldCaptionTD' align='right' colspan="2"><br>Sabado&nbsp;</td>
		<td class='DataTD' align='left' id='tturno_dia6'><?php echo $tturno_dia6?></td>
	</tr>
	<tr>
		<td class='FieldCaptionTD' align='right' colspan="2"><br>Domingo&nbsp;</td>
		<td class='DataTD' align='left' id='tturno_dia7'><?php echo $tturno_dia7?></td>
	</tr>
	</table>
  </td>
 </tr>
</table>
</form>
<div style='position:absolute;left:680px;top:75px;display:none' id='div_procesos'>
    <iframe id='ifr_procesos' name='ifr_procesos' width='283px' height='273px' src=''></iframe>
</div>
</body>
</html>