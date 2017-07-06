<?php

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/bio_Plataforma.php"); 
  

$operacion = isset($_POST["operacion"]) ? $_POST["operacion"]: "";


$o = new BIO_Plataforma();


$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());


switch($operacion){
    case    'listar_lectores_asignados':
		
        $o->listarLectoresAsignados($_POST["plataforma_id"]);
        break;
		
	case    'obtener_nombre_plataforma':
		
        
        $o->obtenerNombrePlataforma($_POST["plataforma_id"]);
        break;	
		
		
	case    'listar_lectores':
		
        $o->listarLectores($_POST["plataforma_id"], $_POST["lector_id"] );
        break;
		
	case    'asignar_lector_plataforma':
		

	 	    $_POST["activado"] = (!empty($_POST["activado"]) && $_POST["activado"] == 'on') ? 1:0;
        $o->asignarLectorPlataforma( $_POST["lector_id"], $_POST["plataforma_id"], $_POST["activado"]);
        break;
		
	case    'editar_lector_plataforma':
		
		  $_POST["activado"] = (!empty($_POST["activado"]) && $_POST["activado"] == 'on') ? 1:0;
	    $o->editarLectorPlataforma( $_POST["lector_id"], $_POST["id"],  $_POST["activado"]);
      break;
	
	case    'eliminar_lector_plataforma':
		
        $o->eliminarLectorPlataforma($_POST["id"]);
        break;
        
  case    'verificar_plataformas_asignadas':
		
        $o->verificarPlataformasAsignadas($_POST["lector_id"],$_POST["plataforma_id"], $_POST["edicion"] );
        break;	      
        				
}

?>