<?php header("Expires: 0"); ?>
<?php

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/clsIncidencias.php"); 
require_once("../../Includes/MyCombo.php"); 
require_once("../includes/clsCA_Areas.php");
require_once("../../Includes/clsLibreria.php");

$incidencia_codigo="0";
$incidencia_descripcion="";
$incidencia_operacion="";
$incidencia_hh_dd="0";
$incidencia_activo="0";
$incidencia_icono="";
$area_codigo="";
$Incidencia_manual='0';
$incidencia_editable='0';
$incidencia_signo='0';
$usuario_id='0';
$evento='0';
$tipo_codigo= "";
$torder="";
$area_descripcion="";
$sustituido="";
$hddAreas="";
$hddAreasdes="";
$codarea="";
$hddruta="";
$usuario_id = $_SESSION["empleado_codigo"];
$mensaje = "";
$validable_mando = "0";
$evento_validable = "0";
$validable_gerente = "0";
$silencio="";
$evento_horas_vbo = "";
$evento_inicio="0";
$evento_fin="0";

$Afecta_Asistencia = "N";

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
if (isset($_POST["incidencia_operacion"])) $incidencia_operacion = $_POST["incidencia_operacion"];
if (isset($_POST["incidencia_hh_dd"])) $incidencia_hh_dd = $_POST["incidencia_hh_dd"];
if (isset($_POST["incidencia_activo"])) $incidencia_activo = $_POST["incidencia_activo"];
if (isset($_POST["Incidencia_manual"])) $Incidencia_manual = $_POST["Incidencia_manual"];
if (isset($_POST["incidencia_signo"])) $incidencia_signo = $_POST["incidencia_signo"];
if (isset($_POST["incidencia_editable"])) $incidencia_editable = $_POST["incidencia_editable"];
if (isset($_POST["evento"])) $evento = $_POST["evento"];
if (isset($_POST["incidencia_icono"])) $incidencia_icono = $_POST["incidencia_icono"];
if (isset($_POST["area_codigo"])) $area_codigo= $_POST["area_codigo"];
if (isset($_POST["tipo_codigo"])) $tipo_codigo= $_POST["tipo_codigo"];
if (isset($_POST["Cmb_Plazo_Horas"])) $evento_horas_vbo= $_POST["Cmb_Plazo_Horas"];
if (isset($_POST["cmb_sustituido"])) $sustituido = $_POST["cmb_sustituido"];
if (isset($_POST["rbtn_silencio"])) $silencio= $_POST["rbtn_silencio"];
if (isset($_POST["hddarea_codigo"])) $hddAreas = $_POST["hddarea_codigo"];
if (isset($_POST["hddarea_codigo_des"])) $hddAreasdes = $_POST["hddarea_codigo_des"];
if (isset($_POST["hddruta"])) $hddruta = $_POST["hddruta"];
if(isset($_POST["evento_inicio"])) $evento_inicio=$_POST["evento_inicio"];
if(isset($_POST["evento_fin"])) $evento_fin=$_POST["evento_fin"];

if (isset($_POST["chk_evento_validable"])) $evento_validable= $_POST["chk_evento_validable"];
if (isset($_POST["chk_validable_mando"])) $validable_mando= $_POST["chk_validable_mando"];
if (isset($_POST["chk_validable_gerente"])) $validable_gerente= $_POST["chk_validable_gerente"];

if (isset($_POST["Incidencia_Inicio_Fin"])) $Incidencia_Inicio_Fin= $_POST["Incidencia_Inicio_Fin"];
if (isset($_POST["Incidencia_NroTicket"])) $Incidencia_NroTicket= $_POST["Incidencia_NroTicket"];
if (isset($_POST["Afecta_Asistencia"])) $Afecta_Asistencia= $_POST["Afecta_Asistencia"];


if(isset($_POST["visado"])){
    $visados =  $_POST["visado"];
    foreach($visados as $visado):
        switch($visado){
            case "validable":
                $evento_validable = 1;
                break;
            case "validable_mando":
                $validable_mando = 1;
                break;
            case "validable_gerente":
                $validable_gerente = 1;
                break;
        }
    endforeach;
}


$o = new incidencias();
$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());

$a = new areas();
$a->setMyUrl(db_host());
$a->setMyUser(db_user());
$a->setMyPwd(db_pass());
$a->setMyDBName(db_name());

$combo = new MyCombo();
$combo->setMyUrl(db_host());
$combo->setMyUser(db_user());
$combo->setMyPwd(db_pass());
$combo->setMyDBName(db_name());

$lib = new libreria();
$lib->MyUrl = db_host();
$lib->MyUser= db_user();
$lib->MyPwd = db_pass();
$lib->MyDBName= db_name();


if (isset($_POST["hddaccion"])){
    if ($_POST["hddaccion"]=='SVE'){
        $o->incidencia_codigo = $incidencia_codigo;
        $o->incidencia_descripcion = $incidencia_descripcion;
        $o->incidencia_hh_dd = $incidencia_hh_dd;
        $o->incidencia_activo = $incidencia_activo;
        $o->incidencia_icono = $incidencia_icono;
        $o->Incidencia_manual = $Incidencia_manual;
        $o->incidencia_editable = $incidencia_editable;
        $o->incidencia_signo = $incidencia_signo;
        $o->area_codigo=0;
        $o->evento = $evento;
        $o->usuario_id=$usuario_id;
        $o->tipo_codigo = $tipo_codigo;
        $o->validable=$evento_validable;
        $o->validable_mando=$validable_mando;
        $o->validable_gerente=$validable_gerente;
        $o->silencio_vbo=$silencio;
        $o->horas_vbo=$evento_horas_vbo;
        $o->sustituye=$sustituido;
        $o->evento_inicio=$evento_inicio;
        $o->evento_fin=$evento_fin;
        $o->Incidencia_Inicio_Fin   = $Incidencia_Inicio_Fin;
        $o->Incidencia_NroTicket    = $Incidencia_NroTicket;
        $o->Afecta_Asistencia = $Afecta_Asistencia;
        //--* guardar registro
        if ($incidencia_codigo==''){
            $mensaje = $o->Addnew();
            $incidencia_codigo = $o->incidencia_codigo;
        }else{
            $mensaje = $o->Update();
        }
        
        
        if($hddruta=="1-2"){
            $arrAreas=split("¬",$hddAreas);
            for($i=0;$i<count($arrAreas);$i++){
                $o->incidencia_codigo=$incidencia_codigo;
                $o->area_codigo=$arrAreas[$i];
                $o->usuario_id=$usuario_id;
                if($incidencia_codigo==""){

                }
                $o->AddAreas();

            }
        }else if($hddruta=="2-1"){
            $arrAreasd=split("¬",$hddAreasdes);
            for($i=0;$i<count($arrAreasd);$i++){
                $o->incidencia_codigo=$incidencia_codigo;
                $o->area_codigo=$arrAreasd[$i];
                $o->usuario_id=$usuario_id;
                $o->Desasignar_area();
            }
        }
        
            if($mensaje=='OK'){
?>
    <script language='javascript'>
        self.location.href='main_incidencias.php?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>';
    </script>
<?php
            }
    }
}

if ($incidencia_codigo!=""){
	//recuperar datos
	$o->incidencia_codigo = $incidencia_codigo;
	$mensaje = $o->Query();
	$incidencia_descripcion = $o->incidencia_descripcion;
	$incidencia_hh_dd = $o->incidencia_hh_dd;
	$incidencia_activo = $o->incidencia_activo;
	$Incidencia_manual=$o->Incidencia_manual;
	$evento=$o->evento;
	$incidencia_icono=$o->incidencia_icono;
	$incidencia_signo=$o->incidencia_signo;	
	$incidencia_editable=$o->incidencia_editable;
    $tipo_codigo = $o->tipo_codigo;
	$area_codigo=$o->area_codigo;	
    $evento_validable=$o->validable;
    $evento_horas_vbo=$o->horas_vbo;
    $sustituido=$o->sustituye;
    $validable_mando=$o->validable_mando;
    $evento_inicio=$o->evento_inicio;
    $evento_fin=$o->evento_fin;
    $validable_gerente=$o->validable_gerente;
    $silencio=$o->silencio_vbo;
    
    $Incidencia_Inicio_Fin  = $o->Incidencia_Inicio_Fin;
    $Incidencia_NroTicket   = $o->Incidencia_NroTicket;
    $Afecta_Asistencia = $o->Afecta_Asistencia;
    
	$a->area_codigo=$area_codigo;
	$rpta=$a->Query();
	$area_descripcion=$a->area_descripcion;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    <title>Registro de Incidencias</title>
    <meta http-equiv="pragma" content="no-cache"/>
    
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <script language="JavaScript" src="../../default.js"></script>
    <script type="text/javascript" src="../asistencias/app/app.js"></script>
    <link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
    <?php  require_once('../includes/librerias_easyui.php');?>

<script language='javascript'>
function ok(){
    if (validarCampo('frm','incidencia_descripcion')!=true) return false;
    
    if(document.frm.hddarea_codigo.value==""){
        alert('Seleccione Area');
        return false;
    }
    
    if(document.frm.chk_evento_validable.checked || document.frm.chk_validable_mando.checked || document.frm.chk_validable_gerente.checked){
        if(document.frm.Cmb_Plazo_Horas.value=="0"){
            alert("Seleccionar Plazo");
            document.frm.Cmb_Plazo_Horas.focus();
            return false;
        }
    }
    
    if (confirm('confirme guardar los datos')== false){
        return false;
    }
    
    document.frm.action += "?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
    document.frm.hddaccion.value="SVE";
    return true;
}
function cancelar(){
	self.location.href = "main_incidencias.php?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
}
function buscararea(search){
	CenterWindow("../manto/listaAreas.php?strbuscar=" + search + "&flag_gerente=0&area_codigo=0&todos=1","ModalChild",600,500,"yes","center");
	return true;
}

function filtroAreas(codigo, descripcion){
document.frm.area_codigo.value=codigo;
document.frm.area_descripcion.value=descripcion;
}
    
function habilita(){
    if(document.frm.chk_evento_validable.checked==true){
        document.frm.cmb_sustituido.disabled=false;
        document.frm.chk_validable_mando.disabled=true;
        document.frm.chk_validable_mando.checked=false;
        document.getElementsByName("rbtn_silencio")[0].checked=true;
        document.frm.chk_validable_gerente.disabled=true;
        document.frm.chk_validable_gerente.checked=false;
    }else if(document.frm.chk_evento_validable.checked==false){
        document.frm.cmb_sustituido.disabled=true;
        document.frm.chk_validable_mando.disabled=false;
        document.frm.chk_validable_mando.checked=false;
        document.getElementsByName("rbtn_silencio")[0].checked=false;
        document.frm.Cmb_Plazo_Horas.selectedIndex=0;
        document.frm.cmb_sustituido.selectedIndex=0;
        document.frm.chk_validable_gerente.disabled=false;
    }
}
        
function habilita_mando(){
    if(document.frm.chk_validable_mando.checked==true){
        document.frm.chk_evento_validable.disabled=true;
        document.frm.chk_validable_gerente.disabled=true;
        document.getElementsByName("rbtn_silencio")[0].checked=true;
        document.frm.chk_validable_gerente.checked=false;
        document.frm.chk_evento_validable.checked=false;
        document.frm.cmb_sustituido.disabled=true;
        document.frm.Cmb_Plazo_Horas.selectedIndex=0;
        document.frm.cmb_sustituido.selectedIndex=0;
    }else if(document.frm.chk_validable_mando.checked==false){
        document.frm.chk_evento_validable.disabled=false;
        document.frm.chk_validable_gerente.disabled=false;
        document.getElementsByName("rbtn_silencio")[0].checked=false;
        document.frm.Cmb_Plazo_Horas.disabled=false;
        document.frm.Cmb_Plazo_Horas.selectedIndex=0;
        document.frm.cmb_sustituido.disabled=false;
    }
}
    
function habilita_gerente(){
    if(document.frm.chk_validable_gerente.checked==true){
        document.frm.chk_evento_validable.disabled=true;
        document.frm.chk_validable_mando.disabled=true;
        document.frm.chk_evento_validable.checked=false;
        document.frm.chk_validable_mando.checked=false;
        document.getElementsByName("rbtn_silencio")[1].checked=true;
        document.frm.cmb_sustituido.disabled=true;
        document.frm.Cmb_Plazo_Horas.selectedIndex=0;
        document.frm.cmb_sustituido.selectedIndex=0;
    }else{
        document.frm.chk_evento_validable.disabled=false;
        document.frm.chk_validable_mando.disabled=false;
        document.frm.Cmb_Plazo_Horas.disabled=false;
        document.frm.Cmb_Plazo_Horas.selectedIndex=0;
        document.frm.cmb_sustituido.disabled=false;
        document.getElementsByName("rbtn_silencio")[1].checked=false;
    }
}
    
function habilita_ini_fin(){
    if(document.frm.evento.checked==true){
        document.frm.evento_inicio.disabled=false;
        document.frm.evento_fin.disabled=false;
        document.frm.evento_fin.checked=true;
    }else if(document.frm.evento.checked==false){
        document.frm.evento_inicio.disabled=true;
        document.frm.evento_fin.disabled=true;
        document.frm.evento_inicio.checked=false;
        document.frm.evento_fin.checked=false;
    }
}
    
function Mover(frm,origen,destino,ret){   
    var myForm, mySelect, myElement;
    myForm = document.forms(frm);
    mySelect = myForm.item(destino);
    with(myForm.item(origen).options){
        for( i = 0; i < length; i++ ){
            if( item(i).selected ){
                myElement = document.createElement("option");
                myElement.text = options(i).text;
                myElement.value = options(i).value;
                mySelect.add( myElement );
                asignar(myElement.value,ret);
                remove(i);
                i--;
            }
        }
    }
}

function asignar(codigo,ret){
    if(ret=="1-2"){
        var cadena=$("#hddarea_codigo").val();
        var newcadena="";
        if($("#hddarea_codigo").val()==""){
            $("#hddarea_codigo").val(codigo);
        }else{
            newcadena=cadena+"¬"+codigo;
            $("#hddarea_codigo").val(newcadena);
        }
        
    }else if(ret=="2-1"){
        var cadenad=$("#hddarea_codigo_des").val();
        var newcadenam="";
        if($("#hddarea_codigo_des").val()==""){
            $("#hddarea_codigo_des").val(codigo);
        }else{
            newcadenam=cadenad+"¬"+codigo;
            $("#hddarea_codigo_des").val(newcadenam);
        }   
    }
    $("#hddruta").val(ret);   
}
</script>
</head>
<body Class='PageBODY'>
<?php
if ($mensaje!="OK") echo getMensaje($mensaje);
?>
<center class=FormHeaderFont>Registro de Incidencias</center>
<form name='frm' id='frm' action='<?php echo $_SERVER['PHP_SELF'] ?>' method='post' onSubmit='javascript:return ok();'>
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
    <td class='FieldCaptionTD' align='right' valign="top">Area</td>
    <td class='DataTD' valign="top">
        <select class="Select" id="lstAreaOrigen" name="lstAreaOrigen" size="10" style="width: 300px;" ondblclick="return Mover('frm', 'lstAreaOrigen','lstAreaDestino','1-2')">
        <?php
            $ssql="select areas.area_codigo, areas.area_descripcion";
            $ssql.=" from areas ";
            $ssql.=" where area_activo = 1 order by areas.area_descripcion asc";
	 
            $resul = $lib->consultar_datos_sql($ssql);
            if (count($resul)>0){
                foreach($resul as $rs){
                    echo "<option value =" . $rs[0] . " >" . $rs[1] . "</option>/n";
                }
            }
        ?>
        </select>
    </td>
    <td class='DataTD'>
		<input type="button" id="cmd1" value=">>" class="Button" onclick="return Mover('frm', 'lstAreaOrigen','lstAreaDestino','1-2')"/>
		<br/><br/>
		<input type="button" id="cmd2" value="<<" class="Button" onclick="return Mover('frm', 'lstAreaDestino','lstAreaOrigen','2-1')"/>
    </td>
    <td class='DataTD' valign="top">
        <select class="Select" id="lstAreaDestino" name="lstAreaDestino" size="10" style="width: 300px;" ondblclick="return Mover('frm', 'lstPersonal','lstSeleccionados','1-2')">
        <?php
            if($incidencia_codigo=="") $incidencia_codigo=0;
            $ssql=" select CA_INCIDENCIA_AREA.AREA_CODIGO,areas.Area_Descripcion ";
                $ssql.=" from CA_INCIDENCIA_AREA ";
                    $ssql.=" inner join areas on CA_INCIDENCIA_AREA.AREA_CODIGO = areas.Area_Codigo ";
                $ssql.=" where CA_INCIDENCIA_AREA.incidencia_codigo =  ".$incidencia_codigo." ";
                    $ssql.=" order by CA_INCIDENCIA_AREA.FECHA_REGISTRO asc ";
            $ii=0;
            
            $resul = $lib->consultar_datos_sql($ssql);
            if (count($resul)>0){
                foreach($resul as $rs){
                    echo "<option value =" . $rs[0] . " >" . $rs[1] . "</option>/n";
                    if($ii==0){
                        $codarea=$rs[0];
                    }else{
                        $codarea=$codarea."¬".$rs[0];
                    }
                    $ii++;
                }
            }
            
        ?>
        </select>
    </td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Alcance</td>
        <td class='DataTD' colspan="3">
		<input type="radio" name="incidencia_hh_dd" id="incidencia_hh" value="1" <?php if ($incidencia_hh_dd=="1") echo "Checked"; ?>>Horario
		<input type="radio" name="incidencia_hh_dd" id="incidencia_dd" value="0" <?php if ($incidencia_hh_dd=="0") echo "Checked"; ?>>Diario
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Icono</td>
        <td class='DataTD' colspan="3">
		<Input  class='Input' type='text' name='incidencia_icono' id='incidencia_icono' value="<?php echo $incidencia_icono?>" maxlength='30' style='width:200px;' >
	</td>
</tr>
<tr>
    <td class='FieldCaptionTD' align='right'>Afecta Asistencia</td>
    <td class='DataTD'>
        <select name="Afecta_Asistencia" id="Afecta_Asistencia">
            <option value="N" <?php if($Afecta_Asistencia=="N") echo "selected"?>>NO</option>
            <option value="S" <?php if($Afecta_Asistencia=="S") echo "selected"?>>SI</option>
        </select>
    </td>
	<td class='FieldCaptionTD' align='right'>Signo</td>
        <td class='DataTD' >
        <select id="incidencia_signo" name="incidencia_signo" class='select'  style='width:90px'>
              <option value='1' <?php if($incidencia_signo==1) echo "selected" ?>>Ninguno</option>
              <option value='2' <?php if($incidencia_signo==2) echo "selected" ?>>-</option>
              <option value='3' <?php if($incidencia_signo==3) echo "selected" ?>>+</option>
        </select>

	</td>
</tr>
<tr>
    <td class='FieldCaptionTD' align='right'>Tipo Incidencia</td>
    <td class='DataTD' colspan="3">
    <?php
		$sSql = "select tipo_codigo, tipo_descripcion";
		$sSql = $sSql . " from ca_tipo_incidencia ";
		$sSql = $sSql . " where tipo_estado = 1 order by 2";

		$combo->query = $sSql;
		$combo->name = "tipo_codigo";
		$combo->value = $tipo_codigo."";
		$combo->more = "class=input style='width:300px;'";
		$rpta = $combo->Construir();
		echo $rpta;
	?>    
    </td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Incidencia Manual(caso contrario es automatica)</td>
        <td class='DataTD' colspan="3">
		<Input class='Input' type='checkbox' name='Incidencia_manual' id='Incidencia_manual' value='1' <?php if ($Incidencia_manual=="1") echo "Checked"; ?>>
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Visible como Evento</td>
        <td class='DataTD' colspan="3">
                
		<input class='Input' type='checkbox' name='evento' id='evento' value='1' <?php if ($evento=="1") echo "Checked"; ?> onclick="javascript:habilita_ini_fin();"/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Supervisor Autoriza:&nbsp;&nbsp;
                Inicio&nbsp;<input type="checkbox" name="evento_inicio" id="evento_inicio" value="1" class="Input" <?php if($evento_inicio=="1") echo "checked"; if($evento=="0") echo "disabled"; ?> />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Fin&nbsp;<input type="checkbox" name="evento_fin" id="evento_fin" class="Input" <?php if($evento_fin=="1") echo " checked "; if($evento=="0") echo "disabled"; ?> value="1"/>
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Editable</td>
        <td class='DataTD' colspan="3">
		<input class='Input' type='checkbox' name='incidencia_editable' id='incidencia_editable' value='1' <?php if ($incidencia_editable=="1") echo "Checked"; ?>>
	</td>
</tr>

<tr>
	<td class='FieldCaptionTD' align='right'>Requiere Inicio y Fin</td>
        <td class='DataTD' colspan="3">
		<input class='Input' type='checkbox' name='Incidencia_Inicio_Fin' id='Incidencia_Inicio_Fin' value='1' <?php if ($Incidencia_Inicio_Fin=="1") echo "Checked"; ?>/>
	</td>
</tr>

<tr>
	<td class='FieldCaptionTD' align='right'>Requiere # de Ticket</td>
        <td class='DataTD' colspan="3">
		<input class='Input' type='checkbox' name='Incidencia_NroTicket' id='Incidencia_NroTicket' value='1' <?php if ($Incidencia_NroTicket=="1") echo "Checked"; ?>/>
	</td>
</tr>

<tr>
	<td class='FieldCaptionTD' align='right'>Activo</td>
        <td class='DataTD' colspan="3">
		<input class='Input' type='checkbox' name='incidencia_activo' id='incidencia_activo' value='1' <?php if ($incidencia_activo=="1") echo "Checked"; ?>>
	</td>
</tr>

<tr>
    <td class="FieldCaptionTD" align='right'>Visado</td>
    <td class='DataTD' colspan="3">
            
            &nbsp;Por Mando de Area: 
            <!--<Input class='Input' type='checkbox' name='chk_validable_mando' id='chk_validable_mando' <?php if($validable_mando=="1") echo "Checked"; ?> value='1' onclick="javascript:habilita_mando();">-->
            <input class='Input' type='checkbox' name='visado[]' id='chk_validable_mando' <?php if($validable_mando=="1") echo "Checked"; ?> value='validable_mando' />
            &nbsp;Por Gerente: 
            <!--<Input class='Input' type='checkbox' name='chk_validable_gerente' id='chk_validable_gerente' <?php if($validable_gerente=="1") echo "Checked"; ?> value='1' onclick="javascript:habilita_gerente();">-->
            
            <input class='Input' type='checkbox' name='visado[]' id='chk_validable_gerente' <?php if($validable_gerente=="1") echo "Checked"; ?> value='validable_gerente' />
            
            Por Area de Apoyo:
            <!--<Input class='Input' type='checkbox' name='chk_evento_validable' id='chk_evento_validable' <?php if($evento_validable=="1") echo "Checked"; ?> value='1' onclick="javascript:habilita();">-->
            <input class='Input' type='checkbox' name='visado[]' id='chk_evento_validable' <?php if($evento_validable=="1") echo "Checked"; ?> value='validable'/>
	</td>
</tr>
<!--eventos validables-->
<tr>
	<td class='FieldCaptionTD' align='right'>Plazo</td>
        <td class='DataTD' colspan="3">
            <?php
                $aCodigo="1¬2¬3¬4¬5¬6¬7¬8¬9¬10¬11¬12¬13¬14¬15¬16¬17¬18¬19¬20¬21¬22¬23¬24";
                $aDescripcion="01¬02¬03¬04¬05¬06¬07¬08¬09¬10¬11¬12¬13¬14¬15¬16¬17¬18¬19¬20¬21¬22¬23¬24";
                $combo->name = "Cmb_Plazo_Horas";
                $combo->value = $evento_horas_vbo."";
                
                //if($evento_horas_vbo=="") $habilitado = "disabled";
                //else $habilitado = "";
                
                //$combo->more = "class=select style='width:120px;' ".$habilitado;
                $combo->more = "class=select style='width:120px;' ";
                $rpta=$combo->Construir_Array($aCodigo,$aDescripcion);
                echo $rpta;
            ?>
            &nbsp;Dias&nbsp;&nbsp;&nbsp;
            <b>Silencio Administrativo:</b>&nbsp;&nbsp;Positivo&nbsp;
            <input type="radio" name="rbtn_silencio" id="rbtn_silencio" value="1" <?php if($silencio=="1") echo "checked";?>/>&nbsp;&nbsp;
            Negativo:&nbsp;<input type="radio" name="rbtn_silencio" id="rbtn_silencio" value="0" <?php if($silencio=="0") echo "checked";?>/>
	</td>
</tr>

<tr>
    <td class='FieldCaptionTD' align='right'>Sustituido por</td>
    <td class='DataTD' colspan="3">
        <?php
            $ssql=" select incidencia_codigo,incidencia_descripcion ";
            $ssql.=" from ca_incidencias where incidencia_activo = 1 and validable = 1 and incidencia_manual = 1 ";
            $combo->query = $ssql;
            $combo->name="cmb_sustituido";
            $combo->value=$sustituido."";
            //$habilitado=$sustituido=="" ? "disabled" : "";
            $combo->more = "class=select style='width:480px;' ";
            $rpta = $combo->Construir();
            echo $rpta;
        ?>
    </td>
<tr>
<tr>
	<td colspan=4  class='FieldCaptionTD'>&nbsp;
</td>
</tr>
<tr align='center'>
	<td colspan=4  class='FieldCaptionTD'>
		<input name='cmdGuardar' id='cmdGuardar' class=buttons type='submit' value='Aceptar'  style='width:80px'/>
		<input name='cmdCerrar' id='cmdCerrar' type='button' class=buttons value='Cerrar'   style='width:80px' onclick="cancelar();"/>
	</td>
</tr>
</table>
<input type="hidden" id="hddaccion" name="hddaccion" value=""/>
<input type="hidden" id="hddarea_codigo" name="hddarea_codigo" value="<?php echo $codarea;?>"/>
<input type="hidden" id="hddarea_codigo_des" name="hddarea_codigo_des" value=""/>
<input type="hidden" id="hddruta" name="hddruta" value=""/>
<input type="hidden" id="pagina" name="pagina" value="<?php echo $npag ?>"/>
<input type="hidden" id="buscam" name="buscam" value="<?php echo $buscam ?>"/>
<input type="hidden" id="orden" name="orden" value="<?php echo $orden ?>"/>
<input type="hidden" id="torder" name="torder" value="<?php echo $torder ?>"/>
</form>
</body>
</html>