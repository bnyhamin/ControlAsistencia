<?php
require_once("../../Includes/mantenimiento.php");
class ca_movilidad_unidad extends mantenimiento{
   var $movil_unidad_codigo='';
   var $movil_unidad_descripcion='';
   var $movil_unidad_capacidad='';
   var $movil_unidad_espera='';
   var $movil_unidad_placa='';
   var $movil_unidad_chofer='';
   var $movil_unidad_activo='';
   var $fecha_registro='';
   var $usuario_registro='';
   var $fecha_modifica='';
   var $usuario_modifica='';

  function Query(){
    /*$sRpta = "OK";
    $sSql ="";
    $link = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());

    $sSql="Select movil_unidad_codigo,movil_unidad_descripcion,movil_unidad_capacidad,movil_unidad_placa,movil_unidad_chofer,movil_unidad_activo," .
    		" fecha_registro,usuario_registro,fecha_modifica,usuario_modifica, movil_unidad_espera";
    $sSql.=" From ca_movilidad_unidad";
    $sSql.= " Where movil_unidad_codigo = \"" . $this->movil_unidad_codigo . "\"";
    $result = mssql_query($sSql);
    if (mssql_num_rows($result)==true){
      $rs= mssql_fetch_row($result);
      $this->movil_unidad_codigo= $rs[0];
      $this->movil_unidad_descripcion= $rs[1];
      $this->movil_unidad_capacidad= $rs[2];
      $this->movil_unidad_placa= $rs[3];
      $this->movil_unidad_chofer= $rs[4];
      $this->movil_unidad_activo= $rs[5];
      $this->fecha_registro= $rs[6];
      $this->usuario_registro= $rs[7];
      $this->fecha_modifica= $rs[8];
      $this->usuario_modifica= $rs[9];
      $this->movil_unidad_espera=$rs[10];
    }

    return $sRpta;*/
      
      
    $sRpta = "OK";
    $sSql ="";
    $cn=$this->getMyConexionADO();
    
    $sSql="Select movil_unidad_codigo,movil_unidad_descripcion,movil_unidad_capacidad,movil_unidad_placa,movil_unidad_chofer,movil_unidad_activo," .
    		" fecha_registro,usuario_registro,fecha_modifica,usuario_modifica, movil_unidad_espera";
    $sSql.=" From ca_movilidad_unidad";
    $sSql.= " Where movil_unidad_codigo = '".$this->movil_unidad_codigo . "'";
    
    $rs=$cn->Execute($sSql);
    if ($rs->RecordCount()>0){
        
      $this->movil_unidad_codigo= $rs->fields[0];
      $this->movil_unidad_descripcion= $rs->fields[1];
      $this->movil_unidad_capacidad= $rs->fields[2];
      $this->movil_unidad_placa= $rs->fields[3];
      $this->movil_unidad_chofer= $rs->fields[4];
      $this->movil_unidad_activo= $rs->fields[5];
      $this->fecha_registro= $rs->fields[6];
      $this->usuario_registro= $rs->fields[7];
      $this->fecha_modifica= $rs->fields[8];
      $this->usuario_modifica= $rs->fields[9];
      $this->movil_unidad_espera=$rs->fields[10];
    }

    return $sRpta;
    
    
    
  }

  function AddNew() {
    $sRpta = "OK";
    $sSql ="";
    $cn = $this->getMyConexionADO();
    
    $sSql = "select case when max(movil_unidad_codigo) is null then 1 else max(movil_unidad_codigo)+1 end as id from ca_movilidad_unidad";
    $rs = $cn->Execute($sSql);
    $this->movil_unidad_codigo = $rs->fields[0];
    $params = array(
                    $this->movil_unidad_codigo,
                    $this->movil_unidad_descripcion ,
                    $this->movil_unidad_capacidad ,
                    $this->movil_unidad_placa,
                    $this->movil_unidad_chofer,
                    $this->movil_unidad_activo,
                    $this->movil_unidad_espera
                );
    $sSql = "Insert into ca_movilidad_unidad(
                movil_unidad_codigo,
                movil_unidad_descripcion,
                movil_unidad_capacidad,
                movil_unidad_placa,
                movil_unidad_chofer,
                movil_unidad_activo,
    			fecha_registro,
                movil_unidad_espera)";
    $sSql.= " values(?,?,?,?,?,?,getdate(),?)";
    $rs= $cn->Execute($sSql, $params);
    if($rs==false) $sRpta="Error al Insertar registro";
    return $sRpta;
  }

  function Update() { 
    $sRpta = "OK";
    $sSql ="";
    $cn = $this->getMyConexionADO();

    $params = array(
                    $this->movil_unidad_descripcion,
                    $this->movil_unidad_capacidad,
                    $this->movil_unidad_placa,
                    $this->movil_unidad_chofer,
                    $this->movil_unidad_activo ,
                    $this->movil_unidad_espera,
                    $this->movil_unidad_codigo 
                );
	$sSql="Update ca_movilidad_unidad";
	$sSql .= " set ";
	$sSql .= " movil_unidad_descripcion= ?";
	$sSql .= ", movil_unidad_capacidad= ?";
	$sSql .= ", movil_unidad_placa= ?";
	$sSql .= ", movil_unidad_chofer= ?";
	$sSql .= ", movil_unidad_activo= ?";
	$sSql .= ", fecha_modifica= getdate() ";
	//$sSql .= ", usuario_modifica= " . $this->usuario_modifica . "";
	$sSql .= ", movil_unidad_espera= ?";
    $sSql .= " Where movil_unidad_codigo = ?";
    $rs= $cn->Execute($sSql, $params);
    if($rs==false) $sRpta="Error al Actualizar registro";
    return $sRpta;
  }

}
?>


