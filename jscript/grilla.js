x=0;
y=0;
Ext.ns('com.atento.gap');
Ext.BLANK_IMAGE_URL='../../extjs/resources/images/s.gif';
com.atento.gap.Grilla={
    id:'frmgrilla',
    url:'../controllers/validar_incidencia_controller.php',
    init:function(){
        xMousePos = 0;
        yMousePos = 0;
        if (document.layers){ // Netscape
            document.captureEvents(Event.MOUSEMOVE);
            document.onmousemove = com.atento.gap.Grilla.captureMousePosition;
        }else if (document.all) { // Internet Explorer
            document.onmousemove = com.atento.gap.Grilla.captureMousePosition;
        }else if (document.getElementById) { // Netcsape 6
            document.onmousemove = com.atento.gap.Grilla.captureMousePosition;
        }
    },aparece:function(val){
        if (document.layers) { // Netscape
            document.captureEvents(Event.MOUSEMOVE);
            document.onmousemove = com.atento.gap.Grilla.captureMousePosition;
        } else if (document.all) { // Internet Explorer
            document.onmousemove = com.atento.gap.Grilla.captureMousePosition;
        } else if (document.getElementById) { // Netcsape 6
            document.onmousemove = com.atento.gap.Grilla.captureMousePosition;
        }
        var el=null;
        if(Ext.isIE){//IE
            el=Ext.get('div_'+val);
            
            el.setStyle({
                'visibility':'visible',
                'top':window.y+'px',
                'left':window.x+'px'
            });
        }else{
            el=Ext.get('div_'+val);
            el.setStyle({
                'visibility':'visible',
                'top':yMousePos+'px',
                'left':xMousePos+'px'
            });
        }
        document.getElementById('txtb_'+val).focus();
        document.getElementById('txtb_'+val).select();
        
    },desaparece:function(val){
        var el=null;
        if(Ext.isIE){
            el=Ext.get('div_'+val);
        }else{
            el=Ext.get('div_'+val);
        }
        el.setStyle({
            'visibility':'hidden'
        });
    },criterio:function(id){
        var cadena='';
        var cadenam='';
        var t=document.getElementById('txtb_empleado');
        if (t.value != ''){
            cadena += 'empleado:' + t.value+'';
            cadenam += 'empleado|' + t.value+'';
        }
        var b1=document.getElementById('fbuscar');
            b1.innerHTML=cadena;
        var b2=document.getElementById('buscam');
            b2.value=cadenam;
    },generaFiltros:function(columnas){
        var newHtml='';
        for(var i=0;i<columnas.length;i++){
            newHtml+="<div class=backgroundGreen style=\"position:absolute;visibility:hidden;background-color:#C6D5FD;padding-bottom: 10px; z-index:1000000; padding-left: 10px; padding-right: 10px;  padding-top: 0px\" id=\"div_"+columnas[i]+"\">";
            newHtml+=columnas[i]+"<br/>";
            newHtml+="<input type=text style=\"width:80;\" id=\"txtb_"+columnas[i]+"\" name=\"txtb_"+columnas[i]+"\" value=\"\" onblur=\"return  com.atento.gap.Grilla.criterio(this);\"/>";
            newHtml+="</div>";
        }
        
        newHtml+="<input type=\"hidden\" name=\"buscam\" id=\"buscam\" value=\"\"/>"
        
        return newHtml;
    },generaBuscarOrden:function(){
        var html='';
            html='<table id="tablecabecera" name="tablecabecera" border="0">';
            html+="<tr>";
            html+='<input type="hidden"  name="TxtBuscar" id="TxtBuscar" style="WIDTH: 5px" onkeyup="javascript:pulsar(2)"/>';
            html+='<td><div class="buttons" style="text-align:center;" >';
            html+='<label class="personalizado" id="cmdBuscar" name="cmdBuscar" style="font-size:12px; border: 2px solid #CED9E7; color:#000000;" onclick="javascript:com.atento.gap.ValidarIncidencia.recarga();">';
            html+='<img src="../images/zoom.png" alt="Buscar" width="5%" />Buscar';
            html+='</label>';
            html+='</div></td>';
            html+='<td><b><font id="fbuscar" color=Red></font></b></td></tr></table>';
        return html;
    },captureMousePosition:function(e){
        if (document.layers) {
            xMousePos = e.pageX;
            yMousePos = e.pageY;
            xMousePosMax = window.innerWidth+window.pageXOffset;
            yMousePosMax = window.innerHeight+window.pageYOffset;
        } else if (document.all){
            xMousePos = window.event.x+document.body.scrollLeft;
            yMousePos = window.event.y+document.body.scrollTop;
            xMousePosMax = document.body.clientWidth+document.body.scrollLeft;
            yMousePosMax = document.body.clientHeight+document.body.scrollTop;
        } else if (document.getElementById){
            xMousePos = e.pageX;//pocision en x
            yMousePos = e.pageY;//pocision en y
            xMousePosMax = window.innerWidth+window.pageXOffset;//pocision maxima en x
            yMousePosMax = window.innerHeight+window.pageYOffset;//pocision maxima en y
        }
    }
}
Ext.onReady(com.atento.gap.Grilla.init,com.atento.gap.Grilla);