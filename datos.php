<?php
include('../Includes/adodb/adodb.inc.php');

function getMyConexionADO(){
    
    //$dbserver='10.252.196.98:1400';
    
    /*$dbserver='10.252.194.154';
    $dsn='';
    $user='userweb';
    $pass='w6b109';
    $dbname='DB_PERSONAL01';*/
    
    
    $dbserver='10.252.197.69';
    $dsn='';
    $user='sa';
    $pass='camori';
    $dbname='DB_PERSONAL01';
    
    //$dbname='TM_ARGUS';
    //$dbname='DB_Curriculo';
    //$cn = ADONewConnection('mssql');
    $cn = &ADONewConnection('mssql');
    
    //$cn = ADONewConnection('mssqlnative');
    
    $cn->debug = true;
    $cn->connect($dbserver,$user,$pass,$dbname);
    
    
    
    $query = "select * from Anio";
    //$query = "select * from empresa";
    $rs = $cn->execute($query);
    //execute the SQL statement and return records
    $arr = $rs->GetArray();
    print_r($arr);
    return $cn;
    /*$cn = ADONewConnection('mssqlnative');
    $cn->connect($this->getMyUrl(),$this->getMyUser(),$this->getMyPwd(),$this->getMyDBName());
    return $cn;*/
}


getMyConexionADO();


?>
