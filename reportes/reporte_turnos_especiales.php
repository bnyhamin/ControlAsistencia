<?php
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/clsReportes.php");

$ref= new reportes();
$ref->MyDBName= db_name();
$ref->MyUrl = db_host();
$ref->MyUser= db_user();
$ref->MyPwd = db_pass();
$mes = $_GET["mes"];
$anio = $_GET["anio"];
$titulo_mes = "";
$rs = $ref->obtenerReporteRequerimiento($mes, $anio);
?>
<html>
<body onLoad="return WindowResize(10,20,'center')">
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    <title>Reporte de Detalle Requerimiento Por Periodo Registrado</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <script language="JavaScript" src="../default.js"></script>
    <link href="../css/tstyle.css" rel="stylesheet" type="text/css"/>
</head>
    <?php
    
        switch($mes){
            case 1:
                $titulo_mes = "Enero";
                break;
            case 2:
                $titulo_mes = "Febrero";
                break;
            case 3:
                $titulo_mes = "Marzo";
                break;
            case 4:
                $titulo_mes = "Abril";
                break;
            case 5:
                $titulo_mes = "Mayo";
                break;
            case 6:
                $titulo_mes = "Junio";
                break;
            case 7:
                $titulo_mes = "Julio";
                break;
            case 8:
                $titulo_mes = "Agosto";
                break;
            case 9:
                $titulo_mes = "Setiembre";
                break;
            case 10:
                $titulo_mes = "Octubre";
                break;
            case 11:
                $titulo_mes = "Noviembre";
                break;
            case 12:
                $titulo_mes = "Diciembre";
                break;    
            
        }
		//date_default_timezone_set('PER');

        $cadena = "<table border='0' width='200' cellPadding='0' cellSpacing='0'>";
        //$cadena .= "<tr><td>Usuario:</td>                <td >&nbsp;</td></tr>";
        $cadena .= "<tr><td>Fecha Generaci&oacute;n:</td> <td >".date('d/m/Y')."</td></tr>";
        //$cadena .= "<tr><td>Hora Generaci&oacute;n:</td>  <td >".date(DATE_RFC2822)."</td></tr>";
        $cadena .= "</table>";
        $total_registros = 0;
        if($rs):
            if($rs->RecordCount() > 0):
                $cadena .= "<center><table border='1' class='FormTable' id='listado' width='100%'  border='0' cellPadding='0' cellSpacing='0'>";
                $cadena .=  "<tr style='background-color:#D8DAB4'><td colspan='20'><strong>REPORTE DE DETALLE REQUIRIMIENTO POR PERIODO REGISTRADO - ".strtoupper($titulo_mes)."-".$anio." </strong></td></tr>";
                $cadena .=  "<tr style='background-color:#C5D8ED'>";
                
                $cadena .=    "<td class='ColumnTD'>CODIGO</td>";
                $cadena .=    "<td class='ColumnTD'>AREA</td>";
                $cadena .=    "<td class='ColumnTD'>SERVICIO</td>";
                $cadena .=    "<td class='ColumnTD'>CARGO</td>";
                $cadena .=    "<td class='ColumnTD'>FSOLICITUD</td>";
				//5
                $cadena .=    "<td class='ColumnTD'>FAPROBACION</td>";
                $cadena .=    "<td class='ColumnTD'>ESTADO</td>";
                $cadena .=    "<td class='ColumnTD'>SIGT. APROBADOR</td>";
                $cadena .=    "<td class='ColumnTD'>AREA APROBADOR</td>";
                $cadena .=    "<td class='ColumnTD'>VACANTES</td>";
                $cadena .=    "<td class='ColumnTD'>POSTULANTES</td>";
                $cadena .=    "<td class='ColumnTD'>ACEPTADOS</td>";
                //10
                $cadena .=    "<td class='ColumnTD'>PRE_ALTA</td>";
                
                
                $cadena .=    "<td class='ColumnTD'>MOTIVO</td>";
                $cadena .=    "<td class='ColumnTD'>SUSTENTO</td>";
                $cadena .=    "<td class='ColumnTD'>MODALIDAD</td>";
                $cadena .=    "<td class='ColumnTD'>HORARIO</td>";
                $cadena .=    "<td class='ColumnTD'>PERFIL</td>";
                $cadena .=    "<td class='ColumnTD'>MONTO</td>";
                $cadena .=    "<td class='ColumnTD'>COMISION</td>";
                $cadena .=    "<td class='ColumnTD'>ESPECIFICAR</td>";
                $cadena .=    "<td class='ColumnTD'>TIPO CONTRATO</td>";
                
				//$cadena .=    "<td class='ColumnTD'>PERFIL</td>";
								
                $cadena .=  "</tr>";
                while(!$rs->EOF){
                    $cadena .=  "<tr >";
                    $cadena .=  "   <td>".$rs->fields[0]."</td>";
                    $cadena .=  '   <td>'.$rs->fields[1].'&nbsp;</td>';
                    $cadena .=  "   <td>".$rs->fields[2]."</td>";
                    $cadena .=  "   <td>".$rs->fields[3]."</td>";
                    $cadena .=  '   <td>'.$rs->fields[4].'&nbsp;</td>';
                    $cadena .=  "   <td>".$rs->fields[5]."</td>";
                    $cadena .=  "   <td>".$rs->fields[6]."</td>";
                    $cadena .=  "   <td>".$rs->fields[20]."</td>";
                    $cadena .=  "   <td>".$rs->fields[21]."</td>";
                    
                    $cadena .=  '   <td>'.$rs->fields[7].'&nbsp;</td>';
                    $cadena .=  "   <td>".$rs->fields[8]."</td>";
                    $cadena .=  "   <td>".$rs->fields[9]."</td>";
                    $cadena .=  '   <td>'.$rs->fields[10].'&nbsp;</td>';
                    
                    $cadena .=  "   <td>".$rs->fields[11]."</td>";
					$cadena .=  "   <td>".htmlentities($rs->fields[12])."</td>";
                    $cadena .=  "   <td>".$rs->fields[13]."</td>";
                    $cadena .=  "   <td>".$rs->fields[14]."</td>";
                    $cadena .=  "   <td>".$rs->fields[15]."</td>";
                    $cadena .=  "   <td>".$rs->fields[16]."</td>";
                    $cadena .=  "   <td>".$rs->fields[17]."</td>";
                    $cadena .=  "   <td>".$rs->fields[18]."</td>";
                    $cadena .=  "   <td>".$rs->fields[19]."</td>";
                    					                    				
                    $cadena .= "</tr>";
                    $total_registros += 1;
                    $rs->MoveNext();
                }
                $cadena .=  "<tr><td colspan='11' align='right'> Total de Registros = ".$total_registros."&nbsp;&nbsp;</td></tr>";
            else:
                $cadena .= "<tr coslpan='11'><td>No se encontraron requerimientos en el periodo seleccionado!</td></tr>";
            endif;
        else:
            $cadena .= "<tr>";
            $cadena .= "    <td colspan='11'>Error en la Consulta</td>";
            $cadena .= "</tr>";
        endif;
    $cadena .= "</table><center>";
    //echo $cadena;
    ?>
    
    <?php
    $nombre_archivo = "reporte_turnos_especiales_".$mes."_".$anio.".xls";
    if (file_exists($nombre_archivo)){ 
        unlink($nombre_archivo);
    }
    $ar=fopen($nombre_archivo,"a") or die("Problemas en la creacion");
    fputs($ar,$cadena);
    fputs($ar,"\n");
	//$ar2=fopen("hoja22","a") or die("Problemas en la creacion");
	fputs($ar,$cadena);
    fclose($ar);
	//fclose($ar2);
    ?>
    
    <script type="text/javascript">
	var nombre = "<?php echo $nombre_archivo?>";
    window.open(nombre,18,"width=150px, height=100px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
    window.close();
	self.location.href = nombre;
    
    </script>
    
    
</body>
</html>