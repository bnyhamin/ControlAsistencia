<?php header("Expires: 0"); 

//require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 

  // ini_set('display_errors', 'On');
  //   error_reporting(E_ALL);


if (isset($_GET["responsable"])) $responsable = $_GET["responsable"];


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Consulta de Turno</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
<script language='javascript'>

function Finalizar(){
    window.close();
}

</script>

</head>

<body Class='PageBODY'>
<center class=FormHeaderFont><br> Importar Empleados que asistiran en Feriados </center>
</table>
<center class=FormHeaderFont>
<div style='display:block' id='div_asignar'>
	<form method="post" id="frmimport" action="proceso_upload_feriado.php" enctype="multipart/form-data" >
	<div style="margin:auto;width:400px;line-height: 5;">
		<label for="subir_archivo">Subir archivo</label>
		<input type="file" name="subir_archivo" id="subir_archivo">
		<em style="font-size:9px">Formato txt:</em><a href="formato.txt" target="_blank"><img border="0" src="../../Images/notepad.png"></a>
	</div>
	<div style="margin:auto;width:400px;line-height: 2;text-align:center">
		<input type="hidden" value="<?php echo $responsable ?>" name="session_id" id="session_id">
		<input type="submit" class="buttons" id="Upload" name="Upload" value="Subir">
	</div>
	</form>

</div>
</center>
<div id="result"></div>


<script type="text/javascript" src="../../views/js/librerias/jquery/jquery.form.min.js" ></script>
<script type="text/javascript">
$(document).ready(function() {

	function validate(formData, jqForm, options) { 

	 
        if (!formData[0].value) { 
			alert('Seleccione archivo a importar');
            return false; 
	    } 
		if (confirm('Confirme importar este archivo txt')==false) return false;
	}

	function showResponse(responseText, statusText, xhr, $form)  { 

		return responseText;
	}

	 var options = { 
        target:        '#result',
        beforeSubmit:  validate,  
        success:       showResponse,
        clearForm: true
    }; 
 	$('#frmimport').ajaxForm(options); 


	



});	
</script>
</body>
</html>