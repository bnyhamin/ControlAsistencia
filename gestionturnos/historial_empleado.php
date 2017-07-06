<?php header("Expires: 0"); 
//session_start();
//if (!isset($_SESSION["empleado_codigo"])){
/*?><script language="JavaScript">
    //alert("Su sesión a caducado!!, debe volver a registrarse.");
    //document.location.href = "../login.php";
    
    //function Finalizar(){
    //window.close();
    //}

  </script>
<?php
//exit;
}*/
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
//require_once("../../includes/Seguridad.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Turnos_Empleado.php"); 
require_once("../../Includes/MyGrilla.php");
require_once("../../Includes/MyCombo.php");

$e = new ca_turnos_empleado();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();

$te_semana= date('W');
$te_anio=date('Y');
$rango = "";
$rpta = "";
$body="";
$pagina= 1;
$orden = "a.fecha_modificacion";
$buscam = "";
$torder = "DESC";

if (isset($_GET['pagina'])){
    $pagina = $_GET['pagina'];
    $orden = $_GET['orden'];
    $buscam = $_GET['buscam'];
}elseif(isset($_POST['pagina'])){
    $pagina = $_POST['pagina'];
    $orden = $_POST['orden'];
    $buscam = $_POST['buscam'];
}


if (isset($_GET["empleado_id"])) $empleado_id = $_GET["empleado_id"];
elseif (isset($_POST["empleado_id"])) $empleado_id = $_POST["empleado_id"];

if (isset($_GET["te_semana"])) $te_semana= $_GET["te_semana"];
elseif (isset($_POST["te_semana"])) $te_semana = $_POST["te_semana"];

if (isset($_GET["te_anio"])) $te_anio= $_GET["te_anio"];
elseif (isset($_POST["te_anio"])) $te_anio = $_POST["te_anio"];


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Historial Turnos </title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css">
<SCRIPT LANGUAGE=javascript>



</SCRIPT>
</head>


<body class="PageBODY" >
<br />
<CENTER class="FormHeaderFont">Historial de Suplencias <br></CENTER>
<form id=frm2 name=frm2 method=post action="<?php echo $_SERVER["PHP_SELF"];?>">
<?php
	//echo $objr->getmssql();
    //$ssql= $objr->getmssql();
  /*
    $ssql =" Select tc.tc_codigo_sap as root,tc.tc_codigo_sap as Codigo_SAP,convert(varchar(10),a.te_fecha_inicio ";
	$ssql.=" ,103) as Inicio,convert(varchar(10),a.te_fecha_fin,103) as Fin,isnull(t1.Turno_Descripcion, ";
    $ssql.=" 'Descanso') as Lunes,isnull(t2.Turno_Descripcion,'Descanso') as Martes,isnull(t3.Turno_Descripcion, ";
	$ssql.=" 'Descanso') as Miercoles,isnull(t4.Turno_Descripcion,'Descanso') as Jueves,isnull(t5. ";
    $ssql.=" Turno_Descripcion,'Descanso') as Viernes,isnull(t6.Turno_Descripcion,'Descanso') as Sabado, ";
    $ssql.=" isnull(t7.Turno_Descripcion,'Descanso') as Domingo,convert(varchar(10),a.fecha_modificacion,103) ";
    $ssql.=" +' '+convert(varchar(10),a.fecha_modificacion,108) as Fec_Mod,ee.Empleado_Nombres + ' ' + "; 
    $ssql.=" ee.Empleado_Apellido_Paterno + ' ' + ee.Empleado_Apellido_Materno as Usuario_Mod from empleados ";
    $ssql.=" e inner join ca_turno_empleado a on e.empleado_codigo = a.empleado_codigo left join ";
    $ssql.=" empleados ee on ee.empleado_codigo = a.empleado_codigo_modificacion inner join ";
    $ssql.=" ca_turnos_combinacion tc on tc.tc_codigo = a.tc_codigo left join CA_Turnos t1 on ";
    $ssql.=" t1.Turno_Codigo = a.turno_dia1 left join CA_Turnos t2 on t2.Turno_Codigo = a.turno_dia2 left join ";
	$ssql.=" CA_Turnos t3 on t3.Turno_Codigo = a.turno_dia3 left join CA_Turnos t4 on t4.Turno_Codigo = ";
    $ssql.=" a.turno_dia4 left join CA_Turnos t5 on t5.Turno_Codigo = a.turno_dia5 left join CA_Turnos t6 on "; 
    $ssql.=" t6.Turno_Codigo = a.turno_dia6 left join CA_Turnos t7 on t7.Turno_Codigo = a.turno_dia7 ";
    $ssql.=" where a.te_anio=".$te_anio." and a.te_semana=".$te_semana." and a.empleado_codigo=".$empleado_id; 
    $ssql.=" union ";
    $ssql.=" Select tc.tc_codigo_sap as root,tc.tc_codigo_sap as Codigo_SAP,convert(varchar(10),a.te_fecha_inicio ";
	$ssql.=" ,103) as Inicio,convert(varchar(10),a.te_fecha_fin,103) as Fin,isnull(t1.Turno_Descripcion, ";
    $ssql.=" 'Descanso') as Lunes,isnull(t2.Turno_Descripcion,'Descanso') as Martes,isnull(t3.Turno_Descripcion, ";
	$ssql.=" 'Descanso') as Miercoles,isnull(t4.Turno_Descripcion,'Descanso') as Jueves,isnull(t5. ";
    $ssql.=" Turno_Descripcion,'Descanso') as Viernes,isnull(t6.Turno_Descripcion,'Descanso') as Sabado, ";
    $ssql.=" isnull(t7.Turno_Descripcion,'Descanso') as Domingo,convert(varchar(10),a.fecha_modificacion,103) ";
    $ssql.=" +' '+convert(varchar(10),a.fecha_modificacion,108) as Fec_Mod,ee.Empleado_Nombres + ' ' + "; 
    $ssql.=" ee.Empleado_Apellido_Paterno + ' ' + ee.Empleado_Apellido_Materno as Usuario_Mod from empleados ";
    $ssql.=" e inner join ca_turno_empleado_auditor a on e.empleado_codigo = a.empleado_codigo left join ";
    $ssql.=" empleados ee on ee.empleado_codigo = a.empleado_codigo_modificacion inner join ";
    $ssql.=" ca_turnos_combinacion tc on tc.tc_codigo = a.tc_codigo left join CA_Turnos t1 on ";
    $ssql.=" t1.Turno_Codigo = a.turno_dia1 left join CA_Turnos t2 on t2.Turno_Codigo = a.turno_dia2 left join ";
	$ssql.=" CA_Turnos t3 on t3.Turno_Codigo = a.turno_dia3 left join CA_Turnos t4 on t4.Turno_Codigo = ";
    $ssql.=" a.turno_dia4 left join CA_Turnos t5 on t5.Turno_Codigo = a.turno_dia5 left join CA_Turnos t6 on "; 
    $ssql.=" t6.Turno_Codigo = a.turno_dia6 left join CA_Turnos t7 on t7.Turno_Codigo = a.turno_dia7 ";
    $ssql.=" where a.te_anio=".$te_anio." and a.te_semana=".$te_semana." and a.empleado_codigo=".$empleado_id; 
    $ssql.=" Order by Fec_Mod DESC ";
 	//echo $ssql; 
	$objr = null;
	//echo $body;
	echo "<br>";
	//echo Menu("../menu.php");
    
    $msconnect = mssql_connect(db_host(),db_user(),db_pass()) or die("No puedo conectarme a servidor");
    $cnn = mssql_select_db(db_name(),$msconnect) or die("No puedo seleccionar BD");
    //$ssql = $objr->getmssql();
    $result = mssql_query($ssql);
    $rs = mssql_fetch_row($result);
    */
    $filas = $e->historial_empleado($te_anio,$te_semana,$empleado_id)
    ?>
    
    <table class=FormTABLE width="100%" cellspacing='1' cellpadding='1' border='0'>
    <tr  class=ColumnTD>
        <td>Codigo_Com</td>
    	<td>Inicio</td>
    	<td>Fin</td>
    	<td>Lunes</td>
        <td>Martes</td>
        <td>Miercoles</td>
        <td>Jueves</td>
        <td>Viernes</td>
        <td>Sabado</td>
        <td>Domingo</td>
        <td>Fec_Mod</td>
        <td>Usuario_Mod</td>
    </tr>
    <?php
    foreach($filas as $fila){    

        $rs_Codigo_SAP= $fila[1];
    	$rs_Inicio= $fila[2];
    	$rs_Fin= $fila[3];
    	$rs_Lunes= $fila[4];
        $rs_Martes= $fila[5];
        $rs_Miercoles= $fila[6];
        $rs_Jueves= $fila[7];
        $rs_Viernes= $fila[8];
        $rs_Sabado= $fila[9];
        $rs_Domingo= $fila[10];
        $rs_Fec_Mod= $fila[11];
        $rs_Usuario_Mod= $fila[12];
        
        echo "<td nowrap>".$rs_Codigo_SAP."</td>";
    	echo "<td nowrap>".$rs_Inicio."</td>";
    	echo "<td nowrap>".$rs_Fin."</td>";
    	echo "<td nowrap>".$rs_Lunes."</td>";
        echo "<td nowrap>".$rs_Martes."</td>";
        echo "<td nowrap>".$rs_Miercoles."</td>";
        echo "<td nowrap>".$rs_Jueves."</td>";
        echo "<td nowrap>".$rs_Viernes."</td>";
        echo "<td nowrap>".$rs_Sabado."</td>";
        echo "<td nowrap>".$rs_Domingo."</td>";
        echo "<td nowrap>".$rs_Fec_Mod."</td>";
        echo "<td nowrap>".$rs_Usuario_Mod."</td>";
        echo "</tr>";
    }
    
?>
    </table>
    <input type='hidden' name='te_anio' id='te_anio' value='<?php echo $te_anio ?>'>
    <input type='hidden' name='te_semana' id='te_semana' value='<?php echo $te_semana ?>'>
    <input type='hidden' name='empleado_id' id='empleado_id' value='<?php echo $empleado_id ?>'>
    
    <!--
<input type='hidden' name='pagina' id='pagina' value='<?php echo $pagina ?>'>
    <input type='hidden' name='orden' id='orden' value='<?php echo $orden ?>'>
    <input type='hidden' name='buscam' id='buscam' value='<?php echo $buscam ?>'>
-->

</form>

</body>
</html>