 <?php header("Expires: 0");?>
<?php
    require_once("../../Includes/Connection.php");
    require_once("../../Includes/Constantes.php");
    require_once("../../Includes/clswfm_empleado_disponibilidad.php");
    //require_once("../includes/Seguridad.php");
    require_once("../../Includes/JSON/JSON.php");
    //$usuario=$_SESSION["empleado_codigo"];
    //$usuario=3300;
    //$area=178;
    if (isset($_POST["anio"])) $anio = $_POST["anio"];
    if (isset($_POST["semana"])) $semana = $_POST["semana"];
    if (isset($_POST["fecha"])) $fecha = $_POST["fecha"];
    if (isset($_POST["empleado"])) $empleado = $_POST["empleado"];
    $a = new wfm_empleado_disponibilidad();
    $a->setMyUrl(db_host());
    $a->setMyUser(db_user());
    $a->setMyPwd(db_pass());
    $a->setMyDBName(db_name());
    $a->anio=$anio;
    $a->semana=$semana;
    $a->fecha=$fecha;
    $a->empleado_codigo=$empleado;
    /*$a->anio=2010;
    $a->semana=30;
    $a->fecha=1;
    $a->empleado_codigo=13626;*/
    $lista_detalle_programacion=$a->lista_detalle_programacion_empleado();
    $final{'success'} = "true";
    $final{'totales'} = 5;
    $final{"data"} = $lista_detalle_programacion;
    $json = new Services_JSON();
    $output = $json->encode($final);
    //print($output);
    echo $output;

 //**********combo************** 
 /*$i=1;
$padre = array();
while($i<3){
// We fill the $value array with the data. 
 $hijo = array();
 $hijo["Codigo"]= $i;
 $hijo["Descripcion"]= "nombre "+$i;
 array_push($padre,$hijo);
 //array_push($value{"Descricpion"},"nombre "+$i);
 $i++;
}

$final{"data"} = $padre;
$json = new Services_JSON();
$output = $json->encode($final);
//print($output);
echo $output;*/

//*************grilla*****************

/*$i=1;
$padre = array();
while($i<10){
// We fill the $value array with the data. 
 $hijo = array();
 $hijo["Codigo"]= $i;
 $hijo["Descripcion"]= "nombre "+$i;
 array_push($padre,$hijo);
 //array_push($value{"Descricpion"},"nombre "+$i);
 $i++;
}

$final{'success'} = "true";
$final{'totales'} = $i;
$final{"data"} = $padre;
$json = new Services_JSON();
$output = $json->encode($final);
//print($output);
echo $output;*/
 


?>