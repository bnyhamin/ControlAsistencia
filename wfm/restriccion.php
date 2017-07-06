<?php header("Expires: 0"); ?>
<?php
session_start();
//echo session_start();
if (!isset($_SESSION["empleado_codigo"])){
?>	 <script language="JavaScript">
    alert("Su sesión a caducado!!, debe volver a registrarse.");
    document.location.href = "../login.php";
  </script>
	<?php
	exit;
}

require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/clswfm_restriccion.php"); 
require_once("../../Includes/MyCombo.php"); 
//require_once("../includes/clsCA_Areas.php"); 

$restriccion_codigo="";
$restriccion_descripcion="";
$restriccion_hh_dd="0";
$restriccion_activo="0";
$usuario_id='0';
$rc_codigo="";

$torder="";

$hora_inicio="-1";
$minuto_inicio="-1";
$hora_fin="-1";
$minuto_fin="-1";

$usuario_id = $_SESSION["empleado_codigo"];
$mensaje = "";

if (isset($_POST["pagina"])) $npag = $_POST["pagina"];
if (isset($_POST["orden"])) $orden = $_POST["orden"];
if (isset($_POST["buscam"])) $buscam = $_POST["buscam"];
if (isset($_POST["cboTOrden"])) $torder = $_POST["cboTOrden"];

if (isset($_GET["pagina"])) $npag = $_GET["pagina"];
if (isset($_GET["orden"])) $orden = $_GET["orden"];
if (isset($_GET["buscam"])) $buscam = $_GET["buscam"];
if (isset($_GET["cboTOrden"])) $torder = $_GET["cboTOrden"];


if (isset($_POST["restriccion_codigo"])) $restriccion_codigo = $_POST["restriccion_codigo"];
if (isset($_GET["restriccion_codigo"])) $restriccion_codigo = $_GET["restriccion_codigo"];

if (isset($_POST["rc_codigo"])) $rc_codigo = $_POST["rc_codigo"];
if (isset($_POST["restriccion_descripcion"])) $restriccion_descripcion = $_POST["restriccion_descripcion"];

if (isset($_POST["hora_inicio"])) $hora_inicio = $_POST["hora_inicio"];
if (isset($_POST["minuto_inicio"])) $minuto_inicio = $_POST["minuto_inicio"];
if (isset($_POST["hora_fin"])) $hora_fin = $_POST["hora_fin"];
if (isset($_POST["minuto_fin"])) $minuto_fin = $_POST["minuto_fin"];
if (isset($_POST["minutos"])) $minutos = $_POST["minutos"];
if (isset($_POST["restriccion_signo"])) $restriccion_signo = $_POST["restriccion_signo"];
if (isset($_POST["restriccion_es"])) $restriccion_es = $_POST["restriccion_es"];

if (isset($_POST["restriccion_hh_dd"])) $restriccion_hh_dd = $_POST["restriccion_hh_dd"];
if (isset($_POST["restriccion_hh_tt"])) $restriccion_hh_tt = $_POST["restriccion_hh_tt"];
if (isset($_POST["restriccion_activo"])) $restriccion_activo = $_POST["restriccion_activo"];


$o = new wfm_restriccion();
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
				
        $o->restriccion_codigo = $restriccion_codigo;
        $o->rc_codigo = $rc_codigo;
        $o->restriccion_descripcion = $restriccion_descripcion;
        $o->restriccion_hh_dd = $restriccion_hh_dd;
        if(isset($restriccion_hh_tt)){
            $o->restriccion_hh_tt = $restriccion_hh_tt;
        }
        
        $o->hora_inicio = $hora_inicio;
        $o->hora_fin=$hora_fin;
        $o->minuto_inicio=$minuto_inicio;
        $o->minuto_fin=$minuto_fin;
        if(isset($minutos)){
            $o->minutos=$minutos;
        }
        
        $o->usuario_registra=$usuario_id;
        $o->restriccion_activo = $restriccion_activo;
        $o->restriccion_signo = $restriccion_signo;
        $o->restriccion_es = $restriccion_es;

        //--* guardar registro
        if ($restriccion_codigo==''){
                $mensaje = $o->Addnew();
                $restriccion_codigo = $o->restriccion_codigo;
        }else{
                $mensaje = $o->Update();

        }
        if($mensaje=='OK'){
        ?><script language='javascript'>
           self.location.href='main_restricciones.php?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>';
          </script>
        <?php
        }
    }
}

if ($restriccion_codigo!=""){
	//recuperar datos
	$o->restriccion_codigo = $restriccion_codigo;
	$mensaje = $o->Query();
	$rc_codigo = $o->rc_codigo;
	$restriccion_descripcion = $o->restriccion_descripcion;
	$restriccion_hh_dd = $o->restriccion_hh_dd;
	$restriccion_hh_tt = $o->restriccion_hh_tt;
	$hora_inicio=$o->hora_inicio;
	$hora_fin=$o->hora_fin;
	$minuto_inicio=$o->minuto_inicio;
	$minuto_fin=$o->minuto_fin;
	$minutos=$o->minutos;
	$restriccion_signo=$o->restriccion_signo;
	$restriccion_es=$o->restriccion_es;
	$restriccion_activo = $o->restriccion_activo;

}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Registro de Restricciones</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>

<script language='javascript'>

function ok(){
    if (validarCampo('frm','restriccion_descripcion')!=true) return false;
    if (document.frm.restriccion_hh_dd1.checked == false && document.frm.restriccion_hh_dd2.checked == false){
    	alert ('Ingrese el alcance');
    	return false;
    } 
    if (document.frm.rc_codigo.value == 0 ){
    	alert ('Ingrese la clase');
    	document.frm.rc_codigo.focus();
   	 	return false;
    }
    if (document.frm.restriccion_hh_dd1.checked == true){
    	
		if (document.frm.restriccion_hh_tt1.checked == false && document.frm.restriccion_hh_tt2.checked == false){
    	alert ('Ingrese el rango');
    	return false;
    	}
    	
    } 
    
    if (document.frm.restriccion_hh_tt1.checked == true){
    	
    	if (document.frm.hora_inicio.value== -1){
    		alert ('Ingrese valor');
    		document.frm.hora_inicio.focus();
    		return false;
    	}
    	
   		if (document.frm.minuto_inicio.value== -1){
    		alert ('Ingrese valor');
    		document.frm.minuto_inicio.focus();
    		return false;
    	}
    	
   		if (document.frm.hora_fin.value== -1){
    		alert ('Ingrese valor');
    		document.frm.hora_fin.focus();
    		return false;
    	}
    	
   		if (document.frm.minuto_fin.value== -1){
    		alert ('Ingrese valor');
    		document.frm.minuto_fin.focus();
    		return false;
    	}
    }
    
    if (document.frm.restriccion_hh_tt2.checked == true){
    	
   		if (document.frm.minutos.value== ''){
    		alert ('Ingrese valor');
    		document.frm.minutos.focus();
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
	self.location.href = "main_restricciones.php?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
}

function Block(esto,id1, id2, id3 ,id4, id5, id6, id7)
   {
    if(esto.checked==false)
     {
		id1=document.getElementById(id1);
		id1.disabled=false;
		
		id2=document.getElementById(id2);
		id2.disabled=false;
		
		id3=document.getElementById(id3);
		id3.disabled=false;
		
		id4=document.getElementById(id4);
		id4.disabled=false;
		
		id5=document.getElementById(id5);
		id5.disabled=false;
		
		id6=document.getElementById(id6);
		id6.disabled=false;
		
		id7=document.getElementById(id7);
		id7.disabled=false;
			
	 }	
	else
	 {  //document.frm.mr_codigo.value=0;	
		id1=document.getElementById(id1);
		id1.disabled=true;
		
		id2=document.getElementById(id2);
		id2.disabled=true;
		
		id3=document.getElementById(id3);
		id3.disabled=true;
		
		id4=document.getElementById(id4);
		id4.disabled=true;	
		
		id5=document.getElementById(id5);
		id5.disabled=true;
		
		id6=document.getElementById(id6);
		id6.disabled=true;
		
		id7=document.getElementById(id7);
		id7.disabled=true;			
	 }
   }
   
   function Block1(esto,id1, id2 , id3 , id4 , id5 , id6, id7)
   {
    if(esto.checked==true)
     {
		id1=document.getElementById(id1);
		id1.disabled=false;
		
		id2=document.getElementById(id2);
		id2.disabled=false;
		
		id3=document.getElementById(id3);
		id3.disabled=false;
		
		id4=document.getElementById(id4);
		id4.disabled=false;
		
		id5=document.getElementById(id5);
		id5.disabled=false;
		
		id6=document.getElementById(id6);
		id6.disabled=false;
		
		id7=document.getElementById(id7);
		id7.disabled=false;
				
	 }	
	else
	 {
		id1=document.getElementById(id1);
		id1.disabled=true;
		
		id2=document.getElementById(id2);
		id2.disabled=true;
		
		id3=document.getElementById(id3);
		id3.disabled=true;
		
		id4=document.getElementById(id4);
		id4.disabled=true;	
		
		id5=document.getElementById(id5);
		id4.disabled=true;
		
		id6=document.getElementById(id6);
		id6.disabled=true;
		
		id7=document.getElementById(id7);
		id7.disabled=true;			
	 }
   }
   
   function Block2(esto,id1, id2, id3 ,id4 , id5)
   {
    if(esto.checked==false)
     {
		id1=document.getElementById(id1);
		id1.disabled=false;
		
		id2=document.getElementById(id2);
		id2.disabled=false;
		
		id3=document.getElementById(id3);
		id3.disabled=false;
		
		id4=document.getElementById(id4);
		id4.disabled=false;
		
		id5=document.getElementById(id5);
		id5.disabled=true;
		
					
	 }	
	else
	 {  //document.frm.mr_codigo.value=0;	
		id1=document.getElementById(id1);
		id1.disabled=true;
		
		id2=document.getElementById(id2);
		id2.disabled=true;
		
		id3=document.getElementById(id3);
		id3.disabled=true;
		
		id4=document.getElementById(id4);
		id4.disabled=true;	
		
		id5=document.getElementById(id5);
		id5.disabled=false;	
		
			
	 }
   }
   
   function Block3(esto,id1, id2, id3 ,id4 , id5)
   {
    if(esto.checked==false)
     {
		id1=document.getElementById(id1);
		id1.disabled=true;
		
		id2=document.getElementById(id2);
		id2.disabled=true;
		
		id3=document.getElementById(id3);
		id3.disabled=true;
		
		id4=document.getElementById(id4);
		id4.disabled=true;
		
		id5=document.getElementById(id5);
		id5.disabled=false;
		
					
	 }	
	else
	 {  //document.frm.mr_codigo.value=0;	
		id1=document.getElementById(id1);
		id1.disabled=false;
		
		id2=document.getElementById(id2);
		id2.disabled=false;
		
		id3=document.getElementById(id3);
		id3.disabled=false;
		
		id4=document.getElementById(id4);
		id4.disabled=false;	
		
		id5=document.getElementById(id5);
		id5.disabled=true;	
		
			
	 }
   }
   
</script>

</head>


<body Class='PageBODY'>

<?php
if ($mensaje!="OK") echo getMensaje($mensaje);
?>
<center class=FormHeaderFont>Registro de Restricciones</center>
<form name='frm' id='frm' action='<?php echo $PHP_SELF ?>' method='post' onSubmit='javascript:return ok();'>
<table  class='FormTable' width='55%' align='center' cellspacing='0' cellpadding='0' border='1'>
<tr>
	<td class='FieldCaptionTD' align='right'>Código</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='restriccion_codigo' id='restriccion_codigo' value="<?php echo $restriccion_codigo ?>" maxlength='0' style='width:80px;' <?php echo  lectura("D")?>>
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Descripcion</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='restriccion_descripcion' id='restriccion_descripcion' value="<?php echo $restriccion_descripcion?>" maxlength='80' style='width:300px;' >
	</td>
</tr>

<tr>
	<td class='FieldCaptionTD' align='right'>Alcance</td>
	<td class='DataTD'>
		<input type="radio" name="restriccion_hh_dd" id="restriccion_hh_dd1" value="1" onclick="Block1(this,'restriccion_hh_tt1' , 'restriccion_hh_tt2' , 'hora_inicio', 'minuto_inicio','hora_fin','minuto_fin' , 'minutos')" <?php if ($restriccion_hh_dd=="1") echo "Checked"; ?>>Horario
		<input type="radio" name="restriccion_hh_dd" id="restriccion_hh_dd2" value="0" onclick="Block(this, 'restriccion_hh_tt1' , 'restriccion_hh_tt2' , 'hora_inicio', 'minuto_inicio','hora_fin','minuto_fin' , 'minutos')" <?php if ($restriccion_hh_dd=="0") echo "Checked"; ?>>Diario
	</td>
</tr>
<tr>
<td class="FieldCaptionTD" align="center">Clase</td>
<td class="DataTD">
	<?php 
			$sql ="select rc_codigo as codigo, rc_descripcion";
		    $sql .=" from wfm_restriccion_clase ";
		    $sql .=" where  rc_activo=1 " ;                   
			$sql .=" order by 2 asc"; 
			$combo->query = $sql;
			$combo->name = "rc_codigo"; 
			$combo->value = $rc_codigo."";
			$combo->more = " class='select' ";
			$rpta = $combo->Construir();
			echo $rpta;
			?>
</td>
</tr>

<tr>
	<td class='FieldCaptionTD' align='right'>Rango</td>
	<td class='DataTD'>
		<input type="radio" name="restriccion_hh_tt" id="restriccion_hh_tt1" value="1" onclick="Block3(this,'hora_inicio', 'minuto_inicio','hora_fin','minuto_fin' , 'minutos')" <?php if(isset($restriccion_hh_tt)){ if ($restriccion_hh_tt=="1") echo "Checked"; }?>>Intervalo
		<input type="radio" name="restriccion_hh_tt" id="restriccion_hh_tt2" value="0" onclick="Block2(this,'hora_inicio', 'minuto_inicio','hora_fin','minuto_fin' , 'minutos')" <?php if(isset($restriccion_hh_tt)){ if ($restriccion_hh_tt=="0") echo "Checked"; }?>>Tiempo
	</td>
</tr>


<tr>
	<td class='FieldCaptionTD' align='right'>Inicio&nbsp;</td>
	<td class='DataTD'>
		Horas&nbsp;<select  class='select' name='hora_inicio' id='hora_inicio' >
		  <option value="-1">hh</option>
		  <?php
		   for($h=0; $h < 24; $h++){
		     $hh=$h;
             if(strlen($h)<=1) $hh="0".$h;
				 if ($h==$hora_inicio) echo "\t<option value=". $h . " selected>". $hh ."</option>" . "\n";
			     else 
				 echo "\t<option value=". $h . ">". $hh ."</option>" . "\n";
		   }
		  ?>
		 </select>
		 &nbsp;:&nbsp;Minutos&nbsp;
		 <select  class='select' name='minuto_inicio' id='minuto_inicio' >
		  <option value="-1">mm</option>
		  <?php
		   for($m=0; $m < 60; $m++){
		     $mm=$m;
             if(strlen($m)<=1) $mm="0".$m;
		      if($m==$minuto_inicio) echo "\t<option value=". $m ." selected>". $mm ."</option>" . "\n";
			  else echo "\t<option value=". $m . ">". $mm ."</option>" . "\n";
		   }
		  ?>
		 </select> 
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Fin&nbsp;</td>
	<td class='DataTD'>
      Horas&nbsp;<select  class='select' name='hora_fin' id='hora_fin' >
		  <option value="-1">hh</option>
		  <?php
		   for($h=0; $h < 24; $h++){
		     $hh=$h;
             if(strlen($h)<=1) $hh="0".$h;
				 if ($h==$hora_fin) echo "\t<option value=". $h . " selected>". $hh ."</option>" . "\n";
			     else echo "\t<option value=". $h . ">". $hh ."</option>" . "\n";
		   }
		  ?>
		 </select>
		 &nbsp;:&nbsp;Minutos&nbsp;
		 <select  class='select' name='minuto_fin' id='minuto_fin' >
		  <option value="-1">mm</option>
		  <?php
		   for($m=0; $m < 60; $m++){
		     $mm=$m;
             if(strlen($m)<=1) $mm="0".$m;
		      if($m==$minuto_fin) echo "\t<option value=". $m ." selected>". $mm ."</option>" . "\n";
			  else echo "\t<option value=". $m . ">". $mm ."</option>" . "\n";
		   }
		  ?>
		 </select> 
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Minutos</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='minutos' id='minutos' value="<?php if(isset($minutos)){ echo $minutos; } ?>" maxlength='10' style='width:80px;' >
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Signo</td>
	<td class='DataTD'>
	 <select id="restriccion_signo" name="restriccion_signo" class='select'  style='width:90px'>
              <option value='1' <?php if(isset($restriccion_signo)) if($restriccion_signo==1) echo "selected" ?>>Ninguno</option>
              <option value='2' <?php if(isset($restriccion_signo)) if($restriccion_signo==2) echo "selected" ?>>-</option>
              <option value='3' <?php if(isset($restriccion_signo)) if($restriccion_signo==3) echo "selected" ?>>+</option>
         </select>

	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Entrada / Salida</td>
	<td class='DataTD'>
	 <select id="restriccion_es" name="restriccion_es" class='select'  style='width:90px'>
              <option value='1' <?php if(isset($restriccion_es)) if($restriccion_es==1) echo "selected" ?>>Ninguno</option>
              <option value='2' <?php if(isset($restriccion_es)) if($restriccion_es==2) echo "selected" ?>>Salida</option>
              <option value='3' <?php if(isset($restriccion_es)) if($restriccion_es==3) echo "selected" ?>>Entrada</option>
         </select>

	</td>
</tr>

<tr>
	<td class='FieldCaptionTD' align='right'>Activo</td>
	<td class='DataTD'>
		<Input class='Input' type='checkbox' name='restriccion_activo' id='restriccion_activo' value='1' <?php if ($restriccion_activo=="1") echo "Checked"; ?>>
	</td>
</tr>
<tr>
	<td colspan=2  class='FieldCaptionTD'>&nbsp;
</td>
</tr>
<tr align='center'>
	<td colspan=2  class='FieldCaptionTD'>
		<input name='cmdGuardar' id='cmdGuardar' class=button type='submit' value='Aceptar'  style='width:80px'>
		<input name='cmdCerrar' id='cmdCerrar' type='button' class=button value='Cerrar'   style='width:80px' onclick="cancelar();">
	</td>
</tr>
</table>
<input type="hidden" id="hddaccion" name="hddaccion" value="">
<input type="hidden" id="pagina" name="pagina" value="<?php echo $npag ?>">
<input type="hidden" id="buscam" name="buscam" value="<?php echo $buscam ?>">
<input type="hidden" id="orden" name="orden" value="<?php echo $orden ?>">
<input type="hidden" id="torder" name="torder" value="<?php echo $torder ?>">

<script language='javascript'>
var v= <?php echo $restriccion_hh_dd ?>;

if (v == 0){

	Block(this, 'restriccion_hh_tt1' , 'restriccion_hh_tt2' , 'hora_inicio', 'minuto_inicio','hora_fin','minuto_fin' , 'minutos');
	
}
/*if (v == 1){
	var w= <?php if(isset($restriccion_hh_tt)) echo $restriccion_hh_tt ?>;
	
	if (w == 0){
		
		Block2(this,'hora_inicio', 'minuto_inicio','hora_fin','minuto_fin' , 'minutos');
		//document.frm.hora_inicio.disabled=true;
		//document.frm.hora_fin.disabled=true;
		
		
	}
	if (w == 1){
		
		Block3(this,'hora_inicio', 'minuto_inicio','hora_fin','minuto_fin' , 'minutos');
		//document.frm.hora_inicio.disabled=true;
		//document.frm.hora_fin.disabled=true;
		
		
	}
	
}*/

</script>

</form>

</body>
</html>