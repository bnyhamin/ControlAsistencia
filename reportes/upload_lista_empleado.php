<?php 
if(isset($_GET['u'])) $usuario = $_GET['u'];
 ?>
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
	<meta http-equiv="" content="text/html; charset=utf-8">
	<title>Carga de Empleados</title>
	<link rel="stylesheet" type="text/css" href="../../default.css"/>
	<meta http-equiv='pragma' content='no-cache'>
	<style type="text/css">
	#output{
		font-size:12px;
		color: red;
		text-align: center;
	}
	</style>

</head>
<body>
<p class="TITOpciones">Seleccione archivo txt a cargar</center>
<form id="form" action ="procesa_upload_empleados.php" enctype="multipart/form-data"  method="post">
	<table align="center">
		<tr>
			<td><input type="file" name="carga" id="carga">
				<input type="hidden" value="<?php echo $usuario ?>" name="empleado_codigo" id="empleado_codigo"></input>
			</td>

			<td><input type="submit" name="Upload" class="button" value="Subir" >

			</td>
			<td>
			<div style="cursor:hand" onclick="window.open('formato_carga.txt','plantilla_carga','scrollbars=1,resize=1,width=700,height=400')" href="javascript:void(0)"><img title="Formato" border="0" src="../../Images/notepad.png"/></div>				
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center" >
				<img id="load" src="../../Images/load.gif" style="display:none;" width="100px">
			</td>

		</tr>
		<tr>
			<td colspan="2">


			</td>
		</tr>
	</table>
	<div id="output"></div>
	
</form>
<script type="text/javascript" src="../../views/js/librerias/jquery/jquery.min.js"></script>
<script type="text/javascript" src="../../views/js/librerias/jquery/jquery.form.min.js"></script>
<script type="text/javascript">
var percent = $('.percent');
	 $('#form').submit(function() { 
	 	$('#load').show();
	 	$('#output').hide();
        $(this).ajaxSubmit({
        	'target': '#output',        	
        	'success': function(rs){
        		$('#load').hide();
	 			$('#output').show();
	 			if (rs =='OK') {
	 				self.close();
	 			}else{
	 				$('#output').html(rs);
	 			}
        	},
        	'error':function(){

        	}
        }); 
 
        return false; 
    }); 

</script>
</body>
</html>


