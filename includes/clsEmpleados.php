<?php
require_once("Constantes.php");
require_once("mantenimiento.php");
class Empleados extends mantenimiento{
	var $resultado;
	var $myParm1;
	var $empleado_codigo="";
	var $empleado_apellido_paterno="";
	var $empleado_apellido_materno="";
	var $empleado_nombres="";
	var $empleado_carnet="";
	var $empleado_dni="";
	var $empleado_email="";
	var $empleado_estado_civil='';
	var $rqto_codigo="";
	var $postulante_codigo="";
	var $estado_codigo="";
	var $estado_civil="";
	var $local_codigo="";
	var $turno_codigo="";
	var $usuario_id="";
	var $empleado_responsable_area="";
	var $empleado_fecha_ingreso="";
	var $myAtributo_Codigo='';
	var $myconnection='';

	var $empleado_sexo ='';
	var $empleado_ruc = '';
	var $empleado_lib_militar ='';
	var $empleado_dependientes='';
	var $empleado_hijos_mayores = '';
	var $usuario_nick = '';

	var $empleado_nombre_via = '';
	var $empleado_nro = '';
	var $distrito_residencia = '';
	var $Dist_Residencia_desc = '';
	var $empleado_fecha_nacimiento = '';
	var $distrito_nacimiento = '';

	var $nombre_zona='';
	var $referencia_direccion='';
	var $cod_zona='';

	var $empleado_tlf = '';
	var $empleado_celular = '';
	var $empleado_tlf_referencia = '';
	var $empleado_preguntar_por = '';

	var $movimiento_codigo='';
	var $area_codigo='';
	var $area_descripcion='';
	var $empleado_jefe='';
	var $sueldo='0';

	var $Ccto_Codigo='';
	var $Ccb_Codigo='';
	var $Ccr_Codigo='';
	var $Empleado_Trasvase='';
	var $empleado_num_seguro='';
	var $moneda_codigo='';

	var $urba_nombre="";
	var $empleado_interior="";
	var $ipss_vida='N';
    var $seguro_vida_ley='N';
    var $sctr='N';
    var $domiciliado='S';
    var $id_nomina=1;
    var $tipo_nomina_codigo=1;
    var $indicador_essalud=0;
	//--Variables RTPS
  var $TDI_codigo ='';
  var $codigo_nacionalidad='';
  var $codigo_zona= '';
  var $descripcion_zona= '';
  var $trabajador_tipo = '';
  var $horario_nocturno = '';
  var $academico_codigo= '';
  var $discapacidad= '';
  var $situacion_especial= '';
  var $sindicalizado= '';
  var $tipo_actividad= '';
  var $regimen_alternativo = '';
  var $jornada_maxima = '';
  var $regimen_fecha_inscripcion='';
  var $modalidad_formativa='';
  //var $local_codigo='';
  var $local_descripcion='';
  var $sqlquerycargo;
  var $Cod_Campana = "";
  var $Exp_NombreCorto = "";
  var $empleado_tipo_pago=NULL;
  var $nro_sueldos='';
  var $aplica_mr='';
  var $aplica_srv='';
  var $msg_caducidad = '';
  var $arrF=array();
  var $tipocomisionafp='';

//----------fin.

function Query(){
    $rpta="";
    $cn = $this->getMyConexionADO();
    //$cn->debug=true;
    $params = array($this->empleado_codigo);
    $ssql = "SELECT Empleado_Codigo, Empleado_Carnet, empleado_apellido_paterno, empleado_apellido_materno, empleado_nombres, ";
    $ssql .= " Empleado_Dni, Empleado_Email, Postulante_Codigo, ";
    $ssql .= " usuario_Id, 0 AS Responsable_Area, ";
    $ssql .= " convert(varchar(10),empleado_fecha_ingreso,103) as fecha_ingreso, estado_codigo, urba_nombre,  empleado_interior, ";
    $ssql .= " empleados.local_codigo, locales.local_descripcion, referencia_direccion, codigo_zona,Empleado_sexo ";
    $ssql .= " From Empleados left join locales on ";
    $ssql .= "   empleados.local_codigo=locales.local_codigo ";
    $ssql .= " WHERE empleado_codigo = ?";
	$rs = $cn->Execute($ssql, $params);
	if( $rs->RecordCount() >0){
            $this->empleado_carnet            = $rs->fields[1];
            $this->empleado_apellido_paterno  = $rs->fields[2];
            $this->empleado_apellido_materno  =	$rs->fields[3];
            $this->empleado_nombres           =	$rs->fields[4];
            $this->empleado_dni               =	$rs->fields[5];
            $this->empleado_email             =	$rs->fields[6];
            $this->postulante_codigo          =	$rs->fields[7];
            $this->usuario_id                 =	$rs->fields[8];
            $this->empleado_responsable_area  =	$rs->fields[9];
            $this->empleado_fecha_ingreso     = $rs->fields[10];
            $this->estado_codigo              = $rs->fields[11];
            $this->urba_nombre                = $rs->fields[12];
            $this->empleado_interior          = $rs->fields[13];
            $this->local_codigo               = $rs->fields[14];
            $this->local_descripcion          = $rs->fields[15];
            $this->referencia_direccion       = $rs->fields[16];
            $this->cod_zona                   = $rs->fields[17];
            $this->Empleado_sexo              = $rs->fields[18];
            $rpta = "OK";
	}else{
            $rpta="No se encontro registro: " . $this->empleado_codigo;
	}
	return $rpta;
}

function AccesoPersonal($usuario){
   $cn=$this->getMyConexionADO();
   //$cn->debug = true;

   if($usuario==""){
      return "207";//acceso de consulta
   }
   
   $ssql = "SELECT empleados.Empleado_Codigo, empleados.Empleado_Codigo as Usuario_Id, CASE WHEN Empleados.Empleado_Codigo = Areas.empleado_responsable THEN 1 ELSE 0 END AS responsable " .
		" FROM Empleados INNER JOIN " .
    	"      Empleado_Area ON Empleados.Empleado_Codigo = Empleado_Area.Empleado_Codigo INNER JOIN " .
    	"      Areas ON Empleado_Area.Area_Codigo = Areas.Area_Codigo " .
		" WHERE (Empleados.Empleado_Codigo = ?) AND (Empleado_Area.Empleado_Area_Activo = 1)";
   
   $params=array($usuario);
   $rs=$cn->Execute($ssql,$params);
   if($rs->RecordCount()>0){
      $empleado=$rs->fields[0];
      if($rs->fields[2]==1){
         $acceso="234"; //Responsable de Area
      }else{
         $acceso="207"; //Consulta
      }
      $tipo="206";     //Responsable Administrador
      $ssql="SELECT Empleado_Codigo, Especial_Tipo ";
      $ssql.=" From Especiales WHERE (Empleado_Codigo = ? ) AND (Especial_Activo = 1) AND (Especial_Tipo = ?) ";
      $params=array(
          $empleado,
          $tipo
      );
      
      $rs=$cn->Execute($ssql,$params);
      if($rs->RecordCount()>0){ //si es administrador
         $acceso = $tipo;
      }else{
        $tipo = "237";
        $ssql="SELECT Empleado_Codigo, Especial_Tipo";
        $ssql.=" From Especiales WHERE (Empleado_Codigo = ?) AND (Especial_Activo = 1) AND (Especial_Tipo = ?) ";
        $params=array($empleado,$tipo);
        
        $rs=$cn->Execute($ssql,$params);
        if($rs->RecordCount()>0) $acceso=$tipo;
      }

   }else{
      return "207";
   }
   return $acceso;
}

//(*)funcion de acceso especiales
function Acceso_Especiales($usuario,$tipo){
   //$usuario=3300 $tipo=461
   $cn=$this->getMyConexionADO();
   $acceso_especiales=0;
   $ssql = "SELECT Empleados.Empleado_Codigo,Empleados.Usuario_Id, Especiales.Especial_Tipo, Especiales.Especial_Activo";
   $ssql.= " FROM Empleados INNER JOIN Especiales ON Empleados.Empleado_Codigo = Especiales.Empleado_Codigo";
   $ssql.= " WHERE Empleados.Usuario_Id = ? AND Especiales.Especial_Tipo = ? ";
   $params=array($usuario,$tipo);
   $rs=$cn->Execute($ssql,$params);
   if($rs->RecordCount()) $acceso_especiales=1;
   return $acceso_especiales;
}

//funcion de modalidades
function Emp_Modalidades(){
   //arreglo de modalidades
   $cn=$this->getMyConexionADO();
   $sms="";
   $ssql="SELECT Items.Item_Codigo, Items.Item_Descripcion, Tablas.Tabla_Descripcion";
   $ssql.=" FROM Items INNER JOIN Tablas ON Items.Tabla_Codigo = Tablas.Tabla_Codigo AND Items.Tabla_Codigo = Tablas.Tabla_Codigo";
   $ssql.=" WHERE (Tablas.Tabla_Codigo = 7 and Items.Item_activo=1) order by 2";
   $rs=$cn->Execute($ssql);
   if($rs->RecordCount()>0){
      $sms = "\n<script language=javascript>";
      $sms.= "\n var arr_M = new Array();";
      $sms.= " var arrd_M = new Array();";
      $i=0;
      while(!$rs->EOF){
         $sms.= "\n arr_M[". $i . "] = ".$rs->fields[0].";"; // item_codigo
		   $sms.= " arrd_M[". $i . "] = '".$rs->fields[1]."';"; // item_descripcion
         $i=$i+1;
         $rs->MoveNext();
      }
   }
   //arreglo de tipos de remuneraciones

   $ssql = "select item_codigo, item_descripcion from Items";
   $ssql.= " where Tabla_Codigo=39 and Item_Activo=1";
   $rs=null;

   $rs=$cn->Execute($ssql);
   if($rs->RecordCount()){
      $sms.= "\n var arr_TR = new Array();";
      $sms.= " var arrd_TR = new Array();";
      $i=0;
      while(!$rs->EOF){
         $sms.= "\n arr_TR[". $i . "] = ".$rs->fields[0].";"; // item_codigo
         $sms.= " arrd_TR[". $i . "] = '".$rs->fields[1]."';"; // item_descripcion
         $i=$i +1;
         $rs->MoveNext();
      }
   }
   $sms.="\n </script>";
   return $sms;
}

//(*)funcion zona03 seleccionatotales
function seleccionaTotales($Horario){
   $sms="";
   $cn=$this->getMyConexionADO();
   $ssql ="SELECT Items.Item_Codigo, Items.Item_Descripcion, Tablas.Tabla_Descripcion FROM Items INNER JOIN ";
   $ssql.=" Tablas ON Items.Tabla_Codigo = Tablas.Tabla_Codigo AND Items.Tabla_Codigo = Tablas.Tabla_Codigo ";
   $ssql.=" WHERE (Tablas.Tabla_Codigo = 10 and Items.Item_activo=1) order by 2";

   $rs=$cn->Execute($ssql);
   if($rs->RecordCount()>0){
      $sms = "\n<script language=javascript>";
      $sms.="\n var arr_H = new Array();";
      $sms.=" var arrd_H = new Array();";
      $sms.="\n</script>";

      $i=0;
      while(!$rs->EOF){
         $okSel = '';
         //item_codigo
         if ($rs->fields[0] == $Horario)	$okSel=' Selected ';
         //echo "\n<option value='" . $rs[0] . "' " . $okSel . " >" . $rs[1] . "</option>";
         $sms.= "\n <script language=javascript>";
         $sms.= "\n arr_H[". $i . "] = " . $rs->fields[0] . ";";
         $sms.= " arrd_H[". $i . "] = '" . $rs->fields[1] . "';";
         $sms.= "\n </script>";
         $i=$i +1;
         $rs->MoveNext();
      }
   }
   return $sms;
}

//funcion zona03 seleccionaMoneda
function seleccionaMoneda($Comision_Moneda){
   $sms="";
   $cn=$this->getMyConexionADO();
   $ssql ="select Moneda_Codigo, Moneda_Descripcion from Monedas";
	$ssql.=" where Moneda_Activo = 1 order by 2";
   $rs=$cn->Execute($ssql);
   if($rs->RecordCount()>0){
      $sms="\n<script language=javascript>";
      $sms.="\n var arr_Mo = new Array();";
      $sms.=" var arrd_Mo = new Array();";
      $sms.="\n</script>";
      $i=0;
      while(!$rs->EOF){
         $okSel = '';
         //Moneda_codigo
         if ($rs->fields[0] == $Comision_Moneda)	$okSel=' Selected ';
         // echo "\n<option value='" . $rs[0] . "' " . $okSel . " >" . $rs[1] . "</option>";
         $sms.="\n <script language=javascript>";
         $sms.="\n arr_Mo[". $i . "] = " . $rs->fields[0] . ";";//Moneda_codigo
         $sms.=" arrd_Mo[". $i . "] = '" . $rs->fields[1] . "';";//Moneda_descripcion
         $sms.="\n </script>";
         $i=$i +1;
         $rs->MoveNext();
      }
   }
   return $sms;
}

//function seleccionRequisitoscargo
function seleccionRequisitoscargo(){
   $sms="";
   $cn=$this->getMyConexionADO();
   $ssql ="SELECT Perfil_codigo, Perfil_Descripcion FROM Perfil_Cargo ";
   $ssql.=" WHERE Perfil_activo=1 order by 2";
   $rs=$cn->Execute($ssql);
   if($rs->RecordCount()>0){
      $sms ="\n<script language=javascript>";
      $sms.="\n var arr_P = new Array();";
      $sms.=" var arrd_P = new Array();";
      $sms.="\n</script>";
      $i=0;
      $sms.="\n <script language=javascript>";
      while(!$rs->EOF){
         $sms.="\n arr_P[". $i . "] = " . $rs->fields[0] . ";";
         $sms.=" arrd_P[". $i . "] = '" . $rs->fields[1] . "';";
         $i=$i +1;
         $rs->MoveNext();
      }
     $sms.="\n </script>";
   }
   return $sms;
}


//(**)function devueleve query
//marca
function getQueryCargo($filtro){

      $ssql ="SELECT banda.banda_codigo, banda.cargo_codigo, c.Item_Descripcion AS cargo, ";
      $ssql.="banda.horario_codigo, h.Item_Descripcion AS horario, ";
      $ssql.="banda.salario_minimo, banda.salario_maximo, g.grupo_o_descripcion ";
      $ssql.="FROM banda INNER JOIN ";
      $ssql.="Items c ON banda.cargo_codigo = c.Item_Codigo INNER JOIN ";
      $ssql.="Items h ON banda.horario_codigo = h.Item_Codigo ";
      $ssql.="inner join grupos_cargos gc on c.item_codigo=gc.cargo_codigo ";
      $ssql.="inner join grupos_ocupacionales g on gc.grupo_o_codigo=g.grupo_o_codigo ";
      $ssql.="WHERE  (banda.banda_activo = 1) AND ";
      $ssql.="(c.Item_Activo = 1) AND ";
      $ssql.="(h.Item_Activo = 1) AND ";
      $ssql.="(c.Tabla_Codigo = 11) AND ";
      $ssql.="(h.Tabla_Codigo = 10) ";
      $ssql.="and (gc.grupo_cargo_activo=1) ";
      if (strlen($filtro)>0) {
         $ssql.= " and upper(c.Item_Descripcion) like '%" . strtoupper($filtro) . "%' ";
      }
      $ssql.= " ORDER BY c.Item_Descripcion, h.Item_Descripcion";

      $this->sqlquerycargo=$ssql;
}

//function seleccionaCargos
function seleccionaCargos(){
   $sms='';
   $cn=$this->getMyConexionADO();
   
   $ssql=$this->sqlquerycargo;

   $rs=$cn->Execute($ssql);
   if($rs->RecordCount()>0){
    //echo $rs->RecordCount();
      while(!$rs->EOF){
         $sms.='<tr onMouseout=this.style.backgroundColor="" onMouseover=this.style.backgroundColor="#ffebd7">';
         $sms.='<td class="texto">';
         $sms.='<font id="font'.$rs->fields[0].'" title="'.$rs->fields[0].'" style="CURSOR: hand" LANGUAGE="javascript" onclick="return font01_onclick(font'.$rs->fields[0].',\''.$rs->fields[1].'\',\''.$rs->fields[2].'\',\''.$rs->fields[3].'\',\''.$rs->fields[5].'\')" color="Black">'.$rs->fields[0].'</font>';
         $sms.='</TD>';
         $sms.='<TD class="texto">'.$rs->fields[2];
         $sms.='<TD class="texto">'.$rs->fields[4];
         $sms.='<TD class="texto" align="right">'.$rs->fields[5];
         $sms.='<TD class="texto" align="right">'.$rs->fields[6];
         $sms.='<TD class="texto">'.$rs->fields[7];
         $sms.='</TD>';
         $sms.='</TR>';
         $rs->MoveNext();
      }
   }

   return $sms;
}


function EmpleadoCodigo($usuario){
	$cn = $this->getMyConexionADO();
    $params = array( $usuario);
	$ssql = "Select Empleado_Codigo from empleados where usuario_id= ?";
	$rs = $cn->Execute($ssql, $params);
	if( $rs->RecordCount() >0){
		return $rs->fields[0];
	}else{
		return "";
	}
}

function EmpleadoDNI($empleado_dni){
    $cn = $this->getMyConexionADO();
    $params = array($empleado_dni);
    $ssql = "Select Empleado_Codigo from empleados where empleado_dni=?";
    $rs = $cn->Execute($ssql, $params);
    if( $rs->RecordCount()>0){
        return $rs->fields[0];
    }else{
        return "";
    }
}

function Empleado_Estado($empleado, $estado){
    $cn = $this->getMyConexionADO();
    $params = array(
                    $empleado,
                    $estado
                    );
	$ssql = "select * from empleados where empleado_codigo = ? and estado_codigo = ?";
	$rs = $cn->Execute($ssql, $params);
	if( $rs->RecordCount() >0) return 1;
	else return 0;
}

function CurriculumAyuda($codigo){
    $cn = $this->getMyConexionADO();
    $params = array($codigo);
	$ssql = "SELECT ec_codigo,ec_orden,ec_tipo_dato_codigo,ec_campo_nombre,ec_campo_alias,ec_campo_ayuda_texto,ec_campo_ayuda,ec_campo_activo
             FROM Empleado_campos
             WHERE ec_codigo= ?";
	$rs = $cn->Execute($ssql, $params);
    return $rs->fields;
}

function CurriculumAyudaRegistros($codigo){
	$rpta = "";
	$cn = $this->getMyConexionADO();
	$params = array($codigo);
	$ssql = "SELECT ec_campo_nombre
             FROM Empleado_campos
             WHERE ec_codigo= ?";
	$rs = $cn->Execute($ssql, $params);

	$ssql = "SELECT isnull(" . $rs->fields[0] . ",'') as campo
             FROM v_empleados_filtro
             GROUP BY " . $rs->fields[0] . "
             ORDER BY 1";
	$this->resultado = $cn->Execute($ssql);
    $rs2 = $this->resultado;
	$i=0;
	while(!$rs2->EOF){
		$i += 10;
		$rpta .= "<tr>\n";
		$rpta .= "\t<td class=DataTD><font id=font" . $i . " title='" . $rs2->fields[0] . "' style='CURSOR: hand' LANGUAGE=javascript onclick=\"return font01_onclick(this," . $i . ",'" . $rs2->fields[0] . "')\">" . $i . "</font></td>\n";
		$rpta .= "\t<td class=DataTD>" . $rs2->fields[0] . "</td>\n";
		$rpta .= "</tr>\n";
        $rs2->MoveNext();
	}
	return $rpta;
}

function Igualdad($Logico_Codigo,$Operacion,$valor,$TipoDato){
	$cadena="";
	switch ($TipoDato) {
		case "1":
		case "2":
		case "3":
		case "6":
			$cadena = $Operacion . " " . $valor;
			break;
		case "4":
       		if($Logico_Codigo == "6") $cadena = "like '%" . $valor . "%'";
       		else $cadena = $Operacion . " '" . $valor . "' ";
			break;
	    case "5":
        	$cadena = $Operacion . " convert(datetime, '" . $valor . "', 103)";
        break;
	    default:
	    	$cadena = "";
	    	break;
	}
	return $cadena;
}

function ObtenerFiltro($cadena,$marcaFil,$marcaCol){
		$cad = "";
	$myParm1 = "";
	$arr = split($marcaFil,$cadena);
	$i = 0;
	$finales = sizeof($arr);
	for ($i=0;$i<$finales-1;$i++){
		$xarr = split($marcaCol,$arr[$i]);
		//echo "<br>".$xarr[0];
	   	$rs = $this->CurriculumAyuda($xarr[0]);
   		// crear condicion
     	$subCad = "( " . $rs[3]; //Campo_Nombre
     	// obtener simbolo logico de la operacion
      	$rsL = $this->logico($xarr[1]);
      	$subCad .= " " . $this->Igualdad($xarr[1], $rsL->fields[1], $xarr[2], $rs[2]);
      	$subCad .= " ) ";
      	if ($cad == "") $cad = $subCad;
	    else $cad .= " and " . $subCad;
	}
	// obtener los campos a filtrar segun el codigo de alias ingresado
	$ssql = "select distinct Empleado_Codigo from v_empleados_filtro ";
	if ($cad != "") $ssql .= " where " . $cad;
    
	$this->myParm1 = $ssql;
	return "OK";
}

function logico($codigo){

	$cn = $this->getMyConexionADO();
    $params = array($codigo);
	$ssql = "select logico_codigo, logico_Simbolo from logicos where logico_codigo = ?";
	$rs = $cn->Execute($ssql, $params);
	return $rs;
}

function verificar_USR_PWD($sUsuario,$sClave,$sNewClave){ //validacion de EnLinea
    $rpta="";
    $cn = $this->getMyConexionADO();
    //$cn->debug=true;
    $params = array($sUsuario,$sClave);
    $ssql = "SELECT empleado_codigo, empleado_clave_modificada
         FROM empleados
         WHERE empleado_dni=? AND empleado_clave_acceso=dbo.udf_md5(?) and estado_codigo=2";
    
    $rs= $cn->Execute($ssql, $params);
    if( $rs->RecordCount()==1){
        return "Empleado Cesado";
    }else{
        $ssql = "SELECT empleado_codigo, empleado_clave_modificada
         FROM empleados
         WHERE empleado_dni=? AND empleado_clave_acceso=dbo.udf_md5(?) and estado_codigo=1";
	$rs= $cn->Execute($ssql, $params);
	if( $rs->RecordCount()==0){
	    //return "DNI y/o Clave Errada";
            return "Credenciales Incorrectas";
	}else{
 
            $ssql2 = " exec spEmp_Valida_Caducidad ? ";
            $params = array($sUsuario);
            $rs2 = $cn->Execute($ssql2, $params);
            if($rs2->RecordCount() > 0){
                $ar_res = array($rs2->fields[0],$rs2->fields[1]);
                if ($ar_res[0]) {
                    $this->empleado_codigo = $rs->fields[0];
                    if ($ar_res[1] == 'Vigente') {
                      $this->msg_caducidad = '';
                    }else{
                      $this->msg_caducidad = $ar_res[1];
                    }
                    $rpta = "OK";
                }else{
                    
                    return $ar_res[1];
                }
            }
        }
    }
	
	if ($sNewClave != "") { //-- cambiar clave
	    
	    if ($sUsuario == $sNewClave) return "Usuario y clave deben ser diferentes";
	    
        $params = array($sUsuario,$sNewClave);
        $ssql= "exec spRRHH_actualizar_clave ?,?";
   	    
        $rs2 = $cn->Execute($ssql, $params);
        
        if ($rs2->fields[0]*1==1){
			//ADICIONAR EMPLEADO EN CA_CAMBIO_CLAVE
      $params = array($sUsuario);
			$ssql = "delete from CA_CAMBIO_CLAVE where empleado_dni = ?";
			$_rs = $cn->Execute($ssql, $params);
            $params = array($sUsuario,$sNewClave);
			$ssql = "insert into CA_CAMBIO_CLAVE (empleado_dni, empleado_nueva_clave) values (?,?)";
			$__rs = $cn->Execute($ssql, $params);
	        $rpta="CMB";
	        return $rpta;
        }
        else{
            return $rs2->fields[1];
        }
	}else{
	    if ($rs->fields[1] == 0){
	        return "MPD";
	    }
	}
	return $rpta;
}


function verificar_USR_PWD_Antiguo($sUsuario,$sClave,$sNewClave){ //validacion de EnLinea
	$rpta="";
	$cn = $this->getMyConexionADO();
        //$params = array($sUsuario,$sClave);
  $params = array($sUsuario,$sClave);
        /*$ssql = "SELECT empleado_codigo, empleado_clave_modificada
             FROM empleados
             WHERE empleado_dni=? AND empleado_clave_acceso=dbo.udf_md5(?) and estado_codigo=2";*/
  
	$ssql = "SELECT empleado_codigo, empleado_clave_modificada
             FROM empleados
             WHERE empleado_dni=? AND empleado_clave_acceso=dbo.udf_md5(?) and estado_codigo=1";
	$rs= $cn->Execute($ssql, $params);
	if( $rs->RecordCount()==0){
	    return "DNI y/o Clave Errada y/o Empleado Cesado";
	}else{
 

       // $cn->debug=true;
        $ssql2 = " exec spEmp_Valida_Caducidad ? ";
        $params = array($sUsuario);
        $rs2 = $cn->Execute($ssql2, $params);
        //echo 'rs2[1]:'.$rs2[1] . ' rs2[0]:'.$rs2[0];
        if($rs2->RecordCount() > 0){

          $ar_res = array($rs2->fields[0],$rs2->fields[1]);

          if ($ar_res[0]) {

            $this->empleado_codigo = $rs->fields[0];
            if ($ar_res[1] == 'Vigente') {
              $this->msg_caducidad = '';
            }else{
              $this->msg_caducidad = $ar_res[1];
            }
            $rpta = "OK";
          }else{

            return $ar_res[1];
          }
        }


	}


	if ($sNewClave != "") { //-- cambiar clave
	    //if ($sClave == $sNewClave) return "NRPD";
	    if ($sUsuario == $sNewClave) return "Usuario y clave deben ser diferentes";
	    /*$ssql = "update empleados set empleado_clave_acceso ='" . $sNewClave . "',";
	    $ssql .="		empleado_clave_modificada=1 ";
	    $ssql .=" where empleado_codigo = " . $this->empleado_codigo;*/
        $params = array($sUsuario,$sNewClave);
        $ssql= "exec spRRHH_actualizar_clave ?,?";
   	    //echo $ssql;
        $rs2 = $cn->Execute($ssql, $params);
        //echo 'rs2[1]:'.$rs2[1] . ' rs2[0]:'.$rs2[0];
        if ($rs2->fields[0]*1==1){
			//ADICIONAR EMPLEADO EN CA_CAMBIO_CLAVE
      $params = array($sUsuario);
			$ssql = "delete from CA_CAMBIO_CLAVE where empleado_dni = ?";
			$_rs = $cn->Execute($ssql, $params);
            $params = array($sUsuario,$sNewClave);
			$ssql = "insert into CA_CAMBIO_CLAVE (empleado_dni, empleado_nueva_clave) values (?,?)";
			$__rs = $cn->Execute($ssql, $params);
	        $rpta="CMB";
	        return $rpta;
        }
        else{
            return $rs2->fields[1];
        }
	}else{
	    if ($rs->fields[1] == 0){
	        return "MPD";
	    }
	}
	return $rpta;
}


function Rep_Detalle_Total($filtro){
     $rpta="OK";
     $cn = $this->getMyConexionADO();
     $subfiltro="";
     if ($filtro!=0) $subfiltro = " where v.area_codigo = " . $filtro;
     $ssql = "Select v.Empleado_Codigo, v.Empleado_Carnet, v.empleado, v.Empleado_Dni, " .
		" convert(varchar(10),v.Empleado_Fecha_Ingreso,103) as Fecha_Ing, v.Area_Descripcion, " .
		" v.Modalidad_descripcion, v.Cargo_descripcion, v.Empleado_Email,  " .
		" v.Empleado_sexo, v.Empleado_Tlf, v.Empleado_Celular, v.Local_Descripcion, " .
		" v.Turno_Descripcion, v.Cod_Campana, " .
		" v.Exp_NombreCorto, v.Exp_Codigo,  " .
		" (SELECT Items_1.Item_Descripcion AS banco " .
		"  FROM Cuenta_Banco INNER JOIN  " .
		"       Items Items_1 ON Cuenta_Banco.Banco_Codigo = Items_1.Item_Codigo  " .
		"  WHERE (Cuenta_Banco.Empleado_Codigo = v.Empleado_Codigo) AND (Cuenta_Banco.Banco_Estado = 1)) as bco, " .
		" vc.ccto_codigo, vc.ccb_codigo, a.ccr_codigo, v.via, v.empleado_nombre_via, v.empleado_nro, " .    
		" v.fecha_nacimiento, v.distrito, jefe.area_descripcion as area_jefe, v.Horario_descripcion, v.tc_codigo_sap, ".
	    " vc.grupo_gestion_asignado,vc.grupo_gestion_nombre, ".
	    
		" DBO.UDF_ATRIBUTOS_ITEM_VALOR (v.Empleado_Codigo,37,1) as Cargo_Nomina ";	
		
     $ssql .= " from vDatos v (nolock) inner join areas a (nolock) on " .
			" v.area_codigo = a.area_codigo left outer join areas jefe (nolock) on " .
			" a.area_jefe = jefe.area_codigo ";
     $ssql .= "  left outer join v_campanas_gg_cliente vc on vc.Cod_Campana=v.Cod_Campana ";
     $ssql .= $subfiltro;
     $ssql .= " order by v.area_descripcion, v.empleado";
      $rs = $cn->Execute($ssql);  
 
   
     //exit;
                                    
     if( $rs->RecordCount() ==0){
          echo "No hay registros a listar";
          exit;
     }else{
          //-- hacer cabecera de tabla
          echo "<table class=sinborde border=0 width='200%' >\n";
          echo "<tr>\n";
          echo "     <td class=ColumnTD width=40px>Nro</td>\n";
          echo "     <td class=ColumnTD width=150px>&Aacute;rea</td>\n";
          echo "     <td class=ColumnTD width=60px>C&oacutedigo</td>\n";
          echo "     <td class=ColumnTD width=60px>Carnet</td>\n";
          echo "     <td class=ColumnTD>Empleado</td>\n";
          echo "     <td class=ColumnTD width=70px>DNI</td>\n";
          echo "     <td class=ColumnTD>Modalidad</td>\n";
          echo "     <td class=ColumnTD>Cargo</td>\n";
          echo "     <td class=ColumnTD>Fecha_Ingreso</td>\n";
          echo "     <td class=ColumnTD>Sexo</td>\n";
          echo "     <td class=ColumnTD>E-Mail</td>\n";
          echo "     <td class=ColumnTD>Tel&eacutefono</td>\n";
          echo "     <td class=ColumnTD>Celular</td>\n";
          echo "     <td class=ColumnTD>Local</td>\n";
          echo "     <td class=ColumnTD>Combinaci&oacuten Semanal</td>\n";
          echo "     <td class=ColumnTD>Horario Contractual</td>\n";
          echo "     <td class=ColumnTD>U.Servicio</td>\n";
          echo "     <td class=ColumnTD>Banco Pago</td>\n";
          echo "     <td class=ColumnTD>Cod. Centro Costo</td>\n";
          echo "     <td class=ColumnTD>Cod. Centro Beneficio</td>\n";
          echo "     <td class=ColumnTD>Centro Responsabilidad</td>\n";
          echo "     <td class=ColumnTD>Direcci&oacuten de Domicilio</td>\n";
          echo "     <td class=ColumnTD>Distrito</td>\n";
          echo "     <td class=ColumnTD>Fecha Nacimiento</td>\n";
		  echo "     <td class=ColumnTD>Gerencia / Direcci&oacuten</td>\n";
      echo "     <td class=ColumnTD>Grupo Gesti&oacute;n Asignado</td>\n";
      echo "     <td class=ColumnTD>Grupo Gesti&oacute;n Nombre</td>\n";
	  
      echo "     <td class=ColumnTD>Cargo N&oacute;mina</td>\n";	 
	   
          echo "</tr>\n";
          //-- listar detalles
          $i=0;
          while(!$rs->EOF){
               $i+=1;
               echo "<tr>\n";
               echo "     <td class=datatd align=right>" . $i . "</td>\n";
               echo "     <td class=datatd>" . utf8_encode($rs->fields[5]) . "</td>\n"; // area
               echo "     <td class=datatd align=right>" . $rs->fields[0] . "</td>\n";//codigo
               echo "     <td class=datatd align=center>" . $rs->fields[1] . "</td>\n"; //carnet
               echo "     <td class=datatd>" . utf8_encode($rs->fields[2]) . "</td>\n"; //empleado
               echo "     <td class=datatd align=center>" . $rs->fields[3] . "</td>\n"; //dni
               echo "     <td class=datatd>" . utf8_encode($rs->fields[6]) . "</td>\n"; //modalidad
               echo "     <td class=datatd>" . utf8_encode($rs->fields[7]) . "</td>\n"; //cargo
               echo "     <td class=datatd>" . $rs->fields[4] . "</td>\n";// fecha ingreso
               echo "     <td class=datatd align=center>" . $rs->fields[9] . "</td>\n";//sexo
               echo "     <td class=datatd align=center>" . $rs->fields[8] . "</td>\n";//E-Mail
               echo "     <td class=datatd align=left>" . $rs->fields[10] . "</td>\n";//telefono
               echo "     <td class=datatd align=left>" . $rs->fields[11] . "</td>\n";//celular
               echo "     <td class=datatd align=left>" . utf8_encode($rs->fields[12]) . "</td>\n";//local
               echo "     <td class=datatd align=left>" . $rs->fields[28] . "</td>\n";//turno $rs[12]-->semanal $rs[28]
               echo "     <td class=datatd align=left>" . $rs->fields[27] . "</td>\n";//horario contractual
               echo "     <td class=datatd align=left>" . utf8_encode($rs->fields[14]) . " - " . $rs->fields[15] . " [" . $rs->fields[16] . "]</td>\n"; //u. servicio
               echo "     <td class=datatd align=left>" . utf8_encode($rs->fields[17]) . "</td>\n";//banco
               echo "     <td class=datatd align=left>" . $rs->fields[18] . "</td>\n";//ceco
               echo "     <td class=datatd align=left>" . $rs->fields[19] . "</td>\n";//cebe
               echo "     <td class=datatd align=left>" . $rs->fields[20] . "</td>\n";//ccr
               echo "     <td class=datatd align=left>" . utf8_encode($rs->fields[21]) . ' ' . $rs->fields[22]. ' ' . $rs->fields[23] . "</td>\n";//direccion de domicilio
               echo "     <td class=datatd align=left>" . utf8_encode($rs->fields[25]) . "</td>\n";//distrito
               echo "     <td class=datatd align=left>" . $rs->fields[24] . "</td>\n";//fecha nacimiento
               echo "     <td class=datatd align=left>" . utf8_encode($rs->fields[26]) . "</td>\n";//gerencia/direccion
               echo "     <td class=datatd align=left>" . utf8_encode($rs->fields[29]) . "</td>\n";//GrupoGestionAsignado
               echo "     <td class=datatd align=left>" . utf8_encode($rs->fields[30]) . "</td>\n";//GrupoGestionNombre
               
               echo "     <td class=datatd align=left>" . utf8_encode($rs->fields[31]) . "</td>\n";//CargoNomina     
                         
               echo "</tr>\n";
               $rs->MoveNext();
          }
          //--cerrar tabla
          echo "</table>";
     }
     return $rpta;

}

function Rep_Cargos_x_area($filtro){
     $rpta="OK";
     $cn = $this->getMyConexionADO();

     $subfiltro="";
     if ($filtro!=0) $subfiltro = " where area_codigo = " . $filtro;

     $ssql = "SELECT Area_Codigo, Area_Descripcion, Cargo_descripcion, COUNT(Cargo_descripcion) AS Total ";
     $ssql .= " FROM vDatos ";
     $ssql .= $subfiltro;
     $ssql .= " GROUP BY Area_Codigo, Area_Descripcion, Cargo_descripcion ";
     $ssql .= " order by area_descripcion, Cargo_Descripcion";

     $rs = $cn->Execute($ssql);
     if( $rs->RecordCount() ==0){
          echo "No hay registros a listar";
          exit;
     }else{
          //-- hacer cabecera de tabla
          echo "<table class=table border=0 width='80%' align=center >\n";
          echo "<tr>\n";
          echo "     <td class=ColumnTD width=40px>Nro</td>\n";
          echo "     <td class=ColumnTD>Area</td>\n";
          echo "     <td class=ColumnTD>Cargo</td>\n";
          echo "     <td class=ColumnTD width=70px>Total</td>\n";
          echo "</tr>\n";
          //-- listar detalles
          $i=0;
          $codigo="";
          while(!$rs->EOF){
               $cadena="";
               if ($codigo!= $rs->fields[0]){
                    $codigo = $rs->fields[0];
                    $i+=1;
                    //pregunta por total del area
                    $params = array($rs->fields[0]);
                    $ssql="SELECT Area_Codigo, COUNT(Area_Descripcion) AS total FROM vDatos ";
                    $ssql .= " where Area_Codigo = ?";
                    $ssql .= " GROUP BY Area_Codigo ";
                    $rst = $cn->Execute($ssql, $params);
                    $total= $rst->fields[1];
                    $cadena= "<tr>\n";
                    $cadena .= "     <td class=datatd align=right><b>" . $i . "</b></td>\n";
                    $cadena .= "     <td class=datatd align=left colspan=2 ><b>" . $rs->fields[1] . "</b></td>\n";
                    $cadena .= "     <td class=datatd align=right><b>" . $total . "</b></td>\n";
                    $cadena .= "</tr>\n";
                    $cadena .= "<tr>\n";
                    $cadena .= "     <td class=datatd align=right></td>\n";
                    $cadena .= "     <td class=datatd align=left></td>\n";
                    $cadena .= "     <td class=datatd align=left>" . $rs->fields[2] . "</td>\n";
                    $cadena .= "     <td class=datatd align=right>" . $rs->fields[3] . "</td>\n";
                    $cadena .= "</tr>\n";
                    echo $cadena;
               }else{
                    echo "<tr>\n";
                    echo "     <td class=datatd align=right></td>\n";
                    echo "     <td class=datatd align=left></td>\n";
                    echo "     <td class=datatd align=left>" . $rs->fields[2] . "</td>\n";
                    echo "     <td class=datatd align=right>" . $rs->fields[3] . "</td>\n";
                    echo "</tr>\n";
               }
               $rs->MoveNext();
          }
          //--cerrar tabla
          echo "</table>";
     }
     return $rpta;

}

function Rep_Modalidad_x_area($filtro){
     $rpta="OK";
     $cn = $this->getMyConexionADO();
     $subfiltro="";
     if ($filtro!=0) $subfiltro = " where area_codigo = " . $filtro;

     $ssql = "SELECT Area_Codigo, Area_Descripcion, Modalidad_descripcion, COUNT(Modalidad_descripcion) AS Total ";
     $ssql .= " FROM vDatos ";
     $ssql .= $subfiltro;
     $ssql .= " GROUP BY Area_Codigo, Area_Descripcion, Modalidad_descripcion ";
     $ssql .= " order by area_descripcion, Modalidad_Descripcion";

     $rs = $cn->Execute($ssql);
     if( $rs->RecordCount()==0){
          echo "No hay registros a listar";
          exit;
     }else{
          //-- hacer cabecera de tabla
          echo "<table class=table border=0 width='80%' align=center >\n";
          echo "<tr>\n";
          echo "     <td class=ColumnTD width=40px>Nro</td>\n";
          echo "     <td class=ColumnTD>Area</td>\n";
          echo "     <td class=ColumnTD>Modalidad</td>\n";
          echo "     <td class=ColumnTD width=70px>Total</td>\n";
          echo "</tr>\n";
          //-- listar detalles
          $i=0;
          $codigo="";
          while(!$rs->EOF){
               $cadena="";
               if ($codigo!= $rs->fields[0]){
                    $codigo = $rs->fields[0];
                    $i+=1;
                    //pregunta por total del area
                    $params = array($rs->fields[0]);
                    $ssql="SELECT Area_Codigo, COUNT(Area_Descripcion) AS total FROM vDatos ";
                    $ssql .= " where Area_Codigo = ?";
                    $ssql .= " GROUP BY Area_Codigo ";
                    $rst = $cn->Execute($ssql, $params);
                    $total= $rst->fields[1];
                    $cadena= "<tr>\n";
                    $cadena .= "     <td class=datatd align=right><b>" . $i . "</b></td>\n";
                    $cadena .= "     <td class=datatd align=left colspan=2 ><b>" . $rs->fields[1] . "</b></td>\n";
                    $cadena .= "     <td class=datatd align=right><b>" . $total . "</b></td>\n";
                    $cadena .= "</tr>\n";
                    $cadena .= "<tr>\n";
                    $cadena .= "     <td class=datatd align=right></td>\n";
                    $cadena .= "     <td class=datatd align=left></td>\n";
                    $cadena .= "     <td class=datatd align=left>" . $rs->fields[2] . "</td>\n";
                    $cadena .= "     <td class=datatd align=right>" . $rs->fields[3] . "</td>\n";
                    $cadena .= "</tr>\n";

                    echo $cadena;

               }else{
                    echo "<tr>\n";
                    echo "     <td class=datatd align=right></td>\n";
                    echo "     <td class=datatd align=left></td>\n";
                    echo "     <td class=datatd align=left>" . $rs->fields[2] . "</td>\n";
                    echo "     <td class=datatd align=right>" . $rs->fields[3] . "</td>\n";
                    echo "</tr>\n";
               }
               $rs->MoveNext();
          }
          echo "</table>";
     }
     return $rpta;

}

function Actualizar_Campo($Codigo_Tabla, $Codigo_Campo, $Valor_Campo, $Codigo_Usuario){
	$rpta='OK'; $ssql='';
	$cn = $this->getMyConexionADO();
    //$cn->debug = true;
    
	if ($Codigo_Tabla == "" ) return "No se encontró Codigo de Tabla";
    if ($Codigo_Campo == "" ) return "No se encontró Codigo de Campo";
    if ($Codigo_Usuario == "" ) return "No se encontró Codigo de Usuario";
    if (trim($Valor_Campo) == '' ) $Valor_Campo="";
	$sSql_aux = '';
  switch($Codigo_Campo){
    case '153':
        $sSql_aux = " and item_codigo= " . $Codigo_Campo;
        break;
    case '154':
        $sSql_aux = " and item_codigo= " . $Codigo_Campo;
        break;
    case '155'://Bonificacion_Compensatoria
        $sSql_aux = " and item_codigo= " . $Codigo_Campo;
        break;
    case '156': //Bonificacion_Capacitacion
        $sSql_aux = " and item_codigo= " . $Codigo_Campo;
        break;
    case '157'://Bonificacion_Supervision
        $sSql_aux = " and item_codigo= " . $Codigo_Campo;
        break;
    case '158'://Bonificacion_Idioma
        $sSql_aux = " and item_codigo= " . $Codigo_Campo;
        break;
    default:
        $sSql_aux = '';
        break;
  }

	if ($Valor_Campo == "" ){
        $ssql = "select tabla_codigo, Atributo_codigo, Item_Codigo, isnull(atributo_valor,'') as atributo_valor, Empleado_Codigo ";
        $ssql .= " From vw_atributos_items_emp ";
        $ssql .= " where tabla_codigo= " . $Codigo_Tabla . " and Estado_Codigo = 1 and ";
        $ssql .= " empleado_codigo = " . $this->empleado_codigo;
        $ssql .= $sSql_aux;
    }else{
        $ssql = "select tabla_codigo, Atributo_codigo, Item_Codigo, isnull(atributo_valor,'') as atributo_valor, Empleado_Codigo ";
        $ssql .= " From vw_atributos_items_emp ";
        $ssql .= " where tabla_codigo= " . $Codigo_Tabla . " and Estado_Codigo = 1 and ";
        $ssql .= " empleado_codigo = " . $this->empleado_codigo; // . " and Item_Codigo =" . $Codigo_Campo;

        $ssql .= $sSql_aux;

    }
	//if ($Codigo_Campo.''=='153') echo $ssql;
	//echo $ssql;
	$rs = $cn->Execute($ssql);
    if( $rs->RecordCount() >0){
		//echo "$rs[2] <-- $Codigo_Campo -.- $rs[3] <-- $Valor_Campo ";
		if (($rs->fields[2].'' == $Codigo_Campo.'') && (trim($rs->fields[3].'') == trim($Valor_Campo.'')) ){
			 return $rpta;
		}
        echo "empleado_codigo:".$this->empleado_codigo."|field1:".$rs->fields[1]."|Codigo_Campo:".$Codigo_Campo."|Valor_Campo:".$Valor_Campo."|Codigo_Usuario:".$Codigo_Usuario;
			$ssql="exec spAgregar_Atributo_Empleado 
                                        $this->empleado_codigo,
                                        ".$rs->fields[1].",
                                        $Codigo_Campo,
                                        '$Valor_Campo',
                                        $Codigo_Usuario";
			$rs_store = $cn->Execute($ssql);
			if ($rs_store->fields[0]>0){
				$this->myAtributo_Codigo = $rs_store->fields[0];
			}
			$rpta = $this->myAtributo_Codigo;
			switch ($rpta){
				case '0': echo "Error al Insertar nuevo item " . $Codigo_Campo;
					break;
				case '-1': echo "Error al Modificar item " . $Codigo_Campo;
					break;
				case '-2': echo "Error al Agregar item " . $Codigo_Campo;
					break;
			}
			//echo " ->" . $rpta . "<-";
        //}
	}else{
        if ($Codigo_Campo *1 == 0 ) return $rpta;
        $this->myAtributo_Codigo = "";
        $this->Insertar_Atributo($Codigo_Campo, $Valor_Campo, $Codigo_Usuario, $this->empleado_codigo);
        $rpta = $this->myAtributo_Codigo;
	}

	 return $rpta;
}

function registrar_tipo_comision_afp(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
    $params = array(
                $this->tipocomisionafp,
                $this->empleado_codigo,
                $this->tipocomisionafp
            );
	$ssql = " UPDATE empleado_indicador SET tipo_comision_afp = ?, tipo_comision_fecha_reg = getdate()";
    $ssql .= "  WHERE Empleado_Codigo = ? and tipo_comision_afp <> ?";
	$rs= $cn->Execute($ssql, $params);
 	return $rpta;
}

function Desactivar_Atributo($Atributo_Codigo, $Item_Codigo, $Usuario, $Empleado_Codigo){
	$rpta='';
	$cn = $this->getMyConexionADO();
	$Estado=2;
    $params = array(
                    $Usuario,
                    $Atributo_Codigo,
                    $Item_Codigo,
                    $Empleado_Codigo
                );
	$ssql= "update Atributos ";
	$ssql .= " set 	Estado_Codigo = 2";
	$ssql .= " 		, Atributo_fecha_Modifica=getdate()";
	$ssql .= " 		, usuario_responsable_Modifica = ?";
	$ssql .= " where Atributo_Codigo = ? and ";
	$ssql .= " 		 item_codigo = ? and Empleado_Codigo = ?";
	$rs = $cn->Execute($ssql, $params);
	if($rs==false) $rpta= "Error al desactivar atributo: " . $Atributo_Codigo . " item: " . $Item_Codigo;
	return $rpta;

}

function Insertar_Atributo ($Item_Codigo, $valor, $Usuario, $Empleado,$tipocomisionafp){
	$rpta='OK';
    $cn = $this->getMyConexionADO();
    //$cn->debug = true;
	$ssql = "select isnull(max(atributo_codigo), 0)+1 from atributos(nolock)";
	$rs = $cn->Execute($ssql);
	if( $rs->RecordCount()>0){
		$this->myAtributo_Codigo = $rs->fields[0];
	}
    $valor = $valor == ""?null:$valor;
    $params = array(
                    $this->myAtributo_Codigo,
                    $Item_Codigo ,
                    $Empleado,
                    $valor ,
                    $Usuario
                );
	$ssql = "INSERT INTO Atributos (
                    Atributo_codigo,
                    Item_Codigo,
                    Empleado_Codigo,
                    Emp_Mov_codigo,
	                Atributo_Valor,
                    Atributo_Fecha,
                    Estado_codigo,
                    Usuario_Responsable,
	                Atributo_Fecha_Modifica,
                    Usuario_Responsable_Modifica
                    )
	          VALUES(?,?, ?, null,?, getdate(), 1, ?, null,null) ";
	//echo $ssql;
	$rs = $cn->Execute($ssql, $params);
	if($rs==false) $rpta= "Error al desactivar atributo: " . $this->myAtributo_Codigo . " item: " . $Item_Codigo;
	return $rpta;
}
/*

function Insertar_Atributo ($Item_Codigo, $valor, $Usuario, $Empleado,$tipocomisionafp){
	$rpta='OK';
    $cn = $this->getMyConexionADO();
    //$cn->debug = true;
	$ssql = "select isnull(max(atributo_codigo), 0)+1 from atributos(nolock)";
	$rs = $cn->Execute($ssql);
	if( $rs->RecordCount()>0){
		$this->myAtributo_Codigo = $rs->fields[0];
	}
    $valor = $valor == ""?null:$valor;
    $params = array(
                    $this->myAtributo_Codigo,
                    $Item_Codigo ,
                    $Empleado,
                    $valor ,
                    $Usuario
                );
	$ssql = "INSERT INTO Atributos (
                    Atributo_codigo,
                    Item_Codigo,
                    Empleado_Codigo,
                    Emp_Mov_codigo,
	                Atributo_Valor,
                    Atributo_Fecha,
                    Estado_codigo,
                    Usuario_Responsable,
	                Atributo_Fecha_Modifica,
                    Usuario_Responsable_Modifica                    
                    )
	          VALUES(?,?, ?, null,?, getdate(), 1, ?, null,null) ";
	//echo $ssql;
	$rs = $cn->Execute($ssql, $params);
	if($rs==false) $rpta= "Error al desactivar atributo: " . $this->myAtributo_Codigo . " item: " . $Item_Codigo;
	return $rpta;
}
*/

function Actualizar_Areas($area, $usuario){
	$rpta='OK';
    $cn = $this->getMyConexionADO();
    
    $params = array($this->empleado_codigo);
	$ssql = "select Empleado_Area_Codigo, area_Codigo from Empleado_area ";
    $ssql .=" where empleado_codigo = ?";
    $ssql .=" and  Empleado_Area_Activo = 1 ";
    $rs = $cn->Execute($ssql, $params);
    if( $rs->RecordCount() >0){
        if ($area == "0" ) return $rpta;
        if ($rs->fields[1]*1 == $area*1) return $rpta;
         //--Desactivar actual
         $cn1 = $this->getMyConexionADO();
         
         
         $params = array($this->empleado_codigo , $usuario);
         $ssql = "exec sp_desactivar_empleado_area ?,? ";
         //$rDel= $cn->Execute($ssql, $params);
         $cn1->Execute($ssql, $params);
         $cn1->Close();
            
         $cn1 = $this->getMyConexionADO();
         
            
         $parms = array($this->empleado_codigo,$area,$usuario);
         $ssql = "exec sp_activar_empleado_area ?,?,? ";
         //$rIns= $cn->Execute($ssql, $params);
         $cn1->Execute($ssql, $parms);
         $cn1->Close();
    }
    return $rpta;
}

function Actualizar_Empleado_Servicio($campana, $usuario){
	$rpta='OK';
	$cn = $this->getMyConexionADO();
    //$cn->debug = true;
    $params = array($this->empleado_codigo);
	$ssql = "select Empleado_Servicio_Codigo, Cod_Campana from Empleado_Servicio ";
    $ssql .=" where empleado_codigo = ?";
    $ssql .=" and  Empleado_Servicio_Activo = 1 ";
    $rs = $cn->Execute($ssql, $params);
    if( $rs->RecordCount() >0){
        if ($campana == "0" ) return $rpta;
        if ($rs->fields[1]*1 == $campana*1) return $rpta;
        $cn1 = $this->getMyConexionADO();
         //--Desactivar actual
         $params = array($this->empleado_codigo ,$usuario);
         $ssql = "exec sp_Desactivar_Empleado_Servicio ?,? ";
         $cn1->Execute($ssql, $params);
         $cn1->Close();
            
         $cn1 = $this->getMyConexionADO();
         $params = array($this->empleado_codigo,$campana,$usuario );
         $ssql = "exec sp_activar_empleado_servicio ?,?,?";
         $cn1->Execute($ssql, $params);
         $cn1->Close();
    }

	return $rpta;
}

function Actualizar_moneda($valor){
	$rpta='OK';
	$cn = $this->getMyConexionADO();
    $params = array(
                    $valor,
                    $this->empleado_codigo
                );
	$ssql = "UPDATE empleados set moneda_codigo = ?
	         WHERE empleado_codigo = ?";
    if($cn->Execute($ssql, $params)== false)
        $rpta = "fallo al actualizar";
	return $rpta;
}

function Actualizar_dependientes($dependientes, $hijos){
	$rpta='OK';
	$cn = $this->getMyConexionADO();
	if ($dependientes=='') $dependientes='null';
	if ($hijos=='') $hijos='null';
    $params = array(
                    $dependientes ,
                    $hijos,
                    $this->empleado_codigo
                    );
    $ssql = "UPDATE empleados SET
                    empleado_dependientes = ?;
                    empleado_hijos_mayores= ?;
             WHERE empleado_codigo = ?";
	if($cn->Execute($ssql, $params)== false)
        $rpta = "Fallo al Atualizar";
	return $rpta;
}

function Actualizar_retenciones($retencion, $cantidad){
	$rpta='OK';
	$cn = $this->getMyConexionADO();
	if ($cantidad=='') $cantidad='0';
    $params = array(
                    $retencion,
                    $cantidad,
                    $this->empleado_codigo
                    );
	$ssql = "UPDATE empleados set
                    Retencion_judicial =?,
                    Retencion_judicial_cantidad= ?
	         WHERE empleado_codigo = ?";
	if($cn->Execute($ssql, $params)== false)
        $rpta = "Fallo al Atualizar";
	return $rpta;
}

function Actualizar_local($centro_contacto){
	$rpta='OK';
	$cn = $this->getMyConexionADO();
	if ($centro_contacto=='') $centro_contacto='null';
    $params = array(
                    $centro_contacto,
                    $this->empleado_codigo
                    );
	$ssql = "UPDATE empleados set local_codigo = ?
	         WHERE empleado_codigo = ?";
	if($cn->Execute($ssql, $params)== false)
        $rpta = "Fallo al Atualizar";
	return $rpta;
}

function Empleado_Update_Ficha(){
 //--- Funcion que modifica los datos principales de la tabla Empleados para
 //--- la ficha de datos de personal
	$rpta='OK';
	$cn = $this->getMyConexionADO();
    //$cn->debug = true;
	$msgResponsableArea='';
	$msgActualizacion='';
    
    $params = array(
        $this->empleado_codigo,
        $this->empleado_carnet,
        $this->empleado_apellido_paterno,
        $this->empleado_apellido_materno,
        $this->empleado_nombres,
        $this->Sin_Valor($this->Ccto_Codigo),
        $this->Sin_Valor($this->Ccr_Codigo),
        $this->Sin_Valor($this->Empleado_Trasvase),
        0,
        $this->empleado_estado_civil,
        $this->empleado_sexo,
        $this->empleado_email,
        $this->Sin_Valor(substr($this->distrito_residencia,0,2)) ,
        $this->Sin_Valor(substr($this->distrito_residencia,2,2)),
        $this->Sin_Valor(substr($this->distrito_residencia,4,2)),
        $this->Sin_Valor(substr($this->distrito_nacimiento,0,2)),
        $this->Sin_Valor(substr($this->distrito_nacimiento,2,2)),
        $this->Sin_Valor(substr($this->distrito_nacimiento,4,2)),
        $this->empleado_fecha_nacimiento,
        $this->empleado_tlf,
        $this->empleado_celular,
        $this->empleado_tlf_referencia,
        $this->empleado_preguntar_por,
        $this->empleado_dni,
        $this->empleado_ruc,
        $this->empleado_lib_militar,
        $this->empleado_num_seguro,
        $this->empleado_nombre_via,
        $this->empleado_nro,
        $this->empleado_fecha_ingreso,
        $this->moneda_codigo,
        $this->usuario_nick,
        $this->estado_codigo,
        $this->Sin_Valor($this->turno_codigo),
        $this->empleado_dependientes,
        $this->empleado_hijos_mayores,
        $this->local_codigo,
        $this->TDI_codigo,
        $this->codigo_nacionalidad ,
        $this->codigo_zona,
        $this->descripcion_zona,
        $this->trabajador_tipo,
        $this->horario_nocturno,
        $this->academico_codigo,
        $this->discapacidad ,
        $this->situacion_especial,
        $this->sindicalizado,
        $this->tipo_actividad,
        $this->regimen_alternativo,
        $this->jornada_maxima,
        $this->modalidad_formativa 
    );
    
    $ssql = "exec spRRHH_Empleado_U ?,?,?,?,?,?,?,?,?,?, ?,?,?,?,?,?,?,?,?,?, ?,?,?,?,?,?,?,?,?,?, ?,?,?,?,?,?,?,?,?,?, ?,?,?,?,?,?,?,?,?,?, ?";
    /*    
    $ssql="exec spRRHH_Empleado_U " . 	$this->empleado_codigo . ",'" . $this->empleado_carnet . "','" . $this->empleado_apellido_paterno . "','" . $this->empleado_apellido_materno . "','" . $this->empleado_nombres . "', ";
	$ssql.= "'" . $this->Sin_Valor($this->Ccto_Codigo) . "','" . $this->Sin_Valor($this->Ccr_Codigo) . "','" . $this->Sin_Valor($this->Empleado_Trasvase) . "',0, ";
	$ssql.= "" . $this->empleado_estado_civil . ",'" . $this->empleado_sexo . "','" . $this->empleado_email . "','" . $this->Sin_Valor(substr($this->distrito_residencia,0,2)) . "','" . $this->Sin_Valor(substr($this->distrito_residencia,2,2)) . "', ";
	$ssql.= "'" . $this->Sin_Valor(substr($this->distrito_residencia,4,2)) . "','" . $this->Sin_Valor(substr($this->distrito_nacimiento,0,2)) . "','" . $this->Sin_Valor(substr($this->distrito_nacimiento,2,2)) . "','" . $this->Sin_Valor(substr($this->distrito_nacimiento,4,2)) . "','" . $this->empleado_fecha_nacimiento . "', ";
	$ssql.= "'" . $this->empleado_tlf . "','" . $this->empleado_celular . "','" . $this->empleado_tlf_referencia . "','" . $this->empleado_preguntar_por . "','" . $this->empleado_dni . "', ";
	$ssql.= "'" . $this->empleado_ruc . "','" . $this->empleado_lib_militar . "','" . $this->empleado_num_seguro . "','" . $this->empleado_nombre_via . "','" . $this->empleado_nro . "', ";
	$ssql.= "'" . $this->empleado_fecha_ingreso . "','" . $this->moneda_codigo . "','" . $this->usuario_nick . "'," . $this->estado_codigo .",'" . $this->Sin_Valor($this->turno_codigo) . "', ";
	$ssql.= "'" . $this->empleado_dependientes . "','" . $this->empleado_hijos_mayores . "','" . $this->local_codigo . "', ";
	$ssql.= "'" . $this->TDI_codigo  . "','" . $this->codigo_nacionalidad . "','" . $this->codigo_zona . "','" . $this->descripcion_zona . "', ";
  	$ssql.= "'" . $this->trabajador_tipo . "','" . $this->horario_nocturno . "','" . $this->academico_codigo . "','" . $this->discapacidad . "', ";
  	$ssql.= "'" . $this->situacion_especial . "','" . $this->sindicalizado . "','" . $this->tipo_actividad . "','" . $this->regimen_alternativo . "','" . $this->jornada_maxima . "', '" . $this->modalidad_formativa . "'";
    */
	$rs_store = $cn->Execute($ssql, $params);
	$rpta='0';
	if ($rs_store->fields[0]>0){
		$rpta = $rs_store->fields[0];
	}
	switch ($rpta){
		case '1': $msgActualizacion="<br>Error al Modificar Registro de Empleado ";
			break;
	}

  	if ($rpta*1==0){ //--obtener responsable de area
        $params  = array($this->empleado_codigo);
  		$ssql= "SELECT Empleados.Empleado_Codigo, Areas.empleado_responsable " .
			" FROM Empleados INNER JOIN " .
			"      Empleado_Area ON Empleados.Empleado_Codigo = Empleado_Area.Empleado_Codigo INNER JOIN " .
			"      Areas ON Empleado_Area.Area_Codigo = Areas.Area_Codigo " .
			" WHERE (Empleados.Empleado_Codigo = ?) " .
			"		AND (Empleado_Area.Empleado_Area_Activo = 1) " .
			"		AND (Areas.Area_Activo = 1) ";

	    $rsJefe= $cn->Execute($ssql, $params);
	    if( $rsJefe->RecordCount() >0){
	        $boss='';
			if ($rsJefe->fields[0]!=$rsJefe->fields[1]){
				//-- si el responsable del area no es el mismo se actualiza jefe.
		      	$boss=$rsJefe->fields[1];
			}else{
				//-- si el responsable es el mismo, se busca al responsable del area padre, no aplica para el gerente general.
                $params = array($this->empleado_codigo);
				$ssql="SELECT  Areas_jefe.empleado_responsable
    				   FROM Empleados INNER JOIN
    				     Empleado_Area ON Empleados.Empleado_Codigo = Empleado_Area.Empleado_Codigo INNER JOIN
    				     Areas ON Empleado_Area.Area_Codigo = Areas.Area_Codigo INNER JOIN
    				     Areas Areas_jefe ON Areas.Area_Jefe = Areas_jefe.Area_Codigo
    				   WHERE (Empleados.Empleado_Codigo = ?)
    						AND (Empleado_Area.Empleado_Area_Activo = 1)
    						AND (Areas.Area_Activo = 1) AND (Areas_jefe.Area_Activo = 1) ";
				$rsBoss= $cn->Execute($ssql, $params);
			    if( $rsBoss->RecordCount()>0){
			      $boss=$rsBoss->fields[0];
			    }
			}
			$ssql = "update empleados set ";
			if ($boss==''){
				$ssql.= " empleado_jefe=null ";
			}else{
				$ssql.= " empleado_jefe=" . $boss;
			}
	        $ssql.= " where empleado_codigo = " . $this->empleado_codigo;
	        $rs= $cn->Execute($ssql);
	    }else{
	    	//-- sin mando definido
            $params = array($this->empleado_codigo);
	    	$ssql = "update empleados set empleado_jefe = null";
		    $ssql.= " where empleado_codigo = ?";
		    $rs= $cn->Execute($ssql, $params);
	    }
  	}
	return $msgResponsableArea . $msgActualizacion;
}

function Empleado_modificar_basicos(){
	$rpta='OK';
	$cn = $this->getMyConexionADO();
    $params = array(
                        $this->empleado_apellido_paterno,
                        $this->empleado_apellido_materno,
                        $this->empleado_nombres,
                        $this->empleado_dni ,
                        $this->local_codigo,
                        $this->empleado_estado_civil,
                        $this->empleado_sexo ,
                        $this->empleado_ruc,
                        $this->empleado_lib_militar
                    );
	$ssql = "UPDATE empleados set ";
	$ssql .= " 	empleado_apellido_paterno = ?";
	$ssql .= " 	,empleado_apellido_materno = ?";
	$ssql .= " 	,empleado_nombres = ?";
	$ssql .= " 	,empleado_dni = ?";
	$ssql .= " 	,local_codigo = ?";
	$ssql .= " 	,empleado_estado_civil = ?";
	$ssql .= " 	,empleado_sexo = ?";
	$ssql .= " 	,empleado_ruc = ?";
	$ssql .= " 	,empleado_lib_militar =?";

	if ($this->empleado_dependientes!=''){
        $params[] = $this->empleado_dependientes;
        $ssql .= " 	,empleado_dependientes= ?";
    }
	if ($this->empleado_hijos_mayores!=''){
	    $params[] = $this->empleado_hijos_mayores;
        $ssql .= " 	,empleado_hijos_mayores = ?";
    }

    $params[] = $this->usuario_nick ;
    $params[] = $this->empleado_nombre_via;
    $params[] = $this->empleado_nro;

	$ssql .= " 	,usuario_nick = ?";
	$ssql .= " 	,empleado_nombre_via = ?";
	$ssql .= " 	,empleado_nro = ?";

	if ($this->distrito_residencia!=''){ //-- ubigeo residencia
        $params[] = substr($this->distrito_residencia,0,2);
        $params[] = substr($this->distrito_residencia,2,2);
        $params[] = substr($this->distrito_residencia,4,2);
		$ssql .= " 	, empleado_dpto_residencia = ?";
		$ssql .= " 	, empleado_prov_residencia = ?";
		$ssql .= " 	, empleado_dist_residencia = ?";
	}

	if ($this->empleado_fecha_nacimiento!=''){
	    $params[] = $this->empleado_fecha_nacimiento;
        $ssql .= " 	,empleado_fecha_nacimiento = convert(datetime,?, 103) ";
    }
	if ($this->distrito_nacimiento!=''){ //-- ubigeo nacimiento
        $params[] = substr($this->distrito_nacimiento,0,2);
        $params[] = substr($this->distrito_nacimiento,2,2);
        $params[] = substr($this->distrito_nacimiento,4,2);
		$ssql .= " 	, empleado_dpto_nacimiento = ?";
		$ssql .= " 	, empleado_prov_nacimiento = ?";
		$ssql .= " 	, empleado_dist_nacimiento = ?";
	}
    $params[] = $this->empleado_tlf ;
    $params[] = $this->empleado_celular;
    $params[] = $this->empleado_tlf_referencia;
    $params[] = $this->empleado_preguntar_por;
    $params[] = $this->empleado_codigo;
	$ssql .= " 	,empleado_tlf = ?";
	$ssql .= " 	,empleado_celular = ?";
	$ssql .= " 	,empleado_tlf_referencia = ?";
	$ssql .= " 	,empleado_preguntar_por = ?";
	$ssql .= " WHERE empleado_codigo = ?";

	$rs= $cn->Execute($ssql, $params);
	if($rs==false) $rpta= "Error al modificar datos de empleado ";
	return $rpta;
}

function Empleado_modificar_basicos_man(){
	$rpta='OK';
	$cn = $this->getMyConexionADO();
	$ssql = "update empleados set ";
	$ssql .= " 	empleado_apellido_paterno = '" . $this->empleado_apellido_paterno . "'";
	$ssql .= " 	,empleado_apellido_materno = '" . $this->empleado_apellido_materno . "'";
	$ssql .= " 	,empleado_nombres = '" . $this->empleado_nombres . "'";
	$ssql .= " 	,empleado_dni = '" . $this->empleado_dni . "'";
	//$ssql .= " 	,turno_codigo = '" . $this->turno_codigo . "'";
	$ssql .= " 	,local_codigo = '" . $this->local_codigo . "'";
	$ssql .= " 	,empleado_estado_civil = '" . $this->empleado_estado_civil . "'";
	$ssql .= " 	,empleado_sexo ='" . $this->empleado_sexo . "'";
	$ssql .= " 	,empleado_ruc = '" . $this->empleado_ruc . "'";
	$ssql .= " 	,empleado_lib_militar ='" . $this->empleado_lib_militar . "'";
	if ($this->empleado_dependientes!='') $ssql .= " 	,empleado_dependientes='" . $this->empleado_dependientes . "'";
	if ($this->empleado_hijos_mayores!='') $ssql .= " 	,empleado_hijos_mayores = '" . $this->empleado_hijos_mayores . "'";
	$ssql .= " 	,usuario_nick = '" . $this->usuario_nick . "'";
	$ssql .= " 	,empleado_nombre_via = '" . $this->empleado_nombre_via . "'";
	$ssql .= " 	,empleado_nro = '" . $this->empleado_nro . "'";
	if ($this->distrito_residencia!=''){ //-- ubigeo residencia
		$ssql .= " 	, empleado_dpto_residencia = '" . substr($this->distrito_residencia,0,2) . "'";
		$ssql .= " 	, empleado_prov_residencia = '" . substr($this->distrito_residencia,2,2) . "'";
		$ssql .= " 	, empleado_dist_residencia = '" . substr($this->distrito_residencia,4,2) . "'";
	}
	if ($this->empleado_fecha_nacimiento!='') $ssql .= " 	,empleado_fecha_nacimiento = convert(datetime,'" . $this->empleado_fecha_nacimiento . "', 103) ";
	if ($this->distrito_nacimiento!=''){ //-- ubigeo nacimiento
		$ssql .= " 	, empleado_dpto_nacimiento = '" . substr($this->distrito_nacimiento,0,2) . "'";
		$ssql .= " 	, empleado_prov_nacimiento = '" . substr($this->distrito_nacimiento,2,2) . "'";
		$ssql .= " 	, empleado_dist_nacimiento = '" . substr($this->distrito_nacimiento,4,2) . "'";
	}
	$ssql .= " 	,empleado_tlf = '" . $this->empleado_tlf . "'";
	$ssql .= " 	,empleado_celular = '" . $this->empleado_celular . "'";
	$ssql .= " 	,empleado_tlf_referencia = '" . $this->empleado_tlf_referencia . "'";
	$ssql .= " 	,empleado_preguntar_por = '" . $this->empleado_preguntar_por . "'";
	$ssql .= " 	,urba_nombre = \"" . $this->urba_nombre . "\"";
	$ssql .= " 	,empleado_interior = \"" . $this->empleado_interior . "\"";

	$ssql .= " Where empleado_codigo = " . 	$this->empleado_codigo;

	$res= mssql_query($ssql);
	if ($res==false) $rpta= "Error al modificar datos de empleado x";
	mssql_close($linkEMBA);
	return $rpta;
}

function Empleado_modificar_direccion(){
    $rpta='OK';
	$cn = $this->getMyConexionADO();
    //$cn->debug = true;
    $params = array(
                    $this->empleado_nombre_via,
                    $this->empleado_nro
                );
	$ssql = "UPDATE empleados set ";
	$ssql .= " 	empleado_nombre_via = ?";
	$ssql .= " 	,empleado_nro = ?";
	if ($this->distrito_residencia!=''){ //-- ubigeo residencia
        $params[] = substr($this->distrito_residencia,0,2);
        $params[] = substr($this->distrito_residencia,2,2);
        $params[] = substr($this->distrito_residencia,4,2);
		$ssql .= " 	, empleado_dpto_residencia = ?";
		$ssql .= " 	, empleado_prov_residencia = ?";
		$ssql .= " 	, empleado_dist_residencia = ?";
	}
	
    $params[] = $this->empleado_tlf ==""?null:$this->empleado_tlf;
    $params[] = $this->empleado_celular =="" ?null:$this->empleado_celular;
    $params[] = $this->empleado_tlf_referencia == ""?null:$this->empleado_tlf_referencia;
    $params[] = $this->empleado_preguntar_por == ""?null:$this->empleado_preguntar_por;
    $params[] = $this->referencia_direccion;
    $params[] = $this->nombre_zona == ""?null:$this->nombre_zona ;
    $params[] = $this->cod_zona;
    $params[] = $this->empleado_codigo;
    
	$ssql .= " 	,empleado_tlf = ?";
	$ssql .= " 	,empleado_celular = ?";
	$ssql .= " 	,empleado_tlf_referencia = ?";
	$ssql .= " 	,empleado_preguntar_por = ?";
    $ssql .= " 	,referencia_direccion = ?";
    $ssql .= " 	,nombre_zona = ?";
    $ssql .= " 	,codigo_zona = ?";
	$ssql .= " WHERE empleado_codigo = ?";

	$rs= $cn->Execute($ssql, $params);
	if($rs==false) $rpta= "Error al modificar datos de empleado ";
	return $rpta;

}

function modificar_regimen_fecha_inscripcion(){
	$rpta='OK';
	$cn = $this->getMyConexionADO();
    $params = array();
	$ssql = "update empleados set ";
	if ($this->regimen_fecha_inscripcion==''){
	   $ssql .= " 	regimen_fecha_inscripcion = null ";
    }else{
       $params[] = $this->regimen_fecha_inscripcion; 
	   $ssql .= " 	regimen_fecha_inscripcion = convert(datetime, ?,103) ";
	}
    $params[] = $this->empleado_codigo;
	$ssql .= " Where empleado_codigo = ?";
	$rs= $cn->Execute($ssql, $params);
	if($rs==false) $rpta= "Error al modificar fecha de inscripcion de Regimen de pension ";
	return $rpta;
}

function Empleado_modificar_basicos_alta(){
	$rpta='OK';
	$linkEMBA = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());

	$ssql = "update empleados set ";
	$ssql .= " 	empleado_apellido_paterno = '" . $this->empleado_apellido_paterno . "'";
	$ssql .= " 	,empleado_apellido_materno = '" . $this->empleado_apellido_materno . "'";
	$ssql .= " 	,empleado_nombres = '" . $this->empleado_nombres . "'";
	$ssql .= " 	,empleado_dni = '" . $this->empleado_dni . "'";
	$ssql .= " 	,turno_codigo = '" . $this->turno_codigo . "'";
	$ssql .= " 	,local_codigo = '" . $this->local_codigo . "'";
	$ssql .= " 	,empleado_estado_civil = '" . $this->empleado_estado_civil . "'";
	$ssql .= " Where empleado_codigo = " . 	$this->empleado_codigo;
	$res= mssql_query($ssql);
	if ($res==false) $rpta= "Error al modificar datos de empleado s ";
	mssql_close($linkEMBA);
	return $rpta;
}

function Empleado_Especial_Email($grupo_especial){
	$rpta='OK';
	$linkEEM = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());

	$ssql = "Select empleado_email from vw_Empleado_Especial ";
	$ssql .= " where empleado_email is not null and Especial_Tipo=" . $grupo_especial;
	$res= mssql_query($ssql);
	$cadena='';
	while ($rs=mssql_fetch_row($res)){
		if ($cadena==''){
	        $cadena .= $rs[0];
		}else{
			$cadena .= ';' . $rs[0];
		}
    }

	mssql_close($linkEEM);
	return $cadena;
}

function verificar_registro_postulante($codApto, $tipo_sujeto){
	$rpta='';
	$linkVREPO = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());

	$ssql = "select abs(isnull(flag_envio,0)) as flagEnvio from Rqto_Sujeto ";
	$ssql .= " where Rqto_Codigo= " . $this->rqto_codigo;
    $ssql .= "       and Rqto_Sujeto_Codigo = " . $this->postulante_codigo;
	//echo $ssql;
	$res= mssql_query($ssql);
	$rs = mssql_fetch_row($res);

	if ($rs[0]*1==1 && $codApto*1==1) return "<br>Registro ya fuï¿½ Enviado: " . $this->postulante_codigo;
	//echo "codapto: " . $codApto;
	if ($rs[0]*1==0 && $codApto*1==0) return "";

	$ssql = " UPDATE Rqto_Sujeto ";
    $ssql .= " SET Fecha_Ingreso = convert(datetime, '" . $this->empleado_fecha_ingreso . "', 103)";
    $ssql .= " 		, Sueldo = '" . $this->sueldo . "'";
    $ssql .= "  WHERE Rqto_Codigo = " . $this->rqto_codigo . " AND ";
	$ssql .= " 		  Tipo_Sujeto = " . $tipo_sujeto . " AND ";
    $ssql .= "        Rqto_Sujeto_Codigo = " . $this->postulante_codigo;
	//echo $ssql;
	$res= mssql_query($ssql);

	//-- obtenemos estado del empleado
	$ssql = "select empleado_codigo, estado_codigo from Empleados ";
	$ssql .= " where Postulante_Codigo=" . $this->postulante_codigo;
	//echo $ssql;
	$res= mssql_query($ssql);
	if (mssql_num_rows($res)>0){
		$rs = mssql_fetch_row($res);
		$this->empleado_codigo = $rs[0];
		$this->estado_codigo = $rs[1];
	}else{
		$this->empleado_codigo = "";
		$this->estado_codigo = "";
		if ($codApto*1==1) return "Registro de Empleado del Postulante NO ha sido creado";
	}

	return "OK";

}

function Envio_Alta_Personal($codApto, $tipo_sujeto, $Estado, $Observaciones, $Usuario){
	$rpta='OK';
	$linkALPER = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());

	$ssql="exec spRRHH_Enviar_Alta_Postulante ";
	$ssql .= "\"" . $this->rqto_codigo . "\"";
	$ssql .= ",\"" . $this->postulante_codigo . "\"";
	$ssql .= ",\"" . $this->empleado_codigo . "\"";
	$ssql .= ",\"" . $this->empleado_fecha_ingreso . "\"";
	$ssql .= ",\"" . $tipo_sujeto . "\"";
	$ssql .= ",\"" . $Observaciones . "\"";
	$ssql .= ",\"" . $Usuario . "\"";
	//echo $ssql;
	$result_store = mssql_query($ssql);
	$rs_store= mssql_fetch_row($result_store);
	if ($rs_store[0]>0){
		$rpta = $rs_store[0];
	}
	switch ($rpta){
		case '-1':  $rpta = "Error al crear atributos de empleado " . $this->postulante_codigo;
					echo $rpta;
			break;
		case '-2':  $rpta = "Error al crear atributos de empleado " . $this->postulante_codigo;
					echo $rpta;
			break;
		case '-3':  $rpta = "Error al crear atributo de tipo de remuneracion de empleado " . $this->postulante_codigo;
					echo $rpta;
			break;
		case '-4':  $rpta = "Error al crear atributo de remuneracion de empleado " . $this->postulante_codigo;
					echo $rpta;
			break;
		case '-5':  $rpta = "Error al actualizar registro de empleado " . $this->postulante_codigo;
					echo $rpta;
			break;

		default:
			$rpta='OK';
	}
	mssql_close($linkALPER);

	return $rpta;
}

function Ejecutar_Alta_Personal($reg_empleado, $reg_movimiento_codigo, $reg_contrato_codigo, $reg_usuario){
  $rpta='';
	$linkEALTAPER = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
  mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());

	if ($reg_empleado==''){
	   $rpta = 'Debe indicar codigo de Empleado.';
	   return $rpta;
  }

	if ($reg_movimiento_codigo<>'16' ){
	   $rpta = 'Movimiento de personal Equivocado, no se ejecutï¿½ proceso.';
	   return $rpta;
  }

	if ($reg_contrato_codigo==''){
	   $rpta = 'Debe indicar tipo de contrato.';
	   return $rpta;
  }

	$ssql="exec spRRHH_ALTA_PERSONAL ";
	$ssql .= "\"" . $reg_empleado . "\"";
	$ssql .= ",\"" . $reg_movimiento_codigo . "\"";
	$ssql .= ",\"" . $reg_contrato_codigo . "\"";
	$ssql .= ",\"" . $reg_usuario . "\"";
	//echo $ssql;
	$result_store = mssql_query($ssql);
	$rs_store= mssql_fetch_row($result_store);
	if ($rs_store[0]>0){
		$rpta = $rs_store[0];
	}
	switch ($rpta){
		case '1':  $rpta = "ERROR, Ya existe registro de Alta del Empleado: " . $reg_empleado;
					echo $rpta;
			break;
		case '2':  $rpta = "ERROR al crear registro de movimiento de Alta, Empleado: " . $reg_empleado;
					echo $rpta;
			break;
		case '3':  $rpta = "ERROR al crear registro de CONTRATO, Empleado: " . $reg_empleado;
					echo $rpta;
			break;
		case '4':  $rpta = "ERROR al ACTIVAR REGISTRO DE EMPLEADO: " . $reg_empleado;
					echo $rpta;
			break;

		default:
			$rpta='OK';
	}

	mssql_close($linkEALTAPER);

	return $rpta;
}

function Envio_Reingreso_Personal($codApto, $tipo_sujeto, $Estado, $Observaciones,$Usuario){
	$rpta='';
	$linkREIPER = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
  	mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());
	$Responsable=''; $Serv='';
	$AreaO='';	$AreaD='';

	$ssql = "select abs(isnull(flag_envio,0)) as flagEnvio from Rqto_Sujeto ";
	$ssql .= " where Rqto_Codigo=" . $this->rqto_codigo;
  	$ssql .= "       and Rqto_Sujeto_Codigo = " . $this->postulante_codigo;
	//echo $ssql;
	$resc=mssql_query($ssql);
	$rsc = mssql_fetch_row($resc);

	If ($this->estado_codigo *1==1 || $this->estado_codigo *1==2 ){ //-- Activo o Desactivo

		$result = mssql_query($ssql);
		$xEnvio = $codApto;
		$Movi = "";

		$ssql = "select Empleado_Codigo,Usuario_Id from Empleados where Usuario_Id= " . $Usuario;
		//echo $ssql;
		$resre= mssql_query($ssql);
		if (mssql_num_rows($resre)>0){
			$rsres=mssql_fetch_row($resre);
			$Responsable = $rsres[0];
		}

		//-- Obtener el estado y Area del Empleado
		$ssql="Select estado_codigo, area_codigo from vw_Empleado_Area where empleado_codigo = " . $this->empleado_codigo;
		//echo $ssql;
		$resu= mssql_query($ssql);
		if (mssql_num_rows($resu)>0){
			$rsu = mssql_fetch_row($resu);
			//--- Determinar el estado del Empleados.
	        if ($rsc[0]*1 ==1 && $codApto*1 == 0){
				//-- desactivar movimiento, cambiar estado de flagenvio en postulante
				if ($rsu[0]*1 ==1 ){ //--Esta Activo
					$Movi = "25";    //-- Mov. de Convocatoria Interna
				}else{
					if ($rsu[0]*1 == 2 ){ //-- esta Cesado
						$Movi = "22";    //-- Movimiento de Reingreso
					}
				}
				if ($Movi !=""){
          			//-- obtener codigo del Movimiento
					$ssql = "SELECT Emp_Mov_codigo ";
					$ssql .= " From Empleado_Movimiento ";
					$ssql .= " Where Empleado_Codigo = " . $this->empleado_codigo;
					$ssql .= " 		And Movimiento_codigo = " . $Movi;
					$ssql .= " 		And Estado_codigo = 6 ";
					//echo $ssql;
					$ress= mssql_query($ssql);
					if (mssql_num_rows($ress)>0) {
						//-- Anular Movimiento
						$rss= mssql_fetch_row($ress);
						$omov = new COM("Movimientos.clsMovimientos") or die("No puedo Ejecutar el Componente");
						$omov->Connection = $this->myconnection;
						$rpta = $omov->Anular_Movimientos($rss[0], "Anulado por interface de Envio de Personal", $Responsable);
						$omov=null;
					}
				}
			}
			//--Estado es Activo: Generar Movimiento de Convocatoria Interna
			//--Estado es Desactivo: Generar Movimiento de Reingreso
			if ($codApto *1 == 1 ){
				if ($rsu[0] *1== 1 ){ //--Esta Activo
                    $Movi = "25";    //-- Mov. de Convocatoria Interna
        }else{
					if ($rsu[0]*1 == 2 ){
						$Movi = "22";    //-- Movimiento de Reingreso
					}else{
						$rpta = "Registro de Empleado No tiene el estado apropiado para generarle movimiento";
						return $rpta;
					}
				}
				//-- Tomar datos del Rqto
		        $AreaO = $rsu[1]; //area_codigo
		        $ssql = "Select area_codigo, cod_campana, convert(varchar(10),rqto_fecha_ingreso, 103) as rqto_fecha_ingreso from requerimientos where rqto_codigo = " . $this->rqto_codigo;
				//echo $ssql;
		        $resd =mssql_query($ssql);
				$rsd= mssql_fetch_row($resd);
		        $AreaD = $rsd[0]; //area_codigo
		        $Serv = $rsd[1];  //cod_campana
		        $FInicio = $this->empleado_fecha_ingreso;

		        //-- Llamar al Movimiento indicado
		        //Set o = CreateObject("Movimientos.clsMovimientos")
		        $omov = new COM("Movimientos.clsMovimientos") or die("No puedo Ejecutar el Componente");
    				$omov->Connection = $this->myconnection;
    				//echo "$Movi, $this->empleado_codigo, $Responsable, $AreaO, $AreaD, $Serv, '', $FInicio, '', '', $Usuario";
		        $rpta = $omov->Generar_Movimiento_Envio($Movi, $this->empleado_codigo, $Responsable, $AreaO, $AreaD, $Serv, "", $FInicio, "", "0", "", $Usuario);
				if ($rpta==''){
					$rpta='OK';
				}else{
					echo "<br>" . $rpta;
				}
            	$omov = null;
			}
			if ($rpta=='OK'){
				//--Actualizo el estado de Postulantes a Seleccionado
				$ssql = "update Postulantes set Estados_Codigo= 21";
				$ssql .= "  ,Postulante_Fecha_Reg= GetDate() ";
				$ssql .= "  , Postulante_nombre= upper(Postulante_nombre)";
				$ssql .= "  , Postulante_Apellido_Paterno= upper(Postulante_Apellido_Paterno)";
				$ssql .= "  , Postulante_Apellido_Materno= upper(Postulante_Apellido_Materno)";
		    	$ssql .= " where Postulante_Codigo =" . $this->postulante_codigo;
				//echo $ssql;
				//-- Marco al postulante como sujeto enviado alta
				$ssql = "update Rqto_Sujeto set Flag_Envio= 1 ";
				$ssql .= " , Estado_Codigo= " . $Estado;
				$ssql .= " , Rqto_Sujeto_Observaciones= '" . $Observaciones . "' ";
				$ssql .= " , Fecha_Ingreso = convert(datetime,'" . $this->empleado_fecha_ingreso . "',103) ";
				$ssql .= " where Rqto_Sujeto_Codigo = " . $this->postulante_codigo;
				$ssql .= " 		and Rqto_codigo =" . $this->rqto_codigo;
				$ssql .= " 		and Tipo_Sujeto =" . $tipo_sujeto;
				//echo $ssql;
				$result = mssql_query($ssql);
			}
		}

	}
	return $rpta;
 }

 function Anular_Alta(){
  $rpta='';
	$linkBALTA = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
  mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());

	$ssql="exec spRRHH_ALTA_ANULAR ";
	$ssql .= "\"" . $this->empleado_codigo . "\"";
	//echo $ssql;
	$result_store = mssql_query($ssql);
	$rs_store= mssql_fetch_row($result_store);
	if ($rs_store[0]>0){
		$rpta = $rs_store[0];
	}
	switch ($rpta){
		case '1':  $rpta = "ERROR, No se permite anular alta. : " . $this->empleado_codigo;
					echo $rpta;
			break;

		default:
			$rpta='OK';
	}

	mssql_close($linkBALTA);

	return $rpta;
 }

 function Listar_Movimientos_Resumen1(){
 	$rpta="";
	$linkLMRES = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());

	$ssql="SELECT Empleado_Movimiento.Movimiento_codigo, Movimiento.Movimiento_Descripcion, COUNT(Empleado_Movimiento.Emp_Mov_codigo) AS veces ";
	$ssql .= " FROM  Empleado_Movimiento INNER JOIN ";
	$ssql .= " 		  Movimiento ON Empleado_Movimiento.Movimiento_codigo = Movimiento.Movimiento_codigo ";
	$ssql .= " 	WHERE Empleado_Movimiento.Empleado_Codigo = " . $this->empleado_codigo;
	$ssql .= " 	GROUP BY Empleado_Movimiento.Movimiento_codigo, Movimiento.Movimiento_Descripcion ";
	$ssql .= " 	ORDER BY Movimiento.Movimiento_Descripcion" ;
	//echo $ssql;
	$res= mssql_query($ssql);
	$i=0;
	if (mssql_num_rows($res)>0){
		while ($rs=mssql_fetch_row($res)){
			$i+=1;
			echo "\n<tr> ";
			echo "\n	<td class=dataTD align=rigth>" . $i . "&nbsp;</td> ";
			echo "\n	<td class=dataTD >&nbsp;" . $rs[1] . "</td> ";
			echo "\n	<td class=dataTD >&nbsp;" . $rs[2] . "</td> ";
			echo "\n	<td class=dataTD >&nbsp;[&nbsp;<font onclick='ver_detalle(" . $rs[0] . ")' style='cursor:hand'>Detalle</font>&nbsp;]&nbsp;</td> ";
			echo "\n</tr> ";
		}
	}else{
		echo "<p><b>No se encontraron registros de Movimientos</b></p>";
	}

 	mssql_close($linkLMRES);
 }

 function Listar_Movimientos_Detalle(){
 	$rpta="";
	$cn = $this->getMyConexionADO();
    $params = array(
                    $this->empleado_codigo,
                    $this->movimiento_codigo
                );
 	$ssql="SELECT 
                Empleado_Movimiento.Emp_Mov_codigo, 
                Movimiento.Movimiento_Descripcion, 
                Areas_2.Area_Descripcion,  
	 	        Areas_1.Area_Descripcion AS area_destino, 
                v_campanas.Exp_NombreCorto + ' - ' + v_campanas.Exp_Codigo AS Campana,  
	 	        convert(varchar(10),Empleado_Movimiento.Emp_Mov_Fecha_Inicio, 103) as Emp_Mov_Fecha, 
                convert(varchar(10),Empleado_Movimiento.Emp_Mov_Fecha_Fin, 103) as Emp_Mov_Fecha_Fin, 
                Empleado_Movimiento.Rqto_Codigo,  
	 	        Estados.Estado_descripcion, 
                Empleado_Movimiento.Emp_Mov_Motivo, 
                convert(varchar(10), Empleado_Movimiento.Movimiento_Fecha_Registro, 103) + ' ' + convert(varchar(10), Empleado_Movimiento.Movimiento_Fecha_Registro, 108), 
                convert(varchar(10), Emp_Mov_Fecha_Reg, 103) + ' ' + convert(varchar(10), Emp_Mov_Fecha_Reg, 108),  
                Empleado_Movimiento.Ccb_Codigo, 
	 	        Empleado_Movimiento.empleado_codigo,
                Empleado_Movimiento.movimiento_codigo, 
	            isnull (datediff (day,Empleado_Movimiento.Emp_Mov_Fecha_Inicio,Empleado_Movimiento.Emp_Mov_Fecha_Fin)+1 , 1 ) ";
	$ssql .= " FROM  Empleado_Movimiento INNER JOIN ";
	$ssql .= " 	  Movimiento ON Empleado_Movimiento.Movimiento_codigo = Movimiento.Movimiento_codigo INNER JOIN ";
	$ssql .= " 	  Estados ON Empleado_Movimiento.Estado_codigo = Estados.Estado_codigo LEFT OUTER JOIN ";
	$ssql .= " 	  v_campanas ON Empleado_Movimiento.Cod_Campana = v_campanas.Cod_Campana LEFT OUTER JOIN ";
	$ssql .= " 	  Areas Areas_1 ON Empleado_Movimiento.Area_Destino = Areas_1.Area_Codigo LEFT OUTER JOIN ";
	$ssql .= " 	  Areas Areas_2 ON Empleado_Movimiento.Area_Origen = Areas_2.Area_Codigo ";
	$ssql .= " WHERE  Empleado_Movimiento.Empleado_Codigo = ? AND Empleado_Movimiento.Movimiento_codigo = ?";
	$ssql .= " ORDER BY Empleado_Movimiento.Emp_Mov_Fecha_Inicio desc ";

	$rs= $cn->Execute($ssql, $params);
	$i=0;
	if( $rs->RecordCount() >0){
		while(!$rs->EOF){
			$i+=1;
			echo "\n<tr> ";
			echo "\n	<td class=DataTD align=rigth>" . $rs->fields[0] . "&nbsp;</td> ";
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[1] . "</td> ";
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[2] . "</td> ";
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[3] . "</td> ";
			//echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[4] . "</td> ";
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[5] . "</td> ";  //fini
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[6] . "</td> ";  //ffin
			echo "\n	<td class=DataTD align=center>&nbsp;" . $rs->fields[15] . "</td> ";  //nro dias
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[7] . "</td> "; //rqto
      		//echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[12] . "</td> "; //Cebe
			echo "\n	<td class=DataTD ><p>" . $rs->fields[9] . "</p></td> ";
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[10] . "</td> ";
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[11] . "</td> ";
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[8] . "</td> "; //estado
			//echo "\n	<td class=DataTD >&nbsp;<img src='../Images/columnselect.gif' onclick=lanzarflujo('" . $rs->fields[0] . "') border=0 style='cursor:hand'></td> "; //flujo
                        echo "\n	<td class=DataTD >&nbsp;<img src='../Images/columnselect.gif' onclick=lanzarflujo('" . $rs->fields[0] . "','".$this->movimiento_codigo."','" . $this->empleado_codigo . "') border=0 style='cursor:hand'></td> "; //flujo
			echo "\n	<td class=DataTD >&nbsp;<img src='../Images/glasses1.gif' onclick=lanzarver('" . $rs->fields[0] . "','" . $rs->fields[13] . "','" . $rs->fields[14] . "') border=0 style='cursor:hand'></td> "; //ver
			echo "\n</tr> ";
            $rs->MoveNext();
		}
	}else{
		echo "<p><b>No se encontraron registros de Movimientos</b></p>";
	}

 }

 function Listar_Movimientos_Resumen(){
 	$rpta="";
	$cn = $this->getMyConexionADO();
        $params = array($this->empleado_codigo);
	$ssql="SELECT Empleado_Movimiento.Movimiento_codigo, Movimiento.Movimiento_Descripcion, COUNT(Empleado_Movimiento.Emp_Mov_codigo) AS veces ";
	$ssql .= " FROM  Empleado_Movimiento INNER JOIN ";
	$ssql .= " 		  Movimiento ON Empleado_Movimiento.Movimiento_codigo = Movimiento.Movimiento_codigo ";
	$ssql .= " 	WHERE Empleado_Movimiento.Empleado_Codigo = ?";
	$ssql .= " 	GROUP BY Empleado_Movimiento.Movimiento_codigo, Movimiento.Movimiento_Descripcion ";
	$ssql .= " 	ORDER BY Movimiento.Movimiento_Descripcion" ;
	//echo $ssql;
	$rs= $cn->Execute($ssql, $params);
	$i=0;
	if( $rs->RecordCount()>0){
		while(!$rs->EOF){
			$i+=1;
			echo "\n<tr> ";
			echo "\n	<td class=dataTD align=rigth>" . $i . "&nbsp;</td> ";
			echo "\n	<td class=dataTD >&nbsp;" . $rs->fields[1] . "</td> ";
			echo "\n	<td class=dataTD >&nbsp;" . $rs->fields[2] . "</td> ";
			//echo "\n	<td class=dataTD >&nbsp;[&nbsp;<font onclick='ver_detalle(" . $rs->fields[0] . ")' style='cursor:hand'>Detalle</font>&nbsp;]&nbsp;</td> ";
                        echo "\n	<td class=dataTD >&nbsp;[&nbsp;<font onclick=\"ver_detalle('".$rs->fields[0]."','".$this->empleado_codigo."')\" style='cursor:hand'>Detalle</font>&nbsp;]&nbsp;</td> ";
			echo "\n</tr> ";
            $rs->MoveNext();
		}
	}else{
		echo "<p><b>No se encontraron registros de Movimientos</b></p>";
	}
 }

 function Listar_ultimos_movimientos(){
 	  $rpta="";
	  $cn = $this->getMyConexionADO();
      $params = array($this->empleado_codigo);
      $ssql="SELECT em.Emp_Mov_codigo, m.Movimiento_Descripcion, ";
      $ssql.="	aorigen.Area_Descripcion AS Origen, adestino.Area_Descripcion AS Destino, v.Exp_NombreCorto,";
      $ssql.="	convert(varchar(10),em.Emp_Mov_Fecha_Inicio,103) AS Inicio,";
      $ssql.="	convert(varchar(10),em.Emp_Mov_Fecha_Fin, 103) AS Fin, ";
      $ssql.="	em.Rqto_Codigo as Rqto, isnull(em.Ccb_codigo,'') as Cebe, em.Emp_Mov_Motivo as Observaciones,";
      $ssql.="	convert(varchar(10),em.Emp_Mov_Fecha_Reg,103) + ' ' + convert(varchar(10),em.Emp_Mov_Fecha_Reg,108) AS Fecha_Registro, ";
      $ssql.="	convert(varchar(10),em.Movimiento_fecha_registro,103) + ' ' +  convert(varchar(10),em.Movimiento_fecha_registro,108) AS Fecha_Atencion,";
      $ssql.="	Estados.Estado_descripcion, em.empleado_codigo, em.movimiento_codigo";
      $ssql.="	, isnull (datediff (day,em.Emp_Mov_Fecha_Inicio,em.Emp_Mov_Fecha_Fin)+1 , 1 )";
      $ssql.=" FROM Empleado_Movimiento em INNER JOIN";
      $ssql.="     Movimiento M ON em.Movimiento_codigo = m.Movimiento_codigo INNER JOIN";
      $ssql.="     Areas aorigen ON em.Area_Origen = aorigen.Area_Codigo INNER JOIN";
      $ssql.="     Estados ON em.Estado_codigo = Estados.Estado_codigo LEFT OUTER JOIN";
      $ssql.="     Areas adestino ON em.Area_Destino = adestino.Area_Codigo LEFT OUTER JOIN";
      $ssql.="   v_campanas v on v.cod_campana= em.cod_campana";
      $ssql.=" WHERE em.Empleado_Codigo = ?";
      $ssql.=" ORDER BY em.Emp_Mov_Fecha_Reg DESC";

      //echo $ssql;
      $rs= $cn->Execute($ssql, $params);
      $i=0;
      if( $rs->RecordCount() >0){
    		while(!$rs->EOF){
    			$i+=1;
    			echo "\n<tr> ";
    			echo "\n	<td class=DataTD align=rigth>" . $rs->fields[0] . "&nbsp;</td> ";
    			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[1] . "</td> "; //-- Movimiento nombre
    			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[2] . "</td> "; //-- origen
    			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[3] . "</td> "; //-- destino
    			//echo "\n	<td class=DataTD >&nbsp;" . $rs[4] . "</td> "; //-- U. Servicio
    			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[5] . "</td> ";  //fini
    			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[6] . "</td> ";  //ffin
    			echo "\n	<td class=DataTD align=center>&nbsp;" . $rs->fields[15] . "</td> ";  //nro dias
    			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[7] . "</td> "; //rqto
          		//echo "\n	<td class=DataTD >&nbsp;" . $rs[8] . "</td> "; //Cebe
    			echo "\n	<td class=DataTD ><p>" . $rs->fields[9] . "</p></td> ";//-- obs
    			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[11] . "</td> "; //--fecha_reg
    			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[10] . "</td> "; //-- fecha atencion
    			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[12] . "</td> "; //estado
    			echo "\n	<td class=DataTD >&nbsp;<img src='../Images/columnselect.gif' onclick=lanzarflujo('" . $rs->fields[0] . "','" . $rs->fields[14] . "') border=0 style='cursor:hand'></td> "; //flujo
    			echo "\n	<td class=DataTD >&nbsp;<img src='../Images/glasses1.gif' onclick=lanzarver('" . $rs->fields[0] . "','" . $rs->fields[13] . "','" . $rs->fields[14] . "') border=0 style='cursor:hand'></td> "; //parametros
    			echo "\n</tr> ";
                $rs->MoveNext();
    		}
    	}else{
    		echo "<p><b>No se encontraron registros de Movimientos</b></p>";
    	}
     }

  function Listar_movimientos(){
 	$rpta="";
	$cn = $this->getMyConexionADO();
    $params = array($this->empleado_codigo);
    $ssql="SELECT em.Emp_Mov_codigo,
                m.Movimiento_Descripcion, 
                aorigen.Area_Descripcion AS Origen,
                adestino.Area_Descripcion AS Destino,
                v.Exp_NombreCorto,
                convert(varchar(10),em.Emp_Mov_Fecha_Inicio,103) AS Inicio,
                convert(varchar(10),em.Emp_Mov_Fecha_Fin, 103) AS Fin, 
                em.Rqto_Codigo as Rqto, 
                isnull(em.Ccb_codigo,'') as Cebe,
                em.Emp_Mov_Motivo as Observaciones,
                convert(varchar(10),em.Emp_Mov_Fecha_Reg,103) + ' ' + convert(varchar(10),em.Emp_Mov_Fecha_Reg,108) AS Fecha_Registro, 
                convert(varchar(10),em.Movimiento_fecha_registro,103) + ' ' + convert(varchar(10),em.Movimiento_fecha_registro,108) AS Fecha_Atencion,
                Estados.Estado_descripcion,
                em.empleado_codigo,
                em.movimiento_codigo ,
                estados.estado_codigo,
                convert(varchar(10),ma.fecha_registro,103) + ' ' + convert(varchar(10),ma.fecha_registro,108) AS Fecha_Anulacion ,
                isnull (datediff (day,em.Emp_Mov_Fecha_Inicio,em.Emp_Mov_Fecha_Fin)+1 , 1 ),
                ( SELECT TOP 1
        		CASE WHEN GETDATE()  BETWEEN NPP.FECHA_ANTERIOR AND NPP.FECHA_ACTUAL
        			THEN
        				CASE WHEN EM.EMP_MOV_FECHA_REG BETWEEN NPP.FECHA_ANTERIOR AND NPP.FECHA_ACTUAL
        					THEN 'SI'
        				ELSE
        					CASE WHEN EM.EMP_MOV_FECHA_REG < NPP.FECHA_ANTERIOR AND EM.EMP_MOV_FECHA_INICIO > CONVERT(DATETIME, '01/' + LTRIM(STR(NP.Mes_Codigo)) +  '/' + LTRIM(STR(NP.Anio_Codigo)) , 103)
        						THEN 'SI'
        					ELSE
        						'NO'
        					END
        				END
        		ELSE
        			CASE WHEN GETDATE() > NPP.FECHA_ACTUAL
        				THEN	CASE WHEN EM.EMP_MOV_FECHA_REG > NPP.FECHA_ACTUAL 
        							THEN 'SI'
        						ELSE
        							'NO'
        						END
        			END
        				
        		END
        	  FROM NOM_PERIODO NP
        			INNER JOIN NOM_PERIODO_PROCESOS NPP ON NP.ANIO_CODIGO = NPP.ANIO_CODIGO AND NP.MES_CODIGO = NPP.MES_CODIGO
        	  WHERE NP.APERTURADO = 'S'
        			AND NPP.PROCESO_CODIGO = 1) AS PUEDE_ANULARSE
            FROM Empleado_Movimiento em INNER JOIN
                Movimiento M ON em.Movimiento_codigo = m.Movimiento_codigo INNER JOIN
                Areas aorigen ON em.Area_Origen = aorigen.Area_Codigo INNER JOIN
                Estados ON em.Estado_codigo = Estados.Estado_codigo LEFT OUTER JOIN
                Areas adestino ON em.Area_Destino = adestino.Area_Codigo LEFT OUTER JOIN
                v_campanas v on v.cod_campana= em.cod_campana
                LEFT OUTER JOIN  movimiento_anulado ma ON em.Emp_Mov_codigo = ma.Emp_Mov_codigo 
                INNER JOIN  empleados ON em.empleado_codigo = empleados.empleado_codigo 
            WHERE em.Empleado_Codigo = ?
                and em.estado_codigo not in (6,3)
                and empleados.estado_codigo in (1,2)
                and ( M.movimiento_codigo in (30,8,9,6,5,14) or (M.Movimiento_codigo = 28 and em.Emp_Mov_Fecha_Inicio > GETDATE()))
            ORDER BY em.Emp_Mov_codigo DESC";

  //echo $ssql;
    $rs= $cn->Execute($ssql, $params);
    return $rs;
}

function  Detalle_Movimiento_Anulado($empmovcodigo){
    $rpta="";
    $cn = $this->getMyConexionADO();
    $params = array($empmovcodigo);
    $ssql="SELECT em.Emp_Mov_codigo, m.Movimiento_Descripcion, ";
    $ssql.="	e.empleado_apellido_paterno + ' ' + e.empleado_apellido_materno + ' ' + e.empleado_nombres as responsable, ";
    $ssql.="	convert(varchar(10),ma.fecha_registro,103)+' '+convert(varchar(10),ma.fecha_registro,108) AS Fecha_Atencio,";
    $ssql.="	ma.observaciones as Observaciones";
    $ssql.=" FROM Empleado_Movimiento em INNER JOIN";
    $ssql.="     Movimiento M ON em.Movimiento_codigo = m.Movimiento_codigo INNER JOIN";
    $ssql.="     movimiento_anulado ma ON em.emp_mov_codigo = ma.emp_mov_codigo INNER JOIN";
    $ssql.="     Empleados e ON e.empleado_codigo = ma.responsable_codigo ";
    $ssql.=" WHERE em.emp_mov_Codigo = ?";
    
    $rs= $cn->Execute($ssql, $params);
	  $i=0;
	  if( $rs->RecordCount()>0){
		while(!$rs->EOF){
			$i+=1;
			echo "\n<tr> ";
			echo "\n	<td class=DataTD align=rigth>" . $rs->fields[0] . "&nbsp;</td> ";
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[1] . "</td> "; //-- Movimiento nombre
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[2] . "</td> "; //-- origen
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[3] . "</td> "; //-- destino
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[4] . "</td> "; //-- U. Servicio
			echo "\n</tr> ";
            $rs->MoveNext();
		}
	   }else{
		  echo "<p><b>No se encontraron registros de Movimientos</b></p>";
	   }

}



  function Listar_Empleados($filtro){
 	$rpta="";
	$cn = $this->getMyConexionADO();
    $ssql="SELECT  Empleados.Empleado_codigo,";
    $ssql.="	Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres AS empleado";
    $ssql.="	FROM    Empleados";
    $ssql.="	WHERE (Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres like '%$filtro%')";
	$rs=$cn->Execute($ssql);
    if( $rs->RecordCount()==0){
        echo "<center><b>No existen empelados</b><center>" ;
    }else{
      echo "\n<table class=table cellspacing='1' cellpadding='0' border='0' align=center style='width:95%'>";
      echo "\n<tr Class=Cabecera align=center>";
      echo "\n  <td>Codigo</td>";
      echo "\n  <td>Empleado</td>";
      echo "\n</tr>";
      while(!$rs->EOF){
	    echo "\n<tr class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#ffebd7'>";
        echo "\n   <td class=DataTD><font color=red style='CURSOR: hand' onclick=\"guardar('". $rs->fields[0] . "','" . $rs->fields[1] . "')\"><b>" . $rs->fields[0] . "</b></font></td> ";
        echo "\n   <td class=DataTD>" . $rs->fields[1] . "</td>";
        echo "\n</tr>";
        $rs->MoveNext();
       }
       echo "</table><br>";
    }
    return $rpta;
 }

    function Listar_Movimientos_Flujo($cod_mov){
        $cn = $this->getMyConexionADO();
        $params = array($cod_mov);
        $sql = "select fe.Flujo_Orden as secuencia,
                	e.Estado_descripcion as estado,
                	dbo.UDF_EMPLEADO_NOMBRE(fe.Responsable_Codigo) AS registrado_por,
                	convert(varchar(10), fe.flujo_fecha,103) + ' ' + convert(varchar(10), fe.flujo_fecha,108) as fecha_registro,
                	fe.Observaciones
                from Flujo_Ejecucion fe
                	inner join Estados e on fe.Estado_Codigo = e.Estado_codigo
                where fe.Emp_Mov_codigo = ?
                order by 1 ";
        $rs = $cn->Execute($sql, $params);
        return $rs;
    }   
    
    function Listar_Movimientos_No_Flujo($cod_mov){
        $cn = $this->getMyConexionADO();
        //$cn->debug = TRUE;
        $params = array($cod_mov);
        $sql = "select (e.empleado_apellido_paterno + ' ' + e.empleado_apellido_materno + ' ' + e.Empleado_Nombres) as empleado,
                	CONVERT(varchar(10),Movimiento_fecha_registro,103) + ' ' + CONVERT(varchar(10),Movimiento_fecha_registro,108)
                from Empleado_Movimiento em inner join empleados e on em.Usuario_Responsable = e.Empleado_Codigo
                where Emp_Mov_codigo = ?";
        $rs = $cn->Execute($sql, $params);
        return $rs;
    }   
    
    function Obtener_Datos_Movimiento($cod_mov){
        $cn = $this->getMyConexionADO();
        $estado = 0;
        $params = array($cod_mov);
        $sql = "select em.Estado_codigo,
                	convert(varchar(10),em.Movimiento_fecha_registro,103) + ' ' + convert(varchar(10),em.Movimiento_fecha_registro,108) ,
                	m.Movimiento_Descripcion,
                    em.area_destino,
                    dbo.UDF_EMPLEADO_NOMBRE(em.empleado_codigo) as empleado
                from Empleado_Movimiento em 
                	inner join movimiento m on em.Movimiento_codigo = m.Movimiento_codigo
                where em.Emp_Mov_codigo = ?";
        $rs = $cn->Execute($sql, $params);
        return $rs;
    }
    
    function Listar_Movimientos_Aprobadores($cod_mov,$movimiento_codigo,$area_destino){
        $cn = $this->getMyConexionADO();
        //$cn->debug = true;
        $params = array($movimiento_codigo);
        
        if($movimiento_codigo == 1){
            //validamos si es un movmiento de traslado en proceso(estado=6)) y un  
            //flujo aprobado(estado=8) para mostrar al mando como siguiente aprobador
            $params =array($cod_mov);
            $sql = "select top 1 fe.Flujo_Orden, fe.Estado_Codigo
                    from empleado_movimiento em
                    	inner join Flujo_Ejecucion fe on em.Emp_Mov_codigo = fe.Emp_Mov_codigo
                    where em.Emp_Mov_codigo = ? 
                    order by Flujo_Orden desc";
            $rs = $cn->Execute($sql, $params);
            if($rs->RecordCount() > 0){
                $orden = $rs->fields[0];
                $estado = $rs->fields[1];
                // si el orden es inicial y esta aprobado, entonces el siguiente aprobador es el mando del area destino
                
                if($orden == 1 && $estado == 8){
                    $params = array($area_destino);
                    $sql = "select dbo.UDF_EMPLEADO_NOMBRE(a.empleado_responsable) AS aprobador,
                            	a.Area_Descripcion
                            from Areas a
                            where Area_Codigo = ?";
                    $rs = $cn->Execute($sql, $params);
                    return $rs;
                }
            }
        }   
        $params =array($movimiento_codigo);
        //no considerar empleados cesados, por eso se toma de la vdatos 
        $sql = "select  v.empleado, v.area_descripcion
                from Flujo_Movimiento fm
                	inner join Flujo_Especial fe on fm.Flujo_codigo = fe.Flujo_Codigo
                	inner join vdatos v on v.empleado_codigo = fe.empleado_codigo
                where fm.Movimiento_codigo = ? and fm.Flujo_Mov_Activo = 1 and fe.Flujo_Especial_Activo = 1
                order by 1";
        /*
        $sql = "select  dbo.UDF_EMPLEADO_NOMBRE(fe.Empleado_Codigo) AS aprobador,
                	a.Area_Descripcion
                from Flujo_Movimiento fm
                	inner join Flujo_Especial fe on fm.Flujo_codigo = fe.Flujo_Codigo
                	inner join Empleado_Area ea on ea.Empleado_Codigo = fe.Empleado_Codigo and ea.Empleado_Area_Activo=1
                	inner join Areas a on ea.Area_Codigo = a.Area_Codigo
                where fm.Movimiento_codigo = ? and fm.Flujo_Mov_Activo = 1 and fe.Flujo_Especial_Activo = 1
                order by 1";
        */
        $rs = $cn->Execute($sql, $params);
        return $rs;  
        
    }
/*
 function Listar_Movimientos_Flujo($cod_mov){
 	$rpta="";
	$cn = $this->getMyConexionADO();
    $params = array($cod_mov);
	$ssql="SELECT Empleado_Movimiento.Emp_Mov_codigo,
                  Flujo_Ejecucion.Flujo_Mov_Codigo,
                  Empleado_Movimiento.Movimiento_fecha_registro AS FechaMov,
                  Flujo_Ejecucion.Flujo_Orden,
                  Estados.Estado_descripcion,
                  dbo.UDF_EMPLEADO_NOMBRE(Flujo_Ejecucion.Responsable_Codigo) AS Responsable,
                  Flujo_Ejecucion.Observaciones,
                  Flujo_Ejecucion.Usuario_Admin,
                  dbo.UDF_EMPLEADO_NOMBRE(CASE WHEN Flujo_Ejecucion.Usuario_Admin is null
                    THEN Flujo_Ejecucion.Responsable_Codigo
                    ELSE Flujo_Ejecucion.Usuario_Admin END) AS Ejecutor,
                  convert(varchar(10),Flujo_Ejecucion.Flujo_Fecha,103) + ' ' + convert(varchar(10),Flujo_Ejecucion.Flujo_Fecha,108)
            FROM Empleado_Movimiento INNER JOIN
                  Flujo_Ejecucion ON Empleado_Movimiento.Emp_Mov_codigo = Flujo_Ejecucion.Emp_Mov_codigo INNER JOIN
                  Estados ON Flujo_Ejecucion.Estado_Codigo = Estados.Estado_codigo
            WHERE Empleado_Movimiento.Emp_Mov_codigo = ?
            ORDER BY Flujo_Ejecucion.Flujo_Orden ";
	$rs= $cn->Execute($ssql, $params);
	$i=0;
	if( $rs->RecordCount() >0){
	    echo "\n<center><b>Fecha Movimiento:&nbsp;" . $rs->fields[2] . "</b></center>";
	    echo "\n<table class='table' width='100%'  border=0 cellspacing=1 cellpadding=0>";
        echo "\n <tr class=Cabecera>";
        echo "\n   <td style='width:30px'>Orden</td>";
        echo "\n   <td style='width:80px'>Estado</td>";
        echo "\n   <td style='width:120px'>Responsable Flujo</td>";
        echo "\n   <td style='width:120px'>Ejecuta Flujo</td>";
        echo "\n   <td style='width:80'>Fecha</td>";
        echo "\n   <td style='width:160px'>Observaciones</td>";
        echo "\n </tr>";
		while(!$rs->EOF){
			$i+=1;
			echo "\n<tr> ";
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[3] . "</td> ";
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[4] . "</td> ";
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[5] . "</td> ";
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[8] . "</td> ";
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[9] . "</td> ";
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[6] . "</td> ";  //fini
			echo "\n</tr> ";
            $rs->MoveNext();
		}
		echo "\n</table>";
	}else{
        $params = array($cod_mov);
        $sql = "select (e.empleado_apellido_paterno + ' ' + e.empleado_apellido_materno + ' ' + e.Empleado_Nombres) as empleado,
                	CONVERT(varchar(10),Movimiento_fecha_registro,103) + ' ' + CONVERT(varchar(10),Movimiento_fecha_registro,108)
                from Empleado_Movimiento em inner join empleados e on em.Usuario_Responsable = e.Empleado_Codigo
                where Emp_Mov_codigo = ?";
        $rs = $cn->Execute($sql, $params);
        if($rs->RecordCount() > 0){
            echo "\n<center><b>Fecha Movimiento:&nbsp;" . $rs->fields[2] . "</b></center>";
    	    echo "\n<table class='table' width='100%'  border=0 cellspacing=1 cellpadding=0>";
            echo "\n <tr class=Cabecera>";
            echo "\n   <td>Usuario Registra</td>";
            echo "\n   <td>Fecha y Hora Registro</td>";
            echo "   </tr>";
            echo "\n<tr> ";
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[0] . "</td> ";
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[1] . "</td> ";
			echo "\n</tr></table>";
        }else{
            echo "<p><b>No se encontraron registros de flujo de Movimientos</b></p>"; 
        }
	   
	}
 }
*/
  function Listar_Movimientos_Sin_Flujo($cod_mov){
 	$rpta="";
	$cn = $this->getMyConexionADO();
    $params = array($cod_mov);
    $ssql="SELECT Empleado_Movimiento.Emp_Mov_codigo,
                convert(varchar(10), Empleado_Movimiento.Movimiento_fecha_registro, 103) + ' ' +
                convert(varchar(10), Empleado_Movimiento.Movimiento_fecha_registro, 108)  AS FechaMov,
                dbo.UDF_EMPLEADO_NOMBRE(Empleado_Movimiento.Usuario_Responsable) AS Responsable,
                Empleado_Movimiento.Emp_Mov_Motivo,
                dbo.UDF_EMPLEADO_NOMBRE(Empleado_Movimiento.Usuario_Admin)
         FROM Empleado_Movimiento
         WHERE Empleado_Movimiento.Emp_Mov_codigo = ?";
	$rs= $cn->Execute($ssql, $params);
	$i=0;
	if($rs->RecordCount()>0){
	   
	   echo "\n<br><table class='table' width='100%'  border=0 cellspacing=1 cellpadding=0>";
       echo "\n <tr class=Cabecera>";
       echo "\n   <td style='width:30px'>Orden</td>";
       echo "\n   <td style='width:120px'>Responsable Flujo</td>";
       echo "\n   <td style='width:80'>Fecha</td>";
       echo "\n   <td style='width:160px'>Observaciones</td>";
       echo "\n </tr>";
	   while(!$rs->EOF){
			$i+=1;
			echo "\n<tr> ";
			echo "\n	<td class=DataTD >&nbsp;" . $i. "</td> ";
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[2] . "</td> ";
            echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[1] . "</td> ";
			echo "\n	<td class=DataTD >&nbsp;" . $rs->fields[3] . "</td> ";  //fini
			echo "\n</tr> ";
            $rs->MoveNext();
		}
		echo "\n</table>";
	}else{
		echo "<p><b>No se encontraron registros de flujo de Movimientos</b></p>";
	}
 }

 function Empleado_Area(){
    //empleado_codigo=3300
   $rpta="OK";
   $cn = $this->getMyConexionADO();
   
   $params = array($this->empleado_codigo);
   $ssql="SELECT Empleado_Area.Area_Codigo, Areas.Area_Descripcion ";
   $ssql .= " FROM Empleados INNER JOIN ";
   $ssql .= " 		Empleado_Area ON Empleados.Empleado_Codigo = Empleado_Area.Empleado_Codigo INNER JOIN ";
   $ssql .= " 		Areas ON Empleado_Area.Area_Codigo = Areas.Area_Codigo ";
   $ssql .= " WHERE Empleados.Empleado_Codigo = ? AND Empleado_Area.Empleado_Area_Activo = 1 ";
   
   $rs = $cn->Execute($ssql, $params);
   if($rs->RecordCount() > 0){
		 $this->area_codigo = $rs->fields[0];
         $this->area_descripcion = $rs->fields[1];
	}else{
      $rpta='Error, No existe area de empleado: ' . $this->empleado_codigo;
   }


   return $rpta;
 }

 function Empleado_Area_y_jefe(){
    
    /*previo
     * $rpta="OK";
    $linkEAJ = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());

    $ssql = "select a.area_codigo, a.Area_Descripcion, a.empleado_responsable, ";
    $ssql .= "	a.Empleado_Apellido_Paterno, a.Empleado_Apellido_Materno, a.Empleado_Nombres ";
    $ssql .= " from vRRHH_Responsable_Area a inner join empleado_area ea on ";
    $ssql .= " 	a.area_codigo=ea.area_codigo inner join empleados e on e.Empleado_Codigo = ea.Empleado_Codigo ";
    $ssql .= " where (ea.Empleado_Area_Activo = 1) AND ";
    $ssql .= "       (e.empleado_codigo=" . $this->empleado_codigo . ") ";
    $res= mssql_query($ssql);
    if (mssql_num_rows($res)>0){
        $rs=mssql_fetch_row($res);
        $this->area_codigo = $rs[0];
        $this->area_descripcion = $rs[1];
        $this->empleado_jefe = $rs[2];
        $this->empleado_apellido_paterno= $rs[3];
        $this->empleado_apellido_materno= $rs[4];
        $this->empleado_nombres= $rs[5];
    }else{
        $rpta='Error, No existe area de empleado: ' . $this->empleado_codigo;
    }
    return $rpta;
     */
    
    $rpta="OK";
    $cn=$this->getMyConexionADO();

    $ssql = "select a.area_codigo, a.Area_Descripcion, a.empleado_responsable, ";
    $ssql .= "	a.Empleado_Apellido_Paterno, a.Empleado_Apellido_Materno, a.Empleado_Nombres ";
    $ssql .= " from vRRHH_Responsable_Area a inner join empleado_area ea on ";
    $ssql .= " 	a.area_codigo=ea.area_codigo inner join empleados e on e.Empleado_Codigo = ea.Empleado_Codigo ";
    $ssql .= " where (ea.Empleado_Area_Activo = 1) AND ";
    $ssql .= "       (e.empleado_codigo=" . $this->empleado_codigo . ") ";

    $rs=$cn->Execute($ssql);
    if($rs->RecordCount()>0){
        $this->area_codigo = $rs->fields[0];
        $this->area_descripcion = $rs->fields[1];
        $this->empleado_jefe = $rs->fields[2];
        $this->empleado_apellido_paterno= $rs->fields[3];
        $this->empleado_apellido_materno= $rs->fields[4];
        $this->empleado_nombres= $rs->fields[5];
    }else{
        $rpta='Error, No existe area de empleado: ' . $this->empleado_codigo;
    }

    return $rpta;
    
 }

 /*function Empleado_Area_y_jefe02(){
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    
    $ssql = "select a.area_codigo, a.Area_Descripcion, a.empleado_responsable, ";
    $ssql .= "	a.Empleado_Apellido_Paterno, a.Empleado_Apellido_Materno, a.Empleado_Nombres ";
    $ssql .= " from vRRHH_Responsable_Area a inner join empleado_area ea on ";
    $ssql .= " 	a.area_codigo=ea.area_codigo inner join empleados e on e.Empleado_Codigo = ea.Empleado_Codigo ";
    $ssql .= " where (ea.Empleado_Area_Activo = 1) AND ";
    $ssql .= "       (e.empleado_codigo=" . $this->empleado_codigo . ") ";

    $rs=$cn->Execute($ssql);
    if($rs->RecordCount()>0){
        $this->area_codigo = $rs->fields[0];
        $this->area_descripcion = $rs->fields[1];
        $this->empleado_jefe = $rs->fields[2];
        $this->empleado_apellido_paterno= $rs->fields[3];
        $this->empleado_apellido_materno= $rs->fields[4];
        $this->empleado_nombres= $rs->fields[5];
    }else{
        $rpta='Error, No existe area de empleado: ' . $this->empleado_codigo;
    }

   return $rpta;
 }*/

function Valor_Campo($tabla, $estado){
 	$rpta="";
	$cn = $this->getMyConexionADO();
	if (trim($tabla) == '') return 'No se especifico código de Tabla';
    $params = array(
                    $tabla,
                    $estado,
                    $this->empleado_codigo
                );
	$ssql = "select Item_Codigo, Atributo_codigo ";
    $ssql .= " From vw_atributos_items_emp ";
    $ssql .= " where tabla_codigo= ? and Estado_Codigo = ? and empleado_codigo = ?" ;
	//echo $ssql;
	$rs= $cn->Execute($ssql, $params);
	if( $rs->RecordCount() >0){
		$rpta=$rs->fields[0];
	}else{
		$rpta="";
	}
	return $rpta;
}

function Atributo_Valor($tabla, $estado){
 	$rpta="";
	$cn = $this->getMyConexionADO();
	if (trim($tabla) == '') return 'No se especifico codigo de Tabla';
    $params = array(
                    $tabla,
                    $estado,
                    $this->empleado_codigo
                );
	$ssql = "SELECT Item_Codigo, Atributo_codigo, Atributo_Valor 
             FROM vw_atributos_items_emp 
             WHERE tabla_codigo= ? and Estado_Codigo = ? and empleado_codigo = ?";
	//echo $ssql;
	$rs= $cn->Execute($ssql, $params);
	if( $rs->RecordCount()>0){
		$rpta=$rs->fields[2];
	}else{
		$rpta="";
	}
 	return $rpta;
}

function Valor_Tipo_Comision_Afp(){
 	$rpta="";
	$cn = $this->getMyConexionADO();
	$params = array($this->empleado_codigo);
    $ssql = "select ei.tipo_comision_afp, tca.tipo_comision_descripcion 
             from empleado_indicador ei inner join tipo_comision_afp tca on ei.tipo_comision_afp = tca.tipo_comision_codigo
              where ei.empleado_codigo = ?";            

	$rs= $cn->Execute($ssql, $params);
	if( $rs->RecordCount()>0){
		$rpta=$rs->fields[0];
	}else{
		$rpta="";
	}
 	return $rpta;
}

function leer_ipss_vida(){
 	$rpta="OK";
	$cn = $this->getMyConexionADO();
    $params = array($this->empleado_codigo);
	$ssql = "select ipss_vida From empleados ";
    $ssql .= " where empleado_codigo= ?";
	//echo $ssql;
	$rs= $cn->Execute($ssql, $params);
	if( $rs->RecordCount()>0){
		$this->ipss_vida=$rs->fields[0];
	}
 	return $this->ipss_vida;
}

function registrar_ipss_vida(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
    $params = array(
                    $this->ipss_vida,
                    $this->empleado_codigo
                );
	$ssql = " UPDATE empleados SET ipss_vida = ?";
    $ssql .= "  WHERE Empleado_Codigo = ?";
	$rs= $cn->Execute($ssql, $params);
 	return $rpta;
}

function leer_seguro_vida_ley(){
 	$rpta="OK";
	$cn = $this->getMyConexionADO();
    $params = array($this->empleado_codigo);
	$ssql = "select seguro_vida_ley From empleado_indicador ";
    $ssql .= " where empleado_codigo= ?";
	//echo $ssql;
	$rs= $cn->Execute($ssql, $params);
	if( $rs->RecordCount() >0){
		$this->seguro_vida_ley=$rs->fields[0];
	}
 	return $this->seguro_vida_ley;
}

function registrar_seguro_vida_ley(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
    $params = array(
                $this->seguro_vida_ley,
                $this->empleado_codigo
            );
	$ssql = " UPDATE empleado_indicador SET seguro_vida_ley = ?";
    $ssql .= "  WHERE Empleado_Codigo = ?";
	$rs= $cn->Execute($ssql, $params);
 	return $rpta;
}

function leer_SCTR(){
 	$rpta="OK";
	$cn = $this->getMyConexionADO();
    $params = array($this->empleado_codigo);
	$ssql = "select sctr From empleado_indicador ";
    $ssql .= " where empleado_codigo= ?";
	//echo $ssql;
	$rs= $cn->Execute($ssql, $params);
	if( $rs->RecordCount() >0){
		$this->sctr=$rs->fields[0];
	}
 	return $this->sctr;
}

function registrar_SCTR(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
    $params = array(
                    $this->sctr,
                    $this->empleado_codigo
                );
	$ssql = " UPDATE empleado_indicador SET sctr = ?";
    $ssql .= "  WHERE Empleado_Codigo = ?";
	$rs= $cn->Execute($ssql, $params);
 	return $rpta;
}

function leer_domiciliado(){
 	$rpta="OK";
	$cn = $this->getMyConexionADO();
    $params = array($this->empleado_codigo);
	$ssql = "select domiciliado From empleado_indicador ";
    $ssql .= " where empleado_codigo= ?";
	//echo $ssql;

	$rs= $cn->Execute($ssql, $params);
	if( $rs->RecordCount() >0){
		$this->domiciliado=$rs->fields[0];
	}
 	return $this->domiciliado;
}

function registrar_domiciliado(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
    $params = array(
                    $this->domiciliado,
                    $this->empleado_codigo
                );
	$ssql = " UPDATE empleado_indicador SET domiciliado = ?";
    $ssql .= "  WHERE Empleado_Codigo = ?";
	$rs= $cn->Execute($ssql, $params);
 	return $rpta;
}
/************************************************/
function leer_nomina(){
 	$rpta="OK";
	$cn = $this->getMyConexionADO();
    //$cn->debug=true;
    $params = array($this->empleado_codigo);
	$ssql = "select id_nomina From empleado_indicador ";
    $ssql .= " where empleado_codigo= ?";
	//echo $ssql;
	$rs= $cn->Execute($ssql, $params);
	if( $rs->RecordCount()>0){
		$this->id_nomina=$rs->fields[0];
	}
 	return $this->id_nomina;
}

function registrar_nomina(){
  $rpta="OK";
  $cn = $this->getMyConexionADO();
    $params = array(
                    $this->id_nomina,
                    $this->empleado_codigo
                );
  $ssql = " UPDATE empleado_indicador SET id_nomina = ?";
  $ssql .= "  WHERE Empleado_Codigo = ?";
  $rs= $cn->Execute($ssql, $params);
  return $rpta;
}
/************************************************/
function leer_tipo_nomina(){
 	$rpta="OK";
	$cn = $this->getMyConexionADO();
    $params = array($this->empleado_codigo);
	$ssql = "select tipo_nomina_codigo From empleado_indicador ";
    $ssql .= " where empleado_codigo= ?";
	//echo $ssql;
	$rs= $cn->Execute($ssql, $params);
	if( $rs->RecordCount()>0){
		$this->tipo_nomina_codigo=$rs->fields[0];
	}
 	return $this->tipo_nomina_codigo;
}

function registrar_tipo_nomina(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
    $params = array(
                    $this->tipo_nomina_codigo,
                    $this->empleado_codigo
                );
	$ssql = " UPDATE empleado_indicador SET tipo_nomina_codigo = ?";
    $ssql .= "  WHERE Empleado_Codigo = ?";
	$rs= $cn->Execute($ssql, $params);
 	return $rpta;
}

function leer_indicador_essalud(){
 	$rpta="OK";
	$cn = $this->getMyConexionADO();
    $params = array($this->empleado_codigo);
	$ssql = "select indicador_essalud From empleado_indicador ";
    $ssql .= " where empleado_codigo= ?";
	//echo $ssql;
	$rs= $cn->Execute($ssql, $params);
	if( $rs->RecordCount() >0){
		$this->indicador_essalud=$rs->fields[0];
	}
 	return $this->indicador_essalud;
}

function registrar_indicador_essalud(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
    $params = array(
                $this->indicador_essalud,
                $this->empleado_codigo
            );
	$ssql = " UPDATE empleado_indicador SET indicador_essalud = ?";
    $ssql .= "  WHERE Empleado_Codigo = ?";
	//echo $ssql;
	$rs= $cn->Execute($ssql, $params);
 	return $rpta;
}

function Obtener_Responsable_area(){
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    
    if(!empty($this->area_codigo)){
        $ssql = "select empleado_responsable, Empleado_Apellido_Paterno, Empleado_Apellido_Materno, Empleado_Nombres, ";
        $ssql.= " 	Empleado_Email, Empleado_Dni, Area_Descripcion ";
        $ssql.= " from vRRHH_Responsable_Area ";
        $ssql.= " where area_codigo= " . $this->area_codigo;
        $rs=$cn->Execute($ssql);
        if($rs->RecordCount()>0){
            $this->empleado_codigo = $rs->fields[0];
            $this->empleado_apellido_paterno=$rs->fields[1];
            $this->empleado_apellido_materno=$rs->fields[2];
            $this->empleado_nombres=$rs->fields[3];
            $this->empleado_email=	$rs->fields[4];
            $this->empleado_dni=	$rs->fields[5];
            $this->area_descripcion=$rs->fields[6];
        }
    }else{
        $rpta = "codigo de area vacio";
    }
    
    return $rpta;
}

/*function Obtener_Responsable_area02(){
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    
    $ssql = "select empleado_responsable, Empleado_Apellido_Paterno, Empleado_Apellido_Materno, Empleado_Nombres, ";
    $ssql.= " 	Empleado_Email, Empleado_Dni, Area_Descripcion ";
    $ssql.= " from vRRHH_Responsable_Area ";
    $ssql.= " where area_codigo= " . $this->area_codigo;
    
    $rs=$cn->Execute($ssql);
    
    if($rs->RecordCount()>0){
        $this->empleado_codigo = $rs->fields[0];
        $this->empleado_apellido_paterno=$rs->fields[1];
        $this->empleado_apellido_materno=$rs->fields[2];
        $this->empleado_nombres=$rs->fields[3];
        $this->empleado_email=	$rs->fields[4];
        $this->empleado_dni=	$rs->fields[5];
        $this->area_descripcion=$rs->fields[6];
    }
    return $rpta;
}*/




function validar_responsable_gerencia_general($ger_area, $ger_user, $ger_pass){
	$rpta="OK";
	$cn = $this->getMyConexionADO(); 
    $params = array(
                    $ger_area,
                    $ger_user,
                    $ger_pass
                );
	$ssql="SELECT  Empleados.Empleado_Codigo, Empleados.Empleado_Apellido_Paterno, Empleados.Empleado_Apellido_Materno, Empleados.Empleado_Nombres,";
    $ssql.="       Empleados.Empleado_Email, Empleados.Empleado_Dni, Areas.Area_Descripcion ";
	$ssql.=" FROM  Empleados INNER JOIN Areas ON Empleados.Empleado_Codigo = Areas.empleado_responsable ";
	$ssql.=" WHERE (Areas.Area_Codigo = ?) AND ";
	$ssql.="      (Empleados.Empleado_DNI=?) and (Empleados.Empleado_clave_acceso=?)";
	//echo $ssql;
	$rs= $cn->Execute($ssql, $params);
	if($rs->RecordCount() > 0){
		$this->empleado_codigo    = $rs->fields[0];
		$this->empleado_apellido_paterno=$rs->fields[2];
		$this->empleado_apellido_materno=	$rs->fields[3];
		$this->empleado_nombres   = $rs->fields[4];
		$this->empleado_email     =	$rs->fields[5];
		$this->empleado_dni       =	$rs->fields[6];
		$this->area_descripcion   =	$rs->fields[7];
	}else{
		$rpta='Usuario o Contraseï¿½a Incorrecta.';
	}
 	return $rpta;
}

function empleado_responsable_area($usuario){
	$rpta="";
	$cn = $this->getMyConexionADO();
    $params = array($usuario );
	$ssql = "SELECT empleados.Empleado_Codigo, CASE WHEN Empleados.Empleado_Codigo = Areas.empleado_responsable THEN 1 ELSE 0 END AS responsable " .
		" FROM Empleados INNER JOIN Areas ON Empleados.Empleado_Codigo = Areas.Empleado_Responsable " .
		" WHERE (Empleados.Empleado_Codigo = ?) AND (Empleados.estado_codigo = 1)  and (areas.Area_Activo = 1)";

	//echo $ssql;
	$rs= $cn->Execute($ssql, $params);
	if($rs->RecordCount() > 0){
		$this->empleado_codigo = $rs->fields[0];
		return $rs->fields[0];
	}else{
		$rpta='';
	}
 	return $rpta;
}

function empleado_apoyo_administrativo($usuario){
	$rpta="";
	$cn = $this->getMyConexionADO();
    $params = array($usuario );
	$ssql = "select distinct Empleado_Codigo 
            from Ejecutivo_Areas 
            WHERE Empleado_Codigo = ? 
            and Ejecutivo_Area_Activo = 1";

	//echo $ssql;
	$rs= $cn->Execute($ssql, $params);
	if($rs->RecordCount() > 0){
		return $rs->fields[0];
	}else{
		$rpta='';
	}
 	return $rpta;
}

function Sin_Valor($dato){
  if (trim($dato)==''){
    return "";
  }
  if (trim($dato)=='0'){
    return '';
  }
  return $dato;
}

function Valor_Nulo($dato){
  if (trim($dato)==''){
    return null;
  }
  return $dato;
}

function honomastico(){
	$rpta="0";
	$cn = $this->getMyConexionADO();
    $params = array($this->empleado_codigo);
	$ssql = "SELECT case when day(empleado_fecha_nacimiento)=day(getdate()) and month(empleado_fecha_nacimiento)=month(getdate()) then 1 else 0 end as f ";
	$ssql .= " From Empleados ";
	$ssql .= " WHERE empleado_codigo = ?";
	$rs = $cn->Execute($ssql, $params);
	if($rs->RecordCount() > 0){
		$rpta = $rs->fields[0];
	}else{
		$rpta="0";
	}
	return $rpta;
}

function historico_remuneracion(){
	$rpta="0";
	$cn = $this->getMyConexionADO();
    $params = array($this->empleado_codigo);
	$ssql = "SELECT Atributo_Valor, Estado_codigo, convert(varchar(10),Atributo_Fecha,103) + ' ' +  convert(varchar(10),Atributo_Fecha,108) ";
	$ssql .= " FROM Atributos(nolock) ";
	$ssql .= " WHERE (Item_Codigo = 153) " .
			"   AND (Empleado_Codigo = ?) ";
	$ssql .= " ORDER BY Estado_codigo, Atributo_Fecha DESC ";
	$rs = $cn->Execute($ssql, $params);
	while(!$rs->EOF){
		echo "\n<tr> ";
		echo "\n	<td class=DataTD width='60%' align=center>" . number_format($rs->fields[0],2) . "</td> ";
		echo "\n	<td class=DataTD>".$rs->fields[2]."</td> ";
		echo "\n</tr>";
        $rs->MoveNext();
	}
	return $rpta;
}

function historico_items($tabla){
	$rpta="0";
	$cn = $this->getMyConexionADO();
    $params = array(
                    $this->empleado_codigo,
                    $tabla
                );
	$ssql = "SELECT Atributos.Estado_codigo,
                    Items.Item_Descripcion,
                    convert(varchar(10),Atributos.Atributo_Fecha,103)  + ' ' + convert(varchar(10),Atributos.Atributo_Fecha,108),
                    atributos.Atributo_Valor                    
	         FROM Atributos(nolock) INNER JOIN
	               Items(nolock) ON Atributos.Item_Codigo = Items.Item_Codigo
	         WHERE (Atributos.Empleado_Codigo = ?) AND (Items.Tabla_Codigo = ?)
	         ORDER BY Atributos.Estado_codigo, Atributos.Atributo_Fecha DESC ";
	$rs = $cn->Execute($ssql, $params);
	while(!$rs->EOF){
		echo "\n<tr> ";
		echo "\n	<td class=DataTD width='40%' align=center>" . $rs->fields[1] . "</td> ";
		echo "\n	<td class=DataTD>".$rs->fields[2]."</td> ";
        echo "\n	<td class=DataTD>".$rs->fields[3]."</td> ";
		echo "\n</tr>";
        $rs->MoveNext();
	}
	return $rpta;
}



function Historial_Area_Empleado(){
 	$rpta="OK";
	$cn = $this->getMyConexionADO();
    //$cn->debug = true;
    $params = array($this->empleado_codigo);
	//convert(varchar(10),empleado_area.empleado_area_fecha,103) + ' ' + convert(varchar(10),empleado_area.empleado_area_fecha,108)
	$ssql="SELECT Empleado_Area.Area_Codigo,
                     Areas.Area_Descripcion,
                     convert(varchar(20),empleado_area.empleado_area_fecha,113),
                     empleado_area.empleado_area_fecha
            FROM Empleados 
            	INNER JOIN	Empleado_Area ON Empleados.Empleado_Codigo = Empleado_Area.Empleado_Codigo 
            	INNER JOIN	Areas ON Empleado_Area.Area_Codigo = Areas.Area_Codigo 
            WHERE Empleados.Empleado_Codigo = ?	
            order by 4 desc";
	$rs = $cn->Execute($ssql, $params);
	while(!$rs->EOF){
		echo "\n<tr> ";
		echo "\n	<td class=DataTD width='70%' align=center>" . $rs->fields[1] . "</td> ";
		echo "\n	<td class=DataTD>".$rs->fields[2]."</td> ";
		echo "\n</tr>";
        $rs->MoveNext();
	}
  return $rpta;
 }

function Historial_Uservicio_Empleado(){
  $rpta="OK";
  $cn = $this->getMyConexionADO();
  $params = array($this->empleado_codigo);
  $ssql="SELECT v.Cod_Campana, v.Exp_NombreCorto,";
  $ssql .=" convert(varchar(20),Empleado_Servicio.Empleado_Servicio_Fecha,113),";
  $ssql .=" Empleado_Servicio.Empleado_Servicio_Fecha ";
  $ssql .=" FROM Empleados INNER JOIN ";
  $ssql .=" Empleado_Servicio ON Empleados.Empleado_Codigo = Empleado_Servicio.Empleado_Codigo ";
  $ssql .=" INNER JOIN v_campanas v ON v.Cod_Campana = Empleado_Servicio.Cod_Campana ";
  $ssql .=" WHERE Empleados.Empleado_Codigo = ?  order by 4 desc";
  $rs = $cn->Execute($ssql, $params);
  while(!$rs->EOF){
    echo "\n<tr> ";
    echo "\n  <td class=DataTD width='70%' align=center>" . $rs->fields[1] . "</td> ";
    echo "\n  <td class=DataTD>".$rs->fields[2]."</td> ";
    echo "\n</tr>";
    $rs->MoveNext();
  }
  return $rpta;
 }


function Historial_Seguro_Empleado(){
 	$rpta="OK";
	$cn = $this->getMyConexionADO();
    $params = array($this->empleado_codigo);
	$ssql="SELECT  s.cuenta_seguro_codigo,
                i.item_descripcion,
                convert(varchar(10), s.cuenta_seguro_fecha_reg,103) + ' ' + convert(varchar(10), s.cuenta_seguro_fecha_reg,108),
    	        convert(varchar(10),s.eps_fecha_inicio,103),
                convert(varchar(10),s.eps_fecha_cierre,103), 
    	        eps.item_descripcion,
                p.item_descripcion,
                convert(varchar(10),s.fecha_plan_eps,103) 
    	 FROM cuentaseguros s 
            	 left join items i on s.seguro_tipo=i.item_codigo 
            	 left join items eps on s.eps_codigo=eps.item_codigo 
            	 left join items p on s.seguro_plan=p.item_codigo 
    	 WHERE s.Empleado_Codigo = ? 
         ORDER BY s.cuenta_seguro_fecha_reg desc";
	$rs = $cn->Execute($ssql, $params);
	while(!$rs->EOF){
		echo "\n<tr> ";
		echo "\n	<td class=DataTD width='5%' align=center>" . $rs->fields[1] . "</td> ";
		echo "\n	<td class=DataTD width='5%'>".$rs->fields[3]."</td> ";
		echo "\n	<td class=DataTD width='5%'>".$rs->fields[4]."</td> ";
		echo "\n	<td class=DataTD width='10%'>".$rs->fields[5]."</td> ";
		echo "\n	<td class=DataTD width='25%'>".$rs->fields[6]."</td> ";
		echo "\n	<td class=DataTD width='10%'>".$rs->fields[7]."</td> ";
		echo "\n	<td class=DataTD width='25%'>".$rs->fields[2]."</td> ";
		echo "\n</tr>";
        $rs->MoveNext();
	}

   return $rpta;
 }

 function Tabla_Simple_Empleados ($codigos,$movimiento = 0){
      $rpta="OK";
      $cn = $this->getMyConexionADO();
      //$cn->debug = true;
        $strl= "select empleados.empleado_codigo, 
                empleados.Empleado_dni, 
                Empleados.Empleado_apellido_paterno + ' ' + Empleados.empleado_apellido_materno + ' ' + Empleados.empleado_nombres as Empleado,
                vdatos.empleado_fecha_ingreso,
                vdatos.modalidad_codigo , 
                vdatos.modalidad_descripcion ,
                vdatos.cargo_codigo, 
                vdatos.cargo_descripcion ,
                ( SELECT ITEMS.ITEM_DESCRIPCION FROM  Atributos INNER JOIN Items ON Atributos.Item_Codigo = Items.Item_Codigo  WHERE (Atributos.Empleado_Codigo =empleados.Empleado_Codigo) AND (Atributos.Estado_codigo = 1) AND (Items.Tabla_Codigo = 10))
            FROM empleados
                inner join vdatos on vdatos.empleado_codigo= empleados.empleado_codigo
            where Empleados.Empleado_Codigo in (" . $codigos . ") ";
        if($movimiento == 39 || $movimiento == 6){
            $strl .= " AND vdatos.Empleado_sexo = 'F'";
        }
        
      $rs = $cn->Execute($strl );
      if( $rs->RecordCount()>0){
         
            echo "<br><br><center class=CabeceraTabla>Empleados Seleccionados</center><br />";
            echo "\n<table align=center class=table bgcolor=" . bgTitulos() . "> ";
            echo "\n<tr class='Cabecera'>";
            echo "\n<td align=center class=CabeceraTabla>DNI</td>";
    		echo "\n<td align=center class=CabeceraTabla>Empleado</td>";
    		echo "\n<td align=center class=CabeceraTabla>Fecha Ingreso</td>";
    		echo "\n<td align=center class=CabeceraTabla>Modalidad</td>";
    		echo "\n<td align=center class=CabeceraTabla>Cargo</td>";
    		echo "\n<td align=center class=CabeceraTabla>Horario</td>";
    		echo "\n</tr>";
		while(!$rs->EOF){
    		echo "\n<tr style='height:25px' bgcolor=#ecf9ff onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#ffebd7'>";
    		echo "\n<td class=CeldaTabla>".$rs->fields[1]."</td>";
    		echo "\n<td class=CeldaTabla>".$rs->fields[2]."</td>";
    		echo "\n<td align=center class=CeldaTabla>".$rs->fields[3]."</td>";
    		echo "\n<td class=CeldaTabla>".$rs->fields[5]."</td>";
    		echo "\n<td class=CeldaTabla>".$rs->fields[7]."</td>";
    		echo "\n<td class=CeldaTabla>".$rs->fields[8]."</td>";
    		echo "\n</tr>";
            $rs->MoveNext();
		}
		echo "\n</table>";
    }
	return $rpta;

}




 function Obtener_mail() {
  $rpta="OK";
  $cn = $this->getMyConexionADO();
  $strl= "select empleados.empleado_email " ;
  $strl .= " FROM  from empleados";
  $strl .= " inner join especiales on especiales.empleado_codigo= empleados.empleado_codigo";
  $strl .= " inner join areas on areas.empleado_responsable=especiales.empleado_codigo";
  $strl .= " where especiales.especial_tipo=215  and especiales.especial_activo=1";
  $rs = $cn->Execute($strl);
	if($rs->RecordCount()>0){
		$this->empleado_email=$rs->fields[0];
		$rpta = "OK";
	}
 	return $rpta;
 }

 function Historial_Banco_Empleado(){
 	$rpta="OK";
	$cn = $this->getMyConexionADO();
    $params = array($this->empleado_codigo);
	$ssql="SELECT m.descripcion,
                  bd.item_descripcion,
                  tc.item_descripcion,
                  convert(varchar(10),b.fecha_inicio_cuenta,103), 
	              mo.moneda_simbolo,
                  b.cuenta_banco_numero,
                  convert(varchar(10),b.fecha_fin_cuenta,103),
                  convert(varchar(10),cuenta_banco_fecha_reg,103) + ' ' + convert(varchar(10),cuenta_banco_fecha_reg,108), 
                  b.Banco_cci 
    	 FROM cuenta_banco b 
        	 inner join empleados c (nolock) on b.empleado_codigo=c.empleado_codigo 
        	 left join modalidad_pago_trabajador m on c.modalidad_pago=m.modalidad_pago 
        	 left join items bd (nolock) on b.banco_codigo=bd.item_codigo 
        	 left join items tc (nolock) on b.tipo_cuenta_codigo=tc.item_codigo 
        	 left join monedas mo on b.moneda_codigo=mo.moneda_codigo 
     	 WHERE b.Empleado_Codigo = ?
    	 ORDER BY b.cuenta_banco_fecha_reg desc ";
	$rs = $cn->Execute($ssql, $params);
	while(!$rs->EOF){
		echo "\n<tr> ";
		echo "\n	<td class=DataTD >" . $rs->fields[1] . "</td> ";
		echo "\n	<td class=DataTD >".$rs->fields[2]."</td> ";
		echo "\n	<td class=DataTD align=center>".$rs->fields[4]."</td> ";
		echo "\n	<td class=DataTD >".$rs->fields[5]."</td> ";
		echo "\n	<td class=DataTD align=center>".$rs->fields[3]."</td> ";
		echo "\n	<td class=DataTD align=center>".$rs->fields[6]."</td> ";
		echo "\n	<td class=DataTD >".$rs->fields[7]."</td> ";
        echo "\n	<td class=DataTD >".$rs->fields[8]."</td> ";
		echo "\n</tr>";
        $rs->MoveNext();
	}
   return $rpta;
 }


function Historial_CTS_Empleado(){
 	$rpta="OK";
	$cn = $this->getMyConexionADO();
    $params = array($this->empleado_codigo);
	$ssql="SELECT b.item_descripcion,
                 t.item_descripcion,
                 convert(varchar(10),cts.Fecha_Inicio_CTS,103),
        	     m.Moneda_simbolo,
                 cuenta_cts_numero,
                 convert(varchar(10),cts.Fecha_Fin_CTS,103),
                 convert(varchar(10),cuenta_cts_fecha_reg, 103 ) + ' ' + convert(varchar(10),cuenta_cts_fecha_reg, 108) ,
                 cts.Cuenta_cci 
        	from cuenta_cts cts 
        	left join items b (nolock) on cts.banco_codigo=b.item_codigo 
        	left join items t (nolock) on cts.tipo_cuenta_codigo=t.item_codigo 
        	left join monedas m on cts.moneda_codigo=m.moneda_codigo 
        	 WHERE cts.Empleado_Codigo = ?
        	 order by cts.cuenta_cts_fecha_reg desc ";
	$rs = $cn->Execute($ssql, $params);
	while(!$rs->EOF){
		echo "\n<tr> ";
		echo "\n	<td class=DataTD >" . $rs->fields[0] . "</td> ";
		echo "\n	<td class=DataTD align=center>" . $rs->fields[1] . "</td> ";
		echo "\n	<td class=DataTD align=center>".$rs->fields[3]."</td> ";
		echo "\n	<td class=DataTD >".$rs->fields[4]."</td> ";
		echo "\n	<td class=DataTD align=center>".$rs->fields[2]."</td> ";
		echo "\n	<td class=DataTD align=center>".$rs->fields[5]."</td> ";
		echo "\n	<td class=DataTD >".$rs->fields[6]."</td> ";
        echo "\n	<td class=DataTD >".$rs->fields[7]."</td> ";
		echo "\n</tr>";
        $rs->MoveNext();
	}

   return $rpta;
 }


function lista_marcas($usuario){
	$cadena='';
	$cn = $this->getMyConexionADO();
	$params = array($usuario);
	$ssql = " select i.item_descripcion,i.item_codigo,isnull(a.atributo_codigo,0) as atributo_codigo, ";
	$ssql.= " a.atributo_valor ";
	$ssql.= " from items i left join atributos a on i.item_codigo=a.item_codigo and a.empleado_codigo= ?";
	$ssql.= " and a.estado_codigo=1 ";
	$ssql.= " where tabla_codigo=54 --17 --54";

   	$rs= $cn->Execute($ssql, $params);
   	$padre = array();
	while(!$rs->EOF){
		$hijo = array();
		$hijo["item_descripcion"] = $rs->fields[0];
		$hijo["item_codigo"]      = $rs->fields[1];
		$hijo["atributo_codigo"]  = $rs->fields[2];
		$hijo["atributo_valor"]   = $rs->fields[3];
		array_push($padre,$hijo);
        $rs->MoveNext();
	}
	return $padre;
}

function Actualizar_Atributo($cod_empleado, $atributo_codigo, $item_codigo, $valor_campo, $codigo_usuario){
	$rpta=''; $ssql='';
	$cn = $this->getMyConexionADO();
    $params = array(
                    $cod_empleado,
                    $atributo_codigo,
                    $item_codigo,
                    $valor_campo,
                    $codigo_usuario
                );
	$ssql = " exec spAgregar_Atributo_Empleado ?,?,?,?,?";
	$rs = $cn->Execute($ssql, $params);
    if($rs->fields[0]>0){
		$this->myAtributo_Codigo = $rs->fields[0];
	}
	$rpta = $this->myAtributo_Codigo;
	switch ($rpta){
		case '0': echo "Error al Insertar nuevo item " . $Codigo_Campo;
		break;
	case '-1': echo "Error al Modificar item " . $Codigo_Campo;
		break;
	case '-2': echo "Error al Agregar item " . $Codigo_Campo;
		break;
	}
	return $rpta;
}

function Actualizar_tc_codigo(){
	$rpta='OK';
	$cn = $this->getMyConexionADO();
    $params = array($this->empleado_codigo);
	$ssql = "update Empleados ";
	$ssql .=" set tc_codigo= null";
    $ssql .=" where empleado_codigo = ?";
   //echo $ssql;
   $rs= $cn->Execute($ssql, $params);
	return $rpta;
}
function EmpleadoRegistro($codEmp){
    $cn = $this->getMyConexionADO();
    $cn->SetFetchMode(ADODB_FETCH_ASSOC);
    $params = array($codEmp);
    $sSql = "SELECT Areas.Area_Jefe, Empleado_Area.Area_Codigo,
                 Empleado_Area.Empleado_Codigo,
                 Empleados.Empleado_Responsable_Area,
                 Empleado_Area.Empleado_Area_Activo, Areas.Area_Activo,
                 Empleados.Empleado_activo, Empleados.Empleado_Carnet,
                 Empleados.Empleado_Jefe, Empleados.Usuario_Id,
                 Empleados.Ccto_Codigo, Empleados.Ccr_Codigo,
                 Empleados.Ccb_Codigo,
                 Empleados.Empleado_Apellido_Paterno,
                 Empleados.Empleado_Apellido_Materno,
                 Empleados.Empleado_Nombres,
                 convert(varchar(10), Empleados.Empleado_Fecha_Nacimiento, 103) as Empleado_Fecha_Nacimiento,
                 Empleados.Empleado_Nombre_Via,
                 Empleados.Empleado_Nro,
                 Empleados.Empleado_Pais_Nacimiento,
                 Empleados.Empleado_Dpto_Nacimiento,
                 Empleados.Empleado_Prov_Nacimiento,
                 Empleados.Empleado_Dist_Nacimiento,
                 Empleados.Empleado_Dpto_Residencia,
                 Empleados.Empleado_Prov_Residencia,
                 Empleados.Empleado_Dist_Residencia,
                 Empleados.Empleado_sexo, Empleados.Empleado_Tlf,
                 Empleados.Empleado_Tlf_Referencia,
                 Empleados.Empleado_Preguntar_Por,
                 Empleados.Empleado_Celular, Empleados.Empleado_Email,
                 Empleados.Empleado_Estado_Civil,
                 Empleados.Empleado_Dni, Empleados.Empleado_Ruc,
                 Empleados.Empleado_Lib_Militar,
                 Empleados.Empleado_Num_Seguro,
                 Empleados.Empleado_Nivel, Empleados.Empleado_trasvase,
                 Empleados.Empleado_Foto, Empleados.Postulante_Codigo,
                 Empleados.Estado_Codigo
             FROM Empleado_Area INNER JOIN
                 Empleados ON
                 Empleado_Area.Empleado_Codigo = Empleados.Empleado_Codigo
                 Inner Join
                 Areas ON
                 Empleado_Area.Area_Codigo = Areas.Area_Codigo
             WHERE (Empleado_Area.Empleado_Codigo = ?) AND
             (Empleado_Area.Empleado_Area_Activo = 1) AND
             (Areas.Area_Activo = 1) AND
             (Empleados.Empleado_activo = 1)";
    $rs = $cn->Execute($sSql, $params);
    if($rs == false){
        return false;
    }
    else{
        $data = $rs->fields;
        return $data;
    }

}
//Proceso que devuelve el codigo del empleado pero que sea responsable
function Empleado($id_usuario){
    $cn = $this->getMyConexionADO();
    $params = array($id_usuario); 
    $sql = "SELECT Empleado_Codigo,Usuario_Id 
            FROM Empleados 
            WHERE Usuario_Id=? AND  empleado_responsable_area=1 ";
    $rs = $cn->Execute($sql, $params);    
    if($rs->RecordCount() > 0)
        return $rs->fields[0];
    else
        return "";
}
//Proceso que devuelve el codigo del empleado especial que no es responsable
function Emp_Especial($id_usuario){
    $cn = $this->getMyConexionADO();
    $params = array($id_usuario);        
    $sql = "SELECT Empleado_Codigo,Usuario_Id 
            FROM Empleados 
            WHERE Usuario_Id= ? ";
    $rs = $cn->Execute($sql, $params);
    if($rs->RecordCount() > 0)
        return $rs->fields[0];
    else
        return "";

}

function consulta_sql($sql){
    $cn = $this->getMyConexionADO();
    $rs = $cn->Execute($sql);
    if($rs->RecordCount() > 0)
        return $rs->fields;
    else
        return "";
}


//ultimas funciones ficha remuneracion
function registrar_nro_sueldos(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
    $params = array(
                    $this->nro_sueldos,
                    $this->empleado_codigo
                );
	$ssql = " UPDATE empleado_indicador SET nro_sueldos = ?
              WHERE Empleado_Codigo = ?";
	//echo $ssql;
	$rs= $cn->Execute($ssql, $params);
 	return $rpta;
}

function registrar_srv(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
  // $cn->debug=true;
    $params = array(
                    $this->aplica_srv,
                    $this->empleado_codigo
                );
	$ssql = " UPDATE empleado_indicador SET aplica_srv= " . $this->aplica_srv;
    $ssql .= "  WHERE Empleado_Codigo = " . $this->empleado_codigo;
	$rs= $cn->Execute($ssql);
 	return $rpta;
}

function registrar_mr(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
  // $cn->debug = true;
    $params = array(
                    $this->aplica_mr,
                    $this->empleado_codigo
                );
  // print_r()
	$ssql = " UPDATE empleado_indicador SET aplica_mr = " . $this->aplica_mr;
    $ssql .= "  WHERE Empleado_Codigo = " . $this->empleado_codigo;
	$rs= $cn->Execute($ssql);
 	return $rpta;
}

/*******************************************/
function leer_nro_sueldos(){
 	$rpta="OK";
	$cn = $this->getMyConexionADO();
	//$cn->debug=true;
    $params = array($this->empleado_codigo );
	$ssql = "select nro_sueldos From empleado_indicador ";
    $ssql .= " where empleado_codigo= " . $this->empleado_codigo;
	$rs= $cn->Execute($ssql);
	if( $rs->RecordCount() >0){
		$this->nro_sueldos =$rs->fields[0];
	}
 	return $this->nro_sueldos;
}

function leer_srv(){
 	$rpta="OK";
	$cn = $this->getMyConexionADO();
    $params = array($this->empleado_codigo);
	$ssql = "select aplica_srv From empleado_indicador ";
    $ssql .= " where empleado_codigo= " . $this->empleado_codigo;
	$rs= $cn->Execute($ssql);
	if($rs->RecordCount() >0){
		$this->aplica_srv=$rs->fields[0];
	}
 	return $this->aplica_srv;
}

function leer_mr(){
 	$rpta="OK";
	$cn = $this->getMyConexionADO();
    $params = array($this->empleado_codigo);
	$ssql = "select aplica_mr From empleado_indicador ";
    $ssql .= " where empleado_codigo= " . $this->empleado_codigo;
    $rs = $cn->Execute($ssql);
	if( $rs->RecordCount()>0){
		$this->aplica_mr=$rs->fields[0];
	}
 	return $this->aplica_mr;
}
/*******************************************/
    
    /*************************************************/
    /*Creado 22/09/2011                              */
    /*Autor: Banny Solano                            */
    /*Reemplazar al componente controlesActivos      */ 
    /*************************************************/
    function ControlesActivos($movimiento){
        $cn = $this->getMyConexionADO();
        //$cn->debug=true;
        $cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $params = array($movimiento);
        $sql = "SELECT * FROM Controles_Movimiento WHERE Movimiento_Codigo= ?";
        if($rs= $cn->Execute($sql, $params))
            return $rs;
        else
            echo "error al ejecutar la consulta(Metodo: ControlesActivos)";
    }
    
    /**************************************************/
    /*Creado 28/09/2011                               */
    /*Autor: Banny Solano                             */
    /*Reemplazar al método consulta                   */ 
    /**************************************************/
    function Consulta(){
        $cn = $this->getMyConexionADO();
        //$cn->debug=false;
        
        //$cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $params = array($this->empleado_codigo);
        $ssql = "SELECT *, isnull(Postulante_Codigo, 0) as Post_Codigo 
                 FROM vw_Datos_Empleados 
                 WHERE Empleado_Codigo = ?";
        $rs = $cn->Execute($ssql, $params);    
        if($rs == false){
            echo "Error de consulta de registros";
        }else{
            return $rs->fields;
        }    
    }
    /**************************************************/
    /*Creado 29/09/2011                               */
    /*Autor: Banny Solano                             */
    /*Devuelve el código de la unidad de serv.        */ 
    /**************************************************/
    function Servicio_Codigo(){
        $cn = $this->getMyConexionADO();
        if($this->empleado_codigo == "") 
            return "";    
        
        $params = array($this->empleado_codigo);
        $sSql = "SELECT Cod_Campana, Exp_NombreCorto 
                 FROM  vw_Empleado_Servicio
                 WHERE (Empleado_Servicio_Activo = 1) AND (Empleado_Codigo = ?)";
        $rs = $cn->Execute($sSql, $params);         
        if($rs == false){
            echo "Error en la consulta";
        }else{                 
            if($rs->RecordCount() == 0)         
                return  "";
            else
                return $rs->fields[0];
        }

    }
    /*************************************************/
    /*Creado 29/09/2011                              */
    /*Autor: Banny Solano                            */
    /*Devuelve la descripcion de la unidad de serv.  */ 
    /*************************************************/
    function Servicio_Descripcion(){
        $cn = $this->getMyConexionADO();
        if($this->empleado_codigo == "") 
            return "";    
        
        $params = array($this->empleado_codigo);
        $sSql = "SELECT Cod_Campana, Exp_NombreCorto 
                 FROM  vw_Empleado_Servicio
                 WHERE (Empleado_Servicio_Activo = 1) AND (Empleado_Codigo = ?)";
        $rs = $cn->Execute($sSql, $params);         
        if($rs == false){
            echo "Error en la consulta";
        }else{                 
            if($rs->RecordCount() == 0)         
                return  "";
            else
                return $rs->fields[1];
        }

    }
    /*************************************************/
    /*Creado 29/09/2011                              */
    /*Autor: Banny Solano                            */
    /*Devuelve la descripcion de la Provincia.       */ 
    /*************************************************/
    function Provincia($sDpto, $sProv){
            $cn = $this->getMyConexionADO();
            
            if(Trim($sDpto)=="") return "";
            if(Trim($sProv)=="") return "";
            $params = array(
                            $sDpto,
                            $sProv
                        );    
            $ssql = "SELECT Nombre 
                     FROM Ubigeo 
                     WHERE coddpto = ? and codprov = ?";
            $rs = $cn->Execute($ssql, $params);
            if($rs == false){
                echo "error en la consulta";
            }else{
                
                if($rs->RecordCount() > 0)
                    return $rs->fields[0];
                else
                    return "";
            }
    }
    /*************************************************/
    /*Creado 29/09/2011                              */
    /*Autor: Banny Solano                            */
    /*Devuelve la descripcion del Distrito.          */ 
    /*************************************************/
    function Distrito($sDpto, $sProv,$sDist){
        $cn = $this->getMyConexionADO();
         
        if(trim($sDpto)=="") return "";
        if(trim($sProv)=="") return "";
        if(trim($sDist)=="") return "";
        $params = array(
                        $sDpto, 
                        $sProv,
                        $sDist);
        $ssql = "SELECT Nombre 
                 FROM Ubigeo 
                 WHERE coddpto = ? and codprov = ? and coddist = ?";
        $rs = $cn->Execute($ssql, $params);
        if($rs == false){
                echo "error en la consulta";
        }else{
                
                if($rs->RecordCount() > 0)
                    return $rs->fields[0];
                else
                    return "";
        }
    }
    
    function Reporte_Cumpleanos($inicio,$final){
        $ssql='';
        $rpta='OK';
        $cn = $this->getMyConexionADO();
        $ssql="select empleado_codigo,empleado,empleado_dni,fecha_nacimiento,empleado_email,cargo_descripcion,
          modalidad_descripcion,area_descripcion,empleado_fecha_ingreso
          from vdatostotal 
          where fecha_nacimiento is not null and 
          convert(datetime, case when left(fecha_nacimiento,6)='29/02/' then 
          case when year(convert(datetime,'" . $inicio . "',103))%4=0 then left(fecha_nacimiento,6) else '28/02/' end else  
          left(fecha_nacimiento,6) end +cast(year(convert(datetime,'" . $inicio . "',103)) as char), 103) 
          between convert(datetime,'" . $inicio . "',103) and convert(datetime,'" . $final . "',103)
          order by empleado "; 
        $texto="";
        $texto.="<table align='center' border='0' class='TABLE' cellpadding='0' cellspacing='1' width='100%'>";
        $texto.="	<tr class=Cabecera align='center'><td class='textonegrita' colspan=9>REPORTE DE CUMPLEAÑOS desde el " . $inicio . " al " . $final ."</td></tr>";
        $texto.="	<tr class=Cabecera align='center'>";
        $texto.="		<td  width='20px'><b>Nro</b></td>";
        $texto.="		<td  width='20px'><b>Empleado</b></td>";
        $texto.="		<td  width='20px'><b>DNI</b></td>";
        $texto.="		<td  width='20px'><b>Nacimiento</b></td>";
        $texto.="		<td  width='20px'><b>Email</b></td>";
        $texto.="		<td  width='120px'><b>Cargo</b></td>";
        $texto.="		<td  width='120px'><b>Modalidad</b></td>";
        $texto.="		<td  width='120px'><b>Area</b></td>";
        $texto.="		<td  width='50px'><b>Ingreso</b></td>";
        $texto.="	</tr>";
        //echo $ssql;
        $rs= $cn->Execute($ssql);
        if( $rs->RecordCount()==0){
            $texto.="	<tr class=Cabecera align='center'>";
            $texto.="		<td  colspan=9 width='20px'>No existen datos para la fecha solicitada</td>";
            $texto.="	</tr>";
        }
        $i=0;
        while(!$rs->EOF){
            $i++;
            $texto.="	<tr class=texto>";
            $texto.="		<td>".$i."</td>";
            $texto.="		<td>".$rs->fields[1]."</td>";
            $texto.="		<td>".$rs->fields[2]."</td>";
            $texto.="		<td>".$rs->fields[3]."</td>";
            $texto.="		<td>".$rs->fields[4]."</td>";
            $texto.="		<td>".$rs->fields[5]."</td>";
            $texto.="		<td>".$rs->fields[6]."</td>" ;
            $texto.="		<td>".$rs->fields[7]."</td>";
            $texto.="		<td>".$rs->fields[8]."</td>";
            $texto.="	</tr>";
            $rs->MoveNext();
        }
        $texto.="</table>";
        return $texto;
    }
    
    function obtener_cco_cbe_campana(){
        $cn = $this->getMyConexionADO();
        $params = array($this->empleado_codigo);
        $sql = "SELECT ccto_codigo, ccb_codigo,Cod_Campana, Exp_NombreCorto 
                FROM vdatos 
                WHERE empleado_codigo = ?";
        $rs = $cn->Execute($sql, $params);
        if($rs->RecordCount() > 0){
           $this->Ccto_Codigo  = $rs->fields[0];
	       $this->Ccb_Codigo   = $rs->fields[1];
           $this->Cod_Campana  = $rs->fields[2];
           $this->Exp_NombreCorto = $rs->fields[3];
        }
        
    }
     /************************************************/
    /*Creado 09/04/2012                              */
    /*Autor: Banny Solano                            */
    /*Reemplazar al componente Consulta_Familiar     */ 
    /*************************************************/
    function Consulta_Familiar($Tipo_Familiar){
        $cn = $this->getMyConexionADO();
        $cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $params = array($this->empleado_codigo, $Tipo_Familiar);
        $sql = " SELECT Empleado_Codigo,
                    Familiar_codigo,
                    Tipo_Familiar_Codigo, 
                    ABS(Familiar_Vive) AS Familiar_Vive,
                    Familiar_Apellido_Paterno,
                    Familiar_Apellido_Materno, 
                    Familiar_Nombre,
                    isnull(Ocupacion_Codigo,0) as Ocupacion_Codigo,
                    CONVERT(varchar(10),
                    Familiar_Fecha_Nacimiento, 103) AS Familiar_Fecha_Nacimiento, 
                    ABS(Familiar_Seguro_Medico) AS Familiar_Seguro_Medico, ABS(Familiar_Trabaja) AS Familiar_Trabaja 
                FROM Familiares 
                WHERE Empleado_Codigo = ? and tipo_familiar_codigo = ?";
        $rs = $cn->Execute($sql, $params);
        $data = array();
        if($rs->RecordCount() > 0){
            if($rs->RecordCount() == 1)
                $data = $rs->fields;
            else{
                while(!$rs->EOF){
                    $data[] = $rs->fields;
                    $rs->MoveNext();
                }
            }
        }
        return $data;
    }
     /************************************************/
    /*Creado 09/04/2012                              */
    /*Autor: Banny Solano                            */
    /*Extension de Consulta_Familiar, los resultados */
    /*muestran una matriz con los hijos              */ 
    /*************************************************/
    function Consulta_Familiar_Hijos($Tipo_Familiar){
        $cn = $this->getMyConexionADO();
        $cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $params = array($this->empleado_codigo, $Tipo_Familiar);
        $sql = " SELECT Empleado_Codigo,
                    Familiar_codigo,
                    Tipo_Familiar_Codigo, 
                    ABS(Familiar_Vive) AS Familiar_Vive,
                    Familiar_Apellido_Paterno,
                    Familiar_Apellido_Materno, 
                    Familiar_Nombre,
                    isnull(Ocupacion_Codigo,0) as Ocupacion_Codigo,
                    CONVERT(varchar(10),
                    Familiar_Fecha_Nacimiento, 103) AS Familiar_Fecha_Nacimiento, 
                    ABS(Familiar_Seguro_Medico) AS Familiar_Seguro_Medico, ABS(Familiar_Trabaja) AS Familiar_Trabaja 
                FROM Familiares 
                WHERE Empleado_Codigo = ? and tipo_familiar_codigo = ?";
        $rs = $cn->Execute($sql, $params);
        $data = array();
        if($rs->RecordCount() > 0){
            while(!$rs->EOF){ //aqui esta la diferencia con el metodo anterior
                $data[] = $rs->fields;
                $rs->MoveNext();
            }
        }
        return $data;
    }
     /************************************************/
    /*Creado 09/04/2012                              */
    /*Autor: Banny Solano                            */
    /*Reemplazar al componente Consulta_Parientes     */ 
    /*************************************************/
    function Consulta_Parientes(){
        $cn = $this->getMyConexionADO();
        $params = array($this->empleado_codigo);
        $sSql = "SELECT Parientes_Empresa.Pariente_Codigo,
                        Parientes_Empresa.Pariente_Empleado_codigo,
                        Parientes_Empresa.Tipo_Familiar_Codigo,  
                        Empleados.Empleado_Apellido_Paterno + ' ' +  Empleados.Empleado_Apellido_Materno + ' ' +  Empleados.Empleado_Nombres as Pariente 
                FROM Parientes_Empresa INNER JOIN Empleados ON  Parientes_Empresa.Pariente_Empleado_codigo = Empleados.Empleado_Codigo
                WHERE Parientes_Empresa.Empleado_codigo = ?";
        $rs = $cn->Execute($sSql, $params);
        $data = array();
        if($rs->RecordCount() > 0){
            if($rs->RecordCount() == 1)
                $data = $rs->fields;
            else{
                while(!$rs->EOF){
                    $data[] = $rs->fields;
                    $rs->MoveNext();
                }
            }
        }
        return $data;
    }
     /************************************************/
    /*Creado 09/04/2012                              */
    /*Autor: Banny Solano                            */
    /*Reemplazar al componente Pariente_Area         */ 
    /*************************************************/
    function Pariente_Area($Pariente_Empleado_Codigo){
        $cn = $this->getMyConexionADO();
        $Pariente_Area = "";
        $cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $params = array($Pariente_Empleado_Codigo);
        $sSql = "SELECT Empleados.Empleado_Codigo,
                        Empleado_Area.Area_Codigo ,
                        Areas.Area_Descripcion 
                FROM Areas INNER JOIN Empleado_Area ON 
                    Areas.Area_Codigo = Empleado_Area.Area_Codigo INNER JOIN  Empleados ON 
                    Empleado_Area.Empleado_Codigo = Empleados.Empleado_Codigo 
                WHERE (Empleado_Area.Empleado_Area_Activo = 1) and Empleado_Area.empleado_codigo = ?";
        $rs = $cn->Execute($sSql, $params);
        if($rs->RecordCount() > 0)
            $Pariente_Area = $rs->fields["Area_Descripcion"];
        else
            $Pariente_Area = "";
        
        return $Pariente_Area;   
    }
     /************************************************/
    /*Creado 09/04/2012                              */
    /*Autor: Banny Solano                            */
    /*Reemplazar al componente ObtenerValorItem      */ 
    /*************************************************/
    function ObtenerValorItem($CodigoItem){
        $cn = $this->getMyConexionADO();
        $cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $params = array(
                        $this->empleado_codigo,
                        $CodigoItem
                    );
        $sSql = "Select * 
                from Atributos 
                where empleado_codigo = ? and item_codigo = ? and estado_codigo = 1";
                
        $rs = $cn->Execute($sSql, $params);
        
        if($rs->RecordCount() > 0)
            $retorno = $rs->fields["Atributo_Valor"];
        else
            $retorno = "0";
        
        return $retorno;
    }
    /************************************************/
    /*Creado 13/11/2012                              */
    /*Autor: Banny Solano                            */
    /*Reemplazar Anular el codigo de combinacion de
    la tala empleados                                 */ 
    /*************************************************/
    function Anular_Turno_Combinacion($empleado_anular){
        $cn = $this->getMyConexionADO();
        //$cn->debug=true;
        $cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $params = array(
                        $empleado_anular
                    );
        $sSql = "UPDATE empleados SET tc_codigo = NULL 
                WHERE Empleado_Codigo = ?";
                
        $rs = $cn->Execute($sSql, $params);
        if(!$rs === false)
            return "OK";
        else
            return "Ocurrió un Error al Anular";
    }
	
	
    /************************************************/
    /*Creado 13/11/2012                              */
    /*Autor: Banny Solano                            */
    /*Reemplazar Registrar y Agrega Histórico a la 
        dirección de transporte del empleado         */ 
    /*************************************************/
    function Actualizar_Direccion_Transporte($codigo_via,$ip_registro){
                            
        $cn = $this->getMyConexionADO();
        //$cn->debug = true;

        $params = array($this->empleado_codigo);
        $sql = "SELECT Emp_Tran_codigo 
                FROM Empleado_Transporte 
                WHERE Empleado_Codigo = ?";
        $rs = $cn->Execute($sql, $params);
        
        
        if($rs->RecordCount() > 0){
            if ($this->distrito_residencia!=''){ //-- ubigeo residencia
                $departamento   = substr($this->distrito_residencia,0,2);
                $provincia      = substr($this->distrito_residencia,2,2);
                $distrito       = substr($this->distrito_residencia,4,2);
            } 
            $params = array(
                            $this->empleado_codigo);
            $sql = "UPDATE Empleado_Transporte SET
                        Estado_codigo = 2
                    WHERE  Empleado_Codigo = ?";
            $rs = $cn->Execute($sql, $params);
            $rpta = $this->Insertar_Direccion_Transporte($codigo_via,$ip_registro);
        }else{
            $rpta =  $this->Insertar_Direccion_Transporte($codigo_via,$ip_registro);
        }
        
        return $rpta;
    }
    
    function Insertar_Direccion_Transporte($codigo_via,$ip_registro){
        $cn = $this->getMyConexionADO();
        //$cn->debug = true;
        $ssql = "SELECT isnull(max(Emp_Tran_codigo), 0)+1 FROM Empleado_Transporte(nolock)";
    	$rs = $cn->Execute($ssql);
   		$Emp_Tran_codigo = $rs->fields[0];
        if ($this->distrito_residencia!=''){ //-- ubigeo residencia
            $departamento   = substr($this->distrito_residencia,0,2);
            $provincia      = substr($this->distrito_residencia,2,2);
            $distrito       = substr($this->distrito_residencia,4,2);
        } 
        $params = array(
                        $Emp_Tran_codigo,
                        $this->empleado_codigo,
                        $codigo_via,
                        $this->empleado_nombre_via,
                        $this->empleado_nro,
                        $this->cod_zona,
                        $this->nombre_zona,
                        $departamento,
                        $provincia,
                        $distrito,
                        $this->referencia_direccion,
                        $this->empleado_tlf,
                        $this->empleado_celular,
                        $this->usuario_id,
                        $ip_registro
                    );
        $sSql = "INSERT INTO Empleado_Transporte(
                    Emp_Tran_codigo,
                    Empleado_Codigo,
                    Estado_codigo,
                    codigo_via,
                    Nombre_Via,
                    Nro,
                    codigo_zona,
                    nombre_zona,
                    Dpto_Transporte,
                    Prov_Transporte,
                    Dist_Transporte,
                    referencia,
                    telefono,
                    celular,
                    usuario_registro,
                    ip_registro)
                VALUES (?,?,1,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                
        $rs = $cn->Execute($sSql, $params);
        if(!$rs === false)
            return "Ocurrió un Error al Insertar";
        else
            return "OK";
    }
      
    function Consultar_Direccion_Transporte(){
        $cn = $this->getMyConexionADO();
        $cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $params = array($this->empleado_codigo);
        $sql = "SELECT Emp_Tran_codigo,
                    Empleado_Codigo,
                    Estado_codigo,
                    codigo_via,
                    Nombre_Via,
                    Nro,
                    codigo_zona,
                    nombre_zona,
                    Dpto_Transporte,
                    Prov_Transporte,
                    Dist_Transporte,
                    referencia,
                    telefono,
                    celular
                FROM Empleado_Transporte 
                WHERE Empleado_Codigo = ? AND Estado_Codigo = 1";
        $rs = $cn->Execute($sql, $params);
        if($rs->RecordCount() > 0){
            return $rs;
        }else{
            return array();
        }
        
    }
    
    function Actualizar_Extension($empleados_actualizar,$estado){
        $cn = $this->getMyConexionADO();
        // $cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $ssql = "select modalidad_codigo, Empleado_Codigo from vDatos where Empleado_Codigo in (".$empleados_actualizar.")";
        $rs_q = $cn->Execute($ssql);
        if($rs_q->RecordCount() > 0){
          while(!$rs_q->EOF){
            if ($estado == 0) {
               $params = array($estado,null);
            }else{
              if ($rs_q->fields[0] == 77) {
                $params = array($estado,1);
              }else{              
                $params = array($estado,$this->empleado_tipo_pago);
              }
            }
            $sSql = "UPDATE empleados SET turno_extendido = ? ,tipo_extension_codigo = ? 
                    WHERE Empleado_Codigo =".$rs_q->fields[1];
            $rs = $cn->Execute($sSql, $params);
            
            $rs_q->MoveNext();
          }

          return "OK";
        }
        else
            return "Ocurrió un Error al Anular";
    }



    function Horas_Extras_Periodo_Nomina($mes, $anio){
        $cn = $this->getMyConexionADO();
        //$cn->debug=true;
        $cn->SetFetchMode(ADODB_FETCH_ASSOC);
        // if (!$mes) return false; 
        // if(!$anio) return false;
        $params = array($mes, $anio);
        $sql = "SELECT * FROM ca_proceso_horas 
                WHERE Mes_Periodo = ? AND Anio_Periodo = ?";
        $rs = $cn->Execute($sql, $params);
        if($rs->RecordCount() > 0){
            return true;
        }else{
            return false;
        }

    }


    function Resultado_Horas_Extra_Periodo($mes, $anio, $empleado_codigo){
        $cn = $this->getMyConexionADO();
        // $cn->debug=true;
        $cn->SetFetchMode(ADODB_FETCH_ASSOC);
        // if (!$mes) return false; 
        // if(!$anio) return false;

        $params = array((int)$mes, (int)$anio, (int)$empleado_codigo);
        $ssql = "exec spReporte_Horas_Extra_Empleado ?,?,?";
        $rs = $cn->Execute($ssql, $params);
        $cn->Close();
        if($rs->RecordCount() > 0){
            return $rs->fields;
        }else{
            return array();
        }
    }
    
    function Detalle_Horas_Extras($emp_codigo,$fecha_inicio,$fecha_final,$mes,$anio,$tipo){
        $cn = $this->getMyConexionADO();
        if($cn){
            $params = array($emp_codigo,$fecha_inicio,$fecha_final,(int)$mes,(int)$anio,$tipo);
            $ssql = " exec spDetalle_Horas_Extras ?,?,?,?,?,? ";
            $rs = $cn->Execute($ssql, $params);
            $padre = array();
            $i=0;
            while(!$rs->EOF){
                $hijo=array();
                $hijo["empleado"] = $rs->fields[0];
                $hijo["fecha"] = $rs->fields[1];
                $hijo["horas"] = $rs->fields[2];
                $hijo["fecha_inicial"] = $rs->fields[3];
                $hijo["fecha_final"] = $rs->fields[4];
                if($tipo==3 && $i==0){
                    
                    $this->arrF['f_inicio']=$rs->fields[3];
                    $this->arrF['f_final']=$rs->fields[4];
                }
                $i++;
                array_push($padre, $hijo);
                $rs->MoveNext();
            }
            
            
        }
        
        return $padre;
    }
    
    
    
    /* Creado por: Banny Solano Arevalo              */
    /* Fecha: 17/07/2013                             */
    /* Descripcion : Actualiza el estado de los 
                    atributos de un empleadopor item */
    function Actualiza_Atributos_Item($estado, $item_codigo, $empleado_codigo){
        $cn = $this->getMyConexionADO();
        //$cn->debug=true;
        $params = array($estado, $item_codigo, $empleado_codigo);
        $sql = "UPDATE Atributos
                    SET Estado_codigo = ?
                WHERE Item_Codigo = ? and Empleado_Codigo = ?";
        $rs = $cn->Execute($sql, $params);
        if($rs){
            return "OK";
        }else{
            return "OCURRIO UN ERROR AL ACTUALIZAR ATRIBUTOS";
        }
    }



    function Validar_Fortaleza_Clave($pwd){
        $cn = $this->getMyConexionADO();

        $ssql ="exec spEmp_Verifica_Fortaleza ? ";
        $params=array(
           $pwd
        );
        $rs=$cn->Execute($ssql,$params);

        //echo 'rs2[1]:'.$rs2[1] . ' rs2[0]:'.$rs2[0];
        if($rs->RecordCount() > 0){
          return  $rs->fields[0];
        }else{
          return 'falla';
        }
    }


    function Validar_Actualizar_Clave($dni, $pwd, $new_pwd, $reply, $ip){
        $ar_res = array();
        $cn = $this->getMyConexionADO();
        //$cn->debug=true;

        $ssql = " exec spEmp_Cambiar_Clave ?, ?, ?, ?, ?";

        $params = array(
          $dni, 
          $pwd,
          $new_pwd,
          $reply,
          $ip
        );
        //print_r($params);
        $rs=$cn->Execute($ssql,$params);
        //echo 'rs2[1]:'.$rs2[1] . ' rs2[0]:'.$rs2[0];
        if($rs->RecordCount() > 0){
          
          $ar_res = array($rs->fields[0],$rs->fields[1]);
        }
        // die();
        $cn->Close();

        return $ar_res;
    }

    function ResponsableArea($emp_cod){
        $cn = $this->getMyConexionADO();
        $res = '';
        if($cn){
            $params = array($emp_cod);
            $ssql = "select area_codigo FROM areas where empleado_responsable= ? and Area_Activo=1 ";
            $rs = $cn->Execute($ssql, $params);
            $i=0;
            $areas = '';
            while(!$rs->EOF){
                $areas .= "'".$rs->fields[0]."',";
                $rs->MoveNext();
            }
            
            $res = substr($areas, 0, -1);
        }
        return $res;
    }
    // AGREGADO PARA EL CAMBIO DE HORARIO DE LACTANCIA 
    // DIEGO MACHACA
 
    function Area_Actual(){
      $rpta='OK';
      $cn = $this->getMyConexionADO();
      $params = array($this->empleado_codigo);
      $ssql = "select area_Codigo from Empleado_area ";
      $ssql .=" where empleado_codigo = ?";
      $ssql .=" and  Empleado_Area_Activo = 1 ";
      $rs = $cn->Execute($ssql, $params);
      if($rs->RecordCount() > 0){
          return $rs->fields[0];
      }else{
          return 0;
      }


    
    }
    function ExisteRegistroHorario_Lactancia(){
      $cn = $this->getMyConexionADO();
      $sql = "SELECT Fecha_Aplicacion from Empleado_Horario_Lactancia where Empleado_Codigo = ".$this->empleado_codigo;
      $rs = $cn->Execute($sql);
      if($rs->RecordCount() > 0){
        return 1;
      }else{
        return 0;
      }

    }
    function Registrar_Horario_Lactancia($fecha='',$hr='', $fant='',$fact=''){
      $rpta='OK';
      $cn = $this->getMyConexionADO();
      //$cn->debug = true;
      if ($fecha && $hr) {
        if($fant > $fact){

          $param_u = array($fecha, $hr, $this->empleado_codigo);
          $sql_update = "UPDATE Empleado_Horario_Lactancia SET Fecha_Aplicacion = CONVERT(DATETIME,?,103), Horario = ? WHERE Empleado_Codigo = ? and Estado = 1";
          // print_r($param_u);
          // echo $sql_update;
          // die('VBBN');
          $cn->Execute($sql_update,$param_u);
        } else{
          $param_u = array($this->empleado_codigo);
          $sql_update = "UPDATE Empleado_Horario_Lactancia SET Estado = 2 WHERE Empleado_Codigo = ?";
          $cn->Execute($sql_update,$param_u);
          // die('aca');

          $params = array($this->empleado_codigo,$fecha,$hr,$this->usuario_id);
          $sql_insert = "INSERT INTO Empleado_Horario_Lactancia (Empleado_Codigo,Fecha_Aplicacion,Fecha_reg,Horario,Observacion, Usuario_reg)";
          $sql_insert .= " VALUES(?, CONVERT(DATETIME,?,103),GETDATE(),?,'Cambio de horario por GAP',?)";
          // echo $sql_insert;
                // echo $fecha; die();

          $rs = $cn->Execute($sql_insert,$params);
        }


      }else{
        $sql = "SELECT Fecha_Aplicacion from Empleado_Horario_Lactancia where Empleado_Codigo = ".$this->empleado_codigo;
        $rs = $cn->Execute($sql);

        if($rs->RecordCount() > 0){
          $param_u = array($this->empleado_codigo);
          $sql_update = "UPDATE Empleado_Horario_Lactancia SET Estado = 2 WHERE Empleado_Codigo = ?";
          $cn->Execute($sql_update,$param_u);

        }

        $params = array($this->empleado_codigo, $this->usuario_id);
        $sql_insert = "INSERT INTO Empleado_Horario_Lactancia (Empleado_Codigo,Fecha_Aplicacion,Fecha_reg,Horario,Observacion, Usuario_reg)";
        $sql_insert .= " VALUES(?, DATEADD(DAY,1,GETDATE()),GETDATE(),'I','Horario inicial por defecto al inicio',?)";

        $rs = $cn->Execute($sql_insert,$params);

      }


      return $rpta;
    }

    function ObtenerDatosLactancia(){
      $rpta='OK';
      $cn = $this->getMyConexionADO();
      $params = array($this->empleado_codigo);
      $ssql = "select e.Empleado_Apellido_Paterno+' '+e.Empleado_Apellido_Materno+' '+e.Empleado_Nombres as Emp,";
      $ssql .= "CONVERT(VARCHAR(10),Fecha_Aplicacion,103), Horario, CONVERT(VARCHAR(10),DATEADD(DAY,2-DATEPART(WEEKDAY, CURRENT_TIMESTAMP),DATEADD(WEEK,1,GETDATE())),103),";
      $ssql .= "CONVERT(VARCHAR(8),GETDATE(),112),CONVERT(VARCHAR(10),Fecha_Aplicacion,112) FROM Empleado_Horario_Lactancia eh INNER JOIN Empleados e ON e.Empleado_Codigo = eh.Empleado_Codigo WHERE eh.Empleado_Codigo = ? and Estado = 1";
      $rs = $cn->Execute($ssql, $params);
      if($rs->RecordCount() > 0){
          return $rs->fields;
      }else{
          return 0;
      }

    }

    function Historico_Horario_Lactancia(){
      $rpta='OK';
      $cn = $this->getMyConexionADO();
      // $cn->debug = true;
      $params = array($this->empleado_codigo);
      $ar_histo =array();
      $ssql = "select CASE WHEN Horario = 'I' THEN 'Inicio de turno' ELSE 'Fin de turno' END, CONVERT(VARCHAR(10),Fecha_Aplicacion,103),";
      $ssql .= "CASE WHEN Estado = 1 THEN 'Actual' ELSE 'Pasado' END, Empleados.Empleado_Apellido_Paterno+''+Empleados.Empleado_Apellido_Materno+''+Empleados.Empleado_Nombres";
      $ssql .= " from Empleado_Horario_Lactancia INNER JOIN Empleados ON Empleados.Empleado_Codigo = Empleado_Horario_Lactancia.Usuario_reg where Empleado_Horario_Lactancia.Empleado_codigo = ? ";
      $ssql .= " and Fecha_Aplicacion <= CONVERT(DATETIME,CONVERT(VARCHAR(8),GETDATE(),112),103) order by Fecha_reg DESC";
      // echo $ssql;die();
      $rs = $cn->Execute($ssql, $params);
      while(!$rs->EOF){
          $ar_histo[] = array('horario' => $rs->fields[0], 'fecha' => $rs->fields[1], 'estado' => $rs->fields[2],'usuario_reg' => $rs->fields[3]);
          $rs->MoveNext();
      }
      return $ar_histo;
    }

    function Verifica_Empleado_Femenino($empleados){
        $cn = $this->getMyConexionADO();
        //$cn->debug = true;
        $array_empleados = explode(",",$empleados);
        $empleados_validados = "";
        if(count($array_empleados) >0){
            for($i=0; $i<count($array_empleados);$i++){
                $params = array($array_empleados[$i]);
                $sql = "select empleado_codigo from empleados where Empleado_Codigo = ? and empleado_sexo = 'F'";
                $rs = $cn->Execute($sql,$params);
                if($rs->RecordCount() > 0){
                    if(count($array_empleados) == $i+1)
                        $empleados_validados .= $array_empleados[$i];
                    else
                        $empleados_validados .= $array_empleados[$i].",";
                }
            }    
        }else{
            $empleados_validados = $empleados;                         
        }
        return $empleados_validados;
    }
    
    function Obtener_Item_Default($tabla, $item){
        $cn = $this->getMyConexionADO();
        $params = array($tabla, $item);
        $sql = "select item_default from items where tabla_codigo = ? and item_codigo = ?";
        $rs = $cn->Execute($sql, $params);
        if($rs->RecordCount() > 0){
            return $rs->fields[0];
        }
        return "";
        
    }
    
    function Actualizar_Correo_Personal($email,$empleado){
        $cn = $this->getMyConexionADO();
        
        $params = array($email,$empleado);
        $sql = "update empleado_indicador set email_personal = ? where empleado_codigo = ?";
        $rs = $cn->Execute($sql, $params);
    }


    function Obtener_Cumpleanios_Hoy(){
	    $cn = $this->getMyConexionADO();
	    $sql ="select Empleados.Empleado_Codigo, Empleado_Dni, Empleado_Apellido_Paterno+' '+Empleado_Apellido_Materno+' '+ Empleado_Nombres, Empleado_Nombres, ";
	    $sql .="ISNULL(Empleado_Email,''), ISNULL(email_personal,'') FROM Empleados  inner join Empleado_indicador ei ON Empleados.Empleado_Codigo = ei.empleado_codigo ";
	    $sql .="WHERE Estado_Codigo = 1 AND DATEPART(DAY, Empleado_Fecha_Nacimiento) = DATEPART(DAY, GETDATE()) AND ";
	    $sql .="DATEPART(MONTH, Empleado_Fecha_Nacimiento)= DATEPART(MONTH, GETDATE()) ";
		    $sql .=" AND Empleados.Empleado_Codigo in(38339) "; // dato dev
	    $sql .="UNION SELECT Empleados.Empleado_Codigo, Empleado_Dni, Empleado_Apellido_Paterno+' '+Empleado_Apellido_Materno+' '+ Empleado_Nombres, Empleado_Nombres, ";
	    $sql .="ISNULL(Empleado_Email,''), ISNULL(email_personal,'') FROM Empleados  inner join Empleado_indicador ei ON Empleados.Empleado_Codigo = ei.empleado_codigo ";
	    $sql .="WHERE Estado_Codigo = 1 AND DATEPART(DAY, Empleado_Fecha_Nacimiento) = DATEPART(DAY, GETDATE())+1 "; 
	    $sql .="and DATEPART(MONTH, Empleado_Fecha_Nacimiento) =  DATEPART(MONTH, GETDATE())  and ";
	    $sql .="( DATEPART(MONTH, GETDATE()) = 2 AND  DATEPART(DAY, GETDATE()) = 28)  ";
	    // echo $sql;die();
	    $rs = $cn->Execute($sql);
	    $i=0;
	    $areas = '';
	    $ar_result = array();
      while(!$rs->EOF){
        $ar_result[] = array('cod' => $rs->fields[0],
            'empleado' => $rs->fields[2],'nombres' => $rs->fields[3],
            'email_corp' => $rs->fields[4],'email_personal' => $rs->fields[5]);
        $rs->MoveNext();
      }

      return $ar_result;


    }

     function tmp_eliminar_empleado_codigo_seleccionados () {
      $cn = $this->getMyConexionADO();
      $ssql = "delete from TMP_SELECCIONADO";
      $_rs = $cn->Execute($ssql);
            

     } 
   
    function tmp_insertar_empleado_codigo_seleccionados () {
      $cn = $this->getMyConexionADO();
      $sUsuario=$this->Empleado_Codigo;
      $params = array($sUsuario);
      $ssql = "insert into TMP_SELECCIONADO (empleado_codigo) values (?)";
      $rs = $cn->Execute($ssql, $params);

      if($rs)
            return "OK";
        else
            return "Ocurrió un Error al Insertar";
    }
}

?>
