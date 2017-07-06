<?php header("Expires: 0"); ?>
<?php
//echo "Procesando! Presentado el excel cierre esta ventana";
set_time_limit(30000);
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../includes/clsCA_Turnos_Combinacion.php"); 
require_once("../includes/clsCA_Turnos_Empleado.php");
require_once("../../Includes/MyCombo.php");
$opcion="";
$tc_codigo_sap="";
$turno_dia1="0";
$turno_dia2="0";
$turno_dia3="0";
$turno_dia4="0";
$turno_dia5="0";
$turno_dia6="0";
$turno_dia7="0";
$te_semana="";
$te_anio="";
$fecha_desde="";
$fecha_hasta="";
$carga_codigo="";
$empleado_dni="";
$tc_codigo="";

$o = new ca_turnos_combinacion();
$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());

$te = new ca_turnos_empleado();
$te->setMyUrl(db_host());
$te->setMyUser(db_user());
$te->setMyPwd(db_pass());
$te->setMyDBName(db_name());

if (isset($_GET["opcion"])){
    if ($_GET["opcion"]=='existe_tc'){
        if (isset($_GET["tc_codigo_sap"])) $tc_codigo_sap = $_GET["tc_codigo_sap"];
        if (isset($_GET["turno_dia1"])) $turno_dia1 = $_GET["turno_dia1"];
        if (isset($_GET["turno_dia2"])) $turno_dia2 = $_GET["turno_dia2"];
        if (isset($_GET["turno_dia3"])) $turno_dia3 = $_GET["turno_dia3"];
        if (isset($_GET["turno_dia4"])) $turno_dia4 = $_GET["turno_dia4"];
        if (isset($_GET["turno_dia5"])) $turno_dia5 = $_GET["turno_dia5"];
        if (isset($_GET["turno_dia6"])) $turno_dia6 = $_GET["turno_dia6"];
        if (isset($_GET["turno_dia7"])) $turno_dia7 = $_GET["turno_dia7"];
        if (isset($_GET["tc_codigo"])) $tc_codigo = $_GET["tc_codigo"];
	
        $o->tc_codigo_sap = $tc_codigo_sap;
        $o->turno_dia1 = $turno_dia1;
        $o->turno_dia2 = $turno_dia2;
        $o->turno_dia3 = $turno_dia3;
        $o->turno_dia4 = $turno_dia4;
        $o->turno_dia5 = $turno_dia5;
        $o->turno_dia6 = $turno_dia6;
        $o->turno_dia7 = $turno_dia7;
        $o->tc_codigo  = $tc_codigo;
        $mensaje = $o->Query_Existe();
        
        if($mensaje=='NOT'){
?>
        <script language='javascript'>
            alert('El registro que esta intentando grabar ya existe Verifique!!');
        </script>
<?php
        }else{
?>
        <script language='javascript'>
            window.parent.aplicar();
        </script>
<?php
        }
    }
	
	if ($_GET["opcion"]=='suplex'){
                
		//$narchivo=date("YmdHis");
		echo "<img src='../images/procesando.gif' alt='Procesando! Presentado el excel cierre esta ventana' >";
		?>
		<script type="text/javascript">
			setTimeout("",2000);
		</script>
		<?php
		$narchivo='datoss';
		if (isset($_GET["te_semana"])) $te_semana = $_GET["te_semana"];
		if (isset($_GET["te_anio"])) $te_anio = $_GET["te_anio"];
		$te->te_semana = $te_semana;
		$te->te_anio = $te_anio;
		$cadena = $te->Suplex_Turnos();
		
		if (file_exists($narchivo.".xls")){
		    unlink($narchivo.".xls");
		}
		$ar=fopen($narchivo.".xls","a") or die("Problemas en la creacion");
		fputs($ar,$cadena);
		fputs($ar,"\n");
		fclose($ar); ?>
		<script type="text/javascript">
			//self.location.href = "<?php echo $narchivo ?>.xls";
			window.open("<?php echo $narchivo ?>.xls",18,"width=950px, height=600px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
		</script>
		<?php
		//time_nanosleep(1,1);
	    //unlink($narchivo.".xls");
	}

	if ($_GET["opcion"]=='suplex_fechas'){
		//$narchivo=date("YmdHis");
		echo "<img src='../images/procesando.gif' alt='Procesando! Presentado el excel cierre esta ventana' >";
		?>
		<script type="text/javascript">
			setTimeout("",2000);
		</script>
		<?php
		$narchivo='datoss';
		if (isset($_GET["fecha_desde"])) $fecha_desde = $_GET["fecha_desde"];
		if (isset($_GET["fecha_hasta"])) $fecha_hasta = $_GET["fecha_hasta"];
		$te->te_fecha_inicio = $fecha_desde;
		$te->te_fecha_fin = $fecha_hasta;
		//$cadena = $te->Suplex_Turnos();
		$cadena = $te->Suplex_Turnos_Fechas();
		
		if (file_exists($narchivo.".xls")){
		    unlink($narchivo.".xls");
		}
		$ar=fopen($narchivo.".xls","a") or die("Problemas en la creacion");
		fputs($ar,$cadena);
		fputs($ar,"\n");
		fclose($ar); ?>
		<script type="text/javascript">
			//self.location.href = "<?php echo $narchivo ?>.xls";
			window.open("<?php echo $narchivo ?>.xls",18,"width=950px, height=600px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
		</script>
		<?php
		//time_nanosleep(1,1);
	    //unlink($narchivo.".xls");
	}

	if ($_GET["opcion"]=='reporte01'){
                
		$narchivo=date("YmdHis");
		
		echo "<img src='../images/procesando.gif' alt='Procesando! Presentado el excel cierre esta ventana' >";
		?>
		<script type="text/javascript">
			setTimeout("",2000);
		</script>
		<?php
		if (isset($_GET["fecha_desde"])) $fecha_desde = $_GET["fecha_desde"];
		if (isset($_GET["fecha_hasta"])) $fecha_hasta = $_GET["fecha_hasta"];
		$te->te_fecha_inicio = $fecha_desde;
		$te->te_fecha_fin = $fecha_hasta;
		$cadena = $te->Reporte01();
		
		if (file_exists("../reportes_generados/".$narchivo.".xls")){ 
		    unlink("../reportes_generados/".$narchivo.".xls");
		}
		$ar=fopen("../reportes_generados/".$narchivo.".xls","a") or die("Problemas en la creacion");
		fputs($ar,$cadena);
		fputs($ar,"\n");
		fclose($ar); ?>
		<script type="text/javascript">
			window.open("../reportes_generados/<?php echo $narchivo ?>.xls",18,"width=950px, height=600px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
			//self.location.href = "../reportes_generados/<?php echo $narchivo ?>.xls";
		</script>
		<?php
	}

	if ($_GET["opcion"]=='reporte02'){
                
		$narchivo=date("YmdHis");
		
		echo "<img src='../images/procesando.gif' alt='Procesando! Presentado el excel cierre esta ventana' >";
		?>
		<script type="text/javascript">
			setTimeout("",2000);
		</script>
		<?php
		if (isset($_GET["fecha_desde"])) $fecha_desde = $_GET["fecha_desde"];
		if (isset($_GET["fecha_hasta"])) $fecha_hasta = $_GET["fecha_hasta"];
		$te->te_fecha_inicio = $fecha_desde;
		$te->te_fecha_fin = $fecha_hasta;
		$cadena = $te->Reporte02();
		
		if (file_exists("../reportes_generados/".$narchivo.".xls")){ 
		    unlink("../reportes_generados/".$narchivo.".xls");
		}
		$ar=fopen("../reportes_generados/".$narchivo.".xls","a") or die("Problemas en la creacion");
		fputs($ar,$cadena);
		fputs($ar,"\n");
		fclose($ar); ?>
		<script type="text/javascript">
			window.open("../reportes_generados/<?php echo $narchivo ?>.xls",18,"width=950px, height=600px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
			//self.location.href = "../reportes_generados/<?php echo $narchivo ?>.xls";
		</script>
		<?php
	}

	if ($_GET["opcion"]=='reporte03'){
                
		$narchivo=date("YmdHis");
		
		echo "<img src='../images/procesando.gif' alt='Procesando! Presentado el excel cierre esta ventana' >";
		?>
		<script type="text/javascript">
			setTimeout("",2000);
		</script>
		<?php
		if (isset($_GET["te_semana"])) $te_semana = $_GET["te_semana"];
		if (isset($_GET["te_anio"])) $te_anio = $_GET["te_anio"];
		if (isset($_GET["fecha_desde"])) $fecha_desde = $_GET["fecha_desde"];
		if (isset($_GET["fecha_hasta"])) $fecha_hasta = $_GET["fecha_hasta"];
		$te->te_semana = $te_semana;
		$te->te_anio = $te_anio;
		$te->te_fecha_inicio = $fecha_desde;
		$te->te_fecha_fin = $fecha_hasta;
		$cadena = $te->Reporte03();
		
		if (file_exists("../reportes_generados/".$narchivo.".xls")){ 
		    unlink("../reportes_generados/".$narchivo.".xls");
		}
		$ar=fopen("../reportes_generados/".$narchivo.".xls","a") or die("Problemas en la creacion");
		fputs($ar,$cadena);
		fputs($ar,"\n");
		fclose($ar); ?>
		<script type="text/javascript">
			window.open("../reportes_generados/<?php echo $narchivo ?>.xls",18,"width=950px, height=600px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
			//self.location.href = "../reportes_generados/<?php echo $narchivo ?>.xls";
		</script>
		<?php
	}

	if ($_GET["opcion"]=='reporte04'){
                
		$narchivo=date("YmdHis");
		
		echo "<img src='../images/procesando.gif' alt='Procesando! Presentado el excel cierre esta ventana' >";
		?>
		<script type="text/javascript">
			setTimeout("",2000);
		</script>
		<?php
		if (isset($_GET["fecha_desde"])) $fecha_desde = $_GET["fecha_desde"];
		if (isset($_GET["fecha_hasta"])) $fecha_hasta = $_GET["fecha_hasta"];
		if (isset($_GET["empleado_dni"])) $empleado_dni = $_GET["empleado_dni"];
		$te->te_fecha_inicio = $fecha_desde;
		$te->te_fecha_fin = $fecha_hasta;
		$te->empleado_dni = $empleado_dni;
		$cadena = $te->Reporte04();
		
		if (file_exists("../reportes_generados/".$narchivo.".xls")){ 
		    unlink("../reportes_generados/".$narchivo.".xls");
		}
		$ar=fopen("../reportes_generados/".$narchivo.".xls","a") or die("Problemas en la creacion");
		fputs($ar,$cadena);
		fputs($ar,"\n");
		fclose($ar); ?>
		<script type="text/javascript">
			window.open("../reportes_generados/<?php echo $narchivo ?>.xls",18,"width=950px, height=600px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
			//self.location.href = "../reportes_generados/<?php echo $narchivo ?>.xls";
		</script>
		<?php
	}

	if ($_GET["opcion"]=='reporte05'){
                
		$narchivo=date("YmdHis");
		
		echo "<img src='../images/procesando.gif' alt='Procesando! Presentado el excel cierre esta ventana' >";
		?>
		<script type="text/javascript">
			setTimeout("",2000);
		</script>
		<?php
		if (isset($_GET["fecha_desde"])) $fecha_desde = $_GET["fecha_desde"];
		if (isset($_GET["fecha_hasta"])) $fecha_hasta = $_GET["fecha_hasta"];
		$te->te_fecha_inicio = $fecha_desde;
		$te->te_fecha_fin = $fecha_hasta;
		$cadena = $te->Reporte05();
		
		if (file_exists("../reportes_generados/".$narchivo.".xls")){ 
		    unlink("../reportes_generados/".$narchivo.".xls");
		}
		$ar=fopen("../reportes_generados/".$narchivo.".xls","a") or die("Problemas en la creacion");
		fputs($ar,$cadena);
		fputs($ar,"\n");
		fclose($ar); ?>
		<script type="text/javascript">
			window.open("../reportes_generados/<?php echo $narchivo ?>.xls",18,"width=950px, height=600px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
			//self.location.href = "../reportes_generados/<?php echo $narchivo ?>.xls";
		</script>
		<?php
	}

	if ($_GET["opcion"]=='reporte06'){
                
		$narchivo=date("YmdHis");
		
		echo "<img src='../images/procesando.gif' alt='Procesando! Presentado el excel cierre esta ventana' >";
		?>
		<script type="text/javascript">
			setTimeout("",2000);
		</script>
		<?php
		if (isset($_GET["fecha_desde"])) $fecha_desde = $_GET["fecha_desde"];
		$te->te_fecha_inicio = $fecha_desde;
		$cadena = $te->Reporte06();
		
		if (file_exists("../reportes_generados/".$narchivo.".xls")){ 
		    unlink("../reportes_generados/".$narchivo.".xls");
		}
		$ar=fopen("../reportes_generados/".$narchivo.".xls","a") or die("Problemas en la creacion");
		fputs($ar,$cadena);
		fputs($ar,"\n");
		fclose($ar); ?>
		<script type="text/javascript">
			window.open("../reportes_generados/<?php echo $narchivo ?>.xls",18,"width=950px, height=600px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
		</script>
		<?php
	}
	
	if ($_GET["opcion"]=='reporte07'){
                
		$narchivo=date("YmdHis");
		
		echo "<img src='../images/procesando.gif' alt='Procesando! Presentado el excel cierre esta ventana' >";
		?>
		<script type="text/javascript">
			setTimeout("",2000);
		</script>
		<?php
		if (isset($_GET["fecha_desde"])) $fecha_desde = $_GET["fecha_desde"];
		if (isset($_GET["fecha_hasta"])) $fecha_hasta = $_GET["fecha_hasta"];
		$ini=explode('/',$fecha_desde);
		$fin=explode('/',$fecha_hasta);
		$narchivo="LOGGER_".$ini[2].$ini[1].$ini[0]."_".$fin[2].$fin[1].$fin[0];
		$te->te_fecha_inicio = $fecha_desde;
		$te->te_fecha_fin = $fecha_hasta;
		$cadena = $te->Reporte07();
		
		if (file_exists("../reportes_generados/".$narchivo.".txt")){ 
		    unlink("../reportes_generados/".$narchivo.".txt");
		}
		$ar=fopen("../reportes_generados/".$narchivo.".txt","a") or die("Problemas en la creacion");
		fputs($ar,$cadena);
		fputs($ar,"\n");
		fclose($ar); ?>
		<script type="text/javascript">
			window.open("../reportes_generados/<?php echo $narchivo ?>.txt",18,"width=950px, height=600px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
		</script>
		<?php
	}
	
	if ($_GET["opcion"]=='reporte08'){
                
		$narchivo=date("YmdHis");
		
		echo "<img src='../images/procesando.gif' alt='Procesando! Presentado el excel cierre esta ventana' >";
		?>
		<script type="text/javascript">
			setTimeout("",2000);
		</script>
		<?php
		if (isset($_GET["fecha_desde"])) $fecha_desde = $_GET["fecha_desde"];
		if (isset($_GET["fecha_hasta"])) $fecha_hasta = $_GET["fecha_hasta"];
		$ini=explode('/',$fecha_desde);
		$fin=explode('/',$fecha_hasta);
		$narchivo="INCIDENCIA_".$ini[2].$ini[1].$ini[0]."_".$fin[2].$fin[1].$fin[0];
		$te->te_fecha_inicio = $fecha_desde;
		$te->te_fecha_fin = $fecha_hasta;
		$cadena = $te->Reporte08();
		
		if (file_exists("../reportes_generados/".$narchivo.".txt")){ 
		    unlink("../reportes_generados/".$narchivo.".txt");
		}
		$ar=fopen("../reportes_generados/".$narchivo.".txt","a") or die("Problemas en la creacion");
		fputs($ar,$cadena);
		fputs($ar,"\n");
		fclose($ar); ?>
		<script type="text/javascript">
			window.open("../reportes_generados/<?php echo $narchivo ?>.txt",18,"width=950px, height=600px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
		</script>
		<?php
	}
	
	if ($_GET["opcion"]=='reporte32'){
                
		$narchivo=date("YmdHis");
		
		echo "<img src='../images/procesando.gif' alt='Procesando! Presentado el excel cierre esta ventana' >";
		?>
		<script type="text/javascript">
			setTimeout("",2000);
		</script>
		<?php
		if (isset($_GET["fecha_desde"])) $fecha_desde = $_GET["fecha_desde"];
		if (isset($_GET["fecha_hasta"])) $fecha_hasta = $_GET["fecha_hasta"];
		//if (isset($_GET["empleado_dni"])) $empleado_dni = $_GET["empleado_dni"];
		$te->te_fecha_inicio = $fecha_desde;
		$te->te_fecha_fin = $fecha_hasta;
		//$te->empleado_dni = $empleado_dni;
		$cadena = $te->Reporte32();
		
		if (file_exists("../reportes_generados/".$narchivo.".xls")){ 
		    unlink("../reportes_generados/".$narchivo.".xls");
		}
		$ar=fopen("../reportes_generados/".$narchivo.".xls","a") or die("Problemas en la creacion");
		fputs($ar,$cadena);
		fputs($ar,"\n");
		fclose($ar); ?>
		<script type="text/javascript">
			window.open("../reportes_generados/<?php echo $narchivo ?>.xls",18,"width=950px, height=600px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
			//self.location.href = "../reportes_generados/<?php echo $narchivo ?>.xls";
		</script>
		<?php
	}
	
		if ($_GET["opcion"]=='reporte33'){
                    
		$narchivo=date("YmdHis");
		
		echo "<img src='../images/procesando.gif' alt='Procesando! Presentado el excel cierre esta ventana' >";
		?>
		<script type="text/javascript">
			setTimeout("",2000);
		</script>
		<?php
		if (isset($_GET["fecha_desde"])) $fecha_desde = $_GET["fecha_desde"];
		if (isset($_GET["fecha_hasta"])) $fecha_hasta = $_GET["fecha_hasta"];
		//if (isset($_GET["empleado_dni"])) $empleado_dni = $_GET["empleado_dni"];
		$te->te_fecha_inicio = $fecha_desde;
		$te->te_fecha_fin = $fecha_hasta;
		//$te->empleado_dni = $empleado_dni;
		$cadena = $te->Reporte33();
		
		if (file_exists("../reportes_generados/".$narchivo.".xls")){ 
		    unlink("../reportes_generados/".$narchivo.".xls");
		}
		$ar=fopen("../reportes_generados/".$narchivo.".xls","a") or die("Problemas en la creacion");
		fputs($ar,$cadena);
		fputs($ar,"\n");
		fclose($ar); ?>
		<script type="text/javascript">
			window.open("../reportes_generados/<?php echo $narchivo ?>.xls",18,"width=950px, height=600px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
			//self.location.href = "../reportes_generados/<?php echo $narchivo ?>.xls";
		</script>
		<?php
	}

	if ($_GET["opcion"]=='reporte37'){
                
		$narchivo=date("YmdHis");
		
		echo "<img src='../images/procesando.gif' alt='Procesando! Presentado el excel cierre esta ventana' >";
		if (isset($_GET["te_anio"])) $te_anio = $_GET["te_anio"];
		if (isset($_GET["empleado_dni"])) $empleado_dni = $_GET["empleado_dni"];
		$te->te_anio = $te_anio;
		$te->empleado_dni = $empleado_dni;
		$cadena = $te->Reporte37();
		
		if (file_exists("../reportes_generados/".$narchivo.".xls")){ 
		    unlink("../reportes_generados/".$narchivo.".xls");
		}
		$ar=fopen("../reportes_generados/".$narchivo.".xls","a") or die("Problemas en la creacion");
		fputs($ar,$cadena);
		fputs($ar,"\n");
		fclose($ar); ?>
		<script type="text/javascript">
			window.open("../reportes_generados/<?php echo $narchivo ?>.xls",18,"width=950px, height=600px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
		</script>
		<?php
	}
	
	if ($_GET["opcion"]=='log01'){
                
		$narchivo=date("YmdHis");
		
		echo "<img src='../images/procesando.gif' alt='Procesando! Presentado el excel cierre esta ventana' >";
		?>
		<script type="text/javascript">
			setTimeout("",2000);
		</script>
		<?php
		if (isset($_GET["carga_codigo"])) $carga_codigo = $_GET["carga_codigo"];
		$te->carga_codigo = $carga_codigo;
		$cadena = $te->Log01();
		//echo $cadena;
		if (file_exists("../reportes_generados/".$narchivo.".xls")){ 
		    unlink("../reportes_generados/".$narchivo.".xls");
		}
		$ar=fopen("../reportes_generados/".$narchivo.".xls","a") or die("Problemas en la creacion");
		fputs($ar,$cadena);
		fputs($ar,"\n");
		fclose($ar); ?>
		<script type="text/javascript">
			window.open("../reportes_generados/<?php echo $narchivo ?>.xls",18,"width=950px, height=600px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
		</script>
		<?php
	}

	if ($_GET["opcion"]=='publicar'){
                
		if (isset($_GET["te_semana"])) $te_semana = $_GET["te_semana"];
		if (isset($_GET["te_anio"])) $te_anio = $_GET["te_anio"];
		$te->te_semana = $te_semana;
		$te->te_anio = $te_anio;
		$cadena = $te->Reporte_Turnos();
		
		if (file_exists("datos.xls")){ 
		    unlink("datos.xls");
		}
		$ar=fopen("datos.xls","a") or die("Problemas en la creacion");
		fputs($ar,$cadena);
		fputs($ar,"\n");
		fclose($ar); ?>
		<script type="text/javascript">
			self.location.href = "datos.xls";
		</script>
		<?php
	}

	if ($_GET["opcion"]=='exportar'){
                
		if (isset($_GET["te_semana"])) $te_semana = $_GET["te_semana"];
		if (isset($_GET["te_anio"])) $te_anio = $_GET["te_anio"];
		$te->te_semana = $te_semana;
		$te->te_anio = $te_anio;
		$cadena = $te->Reporte_Turnos();
		
		if (file_exists("datos.xls")){ 
		    unlink("datos.xls");
		}
		$ar=fopen("datos.xls","a") or die("Problemas en la creacion");
		fputs($ar,$cadena);
		fputs($ar,"\n");
		fclose($ar); ?>
		<script type="text/javascript">
			self.location.href = "datos.xls";
		</script>
		<?php
	}

	if ($_GET["opcion"]=='Ex_Com'){
                
		$narchivo=date("YmdHis");
		$cadena = $o->Reporte_Combinacion();
		
		if (file_exists("../reportes_generados/".$narchivo.".xls")){ 
		    unlink("../reportes_generados/".$narchivo.".xls");
		}
		$ar=fopen("../reportes_generados/".$narchivo.".xls","a") or die("Problemas en la creacion");
		fputs($ar,$cadena);
		fputs($ar,"\n");
		fclose($ar); ?>
		<script type="text/javascript">
			window.open("../reportes_generados/<?php echo $narchivo ?>.xls",18,"width=950px, height=600px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
		</script>
		<?php
	}
	
	if ($_GET["opcion"]=='Ex_Turnos'){
                
		if (isset($_GET["tc_codigo"])) $tc_codigo = $_GET["tc_codigo"];
		$narchivo=date("YmdHis");
		$cadena = $o->Reporte_Turnos_Detalle($tc_codigo);
		
		if (file_exists("../reportes_generados/".$narchivo.".xls")){ 
		    unlink("../reportes_generados/".$narchivo.".xls");
		}
		$ar=fopen("../reportes_generados/".$narchivo.".xls","a") or die("Problemas en la creacion");
		fputs($ar,$cadena);
		fputs($ar,"\n");
		fclose($ar); ?>
		<script type="text/javascript">
			window.open("../reportes_generados/<?php echo $narchivo ?>.xls",18,"width=950px, height=600px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
		</script>
		<?php
	}

}
