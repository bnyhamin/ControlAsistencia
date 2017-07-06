<?php
header("Expires: 0");
session_start();
$cod_emp = $_GET["semp"];
$rol_emp = $_GET["srol"];

$valida = true;
if (!isset($_SESSION["empleado_codigo"])){
  $valida = false;
}else{
  if($_SESSION["empleado_codigo"]!=$cod_emp){
    $valida = false;
  }
}
//echo "empses:".$_SESSION["empleado_codigo"]."empform:".$cod_emp;
//exit;
if(!$valida){
?>	
    <script language="JavaScript">  
        window.parent.location.href = 'login.php';
    </script>
<?php
}
//DESTRUIR LA SESION UNA VEZ GENERADA LA SESION EN PYTHON
if ($rol_emp*1==0){//SIN ROLES EN GAP
    unset ($_SESSION);
    session_destroy();
}
/*unset ($_SESSION);
session_destroy();*/

?>