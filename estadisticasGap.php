<?php 
	require_once("includes/Connection.php");
	require_once("includes/Constantes.php");
	require_once("includes/mantenimiento.php");
	require_once("includes/clsCA_Estadisticas.php");

	header("Content-Type: text/plain; charset=iso-8859-1"); 
	session_start();
	set_time_limit (300);

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
	$minutos_param = $objE->minutoparametroGAP();

	$numero_dependientes = $objE->retornaEmpleadosDependientes($superiorEmpleados, 0, $flag_tipo_usuario);
	$numero_dependientes_asistencias = $objE->retornaEmpleadosDependientesAsistencias($superiorEmpleados, 0, $flag_tipo_usuario);
	$numero_dependientes_inasistencias = $objE->retornaEmpleadosDependientesInasistencias($superiorEmpleados, 0, $flag_tipo_usuario);
	$numero_dependientes_tardanzas = $objE->retornaEmpleadosDependientesTardanzas($superiorEmpleados, 0, $flag_tipo_usuario);
	$numero_dependientes_vacaciones = $objE->retornaEmpleadosDependientesVacaciones($superiorEmpleados, 0, $flag_tipo_usuario);
	$numero_dependientes_licencias = $objE->retornaEmpleadosDependientesLicencias($superiorEmpleados, 0, $flag_tipo_usuario);
	$numero_dependientes_sin_control = $objE->retornaEmpleadosNoSujetoControl($superiorEmpleados, 0, $flag_tipo_usuario);
	$numero_dependientes_loggervsmarca = $objE->retornaEmpleadosDiferenciaLoggerMarcacion($superiorEmpleados, 0, $flag_tipo_usuario);
	
	$porcentajeAsistencias = $numero_dependientes > 0 ?round(($numero_dependientes_asistencias/$numero_dependientes)*100, 2) : 0;
	$porcentajeInasistencias = $numero_dependientes > 0 ?round(($numero_dependientes_inasistencias/$numero_dependientes)*100, 2) : 0;
	$porcentajeVacaciones = $numero_dependientes > 0 ?round(($numero_dependientes_vacaciones/$numero_dependientes)*100, 2) : 0;
	$porcentajeLicencias = $numero_dependientes > 0 ?round(($numero_dependientes_licencias/$numero_dependientes)*100, 2) : 0;
	$porcentajeTardanzas = $numero_dependientes_asistencias > 0 ? round(($numero_dependientes_tardanzas/$numero_dependientes_asistencias)*100, 2) : 0;
	$porcentajeSinControl = $numero_dependientes > 0 ?round(($numero_dependientes_sin_control/$numero_dependientes)*100, 2) : 0;
	$porcentajeLoggervsmarca = $numero_dependientes > 0 ?round(($numero_dependientes_loggervsmarca/$numero_dependientes)*100, 2) : 0;
?>

<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
	<link rel="stylesheet" TYPE="text/css" href="includes/dojotoolkit/dojo/resources/dojo.css" />
	<link rel="stylesheet" TYPE="text/css" href="includes/dojotoolkit/dojox/grid/resources/Grid.css" />
	<link rel="stylesheet" TYPE="text/css" href="includes/dojotoolkit/dijit/themes/tundra/tundra.css" />
</head>
<body class="tundra">
<script language="javascript">
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
	
	dojo.declare("my.Dialog", [dijit.Dialog], { // CREAMOS UN TOOLTIP QUE NO PUEDE SER CANCELADO CON "ESC" NI TIENE LA "X" PARA CERRAR
		disableCloseButton : true,

		/* *********************************************************** postCreate */
		postCreate : function() {
			this.inherited(arguments);
			this._updateCloseButtonState();
		},

		/* *************************************************************** _onKey */
		_onKey : function(evt) {
			if (this.disableCloseButton && evt.charOrCode == dojo.keys.ESCAPE)
				return;
			this.inherited(arguments);
		},

		/* ************************************************ setCloseButtonDisabled*/
		setCloseButtonDisabled : function(flag) {
			this.disableCloseButton = flag;
			this._updateCloseButtonState();
		},

		/* ********************************************** _updateCloseButtonState */
		_updateCloseButtonState : function() {
			dojo.style(this.closeButtonNode, "display",
					this.disableCloseButton ? "none" : "block");
		}
	});
	
	dojo
		.addOnLoad(function() {
		new dijit.Tooltip(
			{
				connectId : [ "lnkDependientes" ],
				label : "<span class='labelToolTip'><?php echo $numero_dependientes ?></span>"
			});
		new dijit.Tooltip(
			{
				connectId : [ "lnkAsistencias" ],
				label : "<span class='labelToolTip'><?php echo $porcentajeAsistencias ?>%</span>"
			});
		new dijit.Tooltip(
			{
				connectId : [ "lnkInasistencias" ],
				label : "<span class='labelToolTip'><?php echo $porcentajeInasistencias ?>%</span>"
			});
		new dijit.Tooltip(
			{
				connectId : [ "lnkVacaciones" ],
				label : "<span class='labelToolTip'><?php echo $porcentajeVacaciones ?>%</span>"
			});
		new dijit.Tooltip(
			{
				connectId : [ "lnkLicencias" ],
				label : "<span class='labelToolTip'><?php echo $porcentajeLicencias ?>%</span>"
			});
		new dijit.Tooltip(
			{
				connectId : [ "lnkTardanzas" ],
				label : "<span class='labelToolTip'><?php echo $porcentajeTardanzas ?>%</span>"
			});
		new dijit.Tooltip(
			{
				connectId : [ "lnkSinControl" ],
				label : "<span class='labelToolTip'><?php echo $porcentajeSinControl ?>%</span>"
			});
		new dijit.Tooltip(
			{
				connectId : [ "lnkLoggervGAP" ],
				label : "<span class='labelToolTip'><?php echo $porcentajeLoggervsmarca ?>%</span>"
			});
		
		var jsonStore =  new dojo.data.ItemFileReadStore({
			data : {
			  items : []
			}
		});
		
		var gridTmp = new dojox.grid.DataGrid({
				store: jsonStore,
				jsId: "gridTmp",
				id: "gridTmp",
				structure: [ 					
				{name: "Nro.", field:"numeroRegistro", width : "30px", headerClasses: "gridHeader"},
				//{name: "Empleado Codigo", field:"empleadoCodigo", width : "60px", headerClasses: "gridHeader"},
				{name: "Nombres", field: "nombres", width : "210px", headerClasses: "gridHeader"},
				{name: "Area", field: "area", width : "150px", headerClasses: "gridHeader"}
				],
				noDataMessage: 'No se encontraron coincidencias'
		}, document.createElement('div'));
		dojo.byId("grillaEmpleados").appendChild(gridTmp.domNode);
		gridTmp.startup();
		
		var gridTmpLicencias = new dojox.grid.DataGrid({
				store: jsonStore,
				jsId: "gridTmpLicencias",
				id: "gridTmpLicencias",
				structure: [ 					
				{name: "Nro.", field:"numeroRegistro", width : "30px", headerClasses: "gridHeader"},
				//{name: "Empleado Codigo", field:"empleadoCodigo", width : "60px", headerClasses: "gridHeader"},
				{name: "Nombres", field: "nombres", width : "185px", headerClasses: "gridHeader"},
				{name: "Area", field: "area", width : "125px", headerClasses: "gridHeader"},
				{name: "Movimiento", field: "movimiento", width : "100px", headerClasses: "gridHeader"}
				],
				noDataMessage: 'No se encontraron coincidencias'
		}, document.createElement('div'));
		dojo.byId("grillaEmpleadosLicencias").appendChild(gridTmpLicencias.domNode);
		gridTmpLicencias.startup();
		
		dojo.connect(dijit.byId("buscarEmpleadoPopup"), "onHide", function(evt){dijit.byId('processInformation').hide();})
		dojo.connect(dijit.byId("buscarEmpleadoPopupLicencias"), "onHide", function(evt){dijit.byId('processInformation').hide();})
		dojo.connect(dijit.byId('processInformation'), "onShow", function(evt){dojo.byId("processInformation").style.display = "block";});
		dojo.connect(dijit.byId('processInformation'), "onHide", function(evt){dojo.byId("processInformation").style.display = "none";});			
		dojo.connect(gridTmp,"onStyleRow",function(row){
			row.customStyles = 'FONT-SIZE: 9px; FONT-FAMILY: verdana; FONT-WEIGHT: normal; COLOR: #617632; CURSOR: HAND;';
		});
		dojo.connect(gridTmpLicencias,"onStyleRow",function(row){
			row.customStyles = 'FONT-SIZE: 9px; FONT-FAMILY: verdana; FONT-WEIGHT: normal; COLOR: #617632; CURSOR: HAND;';
		});
	});

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
						{value: 7, text: "N.S.C."},
						{value: 8, text: "L>=5"}
					]
		});
		
		chart1.addSeries("Series 1",[
										{y: <?php echo $numero_dependientes ?>, fill:"blue"},
										{y: <?php echo $numero_dependientes_asistencias ?>,  fill:"yellow", tooltip: '<?php echo $porcentajeAsistencias ?>' + "%"},
										{y: <?php echo $numero_dependientes_inasistencias ?>,  fill:"red", tooltip: '<?php echo $porcentajeInasistencias ?>' + "%"},
										{y: <?php echo $numero_dependientes_vacaciones ?>,  fill:"green", tooltip: '<?php echo $porcentajeVacaciones ?>' + "%"},
										{y: <?php echo $numero_dependientes_licencias ?>, fill:"gray", tooltip: '<?php echo $porcentajeLicencias ?>' + "%"},
										{y: <?php echo $numero_dependientes_tardanzas ?>,  fill:"orange", tooltip: '<?php echo $porcentajeTardanzas ?>' + "%"},
										{y: <?php echo $numero_dependientes_sin_control ?>,  fill:"pink", tooltip: '<?php echo $porcentajeSinControl ?>' + "%"},											
										{y: <?php echo $numero_dependientes_loggervsmarca ?>,  fill:"pink", tooltip: '<?php echo $porcentajeLoggervsmarca ?>' + "%"}											
									], 
						{stroke: {color: "blue", width: 1}, fill: "lightblue"}
		);
		var anim3a = new dc.action2d.Highlight(chart1, "default");
		var anim3b = new dc.action2d.Tooltip(chart1, "default");
		chart1.render();
		var legend1 = new dojox.charting.widget.Legend({chart: chart1, horizontal: false}, "legend1");
		chart1.connectToPlot( // PARA CONTROLAR CLICKS EN COLUMNAS
			"default",  function(o){
				if(o.element == "column" && o.type == "onmouseover")
				{
					document.body.style.cursor = "hand";
				}
				else if(o.element == "column" && o.type == "onmouseout")
				{
					document.body.style.cursor = "default";
				}
				
				if(o.type == "onclick")
				{
					if(o.index == 0) //DEPENDIENTES
					{
						obtenerDetalleEstadisticas(1);
					}
					else if(o.index == 1) //ASISTENCIAS
					{
						obtenerDetalleEstadisticas(2);
					}						
					else if(o.index == 2) //INASISTENCIAS
					{
						obtenerDetalleEstadisticas(3);
					}
					else if(o.index == 3) //VACACIONES
					{
						obtenerDetalleEstadisticas(5);
					}
					else if(o.index == 4) //LICENCIAS
					{
						obtenerDetalleEstadisticas(6);
					}
					else if(o.index == 5) //TARDANZAS
					{
						obtenerDetalleEstadisticas(4);
					}
					else if(o.index == 6) //NO SUJETOS A CONTROL
					{
						obtenerDetalleEstadisticas(7);
					}					
					else if(o.index == 7) //LOOGER MAYOR GAP
					{
						obtenerDetalleEstadisticas(8);
					}
				}
			}
		);
	};
	dojo.addOnLoad(makeCharts);
			
	function obtenerDetalleEstadisticas(consulta)
	{
		dijit.byId('processInformation').show();
		var tiempo = 5 * 60 * 1000;
		strUrl = "includes/clsCA_MenuEstadisticasGap.php?action=obtenerDetalleEstadisticas&superiorEmpleados=<?php echo $superiorEmpleados ?>&consulta=" + consulta + "&flag_tipo_usuario=<?php echo $flag_tipo_usuario ?>";
		
		dojo.xhrGet({			
			url: strUrl, 
			handleAs: "json",
			timeout: tiempo,
			preventCache: true,
			load: function(response)
			{
				if(response != undefined && response != null && response != "")
				{
					gapEstadisticasParser(response, consulta);
				}
				else
				{
					dijit.byId('buscarEmpleadoPopup').hide();
					dojo.byId("lblResultado").innerHTML = response != "" ? response : "No se obtuvo respuesta del servidor.";
					dijit.byId('processInformation').hide();
					setTimeout(function() {dijit.byId("processOk").show();}, 250);
				}
			},
			error: function(err)
			{
				dijit.byId('buscarEmpleadoPopup').hide();
				dojo.byId("lblResultado").innerHTML = "Hubo un error al obtener respuesta del servidor. Intente nuevamente por favor";
				dijit.byId('processInformation').hide();
				setTimeout(function() {dijit.byId("processOk").show();}, 250);
			}
		});
		
	}

	function gapEstadisticasParser(listadoEmpleados, consulta)
	{		
		var items = null;
		if(consulta == 6)
		{
			items = dojo.map(listadoEmpleados, function(item) {
			return {
						numeroRegistro : item.numeroRegistro,
						//empleadoCodigo : item.empleadoCodigo, 
						nombres : item.nombres,
						area : item.area,
						movimiento : item.movimiento
					};
			});
		}
		else
		{
			items = dojo.map(listadoEmpleados, function(item) {
			return {
						numeroRegistro : item.numeroRegistro,
						//empleadoCodigo : item.empleadoCodigo, 
						nombres : item.nombres,
						area : item.area
					};
			});
		}
		
		//cargamos el objeto de lectura para el datagrid
		var jsonStoreBusqueda =  new dojo.data.ItemFileReadStore({
			data : {
			  items : items
			}
		});
		
		if(consulta == 6)
		{
			dijit.byId('buscarEmpleadoPopupLicencias').show();
			dijit.byId("gridTmpLicencias").setStore(jsonStoreBusqueda);
			dijit.byId("gridTmpLicencias").update();
			dijit.byId("gridTmpLicencias").sort();
		}
		else
		{
			dijit.byId('buscarEmpleadoPopup').show();
			dijit.byId("gridTmp").setStore(jsonStoreBusqueda);
			dijit.byId("gridTmp").update();
			dijit.byId("gridTmp").sort();		
		}
		
		
		dijit.byId('processInformation').hide();
	}

</script>
<!--div style="position:relative; top:80px; left:20px;"-->
<table cellpadding="0" cellspacing="0" border="0" width="518" style="margin-left: 10px">
	<tr>
		<td>
			<table width="280" class="tableEstadisticas" cellspacing="0">
				<tr class="filaHeaderTableEstadisticas">
					<td colspan="2" class="colHeaderTableEstadisticas">
						ESTADISTICAS DEL DÍA DE TUS EMPLEADOS
					</td>
				</tr>
				<tr class="filaParTableEstadisticas">
					<td width="220" class="colParTableEstadisticas">
						<span>Total Empleados:</span>
					</td>
					<td width="60">								
						<a id="lnkDependientes" href="javascript:;" onclick="javascript:obtenerDetalleEstadisticas(1);">
							<span class="vinculosEstadisticasOn"><?php echo $numero_dependientes ?></span>
						</a>
					</td>
				</tr>
				<tr class="filaImparTableEstadisticas">
					<td class="colImparTableEstadisticas">
						<span>Con asistencia:</span>
					</td>
					<td >
						<?php if($numero_dependientes_asistencias > 0) {?>
							<a id="lnkAsistencias" href="javascript:;" onclick="javascript:obtenerDetalleEstadisticas(2);">
								<span class="vinculosEstadisticasOn"><?php echo $numero_dependientes_asistencias ?></span>
							</a>
						<?php } else { ?>
							<span class="vinculosEstadisticasOff"><?php echo $numero_dependientes_asistencias ?></span>
						<?php } ?>
					</td>
				</tr>
				<tr class="filaParTableEstadisticas">
					<td class="colParTableEstadisticas">
						<span>Sin marcar:</span>
					</td>
					<td >
						<?php if($numero_dependientes_inasistencias > 0) {?>
							<a id="lnkInasistencias" href="javascript:;" onclick="javascript:obtenerDetalleEstadisticas(3);">
								<span class="vinculosEstadisticasOn"><?php echo $numero_dependientes_inasistencias ?></span>
							</a>
						<?php } else { ?>
							<span class="vinculosEstadisticasOff"><?php echo $numero_dependientes_inasistencias ?></span>
						<?php } ?>
					</td>
				</tr>							
				<tr class="filaImparTableEstadisticas">
					<td class="colImparTableEstadisticas">
						<span>En vacaciones</span>
					</td>
					<td >
						<?php if($numero_dependientes_vacaciones > 0) {?>
							<a  id="lnkVacaciones" href="javascript:;" onclick="javascript:obtenerDetalleEstadisticas(5);">
								<span class="vinculosEstadisticasOn"><?php echo $numero_dependientes_vacaciones ?></span>
							</a>
						<?php } else { ?>
							<span class="vinculosEstadisticasOff"><?php echo $numero_dependientes_vacaciones ?></span>
						<?php } ?>
					</td>
				</tr>
				<tr class="filaParTableEstadisticas">
					<td class="colParTableEstadisticas">
						<span>Con licencias</span>
						<span style="margin-left:5px;">(Méd. Mat.C/S Goce)</span>									
					</td>
					<td >
						<?php if($numero_dependientes_licencias > 0) {?>
							<a id="lnkLicencias"href="javascript:;" onclick="javascript:obtenerDetalleEstadisticas(6);">
								<span class="vinculosEstadisticasOn"><?php echo $numero_dependientes_licencias ?></span>
							</a>
						<?php } else { ?>
							<span class="vinculosEstadisticasOff"><?php echo $numero_dependientes_licencias ?></span>
						<?php } ?>
					</td>
				</tr>							
				<tr class="filaTardanzasTableEstadisticas" height="20">
					<td class="colImparTableEstadisticas">
						<span>Con tardanza</span>
					</td>
					<td >
						<?php if($numero_dependientes_tardanzas > 0) {?>
							<a id="lnkTardanzas" href="javascript:;" onclick="javascript:obtenerDetalleEstadisticas(4);">
								<span class="vinculosEstadisticasOn"><?php echo $numero_dependientes_tardanzas ?></span>
							</a>
						<?php } else { ?>
							<span class="vinculosEstadisticasOff"><?php echo $numero_dependientes_tardanzas ?></span>
						<?php } ?>
					</td>
				</tr>
				<tr class="filaNoControlTableEstadisticas" height="20">
					<td class="colParTableEstadisticas">
						<span>No sujetos a control</span>
					</td>
					<td >
						<?php if($numero_dependientes_sin_control > 0) {?>
							<a id="lnkSinControl" href="javascript:;" onclick="javascript:obtenerDetalleEstadisticas(7);">
								<span class="vinculosEstadisticasOn"><?php echo $numero_dependientes_sin_control ?></span>
							</a>
						<?php } else { ?>
							<span class="vinculosEstadisticasOff"><?php echo $numero_dependientes_sin_control ?></span>
						<?php } ?>
					</td>
				</tr>
				<tr class="filaImparTableEstadisticas" height="20">
					<td class="colParTableEstadisticas">
						<span>Inicio Logger >=<?php echo $minutos_param ?> min H.E.</span>
					</td>
					<td >
						<?php if($numero_dependientes_loggervsmarca > 0) {?>
							<a id="lnkLoggervGAP" href="javascript:;" onclick="javascript:obtenerDetalleEstadisticas(8);">
								<span class="vinculosEstadisticasOn"><?php echo $numero_dependientes_loggervsmarca ?></span>
							</a>
						<?php } else { ?>
							<span class="vinculosEstadisticasOff"><?php echo $numero_dependientes_loggervsmarca ?></span>
						<?php } ?>
					</td>
				</tr>
			</table>
		</td>
		<td>
			<div style="margin-left:20px; padding: 1px; position:relative; background-color:#B0B0B0;" id="chartContainer">							
				<div id="chartGap" style="width: 250px; height: 160px;"></div>
			</div>
		</td>
	</tr>
</table>
<div id="processOk" dojoType="dijit.Dialog" title="Resultado de la Operaci&oacute;n">
	<table cellpadding="0" cellspacing="0" border="0" width="350">
		<tr>
			<td align="left"><label id="lblResultado">&nbsp;</label></br> </br></td>
		</tr>
		<tr>
			<td align="center">
				<div dojoType="dijit.form.Button" label="Aceptar"
					onClick="javascript:dijit.byId('processOk').hide();"></div>
			</td>
		</tr>
	</table>
</div>
<div id="processInformation" style="display:none;" dojoType="my.Dialog" title="Procesando Operaci&oacute;n">
	<table  cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td align="center"><img src="images/Preloader.gif" /></td>
		</tr>
		<tr>
			<td align="center"><label>Espere un momento por favor. Procesando...</br> </br>
			</td>
		</tr>
	</table>
</div>
<div id="buscarEmpleadoPopup" dojoType="dijit.Dialog" title="Resultados">
	<table cellpadding="0" cellspacing="0" style="border: 0;" style="width: 420px; height: 100%;">
		<tr>
			<td colspan="4" style="padding-top: 10px; padding-left: 20px; padding-right: 20px" height="200" valign="top">
				<div id="grillaEmpleados" style="width: 420px; height: 100%;"></div>
			</td>
		</tr>
	</table>	
</div>
<div id="buscarEmpleadoPopupLicencias" dojoType="dijit.Dialog" title="Resultados">
	<table cellpadding="0" cellspacing="0" style="border: 0;" style="width: 470px; height: 100%;">
		<tr>
			<td colspan="4" style="padding-top: 10px; padding-left: 20px; padding-right: 20px" height="200" valign="top">
				<div id="grillaEmpleadosLicencias" style="width: 470px; height: 100%;"></div>
			</td>
		</tr>
	</table>	
</div>
</body>
</html>