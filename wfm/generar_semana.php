<?php
header("Expires: 0"); 
session_start();
if (!isset($_SESSION["empleado_codigo"])){
?>
    <script language="JavaScript">
        alert("Su sesión a caducado!!, debe volver a registrarse.");
        document.location.href = "../login.php";
    </script>
<?php
exit;
}
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Turnos_Empleado.php"); 
require_once("../../Includes/clsEmpleados.php");
require_once("../../Includes/clswfm_empleado_disponibilidad.php");

set_time_limit(30000); 
$usuario=$_SESSION["empleado_codigo"];

$e = new ca_turnos_empleado();
$e->setMyUrl(db_host());
$e->setMyUser(db_user());
$e->setMyPwd(db_pass());
$e->setMyDBName(db_name());

$ed = new wfm_empleado_disponibilidad();
$ed->setMyUrl(db_host());
$ed->setMyUser(db_user());
$ed->setMyPwd(db_pass());
$ed->setMyDBName(db_name());

//$mensaje1='';

if (isset($_GET["inicio"])){ $inicio=$_GET["inicio"]; }
if (isset($_GET["fin"])){ $fin=$_GET["fin"]; }
		
		$e->te_fecha_inicio=$inicio;
		$e->te_fecha_fin=$fin;
                //echo $inicio."-".$fin;
		$e->empleado_codigo_registro=$usuario;
                
		$e->eliminar_carga_semana();
		$mensaje1 =$e->Generar_Semana();
		if($mensaje1=="OK"){
			$ed->Generar_Disponibilidad($inicio , $fin , $usuario );
			
?>
			<script language="javascript">
			alert('Proceso Satisfactorio');
			//window.close();
		
			
			</script>
			<?php
		}else{
			?>
			<script language="javascript">
			alert('¡ERROR! Hubo problemas al realizar esta operacion, verifique los datos y reintente');
			//window.close();
			</script>
			<?php
		}
		
?>

<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
	<link rel="stylesheet" type="text/css" href="../../default.css">
	<script language="JavaScript" src="../../default.js"></script>
    <title>Servicios</title>
    <script language=javascript>
 window.returnValue = "";
 
 var msje= '<?php echo $mensaje1 ?>';
  
 if (msje=='OK' ){
 	self.returnValue ='OK';
	//self.close();
 }
  
  function cancelar(){
	self.returnValue = 0;
	self.close();
  }
 
</script>
    
</head>


<body>
<form name=frm id=frm action="<?php echo $PHP_SELF; ?>" method="post">
<center><b>Validar Generacion Disponibilidad </b></center>
<br>

<table border="0" width="100%" align="center" border="0" cellspacing="0" cellpadding="1">
	<tr>
		<td class="ColumnTd" align="center">Detalle de Disponibilidad</td>
	</tr>
	<tr>
		<td class="DataTd" align="center"><?php echo $mensaje1 ?></td>
	</tr>


</table>
<br>
<center><input type="button" class="boton" value='Cerrar' onclickput type="button" class="button" value='Cerrar' onclick='cancelar()'></center>
</form>
</body>
</html>



