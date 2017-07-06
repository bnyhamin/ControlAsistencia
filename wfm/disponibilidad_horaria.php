<?php header('Expires: 0'); ?>
<?php
  require('../../Includes/Connection.php');
  require_once('../../Includes/seguridad.php');
  require('../../Includes/Constantes.php'); 
  require('../../Includes/clswfm_disponibilidad_horaria.php'); 
  require_once("../../Includes/MyCombo.php");
      
      
   $dh = new wfm_disponibilidad_horaria();
   $dh->MyDBName= db_name();
   $dh->MyUrl = db_host();
   $dh->MyUser= db_user();
   $dh->MyPwd = db_pass();
   
   $combo1 = new MyCombo();
   $combo1->setMyUrl(db_host());
   $combo1->setMyUser(db_user());
   $combo1->setMyPwd(db_pass());
   $combo1->setMyDBName(db_name());
   
   $dh_codigo='';
   $dh_indicador='';
   $dh_descripcion='';
   $dh_hora_inicio=-1;
   $dh_hora_fin=-1;
   $dh_minuto_inicio=-1;
   $dh_minuto_fin=-1;
   $dh_activo=0;
   
   
   
?>

<?php 
   $sRpta="";
   $pagina= "";
   $orden= "";
   $buscam= ""; 
   $cadenilla="";

   if (isset($_GET["pagina"])){
       $pagina= $_GET["pagina"];
       $orden= $_GET["orden"];
       $buscam= $_GET["buscam"];
   }else{
       $pagina= $_POST["pagina"];
       $orden= $_POST["orden"];
       $buscam= $_POST["buscam"];
   }

?>


  <?php 
  if (isset($_POST["dh_codigo"])){
   $dh_codigo = $_POST["dh_codigo"]; 
  }else{
   $dh_codigo = $_GET["codigo"]; 
  }
  if (isset($_POST["dh_identificador"])){
   $dh_identificador = $_POST["dh_identificador"]; 
  }
  if (isset($_POST["dh_descripcion"])){
   $dh_descripcion = strtoupper($_POST["dh_descripcion"]); 
  }
  if (isset($_POST["dh_hora_inicio"])){
   $dh_hora_inicio = $_POST["dh_hora_inicio"]; 
  }
  if (isset($_POST["dh_hora_fin"])){
   $dh_hora_fin = $_POST["dh_hora_fin"]; 
  }
  if (isset($_POST["dh_minuto_inicio"])){
   $dh_minuto_inicio = $_POST["dh_minuto_inicio"]; 
  }
  if (isset($_POST["dh_minuto_fin"])){
   $dh_minuto_fin = $_POST["dh_minuto_fin"]; 
  }
  if (isset($_POST["dh_activo"])){
   $dh_activo = $_POST["dh_activo"]; 
  }
  
  if (isset($_POST["hddaccion"])){
     if ($_POST["hddaccion"]=="SVE"){//la orden es grabar
       $dh->dh_descripcion=$dh_descripcion;
       $dh->dh_hora_inicio= $dh_hora_inicio;
       $dh->dh_minuto_inicio= $dh_minuto_inicio;
       $dh->dh_hora_fin= $dh_hora_fin;
       $dh->dh_minuto_fin= $dh_minuto_fin;
       $dh->dh_activo= $dh_activo;
       $dh->usuario_registra= $id_usuario;
       $dh->usuario_modifica= $id_usuario;
       
       if ($_POST["dh_codigo"]==""){
       	
       		$consulta=$dh->verifica_disponibilidad_horaria();
       		if ($consulta != 0){
	   ?>
		<script language='javascript'>
			         
  			alert('Error, rango de disponibilidad horaria ya existe ');
					        
       </script> <?php
       			
       		}else{
       			
	  			$sRpta = $dh->AddNew();
	            $dh_codigo = $dh->dh_codigo;
	            $dh_identificador = $dh->dh_identificador;
	            //$dh_descripcion = $dh->dh_descripcion;       			
       		}       		
    	}
 	    else{
 	    	
 	    	$consulta=$dh->verifica_disponibilidad_horaria_actualizar($dh_codigo);
       		if ($consulta != 0){
	        ?>
			<script language='javascript'>
				         
	  			alert('Error, rango de disponibilidad horaria ya existe en el Sistema.');
						        
	       </script> <?php
       			
       		}else{
       			
 	    	 $dh->dh_codigo=$dh_codigo;
   			 $sRpta = $dh->Update();
   			 //$dh_identificador = $dh->dh_identificador;
             //$dh_descripcion = $dh->dh_descripcion;
           }  
 	    }
       

           if ($sRpta!="OK"){
               echo $sRpta;
           }else{
           	
           	$cadenilla = "<center><font size=2 color=#800000>Los datos se grabaron satisfactoriamente</font></center>";
           	
           }
     }
  }else{
     if (isset($_GET["dh_codigo"])) $dh_codigo= $_GET["dh_codigo"];
     if ($dh_codigo!=""){
        $dh->dh_codigo=$dh_codigo;
        //Recuperar datos del Registro
        $sRpta = $dh->Query();
        if ($sRpta!="OK"){
            echo sRpta;
        }
           $dh_identificador = $dh->dh_identificador;
           $dh_descripcion = $dh->dh_descripcion;
           $dh_hora_inicio = $dh->dh_hora_inicio;
           $dh_minuto_inicio = $dh->dh_minuto_inicio;
           $dh_hora_fin = $dh->dh_hora_fin;
           $dh_minuto_fin = $dh->dh_minuto_fin;
           $dh_activo = $dh->dh_activo;
           $usuario_modifica = $id_usuario;

     }
  } 
?>
<HTML>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv='pragma' content='no-cache'>
<META NAME=author CONTENT='TUMI Solutions S.A.C.'>
<title>Registro Disponibilidad Horaria - <?php echo SistemaNombre(); ?> </title>
<link rel="stylesheet" type="text/css" href="../../default.css">
<script language="JavaScript" src="../../default.js"></script>
</HEAD>
<script type="text/javascript" language="javascript">

function enviar(){
	
	if(document.frm.dh_descripcion.value==''){
	  alert('Indique  valor');
	  document.frm.dh_descripcion.focus();
	  return false;
	}
	if(document.frm.dh_hora_inicio.value==-1){
	  alert('Indique  valor');
	  document.frm.dh_hora_inicio.focus();
	  return false;
	}
	if(document.frm.dh_minuto_inicio.value==-1){
	  alert('Indique  valor');
	  document.frm.dh_minuto_inicio.focus();
	  return false;
	}
	if(document.frm.dh_hora_fin.value==-1){
	  alert('Indique  valor');
	  document.frm.dh_hora_fin.focus();
	  return false;
	}
	if(document.frm.dh_minuto_fin.value==-1){
	  alert('Indique  valor');
	  document.frm.dh_minuto_fin.focus();
	  return false;
	}
	
	var inicio = document.frm.dh_hora_inicio.value + '.' + document.frm.dh_minuto_inicio.value;
	var fin = document.frm.dh_hora_fin.value +	'.' + document.frm.dh_minuto_fin.value;
	
	//alert(inicio*1);
	//alert(fin*1);
	if (fin*1 > inicio*1){
		if ((fin*1 - inicio*1) > 12 )
	    {
			 alert('Error, El rango horario debe ser menor o igual a 12 horas');
		     return false;
        }
		
	}else{
		if (24 - inicio*1 + fin*1 > 12){
			alert('Error, El rango horario debe ser menor o igual a 12 horas');
            return false;
		}
	}
	
	
	
  	if (confirm("Guardar cambios")==false) return false;
	document.frm.hddaccion.value='SVE'
	document.frm.submit();
	
}

function cerrar(){
var pg="";
pg = "main_disponibilidad_horaria.php"
location.href = pg + "?pagina=<?php echo $pagina ?>&orden=<?php echo $orden ?>&buscam=<?php echo $buscam?>";

}

</script>
<body bgcolor='#ffffff' text='#000000' link='#000000' alink='#999999' vlink='#000000'   Class='PageBody'  >

<p class=TITOpciones>Registro de Disponibilidad Horaria</p>

<form name='frm' id='frm' action='<?php echo $PHP_SELF; ?>' method='post' >
<table  class='table' width='70%' align='center' cellspacing='1' cellpadding='0' border='0'>
<tr>
 <td  class="ColumnTD" align='right'>
     Código &nbsp;</td>
 <td  class='DataTD'>
     <Input  class='Input' type='text' name='dh_codigo' id='dh_codigo' value='<?php echo $dh_codigo?>' size='6' readonly  >
 </td>
</tr>
<tr>
 <td  class="ColumnTD" align='right'>
     Código Identificador &nbsp;</td>
 <td  class='DataTD'>
     <Input  class='Input' type='text' name='dh_identificador' id='dh_identificador' value='<?php echo $dh_identificador?>' size='6' readonly  >
 </td>
</tr>
<tr>
 <td  class="ColumnTD" align='right'>
     Descripción &nbsp;</td>
 <td  class='DataTD'>
     <Input  class='Input' type='text' name='dh_descripcion' id='dh_descripcion' value='<?php echo $dh_descripcion?>' size='40' maxlength='80'> 
 </td>
</tr>
<tr>
	<td class='ColumnTD' align='right'>Inicio&nbsp;</td>
	<td class='DataTD'>
		Horas&nbsp;<select  class='select' name='dh_hora_inicio' id='dh_hora_inicio' >
		  <option value="-1">hh</option>
		  <?php
		   for($h=0; $h < 24; $h++){
		     $hh=$h;
             if(strlen($h)<=1) $hh="0".$h;
				 if ($h==$dh_hora_inicio) echo "\t<option value=". $h . " selected>". $hh ."</option>" . "\n";
			     else 
				 echo "\t<option value=". $h . ">". $hh ."</option>" . "\n";
		   }
		  ?>
		 </select>
		 &nbsp;:&nbsp;Minutos&nbsp;
		 <select  class='select' name='dh_minuto_inicio' id='dh_minuto_inicio' >
		  <option value="-1">mm</option>
		  <?php
		   for($m=0; $m < 60; $m++){
		     $mm=$m;
             if(strlen($m)<=1) $mm="0".$m;
		      if($m==$dh_minuto_inicio) echo "\t<option value=". $m ." selected>". $mm ."</option>" . "\n";
			  else echo "\t<option value=". $m . ">". $mm ."</option>" . "\n";
		   }
		  ?>
		 </select> 
	</td>
</tr>
<tr>
	<td class='ColumnTD' align='right'>Fin&nbsp;</td>
	<td class='DataTD'>
      Horas&nbsp;<select  class='select' name='dh_hora_fin' id='dh_hora_fin' >
		  <option value="-1">hh</option>
		  <?php
		   for($h=0; $h < 24; $h++){
		     $hh=$h;
             if(strlen($h)<=1) $hh="0".$h;
				 if ($h==$dh_hora_fin) echo "\t<option value=". $h . " selected>". $hh ."</option>" . "\n";
			     else echo "\t<option value=". $h . ">". $hh ."</option>" . "\n";
		   }
		  ?>
		 </select>
		 &nbsp;:&nbsp;Minutos&nbsp;
		 <select  class='select' name='dh_minuto_fin' id='dh_minuto_fin' >
		  <option value="-1">mm</option>
		  <?php
		   for($m=0; $m < 60; $m++){
		     $mm=$m;
             if(strlen($m)<=1) $mm="0".$m;
		      if($m==$dh_minuto_fin) echo "\t<option value=". $m ." selected>". $mm ."</option>" . "\n";
			  else echo "\t<option value=". $m . ">". $mm ."</option>" . "\n";
		   }
		  ?>
		 </select> 
	</td>
</tr>





<tr>
 <td  class="ColumnTD" align='right'>
     Activo &nbsp;</td>
 <td  class='DataTD'>
     <Input  class='Input' type='checkbox' name='dh_activo' id='dh_activo' value='1' <?php if ($dh_activo*1==1) echo 'checked' ?>>
 </td>
</tr>

<tr>
 <td colspan=2  class='ColumnTD'>&nbsp;
 </td>
</tr>
<tr align='center'>
 <td  colspan=2  class='ColumnTD'>
   <input name='cmdGuardar' id='cmdGuardar' type='button' value='Guardar'  class='Boton' style='width:70px' onclick="enviar()" >
   <input class=boton type="button" name=cmdCerrar value="Cerrar" style="width:80px" onclick="javascript:cerrar();">&nbsp;
   
 </td>
</tr>
<input type='hidden' name='pagina' id='pagina' value='<?php echo $pagina ?>'>
<input type='hidden' name='orden' id='orden' value='<?php echo $orden ?>'>
<input type='hidden' name='buscam' id='buscam' value='<?php echo $buscam ?>'>
<!--<input type="hidden" name="hddorigen" id="hddorigen" value="<%=$origen%>">-->
<input type='hidden' name='hddaccion' id='hddaccion' value=''>
</table>
<br /><br />

<?php echo $cadenilla; ?>
</form>
</body>
</HTML>