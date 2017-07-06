<?php header("Expires: 0"); ?>
<?php
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Usuarios.php");

$dni="";
$fecha_selected="";
$rpta="";
if (isset($_POST["txtDni"])) $dni= $_POST["txtDni"];

$u = new ca_usuarios();
$u->setMyUrl(db_host());
$u->setMyUser(db_user());
$u->setMyPwd(db_pass());
$u->setMyDBName(db_name());

$u_empleado = new ca_usuarios();
$u_empleado->setMyUrl(db_host());
$u_empleado->setMyUser(db_user());
$u_empleado->setMyPwd(db_pass());
$u_empleado->setMyDBName(db_name());

$u->empleado_codigo = $_SESSION["empleado_codigo"];
$r = $u->Identificar();
$nombre_usuario = $u->empleado_nombre;
$area = $u->area_codigo;
$area_descripcion = $u->area_nombre;
$jefe = $u->empleado_jefe;
$fecha = $u->fecha_actual;
$ndias=$u->Actualizacion_dias();
$fecha_selected = $fecha;
if (isset($_POST["hdd_action"])){
    if ($_POST["hdd_action"]=='VLA'){
        if (isset($_POST["hdd_fecha_selected"])) $fecha_selected= $_POST["hdd_fecha_selected"];
        $u_empleado->empleado_dni=$dni;
        $rpta=$u_empleado->getEmpleadoCodigo();
        if($rpta=="1"){
            $u_empleado->empleado_codigo;
            $u_empleado->Identificar();
            if($u_empleado->area_codigo==$u->area_codigo){
?>
                <script type="text/javascript">
                    window.parent.frames[1].location='val_left_desbloquear.php?f=<?php echo $fecha_selected;?>&empleado=<?php echo $u_empleado->empleado_codigo;?>';
                    window.parent.frames[2].location="val_right_desbloquear.php?empleado_cod=0&fecha=&tipo=";
                </script>
<?php            
            }else{
?>
                <script type="text/javascript">
                    alert("El ejecutivo no pertenece a la jefatura");
                </script>
<?php
            }
        }
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Listado de Asistencia</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
<!--<script language="JavaScript" src="../tecla_f5.js"></script>
<script language="JavaScript" src="../mouse_keyright.js"></script>-->
<script language="JavaScript" src="../jscript.js"></script>
<script type="text/javascript" src="../../views/js/librerias/jquery/app.js"></script>
<script language="JavaScript" type="text/javascript">
$(document).ready(function(){
    $('#txtDni').keyup(function(e){
        if(e.keyCode==13){
            if(document.frm.txtDni.value.length<8){
                alert("El Dni debe contener 8 digitos");
                document.frm.txtDni.focus();
            }else{
                var arrIndicador = document.frm.txtFecha.value.split("/");
                ano = arrIndicador[2];
                mes = arrIndicador[1];
                dia = arrIndicador[0];
                campoDeRetorno=document.frm.txtFecha;
                titulo='Cambiar Fecha';
                escribirFecha();
            }
        }}
    );
    $("#txtDni").keyup(Ext_Sistema.validaN);
});
Ext_Sistema={
    validaN:function(){
        if ($("#txtDni").val()!=""){
            $("#txtDni").val($("#txtDni").val().replace(/[^0-9\.]/g, ""));
	}
    }
}
    
var fecha_seleccion='';
function salir(){
    window.parent.location.href='../menu.php';
}

function numDias(d,m,a){
  m = (m + 9) % 12;
  a = a - Math.floor(m/10);
  return 365*a+Math.floor(a/4)-Math.floor(a/100)+Math.floor(a/400)
            +Math.floor((m*306+5)/10)+d-1 
}
function difDias(d1,m1,a1,d2,m2,a2){
   return numDias(d2,m2,a2) - numDias(d1,m1,a1)
}

function fechaC(cadena) {
    var separador = "/";
    if (cadena.indexOf(separador) != -1 ) {
        var posi1 = 0;
        var posi2 = cadena.indexOf(separador, posi1 + 1 );
        var posi3 = cadena.indexOf(separador, posi2 + 1 );
        this.dia = cadena.substring( posi1, posi2 );
        this.mes = cadena.substring( posi2 + 1, posi3 );
        this.anio = cadena.substring( posi3 + 1, cadena.length );
    }else{
        this.dia = 0;
        this.mes = 0;
        this.anio = 0;
    }
}

function escribirFecha(){
    if(document.frm.txtDni.value.length<8){
        alert("El Dni debe contener 8 digitos");
        document.frm.txtDni.focus();
        return false;
    }
    if(document.frm.txtDni.value==""){
        alert("Debe Ingresar Dni");
        document.frm.txtDni.focus();
        return false;
    }
    
    var fecha_servidor="<?php echo $fecha?>";
    dia=dia + "";
    mes=mes + "";
    ano=ano + "";
    if((dia + "").length==1) dia="0" + dia;
    if(mes.length==1) mes="0" + mes;
    campoDeRetorno.value = dia + "/" + mes + "/" + ano;
    CadenaFecha1 = fecha_servidor;
    CadenaFecha2 = campoDeRetorno.value;
    
    var fecha1 = new fechaC(CadenaFecha1);
    var fecha2 = new fechaC(CadenaFecha2);
    
    var dias=difDias(fecha1.dia*1, fecha1.mes*1, fecha1.anio*1, fecha2.dia*1, fecha2.mes*1, fecha2.anio*1);
    if (fecha2.mes*1==1 || fecha2.mes*1==3 || fecha2.mes*1==5 || fecha2.mes*1==7 || fecha2.mes*1==8 || fecha2.mes*1==10 || fecha2.mes*1==12){
    	
    }
    if ((Math.abs(dias))*1>(<?php echo $ndias ?>)*1){
        alert('Fecha NO VALIDA!!.\nNo puede validar fechas menores a  ' + <?php echo $ndias ?> + ' dias ');
        document.frm.txtFecha.value=fecha_seleccion;
        return false;
    }
    if (Date.parse(fecha2.anio+'/'+fecha2.mes+'/'+fecha2.dia) > Date.parse(fecha1.anio+'/'+fecha1.mes+'/'+fecha1.dia)) {
        if (dias > 0 && (fecha2.anio + "-" + fecha2.mes + "-" + fecha2.dia != '2011-01-31')){
            alert('Fecha NO VALIDA!!.\nElija una fecha anterior o igual al actual');
            document.frm.txtFecha.value=fecha_seleccion;
            return false;
        }
    }
    
    document.frm.hdd_fecha_selected.value = campoDeRetorno.value;
    document.frm.hdd_action.value='VLA';
    document.frm.submit();
    //window.parent.frames[1].location="val_left.php?f=" + campoDeRetorno.value ;
    //setear el iframe derecho
}
function pedirFecha(campoTexto,nombreCampo) {
    fecha_seleccion=campoTexto.value;
    ano = anoHoy();
    mes = mesHoy();
    dia = diaHoy();
    campoDeRetorno = campoTexto;
    titulo = nombreCampo;
    dibujarMes(ano,mes);
    
}
</script>

</head>
<body class="PageBODY" onLoad="return WindowParentResize(10,20,'center')" onclick="document.all('popCalf').style.visibility='hidden';">
<div id="popCalF" style="BORDER-BOTTOM: 2px ridge; Z-INDEX:200; BORDER-LEFT: 2px ridge; BORDER-RIGHT: 2px ridge; BORDER-TOP: 2px ridge; POSITION: absolute; VISIBILITY: hidden; WIDTH: 10px">
	<iframe name="popFrameF" id="popFrameF" src="../popcj.htm" frameborder="1" scrolling="no" width="183" height="188"></iframe>
</div>
<form id="frm" name="frm" method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>" class="letra">
<center class="Titlecabecera">Validar Asistencia de Personal por Jefatura</center>
<table align='center' width="60%" border="0">
 <tr>
    <td width="45%" align="right"><b>Supervisor : </b></td>
    <td align="left"><font color="#3366CC"><b><?php echo $nombre_usuario?></b></font></td>
 </tr>
</table>

<table width="25%"  border="1" cellspacing="1" cellpadding="0" align="center" >
<tr>
    <td class="ColumnTD" align="left" width="8%" style="padding-left: 4px;">
        Dni&nbsp;
        <input type="text" class="input" id="txtDni" name="txtDni" size="11" maxlength="8" value="<?php echo $dni;?>"/>
    </td>
    <td class="ColumnTD" align="left" width="12%">
        Fecha&nbsp;
        <input type="text" class="input" id="txtFecha" name="txtFecha" readOnly size="11" value="<?php echo $fecha_selected; ?>"/>
        <img onClick="javascript:pedirFecha(txtFecha,'Cambiar Fecha');" src="../images/columnselect.gif" width="15" height="15" border="0"  style='cursor:hand;' alt="Seleccionar Fecha"/>
    </td>
    <td align="left" class="ColumnTD" width="3%">
        <img src="../images/biGoingOnline.gif" style="cursor:hand" onClick="salir();" title="Ir a menu principal"/>
    </td>
</tr>
</table>
<input type="hidden" name="hdd_fecha_selected" id="hdd_fecha_selected" value="<?php echo $fecha;?>"/>
<input type="hidden" name="hdd_action" id="hdd_action" value=""/>
</form>
</body>
</html>