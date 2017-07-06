<?php header("Expires: 0");
session_start();
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require('../../Includes/MyCombo.php');
require_once("../../Includes/clswfm_empleado_disponibilidad.php");

set_time_limit(30000);
//$usuario=3300;
$valores = "";
$valores2 = "";
$usuario=$_SESSION["empleado_codigo"];

$combo = new MyCombo();
$combo->setMyUrl(db_host());
$combo->setMyUser(db_user());
$combo->setMyPwd(db_pass());
$combo->setMyDBName(db_name());

$ed = new wfm_empleado_disponibilidad();
$ed->setMyUrl(db_host());
$ed->setMyUser(db_user());
$ed->setMyPwd(db_pass());
$ed->setMyDBName(db_name());
	
    if (isset($_GET['empleado'])){
        $empleado=$_GET['empleado'];
    }

    /*if (isset($_GET['area'])){
        $area=$_GET['area'];
    }*/

    if (isset($_POST['area'])){
        $area=$_POST['area'];
    }else{
        //agregado
        $area='';
    }
	
    if (isset($_POST['valores'])){
        $valores=$_POST['valores'];
    }
	
    if (isset($_POST['valores2'])){
        $valores2=$_POST['valores2'];
    }

    if (isset($_POST["hddaccion"])){

        if ($_POST["hddaccion"] == "BUS"){
                $body=$ed->mostrar_servicios_disponibles($empleado,$area);
        }
        
        if ($_POST["hddaccion"] == "SEL"){
            $arr=split("@",$valores);
            $num_arr = sizeof($arr);
            for ($t=0; $t<$num_arr; $t++){
                $mensaje = $ed->registra_disponibilidad_servicio($empleado , $arr[$t] , $usuario);
            }
        }

        if ($_POST["hddaccion"] == "DEL"){
            $arr=split("@",$valores2);
            $num_arr = sizeof($arr);
            for ($t=0; $t<$num_arr; $t++){
                $mensaje = $ed->desactivar_disponibilidad_servicio($empleado , $arr[$t] , $usuario);
            }
        }

    }
	
    if ($empleado != 0){	
        $body2 = $ed->mostrar_servicios_registrados($empleado);
    }


?>

<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
  <link rel='stylesheet' type='text/css' href='../style/tstyle.css'>
  <script language="JavaScript" src="../../default.js"></script>
  <title>Disponibilidad de Servicio</title>
  <script language='javascript'>
    function seleccionar_area(){
	
	frm.hddArea.value=document.getElementById('area').value;
	//alert(frm.hddArea.value);
	
	
	}
    function BuscarServicio(){
 	
	var valor = window.showModalDialog("../../requerimientos/Servicios.php?area=" + frm.hddArea.value + "&filtro=" + frm.txtServicio.value,"Servicio",'dialogWidth:500px; dialogHeight:500px');
	if (valor == "" || valor == "0" ){
		 return;
	}

	arr_valor = valor.split("¬");
	frm.hddServicio.value = arr_valor[0];
	frm.txtServicio.value =  arr_valor[1];
	

}

	function buscar(){
      if (document.frm.area.value == 0 ){
        alert("Ingrese un area");
        return false;
      }
      document.frm.hddaccion.value = "BUS";
      document.frm.submit();
    }
    
    function habilitaChecks(){
      var r=document.getElementsByTagName('input');
      for (var i=0; i< r.length; i++) {
        var o=r[i];
        if (o.id=='chkServicio') {
          o.checked=document.frm.chk_Todos.checked;
        }
      }
    }
    
    function seleccionar(){
      cadena="";
      var r=document.getElementsByTagName('input');
      for (var i=0; i< r.length; i++) {
        var o=r[i];
        if (o.id=='chkServicio') {
          if (o.checked){
            cadena += o.value + "@";
          }
        }
      }
      if (cadena==""){
        alert("Seleccione al menos un servicio");
        return false;
      } else {
        cadena = cadena.substring(0,cadena.length - 1);
        document.frm.valores.value = cadena
        document.frm.hddaccion.value = "SEL";
        //alert(cadena);
        document.frm.submit();
      }
    }
    
    function habilitaChecks2(){
      var r=document.getElementsByTagName('input');
      for (var i=0; i< r.length; i++) {
        var o=r[i];
        if (o.id=='cmdServicio') {
          o.checked=document.frm.chk_Todos2.checked;
        }
      }
    }
    function eliminar(){
      cadena="";
      var r=document.getElementsByTagName('input');
      for (var i=0; i< r.length; i++) {
        var o=r[i];
        if (o.id=='cmdServicio') {
          if (o.checked){
            cadena += o.value + "@";
          }
        }
      }
      if (cadena==""){
        alert("Seleccione al menos un servicio a eliminar");
        return false;
      } else {
        cadena = cadena.substring(0,cadena.length - 1);
        document.frm.valores2.value = cadena
        //alert(cadena);
        document.frm.hddaccion.value = "DEL";
        document.frm.submit();
      }
    }
   
  </script>

</head>
<body bgcolor='#ffffff' text='#000000' link='#000000' alink='#999999' vlink='#000000' Class='PageBody'>
<form name='frm' id='frm' method='post'>
  <table class=FormTABLE cellspacing='0' cellpadding='0' border='1' width='60%' align=center>

	<tr>
			<td ><strong>Area :</strong></td>
			<td>
			<?php
			    /*$sql =" select areas.area_codigo , areas.area_descripcion";
				$sql .= " from ca_controller ";
				$sql .= " inner join areas on areas.area_codigo = ca_controller.area_codigo ";
				$sql .= " where ca_controller.empleado_codigo=" . $usuario . " and ca_controller.activo=1";*/
				
				$sql=" select areas.area_codigo , areas.area_descripcion ";
				$sql .= " from empleado_area ";
				$sql .= " inner join areas on empleado_area.area_codigo = areas.area_codigo ";
				$sql .= " where empleado_codigo=" . $empleado . " and empleado_area_activo=1 ";

				$combo->query = $sql;
				$combo->name = "area"; 
				$combo->value = $area ." ";
				$combo->more = "class='Select' style='width:400px' onchange='seleccionar_area()'";
				$rpta = $combo->Construir();
				//$rpta = $combo->Construir_Opcion("---Seleccione---");
				echo $rpta;
			?>
			<input type="hidden" name="hddArea" id="hddArea" value="">
			</td>
			


			
			</tr>
  		    <tr>
  		    <td colspan="2">&nbsp;</td>
			<!--<td ><strong>Servicio:</strong> </td>
			<td >
				<input type="text" name="txtServicio" style="TEXT-ALIGN: left" id="txtServicio" class="input" style="width:340px" onchange="javascript: limpiarServicio();" onkeypress="tecla(2)">&nbsp;<img src="../Images/buscaroff.gif" width="16" height="15" border="0" alt="Buscar Servicio" style="cursor:hand" onclick="javascript:BuscarServicio();">
				<input type="hidden" name="hddServicio" id="hddServicio">
			</td>-->

		</tr>
    <tr>
      <td class=DataTDList align=center colspan='2'>
        <input type=button id=cmdBuscar name=cmdBuscar value="Buscar" style='width:80px' class='Button' language='javascript' onclick="return buscar();">
      </td>
    </tr>
  </table><br>
  <table class=FormTABLE cellspacing='0' cellpadding='0' border='0' width='100%' align=center>
    <tr>
      <td colspan='4' class=FieldCaptionTD align=center>PARA SELECCIONAR</td>
    </tr>
    <tr>
      <td class=FieldCaptionTD width='50px'>Sel</td>
      <td class=FieldCaptionTD>Código</td>
      <td class=FieldCaptionTD>Servicio</td>
    </tr>
    <tr>
      <td colspan='4' class=FieldCaptionTD>
        <input type=checkbox name='chk_Todos' id='chk_Todos' value='2' onclick='return habilitaChecks();'>&nbsp;Todos
      </td>
    </tr>
    <?php if(isset($body)) echo $body; ?>
  </table><br>
  <table class=FormTABLE cellspacing='0' cellpadding='0' border='0' width='100%' align=center>
    <tr>
      <td colspan='5' class=FieldCaptionTD align=center>SELECCIONADOS</td>
    </tr>
    <tr>
      <td class=FieldCaptionTD width='50px'>Sel</td>
      <td class=FieldCaptionTD>Código</td>
      <td class=FieldCaptionTD>Servicio</td>
     </tr>
    <tr>
      <td colspan='5' class=FieldCaptionTD>
        <input type=checkbox name='chk_Todos2' id='chk_Todos2' value='3' onclick='return habilitaChecks2();'>&nbsp;Todos
      </td>
    </tr>
    <?php echo $body2; ?>
  </table>
  <input type="hidden" name="hddaccion" id="hddaccion" value="">
  <input type=hidden id="valores" name="valores" value="<?php echo $valores ?>">
  <input type=hidden id="valores2" name="valores2" value="<?php echo $valores2 ?>">
</form>

</body>
</html>