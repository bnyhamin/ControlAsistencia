<?php
 require_once("../../Includes/Connection.php");
 require_once("../includes/clsCA_Campanas.php");
 //require_once("../Includes/Seguridad.php");
    
    
 $req = new ca_campanas();
 $req->setMyUrl(db_host());
 $req->setMyUser(db_user());
 $req->setMyPwd(db_pass());
 $req->setMyDBName(db_name());
  //$id_usuario=$_SESSION['usuario_id'];    
 $id_usuario = 3300;
    
 $operacion = isset($_POST["operacion"])?$_POST["operacion"]:$_GET["operacion"];
 $start = isset($_POST["start"])?$_POST["start"]:0;
 $limit = isset($_POST["limit"])?$_POST["limit"]:0;
 $filtro = isset($_POST["filtro"])?$_POST["filtro"]:"";

 switch($operacion){
    case "LISTAR_VALORES":
        $data = array(  
            array('value'=>0,'label'=>'Seleccionar...'),
            array('value'=>1,'label'=>'Agrega'),  
            array('value'=>2,'label'=>'Reemplaza'),  
        );  
        echo json_encode(array(  
            'records'=>$data  
        ));  
        break;
        
    case "LISTAR_CAMPANAS":
        echo $req->Listar_Campanas($start, $limit, $filtro);
        break;
        
    case "GUARDAR_VALOR_TRASPASO":
        $info = $_POST["data"];  
        $data = json_decode(stripslashes($info)); //step 2  
        $campana = $data->Cod_Campana;  //step 3  
        $dias = $data->valor;  
        echo $req->Guardar_Valor_Traspaso($campana, $dias);
        break;
  }

?>