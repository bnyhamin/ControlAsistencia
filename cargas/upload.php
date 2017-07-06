<?php
header("Expires: 0");
session_start();
require_once("../../Includes/Connection.php");

$id = $_SESSION["empleado_codigo"];
$file=$HTTP_POST_FILES['file']['name'];
$tipo_file = $HTTP_POST_FILES['file']['type'];
$tamano_file = $HTTP_POST_FILES['file']['size'];
$nombre_file="archivos_excel/".$file;
set_time_limit(320);

?>
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Carga TX</title>
<link rel='stylesheet' type='text/css' href='../style/tstyle.css'>
<script language="JavaScript">
function volver(){
self.location.href="cargatx.php";
}
function buzon(){
self.location.href="../reportes/reportes_generados.php";
}

</script>
</head>
<body class="PageBODY" >
<?php
//compruebo si las características del archivo son las que deseo
$extension = explode(".",$file);
$num = count($extension)-1;
if($extension[$num] != "xls"){
	echo "<br>";
	echo "<br>";
	echo "<center class='CA_FormHeaderFont'>El archivo seleccionado no es un archivo de excel.!!</center><br>";
	echo "<center><font  color=#005279 style='cursor:hand' onclick='return volver()'>[&nbsp;Regresar&nbsp;]</font></center>";

}else{
    if (move_uploaded_file($HTTP_POST_FILES['file']['tmp_name'], $nombre_file)){
       //echo "El archivo ha sido cargado correctamente.";
        $o = new COM("CargaMax.clsCarga") or die("No se puede abrir componente Carga");
		$o->Conn= cnRRHH();
		//$o->Ruta    = "D:\ApacheGroup\Apache2\htdocs\sispersonal01\ControlAsistencia\cargas\archivos_excel";
		//$o->Ruta_log= "D:\ApacheGroup\Apache2\htdocs\sispersonal01\ControlAsistencia\cargas\log_cargas";
		//echo '<br>' . CA_Carga_tx();
		//echo '<br>' . CA_Logs_tx();
		//echo '<br>' . $file;
		$o->Ruta    =CA_Carga_tx();
		$o->Ruta_log=CA_Logs_tx();
		$o->Archivo = $file;
		$o->Usuario=$id;
		$rpta=$o->Carga_TX();
        ?>
        <script languaje='javascript'>
        var rpta="<?php echo $rpta ?>";
        alert(rpta);
        </script>
        <?
		$o=null;
		echo "<br>";
	    echo "<br>";
		echo "<center class='CA_FormHeaderFont'>Carga Terminada.!!. Consulte su bandeja de reportes generados para ver el detalle de su carga de datos</center><br>";
	    echo "<br><br><center><font color=#005279  style='cursor:hand' onclick='return volver()'>[&nbsp;Regresar&nbsp;]</font></center>&nbsp;&nbsp;<center><font color=#005279  style='cursor:hand' onclick='return buzon()'>[&nbsp;Ir a Buzon&nbsp;]</font></center>";

    }else{
       echo "Ocurrió algún error al subir el archivo excel. No pudo se procesaron registros.";
    }
}
?>
</body>
</html>

