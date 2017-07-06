<?php

//require_once(PathIncludes() . "mantenimiento.php");
class gm_cms_dia extends mantenimiento{
var $empleado_codigo='';
var $fecha='';



function verifica_avaya3(){
      
    $rpta="OK";
    $ssql = "";
    $cn=$this->getMyConexionPG();

    $datos_gestion_me = "";
    $datos_gestion_me.= "<br/><table width=95% border=0 cellspacing=0 cellpadding=0 align=center style=font-size:12px >";
    $datos_gestion_me.= "<tr><td colspan='6' class='titulo'>Avaya 3</td></tr></table>"; 
    $datos_gestion_me.= "<table class='FormTABLA' id='idTablagm' width=95% border=0 cellspacing=0 cellpadding=0 align=center style=font-size:12px >";
    $datos_gestion_me .= "<tr align=center>";
    //$datos_gestion_me .= "<td align=left class=Columna>Empleado_Codigo</td>";
    $datos_gestion_me .= "<td align=center class=Columna>Fecha</td>";
    $datos_gestion_me .= "<td align=center class=Columna>Tiempo Hablado<br>(i_acdtime)</td>";
    $datos_gestion_me .= "<td align=center class=Columna>Tiempo Back Office<br>(i_acwtime)</td>";
    $datos_gestion_me .= "<td align=center class=Columna>Tiempo Disponible<br>(ti_availtime)</td>";
    //$datos_gestion_me .= "<td align=center class=Columna>Tiempo conexion auxiliar</td>";
    $datos_gestion_me .= "</tr>";
					
    $ssql = "SELECT empleado_codigo, to_char(fecha,'dd/mm/yyyy' ), row_date, 
'0' || cast (trunc((i_acdtime::numeric(9,2)/3600)::numeric(9,2)) as varchar) || ':' || case when ((((i_acdtime::numeric(9,2)/3600)::numeric(9,2)) - trunc((i_acdtime::numeric(9,2)/3600)::numeric(9,2)))* 60)::int <= 9 then '0' || cast(((((i_acdtime::numeric(9,2)/3600)::numeric(9,2)) - trunc((i_acdtime::numeric(9,2)/3600)::numeric(9,2)))* 60)::int as varchar) else cast(((((i_acdtime::numeric(9,2)/3600)::numeric(9,2)) - trunc((i_acdtime::numeric(9,2)/3600)::numeric(9,2)))* 60)::int as varchar) end   , 
'0' || cast (trunc((i_acwtime::numeric(9,2)/3600)::numeric(9,2)) as varchar) || ':' || case when ((((i_acwtime::numeric(9,2)/3600)::numeric(9,2)) - trunc((i_acwtime::numeric(9,2)/3600)::numeric(9,2)))* 60)::int <= 9 then '0' || cast(((((i_acwtime::numeric(9,2)/3600)::numeric(9,2)) - trunc((i_acwtime::numeric(9,2)/3600)::numeric(9,2)))* 60)::int as varchar) else cast(((((i_acwtime::numeric(9,2)/3600)::numeric(9,2)) - trunc((i_acwtime::numeric(9,2)/3600)::numeric(9,2)))* 60)::int as varchar) end , 
ti_auxtime0, 
'0' || cast (trunc((ti_availtime::numeric(9,2)/3600)::numeric(9,2)) as varchar) || ':' || case when ((((ti_availtime::numeric(9,2)/3600)::numeric(9,2)) - trunc((ti_availtime::numeric(9,2)/3600)::numeric(9,2)))* 60)::int <= 9 then '0' || cast(((((ti_availtime::numeric(9,2)/3600)::numeric(9,2)) - trunc((ti_availtime::numeric(9,2)/3600)::numeric(9,2)))* 60)::int as varchar) else cast(((((ti_availtime::numeric(9,2)/3600)::numeric(9,2)) - trunc((ti_availtime::numeric(9,2)/3600)::numeric(9,2)))* 60)::int as varchar) end , 
'0' || cast (trunc((ti_stafftime::numeric(9,2)/3600)::numeric(9,2)) as varchar) || ':' || case when ((((ti_stafftime::numeric(9,2)/3600)::numeric(9,2)) - trunc((ti_stafftime::numeric(9,2)/3600)::numeric(9,2)))* 60)::int <= 9 then '0' || cast(((((ti_stafftime::numeric(9,2)/3600)::numeric(9,2)) - trunc((ti_stafftime::numeric(9,2)/3600)::numeric(9,2)))* 60)::int as varchar) else cast(((((ti_stafftime::numeric(9,2)/3600)::numeric(9,2)) - trunc((ti_stafftime::numeric(9,2)/3600)::numeric(9,2)))* 60)::int as varchar) end , 
acdtime, acwtime, i_availtime
FROM cms_dia.hagent_dia
where empleado_codigo=" .$this->empleado_codigo . " and to_char(fecha,'dd/mm/yyyy')='" . $this->fecha . "'" ;

    $rs=$cn->Execute($ssql);
    if ($rs->RecordCount()>0){
        $datos_gestion_me.="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
        $datos_gestion_me.= " <td class='fila' align=center >" .$rs->fields[1]. "</td>";
        $datos_gestion_me.= " <td class='fila' align=center>" .$rs->fields[3] . "</td>";
        $datos_gestion_me.= " <td class='fila' align=center>" .$rs->fields[4] . "</td>";
        $datos_gestion_me.= " <td class='fila' align=center>" .$rs->fields[6] . "</td>";
        //$datos_gestion_me.= " <td class='fila' align=center>" .$rs[7] . "</td>";
        //$datos_gestion_me.= " <td class='fila' align=center>" .$rs[5] . "</td>";
        $datos_gestion_me.= "</tr>";
        $datos_gestion_me.= "</table><br/>";
        //return $rs[0];
        echo $datos_gestion_me;
    }
    
 }


}
?>
