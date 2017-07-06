<?php header("Expires: 0");
session_start();
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../includes/clsCA_Turnos_Empleado.php");
require_once("../../Includes/clswfm_requerimiento.php"); 
set_time_limit(30000); 

    $empleado_codigo_registro=$_SESSION["empleado_codigo"];
    if (isset($_POST['hddsemana'])){
        $semana=$_POST['hddsemana'];
    }
    
    if (isset($_POST['hddanio'])){
        $anio=$_POST['hddanio'];
    }
        
    if (isset($_POST['hddServicio'])){
        $servicio=$_POST['hddServicio'];
    }
    
    if (isset($_POST['hddArea'])){
        $area=$_POST['hddArea'];
    }
    
    $carga_codigo=date("YmdHis");

    $o = new ca_turnos_empleado();
    $o->setMyUrl(db_host());
    $o->setMyUser(db_user());
    $o->setMyPwd(db_pass());
    $o->setMyDBName(db_name());

    $w = new wfm_requerimiento();
    $w->setMyUrl(db_host());
    $w->setMyUser(db_user());
    $w->setMyPwd(db_pass());
    $w->setMyDBName(db_name());

    /*$w->anio=$anio;
    $w->semana=$semana;
    $w->cod_campana= $servicio;
    $w->eliminar_carga_actual();
    */
?>
<script language='javascript'>
function volver(){
	self.location.href='subir_archivoWFM.php';	
}

function exportarExcel() {
	var i;
	var j;
	var mycell;
	var objXL = new ActiveXObject("Excel.Application");
	var objWB = objXL.Workbooks.Add();
	var objWS = objWB.ActiveSheet;
	for (i=0; i < document.getElementById("listado").rows.length; i++)
	{
	    for (j=0; j < document.getElementById("listado").rows(i).cells.length; j++)
	    {
	        mycell = document.getElementById("listado").rows(i).cells(j)
	        objWS.Cells(i+1,j+1).Value = mycell.innerText;
	    }
	}
	objWS.Range("A1", "Z1").EntireColumn.AutoFit();
	objXL.Visible = true;
}

</script>
<?php
if(!isset($_POST['Upload'])) { 
	include("subir_archivoWFM.php?te_semana=".$semana."&te_anio=".$anio); //Si no hemos cargado, mostramos el formulario 
	
}else{
	//--Agregado 20091012 para guardar el archivo en directorio cargas
	$temp_file = $_FILES['pix']['tmp_name']; 
	$na_origen = $_FILES['pix']['name'];
	$aux = explode(".",$na_origen);
	$na_carga = substr($aux[0],0,20)."_".$carga_codigo."_".$empleado_codigo_registro.".".$aux[1];
	$destination_two = '..\\gestionturnos\\cargas'."\\".$na_carga;
	copy($temp_file,$destination_two);
	$o->empleado_codigo_registro = $empleado_codigo_registro;
	$o->nombre_archivo_origen = $na_origen;
	$o->nombre_archivo_carga = $na_carga;
	$o->carga_codigo = $carga_codigo;
	$o->Addnew_Turno_Carga();
	

	//--fin
	/*if($area == "") { //Si no se ha cargado o es más grande de lo especificado
	    echo $semana;  
		echo $anio; 
	    echo "<b>Ingrese el area.<br>";   
		include("subir_archivoWFM.php?te_semana=".$semana."&te_anio=".$anio); //mostramos de nuevo el formulario   
		exit();   
	}
	if($servicio == "") { //Si no se ha cargado o es más grande de lo especificado   
	    echo "<b>Ingrese el servicio.<br>";   
		include("subir_archivoWFM.php?te_semana=".$semana."&te_anio=".$anio."&area=".$area); //mostramos de nuevo el formulario   
		exit();   
	}*/

	
	if($_FILES['pix']['tmp_name'] == "none") { //Si no se ha cargado o es más grande de lo especificado   
            echo "<b>El archivo no se ha podido cargar. El tamaño debe ser menor que 5000Kb.<br>";
            include("subir_archivoWFM.php"); //mostramos de nuevo el formulario   
            exit();   
	}
	
	if($area == ""){
            echo "<b><font color=#800000>Ingrese el area.</font><br>";
            include("subir_archivoWFM.php");  
            exit();   
	}else{
		
            if($servicio == "") {
                echo "<b><font color=#800000>Ingrese el servicio.</font><br>";   
                include("subir_archivoWFM.php");    
                exit();   
            }else {
		
                if(!ereg("text/plain",$_FILES['pix']['type'])) { //si no es un archivo de texto   
                        echo "<b><font color=#800000>El archivo no tiene el formato deseado. Intenta otro archivo.</b></font><br>";   
                        include("subir_archivoWFM.php");   
                        exit();   
                }else{ //Si todo está bien, es una imagen y se cargó completa   

                    $w->anio=$anio;
                    $w->semana=$semana;
                    $w->cod_campana= $servicio;
                    $w->eliminar_carga_actual();

                    $_FILES['pix']['name']='tmp.txt'; // nuevo nombre
                    $destination = $_FILES['pix']['name'];
                    $temp_file = $_FILES['pix']['tmp_name'];   
                    move_uploaded_file($temp_file,$destination); //Lo movemos de la ubicación temporal a la que queramos.
                    //echo "<p><b>Archivo cargado satisfa:</b>{$_FILES['pix']['name']}({$_FILES['pix']['size']})</p>";
                    $archivo = file("tmp.txt"); 
                    $lineas = count($archivo); 
		
                        for($i=0; $i < $lineas ; $i++){
                                //echo "archivo" .$archivo[$i]."<br>";
                                $columna = explode('	',$archivo[$i]);
                                //$columna = split('\t',$archivo[$i]);
                                //echo $columna;

                                if ($columna[0]==null || $columna[1]==null || $columna[2]==null || $columna[3]==null || 
                                    $columna[4]==null || $columna[5]==null || $columna[6]==null || $columna[7]==null ||
                                        $columna[8]==null ){
                                        echo "<b><font color=#800000>El archivo contiene caracteres extraños. Verifique el contenido de archivo.</font></b><br>";   
                                        include("subir_archivoWFM.php");   
                                        exit();   
                                }

                                $inicio =$columna[0];
                                $inicio_total = split(':' ,$inicio);
                                $hora_inicio = $inicio_total[0];
                                $minuto_inicio = $inicio_total[1];
                                $fin = $columna[1];
                                $fin_total=explode(':' ,$fin);
                                $hora_fin=$fin_total[0];
                                $minuto_fin=$fin_total[1];
                                $rq_dia1 = $columna[2];
                                $rq_dia2 = $columna[3];
                                $rq_dia3 = $columna[4];
                                $rq_dia4 = $columna[5];
                                $rq_dia5 = $columna[6];
                                $rq_dia6 = $columna[7];
                                $rq_dia7 = $columna[8];


                                $w->semana=$semana;
                                $w->anio=$anio;
                                $w->cod_campana=$servicio;
                                $w->area=$area;
                                $w->hora_inicio=$hora_inicio;
                                $w->minuto_inicio=$minuto_inicio;
                                $w->hora_fin=$hora_fin;
                                $w->minuto_fin=$minuto_fin;
                                $w->rq_dia1=$rq_dia1;
                                $w->rq_dia2=$rq_dia2;
                                $w->rq_dia3=$rq_dia3;
                                $w->rq_dia4=$rq_dia4;
                                $w->rq_dia5=$rq_dia5;
                                $w->rq_dia6=$rq_dia6;
                                $w->rq_dia7=$rq_dia7;
                                $w->empleado_codigo_registro=$empleado_codigo_registro;
                                $w->carga_codigo=$carga_codigo;
                                //
                                //echo "linea:" .$i;
                                //echo "semana:" . $semana;
                                //echo "anio:" . $anio;
                                //echo "servicio" . $servicio;
                                //echo "hora_inicio: ". $hora_inicio;
                                //echo "minuto_inicio". $minuto_inicio;
                                //echo "hora_fin". $hora_fin;
                                //echo "minuto_fin". $minuto_fin;
                                //echo "dia1".$rq_dia1;
                                //echo "dia12".$rq_dia2;
                                //echo "dia3".$rq_dia3;
                                //echo "dia4".$rq_dia4;
                                //echo "dia5".$rq_dia5;
                                //echo "dia6".$rq_dia6;
                                //echo "dia7" .$rq_dia7;
                                //echo "usuario" . $empleado_codigo_registro;
                                //echo "carga_codigo". $carga_codigo;


                                $w->Addnew();
                        }
                    //include("subir_archivoWFM.php");

                    //echo "<img align=left style='CURSOR: hand' src='../images/left.gif' onclick='volver();' WIDTH='18' HEIGHT='18' title='Retornar'>";
                    //echo "<br>";
                    //echo "<img style='CURSOR: hand' src='../images/contratos/excel_ico.gif' onclick='exportarExcel();' WIDTH='25' HEIGHT='25' alt='Imprimir' title='Exportar Excel'>";
                    //echo "<table class='FormTable' id='listado' width='100%' align='center' border='1' cellPadding='1' cellSpacing='0' >";
                    //echo "	<tr style='background:FFFFFF'>";
                    //echo "    	<td align=center style='width:100px'><b>Dni</td>";
                    //echo "    	<td align=center><b>Descripcion del Error</td>";
                    //echo "	</tr>";
                    //$o->carga_codigo=$carga_codigo;
                    //$o->Query_Turnos_Log();
                    //echo "</table>";
                    //echo "<img align=left style='CURSOR: hand' src='../images/left.gif' onclick='volver();' WIDTH='18' HEIGHT='18' title='Retornar'>";
                    //echo " <script language='javascript'> window.opener.document.forms['frm'].submit();window.close(); </script> ";
                    echo " <script language='javascript'> parent.windowv.document.forms['frm'].submit(); parent.windowv.hide(); </script> ";	
            }   
	}
	}
}   
?> 