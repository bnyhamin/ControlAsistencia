<?php
    header("Expires: 0"); 
    require_once("../includes/Seguridad.php");
    require_once("../../Includes/Connection.php");
    require_once("../../Includes/Constantes.php"); 
    require_once("../../Includes/mantenimiento.php");
    require_once("../includes/clsCA_Usuarios.php");

    $u = new ca_usuarios();
    $u->MyUrl = db_host();
    $u->MyUser= db_user();
    $u->MyPwd = db_pass();
    $u->MyDBName= db_name();

    $u->empleado_codigo = $_SESSION["empleado_codigo"];
    
    $empleado_codigo=$_SESSION["empleado_codigo"];
    $r = $u->Identificar();
    $nombre_usuario = $u->empleado_nombre;
    
?>
 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?></title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css"/>
<style type="text/css" title="currentStyle">
    @import "css/list_paginado.css";
</style>
<script type="text/javascript" src="../asistencias/app/app.js"></script>
<script type="text/javascript" src="../asistencias/app/app.messages.js"></script>
<script type="text/javascript" src="../asistencias/app/app.ui.message.js"></script>
<script type="text/javascript" src="../asistencias/app/app.ui.core.js"></script>
<script type="text/javascript" src="../asistencias/app/app.ui.draggable.js"></script>
<style type="text/css">
    .information,.error,.warning,.success{
            background-position:10px center;
            background-repeat:no-repeat;
            border:1px solid;
            margin:10px 0;
            padding:15px 10px 15px 50px;
            font-family:"tahoma";
            font-size:11px;
            height:25px;
    }
    .information {
            background-image:url(../asistencias/css/img/information.png);
            color:#00529B;	
            background-color:#BDE5F8;
            text-align:left;	
    }

    .error {
            background-image:url(../asistencias/css/img/error.png);
            color: #D8000C;
            background-color:#FFBABA;
    }

    .warning {
            background-image:url(../asistencias/css/img/warning.png);
      color: #9F6000;
      background-color: #FEEFB3;
            text-align:left;
    }

    .success {
            background-image:url(../asistencias/css/img/success.png);
      color: #4F8A10;
      background-color: #DFF2BF;
            text-align:left;
            text-align:left;
    }   
</style>
<script type="text/javascript" src="../asistencias/app/jquery.dataTables.js"></script><!--app.ui.datatable.js  jquery.dataTables-->
<script src="../asistencias/app/app.ui.block.js" type="text/javascript" ></script>
<script type="text/javascript" src="../js/main_areas_dias.js"/>

<script language="javascript">
function comprobarvalor(obj){
    
}
</script>
</head>
<body class="PageBODY" onLoad="return WindowResize(10,20,'center')">
<form id="frm" name="frm" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
    <table  border="0" align="center" cellpadding="0" cellspacing="1" >
        <tr>
            <td align="left" width="500" style="font-size: 13px;color: #000000;font-weight: bold;"><center>Habilitar dias de validacion</center></td>
        </tr>
        <tr>
           <td align="center" width="500" style="font-size: 13px;">&nbsp;&nbsp;<font color=#3366CC><b><?php echo $nombre_usuario;?></b></font></td>
        </tr>
        <tr>
            <td align="center">
                <button type="button" style="cursor:hand" title="Actualizar" style="width:20px; height:21px" onclick="javascript:PooMA.jsGetListadoAreas('L');">
                    <img src="../images/smyles/refresh.png" height="15">
                    &nbsp;
                </button>&nbsp;
                <button type="button" style="cursor:hand" title="Salir" style="width:20px; height:21px" onclick="javascript:PooMA.salir();">
                    <img src="../images/biGoingOnline.gif" height="15">
                    &nbsp;
                </button>&nbsp;
            </td>
        </tr>
</table>
<br/>
    <p>
        <a href="javascript:PooMA.levanta_ventana()" style="text-decoration: none;color: #000000; font-weight:600;" title="Actualizar Todos los dias">Habilitar Todos</a>
    </p>

    <!--dias validacion-->
    <div id="lst_eventos"></div>
    <div id="message_busqueda"></div>
    <input type="hidden" name="empleado_codigo_s" id="empleado_codigo_s" value="<?php echo $empleado_codigo;?>"/>
    
</form>
</body>
</html>