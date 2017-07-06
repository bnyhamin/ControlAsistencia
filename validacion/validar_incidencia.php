<?php
header("Expires: 0");
session_start();
set_time_limit(30000);
//require_once("../includes/Seguridad.php");//colocar seguridad a la pagina(*)
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php");
require_once("../../Includes/mantenimiento.php");
require_once("../librerias/MyCombo.php");
require_once("../includes/clsCA_Usuarios.php");

$u = new ca_usuarios();
$u->setMyUrl(db_host());
$u->setMyUser(db_user());
$u->setMyPwd(db_pass());
$u->setMyDBName(db_name());

$id=$_SESSION["empleado_codigo"];
$u->empleado_codigo = $id;
$r = $u->Identificar();
$nombre_usuario = $u->empleado_nombre;
$area = $u->area_codigo;
$area_descripcion = $u->area_nombre;
$jefe = $u->empleado_jefe; // responsable area
$fecha = $u->fecha_actual;
$ndias=$u->Actualizacion_dias();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" >
    <HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
    <link rel="stylesheet" type="text/css" href="../../extjs/resources/css/ext-all.css"/>
    <script type="text/javascript" src="../../extjs/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="../../extjs/ext-all.js"></script>
    <script type="text/javascript" src="../../extjs/src/locale/ext-lang-es.js"></script>
    <link rel="stylesheet" type="text/css" href="../style/style_incidencia.css"/>
    <script language="JavaScript" src="../../default.js"></script>
    <!--incluir estas librerias-->
    <!--<script language="JavaScript" src="../tecla_f5.js"></script>
    <script language="JavaScript" src="../mouse_keyright.js"></script>-->
    <!--incluir estas librerias-->
    <!-- extensions -->
    <script type="text/javascript" src="../../extjs/examples/ux/Portal.js"></script>
    <script type="text/javascript" src="../../extjs/examples/ux/PortalColumn.js"></script>
    <script type="text/javascript" src="../../extjs/examples/ux/Portlet.js"></script>
    
    <script type="text/javascript" src="../jscript/grilla.js"></script>
    <script type="text/javascript" src="../jscript/valida_fecha.js"></script>
    <script type="text/javascript" src="../jscript/menu.js"></script>
    <script type="text/javascript" src="../jscript/validar_incidencia.js"></script>
</head>
<body>
    <form id="frm" name="frm">
        <!--zona de filtros-->
        <div id="div_filtros"></div>
        <input type="hidden" name="empleado_codigo" id="empleado_codigo" value="<?php echo $id;?>"/><!--responsable | id-->
        <input type="hidden" name="empleado_nombre" id="empleado_nombre" value="<?php echo $nombre_usuario;?>"/>
        <input type="hidden" name="hdd_fecha" id="hdd_fecha" value="<?php echo $fecha;?>"/>
        <input type="hidden" name="hdd_area" id="hdd_area" value="<?php echo $area;?>"/>
        <input type="hidden" name="hdd_dias" id="hdd_dias" value="<?php echo $ndias;?>"/>
        <input type="hidden" name="hdd_registro" id="hdd_registro" value=""/>
        <input type="hidden" name="hdd_tiempo_efectivo" id="hdd_tiempo_efectivo" value=""/>
        <input type="hidden" name="hdd_extension_tiempo" id="hdd_extension_tiempo" value=""/>
        <!--<input type="button" style="width:0px; height:0px; display: none;" value="ok" id="cmdx" name="cmdx" onclick="javascript:com.atento.gap.ValidarIncidencia.cargarIncidencias()"/>-->
        <input type="button" style="width:0px; height:0px; display: none;" value="ok" id="cmdx" name="cmdx" onclick="javascript:com.atento.gap.ValidarIncidencia.cargarStorePivot()"/>
    </form>
</body>
</html>


