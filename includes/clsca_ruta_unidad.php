<?php

require_once(PathIncludes() . "mantenimiento.php");
class ca_ruta_unidad extends mantenimiento{
   var $ruta_unidad_codigo='';
   var $ruta_codigo='';
   var $movil_unidad_codigo='';
   var $ruta_unidad_activo='';
   var $fecha_registro='';
   var $usuario_registro='';
   var $fecha_modifica='';
   var $usuario_modifica='';

  function Query(){
    $sRpta = "OK";
    $sSql ="";
    $cn = $this->getMyConexionADO();
    $params = array($this->ruta_unidad_codigo);
    $sSql="Select ruta_unidad_codigo,ruta_codigo,movil_unidad_codigo,ruta_unidad_activo,fecha_registro,usuario_registro,fecha_modifica,usuario_modifica";
    $sSql.=" From ca_ruta_unidad";
    $sSql.= " Where ruta_unidad_codigo = ?";
    $rs = $cn->Execute($sSql, $params);
    if($rs->RecordCount() > 0){
      $this->ruta_unidad_codigo= $rs->fields[0];
      $this->ruta_codigo= $rs->fields[1];
      $this->movil_unidad_codigo= $rs->fields[2];
      $this->ruta_unidad_activo= $rs->fields[3];
      $this->fecha_registro= $rs->fields[4];
      $this->usuario_registro= $rs->fields[5];
      $this->fecha_modifica= $rs->fields[6];
      $this->usuario_modifica= $rs->fields[7];
    }

    return $sRpta;
  }

  function AddNew() {
    $sRpta = "OK";
    $sSql ="";
    $cn = $this->getMyConexionADO();

    $sSql = "select case when max(ruta_unidad_codigo) is null then 1 else max(ruta_unidad_codigo)+1 end as id from ca_ruta_unidad";
    $rs = $cn->Execute($sSql);
    $this->ruta_unidad_codigo = $rs->fields[0];
    $params = array(
                    $this->ruta_unidad_codigo,
                    $this->ruta_codigo,
                    $this->movil_unidad_codigo,
                    $this->ruta_unidad_activo,
                    $this->usuario_registro 
                );
    $sSql = "Insert into ca_ruta_unidad";
    $sSql .=" (ruta_unidad_codigo,ruta_codigo,movil_unidad_codigo,ruta_unidad_activo,fecha_registro,usuario_registro)";
    $sSql.= " values(?,?,?,?,getdate(),?)";
    $rs = $cn->Execute($sSql, $params);
    if($rs==false) $sRpta="Error al Insertar registro";
    return $sRpta;
  }

  function Update() {
    $sRpta = "OK";
    $sSql ="";
    $cn = $this->getMyConexionADO();
    $params = array(
                    $this->ruta_unidad_codigo,
                    $this->ruta_codigo,
                    $this->movil_unidad_codigo,
                    $this->ruta_unidad_activo,
                    $this->usuario_modifica,
                    $this->ruta_unidad_codigo 
                    
                );
  	$sSql="Update ca_ruta_unidad";
  	$sSql .= " set ruta_unidad_codigo= ?";
  	$sSql .= ", ruta_codigo= ?";
  	$sSql .= ", movil_unidad_codigo= ?";
  	$sSql .= ", ruta_unidad_activo= ?";
  	$sSql .= ", fecha_modifica= getdate()";
  	$sSql .= ", usuario_modifica= ?";
    $sSql .= " Where ruta_unidad_codigo = ?";
    $rs= $cn->Execute($sSql, $params);
    if($rs==false) $sRpta="Error al Actualizar registro";
    return $sRpta;
  }

 function editar_ruta_unidades(){
    $sRpta = "OK";
    $sSql ="";
    $cn = $this->getMyConexionADO();
    //$cn->debug = true;
    $params = array(
                    $this->ruta_codigo
                );
    $sSql="SELECT ca_ruta_unidad.ruta_unidad_codigo, ca_ruta_unidad.ruta_codigo, ca_ruta_unidad.movil_unidad_codigo, " .
    " ca_movilidad_unidad.movil_unidad_descripcion, ca_movilidad_unidad.movil_unidad_capacidad, ca_movilidad_unidad.movil_unidad_placa " .
		" FROM ca_ruta_unidad INNER JOIN " .
    " ca_movilidad_unidad ON ca_ruta_unidad.movil_unidad_codigo = ca_movilidad_unidad.movil_unidad_codigo " .
		" WHERE ca_ruta_unidad.ruta_unidad_activo = 1" .
		" AND ca_movilidad_unidad.movil_unidad_activo =1 " .
		" AND ca_ruta_unidad.ruta_codigo = ?".
		" ORDER BY ca_movilidad_unidad.movil_unidad_descripcion";

    $rs = $cn->Execute($sSql, $params);
    if( $rs->RecordCount() > 0){
      while(!$rs->EOF){
	      echo "\n<tr> ";
  		  echo "\n <td  class='DataTD' width='50%'>".$rs->fields[3]."</td> ";
  		  echo "\n <td  class='DataTD' width='10%' align='center'>".$rs->fields[4]."</td> ";
  		  echo "\n <td  class='DataTD' width='10%'>".$rs->fields[5]."</td> ";
  		  echo "\n <td  class='DataTD' align='center'>[&nbsp;&nbsp;<font style='cursor:hand' onclick='Quitar(".$rs->fields[0].")'><u>Eliminar</u></font>&nbsp;&nbsp;]</td> ";
  		  echo "\n <td class='DataTD' width='10%' align='center'>&nbsp;</td>";
  		  echo "\n</tr>";
          $rs->MoveNext();
      }
    }
    return $sRpta;
 }

 function iliminar_ruta_unidad(){
    $sRpta = "OK";
    $cn = $this->getMyConexionADO();
    $sSql  = "";
    $sSql  = " UPDATE ca_ruta_unidad ";
    $sSql .= " SET ruta_unidad_activo = 0 ";
    $sSql .= " WHERE (ruta_unidad_codigo = $this->ruta_unidad_codigo) ";
    $rs = $cn->Execute($sSql);
    if($rs==false) $sRpta="Error al Eliminar registro";
    return $sRpta;
 }

 function mostrar_ruta_unidades_inactivas(){
    $sRpta = "OK";
    $sSql ="";
    $cn = $this->getMyConexionADO();
	$resul='';
    $sSql="SELECT ca_ruta_unidad.ruta_unidad_codigo, ca_ruta_unidad.ruta_codigo, ca_ruta_unidad.movil_unidad_codigo, " .
    " ca_movilidad_unidad.movil_unidad_descripcion, ca_movilidad_unidad.movil_unidad_capacidad, ca_movilidad_unidad.movil_unidad_placa " .
		" FROM ca_ruta_unidad INNER JOIN " .
    " ca_movilidad_unidad ON ca_ruta_unidad.movil_unidad_codigo = ca_movilidad_unidad.movil_unidad_codigo " .
		" WHERE ca_ruta_unidad.ruta_unidad_activo = 0" .
		" AND ca_movilidad_unidad.movil_unidad_activo = 1 " .
		" AND ca_ruta_unidad.ruta_codigo =" . $this->ruta_codigo .
		" ORDER BY ca_movilidad_unidad.movil_unidad_descripcion";

    $rs= $cn->Execute($sSql);
    if( $rs->RecordCount() > 0){
      while(!$rs->EOF){
	      echo "\n<tr> ";
  		  echo "\n <td  class='DataTD' width='50%'>".$rs->fields[3]."</td> ";
  		  echo "\n <td  class='DataTD' width='10%' align='center'>".$rs->fields[4]."</td> ";
  		  echo "\n <td  class='DataTD' width='10%'>".$rs->fields[5]."</td> ";
  		  echo "\n <td  class='DataTD' align='center'>[&nbsp;&nbsp;<font style='cursor:hand' onclick='Agregar(".$rs->fields[0].")'><u>Agregar</u></font>&nbsp;&nbsp;]</td> ";
  		  echo "\n <td class='DataTD' width='10%' align='center'>&nbsp;</td>";
  		  echo "\n</tr>";
          $rs->MoveNext();
      }
    }

    return $sRpta;
 }

 function agregar_ruta_unidad(){
    $sRpta = "OK";
    $cn = $this->getMyConexionADO();
    $sSql  = "";
    $sSql  = " UPDATE ca_ruta_unidad ";
    $sSql .= " SET ruta_unidad_activo = 1 ";
    $sSql .= " WHERE (ruta_unidad_codigo = $this->ruta_unidad_codigo) ";
    $rs = $cn->Execute($sSql);
    if($rs==false) $sRpta="Error al Agregar registro";
    return $sRpta;
 }

 function existeMovilidadEnRuta(){
    $cls  = false;
    $cn = $this->getMyConexionADO();
    $params = array(
                    $this->ruta_codigo,
                    $this->movil_unidad_codigo
                );    
    $sSql = " SELECT ruta_unidad_codigo, ruta_codigo, movil_unidad_codigo, ruta_unidad_activo ";
    $sSql.= " FROM   ca_ruta_unidad ";
    $sSql.= " WHERE  (ruta_codigo = ? ) AND (movil_unidad_codigo =  ?)";
    $rs= $cn->Execute($sSql, $params);
    if( $rs->RecordCount() > 0){
      $cls=true;
    }
    return $cls;
 }

 function MovilidadRutaInactiva(){

    $cn = $this->getMyConexionADO();
    $params = array(
                    $this->ruta_codigo,
                    $this->movil_unidad_codigo
                );
    $sSql = "";
    $sSql = " SELECT ruta_unidad_codigo, ruta_codigo, movil_unidad_codigo, ruta_unidad_activo ";
    $sSql.= " FROM   ca_ruta_unidad ";
    $sSql.= " WHERE  (ruta_codigo = ? ) AND (movil_unidad_codigo =  ?)";
    $rs = $cn->Execute($sSql, $params);
    return $rs->fields[0];
 }


 function activarMovilidadRuta(){
    $sRpta = "OK";
    $cn = $this->getMyConexionADO();
    $params = array($this->ruta_unidad_codigo);
    $sSql  = "";
    $sSql  = " UPDATE ca_ruta_unidad ";
    $sSql .= " SET ruta_unidad_activo = 1 ";
    $sSql .= " WHERE (ruta_unidad_codigo = ? ) ";
    $rs = $cn->Execute($sSql);
    if($rs==false) $sRpta="Error al Agregar registro";
    return $sRpta;

 }
 
 function mostrar_unidades_ruta(){
    $sRpta = "OK";
    $cn = $this->getMyConexionADO();
    $data = array();
    $sSql = " SELECT movil_unidad_codigo, movil_unidad_descripcion, movil_unidad_capacidad, movil_unidad_placa ";
    $sSql.= " FROM   ca_movilidad_unidad ";
    $sSql.= " WHERE  (movil_unidad_activo = 1)";
    $sSql.= " ORDER BY 2";
    $rs = $cn->Execute($sSql);
    if($rs->RecordCount() > 0){
        while(!$rs->EOF){
            $data[] = $rs->fields;
            $rs->MoveNext(); 
        }
    }
    return $data;
 }
  function mostrar_estado_bus($movil_unidad_codigo){ 
    $cn = $this->getMyConexionADO();
    $params = array($movil_unidad_codigo);
    $title = "";
    $sSql = " SELECT  ruta_unidad_codigo, ruta_codigo, movil_unidad_codigo, ruta_unidad_activo";
    $sSql.= " FROM    ca_ruta_unidad ";
    $sSql.= " WHERE   (movil_unidad_codigo =?  ) AND (ruta_unidad_activo = 1)";
    $sSql.= " ORDER BY ruta_codigo";

    $title = " - ";
    $rs = $cn->Execute($sSql, $params);
    if( $rs->RecordCount() > 0){
      while(!$rs->EOF){
        $title .= "Ruta : ".$rs->fields[1]." - ";
        $rs->MoveNext();
      }
    } 
    return $title;  
  }
    function verificar_estado($movil_unidad_codigo){
        $cn = $this->getMyConexionADO();
        $cls = "<b>Libre</b>";
        $params = array($movil_unidad_codigo);
        $sSql = " SELECT ruta_unidad_codigo, ruta_codigo, movil_unidad_codigo, ruta_unidad_activo ";
        $sSql.= " FROM   ca_ruta_unidad";
        $sSql.= " WHERE  (movil_unidad_codigo = ?) AND (ruta_unidad_activo != 0)";
        $rs = $cn->Execute($sSql, $params);
        if( $rs->RecordCount() > 0){ 
          $cls = "<b>Asignado</b>";
        }
        return $cls;    
  }
}
?>


