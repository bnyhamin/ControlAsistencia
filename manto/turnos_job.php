<?php header("Expires: 0"); ?>
<?php

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../includes/clsCA_Turnos.php"); 
require_once("../../Includes/MyCombo.php");

$turno_codigo="";
$turno_descripcion="";
$turno_hora_inicio="-1";
$turno_minuto_inicio="-1";
$turno_hora_fin="-1";
$turno_minuto_fin="-1";
$turno_modalidad="0";
$turno_duo="0";
$turno_activo="0";
$turno_tolerancia='0';
$tipo_area_codigo="1";
$turno_refrigerio="0";
$turno_descanzo="0";
$turno_id="";
$turno_descanso2="0";
$turno_hora_refrigerio="-1";
$turno_minuto_refrigerio="-1";
$empleado_codigo = $_SESSION["empleado_codigo"];
$Dia1="1";
$Dia2="1";
$Dia3="1";
$Dia4="1";
$Dia5="1";
$Dia6="1";
$Dia7="1";

$npag = $_GET["pagina"];
$buscam = $_GET["buscam"];
$orden = $_GET["orden"];
if(isset($_GET["torder"])){
    $torder = $_GET["torder"];
}

$mensaje = "";

if (isset($_POST["turno_codigo"])) $turno_codigo = $_POST["turno_codigo"];
if (isset($_GET["turno_codigo"])) $turno_codigo = $_GET["turno_codigo"];

if (isset($_POST["turno_descripcion"])) $turno_descripcion = $_POST["turno_descripcion"];
if (isset($_POST["turno_hora_inicio"])) $turno_hora_inicio = $_POST["turno_hora_inicio"];
if (isset($_POST["turno_minuto_inicio"])) $turno_minuto_inicio = $_POST["turno_minuto_inicio"];
if (isset($_POST["turno_hora_fin"])) $turno_hora_fin = $_POST["turno_hora_fin"];
if (isset($_POST["turno_minuto_fin"])) $turno_minuto_fin = $_POST["turno_minuto_fin"];
if (isset($_POST["turno_refrigerio"])) $turno_refrigerio = $_POST["turno_refrigerio"];
if (isset($_POST["turno_descanzo"])) $turno_descanzo = $_POST["turno_descanzo"];
if (isset($_POST["turno_tolerancia"])) $turno_tolerancia = $_POST["turno_tolerancia"];
if (isset($_POST["turno_duo"])) $turno_duo= $_POST["turno_duo"];
if (isset($_POST["tipo_area_codigo"])) $tipo_area_codigo= $_POST["tipo_area_codigo"];
if (isset($_POST["turno_id"])) $turno_id= $_POST["turno_id"];
if (isset($_POST["turno_descanso2"])) $turno_descanso2 = $_POST["turno_descanso2"];
if (isset($_POST["turno_hora_refrigerio"])) $turno_hora_refrigerio = $_POST["turno_hora_refrigerio"];
if (isset($_POST["turno_minuto_refrigerio"])) $turno_minuto_refrigerio = $_POST["turno_minuto_refrigerio"];
//if (isset($_POST["turno_horario"])) $turno_horario= $_POST["turno_horario"];

if (isset($_POST["turno_activo"])) $turno_activo = $_POST["turno_activo"];
if (isset($_POST["Dia1"])) $Dia1 = $_POST["Dia1"];
if (isset($_POST["Dia2"])) $Dia2 = $_POST["Dia2"];
if (isset($_POST["Dia3"])) $Dia3 = $_POST["Dia3"];
if (isset($_POST["Dia4"])) $Dia4 = $_POST["Dia4"];
if (isset($_POST["Dia5"])) $Dia5 = $_POST["Dia5"];
if (isset($_POST["Dia6"])) $Dia6 = $_POST["Dia6"];
if (isset($_POST["Dia7"])) $Dia7 = $_POST["Dia7"];

$o = new ca_turnos();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

$combo = new MyCombo();
$combo->setMyUrl(db_host());
$combo->setMyUser(db_user());
$combo->setMyPwd(db_pass());
$combo->setMyDBName(db_name());

if (isset($_POST["hddaccion"])){
    if ($_POST["hddaccion"]=='SVE'){
				
        $o->turno_codigo = $turno_codigo;
        $o->turno_descripcion = $turno_descripcion;
        $o->turno_hora_inicio=$turno_hora_inicio;
        $o->turno_minuto_inicio=$turno_minuto_inicio;
        $o->turno_hora_fin=$turno_hora_fin;
        $o->turno_minuto_fin=$turno_minuto_fin;
        $o->turno_duo = $turno_duo;
        $o->turno_activo = $turno_activo;
        $o->turno_refrigerio = $turno_refrigerio;
        //$o->turno_descanzo = $turno_descanzo==null?"0":$turno_descanzo;
        $o->turno_descanzo = $turno_descanzo;
        $o->turno_tolerancia=$turno_tolerancia;
        $o->tipo_area_codigo=$tipo_area_codigo;
        $o->empleado_codigo=$empleado_codigo;
        $o->turno_id=$turno_id;
        $o->turno_descanso2=$turno_descanso2;
        $o->turno_hora_refrigerio=$turno_hora_refrigerio;
        $o->turno_minuto_refrigerio=$turno_minuto_refrigerio;
        //$o->turno_modalidad=0;
        //$o->turno_horario=0;

        $o->Dia1 = $Dia1;
        $o->Dia2 = $Dia2;
        $o->Dia3 = $Dia3;
        $o->Dia4 = $Dia4;
        $o->Dia5 = $Dia5;
        $o->Dia6 = $Dia6;
        $o->Dia7 = $Dia7;
        //--* guardar registro
        if ($turno_codigo==''){
            $mensaje = $o->Addnew();
            $turno_codigo = $o->turno_codigo;
        }else{
            $mensaje = $o->Update();
        }
        
        if($mensaje=='OK'){
?>
    <script language='javascript'>
        self.location.href='main_turnos.php?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php if(isset($torder)){ echo $torder; }?>';
    </script>
<?php
        }else{
            echo $mensaje;
        }
    }
}
if ($turno_codigo!=""){
    //recuperar datos
    $o->turno_codigo = $turno_codigo;
    $mensaje = $o->Query();
    $turno_descripcion = $o->turno_descripcion;
    $turno_hora_inicio=$o->turno_hora_inicio;
    $turno_minuto_inicio=$o->turno_minuto_inicio;
    $turno_hora_fin=$o->turno_hora_fin;
    $turno_minuto_fin=$o->turno_minuto_fin;
    $turno_refrigerio=$o->turno_refrigerio;
    $turno_descanzo=$o->turno_descanzo;
    $turno_tolerancia=$o->turno_tolerancia;
    $turno_duo = $o->turno_duo;
    $turno_activo = $o->turno_activo;
    $tipo_area_codigo= $o->tipo_area_codigo;
    $turno_id= $o->turno_id;
    $turno_descanso2 = $o->turno_descanso2;
    $turno_hora_refrigerio = $o->turno_hora_refrigerio;
    $turno_minuto_refrigerio = $o->turno_minuto_refrigerio;
    //$turno_horario= $o->turno_horario;
    $Dia1 = $o->Dia1;
    $Dia2 = $o->Dia2;
    $Dia3 = $o->Dia3;
    $Dia4 = $o->Dia4;
    $Dia5 = $o->Dia5;
    $Dia6 = $o->Dia6;
    $Dia7 = $o->Dia7;
	
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Registro de Turno</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
<script language='javascript'>
function ok(){
    if (validarCampo('frm','turno_descripcion')!=true) return false;
	if(document.frm.turno_id.value==''){
	  alert('Indique  valor');
	  document.frm.turno_id.focus();
	  return false;
	}
	if(document.frm.turno_hora_inicio.value==-1){
	  alert('Indique  valor');
	  document.frm.turno_hora_inicio.focus();
	  return false;
	}
	if(document.frm.turno_minuto_inicio.value==-1){
	  alert('Indique  valor');
	  document.frm.turno_minuto_inicio.focus();
	  return false;
	}
	if(document.frm.turno_hora_fin.value==-1){
	  alert('Indique  valor');
	  document.frm.turno_hora_fin.focus();
	  return false;
	}
	if(document.frm.turno_minuto_fin.value==-1){
	  alert('Indique  valor');
	  document.frm.turno_minuto_fin.focus();
	  return false;
	}	
	if(document.frm.turno_refrigerio.value==''){
	  alert('Indique  valor');
	  document.frm.turno_refrigerio.focus();
	  return false;
	}
	if(document.frm.turno_descanzo.value==''){
	  alert('Indique  valor');
	  document.frm.turno_descanzo.focus();
	  return false;
	}
	if(document.frm.turno_descanso2.value==''){
	  alert('Indique  valor');
	  document.frm.turno_descanso2.focus();
	  return false;
	}
	if(document.frm.turno_tolerancia.value==''){
	  alert('Indique  valor');
	  document.frm.turno_tolerancia.focus();
	  return false;
	}
	
     if(document.frm.turno_hora_inicio.value*1 > document.frm.turno_hora_fin.value*1){
	   document.frm.turno_duo.checked=true;
	 }else{
	   document.frm.turno_duo.checked=false;	
	 }
		
	if (confirm('confirme guardar los datos')== false){
		return false;
	}
	document.frm.action += "?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php if(isset($torder)){ echo $torder; } ?>";
	document.frm.hddaccion.value="SVE";
	return true;
}
function cancelar(){
	self.location.href = "main_turnos.php?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php if(isset($torder)){ echo $torder; }?>";
}
function validarHoras(){
if(document.frm.turno_duo.checked){	
		if(document.frm.turno_hora_inicio.value==-1){
		  alert('Indique  valor');
		  document.frm.turno_duo.checked=false;
		  document.frm.turno_hora_inicio.focus();
		  return false;
		}
		if(document.frm.turno_minuto_inicio.value==-1){
		  alert('Indique  valor');
		  document.frm.turno_duo.checked=false;
		  document.frm.turno_minuto_inicio.focus();
		  return false;
		}
		if(document.frm.turno_hora_fin.value==-1){
		  alert('Indique  valor');
		  document.frm.turno_duo.checked=false;
		  document.frm.turno_hora_fin.focus();
		  return false;
		}
		if(document.frm.turno_minuto_fin.value==-1){
		  alert('Indique  valor');
		  document.frm.turno_duo.checked=false;
		  document.frm.turno_minuto_fin.focus();
		  return false;
		}
	    if(document.frm.turno_hora_inicio.value*1<=document.frm.turno_hora_fin.value*1){
			alert('No es un turno duo');
			document.frm.turno_duo.checked=false;
			return false;
		}
 }else{
 	  if(document.frm.turno_hora_inicio.value*1>document.frm.turno_hora_fin.value*1){
			alert('Es un turno duo');
			document.frm.turno_duo.checked=true;
			return false;
		}
 	
 	
	 }							
}


var hora_inicio = "";
var minuto_inicio = "";
var hora_fin = "";
var minuto_fin = "";
var turno_refrigerio = "";



function inicia_valores_descripcion(){
    hora_inicio = '<?php echo $turno_hora_inicio;?>'
    if(hora_inicio.length == 1) hora_inicio = "0" + hora_inicio;
    hora_inicio = "De " + hora_inicio + ":";
    
    minuto_inicio = '<?php echo $turno_minuto_inicio;?>'
    if(minuto_inicio.length == 1) minuto_inicio = "0" + minuto_inicio;
    
    hora_fin = '<?php echo $turno_hora_fin;?>'
    if(hora_fin.length == 1) hora_fin = "0" + hora_fin;
    hora_fin = " a " + hora_fin + ":";
    
    minuto_fin = '<?php echo $turno_minuto_fin;?>'
    if(minuto_fin.length == 1) minuto_fin = "0" + minuto_fin;
    
    turno_refrigerio = '<?php echo $turno_refrigerio;?>'
    turno_refrigerio = " R" + turno_refrigerio;
    
}

function actualiza_descripcion(valor, campo){
    
    if(valor.length == 1 && campo != 'turno_refrigerio') valor = "0" + valor;
    
    if(campo == 'hora_inicio'){
        hora_inicio = "De " + valor + ":";
    }
    if(campo == 'minuto_inicio'){
        minuto_inicio = valor;
    }
    if(campo == 'hora_fin'){
        hora_fin = " a " + valor + ":";
    }
    
    if(campo == 'minuto_fin'){
        turno_refrigerio = " R" + document.frm.turno_refrigerio.value;
        minuto_fin = valor;
    }
    
    if(campo == 'turno_refrigerio'){
        turno_refrigerio = " R" + valor
    }
    
    document.frm.turno_descripcion.value =   hora_inicio + minuto_inicio + hora_fin + minuto_fin + turno_refrigerio
}
</script>

</head>


<body Class='PageBODY'>

<?php
if ($mensaje!="OK") echo getMensaje($mensaje);
?>
<center class=FormHeaderFont>Registro de Turno</center>
<form name='frm' id='frm' action='<?php echo $_SERVER["PHP_SELF"] ?>' method='post' onSubmit='javascript:return ok();'>
<table  class='FormTable' width='55%' align='center' cellspacing='0' cellpadding='0' border='1'>
<tr>
	<td class='FieldCaptionTD' align='right'>Código&nbsp;</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='turno_codigo' id='turno_codigo' value="<?php echo $turno_codigo ?>" maxlength='0' style='width:80px;' <?php echo  lectura("D")?>>
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Codigo Turno&nbsp;</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='turno_id' id='turno_id' value="<?php echo $turno_id?>" maxlength='10' style='width:80px;'>
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Descripcion&nbsp;</td>
	<td class='DataTD'>
		<input  class='Input' type='text' name='turno_descripcion' readonly="readonly" id='turno_descripcion' value="<?php echo $turno_descripcion?>" maxlength='80' style='width:300px;' />
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Tipo</td>
	<td class='DataTD'>
		<input type="radio" name="tipo_area_codigo" id="tipo_operativo" value="1" <?php if ($tipo_area_codigo=="1") echo "Checked"; ?>>Operativo
		<input type="radio" name="tipo_area_codigo" id="tipo_administrativo" value="0" <?php if ($tipo_area_codigo=="0") echo "Checked"; ?>>Administrativo
	</td>
</tr>
<!--<TR>
		<TD class='FieldCaptionTD' width='30%' align=right>
		Modalidad&nbsp;
		</TD>
		<TD class='DataTD' align=left >
		  <?php
			/*$sql=" SELECT  item_codigo as codigo,item_descripcion as descripcion FROM Items ";
            $sql .=" WHERE  (Tabla_Codigo = 7) and item_activo=1";
            $sql .=" order by 2 asc";
			$combo->query = $sql;
			$combo->name = "turno_modalidad"; 
			$combo->value = $turno_modalidad ."";
			$combo->more = "class='Select' style='width:300px' ";
			$rpta = $combo->Construir();
			echo $rpta;*/

		  ?>
		</TD>
</TR>
<TR>
		<TD class='FieldCaptionTD' width='30%' align=right>
		Horario&nbsp;
		</TD>
		<TD class='DataTD' align=left >
		  <?php
			/*
			$sql=" SELECT  item_codigo as codigo,item_descripcion as descripcion FROM Items ";
            $sql .=" WHERE  (Tabla_Codigo = 10) and item_activo=1";
            $sql .=" order by 2 asc";
			$combo->query = $sql;
			$combo->name = "turno_horario"; 
			$combo->value = $turno_horario ."";
			$combo->more = "class='Select' style='width:300px' ";
			$rpta = $combo->Construir();
			echo $rpta;
             */
		  ?>
		</TD>
</TR>-->
<tr>
	<td class='FieldCaptionTD' align='right'>Inicio&nbsp;</td>
	<td class='DataTD'>
		Horas&nbsp;<select  class='select' name='turno_hora_inicio' id='turno_hora_inicio' onchange="actualiza_descripcion(this.value, 'hora_inicio')" >
		  <option value="-1">hh</option>
		  <?php
		   for($h=0; $h < 24; $h++){
		     $hh=$h;
             if(strlen($h)<=1) $hh="0".$h;
				 if ($h==$turno_hora_inicio) echo "\t<option value=". $h . " selected>". $hh ."</option>" . "\n";
			     else 
				 echo "\t<option value=". $h . ">". $hh ."</option>" . "\n";
		   }
		  ?>
		 </select>
		 &nbsp;:&nbsp;Minutos&nbsp;
		 <select  class='select' name='turno_minuto_inicio' id='turno_minuto_inicio' onchange="actualiza_descripcion(this.value, 'minuto_inicio')">
		  <option value="-1">mm</option>
		  <?php
		   for($m=0; $m < 60; $m++){
		     $mm=$m;
             if(strlen($m)<=1) $mm="0".$m;
		      if($m==$turno_minuto_inicio) echo "\t<option value=". $m ." selected>". $mm ."</option>" . "\n";
			  else echo "\t<option value=". $m . ">". $mm ."</option>" . "\n";
		   }
		  ?>
		 </select> 
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Fin&nbsp;</td>
	<td class='DataTD'>
      Horas&nbsp;<select  class='select' name='turno_hora_fin' id='turno_hora_fin' onchange="actualiza_descripcion(this.value, 'hora_fin')">
		  <option value="-1">hh</option>
		  <?php
		   for($h=0; $h < 24; $h++){
		     $hh=$h;
             if(strlen($h)<=1) $hh="0".$h;
				 if ($h==$turno_hora_fin) echo "\t<option value=". $h . " selected>". $hh ."</option>" . "\n";
			     else echo "\t<option value=". $h . ">". $hh ."</option>" . "\n";
		   }
		  ?>
		 </select>
		 &nbsp;:&nbsp;Minutos&nbsp;
		 <select  class='select' name='turno_minuto_fin' id='turno_minuto_fin' onchange="actualiza_descripcion(this.value, 'minuto_fin')">
		  <option value="-1">mm</option>
		  <?php
		   for($m=0; $m < 60; $m++){
		     $mm=$m;
             if(strlen($m)<=1) $mm="0".$m;
		      if($m==$turno_minuto_fin) echo "\t<option value=". $m ." selected>". $mm ."</option>" . "\n";
			  else echo "\t<option value=". $m . ">". $mm ."</option>" . "\n";
		   }
		  ?>
		 </select> 
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Refrigerio&nbsp;</td>
	<td class='DataTD'>
		<input class='Input' type='text' name='turno_refrigerio' id='turno_refrigerio' maxlength='3' 
            value='<?php echo $turno_refrigerio ?>' style="width:35px" onkeypress='return esnumero()' onchange="actualiza_descripcion(this.value, 'turno_refrigerio')" />
	    minutos
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Inicio Refrigrerio&nbsp;</td>
	<td class='DataTD'>
		Horas&nbsp;<select  class='select' name='turno_hora_refrigerio' id='turno_hora_refrigerio' >
		  <option value="-1">hh</option>
		  <?php
		   for($h=0; $h < 24; $h++){
		     $hh=$h;
             if(strlen($h)<=1) $hh="0".$h;
				 if ($h==$turno_hora_refrigerio) echo "\t<option value=".$h." selected>".$hh."</option>"."\n";
			     else 
				 echo "\t<option value=". $h . ">". $hh ."</option>" . "\n";
		   }
		  ?>
		 </select>
		 &nbsp;:&nbsp;Minutos&nbsp;
		 <select  class='select' name='turno_minuto_refrigerio' id='turno_minuto_refrigerio' >
		  <option value="-1">mm</option>
		  <?php
		   for($m=0; $m < 60; $m++){
		     $mm=$m;
             if(strlen($m)<=1) $mm="0".$m;
		      if($m==$turno_minuto_refrigerio) echo "\t<option value=". $m ." selected>". $mm ."</option>" . "\n";
			  else echo "\t<option value=". $m . ">". $mm ."</option>" . "\n";
		   }
		  ?>
		 </select> 
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Descanso 1&nbsp;</td>
	<td class='DataTD'>
		<Input class='Input' type='text' name='turno_descanzo' id='turno_descanzo' maxlength='2' value='<?php echo $turno_descanzo ?>' style="width='35px'" onKeyPress='return esnumero()'>
	    minutos
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Descanso 2&nbsp;</td>
	<td class='DataTD'>
		<Input class='Input' type='text' name='turno_descanso2' id='turno_descanso2' maxlength='2' value='<?php echo $turno_descanso2 ?>' style="width='35px'" onKeyPress='return esnumero()'>
	    minutos
	</td>
</tr>
<tr style="display:none">
	<td colspan="2" align='center'>
	<table width="75%" border="0" class="FormTABLE" cellpadding="0" cellspacing="0">
	<tr>
		<td class='FieldCaptionTD' align='center' colspan="4"><strong>Dias de la Semana</strong>&nbsp;</td>
	</tr>
	<tr>
		<td class='FieldCaptionTD' align='right'>Lunes&nbsp;</td>
		<td class='DataTD' align="center">
			<Input class='Input' type='checkbox' name='Dia1' id='Dia1' value='1' <?php if ($Dia1=="1") echo "Checked"; ?>>
		</td>
		<td class='FieldCaptionTD' align='right'>Martes&nbsp;</td>
		<td class='DataTD' align="center">
			<Input class='Input' type='checkbox' name='Dia2' id='Dia2' value='1' <?php if ($Dia2=="1") echo "Checked"; ?>>
		</td>
	</tr>
	<tr>
		<td class='FieldCaptionTD' align='right'>Miercoles&nbsp;</td>
		<td class='DataTD' align="center">
			<Input class='Input' type='checkbox' name='Dia3' id='Dia3' value='1' <?php if ($Dia3=="1") echo "Checked"; ?>>
		</td>
		<td class='FieldCaptionTD' align='right'>Jueves&nbsp;</td>
		<td class='DataTD' align="center">
			<Input class='Input' type='checkbox' name='Dia4' id='Dia4' value='1' <?php if ($Dia4=="1") echo "Checked"; ?>>
		</td>
	</tr>
	<tr>
		<td class='FieldCaptionTD' align='right'>Viernes&nbsp;</td>
		<td class='DataTD' align="center">
			<Input class='Input' type='checkbox' name='Dia5' id='Dia5' value='1' <?php if ($Dia5=="1") echo "Checked"; ?>>
		</td>
		<td class='FieldCaptionTD' align='right'>Sabado&nbsp;</td>
		<td class='DataTD' align="center">
			<Input class='Input' type='checkbox' name='Dia6' id='Dia6' value='1' <?php if ($Dia6=="1") echo "Checked"; ?>>
		</td>
	</tr>
	<tr>
		<td class='FieldCaptionTD' align='right'>Domingo&nbsp;</td>
		<td class='DataTD' align="center">
			<Input class='Input' type='checkbox' name='Dia7' id='Dia7' value='1' <?php if ($Dia7=="1") echo "Checked"; ?>>
		</td>
		<td colspan="2" class="DataTD">&nbsp;</td>
	</tr>
</table>
</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Tolerancia&nbsp;</td>
	<td class='DataTD'>
		<Input class='Input' type='text' name='turno_tolerancia' id='turno_tolernacia' value='<?php echo $turno_tolerancia ?>' style="width='35px'" onKeyPress='return esnumero()'>
	    minutos
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Duo?&nbsp;</td>
	<td class='DataTD'>
		<Input class='Input' type='checkbox' name='turno_duo' id='turno_duo' value='1' <?php if ($turno_duo=="1") echo "Checked"; ?> onclick='validarHoras()'>
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Activo&nbsp;</td>
	<td class='DataTD'>
		<Input class='Input' type='checkbox' name='turno_activo' id='turno_activo' value='1' <?php if ($turno_activo=="1") echo "Checked"; ?> >
	</td>
</tr>
<tr>
	<td colspan=2  class='FieldCaptionTD'>&nbsp;
</td>
</tr>
<tr align='center'>
	<td colspan=2  class='FieldCaptionTD'>
		<input name='cmdGuardar' id='cmdGuardar' type='submit' value='Aceptar'  class='Button' style='width:80px'>
		<input name='cmdCerrar' id='cmdCerrar' type='button' value='Cerrar'  class='Button' style='width:80px' onclick="cancelar();">
	</td>
</tr>
</table>
<input type="hidden" id="hddaccion" name="hddaccion" value="">
</form>
<?php
if($turno_codigo != ""){
    ?>
    <script>
        inicia_valores_descripcion();
    </script>
    <?php    
}
?>

</body>
</html>