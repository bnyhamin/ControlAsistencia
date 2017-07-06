<?php

require_once("mantenimiento.php");
class MyGrilla extends mantenimiento{//default public
private $order;
private $findm = '';
private $TOrder;
private $from = '';
private $where = '';
private $size;
private $elSize;//combo cantidad filas por pagina
private $url = '';
private $page;
private $multipleSeleccion=false;
private $alias = array();
private $campos = array();
private $formatter = array();//mcortezcc@
private $distinct = '';
public $rs=NULL;
private $mssql;
private $nRecords;
private $str='';
private $cadenaDivs='';
private $cadenaBotones='';
private $strColumnGrid='';
private $noSeleccionable=false;
private $opcionesComboPaginador=false;
private $urlStore='';
public static $query='select';
private $driver_Coneccion = "";
private $url_Coneccion = "";
private $user = "";
private $pwd = "";
private $load_msg='Cargando datos, por favor espere ...';
private $evento_fila = 0;

public $alias_campo_especial = "";
public $valor_campo_especial = "";

public $visibleBotones = true;
public $checkall = false;

public $groupby = "";

function __construct() {//setear valores por default
    $this->size = 10;
    $this->page = 1;
    $this->TOrder = 'ASC';
    $this->urlStore='../controllers/grid_controller.php';
    $this->opcionesComboPaginador=false;
}

public function getGroupby(){
    return $this->groupby;
}

public function setGroupby($valor){
    $this->groupby = $valor;
}

public function getCheckall(){
    return $this->checkall;
}

public function setCheckAll($valor){
    $this->checkall = $valor;
}

public function __set($var, $valor) {
    if (property_exists(__CLASS__, $var)) $this->$var = $valor;
    //echo $var;
    else echo "No existe el atributo $var.";
}

public function __get($var){
    if (property_exists(__CLASS__, $var)) return $this->$var;
    return NULL;
}
public function getmssql(){
    return $this->mssql;
}
public function setmssql($mssql){
    $this->mssql = $mssql;
}

public function setOrder($order) {
    $this->order = $order;
}

/* AGREGADO POR: BANNY SOLANO                   */
/* ORDENA POR UN CAMPO TENIENDO EN CUENTA       */
/* EL TIPO DE DATO Y NO EL ALIAS                */
/* 27/08/2015                                   */
public function verificaOrder($order) {
    if($this->alias_campo_especial == $order){
        if($this->valor_campo_especial != ""){
            $this->order = $this->valor_campo_especial;
        }else{
            $this->order = $order;
        }
    }else{
        $this->order = $order;
    }
}

public function setCampoEspecial($campo){
    $this->alias_campo_especial = $campo;
}

public function getCampoEspecial(){
    return $this->alias_campo_especial;
}

public function setFindm($findm) {
    $this->findm = $findm;
}
public function setTOrder($TOrder) {
    $this->TOrder = $TOrder;
}
public function setSize($size) {
    $this->size = $size;
}
public function setUrl($url) {
    $this->url = $url;
}
public function setPage($page) {
    $this->page = $page;
}
public function setMultipleSeleccion($valor) {
    $this->multipleSeleccion = $valor;
}
public function setAlias($alias) {
    $this->alias = $alias;
}
public function setCampos($campos) {
    $this->campos = $campos;
}
public function setFrom($from) {
    $this->from = $from;
}
public function setWhere($where) {
    $this->where = $where;
}
function setNoSeleccionable($noSeleccionable) {
    $this->noSeleccionable = $noSeleccionable;
  }
//metodos no usados
public function setFont($font) {
}
public function setFormatoBto($formatoBto) {
}
public function setFormaTabla($formaTabla) {
}
public function setFormaCabecera($formaCabecera) {
}
public function setFormaTCabecera($formaTCabecera) {
}
public function setFormaItems($formaItems) {
}
public function getDriver_Coneccion() {
    return $this->driver_Coneccion;
  }
public function setDriver_Coneccion($driver_Coneccion) {
    $this->driver_Coneccion = $driver_Coneccion;
}
public function getUrl_Coneccion() {
    return $this->url_Coneccion;
  }
public function setUrl_Coneccion($url_Coneccion) {
    $this->url_Coneccion = $url_Coneccion;
}
public function getUser() {
    return $this->user;
  }
public function setUser($user) {
    $this->user = $user;
}
public function getPwd() {
    return $this->pwd;
  }
public function setPwd($pwd) {
    $this->pwd = $pwd;
}
public function setEvento_Fila($evento_fila) {
    $this->evento_fila = $evento_fila;
}
public function setDistinct($distinct) {
    $this->distinct = $distinct;
}

public function setVisibleBotones($visibleBotones) {
    $this->visibleBotones = $visibleBotones;
}

private function getNombreUrl($esdirectorio){
    $nombre_archivo='';
    $nombre_archivo=parse_url(($esdirectorio?dirname($this->url):$this->url),PHP_URL_PATH);
    if ( strpos($nombre_archivo, '/') !== FALSE )
            if($esdirectorio)
                $nombre_archivo = array_pop(explode('/', $nombre_archivo));
            else
                $nombre_archivo = preg_replace('/\.php$/', '' ,array_pop(explode('/', $nombre_archivo)));
    return $nombre_archivo;
}

public function Construir() {

    $this->elSize=$this->size;//combo cantidad filas por pagina
    $this->generaBotones();
    $this->capturarPocisionMouse($this->order);
    //$this->generaQuery();
    $this->generaGrilla($this->order);
    $this->obtenerValorFiltrosPocision();
    //echo $this->str;
    return $this->str;
}
/*
private function generaQuery(){
   $_SESSION['from_datagrid_'.$this->getNombreUrl(true).'_'.$this->getNombreUrl(false)]=$this->from;
   $_SESSION['where_datagrid_'.$this->getNombreUrl(true).'_'.$this->getNombreUrl(false)]=$this->where;
}
*/

private function capturarPocisionMouse(){
    $this->str = "\n<script type=\"text/javascript\">\n";

    $this->str .= "\n$(document).ready(function(){\n";



        $this->str .= "PooGrilla.init();\n";

		$this->str .= "PooGrilla.setear_Filtros();\n";
        if($this->visibleBotones==true){ //inicio de modificacion por binfantes de poner visible los filtros
        foreach ($this->alias as $key => $value) {
            if($value!='root'){

                $this->str .= "$('div.datagrid-cell.datagrid-cell-c1-column".$value." span').on(\"mouseover\", function(e){\n";
                    $this->str .= "posX=e.pageX;\n";
                    $this->str .= "posY=e.pageY;\n";
                    $this->str .= "PooGrilla.aparece(\"".$value."\");\n";
                $this->str .= "});\n";

                $this->str .= "$('#txtb_".$value."').keyup(function(e){\n";
                    $this->str .= "var tecla=(document.all)?e.keyCode:e.which;\n";
                    $this->str .= "if(tecla==13){\n";
                        $this->str .= "if(this.value!=''){\n";
                            $this->str .="$('#espaginacion').val('0');\n";//pointered
                            $this->str .="$('#pagina').val('1');\n";//pointered2
                            $this->str .= "PooGrilla.criterio();\n";
                            $this->str .= "PooGrilla.recarga();\n";
                        $this->str .= "}\n";
                    $this->str .= "}\n";
                $this->str .= "});\n";

                $this->str .= "$('div.datagrid-cell.datagrid-cell-c1-column".$value." span').on(\"mouseout\", function(e){\n";
                    $this->str .= "PooGrilla.desaparece(\"".$value."\");\n";
                $this->str .= "});\n";

                $this->str .= "$('div.datagrid-cell.datagrid-cell-c1-column".$value." span').on(\"click\", function(e){\n";
                    $this->str .= "PooGrilla.cleanCabecera(\"".$value."\");\n";
                    $this->str .= "e.target.innerHTML='[".$value."]';\n";
                    $this->str .= "$('#order').val(\"".$value."\");\n";

                    //$this->str .= "$('#alias_campo_especial').val(\"".$value."\");\n";
                    //$this->str .= "$('#valor_campo_especial').val(\"".$value."\");\n";

                    $this->str .= "$('#espaginacion').val('0');\n";//pointered
                    $this->str .= "$('#pagina').val('1');\n";//pointered2
                    $this->str .= "PooGrilla.recarga(); \n";



                $this->str .= "} );\n";
            }
        }

    } //fin de modificacion de binfantes de poner visible los filtros



    $this->str .= "});\n";


    $this->str .= "PooGrilla={\n";
        $this->str .= "init:function(){\n";
}

private function generaGrilla($order){
    $cadena_campos='';
    $cadena_alias='';
    $this->str.="$('#div_datagrid').datagrid({\n";
		$this->str.="url:'".$this->urlStore."',\n";
		$this->str.="pagination:true,\n";
		$this->str.="pageSize:".$this->size.",\n";
		$this->str.="pageNumber:".$this->page.",\n";
                if($this->opcionesComboPaginador)
                    $this->str.="pageList:[".$this->elSize.",".(intval($this->elSize)*2).",".(intval($this->elSize)*3)."],//opciones del combo\n";
                else
                    $this->str.="pageList:[".$this->size."],//opciones del combo\n";

		$this->str.="striped:true,\n";//true-lineas punteadas
        if(!$this->getCheckall()) $this->str.="singleSelect:true,\n";//true

                $this->str.="width: '100%',\n";//mcortezc
                //$this->str.="nowrap:false,\n";//false-true prohibe saltos de linea automaticos - @
                //$this->str.="fit:true,\n";//true mostrar barra desplazamiento horizontal @
                $this->str.="fitColumns:true,\n";//true mostrar barra desplazamiento horizontal
                //$this->str.="autoRowHeight:true,\n";//@

                //$this->str.="scrollbarSize:36,\n";
                //$this->str.="fitColumns:true,\n";//true mostrar barra desplazamiento horizontal

            $this->str.="loadMsg:'".$this->load_msg."',\n";
            $this->str.="queryParams: {\n";
			$this->str.="opcion: '1',\n";
			$this->str.="TOrder:$('#cboTOrden').val(),\n";
			$this->str.="order:$('#order').val(),\n";

            $this->str.="alias_campo_especial:$('#alias_campo_especial').val(),\n";
            $this->str.="valor_campo_especial:$('#valor_campo_especial').val(),\n";

			$this->str.="findm:$('#buscam').val(),\n";
            $this->str.="hdd_campos:$('#hdd_campos').val(),\n";
            $this->str.="hdd_alias:$('#hdd_alias').val(),\n";
            $this->str.="hdd_espaginacion:'1',\n";//pointered
			$this->str.="hdd_evento_fila:$('#hdd_evento_fila').val(),\n";//pointered
            $this->str.="hdd_ses_directorio:$('#hdd_ses_directorio').val(),\n";

            $this->str.="hdd_ses_by:$('#hdd_ses_by').val(),\n";

            $this->str.="hdd_multiple_seleccion:$('#hdd_multiple_seleccion').val(),\n";
            $this->str.="hdd_no_seleccionable:$('#hdd_no_seleccionable').val(),\n";
            $this->str.="hdd_ses_pagina:$('#hdd_ses_pagina').val(),\n";
			$this->str.="pagina:$('#pagina').val()\n";
		$this->str.="},\n";
	$this->strColumnGrid="";
    $this->strColumnGrid.="columns:[[\n";
    if(!$this->noSeleccionable) $this->strColumnGrid.="{field:'columncheck',title:'#'},\n";

    if($this->getCheckall()){ //is esta activado se habilita la seleccion multiple, por defecto esta desactivado
        $this->strColumnGrid.="{field:'columnnum', title:'<input type=checkbox name=checkall id=checkall  />'},\n";
    }else{
        $this->strColumnGrid.="{field:'columnnum',title:'N°'},\n";
    }



    //$this->strColumnGrid.="{field:'columnnum', checkbox:true},\n";

    $valor="";
    $cantidad=count($this->alias);
    $this->cadenaDivs='<div id="contened">';

    $formatter="";

    foreach ($this->alias as $key => $value) {

        if($cantidad==$key+1){
            $cadena_campos.=$this->campos[$key];
            $cadena_alias.=$value;
        }
        else{

            $cadena_campos.=$this->campos[$key]."||";
            $cadena_alias.=$value."||";
        }
        //mcortezcc@
        if(count($this->formatter)>0){
            $formatter="";
            if(array_key_exists($key, $this->formatter)){
                $formatter=",formatter: function(value,row,index){ ";
                $formatter.=" var enlace = '';var valorenlace='';";
                $formatter.=" if(typeof value!='undefined'){ enlace=value.split('|')[0];valorenlace=value.split('|')[1]; }";
                $formatter.=" return ";
                $formatter.=strstr($this->formatter[$key],"@",true)."'+enlace+'";
                $formatter.=strstr(strstr($this->formatter[$key],"@"),"|",true)."'+valorenlace+'";
                $formatter.=substr(strstr(strstr($this->formatter[$key],"@"),"|"),1,strlen(strstr(strstr($this->formatter[$key],"@"),"|"))-1);
                $formatter.="}";
            }
        }

        if($value!='root'){
            if($value==$order) $valor = "[" . $value . "]";
            else  $valor = $value;

            if($cantidad==$key+1) $this->strColumnGrid.="{field:'column".$value."',title:'".$valor."'".$formatter."}\n";//mcortezcc@
            else $this->strColumnGrid.="{field:'column".$value."',title:'".$valor."'".$formatter."},\n";//mcortezcc@

            $this->cadenaDivs.="<div class=textfiltros style=\"position:absolute;";
            $this->cadenaDivs.="visibility:hidden;background-color:#C6D5FD;padding-bottom: 10px;";
            $this->cadenaDivs.="z-index:1000000; padding-left: 10px; padding-right: 10px;";
            $this->cadenaDivs.="padding-top: 0px\" id=\"div_".$value."\">";
                $this->cadenaDivs.=$value."<br/>";
                $this->cadenaDivs.="<input type=text style=\"width:80;\" id=\"txtb_".$value."\" ";
                $this->cadenaDivs.="name=\"txtb_".$value."\" value=\"\" onblur=\"PooGrilla.criterio(this);\"/>";
                $this->cadenaDivs.="</div>";
        }
    }

	$this->cadenaDivs.='<input type="hidden" name="hdd_evento_fila" id="hdd_evento_fila" value="'.$this->evento_fila.'"/>';//E
    $this->cadenaDivs.='<input type="hidden" name="buscam" id="buscam" value="'.$this->findm.'"/>';
    $this->cadenaDivs.='<input type="hidden" name="order" id="order" value="'.$this->order.'"/>';

    $this->cadenaDivs.='<input type="hidden" name="alias_campo_especial" id="alias_campo_especial" value="'.$this->alias_campo_especial.'"/>';
    $this->cadenaDivs.='<input type="hidden" name="valor_campo_especial" id="valor_campo_especial" value="'.$this->valor_campo_especial.'"/>';

	$this->cadenaDivs.='<input type="hidden" name="pagina" id="pagina" value="'.$this->page.'"/>';
    $this->cadenaDivs.='<input type="hidden" name="espaginacion" id="espaginacion" value="1"/>';//pointered
	$this->cadenaDivs.='<input type="hidden" name="pagsize" id="pagsize" value="'.$this->size.'"/>';
    $this->cadenaDivs.='<input type="hidden" name="hdd_campos" id="hdd_campos" value="'.$cadena_campos.'"/>';
    $this->cadenaDivs.='<input type="hidden" name="hdd_alias" id="hdd_alias" value="'.$cadena_alias.'"/>';

    $this->cadenaDivs.='<input type="hidden" name="hdd_ses_directorio" id="hdd_ses_directorio" value="'.(trim($this->from)=="" ? $this->from : base64_encode($this->from)).'"/>';
	$this->cadenaDivs.='<input type="hidden" name="hdd_ses_pagina" id="hdd_ses_pagina" value="'.(trim($this->where)=="" ? $this->where : base64_encode($this->where)).'"/>';

    $this->cadenaDivs.='<input type="hidden" name="hdd_ses_by" id="hdd_ses_by" value="'.(trim($this->getGroupby())=="" ? $this->getGroupby() : base64_encode($this->getGroupby())).'"/>';

    $this->cadenaDivs.='<input type="hidden" name="hdd_multiple_seleccion" id="hdd_multiple_seleccion" value="'.($this->multipleSeleccion?1:0).'"/>';
    $this->cadenaDivs.='<input type="hidden" name="hdd_no_seleccionable" id="hdd_no_seleccionable" value="'.($this->noSeleccionable?1:0).'"/>';

    $this->cadenaDivs.='</div>';
    $this->cadenaDivs.='<div id="div_datagrid"></div>';

    $this->strColumnGrid.="]]\n";
	$this->str.=$this->strColumnGrid;
        $this->str.=",onLoadSuccess: function (data) {\n";//mcortezc
		$this->str.="$('#div_datagrid').datagrid('resize');\n";
	$this->str.="}\n";

    $this->str.="});\n";


	$this->str .= "$('#checkall').on(\"click\",
                function(e){\n
                    cambiarcheck();
                    ";
    $this->str .= "} );\n";

    $this->str.="var pager = $('#div_datagrid').datagrid('getPager');\n";



    $this->str.="pager.pagination({\n";
		$this->str.="displayMsg:'Mostrando {from} a {to} de {total} registros'\n";
		$this->str.=",beforePageText:'Pag'\n";
		$this->str.=",afterPageText:'de {pages}'\n";
		$this->str.=",onSelectPage:function(pageNumber, pageSize){\n";
			$this->str.="var opts = $('#div_datagrid').datagrid('options');\n";
			$this->str.="$('#pagina').val(pageNumber);\n";
			$this->str.="$('#pagsize').val(pageSize);\n";
                        $this->str.="$('#espaginacion').val('1');\n";//pointered
			$this->str.="opts.pageSize = pageSize;\n";
			$this->str.="PooGrilla.recarga();\n";
			$this->str.="pager.pagination('refresh',{\n";
				$this->str.="pageNumber:pageNumber,\n";
				$this->str.="pageSize:pageSize\n";
			$this->str.="});\n";
		$this->str.="}\n";
    $this->str.="});\n";

    $this->str.="},\n";
    echo $this->cadenaDivs;

}

private function obtenerValorFiltrosPocision(){

    $this->str .=" criterio:function(id){\n ";
    $this->str .=" var cadena='';\n ";
    $this->str .=" var cadenam='';\n ";
    foreach ($this->alias as $key => $value) {
        if($value!='root'){
            $this->str .= "var t=document.getElementById('txtb_".$value."');\n";
            $this->str .= "if (t.value != 0){\n";
            $this->str .= "  cadena += '".$value.":' + t.value + ' <-> ';\n";
            $this->str .= "  cadenam += '".$value."|' + t.value + '^';\n";
            $this->str .= "}\n";
        }
    }
    $this->str .= "var b1=document.getElementById('fbuscar');\n";
    $this->str .= "b1.innerHTML=cadena;\n";
    $this->str .= "var b2=document.getElementById('buscam');\n";
    $this->str .= "b2.value=cadenam;\n";

    $this->str .= "},\n";
    $this->str .= "Ocultar_Div:function(id){\n";
    $this->str .= "var o=document.getElementById('div_' + id);\n";
    $this->str .= "o.style.visibility='hidden';\n";
    $this->str .= "},\n";
	$this->str .= "setear_Filtros:function(){\n";
		$this->str .= "if($('#buscam').val()!=''){\n";

			$this->str .= "var valorm=$('#buscam').val();\n";
			$this->str .= "valorm=valorm.substring(0,valorm.length - 1);\n";
			$this->str .= "var av=valorm.split('^');\n";
			$this->str .= "for (var k=0;k<av.length;k++){\n";
				$this->str .= "var valorm2=av[k];\n";
				$this->str .= "var av2=valorm2.split('|');\n";
				$this->str .= "var ob=document.getElementById('txtb_' + av2[0]);\n";
				$this->str .= "ob.value=av2[1];\n";
			$this->str .= "}\n";
			$this->str .= "PooGrilla.criterio();\n";
		$this->str .= "}\n";
    $this->str .= "},\n";
	$this->str .= "Registro:function(){\n";
			$this->str .= "valor='';\n";
			$this->str .= "var r=document.getElementsByTagName('input');\n";
			$this->str .= "for (var i=0; i<r.length; i++) {\n";
				$this->str .= "var o=r[i];\n";
				$this->str .= "if (o.id=='rdo') {\n";
					$this->str .= "if (o.checked) {\n";
						$this->str .= "valor= o.value;\n";
					$this->str .= "}\n";
				$this->str .= "}\n";
			$this->str .= "}\n";
			$this->str .= "if ( valor =='' ){\n";
			$this->str .= "alert('Seleccione Registro');\n";
			$this->str .= "}\n";
			$this->str .= "return valor;\n";
	$this->str .= "},\n";

        $this->str .= "SeleccionMultiple:function(){\n";
        $this->str .= "var i;\n";
        $this->str .= "var valor='';\n";
        $this->str .= "var r=document.getElementsByTagName('input');\n";
        $this->str .= "for(i=0; i< r.length; i++ ){\n";
        $this->str .= "var o=r[i];\n";
        $this->str .= "var oo = o.id;\n";
        $this->str .= "if (oo.substring(0,3)=='chk') {\n";
        $this->str .= "if (o.checked) {\n";
        $this->str .= "if (valor==''){\n";
        $this->str .= "valor = oo.substring(3);\n";
        $this->str .= "}else{\n";
        $this->str .= "valor = valor + ',' + oo.substring(3);\n";
        $this->str .= "}\n";
        $this->str .= "}\n";
        $this->str .= "}\n";
        $this->str .= "}\n";
        $this->str .= "if ( valor =='' ){\n";
        $this->str .= "alert('Seleccione Registro');\n";
        $this->str .= "}\n";
        $this->str .= "return valor;\n";
        $this->str .= "},\n";


	$this->str .= "cleanCabecera:function(colSel){\n";
            $this->str .= "var cadena='';var pos_ini=0;var pos_fin=0;var columna='';\n";
                $this->str .= "$('div[class^=datagrid-cell] > span:first-child').each(function( index ) {\n";
                $this->str .= "cadena=$(this)[0].innerHTML;\n";
                $this->str .= "pos_ini=parseInt(cadena.indexOf('['));\n";
                $this->str .= "pos_fin=parseInt(cadena.indexOf(']'));\n";
                $this->str .= "columna=cadena.substring(pos_ini+1,pos_fin);\n";
                $this->str .= "if(pos_ini!=-1 && pos_fin!=-1){\n";
                    $this->str .= "if(colSel!=columna) $(this)[0].innerHTML=columna;\n";
                $this->str .= "}\n";
            $this->str .= "});\n";
	$this->str .= "}\n";

    $this->str .= ",recarga:function(){\n";

        $this->str .= "$('#div_datagrid').datagrid('load',{\n";
                $this->str .= "opcion: '1',\n";
                $this->str .= "findm: $('#buscam').val(),\n";
                $this->str .= "TOrder:$('#cboTOrden').val(),\n";
                $this->str .= "order:$('#order').val(),\n";

                $this->str .= "alias_campo_especial:$('#alias_campo_especial').val(),\n";
                $this->str .= "valor_campo_especial:$('#valor_campo_especial').val(),\n";


                $this->str .= "hdd_campos:$('#hdd_campos').val(),\n";
                $this->str .= "hdd_alias:$('#hdd_alias').val(),\n";
                $this->str .= "hdd_espaginacion:$('#espaginacion').val(),\n";//pointered
				$this->str .= "hdd_evento_fila:$('#hdd_evento_fila').val(),\n";//pointered
                $this->str .= "hdd_ses_directorio:$('#hdd_ses_directorio').val(),\n";
                $this->str .= "hdd_ses_by:$('#hdd_ses_by').val(),\n";
                $this->str .= "hdd_multiple_seleccion:$('#hdd_multiple_seleccion').val(),\n";
                $this->str .= "hdd_no_seleccionable:$('#hdd_no_seleccionable').val(),\n";
                $this->str .= "hdd_ses_pagina:$('#hdd_ses_pagina').val(),\n";
                $this->str .= "pagina:$('#pagina').val()\n";
        $this->str .= "});\n";

    $this->str .= "},\n";
    $this->str .= "ordenando:function(){\n";//pointered1
    //PooGrilla.recarga()
    $this->str .= "$('#espaginacion').val('0');\n";//pointered
    $this->str .= "$('#pagina').val('1');\n";//pointered2
    $this->str .= "PooGrilla.recarga();\n";//pointered1
    $this->str .= "},\n";
        $this->str .= "desaparece:function(val){\n";
            $this->str .= "$('#div_'+val).css({\n";
                        $this->str .= "'visibility':'hidden'\n";
            $this->str .= "});\n";
        $this->str .= "},\n";
        $this->str .= "aparece:function(val){\n";
                    $this->str .= "$('#div_'+val).css({\n";
                        $this->str .= "'visibility':'visible',\n";
                        $this->str .= "'top':posY+'px',\n";
                        $this->str .= "'left':posX+'px'\n";
                    $this->str .= "});\n";
                    $this->str .= "document.getElementById('txtb_'+val).focus();\n";
                    $this->str .= "document.getElementById('txtb_'+val).select();\n";
                $this->str .= "}\n";
                $this->str .= "}\n";
                $this->str .= "function cambiarcheck(){
                                    if(document.frm.checkall.checked){
                                		checkear_todos_empleados(true);
                                	}else{
                                		checkear_todos_empleados(false);
                                	}
                                }

                                function checkear_todos_empleados(flag){
                                	var r=document.getElementsByTagName('input');
                                	for (var i=0; i< r.length; i++) {
                                		var o=r[i];
                                		var oo=o.id;
                                		if(oo.substring(0,3)=='chk'){
                                			if(o.checked){
                                				o.checked=flag;
                                			}else{
                                				o.checked=flag;
                                			}
                                		}
                                	}
                                }";

                $this->str .= "</script>\n";
}


private function generaBotones(){
    $this->cadenaBotones='';
    if($this->visibleBotones==true){
        $this->cadenaBotones.='<table id="tablecabecera" name="tablecabecera" border="0">';
        $this->cadenaBotones.="<tr>";
        //pointered1
        $this->cadenaBotones.='<td><select  name="cboTOrden" id="cboTOrden" style="WIDTH: 90px" onchange="javascript:PooGrilla.ordenando()">';

        $this->cadenaBotones.='<option value="ASC" '.((strtolower($this->TOrder)==strtolower("ASC"))?'selected':'').'>Ascendente</option>';
        $this->cadenaBotones.='<option value="DESC" '.((strtolower($this->TOrder)==strtolower("DESC"))?'selected':'').'>Descendente</option>';
        $this->cadenaBotones.='</select><td width="5">';
        $this->cadenaBotones.='<input type="hidden"  name="TxtBuscar" id="TxtBuscar" style="WIDTH: 5px" onkeyup="javascript:pulsar(2)"/>';
        $this->cadenaBotones.='<td><div class="buttons" style="text-align:center;" >';
        $this->cadenaBotones.='<label class="personalizado" id="cmdBuscar" name="cmdBuscar" style="font-size:12px; border: 2px solid #CED9E7; color:#000000;" onclick="javascript:PooGrilla.recarga();">';
        $this->cadenaBotones.='<img src="'.URLGap().'/images/zoom.png" alt="Buscar" width="5%" />Buscar';
        $this->cadenaBotones.='</label>';
        $this->cadenaBotones.='</div>';
        $this->cadenaBotones.='<td><b><font id="fbuscar" color=Red></font></b></tr></table>';
    }
    echo $this->cadenaBotones;
}

public function inicio(){

    $this->MyUrl    =$this->getUrl_Coneccion();
    $this->MyUser   =$this->getUser();
    $this->MyPwd    =$this->getPwd();
    $this->MyDBName =$this->getDriver_Coneccion();

    $cn = $this->getMyConexionADO();
    //$cn->debug=true;
    $cadena_filtro='';
    $c = "" ;
    $indice_alias=0;
    $y = 1 ;
    $nRecord = 0;
    $buscar='';
    $Query = "Select " . $this->distinct . " " ;
    $Query2 = "Select " . $this->distinct . " Count(*) as N From ".$this->from;
    foreach ($this->campos as $key => $value) {
        $Query .= $value." as " . $this->alias[$key] . "," ;
    }
    $Query = substr($Query, 0 , strlen($Query) - 1);
    $Query .= " " . "from" . " " . $this->from;
    if($this->findm!=''){
        $cadena_filtro = substr($this->findm, 0 , strlen($this->findm) - 1);
        foreach (explode("^",$cadena_filtro) as $key => $value) {
            $arr_columna_valor = explode("|",$value);
            $indice_alias = $this->buscarEnArreglo($this->alias,$arr_columna_valor[0]);
            if ($indice_alias >= 0) $buscar .=  $this->campos[$indice_alias] . " like '%" . $arr_columna_valor[1] . "%' and ";
            else $buscar .= "No Hallado";
        }
    }
    if ($this->where != '') {
      $Query .=   " where " . $this->where;
      $Query2 .=   " where " . $this->where;
      if (strlen($buscar) > 0) $buscar = " and " . $buscar;
    }else {
      if (strlen($buscar) > 0) $buscar =  " where " . $buscar ;
    }

    if ($buscar != "") $buscar = substr($buscar,0,strlen($buscar) - 4);

    /*agregado por Banny Solano*/
    $groupby= "";
    if($this->getGroupby() != ""){
      $groupby = " group by " . $this->getGroupby();
    }
    /*fin agregado */

    $this->verificaOrder($this->order);
    $Query .= $buscar . $groupby." ORDER BY " . $this->order . " " . $this->TOrder;
    $Query2 .= $buscar.$groupby;
    //colocar try catch
    //echo $Query;exit;
    $this->mssql=$Query;
    $cn->SetFetchMode(ADODB_FETCH_ASSOC);
    $this->rs = $cn->Execute($Query);
    $pos = ($this->size * ($this->page - 1)) + 1;
    $pos = ($pos)-1;
    $ars = $this->rs;

    $ars->Move($pos);
    $x=$pos;
	$cadenaev="";

    $padre=array();
    while(!$ars->EOF){
        $hijo=array();
        $x++;
        if ($y <= $this->size){
            $y++;

            foreach ($this->alias as $key => $value) {
                if($key==0){
                    if($this->multipleSeleccion) $c="<input type=checkbox name=chk" .utf8_encode($ars->fields["".$value.""]) . " id=chk" . utf8_encode($ars->fields["".$value.""]) . " value=1>";
                    if($this->noSeleccionable){
                        $hijo["columncheck"]="";
                        $hijo["columnnum"]=$c.$x;
                    }else{
						//mcortezc
                        if($this->evento_fila == 1){ $cadenaev="onclick=javascript:meto()"; }
                        $hijo["columncheck"]="<input type='radio' id='rdo' name='rdo' ".$cadenaev." value='" . $ars->fields["".$value.""] . "' >";//colocar el evento
						//$hijo["columncheck"]="<input type='radio' id='rdo' name='rdo' value='" . $ars->fields["".$value.""] . "' >";//colocar el evento
                        $hijo["columnnum"]=$c.$x;
                    }

                }else{
                    $hijo["column".$value.""]=utf8_encode($ars->fields["".$value.""]);
                }

            }

        }else{
            break;
        }
        array_push($padre, $hijo);
        $ars->MoveNext();
    }

    $rs2 = $cn->Execute($Query2);
    $nRecord = $rs2->fields["N"];

    $this->nRecords=$nRecord;
    return $padre;
}

private function buscarEnArreglo($arrAlias, $palabra){
    $i = 0;
    $indice = -1;
    foreach ($arrAlias as $key => $value) {
        if (strtolower($value) == strtolower($palabra)){
            $indice = $key;
            break;
        }
    }
    return $indice;
}



}
?>