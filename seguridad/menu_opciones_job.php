<?php header("Expires: 0"); 

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
//require_once("../../includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../includes/clsCA_Menu.php"); 
require_once("../../Includes/MyCombo.php"); 

$menu_codigo ="";
$menu_codigo_padre ="";
$pagina_codigo="";
$rpta="";
$msg="";
$menu_descripcion="";
$menu_query="";
$menu_anchor="";
$menu_target="";
$titulo="";
$mensaje="";

//$menu_codigo = $_GET["menu_codigo"];
//$menu_codigo_padre = $_GET["menu_codigo_padre"];

$menu_codigo = isset($_GET["menu_codigo"])? $_GET["menu_codigo"]:"";
$menu_codigo_padre = isset($_GET["menu_codigo_padre"]) ? $_GET["menu_codigo_padre"]:"";
//echo $menu_codigo;
//echo $menu_codigo_padre;

if (isset($_POST["pagina_codigo"])) $pagina_codigo = $_POST["pagina_codigo"];

if (isset($_POST["menu_codigo"])) $menu_codigo = $_POST["menu_codigo"];
if (isset($_POST["menu_codigo_padre"])) $menu_codigo_padre = $_POST["menu_codigo_padre"];

if (isset($_POST["menu_descripcion"])) $menu_descripcion = $_POST["menu_descripcion"];
if (isset($_POST["menu_query"])) $menu_query = $_POST["menu_query"];
if (isset($_POST["menu_anchor"])) $menu_anchor = $_POST["menu_anchor"];
if (isset($_POST["menu_target"])) $menu_target = $_POST["menu_target"];

$o = new ca_menu();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

if (isset($_POST["hddaccion"])){
	if ($_POST["hddaccion"]=='ok'){
	    $o->menu_codigo=$menu_codigo;
    	$o->pagina_codigo=$pagina_codigo;
    	$o->menu_codigo_padre=$menu_codigo_padre;
    	$o->menu_descripcion=$menu_descripcion;
    	$o->menu_query=$menu_query;
    	$o->menu_anchor=$menu_anchor;
    	$o->menu_target=$menu_target;

		//--* guardar registro
		if ($menu_codigo=='0'){
			$mensaje = $o->Addnew();
		}else{
			$mensaje = $o->Update();
		}
        //echo "<br>mr-".$menu_codigo."-mr-".$pagina_codigo."-mr-".$menu_codigo_padre."-mr-".$menu_descripcion."-mr-".$menu_query."-mr-".$menu_anchor."-mr-".$menu_target."-fin <br>";
        //echo "sms1". $mensaje;
		if($mensaje=='OK'){
		  $msg="Se guardaron datos satisfactoriamente!!";
          $menu_codigo = "0";
		}else{
		  $msg=$mensaje; 
		}
                
                //echo "sms2". $msg;
                
		?><script language='javascript'>
		    alert("<?php echo $msg ?>")
        	window.parent.frames[0].location='menu_opciones.php';
		  </script>
		<?php
	}
}

if ($menu_codigo!="" && $menu_codigo !=0){
	//recuperar datos
	$o->menu_codigo = $menu_codigo;
	$mensaje = $o->Query();
	$pagina_codigo = $o->pagina_codigo;
    $menu_codigo_padre = $o->menu_codigo_padre;
    $menu_descripcion= $o->menu_descripcion;
    $menu_query = $o->menu_query;
    $menu_anchor = $o->menu_anchor;
    $menu_target = $o->menu_target;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Registro de Menu</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
</head>
<script language='javascript'>
function guardar(){
    if (confirm('Guardar registro?')==false) return false;
    document.frm.hddaccion.value='ok';
    document.frm.submit();
}
</script>
<body Class='PageBODY'>
<?php
if ($mensaje!="OK") echo getMensaje($mensaje);
//if ($mensaje!="OK") echo $mensaje;
?>
<form id="frm" name='frm' method="post" action='<?php echo $_SERVER["PHP_SELF"] ?>'>
<?php
if ($menu_codigo!=''){
    if ($menu_codigo=="0")
        $titulo="Insertar";
    else
        $titulo="Modificar";
  ?>
<center class=FormHeaderFont>Menu <?php echo $titulo ?></center>
<table class='formtable' align=center border='0' width='520px' cellpadding='0' cellspacing='1'>  
  <tr align=center>
    <td class='ColumnTd' colspan='3'>Registro de Opción de Menú</td>
  </tr>
  <tr>
    <td class="ColumnTd" width="100px" align=right>Descripción&nbsp;</td>
    <td class='DataTd'>&nbsp;
        <input class='input' id="menu_descripcion" maxLength="80" name="menu_descripcion" alt="1" size='31' value='<?php echo $menu_descripcion?>' />&nbsp;(*)
    </td>
  </tr>  
  <tr>
    <td class='ColumnTd' align=right>Url&nbsp;</td>
    <td class='DataTd'>&nbsp;
	<?php  
	        $combo = new MyCombo();
			$combo->setMyUrl(db_host());
			$combo->setMyUser(db_user());
			$combo->setMyPwd(db_pass());
			$combo->setMyDBName(db_name());
			
			$sql="Select p.pagina_codigo as codigo,p.pagina_url as descripcion ";
            $sql .=" from ca_paginas p ";
            $sql .=" where p.pagina_activo=1 ";
            $sql .=" order by 2";
			 
			$combo->query = $sql;
			$combo->name = "pagina_codigo"; 
			$combo->value = $pagina_codigo."";
			$combo->more = " class='select' style='width:300px'";
			$rpta = $combo->Construir();
			echo $rpta;
		  ?>
    </td>
  </tr>
  <tr>
    <td class='ColumnTd' align=right>Query&nbsp;</td>
    <td class='DataTd'>&nbsp;
        <input type=text class='input' id="menu_query" maxLength="80" name="menu_query" value='<?php echo $menu_query?>' />
    </td>
  </tr>
  <TR>
    <TD class='ColumnTd' align=right>Anchor&nbsp;</TD>
    <TD class='DataTd'>&nbsp;
        <input type=text class='input' id="menu_anchor" maxLength="80" name="menu_anchor" value='<?php echo $menu_anchor?>' />
    </TD>
  </TR>
  <tr>
    <td class='ColumnTd' align=right>Target&nbsp;</td>
    <td class='DataTd'>&nbsp;
        <input type=text class='input' id="menu_target" maxLength="80" name="menu_target" value='<?php echo $menu_target?>' />
    </td>
  </tr>
   <tr align=center>
    <td class='ColumnTd' colspan='2'>&nbsp;</td>
  </tr>
  <tr align=center>
    <td class='ColumnTd' colspan='2'>
        <input class='button' type="button" id='cmd' value="Guardar" onclick='return guardar()' />
    </td>
  </tr>
</table>
<?php }?>
<input type=hidden id='menu_codigo' name='menu_codigo' value='<?php echo $menu_codigo?>' />
<input type=hidden id='menu_codigo_padre' name='menu_codigo_padre' value='<?php echo $menu_codigo_padre?>' />
<input type=hidden id='hddaccion' name='hddaccion' value='' />

</form>
</body>
</html>