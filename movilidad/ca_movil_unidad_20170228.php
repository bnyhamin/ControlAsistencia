<?PHP header('Expires: 0');

  require('../../Includes/Seguridad.php');
  require('../../Includes/Connection.php');
  require('../../Includes/Constantes.php');
  require('../includes/clsca_movilidad_unidad.php');

 $movil_unidad_codigo='';
 $movil_unidad_descripcion='';
 $movil_unidad_capacidad='';
 $movil_unidad_espera=0;
 $movil_unidad_placa='';
 $movil_unidad_chofer='';
 $movil_unidad_activo='';
 $fecha_registro='';
 $usuario_registro='';
 $fecha_modifica='';
 $usuario_modifica=$_SESSION["usuario_id"];

   $o = new ca_movilidad_unidad();
   $o->MyDBName= db_name();
   $o->MyUrl = db_host();
   $o->MyUser= db_user();
   $o->MyPwd = db_pass();

   $sRpta="";
   $pagina= "";
   $orden= "";
   $buscam= "";

   if (isset($_GET["pagina"])){
       $pagina= $_GET["pagina"];
       $orden= $_GET["orden"];
       $buscam= $_GET["buscam"];
   }else{
       $pagina= $_POST["pagina"];
       $orden= $_POST["orden"];
       $buscam= $_POST["buscam"];
   }

  if (isset($_POST["movil_unidad_codigo"])){
   $movil_unidad_codigo = $_POST["movil_unidad_codigo"];
  }else{
   $movil_unidad_codigo = isset($_GET["movil_unidad_codigo"])?$_GET["movil_unidad_codigo"]:0;
  }
  if (isset($_POST["movil_unidad_descripcion"])){
   $movil_unidad_descripcion = $_POST["movil_unidad_descripcion"];
  }
  if (isset($_POST["movil_unidad_capacidad"])){
   $movil_unidad_capacidad = $_POST["movil_unidad_capacidad"];
  }
  if (isset($_POST["movil_unidad_espera"])){
   $movil_unidad_espera = $_POST["movil_unidad_espera"];
  }
  if (isset($_POST["movil_unidad_placa"])){
   $movil_unidad_placa = $_POST["movil_unidad_placa"];
  }
  if (isset($_POST["movil_unidad_chofer"])){
   $movil_unidad_chofer = $_POST["movil_unidad_chofer"];
  }
  if (isset($_POST["movil_unidad_activo"])){
   $movil_unidad_activo = $_POST["movil_unidad_activo"];
  }

  if (isset($_POST["hddaccion"])){
     if ($_POST["hddaccion"]=="SVE"){//la orden es grabar
       $o->movil_unidad_descripcion= $_POST["movil_unidad_descripcion"];
       $o->movil_unidad_capacidad= $_POST["movil_unidad_capacidad"];
       $o->movil_unidad_espera= $_POST["movil_unidad_espera"];
       $o->movil_unidad_placa= $_POST["movil_unidad_placa"]==""?null:$_POST["movil_unidad_placa"];
       $o->movil_unidad_chofer= $_POST["movil_unidad_chofer"]==""?null:$_POST["movil_unidad_chofer"];
       $o->movil_unidad_activo= isset($_POST["movil_unidad_activo"])?$_POST["movil_unidad_activo"]:0;
       if ($_POST["movil_unidad_codigo"]==""){
           //Insertar
           $sRpta = $o->AddNew();
           $movil_unidad_codigo = $o->movil_unidad_codigo;
       }else{
           //Actualizar
          $o->movil_unidad_codigo=$movil_unidad_codigo;
          $sRpta = $o->Update();
       }
       if ($sRpta!="OK"){
           echo $sRpta;
       }else{
       	?>
       	<script language=javascript>
       	alert('Se guardo registro');
       	</script>
       	<?php
       }
     }
  }else{
     if (isset($_GET["codigo"])) $movil_unidad_codigo= $_GET["codigo"];
     if ($movil_unidad_codigo!=""){
        $o->movil_unidad_codigo=$movil_unidad_codigo;
        //Recuperar datos del Registro
        $sRpta = $o->Query();
        if ($sRpta!="OK"){
            echo sRpta;
        }
           $movil_unidad_descripcion = $o->movil_unidad_descripcion;
           $movil_unidad_capacidad = $o->movil_unidad_capacidad;
           $movil_unidad_espera = $o->movil_unidad_espera;
           $movil_unidad_placa = $o->movil_unidad_placa;
           $movil_unidad_chofer = $o->movil_unidad_chofer;
           $movil_unidad_activo = $o->movil_unidad_activo;
           $fecha_registro = $o->fecha_registro;
           $usuario_registro = $o->usuario_registro;
           $fecha_modifica = $o->fecha_modifica;
           $usuario_modifica = $o->usuario_modifica;

     }
  }
?>
<HTML>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv='pragma' content='no-cache'>
<META NAME=author CONTENT='TUMI Solutions S.A.C.'>
<title>Maestro de Unidades de Transporte</title>
<link rel="stylesheet" type="text/css" href="../../default.css">
<script language="JavaScript" src="../../default.js"></script>
</HEAD>
<body Class='PageBody'  >
<center class=TITOpciones>Maestro de Unidades de Transporte</center>

<script language='javascript'>
function confirmar(){
	if (validarEntrada('movil_unidad_descripcion')==false) return false;
    if (validarEntrada('movil_unidad_capacidad')==false) return false;
    if (document.frm.movil_unidad_espera.value=='') document.frm.movil_unidad_espera.value=0;

 if (confirm('confirme guardar los datos')== false){
     return false;
 }
 document.frm.hddaccion.value='SVE';
 return true;
}
</script>
<form name='frm' id='frm' action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'  onSubmit='javascript:return confirmar();'>
<table  class='Table' width='70%' align='center' cellspacing='1' cellpadding='1' border='0'>
<tr>
 <td  class='ColumnTD'  align='right'>
     Código &nbsp;</td>
 <td  class='DataTD'>
     <Input  class='Input' type='text' name='movil_unidad_codigo' id='movil_unidad_codigo' value='<?php echo $movil_unidad_codigo?>' size='7' readOnly >
 </td>
</tr>
<tr>
 <td  class='ColumnTD'  align='right'>
     Descripción &nbsp;</td>
 <td  class='DataTD'>
     <Input  class='Input' type='text' name='movil_unidad_descripcion' id='movil_unidad_descripcion' value='<?php echo $movil_unidad_descripcion?>' size='51' maxlength='80' alt='Descripción'> (*)
 </td>
</tr>
<tr>
 <td  class='ColumnTD'  align='right'>
     Capacidad &nbsp;</td>
 <td  class='DataTD'>
     <Input  class='Input' type='text' name='movil_unidad_capacidad' id='movil_unidad_capacidad' value='<?php echo $movil_unidad_capacidad?>' size='7' alt='Capacidad' onkeypress='return esnumero(event);'> (*)
 </td>
</tr>
<tr>
 <td  class='ColumnTD'  align='right'>
     Nro. en Lista Espera &nbsp;</td>
 <td  class='DataTD'>
     <Input  class='Input' type='text' name='movil_unidad_espera' id='movil_unidad_espera' value='<?php echo $movil_unidad_espera?>' size='7' alt='Lista de espera' onkeypress='return esnumero(event);' > (*)
 </td>
</tr>
<tr>
 <td  class='ColumnTD'  align='right'>
     Nro. Placa &nbsp;</td>
 <td  class='DataTD'>
     <Input  class='Input' type='text' name='movil_unidad_placa' id='movil_unidad_placa' value='<?php echo $movil_unidad_placa?>' size='11' maxlength='10' alt='Nro. de Placa'>
 </td>
</tr>
<tr>
 <td  class='ColumnTD'  align='right'>
     Nombre Conductor &nbsp;</td>
 <td  class='DataTD'>
     <Input  class='Input' type='text' name='movil_unidad_chofer' id='movil_unidad_chofer' value='<?php echo $movil_unidad_chofer?>' size='51' maxlength='80' alt='Nombre de Conductor'>
 </td>
</tr>
<tr>
 <td  class='ColumnTD'  align='right'>
     Activo &nbsp;</td>
 <td  class='DataTD'>
     <Input  class='Input' type='checkbox' name='movil_unidad_activo' id='movil_unidad_activo' value='1' <?php if ($movil_unidad_activo*1==1) echo 'Checked' ?>>
 </td>
</tr>
<tr>
 <td colspan=2  class='ColumnTD'>&nbsp;
 </td>
</tr>
<tr align='center'>
 <td colspan=2  class='ColumnTD'>
   <input name='cmdGuardar' id='cmdGuardar' type='submit' value='Guardar'  class='Button' style='width:70px' >
   <input name='cmdCerrar' id='cmdCerrar' type='button' value='Cerrar'  class='Button' style='width:70px' onclick="self.location.href='../../Mantenimientos/main_ca_movil_unidad.php?pagina=<?php echo $pagina?>&orden=<?php echo $orden?>&buscam=<?php echo $buscam?>'" >
 </td>
</tr>
<input type='hidden' name='pagina' id='pagina' value='<?php echo $pagina ?>'>
<input type='hidden' name='orden' id='orden' value='<?php echo $orden ?>'>
<input type='hidden' name='buscam' id='buscam' value='<?php echo $buscam ?>'>
<input type='hidden' name='hddaccion' id='hddaccion' value=''>
</table>
</form>
</body>
</HTML>
<!-- TUMI Solutions S.A.C.-->