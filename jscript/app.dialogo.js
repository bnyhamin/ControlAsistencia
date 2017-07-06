$(document).ready(function(){
        //alert('carga dom');
        /*$.extend($.fn.dialog.methods, {
		mymove: function(jq, newposition){
                    alert('movi'+newposition);
			return jq.each(function(){
				$(this).dialog('move', newposition);
			});
		}
	});*/
});
Ext_Dialogo={
    genera_Dialog:function(ancho,alto,titulo,url,div,izquierda,arriba){
        /*
        $.extend($.fn.dialog.methods, {
		mymove: function(jq, newposition){
			return jq.each(function(){
				$(this).dialog('move', newposition);
			});
		}
	});
        */
        //$('#win').window('open');
        
        var myDialogGrupo=$("#"+div).dialog({
            title:''+titulo
            ,width:ancho
            ,height:alto
            ,modal:true
            ,resizable:true
            ,href:url
            ,cache: true
            ,left:izquierda
            ,top:arriba
            /*,onResize:function(){
                $(this).dialog('center');
            }*/
            /*,buttons:[{
                text:'Cerrar'
                ,iconCls:'icon-cancel'
                ,handler:function(){
                        $('#'+div).dialog('close');
                }
                ,plain:true
            }]*/
	});//.dialog('open');
        
        //--myDialogGrupo.dialog('open');
       
        //alert('iz@'+izquierda+'ar@'+arriba);
        /*
        $('#'+div).dialog('mymove', {
		left: izquierda,
		top: arriba
	});
        */
        
    },

    dialogCenter:function(ancho,alto,titulo,url,div){
       
        var myDialog=$("#"+div).dialog({
            title:''+titulo
            ,width:ancho
            ,height:alto
            ,modal:true
            ,resizable:true
            ,href:url
            ,cache: true
        
        });
        myDialog.position({
           my: "center",
           at: "center",
           of: window
        });
        myDialog.dialog('open');

        
    },
    close_Dialog:function (div){
        $('#'+div).dialog('close');
    }
}


