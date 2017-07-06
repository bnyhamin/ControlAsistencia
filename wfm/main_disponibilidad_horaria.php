<?PHP header('Expires: 0'); 
      require('../../Includes/Connection.php'); 
      require('../../Includes/Constantes.php'); 
      require('../../Includes/MyGrilla.php'); 
      require_once("../../Includes/seguridad.php");
?>
<HTML>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv='pragma' content='no-cache'>
<META NAME=author CONTENT='TUMI Solutions S.A.C.'>
<title>Maestro de Disponibilidad Horaria - <?php echo SistemaNombre(); ?> </title>
<link rel="stylesheet" type="text/css" href="../../default.css">
<script language="JavaScript" src="../../default.js"></script>
</HEAD>
<body bgcolor='#ffffff' text='#000000' link='#000000' alink='#999999' vlink='#000000'   Class='PageBody'  >
<p class=TITOpciones>Maestro de Disponibilidad Horaria </p>

<form name='frm' id='frm' action='<?php echo $PHP_SELF; ?>' method='post'  onSubmit='javascript:return confirmar();'>

<?php
 $body = ''; 
 $npag = 1;
 $orden = 'Codigo'; 
 $buscam = '';
 $torder = 'ASC'; 
if (isset($_GET['pagina'])){
    $npag = $_GET['pagina'];
    $orden = $_GET['orden'];
    $buscam = $_GET['buscam'];
}elseif(isset($_POST['pagina'])){
    $npag = $_POST['pagina'];
    $orden = $_POST['orden'];
    $buscam = $_POST['buscam'];
}
if (isset($_POST['cboTOrden'])){
    $torder = $_POST['cboTOrden'];
} ?>
<SCRIPT ID=clientEventHandlersJS LANGUAGE=javascript>

function cmdAdicionar_onclick() {
    self.location.href="disponibilidad_horaria.php?codigo=&pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>";
}
function cmdModificar_onclick() {
    var rpta=Registro();
    if (rpta != '' ) {
    self.location.href="disponibilidad_horaria.php?codigo=" + rpta + "&pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>";
    }
}

</SCRIPT>
<TABLE class='sinborde' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    <TR>
        <TD >
            <INPUT type='button' value='Adicionar' id='cmdAdicionar' name='cmdAdicionar' class='button' LANGUAGE=javascript onclick='return cmdAdicionar_onclick()' style='width:80px;'>
            <INPUT type='button' value='Modificar' id='cmdModificar' name='cmdModificar' class='button' LANGUAGE=javascript onclick='return cmdModificar_onclick()' style='width:80px;'>
        </TD>
    </TR>
</TABLE>

<?php
$objr = new MyGrilla;
// Parametros de la clase
$objr->setDriver_Coneccion(db_name());
$objr->setUrl_Coneccion(db_host());
$objr->setUser(db_user());
$objr->setPwd(db_pass());
$objr->setOrder($orden);
$objr->setFindm($buscam);
//$objr->setNoSeleccionable(true);
$objr->setFont(CabeceraGrilla());
$objr->setFormatoBto('class=boton');
$objr->setFormaTabla("class=table style='width:100%' cellspacing='1' cellpadding='0' border='0'");
$objr->setFormaCabecera("class=Cabecera");
$objr->setFormaTCabecera("class=Table");
$objr->setFormaItems("class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F5F5E2'");
$objr->setTOrder($torder);
$from =  "disponibilidad_horas ";
$objr->setFrom($from);
$where = '';
$objr->setWhere($where);
$objr->setSize(15);
$objr->setUrl($PHP_SELF);
$objr->setPage($npag);
$objr->setMultipleSeleccion(false);
    $Alias[0] = "root";
    $Alias[1]= "Codigo";
    $Alias[2]= "Identificador";
    $Alias[3]= "Descripcion";
    $Alias[4]= "Activo";
    $Campos[0] = "dh_codigo";
    $Campos[1] = "dh_codigo";
    $Campos[2] = "dh_identificador";
    $Campos[3] = "dh_descripcion";
    $Campos[4] = "case when dh_activo=1 then 'Si' else 'No' end";
    $objr->setAlias($Alias);
    $objr->setCampos($Campos);
    $body = $objr->Construir();
    //echo $objr->getmssql();
    $objr = null;
    echo $body;
    echo "<br> ";
    echo Menu('../../index.php');    ?>

</form>
</BODY>
</HTML>