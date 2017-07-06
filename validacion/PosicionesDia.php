<?php header("Expires: 0");

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/MyCombo.php");
require_once("../includes/clsCA_Posiciones.php");
require_once("../includes/clsCA_Usuarios.php");
require_once("../../Includes/librerias.php");

$id = $_SESSION["empleado_codigo"];

$cod_personal='';
$instalada='';
$reasignada='';
$instaladahc='';
$reasignadahc='';

$usr = new ca_usuarios();
$usr->setMyUrl(db_host());
$usr->setMyUser(db_user());
$usr->setMyPwd(db_pass());
$usr->setMyDBName(db_name());

$usr->empleado_codigo = $id;
$r = $usr->Identificar();
$empleado= $usr->empleado_nombre;
$area= $usr->area_codigo;
$area_descripcion= $usr->area_nombre;
$jefe= $usr->empleado_jefe; // responsable area
$fecha= $usr->fecha_actual;
$dia= $usr->numero_dia;

$THoja_Codigo=0;
$cod_campana=0;

$o = new ca_posiciones();
$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());


if (isset($_GET["cboPersonal"])) $cod_personal = $_GET["cboPersonal"];
if (isset($_GET["cod_campana"])) $cod_campana = $_GET["cod_campana"];
if (isset($_GET["txtFecha"])) $fecha = $_GET["txtFecha"];

if (isset($_POST["txtFecha"])) $fecha = $_POST["txtFecha"];
if (isset($_POST["cboPersonal"])) $cod_personal = $_POST["cboPersonal"];
if (isset($_POST["cod_campana"])) $cod_campana = $_POST["cod_campana"];
if (isset($_POST["txtInstalada"])) $instalada= $_POST["txtInstalada"];
if (isset($_POST["txtInstaladaHC"])) $instaladahc = $_POST["txtInstaladaHC"];
if (isset($_POST["txtReasignada"])) $reasignada= $_POST["txtReasignada"];
if (isset($_POST["txtReasignadaHC"])) $reasignadahc = $_POST["txtReasignadaHC"];


if (isset($_POST['hddAccion'])){
  if ($_POST['hddAccion']=='Guardar'){
    $o->posicion_personal=$cod_personal;
    $o->cod_campana=$cod_campana;
    $o->posicion_fecha=$fecha;
    $o->posicion_empleado_codigo=$id;

    if($cod_personal==1){
        $codigo1 = "1";
        $codigo2 = "2";
        $codigo3 = "3";
        $codigo4 = "4";
    }else{
        $codigo1 = "5";
        $codigo2 = "6";
        $codigo3 = "7";
        $codigo4 = "8";
    }
    
    if($instalada=="") {
        $o->total="";
        $o->subt_posicion_codigo=$codigo1;
        $rpta=$o->Save();
    }else{
        $o->total=$instalada;
        $o->subt_posicion_codigo=$codigo1;	
        $rpta=$o->Save();
    }
    
    if($rpta!="OK") echo "Error : " .$rpta;

    if($instaladahc==""){
        $o->total="";
        $o->subt_posicion_codigo=$codigo2;
        $rpta=$o->Save();
    }else{
        $o->total=$instaladahc;
        $o->subt_posicion_codigo=$codigo2;	
        $rpta=$o->Save();
    }
    
    if($rpta!="OK") echo "Error : " .$rpta;
	
    if($reasignada==""){
        $o->total="";
        $o->subt_posicion_codigo=$codigo3;
        $rpta=$o->Save();
    }else{
        $o->total=$reasignada;
        $o->subt_posicion_codigo=$codigo3;	
        $rpta=$o->Save();
    }
    
    if($rpta!="OK") echo "Error : " .$rpta;
	
    if($reasignadahc=="") {
        $o->total="";
        $o->subt_posicion_codigo=$codigo4;
        $rpta=$o->Save();
    }else{
        $o->total=$reasignadahc;
        $o->subt_posicion_codigo=$codigo4;	
        $rpta=$o->Save();
    }
    
    if($rpta!="OK") echo "Error : " .$rpta;
    if($rpta=="OK") echo "Se guardo registro con exito.";

    }
}	


if (isset($_POST['hddAccion'])){
    if ($_POST['hddAccion']=='Buscar'){
    $o->posicion_personal=$cod_personal;
    $o->cod_campana=$cod_campana;
    $o->posicion_fecha=$fecha;

    //Buscar posiciones
    $str=$o->Buscar_Posiciones();
    $array= split("_",$str);
        for ($i=0;$i<count($array);$i++){
        $arr= split(",",$array[$i]);
            switch($arr[0]){
                case "1": $instalada = $arr[2]; break;		
                case "2": $instaladahc = $arr[2]; break;
                case "3": $reasignada = $arr[2]; break; 
                case "4": $reasignadahc = $arr[2]; break;
                case "5": $instalada = $arr[2]; break;
                case "6": $instaladahc = $arr[2]; break;
                case "7": $reasignada = $arr[2]; break;
                case "8": $reasignadahc = $arr[2]; break;		 
            }
        }
    }
}	

?>
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' type='text/css' href='../style/tstyle.css'>
<title>Posiciones del Día</title>	
	<style>
TABLE
	{
		BORDER-BOTTOM: #00415d 0pt solid;
		BORDER-LEFT: #00415d 0pt solid;
		BORDER-RIGHT: #00415d 0pt solid;
		BORDER-TOP: #00415d 0pt solid;
		FONT-SIZE: 8pt;
		MARGIN: 4px;
		PADDING-BOTTOM: 0px;
		PADDING-LEFT: 0px;
		PADDING-RIGHT: 0px;
		PADDING-TOP: 0px
	}
	</style>
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<SCRIPT LANGUAGE=javascript>
function cmdCerrar_onclick() {
	window.close();
}

function cmdGuardar_onclick(){
	if(document.frm.cod_campana.value==0){
		alert('Seleccione el servicio');
		document.frm.cod_campana.focus(); 
		return false;
	}
	
	if(document.frm.txtInstaladaHC.value !=""){ 
		if(document.frm.txtInstalada.value ==""){
		    alert("No puede declarar Posiciones en Hora Cargada si no ha declarado posiciones asignadas");
			document.frm.txtInstaladaHC.focus();
			return false;
		}
		if(parseInt(document.frm.txtInstaladaHC.value) > parseInt(document.frm.txtInstalada.value)){
			alert("Número de Posiciones en Hora Cargada no debe exceder al de Posiciones Asignadas");
			document.frm.txtInstaladaHC.focus();
			return false;
		}
	}
	if(document.frm.txtReasignadaHC.value != ""){  
		if(document.frm.txtReasignada.value ==""){
			alert("No puede declarar Posiciones Reasignadas en Hora Cargada si no ha declarado Posiciones Reasignadas");
			document.frm.txtInstaladaHC.focus();
			return false;
		}
		if(parseInt(document.frm.txtReasignadaHC.value) > parseInt(document.frm.txtReasignada.value)){
			alert("Número de Posiciones Reasignadas en Hora Cargada no debe exceder al de Posiciones Reasignadas");
			document.frm.txtReasignadaHC.focus();
			return false;
		}
	}
	if (confirm('Confirma Guardar Datos')==false) return false;
	document.frm.hddAccion.value="Guardar";
	document.frm.submit();
}

function cboServicios_onchange(){
//cboServicios_onchange()
	if(document.frm.cboPersonal.value==0){
		alert('Seleccione Tipo de Personal');
		document.frm.cod_campana.value=0;
		inicializar_controles();
		document.frm.cboPersonal.focus(); 
		return false;
	}
 var personal=document.frm.cboPersonal.value;	
 var campana=document.frm.cod_campana.value;
 var fecha=document.frm.txtFecha.value;
	
 if(document.frm.cod_campana.value==0){
	 inicializar_controles();
 }else{
	inicializar_controles();
    document.frm.action +="?cboPersonal=" + personal + "&cod_campana=" + campana + "&txtFecha=" + fecha;
    document.frm.hddAccion.value="Buscar";	
	document.frm.submit();	
	//set s = RSGetASPObject( serverURL )
	//set co = s.PosicionesServicio( frm.cboServicios.value, frm.txtFecha.value, frm.cboPersonal.value )
	
	//call DistribuirDatos(co)	
  }
}

function cboPersonal_onchange(){
 var personal=document.frm.cboPersonal.value;	
 var campana=document.frm.cod_campana.value;
 var fecha=document.frm.txtFecha.value;
 if(document.frm.cod_campana.value==0){
	 inicializar_controles();
 }else{
	inicializar_controles();
    document.frm.action +="?cboPersonal=" + personal + "&cod_campana=" + campana + "&txtFecha=" + fecha;
    document.frm.hddAccion.value="Buscar";	
	document.frm.submit();	
  }
}


function inicializar_controles(){
  document.frm.txtInstalada.value	="";
  document.frm.txtInstaladaHC.value	="";
  document.frm.txtReasignada.value	="";
  document.frm.txtReasignadaHC.value	="";
}

function inicializar_parametros(){
  document.frm.cboPersonal.value	="0";
  document.frm.cod_campana.value	="0";
  inicializar_controles()
}
function pedirFecha(campoTexto,nombreCampo) {
  ano = anoHoy();
  mes = mesHoy();
  dia = diaHoy();
  campoDeRetorno = campoTexto;
  titulo = nombreCampo;
  dibujarMes(ano,mes);
}
</SCRIPT>
</HEAD>
<BODY  class="PageBODY">
<table align='center' width="60%" border="0">
 <tr>
    <td width='40%' align=right><b>Supervisor:</b></td>
	<td class='CA_FormHeaderFont' align=left><?php echo $empleado?></td>
 </tr>
</table>
<br>
<form id=frm name=frm action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
<TABLE class='FormTable' align=center border=0 cellPadding=1 cellSpacing=1 width=95% bordercolor=Goldenrod>
	<TR>
    <TD class="ColumnTD" width="80%" align="right">
	<STRONG>Fecha</STRONG>&nbsp;</TD>
    <TD class="DataTD" colspan="2">&nbsp;
		<input class="input" style="TEXT-ALIGN: center; WIDTH: 70px" name="txtFecha" id="txtFecha" readOnly value="<?php echo $fecha ?>"/>
	    <img onClick="javascript:pedirFecha(txtFecha,'Cambiar Fecha');inicializar_parametros();" src="../images/columnselect.gif" width="15" height="15" border="0"  style='cursor:hand;' alt="Seleccionar Fecha">
			
	</TD>
  </TR>
	<TR>
    <TD style="with:150px" class='ColumnTD' align="right"><STRONG>Posiciones de Personal</STRONG></TD>
    <TD colspan=2 class='DataTD' >&nbsp;
		<SELECT class=select id=cboPersonal name=cboPersonal style="HEIGHT: 18px; WIDTH: 259px" onchange='cboPersonal_onchange()'> 
			<OPTION value=0 selected>-- Personal --</OPTION>
			<OPTION value=1 <?php if($cod_personal==1) echo "selected"?>>Operador</OPTION>
            <OPTION value=2 <?php if($cod_personal==2) echo "selected"?>>Supervisor</OPTION>
		</SELECT></TD>
    </TR>
  <TR>
    <TD style="with:150px" class='ColumnTD' align="right"><STRONG>Seleccione Servicio</STRONG></TD>
    <TD colspan=2 class='DataTD' >&nbsp;
	<?php
            $combo = new MyCombo();
            $combo->setMyUrl(db_host());
            $combo->setMyUser(db_user());
            $combo->setMyPwd(db_pass());
            $combo->setMyDBName(db_name());

            $sql ="select v_campanas.cod_campana as codigo, ";
            $sql .=" v_campanas.exp_codigo + '-' + v_campanas.exp_nombrecorto + '(' + convert(varchar,v_campanas.cod_campana) + ')' as descripcion, "; 
            $sql .="  case when v_Campanas.Exp_Dias_Modi > DATEDIFF(day, CONVERT(datetime,'" . $fecha . "', 103), GETDATE()) then 1 else 0 end as Dias";
            $sql .=" from v_campanas ";
            $sql .=" where  v_campanas.exp_activo=1 and coordinacion_codigo=" . $area;                   
            $sql .=" order by 2 "; 

            $combo->query = $sql;
            $combo->name = "cod_campana"; 
            $combo->value = $cod_campana."";
            $combo->more = " class='select' onchange='cboServicios_onchange()'";
            $rpta = $combo->Construir();
            echo $rpta;
	?>
    </TD>
    </TR>
</table>
<TABLE border=0 cellPadding=1 cellSpacing=1 width=400 align=center style="HEIGHT: 24px; WIDTH: 468px">
  <TR align=middle>
    <TD>
	 <center class="CA_FormHeaderFont">Registro de Posiciones</Center>
	</TD>
  </TR>
</TABLE>
<TABLE align=center border=1 cellPadding=2 cellSpacing=0 width=400>
<TR align=center>
    <TD class='ColumnTD' colspan=2 style="with:200px"  align=center><STRONG>Posiciones</STRONG></TD>
    <TD class='ColumnTD' colspan=2 style="with:200px"  align=center><STRONG>Posiciones Reasignadas</STRONG></TD>
 </TR>
  <TR >
    <TD class='DataTD'>Posiciones Instaladas</TD>
    <TD class='DataTD'><INPUT class='input' id=txtInstalada name=txtInstalada style="HEIGHT: 22px; WIDTH: 77px" value='<?php echo $instalada?>'></TD>
    <TD class='DataTD'>Posiciones Reasignadas</TD>
    <TD class='DataTD'><INPUT class='input' id=txtReasignada name=txtReasignada style="HEIGHT: 22px; WIDTH: 77px" value='<?php echo $reasignada?>'></TD>
  </tr>
  <tr>
    <TD class='DataTD'>Posiciones en Hora Cargada</TD>
    <TD class='DataTD'><INPUT class='input' id=txtInstaladaHC name=txtInstaladaHC style="HEIGHT: 22px; WIDTH: 77px" value='<?php echo $instaladahc?>'></TD>
    <TD class='DataTD'>Posiciones en Hora Cargada</TD>
    <TD class='DataTD'><INPUT class='input' id=txtReasignadaHC name=txtReasignadaHC style="HEIGHT: 22px; WIDTH: 78px" value='<?php echo $reasignadahc?>'></TD>
  </TR>
</table>

<TABLE align=center border=0 cellPadding=1 cellSpacing= 1 width=400 bordercolordark=IndianRed>
  <TR style="HEIGHT: 40px"  align=center>
    <TD  align=right><INPUT class='button' id=cmdGuardar name=cmdGuardar type=button value=Guardar style="width:100px" LANGUAGE=javascript onClick="return cmdGuardar_onclick()"></TD>
    <TD  align=left><INPUT class='button' type="button" value="Cerrar" id=cmdCerrar name=cmdCerrar style="width:100px" LANGUAGE=javascript onClick="return cmdCerrar_onclick()"></TD>
  </TR>
 </TABLE>
<INPUT type="hidden" id=hddAccion name=hddAccion value=>
</form>
</BODY>
</HTML>