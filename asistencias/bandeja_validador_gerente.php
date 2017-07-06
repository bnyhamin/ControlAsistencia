<?php
    header("Expires: 0"); 
    require_once("../includes/Seguridad.php");
    require_once("../../Includes/Connection.php");
    require_once("../../Includes/Constantes.php"); 
    require_once("../../Includes/mantenimiento.php");
    require_once("../../Includes/MyCombo.php"); 
    require_once("../includes/clsCA_Asignacion_Empleados.php");
    require_once("../includes/clsCA_Usuarios.php");
    
    $nombre_usuario="";
    $empleado_codigo=0;
    
    $u = new ca_usuarios();
    $u->MyUrl = db_host();
    $u->MyUser= db_user();
    $u->MyPwd = db_pass();
    $u->MyDBName= db_name();
    
    $empleado_codigo=$_SESSION["empleado_codigo"];
    $u->empleado_codigo = $_SESSION["empleado_codigo"];
    $u->Identificar();
    $nombre_usuario= $u->empleado_nombre;
    $fecha=date("d/m/Y");
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
<script type="text/javascript" src="app/app.js"></script>
<script type="text/javascript" src="app/app.messages.js"></script>
<script type="text/javascript" src="app/app.ui.message.js"></script>
<script type="text/javascript" src="app/app.ui.core.js"></script>
<script type="text/javascript" src="app/app.ui.draggable.js"></script>
<style type="text/css">
        /*MESSAGES*/
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
            background-image:url(css/img/information.png);
            color:#00529B;	
            background-color:#BDE5F8;
            text-align:left;	
    }

    .error {
            background-image:url(css/img/error.png);
            color: #D8000C;
            background-color:#FFBABA;
    }

    .warning {
            background-image:url(css/img/warning.png);
      color: #9F6000;
      background-color: #FEEFB3;
            text-align:left;
    }

    .success {
            background-image:url(css/img/success.png);
      color: #4F8A10;
      background-color: #DFF2BF;
            text-align:left;
            text-align:left;
    }   
</style>
<script type="text/javascript" src="app/jquery.dataTables.js"></script>
<script src="app/app.ui.block.js" type="text/javascript" ></script>
<script type="text/javascript" src="js/bandeja.validador.mando.js"/>
<script language="javascript">
/*function comprobarvalor(obj){
    
}*/
</script>
</head>
<body class="PageBODY" onLoad="return WindowResize(10,20,'center')">
<form id="frm" name="frm" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
    <table  border="0" align="center" cellpadding="0" cellspacing="1" >
        <tr>
            <td align="left" width="500" style="font-size: 13px;color: #000000;font-weight: bold;" colspan="2"><center>Bandeja de Aprobaci&oacute;n de Eventos</center></td>
        </tr>
        <tr>
            <td align="center" colspan="2">
                <button type="button" style="cursor:hand" title="Actualizar" style="width:20px; height:21px" onclick="javascript:PooBVM.jsGetListadoEventos();">
                    <img src="../images/smyles/refresh.png" height="15">
                    &nbsp;
                </button>&nbsp;
                <button type="button" style="cursor:hand" title="Salir" style="width:20px; height:21px" onclick="javascript:PooBVM.salir();">
                    <img src="../images/biGoingOnline.gif" height="15">
                    &nbsp;
                </button>&nbsp;&nbsp;
                <a href="javascript:PooBVM.levanta_ventana('G')" style="text-decoration: none;color: #E17777; font-size: 12px; font-weight:600;" title="Consultar Eventos Registrados">
                    Ver Eventos Registrados</a>
                &nbsp;
           <img src="../images/ico/search.png" alt="Consultar Eventos Registrados" border="0" onclick="javascript:PooBVM.levanta_ventana('G')" height="16" width="16" style="cursor: hand;" />
                &nbsp;&nbsp;
            </td>
        </tr>
        <tr>
            <td align="center" width="500" style="font-size: 13px;" colspan="2"><b>Gerente&nbsp;:</b>&nbsp;&nbsp;<font color=#3366CC><b><?php echo $nombre_usuario;?></b></font></td>
        </tr>
        
        <tr>
            <td align="right" valign="top"><b>Jefaturas</b>&nbsp;:&nbsp;</td>
            <td valign="top">
            <?php
                $combo = new MyCombo();
                $combo->setMyUrl(db_host());
                $combo->setMyUser(db_user());
                $combo->setMyPwd(db_pass());
                $combo->setMyDBName(db_name());

                $ssql=" select Area_Codigo,Area_Descripcion  ";
                $ssql.=" from Areas ";
                $ssql.=" where Area_Jefe = 243 and Area_Activo=1 and Area_Padre = 0 order by Area_Descripcion asc";
                
                $combo->query = $ssql;
                $combo->name = "Cmb_Area";
                
                $combo->more = "class=select style='width:320px;' ";
                $rpta = $combo->Construir();
                echo $rpta;
            ?>
            </td>
        </tr>
        
        
</table>
<br/>


<table width='400px' align='center' cellspacing='0' cellpadding='0' border='0'>
<tr align='center'>
 <td colspan=2  >
     <input name="cmdAprobar" id="cmdAprobar" type="button" value="Aprobar"  class="Button"  style="WIDTH: 90px;" onclick="javascript:PooBVM.accion(1);"/>
     <input name="cmdRechazar" id="cmdRechazar" type="button" value="Rechazar"  class="Button"  style="WIDTH: 90px;" onclick="javascript:PooBVM.accion(2);"/>
 </td>
</tr>
</table>
<br/>

    <div id="lst_eventos"></div>
    <div id="message_busqueda"></div>
    <input type="hidden" name="empleado_codigo_s" id="empleado_codigo_s" value="<?php echo $empleado_codigo;?>"/>
    <input type="hidden" name="hddaccion" id="hddaccion" value=""/>
    <input type="hidden" name="hddCodigos" id="hddCodigos" value=""/>
    <input type="hidden" name="hddflagmasivo" id="hddflagmasivo" value=""/>
    <input type="hidden" name="hddtipo" id="hddtipo" value="VG"/>
    <input type="hidden" name="hddarea" id="hddarea" value="0"/>
    
</form>
</body>
</html>