<?php
$tiempo = isset($_GET["tiempo"])?$_GET["tiempo"]:"";
$turno = isset($_GET["turno"])?$_GET["turno"]:"";
$tiene_turno = "SI";
if($turno == "ADT"){
    $tiene_turno = "NO";
    $turno = "<b>Ud. no tiene Turno</b>";
}

?>
<!doctype html>
<html lang="us">
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
	<meta charset="utf-8">
	<title>Mensaje GAP</title>
	<link href="jquery/css/ui-lightness/jquery-ui-1.9.1.custom.css" rel="stylesheet"/>
	<script src="jquery/js/jquery-1.8.2.js"></script>
	<script src="jquery/js/jquery-ui-1.9.1.custom.js"></script>
	<script>
	$(function() {
		$( "#dialog" ).dialog({
			autoOpen: false,
			width: 400,
            modal: true,
			buttons: [
				{
					text: "Ok",
					click: function() {
						$( this ).dialog( "close" );
                        window.location='login.php'
					}
				}
			]
		});
		// Link to open the dialog
		$( "#dialog" ).dialog( "open" );

	
	});
	</script>
	<style>
	body{
		font: 80.5% "Trebuchet MS", sans-serif;
		margin: 50px;
	}
	.demoHeaders {
		margin-top: 2em;
	}
	#dialog-link {
		padding: .4em 1em .4em 20px;
		text-decoration: none;
		position: relative;
	}
	#dialog-link span.ui-icon {
		margin: 0 5px 0 0;
		position: absolute;
		left: .2em;
		top: 50%;
		margin-top: -8px;
	}
	
	</style>
</head>
<body>

<div id="dialog" title="MENSAJE GAP">
    <table border="0">
        <tr>
        <td><img src="images/candado.gif" width="100"/></td>
            <td>
                <p style="text-align: justify;">
                <strong>Registro de Asistencia No Permitida.</strong>
                <br /><br /> 
                Ud. puede marcar su entrada desde <?php echo $tiempo?> minutos antes del inicio de su turno y 
                su salida hasta <?php echo $tiempo?> minutos despues del fin de su turno.<br /><br />
                <?php if($tiene_turno == "NO"): ?> 
                    <?php echo $turno?>
                <?php else:?>
                    Su turno es: <?php echo $turno?>
                <?php endif ?>
                </p>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
