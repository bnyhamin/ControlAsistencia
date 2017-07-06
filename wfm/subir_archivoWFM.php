<?php header("Expires: 0"); 
    session_start();
    require_once("../../Includes/Connection.php");
    require_once("../../Includes/Constantes.php");
    require('../../Includes/MyCombo.php');
    require_once("../../Includes/clsEmpleados.php");
    $area="";
    //$usuario=3300;
    $usuario=$_SESSION["empleado_codigo"];
  
    $e = new Empleados();
    $e->setMyUrl(db_host());
    $e->setMyUser(db_user());
    $e->setMyPwd(db_pass());
    $e->setMyDBName(db_name());

    $combo = new MyCombo();
    $combo->setMyUrl(db_host());
    $combo->setMyUser(db_user());
    $combo->setMyPwd(db_pass());
    $combo->setMyDBName(db_name());


    if (isset($_GET["te_semana"])){
            $semana = $_GET["te_semana"];    // obligatorio de entrada
    }else{
            $semana = $_POST["hddsemana"];
            //echo $semana;
    }

    if (isset($_GET["te_anio"])){
            $anio = $_GET["te_anio"];    // obligatorio de entrada
    }else{
            $anio = $_POST["hddanio"];
    }

    if (isset($_GET["fecha_inicio"])){
            $fecha_inicio = $_GET["fecha_inicio"];    // obligatorio de entrada
    }else{
            $fecha_inicio = $_POST["hddfechainicio"];
    }

    if (isset($_GET["fecha_fin"])){
            $fecha_fin = $_GET["fecha_fin"];    // obligatorio de entrada
    }else{
            $fecha_fin = $_POST["hddfechafin"];
    }

    if (isset($_GET["area"])){
            $area = $_GET["area"];    // obligatorio de entrada
    }else{
        if(isset($_POST["hddArea"])) $area = $_POST["hddArea"];

    }

    if (isset($_GET["servicio"])){
            $servicio = $_GET["servicio"];    // obligatorio de entrada
    }else{
        if(isset($_POST["hddServicio"])) $servicio = $_POST["hddServicio"];
    }

    if(isset($_POST["txtServicio"])) $servicio_nombre=$_POST["txtServicio"];
        
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>  
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Importar Requerimientos WFM</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css"/>
<link rel="stylesheet" href="../../jscripts/dhtmlmodal/windowfiles/dhtmlwindow.css" type="text/css"/>
<script type="text/javascript" src="../../jscripts/dhtmlmodal/windowfiles/dhtmlwindow.js"></script>
<link rel="stylesheet" href="../../jscripts/dhtmlmodal/modalfiles/modal.css" type="text/css"/>
<script type="text/javascript" src="../../jscripts/dhtmlmodal/modalfiles/modal.js"></script>
<script language="javascript">
function limpiarArea(){
    frm.hddArea.value="";
}

function tecla(opt){
    var a=window.event.keyCode;
    if (a!=13) return;
    switch (opt){
    case 1:	BuscarArea();
            break;
    case 2:	BuscarServicio();
            break;
    }
    return;
}

function BuscarArea(){
    //if (val != 0) return;
     if (frm.txtArea.value == 0){
            alert("Indique el nombre del Area Destino");
            frm.txtArea.focus();
            return;
    }


    var valor = window.showModalDialog("../Movimientos/AreaDestino.php?filtro=" + frm.txtArea.value,"AreaDestino",'dialogWidth:600px; dialogHeight:500px');
    if (valor == "" || valor == "0" ){
             return;
    }
    
    arr_valor = valor.split("¬");
    frm.hddArea.value = arr_valor[0];
    frm.txtArea.value =  arr_valor[1];
    //alert(frm.hddArea.value);
}
 
function limpiarServicio(){
    frm.hddServicio.value="";
}

function BuscarServicio(){
    /*if (frm.txtArea.readOnly==false){
            if (frm.hddArea.value == 0){
                    alert("Indique el nombre del Area Destino");
                    frm.txtArea.focus();
                    return;
            }
    }*/
                                        
    
    var valor = window.showModalDialog("../../Requerimientos/Servicios.php?area=" + frm.hddArea.value + "&filtro=" + frm.txtServicio.value,"Servicio",'dialogWidth:500px; dialogHeight:500px');
    if (valor == "" || valor == "0" ){
             return;
    }
    arr_valor = valor.split("¬");
    frm.hddServicio.value = arr_valor[0];
    frm.txtServicio.value =  arr_valor[1];
    //alert(frm.hddServicio.value);
}

function subir_archivo(){
//window.open('uploadFileWFM.php?semana=<?php //echo $semana?>&anio=<?php //echo $anio?>&usuario=<?php //echo $id_usuario ?>&servicio=<?php //echo $codServ ?>', 'Historico',900,600, 'yes', 'center')
}

function seleccionar(){
    frm.hddArea.value=document.getElementById('area').value;
    //alert(frm.hddArea.value);	
}
function ver_archivo() {
    CenterWindow("mapa_turnito.txt",'Texto',670,500,"location=no, menubar=yes, toolbar=no, scrollbars=yes, resizable=yes")
}
</script>
</head>
<body Class='PageBODY'>
<!--<center class=FormHeaderFont>Importar Requerimientos WFM</center>-->
<center><strong><?php echo $fecha_inicio?> - <?php echo $fecha_fin?></strong></center>
<!--<div align="center"><hr>  -->
<form name=frm id=frm enctype="multipart/form-data" action='uploadFileWFM.php' method="POST">  
        <table align="center">
            <tr><td colspan="2"><input type="hidden" name="MAX_FILE_SIZE" value="5000000"></td></tr>
            <tr>
                <td ><strong>Area :</strong></td>
                <td>
                    <?php
                        $sql =" select areas.area_codigo , areas.area_descripcion";
                        $sql .= " from ca_controller ";
                        $sql .= " inner join areas on areas.area_codigo = ca_controller.area_codigo ";
                        $sql .= " where ca_controller.empleado_codigo=" . $usuario . " and ca_controller.activo=1 order by 2";

                        $combo->query = $sql;
                        $combo->name = "area";
                        $combo->value = $area ."";
                        $combo->more = "class='Select' style='width:340px' onchange='seleccionar()'";
                        $rpta = $combo->Construir_Opcion("---Seleccione---");
                        echo $rpta;
                    ?>
                    <input type="hidden" name="hddArea" id="hddArea" value="<?=$area?>">
                </td>
            </tr>
            <tr>
			<td ><strong>U. Servicio:</strong> </td>
			<td >
                            <input type="text" name="txtServicio" style="TEXT-ALIGN: left" id="txtServicio" value="<?php if(isset ($servicio_nombre)) echo $servicio_nombre; ?>" class="input" style="width:340px" onchange="javascript: limpiarServicio();" onkeypress="tecla(2)">&nbsp;<img src="../images/buscaroff.gif" width="16" height="15" border="0" alt="Buscar Servicio" style="cursor:hand" onclick="javascript:BuscarServicio();" >
				<input type="hidden" name="hddServicio" id="hddServicio" value="<?php if(isset($servicio)) echo $servicio?>">
			</td>
            </tr>
            <tr><td colspan="2">Seleccione archivo WFM</td></tr> 
            <tr><td colspan="2"><input type="file" name="pix" size="53">  </td></tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="2"><input type="submit" name="Upload" value="subir archivo" ></td>
            </tr>
        </table>
        <br> 
        <br>
<table width='80%' align='center' cellspacing='0' cellpadding='0' border='0'>
	<tr>
		<td align='center'><b>Modelo de la Plantilla &nbsp;
		<img src="../../Images/listado.gif" width="25" height="25" border="0" style="cursor:hand" onclick="javascript:ver_archivo();">
		</td>
	</tr>
</table>
<br />
<fieldset >
<legend align="center">Descripción de las columnas
</legend>
<table width='70%' align='center' cellspacing='0' cellpadding='0' border='0'>
	<tr><td>&nbsp;</td></tr>
	<tr><td>Columna 1 = Hora inicio del bloque horario</td></tr>
	<tr><td>Columna 2 = Hora fin del bloque horario</td></tr>
	<tr><td>Columna 3 = Dimensionado de personal del Lunes</td></tr>
	<tr><td>Columna 4 = Dimensionado de personal del Martes</td></tr>
	<tr><td>Columna 5 = Dimensionado de personal del Miercoles</td></tr>
	<tr><td>Columna 6 = Dimensionado de personal del Jueves</td></tr>
	<tr><td>Columna 7 = Dimensionado de personal del Viernes</td></tr>
	<tr><td>Columna 8 = Dimensionado de personal del Sábado</td></tr>
	<tr><td>Columna 9 = Dimensionado de personal del Domingo</td></tr>
	
</table>
</fieldset>
    <input type="hidden" name=hddsemana id=hddsemana value="<?=$semana?>">
    <input type="hidden" name=hddanio id=hddanio value="<?=$anio?>">
    <input type="hidden" name=hddfechainicio id=hddfechainicio value="<?=$fecha_inicio?>">
    <input type="hidden" name=hddfechafin id=hddfechafin value="<?=$fecha_fin?>">
</form>  
</body>
</html> 