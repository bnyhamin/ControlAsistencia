<?php header("Expires: 0");
session_start();
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../includes/clsCA_Turnos_Empleado.php");
require_once("../../Includes/clswfm_disponibilidad_diaria.php");
require_once("../../Includes/clsEmpleados.php");
set_time_limit(30000); 

$empleado_codigo_registro=$_SESSION["empleado_codigo"];
$carga_codigo=date("YmdHis");

$o = new ca_turnos_empleado();
$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());

$w = new wfm_disponibilidad_diaria();
$w->setMyUrl(db_host());
$w->setMyUser(db_user());
$w->setMyPwd(db_pass());
$w->setMyDBName(db_name());

$e = new Empleados;
$e->setMyUrl(db_host());
$e->setMyUser(db_user());
$e->setMyPwd(db_pass());
$e->setMyDBName(db_name());


?>
<script language='javascript'>
function volver(){
	self.location.href='subir_archivoDisponibilidad.php';	
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
	include("subir_archivoDisponibilidad.php"); //Si no hemos cargado, mostramos el formulario   
}else{
	//--Agregado 20091012 para guardar el archivo en directorio cargas
	$temp_file = $_FILES['pix']['tmp_name']; 
	$na_origen = $_FILES['pix']['name'];
	$aux = explode(".",$na_origen);
	$na_carga = substr($aux[0],0,20)."_".$carga_codigo."_".$empleado_codigo_registro.".".$aux[1];
	$destination_two = 'cargas'."\\".$na_carga;
	copy($temp_file,$destination_two);
	$o->empleado_codigo_registro = $empleado_codigo_registro;
	$o->nombre_archivo_origen = $na_origen;
	$o->nombre_archivo_carga = $na_carga;
	$o->carga_codigo = $carga_codigo;
	$o->Addnew_Turno_Carga();
	//--fin
	if($_FILES['pix']['tmp_name'] == "none") { //Si no se ha cargado o es más grande de lo especificado   
	    echo "<b>El archivo no se ha podido cargar. El tamaño debe ser menor que 5000Kb.<br>";   
		include("subir_archivoDisponibilidad.php"); //mostramos de nuevo el formulario   
		exit();   
	}
	//if(!ereg("image",$_FILES['pix']['type'])) //si no es una imagen   
	if(!ereg("text/plain",$_FILES['pix']['type'])) { //si no es un archivo de texto   
		echo "<b>El archivo no tiene el formato deseado. Intenta otro archivo.</b><br>";   
		include("subir_archivoDisponibilidad.php");   
		exit();   
	}else{ //Si todo está bien, es una imagen y se cargó completa   
		//$destination = 'c:\data'."\\".$_FILES['pix']['name'];
		//$o->Delete_Importar_Tmp_T();
		$_FILES['pix']['name']='tmp.txt'; // nuevo nombre
		$destination = $_FILES['pix']['name'];
		$temp_file = $_FILES['pix']['tmp_name'];   
		move_uploaded_file($temp_file,$destination); //Lo movemos de la ubicación temporal a la que queramos.
		//echo "<p><b>Archivo cargado satisfa:</b>{$_FILES['pix']['name']}({$_FILES['pix']['size']})</p>";
		$archivo = file("tmp.txt"); 
		$lineas = count($archivo); 
		
		for($i=0; $i < $lineas ; $i++){ 
			//echo "archivo" .$archivo[$i]."<br>";
			$columna = explode('	',$archivo[$i]);
			//$columna = split('\t',$archivo[$i]);
			//echo $columna;
			
			if ($columna[0]==null || $columna[1]==null || $columna[2]==null){
				echo "<b>El archivo contiene caracteres extraños. Intenta con archivo de texto nuevo.</b><br>";   
				include("subir_archivoDisponibilidad.php");   
				exit();   
			}

			$empleado_dni =$columna[0];
			$codigo_empleado=$e->EmpleadoDNI($empleado_dni);
			
			$dd_codigo = $columna[1];
			$dd_codigo=substr($dd_codigo,1,3);
			$dd_codigo = $dd_codigo*1;

			$dh_codigo = $columna[2];
			$dh_codigo=substr($dh_codigo,1,3);
			$dh_codigo = $dh_codigo*1;
                        
			$w->Update_Empleado_Indicador($dd_codigo , $dh_codigo , $codigo_empleado);
		}
		//include("subir_archivoWFM.php");
		
		//echo "<img align=left style='CURSOR: hand' src='../images/left.gif' onclick='volver();' WIDTH='18' HEIGHT='18' title='Retornar'>";
		//echo "<br>";
		//echo "<img style='CURSOR: hand' src='../images/contratos/excel_ico.gif' onclick='exportarExcel();' WIDTH='25' HEIGHT='25' alt='Imprimir' title='Exportar Excel'>";
		//echo "<table class='FormTable' id='listado' width='100%' align='center' border='1' cellPadding='1' cellSpacing='0' >";
		//echo "	<tr style='background:FFFFFF'>";
		//echo "    	<td align=center style='width:100px'><b>Dni</td>";
		//echo "    	<td align=center><b>Descripcion del Error</td>";
		//echo "	</tr>";
		//$o->carga_codigo=$carga_codigo;
		//$o->Query_Turnos_Log();
		//echo "</table>";
		//echo "<img align=left style='CURSOR: hand' src='../images/left.gif' onclick='volver();' WIDTH='18' HEIGHT='18' title='Retornar'>";
		//echo " <script language='javascript'> window.opener.document.forms['frm'].submit();window.close(); </script> ";
                echo " <script language='javascript'> parent.windowv.document.forms['frm'].submit(); parent.windowv.hide(); </script> ";
	}   
}   
?> 