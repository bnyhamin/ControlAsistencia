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
    if (isset($_POST["servicio"])) $servicio = $_POST["servicio"];
    if (isset($_POST["star"])) $start = $_POST["start"]; else $start='';
    if (isset($_POST["limit"])) $limit = $_POST["limit"]; else $limit=0;
    
    $a = new wfm_empleado_disponibilidad();
    $a->setMyUrl(db_host());
    $a->setMyUser(db_user());
    $a->setMyPwd(db_pass());
    $a->setMyDBName(db_name());
	
    /*$servicio=14585;
    $a->anio=2010;
    $a->semana=30;
    $a->fecha=1;*/

    $a->anio=$anio;
    $a->semana=$semana;
    
    //$a->fecha=$fecha;
    //$start=0;
    //$limit=15;
    $lista_empleados=$a->lista_empleados_inicial($servicio);
    $tam=sizeof($lista_empleados);
    $lista_empleados=$a->lista_empleados($servicio, $start , $limit);
    //$final{'success'} = "true";
    $final{'totales'} = $tam;
    $final{"data"} = $lista_empleados;
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