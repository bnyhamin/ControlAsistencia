<?php
header("Expires: 0");
session_start();
$pagina_actual=$_SERVER['PHP_SELF'];

//$pagina_actual = str_replace("controlasistenciax", "controlasistencia", $pagina_actual);


if(!isset($_SESSION['empleado_paginas']) ){

    //echo '<script type="text/javascript">top.location="/SisPersonal01/ControlAsistencia/sesionreinicio.php";</script>';
    echo '<script type="text/javascript">top.location="/ControlAsistencia/sesionreinicio.php";</script>';

    //session_destroy();
    //header("Location: /sispersonal01/controlasistencia/login.php?mensaje=Ud no est asutorizado");
    //echo '<script type="text/javascript">top.location="/sispersonal01/controlasistencia/login.php";</script>';
    //header("Location: /sispersonal01/sesionreinicio.php");//ok
    exit;
}


$arr=explode('|',$_SESSION['empleado_paginas']);

if (!in_array(strtoupper($pagina_actual), $arr)){
    //header("Location: /SisPersonal01/ControlAsistencia/login.php?mensaje=Lo sentimos!!. Ud. no esta autorizado para acceder a esta pagina. Comuniquese con el administrador del sistema");
    header("Location: /ControlAsistencia/login.php?mensaje=Lo sentimos!!. Ud. no esta autorizado para acceder a esta pagina. Comuniquese con el administrador del sistema");
    //header("Location: /sispersonal01/error.php?page=$pagina_actual");
    session_destroy();
    exit;
}

?>