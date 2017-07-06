<?php
header('Expires: 0');
require('../../Includes/Connection.php');
require('../../Includes/Constantes.php');
require_once("../../Includes/mantenimiento.php");
require('../includes/clsCA_Macs_Autorizados.php');
    
$objm = new CA_Macs_Autorizados();
$objm->MyUrl = db_host();
$objm->MyUser= db_user();
$objm->MyPwd = db_pass();
$objm->MyDBName= db_name();

$data="";

$mac_numero='';
if(isset($_POST["param_mac_numero"])) $mac_numero=$_POST["param_mac_numero"];
else if(isset($_GET["param_mac_numero"])) $mac_numero=$_GET["param_mac_numero"];

$objm->mac_numero=$mac_numero;

$mac_activo='';
if(isset($_POST["param_mac_activo"])) $mac_activo=$_POST["param_mac_activo"];
else if(isset($_GET["param_mac_activo"])) $mac_activo=$_GET["param_mac_activo"];

$objm->mac_activo=$mac_activo;

$opcion='';
if(isset($_POST["opcion"])) $opcion=$_POST["opcion"];
else if(isset($_GET["opcion"])) $opcion=$_GET["opcion"];

$accion='';
if(isset($_POST["accion"])) $accion=$_POST["accion"];
else if(isset($_GET["accion"])) $accion=$_GET["accion"];

switch ($opcion){
    case "1":
        if($accion=="G"){
            $data = $objm->Addnew();
        }else{
            $data = $objm->Update();
        }
        
        $rpta=array(
            "respuesta"=>$data
        );
        echo json_encode($rpta);
    break;
    case "2":
        $data=$objm->Query();
        if(count($data)>0) $status=1;
        else $status=0;
        $rpta=array(
            "data"=>$data,
            "status"=>$status
        );
        echo json_encode($rpta);
    break;
}

?>