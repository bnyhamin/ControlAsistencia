<?php header("Expires: 0"); 

//require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/clsEmpleado_Asistencia_Feriado.php");

$o = new Empleado_Asistencia_Feriado();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();
  // ini_set('display_errors', 'On');
  //   error_reporting(E_ALL);
if (isset($_POST["session_id"])) $empleado_codigo_registro = $_POST["session_id"];
$fecha_carga = date("YmdHis");

if(!isset($_POST['Upload'])) { 
	echo '<b>Error en la carga de datos</b>';
}else{

	$temp_file = $_FILES['subir_archivo']['tmp_name']; 
	$na_origen = $_FILES['subir_archivo']['name'];
	$aux = explode(".",$na_origen);
	$na_carga = substr($aux[0],0,20)."_".$fecha_carga."_".$empleado_codigo_registro.".".$aux[1];
	$path_destino = PathReportes();
	$destination_two = $path_destino."\\".$na_carga;
	// echo $destination_two;die();
	copy($temp_file,$destination_two);



	//--fin
	if($_FILES['subir_archivo']['tmp_name'] == "none") { //Si no se ha cargado o es más grande de lo especificado   
	    echo "<b>El archivo no se ha podido cargar. El tamaño debe ser menor que 5000Kb.<br>";   
		exit();   
	}
	//if(!ereg("image",$_FILES['pix']['type'])) //si no es una imagen   
	if(!ereg("text/plain",$_FILES['subir_archivo']['type'])) { //si no es un archivo de texto   
		echo "<b>El archivo no tiene el formato deseado. Intenta otro archivo.</b><br>";   
		exit();   
	}else{ //Si todo está bien, es una imagen y se cargó completa   


		$o->Empleado_Codigo = $empleado_codigo_registro;
		$o->nombre_archivo_carga = $na_carga;
		$o->tipo = 'FER';
		$o->carga_cod = $fecha_carga;
		$o->Addnew_Cuentas_Carga();

		
		$o->Delete_Importar_Tmp_T();
		$_FILES['subir_archivo']['name']='tmp.txt'; // nuevo nombre
		$destination = $_FILES['subir_archivo']['name'];
		$temp_file = $_FILES['subir_archivo']['tmp_name'];   
		move_uploaded_file($temp_file,$destination); //Lo movemos de la ubicación temporal a la que queramos.
		// echo "<p><b>Archivo cargado satisfa:</b>{$_FILES['subir_archivo']['name']}({$_FILES['subir_archivo']['size']})</p>";
		$archivo = file("tmp.txt"); 
		$lineas = count($archivo);
		if (substr($archivo[0],0,2)=='ÿþ' or substr($archivo[0],0,2)=='þÿ' or substr($archivo[0],0,3)=='ï»¿'){
			echo "<center><b><font color=red>El archivo tiene una codificacion extraña. Edite el archivo con el Block de notas y utilice la opcion guardar como, en la seccion codificación seleccione ANSI. Grabe y vuelva a cargar el archivo</font></b><br></center>";
			exit();   
		}
		if (substr($archivo[0],0,2)=='' and $lineas==0){
			// echo "<center><b><font color=red>El archivo no tiene informacion verifique</font></b><br></center>";
			exit();   
		}
		for($i=0; $i < $lineas; $i++){ 
			// echo $archivo[$i]."<br>";
			$columna = split('	',$archivo[$i]);
            if(count($columna) == 3){
				$o->Addnew_Tmp_T_Nuevo($columna[0],$columna[1],$columna[2]);
            }
		}

		$o->Importar_Locales_Tmp($empleado_codigo_registro,$fecha_carga);

		
		echo "<h3>Resultado de importacion de Local</h3>";
		echo "<table class='FormTable' id='listado' width='100%' align='center' border='1' cellPadding='1' cellSpacing='0' >";
		echo "	<tr style='background:FFFFFF'>";
		echo "    	<td align=center style='width:100px'><b>RUT</td>";
		echo "    	<td align=center><b>Tipo de carga</td>";
		echo "    	<td align=center><b>Descripcion del Error</td>";
		echo "	</tr>";
		// $o->carga_cod=$fecha_carga;
		
		$o->Query_Cuenta_Carga_Log();
		echo "<tr><td colspan=3 align=center><input type=button value='Cerrar' onclick='Finalizar()' ></td></tr>";
		echo "</table>";
		
	}   




}




?>
