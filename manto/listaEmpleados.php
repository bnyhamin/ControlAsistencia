<?php
session_start();
header("Expires: 0");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/clsIncidencias.php");
require_once("../includes/clsCA_Empleados.php");

$search="";
$incidencia=0;
$empleado=0;
$descripcion="";

$i = new incidencias();
$i->MyUrl = db_host();
$i->MyUser= db_user();
$i->MyPwd = db_pass();
$i->MyDBName= db_name();

$e = new ca_empleados();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();
$usuario_id = $_SESSION["empleado_codigo"];

if(isset($_GET["strbuscar"])) $search= $_GET["strbuscar"];
if(isset($_GET["hddincidencia"])) $incidencia=$_GET["hddincidencia"];

if (isset($_POST["hddaccion"])){
    if ($_POST["hddaccion"]=='SVE'){
        $incidencia=$_POST["hddincidencia"];
        $empleado=$_POST["hddempleado"];
        $search=$_POST["hddsearch"];
        $descripcion=$_POST["hdddescripcion"];
        
        $i->incidencia_codigo=$incidencia;
        $i->empleado_codigo=$empleado;
        $i->usuario_registra=$usuario_id;
        
        if($i->validaEmpleadoIncidencia()=="NO"){
            echo "Empleado ya tiene asignada la incidencia";
        }else{
            $rpta=$i->agregarEmpleadoaIncidencia();
            //validar si ya se encuentra en el rol
            
            if($i->validaRolExistente()!="NO"){
                $rpta_rol=$i->agregarEmpleadoaRolValidador();
            }
            
            if($rpta=="OK"){
?>
                <script type="text/javascript">
                    var codigo="<?php echo $empleado;?>";
                    var descripcion="<?php echo $descripcion;?>";
                    var v_search="<?php echo $search;?>";
                    window.opener.filtroAreas(codigo, descripcion,v_search);
                    window.close();
                </script>
<?php
            }else{
                echo "Error1".$rpta;
                echo "Error2".$rpta_rol;
            }
        }
    }
}
?>

<html> 
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Seleccionar Empleado</title>
<meta http-equiv="pragma" content="no-cache"/>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css"/>
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript">
function cmdEnviar(codigo,descripcion){
    document.frm.hddaccion.value="SVE";
    document.frm.hddempleado.value=codigo;
    document.frm.hdddescripcion.value=descripcion;
    document.frm.submit();
}
function cerrar(){
    window.close();
}
</script>
</head>
<body class="PageBODY">
<center class="FormHeaderFont">Seleccione Area</center>
<form name='frm' id='frm' action="<?php echo $_SERVER['PHP_SELF']; ?>" method='post'>
<?php 
    $cadena=$e->listaEmpleado($search);
    echo $cadena;
?>
<br/>
<table border="0" align="center">
<tr align="center">
    <td>
        <input type="button" class="Button" id="cmdg" name="cmdg" value="Cancelar" style="width:80px"  onClick="cerrar()"/>
    </td>
</tr>
</table>
<input type="hidden" id="hddaccion" name="hddaccion" value=""/>
<input type="hidden" id="hddincidencia" name="hddincidencia" value="<?php echo $incidencia;?>"/>
<input type="hidden" id="hddempleado" name="hddempleado" value=""/>
<input type="hidden" id="hdddescripcion" name="hdddescripcion" value=""/>
<input type="hidden" id="hddsearch" name="hddsearch" value="<?php echo $search;?>"/>

</form>
</body>
</html>
