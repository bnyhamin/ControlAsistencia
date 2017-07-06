<?php
require_once("adodb/adodb.inc.php");

class mantenimiento {
	var $cnnado;
    var $MyUrl;
    var $MyUser;
    var $MyPwd;
    var $MyDBName;

    function setMyUrl($valor){
        $this->MyUrl=$valor;
    }
    function getMyUrl(){
        return $this->MyUrl;
    }
    function setMyUser($valor){
        $this->MyUser=$valor;
    }
    function getMyUser(){
        return $this->MyUser;
    }
    function setMyPwd($valor){
        $this->MyPwd=$valor;
    }
    function getMyPwd(){
        return $this->MyPwd;
    }
    function setMyDBName($valor){
        $this->MyDBName=$valor;
    }
    function getMyDBName(){
        return $this->MyDBName;
    }
    //  function conectarme(){
    //    $rpta = "OK";
    //    if ($msconnect = mssql_pconnect($this->getMyUrl(),$this->getMyUser(),$this->getMyPwd()) or die("No puedo conectarme a servidor")){
    //    	$cnn = mssql_select_db($this->getMyDBName(),$msconnect) or die("No puedo seleccionar BD");
    //    } else {
    //      $rpta = "Error al tratar de conectarse a la bd.";
    //    }
    //		return $rpta;
    //  }
    //


    /*
    function conectarme_pg(){

    $msconnect = pg_connect("host=" . $this->getMyUrl() . " user= " . $this->getMyUser() . " password= " . $this->getMyPwd() . " dbname=" . $this->getMyDBName()) or die("No puedo conectarme ");


    }


    function conectarme_adox(){
    $rpta = "";
    $cadena = "Provider=SQLOLEDB.1;User ID=" . $this->getMyUser() . ";Initial Catalog=" . $this->getMyDBName() . ";Data Source=" . $this->getMyUrl() . ";PASSWORD=" . $this->getMyPwd() . ";Description=" . $this->getMyDBName() . ";SERVER=" . $this->getMyUrl() . ";DATABASE=" . $this->getMyDBName() . ";Current Language=spanish";
    $this->cnnado = new COM("ADODB.Connection") or die("No se puede abrir ADO");
    $this->cnnado->Open($cadena);
    if ($this->cnnado->State == 1) $rpta="OK";
    else $rpta="Error al conectarse a la Base de Datos";
    return $rpta;
    }

    //conexion con postgres
    function getMyConexionPG(){
    $cn=ADONewConnection('postgres7');
    $cn->connect($this->getMyUrl(),$this->getMyUser(),$this->getMyPwd(),$this->getMyDBName());
    return $cn;
    }

    /*function getMyConexionADO() // version PHP4 y PHP5
    {
    $cn = ADONewConnection('mssqlnative');
    $cn->connect($this->getMyUrl(),$this->getMyUser(),$this->getMyPwd(),$this->getMyDBName());
    return $cn;
    }*/

    function getMyConexionADO()
    {
        $cn = ADOnewConnection('pdo');
        $server = 'sqlsrv:server='.$this->getMyUrl().';database='.$this->getMyDBName().';';
        $cn->connect($server,$this->getMyUser(),$this->getMyPwd());
        //$user = 'userDesarrollo2016';
        //$password = 'Atento2017+';
        //$cn->connect('sqlsrv:server=172.30.194.21\SIMPLEX2016;database=DB_PERSONAL01;',$user,$password);
        //$cn->debug = true;
        return $cn;
    }


    /*
    function getMyConexionADOParams($Url,$User,$Pwd,$DBName)
    {
    //$cn = ADONewConnection('mssqlnative');
    $cn = &ADONewConnection('mssql');
    $cn->connect($Url,$User,$Pwd,$DBName);
    return $cn;
    }

    function Begin_Tran(){
    $cn = $this->getMyConexionADO();
    $cn->BeginTrans();
    }

    function Commit_Tran(){
    $cn = $this->getMyConexionADO();
    $cn->CommitTrans();
    }

    function Rollback_Tran(){
    $cn = $this->getMyConexionADO();
    $cn->RollbackTrans();
    }
     */

    //var $adodb_driver = "mssqlnative";

}
?>
