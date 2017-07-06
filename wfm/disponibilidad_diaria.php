<?php header('Expires: 0'); ?>
<?php
  require('../../Includes/Connection.php');
  require_once('../../Includes/seguridad.php');
  require('../../Includes/Constantes.php'); 
  require('../../Includes/clswfm_disponibilidad_diaria.php'); 
  require_once("../../Includes/MyCombo.php");
      
   $dd = new wfm_disponibilidad_diaria();
   $dd->MyDBName= db_name();
   $dd->MyUrl = db_host();
   $dd->MyUser= db_user();
   $dd->MyPwd = db_pass();
   
   $combo1 = new MyCombo();
   $combo1->setMyUrl(db_host());
   $combo1->setMyUser(db_user());
   $combo1->setMyPwd(db_pass());
   $combo1->setMyDBName(db_name());
   
   $dd_codigo='';
   $dd_indicador='';
   $dd_descripcion='';
   $dia1=0;
   $dia2=0;
   $dia3=0;
   $dia4=0;
   $dia5=0;
   $dia6=0;
   $dia7=0;    
   $dd_activo=0;

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

  if (isset($_POST["dd_codigo"])){
   $dd_codigo = $_POST["dd_codigo"]; 
  }else{
   $dd_codigo = $_GET["codigo"]; 
  }
  if (isset($_POST["dd_identificador"])){
   $dd_identificador = $_POST["dd_identificador"]; 
  }
  if (isset($_POST["dd_descripcion"])){
   $dd_descripcion = $_POST["dd_descripcion"]; 
  }
  if (isset($_POST["dia1"])){
   $dia1 = $_POST["dia1"]; 
  }
  if (isset($_POST["dia2"])){
   $dia2 = $_POST["dia2"]; 
  }
  if (isset($_POST["dia3"])){
   $dia3 = $_POST["dia3"]; 
  }
  if (isset($_POST["dia4"])){
   $dia4 = $_POST["dia4"]; 
  }
  if (isset($_POST["dia5"])){
   $dia5 = $_POST["dia5"]; 
  }
  if (isset($_POST["dia6"])){
   $dia6 = $_POST["dia6"]; 
  }
  if (isset($_POST["dia7"])){
   $dia7 = $_POST["dia7"]; 
  }
  if (isset($_POST["dd_activo"])){
   $dd_activo = $_POST["dd_activo"]; 
  }
  
  //if (isset($_POST["hdddias"])) $dias = $_POST["hdddias"];
  
  if (isset($_POST["hddaccion"])){
     if ($_POST["hddaccion"]=="SVE"){//la orden es grabar
       
       $dd->dd_descripcion=$dd_descripcion;
	   $dd->dia1=$dia1;
       $dd->dia2=$dia2;
       $dd->dia3=$dia3;
       $dd->dia4=$dia4;
       $dd->dia5=$dia5;
	   $dd->dia6=$dia6;
	   $dd->dia7=$dia7;
	   $dd->dd_activo= $dd_activo;
       $dd->usuario_registra= $id_usuario;
       $dd->usuario_modifica= $id_usuario;
       
       if ($_POST["dd_codigo"]==""){
       	
       		$consulta=$dd->verifica_disponibilidad_diaria();
       		if ($consulta != 0){
	   ?>
		<script language='javascript'>
			         
  			alert('Advertencia la disponibilidad diaria ya existe ');
					        
       </script> <?php
       			
       		}else{
       			
	  			$sRpta = $dd->AddNew();
	            $dd_codigo = $dd->dd_codigo;
	            $dd_identificador = $dd->dd_identificador;
	            $dd_descripcion = $dd->dd_descripcion;
       			
       		}
       		
    	}
 	    else{
 	    	
 	    	$consulta=$dd->verifica_disponibilidad_diaria_actualizar($dd_codigo);
       		if ($consulta != 0){
	        ?>
			<script language='javascript'>
				         
	  			alert('Advertencia la disponibilidad diaria ya existe ');
						        
	       </script> <?php
       			
       		}else{
       			
 	    	 $dd->dd_codigo=$dd_codigo;
   			 $sRpta = $dd->Update();
   			 //$dh_identificador = $dh->dh_identificador;
             $dd_descripcion = $dd->dd_descripcion;
           }  
 	    }
       

           if ($sRpta!="OK"){
               echo $sRpta;
           }else{
           	
           	$cadenilla = "<center><font size=2 color=#800000>Los datos se grabaron satisfactoriamente</font></center>";
           	
           }
     }
  }else{
     if (isset($_GET["dd_codigo"])) $dd_codigo= $_GET["dd_codigo"];
     if ($dd_codigo!=""){
        $dd->dd_codigo=$dd_codigo;
        //Recuperar datos del Registro
        $sRpta = $dd->Query();
        if ($sRpta!="OK"){
            echo sRpta;
        }
           $dd_identificador = $dd->dd_identificador;
           $dd_descripcion = $dd->dd_descripcion;
           $dia1= $dd->dia1;
           $dia2= $dd->dia2;
           $dia3= $dd->dia3;
           $dia4= $dd->dia4;
           $dia5= $dd->dia5;
           $dia6= $dd->dia6;
           $dia7= $dd->dia7;
		   $dd_activo = $dd->dd_activo;
           $usuario_modifica = $id_usuario;

     }
  } 
?>
<HTML>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv='pragma' content='no-cache'>
<META NAME=author CONTENT='TUMI Solutions S.A.C.'>
<title>Registro Disponibilidad Diaria - <?php echo SistemaNombre(); ?> </title>
<link rel="stylesheet" type="text/css" href="../../default.css">
<script language="JavaScript" src="../../default.js"></script>
</HEAD>
<script type="text/javascript" language="javascript">

function cambiarcheck(){
	if (document.getElementById("Dias").checked){
		document.getElementById("Dia1").checked = true;
		document.getElementById("Dia2").checked = true;
		document.getElementById("Dia3").checked = true;
		document.getElementById("Dia4").checked = true;
		document.getElementById("Dia5").checked = true;
		document.getElementById("Dia6").checked = true;
		document.getElementById("Dia7").checked = true;
		
		resaltar();
	}else{
		document.getElementById("Dia1").checked = false;
		document.getElementById("Dia2").checked = false;
		document.getElementById("Dia3").checked = false;
		document.getElementById("Dia4").checked = false;
		document.getElementById("Dia5").checked = false;
		document.getElementById("Dia6").checked = false;
		document.getElementById("Dia7").checked = false;
		
		document.frm.dd_descripcion.value='';
	}
}

function resaltar(){ //alert('idem');
  var r=document.getElementsByTagName('input');
  var cods='';
  var dia='';
  for (var i=0; i< r.length; i++) {
  	var o=r[i];

  	if ((o.id).substr(0,3)==('dia')) {

		if (o.checked){
		    if (o.id =='dia1'){
				dia='LU';
			}
			if (o.id =='dia2'){
				dia='MA';
			}
			if (o.id =='dia3'){
				dia='MI';
			}
			if (o.id =='dia4'){
				dia='JU';
			}
			if (o.id =='dia5'){
				dia='VI';
			}
			if (o.id =='dia6'){
				dia='SA';
			}
			if (o.id =='dia7'){
				dia='DO';
			}
	
			if (cods==''){
				
				cods=dia;
			}else{
				cods=cods + '-' + dia;
			}
		}
  	}
  }
  
  if (cods != ''){
  	//alert(cods);
   	document.frm.dd_descripcion.value=cods;
  }

}





function enviar(){
	
	/*var dias='';
        	
	for(i=0; i< document.frm.length; i++ ){
		if (frm.item(i).type=='checkbox'){
 	    	if ( frm.item(i).checked ){
				if (dias==''){
					dias=frm.item(i).value;
				}else{	
					dias+= ',' + frm.item(i).value;
				}
		    }
		}
	}
	if (dias==''){
		alert('Seleccione por lo menos un dia de la semana');
		return false
	}*/
	
  var r=document.getElementsByTagName('input');
  var cods='';
  for (var i=0; i< r.length; i++) {
  	var o=r[i];

  	if ((o.id).substr(0,3)==('dia')) {

		if (o.checked){
			//alert('Entro: ' + subcadena + ' valor: ' + o.value);
			if (cods==''){
				cods=o.value;
			}else{
				cods=cods + ',' + o.value;
			}
		}
  	}
  }
	if (cods==''){
		alert('Seleccione por lo menos un dia de la semana');
		return false;
	}
	
	
			

	if (confirm("Guardar cambios")==false) return false;
	document.frm.hddaccion.value='SVE'
	document.frm.submit();
	
}

function cerrar(){
var pg="";

pg = "main_disponibilidad_diaria.php"

location.href = pg + "?pagina=<?php echo $pagina ?>&orden=<?php echo $orden ?>&buscam=<?php echo $buscam?>";

}

</script>
<body bgcolor='#ffffff' text='#000000' link='#000000' alink='#999999' vlink='#000000'   Class='PageBody'  >

<p class=TITOpciones>Registro de Disponibilidad Diaria</p>

<form name='frm' id='frm' action='<?php echo $PHP_SELF; ?>' method='post' >
<table  class='table' width='70%' align='center' cellspacing='1' cellpadding='0' border='0'>
<tr>
 <td  class="ColumnTD" align='right'>
     Código &nbsp;</td>
 <td  class='DataTD' colspan="7">
     <Input  class='Input' type='text' name='dd_codigo' id='dd_codigo' value='<?php echo $dd_codigo?>' size='6' readonly  >
 </td>
</tr>
<tr>
 <td  class="ColumnTD" align='right'>
     Código Identificador &nbsp;</td>
 <td  class='DataTD' colspan="7">
     <Input  class='Input' type='text' name='dd_identificador' id='dd_identificador' value='<?php echo $dd_identificador?>' size='6' readonly  >
 </td>
</tr>
<tr>
 <td  class="ColumnTD" align='right'>
     Descripción &nbsp;</td>
 <td  class='DataTD' colspan="7">
     <Input  class='Input' type='text' name='dd_descripcion' id='dd_descripcion' value='<?php echo $dd_descripcion?>' size='40' maxlength='80' readonly > </font>
 </td>
</tr>
<tr>
<td  class='ColumnTD' style='width:80px' align='center'><Input class='Input' type='checkbox' name='Dias' id='Dias' title='Marcar Para Todos Los Dias' value='1' onclick='cambiarcheck();'>Todos</td>
<td  class='DataTD' style='width:80px' align='center'><Input class='Input' type='checkbox' name='dia1' id='dia1' value='1' <?php if ($dia1*1==1) echo 'checked' ?> onclick='resaltar()' >Lun</td>
<td  class='DataTD' style='width:80px' align='center'><Input class='Input' type='checkbox' name='dia2' id='dia2' value='1' <?php if ($dia2*1==1) echo 'checked' ?> onclick='resaltar()'>Mar</td>
<td  class='DataTD' style='width:80px' align='center'><Input class='Input' type='checkbox' name='dia3' id='dia3' value='1' <?php if ($dia3*1==1) echo 'checked' ?> onclick='resaltar()' >Mié</td>
<td  class='DataTD' style='width:80px' align='center'><Input class='Input' type='checkbox' name='dia4' id='dia4' value='1' <?php if ($dia4*1==1) echo 'checked' ?> onclick='resaltar()' >Jue</td>
<td  class='DataTD' style='width:80px' align='center'><Input class='Input' type='checkbox' name='dia5' id='dia5' value='1' <?php if ($dia5*1==1) echo 'checked' ?>  onclick='resaltar()'>Vie</td>
<td  class='DataTD' style='width:80px' align='center'><Input class='Input' type='checkbox' name='dia6' id='dia6' value='1' <?php if ($dia6*1==1) echo 'checked' ?> onclick='resaltar()'>Sáb</td>
<td  class='DataTD' style='width:80px' align='center'><Input class='Input' type='checkbox' name='dia7' id='dia7' value='1' <?php if ($dia7*1==1) echo 'checked' ?> onclick='resaltar()'>Dom</td>
</tr>
<tr>
 <td  class="ColumnTD" align='right'>
     Activo &nbsp;</td>
 <td  class='DataTD' colspan="7">
     <Input  class='Input' type='checkbox' name='dd_activo' id='dd_activo' value='1' <?php if ($dd_activo*1==1) echo 'checked' ?>>
 </td>
</tr>

<tr>
 <td colspan=8  class='ColumnTD'>&nbsp;
 </td>
</tr>
<tr align='center'>
 <td  colspan=8  class='ColumnTD'>
   <input name='cmdGuardar' id='cmdGuardar' type='button' value='Guardar'  class='Boton' style='width:70px' onclick="enviar()" >
   <input class=boton type="button" name=cmdCerrar value="Cerrar" style="width:80px" onclick="javascript:cerrar();">&nbsp;
   
 </td>
</tr>
<input type='hidden' name='pagina' id='pagina' value='<?php echo $pagina ?>'>
<input type='hidden' name='orden' id='orden' value='<?php echo $orden ?>'>
<input type='hidden' name='buscam' id='buscam' value='<?php echo $buscam ?>'>
<!--<input type="hidden" name="hddorigen" id="hddorigen" value="<%=$origen%>">-->
<input type='hidden' name='hddaccion' id='hddaccion' value=''>
<input type="hidden" id="hdddias" name="hdddias" value=""/>
</table>
<br /><br />

<?php echo $cadenilla; ?>
</form>
</body>
</HTML>