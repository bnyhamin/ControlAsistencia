<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Justifica.php");
require_once("../../Includes/MyGrilla.php");
require_once("../../Includes/MyCombo.php");
  
$fecha=date("d/m/Y");

$combo = new MyCombo();
$combo->setMyUrl(db_host());
$combo->setMyUser(db_user());
$combo->setMyPwd(db_pass());
$combo->setMyDBName(db_name());

$justifica = new Justifica();
$justifica->setMyUrl(db_host());
$justifica->setMyUser(db_user());
$justifica->setMyPwd(db_pass());
$justifica->setMyDBName(db_name());

$h_inicio = "00";
$m_inicio = "00";

$h_fin = "23";
$m_fin = "59";
  
$local_codigo = 0;
$incidencia_codigo = 0;
$empleado_dni = "";
$cmb_areas = "";
$areas_seleccionadas = ""; 
$incidencia_justifica = 0; 
$operacion = 0;

$start = 0;
$limit= 50;
$pagina = 1;

if(isset($_GET["start"])) $start = $_GET["start"]; 
if(isset($_GET["pagina"])){
    $pagina = $_GET["pagina"];
    //echo $pagina;
} 
    

$usuario = $_SESSION["empleado_codigo"];
  
if (isset($_POST["fecha"])) $fecha = $_POST["fecha"];
if (isset($_POST["h_inicio"])) $h_inicio = $_POST["h_inicio"];
if (isset($_POST["m_inicio"])) $m_inicio = $_POST["m_inicio"];
if (isset($_POST["h_fin"])) $h_fin = $_POST["h_fin"];
if (isset($_POST["m_fin"])) $m_fin = $_POST["m_fin"];
if (isset($_POST["empleado_dni"])) $empleado_dni = $_POST["empleado_dni"];
if (isset($_POST["local_codigo"])) $local_codigo = $_POST["local_codigo"];
if (isset($_POST["cmb_areas"])) $cmb_areas = $_POST["cmb_areas"];
if (isset($_POST["hddAreas"])) $areas_seleccionadas = $_POST["hddAreas"];
if (isset($_POST["incidencia_codigo"])) $incidencia_codigo = $_POST["incidencia_codigo"];

if (isset($_POST["operacion"])) $operacion = $_POST["operacion"];

$arr_areas = explode(",",$areas_seleccionadas);

$inicio = $fecha." ".$h_inicio.":".$m_inicio;
$fin    = $fecha." ".$h_fin.":".$m_fin;
$accion = "";
$total_registros = 0;

if(isset($_POST["total_registros"]))
    $total_registros = $_POST["total_registros"];

if(isset($_POST["hddAccion"])){
    $accion = $_POST["hddAccion"];
    

    if($accion == "JUSTIFICA_TARDANZA"){
        $rpta =$justifica->Justifica_Tardanza($fecha,$inicio, $fin,$empleado_dni,$local_codigo,$incidencia_codigo,$areas_seleccionadas, $usuario);
        if($rpta = "OK"){
            ?>
            <script>alert("Se realizo la justificación de Tardanza");</script>
            <?php
        }else{
            ?>
            <script>alert("Ocurrio un error al justificar tardanza");</script>
            <?php
        }
    }
    
    if($accion == "JUSTIFICA_SALIDA"){
        $rpta = $justifica->Justificar_Salida($fecha, $inicio, $fin, $empleado_dni,$local_codigo,$areas_seleccionadas,$usuario);
        if($rpta = "OK"){
            ?>
            <script>alert("Se realizo la justificación de Salida");</script>
            <?php
        }else{
            ?>
            <script>alert("Ocurrio un error al justificar salida");</script>
            <?php
        }
    }
    
    
}


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Catálogo de Incidencias</title>
<meta http-equiv="pragma" content="no-cache"/>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" media="all" href="../../views/js/librerias/datepicker/calendar-win2k-cold-1.css" title="win2k-cold-1" />


<link rel="stylesheet" type="text/css" href="../js/plugins/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="../js/plugins/multiselect/demos/assets/style.css" />
<link rel="stylesheet" type="text/css" href="../js/plugins/multiselect/demos/assets/prettify.css" />
<link rel="stylesheet" type="text/css" href="../js/plugins/jqueryui/themes/base/app.custom.css"/><!--jQuery Css-->
<script type="text/javascript" language="javascript" src="../js/jquery11.js"></script><!--jQuery v1.11.0-->
<script type="text/javascript" language="javascript" src="../js/plugins/jqueryui/ui/app.custom.js"></script><!--jQuery UI - v1.10.4-->
<script type="text/javascript" src="../js/plugins/multiselect/demos/assets/prettify.js"></script>
<script type="text/javascript" src="../js/plugins/multiselect/src/jquery.multiselect.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
      
      $("#cmb_areas").multiselect({
        //header: "1,2,3"
      });

    });
    Ext_Conceptos_US={
      salirConceptos:function(){
        var values = $("#cmb_areas").val();
        var array_of_checked_values = $("#cmb_areas").multiselect("getChecked").map(function(){
          return this.value;
        }).get();
        $("#hddAreas").val(array_of_checked_values);
      }
    }
</script>

<script>
function buscar(){
    Ext_Conceptos_US.salirConceptos();
    var operacion = document.frm.operacion.value
    if(operacion == 0){
        alert("Debe seleccionar primero el tipo de operacion");
        return;
    }
    document.frm.hddAccion.value="FILTRAR";
	document.frm.submit();
}

function paginacion(start,pagina){
    document.frm.hddAccion.value="FILTRAR";
    document.frm.action = 'justificar_incidencia.php?start=' + start + '&pagina=' + pagina
	document.frm.submit();
}

function justificar(){
    var operacion = document.frm.operacion.value;
    if(operacion == 0){
        alert("Debe seleccionar primero el tipo de operacion");
        return;
    }
    
    var total_registros = document.frm.total_registros.value;
    if(total_registros == 0){
        alert("No existen registros para justificar");
        return;
    }
    
    if(operacion == 1){
        if(document.frm.incidencia_codigo.value == 0 || document.frm.incidencia_codigo.value == ""){
            alert("Debe seleccionar la incidencia que reemplazara a la tardanza")
            document.frm.incidencia_codigo.focus()
        }else{
            if (confirm('Seguro de justificar tardanzas a los empleados encontrados?')==false) return false;
            Ext_Conceptos_US.salirConceptos();
            document.frm.hddAccion.value="JUSTIFICA_TARDANZA";
            document.frm.submit();    
        }
    }
    
    if(operacion == 2){
        if (confirm('Seguro de justificar salida a los empleados encontrados?')==false) return false;
        Ext_Conceptos_US.salirConceptos();
        document.frm.hddAccion.value="JUSTIFICA_SALIDA";
        document.frm.submit();    
    }
    
	
}
function selecciona_operacion(){
    document.frm.hddAccion.value = "";
    document.frm.submit();
}
</script>

</head>


<body class="PageBODY" onload="return WindowResize(10,20,'center')" >

<div id="popCalF" style="BORDER-BOTTOM: 2px ridge; Z-INDEX:200; BORDER-LEFT: 2px ridge; BORDER-RIGHT: 2px ridge; BORDER-TOP: 2px ridge; POSITION: absolute; VISIBILITY: hidden; WIDTH: 10px">
<iframe name="popFrameF" id="popFrameF" src="../popcj.htm" frameborder="1" scrolling="no" width="183" height="188"></iframe>
</div>

<center class="FormHeaderFont">
    <?php echo $operacion == 1?"Justificar Tardanza":($operacion==2?"Justificar Salida":"Justificación de Incidencia")?> 
</center>
<form id="frm" name="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<table class='FormTable' border="0" cellpadding="1" cellspacing="1"  id='tblOpciones' align="center" style="border: 1px solid #000;">
    <tr>
        <td class="ColumnTD" align="right">Operaci&oacute;n:</td>
        <td colspan="3">
            <select name="operacion" id="operacion" onchange="selecciona_operacion();">
                <option value="0" <?php if($operacion == 0) echo "Selected='selected'"?>>Seleccionar</option>
                <option value="1" <?php if($operacion == 1) echo "Selected='selected'"?>>Justificar Tardanza</option>
                <option value="2" <?php if($operacion == 2) echo "Selected='selected'"?>>Justificar Salida</option>
            </select>
        </td>
    </tr>
    <tr><td class="ColumnTD" colspan="4" align="center">Filtros Principales</td></tr>
    <tr>
        <td class="ColumnTD" width="20%" align="right">&nbsp;&nbsp;Fecha:&nbsp;</td>
		<td colspan="3">
        	<input type='text' class='input' id='fecha' name='fecha' size='11' value='<?php if(isset($fecha)) echo $fecha?>' readonly="true" />
			<img  src="../images/columnselect.gif" width="15" height="15" border="0"  style='cursor:hand;' id="calendario" alt="Seleccionar Fecha"/>
		</td>
    </tr>
    <tr>
        <td class="ColumnTD" align="right"><?php if($operacion==1) echo "Turno inicio";if($operacion==2) echo "Turno finalizo"; ?> entre las:</td>
        <td>
            <select class='select' name='h_inicio' id='h_inicio' style='width:40px'>
            <?php for($h=0; $h < 24; $h++):
                    $hh=$h; 
                    if(strlen($h)<=1) $hh="0".$h; 
                    if ($h==$h_inicio):?> 
                        <option value="<?php echo $h?>" selected="selected" ><?php echo $hh ?></option>;
            <?php   else:?> 
    				    <option value="<?php echo $h?>"><?php echo $hh ?></option>
            <?php   endif;?>  
            <?php endfor; ?>
            </select>
            &nbsp;:&nbsp;
            <select class='select' name='m_inicio' id='m_inicio' style='width:40px'>
            <?php for($m=0; $m < 60; $m++):
                    $mm=$m; 
                    if(strlen($m)<=1) $mm="0".$m; 
                    if ($m==$m_inicio):?> 
                        <option value="<?php echo $m?>" selected="selected" ><?php echo $mm ?></option>;
            <?php   else:?> 
    				    <option value="<?php echo $m?>"><?php echo $mm ?></option>
            <?php   endif;?>  
            <?php endfor; ?>
            </select>
        </td>
        
        <td class="ColumnTD" align="right">Y las:</td>
        <td>
            <select class='select' name='h_fin' id='h_fin'>
            <?php for($H=0; $H < 24; $H++):
                    $HH=$H; 
                    if(strlen($H)<=1) $HH="0".$H; 
                    if ($H==$h_fin):?> 
                        <option value="<?php echo $H?>" selected="selected" ><?php echo $HH ?></option>;
            <?php   else:?> 
    				    <option value="<?php echo $H?>"><?php echo $HH ?></option>
            <?php   endif;?>  
            <?php endfor; ?>
            </select>
            &nbsp;:&nbsp;
            <select class='select' name='m_fin' id='m_fin'>
            <?php for($M=0; $M < 60; $M++):
                    $MM=$M; 
                    if(strlen($M)<=1) $MM="0".$M; 
                    if ($M==$m_fin):?> 
                        <option value="<?php echo $M?>" selected="selected" ><?php echo $MM ?></option>;
            <?php   else:?> 
    				    <option value="<?php echo $M?>"><?php echo $MM ?></option>
            <?php   endif;?>  
            <?php endfor; ?>
            </select>
        </td>
        
    </tr>
    <?php if($operacion == 1):?>
    <tr align='left'>
        <td class="ColumnTD" align="right">Incidencia Reemplaza: </td>
        <td  colspan="3">
            <?php
            $sSql = "select Incidencia_codigo, str(Incidencia_codigo) + '-' + Incidencia_descripcion
                    from CA_Incidencias 
                    where  incidencia_codigo in (150)
                    order by 2 asc";
        	$combo->query = $sSql;
        	$combo->name = 'incidencia_codigo';
            $combo->more = "class=select";
        	$combo->value = $incidencia_codigo."";
        	$rpta = $combo->Construir();
        	echo $rpta;
            ?>
        &nbsp;</td>
    </tr>
    <?php endif;?>
    <tr><td class="ColumnTD" colspan="4" align="center">Filtros Adicionales </td></tr>
    <tr>
        <td class="ColumnTD" align="right">Dni:</td>
        <td colspan="3">
            <input class='Input' name='empleado_dni' id='empleado_dni' size="8" maxlength="8" value='<?php if(isset($empleado_dni)) echo $empleado_dni ?>' />
        </td>
    </tr>
    <tr align='left'>
        <td class="ColumnTD" align="right">Centro Contacto: </td>
        <td  colspan="3">
            <?php
            $sSql = "select Local_Codigo, Local_Descripcion
                    from Locales              
                    where Local_activo = 1 order by 2";            
        	$combo->query = $sSql;
        	$combo->name = 'local_codigo';
            $combo->more = "class=select";
        	$combo->value = $local_codigo."";
        	$rpta = $combo->Construir();
        	echo $rpta;
            ?>
        &nbsp;</td>
    </tr>
    <tr>
        <td class="ColumnTD" align="right">Area(s):</td>
        <td colspan="3">
            <select title="Basic example" multiple="multiple" name="cmb_areas" id="cmb_areas" size="5">
            <?php 
            $rs = $justifica->Listar_Areas();
            if($rs->RecordCount() > 0):
                while(!$rs->EOF):
                ?>
                <option value="<?php echo $rs->fields["0"]?>" <?php if(in_array($rs->fields["0"],$arr_areas)) echo "selected='selected'"; ?>>
                    <?php echo $rs->fields["1"]?>
                </option>
            <?php
                $rs->MoveNext(); 
                endwhile;
            endif;
            ?>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="4" class="ColumnTD" align="center">
            <button class="button" onclick="buscar();">Buscar</button>&nbsp;&nbsp;
            <button class="button" onclick="justificar();">Justificar</button>
        </td>
    </tr>
</table>

<input type="hidden" name="hddAccion" id="hddAccion" value="<?php if(isset($_POST["hddAccion"])) echo $_POST["hddAccion"];?>"/>
<input type="hidden" name="hddAreas"   id="hddAreas" value="<?php echo $areas_seleccionadas?>"/>

<br />

<?php
	
    if($operacion == 1 || $operacion == 2):
            
        $rs = $operacion==1?$justifica->Listar_Tardanzas($fecha,$inicio, $fin, $empleado_dni,$local_codigo,$areas_seleccionadas):
                            $justifica->Listar_Salidas($fecha,$inicio,$fin,$empleado_dni,$local_codigo,$areas_seleccionadas); 
        
        $total_registros = $rs->RecordCount();
        if($total_registros > 0){
            $datos = $justifica->Paginacion($rs,$start,$limit);
            $i=0;?>
            <table border="0" align="center" width="100%" cellpadding="2" cellspacing="1"  style="border: 1px solid #000;">
            <tr class="ColumnTD">
                <td>Nº</td>
                <td>DNI</td>
                <td>Empleado</td>
                <td>Area</td>
                <td>Local</td>
                <td><?php echo $operacion==1?"Inicio Turno":"Fin Turno";?></td>
                <td>Marcaci&oacute;n</td>
                <td>Tiempo(min)</td>
            </tr>
            <?php
            $total_parcial = count($datos);
            foreach($datos as $dato):?>
                <tr bgcolor="white">
                    <td><?php echo $dato["N"]?></td>
                    <td><?php echo $dato["DNI"]?></td>
                    <td><?php echo $dato["EMPLEADO"]?></td>
                    <td><?php echo $dato["AREA"]?></td>
                    <td><?php echo $dato["LOCAL"]?></td>
                    <td><?php echo $dato["INICIO_FIN"]?></td>
                    <td><?php echo $dato["MARCACION"]?></td>
                    <td><?php echo $dato["TIEMPO"]?></td>
                </tr>
            <?php
            endforeach;
            ?>
            <tr>
                <td align="right" colspan="8">Mostrando <?php echo $start + $total_parcial ?> de <?php echo $total_registros?> registros</td>
            </tr>
            </table>
            <br />
            <table border="0" cellpadding="1" cellspacing="1"  align="center">
                <tr>
                <td >
                    <?php $anterior = $pagina-1;$siguiente = $pagina + 1;?>
                    <?php if( ($start - $limit) >= 0 ): ?>
                        <img align="center" src="../images/1leftarrow.png" onclick="paginacion(<?php echo $start - $limit;?>,<?php echo $anterior ?>)"/>
                    <?php endif;?>
                </td>
                <td align="center">Pag. <?php echo $pagina;?></td>
                <td ><?php if( ($start + $limit) < $total_registros ): ?>
                    <img align="center" src="../images/1rightarrow.png" onclick="paginacion(<?php echo $start + $limit;?>,<?php echo $siguiente ?>)"/>
                    <?php endif;?>
                </td>
                </tr>
            </table>
            <?php
        }else{
            ?>
            <table border="0" align="center" width="100%" cellpadding="2" cellspacing="1"  style="border: 1px solid #000;">
                <tr bgcolor="white">
                    <td> No se encontraron registros con las condiciones ingresadas</td>
                </tr>
            </table>
            <?php
        }
    endif;
    echo Menu("../menu.php");
?>

    <input type="hidden" name="total_registros" id="total_registros" value="<?php echo $total_registros?>"/>
</form>




<script type="text/javascript" src="../../views/js/librerias/datepicker/calendar.js"></script>
<script type="text/javascript" src="../../views/js/librerias/datepicker/lang/calendar-en.js"></script>
<script type="text/javascript" src="../../views/js/librerias/datepicker/calendar-setup.js"></script>

<script type="text/javascript">
    Calendar.setup({
        inputField     :    "fecha",
        ifFormat       :    "%d/%m/%Y",
        showsTime      :    false,
        button         :    "calendario",
        singleClick    :    true,
        step           :    1
    });
</script>


</body>
</html>