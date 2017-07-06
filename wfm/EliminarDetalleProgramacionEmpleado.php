<?php header("Expires: 0");?>
<?php
  require_once("../../Includes/Connection.php");
  require_once("../../Includes/Constantes.php");
  require_once("../../Includes/clswfm_empleado_disponibilidad.php");
  //require_once("../includes/Seguridad.php");
  require_once("../../Includes/json/json.php");
  
  //$usuario=$_SESSION["empleado_codigo"];
  //$usuario=3300;
  
  $suceso=false;
  $mensaje='';
  
  	$a = new wfm_empleado_disponibilidad();
	$a->MyUrl = db_host();
	$a->MyUser= db_user();
	$a->MyPwd = db_pass();
	$a->MyDBName= db_name();

    if (isset($_POST["accion"])) $accion = $_POST["accion"];
	if (isset($_POST["anio"])) $anio = $_POST["anio"];
	if (isset($_POST["semana"])) $semana = $_POST["semana"];
	if (isset($_POST["fecha"])) $fecha = $_POST["fecha"];
	if (isset($_POST["empleado"])) $empleado = $_POST["empleado"];
	if (isset($_POST["hora_inicio"])) $hora_inicio = $_POST["hora_inicio"];
	if (isset($_POST["minuto_inicio"])) $minuto_fin = $_POST["minuto_inicio"];//corregir
	if (isset($_POST["hora_fin"])) $hora_fin = $_POST["hora_fin"];
	if (isset($_POST["minuto_fin"])) $minuto_fin = $_POST["minuto_fin"];
	if (isset($_POST["servicio"])) $servicio = $_POST["servicio"];

	
	if ($_POST["accion"] == "DEL"){
		$a->anio=$anio;
		$a->semana=$semana;
		$a->fecha=$fecha;
		$a->empleado_codigo=$empleado;
		$a->hora_inicio=$hora_inicio;
		$a->minuto_inicio=$minuto_inicio;
		$a->hora_fin=$hora_fin;
		$a->minuto_fin=$minuto_fin;
		
		//$mensaje = $anio."-".$semana."-".$fecha."-".$empleado."-".$hora_inicio."-".$minuto_inicio."-".$hora_fin."-".$minuto_fin;
		/*$a->anio=2010;
		$a->semana=30;
		$a->fecha=1;
		$a->empleado_codigo=614;
		$a->hora_inicio=8;
		$a->minuto_inicio=0;
		$a->hora_fin=13;
		$a->minuto_fin=0;*/
		
		
 		$mensaje=$a->EliminarDetalleProgramacionEmpleado($servicio);
 		
		 		 		
 		if($mensaje == "OK"){
 			$suceso = True;
            $mensaje = "Registro Eliminado";
 			
 		}else{
 			$suceso = False;
 		}
}

$final = array();
$final["success"]= $suceso;
$final["msg"]= $mensaje;

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

