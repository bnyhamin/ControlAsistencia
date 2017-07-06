<?php
    header("Expires: 0"); 
    session_start();
    require_once("../../Includes/Connection.php");
    require_once("../../Includes/Constantes.php"); 
    require_once("../../Includes/mantenimiento.php");
    require_once("../includes/clsCA_Asistencias.php");
    require_once("../includes/clsCA_Asistencia_Incidencias.php");
    require_once("../includes/clsCA_Asignacion_Empleados.php");
    $horaI=0;
    $accion='';
    $flag=0;
    $flagmasivo=0;
    $minutoI=0;
    $incidencia_codigoI=0;
    $action="";
    
    if(isset($_GET["codigos"])) $codigos=$_GET["codigos"];
    if(isset($_GET["flagmasivo"])) $flagmasivo=$_GET["flagmasivo"];
    if(isset($_GET["action"])) $action=$_GET["action"];
    
    $usuario=$_SESSION["empleado_codigo"];
    
    $arr=array();
    $arrfilas=array();
    $arrfila=array();
    if($action=="APR"){
        $arrfilas=split(",",$codigos);
        for($jj=0;$jj<count($arrfilas);$jj++){
            $arrfila=split("-",$arrfilas[$jj]);
            if(!in_array($arrfila[3], $arr)){
                $arr[$jj]=$arrfila[3];
            }
            
        }
        
        if(count($arr)>1){
?>
                <script type="text/javascript">
                    alert('No puede validar incidencias de diferente tipo\nRealize filtro por incidencia\nLuego Proceda a validar');
                    window.opener.PooBV.pocisionIncidencia();
                    window.close();
                </script>
<?php        
        }
    }
    
    
    
    if(isset($_GET["h"])) $horaI=$_GET["h"];
    if(isset($_GET["m"])) $minutoI=$_GET["m"];
    if(isset($_GET["s"])) $incidenciaI=$_GET["s"];
    
    $hora=isset($_POST["horas"]) ? $_POST["horas"] : -1;
    $minuto=isset($_POST["minutos"]) ? $_POST["minutos"] : -1;
    $incidencia_codigo_sustituye=isset($_POST["txtIncidencias"]) ? $_POST["txtIncidencias"] : -1;

    $ase = new ca_asignacion_empleados();
    $ase->MyUrl = db_host();
    $ase->MyUser= db_user();
    $ase->MyPwd = db_pass();
    $ase->MyDBName= db_name();
	
	$a=new ca_asistencia();//N
    $a->setMyUrl(db_host());//N
    $a->setMyUser(db_user());//N
    $a->setMyPwd(db_pass());//N
    $a->setMyDBName(db_name());//N
    
    $asistencia_fecha="";//N
    
    
    if(isset($_GET["c"])){
        $cI=split(",",$_GET["c"]);
        $codigos=$cI[0]."-".$cI[1]."-".$cI[2]."-".$cI[3];
        $ase->empleado_codigo=$cI[0];
        $ase->asistencia_codigo=$cI[1];
        $ase->evento_codigo=$cI[2];
        $ase->getHMV();//ok
        $incidencia_codigoI=$ase->incidencia_codigo_sustituye;
        $incidencia_descripcionI=$ase->incidencia_descripcion;
    }
    
    $ainc=new ca_asistencia_incidencias();
    $ainc->MyUrl = db_host();
    $ainc->MyUser= db_user();
    $ainc->MyPwd = db_pass();
    $ainc->MyDBName= db_name();
    
    if (isset($_POST['hddaccionB'])){
       
        if ($_POST['hddaccionB']=='APR' || $_POST['hddaccionB']=='RCH'){
            
            $codigos=$_POST['hddCodigosB'];
            if($_POST['hddflagmasivoB']=="MSV" || $_POST['hddflagmasivoB']=="UNC"){
       
                $arrEventosB=split(",",$codigos);
                for($i=0;$i<count($arrEventosB);$i++){
                    $arrEventoB=split("-",$arrEventosB[$i]);
                    for($j=0;$j<count($arrEventoB);$j++){
                        if($j==0) $emp_codigos = $arrEventoB[0];//P
                        if($j==1) $asistencia_codigos = $arrEventoB[1];//P
                        if($j==2) $evento_codigos = $arrEventoB[2];
                        if($j==3) $incidencia_codigos = $arrEventoB[3];//P
                    }
                    
                    $ase->empleado_codigo=$emp_codigos;
                    $ase->asistencia_codigo=$asistencia_codigos;
                    $ase->evento_codigo=$evento_codigos;
                    $ase->incidencia_codigo=$incidencia_codigos;
                    
                    
                    //variables para el nuevo metodo
                    $num="1";//P1
                    $ip_entrada=$_SERVER['REMOTE_ADDR'];//P2
                    $ip_salida=$_SERVER['REMOTE_ADDR'];//P3
                    $fecha=date("d/m/Y");//P5
					
					$a->empleado_codigo=$emp_codigos;//N
                    $a->asistencia_codigo=$asistencia_codigos;//N
                    $asistencia_fecha=$a->get_Asistencia_Fecha();//N
					
                    $flgproceso=1;//P4
                    $ainc->incidencia_codigo=$incidencia_codigos;
                    $incidencia_hh_dd=$ainc->tipoIncidencia();//P
                    //variables para el nuevo metodo
                    $ase->validaEstadoEvento();//ok
                    
                    if($ase->estado_evento==2 || $ase->estado_evento==5){//proceso-observado
                    
                        $ainc->host_mail = mail_host();
                        $ainc->from_mail = mail_from();
                        
                        $estado=$_POST['hddaccionB']=="RCH" ? 4 : 3;//rechazo-apruebo
                        //$ase->estado_evento = $estado;
                        //$ase->cambiar_estado_evento();
                        $ase->hora=NULL;
                        $ase->minuto=NULL;
                        $ase->incidencia_codigo_sustituye=NULL;
                        if($estado==4){
                            if($hora!=-1) $ase->hora = $hora;//pasar nulos
                            if($minuto!=-1) $ase->minuto = $minuto;
                            if($incidencia_codigo_sustituye!=-1) $ase->incidencia_codigo_sustituye=$_POST["hdd_cod_incidencia"];
                            
                            if($hora==-1 && $ase->getCHMS()==1){//ok
                                $ase->getHMS();//ok
                                $ase->hora = $ase->hora;
                                $ase->minuto=$ase->minuto;
                            }    
                        }
                        if($estado==3){
                            $ase->empleado_codigo=$emp_codigos;
                            $ase->asistencia_codigo=$asistencia_codigos;
                            $ase->evento_codigo=$evento_codigos;
                            $ase->hora = $hora;
                            $ase->minuto = $minuto;
                            if($incidencia_codigo_sustituye!=-1){
                                $ase->incidencia_codigo_sustituye=$_POST["hdd_cod_incidencia"];
                            }else{
                                $ase->incidencia_codigo_sustituye=NULL;
                            }
                            if($hora==-1 && $incidencia_codigo_sustituye==-1){
                                $ase->getHMLog();//ok
                                $ase->hora=$ase->hora;
                                $ase->minuto=$ase->minuto;
                                
                                if($ase->incidencia_codigo_sustituye!=-1){
                                    $ase->incidencia_codigo_sustituye=$ase->incidencia_codigo_sustituye;
                                }else{
                                    $ase->incidencia_codigo_sustituye=NULL;
                                }   
                            }
                            if($hora==-1 && $incidencia_codigo_sustituye!=-1){
                                $ase->getHMLog();//ok
                                $ase->hora=$ase->hora;
                                $ase->minuto=$ase->minuto;
                                $ase->incidencia_codigo_sustituye=$_POST["hdd_cod_incidencia"];
                            }
                            $ase->actualizar_evento();//ok
                        }
                        
                        
                        $ase->obtener_incidencia();
                        $ase->empleado_codigo=$emp_codigos;
                        $ase->obtener_empleado();
                        $aprobado=$_POST['hddaccionB']=="RCH" ? "N" : "S";
                        $desc_susti=($incidencia_codigo_sustituye==-1) ? "" : $incidencia_codigo_sustituye;
                        $ase->aprobado=$aprobado;
                        $ase->texto_descripcion=$_POST["comentario"]." <b>-Incidencia admitida: ".$desc_susti."</b>";
                        $ase->empleado_jefe=$usuario;
                        $ase->empleado_ip=$_SERVER['REMOTE_ADDR'];
                        $ase->realizado='V';
                        $ase->incidencia_codigo=$incidencia_codigos;
                        
                        $rpta=$ase->registrar_evento();
						$ase->estado_evento = $estado;
                        $ase->cambiar_estado_evento();
						
                        $cantidad=$ase->cantidad_rechazos();
                        if($cantidad==1) $ase->desactivar_evento();
                        
                        if($estado==3){
							if($ase->flag_validable*1==1){
								$ainc->empleado_codigo=$emp_codigos;
								$rpta=$ainc->Obtener_servicio_empleado();
								$servicio=$ainc->cod_campana;
								$ainc->empleado_codigo=$emp_codigos;
								$ainc->evento_codigo=$evento_codigos;
								$ainc->asistencia_codigo=$asistencia_codigos;
								$ainc->incidencia_codigo=$incidencia_codigos;
								$ainc->cod_campana = $servicio;
								$responsable=$ainc->obtener_codigo_supervisor();
								$ainc->responsable_codigo = $responsable;
								//incidencias horarias diarias...
								$ainc->fecha=$asistencia_fecha;
								$ainc->flgproceso=$flgproceso;
								$ainc->incidencia_hh_dd=$incidencia_hh_dd;
								$area=$ase->obtener_area_supervisor();
								$ainc->area_codigo=$area;
								$ainc->codigo_empresa=1;
								$ainc->ip_entrada=$ip_entrada;
								$ainc->ip_registro=$ip_entrada;
								$ase->desactivar_evento();
								$mensaje=$ainc->registrar_incidencia($num,$ip_entrada,$ip_salida);
							}
                        }       
                    }
                }
                $msgsend="OK";
                if($msgsend=="OK"){
?>
                    <script type="text/javascript">
                        window.opener.PooBV.actualizar();
                        window.close();
                    </script>
<?php
                }
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
<script type="text/javascript" src="app/app.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css"/>
<script type="text/javascript">
    function accion(){
        
    }
    
function aprobar(){
    var registros = document.frm.hddCodigosB.value;
    if (registros =="") return false;
    var arrIndicador=registros.split(",");
    
    if(arrIndicador.length>1){
        document.frm.hddflagmasivoB.value = "MSV";
    }else{
        document.frm.hddflagmasivoB.value = "UNC";
    }
    
    if(parseInt(document.frm.comentario.value.length)<2){
        alert('Debe ingresar un comentario');
        document.frm.comentario.focus();
        return false;
    }
    
    if(document.frm.hddaccionB.value!="RCH"){
        if(document.frm.chk_tiempo.checked){
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
    }
    
    document.frm.hddCodigosB.value = registros;
    
    if (confirm('¿Desea Grabar Datos?')==false) return false;
    
    document.frm.submit();
}


function habilita(codigo){
    if(parseInt(codigo)==1){
        //valida horas
        if(document.frm.chk_tiempo.checked==true){
            document.frm.horas.disabled=false;
            document.frm.minutos.disabled=false;
        }else if(document.frm.chk_tiempo.checked==false){
            document.frm.horas.disabled=true;
            document.frm.minutos.disabled=true;
        }
    }else{
        if(document.frm.chk_incidencia.checked==true){
            $('#txtIncidencias').removeAttr("disabled");
            $('#txtIncidencias').attr("readonly","true");
        }else if(document.frm.chk_incidencia.checked==false){
            $('#txtIncidencias').removeAttr("readonly");
            $('#txtIncidencias').attr('disabled',true);   
        }   
    }
}

</script>
</head>
<body class="PageBODY">
    <center style="text-align: center; font-weight: bold;font-size: 13px;">Validaci&oacute;n de Eventos</center>
    <?php
        if($action=="APR"){
            $accion="Aprobación";
            $flag=1;
        } 
        if($action=="RCH"){
            $accion="Rechazo";
            $flag=2;
        } 
        
    ?>
<form id="frm" name="frm" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
    
<table width='80%' align="center" border="0" cellspacing="0" cellpadding="1">
<tr>
    <td class='ColumnTD' colspan='2' align='center'>Comentario de <?php echo $accion;?></td>
</tr>
<tr>
    
    <td align="center">
        <textarea  name="comentario" id="comentario" rows="5" cols="30"></textarea> 
    </td>
</tr>



<?php
    if($flag==1){
?>

<tr>
    <td align="center">
        <b>Acciones a realizar:</b>
    </td>
</tr>

<tr>
    <td>
        <Input class="Input" type="checkbox" name="chk_tiempo" id="chk_tiempo" value="1" onclick="javascript:habilita(1);" <?php if(isset ($_GET["h"])){ echo "checked"; }?>/>
        Modificar tiempo
        &nbsp;<select   name="horas" id="horas" <?php if(isset ($_GET["h"])){}else{echo "disabled";}?>>
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
                     
		   }
		  ?>
		 </select>
		 &nbsp;
                 <select  name="minutos" id="minutos" <?php if(isset($_GET["m"])){}else{ echo "disabled"; }?>>
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
		      
		   }
		  ?>
		 </select>
    </td>
</tr>
<?php
    }
?>

<?php
    if($flag==1){
        $codigos=isset($_GET["codigos"]) ? $_GET["codigos"] : $codigos ;
        $arrEventos=split(",",$codigos);
        $arrRegistro=split("-", $arrEventos[0]);
        $incidencia_codigo=$arrRegistro[3];
        
        $ase->incidencia_codigo=$incidencia_codigo;
        $ase->obtener_incidencia_sustituible();//ok

        $incidencia_codigo_sustituye=$ase->incidencia_codigo_sustituye;
        $incidencia_descripcion_sustituye=$ase->incidencia_descripcion_sustituye;
        
        if(isset($_GET["s"]) && $incidencia_codigoI!=""){//no considerar
            $incidencia_codigo_sustituye=$incidencia_codigoI;
            $incidencia_descripcion_sustituye=$incidencia_descripcionI;
        }
    }
?>

<?php
    if($flag==1 && $incidencia_codigo_sustituye!="0"){
?>
<tr>
    <td>
        <Input class="Input" type="checkbox" name="chk_incidencia" id="chk_incidencia" value="1" onclick="javascript:habilita(2);" <?php if($incidencia_codigoI!=""){ echo "checked"; } //if(isset($HTTP_GET_VARS["s"])) echo "checked"; ?>/>
        Cambiar Incidencia&nbsp;
        <Input class="Input" type="text" name="txtIncidencias" id="txtIncidencias" value="<?php echo $incidencia_descripcion_sustituye; ?>" size="40" <?php if($incidencia_codigoI==""){echo "disabled";}//if(isset($HTTP_GET_VARS["s"])){}else{echo "disabled";} ?> />
        
    </td>
</tr>
<?php
    }
?>
<tr>
	<td class='ColumnTD' >&nbsp;</td>
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
        
        <td class="ColumnTD" align=center width="300">Nombre</td>
        <td class='ColumnTD' align=center width="75">Evento</td>

  </tr>
<?php
        
    $codigos=isset($_GET["codigos"]) ? $_GET["codigos"] : $codigos ;
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
                
                echo $ase->obtener_evento();//ok

            }
    
?>
</table>

<Input type="hidden" name="hdd_cod_incidencia" id="hdd_cod_incidencia" value="<?php echo $incidencia_codigo_sustituye;?>" />
<input type="hidden" name="hddCodigosB" id="hddCodigosB" value="<?php echo $codigos;?>"/>
<input type="hidden" name="hddaccionB" id="hddaccionB" value="<?php echo $action;?>"/>
<input type="hidden" name="hddflagmasivoB" id="hddflagmasivoB" value="<?php echo $flagmasivo;?>"/>

</form>
<br/>

</body>
</html>
