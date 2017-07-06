<?php header("Expires: 0"); ?>
<?php

require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Usuarios.php");
?>
<?php
$empleado_id="";
$tipo = "";//1:buscar responsable; 2:buscar turnos de responsable; 3:nro. de semana
$area_codigo = "0";
$fecha = "";  
$empleado = "";
$responsable_codigo = "";
$anio_codigo = "0";
$rol_codigo = "0";
$sql4 = "";

if (isset($_GET["tipo"])) $tipo = $_GET["tipo"];
if (isset($_GET["area_codigo"])) $area_codigo = $_GET["area_codigo"];
if (isset($_GET["fecha"])) $fecha = $_GET["fecha"];
if (isset($_GET["empleado_id"])) $empleado = $_GET["empleado_id"];
if (isset($_GET["rol_codigo"])) $rol_codigo = $_GET["rol_codigo"];
if (isset($_GET["responsable_codigo"])) $responsable_codigo = $_GET["responsable_codigo"];

if ($tipo==3){
   if (isset($_GET["anio_codigo"])) $anio_codigo = $_GET["anio_codigo"];

}

$u = new ca_usuarios();
$u->setMyUrl(db_host());
$u->setMyUser(db_user());
$u->setMyPwd(db_pass());
$u->setMyDBName(db_name());

/*$m = new mantenimiento();
$m->MyUrl = db_host();
$m->MyUser= db_user();
$m->MyPwd = db_pass();
$m->MyDBName= db_name();*/

?>

<?php

    //$u->getRolCode($empleado_id);
    $u->getRolCode($empleado);
    $rol_code=$u->rol_code;
    
	/*$rol_code="0";
	$rpta="OK";
	$ssql = "";
	$rpta=$m->conectarme_ado();
        if($rpta=="OK"){
	  	$ssql = "select rol_codigo FROM CA_EMPLEADO_ROL where empleado_codigo = " . $empleado_id . " and rol_codigo = 10 and empleado_rol_activo=1";
		$rs = $m->cnnado->Execute($ssql);
		$rol_code = $rs->fields[0]->value;
	}
	$rs->close();
	$rs=null;*/

if ($tipo==1){
        $u->rol_code=$rol_code;
        $u->rol_codigo=$rol_codigo;
        //echo 'codigo de rol'.$u->rol_codigo;
        $u->getAgregaOptions1($fecha,$area_codigo,$empleado);
        
	/*if ($rol_code!=10){
	
	$sql4="select r.responsable_codigo, dbo.udf_empleado_nombre(r.responsable_codigo) as responsable ";
	$sql4 .="from ca_asistencias a inner join ca_turnos t on ";
	$sql4 .=" a.turno_codigo = t.turno_codigo inner join ca_controller c on ";
	$sql4 .=" a.area_codigo=c.area_codigo inner join ca_asistencia_responsables r on ";
	$sql4 .=" a.empleado_codigo=r.empleado_codigo and a.asistencia_codigo=r.asistencia_codigo ";
	$sql4 .="where a.asistencia_fecha = convert(datetime,'" . $fecha . "',103) ";
	$sql4 .=" and a.area_codigo =" . $area_codigo;
	$sql4 .=" and c.activo=1 ";
	$sql4 .=" and c.empleado_codigo = " . $empleado . " group by r.responsable_codigo ";
	$sql4 .=" Union ";
	$sql4 .="select r.responsable_codigo, dbo.udf_empleado_nombre(r.responsable_codigo) as responsable ";
	$sql4 .="from ca_asistencias a inner join ca_turnos t on ";
	$sql4 .="a.turno_codigo = t.turno_codigo inner join ca_asistencia_responsables r on ";
	$sql4 .=" a.empleado_codigo=r.empleado_codigo and a.asistencia_codigo=r.asistencia_codigo ";
	$sql4 .="where a.asistencia_fecha = convert(datetime,'" . $fecha . "',103) ";
	$sql4 .=" and a.area_codigo =" . $area_codigo;
	$sql4 .=" and r.responsable_codigo =" . $empleado;
	$sql4 .=" group by r.responsable_codigo ";
        if ($rol_codigo>0){
            $sql4 .=" Union ";
            $sql4 .="select r.responsable_codigo, dbo.udf_empleado_nombre(r.responsable_codigo) as responsable ";
            $sql4 .="from ca_asistencias a inner join ca_turnos t on ";
            $sql4 .="a.turno_codigo = t.turno_codigo inner join ca_asistencia_responsables r on ";
            $sql4 .=" a.empleado_codigo=r.empleado_codigo and a.asistencia_codigo=r.asistencia_codigo ";
            $sql4 .="where a.asistencia_fecha = convert(datetime,'" . $fecha . "',103) ";
            $sql4 .=" and a.area_codigo =" . $area_codigo;
            $sql4 .=" group by r.responsable_codigo ";
        }
            $sql4 .="Order by 2";
    
	}else{
	
		$sql4 ="select r.responsable_codigo, dbo.udf_empleado_nombre(r.responsable_codigo) as responsable ";
		$sql4 .="from ca_asistencias a inner join ca_turnos t on ";
		$sql4 .="a.turno_codigo = t.turno_codigo inner join ca_asistencia_responsables r on ";
		$sql4 .=" a.empleado_codigo=r.empleado_codigo and a.asistencia_codigo=r.asistencia_codigo ";
		$sql4 .="where a.asistencia_fecha = convert(datetime,'" . $fecha . "',103) ";
		$sql4 .=" and a.area_codigo =" . $area_codigo;
		$sql4 .=" group by r.responsable_codigo ";
	    $sql4 .="Order by 2";
	
	}
	
        $link = mssql_connect(db_host(), db_user(), db_pass()) or die("No puedo conectarme a la BD" . mssql_error());
        mssql_select_db(db_name()) or die("No puedo seleccionar la BD" . mssql_error());
        $result = mssql_query($sql4);
        $ars = mssql_fetch_row($result);
        while ($ars) {*/
    ?>
        <!--<script language=javascript>
          window.parent.agregar_options("responsable_codigo","<?php //echo $ars[0]; ?>","<?php //echo $ars[1]; ?>");
        </script>-->
    <?php
            /*$ars = mssql_fetch_row($result);
        }*/
        
        
}//end

?>

<?php

if ($tipo==2){
  
    $u->rol_code=$rol_code;
    $u->getAgregaOptions2($fecha,$area_codigo,$empleado,$responsable_codigo);
    
    /*if ($rol_code!=10){	
        $sql4="select a.turno_codigo,t.turno_descripcion ";
        $sql4 .="from ca_asistencias a inner join ca_turnos t on ";
        $sql4 .="a.turno_codigo = t.turno_codigo inner join ca_controller c on ";
        $sql4 .="a.area_codigo=c.area_codigo inner join ca_asistencia_responsables r on ";
        $sql4 .="a.empleado_codigo = r.empleado_codigo and a.asistencia_codigo = r.asistencia_codigo ";
        $sql4 .="where a.asistencia_fecha = convert(datetime,'" . $fecha . "',103) and a.area_codigo = " . $area_codigo . " ";
        if ($responsable_codigo*1>0) $sql4 .="and r.responsable_codigo = " . $responsable_codigo . " ";
        $sql4 .="and c.activo = 1 and c.empleado_codigo = " . $empleado . " and t.turno_activo = 1 ";
        $sql4 .=" group by a.turno_codigo,t.turno_descripcion ";
        $sql4 .=" Union ";
        $sql4 .="select a.turno_codigo,t.turno_descripcion ";
        $sql4 .="from ca_asistencias a inner join ca_turnos t on ";
        $sql4 .="a.turno_codigo = t.turno_codigo inner join ca_asistencia_responsables r on ";
        $sql4 .="a.empleado_codigo = r.empleado_codigo and a.asistencia_codigo = r.asistencia_codigo ";
        $sql4 .="where a.asistencia_fecha = convert(datetime,'" . $fecha . "',103) and a.area_codigo = " . $area_codigo . " ";
        if ($responsable_codigo*1>0) $sql4 .="and r.responsable_codigo = " . $responsable_codigo . " ";
        $sql4 .=" and t.turno_activo = 1 ";
        $sql4 .=" group by a.turno_codigo,t.turno_descripcion order by t.turno_descripcion";
    }else{
        $sql4 ="select a.turno_codigo,t.turno_descripcion ";
        $sql4 .="from ca_asistencias a inner join ca_turnos t on ";
        $sql4 .="a.turno_codigo = t.turno_codigo inner join ca_asistencia_responsables r on ";
        $sql4 .="a.empleado_codigo = r.empleado_codigo and a.asistencia_codigo = r.asistencia_codigo ";
        $sql4 .="where a.asistencia_fecha = convert(datetime,'" . $fecha . "',103) and a.area_codigo = " . $area_codigo . " ";
        if ($responsable_codigo*1>0) $sql4 .="and r.responsable_codigo = " . $responsable_codigo . " ";
        $sql4 .=" and t.turno_activo = 1 ";
        $sql4 .=" group by a.turno_codigo,t.turno_descripcion order by t.turno_descripcion";
    }
  
    $link = mssql_connect(db_host(), db_user(), db_pass()) or die("No puedo conectarme a la BD" . mssql_error());
    mssql_select_db(db_name()) or die("No puedo seleccionar la BD" . mssql_error());
    $result = mssql_query($sql4);
    $ars = mssql_fetch_row($result);
    while ($ars) {*/
    ?>
    <!--<script language=javascript>
      window.parent.agregar_options("turno_codigo","<?php //echo $ars[0]; ?>","<?php //echo $ars[1]; ?>");
    </script>-->
    <?php
        /*$ars = mssql_fetch_row($result);
    }*/

  
}//end
?>

<?php
if ($tipo==3){
    $u->getAgregaOptions3($anio_codigo);
    
    
    /*$sql4="SELECT DATEPART(wk, Asistencia_fecha) AS semana_codigo,DATEPART(wk, Asistencia_fecha) AS semana_descripcion ";
    $sql4 .=" FROM  dbo.CA_Asistencias WITH (nolock) ";
    $sql4 .=" WHERE (CA_Estado_Codigo = 1) AND (Asistencia_entrada BETWEEN CONVERT(datetime, '01/01/' + '" . $anio_codigo . "',103)" ;
    $sql4 .=" AND CONVERT(datetime, '31/12/' + '" . $anio_codigo . "',103)) ";
    $sql4 .=" GROUP BY DATEPART(wk, Asistencia_fecha),";
    $sql4 .=" CONVERT(varchar(10), Asistencia_fecha - (DATEPART(w, Asistencia_fecha + 7) - 1), 103), ";
    $sql4 .=" CONVERT(varchar(10), Asistencia_fecha + 7 - DATEPART(w, Asistencia_fecha + 7), 103) ORDER BY 2 DESC";
	  
    $link = mssql_connect(db_host(), db_user(), db_pass()) or die("No puedo conectarme a la BD" . mssql_error());
    mssql_select_db(db_name()) or die("No puedo seleccionar la BD" . mssql_error());
    $result = mssql_query($sql4);
    $ars = mssql_fetch_row($result);
    while ($ars) {*/
?>
    <!--<script language=javascript>
      window.parent.agregar_options("semana_codigo","<?php //echo $ars[0]; ?>","<?php //echo $ars[1]; ?>");
    </script>-->
<?php
        /*$ars = mssql_fetch_row($result);
    }*/
    
    
    
}//end
?>
