<?php header("Content-Type: text/html; charset=ISO-8859-1"); ?>
<?php header("Expires: 0");

$accion='';
if(isset($_POST["accion"])) $accion=$_POST["accion"];
else if(isset($_GET["accion"])) $accion=$_GET["accion"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>Mantenimieto Mac</title>
  </head>
<body>    
<form id="frm_macs_add" name="frm_macs_add" style="font-size:10px;">
<fieldset id="tbl_macs">
<legend>Mantenimiento >> Series</legend>
  <table border="0" cellPadding="1" cellSpacing="1" width="100%" id="tbl_macs_add" style="padding: 3px;">
  <tr>
    <td align="left" width="60">N° Serie:&nbsp;</td>
    <td width="10"></td>
    <td width="320">
        <input type="text" name="txt_mac_numero" id="txt_mac_numero" value="" size="30" maxlength="50"/>
    </td>
  </tr>

  <tr><td colspan="3" height="3"></td></tr>
  
  <tr>
    <td align="left">¿Activo?:&nbsp;</td>
    <td width="10"></td>
    <td width="320" align="left" colspan="5">
        <input class="Input" type="checkbox" name="chk_mac_activo" id="chk_mac_activo" value="false" />
    </td>
  </tr>

  <tr><td colspan="3" height="3"></td></tr>

  <TR>
      <TD  colspan="3" align="center">
            <INPUT class=button type="button" value="Grabar" id="cmdAdicionar" name="cmdAdicionar"  onclick="javascript:Ext_Mac_Autorizados.registrarMacs();" style="width:80px;"/>
        </TD>
  </TR>
  
  </table>

</fieldset>

<input type="hidden" name="hdd_accion" id="hdd_accion" value="<?php echo $accion;?>"/>
<input type="hidden" name="hdd_mac_numero" id="hdd_mac_numero" value=""/>
<div id="div_carga"></div>
<br/>

</form>
    
    
</body>
</html>