<?php header("Expires: 0");

// ini_set('display_errors', 'On');
// error_reporting(E_ALL);
require_once("../../Includes/Connection.php");

require_once("../includes/clsCA_Reportes.php");

// require_once("../includes/clsCA_Turnos_Empleado.php");
set_time_limit(30000); 

$fecha_carga = date("YmdHis");
$empleado_codigo = $_POST['empleado_codigo'];

$o = new ca_reportes();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

if(count($_FILES) == 0) { 
	echo '<b>Por favor cargar un archivo</b>';
}else{

	$temp_file = $_FILES['carga']['tmp_name']; 
	$na_origen = $_FILES['carga']['name'];
	$aux = explode(".",$na_origen);
	$na_carga = substr($aux[0],0,20)."_".$fecha_carga.".".$aux[1];
	$path_destino = PathReportes();
	$destination_two = $path_destino."\\".$na_carga;
	copy($temp_file,$destination_two);



	//--fin
	if($_FILES['carga']['tmp_name'] == "none") { //Si no se ha cargado o es más grande de lo especificado   
	    echo "<b>El archivo no se ha podido cargar. El tamaño debe ser menor que 5000Kb.<br>";   
		exit();   
	}
	//if(!ereg("image",$_FILES['pix']['type'])) //si no es una imagen   
	if(!ereg("text/plain",$_FILES['carga']['type'])) { //si no es un archivo de texto   
		echo "<b>El archivo no tiene el formato deseado. Intenta otro archivo.</b><br>";   
		exit();   
	}else{ //Si todo está bien, es una imagen y se cargó completa   
		//$destination = 'c:\data'."\\".$_FILES['pix']['name'];
		$o->empleado_codigo = $empleado_codigo;
		$o->Delete_Temp();
		$_FILES['carga']['name']='tmp.txt'; // nuevo nombre
		$destination = $_FILES['carga']['name'];
		$temp_file = $_FILES['carga']['tmp_name'];   
		move_uploaded_file($temp_file,$destination); //Lo movemos de la ubicación temporal a la que queramos.
		// echo "<p><b>Archivo cargado satisfa:</b>{$_FILES['carga']['name']}({$_FILES['carga']['size']})</p>";
		$archivo = file("tmp.txt"); 
		$lineas = count($archivo);
		if (substr($archivo[0],0,2)=='ÿþ' or substr($archivo[0],0,2)=='þÿ' or substr($archivo[0],0,3)=='ï»¿'){
			echo "<center><b><font color=red>El archivo tiene una codificacion extraña. Edite el archivo con el Block de notas y utilice la opcion guardar como, en la seccion codificación seleccione ANSI. Grabe y vuelva a cargar el archivo</font></b><br></center>";
			exit();   
		}
		if (substr($archivo[0],0,2)=='' and $lineas==0){
			echo "<center><b><font color=red>El archivo no tiene informacion verifique</font></b><br></center>";
			exit();   
		}
		for($i=0; $i < $lineas; $i++){ 
			// echo $archivo[$i]."<br>";
			$columna = explode('|',$archivo[$i]);
            if(count($columna) == 2){
				$o->dni 	    	= trim($columna[0]);
				$o->nombres 		= trim($columna[1]);
				$o->Addnew_Temp();
            }
		}

		echo "OK";

	}   




}


 ?>