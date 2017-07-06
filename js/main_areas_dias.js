$(document).ready(function(){
    PooMA.jsGetListadoAreas('L');
});
PooMA={
pathController:'controller_areas_dias.php',
salir:function(){
	window.location.href='../menu.php';
},jsGetListadoAreas:function(p){
    
    var sms=p=="L" ? "Cargando Registros" : "Guardando registro";
    
    $.ajax({
        url:PooMA.pathController,
        type:'post',
        dataType:'json',
        data:{
            action:'load_areas'
        },
        beforeSend:function(){ 
            $.blockUI({
                    message: sms,
                    css: {
                        width : '25%',
                        padding : '3px 0px 3px 3px',
                        background : '#fbfbfb url("css/img/loading.gif") no-repeat 5px 5px',
                        border : '3px solid #99BBE8',
                        opacity : 0.9,
                        color : '15428B',
                        font : 'normal 13px tahoma, arial, helvetica, sans-serif'
                    } 
            });
            
        },
        success:function(result){
            $.unblockUI();
            PooMA.jsViewTableGetListadoAreas(result.data,result.status);
        }
    });
    
},jsViewTableGetListadoAreas:function(data,status){
    var newHtml='<br/>';
        newHtml+='<table class="HeadTable" cellpadding="0" cellspacing="1" style="border-collapse:collapse;" border="0" class="display" id="table_eventos" width="100%">';
        newHtml+='<thead>';
        newHtml+='<tr style="height:20px;color:#000000;font-weight:bold;cursor:pointer;">';
            newHtml+='<th  align=center width="40">N°</th>';
            newHtml+='<th  align=center width="50">Codigo</th>';
            newHtml+='<th  align=center width="400">Descripcion</th>';
            newHtml+='<th  align=center width="200">Dias Habilitados</th>';
            newHtml+='<th  align=center width="90">Estado</th>';
        newHtml+='</tr>';
        newHtml+='</thead>';
        newHtml+='<tbody>';
        var cont=1;
            
        if(status==1){
            $('#message_busqueda').hide(1000);
            $.each(data,function(key,obj){
                newHtml+='<tr class="CA_DataTD" onMouseout=this.style.backgroundColor=\'\' onMouseover=this.style.backgroundColor=\'#F4F4F4\'>\n';
                    newHtml+='<td>'+cont+'</td>';
                    newHtml+='<td nowrap="nowrap"> '+obj['area_codigo']+'</td>';
                    newHtml+='<td align="left">'+obj['area_descripcion']+'</td>';
                    newHtml+='<td align="center"><input type="text" name="'+obj['area_codigo']+'" id="'+obj['area_codigo']+'" size="7" style="height:18px;font-size:9px;text-align=center;" onblur="PooMA.actualizaDia(\''+obj['area_codigo']+'\',this,\''+obj['diasvalidaciongap']+'\');" value="'+obj["diasvalidaciongap"]+'"/> dias</td>';
                    newHtml+='<td align="center">'+obj['estado']+'<input type="hidden" name="_'+obj['area_codigo']+'" id="_'+obj['area_codigo']+'" value="'+obj["diasvalidaciongap"]+'"/></td>';
                    
                newHtml+='</tr>';
                cont++;
                                
            });
        }else{
            newHtml+='<tr>';
            newHtml+='<th align=center colspan="5" style="padding-bottom:5px;padding-top:5px;background-color:#FFFFFF;">No hay areas activas</th>';//imagen
            newHtml+='</tr>';
            $('#message_busqueda').information('No hay datos');
        }
            
            newHtml+='</tbody>';
            newHtml+='</table>';
            $('#lst_eventos').empty().append(newHtml);
            oTable = $('#table_eventos').dataTable( {
                    "sPaginationType": "full_numbers",
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bJQueryUI": true,
                    "bAutoWidth": true,
                    "sDom": '<"wrapper"plfi>,rt<"bottom"<"clear">'
            });
    },
    actualizaDia:function(area_codigo,dias,dias_ant){
        var dias_anter=$("#_"+area_codigo).val();
        if(parseInt(dias.value)>=0){
            if(dias.value!=dias_anter){
                $.ajax({
                    url:PooMA.pathController,
                    type:'post',
                    dataType:'json',
                    data:{
                        action:'actualiza_dia',                              
                        area_codigo:area_codigo,
                        dias:dias.value
                    },
                    beforeSend:function(){
                    },
                    success:function(result){
                        if(result.status==1){
                            //PooMA.jsGetListadoAreas('A');
                            $.blockUI({
                                message: "Cargando Registros",
                                css: {
                                    width : '25%',
                                    padding : '3px 0px 3px 3px',
                                    background : '#fbfbfb url("css/img/loading.gif") no-repeat 5px 5px',
                                    border : '3px solid #99BBE8',
                                    opacity : 0.9,
                                    color : '15428B',
                                    font : 'normal 13px tahoma, arial, helvetica, sans-serif'
                                } 
                            });
                            setTimeout($.unblockUI, 900);
                            $("#_"+area_codigo).attr({
                                'value':dias.value
                            });
                            
                        }else{
                            alert('Error en la actualización de dias');
                        }
                    }
                });
            }
        }else{
            alert('Los dias tienen que ser mayores o iguales a 0');
            $("#"+dias.id).focus();
            $("#"+dias.id).select();
        }
    },
    levanta_ventana:function(){
        var cadena ="dias_todos.php";
        var vwin =window.open(cadena, "ver", "width=350px, height=140px, toolbar=no, resizable=yes, menubar=no, scrollbars=yes");
        vwin.focus();
    }
    
}