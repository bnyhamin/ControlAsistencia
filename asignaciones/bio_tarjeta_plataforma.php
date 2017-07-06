<?php header("Expires: 0"); 

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/mantenimiento.php");
// require_once("../../Includes/MyGrilla.php");
require_once("../includes/MyGrillaEasyUI.php");


$rpta = "";
$body="";
$npag = 1;
$orden = "tarjeta_id";
$buscam = "";
$torder="ASC";
$acceso =1;
if (isset($_POST["pagina"])) $npag = $_POST["pagina"];
if (isset($_POST["orden"])) $orden = $_POST["orden"];
if (isset($_POST["buscam"])) $buscam = $_POST["buscam"];

if (isset($_GET["pagina"])) $npag = $_GET["pagina"];
if (isset($_GET["orden"])) $orden = $_GET["orden"];
if (isset($_GET["buscam"])) $buscam = $_GET["buscam"];

if (isset($_POST["cboTOrden"])) $torder = $_POST["cboTOrden"];

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Asignaci&oacute;n de Accesos a Tarjetas de Visita hacia Plataformas </title>
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
  
       alert('Seleccione Tarjeta(s)');
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
        
        var valor = window.open('bio_adicionar_tarjeta_plataforma.php?ids='+rpta+"&acceso="+<?php echo $acceso ?>,'Accesos','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=700,height=600,left='+posicion_x+',top='+posicion_y) ;
        
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

 function cmdVerAsignacionPlataforma_onclick(tarjetaID){
     
      var posicion_x; 
      var posicion_y; 
      posicion_x=(screen.width/2)-(680/2); 
      posicion_y=(screen.height/2)-(500/2); 
      
      var valor = window.open('bio_plataforma_tarjeta_asignado.php?tarjeta_id='+tarjetaID,'tarjetaPlataformaAsignado','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=850,height=350,left='+posicion_x+',top='+posicion_y);
      if (valor == "" || valor == "0" || valor == undefined){return false;}
      
      valor.focus();
    
	}
  
  
  function checkear(){
   
if(document.frm.chk_todos.checked){
   checkear_todos_tarjetas(true);
}
else{
   checkear_todos_tarjetas(false);
  }
}

function checkear_todos_tarjetas(flag){
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

<CENTER class="FormHeaderFont"> Accesos de Tarjetas de Visitas a Plataformas </CENTER>
<form id=frm name=frm method=post action="<?php echo $_SERVER["PHP_SELF"];?>">
<TABLE class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    <TR>
      <td class="ColumnTD">
        <input type="checkbox" name="chk_todos" id= "chk_todos" onclick="checkear()"/><font size="0.5">Seleccionar Todos</font>
      </td>
        <TD class='ColumnTD'>
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
	
	$from  = "  bio_tarjeta_proximidad  ";
	
	$objr->setFrom($from);
  
	$where = " tarjeta_activo=1 and tipo_asignAcion = 'EXTERNO'";	
	
	$objr->setWhere($where);
	$objr->setSize(20);
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(true);
	// Arreglo de Alias de los Campos de la consulta
    $arrAlias[0] = "root";
    $arrAlias[1] = "Codigo";
    $arrAlias[2] = "TarjetaDNI";
   	$arrAlias[3] = "TarjetaNombre";
	$arrAlias[4] = "TipoAsignacion";
    $arrAlias[5] = "Ver_Asig";
	// Arreglo de los Campos de la consulta
    $arrCampos[0] = "tarjeta_id";
    $arrCampos[1] = "codigo"; 
    $arrCampos[2] =	"tarjeta_dni";
    $arrCampos[3] =	"tarjeta_nombre";
	$arrCampos[4] =	"tipo_asignacion";
    $arrCampos[5] = "'<center><img id=' + cast(tarjeta_id as varchar)   + ' src=''../../Images/asistencia/inline011.gif'' border=0 style=cursor:hand onclick=cmdVerAsignacionPlataforma_onclick(this.id) title=Ver></center>'";
    
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
