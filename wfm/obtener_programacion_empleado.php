<?php header("Expires: 0");?>
<?php
  require_once("../../Includes/Connection.php");
  require_once("../../Includes/Constantes.php");
  require_once("../../Includes/clswfm_requerimiento.php");
  //require_once("../includes/Seguridad.php");
  require_once("../../Includes/JSON/JSON.php");
  
    //$usuario=$_SESSION["empleado_codigo"];
    $usuario=3300;
    if (isset($_POST["anio"])) $anio = $_POST["anio"];
    if (isset($_POST["semana"])) $semana = $_POST["semana"];
    if (isset($_POST["empleado"])) $empleado = $_POST["empleado"];
    //if (isset($_POST["fecha"])) $fecha = $_POST["fecha"];
    $r = new wfm_requerimiento();
    $r->setMyUrl(db_host());
    $r->setMyUser(db_user());
    $r->setMyPwd(db_pass());
    $r->setMyDBName(db_name());
    $r->anio=$anio;
    $r->semana=$semana;
    //$r->anio=2010;
    //$r->semana=39;
    //$empleado=181;
    /*$r->cod_campana=14585;
    $fecha=1;*/
    $lista_programacion_empleado=$r->lista_programacion_empleado($empleado);
    //$lista_programacion_empleado=$anio;
    $final{'success'} = "true";
    //$final{'totales'} = 20;
    $final{"data"} = $lista_programacion_empleado;
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