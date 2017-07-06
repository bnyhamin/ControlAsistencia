<?php header("Expires: 0");
  // ini_set('display_errors', 'On');
  //   error_reporting(E_ALL);
require_once("../includes/Seguridad.php");   
require('../../Includes/Connection.php');
require('../../Includes/Constantes.php');
require_once("../../Includes/mantenimiento.php");
require('../includes/bio_Tarjeta_Proximidad.php');


$o = new bio_TarjetaProximidad();
$o->MyDBName= db_name();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();


  if($_GET['accion'] == 'guardar'){

    if(!empty($_GET['permiso_id'])){
        $o->desactivarTarjetaPlataforma($_GET['permiso_id']); 
        echo"<script language='javascript'> window.location.replace('bio_plataforma_tarjeta_asignado.php?tarjeta_id=".$_GET['tarjeta_id']."');</script>";  
        exit();
    }else{
       $o->desactivarTarjetaPlataforma($_POST['noactivo']);          
       exit();
    }
    
  }

?>
<html>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
	
  <link rel="stylesheet" type="text/css" href="../../default.css"/>
	<script language="JavaScript" src="../../default.js"></script>
  <script language="JavaScript" src="../jscript.js"></script>
<?php  require_once('../includes/librerias_easyui.php');?>


  <title>Plataformas Asignadas a Tarjetas de Visita</title>
<script language='javascript'>
   
   function Reg(){
  
      valor='';
      var r=document.getElementsByTagName('input');
      
      for (var i=0; i< r.length; i++) {      
              	var o=r[i];
                
              	if (o.id.substring(0,3)=='chk' && o.id !='chk_todos') { 
              		if (o.checked) {
              			valor= o.value;
              		}
              	} 
      }
      
     
      if ( valor =='' ){
      
           alert('Seleccione Registro');
    			 return false;
      }
           return true;
    }

   
    function guardar()
    {
       if (Reg()==false) return false;
       
        var codigos='';
       
        var nodos=document.getElementsByTagName("input");
    
        
        for(var i=0;i<nodos.length;i++){
            if(nodos[i].type=="checkbox" && nodos[i].id.substring(0,3)=='chk'){
                if(nodos[i].checked){
                    
                    if (nodos[i].value!='on'){
                       if (codigos=='')
                            codigos=nodos[i].value;
                       else
                            codigos+= ',' + nodos[i].value;
                    }
                    
                }
            }
        }
        
        document.frm.noactivo.value=codigos;
       
        if(confirm("Estas seguro de continuar?")){
            document.frm.submit();
            self.close();
        }else{
        
          deseleccionarCheckboxes();
        }

    } 
    
    
    function checkear(){
   
      if(document.frm.chk_todos.checked){
         checkear_todos(true);
      }
      else{
         checkear_todos(false);
        }
        
    }

    function checkear_todos(flag){
    
      var r=document.getElementsByTagName('input');  
      for (var i=0; i< r.length; i++) {

           var o=r[i];
           var oo=o.id;
           
           if(oo.substring(0,3)=='chk' &&  !o.disabled){
    	       if(o.checked)
    	   		{
    	   			o.checked=flag;
    	   		}else{
    	   			o.checked=flag;
    	   		}
    	   }    
      }
    }

    function desactivaPermisoEmpleadoPlataforma(permisoID) {
    
      if(document.getElementById("chkpermiso_"+permisoID).checked==true){
      
            if(confirm("Deseas desactivar el permiso a esta plataforma?")){
      
              window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?accion=guardar&tarjeta_id=<?php echo $_GET['tarjeta_id'];?>&permiso_id='+permisoID;
            }
      
            document.getElementById("chkpermiso_"+permisoID).checked=false; 
      
      }
      
    }
    
    function deseleccionarCheckboxes(){

      var r=document.getElementsByTagName('input');
      
      for (var i=0; i< r.length; i++) {
              	var o=r[i];
              	if (o.id.substring(0,3)=='chk') { 
              		  o.checked = false; 
              	
              	} 
      }
   } 

 </script>
 
 
</head>

<body>
    
    <form name='frm' id='frm' action='<?php echo $_SERVER['PHP_SELF']; ?>?accion=guardar' method='post' > 
    <input id ='noactivo' type='hidden' name='noactivo' value='' />

    <table id="table" class="easyui-datagrid" style='width:100%'>

      <thead>

       <tr class='cabecera'>
          <th field="Nro">Nro</th>
          <th field="Plataforma">Plataforma</th>
          <th field="Desde">Desde</th> 
          <th field="Hasta">Hasta</th>
          <th field="Observaci&oacute;n">Observaci&oacute;n</th>
          <th field="Activo">Activo</th>
          <th field="Usuario Registro">Usuario Registro</th>
          <th field="Fecha Asignaci&oacute;n">Fecha Asignaci&oacute;n</th>
          <th field="Usuario Desactivaci&oacute;n">Usuario Desactivaci&oacute;n</th>
          <th field="Fecha Desactivaci&oacute;n">Fecha Desactivaci&oacute;n</th>
          <th field="status"> <input type="checkbox" name="chk_todos" id= "chk_todos" onclick="checkear()"/></th>
         
      </tr>
      </thead>
          <?php
              $o->listarPlataformasAsignadasTarjetas($_GET['tarjeta_id']);
          ?>
      </table>
      <br>
     <table class='sinborde' cellspacing='0' cellpadding='0' border='0' align='center' style='width:150px'>
      <tr align='left'>
       <td>
         	<input name='cmdGuarda' id='cmdGuarda' type='button' value='Desactivar' class='buttons' style='width:70px' onclick='guardar();'/>
       	  <input name='cmdCerra'  id='cmdCerra'  type='button' value='Cerrar' class='buttons' style='width:70px' onclick='window.close();'/> 
       </td>
      </tr>
      </table>
      </form>

      <script type="text/javascript">
        $(document).ready(function(){
            $('#table').datagrid({singleSelect:1});
        });

      </script>
</body>
</html>