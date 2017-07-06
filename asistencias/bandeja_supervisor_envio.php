<?php
    header("Expires: 0"); 
    session_start();
    require_once("../../Includes/Connection.php");
    require_once("../../Includes/Constantes.php"); 
    require_once("../../Includes/mantenimiento.php");
    require_once("../includes/clsCA_Asistencia_Incidencias.php");
    require_once("../includes/clsCA_Asignacion_Empleados.php");
    
    $codigos=$_GET["codigos"];
    $flagmasivo=$_GET["flagmasivo"];
    $action=$_GET["action"];
    $supervisor_codigo=$_SESSION["empleado_codigo"];
    
    $ase = new ca_asignacion_empleados();
    $ase->MyUrl = db_host();
    $ase->MyUser= db_user();
    $ase->MyPwd = db_pass();
    $ase->MyDBName= db_name();
    
    $ainc=new ca_asistencia_incidencias();
    $ainc->MyUrl = db_host();
    $ainc->MyUser= db_user();
    $ainc->MyPwd = db_pass();
    $ainc->MyDBName= db_name();
    
    $horaI="";
    $minutoI="";
    $emp_codigo="";
    $asis_codigo="";
    $even_codigo="";
    $inci_codigo="";
    
    $horas=isset($_POST["horas"]) ? $_POST["horas"] : -1; 
    $minutos=isset($_POST["minutos"]) ? $_POST["minutos"] : -1;
    
    if($flagmasivo=="UNC"){
        $arrF=split("-",$codigos);
        $emp_codigo=$arrF[0];
        $asis_codigo=$arrF[1];
        $even_codigo=$arrF[2];
        $inci_codigo=$arrF[3];
        $ase->empleado_codigo=$emp_codigo;
        $ase->asistencia_codigo=$asis_codigo;
        $ase->evento_codigo=$even_codigo;
        $ase->incidencia_codigo=$inci_codigo;
        $ase->tipo='A';
        $ase->getHMS();
        $horaI = $ase->hora;
        $minutoI =$ase->minuto;
        
    }else{
        $horaI="-1";
        $minutoI="-1";
    }
    
    
    if (isset($_POST['hddaccionB'])){
        if ($_POST['hddaccionB']=='APR' || $_POST['hddaccionB']=='OBS'){
            
            $codigos=$_POST['hddCodigosB'];
            if($_POST['hddflagmasivoB']=="MSV" || $_POST['hddflagmasivoB']=="UNC"){
                $arrEventosB=split(",",$codigos);
            
                for($i=0;$i<count($arrEventosB);$i++){
                    $arrEventoB=split("-",$arrEventosB[$i]);
                    for($j=0;$j<count($arrEventoB);$j++){    
                        if($j==0) $emp_codigox = $arrEventoB[0];
                        if($j==1) $asistencia_codigox = $arrEventoB[1];
                        if($j==2) $evento_codigox = $arrEventoB[2];
                        if($j==3) $incidencia_codigox = $arrEventoB[3];
                    }
                    
                    $ase->empleado_codigo=$emp_codigox;
                    $ase->asistencia_codigo=$asistencia_codigox;
                    $ase->evento_codigo=$evento_codigox;
                    $ase->incidencia_codigo=$incidencia_codigox;
                    
                    $ase->validaEstadoEvento();
                    
                    if($ase->estado_evento==1 || $ase->estado_evento==4){//pendiente-rechazado
                        
                        $estado_codigo=$ase->estado_evento==1 ? 2 : 5;
                        $flag_codigo=$ase->estado_evento;
                        
                        $ase->estado_evento = $estado_codigo;//2 en proceso 5 observado
                        
                        $ase->cambiar_estado_evento();
                        //echo $horas."-".$minutos."----".$estado_codigo;
                        if($estado_codigo==2){
                            if($horas!=-1){
                                $ase->hora=$horas;
                                $ase->minuto=$minutos;
                            }
                            if($horas==-1){
                                $ase->tipo='A';
                                $ase->getHMS();
                                $ase->hora = $ase->hora;
                                $ase->minuto=$ase->minuto;
                            }
                        }
                        
                        //echo "<br/>";
                        //echo $ase->hora."-".$ase->minuto;
                        
                        //exit(0);
                        //agregado
                        if($estado_codigo==5){//*
                            $ase->getHMV();//*
                        }//*
                        
                        $ase->obtener_incidencia();//obtenemos datos de incidencia
                        $ase->empleado_codigo=$emp_codigox;
                        $ase->obtener_empleado();//obtenemos datos del asesor
                        
                        $ase->aprobado="S";
                        $ase->texto_descripcion=$_POST["comentario"];
                        $ase->empleado_jefe=$supervisor_codigo;
                        $ase->empleado_ip=$_SERVER['REMOTE_ADDR'];
                        $ase->realizado='S';
                        //registro datos en el log
                        if($estado_codigo==5){
                            $ase->hora=$horas;//**
                            $ase->minuto=$minutos;//**
                        }
                        $ase->incidencia_codigo=$incidencia_codigox;//**
                        $ase->incidencia_codigo_sustituye=$ase->incidencia_codigo_sustituye;//**
                        
                       $rpta=$ase->registrar_evento();
                             
                    }
                }
                
                if($rpta=="OK"){
?>
                    <script type="text/javascript">
                        window.opener.PooBS.submitea();
                        window.close();
                    </script>
<?php
                }
            }
?>
<?php
        }else if ($_POST['hddaccionB']=='RCH' || $_POST['hddaccionB']=='CERRAR'){
            $codigos=$_POST['hddCodigosB'];
            $arrEventosB=split(",",$codigos);
            for($i=0;$i<count($arrEventosB);$i++){
                $arrEventoB=split("-",$arrEventosB[$i]);
                for($j=0;$j<count($arrEventoB);$j++){    
                    if($j==0) $emp_codigox = $arrEventoB[0];
                    if($j==1) $asistencia_codigox = $arrEventoB[1];
                    if($j==2) $evento_codigox = $arrEventoB[2];
                    if($j==3) $incidencia_codigox = $arrEventoB[3];
                }
                
                $ase->empleado_codigo=$emp_codigox;
                $ase->asistencia_codigo=$asistencia_codigox;
                $ase->evento_codigo=$evento_codigox;
                $ase->incidencia_codigo=$incidencia_codigox;
                $ase->aprobado="N";
                
                $estado=$_POST['hddaccionB']=="RCH" ? 4 : 6;//rechazo-cierro/terminar
                
                $ase->estado_evento = $estado;
                $ase->cambiar_estado_evento();
                
                $ase->texto_descripcion=$_POST["comentario"];
                $ase->empleado_jefe=$supervisor_codigo;
                $ase->empleado_ip=$_SERVER['REMOTE_ADDR'];
                
                $rpta=$ase->desactivar_evento();
                $ase->registrar_evento();
                
            }
            
            if($rpta=="OK"){
?>
                    <script type="text/javascript">
                        window.opener.PooBS.submitea();
                        window.close();
                    </script>
<?php
            }
        }
    }
    
?>
 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Bandeja de Reportes</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css"/>
<script type="text/javascript">
function aprobar(){
    var registros = document.frm.hddCodigosB.value;
    if (registros =="") return false;
    var arrIndicador=registros.split(",");
    
    if(arrIndicador.length>1){
        document.frm.hddflagmasivoB.value = "MSV";
    }else{
        document.frm.hddflagmasivoB.value = "UNC";
    }
    
    document.frm.hddCodigosB.value = registros;
    //CERRAR-APR(RCH)
    //if(document.frm.hddaccionB.value!="CERRAR"){
    if(document.frm.hddaccionB.value=="OBS"){
        if(parseInt(document.frm.horas.value)==-1){
            alert('Debe Seleccionar Hora');
            document.frm.horas.focus();
            return false;
        }   
        if(parseInt(document.frm.minutos.value)==-1 || parseInt(document.frm.minutos.value)==0){
            if(parseInt(document.frm.horas.value)==0){
                alert('Debe Seleccionar Minuto');
                document.frm.minutos.focus();
                return false;
            }
        }
    }
    if (confirm('¿Desea Grabar Datos?')==false) return false;
    document.frm.submit();
}
</script>
</head>
<body class="PageBODY">
    <center style="text-align: center; font-weight: bold;font-size: 13px;">Aprobaci&oacute;n de Eventos</center>
<?php
    if($action=="APR") $accion="Aprobación";
    if($action=="RCH") $accion="Rechazo";
    if($action=="OBS") $accion="Observación";
?>
<form id="frm" name="frm" method="post" action="<?php echo $HTTP_SERVER_VARS["PHP_SELF"];?>">
<table width='75%' align="center" border=0 cellspacing="0" cellpadding="1">
<tr>
    <td class='ColumnTD' colspan='2' align='center'>Comentario de <?php echo $accion;?> de Eventos</td>
</tr>
<tr>
    
    <td align="center">
        <textarea  name="comentario" id="comentario" rows="5" cols="30"></textarea> 
    </td>
</tr>


<tr><td height="3">&nbsp;</td></tr>
<tr>
    <td align="left">
        Modificar tiempo
        &nbsp;<select   name="horas" id="horas">
		  <option value="-1">hh</option>
		  <?php
		   for($h=0; $h < 24; $h++){
		     $hh=$h;
                    if(strlen($h)<=1) $hh="0".$h;
                    if($h==$horaI){
                        echo "\t<option value=". $h . " selected>". $hh ."</option>" . "\n";
                    }else{       
                        echo "\t<option value=". $h . ">". $hh ."</option>" . "\n";
                    }
                     //echo "\t<option value=". $h . ">". $hh ."</option>" . "\n";
		   }
		  ?>
		 </select>
		 &nbsp;
                 <select  name="minutos" id="minutos">
		  <option value="-1">mm</option>
		  <?php
		   for($m=0; $m < 60; $m++){
		     $mm=$m;
                    if(strlen($m)<=1) $mm="0".$m;
                    if($minutoI==$m){
                        echo "\t<option value=". $m . " selected>". $mm ."</option>" . "\n";
                    }else{
                        echo "\t<option value=". $m . ">". $mm ."</option>" . "\n";
                    }
		      //echo "\t<option value=". $m . ">". $mm ."</option>" . "\n";
		   }
		  ?>
		 </select>
    </td>
</tr>
<tr><td height="3">&nbsp;</td></tr>




<tr>
	<td class='ColumnTD' colspan='1'>&nbsp;</td>
</tr>
</table>

<table width='400px' align='center' cellspacing='0' cellpadding='0' border='0'>
<tr align='center'>
 <td colspan=2  >
     <input name="cmdAprobar" id="cmdAprobar" type="button" value="Aceptar"  class="Button"  style="WIDTH: 90px;" onclick="javascript:aprobar();"/>
 </td>
</tr>
</table>
    
<br/>
<table class='FormTable' width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
  <tr>
        <!--<td class='ColumnTD' align=center width="40">Código</td>-->
        <td class="ColumnTD" align=center width="300">Nombre</td>
        <td class='ColumnTD' align=center width="75">Evento</td>

  </tr>
<?php
        
    $codigos=isset($HTTP_GET_VARS["codigos"]) ? $HTTP_GET_VARS["codigos"] : $codigos ;
    $arrEventos=split(",",$codigos);
            for($i=0;$i<count($arrEventos);$i++){
                $arrEvento=split("-",$arrEventos[$i]);
                for($j=0;$j<count($arrEvento);$j++){    
                    if($j==0) $emp_codigo = $arrEvento[0];
                    if($j==1) $asistencia_codigo = $arrEvento[1];
                    if($j==2) $evento_codigo = $arrEvento[2];
                    if($j==3) $incidencia_codigo = $arrEvento[3];
                }
                
                $ase->empleado_codigo=$emp_codigo;
                $ase->asistencia_codigo=$asistencia_codigo;
                $ase->evento_codigo=$evento_codigo;
                $ase->incidencia_codigo=$incidencia_codigo;
                
                echo $ase->obtener_evento();

            }
    
?>
</table>

<input type="hidden" name="hddCodigosB" id="hddCodigosB" value="<?php echo $codigos;?>"/>
<input type="hidden" name="hddaccionB" id="hddaccionB" value="<?php echo $action;?>"/>
<input type="hidden" name="hddflagmasivoB" id="hddflagmasivoB" value="<?php echo $flagmasivo;?>"/>

</form>
<br/>

</body>
</html>
