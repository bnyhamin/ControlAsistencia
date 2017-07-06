<?php
header("Expires: 0");
require_once("../includes/Connection.php");
require_once("../includes/clsEmpleados.php");
require_once("../includes/clsCA_Empleado_Rol.php");
require_once("../includes/clsCA_Empleados.php");
require_once("../includes/clsCA_Asistencias.php");

$USR = '';
$PWD = '';
$NPWD ='';
$accion='';
$rpta='';
$msg = '';
$r='';
$rp='';
$msg_caducidad='';
$paginas='';
$rol=0;
$status=0;
$response=array();
$output='';
$envia_python=0;
$ruta = "";
$l_ip =$_SERVER['REMOTE_ADDR'];
$ar_dimensiones=array();
$ar_formGapPython=array();
$rutaredireccion='';
$entrada_salida=0;
$caducidadporterminar=0;

if (isset($_POST['vp_usuario'])) $USR=$_POST['vp_usuario'];
else if (isset($_GET['vp_usuario'])) $USR=$_GET['vp_usuario'];

if (isset($_POST['vp_clave'])) $PWD=$_POST['vp_clave'];
else if (isset($_GET['vp_clave'])) $PWD=$_GET['vp_clave'];

if (isset($_POST['action'])) $accion=$_POST['action'];
else if (isset($_GET['action'])) $accion=$_GET['action'];

switch ($accion) {
case 'getLogin':
    
    $obj = new Empleados();
    $obj->setMyUrl(db_host());
    $obj->setMyUser(db_user());
    $obj->setMyPwd(db_pass());
    $obj->setMyDBName(db_name());
    
    $objemp = new ca_empleados();
    $objemp->setMyUrl(db_host());
    $objemp->setMyUser(db_user());
    $objemp->setMyPwd(db_pass());
    $objemp->setMyDBName(db_name());
    
    $o = new ca_empleado_rol();
    $o->setMyUrl(db_host());
    $o->setMyUser(db_user());
    $o->setMyPwd(db_pass());
    $o->setMyDBName(db_name());
    
    $objas = new ca_asistencia();
    $objas->setMyUrl(db_host());
    $objas->setMyUser(db_user());
    $objas->setMyPwd(db_pass());
    $objas->setMyDBName(db_name());
    
    //VERIFICAMOS CREDENCIALES O EXPIRACION DE CLAVE
    $rpta = $obj->verificar_USR_PWD($USR, $PWD, $NPWD);

    switch ($rpta){
    case 'OK'://CREDENCIALES CORRECTAS Y NO EXPIRA CLAVE
        
        //INICIALIZAR VARIABLES DE SESION
        session_start();
        //echo "id".session_id()."<br/>";
        $r=$obj->Query();
        //DESTRUIR SESION
        if (isset($_SESSION["empleado_codigo"])){
            //unset ($_SESSION["empleado_codigo"]);
            unset ($_SESSION);
            session_destroy();
        }
        //echo $_SESSION["empleado_codigo"]."@".$_SESSION["empleado_nombres"]."@".$_SESSION["tca"]."@".$_SESSION["tda"]."@rol".$_SESSION["rc"];
        
        if(!isset ($_SESSION["empleado_codigo"])) $_SESSION['empleado_codigo'] = $obj->empleado_codigo;
        
        $msg_caducidad = $obj->msg_caducidad;
        if ($msg_caducidad){//MENSAJE CUANDO ESTA POR TERMINAR LA CADUCIDAD
            $caducidadporterminar=1;
            $msg=$msg_caducidad;
        }
        
        if(!isset ($_SESSION["empleado_nombres"])) $_SESSION["empleado_nombres"]=$obj->empleado_nombres;
        if(!isset ($_SESSION["empleado_dni_nro"])) $_SESSION["empleado_dni_nro"]=$obj->empleado_dni;
        
        $objemp->empleado_codigo=$_SESSION["empleado_codigo"];
        $r=$objemp->Query();
        
        if(!isset ($_SESSION["tca"])) $_SESSION["tca"]=$objemp->turno_codigo;
        if(!isset ($_SESSION["tda"])) $_SESSION["tda"]=$objemp->turno_descripcion;
        
        $o->empleado_codigo = $_SESSION["empleado_codigo"];
        $objas->empleado_codigo=$_SESSION["empleado_codigo"];
                    
        $paginas=$objas->empleado_rol_pagina();
        if(!isset ($_SESSION["empleado_paginas"])) $_SESSION["empleado_paginas"]=strtoupper($paginas);
                	
        $rp = $o->Devolver_rol();
        $rol=$o->rol_codigo;//rol_codigo
        if(!isset ($_SESSION["rc"])) $_SESSION["rc"]=$o->rol_codigo;
        
        //echo $_SESSION["empleado_codigo"]."@".$_SESSION["empleado_nombres"]."@".$_SESSION["tca"]."@".$_SESSION["tda"]."@rol".$_SESSION["rc"];
        
        $envia_python = 0;
        $emp = $_SESSION["empleado_codigo"];
        
        if ($rol==0){//SIN ROLES EN GAP
            $sizewidth=2;
            $sizeheight=2;
            $sizex=500;
            $sizey=300;
            $cod=$objas->validar_asistencia();
            
            if($cod==0){
                $rpta = $objas->validar();
                if($objas->asistencia_codigo==0){ 
                    $ruta="registrar_asistencia.psp"; // nueva asistencia
                    $envia_python = 1;
                    $tip = 1;
                }else{
                    $ruta="registrar_asistencia.psp"; // nueva asistencia
                    $envia_python = 1;
                    $cod = $objas->asistencia_codigo;
                    $tip = 1;
                }
            }else{
                $msg="Su asistencia ya tiene entrada y salida registradas!!";
                $entrada_salida=1;
                //document.location.href = "login.php";
            }
            
        }else{//CON ROLES
            $sizewidth=1;
            $sizeheight=1;
            $sizex=3;
            $sizey=3;
            $ruta="menu.php?mostrarEstadistica=E";
            $rutaredireccion=$ruta;
        }
        $ar_dimensiones=array('sizewidth'=>$sizewidth,'sizeheight'=>$sizeheight,'sizex'=>$sizex,'sizey'=>$sizey);
        //$status=1;//1:EXITO
        $status=$entrada_salida==1 ? 0 : 1;
        
        if($envia_python == 1){//SIN ROLES EN GAP REGRESAR DATOS A formGapPython
            $ar_formGapPython=array('cod'=>$cod,'tip'=>$tip,'emp'=>$emp,'ip'=>$l_ip);
            $rutaredireccion=$url_gap_python.$ruta;
            
        }else{//LLAMAR A VENTANA MENU
            $ar_formGapPython=array('cod'=>'','tip'=>'','emp'=>'','ip'=>'');
        }
        
    break;
    case 'CMB':
        $msg = "Su Clave se cambio con Exito, utilice su Nueva Clave para ingrese al sistema.";
    break;
    case 'MPD':
        $msg = "Debe cambiar su Clave ahora!. seleccione el enlace cambiar clave AQUI.";
        $PWD="";
    break;
    case 'NRPD':
        $msg = "Clave de acceso incorrecta, nueva clave debe ser diferente a la actual!";
        $PWD="";
    break;
    case 'NRUS':
        $msg = "Clave de acceso incorrecta, clave de acceso debe ser diferente al DNI!";
        $PWD="";
    break;
    case 'ERROR':
        $msg = "Usuario/Clave incorrecta!, intentelo nuevamente";
        $PWD="";
    break;
    default:
        $msg = $rpta;
        $PWD="";
    break;

    }
    
    $response = array(
        'success'=>true,
        'status'=>$status,
        'envia_python'=>$envia_python,
        'respuesta'=> utf8_encode($msg),
        'dimensiones'=>$ar_dimensiones,
        'dataform'=>$ar_formGapPython,
        'rutaredireccion'=>$rutaredireccion,
        'entrada_salida'=>$entrada_salida,
        'caducidadporterminar'=>$caducidadporterminar
    );
    
    $output = json_encode($response);
    echo $output;
    
break;
}
?>