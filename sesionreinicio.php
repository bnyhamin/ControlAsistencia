<?php
require_once("includes/Connection.php");
?>
<html>
   <HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
   <title><?php echo tituloGAP();?></title>
   <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
   <script type="text/javascript" src="../views/js/librerias/jquery/app.js"></script>
   <link href="../views/css/stylesesion.css" type="text/css" rel="stylesheet"/>
   <script type="text/javascript">
       $(document).ready(function(){
            $('#b_regresar').click(Ext_Sistema.Ext_Regresar);
        });
        Ext_Sistema={
            Ext_Regresar:function(){
                window.location.href="login.php";
            }
        }
   </script>
   </head>
   <body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color: #FFFFF;">
   <table style="width: 100%;height: 100%;" cellpadding="0" cellspacing="0">
      <tr><td align="center" valign="middle">
          <table cellpadding="0" cellspacing="0" style="border: 6px solid #DDDDDD;"> 
             <tr>
                <td style="width: 1000px; height: 100px; background-color: #FFFFFF;">    
                   <table cellpadding="0" cellspacing="0" border="0">
                        <tr><td width="1000" height="100" style="background-color: #35537E; color: #FAFAFA;font-weight: 600;font-size: 15px;">&nbsp;&nbsp;&nbsp;<?php echo tituloGAP(); ?></td></tr>
                   </table>
                </td>
                </tr>
            <tr><td style="height:550px; background-color:#EFEFF1;" align="center">
                 <table cellpadding="0" cellspacing="0">
                 <tr><td>
                    <table cellpadding="0" cellspacing="0">
                        <tr><td width="46" height="43" background="../views/imagenes/finsesion/barras/seguridad.jpg">&nbsp;
                                <td width="257" height="43" background="../views/imagenes/finsesion/barras/barra1.jpg" class="sttexto01b">&nbsp;&nbsp;&nbsp;TU SESION HA EXPIRADO
                                <td width="5" height="43" background="../views/imagenes/finsesion/barras/barra2.jpg">   
                    </table>
                 <tr><td class="stborde01" align="center">
                     <table cellpadding="4" cellspacing="4">
                         <tr><td>
                         <tr><td>
                             <td align="center">
                                 <table cellpadding="0" cellspacing="0" class="sttexto01">
                                   <td colspan="3" align="center">
                                      <input type="button" name="b_regresar" id="b_regresar" class="stbtn01"  value="REGRESA A LOGUEARTE" class="hide"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                   </td>
                                 </tr>
                                 </table>
                             <td>
                             <tr><td height="5" colspan="3"><div id="MensajeCarga" align="center"></div>
                                 <tr><td colspan="3"><div id="mensaje_valig" style="font-family:Verdana;color:#FF0000;font-size:12px" align="center"></div> 	    		<tr><td>
                     </table>
                 </table>
         </table>
   </table>
</form>     
</body>
</html>
