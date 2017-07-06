<?php header("Expires: 0"); ?>
<?php
$sql="";
$area="";
$anio="";
$opcion="";
$usuario="";
require_once("../../Includes/Connection.php");
require_once("../../Includes/librerias.php");
require_once("../includes/clsCA_Areas.php"); 
require_once("../../Includes/MyCombo.php"); 

?>

<html> 
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Seleccionar Gerencia</title>
<meta http-equiv='pragma' content='no-cache'>
<link rel='stylesheet' type='text/css' href='../style/tstyle.css' />
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript">
window.returnValue = "";
function ok(){	
self.returnValue =document.frm.area.value;
self.close();
}
	
function cerrar(){
  window.returnValue = "";
   self.close();
}
 
</script>

</head>
<body class="PageBODY">
<form name='frm' id='frm' action="<?php echo $_SERVER['PHP_SELF'] ?>" method='post'>
<center class="FormHeaderFont">Seleccione Gerencia</center>
<table border="0" align="center" >
  <tr>
       <td  class='ColumnTD'align=left><?php
	        
			$c = new MyCombo();
			$c->setMyUrl(db_host());
			$c->setMyUser(db_user());
			$c->setMyPwd(db_pass());
			$c->setMyDBName(db_name());
			
	  	    $sql="SELECT Areas.area_codigo as codigo,Areas.area_descripcion as descripcion ";
			$sql .=" FROM Areas ";
			$sql .=" WHERE (Area_Padre=1) ";
			$sql .=" and (Areas.Area_Activo = 1) ";
			$sql .=" order by 2 asc";
			
			$c->query = $sql;
			$c->name = "area"; 
			$c->value = $area. "";
			$c->more = " style='width:500px' class='Select'";
			$rpta = $c->Construir();
			echo $rpta;
			
		  ?>
	</td>
 </tr>
	<tr align="center">
		<td>
			<input type="button" class="Button" id="cmdg" name="cmdg" value="Aceptar" style="width:80px" onclick="ok()" />
			<input type="button" class="Button" id="cmdg" name="cmdg" value="Cancelar" style="width:80px" onclick="cerrar()" />
		</td>
	</tr>
</table>
<font size=2 color=DarkSlateBlue id=lblDescripcion name=lblDescripcion>&nbsp;</font>
<input type='hidden' id='anio' name='anio' value='<?php echo $anio ?>' />
<input type='hidden' id='opcion' name='opcion' value='<?php echo $opcion?>' />
<input type='hidden' id='usuario' name='usuario' value='<?php echo $usuario ?>' />
</form>
</body>
</html>

