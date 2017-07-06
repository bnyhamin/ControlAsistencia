<?php
    header("Expires: 0");
    require_once("../../Includes/Connection.php");
    require_once("../../Includes/Constantes.php"); 
    require_once("../../Includes/mantenimiento.php");
    require_once("../includes/clsCA_Areas.php");
    
    $a = new areas();
    $a->MyUrl = db_host();
    $a->MyUser= db_user();
    $a->MyPwd = db_pass();
    $a->MyDBName= db_name();
    
    $msgsend="";
    
    if (isset($_POST['hddaccion'])){
        if ($_POST['hddaccion']=='SVE'){
            $a->dias=$_POST['txtdias'];
            $msgsend=$a->updAll();
            if($msgsend=="OK"){
?>
                <script type="text/javascript">
                    window.opener.PooMA.jsGetListadoAreas('L');
                    window.close();
                </script>
<?php
            }
        }
    }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Bandeja de Reportes</title>
<meta http-equiv="pragma" content="no-cache">
    <META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" src="../../default.js"></script>
    <script language="JavaScript" src="../jscript.js"></script>
    <script type="text/javascript" src="../asistencias/app/app.js"></script>
    <link rel="stylesheet" type="text/css" href="../style/tstyle.css"/>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#txtdias").keyup(validaN) 
    });
    function validaN(){
        if ($("#txtdias").val()!="") $("#txtdias").val($("#txtdias").val().replace(/[^0-9\.]/g, ""));
    }
    function grabar(){
        if($.trim($("#txtdias").val())==""){
            alert("Debe ingresar Numero de dias");
            $("#txtdias").focus();
            return true;
        }
        $("#hddaccion").val("SVE");
        if (confirm('¿Desea Grabar Datos?')==false) return false;
        document.frm.submit();
        
    }
    </script>
</head>
<body class="PageBODY">
<center style="text-align: center; font-weight: bold;font-size: 13px;">Aplicacion Masiva de Numero de Dias</center>
<form id="frm" name="frm" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">  
<table width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
    <tr>
        <td align="center">Numero de Dias</td>
        <td width="5"></td>
        <td><input type="text" name="txtdias" id="txtdias"></td>
    </tr>
    <tr>
        <td height="5" colspan="3"></td>
    </tr>
    <tr align="center">
        <td colspan="3">
             <input name="cmdAprobar" id="cmdAprobar" type="button" value="Aprobar"  class="Button"  style="WIDTH: 90px;" onclick="javascript:grabar();"/>
        </td>
    </tr>
</table>
    <input type="hidden" name="hddaccion" id="hddaccion" value=""/>
</form>
</body>
</html>
