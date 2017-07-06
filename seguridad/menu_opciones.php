<?php header("Expires: 0"); 
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Menu.php"); 

$o = new ca_menu();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

$menu_codigo="";

if (isset($_POST["hddaccion"])){
	if ($_POST["hddaccion"]=='DEL'){
		$menu_codigo = $_POST["menu_codigo"];	
		$o->menu_codigo = $menu_codigo;
		$rpta= $o->Delete();
        if ($rpta !=0){
            echo "Error al Eliminar registro ";
            echo $rpta;
        }	
	}
}
if (isset($_POST["hddaccion"])){
	if ($_POST["hddaccion"]=='UP'){
		$menu_codigo = $_POST["menu_codigo"];	
		$o->menu_codigo = $menu_codigo;
		$rpta= $o->Up();
        if ($rpta !=0){
            echo "Error al al subir Orden del Registro ";
            echo $rpta;
        }	
	}
}

if (isset($_POST["hddaccion"])){
	if ($_POST["hddaccion"]=='DW'){
		$menu_codigo = $_POST["menu_codigo"];	
		$o->menu_codigo = $menu_codigo;
		$rpta= $o->Down();
        if ($rpta!=0){
            echo "Error al al subir Orden del Registro ";
            echo $rpta;
        }	
	}
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv='pragma' content='no-cache'>
<META NAME=author CONTENT='TUMI Solutions S.A.C.'>
<title><?php echo tituloGAP() ?>- Definición de Menú</title>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
<STYLE>
.ListasSelect { color: #FFFFFF; background-color: #677999; font-size: 13px; }
.titulo { color:#FFFFFF; font-size: 19px; }
.tr { background-color:#153e7e; color:#FFFFFF; font-size: 10px; }
</STYLE>
</HEAD>
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscript.js"></script>
<script language='javascript'>
function Insertar(){
    window.parent.frames[1].location="menu_opciones_job.php?menu_codigo=0&menu_codigo_padre=0";
}
function volver(){
    window.parent.location.href='../menu.php';
}
function animar(img,tipo){
    img.src="../images/" + img.id + "-" + tipo + ".gif";
}
function Actualizar(opt){
    window.parent.frames[1].location="menu_opciones_job.php?menu_codigo=" + opt + "&menu_codigo_padre=0";
}
function Agregar(opt){
    window.parent.frames[1].location="menu_opciones_job.php?menu_codigo=0&menu_codigo_padre=" + opt;
}

function Eliminar(codigo,padre){
   if(padre==0){
        if (confirm('Confirmar Eliminar opcion de menú') == false) return false;
            document.frm.hddaccion.value= "DEL";
            document.frm.menu_codigo.value= codigo;
            document.frm.submit();
   }else{
         alert('No puede eliminar, el menu contiene Sub opciones');
   }   
}

function Subir(codigo){
	document.frm.hddaccion.value="UP";
    document.frm.menu_codigo.value=codigo;
    document.frm.submit();
	
}

function Bajar(codigo){
    document.frm.hddaccion.value="DW";
    document.frm.menu_codigo.value=codigo;
    document.frm.submit();
}

</script>
<body class='PageBODY' onLoad="return WindowParentResize(10,20,'center')">
<center class=FormHeaderFont>Menú del Sistema</center>
<form name='frm' id='frm' action='<?php echo $_SERVER["PHP_SELF"] ?>' method='post'>
<br>
<table class='FormTable' width='90%'  align='center' cellspacing='2' cellpadding='0' border='0'>
    <tr style='height:5px'>
        <td colspan='7' align='right'>&nbsp;<b>[&nbsp;</b><font style='cursor:hand' onclick="return volver()">Cerrar</font>&nbsp;<b>]</b></td>
    </tr>
    <tr style='height:5px'>
        <td class='FieldCaptionTD' colspan='7' align='center'>&nbsp;<b>Opciones del Menú<b></td>
    </tr>
    <tr bgcolor='#ffffff' style='height:5px'>
        <td >&nbsp;</td>
        <td align='center' width='10%'><b>Modificar</b></td>
        <td align='center' width='10%'><b><u><font style='cursor:hand' onclick='Insertar()'>Insertar</font></u></b></td>
        <td align='center' width='10%'><b>Eliminar</b></td>
        <td align='center' width='10%'><b>Subir</b></td>
        <td align='center' width='10%'><b>Bajar</b></td>
    </tr>    
    <?php
	$cadena=$o->Menu_Mostrar();
	echo $cadena;

    ?>
    <tr bgcolor='#ffffff' style='height:5px'><td colspan='7' align='center'>&nbsp;</td>
    </tr>
    <tr style='height:5px'><td class='FieldCaptionTD' colspan='7'>&nbsp;</td>
    </tr>
</table>
<input type=hidden id='menu_codigo' name='menu_codigo' value='<?php echo $menu_codigo ?>' >
<input type=hidden id='hddaccion' name='hddaccion' value='' >
</form>
</body>
</html>