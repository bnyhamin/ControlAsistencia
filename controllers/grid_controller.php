<?php
header('Expires: 0');
require('../includes/Connection.php');
require('../includes/Constantes.php');
require('../includes/MyGrillaEasyUI.php');

$objr = new MyGrilla();
$objr->setDriver_Coneccion(db_name());
$objr->setUrl_Coneccion(db_host());
$objr->setUser(db_user());
$objr->setPwd(db_pass());

$multiple='';
if(isset($_POST["hdd_multiple_seleccion"])) $multiple=$_POST["hdd_multiple_seleccion"];
else if(isset($_GET["hdd_multiple_seleccion"])) $multiple=$_GET["hdd_multiple_seleccion"];
$objr->multipleSeleccion=($multiple=="1"?true:false);

$evento_fila='';
if(isset($_POST["hdd_evento_fila"])) $evento_fila=$_POST["hdd_evento_fila"];
else if(isset($_GET["hdd_evento_fila"])) $evento_fila=$_GET["hdd_evento_fila"];
$objr->evento_fila=$evento_fila;

$noseleccionable='';
if(isset($_POST["hdd_no_seleccionable"])) $noseleccionable=$_POST["hdd_no_seleccionable"];
else if(isset($_GET["hdd_no_seleccionable"])) $noseleccionable=$_GET["hdd_no_seleccionable"];
$objr->noSeleccionable=($noseleccionable=="1"?true:false);

$ses_directorio='';
if(isset($_POST["hdd_ses_directorio"])) $ses_directorio=$_POST["hdd_ses_directorio"];
else if(isset($_GET["hdd_ses_directorio"])) $ses_directorio=$_GET["hdd_ses_directorio"];
$ses_pagina='';
if(isset($_POST["hdd_ses_pagina"])) $ses_pagina=$_POST["hdd_ses_pagina"];
else if(isset($_GET["hdd_ses_pagina"])) $ses_pagina=$_GET["hdd_ses_pagina"];


$groupby='';
if(isset($_POST["hdd_ses_by"])) $groupby=$_POST["hdd_ses_by"];
else if(isset($_GET["hdd_ses_by"])) $groupby=$_GET["hdd_ses_by"];

$objr->from=(trim($ses_directorio)=="" ? $ses_directorio : base64_decode($ses_directorio));
$objr->where=(trim($ses_pagina)=="" ? $ses_pagina : base64_decode($ses_pagina));
$objr->groupby=(trim($groupby)=="" ? $groupby : base64_decode($groupby));

$Alias=array();
if(isset($_POST["hdd_alias"])) $Alias=explode("||",$_POST["hdd_alias"]);
else if(isset($_GET["hdd_alias"])) $Alias=explode("||",$_GET["hdd_alias"]);
$objr->alias=$Alias;

$Campos=array();
if(isset($_POST["hdd_campos"])) $Campos=explode("||",$_POST["hdd_campos"]);
else if(isset($_GET["hdd_campos"])) $Campos=explode("||",$_GET["hdd_campos"]);
$objr->campos=$Campos;

$buscam = '';
if(isset($_POST["findm"])) $buscam=utf8_decode ($_POST["findm"]);
else if(isset($_GET["findm"])) $buscam=utf8_decode($_GET["findm"]);
$objr->findm=$buscam;

$option='';
if(isset($_POST["opcion"])) $option=$_POST["opcion"];
else if(isset($_GET["opcion"])) $option=$_GET["opcion"];

$alias_campo_especial='';
if(isset($_POST["alias_campo_especial"])) $alias_campo_especial=$_POST["alias_campo_especial"];
else if(isset($_GET["alias_campo_especial"])) $alias_campo_especial=$_GET["alias_campo_especial"];
$objr->alias_campo_especial=$alias_campo_especial;

$valor_campo_especial='';
if(isset($_POST["valor_campo_especial"])) $valor_campo_especial=$_POST["valor_campo_especial"];
else if(isset($_GET["valor_campo_especial"])) $valor_campo_especial=$_GET["valor_campo_especial"];
$objr->valor_campo_especial=$valor_campo_especial;


$order='';
if(isset($_POST["order"])) $order=$_POST["order"];
else if(isset($_GET["order"])) $order=$_GET["order"];
$objr->order=$order;

$torder='';
if(isset($_POST["TOrder"])) $torder=$_POST["TOrder"];
else if(isset($_GET["TOrder"])) $torder=$_GET["TOrder"];
$objr->TOrder=$torder;

$espaginacion=1;//pointered
if(isset($_POST["hdd_espaginacion"])) $espaginacion=$_POST["hdd_espaginacion"];//pointered
else if(isset($_GET["hdd_espaginacion"])) $espaginacion=$_GET["hdd_espaginacion"];//pointered


$page=1;
if($espaginacion*1==1){//pointered
    if(isset($_POST["pagina"])) $page = intval($_POST["pagina"]);
    else if(isset($_GET["pagina"])) $page = intval($_GET["pagina"]);
}//pointered


$rows=10;
if(isset($_POST["rows"])) $rows = intval($_POST["rows"]);
else if(isset($_GET["rows"])) $rows = intval($_GET["rows"]);

$objr->size=$rows;
$objr->page=$page;

switch ($option){
    case "1"://CARGAR DATA
        $data = $objr->inicio();
        if(count($data)>0) $status=1;
        else $status=0;
        $rpta=array(
            "rows"=>$data,
            "total"=>$objr->nRecords,
            "cantpag"=>count($data)
            ,"query"=>$objr->getmssql()//snake
            //,"filtro especial"=>$objr->getCampoEspecial()
        );
        echo json_encode($rpta);
        break;
}
?>