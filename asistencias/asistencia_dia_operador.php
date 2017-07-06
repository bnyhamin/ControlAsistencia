<?php header("Expires: 0"); ?>
<?php
session_start();
//echo session_start();
if (!isset($_SESSION["empleado_codigo"])){
?>	 <script language="JavaScript">
    alert("Su sesión a caducado!!, debe volver a registrarse.");
    document.location.href = "../login.php";
  </script>
	<?php
	exit;
}

require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/librerias.php");
require_once("../../Includes/MyCombo.php"); 

$ssql="select Empleado_Carnet, Empleado, Area_Codigo, Area_Descripcion, Empleado_Responsable_Area,";
$ssql.=" convert(varchar(10), getdate(),103) as Fecha ";
$ssql.=" FROM vca_empleado_area ";
$ssql.=" WHERE Estado_Codigo =1 and Empleado_Codigo =" . $_SESSION["empleado_codigo"];
//echo $ssql;
$rs_e = consultar_sql($ssql);
$rs= mssql_fetch_row($rs_e);

$nombre_usuario  = $rs[1];
$area      = $rs[2];
$area_descripcion = $rs[3];
$jefe = $rs[4]; // responsable area
$fecha     = $rs[5];
$mensaje="";
$color='#E7E7D6';


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Control de Asistencia - Designar Grupo</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../../jscript.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
</head>
<body class="PageBODY">
<CENTER class="FormHeaderFont">Consultar Grupo</CENTER>
<table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="center" colspan='2'><b><?php echo $area_descripcion ?></b></td>
  </tr>
  </table>
  <br>
<table width="60%" border="0" cellspacing="0" cellpadding="1" class="FormTABLE" align="center">
  <tr>
    <td align="right">Supervisor :&nbsp;</td>
    <td align="left">&nbsp;<?php echo $nombre_usuario ?></td>
  </tr>
</table>
<br>
<table class='FormTable' align="center" border="0" cellPadding="0" cellSpacing="1" style="width:98%">
 <tr align="center" >
    <td class="ColumnTD" >Nro.</td>
    <td class="ColumnTD" >Código</td>
    <td class="ColumnTD">Nombre</td>
	<td class="ColumnTD">Cargo</td>
</tr>
<?php
$ssql="SELECT Empleados.Empleado_Codigo,  ";
$ssql.= "      Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres as empleado,  ";
$ssql.= "      Cargo_descripcion,";
$ssql.= "      case when ca_asignacion_empleados.responsable_codigo is null then 0 else ca_asignacion_empleados.responsable_codigo end as responsable ";
$ssql.= " FROM Empleado_Area INNER JOIN Empleados ON  ";
$ssql.= "      Empleado_Area.Empleado_Codigo = Empleados.Empleado_Codigo ";
$ssql.= " INNER JOIN vw_empleado_area_cargo on vw_empleado_area_cargo.Empleado_Codigo=Empleados.Empleado_Codigo ";
$ssql .=" LEFT OUTER JOIN ca_asignacion_empleados ON ca_asignacion_empleados.empleado_codigo=Empleados.empleado_codigo ";
$ssql.= " WHERE (Empleados.Estado_Codigo = 1) AND (Empleado_Area.Empleado_Area_Activo = 1) and ";
$ssql.= "		Empleado_Area.Area_Codigo = " . $area . " and ca_asignacion_empleados.asignacion_activo=1 and ";
$ssql.= "		ca_asignacion_empleados.responsable_codigo=" . $_SESSION["empleado_codigo"] . "";
$ssql.= " order by 2 ";
$rs_e = consultar_sql($ssql);
if (mssql_num_rows($rs_e)>0){ //-- mostrar resultados
	$i=0;
	while ($rs= mssql_fetch_row($rs_e)){
		$i+=1;
		?>
		<tr class='DataTD'>
		<?php
		echo "	<td align=center>" . $i . "&nbsp;</td>";
		echo "	<td align=center>" . $rs[0] . "&nbsp;</td>";
		echo "	<td >&nbsp;" . $rs[1];
		echo "	</td>";
		echo "	<td >&nbsp;" . $rs[2];
		echo "	</td>";
		echo "</tr>";
	}
}
?>
</table>
<br>
<br>
<table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="center">
		<input type="button" id="cmd4" onClick="window.close()" value="Cerrar" class="Button" style="width:80px">
	</td>
  </tr>
</table>
</body>
</html>