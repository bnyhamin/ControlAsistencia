$(document).ready(function(){

    $('#txtfecha1_lp,#txtfecha2_lp').datepicker({
        showOn: 'button',
        altFormat: formatDate,
        buttonImage: icoCalendar,
        buttonImageOnly: true	,
        dateFormat: formatDate
    });

    CreaCbo('cboshipper','%','getListShipper','','llamaShipper()');

    $('#btnbuscar').click(jsBuscaAnaqueles);
    $('#imgBtnExpExcel').click(export_excel_listParam);
    $('#cboshipper').change(llamaShipper);
    /*obtener las bovedas y llenar el combo*/
    llamaBoveda();
   

});

llamaShipper=function(){
	CreaCbo('cboservicio','0','ListSrvShipper',$('#cboshipper').val(),'');
	$('#h_shipper').val($('#cboshipper').val());
};


llamaBoveda=function(){

       $.ajax({
            url:pathController,
            dataType:'json',
            data:{
                action:"lista_boveda",
                controller:'Boveda',
                vp_1:$("#h_codsucursal").val(),
                vp_2:$("#h_coduser").val()
            },
            beforeSend:function(){
            },
            success:function(result){
                var newHtml='';
                if(parseInt(result.status)==1){
                    newHtml='<Select id="cbo_boveda" name="cbo_boveda">';
                    newHtml+='<option value="0">SELECCIONE</option>';
                $.each(result.data,function(key, field){
                    newHtml+='<option value="'+$.trim(field["[Expr_1]"])+'">'+$.trim(field["[Expr_2]"])+'</option>';
                });
                    newHtml+='</Select>';

           $('#divcboboveda').empty().html(newHtml);

                }else{ }
            },
            complete:function(){
            }
            });
}

habilita=function(){

    if($("#cbotipoarqueo").val()=='GR'){
         $("#cboshipper").attr("disabled",true);
         $("#cboservicio").attr("disabled",true);
    }else if($("#cbotipoarqueo").val()=='DF'){
        $("#cboshipper").attr("disabled",false);
        $("#cboservicio").attr("disabled",false);
    }

}

CreaCbo=function(nombrecbo,valI,accion,shicodigo,evento){
    $.ajax({
        url:pathController,
        dataType:'json',
        data:{
            action:accion,
            controller:'Boveda',
            shi_codigo:shicodigo
        }, // getListServiciosShipcodigo->age_qry_lstshipper()
        beforeSend:function(){
        },
        success:function(result){
                    var newHtml='';
            if(parseInt(result.status)==1){
                    newHtml='<Select id="'+nombrecbo+'" onchange="'+evento+'" style="width:150px;">';
                    newHtml+='<option value="'+valI+'">TODOS</option>';
                $.each(result.data,function(key, field){
                    newHtml+='<option value="'+$.trim(field["[Expr_1]"])+'">'+$.trim(field["[Expr_2]"])+'</option>';
                });
                    newHtml+='</Select>';

    $('#div_'+nombrecbo).empty().html(newHtml);

            }else{ }

    },
    complete:function(){
    }
    });
}

export_excel_listParam=function(){
var ship= ( $('#h_shipper').val()=='000000' )?'%':$('#h_shipper').val();

var condicion = "?"+
"&pa1="+ ship+
"&pa2="+ $('#cboservicio').val() +
"&pa3="+ $('#txtfecha1_lp').val() +
"&pa4="+ $('#txtfecha2_lp').val();
window.open("rpts/rpt.cli.excel.panel.operativo.php"+condicion,"","width=20,height=20");

};

jsBuscaAnaqueles=function(){

    if($("#cbotipoarqueo").val()=='GR'){
        p_tipoarqueo=$("#cbotipoarqueo").val();
        p_idboveda=$("#cbo_boveda").val();
        p_finicial=$("#txtfecha1_lp").val();
        p_ffinal=$("#txtfecha2_lp").val();

        BuscarAnaqueles(p_idboveda);

    }else if($("#cbotipoarqueo").val()=='DF'){
        p_tipoarqueo=$("#cbotipoarqueo").val();
        p_tipoboveda=$("#cbotipoboveda").val();
        p_shipper=$("#cboshipper").val();
        p_producto=$("#cboservicio").val();
        p_finicial=$("#txtfecha1_lp").val();
        p_ffinal=$("#txtfecha2_lp").val();
        alert("p_tipoarqueo"+p_tipoarqueo+"p_tipoboveda"+p_tipoboveda+"p_shipper"+p_shipper+"p_producto"+p_producto+"p_finicial"+p_finicial+"p_ffinal"+p_ffinal);

    }

    //var fchInC= $('#txtfecha1_lp').val().split("/");
    //var formatoFech1= fchInC[0] + fchInC[1] + fchInC[2];
    //var fchFin= $('#txtfecha2_lp').val().split("/");
    //var formatoFech2= fchFin[0] + fchFin[1] + fchFin[2];

    /*if($('#cboshipper').val()==00){
        alert("Debe selecionar un shipper");
    }else{

    var ship= ( $('#h_shipper').val()=='000000' )?'%':$('#h_shipper').val();*/

    /*$.ajax({
        url:pathController,
        type:'get',
        dataType:'json',
        data:{
        action:'getPanelServicio',
        controller:'Servicios',
        vr_shicod:ship,
        vr_servicio:$('#cboservicio').val(),
        vr_fecini:$('#txtfecha1_lp').val(),
        vr_fecfin:$('#txtfecha2_lp').val()
        },
    beforeSend:function(){
        $.blockUI('CARGANDO LOS DATOS.....');
    },

    success:function(result){

		if(parseInt(result.status)==1){

			$.trim($('#gridPanelOperativo').val());
				if(result.data==-1){
					$('#div_imgbtnExporExc').hide();
				}
				else
				{
					$('#div_imgbtnExporExc').show();
					jsViewTablePanelServicios(result.data,ship,$('#cboservicio').val());
				}
		}
		else
		{
			$('#message').warning('NO HAY DATOS A MOSTRAR');
		}
		$.unBlockUI();
	}
    });*/
//}
};

BuscarAnaqueles=function(vp_idbov){

    if (vp_idbov!="0"){

        $.ajax({
            url:pathController,
            dataType:'json',
            data:{
                action:"lista_anaqueles_x_boveda",
                controller:'Boveda',
                vp_1:vp_idbov
            },
            beforeSend:function(){
                $.blockUI('Cargando Anaqueles para arqueo');
            },
            success:function(result){
                var verifica="";
                var contador=0;

                if(parseInt(result.status)==1){
                var newHtml="";
                $.each(result.data,function(key, field){
                contador++;
                if(contador==1){
                    if ($.trim(field["[Expr_1]"])=="0"){
                        newHtml+=' <li style="Verdana, Arial, Helvetica, sans-serif; font-size: 10px;background-color:#CC0000;color:#FFFFFF;">BOVEDA SIN ANAQUELES</li>';
                        verifica="0";
                    }else{
                        newHtml+=' <li style="Verdana, Arial, Helvetica, sans-serif; font-size: 10px;background-color:#CC0000;color:#FFFFFF;"><a href="javascript:Gabeta('+"'"+$.trim(field["[Expr_1]"])+"'"+","+"'"+$.trim(field["[Expr_3]"])+"'"+","+"'"+$.trim(field["[Expr_4]"])+"'"+","+"'"+$.trim(field["[Expr_5]"])+"'"+')"  >'+$.trim(field["[Expr_5]"])+'</a></li>';
                    }

                }else{
                        newHtml+=' <li style="Verdana, Arial, Helvetica, sans-serif; font-size: 10px;background-color:#CC0000;color:#FFFFFF;"><a href="javascript:Gabeta('+"'"+$.trim(field["[Expr_1]"])+"'"+","+"'"+$.trim(field["[Expr_3]"])+"'"+","+"'"+$.trim(field["[Expr_4]"])+"'"+","+"'"+$.trim(field["[Expr_5]"])+"'"+')"  >'+$.trim(field["[Expr_5]"])+'</a></li>';
                }
                });

                $('#mycarousel').empty().append(newHtml);
                $('#mycarousel').jcarousel();

                }else{ }
                    $.unBlockUI();
                    //$('#selectable').empty().html("");
                    //$('#div_nombre').empty().html("");
            },
            complete:function(){
            }
        })
    }else{
        alert("SELECCIONE BOVEDA");
   }
};



jsViewTablePanelServicios=function(data,shipper,servicio){
    newHtml='';

    newHtml+='<div id="accordionGiftLelo" style="overflow:hidden; width:2100px; height:1500px;">';
    //ESTATICO1
    newHtml+='<div>';

    newHtml+='<table  cellspacing="5"  class="display" id="gridPanelOperativo" style="margin-top:10px;">';
    newHtml+='<thead>';
    newHtml+='<tr style="height:20px;background-color:#000000;color:white;font-weight:bold">';
    newHtml+='<th>##</th>';
    newHtml+='<th>SHIPPER</th>'; // 3
    newHtml+='<th>SERVICIO</th>'; // 4
    newHtml+='<th>SS</th>'; // 5
    newHtml+='</tr>';
    newHtml+='</thead>';

        var cont=1;
	newHtml+='<tbody>';
		$.each(data,function(key,obj){
			if(cont % 2==0){
				newHtml+='<tr id="'+ cont +'" style="background-color:#E1E1E1" align="center" >';
			}
			else{
				newHtml+='<tr id="'+ cont +'" align="center" >';
			}
                        //pasar shipper servicio fecha inicial y fecha final
                        var p_shipper=obj['[Expr_3]'].substring(0,24);
                        var p_servicio=obj['[Expr_4]'].substring(0,24);

                        //24 letras
			newHtml+='<td id="td_'+ cont +'" nowrap="nowrap"> '+'<a href="javascript:detalle_chk(\''+shipper+'\', \''+servicio+'\')" >'+'<img src="css/ico/add.png" border="0" width="12" height="12" ></a> </td>';
			newHtml+='<td  align="left"><a href="#" title="'+obj['[Expr_3]']+'" style="color:#000000; text-decoration:none;" >'+p_shipper+'</a></td>';
			newHtml+='<td  align="left">'+p_servicio+'</td>';
                        newHtml+='<td>'+obj['[Expr_5]']+'</td>';
                        newHtml+='</tr>';
            cont++;
		});
    newHtml+='</tbody>';
    newHtml+='</table>';
    newHtml+='</div>';//end tabla revisar
        //DINAMICO1
        newHtml+='<div class="set">';
	newHtml+='<div class="title" style="margin-top: 18px;"><img src="css/ico/icono_flecha.png" width="14" height="14" id="tooltip1"/></div>'; // 3
        newHtml+='<div class="content">';
        newHtml+='<table cellspacing="5"  class="display" id="gridPanelOperativo1" style="margin-top:0px;">'; // prueba
        newHtml+='<thead>';
	newHtml+='<tr style="height:20px;background-color:#000000;color:white;font-weight:bold">';
	newHtml+='<th>SR</th>'; // 6
	newHtml+='<th>RD</th>'; // 7
        newHtml+='<th>NR</th>'; // 8
        newHtml+='<th>SC</th>'; // 9
        newHtml+='</tr>';
	newHtml+='</thead>';

        cont=1;
	newHtml+='<tbody>';
		$.each(data,function(key,obj){
			if(cont % 2==0){
				newHtml+='<tr id="'+ cont +'" style="background-color:#E1E1E1" align="center"  >';
			}
			else{
				newHtml+='<tr id="'+ cont +'" align="center" >';
			}

			newHtml+='<td >'+obj['[Expr_6]']+'</td>';
			newHtml+='<td class="" >'+obj['[Expr_7]']+'</td>';
                        newHtml+='<td class="" >'+obj['[Expr_8]']+'</td>';
                        newHtml+='<td class="" >'+obj['[Expr_9]']+'</td>';
                        newHtml+='</tr>';

        cont++;
		});

        newHtml+='</tbody>';
        newHtml+='</table>';
        newHtml+='</div>';
        newHtml+='</div>';
    //ESTATICO2
    newHtml+='<div>';
    newHtml+='<table cellspacing="5"  class="display" id="gridPanelOperativo2" style="margin-top: 10px;">'; // prueba
    newHtml+='<thead>';
    newHtml+='<tr style="height:20px;background-color:#000000;color:white;font-weight:bold">';
    newHtml+='<th>AO</th>'; // 10
    newHtml+='</tr>';
    newHtml+='</thead>';
        cont=1;
	newHtml+='<tbody>';
		$.each(data,function(key,obj){
			if(cont % 2==0){
				newHtml+='<tr id="'+ cont +'" style="background-color:#E1E1E1" align="center"  >';
			}
			else{
				newHtml+='<tr id="'+ cont +'" align="center" >';
			}
			newHtml+='<td class="" >'+obj['[Expr_10]']+'</td>';
                        newHtml+='</tr>';
        cont++;
		});
    newHtml+='</tbody>';
    newHtml+='</table>';
    newHtml+='</div>';

            //DINAMICO2
            newHtml+='<div class="set">';
            newHtml+='<div class="title" style="margin-top: 18px;"><img src="css/ico/icono_flecha.png" width="14" height="14" id="tooltip2" /></div>'; // 3
            newHtml+='<div class="content">';

            newHtml+='<table cellspacing="5"  class="display" id="gridPanelOperativo1">'; // prueba
            newHtml+='<thead>';
            newHtml+='<tr style="height:20px;background-color:#000000;color:white;font-weight:bold">';
            newHtml+='<th>DD</th>'; // 11
            newHtml+='<th>AD</th>'; // 12
            newHtml+='<th>SIN AD</th>'; // 13
            newHtml+='<th>LOCAL</th>'; // 14
            newHtml+='</tr>';
            newHtml+='</thead>';

            cont=1;
            newHtml+='<tbody>';
                $.each(data,function(key,obj){
			if(cont % 2==0){
				newHtml+='<tr id="'+ cont +'" style="background-color:#E1E1E1" align="center"  >';
			}
			else{
				newHtml+='<tr id="'+ cont +'" align="center" >';
			}

			newHtml+='<td >'+obj['[Expr_11]']+'</td>';
			newHtml+='<td class="" >'+obj['[Expr_12]']+'</td>';
                        newHtml+='<td class="" >'+obj['[Expr_13]']+'</td>';
                        newHtml+='<td class="" >'+obj['[Expr_14]']+'</td>';
                        newHtml+='</tr>';

            cont++;
		});
            newHtml+='</tbody>';
            newHtml+='</table>';
            newHtml+='</div>';
            newHtml+='</div>';

//ESTATICO3
    newHtml+='<div>';
    newHtml+='<table cellspacing="5"  class="display" id="gridPanelOperativo2" style="margin-top: 10px;">'; // prueba
    newHtml+='<thead>';
    newHtml+='<tr style="height:20px;background-color:#000000;color:white;font-weight:bold">';
    newHtml+='<th>EN</th>'; // 15
    newHtml+='<th>%</th>'; // 16
    newHtml+='</tr>';
    newHtml+='</thead>';
    cont=1;
	newHtml+='<tbody>';
		$.each(data,function(key,obj){
			if(cont % 2==0){
				newHtml+='<tr id="'+ cont +'" style="background-color:#E1E1E1" align="center"  >';
			}
			else{
				newHtml+='<tr id="'+ cont +'" align="center" >';
			}
			newHtml+='<td class="" >'+obj['[Expr_15]']+'</td>';
                        newHtml+='<td class="" >'+obj['[Expr_16]']+'</td>';
                        newHtml+='</tr>';
        cont++;
		});
    newHtml+='</tbody>';
    newHtml+='</table>';
    newHtml+='</div>';
                //DINAMICO3
                newHtml+='<div class="set" style="margin-right:5px;">';
                newHtml+='<div class="title" style="margin-top: 18px;"><img src="css/ico/icono_flecha.png" width="14" height="14" id="tooltip3"/></div>'; // 3
                newHtml+='<div class="content">';
                newHtml+='<table cellspacing="5"  class="display" id="gridPanelOperativo1">'; // prueba
                newHtml+='<thead>';
                newHtml+='<tr style="height:20px;background-color:#000000;color:white;font-weight:bold">';
                newHtml+='<th>1 VIS</th>'; // 17
                newHtml+='<th>% 1V</th>'; // 18
                newHtml+='<th>2 VIS</th>'; // 19
                newHtml+='<th>% 2V</th>'; // 20

                newHtml+='<th>3 VIS</th>'; // 21
                newHtml+='<th>% 3V</th>'; // 22
                newHtml+='<th>4 VIS</th>'; // 23
                newHtml+='<th>% 4VIS</th>'; // 24
                newHtml+='<th>+4 VIS</th>'; // 25
                newHtml+='<th>%+4 VIS</th>'; // 26

                newHtml+='</tr>';
                newHtml+='</thead>';

                cont=1;
                newHtml+='<tbody>';
                    $.each(data,function(key,obj){
                        if(cont % 2==0){
				newHtml+='<tr id="'+ cont +'" style="background-color:#E1E1E1" align="center"  >';
			}
			else{
				newHtml+='<tr id="'+ cont +'" align="center" >';
			}

			newHtml+='<td >'+obj['[Expr_17]']+'</td>';
			newHtml+='<td class="" >'+obj['[Expr_18]']+'</td>';
                        newHtml+='<td class="" >'+obj['[Expr_19]']+'</td>';
                        newHtml+='<td class="" >'+obj['[Expr_20]']+'</td>';

                        newHtml+='<td class="" >'+obj['[Expr_21]']+'</td>';
                        newHtml+='<td class="" >'+obj['[Expr_22]']+'</td>';
                        newHtml+='<td class="" >'+obj['[Expr_23]']+'</td>';
                        newHtml+='<td class="" >'+obj['[Expr_24]']+'</td>';
                        newHtml+='<td class="" >'+obj['[Expr_25]']+'</td>';
                        newHtml+='<td class="" >'+obj['[Expr_26]']+'</td>';

                        newHtml+='</tr>';
        cont++;
		});
                newHtml+='</tbody>';
                newHtml+='</table>';
                newHtml+='</div>';
                newHtml+='</div>';

                    //DINAMICO4
                    newHtml+='<div class="set">';
                    newHtml+='<div class="title" style="margin-top: 18px;"><img src="css/ico/icono_flecha.png" width="14" height="14" id="tooltip4"/></div>'; // 3
                    newHtml+='<div class="content">';

                    newHtml+='<table cellspacing="5"  class="display" id="gridPanelOperativo1">'; // prueba
                    newHtml+='<thead>';
                    newHtml+='<tr style="height:20px;background-color:#000000;color:white;font-weight:bold">';
                    newHtml+='<th>1 DIA</th>'; // 27
                    newHtml+='<th>%1D</th>'; // 28
                    newHtml+='<th>2 DIA</th>'; // 29
                    newHtml+='<th>%2D</th>'; // 30

                    newHtml+='<th>3 DIA</th>'; // 31
                    newHtml+='<th>%3D</th>'; // 32
                    newHtml+='<th>4 DIA</th>'; // 33
                    newHtml+='<th>%4D</th>'; // 34
                    newHtml+='<th>+4DIA</th>'; // 35
                    newHtml+='<th>%+4D</th>'; // 36

                    newHtml+='</tr>';
                    newHtml+='</thead>';

                    cont=1;
                    newHtml+='<tbody>';
                    $.each(data,function(key,obj){
                    	if(cont % 2==0){
				newHtml+='<tr id="'+ cont +'" style="background-color:#E1E1E1" align="center"  >';
			}
			else{
				newHtml+='<tr id="'+ cont +'" align="center" >';
			}

			newHtml+='<td >'+obj['[Expr_27]']+'</td>';
			newHtml+='<td class="" >'+obj['[Expr_28]']+'</td>';
                        newHtml+='<td class="" >'+obj['[Expr_29]']+'</td>';
                        newHtml+='<td class="" >'+obj['[Expr_30]']+'</td>';

                        newHtml+='<td class="" >'+obj['[Expr_31]']+'</td>';
                        newHtml+='<td class="" >'+obj['[Expr_32]']+'</td>';
                        newHtml+='<td class="" >'+obj['[Expr_33]']+'</td>';
                        newHtml+='<td class="" >'+obj['[Expr_34]']+'</td>';
                        newHtml+='<td class="" >'+obj['[Expr_35]']+'</td>';
                        newHtml+='<td class="" >'+obj['[Expr_36]']+'</td>';

                        newHtml+='</tr>';

                    cont++;

                    });
                    newHtml+='</tbody>';
                    newHtml+='</table>';
                    newHtml+='</div>';
                    newHtml+='</div>';

       //ESTATICO4
        newHtml+='<div>';
        newHtml+='<table cellspacing="5"  class="display" id="gridPanelOperativo1" style="margin-top: 10px;">';
        newHtml+='<thead>';
        newHtml+='<tr style="height:20px;background-color:#000000;color:white;font-weight:bold">';
	newHtml+='<th>DV</th>'; // 37
        newHtml+='<th>SALDO</th>'; // 38
        newHtml+='<th>ER</th>'; // 39
        newHtml+='<th>LA</th>'; // 40
        newHtml+='<th>LD</th>'; // 41
        newHtml+='<th>FF</th>'; // 42
        newHtml+='</tr>';
        newHtml+='</thead>';

        cont=1;
	newHtml+='<tbody>';
		$.each(data,function(key,obj){
			if(cont % 2==0){
				newHtml+='<tr id="'+ cont +'" style="background-color:#E1E1E1" align="center"  >';
			}
			else{
				newHtml+='<tr id="'+ cont +'" align="center" >';
			}

			newHtml+='<td >'+obj['[Expr_37]']+'</td>';
			newHtml+='<td class="" >'+obj['[Expr_38]']+'</td>';
			newHtml+='<td >'+obj['[Expr_39]']+'</td>';
                        newHtml+='<td >'+obj['[Expr_40]']+'</td>';
                        newHtml+='<td >'+obj['[Expr_41]']+'</td>';
                        newHtml+='<td >'+obj['[Expr_42]']+'</td>';
                        newHtml+='</tr>';
			cont++;
		});
        newHtml+='</tbody>';

        newHtml+='</table>';
        newHtml+='</div>';

        newHtml+='</div>';//END PADRE END PADRE END PADRE END PADRE END PADRE END PADRE

        newHtml+='<script language="javascript" type="text/javascript">';
        newHtml+='$(document).ready(function(){';
        newHtml+='$("#accordionGiftLelo").msAccordion({defaultid:0});';
        newHtml+='})';
        newHtml+='</script>';

$('#divgridPanelServicio').empty().append(newHtml);

        $('#tooltip1').qtip({
            content: '<span style="font-style:normal;font-weight:700;font-size:12px;font-family:arial,sans-serif">Recolecciones</span>'
	});

        $('#tooltip2').qtip({
            content: '<span style="font-style:normal;font-weight:700;font-size:12px;font-family:arial,sans-serif">Despachos</span>'
	});

        $('#tooltip3').qtip({
            content: '<span style="font-style:normal;font-weight:700;font-size:12px;font-family:arial,sans-serif">Efectividad de Entrega</span>'
	});

        $('#tooltip4').qtip({
            content: '<span style="font-style:normal;font-weight:700;font-size:12px;font-family:arial,sans-serif">Tiempo de Transito</span>'
	});



};


getDetalle=function(id,orden,indica){
    $.ajax({
            url:pathController,
            type:'get',
            dataType:'json',
            data:{
            action:'getPanelOperativoDetalle',
            controller:'Reportes',
            vp_codsuc:$('#h_codsucursal').val(),
            vp_orden:orden,
            vp_fecini:$('#txtfecha1_lp').val(),
            vp_fecfin:$('#txtfecha2_lp').val()
            },
            beforeSend:function(){
                $.blockUI('CARGANDO LOS DATOS.....');
            },
            success:function(result){
                if(result.status==1){
                    Detalle(result.data,id,orden,indica);
                }else{
                    alert('NO SE ENCONTRARON DATOS!!');
                }
            $.unBlockUI();
            }
        });
}


Detalle=function(data,id,orden,indica){
newHtml='';
newHtml='<table width="100%" id="gridPanelOperativo'+id+'">';
newHtml+='<thead>';
newHtml+='<tr style="height:20px;background-color:#000000;color:white;font-weight:bold">';
newHtml+='<th >FECHA</th>'; //1
newHtml+='<th nowrap="nowrap">ORIG.</th>'; //2
newHtml+='<th nowrap="nowrap">SS</th>'; //3
newHtml+='<th nowrap="nowrap">AO</th>'; //4
newHtml+='<th nowrap="nowrap">SIN AO</th>'; //5
newHtml+='<th nowrap="nowrap">MD/RD</th>'; //6
newHtml+='<th nowrap="nowrap">DD</th>';    //7
newHtml+='<th nowrap="nowrap">AD</th>';    //8
newHtml+='<th nowrap="nowrap">SIN AD</th>'; //9
newHtml+='<th nowrap="nowrap">STOCK</th>'; //10
newHtml+='<th nowrap="nowrap">ENTR.</th>'; //11
newHtml+='<th nowrap="nowrap">% ENTREGA</th>'; //12
newHtml+='<th nowrap="nowrap">DEV.</th>'; //13
newHtml+='<th nowrap="nowrap">SALDO</th>'; //14
newHtml+='<th nowrap="nowrap">ER</th>'; //15
newHtml+='<th nowrap="nowrap">RA</th>'; //16
newHtml+='<th nowrap="nowrap">RT</th>'; //17
newHtml+='<th nowrap="nowrap">RM</th>'; //18
newHtml+='<th nowrap="nowrap">SIN RM</th>'; //19
newHtml+='</tr>';
newHtml+='</thead>';
var cont=1;
newHtml+='<tbody>';
$.each(data,function(key,obj){
if(cont % 2==0){
newHtml+='<tr id="'+id+'" style="background-color:#E1E1E1">';
}
else{
newHtml+='<tr id="'+id+'">';
}
newHtml+='<td class="dtfecha">'+obj['[Expr_1]']+'</td>';					  //1
newHtml+='<td class="" style="align="right">'+obj['[Expr_2]']+'</td>';  //2
newHtml+='<td style="color:#409EBA;" align="right" ><a href="javascript:detalle_chk('+obj['[Expr_20]']+',\'SS\')">'+obj['[Expr_3]']+'</a></td>';//3
newHtml+='<td style="color:#1F856D;" align="right" >'+obj['[Expr_4]']+'</td>';//4
newHtml+='<td style="color:#DCA820;" align="right" >'+obj['[Expr_5]']+'</td>';//5
newHtml+='<td style="color:#6E7BC1;" align="right" ><a href="javascript:detalle_chk('+obj['[Expr_20]']+',\'RD\')">'+obj['[Expr_6]']+'</a></td>';//6
newHtml+='<td style="color:#6E7BC1;" align="right" >'+obj['[Expr_7]']+'</td>';//7
newHtml+='<td style="color:#E05410;" align="right" >'+obj['[Expr_8]']+'</td>';//8
newHtml+='<td style="color:#DCA820;" align="right" >'+obj['[Expr_9]']+'</td>';//9
newHtml+='<td style="color:#409EBA;" align="right" >'+obj['[Expr_10]']+'</td>';//10
newHtml+='<td style="color:#EBA410;" align="right" >'+obj['[Expr_11]']+'</td>';//11
newHtml+='<td style="color:#E05EBA;" align="right" >'+obj['[Expr_12]']+'</td>';//12
newHtml+='<td style="color:#409EBA;" align="right" >'+obj['[Expr_13]']+'</td>';//13
newHtml+='<td style="color:#E05410;" align="right" >'+obj['[Expr_14]']+'</td>';//14
newHtml+='<td style="color:#0BA410;" align="right" >'+obj['[Expr_15]']+'</td>';//15
newHtml+='<td style="color:#6E7BC1;" align="right" >'+obj['[Expr_16]']+'</td>';//16
newHtml+='<td style="color:#1F856D;" align="right" >'+obj['[Expr_17]']+'</td>';//17
newHtml+='<td style="color:#000000;" align="right" >'+obj['[Expr_18]']+'</td>';//18
newHtml+='<td style="color:#E0CA01;" align="right" >'+obj['[Expr_19]']+'</td>';//19
newHtml+='</tr>';
cont++;
});

if(indica==1){
$('#td_'+id).empty().append('<a href="javascript:eliminar_hijo(\''+ id +'\', \''+ orden +'\')" ><img src="css/ico/menos.png" border="0" ></a>');
}else{
$('#td_'+id).empty().append('<a href="javascript:getDetalle(\''+ id +'\', \''+orden +'\',\'1\')" ><img src="css/ico/add.png" border="0" ></a> ');
}
newHtml+='</tbody>';
newHtml+='</table>';
hijo('gridPanelOperativo',id,newHtml,indica);
};
hijo=function(id_table,id_fila,content,indica){
if(indica==1){
var tabla = document.getElementById(id_table);
var numColumnas = tabla.rows[0].cells.length;
$('#'+id_fila).after('<tr id="ext_tr_'+id_fila+'"><td colspan="'+numColumnas+'"><div id="div_'+id_fila+'" style="padding-left:20px;border:1px solid black;"></div></td></tr>');
$('#div_'+id_fila).empty().html(content);
}
else{
$('#ext_tr_'+id_fila).remove();
}
}
eliminar_hijo=function(id,orden){
$('#ext_tr_'+id).remove();
$('#td_'+id).empty().append('<a href="javascript:getDetalle(\''+id+'\', \''+ orden +'\',\'1\')" ><img src="css/ico/add.png" border="0" ></a>');
}
getOrdenGuias=function(ord,chk,codsuc){
parent.location.href="javascript:abre_modal('guiaorden','Guias de la orden "+ord+"','../paqueteria/views/ui.cli.rep.estado.servicio.guias.php?ord="+ord+"&chk="+chk+"&codsuc="+codsuc+"',1000,550)";
}

detalle_chk=function(shipper,servicio){
//var codsuc=$.trim($('#h_codsucursal').val());

parent.location.href="javascript:abre_modal('Detalle de Servicios','Serviciomicha "+servicio+"','../paqueteria/views/ui.cli.rep.estado.servicio.detalle.php?txtshipper="+shipper+"&txtservicio="+servicio+"',1000,550)";

}