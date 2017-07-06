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

$mensaje='';

if (isset($_GET["inicio"])){ $inicio=$_GET["inicio"]; }
if (isset($_GET["fin"])){ $fin=$_GET["fin"]; }

$ed->retorna_semana_anio($inicio);
$e->te_semana = $ed->semana;
$e->te_anio = $ed->anio;

$e->te_fecha_inicio=$inicio;
$e->te_fecha_fin=$fin;
$e->empleado_codigo_registro=$usuario;

$ed->usuario_registra=$usuario;

$mensaje1=$ed->verifica_carga_programacion();
if ($mensaje1 != "OK"){ 
?>
    <script language="javascript">
        alert('¡ERROR! No existe empleados programados en esa semana');
        window.close();
    </script>
<?php			
}		
    if($mensaje1=="OK" ){
        //$mensaje =$e->Generar_Programacion_Semanal();
        $mensaje =$e->Genera_Combinacion_Automatica_Semanal();
        if ($mensaje=="OK"){
?>
        <script language="javascript">
            alert('Proceso Satisfactorio');
            //window.close();
        </script>
<?php
        }
    }		
?>
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Generar Combinación</title>
<script language="JavaScript" src="../../default.js"></script>
<script language=javascript>
window.returnValue = "";
var msje= '<?php echo $mensaje ?>';

if (msje=='OK' ){
    self.returnValue ='OK';
    self.close();
}

function cancelar(){
    self.returnValue = 0;
    self.close();
}
</script>
</head>
<body>
<form name=frm id=frm action="<?php echo $_SERVER["PHP_SELF"]  ?>" method="post">
<center><strong>Generar Combinación</strong></center>
<table class="FormTable" width='100%' ALIGN="center" BORDER="0" CELLSPACING="0" CELLPADDING="0">
	<tr>
            <td>
            <?php echo $mensaje ?>
            </td>
	</tr>
</table>
<br>
<center><input type="button" class="boton" value='Cerrar' onclickput type="button" class="button" value='Cerrar' onclick='cancelar()'/></center>
</form>
</body>
</html>

