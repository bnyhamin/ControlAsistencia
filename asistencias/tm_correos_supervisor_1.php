<?php
set_time_limit(0);
require_once(dirname(dirname(dirname(__FILE__)))."/Includes/Connection.php");
require_once(PathIncludes()."mantenimiento.php");
require_once(PathIncludesGAP()."clsCA_Envio_Correos.php");

$ase = new ca_envio_correos();
$ase->MyUrl = db_host();
$ase->MyUser= db_user();
$ase->MyPwd = db_pass();
$ase->MyDBName= db_name();

$ase->host_mail = mail_host();
$ase->from_mail = mail_from();


echo "iniciando...";
$ase->flag=1;
$ase->actualiza_hora();
echo "<br>enviando correo rechazos..<br>";
$rs_rechazos= $ase->obtener_listado_rechazos();
$responsables = array();
if($rs_rechazos->RecordCount() > 0){
    $email = "";
    $responsables = array();
    $personas = array();
    $persona = array();
    while(!$rs_rechazos->EOF){
        if(count($personas) > 0 && $rs_rechazos->fields[3] != $email){
            $responsables[] = $personas;
            $personas = array(); //reseteamos las personas
        }
        $persona[] = $rs_rechazos->fields[0]; //GUARDAMOS A LAS PERSONAS
        $persona[] = $rs_rechazos->fields[1]; //GUARDAMOS LA INCIDENCIA
        $persona[] = $rs_rechazos->fields[2]; //GUARDAMOS LA OBSERVACION
        $persona[] = $rs_rechazos->fields[3]; //GUARDAMOS EL EMAIL
        $personas[] = $persona;
        $persona  = array(); //reseteamos la persona 
        $email = $rs_rechazos->fields[3];
        $rs_rechazos->MoveNext();
    }
    $responsables[] = $personas;
}
foreach($responsables as $responsable){
    $tasesorRe="";$textoRe="";$asuntoRe="";
    $textoRe.="<span style=font-family:Arial;font-size:12px;>Estimado colaborador. <br/>";
    $textoRe.="Le informamos que los Sr(es). tienen las siguientes incidencias que han sido rechazadas por el area validadora. <br/><br/>";
    $tasesorRe.='<table cellpadding="0" cellspacing="0" border="1" style="border:1px solid blue;font-size:10px;font-family:Arial;">';
    $tasesorRe.="<tr style=background-color:#CFCFCF;><td>EMPLEADO</td><td>INCIDENCIA</td><td>COMENTARIOS</td></tr>";
    foreach($responsable as $persona){
        $empleado_correo = $persona[3];
        $tasesorRe.="<tr><td>".$persona[0]."</td><td>".$persona[1]."</td><TD>".$persona[2]."</TD></tr>";
    }
    $tasesorRe.="</table><br>";
    $textoRe.=$tasesorRe;
	$textoRe.="Ingrese al menu Asistencias submenu Eventos Supervisor del Sistema GAP<br/><br/>Atte.<br/>".SistemaNombreGap()."</span><br/><br/>";
    $asuntoRe="Rechazo de Incidencia";
    echo $textoRe;
    $msgsend=$ase->enviar_mail($empleado_correo,$asuntoRe, $textoRe);
    
    $msgsend=$ase->enviar_mail("mcortezc@atentoperu.com.pe",$asuntoRe." rechazos", $textoRe);
    echo "Envio: ".$msgsend;
}



/*original
$ase->actualiza_hora();
$arrSupervisor=$ase->obtener_responsable(); 
for($i=0;$i<count($arrSupervisor);$i++){
    $arrReg=$arrSupervisor[$i];
    $empleado_jefe=$arrReg["empleado_jefe"];
    $empleado_correo=$arrReg["empleado_correo"];
    $ase->responsable_codigo=$arrReg["empleado_jefe"];
    $arrAsesores=$ase->obtener_asesores();
    $arrPe=$arrAsesores["pen"];
    $arrRe=$arrAsesores["rec"];
    echo "enviando correos supervisor...";
    //correos rechazado
    if(count($arrRe)>0){
        $tasesorRe="";$textoRe="";$asuntoRe="";
        $textoRe.="<span style=font-family:Arial;font-size:12px;>Estimado colaborador. <br/>";
        $textoRe.="Le informamos que los Sr(es). tienen las siguientes incidencias que han sido rechazadas por el area validadora. <br/><br/>";
        $tasesorRe.='<table cellpadding="0" cellspacing="0" border="1" style="border:1px solid blue;font-size:10px;font-family:Arial;">';
        $tasesorRe.="<tr style=background-color:#CFCFCF;><td>EMPLEADO</td><td>INCIDENCIA</td><td>PLAZO PARA VALIDAR</td><td>COMENTARIOS</td></tr>";
        foreach ($arrRe as $v) {
            $tasesorRe.="<tr><td>".$v["asesor"]."</td><td>".$v["incidencia_descripcion"]."</td><td align=center>".$v["horas_vbo"]." dia util. </td><TD>".$v["comentario"]."</TD></tr>";
        }
        $tasesorRe.="</table><br>";
        $textoRe.=$tasesorRe;
		$textoRe.="Ingrese al menu Asistencias submenu Eventos Supervisor del Sistema GAP<br/><br/>Atte.<br/>".SistemaNombreGap()."</span><br/><br/>";
        $asuntoRe="Rechazo de Incidencia";
        $msgsend=$ase->enviar_mail($empleado_correo,$asuntoRe, $textoRe);
        echo "Envio: ".$msgsend;
        
    }
}
fin original*/

/*envia correos area validadora*/

echo "<br>enviando correo area validadora..<br>";
$responsables = array();
$personas = array();
$persona = array();
$rs_rechazos= $ase->obtener_listado_valida();
if($rs_rechazos->RecordCount() > 0){
    $email = "";
    while(!$rs_rechazos->EOF){
        if(count($personas) > 0 && $rs_rechazos->fields[4] != $email){
            $responsables[] = $personas;
            $personas = array(); //reseteamos las personas
        }
        $persona[] = $rs_rechazos->fields[0]; //GUARDAMOS A LAS PERSONAS
        $persona[] = $rs_rechazos->fields[1]; //GUARDAMOS LA INCIDENCIA
        $persona[] = $rs_rechazos->fields[2]; //GUARDAMOS EL PLAZO PARA VALIDAR
        $persona[] = $rs_rechazos->fields[3]; //AREA
        $persona[] = $rs_rechazos->fields[4]; //
        $personas[] = $persona;
        $persona  = array(); //reseteamos la persona 
        $email = $rs_rechazos->fields[4];
        $rs_rechazos->MoveNext();
    }
    $responsables[] = $personas;
}
foreach($responsables as $responsable){
    $tasesorP="";$textoP="";$asuntoP="";
    $textoP.="<span style=font-family:Arial;font-size:12px;>Estimado colaborador. <br/>";
    $textoP.="Le informamos que los Sr(es). tienen las siguientes incidencias por validar por Ud. <br/><br/>";
    $tasesorP.='<table cellpadding="1" cellspacing="1" border="0" style="border:1px solid blue;font-size:10px;font-family:Arial;">';
    $tasesorP.="<tr style=background-color:#CFCFCF;><td>EMPLEADO</td><td>INCIDENCIA</td><td>PLAZO PARA VALIDAR</td><td>AREA</td></tr>";
    foreach($responsable as $persona){
        $validador_correo = $persona[4];
        $tasesorP.="<tr><td>".$persona[0]."</td><td>".$persona[1];
        $tasesorP.="</td><td align=center>".$persona[2]." dia util.</td><td>".$persona[3]."</td></tr>";   
    }
    $tasesorP.="</table><br>";
    $textoP.=$tasesorP;
    $textoP.="Ingrese a la Opcion de Soporte del Sistema GAP<br/><br/>Atte.<br/>".SistemaNombreGap()."</span><br/><br/>";
    $asuntoP="Validación de Incidencia";
    echo "<br/>";echo $textoP;echo "<br/>";
    $msgsend=$ase->enviar_mail($validador_correo,$asuntoP, $textoP);
    
    $msgsend=$ase->enviar_mail("mcortezc@atentoperu.com.pe",$asuntoP." validador", $textoP);
    echo "Envio: ".$msgsend;
    
}

/*envia correos mando*/
echo "<br>enviando correo mandos..<br>";
$responsables = array();
$personas = array();
$persona = array();
$rs_listado= $ase->obtener_listado_valida_mando();
if($rs_listado->RecordCount() > 0){
    $email = "";
    while(!$rs_listado->EOF){
        if(count($personas) > 0 && $rs_listado->fields[4] != $email){
            $responsables[] = $personas;
            $personas = array(); //reseteamos las personas
        }
        $persona[] = $rs_listado->fields[0]; //GUARDAMOS A LAS PERSONAS
        $persona[] = $rs_listado->fields[1]; //GUARDAMOS LA INCIDENCIA
        $persona[] = $rs_listado->fields[2]; //GUARDAMOS EL PLAZO PARA VALIDAR
        $persona[] = $rs_listado->fields[3]; //AREA
        $persona[] = $rs_listado->fields[4]; //EMAIL
        $personas[] = $persona;
        $persona  = array(); //reseteamos la persona 
        $email = $rs_listado->fields[4];
        $rs_listado->MoveNext();
    }
    $responsables[] = $personas;
}
foreach($responsables as $responsable){
    $tasesorP="";$textoP="";$asuntoP="";
    $textoP.="<span style=font-family:Arial;font-size:12px;>Estimado colaborador. <br/>";
    $textoP.="Le informamos que los Sr(es). tienen las siguientes incidencias por validar por Ud. <br/><br/>";
    $tasesorP.='<table cellpadding="1" cellspacing="1" border="0" style="border:1px solid blue;font-size:10px;font-family:Arial;">';
    $tasesorP.="<tr style=background-color:#CFCFCF;><td>EMPLEADO</td><td>INCIDENCIA</td><td>PLAZO PARA VALIDAR</td><td>AREA</td></tr>";
    foreach($responsable as $persona){
        $validador_correo = $persona[4];
        $tasesorP.="<tr><td>".$persona[0]."</td><td>".$persona[1];
        $tasesorP.="</td><td align=center>".$persona[2]." dia util.</td><td>".$persona[3]."</td></tr>";   
    }
    $tasesorP.="</table><br>";
    $textoP.=$tasesorP;
    $textoP.="Ingrese a la Opcion de Soporte del Sistema GAP<br/><br/>Atte.<br/>".SistemaNombreGap()."</span><br/><br/>";
    $asuntoP="Validación de Incidencia";
    echo "<br/>";echo $textoP;echo "<br/>";
    $msgsend=$ase->enviar_mail($validador_correo,$asuntoP, $textoP);
    
    $msgsend=$ase->enviar_mail("mcortezc@atentoperu.com.pe",$asuntoP." mando", $textoP);
    echo "Envio: ".$msgsend;
    
}

/* INICIO ORIGINAL VALIDADORES
$arrValidador=$ase->obtener_validadores();
for($j=0;$j<count($arrValidador);$j++){
    $arrRegV=$arrValidador[$j];
    
    $empleado_validador=$arrRegV["empleado_validador"];
    $validador_correo=$arrRegV["validador_correo"];
    $ase->validador_codigo=$empleado_validador;
    $arrAs=$ase->obtener_incidencias_validador();
    $arrP=$arrAs["proc"];
    $arrO=$arrAs["obs"];
    echo "enviando correos validador...";
    //correos con estado en proceso
    if(count($arrP)>0){
        $tasesorP="";$textoP="";$asuntoP="";
        $textoP.="<span style=font-family:Arial;font-size:12px;>Estimado colaborador. <br/>";
        $textoP.="Le informamos que los Sr(es). tienen las siguientes incidencias por validar por Ud. <br/><br/>";
        $tasesorP.='<table cellpadding="1" cellspacing="1" border="0" style="border:1px solid blue;font-size:10px;font-family:Arial;">';
        $tasesorP.="<tr style=background-color:#CFCFCF;><td>EMPLEADO</td><td>INCIDENCIA</td><td>PLAZO PARA VALIDAR</td><td>SUPERVISOR</td><td>AREA</td></tr>";

        foreach ($arrP as $val) {
            $tasesorP.="<tr><td>".$val["asesor_nombre"]."</td><td>".$val["incidencia_descripcion"];
            $tasesorP.="</td><td align=center>".$val["horas_visacion"]." dia util.</td><td>".$val["supervisor_nombre"]."</td><td>".$val["area"]."</td></tr>";   
        }
        $tasesorP.="</table><br>";
        $textoP.=$tasesorP;
        $textoP.="Ingrese a la Opcion de Soporte del Sistema GAP<br/><br/>Atte.<br/>".SistemaNombreGap()."</span><br/><br/>";
        $asuntoP="Validación de Incidencia";
        //echo $validador_correo."<br/>";echo $asuntoP."<br/>";echo $textoP;echo "<br/>";
        $msgsend=$ase->enviar_mail($validador_correo,$asuntoP, $textoP);
        echo "Envio: ".$msgsend;
        
    }
}
FIN ORIGINAL*/


//envia correos gerente
echo "<br>enviando correo gerente..<br>";
$responsables = array();
$personas = array();
$persona = array();
$rs_listado= $ase->obtener_listado_valida_gerente();
if($rs_listado->RecordCount() > 0){
    $email = "";
    while(!$rs_listado->EOF){
        if(count($personas) > 0 && $rs_listado->fields[4] != $email){
            $responsables[] = $personas;
            $personas = array(); //reseteamos las personas
        }
        $persona[] = $rs_listado->fields[0]; //GUARDAMOS A LAS PERSONAS
        $persona[] = $rs_listado->fields[1]; //GUARDAMOS LA INCIDENCIA
        $persona[] = $rs_listado->fields[2]; //GUARDAMOS EL PLAZO PARA VALIDAR
        $persona[] = $rs_listado->fields[3]; //AREA
        $persona[] = $rs_listado->fields[4]; //EMAIL
        $personas[] = $persona;
        $persona  = array(); //reseteamos la persona 
        $email = $rs_listado->fields[4];
        $rs_listado->MoveNext();
    }
    $responsables[] = $personas;
}
foreach($responsables as $responsable){
    $tasesorP="";$textoP="";$asuntoP="";
    $textoP.="<span style=font-family:Arial;font-size:12px;>Estimado colaborador. <br/>";
    $textoP.="Le informamos que los Sr(es). tienen las siguientes incidencias por validar por Ud. <br/><br/>";
    $tasesorP.='<table cellpadding="1" cellspacing="1" border="0" style="border:1px solid blue;font-size:10px;font-family:Arial;">';
    $tasesorP.="<tr style=background-color:#CFCFCF;><td>EMPLEADO</td><td>INCIDENCIA</td><td>PLAZO PARA VALIDAR</td><td>AREA</td></tr>";
    foreach($responsable as $persona){
        $validador_correo = $persona[4];
        $tasesorP.="<tr><td>".$persona[0]."</td><td>".$persona[1];
        $tasesorP.="</td><td align=center>".$persona[2]." dia util.</td><td>".$persona[3]."</td></tr>";   
    }
    $tasesorP.="</table><br>";
    $textoP.=$tasesorP;
    $textoP.="Ingrese a la Opcion de Soporte del Sistema GAP<br/><br/>Atte.<br/>".SistemaNombreGap()."</span><br/><br/>";
    $asuntoP="Validación de Incidencia";
    echo "<br/>";echo $textoP;echo "<br/>";
    $msgsend=$ase->enviar_mail($validador_correo,$asuntoP, $textoP);
    
    $msgsend=$ase->enviar_mail("mcortezc@atentoperu.com.pe",$asuntoP." gerente", $textoP);
    
    echo "Envio: ".$msgsend;
    
}




/*
$arrGerente=$ase->get_cab_mando_gerente('G');
for($j=0;$j<count($arrGerente);$j++){
	$arrRegGerente=$arrGerente[$j];
    $empleado_validador=$arrRegGerente["empleado_validador"];
    $validador_correo=$arrRegGerente["validador_correo"];
    $ase->validador_codigo=$empleado_validador;
    $arrAs=$ase->get_det_mando_gerente('G');
    $arrP=$arrAs["proc"];
	//correos con estado en proceso
	echo "enviando correos gerente...";
	if(count($arrP)>0){
        $tasesorP="";$textoP="";$asuntoP="";
        $textoP.="<span style=font-family:Arial;font-size:12px;>Estimado colaborador. <br/>";
        $textoP.="Le informamos que los Sr(es). tienen las siguientes incidencias por validar por Ud. <br/><br/>";
        $tasesorP.='<table cellpadding="1" cellspacing="1" border="0" style="border:1px solid blue;font-size:10px;font-family:Arial;">';
        $tasesorP.="<tr style=background-color:#CFCFCF;><td>EMPLEADO</td><td>INCIDENCIA</td><td>PLAZO PARA VALIDAR</td><td>SUPERVISOR</td><td>AREA</td></tr>";

        foreach ($arrP as $val) {
            $tasesorP.="<tr><td>".$val["asesor_nombre"]."</td><td>".$val["incidencia_descripcion"];
            $tasesorP.="</td><td align=center>".$val["horas_visacion"]." dia util.</td><td>".$val["supervisor_nombre"]."</td><td>".$val["area"]."</td></tr>";   
        }
        $tasesorP.="</table><br>";
        $textoP.=$tasesorP;
        $textoP.="Ingrese a la Opcion de Autorizacion del Sistema GAP<br/><br/>Atte.<br/>".SistemaNombreGap()."</span><br/><br/>";
        $asuntoP="Validación de Incidencia";
        //echo $validador_correo."<br/>";echo $asuntoP."<br/>";echo $textoP;echo "<br/>";
        $msgsend=$ase->enviar_mail($validador_correo,$asuntoP, $textoP);
        echo "Envio: ".$msgsend;
    }
}*/
$ase->flag=0;
$ase->actualiza_hora();

?>