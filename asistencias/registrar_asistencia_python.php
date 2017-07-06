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
$id = $_SESSION["empleado_codigo"];
$rol = $_SESSION["rc"];

require_once("../../Includes/Connection.php");
require_once("../includes/clsCA_Usuarios.php");


$r = $usr->IdentificarN();
if ($usr->turno_codigo==null){
	$r = $usr->Identificar();
}
$empleado  	= $usr->empleado_nombre;
$turno_codigo_asignado = $usr->turno_codigo;
