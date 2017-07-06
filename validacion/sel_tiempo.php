<?php header("Expires: 0"); ?>
<?php
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Asistencia_Incidencias.php"); 

    $empleado_codigo= '0';
    $asistencia_codigo= '0';
    $responsable_codigo= '0';
    $incidencia_codigo= '0';
    $cod_campana= '0';
    $evento_codigo= '0';
    $tiempo_derg='0';
    $fecha='';
    $tiempo='';
	
    $empleado_codigo= $_GET['empleado'];
    $asistencia_codigo= $_GET['asistencia'];
    $responsable_codigo= $_GET['responsable'];
    $incidencia_codigo=$_GET['incidencia'];
    $cod_campana= $_GET['campana'];	
    $evento_codigo= $_GET['evento'];
    $fecha= $_GET['fech'];
    $tiempo= $_GET['tiempo'];

    $o = new ca_asistencia_incidencias();
    $o->MyUrl = db_host();
    $o->MyUser= db_user();
    $o->MyPwd = db_pass();
    $o->MyDBName= db_name();

    if (isset($_POST["empleado_codigo"]))  $empleado_codigo= $_POST["empleado_codigo"];
    if (isset($_POST["evento_codigo"]))  $evento_codigo= $_POST["evento_codigo"];
    if (isset($_POST["incidencia_codigo"]))  $incidencia_codigo= $_POST["incidencia_codigo"];
    if (isset($_POST["responsable_codigo"]))  $responsable_codigo= $_POST["responsable_codigo"];
    if (isset($_POST["cod_campana"]))  $cod_campana= $_POST["cod_campana"];
    if (isset($_POST["asistencia_codigo"]))  $asistencia_codigo= $_POST["asistencia_codigo"];
    if (isset($_POST["fecha"]))  $fecha= $_POST["fecha"];
    if (isset($_POST["tiempo"]))  $tiempo= $_POST["tiempo"];
    if (isset($_POST["tiempo_derg"]))  $tiempo_derg= $_POST["tiempo_derg"];
    //Aprobar evento(convertir a incidencia)
    if (isset($_POST['hddaprobar'])){
    if ($_POST['hddaprobar']=='APROBAR'){

        $o->empleado_codigo = $empleado_codigo;
        $o->evento_codigo=$evento_codigo;
        $o->asistencia_codigo = $asistencia_codigo;
        $o->incidencia_codigo = $incidencia_codigo;
        $o->cod_campana = $cod_campana;
        $o->responsable_codigo = $responsable_codigo;
        $o->tiempo_derg=$tiempo_derg;
        
        $mensaje = $o->registrar_evento_a_incidencia();
        
        if($mensaje=='OK'){
                ?>
                <script language='javascript'>
                 //alert('Evento Aprobado!!');
                 window.opener.document.forms['frm'].submit();
                 window.opener.document.frm.cmdx.click();
                 window.close();
                </script>
                <?php
                }else{
                  echo "error: " . $mensaje;
                }
        }		
    }

?>

<html> 
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Validar Refrigerio</title>
<meta http-equiv='pragma' content='no-cache'>
<link rel='stylesheet' type='text/css' href='../style/tstyle.css'>
<script language="JavaScript" src="../../default.js"></script>

<script language="JavaScript">
 window.returnValue = "";
 function cmdAprobar_onclick(){
 var tiempo_derg=document.frm.tiempo_derg.value;
 var tiempo="<?php echo $tiempo ?>";	
	switch(parseInt(tiempo_derg)){
		case 0: break;
		case 15 : if(tiempo<15){
				 	 alert('El tiempo total de evento refrigerio debe ser mayor a 15 minutos en este caso');
					 return false;
				 }	 
		         break
		         
		case 30:  if(tiempo<30){
		         	 alert('El tiempo total de evento refrigerio debe ser mayor a 30 minutos en este caso');
				 	 return false;
				  }	 
		          break
		
	}
	
        self.returnValue = document.frm.tiempo_derg.value;
		self.close();
	}
	
 function cerrar(){
	self.returnValue = 0;
	self.close();
 }
 
/*
function cmdAprobar_onclick(){
var tiempo_derg=document.frm.tiempo_derg.value;
var tiempo="<?php echo $tiempo ?>";	
switch(parseInt(tiempo_derg)){
	case 0: break;
	case 15 : if(tiempo<15){
			 	 alert('El tiempo total de evento refrigerio debe ser mayor a 15 minutos en este caso');
				 return false;
			 }	 
	         break
	         
	case 30:  if(tiempo<30){
	         	 alert('El tiempo total de evento refrigerio debe ser mayor a 30 minutos en este caso');
			 	 return false;
			  }	 
	          break
	
}
	
    if (confirm('Seguro de aprobar?')==false) return false;
	document.frm.hddaprobar.value='APROBAR';
	document.frm.submit();
}*/
 
</script>

</head>

<body class="PageBODY">
<form name='frm' id='frm' action="<?php echo $_SERVER['PHP_SELF']  ?>" method='post'>
<center><font color=#333399 style='font-size:12px' ><b>Aprobar Refrigerio con o sin Descanso Ergonometrico</b></font></center>
<table width='60%'border="0" align="center" >
  <tr>
       <td  class='ColumnTD'align=center>
       <!--<input type="checkbox" name="chk" id="chk15" value="1"  onclick=''><input type="text" id="txt15" name="txt15" value='15' class="Input" size="5" readonly>
	    	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	    <input type="checkbox" name="chk" id="chk30" value="2"  onclick=''><input type="text" id="txt30" name="txt30" value="30" class="Input" size="5" readonly></b>
		-->
		<select id="tiempo_derg" name="tiempo_derg" class='select'  style='width:230pxstyle;background:#F4DBA6'>
              <option value='0'>Solo Refrigerio</option>
              <option value='15'>Refrigerio + Desc.Ergo 15 Minutos</option>
              <option value='30'>Refrigerio + Desc.Ergo 30 Minutos</option>
         </select>        
	</td>
 </tr>
	<tr align="center">
		<td><br>
			<input type="button" class="Button" id="cmdg" name="cmdg" value="Aceptar" style="width:80px" onClick="cmdAprobar_onclick()">
			<input type="button" class="Button" id="cmdg" name="cmdg" value="Cancelar" style="width:80px"  onClick="cerrar()">
		</td>
	</tr>
</table>
<font size=2 color=DarkSlateBlue id=lblDescripcion name=lblDescripcion>&nbsp;</font>
<input type='hidden' id='empleado_codigo' name='empleado_codigo' value="<?php echo $empleado_codigo ?>"/>
<input type='hidden' id='evento_codigo' name='evento_codigo' value="<?php echo $evento_codigo ?>"/>
<input type='hidden' id='asistencia_codigo' name='asistencia_codigo' value="<?php echo $asistencia_codigo ?>"/>
<input type='hidden' id='incidencia_codigo' name='incidencia_codigo' value="<?php echo $incidencia_codigo ?>"/>
<input type='hidden' id='cod_campana' name='cod_campana' value="<?php echo $cod_campana?>"/>
<input type='hidden' id='responsable_codigo' name='responsable_codigo' value="<?php echo $responsable_codigo ?>"/>
<input type='hidden' id='fecha' name='fecha' value="<?php echo $fecha ?>"/>
<input type='hidden' id='tiempo' name='tiempo' value="<?php echo $tiempo ?>"/>
<input type='hidden' id='hddaprobar' name='hddaprobar' value=""/>

</form>
</body>
</html>