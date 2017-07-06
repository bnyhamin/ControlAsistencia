<?php header("Expires: 0"); ?>
<?php
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Usuarios.php");

$u = new ca_usuarios();
$u->setMyUrl(db_host());
$u->setMyUser(db_user());
$u->setMyPwd(db_pass());
$u->setMyDBName(db_name());

$u->empleado_codigo = $_SESSION["empleado_codigo"];
$r = $u->Identificar();
$nombre_usuario = $u->empleado_nombre;
$area = $u->area_codigo;
$area_descripcion = $u->area_nombre;
$jefe = $u->empleado_jefe; // responsable area
$fecha = $u->fecha_actual;
$ndias=$u->Actualizacion_dias();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Listado de Asistencia</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../tecla_f5.js"></script>
<script language="JavaScript" src="../mouse_keyright.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<script type="text/javascript" src="../js/app/app.js"></script><!--jquery-->
<script type="text/javascript" src="../js/jtime.js"></script><!--contometro-->
<script language="JavaScript" type="text/javascript">

$(document).ready(function(){
    $('#ndias').jTime();
});

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

function fechaC( cadena ) {
    //Separador para la introduccion de las fechas
    var separador = "/";
    //alert(cadena);
    //Separa por dia, mes y año
    if ( cadena.indexOf( separador ) != -1 ) {
        var posi1 = 0;
        var posi2 = cadena.indexOf( separador, posi1 + 1 );
        var posi3 = cadena.indexOf( separador, posi2 + 1 );
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
    var fecha_servidor="<?php echo $fecha?>"
    var numserv=(fecha_servidor.substring(6,10) + fecha_servidor.substring(3,5) + fecha_servidor.substring(0,2))*1;
    dia=dia + "";
    mes=mes + "";
    ano=ano + "";
    //alert(fecha_servidor);
    if((dia + "").length==1) dia="0" + dia;
    if(mes.length==1) mes="0" + mes;
    campoDeRetorno.value = dia + "/" + mes + "/" + ano;
	
    CadenaFecha1 = fecha_servidor;
    CadenaFecha2 = campoDeRetorno.value;

	//alert(CadenaFecha1);
    //Obtiene dia, mes y año
    var fecha1 = new fechaC( CadenaFecha1 );
    var fecha2 = new fechaC( CadenaFecha2 );
   
    //Obtiene objetos Date
    var miFecha1 = new Date( fecha1.anio, fecha1.mes, fecha1.dia );
    var miFecha2 = new Date( fecha2.anio, fecha2.mes, fecha2.dia );
	//alert(miFecha2);
    //Resta fechas y redondea
    var diferencia = miFecha1.getTime() - miFecha2.getTime();
	//alert(fecha1.anio+'/'+fecha1.mes+'/'+fecha1.dia+'--'+fecha2.anio+'/'+fecha2.mes+'/'+fecha2.dia+'---dias'+Math.floor(diferencia / (1000 * 60 * 60 * 24)));
	//-- SI ES DEL MISMO MES Y AÑO
	/*
   if ((fecha1.mes*1==fecha2.mes*1) && (fecha1.anio*1==fecha2.anio*1)){
	var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
	//alert('1');
   }else if (fecha1.anio*1!=fecha2.anio*1){
	var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
	//alert('2');
   }
   else{
	var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24)) -1;
   }
   */
   //alert(fecha1.dia +'/'+ fecha1.mes +'/'+ fecha1.anio);
   //alert(fecha2.dia +'/'+ fecha2.mes +'/'+ fecha2.anio);
   //alert(fecha1.dia);
   var dias=difDias(fecha1.dia*1, fecha1.mes*1, fecha1.anio*1, fecha2.dia*1, fecha2.mes*1, fecha2.anio*1);   
   //alert(dias);//2013/02/01-2013/01/31
    /*if (fecha1.mes*1==fecha2.mes*1){
	var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
   }else{
	var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24)) -1;
   }*/

    //alert ('La diferencia es de ' + dias);
    if (fecha2.mes*1==1 || fecha2.mes*1==3 || fecha2.mes*1==5 || fecha2.mes*1==7 || fecha2.mes*1==8 || fecha2.mes*1==10 || fecha2.mes*1==12){
    //alert(fecha2.mes + ' < ' + fecha1.mes)
        /*if (fecha2.mes*1<fecha1.mes*2){
            dias=dias+1;
        }*/
		/*if (fecha2.mes*1<fecha1.mes*1 && fecha2.anio*1==fecha1.anio*1){
   			dias=dias+1;
   		}*/
		
    }
   
	//alert('dias: ' +dias + ' | serv: <?php echo $ndias ?>' );
	//document.frm.test.value=dias+'|'+Math.abs(dias)+'|'+<?php echo $ndias ?>;
    
    //--OBTENER EL NUMERO DE DIAS
    var nro_dias=$("#ndias").val();
  
    //if ((Math.abs(dias))*1>(<?php echo $ndias ?>)*1){
    if ((Math.abs(dias))*1>(nro_dias)*1){
        //alert('Fecha NO VALIDA!!.\nNo puede validar fechas menores a  ' + <?php echo $ndias ?> + ' dias ');
        alert('Fecha NO VALIDA!!.\nNo puede validar fechas menores a  ' + nro_dias + ' dias ');
        //document.frm.txtFecha.value=fecha_seleccion;
        document.frm.txtFecha.value=fecha_servidor;
        //return false;
    }
	//alert(fecha1.anio+'/'+fecha1.mes+'/'+fecha1.dia+'|'+fecha2.anio+'/'+fecha2.mes+'/'+fecha2.dia);
	
	if (Date.parse(fecha2.anio+'/'+fecha2.mes+'/'+fecha2.dia) > Date.parse(fecha1.anio+'/'+fecha1.mes+'/'+fecha1.dia)) {
		if (dias > 0 && (fecha2.anio + "-" + fecha2.mes + "-" + fecha2.dia != '2011-01-31')){
			alert('Fecha NO VALIDA!!.\nElija una fecha anterior o igual al actual');
			document.frm.txtFecha.value=fecha_seleccion;
			return false;
		}
	}
	
	/*if (dias < 0 && (fecha2.anio + "-" + fecha2.mes + "-" + fecha2.dia != '2011-01-31')){
        alert('Fecha NO VALIDA!!.\nElija una fecha anterior o igual al actual');
        document.frm.txtFecha.value=fecha_seleccion;
        return false;
    }*/
    
    //window.parent.frames[1].location="val_left.php?f=" + campoDeRetorno.value ;
    window.parent.frames[1].location="val_left.php?f=" + document.frm.txtFecha.value ;

/*
var numret=(ano + mes + dia)*1;

var mymes_servidor = fecha_servidor.substring(3,5)*1;
var mydia_servidor = fecha_servidor.substring(0,2)*1;
var mymes_actual = mes*1;

alert(mymes_actual + " , " + mymes_servidor + " :: numret: " + numret + " numserv: " + numserv);

alert(numserv*1)
alert(numret*1)
alert("Diferencia entre servidor - fecha: " + ((numserv*1)-(numret*1)));
//-- validar que diferencia de dias no supere permitido
var resta=(numserv*1)-(numret*1)
if (resta>70) resta=resta-70
//if(mymes_servidor!=1){/////////////// ojo cambio temporal solo por diciembre 2008
if (resta><?php echo $ndias ?>){
	alert('Fecha NO VALIDA!!.\nNo puede validar fechas menores a  ' + <?php echo $ndias ?> + ' dias');
    //document.frm.txtFecha.value=fecha_servidor;
    document.frm.txtFecha.value=fecha_seleccion;
    return false;
}

if(numret<=numserv) {
  if ((mymes_actual > mymes_servidor) && (mydia_servidor><?php echo $ndias ?>) ){
  //-- si mes actual es mayor a mes anterior y supera dias de validacion
    alert('Fecha NO VALIDA!!.\nPeriodo de Validación Cerrado');
    //document.frm.txtFecha.value=fecha_servidor;
    document.frm.txtFecha.value=fecha_seleccion;
  }else{
    if ((mymes_actual < mymes_servidor) && (mydia_servidor><?php echo $ndias ?>) ){
      alert('Fecha NO VALIDA!!.\nNo puede seleccionar mes anterior');
      //document.frm.txtFecha.value=fecha_servidor;
      document.frm.txtFecha.value=fecha_seleccion;
    }else {
  	   window.parent.frames[1].location="val_left.php?f=" + campoDeRetorno.value ;
  	}
  }
 }else{
 	alert('Fecha NO VALIDA!!.\nElija una fecha anterior o igual al actual');
    //document.frm.txtFecha.value=fecha_servidor;
    document.frm.txtFecha.value=fecha_seleccion;
 }
 */

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
<form id="frm" name="frm" method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>">
<center class="FormHeaderFont">Validación de Asistencia de Personal</Center>
<table align='center' width="60%" border="0">
 <tr>
    <td width="45%" align="right"><b>Supervisor : </b></td>
    <td align="left"><font color="#3366CC"><b><?php echo $nombre_usuario?></b></font></td>
 </tr>
</table>

<table width="45%"  border="1" cellspacing="1" cellpadding="0" align="center" >
  <tr>
    <td class="ColumnTD" align="center" width="50%">
        Fecha&nbsp;
        <input type="text" class="input" id="txtFecha" name="txtFecha" readOnly size="11" value="<?php echo $fecha ?>"/>
        <img onClick="javascript:pedirFecha(txtFecha,'Cambiar Fecha');" src="../images/columnselect.gif" width="15" height="15" border="0"  style='cursor:hand;' alt="Seleccionar Fecha"/>
    </td>
    <td align="left" class="ColumnTD"><img src="../images/biGoingOnline.gif" style="cursor:hand" onClick="salir();" title="Ir a menu principal"></td>
  </tr>
</table>

<input type="hidden" id="ndias" name="ndias" value="<?php echo $ndias;?>"/>
<input type="hidden" id="area" name="area" value="<?php echo $area;?>"/>
<input type="hidden" id="minutos_refrescar" name="minutos_refrescar" value="<?php echo $minutos_refrescar_dias_validacion;?>"/>

</form>
</body>
</html>