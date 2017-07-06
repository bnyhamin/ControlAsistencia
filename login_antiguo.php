<?php
  // ini_set('display_errors', 'On');
  //   error_reporting(E_ALL);
header("Expires: 0");
require_once("../Includes/Connection.php");
require_once("../Includes/Constantes.php");
require_once("../Includes/clsEmpleados.php");
require_once("includes/clsCA_Empleado_Rol.php");
require_once("includes/clsCA_Empleados.php");
require_once("includes/clsCA_Asistencias.php");
session_start();

if (isset($_SESSION["empleado_codigo"])){
	session_unregister('empleado_codigo');
	session_destroy();
//echo "se ha destruido";	
}
$opt='';
$msg = "";
$h="";
$xcolor='#CC0000';
$ruta = "";
$l_ip =$_SERVER['REMOTE_ADDR'];	
if (isset($_POST["txtUSR"])){
        
	$USR = $_POST["txtUSR"];
	$PWD = $_POST["txtPWD"];
	$NPWD = ""; //$_POST["txtNEWPWD"];
        
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

	$rpta = $obj->verificar_USR_PWD($USR, $PWD, $NPWD);
	// $rpta = 'OK';
	switch ($rpta){
		case 'OK':
                    if (!session_is_registered('empleado_codigo')) {
                        session_register("empleado_codigo");
                    }
                    $r=$obj->Query();
                    $_SESSION["empleado_codigo"] = $obj->empleado_codigo;
                    
                    $msg_caducidad = $obj->msg_caducidad;
                    if ($msg_caducidad) {
                    	?>

	                <script language="JavaScript">
	                      alert("<?php echo $msg_caducidad ?>");
	                </script>

                   <?php }

                    if (!session_is_registered('empleado_nombres')) {
                        session_register("empleado_nombres");
                    }
                    //$_SESSION["empleado_nombres"]=$obj->empleado_nombres . " " . $obj->empleado_apellido_paterno . " " . $obj->empleado_apellido_materno ;
                    $_SESSION["empleado_nombres"]=$obj->empleado_nombres;
                    $_SESSION["empleado_dni_nro"]=$obj->empleado_dni;

                    $objemp->empleado_codigo=$_SESSION["empleado_codigo"];
                    $r=$objemp->Query();
                    
                    //$cumpleanios=$obj->honomastico();
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
				
                        
					// Mcamayoc 16sep13 No es necesario esta validacion.
					// - inicio validacion biometrico 
					
                    /*$objas->empleado_dni =$USR;
//					$objas->tiempo_permitido_marca_ingreso = $Tiempo_Permitido_Marca_Ingreso;
					$objas->tiempo_permitido_marca_ingreso = $objas->tpmi();
					$rpta=$objas->Consulta_biometrico($l_ip);*/
					//Si el Resultado es vacio dejará pasar, de lo contrario no dejará pasar
                    //echo "ip:".$l_ip."</br>";
                    //echo "rpta:".$rpta; 
                    //exit(0); 
					
          //if($rpta != ""){
					?>	
						<script language="JavaScript">
							//alert( " <?php //echo $rpta; ?>  ");						
							//document.location.href = "login.php";
						</script>
                	<?php
					//}
          
                    
					// - fin validacion biometrico
                    // Mcamayoc 16sep13 No es necesario esta validacion.
					
					//Seteando la variable $_SESSION["empleado_paginas"]
					$paginas=$objas->empleado_rol_pagina();
                	$_SESSION['empleado_paginas'] = strtoupper($paginas);
					
                    $rp = $o->Devolver_rol();
                    $rol=$o->rol_codigo;
                    if (!session_is_registered('rc')) { //rol_codigo
                        session_register("rc");
                    }
                    
                    $_SESSION["rc"]=$o->rol_codigo;

		            $envia_python = 0;
		            $emp = $_SESSION["empleado_codigo"];
                     
            if ($rol==0){
                //echo "hola";Exit(0);
                $sizewidth=2;
                $sizeheight=2;
                $sizex=500;
                $sizey=300;
                $cod=$objas->validar_asistencia();
                if($cod==0){
                    $rpta = $objas->validar();
                    if($objas->asistencia_codigo==0){ 
                        
                        
                       /* $ruta="asistencias/registrar_asistencia.php?cod=0&tip=1"; // nueva asistencia
                        $envia_python = 0;*/
                        
                        $ruta="registrar_asistencia.psp"; // nueva asistencia
                        $envia_python = 1;
                        $tip = 1;
                    }else{
                        
                        
                        /*$ruta="asistencias/registrar_asistencia.php?cod=" . $objas->asistencia_codigo ."&tip=1";
                        $envia_python = 0;*/
                        
                        $ruta="registrar_asistencia.psp"; // nueva asistencia
                        $envia_python = 1;
                        $cod = $objas->asistencia_codigo;
                        $tip = 1;
                        
                        
                        //flr 19-06-2012 comento xq store calcula fecha fin
                        /*
                        if($objas->tip==0){
                            $ruta="asistencias/consultar_salida_pendiente.php?cod=" . $objas->asistencia_codigo . "&tip=0&r=2";
                        }else{
                            //$ruta="asistencias/registrar_asistencia.php?cod=" . $objas->asistencia_codigo ."&tip=1";
                            //$envia_python = 0;
                            $ruta="registrar_asistencia.psp"; // nueva asistencia
                            $envia_python = 1;
                            $cod = $objas->asistencia_codigo;
                            $tip = 1;
                            
                        }
                        */
                    }
                }else{
?>	 
                <script language="JavaScript">
                      alert("Su asistencia ya tiene entrada y salida registradas!!");
                      document.location.href = "login.php";
                </script>
<?php
                }
            }else{
                $sizewidth=1;
                $sizeheight=1;
                $sizex=3;
                $sizey=3;
                $ruta="menu.php?mostrarEstadistica=E";
                //$ruta="menu.php";
            }
            if($envia_python == 1){
                $form_gap_python = "<form id='formGapPython' name='formGapPython'>";
                $form_gap_python .= "<input type='hidden' id='cod' name='cod' value=".$cod.">";
                $form_gap_python .= "<input type='hidden' id='tip' name='tip' value=".$tip.">";
                $form_gap_python .= "</strong><input type='hidden' id='emp' name='emp' value=".$emp.">";
                $form_gap_python .= "</strong><input type='hidden' id='ip' name='ip' value=".$l_ip.">";
                $form_gap_python .= "</form>";
                echo $form_gap_python;
                
?>
                 <script language="JavaScript">
                    document.formGapPython.action = "<?php echo $url_gap_python.$ruta ?>";
                    document.formGapPython.method = 'POST';
                    document.formGapPython.submit();
                 </script>
<?php
				
				//if (isset($_SESSION["empleado_codigo"])){
					//session_unregister('empleado_codigo');
					//session_destroy();
					//echo "hola";
					//exit(0);
				//}
            }
            
            //echo "holas";
			?>
			<script language="JavaScript">
				//alert('entrando');
				var SmallSizeWidth  = <?php echo $sizewidth ?>;
				var SmallSizeHeight = <?php echo $sizeheight ?>;
				var SmallSizeX      = <?php echo $sizex ?>;
				var SmallSizeY      = <?php echo $sizey ?>;
				//var cumple=<?php //echo $cumpleanios ?>;
				window.resizeTo(window.screen.availWidth/SmallSizeWidth, window.screen.availHeight/SmallSizeHeight);
				window.moveTo(SmallSizeX , SmallSizeY);
				//if (cumple*1==1){
				//	var xw=window.open('../happy.php?codigo=<?php echo $_SESSION["empleado_codigo"]; ?>','happy','menubar=no, location=no, width=350px, height=120, center=yes');
				// //xp.focus();
				//}
				document.location.href = "<?php echo $ruta ?>";
		  	</script>
			<?php
			break;
		case 'CMB':
			//cambio clave exitosamente
			?>
			<script language="JavaScript">
				alert('Su Clave se cambio con Exito, utilice su Nueva Clave para ingrese al sistema.');
				//document.location.href = "login.php";
		  	</script>
			<?php
			//$xcolor='#009900';
			//$PWD="";
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

}else{
	$USR = "";
	$PWD = "";
}

?>
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv="pragma" content="no-cache">
<title><?php echo tituloGAP() ?> - Login</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1"/>
<script language="JavaScript" src="../default.js"></script>
<script language="JavaScript" src="jscript.js"></script>
<script language="JavaScript" src="no_teclas.js"></script>
<link rel="stylesheet" type="text/css" href="style/tstyle.css"/>

<script type="text/javascript">
var isNN = (navigator.appName.indexOf("Netscape")!=-1);
var alternate=0;
var i=0;


function entrar(e){
	var a = (isNN) ? e.which : e.keyCode;
	var ok=false;
	//alert(a);
	if (a==13){
		ingresar();
	}
	return ok;
}

function ingresar(){
	if (document.frm.txtUSR.value==0){
		alert('Indique numero de DNI');
		document.frm.txtUSR.focus();
		return false;
	}
	var xcar = document.frm.txtUSR.value;
	if (xcar.length <8){
		alert('Numero de caracteres de DNI incorrecto');
		document.frm.txtUSR.focus();
		document.frm.txtUSR.select();
		return false;
	}
	if (document.frm.txtPWD.value==0){
		alert('Indique clave de acceso');
		document.frm.txtPWD.focus();
		return false;
	}
	document.frm.submit();
}

function cmb_clave(){
	if (document.frm.chk.checked==true){
		span1.style.display = 'block';
		document.frm.txtNEWPWD.value="";
	}else{
		span1.style.display = 'none';
		document.frm.txtNEWPWD.value="";
	}
}
function enfocar(){
	frm.txtUSR.focus();
}
function cambiarclave(){
  var dni = document.getElementById('txtUSR').value;
  var password = document.getElementById('txtPWD').value;
  //alert (dni + password);

      window.showModalDialog("../cambioclave/index.php", "CambioClave", "dialogHeight:550px;dialogWidth:700px")


  // self.location.href='cambiar_contrasenia.php?dni='+dni+'&password='+password;
}
function redimensionar(){
//WindowResize(500,415,'otro');
WindowResizeXY(520,360);
//frames['hfrm'].location.href ="hora_server_asistencia.php?opt=1";
/*
self.menubar.visible=false;
self.locationbar.visible=false;
self.toolbar.visible=false;
self.statusbar.visible=false;
*/
}

function hora_asistencia(opcion, fecha, hora){
document.getElementById('h').value=hora.substring(0,2);
//alert(document.getElementById('h').value);
document.getElementById('m').value=hora.substring(3,5);
document.getElementById('s').value=hora.substring(6,8);
document.getElementById('di').value=fecha.substring(0,2);
document.getElementById('me').value=fecha.substring(3,5);
document.getElementById('an').value=fecha.substring(6,10);
show();
}

function show(){
//alert('x');    

var o=document.getElementById('hora');

var hh=document.getElementById('h').value * 1;
var mm=document.getElementById('m').value * 1;
var ss=document.getElementById('s').value * 1;
var d=document.getElementById('di').value * 1;
var m=document.getElementById('me').value * 1;
var a=document.getElementById('an').value * 1;
//alert(d + "/"+ m + "/" + a + " " + hh + ":" + mm + ":" + ss);

var Digital=new Date();

Digital.setYear(a);
Digital.setMonth(m);
Digital.setDate(d);
Digital.setHours(hh);
Digital.setMinutes(mm);
Digital.setSeconds(ss + i);

var anio=Digital.getYear();
var mes=Digital.getMonth();
var day=Digital.getDate();
if(mes<10) mes="0" + mes;

var hours=Digital.getHours();
var minutes=Digital.getMinutes();
var seconds = Digital.getSeconds();


var dn="AM";

if (hours==12){
    dn="PM";
}
if (hours>12){
    dn="PM";
 }

if (hours==0) hours=0;
if (hours.toString().length==1){
    hours="0" + hours;
}
if (minutes<=9){
   minutes="0" + minutes;
}

if (seconds<=9){
   seconds="0" + seconds;
 }


if (alternate==0){
   //o.value="" + anio + "-" + mes + "-" + day + " " +hours +":"+ minutes +":"+ seconds +"";
	 o.value="" + hours +":"+ minutes +":"+ seconds +"";

}
else{
     //o.value="" + anio + "-" + mes + "-" + day + " " +hours +":"+ minutes +":"+ seconds +"";
	 o.value="" + hours +":"+ minutes +":"+ seconds +"";
}

alternate = (alternate==0)? 1 : 0;
setTimeout("show()",1000);
i++;
}

</script>

</head>


<!-- body onLoad=" return redimensionar()" topmargin="0" rightmargin="0" leftmargin="0" bottommargin="0" -->
<body topmargin="0" rightmargin="0" leftmargin="0" bottommargin="0" style="background-color:#4F4F4F;" >
<form name="frm" id="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?opt=<?php echo $opt ?>">
<center>
<table class="tablefondot" border="0" style="width:500px; height:100%; margin-top:200px;" cellpadding="0" cellspacing="0">
<tr>
  <td style='height:113px' >&nbsp;</td>
</tr>
<tr>
 <td valign="top" >
  <table width="150px" border="0" cellspacing="0" cellpadding="0" align="right" class="sinborde" style="margin-right:22px;">
  <tr>
    <td style="padding-top:0px;" align=right>
      <input type="text" class="Input" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:xx-small " name="txtUSR" id="txtUSR" maxlength="8" size="12" value="<?php echo $USR ?>">
    </td>
    <td style="width:10px">&nbsp;</td>
  </tr>
  <tr>
    <td style="padding-top:8px;" align=right>
      <input type="password" class="Input" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:xx-small" name="txtPWD" id="txtPWD" maxlength="15" size="12" value="" onKeyPress="entrar(event)">
    </td>
  	<td style="width:10px">&nbsp;</td>
  </tr>
  <?php
  	if ($msg!=''){
	?>
	<script language="javascript">
		alert('<?php echo $msg ?>');
	</script>
	<?php
	}
  ?>
 <tr style='height:30px'>
  	<td colspan="2" align="center"><br>
		<!-- <a href='cambiar_contrasenia.php'><img src='images/botoncambiar_clave_v.gif' border=0></a>&nbsp;&nbsp;&nbsp;&nbsp; -->
		<img src="images/botoncambiar_clave_V.gif" style="cursor:hand;" onClick="cambiarclave()" onMouseOver="this.src='images/botoncambiar_clave_V_off.gif'" onMouseOut="this.src='images/botoncambiar_clave_V.gif'">
	  </td>
	</tr>
        <tr style="border: 1px solid red;" onClick="ingresar()">
      <td align="right" colspan="2" onClick="ingresar()">
	  	<!-- <img src="images/botonGAP-off.gif" onClick="ingresar()" style="cursor:hand" onClick="ok()" onMouseOver="this.src='images/botonGAP-on.gif'" onMouseOut="this.src='images/botonGAP-off.gif'">&nbsp;&nbsp;&nbsp; -->
	  	<!--<img src="images/botonlogin_gap_V.jpg" onClick="ingresar()" style="cursor:hand" onClick="ok()"  onMouseOver="this.src='images/botonlogin_gap_V_off.jpg'" onMouseOut="this.src='images/botonlogin_gap_V.jpg'">&nbsp;&nbsp;&nbsp;&nbsp;-->
                <img src="images/botonlogin_gap_V.jpg" onClick="ingresar()" style="cursor:hand;"  onMouseOver="this.src='images/botonlogin_gap_V_off.jpg'" onMouseOut="this.src='images/botonlogin_gap_V.jpg'"/>&nbsp;&nbsp;&nbsp;&nbsp;
      </td>
  </tr>
  <tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
</table>
</td>
</tr>
</table>
</center>
<iframe src="" id="hfrm" name="hfrm" width="0px" height="0px"></iframe>
<input type='hidden' id='h' name='h' value=''>
<input type='hidden' id='m' name='m' value=''>
<input type='hidden' id='s' name='s' value=''>
<input type='hidden' id='me' name='me' value=''>
<input type='hidden' id='di' name='di' value=''>
<input type='hidden' id='an' name='an' value=''>
</form>
<script language='javascript'>
  if (document.frm.txtUSR.value!=''){
        document.frm.txtPWD.focus();
  }
</script>
</body>
</html>
