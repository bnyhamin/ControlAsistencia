<?php
header("Expires: 0");
require_once("../Includes/Connection.php");
require_once("../Includes/Constantes.php");
require_once("../Includes/clsEmpleados.php");
require_once("includes/clsCA_Empleado_Rol.php");
require_once("includes/clsCA_Empleados.php");
require_once("includes/clsCA_Asistencias.php");

$empleado_usuario = '';
$empleado_password = '';
$empleado_password_new = '';
$hddaccion = '';
$msg='';
$cambio='';
$ruta='';

$o = new ca_empleado_rol();
$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());

$obj = new Empleados();
$obj->setMyUrl(db_host());
$obj->setMyUser(db_user());
$obj->setMyPwd(db_pass());
$obj->setMyDBName(db_name());

$objas = new ca_asistencia();
$objas->setMyUrl(db_host());
$objas->setMyUser(db_user());
$objas->setMyPwd(db_pass());
$objas->setMyDBName(db_name());

$objemp = new ca_empleados();
$objemp->setMyUrl(db_host());
$objemp->setMyUser(db_user());
$objemp->setMyPwd(db_pass());
$objemp->setMyDBName(db_name());

if (isset($_POST['empleado_usuario'])) $empleado_usuario=$_POST['empleado_usuario'];
if (isset($_POST['empleado_password'])) $empleado_password=$_POST['empleado_password'];
if (isset($_POST['empleado_password_new'])) $empleado_password_new=$_POST['empleado_password_new'];
if (isset($_POST['hddaccion'])) $hddaccion=$_POST['hddaccion'];

if (isset($_GET['dni'])) $empleado_usuario=$_GET['dni'];
if (isset($_GET['password'])) $password_get=$_GET['password'];
//echo 'dni='.$dni_get;
//echo 'password_='.$password_get;

if ($hddaccion=='OK'){

	$rpta = $obj->verificar_USR_PWD($empleado_usuario, $empleado_password, $empleado_password_new);
	if ($rpta=='CMB'){
		$cambio=$rpta;
		if (!session_is_registered('empleado_codigo')) session_register("empleado_codigo");
		$r=$obj->Query();
		$_SESSION["empleado_codigo"] = $obj->empleado_codigo;
		if (!session_is_registered('empleado_nombres')) session_register("empleado_nombres");
		$_SESSION["empleado_nombres"]=$obj->empleado_nombres . " " . $obj->empleado_apellido_paterno . " " . $obj->empleado_apellido_materno ;

        $objemp->empleado_codigo=$_SESSION["empleado_codigo"];
        $r=$objemp->Query();

		if (!session_is_registered('tca')) {
			session_register("tca"); //turno_codigo_asignado
		}

		$_SESSION["tca"]=$objemp->turno_codigo;

		if (!session_is_registered('tda')) {
			session_register("tda"); //turno_descripcion_asignado
		}
		$_SESSION["tda"]=$objemp->turno_descripcion;

		//determinar rol de usuario
		$o->empleado_codigo = $_SESSION["empleado_codigo"];
		$objas->empleado_codigo=$_SESSION["empleado_codigo"];
		//echo $objas->empleado_codigo;

		$rp = $o->Devolver_rol();
		$rol=$o->rol_codigo;
		if (!session_is_registered('rc')) { //rol_codigo
			session_register("rc");
		}
		$_SESSION["rc"]=$o->rol_codigo;
		//echo 'rol:' . $rol;
        
        $envia_python = 0;
        $emp = $_SESSION["empleado_codigo"];
        
		if ($rol==0){
		    $sizewidth=2;
			$sizeheight=2;
			$sizex=500;
			$sizey=300;
		    $cod=$objas->validar_asistencia();
		    //echo ' cod:' . $cod;
			if($cod==0){
				$rpta = $objas->validar();
			    if($objas->asistencia_codigo==0){
			    	$ruta="asistencias/registrar_asistencia.php?cod=0&tip=1"; // nueva asistencia
                    $envia_python = 0;
                    //$ruta="registrar_asistencia.psp"; // nueva asistencia
                    //$envia_python = 1;
                    $tip = 1;
			    }else{
				         if($objas->tip==0){
				         	$ruta="asistencias/consultar_salida_pendiente.php?cod=" . $objas->asistencia_codigo . "&tip=0&r=2";
				         }else{
				         	$ruta="asistencias/registrar_asistencia.php?cod=" . $objas->asistencia_codigo ."&tip=1";
                            $envia_python = 0;
                            //$ruta="registrar_asistencia.psp"; // nueva asistencia
                            //$envia_python = 1;
                            $cod = $objas->asistencia_codigo;
                            $tip = 1;
				         }
				}
			}else{

			    echo "<script language='JavaScript'>";
				echo "  alert('Su asistencia ya tiene entrada y salida registradas!!');";
				echo "  document.location.href = 'login.php';";
				echo "</script>";

			}
            
            //redirecciona si cambio ok la clave
            if($envia_python == 1){
                /* NO va a entrar a esta parte de codigo
                $form_gap_python = "<form id='formGapPython' name='formGapPython'>";
                $form_gap_python .= "<input type='hidden' id='cod' name='cod' value=".$cod.">";
                $form_gap_python .= "<input type='hidden' id='tip' name='tip' value=".$tip.">";
                $form_gap_python .= "</strong><input type='hidden' id='emp' name='emp' value=".$emp.">";
                $form_gap_python .= "</form>";
                echo $form_gap_python;
                */
                 ?>
			     <script language="JavaScript">
                    alert('Se cambió su contraseña con exito, utilice su nueva contraseña la próxima vez que ingrese al sistema camayoc');
                    
                    document.formGapPython.action = "<?php echo $url_gap_python.$ruta ?>";
                    document.formGapPython.method = 'POST';
                    document.formGapPython.submit();
                    
                 </script>
			     <?php
                
            }

		}else{
				$sizewidth=1;
				$sizeheight=1;
				$sizex=3;
				$sizey=3;
				$ruta="menu.php";
		}
	}else{
		$msg="<center><font color=red><b>" . $rpta . "</b></font></center>";
		?>
        	<script language=javascript>
        		alert('<?php echo $rpta; ?>');
        	</script>
        	<?php
	}
}
?>
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
 <META NAME=author CONTENT='TUMI Solutions S.A.C.'>
 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
 <meta http-equiv="pragma" content="no-cache">
 <script language="JavaScript" src="../default.js"></script>
 <link rel="stylesheet" type="text/css" href="../css/login.css">
 <title><?php echo tituloGAP() ?></title>
<script language="JavaScript" >
var isW = (navigator.appName.indexOf("Microsoft Internet Explorer")!=-1);

function entrar(e){
    var a = (isW) ? e.keyCode: e.which;
    if (a==13){
        enviar();
    }
}
function enviar(){
    if (validarEntrada('empleado_usuario')!=true) return false;
	if (validarEntrada('empleado_password')!=true) return false;
	if (validarEntrada('empleado_password_new')!=true) return false;
	if (validarEntrada('empleado_password_renew')!=true) return false;

	/*if (document.frm.empleado_password.value==document.frm.empleado_password_new.value){
		alert('La contraseña nueva debe ser diferente de la actual.')
		document.frm.empleado_password_new.focus();
		document.frm.empleado_password_new.select();
		return false;
	}*/
	if (document.frm.empleado_password_new.value!=document.frm.empleado_password_renew.value){
		alert('Re-escriba contraseña correctamente.')
		document.frm.empleado_password_renew.focus();
		document.frm.empleado_password_renew.select();
		return false;
	}
	var l=document.frm.empleado_password_new.value;
	/*if (l.length<6){
		alert('Contraseña nueva debe tener 6 o más caracteres')
		document.frm.empleado_password_new.focus();
		document.frm.empleado_password_new.select();
		return false
	}*/
	/*if (l=='123456'){
		alert('Valor de contraseña nueva no permitido')
		document.frm.empleado_password_new.focus();
		document.frm.empleado_password_new.select();
		return false
	}*/
	document.frm.hddaccion.value='OK';
	document.frm.submit();
	return true;
 }
</script>
</head>
<body OnContextmenu="return false" topmargin="0" leftmargin="0">
<!--    <form name='frm' id='frm' action='<?php //echo $PHP_SELF ?>' method='post' onSubmit='javascript:return confirmar();'> -->
<form name='frm' id='frm' action='<?php echo $_SERVER['PHP_SELF'] ?>' method='post' onSubmit='javascript:return confirmar();'>
<table class=table width="100%" height='100%' align="center" valign=middle border="0"  cellspacing=0 cellpadding=0>
  <tr>
  	<td width="100%">
  		<table class=table width="100%" height='100%' align="center" valign=middle border="0" cellspacing=0 cellpadding=0 >
  		  <tr>
		  	<td class=ColumnTDblanco width="100%" colspan=2 algin=center>
		  		<b><font class=textonegritablanco><?php echo tituloGAP() ?></font></b>
		  	</td>
		  </tr>
		  <tr>
		  	<td width="100%" colspan=2 algin=center>
		  		<?php
		  		if ($cambio==''){
		  		?>
		  		<table width="100%" height='100%' align="center" valign=middle border="0" cellspacing=0 cellpadding=0 background='images/fondoGAP1.jpg'>
		  		<tr height='40%'>
					<td align='center'>
						&nbsp;
					</td>
					<td align=center rowspan=4 valign="bottom">
						<br><center></center><br><br><br><br><br>
						<table class=table width="300px" height='50%' align="center" valign=middle border="0"  cellspacing=0 cellpadding=0>
						  <tr>
							<td class=ColumnTD align='left' colspan="3" STYLE='WIDTH:250''>
								<font class=textonegritagrande>Cambiar contraseña</font>
							</td>
						  </tr>
						  <tr>
							<td class=ColumnTD style="width:240px">
								<font class=texto>Nro. DNI</font>
							</td>
							<td class=ColumnTD nowrap>
								<input class="input" name="empleado_usuario" id="empleado_usuario" type="text" size="11" value="<?php echo $empleado_usuario ?>" maxlength='15' tabindex='1' alt='Nro. DNI'>
							</td>
						  </tr>
						  <tr>
							<td class=ColumnTD>
								<font class=texto>Contraseña actual</font>
							</td>
							<td class=ColumnTD>
								<input class="input" name="empleado_password" id="empleado_password" type="password"  size="11" value="<?php echo $password_get ?>" maxlength="15" tabindex='2' alt='Contraseña actual'>
							</td>
						  </tr>
						  <tr>
							<td class=ColumnTD colspan=2>
  								<center><?php echo $msg ?></center>
  								<hr width="100%"! size="2">
							</td>
						  </tr>
						  <tr>
							<td class=ColumnTD>
								<font class=texto>Contraseña nueva (Mínimo 6 caracteres)</font>
							</td>
							<td class=ColumnTD>
								<input class="input" name="empleado_password_new" id="empleado_password_new" type="password"  size="11" value="" maxlength="15" tabindex='3' alt='Contraseña nueva'>
							</td>
						  </tr>
						  <tr>
							<td class=ColumnTD>
								<font class=texto>Re-escriba contraseña nueva</font>
							</td>
							<td class=ColumnTD>
								<input class="input" name="empleado_password_renew" id="empleado_password_renew" type="password"  size="11" value="" maxlength="15" onKeyPress='javascript:return entrar(event);' tabindex='4'  alt='Re-escriba contraseña actual'>
							</td>
						  </tr>
						  <tr>
							<td class=ColumnTD colspan=2 align=right>
								<img src='../images/bt_ok.gif' onclick='enviar();' style='cursor:hand;' >
							</td>
						  </tr>
						  <tr>
							<td class=ColumnTD colspan=2>
								<font class=texto><a href='login.php'>Volver</a></font>
							</td>
						  </tr>
					      </table>

					 </td>
				  </tr>
				  <tr height='20%'>
				  	<td>
				  		<table width="100%" align="center" valign=middle border="0"  cellspacing=0 cellpadding=0>
				  		<tr height='50%'>
				  			<td>&nbsp;&nbsp;&nbsp;</td>
							<td >
								<font class=textoazul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								</font>
								<br>
							</td>
							<td>&nbsp;&nbsp;&nbsp;</td>
						  </tr>
						  <tr height='50%'>
						  	<td>&nbsp;&nbsp;&nbsp;</td>
							<td>
								<font class=textoazul>&nbsp;</font>
							</td>
							<td>&nbsp;&nbsp;&nbsp;</td>
						  </tr>
						</table>
					</td>
				  <tr>
					<td>&nbsp;
					</td>
				  </tr>
				 </table>
		  	</td>
			</tr>
	  		</table>
	  		<?php
		  	}else{
		  	   
               //if($envia_python != 1){
		  		?>
		  		<table class=table width="100%" align="center" valign=middle border="0"  cellspacing=0 cellpadding=0>
				  <tr height='50%'>
				  	<td>
						<p><h4>Se cambió su contraseña con exito, utilice su nueva contraseña la próxima vez que ingrese al sistema</h4></p>
						<center><h2><a href='<?php echo $ruta; ?>'>Continuar</a></h2></center>
					</td>
				  </tr>
				</table>
		  		<?php
                //}
		  	}
	  		?>
		  	</td>
		  </tr>
 </table>


<input type='hidden' id='hddaccion' name='hddaccion' value=''>
</form>
</body>