<?php header("Expires: 0"); ?>
<?php
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Validacion.php");
require_once("../includes/clsCA_Usuarios.php");
require_once("../includes/clsCA_Empleado_Rol.php");

$emp=0;
$empleado=0;
$o = new ca_validacion();
$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());

$id=$_SESSION["empleado_codigo"];
$o->responsable_codigo=$id;

$u = new ca_usuarios();
$u->setMyUrl(db_host());
$u->setMyUser(db_user());
$u->setMyPwd(db_pass());
$u->setMyDBName(db_name());

$u->empleado_codigo = $id;
$r = $u->Identificar();
$nombre_usuario = $u->empleado_nombre;
$area = $u->area_codigo;
$area_descripcion = $u->area_nombre;
$jefe = $u->empleado_jefe;
$fecha = $u->fecha_actual;

if (isset($_GET["empleado"])) $empleado=$_GET["empleado"];

if (isset($_GET["f"])){
    $fecha = $_GET["f"];
    if (isset($_GET["e"])){
        $emp = $_GET["e"];
        $empleado = $emp;
    }
?>
    <script language="javascript">
        var fecha="<?php echo $fecha ?>";
        window.parent.frames[2].location="val_right_desbloquear.php?fecha=" + fecha + "&empleado_cod=<?php echo $emp ?>"; 
    </script>
<?php
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Listado de Personal</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../../default.js"></script>
<link href="../js/tab/example.css" type="text/css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="../js/tab/tabber.js"></script>
<!--<script language="JavaScript" src="../tecla_f5.js"></script>
<script language="JavaScript" src="../mouse_keyright.js"></script>-->
<script type="text/javascript">
function actualizar(){
    document.frm.action +="?f=<?php echo $fecha ?>";
    document.frm.submit();
}

function asistencias(valor){
    arr=valor.split('_');
    var fecha="<?php echo $fecha ?>";
    window.parent.frames[2].location="val_right_desbloquear.php?empleado_cod=" + arr[0] + "&fecha=" + fecha + "&tipo=" + arr[2]; 
}

</script>
</head>
<body class="PageBODY">
<form id='frm' name='frm' action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
    <!--<center><font color=#333399 STYLE='font-size:12.5px'><b>GUEROVICH MARCHENA&nbsp;</b></font></center>-->
    
    <div class="tabber">
        <div class="tabbertab">
        <h2>2-Atenc. Inic.</h2>
            contenido2
        </div>

        <div class="tabbertab">
        <h2>3-HOSP.</h2>
            contenido3
        </div>
    </div>
    
    
    <table class='FormTable' width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
    <tr>
        <td class='ColumnTD' align=center width="5%">Sel</td>
        <td class="ColumnTD" align=center width="10%">Código</td>
        <td class='ColumnTD' align=center width="48%">Nombre</td>
        <td class='ColumnTD' align=center width="7%">Tx.</td>
        <td class='ColumnTD' align=center width="30%">Incidencias</td>
    </tr>
      <?php
      $o->fecha=$fecha; 
      $rpta=$o->Listar_Empleado_Area($empleado);
      echo $rpta;
      ?>
    </table>
    <br/>
    <input type="button" style="width:0; heigth:0" value="ok" id="cmdx" name="cmdx" onClick="actualizar()">		
</form>
</body>
</html>