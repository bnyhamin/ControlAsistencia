<?php
require_once("../../Includes/mantenimiento.php");
class ca_movilidad extends mantenimiento{
   var $Empleado_Codigo='';
   var $movilidad_fecha='';
   //var $ruta_codigo='';
   var $fecha_registro='';
   var $usuario_registro='';
   var $ruta_codigo='';
   var $ruta_descripcion='';
   var $movil_unidad_codigo='';
   var $movil_unidad_descripcion='';
   var $movil_tipo_codigo='';
   var $movil_tipo_descripcion='';
   var $movilidad_asistencia='';
   var $fecha_sanciona='';
   var $usuario_sanciona='';

  function Query(){
    /*$sRpta = "OK";
    $sSql ="";
    $link = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());

    $sSql="Select Empleado_Codigo,movilidad_fecha, ruta_codigo,fecha_registro,usuario_registro, movilidad_asistencia, convert(varchar(10),fecha_sanciona,103) as fecha_sanciona, usuario_sanciona";
    $sSql.=" From ca_movilidad(nolock)";
    $sSql.= " Where Empleado_Codigo = " . $this->Empleado_Codigo . " and movilidad_fecha=convert(datetime,convert(varchar(10),getdate(),103),103)";
	//echo $sSql;
    $result = mssql_query($sSql);
    if (mssql_num_rows($result)==true){
      $rs= mssql_fetch_row($result);
      $this->Empleado_Codigo= $rs[0];
      $this->movilidad_fecha= $rs[1];
      $this->ruta_codigo=$rs[2];
      $this->fecha_registro= $rs[3];
      $this->usuario_registro= $rs[4];
      $this->movilidad_asistencia=$rs[5];
      $this->fecha_sanciona=$rs[6];
      $this->usuario_sanciona=$rs[7];
    }
    return $sRpta;*/
      
    $sRpta = "OK";
    $sSql ="";
    $cn=$this->getMyConexionADO();

    $sSql="Select Empleado_Codigo,movilidad_fecha, ruta_codigo,fecha_registro,usuario_registro, movilidad_asistencia, convert(varchar(10),fecha_sanciona,103) as fecha_sanciona, usuario_sanciona";
    $sSql.=" From ca_movilidad(nolock)";
    $sSql.= " Where Empleado_Codigo = " . $this->Empleado_Codigo . " and movilidad_fecha=convert(datetime,convert(varchar(10),getdate(),103),103)";
	
    $rs=$cn->Execute($sSql);
    
    if ($rs->RecordCount()>0){
      
      $this->Empleado_Codigo= $rs->fields[0];
      $this->movilidad_fecha= $rs->fields[1];
      $this->ruta_codigo=$rs->fields[2];
      $this->fecha_registro= $rs->fields[3];
      $this->usuario_registro= $rs->fields[4];
      $this->movilidad_asistencia=$rs->fields[5];
      $this->fecha_sanciona=$rs->fields[6];
      $this->usuario_sanciona=$rs->fields[7];
    }
    return $sRpta;
  }

  function Query_registro(){
    /*$sRpta = "OK";
    $sSql ="";
    $link = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());

    $sSql="Select ca_movilidad.ruta_codigo, ca_movilidad.movil_unidad_codigo, ca_rutas.movil_tipo_codigo, ";
    $sSql.=" ca_movilidad.movilidad_asistencia, convert(varchar(10),ca_movilidad.fecha_sanciona,103) as fecha_sanciona, ";
    $sSql.=" ca_movilidad.usuario_sanciona ";
    $sSql.=" From ca_movilidad(nolock) INNER JOIN ca_rutas(nolock) ON ca_movilidad.ruta_codigo = ca_rutas.ruta_codigo AND ca_movilidad.ruta_codigo = ca_rutas.ruta_codigo ";
    $sSql.= " Where ca_movilidad.Empleado_Codigo = " . $this->Empleado_Codigo . " and ca_movilidad.movilidad_fecha=convert(datetime,convert(varchar(10),getdate(),103),103)";
	//echo $sSql;
    $result = mssql_query($sSql);
    if (mssql_num_rows($result)==true){
      $rs= mssql_fetch_row($result);
      $this->ruta_codigo=$rs[0];
      $this->movil_unidad_codigo= $rs[1];
      $this->movil_tipo_codigo= $rs[2];
      $this->movilidad_asistencia=$rs[3];
      $this->fecha_sanciona=$rs[4];
      $this->usuario_sanciona=$rs[5];
    }
    return $sRpta;*/
      
      
    $sRpta = "OK";
    $sSql ="";
    $cn=$this->getMyConexionADO();

    $sSql="Select ca_movilidad.ruta_codigo, ca_movilidad.movil_unidad_codigo, ca_rutas.movil_tipo_codigo, ";
    $sSql.=" ca_movilidad.movilidad_asistencia, convert(varchar(10),ca_movilidad.fecha_sanciona,103) as fecha_sanciona, ";
    $sSql.=" ca_movilidad.usuario_sanciona ";
    $sSql.=" From ca_movilidad(nolock) INNER JOIN ca_rutas(nolock) ON ca_movilidad.ruta_codigo = ca_rutas.ruta_codigo AND ca_movilidad.ruta_codigo = ca_rutas.ruta_codigo ";
    $sSql.= " Where ca_movilidad.Empleado_Codigo = " . $this->Empleado_Codigo . " and ca_movilidad.movilidad_fecha=convert(datetime,convert(varchar(10),getdate(),103),103)";
	
    $rs=$cn->Execute($sSql);
    if ($rs->RecordCount()>0){
      $this->ruta_codigo=$rs->fields[0];
      $this->movil_unidad_codigo= $rs->fields[1];
      $this->movil_tipo_codigo= $rs->fields[2];
      $this->movilidad_asistencia=$rs->fields[3];
      $this->fecha_sanciona=$rs->fields[4];
      $this->usuario_sanciona=$rs->fields[5];
    }
    return $sRpta;
  }

  function Query_asistencia(){
  	$sRpta = "OK";
    $sSql ="";
    $cn = $this->getMyConexionADO();
    //$cn->debug=true;
    $params = array(
                $this->Empleado_Codigo,
                $this->movilidad_fecha
            );
    $sSql="SELECT ca_movilidad.Empleado_Codigo, convert(varchar(10),ca_movilidad.movilidad_fecha,103) as movilidad_fecha, " .
    "		ca_rutas.movil_tipo_codigo, ca_movilidad_tipo.movil_tipo_descripcion," .
    "       ca_movilidad.ruta_codigo, ca_rutas.ruta_descripcion, ca_movilidad.movil_unidad_codigo, ca_movilidad_unidad.movil_unidad_descripcion," .
    "       ca_movilidad.movilidad_asistencia, CONVERT(varchar(10), ca_movilidad.fecha_sanciona, 103) AS fecha_sanciona," .
    "       ca_movilidad.usuario_sanciona" .
	" FROM  ca_movilidad (nolock) INNER JOIN" .
    "      ca_rutas (nolock) ON ca_movilidad.ruta_codigo = ca_rutas.ruta_codigo AND ca_movilidad.ruta_codigo = ca_rutas.ruta_codigo INNER JOIN" .
    "      ca_movilidad_unidad ON ca_movilidad.movil_unidad_codigo = ca_movilidad_unidad.movil_unidad_codigo INNER JOIN" .
    "      ca_movilidad_tipo (nolock) ON ca_rutas.movil_tipo_codigo = ca_movilidad_tipo.movil_tipo_codigo AND " .
    "      ca_rutas.movil_tipo_codigo = ca_movilidad_tipo.movil_tipo_codigo AND ca_rutas.movil_tipo_codigo = ca_movilidad_tipo.movil_tipo_codigo" .
	" WHERE (ca_movilidad.Empleado_Codigo =? ) AND (ca_movilidad.movilidad_fecha = CONVERT(datetime, ?, 103))";
	//echo $sSql;
	$rs = $cn->Execute($sSql, $params);
    if( $rs->RecordCount() > 0){
      $this->movilidad_fecha=$rs->fields[1];
      $this->movil_tipo_codigo= $rs->fields[2];
      $this->movil_tipo_descripcion= $rs->fields[3];
      $this->ruta_codigo=$rs->fields[4];
      $this->ruta_descripcion=$rs->fields[5];
      $this->movil_unidad_codigo= $rs->fields[6];
      $this->movil_unidad_descripcion= $rs->fields[7];
      $this->movilidad_asistencia=$rs->fields[8];
      $this->fecha_sanciona=$rs->fields[9];
      $this->usuario_sanciona=$rs->fields[10];
    }else{
    	$sRpta='Registro no encontrado';
    }
    return $sRpta;
  }

  function AddNew() {
    /*$sRpta = "OK";
    $sSql ="";
    $link = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());

    if ($this->Validar_apertura()=="OK"){ //-- si estamos en el periodo de marcacion, se permite el registro
	    $sSql = "select case when max(Empleado_Codigo) is null then 1 else max(Empleado_Codigo) + 1 end as id from ca_movilidad(nolock) ";
	    $result = mssql_query($sSql);
	    $rs = mssql_fetch_row($result);

	    $sSql = "Insert into ca_movilidad";
	    $sSql .=" (Empleado_Codigo,movilidad_fecha,ruta_codigo,fecha_registro,usuario_registro, movil_unidad_codigo,movilidad_asistencia)";
	    $sSql.= " values(";
	    $sSql.= "\"" . $this->Empleado_Codigo . "\"";
	    $sSql.= ",convert(datetime,\"" . $this->movilidad_fecha . "\",103)";
	    $sSql.= ",\"" . $this->ruta_codigo . "\"";
	    $sSql.= ",getdate()";
	    $sSql.= ",\"" . $this->usuario_registro . "\"";
	    $sSql.= ",\"" . $this->movil_unidad_codigo . "\"";
	    $sSql.= ",1";
	    $sSql.= ")";

	    $r=mssql_query($sSql);

	    if ($r==false) $sRpta="Error al Insertar registro";
    }else{
    	$sRpta="Periodo de Registro de Movilidad CERRADO.<br>NO SE REALIZÓ RESERVA";
    }
    return $sRpta;*/
      
      
    $sRpta = "OK";
    $sSql ="";
    $cn=$this->getMyConexionADO();

    if ($this->Validar_apertura()=="OK"){ //-- si estamos en el periodo de marcacion, se permite el registro
	    $sSql = "select case when max(Empleado_Codigo) is null then 1 else max(Empleado_Codigo) + 1 end as id from ca_movilidad(nolock) ";
	    
	    $rs =$cn->Execute($sSql);
            
            
	    $sSql = "Insert into ca_movilidad";
	    $sSql .=" (Empleado_Codigo,movilidad_fecha,ruta_codigo,fecha_registro,usuario_registro, movil_unidad_codigo,movilidad_asistencia)";
	    $sSql.= " values(?";
	    $sSql.= ",convert(datetime,'" . $this->movilidad_fecha . "',103)";
	    $sSql.= ",?,getdate(),?,?,1)";
	    
            $params=array(
                array($this->Empleado_Codigo, null, null, SQLSRV_SQLTYPE_INT),
                array($this->ruta_codigo, null, null, SQLSRV_SQLTYPE_INT),   
                array($this->usuario_registro, null, null, SQLSRV_SQLTYPE_INT),
                array($this->movil_unidad_codigo, null, null, SQLSRV_SQLTYPE_INT)
            );

	    $rs=$cn->Execute($sSql,$params);

	    if (!$rs) $sRpta="Error al Insertar registro";
    }else{
    	$sRpta="Periodo de Registro de Movilidad CERRADO.<br>NO SE REALIZÓ RESERVA";
    }
    return $sRpta;
      
      
      
      
  }

  function Update() {
    $sRpta = "OK";
    $sSql ="";
    $cn = $this->getMyConexionADO();
    $params = array(
                    $this->usuario_registro,
                    $this->Empleado_Codigo,
                    $this->movilidad_fecha
                );
    $sSql="Update ca_movilidad";
    $sSql .= " set fecha_registro=getdate()";
    $sSql .= ", usuario_registro= ?";
    $sSql .= " Where Empleado_Codigo = ? and movilidad_fecha = convert(datetime, ?,103)";
    $rs= $cn->Execute($sSql, $params);
    if($rs==false) $sRpta="Error al Actualizar registro";
    return $sRpta;
  }

  function Update_asistencia() {
    $sRpta = "OK";
    $sSql ="";
    $cn = $this->getMyConexionADO();
    $params = array(
                    $this->movilidad_asistencia,
                    $this->usuario_sanciona,
                    $this->Empleado_Codigo,
                    $this->movilidad_fecha 
                );
    $sSql="Update ca_movilidad set ";
    $sSql .= " movilidad_asistencia= ?";
    $sSql .= ", fecha_sanciona=getdate()";
 	$sSql .= ", usuario_sanciona= ?";
    $sSql .= " Where Empleado_Codigo = ? and movilidad_fecha = convert(datetime, ?,103)";
    $rs= $cn->Execute($sSql, $params);
	//echo $sSql;
    if($rs==false) $sRpta="Error al Actualizar registro";
    return $sRpta;
  }

  function Delete(){
    /*$sRpta = "OK";
    $sSql ="";
    $link = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());

    $sSql =" DELETE FROM ca_movilidad ";
    $sSql.=" WHERE  (movilidad_fecha=convert(datetime, convert(varchar(10),getdate(),103),103))";
    $sSql.="        AND (Empleado_Codigo =".$this->Empleado_Codigo.")";

    //echo $sSql."<br>";
    $r=mssql_query($sSql);

    if ($r!=0) $sRpta="Error al Eliminar registro";
    return $sRpta;*/
      
    $sRpta = "OK";
    $sSql ="";
    $cn=$this->getMyConexionADO();

    $sSql =" DELETE FROM ca_movilidad ";
    $sSql.=" WHERE  (movilidad_fecha=convert(datetime, convert(varchar(10),getdate(),103),103))";
    $sSql.="        AND (Empleado_Codigo =".$this->Empleado_Codigo.")";

    $rs=$cn->Execute($sSql);
    

    if (!$rs) $sRpta="Error al Eliminar registro";
    
    return $sRpta;
  }


  function bloquear_registro_movilidad($ruta_codigo, $fecha){
    //$fecha= 24/10/2008
    $clave = 0;
    $sSql ="";
    $cn = $this->getMyConexionADO();

    $sSql  =" SELECT * FROM ca_movilidad_control(nolock) ";
    $sSql .=" WHERE ruta_codigo=$ruta_codigo AND FECHA= CONVERT(DATETIME, '$fecha', 103) ";

    $rs = $cn->Execute($sSql);
    if( $rs->RecordCount()>0){
      // si hay fila
      $clave=1;
    }else{
      //no hay fila
      $clave=0;
    }
    return $clave;
  }

  function existe_fecha_registrada($fecha){
    /*$existe = false;
    $sSql ="";
    $link = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());

    $sSql  =" SELECT Empleado_Codigo, movilidad_fecha, ruta_codigo, movil_unidad_codigo ";
    $sSql .=" FROM   ca_movilidad(nolock) ";
    $sSql .=" WHERE  (Empleado_Codigo = $this->Empleado_Codigo ) AND movilidad_fecha=convert(datetime, convert(varchar(10), '$fecha' ,103),103) ";

    //echo $sSql."<br>";
    $result = mssql_query($sSql);
    if(mssql_num_rows($result)>0){ // si hay filas de 1 a mas
      $existe = true;
    }else{ //no hay fila
      $existe = false;
    }
    return $existe;*/
      
      
    $existe = false;
    $sSql ="";
    $cn=$this->getMyConexionADO();

    $sSql  =" SELECT Empleado_Codigo, movilidad_fecha, ruta_codigo, movil_unidad_codigo ";
    $sSql .=" FROM   ca_movilidad(nolock) ";
    $sSql .=" WHERE  (Empleado_Codigo = $this->Empleado_Codigo ) AND movilidad_fecha=convert(datetime, convert(varchar(10), '$fecha' ,103),103) ";

    $rs=$cn->Execute($sSql);
    
    
    if($rs->RecordCount()>0){ // si hay filas de 1 a mas
      $existe = true;
    }else{ //no hay fila
      $existe = false;
    }
    
    return $existe;
      
  }

  function retornar_estado_bus(){
    /*//RETORNA CANTIDAD DE PERSONAS QUE OCUPAN EL BUS EN LA FECHA, RUTA Y MOVILIDAD RESPETIVA
	$sRpta = "OK";
	$sSql ="";
	$capacidad=0;
	$link = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());

	//$vt=array();
	//echo 'db: ' . $this->getMyUrl();
	$sSql="SELECT ca_movilidad_unidad.movil_unidad_capacidad, ";
	$sSql .=" COUNT(ca_movilidad.Empleado_Codigo) AS OCUPADO ";
	$sSql .=" FROM  ca_movilidad(nolock) INNER JOIN ";
	$sSql .="       ca_movilidad_unidad(nolock) ON ca_movilidad.movil_unidad_codigo = ca_movilidad_unidad.movil_unidad_codigo ";
	$sSql .=" WHERE ca_movilidad.ruta_codigo=" . $this->ruta_codigo;
	$sSql .=" 	AND ca_movilidad.movil_unidad_codigo=" . $this->movil_unidad_codigo;
	$sSql .=" 	AND ca_movilidad.movilidad_fecha = CONVERT(DATETIME,'" . $this->movilidad_fecha . "', 103)";
	$sSql .=" GROUP BY ca_movilidad_unidad.movil_unidad_capacidad ";

	$result= mssql_query($sSql);
	if (mssql_num_rows($result)>0){
      $rs= mssql_fetch_row($result);
      //echo " dentro ";
		$vt[0]=$rs[0];
		$vt[1]=$rs[1];
	}else{
		$vt[0]=0;
		$vt[1]=0;
	}
	//return $sRpta;
    return $vt;*/
      
      
      
      
      
    //RETORNA CANTIDAD DE PERSONAS QUE OCUPAN EL BUS EN LA FECHA, RUTA Y MOVILIDAD RESPETIVA
    $sRpta = "OK";
    $sSql ="";
    $capacidad=0;
    $cn=$this->getMyConexionADO();
    
    //$vt=array();
    //echo 'db: ' . $this->getMyUrl();
    $sSql="SELECT ca_movilidad_unidad.movil_unidad_capacidad, ";
    $sSql .=" COUNT(ca_movilidad.Empleado_Codigo) AS OCUPADO ";
    $sSql .=" FROM  ca_movilidad(nolock) INNER JOIN ";
    $sSql .="       ca_movilidad_unidad(nolock) ON ca_movilidad.movil_unidad_codigo = ca_movilidad_unidad.movil_unidad_codigo ";
    $sSql .=" WHERE ca_movilidad.ruta_codigo=" . $this->ruta_codigo;
    $sSql .=" 	AND ca_movilidad.movil_unidad_codigo=" . $this->movil_unidad_codigo;
    $sSql .=" 	AND ca_movilidad.movilidad_fecha = CONVERT(DATETIME,'" . $this->movilidad_fecha . "', 103)";
    $sSql .=" GROUP BY ca_movilidad_unidad.movil_unidad_capacidad ";

    $rs=$cn->Execute($sSql);
    if ($rs->RecordCount()>0){
        $vt[0]=$rs->fields[0];
        $vt[1]=$rs->fields[1];
    }else{
        $vt[0]=0;
        $vt[1]=0;
    }

    return $vt;
      
  }

  function Posicion_lista(){
  	$numero=0;
  	$cn = $this->getMyConexionADO();
    $params = array(
                    $this->ruta_codigo,
                    $this->movil_unidad_codigo
                );
	$sSql="SELECT  ca_movilidad.Empleado_Codigo, " .
			"	ca_movilidad_unidad.movil_unidad_capacidad, " .
			"	ca_movilidad_unidad.movil_unidad_espera, " .
			"	ca_movilidad.fecha_registro ";
	$sSql .=" FROM ca_movilidad(nolock) INNER JOIN ca_movilidad_unidad(nolock) ON ca_movilidad.movil_unidad_codigo = ca_movilidad_unidad.movil_unidad_codigo ";
	$sSql .=" WHERE ca_movilidad.movilidad_fecha = CONVERT(DATETIME,convert(varchar(10),getdate(),103), 103)";
	$sSql .=" 		and ca_movilidad.ruta_codigo= ?";
	$sSql .=" 		and ca_movilidad.movil_unidad_codigo= ?";
	$sSql .=" Order by ca_movilidad.fecha_registro";
	//echo $sSql;
	$rs= $cn->Execute($sSql, $params);
	$n=0;
	while(!$rs->EOF){
		$n+=1;
		if($this->Empleado_Codigo*1== $rs->fields[0]*1){
			if ($n<=$rs->fields[1]) echo "Ud. tiene la reserva Nro. " . $n;
			if ($n>$rs->fields[1] and $n<=$rs->fields[1]+$rs->fields[2]) echo "Ud. está en Lista de Espera";
			return $n;
		}
        $rs->MoveNext();
	}

  	return $numero;

  }
  function Validar_apertura(){
  /*	$sRpta = "";
    $sSql ="";
    $link = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());

	//-- validar que estemos en el tiempo de marcacion
	$sSql="select top 1 1 as resultado " .
	" from ca_rutas(nolock) " .
	" where getdate() between convert(datetime,(SELECT CONVERT(varchar(10), GETDATE(), 103) + ' ' + Item_Default AS tiempos " .
	" 			FROM  Items(nolock) " .
	" 			WHERE (Item_Codigo = 640)),103) " .
	" 		and	convert(datetime,(SELECT CONVERT(varchar(10), GETDATE(), 103) + ' ' + Item_Default AS tiempos " .
	" 			FROM  Items(nolock) " .
	" 			WHERE (Item_Codigo = 641)),103)";
	$resul = mssql_query($sSql);
	if (mssql_num_rows($resul)>0){
		$sRpta = "OK";
	}else{
		$sRpta = "";
	}
	return $sRpta;*/
      
      
      
        $sRpta = "";
        $sSql ="";
        $cn=$this->getMyConexionADO();

	//-- validar que estemos en el tiempo de marcacion
	$sSql="select top 1 1 as resultado " .
	" from ca_rutas(nolock) " .
	" where getdate() between convert(datetime,(SELECT CONVERT(varchar(10), GETDATE(), 103) + ' ' + Item_Default AS tiempos " .
	" 			FROM  Items(nolock) " .
	" 			WHERE (Item_Codigo = 640)),103) " .
	" 		and	convert(datetime,(SELECT CONVERT(varchar(10), GETDATE(), 103) + ' ' + Item_Default AS tiempos " .
	" 			FROM  Items(nolock) " .
	" 			WHERE (Item_Codigo = 641)),103)";
        
	$rs=$cn->Execute($sSql);
        
	if ($rs->RecordCount()>0){
            $sRpta = "OK";
	}else{
            $sRpta = "";
	}
        
	return $sRpta;
      
  }
  
  function Tiene_suspension(){
  	/*$sSql ="";
    $link = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());
	$retorno=0;
	//-- consultar si tenemos suspension
	$sSql="select count(empleado_codigo) as total " .
	" from ca_movilidad(nolock) " .
	" where empleado_Codigo = $this->Empleado_Codigo and movilidad_asistencia=2";
	$resul = mssql_query($sSql);
	$rs=mssql_fetch_row($resul);
	if ($rs[0]>0){
		$retorno=$rs[0];
	}
	return $retorno;*/
      
    $sSql ="";
    $cn=$this->getMyConexionADO();
    $retorno=0;
    //-- consultar si tenemos suspension
    $sSql="select count(empleado_codigo) as total " .
    " from ca_movilidad(nolock) " .
    " where empleado_Codigo = $this->Empleado_Codigo and movilidad_asistencia=2";
    
    $rs=$cn->Execute($sSql);
    if($rs->RecordCount()>0){
        if($rs->fields[0]>0){
            $retorno=$rs->fields[0];
        }
    }
    
    return $retorno;
      
  }

  function Suspender($dias_suspension){
  	$sSql ="";
    $cn = $this->getMyConexionADO();
	$retorno=false;
	//-- validar que estemos en el tiempo de suspension
	$sSql="select TOP 1 movilidad_fecha, fecha_sanciona, DATEDIFF(d, fecha_sanciona, GETDATE()) + 1 AS dias " .
	" from ca_movilidad(nolock) " .
	" where empleado_Codigo = $this->Empleado_Codigo and movilidad_asistencia=2" .
	" ORDER BY movilidad_fecha DESC";
	$rs = $cn->Execute($sSql);
	if( $rs->RecordCount()>0){
		if($rs->fields[2]<=$dias_suspension){
			$retorno=true;
		}
	}
	return $retorno;
  }
  
   
  
}
?>
