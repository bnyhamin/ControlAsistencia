<?php header("Expires: 0"); 
session_start();
if (!isset($_SESSION["empleado_codigo"])){
?><script language="JavaScript">
    alert("Su sesión a caducado!!, debe volver a registrarse.");
    document.location.href = "../login.php";
  </script>
<?php
exit;
}
    require_once("../../Includes/Connection.php");
    require_once("../../Includes/Constantes.php");
    require_once("../../Includes/clsArea.php");
    //require_once("../includes/Seguridad.php");
    require_once("../../Includes/JSON/JSON.php");
    $usuario=$_SESSION["empleado_codigo"];
    //$usuario=3300;
    $a = new areas();    
    $a->setMyUrl(db_host());
    $a->setMyUser(db_user());
    $a->setMyPwd(db_pass());
    $a->setMyDBName(db_name());	
    $areas=$a->Area_Controller($usuario);
    //echo $areas;	
    $final{"data"} = $areas;
    $json = new Services_JSON();
    $output = $json->encode($final);
    //print($output);
    echo $output;
	/*
	$tarr=split('@', $areas);
    $narr=sizeof($tarr);
    
    //echo $tarr[1];

    $padre = array();
	for ($i=0; $i<$narr; $i++){
         $arr=split(',', $tarr[$i]);
    	 $tam=sizeof($arr);
   	 	 $hijo = array();
    	 $hijo["Codigo"]= $arr[0];
	     $hijo["Descripcion"]= $arr[1];
         array_push($padre,$hijo);
		         
         //echo $arr[0];

	}
            $final{"data"} = $padre;
			$json = new Services_JSON();
			$output = $json->encode($final);
			//print($output);
			echo $output;

  */

	
	

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
