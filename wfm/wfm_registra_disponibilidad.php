<?php header("Expires: 0"); 
session_start();
if (!isset($_SESSION["empleado_codigo"])){
?>
    <script language="JavaScript">
        alert("Su sesión a caducado!!, debe volver a registrarse.");
        document.location.href = "../login.php";
    </script>
<?php
exit;
}
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php");
require_once("../../Includes/clsEmpleados.php");
require_once("../../Includes/MyCombo.php");
require_once("../../Includes/clswfm_disponibilidad_diaria.php");

$codigo_empleado=$_GET['empleado'];

$e = new Empleados;
$e->setMyDBName(db_name());
$e->setMyUrl(db_host());
$e->setMyUser(db_user());
$e->setMyPwd(db_pass());

$combo = new MyCombo();
$combo->setMyUrl(db_host());
$combo->setMyUser(db_user());
$combo->setMyPwd(db_pass());
$combo->setMyDBName(db_name());

$dd = new wfm_disponibilidad_diaria();
$dd->setMyUrl(db_host());
$dd->setMyUser(db_user());
$dd->setMyPwd(db_pass());
$dd->setMyDBName(db_name());

$dd->Query_Empleado_Indicador($codigo_empleado);
$dd_codigo=$dd->dd_codigo;
$dh_codigo=$dd->dh_codigo;

$cadenilla="";

if (isset($_POST["dd_codigo"])){
$dd_codigo = $_POST["dd_codigo"]; 
}
if (isset($_POST["dh_codigo"])){
$dh_codigo = $_POST["dh_codigo"]; 
}
if (isset($_POST["hddempleado_codigo"])){
$codigo_empleado = $_POST["hddempleado_codigo"]; 
}
if (isset($_POST["hddempleado"])){
$empleado = $_POST["hddempleado"]; 
}

if (isset($_POST["hddaccion"])){
     if ($_POST["hddaccion"]=="SVE"){//la orden es grabar
       
       $sRpta=$dd->Update_Empleado_Indicador($dd_codigo,$dh_codigo,$codigo_empleado);
       
       if ($sRpta!="OK"){
               echo $sRpta;
       }else{
           	
      	$cadenilla = "<center><font size=2 color=#800000>Los datos se grabaron satisfactoriamente</font></center>";
           	
       }
     }
}

$e->empleado_codigo = $codigo_empleado;
$rpta=$e->Query();

$empleado=$e->empleado_apellido_paterno . ' ' . $e->empleado_apellido_materno . ' ' . $e->empleado_nombres;

?>
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Registro de Disponibilidad</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="../style/tstyle.css">
<script languaje='javascript'>
 function cerrar(){
  
  //window.opener.document.forms['frm'].submit();
  //window.close();
  parent.windowv.document.forms['frm'].submit();
  parent.windowv.hide();
  
 }
 
function guardar(){
    
    if(document.frm.dd_codigo.value==0){
        alert('Indique  valor');
        document.frm.dd_codigo.focus();
        return false;
    }
    
    if(document.frm.dh_codigo.value==0){
        alert('Indique  valor');
        document.frm.dh_codigo.focus();
        return false;
    }
    
    document.frm.hddaccion.value='SVE'
    document.frm.submit();
    
}

</script>
</head>
<!--<center>
        <font class="FormHeaderFont">Registro de Disponibilidad</font>
</center>-->
<br>
<body>
<form name='frm' id='frm' method='post' >
  <table width='400px' class=FormTable  align="center" border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class=ColumnTD colspan=4>&nbsp;</td>
		</tr>
		<!--<tr>
       		<td class=ColumnTD>Código: </td>
       		<td class=DataTD colspan=3><strong><?php echo $codigo_empleado ?>
	   	</tr>-->
		<tr>
       		<td class=ColumnTD>Empleado: </td>
       		<td class=DataTD colspan=3><strong><?php echo $empleado ?>
	   	</tr>
		 <tr>
 <td class="ColumnTD">Disponibilidad Dia</td>
 <td class=DataTD colspan=3>
		<?php
                        $ssql= "select dd_codigo, '('+ dd_identificador + ') ' + dd_descripcion ";
                        $ssql.= " from disponibilidad_dias ";
                        $ssql.= " where dd_activo=1";
                        $ssql.= " order by dd_codigo ";

                        $combo->query = $ssql;
                        $combo->name = "dd_codigo";
                        $combo->value = $dd_codigo."";
                        $combo->more = "id=dd_codigo class=select style='width:200px' ";
                        $rpta = $combo->Construir();
                        echo $rpta;
                ?>
		</td>
  
 </tr>
 <tr>
 <td class="ColumnTD">Disponibilidad Horas</td>
     <td class=DataTD colspan=3>
            <?php
                        $ssql= "select dh_codigo, '(' + dh_identificador + ') ' + dh_descripcion ";
                        $ssql.= " from disponibilidad_horas ";
                        $ssql.= " where dh_activo=1";
                        $ssql.= " order by dh_codigo ";

                        $combo->query = $ssql;
                        $combo->name = "dh_codigo";
                        $combo->value = $dh_codigo."";
                        $combo->more = "id=dh_codigo class=select style='width:200px' ";
                        $rpta = $combo->Construir();
                        echo $rpta;
            ?>
    </td>
  
</tr>
     	<tr>
       		<td class=DataTD>&nbsp;</td>
    	</tr>
	</table>

<br>
 <table class='sinborde' width='400px' align="center" border="0" cellspacing="0" cellpadding="0">
	<tr align=center>
            <td class=DataTD colspan=4>
                <input class=button type="button" name=cmdGuardar value="Guardar" style="width:80px" onclick="javascript:guardar();"/>&nbsp;
                <input class=button type="button" name=cmdCerrar value="Cerrar" style="width:80px" onclick="javascript:cerrar();"/>&nbsp;
            </td>
    	</tr>
</table>
	
	<br />
	<?php echo $cadenilla; ?>
	
<input type='hidden' name='hddaccion' id='hddaccion' value=''/>
<input type='hidden' name='hddempleado_codigo' id='hddempleado_codigo' value='<?php echo $codigo_empleado ?>'/>
<input type='hidden' name='hddempledao' id='hddempleado' value='<?php echo $empleado ?>'/>
</form>
</body>
</html>