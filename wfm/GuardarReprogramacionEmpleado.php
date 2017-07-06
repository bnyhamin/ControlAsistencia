<?php header("Expires: 0"); 
session_start();
if (!isset($_SESSION["empleado_codigo"])){
?>
    <script language="JavaScript">
        alert("Su sesión a caducado!!, debe volver a registrarse.");
        document.location.href = "../login.php";
    </script>
<?php
exit;
}
    require_once("../../Includes/Connection.php");
    require_once("../../Includes/Constantes.php");
    require_once("../../Includes/clswfm_empleado_disponibilidad.php");
    require_once("../../Includes/clsEmpleados.php");
    //require_once("../includes/Seguridad.php");
    require_once("../../Includes/JSON/JSON.php");
    $usuario=$_SESSION["empleado_codigo"];
    //$usuario=3300;
    $suceso=false;
    $mensaje='';
    
    $a = new wfm_empleado_disponibilidad();
    $a->setMyUrl(db_host());
    $a->setMyUser(db_user());
    $a->setMyPwd(db_pass());
    $a->setMyDBName(db_name());
    
    $e = new Empleados();
    $e->setMyUrl(db_host());
    $e->setMyUser(db_user());
    $e->setMyPwd(db_pass());
    $e->setMyDBName(db_name());
    	
    $json = new Services_JSON();

    if (isset($_POST["accion"])) $accion = $_POST["accion"];
    if (isset($_POST["anio"])) $anio = $_POST["anio"];
    if (isset($_POST["semana"])) $semana = $_POST["semana"];
    if (isset($_POST["fecha"])) $fecha = $_POST["fecha"];
    if (isset($_POST["servicio"])) $servicio = $_POST["servicio"];
    if (isset($_POST["hora_inicio"])) $hora_inicio = $_POST["hora_inicio"];
    if (isset($_POST["minuto_inicio"])) $minuto_inicio = $_POST["minuto_inicio"];
    if (isset($_POST["hora_fin"])) $hora_fin = $_POST["hora_fin"];
    if (isset($_POST["minuto_fin"])) $minuto_fin = $_POST["minuto_fin"];
    if (isset($_POST["requerido"])) $requerido = $_POST["requerido"];
    if (isset($_POST["detalle_empleados"])) $detalle_empleados = $_POST["detalle_empleados"];
	
    if ($_POST["accion"] == "GRB"){
            //$arr_valor=split("@",$detalle_empleados);
            //$num_arr = sizeof($arr_valor);
            //for ($i=0; $i<$num_arr; $i++ ){
            $a->anio=$anio;
            $a->semana=$semana;
            $a->fecha=$fecha;
            $a->empleado_codigo=$detalle_empleados;//$arr_valor[$i];
            $a->hora_inicio=$hora_inicio;
            $a->minuto_inicio=$minuto_inicio;
            $a->hora_fin=$hora_fin;
            $a->minuto_fin=$minuto_fin;

            $e->empleado_codigo = $detalle_empleados; //$arr_valor[$i];
            $e->Query();

            $empleado=$e->empleado_apellido_paterno . ' ' . $e->empleado_apellido_materno . ' ' . $e->empleado_nombres;	

            if($mensaje==''){
                    //new
                    
                    if(isset($arr_valor)){
                        if(is_array($arr_valor)){
                            if(count($arr_valor)){
                                $valor=$arr_valor[$i];
                            }
                        }
                    }else{
                        $valor="";
                    }
                    
                    $mensaje=$valor . '  ' . $empleado . ' - ' .$a->ReprogramacionEmpleado($servicio , $usuario);
                    //new
                    
                    //-->previo$mensaje=$arr_valor[$i] . '  ' . $empleado . ' - ' .$a->ReprogramacionEmpleado($servicio , $usuario);
            }else{
                    $mensaje=$mensaje . "," . '<br>' .  $arr_valor[$i] . '  ' . $empleado . ' - ' .$a->ReprogramacionEmpleado($servicio);
            }
            //$suceso=true;
	 		
            /*$final = array();
            $final["success"]= $suceso;
            $final["msg"]= $mensaje;

            //$json = new Services_JSON();
            $output = $json->encode($final);
            //print($output);
            echo $output;*/
		
            //}//end for
            $suceso=true;
            //$mensaje = $anio."-".$semana."-".$fecha."-".$hora_inicio."-".$minuto_inicio."-".$hora_fin."-".$minuto_fin."-".$requerido."-".$detalle_empleados;
            /*$a->anio=2010;
            $a->semana=30;
            $a->fecha=1;
            $a->empleado_codigo=614;
            $a->hora_inicio=8;
            $a->minuto_inicio=0;
            $a->hora_fin=13;
            $a->minuto_fin=0;*/

            //$mensaje=$a->ReprogramacionEmpleado();
            //$suceso=true;

            /*if($mensaje == "OK"){
                $suceso = True;
                $mensaje = "Registro Eliminado";	
            }else{
                $suceso = False;
            }*/
        }

	if ($_POST["accion"] == "REP"){
            $a->anio=$anio;
            $a->semana=$semana;
            //$a->fecha=$fecha;
            $a->usuario_registra=$usuario;
            $mensaje=$a->ReprogramacionGlobal($servicio);
            //$suceso=true;
            /*$final = array();
            $final["success"]= $suceso;
            $final["msg"]= $mensaje;

            //$json = new Services_JSON();
            $output = $json->encode($final);
            //print($output);
            echo $output;*/

            //$mensaje = $anio."-".$semana."-".$fecha."-".$hora_inicio."-".$minuto_inicio."-".$hora_fin."-".$minuto_fin."-".$requerido."-".$detalle_empleados;
            /*$a->anio=2010;
            $a->semana=30;
            $a->fecha=1;
            $a->empleado_codigo=614;
            $a->hora_inicio=8;
            $a->minuto_inicio=0;
            $a->hora_fin=13;
            $a->minuto_fin=0;*/
		
            //$mensaje=$a->ReprogramacionEmpleado();
            //$suceso=true;
 		 		 		
            if($mensaje == "OK"){
                $suceso = True;
                $mensaje = "Exitosa";
            }else{
                $suceso = False;
            }
        }

    $final = array();
    $final["success"]= $suceso;
    $final["msg"]= $mensaje;

    $json = new Services_JSON();
    $output = $json->encode($final);
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

