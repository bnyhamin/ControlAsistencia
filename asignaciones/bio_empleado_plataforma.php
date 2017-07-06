<?php header("Expires: 0"); 

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Turnos_Empleado.php"); 
require_once("../../Includes/clsEmpleados.php");
require_once("../includes/MyGrillaEasyUI.php");

$e = new ca_turnos_empleado();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();


$rpta = "";
$body="";
$npag = 1;
$orden = "empleado";
$buscam = "";
$torder="ASC";

if (isset($_POST["pagina"])) $npag = $_POST["pagina"];
if (isset($_POST["orden"])) $orden = $_POST["orden"];
if (isset($_POST["buscam"])) $buscam = $_POST["buscam"];

if (isset($_GET["pagina"])) $npag = $_GET["pagina"];
if (isset($_GET["orden"])) $orden = $_GET["orden"];
if (isset($_GET["buscam"])) $buscam = $_GET["buscam"];

if (isset($_POST["cboTOrden"])) $torder = $_POST["cboTOrden"];


$esadmin="NO";
$e->empleado_codigo_registro = $_SESSION["empleado_codigo"];
$esadmin=$e->Query_Rol_Admin();
$essuper="NO";
$essuper=$e->Query_Rol_Super();
$area=0;
$ex = new empleados();
$ex->MyUrl = db_host();
$ex->MyUser= db_user();
$ex->MyPwd = db_pass();
$ex->MyDBName= db_name();
$ex->empleado_codigo = $_SESSION["empleado_codigo"];

$rpta = $ex->Empleado_Area();
if ($rpta=='OK'){
	$area=$ex->area_codigo;
}
$acceso = $esadmin =="OK"?1:0;
if($esadmin=='NOT'){
    $esadmin = $e->Query_Rol_Numero(13);//permiso biometrico
    $acceso =  $esadmin =="OK"?1:0;
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Asignaci&oacute;n de Empleados a Plataformas </title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css"/>
<?php  require_once('../includes/librerias_easyui.php');?>

<script language='javascript'>

function Reg(){
  
  valor='';
  var r=document.getElementsByTagName('input');
  
  for (var i=0; i< r.length; i++) {
          	var o=r[i];
          	if (o.id.substring(0,3)=='chk') { 
          		if (o.checked) {
          			valor= o.value;
          		}
          	} 
  }
  if ( valor =='' ){
  
       alert('Seleccione Empleado(s)');
			 return false;
  }
       return true;
}


function cmdAdicionar_onclick() {
    
  if (Reg()==false) return false;
    
	var rpta=PooGrilla.SeleccionMultiple(); 
  
    if(rpta!=''){
        
        var arrTmp        = rpta.split(",");
        var primerElemento= arrTmp.shift();
        
        if(primerElemento != '_todos'){
        
          arrTmp.unshift(primerElemento);
        }
            
        rpta = arrTmp.join(",");
        
        var posicion_x; 
        var posicion_y; 
        posicion_x=(screen.width/2)-(680/2); 
        posicion_y=(screen.height/2)-(500/2); 
        
        var valor = window.open('bio_adicionar_empleado_plataforma.php?ids='+rpta+"&acceso="+<?php echo $acceso ?>,'Accesos','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=600,left='+posicion_x+',top='+posicion_y) ;
        
        if (valor == "" || valor == "0" || valor == undefined){return false;}
        
        valor.focus();
        
        deseleccionarCheckboxes();
        
    }
}

function deseleccionarCheckboxes(){

  var r=document.getElementsByTagName('input');
  
  for (var i=0; i< r.length; i++) {
          	var o=r[i];
          	if (o.id.substring(0,3)=='chk') { 
          		  o.checked = false; 
          	
          	} 
  }


} 

 function cmdVerAsignacionPlataforma_onclick(empleadoCodigo){
     
      var posicion_x; 
      var posicion_y; 
      posicion_x=(screen.width/2)-(680/2); 
      posicion_y=(screen.height/2)-(500/2); 
      
      var valor = window.open('bio_plataforma_empleado_asignado.php?empleadoCodigo='+empleadoCodigo,'empleadoPlataformaAsignado','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=900,height=350,left='+posicion_x+',top='+posicion_y);
      if (valor == "" || valor == "0" || valor == undefined){return false;}
      
      valor.focus();
    
	}
  
  
  function checkear(){
   
if(document.frm.chk_todos.checked){
   checkear_todos_empleados(true);
}
else{
   checkear_todos_empleados(false);
  }
}

function checkear_todos_empleados(flag){
  var r=document.getElementsByTagName('input');
  for (var i=0; i< r.length; i++) {
       var o=r[i];
       var oo=o.id;
       if(oo.substring(0,3)=='chk'){
	       if(o.checked)
	   		{
	   			o.checked=flag;
	   		}else{
	   			o.checked=flag;
	   		}
	   }    
  }
}

</script>

</head>


<body class="PageBODY" >

<CENTER class="FormHeaderFont">Asignaci&oacute;n de Empleados a Plataformas </CENTER>
<form id=frm name=frm method=post action="<?php echo $_SERVER["PHP_SELF"];?>">
<TABLE class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    
    <TR>
      <td class="ColumnTD" >
      <input type="checkbox" name="chk_todos" id= "chk_todos" onclick="checkear()"/><font size="0.5">Seleccionar Todos</font>
    </td>
    <TD class='ColumnTD' colspan="10">
        <INPUT class=buttons type='button' value='Asignar Plataformas' id='cmdModificar' name='cmdModificar'  LANGUAGE=javascript onclick='return cmdAdicionar_onclick()' style='width:150px;'>
    </TD>
    </TR> 
    <tr>
  
</tr>

</TABLE>

<?php

	$objr = new MyGrilla;
	$objr->setDriver_Coneccion(db_name());
  $objr->setUrl_Coneccion(db_host());
  $objr->setUser(db_user());
  $objr->setPwd(db_pass());
  $objr->setOrder($orden);
	$objr->setFindm($buscam);
	$objr->setNoSeleccionable(true); 
	$objr->setFont("color=#000000");
	$objr->setFormatoBto("class=button");
	$objr->setFormaTabla(" class=FormTABLE style='width:100%' cellspacing='1' cellpadding='0' border='0' ");
	$objr->setFormaCabecera(" class=ColumnTD ");
	$objr->setFormaItems(" class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'");
	$objr->setTOrder($torder);
	
	$from  = "  vdatos e  ";
	
  if ($esadmin!='OK'){
		$from .= " inner join areas a on a.area_codigo=e.area_codigo ";
	}
	$objr->setFrom($from);
  
	$where = "";	
	
  
	if ($esadmin!='OK'){
		if ($essuper!='OK'){
			$where.= "  a.area_codigo in "; 
			$where.= " (select area_codigo from ca_controller ";
			$where.= " where empleado_codigo=".$_SESSION["empleado_codigo"]." and activo=1 ";
			$where.=" union select area_codigo from vdatos where empleado_codigo=".$_SESSION["empleado_codigo"]." )";
		}else{
			$where.= " a.area_codigo = ".$area; 
		}
	}
  
	$objr->setWhere($where);
	$objr->setSize(20);
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(true);
	// Arreglo de Alias de los Campos de la consulta
    $arrAlias[0] = "root";
    $arrAlias[1] = "Empleado";
    $arrAlias[2] = "Dni";
   	$arrAlias[3] = "Area";
    $arrAlias[4] = "Ver_Asig";
	// Arreglo de los Campos de la consulta
    $arrCampos[0] = "e.empleado_codigo";
    $arrCampos[1] = "empleado"; 
    $arrCampos[2] =	"empleado_dni";
	  $arrCampos[3] = "e.area_descripcion";
    $arrCampos[4] = "'<center><img id=' + cast(e.empleado_codigo as varchar)   + ' src=''../../Images/asistencia/inline011.gif'' border=0 style=cursor:hand onclick=cmdVerAsignacionPlataforma_onclick(this.id) title=Ver></center>'";
    
	$objr->setAlias($arrAlias);
	$objr->setCampos($arrCampos);
	
	$body = $objr->Construir();
	//echo $objr->getmssql();
	$objr = null;
	echo $body;
	echo "<br>";
  

	echo Menu("../menu.php");
  
?>
     
</form>
</body>
</html>
