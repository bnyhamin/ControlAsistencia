<?php header("Expires: 0");
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../includes/clsCA_Turnos_Empleado.php");
set_time_limit(30000); 
$empleado_codigo_registro=$_SESSION["empleado_codigo"];

$ale=rand(10000000,100000000);
//$carga_codigo=date("YmdHis").''.$_SESSION["empleado_dni_nro"];
$carga_codigo=date("YmdHis").''.$ale;
$o = new ca_turnos_empleado();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

?>
<script language='javascript'>
function volver(){
	self.location.href='subir_archivoe.php';	
}

function exportarExcel() {
	var i;
	var j;
	var mycell;
	var objXL = new ActiveXObject("Excel.Application");
	var objWB = objXL.Workbooks.Add();
	var objWS = objWB.ActiveSheet;
	for (i=0; i < document.getElementById("listado").rows.length; i++)
	{
	    for (j=0; j < document.getElementById("listado").rows(i).cells.length; j++)
	    {
	        mycell = document.getElementById("listado").rows(i).cells(j)
	        objWS.Cells(i+1,j+1).Value = mycell.innerText;
	    }
	}
	objWS.Range("A1", "Z1").EntireColumn.AutoFit();
	objXL.Visible = true;
}

</script>
<?php
if(!isset($_POST['Upload'])) { 
	include("subir_archivoe.php"); //Si no hemos cargado, mostramos el formulario   
}else{
	// die('aaxasxs');

	//--Agregado 20091012 para guardar el archivo en directorio cargas
	$temp_file = $_FILES['pix']['tmp_name']; 
	$na_origen = $_FILES['pix']['name'];
	$aux = explode(".",$na_origen);
	$na_carga = substr($aux[0],0,20)."_".$carga_codigo."_".$empleado_codigo_registro.".".$aux[1];
	//$destination_two = 'cargas'."\\".$na_carga;
        $destination_two = 'cargas'."/".$na_carga;
	copy($temp_file,$destination_two);
	$o->empleado_codigo_registro = $empleado_codigo_registro;
	$o->nombre_archivo_origen = $na_origen;
	$o->nombre_archivo_carga = $na_carga;

	$o->carga_codigo = $carga_codigo;

	$o->Addnew_Turno_Carga();

	//--fin
	if($_FILES['pix']['tmp_name'] == "none") { //Si no se ha cargado o es más grande de lo especificado   
	    echo "<b>El archivo no se ha podido cargar. El tamaño debe ser menor que 5000Kb.<br>";   
		include("subir_archivoe.php"); //mostramos de nuevo el formulario   
		exit();   
	}
	//if(!ereg("image",$_FILES['pix']['type'])) //si no es una imagen   
	if(!ereg("text/plain",$_FILES['pix']['type'])) { //si no es un archivo de texto   
		echo "<b>El archivo no tiene el formato deseado. Intenta otro archivo.</b><br>";   
		include("subir_archivoe.php");   
		exit();   
	}else{ //Si todo está bien, es una imagen y se cargó completa   
		//$destination = 'c:\data'."\\".$_FILES['pix']['name'];
//		$o->Delete_Importar_Tmp_T();
		$_FILES['pix']['name']='tmp.txt'; // nuevo nombre
		$destination = $_FILES['pix']['name'];
		$temp_file = $_FILES['pix']['tmp_name'];   
		move_uploaded_file($temp_file,$destination); //Lo movemos de la ubicación temporal a la que queramos.
		//echo "<p><b>Archivo cargado satisfa:</b>{$_FILES['pix']['name']}({$_FILES['pix']['size']})</p>";
		$archivo = file("tmp.txt"); 
		$lineas = count($archivo);
		if (substr($archivo[0],0,2)=='ÿþ' or substr($archivo[0],0,2)=='þÿ' or substr($archivo[0],0,3)=='ï»¿'){
			echo "<center><b><font color=red>El archivo tiene una codificacion extraña. Edite el archivo con el Block de notas y utilice la opcion guardar como, en la seccion codificación seleccione ANSI. Grabe y vuelva a cargar el archivo</font></b><br></center>";
			include("subir_archivoe.php");   
			exit();   
		}
		if (substr($archivo[0],0,2)=='' and $lineas==0){
			echo "<center><b><font color=red>El archivo no tiene informacion verifique</font></b><br></center>";
			include("subir_archivoe.php");   
			exit();   
		}
		$add ='';
		$columna = array();
		for($i=0; $i < $lineas; $i++){ 
			$columna = split('	',$archivo[$i]);
			$o->empleado_dni = trim($columna[0]);
			$o->te_fecha_inicio = trim($columna[1]);
			$o->tc_codigo_sap = trim($columna[2]);
			$o->empleado_codigo_registro=$empleado_codigo_registro;
			$o->carga_codigo=$carga_codigo;
			$add = $o->Addnew_Tmp();
		}
		if (isset($_POST['pasado'])){
			//$o->Importar_Turnos_Tmp_Rezagados();
		}else{
			 $o->Importar_Eventos_Tmp();
		}

		echo "<img align=left style='CURSOR: hand' src='../../Images/left.gif' onclick='volver();' WIDTH='18' HEIGHT='18' title='Retornar'>";
		echo "<br>";
		echo "<img style='CURSOR: hand' src='../../Images/Contratos/excel_ico.gif' onclick='exportarExcel();' WIDTH='25' HEIGHT='25' alt='Imprimir' title='Exportar Excel'>";
		echo "<table class='FormTable' id='listado' width='100%' align='center' border='1' cellPadding='1' cellSpacing='0' >";
		echo "	<tr style='background:FFFFFF'>";
		echo "    	<td align=center style='width:100px'><b>Dni</td>";
		echo "    	<td align=center><b>Descripcion del resultado</td>";
		echo "	</tr>";
		$o->carga_codigo=$carga_codigo;
		$o->Query_Turnos_Log();
		$o->Delete_Importar_Tmp_T();
		echo "</table>";
		echo "<img align=left style='CURSOR: hand' src='../../Images/left.gif' onclick='volver();' WIDTH='18' HEIGHT='18' title='Retornar'>";
		echo " <script language='javascript'> window.opener.document.forms['frm'].submit(); </script> ";
	}   
}   
?> 
