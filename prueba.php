<?php

include 'includes/adodb/adodb.inc.php';
$db = ADOnewConnection('pdo');
$user = 'userDesarrollo2016';
$password = 'Atento2017+';
$db->connect('sqlsrv:server=172.30.194.21\SIMPLEX2016;database=DB_PERSONAL01;',$user,$password);
//$db->debug = true;
$params = array("07941479","atento2014");

$ssql = "SELECT empleado_codigo, empleado_clave_modificada
        FROM empleados
        WHERE empleado_dni=? AND empleado_clave_acceso=dbo.udf_md5(?) and estado_codigo=1";
$rs= $db->Execute($ssql, $params);


if( $rs->RecordCount()==0){

    $rpta = "Credenciales Incorrectas";
}else{
    $rpta = $rs->fields[0]; 
}

echo $rpta;

?>