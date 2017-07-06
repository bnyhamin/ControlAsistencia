<?php
    header("Expires: 0");
    //header('Content-Type: text/html; charset=ISO-8859-1');
    session_start();
    require_once("../../Includes/Connection.php");
    require_once("../../Includes/Constantes.php"); 
    require_once("../../Includes/mantenimiento.php");
    require_once("../../Includes/MyGrilla.php");
    require_once("../../Includes/MyCombo.php"); 
    require_once("../includes/clsCA_Asignacion_Empleados.php");//ok
    require_once("../includes/clsCA_Usuarios.php");
    
    $u = new ca_usuarios();
    $u->MyUrl = db_host();
    $u->MyUser= db_user();
    $u->MyPwd = db_pass();
    $u->MyDBName= db_name();
    
    $empleado_codigo=$_SESSION["empleado_codigo"];
    
    $u->empleado_codigo = $_SESSION["empleado_codigo"];
    $r = $u->Identificar();
    $nombre_usuario  	= $u->empleado_nombre;
    
    $fecha=date("d/m/Y");
    $estado_proceso=0;
    if (isset($_POST["estado_proceso"])) $estado_proceso = $_POST["estado_proceso"];
?>
 
<!--<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Bandeja de Reportes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></meta>
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css"/>

<link rel="stylesheet" type="text/css" href="css/list_paginado.css"/>
<style type="text/css" title="currentStyle">
    @import "css/list_paginado.css";
</style>
<script type="text/javascript" src="app/jquery.dataTables.js"></script><!--app.ui.datatable.js  jquery.dataTables-->

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

<script src="app/app.ui.block.js" type="text/javascript" ></script>
<script type="text/javascript" src="js/bandeja.validador.test.js"/>

<script language="javascript">
function comprobarvalor(obj){
    
}
</script>
</head>
<body class="PageBODY" onLoad="return WindowResize(10,20,'center')">
<form id="frm" name="frm" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
    <table  border="0" align="center" cellpadding="0" cellspacing="1" >
        <tr>
            <td align="left" width="500" style="font-size: 13px;color: #000000;font-weight: bold;"><center>Bandeja de Aprobaci&oacute;n de Eventos</center></td>
        </tr>
   <tr>
       <td align="left" width="500" style="font-size: 13px;"><b>Validador&nbsp;:</b>&nbsp;&nbsp;
           <font style="color:#3366CC;"><b><?php echo $nombre_usuario;?></b></font>
       </td>
  </tr>
  <tr>
       <td align="center" width="500" style="font-size: 13px;">
        <a href="javascript:PooBV.levanta_ventana()" style="text-decoration: none;color: #E17777; font-weight:600; font-size: 12px;" title="Consultar Eventos Registrados">
            Ver Eventos Registrados</a>&nbsp;
           <img src="../images/ico/search.png" alt="Consultar Eventos Registrados" border="0" onclick="javascript:PooBV.levanta_ventana()" height="16" width="16" style="cursor: hand;" />
       </td>
  </tr>
</table>

<table width='450' align="center" border="0" cellspacing="0" cellpadding="1">
<tr>
    <td class='ColumnTD' colspan='3' align='center'>Parámetros&nbsp; de B&uacute;squeda</td>
</tr>
<tr>
    <td align="right" valign="top"><b>Incidencia</b>&nbsp;:&nbsp;</td>
    <td valign="top">
        <?php
            $combo = new MyCombo();
            $combo->setMyUrl(db_host());
            $combo->setMyUser(db_user());
            $combo->setMyPwd(db_pass());
            $combo->setMyDBName(db_name());

            $sql=" select incidencia_codigo,incidencia_descripcion ";
            $sql.=" from ca_incidencias (nolock) where validable = 1 and incidencia_activo = 1 ";
            $sql.=" order by 2 ";

            $combo->query = $sql;
            $combo->name = "incidencia_proceso";
            $combo->value = $incidencia_proceso."";
            $combo->more = "class='Select' style='width:230px'";
            $rpta = $combo->Construir();
            echo $rpta;
        
        ?>
        &nbsp;&nbsp;
        <button type="button" title="Refresh" style="width:20px; height:21px" onclick="javascript:PooBV.actualizar();">
            <img src="../images/smyles/refresh.png" height="15"/>
        </button>&nbsp;&nbsp;
        <button type="button" title="Salir" style="width:20px; height:21px" onclick="javascript:PooBV.salir();">
            <img src="../images/biGoingOnline.gif" height="15"/>
            &nbsp;
        </button>&nbsp;
    </td>
    
    <td rowspan="2" valign="top" id="resumen"></td>
</tr>

<tr>
        <td align="right" valign="top"><b>Area</b>&nbsp;:&nbsp;</td>
        <td valign="top">
            <?php
                $combo = new MyCombo();
                $combo->setMyUrl(db_host());
                $combo->setMyUser(db_user());
                $combo->setMyPwd(db_pass());
                $combo->setMyDBName(db_name());

                $ssql="
                        SELECT A.Area_Codigo, A.Area_Descripcion
                        FROM   CA_Incidencias I (NOLOCK) INNER JOIN
                               CA_Eventos EV (NOLOCK) INNER JOIN
                               Areas A  (NOLOCK) INNER JOIN
                               ca_incidencia_areas AS iia ON A.Area_Codigo = iia.area_codigo ON 
                               EV.Incidencia_Codigo = iia.incidencia_codigo ON 
                               I.Incidencia_codigo = iia.incidencia_codigo INNER JOIN
                               CA_Asistencias AI  (NOLOCK) ON EV.Empleado_Codigo = AI.Empleado_Codigo AND 
                               EV.Asistencia_codigo = AI.Asistencia_codigo AND iia.area_codigo = AI.area_codigo
                        WHERE  (iia.empleado_codigo = ".$empleado_codigo.") 
                                    AND (A.Area_Activo = 1) 
                                    AND (EV.evento_activo = 1) 
                                    AND (I.validable = 1) 
                                    AND (EV.ee_codigo = 2)
                        GROUP BY A.Area_Codigo, A.Area_Descripcion
                        ORDER BY A.Area_Descripcion
                ";
                
                
                $combo->query = $ssql;
                $combo->name = "Cmb_Area";
                
                $combo->more = "class=select style='width:320px;' ";
                $rpta = $combo->Construir();
                echo $rpta;
            ?>
        </td>
    </tr>
<tr>
	<td class='ColumnTD' colspan='3'>&nbsp;</td>
</tr>
</table>

<table width='400px' align='center' cellspacing='0' cellpadding='0' border='0'>
<tr align='center'>
 <td colspan="2"  >
     <input name="cmdAprobar" id="cmdAprobar" type="button" value="Aprobar"  class="Button"  style="WIDTH: 90px;" onclick="javascript:PooBV.accion(1);"/>
     <input name="cmdRechazar" id="cmdRechazar" type="button" value="Rechazar"  class="Button"  style="WIDTH: 90px;" onclick="javascript:PooBV.accion(2);"/>
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
</form>
</body>
</html>