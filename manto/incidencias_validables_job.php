<?php header("Expires: 0");

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php");
require_once("../../Includes/clsIncidencias.php"); 
require_once("../../Includes/MyCombo.php");
require_once("../../Includes/clsLibreria.php");
require_once("../includes/clsCA_Areas.php"); 

$npag="";
$orden="";
$buscam="";
$torder="";
$usuario_id=0;
$incidencia_codigo=0;
$incidencia_descripcion="";

$usuario_id = $_SESSION["empleado_codigo"];


$empleado_codigo1=0;
$area_codigo=0;
$nombre_empleado="";
$buscar="";

if (isset($_POST["pagina"])) $npag = $_POST["pagina"];
if (isset($_POST["orden"])) $orden = $_POST["orden"];
if (isset($_POST["buscam"])) $buscam = $_POST["buscam"];
if (isset($_POST["cboTOrden"])) $torder = $_POST["cboTOrden"];

if (isset($_GET["pagina"])) $npag = $_GET["pagina"];
if (isset($_GET["orden"])) $orden = $_GET["orden"];
if (isset($_GET["buscam"])) $buscam = $_GET["buscam"];
if (isset($_GET["cboTOrden"])) $torder = $_GET["cboTOrden"];

if (isset($_POST["incidencia_codigo"])) $incidencia_codigo = $_POST["incidencia_codigo"];
if (isset($_GET["incidencia_codigo"])) $incidencia_codigo = $_GET["incidencia_codigo"];
if (isset($_POST["incidencia_descripcion"])) $incidencia_descripcion = $_POST["incidencia_descripcion"];
if (isset($_POST["hddempleado"])) $empleado_codigo1=$_POST["hddempleado"];
if (isset($_POST["hddarea"])) $area_codigo=$_POST["hddarea"];
if (isset($_POST["hddnombreempleado"])) $nombre_empleado=$_POST["hddnombreempleado"];
if (isset($_POST["hddbuscar"])) $buscar=$_POST["hddbuscar"];

$o = new incidencias();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

$a = new areas();
$a->MyUrl = db_host();
$a->MyUser= db_user();
$a->MyPwd = db_pass();
$a->MyDBName= db_name();

$lib = new libreria();
$lib->MyUrl = db_host();
$lib->MyUser= db_user();
$lib->MyPwd = db_pass();
$lib->MyDBName= db_name();

if (isset($_POST["hddaccion"])){
        if ($_POST["hddaccion"]=='QUI'){
            
            $o->incidencia_codigo = $incidencia_codigo;
            $o->empleado_codigo = $empleado_codigo1;
            $o->rol=10;
            
            if($_POST["hddindicador"]=="1"){
                $o->indicador=1;    
                $o->eliminar_empleado_area();
                $o->eliminar_empleado_incidencia();
                //$o->eliminarRolEmpleado();
            }else{
                $o->eliminar_empleado_incidencia();
                //$o->eliminarRolEmpleado();
            }
            
        }
        
        if ($_POST["hddaccion"]=='LST'){
            $empleado_codigo1=$_POST["hddempleado"];
        }
        if ($_POST["hddaccion"]=='SVEA'){
            $lst_area_selec = explode('|',$area_codigo);
            array_pop($lst_area_selec);
            foreach ($lst_area_selec as $key => $value) {
                $o->incidencia_codigo=$incidencia_codigo;
                $o->empleado_codigo=$empleado_codigo1;
                $o->area_codigo=$value;
                $o->usuario_registra=$usuario_id;
                $o->agregarEmpleadoaArea();
            }
                       
        }
        
        if ($_POST["hddaccion"]=='DELA'){
                        
            $lst_area_selec = explode('|',$area_codigo);
            array_pop($lst_area_selec);
            foreach ($lst_area_selec as $key => $value) {
                $o->incidencia_codigo=$incidencia_codigo;
                $o->empleado_codigo=$empleado_codigo1;
                $o->area_codigo=$value;
                $o->eliminar_empleado_area();
            }
            
        }
        
}
if ($incidencia_codigo!=""){
	$o->incidencia_codigo = $incidencia_codigo;
	$mensaje = $o->Query();
	$incidencia_descripcion = $o->incidencia_descripcion;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Registro de Incidencias</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script type="text/javascript" src="../asistencias/app/app.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
<script language='javascript'>
function buscararea(search){
    if(parseInt($.trim($("#empleado_descripcion").val()).length)<3){
        alert('Debe ingresar al menos 3 caracteres');
        $("#empleado_descripcion").focus();
        return false;
    }
    CenterWindow("../manto/listaEmpleados.php?strbuscar=" + search + "&hddincidencia="+document.frm.incidencia_codigo.value+"","ModalChild",600,500,"yes","center");
    return true;
}
function filtroAreas(codigo, descripcion,buscar){
    document.frm.empleado_codigo.value=codigo;
    document.frm.empleado_descripcion.value=descripcion;
    $("#hddempleado").val(codigo);
    $("#hddnombreempleado").val(descripcion);
    $("#hddbuscar").val(buscar);
    
    document.frm.submit();
}
function obtenerEmpleado(frm,origen,indicador){   
    var myForm, myElement;
    myForm = document.forms(frm);
    with(myForm.item(origen).options){
        for( i = 0; i < length; i++ ){
            if( item(i).selected ){
                myElement = document.createElement("option");
                myElement.text = options(i).text;
                myElement.value = options(i).value;
                if(parseInt(indicador)==1){
                    document.frm.hddaccion.value="LST";
                    $("#hddempleado").val(myElement.value);
                    $("#hddnombreempleado").val(myElement.text);
                    document.frm.submit();
                }else{
                    document.frm.hddaccion.value="QUI";
                    $("#hddempleado").val(myElement.value);
                    $("#hddindicador").val("0");
                    if(parseInt(document.frm.lstEmpleadoAreas.length)>0){
                        if (confirm('El empleado tiene areas asignadas ¿Desea eliminarlo?')){
                            $("#hddindicador").val("1");
                            document.frm.submit();
                        }
                    }else{
                        document.frm.submit();
                    }
                    
                }
                
            }
        }
    }
}
function Mover(frm,origen,destino,ret){
        
    var myForm, mySelect, myElement;
    myForm = document.forms(frm);
    mySelect = myForm.item(destino);

    var lst_areas_selec = ''
    var selObj = document.getElementById(origen);
      var i;
      for (i=0; i<selObj.options.length; i++) {
        if (selObj.options[i].selected) {
            myElement = document.createElement("option");
            myElement.text = selObj.options[i].text;
            myElement.value = selObj.options[i].value;
            lst_areas_selec += myElement.value+'|';
        }

      }
    if(ret=="1-2"){
        document.frm.hddaccion.value="SVEA";
    }else{
        document.frm.hddaccion.value="DELA";
    }
    $("#hddarea").val(lst_areas_selec);
    document.frm.submit();
        
        
}
function cancelar(){
    self.location.href = "main_incidencias_validables.php?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
}

</script>
</head>
<body Class='PageBODY'>
<?php
if ($mensaje!="OK") echo getMensaje($mensaje);
?>
<center class=FormHeaderFont>Dirigir Incidencias</center>
<form name='frm' id='frm' action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post' onSubmit='javascript:return ok();'>
<table  class='FormTable' width='70%' align='center' cellspacing='0' cellpadding='0' border='1'>
<tr>
	<td class='FieldCaptionTD' align='right'>Código</td>
        <td class='DataTD' colspan="3">
		<Input  class='Input' type='text' name='incidencia_codigo' id='incidencia_codigo' value="<?php echo $incidencia_codigo ?>" maxlength='0' style='width:80px;' <?php echo  lectura("D")?>>
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Descripcion</td>
        <td class='DataTD' colspan="3">
		<Input  class='Input' type='text' name='incidencia_descripcion' id='incidencia_descripcion' value="<?php echo $incidencia_descripcion?>" maxlength='80' style='width:300px;' >
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Empleado</td>
        <td class='DataTD' colspan="3">
		<Input  class='Input' type='hidden' name='empleado_codigo' id='empleado_codigo' value="<?php //echo $area_codigo ?>">
                <Input  class='Input' type='text' name='empleado_descripcion' id='empleado_descripcion' value='<?php echo $buscar;?>' maxlength='80' style='width:250px;'>
                <img src="../images/buscaroff.gif" alt="buscar empleado" onclick="return buscararea(document.frm.empleado_descripcion.value)" style="cursor:hand" >
	</td>
</tr>
<tr>
    <td class='FieldCaptionTD' align='right' valign="top">Empleados Asignados</td>
    <td class='DataTD' valign="top">
        <select class="Select" id="lstEmpleadoAsignados" name="lstEmpleadoAsignados" size="15" style="width: 300px;" onclick="return obtenerEmpleado('frm','lstEmpleadoAsignados','1')">
        <?php
            $ssql=" select vdatos.empleado_codigo,vdatos.empleado ";
            $ssql.=" from ca_incidencia_empleado ";
            $ssql.=" inner join vdatos on ca_incidencia_empleado.empleado_codigo = vdatos.empleado_codigo ";
            $ssql.=" where ca_incidencia_empleado.incidencia_codigo =  ".$incidencia_codigo." ";
            $ssql.=" order by vdatos.empleado asc ";
            $result = $lib->consultar_datos_sql($ssql);
            if(count($result)>0){
                foreach ($result as $key => $value) {
                    if($value[0]==$empleado_codigo1)
                        echo "<option value =" . $value[0] . " selected>" . $value[1] . "</option>/n";
                    else
                        echo "<option value =" . $value[0] . ">" . $value[1] . "</option>/n";
                }
            }    
        ?>
        </select>
        
      </td>
      <td colspan="3" valign="top" class='DataTD'>
          Eliminar Asignacion&nbsp;
          <img src="../images/stop.png" alt="Eliminar Asignacion" onclick="return obtenerEmpleado('frm', 'lstEmpleadoAsignados','2')" style="cursor:hand"/>
          <table cellpadding="0" cellspacing="0" border="0" width="100%" height="165">
              <tr>
                  <td valign="bottom">Areas asignadas a : <b><?php echo $nombre_empleado;?></b></span></td>
              </tr>
          </table>
      </td>   
</tr>
<tr>
    <td class='FieldCaptionTD' align='right' valign="top">Asignar Areas</td>
    <td class='DataTD' valign="top">
        <select multiple="multiple" class="Select" id="lstAreas" name="lstAreas" size="15" style="width: 300px;" ondblclick="return Mover('frm', 'lstAreas','lstEmpleadoAreas','1-2')">
        <?php
            $ssql=" select areas.area_codigo, areas.area_descripcion ";
            $ssql.=" from areas ";
            $ssql.=" where ";
            $ssql.=" areas.area_codigo not in ( ";
            $ssql.=" select area_codigo ";
            $ssql.=" from ca_incidencia_areas where  incidencia_codigo = ".$incidencia_codigo." and empleado_codigo = ".$empleado_codigo1." ";
            $ssql.=" ) and area_activo = 1 ";
            $ssql.=" order by areas.area_descripcion asc ";
            
            $result = $lib->consultar_datos_sql($ssql);
            
            if(count($result)>0){
                foreach ($result as $key => $value) {
                    echo "<option value =" . $value[0] . " >" . $value[1] . "</option>/n";
                }
            }
            
        ?>
        </select>
        
    </td>
    <td class='DataTD'>
		<input type="button" id="cmd1" value=">>" class="Button" onclick="return Mover('frm', 'lstAreas','lstEmpleadoAreas','1-2')"/>
		<br/><br/>
		<input type="button" id="cmd2" value="<<" class="Button" onclick="return Mover('frm', 'lstEmpleadoAreas','lstAreas','2-1')"/>
    </td>
    <td class='DataTD' valign="top">
        <select multiple="multiple" class="Select" id="lstEmpleadoAreas" name="lstEmpleadoAreas" size="15" style="width: 300px;" ondblclick="return Mover('frm', 'lstEmpleadoAreas','lstAreas','2-1')">
        <?php
            $ssql=" select ca_incidencia_areas.area_codigo,areas.area_descripcion ";
            $ssql.=" from ca_incidencia_areas ";
            $ssql.=" inner join areas on ca_incidencia_areas.area_codigo = areas.area_codigo ";
            $ssql.=" where incidencia_codigo = ".$incidencia_codigo." and empleado_codigo = ".$empleado_codigo1." ";
            $ssql.=" order by areas.area_descripcion asc";
            
            $result = $lib->consultar_datos_sql($ssql);
            if(count($result)>0){
                foreach ($result as $key => $value) {
                    echo "<option value =" . $value[0] . " >" . $value[1] . "</option>/n";        
                }
            }
            
        ?>
        </select>
    </td>    
</tr>
<tr>
    <td colspan=4  class='FieldCaptionTD'>&nbsp;
</td>
</tr>
<tr align='center'>
	<td colspan=4  class='FieldCaptionTD'>
		<input name='cmdCerrar' id='cmdCerrar' type='button' class=button value='Cerrar'   style='width:80px' onclick="cancelar();">
	</td>
</tr>
</table>
    
<input type="hidden" id="hddindicador" name="hddindicador" value=""/>
<input type="hidden" id="hddbuscar" name="hddbuscar" value="<?php echo $buscar;?>"/>
<input type="hidden" id="hddnombreempleado" name="hddnombreempleado" value="<?php echo $nombre_empleado;?>"/>
<input type="hidden" id="hddaccion" name="hddaccion" value=""/>
<input type="hidden" id="hddarea" name="hddarea" value=""/>
<input type="hidden" id="hddempleado" name="hddempleado" value="<?php echo $empleado_codigo1 ?>"/>
<input type="hidden" id="pagina" name="pagina" value="<?php echo $npag ?>">
<input type="hidden" id="buscam" name="buscam" value="<?php echo $buscam ?>">
<input type="hidden" id="orden" name="orden" value="<?php echo $orden ?>">
<input type="hidden" id="torder" name="torder" value="<?php echo $torder ?>">
</form>
</body>
</html>
