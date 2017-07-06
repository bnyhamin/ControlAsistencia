<?php
header('Expires: 0');
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Turnos_Empleado.php"); 

$e = new ca_turnos_empleado();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();

$bandeja=0;//default
if(isset($_GET["bandeja"])) $bandeja = $_GET["bandeja"];
$area=0;
if(isset($_GET["area"])) $area = $_GET["area"];
$usuario=0;
if(isset($_GET["usuario"])) $usuario = $_GET["usuario"];
$opcion="1.7";//default
if(isset($_GET["opcion"])) $opcion = $_GET["opcion"];

$controlar=0;//default
if(isset($_GET["controlar"])) $controlar = $_GET["controlar"];


$nombre_usuario = $e->Consulta_Empleado_Generacion($usuario);
?>
<?php
if($opcion=="1.7"){
    $rs = $e->Listar_Empleado_Modalidad($bandeja,$area,$usuario,$controlar);
    if($rs->RecordCount() > 0){
        $i= 0;
        $cadena = "

                <table>
                <tr>
                    <td colspan='8' align = 'center' style='font-weight:bold;font-family:verdana;font-size:12px;'>Reporte de Movimientos de Cambio de Modalidad</td> 
                </tr>
                <tr>
                    <td colspan='8' style='font-family:verdana;font-size:10px;'>Fecha: ".date("d/m/Y h:i")."</td>
                </tr>
                <tr style='font-weight:bold;font-family:verdana;font-size:10px;'>
                    <td style='background-color:#D8DAB4;'>Nro.</td>
                    <td style='background-color:#D8DAB4;'>Empleado</td>
                    <td style='background-color:#D8DAB4;'>DNI</td>
                    <td style='background-color:#D8DAB4;'>Area</td>
                    <td style='background-color:#D8DAB4;'>Fecha Inicio</td>
                    <td style='background-color:#D8DAB4;'>Modalidad</td>
                    <td style='background-color:#D8DAB4;'>Horario</td>
                    <td style='background-color:#D8DAB4;'>estado</td>
                </tr>";
                while(!$rs->EOF){
                    $cadena .= "<tr style='font-family:verdana;font-size:10px;'>
                                    <td>".++$i."</td>
                                    <td>".$rs->fields["2"]."</td>
                                    <td>".$rs->fields["1"]."</td>
                                    <td>".$rs->fields["3"]."</td>
                                    <td>".$rs->fields["4"]."</td>
                                    <td>".$rs->fields["5"]."</td>
                                    <td>".$rs->fields["6"]."</td>
                                    <td>".$rs->fields["7"]."</td>
                                </tr>";
                    $rs->MoveNext();
                }
        $cadena .= "</table>";

    }
}else if($opcion=="1.6"){
    $rs = $e->Listar_Empleado_Vacaciones($area,$usuario,$controlar);
    
    if($rs->RecordCount() > 0){
        $i= 0;
        $cadena = "

                <table>
                <tr>
                    <td colspan='8' align = 'center' style='font-weight:bold;font-family:verdana;font-size:12px;'>Reporte de Vacaciones</td> 
                </tr>
                <tr>
                    <td colspan='8' style='font-family:verdana;font-size:10px;'>Fecha: ".date("d/m/Y h:i")."</td>
                </tr>
                <tr style='font-weight:bold;font-family:verdana;font-size:10px;'>
                    <td style='background-color:#D8DAB4;'>Nro.</td>
                    <td style='background-color:#D8DAB4;'>Empleado</td>
                    <td style='background-color:#D8DAB4;'>Desde</td>
                    <td style='background-color:#D8DAB4;'>Hasta</td>
                    <td style='background-color:#D8DAB4;'>Dias</td>
                    <td style='background-color:#D8DAB4;'>Adelanto</td>
                    <td style='background-color:#D8DAB4;'>Observaciones</td>
                    <td style='background-color:#D8DAB4;'>Estado</td>
                </tr>";
                while(!$rs->EOF){
                    
                    $cadena .= "<tr style='font-family:verdana;font-size:10px;'>
                                    <td>".++$i."</td>
                                    <td>".$rs->fields["0"]."</td>
                                    <td>".$rs->fields["1"]."</td>
                                    <td>".$rs->fields["2"]."</td>
                                    <td>".$rs->fields["3"]."</td>
                                    <td>".$rs->fields["4"]."</td>
                                    <td>".$rs->fields["5"]."</td>
                                    <td>".$rs->fields["6"]."</td>
                                </tr>";
                    $rs->MoveNext();
                }
        $cadena .= "</table>";

    }
    
}else if($opcion=="1.8"){
    
    
    $rs = $e->Listar_Empleado_Movimientos($area,$usuario,$controlar);
    
    if($rs->RecordCount() > 0){
        $i= 0;
        $cadena = "

                <table>
                <tr>
                    <td colspan='8' align = 'center' style='font-weight:bold;font-family:verdana;font-size:12px;'>Reporte Ausentismo Programado</td> 
                </tr>
                <tr>
                    <td colspan='8' style='font-family:verdana;font-size:10px;'>Fecha: ".date("d/m/Y h:i")."</td>
                </tr>
                <tr style='font-weight:bold;font-family:verdana;font-size:10px;'>
                    <td style='background-color:#D8DAB4;'>Nro.</td>
                    <td style='background-color:#D8DAB4;'>DNI</td>
                    <td style='background-color:#D8DAB4;'>Empleado</td>
                    <td style='background-color:#D8DAB4;'>Area</td>
                    <td style='background-color:#D8DAB4;'>Movimiento_Descripcion</td>
                    <td style='background-color:#D8DAB4;'>Desde</td>
                    <td style='background-color:#D8DAB4;'>Hasta</td>
                    <td style='background-color:#D8DAB4;'>Estado</td>
                </tr>";
                while(!$rs->EOF){
                    
                    $cadena .= "<tr style='font-family:verdana;font-size:10px;'>
                                    <td>".++$i."</td>
                                    <td>".$rs->fields["0"]."</td>
                                    <td>".$rs->fields["1"]."</td>
                                    <td>".$rs->fields["2"]."</td>
                                    <td>".$rs->fields["3"]."</td>
                                    <td>".$rs->fields["4"]."</td>
                                    <td>".$rs->fields["5"]."</td>
                                    <td>".$rs->fields["6"]."</td>
                                </tr>";
                    $rs->MoveNext();
                }
        $cadena .= "</table>";

    }
    
    
}

    if (file_exists("datos_modalidad.xls")){
        unlink("datos_modalidad.xls");
    }
  
    $ar=fopen("datos_modalidad.xls","a") or die("Problemas en la creacion");
    fputs($ar,$cadena);
    fputs($ar,"\n");
    fclose($ar);
  
    $ruta_pdf='datos_modalidad.xls';
    $fp=fopen($ruta_pdf,"rb");//solo modo lectura binaria
    $size=filesize($ruta_pdf);
    $fbyte = fread($fp, $size);
    $file1 = "datos_modalidad.xls";
    header('Content-Description: File Transfer');
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"$file1\"\n");
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    echo $fbyte;
  
  
  ?>
    