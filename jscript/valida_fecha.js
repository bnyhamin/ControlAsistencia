Ext.ns('com.atento.gap');
Ext.BLANK_IMAGE_URL='../../extjs/resources/images/s.gif';
com.atento.gap.Valida_Fecha={
    id:'frmfechas',
    url:'../controllers/validar_incidencia_controller.php',
    init:function(){
    },salir:function(){
        window.parent.location.href='../menu.php';
    },fechaC:function(cadena) {
        
        var separador = "/";
        //Separa por dia, mes y año
        if ( cadena.indexOf( separador ) != -1 ) {
            var posi1 = 0;
            var posi2 = cadena.indexOf( separador, posi1 + 1 );
            var posi3 = cadena.indexOf( separador, posi2 + 1 );
            this.dia = cadena.substring( posi1, posi2 );
            this.mes = cadena.substring( posi2 + 1, posi3 );
            this.anio = cadena.substring( posi3 + 1, cadena.length );
        }else{
            this.dia = 0;
            this.mes = 0;
            this.anio = 0;
        }
    },numDias:function(d,m,a){
        m = (m + 9) % 12;
        a = a - Math.floor(m/10);
        return 365*a+Math.floor(a/4)-Math.floor(a/100)+Math.floor(a/400)
                +Math.floor((m*306+5)/10)+d-1 
    },difDias:function(d1,m1,a1,d2,m2,a2){
        return this.numDias(d2,m2,a2) - this.numDias(d1,m1,a1)
    },validaFecha:function(_fec_actual,_fec_select,_dias_per){      
        _cadenaFecha1 = _fec_actual;
        _cadenaFecha2 = _fec_select;
        var bandera=0;
        var _fecha1 = new com.atento.gap.Valida_Fecha.fechaC(_cadenaFecha1);
        var _fecha2 = new com.atento.gap.Valida_Fecha.fechaC(_cadenaFecha2);
        var _dias=com.atento.gap.Valida_Fecha.difDias(_fecha1.dia*1,_fecha1.mes*1,_fecha1.anio*1, _fecha2.dia*1, _fecha2.mes*1, _fecha2.anio*1);   
        
        if ((Math.abs(_dias))*1>(_dias_per)*1){
            bandera=1;
            //document.frm.txtFecha.value=fecha_seleccion;
            //return false;
        }
        
        if (Date.parse(_fecha2.anio+'/'+_fecha2.mes+'/'+_fecha2.dia) > Date.parse(_fecha1.anio+'/'+_fecha1.mes+'/'+_fecha1.dia)) {
            if (_dias > 0 && (_fecha2.anio + "-" + _fecha2.mes + "-" + _fecha2.dia != '2011-01-31')){
                bandera=2;
                //document.frm.txtFecha.value=fecha_seleccion;
                //return false;
            }
	}
        return bandera;
    },fecha_actual:function(_fecha){
        _fec_desde = new com.atento.gap.Valida_Fecha.fechaC(_fecha);
        if(_fec_desde.mes.substr(0,1)=='0') _fec_desde.mes=_fec_desde.mes.substr(1,1);
        _fecha_desde = new Date(_fec_desde.anio,parseInt(_fec_desde.mes)-1,_fec_desde.dia );
        return _fecha_desde
    }
}
Ext.onReady(com.atento.gap.Valida_Fecha.init,com.atento.gap.Valida_Fecha);