<?php
class MyCombo extends mantenimiento{
var $query = "";
var $datefirst='';
function construir_combo(){
	$rpta = "";
	$cn = $this->getMyConexionADO();
	if($this->datefirst!='') $cn->Execute($this->datefirst);
	$rs=$cn->Execute($this->query);
	$padre=array();
	$hijo['codigo']='0';
	$hijo['descripcion']='---Seleccionar---';
	array_push($padre,$hijo);
	
	while(!$rs->EOF){
		$hijo=array();
		$hijo['codigo']=$rs->fields[0];
		$hijo['descripcion']=utf8_encode(trim($rs->fields[1]));
		array_push($padre,$hijo);
		
		$rs->MoveNext();
	}
	$rs=null;
	return $padre;
}

function construir_cadena($codigo, $descripcion){
	$arrCodigo=explode('¬',$codigo);
	$arrDescripcion=explode('¬',$descripcion);
	$padre=array();
	$i=0;
	foreach($arrCodigo as $valor){
		$hijo=array();
		$hijo['codigo']=trim($valor);
		$hijo['descripcion']=trim($arrDescripcion[$i]);
		$i=$i+1;
		array_push($padre,$hijo);
	}
	return $padre;
}
  
function construir_secuencia($inicio,$fin){
    $padre=array();
    for($i=$inicio*1;$i<=$fin*1;$i++){
        $hh=$i;
        if(strlen($i)<=1) $hh='0'.$i;
        $hijo=array();
        $hijo['codigo']=$i;
        $hijo['descripcion']=$hh;
        array_push($padre,$hijo);
    }
    return $padre;
}
}
?>