<?PHP header('Expires: 0');

    require('../../Includes/Seguridad.php');
    require('../../Includes/Connection.php');
    require('../../Includes/Constantes.php');
    require('../includes/clsca_movilidad_unidad.php');
    
    $movil_unidad_codigo='';
    $movil_unidad_descripcion='';
    $movil_unidad_capacidad='';
    $movil_unidad_espera=0;
    $movil_unidad_placa='';
    $movil_unidad_chofer='';
    $movil_unidad_activo='';
    $fecha_registro='';
    $usuario_registro='';
    $fecha_modifica='';
    $usuario_modifica=$_SESSION["usuario_id"];
    
    $o = new ca_movilidad_unidad();
    $o->MyDBName= db_name();
    $o->MyUrl = db_host();
    $o->MyUser= db_user();
    $o->MyPwd = db_pass();
    
    $sRpta="";
    $pagina= "";
    $orden= "";
    $buscam= "";

    if (isset($_GET["pagina"])){
        $pagina= $_GET["pagina"];
        $orden= $_GET["orden"];
        $buscam= $_GET["buscam"];
    }else{
        $pagina= $_POST["pagina"];
        $orden= $_POST["orden"];
        $buscam= $_POST["buscam"];
    }

    if (isset($_POST["movil_unidad_codigo"])){
        $movil_unidad_codigo = $_POST["movil_unidad_codigo"];
    }else{
        $movil_unidad_codigo = isset($_GET["movil_unidad_codigo"])?$_GET["movil_unidad_codigo"]:0;
    }
    if (isset($_POST["movil_unidad_descripcion"])){
        $movil_unidad_descripcion = $_POST["movil_unidad_descripcion"];
    }
    if (isset($_POST["movil_unidad_capacidad"])){
        $movil_unidad_capacidad = $_POST["movil_unidad_capacidad"];
    }
    if (isset($_POST["movil_unidad_espera"])){
        $movil_unidad_espera = $_POST["movil_unidad_espera"];
    }
    if (isset($_POST["movil_unidad_placa"])){
        $movil_unidad_placa = $_POST["movil_unidad_placa"];
    }
    if (isset($_POST["movil_unidad_chofer"])){
        $movil_unidad_chofer = $_POST["movil_unidad_chofer"];
    }
    if (isset($_POST["movil_unidad_activo"])){
        $movil_unidad_activo = $_POST["movil_unidad_activo"];
    }

    if (isset($_POST["hddaccion"])){
        if ($_POST["hddaccion"]=="SVE"){//la orden es grabar
            $o->movil_unidad_descripcion= $_POST["movil_unidad_descripcion"];
            $o->movil_unidad_capacidad= $_POST["movil_unidad_capacidad"];
            $o->movil_unidad_espera= $_POST["movil_unidad_espera"];
            $o->movil_unidad_placa= $_POST["movil_unidad_placa"]==""?null:$_POST["movil_unidad_placa"];
            $o->movil_unidad_chofer= $_POST["movil_unidad_chofer"]==""?null:$_POST["movil_unidad_chofer"];
            $o->movil_unidad_activo= isset($_POST["movil_unidad_activo"])?$_POST["movil_unidad_activo"]:0;
            if ($_POST["movil_unidad_codigo"]==""){
                //Insertar
                $sRpta = $o->AddNew();
                $movil_unidad_codigo = $o->movil_unidad_codigo;
            }else{
                //Actualizar
                $o->movil_unidad_codigo=$movil_unidad_codigo;
                $sRpta = $o->Update();
            }
            if ($sRpta!="OK"){
                echo $sRpta;
            }else{
                ?>
                <script language=javascript>
                alert('Se guardo registro');
                </script>
                <?php
            }
        }
    }else{
        if (isset($_GET["codigo"])) $movil_unidad_codigo= $_GET["codigo"];
        if ($movil_unidad_codigo!=""){
            $o->movil_unidad_codigo=$movil_unidad_codigo;
            //Recuperar datos del Registro
            $sRpta = $o->Query();
            if ($sRpta!="OK"){
                echo sRpta;
            }
            $movil_unidad_descripcion = $o->movil_unidad_descripcion;
            $movil_unidad_capacidad = $o->movil_unidad_capacidad;
            $movil_unidad_espera = $o->movil_unidad_espera;
            $movil_unidad_placa = $o->movil_unidad_placa;
            $movil_unidad_chofer = $o->movil_unidad_chofer;
            $movil_unidad_activo = $o->movil_unidad_activo;
            $fecha_registro = $o->fecha_registro;
            $usuario_registro = $o->usuario_registro;
            $fecha_modifica = $o->fecha_modifica;
            $usuario_modifica = $o->usuario_modifica;
        
        }
    }
?>
<HTML>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv='pragma' content='no-cache'>
<META NAME=author CONTENT='TUMI Solutions S.A.C.'>
<title>Maestro de Unidades de Transporte</title>
<link rel="stylesheet" type="text/css" href="../../default.css">
<script language="JavaScript" src="../../default.js"></script>
<?php require_once('../../Includes/librerias_easyui.php');?>
<link rel="stylesheet" type="text/css" href="../../views/css/estilos_formulario_easyui.css"  />  
</HEAD>


<script>

function confirmar(){
    if (!$.trim($('#movil_unidad_descripcion').textbox('getValue'))){        
        $.messager.alert('Alerta','Ingrese Descripción');      
        return false;
    }
    if (!$.trim($('#movil_unidad_capacidad').numberbox('getValue'))){        
        $.messager.alert('Alerta','Ingrese Capacidad');      
        return false;
    }
        
    if ($('#movil_unidad_descripcion').textbox('getValue').length  > 80){
        $.messager.alert('Alerta','L\xedmite de caracteres para campo descripción es de 80');
        return false;
    }
    if (!$('#movil_unidad_capacidad').numberbox('getValue') > 0){
        $.messager.alert('Alerta','Monto de capacidad debe ser mayor que 0');
        return false;
    }
    
    if ($('#movil_unidad_placa').textbox('getValue').length  > 10){
        $.messager.alert('Alerta','L\xedmite de caracteres para campo Nro Placa es de 10');
        return false;
    }
    
    if ($('#movil_unidad_chofer').textbox('getValue').length  > 80){
        $.messager.alert('Alerta','L\xedmite de caracteres para campo Nombre Conductor es de 80');
        return false;
    }
    
	
	
    
    $.messager.confirm('Confirmaci\u00F3n', '¿confirma guardar los datos?', function(r){
        if (r){
            document.frm.hddaccion.value='SVE'
            document.frm.submit();
            return true;
        }
    });
    
    return false;   
}



</script>

<body bgcolor='#ffffff' text='#000000' link='#000000' alink='#999999' vlink='#000000'   Class='PageBody'  >
    <center>
        <div class="easyui-panel" title="Maestro de Unidades de Transporte" style="width:60%;padding:30px 0;">
            <form name='frm' id='frm' action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post' >
                <table   width='80%' align='center' cellspacing='2' cellpadding='0' border='0'>
                    <tr>
                        <td  class="fuente" align='right'>
                        Código &nbsp;</td>
                        <td  >
                        <input   class="easyui-textbox"  name='movil_unidad_codigo' id='movil_unidad_codigo' value='<?php echo $movil_unidad_codigo?>' size='7' readonly  >
                        </td>
                    </tr>
                    <tr>
                        <td  class="fuente" align='right'>
                        Descripci&oacute;n &nbsp;</td>
                        <td class="fuente">
                        <input  class="easyui-textbox"  name='movil_unidad_descripcion' id='movil_unidad_descripcion' value='<?php echo $movil_unidad_descripcion?>' size='51'  data-options="required:true,validType:['text','length[0,80]']" />
                        </td>
                    </tr>
                    <tr>
                        <td  class="fuente" align='right'>
                        Capacidad &nbsp;</td>
                        <td class="fuente">
                        <input  type="text" class="easyui-numberbox" name='movil_unidad_capacidad' id='movil_unidad_capacidad'   value='<?php echo $movil_unidad_capacidad?>' data-options="min:0,precision:0">
                        </td>
                    </tr>
                    <tr>
                        <td  class="fuente" align='right'>
                        Nro. en Lista Espera &nbsp;</td>
                        <td class="fuente">
                        <input  type="text" class="easyui-numberbox" name='movil_unidad_espera' id='movil_unidad_espera'   value='<?php echo $movil_unidad_espera?>' data-options="min:0,precision:0">
                        </td>
                    </tr>
                    <tr>
                        <td  class="fuente" align='right'>
                        Nro. Placa &nbsp;</td>
                        <td class="fuente">
                        <input  class="easyui-textbox"  name='movil_unidad_placa' id='movil_unidad_placa' value='<?php echo $movil_unidad_placa ?>' size='11'  data-options="validType:['text','length[0,10]']" /> 
                        </td>
                    </tr>
                    <tr>
                        <td  class="fuente" align='right'>
                        Nombre Conductor &nbsp;</td>
                        <td class="fuente">
                        <input  class="easyui-textbox"  name='movil_unidad_chofer' id='movil_unidad_chofer' value='<?php echo $movil_unidad_chofer ?>' size='51'  data-options="validType:['text','length[0,80]']" /> 
                        </td>
                    </tr>                    
                    <tr>
                        <td  class="fuente" align='right'>
                        Activo &nbsp;</td>
                        <td  >
                        <input class="easyui-checkbox"   type='checkbox' name='movil_unidad_activo' id='movil_unidad_activo' value='1' <?php if ($movil_unidad_activo*1==1) echo 'checked' ?> />
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2 >&nbsp;
                        </td>
                    </tr>
                    <tr align='center'>
                        <td  colspan=2 >
                        <a id="btn_aprobar" title="Guardar" class="easyui-tooltip" href="#" class="negrita" onclick="confirmar();">Guardar</a>
                        <a id="btn_rechazar"   title="Cerrar"   class="easyui-tooltip" href="#" class="negrita" onclick="self.location.href='../../Mantenimientos/main_ca_movil_unidad.php?pagina=<?php echo $pagina?>&orden=<?php echo $orden?>&buscam=<?php echo $buscam?>'">Cerrar</a>   
                        </td>
                    </tr>
                    <input type='hidden' name='pagina' id='pagina' value='<?php echo $pagina ?>' />
                    <input type='hidden' name='orden' id='orden' value='<?php echo $orden ?>' />
                    <input type='hidden' name='buscam' id='buscam' value='<?php echo $buscam ?>' />
                    <input type="hidden" name="hddorigen" id="hddorigen" value="<?php echo $origen ?>" />
                    <input type='hidden' name='hddaccion' id='hddaccion' value='' />
                </table>
                <br /><br />
                
                <?php echo $cadenilla; ?>
            </form>
        </div>
    </center>

<script>
    $('#btn_aprobar').linkbutton({
        iconCls: 'icon-save',
        iconAlign : 'top',
        size : 'small'
    });
    
    $('#btn_rechazar').linkbutton({
        iconCls: 'icon-cancel',
        iconAlign : 'top',
        size : 'small'
    });
    
    $(function(){  
        $('#btn_aprobar').linkbutton('resize', {width: 95});
        $('#btn_rechazar').linkbutton('resize', {width: 95});
    });
</script>
</body>
</HTML>
<!-- TUMI Solutions S.A.C.-->