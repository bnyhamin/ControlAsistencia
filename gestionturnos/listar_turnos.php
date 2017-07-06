<?php header("Expires: 0");
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/MyGrilla.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Turnos_Empleado.php"); 

$tturno_dia1="";
$tturno_dia2="";
$tturno_dia3="";
$tturno_dia4="";
$tturno_dia5="";
$tturno_dia6="";
$tturno_dia7="";
  
$body="";
$npag = 1;
$orden = "Cod";
$buscam = "";
$torder="ASC";
$tc_codigo_sap="";
//$empleado_id=3300;
$empleado_id = $_SESSION["empleado_codigo"];
$tc_codigo="";

if (isset($_GET["pagina"])) $npag = $_GET["pagina"];
if (isset($_GET["orden"])) $orden = $_GET["orden"];
if (isset($_GET["buscam"])) $buscam = $_GET["buscam"];
if (isset($_GET["tc_codigo_sap"])) $tc_codigo_sap = $_GET["tc_codigo_sap"];
if (isset($_GET["empleado_id"])) $empleado_id = $_GET["empleado_id"];

if (isset($_POST["pagina"])) $npag = $_POST["pagina"];
if (isset($_POST["orden"])) $orden = $_POST["orden"];
if (isset($_POST["buscam"])) $buscam = $_POST["buscam"];
if (isset($_POST["cboTOrden"])) $torder = $_POST["cboTOrden"];

$o = new ca_turnos_empleado();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

$item_horario = isset($_GET["horario"])?$_GET["horario"]:$_POST["item_horario"];

$tiempo_horario = $o->Obtener_Tiempo_Horario($item_horario);
$Empleados_Seleccionados = isset($_GET["empleados"])?$_GET["empleados"]:$_POST["empleados"] ;
//echo $tiempo_horario;
$tc_codigo = isset($_POST["tc_codigo"])?$_POST["tc_codigo"]:0;
if(isset($_POST["hddAccion"])) {
    if($_POST["hddAccion"] == "EJE" && $tc_codigo  != 0) {
        $empleados = explode(",",$Empleados_Seleccionados);
        if(count($empleados > 0)){
            for($i=0; $i < count($empleados); $i++){
                $datos = explode("-",$empleados[$i]);
                $empleado_codigo = $datos[0];
                $emp_mov_codigo = $datos[1];
                $rpta = $o->Actualizar_Movimiento_Cambio($tc_codigo,$empleado_id,$emp_mov_codigo);
            }
            ?>
            <script language="javascript">
    	    	window.opener.document.frm.submit();
    	    	alert('Se asigno CS a los empleados seleccionados');
    	    	window.close();
            </script>
            <?php    
        }else{
            ?>
            <script>
    	    	window.opener.document.frm.submit();
    	    	alert('Se asigno CS a los empleados seleccionados');
    	    	window.close();
            </script>
            <?php
        }
        
    	
    }
}
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Catálogo de Turnos Combinación</title>
	<meta http-equiv="pragma" content="no-cache"/>
	<title><?php echo SistemaNombre() ?></title>
	<link rel="stylesheet" type="text/css" href="../../default.css"/>
	<script language="JavaScript" src="../../default.js"></script>
    <script >

function cmdEnviar(){
    var rpta=Registro();
    //alert(rpta)
    if (rpta != '' ){
        //var arr= rpta.split("¬");
		frm.hddAccion.value="EJE";
		//frm.empleado_id.value = document.getElementById('empleado_id').value;
		frm.tc_codigo.value = rpta;
		frm.submit();
		//document.frm.action += "&empleado_id="+document.getElementById('empleado_id').value+"&tc_codigo="+arr[0];
		//document.frm.submit();
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

</script>
</head>


<body class="PageBODY" >
<!--onLoad="return WindowResize(10,20,'center')"-->
<center class="TITOpciones">Catálogo de Combinación de Turnos </center>
<form id="frm" name="frm" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
<table class='sinborde' cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td class='ColumnTD'>
			<input type="button" class="Button" id="cmde" name="cmde" value="Asignar" style="width:80px"  onclick="cmdEnviar()"/>
			<input type="button" class="Button" id="cmdg" name="cmdg" value="Cancelar" style="width:80px"  onclick="cerrar()"/>
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
	$objr->setFont(CabeceraGrilla());
	$objr->setFormatoBto("class=Boton");
	$objr->setFormaTabla(FormaTabla());
	$objr->setFormaCabecera(FormaCabecera());
	$objr->setFormaItems(FormaItems());
	$objr->setTOrder($torder);
	 
	$from = " vCA_Turnos_CombinacionTH ";
	$objr->setFrom($from);
	//echo $tc_codigo_sap;
	$where= " Tc_Activo = 1 and ((total_horas * 60) + (total_minutos )) = ".$tiempo_horario;
	$objr->setWhere($where);
	$objr->setSize(18);
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(false);
	// Arreglo de Alias de los Campos de la consulta
    $arrAlias[0] = "root";
    $arrAlias[1] = "Cod";
    $arrAlias[2] = "CTur";
	$arrAlias[3] = "Lun";
	$arrAlias[4] = "Mar";
	$arrAlias[5] = "Mie";
	$arrAlias[6] = "Jue";
	$arrAlias[7] = "Vie";
	$arrAlias[8] = "Sab";
	$arrAlias[9] = "Dom";
	$arrAlias[10] = "Tot_Hr";
	//$arrAlias[11] = "Ver";
	
	// Arreglo de los Campos de la consulta
    $arrCampos[0] = "cast(Tc_Codigo as varchar)";
    $arrCampos[1] = "Tc_Codigo"; 
    $arrCampos[2] =	"Tc_Codigo_Sap";
	$arrCampos[3] = "tturno_dia1";
	$arrCampos[4] = "tturno_dia2";
	$arrCampos[5] = "tturno_dia3";
	$arrCampos[6] = "tturno_dia4";
	$arrCampos[7] = "tturno_dia5";
	$arrCampos[8] = "tturno_dia6";
	$arrCampos[9] = "tturno_dia7";
	$arrCampos[10] = "case when len(cast(Total_Horas as varchar))<=1 then '0'+cast(Total_Horas as varchar) else cast(Total_Horas as varchar) end +':'+case when len(cast(Total_minutos as varchar))<=1 then '0'+cast(Total_minutos as varchar) else cast(Total_minutos as varchar) end";
	//$arrCampos[11] = "'<font id=fnt_' + iturno_dia1 + '_' + fturno_dia1 + '_' + iturno_dia2 + '_' + fturno_dia2 + '_' + iturno_dia3 + '_' + fturno_dia3 + '_' + iturno_dia4 + '_' + fturno_dia4 + '_' + iturno_dia5 + '_' + fturno_dia5 + '_' + iturno_dia6 + '_' + fturno_dia6 + '_' + iturno_dia7 + '_' + fturno_dia7 + ' style=cursor:hand onclick=sel_fila(this.id) title=ver_detalle>Ver</font>'";
	
	$objr->setAlias($arrAlias);
	$objr->setCampos($arrCampos);
	
	$body = $objr->Construir();  //ejecutar
	//echo $objr->getmssql();
	$objr = null;
	echo $body;
	//echo Menu("../menu.php");
?>
    <br />
<input type="hidden" name="hddAccion" id="hddAccion" value=""/>
<input type="hidden" name="empleados" id="empleados" value="<?php echo $Empleados_Seleccionados ?>" />
<input type="hidden" name="empleado_id" id="empleado_id" value="<?php echo $empleado_id ?>" />
<input type="hidden" name="tc_codigo" id="tc_codigo" value="<?php echo $tc_codigo ?>" />
<input type="hidden" name="item_horario" id="item_horario" value="<?php echo $item_horario ?>" />
</form>

<div style='position:absolute;left:680px;top:75px;display:none' id='div_procesos'>
    <iframe id='ifr_procesos' name='ifr_procesos' width='283px' height='273px' src=''></iframe>
</div>
</body>
</html>