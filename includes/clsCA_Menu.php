<?php
require_once(PathIncludes() . "mantenimiento.php");
//require_once("../../includes/mantenimiento.php");

class CA_Menu extends mantenimiento{
  var $menu_codigo = "0";
  var $empleado_codigo = "0";
  var $pagina_codigo = "0";
  var $menu_codigo_padre = "0";
  var $menu_descripcion = "";
  var $menu_orden = "0";
  var $menu_url = "";
  var $menu_query="";
  var $menu_anchor = "";
  var $menu_target = "";

    var $filter;
    var $alineacion="";
    var $myWidth="";
    var $myHeight="";
    var $myLeft="";
    var $myTop="";
    var $myImagen="";
	var $myStyle="";
    var $myColorFondo="";
    var $myColorSeleccion;
	var $myFont;

function Addnew(){
	$rpta="OK";
    $cn = $this->getMyConexionADO();
    //$cn->debug = true;
	//$rpta=$this->conectarme_ado();
	if($cn){
		 //-- obtener codigo de menu
		$sSql = "SELECT isnull(max(menu_codigo), 0) + 1 as maximo FROM ca_menu ";
		$rs= $cn->Execute($sSql);
		$this->menu_codigo = $rs->fields[0];

		if ($this->menu_codigo_padre==0){ //orden de primer nivel
                $sSql= "SELECT case when max(menu_orden)!=0 then max(menu_orden)+1 else 1 end as orden ";
                $sSql .=" FROM ca_menu ";
                $sSql .=" WHERE menu_codigo_padre is null ";
            }else{
                $sSql= "SELECT case when max(menu_orden)!=0 then max(menu_orden)+1 else 1 end as orden ";
                $sSql .=" FROM ca_menu ";
                $sSql .=" WHERE menu_codigo_padre =" . $this->menu_codigo_padre;
         }

		 $rs = $cn->Execute($sSql);
         $this->menu_orden = $rs->fields[0];
        // insertar forma 2 puede que este fuertemente tipado la tabla
        $sSql = "INSERT INTO ca_menu";
		$sSql .= " (menu_codigo,pagina_codigo,menu_codigo_padre,menu_descripcion,menu_orden,menu_url,menu_query,menu_anchor,menu_target) ";
		$sSql .= " VALUES (".$this->menu_codigo.", ".$this->pagina_codigo;

        if ($this->menu_codigo_padre==0){
          $sSql.= ",null";
        }else{
          $sSql.= ",".$this->menu_codigo_padre;
        }

        $sSql.= ",'".$this->menu_descripcion."',".$this->menu_orden;

		if ($this->menu_url==null){
          $sSql.= ",null";
        }else{
          $sSql.= ",".$this->menu_url;
        }
        //$sSql.= ",".$this->menu_query.",".$this->menu_anchor.",".$this->menu_target.")";
        if ($this->menu_query==null){
          $sSql.= ",null";
        }else{
          $sSql.= ",".$this->menu_query;
        }

        if ($this->menu_anchor==null){
          $sSql.= ",null";
        }else{
          $sSql.= ",".$this->menu_anchor;
        }

        if ($this->menu_target==null){
          $sSql.= ",null";
        }else{
          $sSql.= ",".$this->menu_target;
        }
        $sSql.= ")";

        $rs = $cn->Execute($sSql);

        if($rs==false) $rpta="Error al Insertar registro";
	}
	return $rpta;
}

function Update(){
	$sRpta="";
	//$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
    //$cn->debug=true;
	if($cn){
        $params = array($this->pagina_codigo,
                        $this->menu_descripcion,
                        //$this->menu_query,
                        //$this->menu_anchor,
                        //$this->menu_target,
                        $this->menu_codigo);

        $sSql = "UPDATE ca_menu ";
		$sSql .= " SET pagina_codigo = ? ";
		$sSql .= "     ,menu_descripcion = ? ";
		$sSql .= "     ,menu_query = " . "'". $this->menu_query ."'";
		$sSql .= "     ,menu_anchor= " . "'". $this->menu_anchor ."'";
		$sSql .= "     ,menu_target= " . "'". $this->menu_target ."'";
		$sSql .= " WHERE menu_codigo = ? ";

        $rs = $cn->Execute($sSql, $params);
        if ($rs==false) $sRpta="Error al Actualizar registro";
	}
	return $sRpta;
}

function Query(){
	$rpta="OK";
	//$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
	if($cn){
		$params = array($this->menu_codigo);
        $ssql = "SELECT menu_codigo,pagina_codigo,menu_codigo_padre,menu_descripcion,menu_orden,menu_url,menu_query,menu_anchor,menu_target ";
        $ssql .=" FROM ca_menu ";
        $ssql .=" WHERE menu_codigo= ?";// . $this->menu_codigo;
        $rs = $cn->Execute($ssql, $params);
		if ($rs->RecordCount()>0){
			$this->menu_codigo = $rs->fields[0];
			$this->pagina_codigo= $rs->fields[1];
            $this->menu_codigo_padre= $rs->fields[2];
			$this->menu_descripcion= $rs->fields[3];
			$this->menu_orden= $rs->fields[4];
			$this->menu_url= $rs->fields[5];
			$this->menu_query= $rs->fields[6];
			$this->menu_anchor= $rs->fields[7];
			$this->menu_target= $rs->fields[8];
	  }else{
		   $rpta='No Existe Registro de Menu: ' . $this->menu_codigo;
	  }
    }
	return $rpta;
  }

  function Delete(){
	$rpta="";
	$cadena="";
	//$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
	if($cn){
        $params = array($this->menu_codigo);
		$ssql = "DELETE FROM ca_menu ";
        $ssql .=  " WHERE menu_codigo=?";
        $rs = $cn->Execute($ssql, $params);
	}
	return $rpta;
  }

function Pintar($menu_codigo,$i,$nombre){
	$rpta="";
	$padre=0;
	//$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
	if($cn){
		 //-- obtener codigo de menu
		$ssql="SELECT m.menu_codigo as codigo, menu_descripcion as nombre ";
		$ssql .=" FROM ca_menu m inner join ca_paginas p on p.pagina_codigo = m.pagina_codigo ";
		$ssql .=" WHERE m.menu_codigo_padre= " . $menu_codigo;
		$ssql .=" ORDER BY m.menu_orden";
		$rs= $cn->Execute($ssql);

        $ssql  = "SELECT m.* FROM ca_menu m ";
	    $ssql .= " WHERE menu_codigo_padre= " . $menu_codigo;
        //$rst= $this->cnnado->Execute($ssql);
        $rst= $cn->Execute($ssql);


        if (!$rst->EOF) $padre=1;

		$cadena = $this->menu($menu_codigo,$nombre,$i,$padre);
        $i++;

	   if(!$rs->EOF) {
	   while(!$rs->EOF){
           $cadena .=$this->Pintar($rs->fields[0],$i,$rs->fields[1]);
           $rs->movenext();
	     }
       }else{
            $cadena .="";
        }
	}
	return $cadena;
}

function menu($codigo,$nombre,$i,$padre){
    $cad="";

    $cad = "<tr onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#d6e7f3'>\n";
    $cad .=        "<td class='FieldCaptionTD'>\n";
    $cad .=        "&nbsp;&nbsp;" . $this->Space($i) . "<font size=11px'>" . $nombre . "</font></td>\n";
    $cad .=        "<td class='DataTD' align='center' width='10%'>\n";
    $cad .=        "<IMG onclick='return Actualizar(" . $codigo . ")' title='Actualizar registro' width='17' height='15' src='../images/bookmark-off.gif' id='bookmark' hspace='' vspace='1' style='cursor:hand' onmouseout =\"return animar(this,'off')\" onmouseover=\"return animar(this,'on')\" >\n";
    $cad .=        "</td>\n";
    $cad .=        "<td class='DataTD' align='center' width='10%'>\n";
    $cad .=        "<IMG onclick='return Agregar(" . $codigo . ")' title='Insertar submenu' src='../images/newfile-off.gif' id='newfile' width='17' height='15' hspace='' vspace='1' style='cursor:hand' onmouseout =\"return animar(this,'off')\" onmouseover=\"return animar(this,'on')\">\n";
    $cad .=        "</td>\n";
    $cad .=        "<td class='DataTD' align='center' width='10%'>\n";
    $cad .=        "<IMG onclick='return Eliminar(" . $codigo . "," . $padre . ")' title='Eliminar registro' src='../images/trash-off.gif' id='trash' width='17' height='15' hspace='' vspace='1' style='cursor:hand' onmouseout =\"return animar(this,'off')\" onmouseover=\"return animar(this,'on')\">\n";
    $cad .=        "</td>\n";
    $cad .=        "<td class='DataTD' align='center' width='10%'>\n";
    $cad .=        "<IMG onclick='return Subir(" . $codigo . ")' title='Subir Orden' src='../images/scroll-up-off.gif' id='scroll-up' width='17' height='15' hspace='' style='cursor:hand' vspace='1' onmouseout =\"return animar(this,'off')\" onmouseover=\"return animar(this,'on')\">\n";
    $cad .=        "</td>\n";
    $cad .=        "<td class='DataTD' align='center' width='10%'>\n";
    $cad .=        "<IMG onclick='return Bajar(" . $codigo . ")' title='Bajar Orden' src='../images/scroll-down-off.gif' id='scroll-down' width='17' height='15' hspace='' style='cursor:hand' vspace='1' onmouseout =\"return animar(this,'off')\" onmouseover=\"return animar(this,'on')\">\n";
    $cad .=        "</td>\n";
    $cad .=        "</tr>\n";
    return $cad;
}

function Space($cantidad){
    $strRpta = "";
    for ($a=1; $a<=$cantidad; $a++){
        $strRpta .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    }
    return $strRpta;
 }

function Menu_mostrar(){
    $rpta="";
	$cadena="";
    //$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
    if ($cn){
	$ssql="SELECT m.menu_codigo, m.menu_descripcion as nombre ";
    $ssql .= " FROM ca_menu m inner join ca_paginas p on p.pagina_codigo = m.pagina_codigo ";
    $ssql .= " WHERE m.menu_codigo_padre is null  ";
    $ssql .= " order by m.menu_orden";

    $rst = $cn->Execute($ssql);
    //$rst= $this->cnnado->Execute($ssql);
	  if(!$rst->EOF) {
	    while(!$rst->EOF){
           $cadena .=$this->Pintar($rst->fields[0],'',$rst->fields[1]);
           $rst->movenext();
	     }
       }else{
        $cadena .="<tr><td align='center' colspan='5' height='25px'><font color='#333350'><b>No hay Menú definido</b></font></td></tr>";
       }

    }
    return $cadena;
}

function Up(){
    $rpta="";
	$cad="";
	$cadena='';
	$orden=0;
    //$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
    if ($cn){
	$ssql="SELECT menu_codigo_padre, menu_orden from ca_menu where menu_codigo = " . $this->menu_codigo;
	$rst = $cn->Execute($ssql);
    //$rst= $this->cnnado->Execute($ssql);
	  if(!$rst->EOF) {
        if ($rst->fields[1] > 1){
            $orden = $rst->fields[1];

			if ($rst->fields[0]==null){
                $cad = "SELECT menu_codigo, menu_orden from ca_menu where menu_codigo_padre is null and menu_orden < " . $rst->fields[1] . " order by menu_orden desc ";
            }else{
                $cad = "SELECT menu_codigo, menu_orden from ca_menu where menu_codigo_padre  = " . $rst->fields[0] . " and menu_orden < " . $rst->fields[1]  . " order by menu_orden desc";
             }

            //$rs= $this->cnnado->Execute($cad);
            $rs = $cn->Execute($cad);
            if (!$rs->EOF){
			    //$cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
//			    $cmd->ActiveConnection = $this->cnnado;
                $orden_old = $rs->fields[1] ;
                $cad = "UPDATE ca_menu set menu_orden = " . $orden_old ;
                $cad .=" WHERE menu_codigo=" . $this->menu_codigo;
                $rs2 = $cn->Execute($cad);

                $cad = "UPDATE ca_menu set menu_orden = " . $orden ;
                $cad .=" WHERE menu_codigo=" . $rs->fields[0];
                $rs3 = $cn->Execute($cad);
                }
          }
       }


    }
    return $cadena;
}


function Down(){
    $rpta="";
	$cad="";
	$cadena='';
	$orden=0;
    //$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
    if ($cn){
    	$ssql="SELECT menu_codigo_padre, menu_orden FROM ca_menu where menu_codigo = " . $this->menu_codigo;
    	$rst= $cn->Execute($ssql);
        if(!$rst->EOF) {
            $orden = $rst->fields[1];
            if ($rst->fields[0]==null){
                $cad = "SELECT menu_codigo, menu_orden FROM ca_menu WHERE menu_codigo_padre is null and menu_orden > " . $rst->fields[1] . " ORDER BY menu_orden asc ";
            }else{
                $cad = "SELECT menu_codigo, menu_orden FROM ca_menu WHERE menu_codigo_padre  = " . $rst->fields[0] . " and menu_orden > " . $rst->fields[1]  . " ORDER BY menu_orden asc";
            }

            $rs= $cn->Execute($cad);
            if (!$rs->EOF){
                $orden_old = $rs->fields[1] ;
                $cad = "UPDATE ca_menu set menu_orden = " . $orden_old ;
                $cad .=" WHERE menu_codigo=" . $this->menu_codigo;
                $rs2= $cn->Execute($cad);

                $cad = "UPDATE ca_menu set menu_orden = " . $orden ;
                $cad .=" WHERE menu_codigo=" . $rs->fields[0];
                $rs3= $cn->Execute($cad);
            }
        }
    }
    return $cadena;
}

function Menu_horizontal(){
    $cad="";
    $jscript="";
	$query="";
	$anchor="";
	$target="";
	$myWidth=100;
	$x=0;
	$y=0;
	$myHeight=20;
	$cadena="";
	$iPadre=1;
    $swm=1;
	$sw=1;

    $jscript = "<script language = javascript>\n";
    $jscript .= " var arr=new Array();\n";
    $jscript .= " var ancho=" . $this->myWidth . ";\n";
    $jscript .= " var puntox=0;\n";
    $jscript .=  " \n";
    $jscript .=  " function activar (codigo, accion){\n";
    $jscript .=  "     if (accion==1){\n";
    $jscript .=  "         codigo.style.backgroundColor= '" . $this->myColorSeleccion . "';\n";
    $jscript .=  "         codigo.style.cursor= 'hand';\n";
    $jscript .=  "     }else{\n";
    $jscript .=  "         codigo.style.backgroundColor= '" . $this->myColorFondo . "';\n";
    $jscript .=  "     }\n";
    $jscript .=  " }\n";

    $jscript .=  " function mostrar (obj){\n";
    $jscript .=  "  var arr = obj.id;\n";
    $jscript .=  "  var xleft =obj.style.left;\n";
    $jscript .=  "  var xtop =obj.style.top;\n";

    $jscript .=  "      xrr = arr.split('_');\n";
    $jscript .=  "      if (document.all.item(xrr[0] + '_a' ) == null) return;\n";

    $jscript .=  "      var xi = window.event.x - 1;\n";
    $jscript .=  "      var xii = window.event.y - 1;\n";

    $jscript .=  "      document.all.item(xrr[0] + '_a' ).style.left = xi + 'px';\n";
    $jscript .=  "      document.all.item(xrr[0] + '_a' ).style.top =  xii + 'px';\n";
    $jscript .=  "      document.all.item(xrr[0] + '_a' ).style.visibility='visible';\n";
    $jscript .=  "      puntox = xi;\n";
    $jscript .=  "      return;\n";
    $jscript .=  "  }\n";

    $jscript .=  " function ocultar_previos(){\n";
    $jscript .=  "     for (i=1; i<=len_arr; i++){\n";
    $jscript .=  "         if (document.all.item(arr[i] + '_a' ) != null) {\n";
    $jscript .=  "             document.all.item(arr[i] + '_a' ).style.visibility='hidden';\n";
    $jscript .=  "         }\n";
    $jscript .=  "     }\n";

    $jscript .=  " }\n";

    $jscript .=  " function oculta (obj){\n";
    $jscript .=  " var arr = obj.id;\n";
    $jscript .=  "  var xleft =obj.style.left;\n";
    $jscript .=  "  var xtop =obj.style.top;\n";

    $jscript .=  "      xrr = arr.split('_');\n";

    $jscript .=  "      var s = xleft.indexOf('px');\n";
    $jscript .=  "      xl = (xleft.substr(0,s) *1) + 5 ;\n";
    $jscript .=  "      w = window.event.x;\n";
    $jscript .=  "      xr = window.event.x + ancho;\n";
    $jscript .=  "      wt = window.event.y;\n";
    $jscript .=  "      var ss = xtop.indexOf('px');\n";
    $jscript .=  "      xt = (xtop.substr(0,ss) * 1)+ 2;\n";
    $jscript .=  "      if ( xl > w  || xt > wt){\n";
    $jscript .=  "         document.all.item(xrr[0] + '_a' ).style.visibility='hidden';\n";
    $jscript .=  "      }\n";
    $jscript .=  "      if ( (w + ancho) > xr-1 && xr > (puntox + ancho*2) ){\n";
    $jscript .=  "         document.all.item(xrr[0] + '_a' ).style.visibility='hidden';\n";
    $jscript .=  "      }\n";
    $jscript .=  "      return true;\n";
    $jscript .=  "  }\n";


    //------- obtener solo los jefes --------------
     $ssql =" SELECT m.menu_codigo, m.menu_descripcion, m.pagina_codigo,p.pagina_url, m.menu_orden, ";
	 $ssql .=" case when m.menu_codigo_padre is null then 0 else m.menu_codigo_padre end as menu_codigo_padre, ";
	 $ssql .=" case when m.menu_url is null then 0 else m.menu_url end as menu_url, ";
	 $ssql .=" m.menu_query,m.menu_anchor,m.menu_target, case when j.Jefe is null then 0 else j.Jefe end as jefe ";
	 $ssql .=" FROM ca_menu m ";
	 $ssql .=" inner join ca_paginas p on m.pagina_codigo = p.pagina_codigo ";
	 $ssql .=" inner join ca_pagina_rol pr on p.pagina_codigo = pr.pagina_codigo and pr.pagina_rol_activo=1 ";
	 $ssql .=" inner join ca_empleado_rol er on pr.rol_codigo = er.rol_codigo and er.empleado_rol_activo=1 ";
	 $ssql .=" inner join  ";
	 $ssql .=" (select menu_codigo_padre as jefe from vCA_menu_hijos where empleado_codigo =" . $this->empleado_codigo . ") j ON  ";
	 $ssql .=" m.menu_codigo = j.jefe ";
	 $ssql .=" where er.empleado_codigo = " . $this->empleado_codigo;
	 $ssql .=" group by ";
	 $ssql .=" m.menu_codigo, m.menu_descripcion, m.pagina_codigo,p.pagina_url, m.menu_orden, ";
	 $ssql .=" menu_codigo_padre,menu_url,m.menu_query,m.menu_anchor,m.menu_target,jefe ";
	 $ssql .=" union ";
	 $ssql .=" select mn.menu_codigo, mn.menu_descripcion, mn.pagina_codigo, mn.pagina_url, mn.menu_orden, ";
	 $ssql .=" mn.menu_codigo_padre, mn.menu_url, ";
     $ssql .=" mn.menu_query, mn.menu_anchor, mn.menu_target, mn.jefe ";
	 $ssql .=" from vCA_menu_hijos mn ";
	 $ssql .=" where mn.empleado_codigo =" . $this->empleado_codigo;
     $ssql .=" order by 6,5 ";

    $cn =$this->getMyConexionADO();
    // $cn->debug = true;
    if($cn){
      $rs= $cn->Execute($ssql);
	  if(!$rs->EOF) {
            while($swm=1){
                $iPadre =  $rs->fields[5];
                if ( $iPadre==0 ){ //-- padres son horizontales
                   $cadena = $cadena . "<DIV id='0_a' STYLE='POSITION: absolute; LEFT: " . $this->myLeft . "px; TOP: " . $this->myTop . "px;'>\n";
				   //echo $cadena;
				   if ($rs->fields[5] == $iPadre){
                        $sw=1;

                        while ($sw=1){
                            //-- Crear division horizontal
                            $cadena = $cadena . "  <DIV id='" . $rs->fields[0] . "_" . $rs->fields[4] . "' onmouseover='activar(this,1);' onmouseout='activar(this,0);' onclick='ocultar_previos(); mostrar(this); ";
							if ($rs->fields[2]!=0)
                                $cadena = $cadena . $rs->fields[3];

                            if ($rs->fields[6]!=0)
                                $cadena = $cadena . $rs->fields[6];

                            $cadena = $cadena . " ' STYLE='" . $this->myStyle . " WIDTH:" . $this->myWidth . "px; POSITION: absolute; LEFT: " . ($x * $this->myWidth) . "px; TOP: 10px; HEIGHT:" . $this->myHeight . "px; BACKGROUND-color:" . $this->myColorFondo . ";' align=center><strong><font " . $this->myFont . " >&nbsp;" . $rs->fields[1] . "&nbsp;</font></strong></DIV>\n";
							$x++;

							$rs->movenext();
                            if ($rs->EOF) {
								$sw=0;
                                break;
                            }

                            if ($rs->fields[5] != $iPadre ) break;
                        }
                   	  }
                    	$cadena = $cadena . "</DIV>\n";
                    	if ($sw==0) break;
                    	$iPadre = $rs->fields[5];

                	}

                //--- hijos, son verticales
                if ($iPadre != 0 ){
                    $this->myLeft = 30;
                    $this->myTop = 20;
                    $cadena .=  "<DIV id='" . $rs->fields[5] . "_a" . "' onmouseout='oculta(this);' STYLE='LEFT: " . $this->myLeft . "px; POSITION: absolute; TOP: " . $this->myTop . "px; HEIGHT: 30px; visibility:hidden;'>\n";
                    //echo $cadena;
					$sw=1;
                    while ($sw=1){
                        if ($rs->fields[5] != $iPadre) break;
                        $cadena .= "  <DIV id='" . $rs->fields[0] . "_" . ($rs->fields[4]) . "' onmouseover='activar(this,1);' onmouseout='activar(this,0);' onclick='";
                        if ($rs->fields[10] !=0) //--mostrar menu
                            $cadena .= " mostrar(this); ";

                        if ($rs->fields[7]!='') $query=$rs->fields[7];
                        if ($rs->fields[3]!="ninguno" && strtoupper($rs->fields[9])!= "_BLANK"){
						if($rs->fields[0]==26)
						  $cadena .= "validar_asistencia();' ";
                        else
						    $cadena .= "self.location.href=\"" . $rs->fields[3] . $query . "\"'; ";
						}
                        if ($rs->fields[3] !="ninguno" && strtoupper($rs->fields[9])=="_BLANK"){
		                if($rs->fields[0]==26)
						   $cadena .= "validar_asistencia();' ";
						 else      //window.open()
                            $cadena .= "self.location.href=\"" . $rs->fields[3] . $query . "\"'); ";
                         }
                        $cadena .= "' STYLE='" . $this->myStyle . " WIDTH:" . $this->myWidth . "px; HEIGHT:" . $this->myHeight . "px; BACKGROUND-COLOR:" . $this->myColorFondo . "' align=center><font " . $this->myFont . ">&nbsp;" . $rs->fields[1] . "&nbsp;</font>";
                        if ($rs->fields[10]!="0") // mostrar imagen
                            $cadena .= "\n<img  src='images/arrows.gif' border=0 >\n";

                        $cadena .= "</DIV>\n";
						$rs->movenext();
                        if ($rs->EOF) {
                            $sw=0;
                            break;
                        }
                    }
				     //echo $jscript;
                    $cadena .= "</DIV>\n";
                    if ($sw==1) $iPadre = $rs->fields[5];
                }

                if ($sw==0) break;
                //if (!rs.next()) swm=false;
				//$rs->movenext();

            }

        }


        $rst= $cn->Execute($ssql);
        $x = 0;
        $y = 0;
        $sw=1;
		if(!$rst->EOF){
        while (!$rst->EOF){
            $iPadre = $rst->fields[5];
            if ($iPadre != 0 ){
                $y++;
                $jscript .= " arr[" . $y . "] =" . $rst->fields[5] . ";\n";
                $sw=1;
			  if(!$rst->EOF){
                while(!$rst->EOF){
                    if ($rst->fields[5]!= $iPadre) {
                        //sw=false;
                       break;
                    }
                    $y++;
                    $jscript = $jscript . " arr[" . $y . "] =" . $rst->fields[0] . ";\n";
					$rst->movenext();
                }
                $sw=0;
			  }

            }
            if ($sw==0) break;
	     $rst->movenext();
        }
	}

        $jscript .= " var len_arr =" . $y . ";\n";
        $jscript .= "</script> \n"; //Cerrar javscript
        //echo $jscript;
        $cad=$jscript . $cadena;
    }
    return $cad;
}
function Construir(){
    $cad="";
    if (strtoupper($this->alineacion)=="H"){
        $cad= $this->Menu_horizontal();
    }

   if (strtoupper($this->alineacion)=="V"){
        $cad= $this->Menu_vertical();
    }

    return $cad;
}

function Menu_vertical(){

    $datosMenu="";

    $ssql = "
            with menuAtento
            as
            (
	            select	menu_codigo,menu_codigo_padre,menu_orden
	            from	CA_Menu
	            where	pagina_codigo=0 and menu_codigo_padre is null and menu_activo=1
	            union all
	            select	m.menu_codigo,m.menu_codigo_padre,m.menu_orden+1
	            from	CA_Menu m
	            join menuAtento p on m.menu_codigo_padre=p.menu_codigo
	            where m.menu_codigo_padre is not null and m.menu_activo=1
            )
            select  distinct m.menu_codigo, m.menu_descripcion, m.pagina_codigo,p.pagina_url, m.menu_orden,
             case when m.menu_codigo_padre is null then 0 else m.menu_codigo_padre end as menu_codigo_padre,
             case when m.menu_url is null then 0 else m.menu_url end as menu_url,
             m.menu_query,m.menu_anchor,m.menu_target,a.menu_orden as new_orden
            from menuAtento a
            inner join CA_Menu m on a.menu_codigo=m.menu_codigo
            inner join ca_paginas p on m.pagina_codigo = p.pagina_codigo
            inner join ca_pagina_rol pr on p.pagina_codigo = pr.pagina_codigo and pr.pagina_rol_activo=1
            inner join ca_empleado_rol er on pr.rol_codigo = er.rol_codigo and er.empleado_rol_activo=1
            where er.Empleado_codigo = ?
            order by 6,5,11
            ";

    $params = array($this->empleado_codigo);
    $cn =$this->getMyConexionADO();
    if($cn){

        $dsPadre = array();
        $dsHijo = array();

        $rs = $cn->Execute($ssql,$params);

        while (!$rs->EOF){

            if ($rs->fields[5]*1==0){
                $dsPadre[] = $rs->fields;
            }else{
                $dsHijo[] = $rs->fields;
            }
            $rs->movenext();
        }

        //construir menu por cada padre
        $datosMenu = '<ul id="menu-atento" class="page-sidebar-menu  page-header-fixed" data-auto-scroll data-slide-speed="200" style="padding-top: 20px">';
        foreach ($dsPadre as $rowPadre)
        {
            $tieneHijos = $this->ConstruirHijos($dsHijo,$rowPadre[0]);
            if ($tieneHijos!="")
            {
                $datosMenu .= '<li class="nav-item">
                                    <a href="javascript:;" class="nav-link nav-toggle">
                                        <i class="icon-folder"></i>
                                        <span class="title">'.$rowPadre[1].'</span>
                                        <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu" role="menu">';
                $datosMenu .= $tieneHijos;
                $datosMenu .= "</ul>";
                $datosMenu .= "</li>";
            }else
            {
                $datosMenu .= '<li class="nav-item">
                                    <a href="#" ruta="'.$rowPadre[3].'"  etiqueta="'.$rowPadre[1].'" class="nav-link" id="tab_'.$rowPadre[0].'">
                                        <i class="icon-bar-chart"></i>'.$rowPadre[1].'
                                    </a>
                                </li>';
            }

        }

        $datosMenu .= "</ul>";
    }


    return $datosMenu;
}


function ConstruirHijos($data,$codPadre){
    $datosHijo = "";
    foreach ($data as $rowHijo)
    {
    	if($rowHijo[5]==$codPadre){
            //si es papa
            if($rowHijo[2]*1==0){

                $tieneHijos = $this->ConstruirHijos($data,$rowHijo[0]);
                if ($tieneHijos!="")
                {
                    $datosHijo .= '<li class="nav-item">
                                    <a href="javascript:;" class="nav-link nav-toggle">
                                        <i class="icon-folder"></i>
                                        <span class="title">'.$rowHijo[1].'</span>
                                        <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu" role="menu">';
                    $datosHijo .= $tieneHijos;
                    $datosHijo .= "</ul>";
                    $datosHijo .= "</li>";
                }

            }else{
                //si es hijo
                $datosHijo .= '<li class="nav-item">
                                    <a href="#" ruta="'.$rowHijo[3].'"  etiqueta="'.$rowHijo[1].'" class="nav-link" id="tab_'.$rowHijo[0].'">
                                        <i class="icon-bar-chart"></i>'.$rowHijo[1].'
                                    </a>
                                </li>';
            }

        }

    }

    return $datosHijo;



}



}
?>

