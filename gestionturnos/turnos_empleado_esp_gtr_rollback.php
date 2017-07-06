<?php header("Expires: 0"); ?>
<?php
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../includes/clsCA_Turnos_Empleado.php"); 
require_once("../includes/clsCA_Turnos_Combinacion.php"); 
require_once("../../Includes/MyCombo.php");
set_time_limit(30000);
$empleado_id="";
$fecha=date("d/m/Y");
$h_inicio="";
$m_inicio="";
$t_tiempo="";
$rpta="";
//$empleado_codigo_registro="13625";
$empleado_codigo_registro=$_SESSION["empleado_codigo"];
$cargo_codigo="";
$area_codigo="";
$responsable_codigo=$empleado_codigo_registro;
$nombres="";
$hddbuscar="";
$todos="0";
$empleado_dni="";
$registro = "";
$registros = "";
$mostrarLog = "NO";
//N
$turno_minutos=0;
$turno_combo=0;
$tdni="";

$e = new ca_turnos_empleado();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();

$o = new ca_turnos_empleado();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

$combo = new MyCombo();
$combo->setMyUrl(db_host());
$combo->setMyUser(db_user());
$combo->setMyPwd(db_pass());
$combo->setMyDBName(db_name());

//if (isset($_SESSION["empleado_codigo"])) $empleado_codigo_registro=$_SESSION["empleado_codigo"];
if (isset($_POST["h_inicio"])) $h_inicio = $_POST["h_inicio"];
if (isset($_POST["m_inicio"])) $m_inicio = $_POST["m_inicio"];
if (isset($_POST["t_tiempo"])) $t_tiempo = $_POST["t_tiempo"];
if (isset($_POST["cargo_codigo"])) $cargo_codigo = $_POST["cargo_codigo"];
if (isset($_POST["area_codigo"])) $area_codigo = $_POST["area_codigo"];
if (isset($_POST["responsable_codigo"])) $responsable_codigo = $_POST["responsable_codigo"];
if (isset($_POST["nombres"])) $nombres = $_POST["nombres"];
if (isset($_POST["empleado_dni"])) $empleado_dni = $_POST["empleado_dni"];
if (isset($_POST["fecha"])) $fecha = $_POST["fecha"];

if (isset($_POST['hddaccion'])){
	if ($_POST['hddaccion']=='SVE'){
		$arr = explode(',',$_POST["hddcodigos"]);
                $mostrarLog="SI";
		for($i=0; $i<sizeof($arr); $i++){
			
			$arrT=split("_",$arr[$i]);
			$templeado=$arrT[0];
			$turno_minutos=($arrT[1])+$Tiempo_Permitido_Marca_Ingreso;
			$turno_combo=($h_inicio*60)+$m_inicio;
			$tdni=$arrT[2];
			if($turno_combo*1<$turno_minutos*1){
				echo "Solo puede asignar turno especial ".$Tiempo_Permitido_Marca_Ingreso." minutos despues de su fin de jornada <span style='color: #DE473B;'>".$tdni."</span><br>";
			}else{
				//$o->empleado_codigo=$arr[$i];
				$o->empleado_codigo=$templeado;
				$o->te_fecha_inicio=$fecha;
				$o->total_horas=$h_inicio;
				$o->total_minutos=$m_inicio;
				$o->thorasp=$t_tiempo;
				$o->empleado_codigo_registro = $empleado_codigo_registro; //$_SESSION["empleado_codigo"];
				$rptaa = $o->Add_Update_Especial();
                                $o->Query_Empleado_Nombres();//Obtener el empleado y dni.
                                $registro=$o->empleado_nombres."-".$o->empleado_dni."-".$rptaa;
				if ($registros != "") $registros .= ',';
                                $registros .= $registro;
				/*mcortezc
                                if ($rptaa!='OK'){
					$o->Query_Empleado_Nombres();
					$rpta = "No coincide horas efectivas DNI: ".$o->empleado_dni.", ";
					echo $rpta;
				}else{
					$rpta= "OK";
				}
                                */
                                
			}
		}
                //echo $registros;
		//if($rpta=='OK'){mcortezc
		?>
		<script language='javascript'>
		   //alert('Asignacion De Turnos Satisfactorio!!');
		</script>
		<?php
		//}
	}
	if ($_POST['hddaccion']=='DLT'){
		$o->empleado_codigo = $_POST["hddcodigos"];
		$o->te_fecha_inicio = $fecha;
                $o->empleado_codigo_registro = $empleado_codigo_registro;
                //echo "emple".$o->empleado_codigo_registro;
		$rpta= $o->Quitar_Turno_Especial();
		if ($rpta!='OK') echo "<br><b>" . $rpta . "</b>";
	}
}

$esadmin="NO";
$o->empleado_codigo_registro = $_SESSION["empleado_codigo"];
$esadmin = $o->Query_Rol_Admin();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Asignar Turno Especial</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscript.js"></script>

<link rel="stylesheet" type="text/css" href="../../views/js/librerias/jquery/plugin/easyui/themes/default/easyui.css"/>
<link rel="stylesheet" type="text/css" href="../../views/js/librerias/jquery/plugin/easyui/themes/icon.css"/>
<script type="text/javascript" src="../../views/js/librerias/jquery/plugin/easyui/jquery.min.js"></script>
<script type="text/javascript" src="../../views/js/librerias/jquery/plugin/easyui/jquery.easyui.min.js"></script>

<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
<style type="text/css">
    .window-shadow{
        /*background-color: #E7E7D6;*/
    }
    .dialog-toolbar{
        /*background-color:red;*/
        /*background-color: #E7E7D6;*/
    }
    #pop_log{
        /*background-color:red;*/
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){
        if('<?php echo $mostrarLog;?>'=='SI'){
            var registros='<?php echo $registros;?>';
            var arrRegistros = registros.split(",");
            var newHtml='';
                newHtml+='<table id="table_especial" border="0" class="FormTable" cellspacing="0" width="100%" style="font-size:9px;">';
                newHtml+='<thead>';
                    newHtml+='<tr>';
                    newHtml+='<th class="ColumnTD">Nombres</th>';
                    newHtml+='<th class="ColumnTD">DNI</th>';
                    newHtml+='<th class="ColumnTD">Resultado</th>';
                    newHtml+='</tr>';
                newHtml+='</thead>';
                newHtml+='<tbody>';
            
            for(var i=0;i<arrRegistros.length;i++){
                newHtml+='<tr class="ca_DataTD" onMouseout=this.style.backgroundColor="" onMouseover=this.style.backgroundColor="#F4F4F4">';
                    newHtml+='<td>'+arrRegistros[i].split("-")[0]+'</td>';
                    newHtml+='<td>'+arrRegistros[i].split("-")[1]+'</td>';
                    newHtml+='<td>'+arrRegistros[i].split("-")[2]+'</td>';
                newHtml+='</tr>';
            }
            
            newHtml+='</tbody>';
            newHtml+='</table>';
            
            $("#table_log").empty().append(newHtml);
            
            $("#pop_log").dialog({
                width: 550
                ,modal: true
                ,title : "Log >> Turno Especial"
                ,height: 200
                ,resizable:true
            });
            
        }
    });
</script>

<script language="JavaScript">
var mensaje='';

function asignar(){
	/*if('<?php echo $esadmin;?>'!='OK'){
		alert('No esta Autorizado Asignar o Reasignar Turnos, Comuniquese con el Administrador del Sistema');
		return false;
	}*/
	var codigos='';
	if (document.frm.h_inicio.value==0){
		alert('Seleccione Hora de Inicio');
		document.frm.h_inicio.focus();
		return false;
	}
	if (document.frm.t_tiempo.value==0){
		alert('Seleccione Tiempo en Horas');
		document.frm.t_tiempo.focus();
		return false;
	}
	document.frm.todos.checked=false;
	for(i=0; i< document.frm.length; i++ ){
		if (frm.item(i).type=='checkbox'){
			 if ( frm.item(i).checked ){
				if (codigos==''){
					codigos=frm.item(i).value;
				}else{
					codigos+= ',' + frm.item(i).value;
				}
			}
		}
	}
	if (codigos==''){
		alert('Seleccione Algun Registros de Empleados');
		return false
	}
	if (confirm('Asignar Turno a Empleados Seleccionados?')==false) return false;
	document.frm.hddbuscar.value='OK';
	document.frm.hddaccion.value='SVE';
	document.frm.hddcodigos.value= codigos;
	document.frm.submit();
}

function Quitar(codigo){
	if('<?php echo $esadmin;?>'!='OK'){
		alert('No esta Autorizado Eliminar Turno Programado, Comuniquese con el Administrador del Sistema');
		return false;
	}
	if(confirm('Seguro de Quitar el Turno del Empleado')==false) return false;
	document.frm.hddbuscar.value='OK';
	document.frm.hddaccion.value='DLT';
	document.frm.hddcodigos.value= codigo;
	document.frm.submit();
}

function Buscar(){
	var fecha_inicio = document.getElementById('fecha').value.split('/');
	var now=new Date();
	var dia=now.getDate();
	var mes=now.getMonth() + 1;
	var anio=now.getYear();
	if (mes*1<=9){tmpmes = '0'+mes*1; }else{tmpmes = mes;}
	if (dia*1<=9){tmpdia = '0'+dia*1; }else{tmpdia = dia;}
	if (fecha_inicio[1]*1<=9){atmpmes = '0'+fecha_inicio[1]*1; }else{atmpmes = fecha_inicio[1];}
	if (fecha_inicio[0]*1<=9){atmpdia = '0'+fecha_inicio[0]*1; }else{atmpdia = fecha_inicio[0];}
	var f_actual = anio+''+tmpmes+''+tmpdia;
	var f_inicio = fecha_inicio[2]+''+atmpmes+''+atmpdia;
	if (f_actual*1>f_inicio*1){
		alert('Atencion La fecha no puede ser anterior a la fecha actual');
		document.getElementById('fecha').focus();
		return false;
	}
//alert('x');return false;
	var nombres=document.frm.nombres.value;
	var cargo_codigo=document.frm.cargo_codigo.value;
	var area_codigo=document.frm.area_codigo.value;
	var responsable_codigo=document.frm.responsable_codigo.value;
	document.frm.hddbuscar.value="OK";
	document.frm.action +="?nombres=" + nombres + "&cargo=" + cargo_codigo + "&area_codigo=" + area_codigo + "&responsable_codigo=" + responsable_codigo;
	document.frm.submit();
}

function cambiarcheck(){
	if(document.frm.todos.checked){
		checkear_todos_empleados(true);
	}else{
		checkear_todos_empleados(false);
	}
}

function checkear_todos_empleados(flag){
	var r=document.getElementsByTagName('input');
	for (var i=0; i< r.length; i++) {
		var o=r[i];
		var oo=o.id;
		if(oo.substring(0,3)=='chk'){
			if(o.checked){
				o.checked=flag;
			}else{
				o.checked=flag;
			}
		}    
	}
}


function pedirFecha(campoTexto,nombreCampo) {
	document.getElementById('cmdAgrupar').disabled=true;
	fecha_seleccion=campoTexto.value;
	ano = anoHoy();
	mes = mesHoy();
	dia = diaHoy();
	campoDeRetorno = campoTexto;
	titulo = nombreCampo;
	dibujarMes(ano,mes);
}

function desactiva_aplicar(){
	mensaje.innerHTML = '*';
	document.getElementById('cmdAgrupar').disabled=true;
}

</script>

</head>

<body class="PageBODY" onLoad="return WindowResize(10,20,'center')" >
<div id="popCalF" style="BORDER-BOTTOM: 2px ridge; Z-INDEX:200; BORDER-LEFT: 2px ridge; BORDER-RIGHT: 2px ridge; BORDER-TOP: 2px ridge; POSITION: absolute; VISIBILITY: hidden; WIDTH: 10px">
	<iframe name="popFrameF" id="popFrameF" src="../popcj.htm" frameborder="1" scrolling="no" width="183" height="188"></iframe>
</div>
<form name='frm' id='frm' method='post' action='<?php echo $_SERVER['PHP_SELF'] ?>' >
<CENTER class="FormHeaderFont">Asignar Turno Especial</CENTER>
<br />
<table class='DataTD' align='center'  width='95%' border='0' cellpadding='1' cellspacing='0'>
	<tr>
		<td  class='FieldCaptionTD'  align='center' colspan='4' >Filtro de Empleados
		</td>
	</tr>
	<tr>
        <td align="right" width="80px">Por Nombre&nbsp;</td>
		<td ><input class='Input' name='nombres' id='nombres' type='text' style='width=200px' value='<?php echo $nombres ?>' >
		Dni:
		<input class='Input' name='empleado_dni' id='empleado_dni' type='text' style='width=60px' maxlength='10' value='<?php echo $empleado_dni ?>' >
        </td>
		<td align='right'>Cargo</td>
		<td><?php
			$combo = new MyCombo();
			$combo->setMyUrl(db_host());
			$combo->setMyUser(db_user());
			$combo->setMyPwd(db_pass());
			$combo->setMyDBName(db_name());

			$ssql = "select cargo_codigo, cargo_descripcion from vdatos ";
			$ssql.= " where area_codigo in (select area_codigo from ca_controller where empleado_codigo=" . $empleado_codigo_registro . " and activo=1 ";
			$ssql.= " union select area_codigo from vdatos where empleado_codigo=".$empleado_codigo_registro." ) ";
			$ssql.= " group by cargo_codigo, cargo_descripcion ";
			$ssql.= " Order by 2";
			$combo->query = $ssql;
			$combo->name = "cargo_codigo";
			$combo->value = $cargo_codigo."";
			$combo->more = "class=select style='width:300px'";
			$rpta = $combo->Construir_todos();
			echo $rpta;
		  ?>
        </td>
	</tr>
	<tr>
		<td align='right'>Area</td>
		<td><?php
			$combo = new MyCombo();
			$combo->setMyUrl(db_host());
			$combo->setMyUser(db_user());
			$combo->setMyPwd(db_pass());
			$combo->setMyDBName(db_name());

			$ssql = "select area_codigo, area_descripcion from vdatos ";
			$ssql.= " where area_codigo in (select area_codigo from ca_controller where empleado_codigo=" . $empleado_codigo_registro . " and activo=1 )";
			/*
            $ssql.= " union select area_codigo from vdatos where empleado_codigo=".$empleado_codigo_registro." ) ";*/
			$ssql.= " group by area_codigo, area_descripcion ";
			$ssql.= " Order by 2";
			$combo->query = $ssql;
			$combo->name = "area_codigo";
			$combo->value = $area_codigo."";
			$combo->more = "class=select style='width:300px'";
			$rpta = $combo->Construir_todos();
			echo $rpta;
		  ?>
        </td>
		<td align='right'>Responsable</td>
		<td><?php
			$combo = new MyCombo();
			$combo->setMyUrl(db_host());
			$combo->setMyUser(db_user());
			$combo->setMyPwd(db_pass());
			$combo->setMyDBName(db_name());

			/*$ssql = " select cae.responsable_codigo,v.empleado ";
			$ssql.= " from ca_asignacion_empleados cae join vdatos v on ";
			$ssql.= " cae.responsable_codigo=v.empleado_codigo ";
			$ssql.= " where cae.asignacion_activo=1 and cae.responsable_codigo in ";
			$ssql.= " ( ";
			$ssql.= " 	select empleado_codigo from vdatos where area_codigo in ";
			$ssql.= " 	( ";
			$ssql.= " 		select area_codigo from ca_controller where activo=1 ";
			$ssql.= " 		and empleado_codigo=" . $empleado_codigo_registro;
			$ssql.= " 		union ";
			$ssql.= " 		select area_codigo from vdatos where empleado_codigo=" . $empleado_codigo_registro;
			$ssql.= " 	) ";
			$ssql.= " ) ";
			$ssql.= " group by cae.responsable_codigo,v.empleado ";
			$ssql.= " order by 2";*/
                        
                        
                        $ssql = " select cae.responsable_codigo,v.empleado ";
                        $ssql.= " from ca_asignacion_empleados cae join vdatostotal v on ";
                        $ssql.= " cae.responsable_codigo=v.empleado_codigo ";
                        $ssql.= " where cae.asignacion_activo=1 and cae.responsable_codigo in ";
                        $ssql.= " ( ";
                        $ssql.= "              select empleado_codigo from vdatostotal where area_codigo in ";
                        $ssql.= "              ( ";                                                         
                        $ssql.= "                              select area_codigo from ca_controller where ";
                        $ssql.= "                              empleado_codigo=" . $empleado_codigo_registro . " and activo=1 ";
                        //$ssql.= "                              union ";
                        //$ssql.= "                              select area_codigo from Empleado_Area where empleado_codigo=" . $empleado_codigo_registro . " and Empleado_Area_Activo=1";
                        $ssql.= "              ) ";
                        $ssql.= " ) ";
                        $ssql.= " group by cae.responsable_codigo,v.empleado ";
                        $ssql.= " order by 2";

			$combo->query = $ssql;
			$combo->name = "responsable_codigo";
			$combo->value = $responsable_codigo."";
			$combo->more = "class=select style='width:300px'";
			$rpta = $combo->Construir_todos();
			echo $rpta;
		  ?>
        </td>
	</tr>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
	<tr>
        <td align="center" colspan="4">
		Fecha&nbsp;
		<input type='text' class='input' id='fecha' name='fecha' size='11' value='<?php echo $fecha?>' readOnly >
		<img onClick="javascript:pedirFecha(fecha,'Cambiar Fecha');" src="../images/columnselect.gif" width="15" height="15" border="0"  style='cursor:hand;' alt="Seleccionar Fecha">
		 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Inicio Hora:
		 <select  class='select' name='h_inicio' id='h_inicio' style='width:40px' onchange='desactiva_aplicar()'>
		  <?php
		   for($h=0; $h < 24; $h++){
		     $hh=$h;
             if(strlen($h)<=1) $hh="0".$h;
				 if ($h==$h_inicio) echo "\t<option value=". $h . " selected>". $hh ."</option>" . "\n";
			     else 
				 echo "\t<option value=". $h . ">". $hh ."</option>" . "\n";
		   }
		  ?>
		 </select>
		 &nbsp;Minutos:
		 <select  class='select' name='m_inicio' id='m_inicio' style='width:40px' onchange='desactiva_aplicar()'>
		  <?php
		   for($m=0; $m < 60; $m++){
		     $mm=$m;
             if(strlen($m)<=1) $mm="0".$m;
		      if($m==$m_inicio) echo "\t<option value=". $m ." selected>". $mm ."</option>" . "\n";
			  else echo "\t<option value=". $m . ">". $mm ."</option>" . "\n";
		   }
		  ?>
		 </select>
		 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tiempo Horas:
		 <select  class='select' name='t_tiempo' id='t_tiempo' style='width:40px' >
		  <?php
		   for($t=1; $t < 13; $t++){
		     $tt=$t;
             if(strlen($t)<=1) $tt="0".$t;
		      if($t==$t_tiempo) echo "\t<option value=". $t ." selected>". $tt ."</option>" . "\n";
			  else echo "\t<option value=". $t . ">". $tt ."</option>" . "\n";
		   }
		  ?>
		 </select>
		 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input name='cmdBuscar' id='cmdBuscar' type='button' value='Buscar' class='Button' onclick="return Buscar();" style='width:100px' title='Para activar ASIGNAR buscar ahora.'>
		</td>
		<td id='mensaje'>
		</td>
	</tr>
</table>
<br>
<table class='DataTD' align=center  width='98%' border='0' cellpadding='1' cellspacing='0'>
	<tr align="center" >
    	<td class="DataTD" colspan='2'>
	 		<input class='button' type='button' id='cmdAgrupar' onClick='asignar()' value='Asignar'  style='width:80px'>
	 		<input class='button' type='button' id='cmdCerrar' onClick="self.location.href='../menu.php'" value='Cerrar' style='width:80px'>
		</td>
	<tr>
</table>
<table class='FormTable' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:98%'>
	<tr align='center' >
    	<td class='ColumnTD' style='width:10px'>Nro.</td>
    	<td class='ColumnTD' ><Input class='Input' type='checkbox' name='todos' id='todos' title='Marcar Todos Los Empleados' value='1' <?php if ($todos=="1") echo "Checked"; ?> onclick='cambiarcheck();'></td>
    	<td class='ColumnTD'>DNI</td>
    	<td class='ColumnTD'>Nombres</td>
		<td class='ColumnTD'>Area</td>
		<td class='ColumnTD'>T Progr.</td>
		<td class='ColumnTD'>T Espec.</td>
		<td class='ColumnTD'>Entrada</td>
		<td class='ColumnTD'>Salida</td>
		<td class='ColumnTD'>Quitar</td>
	</tr>
	<?php
        
        //echo "xxx". $_POST['hddbuscar'];
	if (isset($_POST['hddbuscar'])){
		if($_POST["hddbuscar"]=='OK'){

			$nombres=$_POST["nombres"];
			$cargo_codigo=$_POST["cargo_codigo"];
			$area_codigo=$_POST["area_codigo"];
			$e->empleado_codigo_registro=$_SESSION['empleado_codigo'];
                        
			$cadena=$e->Traer_Empleados_Especial($cargo_codigo,$area_codigo,$nombres,$h_inicio,$m_inicio, $responsable_codigo,$esadmin,$empleado_dni,$fecha);
			echo $cadena;
                        
		}
	}
	if (isset($_GET['hddbuscar'])){
		if($_GET["hddbuscar"]=='OK'){
                        
			$h_inicio=0;
			$m_inicio=0;
			$t_tiempo=1;
			$e->empleado_codigo_registro=$_SESSION['empleado_codigo'];
			$cadena=$e->Traer_Empleados_Especial($cargo_codigo,$area_codigo,$nombres,$h_inicio,$m_inicio, $responsable_codigo,$esadmin,$empleado_dni,$fecha);
			echo $cadena;
		}
	}
	?>
</table>
<br>
<br>
<table width='80%' border='0' cellspacing='0' cellpadding='0' align='center'>
  <tr>
    <td align='center'>
		<input type='button' id='cmd4' onClick="self.location.href='../menu.php'" value='Cerrar' class='Button' style='width:80px'>
	</td>
  </tr>
</table>
<div id="pop_log">
    <div id="table_log"></div>
    
</div>
<input type="hidden" id="hddbuscar" name="hddbuscar" value="">
<input type="hidden" id="hddcodigos" name="hddcodigos" value="">
<input type="hidden" id="hddaccion" name="hddaccion" value="">
</form>
</body>
</html>