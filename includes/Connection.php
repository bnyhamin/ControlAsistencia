<?php

define ("cn_HOST", "172.30.194.21\SIMPLEX2016");
define ("cn_USER", "userDesarrollo2016");
define ("cn_PASS", "Atento2017+");
define ("cn_NAME", "DB_PERSONAL01");

function db_host(){
    return cn_HOST;
}
function db_name(){
    return cn_NAME;
}
function db_user(){
    return cn_USER;
}
function db_pass(){
    return cn_PASS;
}


function tituloGAP(){
	return "G.A.P.:Gesti&oacute;n de Asistencia de Personal. ";
}

function PathIncludes(){
    $cadena=dirname(dirname(__FILE__))."/Includes/";
    return $cadena;
}

function URLGap(){
	return 'http://'.$_SERVER['SERVER_ADDR'].'/ControlAsistencia';
}

?>