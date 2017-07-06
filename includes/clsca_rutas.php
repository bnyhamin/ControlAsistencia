<?php

//require_once("../../includes/mantenimiento.php");
require_once(PathIncludes() . "mantenimiento.php");
class ca_rutas extends mantenimiento{
   var $ruta_codigo='';
   var $ruta_descripcion='';
   var $ruta_activo='';
   var $fecha_registro='';
   var $usuario_registro='';
   var $fecha_modifica='';
   var $usuario_modifica='';
   var $ruta_color='';
   var $local_codigo='';
   var $movil_tipo_codigo='';

  function Query(){
    $sRpta = "OK";
    $sSql ="";
    $cn = $this->getMyConexionADO();
    $params = array($this->ruta_codigo);
    $sSql="Select ruta_codigo,ruta_descripcion,ruta_activo," .
    		"fecha_registro,usuario_registro,fecha_modifica,usuario_modifica," .
    		"ruta_color, local_Codigo, movil_tipo_codigo";
    $sSql.=" From ca_rutas ";
    $sSql.= " Where ruta_codigo = ?";
    
    $rs = $cn->Execute($sSql, $params);
    if($rs->RecordCount() > 0 ){
      $this->ruta_codigo        = $rs->fields[0];
      $this->ruta_descripcion   = $rs->fields[1];
      $this->ruta_activo        = $rs->fields[2];
      $this->fecha_registro     = $rs->fields[3];
      $this->usuario_registro   = $rs->fields[4];
      $this->fecha_modifica     = $rs->fields[5];
      $this->usuario_modifica   = $rs->fields[6];
      $this->ruta_color         = $rs->fields[7];
      $this->local_codigo       = $rs->fields[8];
      $this->movil_tipo_codigo  = $rs->fields[9];
    }
    return $sRpta;
  }

  function AddNew() {
    $sRpta = "OK";
    $sSql ="";
    $cn = $this->getMyConexionADO();
    $sSql = "select case when max(ruta_codigo) is null then 1 else max(ruta_codigo)+1 end as id from ca_rutas";
    $rs = $cn->Execute($sSql);
    $this->ruta_codigo = $rs->fields[0];
    $params = array(
                    $this->ruta_codigo,
                    $this->ruta_descripcion,
                    $this->ruta_activo,
                    $this->usuario_registro,
                    $this->usuario_modifica,
                    $this->ruta_color,
                    $this->local_codigo ,
                    $this->movil_tipo_codigo
                );
    $sSql = "INSERT INTO ca_rutas(
                 ruta_codigo,
                 ruta_descripcion,
                 ruta_activo,
    		     fecha_registro,
                 usuario_registro,
                 fecha_modifica,
                 usuario_modifica,
    		     ruta_color, 
                 local_Codigo,
                 movil_tipo_codigo)
            VALUES(?,?,?,getdate(),?,getdate(),?,?,?,?)";
    $rs= $cn->Execute($sSql, $params);
    if($rs==false) $sRpta="Error al Insertar registro";
    return $sRpta;
  }

  function Update() {
    $sRpta = "OK";
    $sSql ="";
    $cn = $this->getMyConexionADO();
    $params = array(
                    $this->ruta_codigo ,
                    $this->ruta_descripcion ,
                    $this->ruta_activo,
                    $this->usuario_registro,
                    $this->usuario_modifica,
                    $this->ruta_color ,
                    $this->local_codigo,
                    $this->movil_tipo_codigo ,
                    $this->ruta_codigo
                );
    $sSql="Update ca_rutas";
    $sSql .= " set ruta_codigo= ?";
    $sSql .= ", ruta_descripcion= ?";
    $sSql .= ", ruta_activo= ?";
    $sSql .= ", usuario_registro= ?";
    $sSql .= ", fecha_modifica= getdate() ";
    $sSql .= ", usuario_modifica= ?";
    $sSql .= ", ruta_color= ?";
    $sSql .= ", local_codigo= ?";
    $sSql .= ", movil_tipo_codigo= ?";
    $sSql .= " WHERE ruta_codigo = ?";
    $rs= $cn->Execute($sSql, $params);
    if($rs==false) $sRpta="Error al Actualizar registro";
    return $sRpta;
  }

  function Delete() {
    $sRpta = "OK";
    $sSql ="";
    $cn = $this->getMyConexionADO();
    $params = array($this->ruta_codigo);
    $sSql="delete from ca_rutas";
    $sSql.= " Where ruta_codigo = ?";
    $rs= $cn->Execute($sSql);
    if($rs == false) $sRpta="Error al Eliminar registro";
    return $sRpta;
  }

  }
?>


