<?php header("Expires: 0");
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/clsEmpleados.php");//mcortezc@atentoperu.com.pe
require_once("../includes/clsGmenu.php");//mcortezc@atentoperu.com.pe
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

//mcortezc@atentoperu.com.pe
$area=0;
$objempleado = new empleados();
$objempleado->MyUrl = db_host();
$objempleado->MyUser= db_user();
$objempleado->MyPwd = db_pass();
$objempleado->MyDBName= db_name();
$objempleado->empleado_codigo = $empleado_codigo_registro;
$rpta = $objempleado->Empleado_Area();
if($rpta=='OK'){
	$area=$objempleado->area_codigo;
}
//rol administrador
$esadmin="NO";
$o->empleado_codigo_registro = $empleado_codigo_registro;
$esadmin=$o->Query_Rol_Admin();
//rol GTR - Suplencia Diaria Toda la Empresa
$esgtr="NO";
$esgtr=$o->Query_Rol_Numero(16);
//rol GTR - Suplencia Diaria Area de Usuario
$esgtr_area="NO";
$esgtr_area=$o->Query_Rol_Numero(17);

$gc = new gmenu();
$gc->setMyUrl(db_host());
$gc->setMyUser(db_user());
$gc->setMyPwd(db_pass());
$gc->setMyDBName(db_name());
$gc->empleado_codigo=$empleado_codigo_registro;

//CONTROLAR CARGA SUPLENCIAS
$controlar=FALSE;
if($gc->getVerificaRol($empleado_codigo_registro,3)==1){//EXISTE 3.ROL Administrador
    $controlar=FALSE;
}else if($gc->getVerificaRol($empleado_codigo_registro,16)==1){//EXISTE ROL 16. GTR - Suplencia Diaria Toda la Empresa
    $controlar=FALSE;
}else if($gc->getVerificaRol($empleado_codigo_registro,17)==1){//EXISTE ROL 17. GTR - Suplencia Diaria Area de Usuario
    $controlar=TRUE;
}else{//DEFAULT CUALQUIER OTRO ROL NO SERA VERIFICADA EL AREA
    $controlar=FALSE;
}

/*
echo $area.'@esadmin@'.$esadmin.'@esgtr@'.$esgtr.'@esgtrarea@'.$esgtr_area;
if ($controlar){
    echo 'controla';
}else{
    echo 'no controla';
}

exit;*/

?>
<script language='javascript'>
function volver(){
	self.location.href='subir_archivog.php';	
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
	include("subir_archivo.php"); //Si no hemos cargado, mostramos el formulario   
}else{   
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
		include("subir_archivo.php"); //mostramos de nuevo el formulario   
		exit();   
	}
	//if(!ereg("image",$_FILES['pix']['type'])) //si no es una imagen   
	if(!ereg("text/plain",$_FILES['pix']['type'])) { //si no es un archivo de texto   
		echo "<b>El archivo no tiene el formato deseado. Intenta otro archivo.</b><br>";   
		include("subir_archivo.php");   
		exit();   
	}else{ //Si todo está bien, es una imagen y se cargó completa   
		//$destination = 'c:\data'."\\".$_FILES['pix']['name'];
//		$o->Delete_Importar_Tmp();
		$_FILES['pix']['name']='tmg.txt'; // nuevo nombre
		$destination = $_FILES['pix']['name'];
		$temp_file = $_FILES['pix']['tmp_name'];   
		move_uploaded_file($temp_file,$destination); //Lo movemos de la ubicación temporal a la que queramos.
		//echo "<p><b>Archivo cargado satisfa:</b>{$_FILES['pix']['name']}({$_FILES['pix']['size']})</p>";
		$archivo = file("tmg.txt"); 
		$lineas = count($archivo);
	    // otro tipo 1 // $comando = "C:\\pdi-open-3.0.4-GA\\ca_carga_turnos.bat";
	    // otro tipo 2 // $rpta = shell_exec($comando); 
		if (substr($archivo[0],0,2)=='ÿþ' or substr($archivo[0],0,2)=='þÿ' or substr($archivo[0],0,3)=='ï»¿'){
			echo "<center><b><font color=red>El archivo tiene una codificacion extraña. Edite el archivo con el Block de notas y utilice la opcion guardar como, en la seccion codificación seleccione ANSI. Grabe y vuelva a cargar el archivo</font></b><br></center>";
			include("subir_archivo.php");   
			exit();   
		}
		if (substr($archivo[0],0,2)=='' and $lineas==0){
			echo "<center><b><font color=red>El archivo no tiene informacion verifique</font></b><br></center>";
			include("subir_archivo.php");   
			exit();   
		}
		// $array_dependientes = $o->EmpleadosACargo($empleado_codigo_registro);
		$columna = array();
                $emp_cod=0;//mcortezc@atentoperu.com.pe
		$emp_area=0;//mcortezc@atentoperu.com.pe
                
		for($i=0; $i < $lineas; $i++){ 
			//echo $archivo[$i]."<br>";
			$columna = split('	',$archivo[$i]);
			/*
                        echo $columna[0]."<br>";
			echo $columna[1]."<br>";
			echo trim($columna[2])."<br>";
                        */
                        
                        //if(in_array($columna[0], $array_dependientes)){
			$o->empleado_dni = $columna[0];
			$o->te_fecha_inicio = $columna[1];
			$o->tc_codigo_sap = trim($columna[2]);
			$o->empleado_codigo_registro=$empleado_codigo_registro;
			$o->carga_codigo=$carga_codigo;
			$o->te_fecha_fin="";
                        //$o->Importar_Gestion(); //usado para carga directa por cada registro del txt
                        
                        //mcortezc@atentoperu.com.pe
                        if ($controlar){//solo cargar suplencias de empleados de la misma area del GTR - Suplencia Diaria Area de Usuario
                            $emp_cod=$objempleado->EmpleadoDNI($columna[0]);
                            $objempleado->empleado_codigo = $emp_cod;
                            $objempleado->Empleado_Area();
                            $emp_area=$objempleado->area_codigo;
                            if($emp_area*1==$area*1) $o->Addnew_Tmp_Nuevo();
                            
                            //echo 'iguales@emp_cod@'.$emp_cod.'@emp_area@'.$emp_area.'@area_rol_gtr_area@'.$area.'<br>';
                            //echo '@emp_cod@'.$emp_cod.'@emp_area@'.$emp_area.'@area_rol_gtr_area@'.$area.'<br>';
                        }else{
                            $o->Addnew_Tmp_Nuevo();
                        }
                        
			//}
		}
		$o->empleado_codigo_registro=$empleado_codigo_registro;
		$o->carga_codigo=$carga_codigo;
		if (isset($_POST['pasado'])){
			//$o->Importar_Gestion_Tmp_Rezagados();
		}else{
			$o->Importar_Gestion_Tmp();
		}
		echo "<img align=left style='CURSOR: hand' src='../../Images/left.gif' onclick='volver();' WIDTH='18' HEIGHT='18' title='Retornar'>";
		echo "<br>";
		echo "<img style='CURSOR: hand' src='../../Images/Contratos/excel_ico.gif' onclick='exportarExcel();' WIDTH='25' HEIGHT='25' alt='Imprimir' title='Exportar Excel'>";
		echo "<table class='FormTable' id='listado' width='120%' align='center' border='1' cellPadding='1' cellSpacing='0' >";
		echo "	<tr style='background:FFFFFF'>";
		echo "    	<td align=center style='width:100px'><b>Dni</td>";
		echo "    	<td align=center><b>Descripcion del Error</td>";
		echo "	</tr>";
		$o->carga_codigo=$carga_codigo;
		$o->Query_Turnos_Log();
		$o->Delete_Importar_Tmp();
		echo "</table>";
		echo "<img align=left style='CURSOR: hand' src='../../Images/left.gif' onclick='volver();' WIDTH='18' HEIGHT='18' title='Retornar'>";
		echo " <script language='javascript'> window.opener.document.forms['frm'].submit(); </script> ";
	}   
}   
?> 
