<?php
    require('../../Includes/MyCombo.php');
    require('../../Includes/Connection.php');
    require('../../Includes/Constantes.php');
    //require('../../includes/mantenimiento.php');
    require_once('../includes/clsca_movilidad_unidad.php');
    require_once('../includes/clsca_movilidad.php');
    require('../../Includes/clsEmpleados.php');
    $msg='';
    $msg1='';
    $nmes='';
    $nanio='';
    $ndia='';
    //$espera=5;
    $rcodigos='';
    $dias='';
    $title_ruta='';
    $movil_unidad_codigo='';
    $movil_tipo_codigo='';
    $ruta_codigo='0';

    if (isset($_POST['nmes'])){
    	$nmes = $_POST['nmes'];
    	$smes = strftime('%b',mktime(0,0,0,$nmes+1,0,0));
    	$nanio = $_POST['nanio'];
    	$ndia = $_POST['ndia'];
    }

    if ($nmes==''){
     	$fecha = getdate();
    	$nmes = $fecha['mon'];
    	$smes = $fecha['month'];
    	$nanio = $fecha['year'];
    	$ndia= $fecha['mday'];
     }

    $numdias = date('t',mktime(0,0,0,$nmes+1,0,$nanio));
    $lerdia = date('w',mktime(0,0,0,$nmes,1,$nanio));

    $o = new ca_movilidad();
    $o->setMyUrl(db_host());
    $o->setMyUser(db_user());
    $o->setMyPwd(db_pass());
    $o->setMyDBName(db_name());

    $u = new ca_movilidad_unidad();
    $u->setMyUrl(db_host());
    $u->setMyUser(db_user());
    $u->setMyPwd(db_pass());
    $u->setMyDBName(db_name());

    $e = new Empleados();
    $e->setMyUrl(db_host());
    $e->setMyUser(db_user());
    $e->setMyPwd(db_pass());
    $e->setMyDBName(db_name());
    
    //Esta parte llega via $_GET
    if (isset($_GET['empleado'])) $id=$_GET['empleado'];
    if (isset($_POST['hddempleado'])) $id=$_POST['hddempleado'];

    $o->Empleado_Codigo=$id;
    $sRpta = $o->Query();

    $e->empleado_codigo=$id;
    $sRpta = $e->Query();

	//--obtener registro del dia
	$sRpta = $o->Query_registro();
	$ruta_codigo=$o->ruta_codigo;
    $movil_unidad_codigo=$o->movil_unidad_codigo;
    $movil_tipo_codigo=$o->movil_tipo_codigo;

    /*----- 123 -----*/
    $car  = "<br>local_codigo: ".$e->local_codigo."<br>";
    $car .= "movil_tipo_codigo: ".$movil_tipo_codigo."<br>";
    $car .= "ruta_codigo: ".$ruta_codigo."<br>";
    $car .= "movil_unidad_codigo: ".$movil_unidad_codigo."<br>";
    //echo $car;

    //fecha_del_dia_de_hoy
    $fecha_hoy = getdate();
	$dia_hoy = $fecha_hoy['mday'];
	$mes_hoy = $fecha_hoy['mon'];
	$anio_hoy = $fecha_hoy['year'];

	$fecha_pedida=$dia_hoy."/".$mes_hoy."/".$anio_hoy;

    if (isset($_POST['ruta_codigo'])) $ruta_codigo=$_POST['ruta_codigo'];
    if (isset($_POST['movil_unidad_codigo'])) $movil_unidad_codigo=$_POST['movil_unidad_codigo'];
    if (isset($_POST['movil_tipo_codigo'])) $movil_tipo_codigo=$_POST['movil_tipo_codigo'];

    //$f_registro = $ndia."/".$nmes."/".$nanio;
    $vt = array();
    $xdisabled='';
	if ($o->Validar_apertura()=='') $xdisabled=" disabled=true ";

    if (isset($_POST["hddaccion"])){
        if ($_POST["hddaccion"]=="INS"){
          if(isset($_POST['chkdia'])){

            if(!$o->existe_fecha_registrada($fecha_pedida)){
            	$o->ruta_codigo=$ruta_codigo;
            	$o->movil_unidad_codigo=$movil_unidad_codigo;
            	$o->movilidad_fecha=$fecha_pedida;
            	$u->movil_unidad_codigo=$movil_unidad_codigo;
            	$rpta=$u->Query();
                $vt = $o->retornar_estado_bus();
                $movil_capacidad   =$u->movil_unidad_capacidad;
                $pasajeros_por_bus =$vt[1];
                if ($pasajeros_por_bus<($movil_capacidad + $u->movil_unidad_espera)){
                    $o->movilidad_fecha  = $fecha_pedida;
                    $o->usuario_registro = $id;
                    $o->ruta_codigo = $ruta_codigo;
                    $o->movil_unidad_codigo = $movil_unidad_codigo;
                    $sRpta = $o->AddNew(); //AGREGAMOS REGISTRO A CA_MOVILIDAD
                    if($sRpta!="OK"){
                        $msg=$sRpta;
                    }else{
                        //echo (($pasajeros_por_bus+1)*1) . " -- " . ($movil_capacidad*1);
                        if(($pasajeros_por_bus+1)<=$movil_capacidad){
                            $msg="Se registró reserva de movilidad, debe estar a la hora de salida del Bus, su inasistencia es causal de suspensión del servicio.";
                        }else{
                            $msg="Se registró reserva de movilidad en <FONT STYLE='FONT-COLOR:#CC0000; FONT-SIZE:14px'>LISTA DE ESPERA</FONT>.";
                        }
                    }
                }else{
                        $msg1="Número de pasajeros completos, NO SE REGISTRÓ CUPO,<br>Seleccione otra unidad o ruta con cupos libres.";
                }

            }

          }else{
          	  //echo "<br> quitar";

              $o->Delete();
			  $msg1='Registro Eliminado';
          }

        }
    }

?>


<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Reserva de Movilidad</title>
	<meta http-equiv='pragma' content='no-cache'>
	<META NAME=author CONTENT='TUMI Solutions S.A.C.'>
	<script language="JavaScript" src="../tecla_f5.js"></script>
	<script language="JavaScript" src="../mouse_keyright.js"></script>
</head>

<style>

body {
  background-color: #F3F5F2;
}

select {
	/*background-color: #717276;*/
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #000000;
}

.texto{
 font: normal x-small;
 font-family: Verdana, Arial, Helvetica, sans-serif;
 font-size: 9px;
 border: #000000;
 border: 1px;


}

.calendario{

 font: normal x-small;
 font-family: Verdana, Arial, Helvetica, sans-serif;
 background-color: #F1F4FA;

}

.cabecera{
  font: normal x-small;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  background-color: #81BFE4;
  font-size:14px;
  font-weight: bold;
  height:24px;
}

.cabecera_menu{
  font: normal x-small;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  background-color: #E7E7D6;
  font-size:11px;
  font-weight: bold;
  height:20px;
}

.cabecera_descripcion{
  font: normal x-small;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  background-color: #FFFFFF;
  font-size:11px;
  font-weight: bold;
  height:20px;
}

.cabecera_aviso{
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-color:#000033;
  background-color: #FF6633;
  font-size:13px;
  font-weight: bold;
  height:20px;
}

.advertencia{
  font: normal x-small;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  background-color: #FF2121;
  font-size:10px;
  font-weight: bold;
}

.atencion{
  font: normal x-small;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  background-color: #9CDEFE;
  font-size:10px;
  font-weight: bold;
  font-color: #021728;
}

</style>

<script language="JavaScript">

  function cambio(){
    	document.frm.submit();
    	return true;
  }

  function enviar(o){
    //alert('enviar ' + o.checked);
    if (o.checked==true){
      if (document.frm.movil_tipo_codigo.value == 0){
          alert("Seleccione SERVICIO");
          document.frm.movil_tipo_codigo.focus();
          o.checked=false;
				  return false;
			}

      if (document.frm.ruta_codigo.value == 0){
          alert("Seleccione RUTA");
          document.frm.ruta_codigo.focus();
          o.checked=false;
				  return false;
			}

      if (document.frm.movil_unidad_codigo.value == 0){
          alert("Seleccione UNIDAD");
          document.frm.movil_unidad_codigo.focus();
          o.checked=false;
				  return false;
			}

    }

    aceptar();
  }

  function aceptar(){
      valor=document.frm.chkdia.value;
      document.frm.hddaccion.value='INS';
      document.frm.submit();
  }

  function cerrar(){
      window.close();
  }

  function cargar(){
       document.frm.submit();
  }

  function cargar2(){
       document.frm.ruta_codigo.value=0;
       document.frm.movil_unidad_codigo.value=0;
       document.frm.submit();
  }

</script>
<body >
<center class=cabecera_menu>Reserva de Movilidad</center>
<p class=cabecera_aviso>Recuerde que a partir del día <b>Jueves 05 de Marzo</b> la movilidad de acercamiento cuesta 1 sol.</p>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="frm" id="frm">
<?php
    function completar($v){
      $c='';
 	    if ($v<10){
 	    $c='0'.$v;
       }else{
       $c=$v;
       }
       return $c;
    }
?>
<table class="cabecera" align="center"  width="100px" border="0" cellspacing="1" cellpadding="1">
<tr>
  <td class="cabecera_menu" align="right" valign="middle" width="100px" title="Empleado">EMPLEADO :</td>
  <td class="cabecera_menu" align="left" valign="middle"><?php echo " ".$e->empleado_apellido_paterno . ' ' .	$e->empleado_apellido_materno . ' ' . $e->empleado_nombres ?></td>
</tr>
<tr>
  <td class="cabecera_menu" align="right" valign="middle" width="100px">LOCAL :</td>
  <td class="cabecera_menu" align="left" valign="middle"><?php echo " ".$e->local_descripcion ?> </td>
</tr>
<tr>
  <td class="cabecera_menu" align="right" valign="middle" width="100px">SERVICIO :</td>
  <td class="cabecera_menu" align="center" valign="middle" width="100px">


  <?php
  	$protegido='';
	if ($msg!='') $protegido='disabled';
	if ($xdisabled!='') $protegido='disabled';
  //Clase combo
  $combo = new MyCombo();
  $combo->setMyUrl(db_host());
  $combo->setMyUser(db_user());
  $combo->setMyPwd(db_pass());
  $combo->setMyDBName(db_name());

  $sSql  = " SELECT ca_movilidad_tipo.movil_tipo_codigo, ca_movilidad_tipo.movil_tipo_descripcion";
  $sSql .= " FROM   ca_movilidad_tipo INNER JOIN ";
  $sSql .= "  ca_rutas ON ca_movilidad_tipo.movil_tipo_codigo = ca_rutas.movil_tipo_codigo AND  ";
  $sSql .= "  ca_movilidad_tipo.movil_tipo_codigo = ca_rutas.movil_tipo_codigo AND ca_movilidad_tipo.movil_tipo_codigo = ca_rutas.movil_tipo_codigo ";
  $sSql .= " WHERE  (ca_rutas.Local_Codigo = ".$e->local_codigo.") and ca_movilidad_tipo.movil_tipo_activo=1 and ca_rutas.ruta_activo=1 ";
  $sSql .= " GROUP BY ca_movilidad_tipo.movil_tipo_codigo, ca_movilidad_tipo.movil_tipo_descripcion";
	//echo $movil_tipo_codigo;
	$combo->query = $sSql;
	$combo->name = "movil_tipo_codigo";
	$combo->value = $movil_tipo_codigo."";
	$largo='style=width:300px';
	$combo->more = $largo." class=select style='width:350px;' onChange='cargar2()' " . $protegido;
	$rpta = $combo->Construir();
	echo $rpta;
	?>

  </td>
</tr>

<tr>
  <td class="cabecera_menu" align="right" valign="middle" width="100px">RUTA :</td>
  <td class="cabecera_menu" align="center" valign="middle" width="100px">

  <?php

        $sSql = " SELECT ruta_codigo, cast(ruta_codigo as varchar) + ' - ' + ruta_descripcion ";
        $sSql .=" FROM ca_rutas INNER JOIN ca_movilidad_tipo ON ca_rutas.movil_tipo_codigo = ca_movilidad_tipo.movil_tipo_codigo AND ca_rutas.movil_tipo_codigo = ca_movilidad_tipo.movil_tipo_codigo AND ca_rutas.movil_tipo_codigo = ca_movilidad_tipo.movil_tipo_codigo ";
        $sSql .=" WHERE (ca_rutas.Local_Codigo = ".$e->local_codigo.") AND (ca_rutas.movil_tipo_codigo = '".$movil_tipo_codigo."') AND (ca_movilidad_tipo.movil_tipo_activo=1 and ca_rutas.ruta_activo=1)";
        //echo $ruta_codigo;
       
  	$combo->query = $sSql;
  	$combo->name = "ruta_codigo";
  	$combo->value = $ruta_codigo."";
  	$largo='style=width:300px';
  	$combo->more= $largo." onChange='cargar()' " . $protegido;
        
  	if ($e->local_codigo*1==2){
            $combo->value = "1";
            $combo->more=$largo ." ";
  	}
        
        echo $combo->Construir();

  ?>


  </td>
</tr>

<tr>
  <td class="cabecera_menu" align="right" valign="middle" width="100px">UNIDAD :</td>
  <td class="cabecera_menu" align="center" valign="middle" width="100px">
  <?php
        $sSql  = " SELECT ca_movilidad_unidad.movil_unidad_codigo, ca_movilidad_unidad.movil_unidad_descripcion + ' ( ' + " .
        " 	case when ca_movilidad_unidad.movil_unidad_capacidad > DBO.UDF_NUMERO_REGISTROS_MOVIL('".$ruta_codigo."',ca_movilidad_unidad.movil_unidad_codigo,convert(varchar(10),getdate(),103)) then 'Libre'" .
        " when ca_movilidad_unidad.movil_unidad_capacidad+1 <= DBO.UDF_NUMERO_REGISTROS_MOVIL('".$ruta_codigo."',ca_movilidad_unidad.movil_unidad_codigo,convert(varchar(10),getdate(),103)) then 'Cerrado' " .
        " when ca_movilidad_unidad.movil_unidad_capacidad <= DBO.UDF_NUMERO_REGISTROS_MOVIL('".$ruta_codigo."',ca_movilidad_unidad.movil_unidad_codigo,convert(varchar(10),getdate(),103)) and ca_movilidad_unidad.movil_unidad_capacidad+1 > DBO.UDF_NUMERO_REGISTROS_MOVIL('".$ruta_codigo."',ca_movilidad_unidad.movil_unidad_codigo,convert(varchar(10),getdate(),103)) then 'Lista espera'" .
        " end  + ' )'";
        $sSql .= " FROM   ca_movilidad_unidad INNER JOIN ";
        $sSql .= " ca_ruta_unidad ON ca_movilidad_unidad.movil_unidad_codigo = ca_ruta_unidad.movil_unidad_codigo ";
        $sSql .= " WHERE  (ca_ruta_unidad.ruta_codigo = '".$ruta_codigo."') AND (ca_ruta_unidad.ruta_unidad_activo=1 and ca_movilidad_unidad.movil_unidad_activo=1)";
        $sSql .= " ORDER BY ca_movilidad_unidad.movil_unidad_descripcion";
                       
                        
			$combo->query = $sSql;
			$combo->name = "movil_unidad_codigo";
			$combo->value = $movil_unidad_codigo."";
			$largo='style=width:300px';
			$combo->more = $largo." class=select style='width:350px;' onChange='cargar()' " . $protegido;
			$rpta = $combo->Construir();
			echo $rpta;
	?>

  </td>
</tr>
</table>
<br/>
<!-- INICIO DE REGISTRO CHECK -->
<?php

    //fecha_del_dia_de_hoy
    $fecha_hoy = getdate();
	$dia_hoy = $fecha_hoy['mday'];
	$mes_hoy = $fecha_hoy['mon'];
	$anio_hoy = $fecha_hoy['year'];

	$fecha_actual=$anio_hoy. completar($mes_hoy). completar($dia_hoy);
	$fecha_barra=completar($dia_hoy)."/".completar($mes_hoy)."/".$anio_hoy;
	$va=0;
?>

<table class="calendario" align="center"  width="350" border="0" cellspacing="1" cellpadding="1">
<tr align="center">
	<td class=cabecera>FECHA</td>
	<td class=cabecera><?php
		echo $fecha_barra."<br>";
		?>
	</td>
</tr>
<tr align="center">
	<td class=cabecera_descripcion>Registrar</td>
	<td class=cabecera_descripcion>&nbsp;
		<input type='checkbox' id='chkdia' name='chkdia' value='1' <?php if($o->existe_fecha_registrada($fecha_pedida)){
					 echo 'checked';
					 $va=1;
					 }
					  ?> onclick='enviar(this)'
					  <?php
					  echo $xdisabled;
					  ?>
					  >
	</td>
</tr>
<tr align="center">
	<td colspan=2 class=cabecera_descripcion>&nbsp;
		<?php
			if ($va==1){
				$o->Empleado_Codigo=$id;
				$rpta=$o->posicion_lista();
			}
			?>

	</td>
</tr>

<?php
if ($xdisabled!=''){
?>
<tr align="center" height=10px valign=middle>
	<td colspan=2 align=center>&nbsp;</td>
</tr>
<tr align="center" height=10px valign=middle>
	<td colspan=2 align=center class=atencion>&nbsp;Periodo de Registro de Reserva Cerrado</td>
</tr>
<?php } ?>
<?php
if ($msg!=''){
?>
<tr align="center" height=10px valign=middle>
	<td colspan=2 align=center>&nbsp;</td>
</tr>
<tr align="center" height=50px valign=middle>
	<td colspan=2 align=center class=atencion><?php echo $msg ?></td>
</tr>
<?php } ?>
<?php
if ($msg1!=''){
?>
<tr align="center" height=10px valign=middle>
	<td colspan=2 align=center>&nbsp;</td>
</tr>
<tr align="center" height=50px valign=middle>
	<td colspan=2 align=center class=atencion><?php echo $msg1 ?></td>
</tr>
<?php } ?>
</table>
<?php
//--validar si tiene suspension
	$suspencion=$o->Tiene_suspension();
	if ($suspencion>0){
		if ($suspencion==1){
			$dias_suspension=7;
			//echo "si tiene suspension: " . $dias_suspension;
			if($o->Suspender($dias_suspension)){
				?>
				<script language=javascript>
				alert('Atención: No podrás hacer uso del servicio por 01 semana');
				self.close();
				</script>
				<?php
			}

		}
		if ($suspencion==2){
			$dias_suspension=15;
			//echo "Tiene suspension: " . $dias_suspension;
			if($o->Suspender($dias_suspension)){
				?>
				<script language=javascript>
				alert('Atención: No podrás hacer uso del servicio por 02 semana');
				self.close();
				</script>
				<?php
			}

		}
		if ($suspencion>2){
			//echo "Tiene suspension permanente";
			?>
				<script language=javascript>
				alert('Atención: Tiene suspensión total del servicio');
				self.close();
				</script>
			<?php
		}
	}
?>
<br>
<table align="center" border="1" cellspacing="1" cellpadding="1">
  <tr align="center">
    <td class="laborable" ><b>[</b> <font color="#000066" onclick='cerrar()' style="cursor:hand ">Salir</font> <b>]</b></td>
  </tr>
</table>
<input type="hidden" id="ndia" name="ndia" value="<?php echo $ndia ?>">
<input type='hidden' name='hddcodigos' id='hddcodigos' value='<?php echo $rcodigos ?>'>
<input type='hidden' name='hddempleado' id='hddempleado' value='<?php echo $id ?>'>
<input type='hidden' name='hddaccion' id='hddaccion' value=''>
<input type='hidden' name='hddruta' id='hddruta' value='<?php echo $ruta_codigo ?>'>
</form>
</body>
</html>