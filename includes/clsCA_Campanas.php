<?php
require_once("../../Includes/mantenimiento.php");
class ca_campanas extends mantenimiento{
var $cod_campana='';
var $exp_nombrecorto='';
var $exp_codigo='';
var $coordinacion_codigo='';
var $exp_activo='';

function Query(){
	$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
	$ssql="select cod_campana,exp_NombreCorto,exp_codigo,exp_descripcion,coordinacion_codigo ";
    $ssql.=" FROM v_campanas ";
	$ssql.=" WHERE Cod_campana =" . $this->cod_campana . " and exp_activo=1";
	
    $rs = $this->cnnado->Execute($ssql);
		if (!$rs->EOF){
			$this->cod_campana= $rs->fields[0]->value;
			$this->exp_nombrecorto= $rs->fields[1]->value;
			$this->exp_codigo= $rs->fields[2]->value;
	        $this->coordinacion_codigo= $rs->fields[4]->value; 
	  }else{
		   $rpta='No Existe Registro de Campana: ' . $this->cod_campana;
	  }
	}
	return $rpta;
 }

function Activacion(){
    
    /*$rpta="OK";
    $rpta=$this->conectarme_ado();
    if($rpta=="OK"){
    $ssql=" update v_campanas set exp_activo=" . $this->exp_activo;
    $ssql.=" WHERE Cod_campana =" . $this->cod_campana;

    $r = $this->cnnado->Execute($ssql);
    }
    return $rpta;*/
    
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql=" update v_campanas set exp_activo=" . $this->exp_activo;
    $ssql.=" WHERE Cod_campana =" . $this->cod_campana;		
    $r = $cn->Execute($ssql);
    return $rpta;
    
}

    function Seleccionar_servicios_area($filtro){
        $rpta="OK";
        $cn=$this->getMyConexionADO();
    	
        $ssql="select cod_campana, exp_codigo + '-' + exp_NombreCorto +' (' + convert(varchar,cod_campana) + ')' as campana, case when exp_activo=1 then 'Si' else 'No' end as activo ";
        $ssql.=" FROM v_campanas ";
        $ssql.=" WHERE coordinacion_codigo =" . $this->coordinacion_codigo . " and exp_activo=1 and ";
        $ssql.=" 	exp_codigo + '-' + exp_NombreCorto +' (' + convert(varchar,cod_campana) + ')' like '%".$filtro."%'";
        $ssql.=" Order by 2 ";
        
        $rs = $cn->Execute($ssql);
    
        echo "<table cellspacing='1' cellpadding='0' border='0' align=center style='width:100%'>";
        echo "<tr bgcolor='#628DB9' align=center>";
        echo "<td><Font color='#fffafa'>Código</Font></td>";
        echo "<td><Font color='#fffafa'>Unidad de Servicio</Font></td>";
        echo "<td><Font color='#fffafa'>Activo</Font></td>";
        echo "</tr>";
        while (!$rs->EOF){
            echo "\n<tr bgcolor='#ecf9ff' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#ffebd7'>";
            echo "\n	<td>";
            echo "\n		<font style='CURSOR: hand' onclick=\"guardar('".$rs->fields[0]."','".$rs->fields[1]."')\">".$rs->fields[0]."</font>";
            echo "\n	</td>";
            echo "\n	<td>".$rs->fields[1]."</td>";
            echo "\n	<td>".$rs->fields[2]."</td>";
            echo "\n</tr>";
            $rs->MoveNext();
        }
        echo "</table>";	  
        return $rpta;
    }
    
    function Listar_Campanas($start, $limit, $filtro){
        $cn = $this->getMyConexionADO();
        $cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $sql = "SELECT  v.Cod_Campana,
                		Exp_Codigo,
                		Exp_NombreCorto,
                		Coordinacion_Codigo,
                		Exp_Activo ,
                		isnull(Servicios_Traspaso.st_valor,0)  as valor
                FROM v_campanas v INNER JOIN Areas a on v.Coordinacion_Codigo=a.Area_Codigo 
                    LEFT JOIN Servicios_Traspaso ON Servicios_Traspaso.Cod_Campana = v.Cod_Campana
                WHERE exp_Activo = 1 AND Exp_NombreCorto LIKE '%$filtro%'
                ORDER BY Exp_NombreCorto ASC";
        $rs = $cn->Execute($sql);
        if($rs){
            $total = $rs->RecordCount();
            if($total > 0){
                $data = array();
                while(!$rs->EOF){
                    $rs->fields["Exp_NombreCorto"] = utf8_encode($rs->fields["Exp_NombreCorto"]);
                    $data[] = $rs->fields;
                    $rs->MoveNext();
                }
                $data = array_splice($data, $start, $limit);
                return json_encode(array("data" => $data, "total" =>$total));
            }
        }else{
            return $cn->ErrorMsg();
        }
    }
    
     function Guardar_Valor_Traspaso($campana, $dias){
        $cn = $this->getMyConexionADO();
        $params = array($campana);
        $sql = "SELECT * FROM Servicios_Traspaso WHERE Cod_Campana = ?";
        $rs = $cn->Execute($sql, $params);
        if($rs->RecordCount() > 0){
            $params = array($dias, $campana);
            $sql = "UPDATE Servicios_Traspaso  SET st_valor  = ? WHERE Cod_Campana = ?";
            $rs = $cn->Execute($sql, $params);
            if($rs){
                echo json_encode(array("success"=> true, "message" => "Actualizo Correctamente"));
            }else{
                echo json_encode(array("success"=> false, "message" => "Ocurrio un error al Actualizar ".$cn->ErrorMsg()));
            }    
        }else{
            $params = array($campana, $dias);
            $sql = "INSERT INTO Servicios_Traspaso (Cod_Campana,st_valor) VALUES(?,?)";
            $rs = $cn->Execute($sql, $params);
            if($rs){
                echo json_encode(array("success"=> true, "message" => "Inserto Correctamente"));
            }else{
                echo json_encode(array("success"=> false, "message" => "Ocurrio un error al insertar ".$cn->ErrorMsg()));
            }    
        }
        
        
    }
    
    


}
