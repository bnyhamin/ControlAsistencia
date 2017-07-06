<?php header("Expires: 0"); ?>
<?php
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php");
require('../includes/MyGrillaEasyUI.php');
require_once("../../Includes/librerias.php");
require_once("../../Includes/MyCombo.php");
require_once("../includes/clsGmenu.php");
require_once("../includes/Seguridad.php");
require_once("../includes/clsCA_Turnos_Empleado.php");
?>
<?php

$fecha = "";
$area_id = "0";
$area_codigo = "0";
$responsable_codigo = "0";
$turno_codigo = "0";
$opcion = "0";
$hoy = getdate();
$sql4= "";
$anio_codigo = $hoy['year'];
$mes_codigo =  $hoy['mon'];
$empleado_id=$_SESSION["empleado_codigo"];
$controlar=1;

if(isset($_GET["area_codigo"])) $area_codigo = $_GET["area_codigo"];
if(isset($_GET["controlar"])) $controlar = $_GET["controlar"];
else if(isset($_POST["controlar"])) $controlar = $_POST["controlar"];
//echo $controlar;

if(isset($_GET["fecha"])) $fecha = $_GET["fecha"];
if(isset($_GET["responsable_codigo"])) $responsable_codigo = $_GET["responsable_codigo"];
if(isset($_GET["turno_codigo"])) $turno_codigo = $_GET["turno_codigo"];
if(isset($_GET["opcion"])) $opcion = $_GET["opcion"];
if(isset($_GET["anio_codigo"])) $anio_codigo = $_GET["anio_codigo"];
if(isset($_POST["area_codigo"])) $area_codigo = $_POST["area_codigo"];
if(isset($_GET["area_id"])) $area_id = $_GET["area_id"];
if(isset($_POST["fecha"])) $fecha = $_POST["fecha"];
if(isset($_POST["responsable_codigo"])) $responsable_codigo = $_POST["responsable_codigo"];
if(isset($_POST["turno_codigo"])) $turno_codigo = $_POST["turno_codigo"];
if(isset($_POST["opcion"])) $opcion = $_POST["opcion"];
if(isset($_POST["anio_codigo"])) $anio_codigo = $_POST["anio_codigo"];
if(isset($_POST["mes_codigo"])) $mes_codigo = $_POST["mes_codigo"];

$titulo='';
$gm = new gmenu();
$gm->setMyUrl(db_host());
$gm->setMyUser(db_user());
$gm->setMyPwd(db_pass());
$gm->setMyDBName(db_name());

    switch($opcion){
    case '1.1':
            $titulo = "Lista de Empleados";
            break;
    case '1.2':
            $titulo = "Marcaciones Realizadas";
            break;
    case '1.3':
            $titulo = "Incidencias Reportadas";
            break;
    case '1.4':
            $titulo = "Marcacion Mensual";
            break;
    case '1.5':
            $titulo = "Horas Acumuladas por Semana";
            break;
    case '2.1':
            $titulo = "Eventos Abiertos";
            break;
    case '2.2':
            $titulo = "Eventos Cerrados";
            break;
    case '3.1':
            $titulo = "Personal con Tiempo Parcial - En Posición";
            break;
    case '3.2':
            $titulo = "Personal con Tiempo Parcial - Horas Acumuladas por Semana";
            break;
    }

?>

<script language="JavaScript" src="../jscript.js"></script>
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript">
function cambiar_mes() {
 	document.frm.submit();
}

function ver_detalle(lista) {

    var f=window.parent.frames[0].document.frm.txtFecha.value;
    var a=window.parent.frames[0].document.frm.area_codigo.value;
    var r=window.parent.frames[0].document.frm.responsable_codigo.value;
    var t=window. parent.frames[0].document.frm.turno_codigo.value;
    var o= <?php echo $opcion ?>;

    switch (o){
        case 1.1:
            window.open("lista_empleados.php?lista_sel=" + lista + "&fecha="+f+"&area_codigo="+a+"&responsable_codigo="+r+"&turno_codigo="+t+"&opcion="+o,"nombre","toolbar=0, menubar=0, status=1, location=0, hotkeys=0, scrollbars=1, resizable =1 width=700, height=480, center=yes")
        break;
        case 1.2:
            window.open("lista_marcaciones.php?lista_sel=" + lista + "&fecha="+f+"&area_codigo="+a+"&responsable_codigo="+r+"&turno_codigo="+t+"&opcion="+o,"nombre","toolbar=0, menubar=0, status=1, location=0, hotkeys=0, scrollbars=1, resizable =1 width=700, height=480, center=yes")
        break;
        case 1.3:
            window.open("lista_incidencias.php?lista_sel=" + lista + "&fecha="+f+"&area_codigo="+a+"&responsable_codigo="+r+"&turno_codigo="+t+"&opcion="+o,"nombre","toolbar=0, menubar=0, status=1, location=0, hotkeys=0, scrollbars=1, resizable =1 width=800, height=480, center=yes")
        break;
        case 2.1:
            window.open("lista_eventos.php?lista_sel=" + lista + "&fecha="+f+"&area_codigo="+a+"&responsable_codigo="+r+"&turno_codigo="+t+"&opcion="+o,"nombre","toolbar=0, menubar=0, status=1, location=0, hotkeys=0, scrollbars=1, resizable =1 width=800, height=480, center=yes")
            break;
        case 2.2: 
            window.open("lista_eventos.php?lista_sel=" + lista + "&fecha="+f+"&area_codigo="+a+"&responsable_codigo="+r+"&turno_codigo="+t+"&opcion="+o,"nombre","toolbar=0, menubar=0, status=1, location=0, hotkeys=0, scrollbars=1, resizable =1 width=800, height=480, center=yes")
        break;
    }  
	
}

function ver_detalle_mes(lista) {
    
    var f=window.parent.frames[0].document.frm.txtFecha.value;
    var a=window.parent.frames[0].document.frm.area_codigo.value;
    var r=window.parent.frames[0].document.frm.responsable_codigo.value;
    var t=window. parent.frames[0].document.frm.turno_codigo.value;
    var o= <?php echo $opcion ?>;
    
    var opcion = "1.4";
    switch (o){ 
        case 1.1: 
            window.open("empleado_marcacion_mensual.php?lista_sel=" + lista + "&opcion=" + opcion + "&fecha="+f+"&area_codigo="+a+"&responsable_codigo="+r+"&turno_codigo="+t,"nombre","toolbar=0, menubar=0, status=1, location=0, hotkeys=0, scrollbars=1, resizable =1 width=1000, height=480, center=yes")
        break;
    }
	
}

function exportar(){
    //alert(document.frm.area_codigo.value);return;
    var area = document.frm.area_codigo.value;
    var opcion = document.frm.opcion.value;
    var controlar = document.frm.hdd_controlar.value;
    
    var usuario = '<?php echo $_SESSION["empleado_codigo"]?>';
    location.href='../gestionturnos/exportar_empleados_modalidad.php?usuario=' + usuario+'&area='+area+'&opcion='+opcion+'&controlar='+controlar+'&bandeja=1';
    //window.open('../gestionturnos/exportar_empleados_modalidad.php?usuario=' + usuario+'&area='+area+'&opcion='+opcion+'&bandeja=1');
}

</script>

<html>
<HEAD>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
    <script language="JavaScript" src="../../default.js"></script>
    <?php
        if ($opcion == '1.6' || $opcion == '1.7' || $opcion == '1.8'){//VACACIONES o MOVIMIENTO CAMBIO MODALIDAD
    ?>
        <?php require_once('../includes/librerias_easyui.php');?>
    <?php
        }
    ?>
</head>

<body class="PageBODY" >
<div id="popCalF" style="BORDER-BOTTOM: 2px ridge; Z-INDEX:200; BORDER-LEFT: 2px ridge; BORDER-RIGHT: 2px ridge; BORDER-TOP: 2px ridge; POSITION: absolute; VISIBILITY: hidden; WIDTH: 10px">
	<iframe name="popFrameF" id="popFrameF" src="../popcj.htm" frameborder="1" scrolling="no" width="183" height="188"></iframe>
</div>

<form id="frm" name="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<center class="FormHeaderFont"><?php echo $titulo ?></center></b>
<p>
 
<?php

if ($opcion == '3.2' || $opcion == '1.4' || $opcion == '1.5'){
    if (isset($_POST["cboanio_codigo"])) $anio_codigo = $_POST["cboanio_codigo"];
    if (isset($_POST["cbomes_codigo"])) $mes_codigo = $_POST["cbomes_codigo"];
    
?>  
  <table border="0" cellspacing="1" cellpadding="2" align="center" >
  <tr>		
	<td width="100px" align="left" class="ColumnTD">Año :&nbsp;
       <?php
                $combo = new MyCombo();
                $combo->setMyUrl(db_host());
                $combo->setMyUser(db_user());
                $combo->setMyPwd(db_pass());
                $combo->setMyDBName(db_name());

                $sql4="select anio_codigo,anio_descripcion from anio WITH (nolock) ";
                $combo->query = $sql4;
                $combo->name = "cboanio_codigo";
                $combo->value = $anio_codigo."";
                $combo->more = "class=select";
                $rpta = $combo->Construir();
                echo $rpta;
	
      ?>
	</td>     
  	<td width="100px" align="left" class="ColumnTD">
       <?php
                echo "Mes :&nbsp;";
                //query del combo de seleccion de semana x año
                $sql4="SELECT mes_codigo, mes_descripcion FROM meses WITH (nolock) ";
                $sql4 .=" ORDER BY 1";
                $combo->query = $sql4;
                $combo->name = "cbomes_codigo";
                $combo->value = $mes_codigo."";
                $combo->more = "class=select onchange=cambiar_mes()";
                $rpta = $combo->Construir();
                echo $rpta;
       ?>
	</td>
  	<td align="left" class="ColumnTD">
	  	<img src="../images/j_salida.png" style="cursor:hand" onclick="return cambiar_mes()" title='Actualizar'/>
        </td>
  </tr>
  </table>
	<img src="../images/excel_ico.GIF" style="cursor:hand" onclick="return exportarExcel('idTabla')" title='Exportar Excel'/>
<?php 
}

if ($opcion != '1.6' && $opcion != '1.7' && $opcion != '1.8'){
    
    $gm->opcion=$opcion;
    $gm->fecha=$fecha;
    $gm->area_codigo=$area_codigo;
    $gm->responsable_codigo=$responsable_codigo;
    $gm->turno_codigo=$turno_codigo;
    $gm->anio_codigo=$anio_codigo;
    $gm->mes_codigo=$mes_codigo;
    $rpta=$gm->Consultar();
    
}

if ($opcion == '1.6' || $opcion == '1.7' ||  $opcion == '1.8'){//VACACIONES o MOVIMIENTO CAMBIO MODALIDAD
    
    $body = '';
    $npag = 1;
    $buscam = '';
    
    if ($opcion == '1.6'){//vacaciones
        //$orden = 'es.estado_codigo desc,vs.vac_sol_codigo';
        $orden = 'Area,Empleado,vs.fecha_inicio';
        $torder = 'DESC';
    }
    if ($opcion == '1.7'){
        $orden = "Empleado";
        $torder="ASC";
    }
    
    if ($opcion == '1.8'){
        $orden = "Area,Empleado,em.Emp_Mov_Fecha_Inicio";
        $torder="ASC";
    }
    
    if(isset($_GET['pagina'])) $npag = $_GET['pagina'];
    elseif(isset($_POST['pagina'])) $npag = $_POST['pagina'];
    if(isset($_GET['orden'])) $orden = $_GET['orden'];
    elseif(isset($_POST['orden'])) $orden = $_POST['orden'];
    if(isset($_GET['buscam'])) $buscam = $_GET['buscam'];
    elseif(isset($_POST['buscam'])) $buscam = $_POST['buscam'];
    if(isset($_GET['cboTOrden'])) $torder = $_GET['cboTOrden'];
    elseif(isset($_POST['cboTOrden'])) $torder = $_POST['cboTOrden'];
    
}

if ($opcion == '1.6'){//VACACIONES
?>
    <table class='FormTable' border="0" cellpadding="1" cellspacing="1" width='100%' id='tblOpciones'>
    <tr>
        <td>
            <a href="javascript:exportar('1');">
                        <img src="../images/excel_ico.GIF" name="Img_Rep_Vacaciones" id="Img_Rep_Vacaciones" width="16" height="15" border="0" alt="Genera Reporte" style="cursor:pointer;"  />
            </a>
        </td>
    </tr>
    </table>
<?php
    
    $objr = new MyGrilla();
    // Parametros de la clase
    $objr->setDriver_Coneccion(db_name());
    $objr->setUrl_Coneccion(db_host());
    $objr->setUser(db_user());
    $objr->setPwd(db_pass());

    $objr->setOrder($orden);
    $objr->setFindm($buscam);
    $objr->setFont('');
    $objr->setFormatoBto("class=boton");
    $objr->setFormaTabla(FormaTabla());
    $objr->setFormaCabecera(FormaCabecera());
    $objr->setFormaTCabecera(FormaTCabecera());
    $objr->setFormaItems(FormaItems());

    $objr->setTOrder($torder);

    $from = " vacaciones_solicitud vs inner join vdatos em on vs.empleado_codigo=em.empleado_codigo ";
    $from.= " inner join estados es on vs.estado_codigo=es.estado_codigo ";
    $from.= " inner join areas a on em.area_codigo=a.area_codigo ";
    $from.= " left join vacaciones_campana vc on vs.vac_cam_codigo=vc.vac_cam_codigo ";
    if(intval($controlar)==1){
        
        $from.= " left join CA_Asignacion_Empleados cae on vs.Empleado_Codigo = cae.Empleado_Codigo ";
        $from.= " and cae.Asignacion_Activo = 1 ";
        
    }
    
    $objr->setFrom($from);
    
    $where= " vs.estado_codigo in (1,25) ";
    $where.= " and (vs.fecha_inicio>getdate()-1 or (getdate() between vs.fecha_inicio and vs.fecha_fin )) ";
    
    if(intval($controlar)==1){
    
        $where.= " and a.empleado_responsable= (select empleado_responsable from Areas where Area_Codigo = ".$area_codigo." ) ";

        $where.= " and 1= ";
           $where.= " case when not exists(select empleado_responsable ";
              $where.= " from Areas ";
              $where.= " where Area_Codigo = ".$area_codigo." ";
                 $where.= " and empleado_responsable=".$empleado_id.") ";
           $where.= " then ";
              $where.= " case when cae.Responsable_Codigo=".$empleado_id." ";
              $where.= " then 1 ";
              $where.= " else 0 ";
              $where.= " end ";
           $where.= " else 1 ";
           $where.= " end ";
    }
    
       
    $objr->setWhere($where);
    $objr->setSize(10);

    $objr->setUrl($_SERVER['PHP_SELF']);
    $objr->setPage($npag);
    $objr->setMultipleSeleccion(false);
    $objr->setNoSeleccionable(true);

    $Alias[0]  = "root";
    $Alias[1]  = "Empleado";
    $Alias[2]  = "Area";
    $Alias[3]  = "Desde";
    $Alias[4]  = "Hasta";
    $Alias[5]  = "Dias";
    $Alias[6]  = "Adelanto";
    $Alias[7]  = "Observaciones";
    $Alias[8]  = "Estado";
    $Alias[9]  = "Img";

    $objr->setAlias($Alias);

    $Campos[0] = "cast(vs.vac_sol_codigo as varchar)+'_'+em.empleado+'_'+convert(varchar(10),vs.fecha_inicio,103)+'_'+convert(varchar(10),vs.fecha_fin,103)+'_'+em.empleado_email + '_' + cast(vs.empleado_codigo as varchar) + '_' + cast(vs.vac_tipo_aplicar as varchar) + '_' + cast(isnull(vs.vac_cam_codigo,0) as varchar)+'_'+cast(vs.estado_codigo as varchar) ";
    $Campos[1] = "em.empleado";
    $Campos[2] = "a.area_descripcion";
    $Campos[3] = "convert(varchar(10),vs.fecha_inicio,103)";
    $Campos[4] = "convert(varchar(10),vs.fecha_fin,103)";
    $Campos[5] = "vs.numero_dias";
    $Campos[6] = "case when vs.aplica_adelanto='S' then 'Si' else 'No' end";
    $Campos[7] = "vs.observaciones";
    $Campos[8] = "es.Estado_Descripcion";
    $Campos[9] = " case when vs.estado_codigo = 25 then '<img src=''..\/images\/stock_smiley-8.png'' border=0 title=Por_Aprobar>' "
   ."else
      case when vs.estado_codigo = 1 and getdate() between vs.fecha_inicio and vs.fecha_fin
      then '<img src=''..\/images\/stock_smiley-15.png'' border=0 title=Aprobado>'
      else '<img src=''..\/images\/ksmiletris.png'' border=0 title=Aprobado>' end
    end ";
    
    $objr->setCampos($Campos);
    $body = $objr->Construir();
    $objr = null;
    echo $body;
    
}else if ($opcion == '1.7'){//MOVIMIENTO CAMBIO MODALIDAD
?>
    <table class='FormTable' border="0" cellpadding="1" cellspacing="1" width='100%' id='tblOpciones'>
    <tr>
        <td>
            <a href="javascript:exportar();">
                <img src="../images/excel_ico.GIF" name="Img_Rep_Movimiento" id="Img_Rep_Movimiento" width="16" height="15" border="0" alt="Genera Reporte" style="cursor:pointer;"  />
            </a>
        </td>
    </tr>
    </table>
<?php

    $objr = new MyGrilla();
    $objr->setDriver_Coneccion(db_name());
    $objr->setUrl_Coneccion(db_host());
    $objr->setUser(db_user());
    $objr->setPwd(db_pass());
    $objr->setOrder($orden);
    $objr->setFindm($buscam);
    $objr->setNoSeleccionable(true);
    $objr->setFont("color=#000000");
    $objr->setFormatoBto("class=button");
    $objr->setFormaTabla(" class=FormTABLE style='width:100%' cellspacing='1' cellpadding='0' border='0' ");
    $objr->setFormaCabecera(" class=ColumnTD ");
    $objr->setFormaItems(" class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'");
    $objr->setTOrder($torder);
	
    $from  = " Empleado_Movimiento em
            inner join Movimiento_cambios mc on mc.Emp_Mov_codigo = em.Emp_Mov_codigo 
            inner join empleados e on em.Empleado_Codigo = e.empleado_codigo and e.Estado_Codigo = 1
            inner join items modalidad on modalidad.Item_Codigo = mc.cc_modalidad_codigo
            inner join Empleado_Area ea on e.Empleado_Codigo = ea.Empleado_Codigo and ea.Empleado_Area_Activo = 1
            inner join areas a on a.Area_Codigo = ea.Area_Codigo 
            inner join items horario on horario.item_codigo = mc.cc_horario_codigo
            inner join estados  on em.Estado_codigo = estados.Estado_Codigo ";
    if(intval($controlar)==1){
        
        $from  .= " left join CA_Asignacion_Empleados cae on em.Empleado_Codigo = cae.Empleado_Codigo 
            and cae.Asignacion_Activo = 1 ";
        
    }
    
    
    $objr->setFrom($from);
	
    $where = " em.Movimiento_codigo = 28 and em.Estado_codigo in (1,6) ";
    if(intval($controlar)==1){
        
        $where .= "and a.empleado_responsable = (select empleado_responsable from Areas where Area_Codigo = ".$area_codigo." ) ";
        $where .= "and 1= ";
           $where .= "case when not exists(select empleado_responsable ";
              $where .= "from Areas  where Area_Codigo = ".$area_codigo." ";
              $where .= "and empleado_responsable=".$empleado_id.") ";
           $where .= "then ";
              $where .= "case when cae.Responsable_Codigo=".$empleado_id." ";
              $where .= "then 1 ";
              $where .= "else 0 ";
              $where .= "end ";
           $where .= "else 1 ";
           $where .= "end ";
       
    }
    
    $where .= " and em.Emp_Mov_Fecha_Inicio > getdate() ";
    
    $objr->setWhere($where);
    $objr->setSize(15);
    $objr->setUrl($_SERVER["PHP_SELF"]);
    $objr->setPage($npag);
    
    $objr->setMultipleSeleccion(false);
    $objr->setNoSeleccionable(true);
    
    $arrAlias[0] = "root";
    $arrAlias[1] = "DNI";
    $arrAlias[2] = "Empleado";
    $arrAlias[3] = "area";
    $arrAlias[4] = "fecha_inicio";
    $arrAlias[5] = "modalidad";
    $arrAlias[6] = "horario";
    $arrAlias[7] = "estado";
    $arrAlias[8] = "Img";
    
    $arrCampos[0] = "rtrim(ltrim(str(e.Empleado_Codigo))) + '-' + rtrim(ltrim(str(mc.Emp_Mov_codigo)))";
    $arrCampos[1] = "e.Empleado_Dni"; 
    $arrCampos[2] =	"e.Empleado_Apellido_Paterno + ' ' + e.Empleado_Apellido_Materno + ' ' + e.Empleado_Nombres";
    $arrCampos[3] =	"a.Area_Descripcion";
    $arrCampos[4] = "CONVERT(varchar(10), em.emp_mov_fecha_inicio,103)";
    $arrCampos[5] = "modalidad.Item_Descripcion";
    $arrCampos[6] = "horario.Item_Descripcion";
    $arrCampos[7] = "estados.Estado_descripcion";
    
    $arrCampos[8] = " case em.estado_codigo "
   ." when 6 then '<img src=''..\/images\/stock_smiley-8.png'' border=0 title=Aprobado> '
   when 1 then '<img src=''..\/images\/ksmiletris.png'' border=0 title=Aprobado>' end ";
    
    $objr->setAlias($arrAlias);
    $objr->setCampos($arrCampos);
    $body = $objr->Construir();
    $objr = null;
    echo $body;
    
}else if ($opcion == '1.8'){//AUSENTISMO PROGRAMADO
    
?>
    <table class='FormTable' border="0" cellpadding="1" cellspacing="1" width='100%' id='tblOpciones'>
    <tr>
        <td>
            <a href="javascript:exportar();">
                <img src="../images/excel_ico.GIF" name="Img_Rep_Movimientos" id="Img_Rep_Movimientos" width="16" height="15" border="0" alt="Genera Reporte" style="cursor:pointer;"  />
            </a>
        </td>
    </tr>
    </table>
<?php
    
    $objr = new MyGrilla();
    $objr->setDriver_Coneccion(db_name());
    $objr->setUrl_Coneccion(db_host());
    $objr->setUser(db_user());
    $objr->setPwd(db_pass());
    $objr->setOrder($orden);
    $objr->setFindm($buscam);
    $objr->setNoSeleccionable(true);
    $objr->setFont("color=#000000");
    $objr->setFormatoBto("class=button");
    $objr->setFormaTabla(" class=FormTABLE style='width:100%' cellspacing='1' cellpadding='0' border='0' ");
    $objr->setFormaCabecera(" class=ColumnTD ");
    $objr->setFormaItems(" class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'");
    $objr->setTOrder($torder);

    $from = " Empleado_Movimiento em ";
    $from.= " inner join empleados on em.Empleado_Codigo = empleados.empleado_codigo and  empleados.Estado_Codigo = 1 ";
    $from.= " inner join movimiento m on em.Movimiento_codigo = m.movimiento_codigo ";
    $from.= " inner join Estados e on em.Estado_codigo = e.Estado_codigo ";
    $from.= " inner join Empleado_Area ea on em.Empleado_Codigo = ea.Empleado_Codigo and ea.Empleado_Area_Activo = 1 ";
    $from.= " inner join areas a on a.Area_Codigo = ea.Area_Codigo ";
    
    if(intval($controlar)==1){
        
        $from.= " left join CA_Asignacion_Empleados cae on em.Empleado_Codigo = cae.Empleado_Codigo ";
        $from.= " and cae.Asignacion_Activo = 1 ";
        
    }
    
    $objr->setFrom($from);
	
    $where = " em.Estado_codigo in (1,2) ";
    $where.= " and (em.Emp_Mov_Fecha_Inicio>getdate()-1 or (getdate() between em.Emp_Mov_Fecha_Inicio and em.Emp_Mov_Fecha_Fin )) ";
    
    if(intval($controlar)==1){
        
        $where .= "and a.empleado_responsable = (select empleado_responsable from Areas where Area_Codigo = ".$area_codigo." ) ";
        $where .= "and 1= ";
           $where .= "case when not exists(select empleado_responsable ";
              $where .= "from Areas  where Area_Codigo = ".$area_codigo." ";
              $where .= "and empleado_responsable=".$empleado_id.") ";
           $where .= "then ";
              $where .= "case when cae.Responsable_Codigo=".$empleado_id." ";
              $where .= "then 1 ";
              $where .= "else 0 ";
              $where .= "end ";
           $where .= "else 1 ";
           $where .= "end ";
    }
    
    $where .= " and em.movimiento_codigo in (5,6,8,9,14,36) ";
    
    $objr->setWhere($where);
    $objr->setSize(15);
    $objr->setUrl($_SERVER["PHP_SELF"]);
    $objr->setPage($npag);
    
    $objr->setMultipleSeleccion(false);
    $objr->setNoSeleccionable(true);
    
    $arrAlias[0] = "root";
    $arrAlias[1] = "DNI";
    $arrAlias[2] = "Empleado";
    $arrAlias[3] = "Area";
    $arrAlias[4] = "Movimiento_Descripcion";
    $arrAlias[5] = "Desde";
    $arrAlias[6] = "Hasta";
    $arrAlias[7] = "Estado";
    $arrAlias[8] = "Img";
    //--$arrAlias[9] = "x";
    
    $arrCampos[0] = "em.Empleado_Codigo";
    $arrCampos[1] = "dbo.UDF_DNI(em.Empleado_Codigo)";
    $arrCampos[2] = "dbo.UDF_EMPLEADO_NOMBRE(em.Empleado_Codigo)";
    $arrCampos[3] = "a.Area_Descripcion";
    $arrCampos[4] = "m.Movimiento_Descripcion";
    $arrCampos[5] = "convert(varchar,em.Emp_Mov_Fecha_Inicio,103)";
    $arrCampos[6] = "convert(varchar,em.Emp_Mov_Fecha_Fin,103)";
    $arrCampos[7] = "e.Estado_descripcion";
    $arrCampos[8] = "case when getdate() between em.Emp_Mov_Fecha_Inicio and em.Emp_Mov_Fecha_Fin
      then '<img src=''..\/images\/ksmiletris.png'' border=0 title=Aprobado>' 
      else '<img src=''..\/images\/stock_smiley-8.png'' border=0 title=Aprobado>' end";
    //--$arrCampos[9] = "empleados.estado_codigo";
    
    $objr->setAlias($arrAlias);
    $objr->setCampos($arrCampos);
    $body = $objr->Construir();
    $objr = null;
    echo $body;
    
}

?>
        
<input type="hidden" id="opcion" name="opcion" value="<?php echo $opcion ?>" />
<input type="hidden" id="hdd_controlar" name="hdd_controlar" value="<?php echo $controlar;?>" />
<input type="hidden" id="fecha" name="fecha" value="<?php echo $fecha ?>" />
<input type="hidden" id="area_codigo" name="area_codigo" value="<?php echo $area_codigo ?>" />
<input type="hidden" id="responsable_codigo" name="responsable_codigo" value="<?php echo $responsable_codigo ?>" />
<input type="hidden" id="turno_codigo" name="turno_codigo" value="<?php echo $turno_codigo ?>" />
<input type="hidden" id="anio_codigo" name="anio_codigo" value="<?php echo $anio_codigo ?>" />
<input type="hidden" id="mes_codigo" name="mes_codigo" value="<?php echo $mes_codigo ?>" />
</form>
<iframe id='ift' name='ift' style='width:600px;display:none;'></iframe>
</body>
</noframes>
</html>