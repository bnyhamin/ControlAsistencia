<?php
	require_once("../Includes/Connection.php");
	require_once("../Includes/Constantes.php");
	require_once("../Includes/mantenimiento.php");
	require_once("includes/clsCA_Estadisticas.php");

	session_start();
	
	//$superiorEmpleados y $flag_tipo_usuario deben estar seteados en sesion
	$superiorEmpleados = $_SESSION['superiorEmpleados'];
	$flag_tipo_usuario = $_SESSION['flag_tipo_usuario'];

	$objE = new ca_estadisticas();
	$objE->setMyUrl(db_host());
	$objE->setMyUser(db_user());
	$objE->setMyPwd(db_pass());
	$objE->setMyDBName(db_name());
	
	$numero_dependientes = 0;
	$numero_dependientes_asistencias = 0;
	$numero_dependientes_inasistencias = 0;
	$numero_dependientes_tardanzas = 0;
	$numero_dependientes_vacaciones = 0;
	$numero_dependientes_licencias = 0;
	$numero_dependientes_sin_control = 0;
	
	$numero_dependientes = $objE->retornaEmpleadosDependientes($superiorEmpleados, 0, $flag_tipo_usuario);
	$numero_dependientes_asistencias = $objE->retornaEmpleadosDependientesAsistencias($superiorEmpleados, 0, $flag_tipo_usuario);
	$numero_dependientes_inasistencias = $objE->retornaEmpleadosDependientesInasistencias($superiorEmpleados, 0, $flag_tipo_usuario);
	$numero_dependientes_tardanzas = $objE->retornaEmpleadosDependientesTardanzas($superiorEmpleados, 0, $flag_tipo_usuario);
	$numero_dependientes_vacaciones = $objE->retornaEmpleadosDependientesVacaciones($superiorEmpleados, 0, $flag_tipo_usuario);
	$numero_dependientes_licencias = $objE->retornaEmpleadosDependientesLicencias($superiorEmpleados, 0, $flag_tipo_usuario);
	$numero_dependientes_sin_control = $objE->retornaEmpleadosNoSujetoControl($superiorEmpleados, 0, $flag_tipo_usuario);
	
	$porcentajeAsistencias = $numero_dependientes > 0 ?round(($numero_dependientes_asistencias/$numero_dependientes)*100, 2) : 0;
	$porcentajeInasistencias = $numero_dependientes > 0 ?round(($numero_dependientes_inasistencias/$numero_dependientes)*100, 2) : 0;
	$porcentajeVacaciones = $numero_dependientes > 0 ?round(($numero_dependientes_vacaciones/$numero_dependientes)*100, 2) : 0;
	$porcentajeLicencias = $numero_dependientes > 0 ?round(($numero_dependientes_licencias/$numero_dependientes)*100, 2) : 0;
	$porcentajeTardanzas = $numero_dependientes_asistencias > 0 ? round(($numero_dependientes_tardanzas/$numero_dependientes_asistencias)*100, 2) : 0;
	$porcentajeSinControl = $numero_dependientes > 0 ?round(($numero_dependientes_sin_control/$numero_dependientes)*100, 2) : 0;
	

	
?>
<html>

<body class="tundra">
<script language="javascript">
	window.alert('la ptm');
	
	dojo.require("dojox.charting.Chart2D");
	dojo.require("dojo.data.ItemFileReadStore");
	dojo.require("dojox.grid.DataGrid");
	dojo.require("dijit.Tooltip");
	dojo.require("dijit.form.Button");
	
	dojo.require("dijit.Dialog");
	dojo.provide("dijit.Dialog");
	
	dojo.require("dojox.charting.action2d.Highlight");		
	dojo.require("dojox.charting.action2d.Tooltip");
	dojo.require("dojox.charting.widget.Legend");
	dojo.require("dojo.colors");
	dojo.require("dojo.fx.easing");
	dojo.require("dojox.charting.themes.PlotKit.green");
	
	var dc = dojox.charting;
	var makeCharts = function(){
		var chart1 = new dojox.charting.Chart2D("chartGap");

		chart1.setTheme(dc.themes.PlotKit.green);			
		chart1.addPlot("default", {type: "Columns", gap: 1, minBarSize: 15, maxBarSize: 15});
		chart1.addAxis("x", {
			labels: [				
						{value: 1, text: "Emp."}, 
						{value: 2, text: "Asist."}, 
						{value: 3, text: "S.M."},							
						{value: 4, text: "Vac."},
						{value: 5, text: "Lic."},
						{value: 6, text: "Tard."},
						{value: 7, text: "N.S.C."}
					]
		});
		
		chart1.addSeries("Series 1",[
										{y: <?php echo $numero_dependientes ?>, fill:"blue"},
										{y: <?php echo $numero_dependientes_asistencias ?>,  fill:"green", tooltip: '<?php echo $porcentajeAsistencias ?>' + "%"},
										{y: <?php echo $numero_dependientes_inasistencias ?>,  fill:"red", tooltip: '<?php echo $porcentajeInasistencias ?>' + "%"},
										{y: <?php echo $numero_dependientes_vacaciones ?>,  fill:"yellow", tooltip: '<?php echo $porcentajeVacaciones ?>' + "%"},
										{y: <?php echo $numero_dependientes_licencias ?>, fill:"gray", tooltip: '<?php echo $porcentajeLicencias ?>' + "%"},
										{y: <?php echo $numero_dependientes_tardanzas ?>,  fill:"orange", tooltip: '<?php echo $porcentajeTardanzas ?>' + "%"},
										{y: <?php echo $numero_dependientes_sin_control ?>,  fill:"pink", tooltip: '<?php echo $porcentajeSinControl ?>' + "%"}											
									], 
						{stroke: {color: "blue", width: 1}, fill: "lightblue"}
		);
		var anim3a = new dc.action2d.Highlight(chart1, "default");
		var anim3b = new dc.action2d.Tooltip(chart1, "default");
		chart1.render();
		var legend1 = new dojox.charting.widget.Legend({chart: chart1, horizontal: false}, "legend1");
		
		window.alert(dojo.exists('dojox.charting.Chart2D.render'));
		
	};
	dojo.addOnLoad(makeCharts);
	
	function obtenerDetalleEstadisticas(consulta)
	{
		window.alert('function obtenerDetalleEstadisticas(consulta)');
	}
		
</script>

<div style="margin-left:20px; padding: 1px; position:relative; background-color:#B0B0B0; width: 250px; height: 160px;" id="chartContainer">							
				<div id="chartGap" style="width: 250px; height: 160px;"></div>
			</div>

</body>
</html>

