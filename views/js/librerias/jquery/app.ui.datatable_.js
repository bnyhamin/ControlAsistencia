/**
 * @author mcabada
 * DATE: 06 / 11 / 2009
 */
(function($) {
	var __tmp_data_grid=[];
	$.fn.dataTable=function(_json){
		var _html='';
		var _config = _json;
		var _total_columns=0;
		var _total_rows=0;
		var _width_grid=0;
		var _class='';
		var _total_paginas=1;
		var _page_actual=1;
		var _id_col=0;
		var _id_row=0;
		var _value_row=0;
		var _dataExtra=[];
		return this.each(function(){
			__tmp_data_grid.length=0;
			//TITLE
			_html='<div class="datatable-title-box">';
				_html+='<div class="datatable-title-bg-center">';
					_html+='<div class="datatable-title-bg-right">';
						_html+='<div class="datatable-title-bg-left"><span class="datatable-title-text">'+(_config.title!=''?_config.title:'DATA - TABLE')+'</span></div>';
					_html+='</div>';
				_html+='</div>';
			_html+='</div>';
			//CONTENT PANEL
			_total_columns=(_config.columns.length?_config.columns.length:0);
			//_total_columns --; //le quitamos 1 por la columna autogenerada
			for (var _pos = 0; _pos < _total_columns; _pos++){
				//alert(_total_columns+'==>'+_pos);
				//alert(_config.columns[_pos]);
				if(typeof _config.columns[_pos]!='undefined'){
					if(typeof _config.columns[_pos].width!='undefined'){
						_width_grid +=_config.columns[_pos].width;
					}				
					if(typeof _config.columns[_pos].id!='undefined' && _config.columns[_pos].id==true ){
						_id_col=_pos;
					}
				}
			}
			
			_width_grid += 80 + _config.columns.length + 1;// 80 + 1pto x cada columna  + 1 por la columna autonumerica
			_total_rows=(_config.data.length);						
			_html+='<div class="datatable-content" style="width:'+_width_grid+'px;">';
			//COLUMNS
				_html+='<div class="datatable-header">';
					_html+='<table><tr>';
					//autonumeric
					_html+='<th style="width:40px;border-right:1px solid #ced0cd;"><div style="text-align:center;">##</div></th>';
					for (var _pos = 0; _pos < _total_columns; _pos++){
						_html += '<th style="width:'+_config.columns[_pos].width+'px;border-right:1px solid #ced0cd;"><div>'+_config.columns[_pos].title+'</div></th>';
					}
					_html+='</table></tr>';
				_html+='</div>';

				//DATA	

				_html += '<div style="overflow-y:auto;overflow-x:hidden;font-weight:normal;height:'+(_config.height || 100)+'px;" class="datatable-data"><table id="'+_config.idGrid+'">';					
				$.each(_config.data,function(key,row){
					$.addRowTmp(row);//add row a tmp data
					_html +='<tr>';
					//autonumeric
					_html += '<td style="width:40px;border-right:1px solid #ced0cd;"><div style="text-align:right;">'+(key + 1)+'</div></td>';
					_pos = 0;

					_id_row=$.getRowCell(row,_id_col);

					$.each(row,function(key,field){
						_class=$.typeCell(typeof _config.columns[_pos]!='undefined'?_config.columns[_pos].type:'text');
						_type = (typeof _config.columns[_pos].extra!='undefined'?_config.columns[_pos].extra:'');
						_extra=_config.path +(_config.columns[_pos].ico!='undefined'?_config.columns[_pos].ico:'');
						_html += '<td style="width:' + _config.columns[_pos].width + 'px;border-right:1px solid #ced0cd;">';
							_html += '<div style="white-space:nowrap;text-align:'+(_config.columns[_pos].align?_config.columns[_pos].align:'left')+'" class="'+_class+'">';

							_value_row=field;
							if(typeof _config.columns[_pos].extra!='undefined' && typeof _config.columns[_pos].extra=='object'){
								if(typeof _config.columns[_pos].extra.ref!='undefined' && typeof _config.columns[_pos].extra.ref=='number'){
									_value_row=$.getRowCell(row,_config.columns[_pos].extra.ref);
								}
							}
							if(typeof _config.columns[_pos].extra!='undefined' && typeof _config.columns[_pos].extra=='object'){
								_dataExtra={
									type:(typeof _config.columns[_pos].extra.type!='undefined'?_config.columns[_pos].extra.type:''),
									text:field,
									ico:(typeof _config.columns[_pos].extra.ico!='undefined'?(_config.path+_config.columns[_pos].extra.ico):''),
									id:_id_row,
									value:_value_row,
									checked:((typeof _config.columns[_pos].extra.type!='undefined' && (field==true || field==1))?'checked="checked"':'')
								};
								_html += $.createExtra(_dataExtra);
							}else{
								_html += field;
							}
							_html += '</div>';
						_html += '</td>';
						_pos++;
					});
					_html += '</tr>';
				});
				_html += '</table></div>';
			//TOOLBAR
			_html+='</div>';	
			_total_paginas = Math.ceil(_total_rows / _config.show);
			_html += '<div class="datatable-toolbar">';
				_html += '<div class="datatable-toolbar-bg-center" id="'+_config.idGrid+'_toolbar">';
					_html += '<div class="datatable-toolbar-bg-right">';
						_html += '<div class="datatable-toolbar-bg-left">';
							_html += '<table style="width:auto;"><tr>';
								if(typeof _config.buttons!='undefined' && typeof _config.buttons=='object'){
									$.each(_config.buttons,function(key,button){
										_html += '<td width="15"><img src="'+(_config.path+button.ico)+'" alt="'+button.ico+'" title="'+button.title+'" id="'+_config.idGrid+'_button_'+key+'" /></td>';	
									});									
								}								
								//_html += '<td><div><img src="'+_config.path+'refresh.png" alt="reload" title="RELOAD" id="'+_config.idGrid+'_refresh" /></div></td>';
								_html += '<td width="15"><img src="'+_config.path+'first.png" alt="first" title="PRIMERO" id="'+_config.idGrid+'_first" /></td>';
								_html += '<td width="15"><img src="'+_config.path+'previous.png" alt="previous" title="ANTERIOR" id="'+_config.idGrid+'_previous" /></td>';
								_html += '<td width="15"><img src="'+_config.path+'next.png" alt="next" title="SIGUIENTE" id="'+_config.idGrid+'_next" /></td>';
								_html += '<td width="15"><img src="'+_config.path+'last.png" alt="last" title="ULTIMO" id="'+_config.idGrid+'_last" /></td>';
								_html += '<td><span>PAGINA:</span><select id="'+_config.idGrid+'_pages">';
								for(var pos=1;pos<=_total_paginas;pos++){
									_html += '<option value="'+(pos)+'">'+(pos)+'</option>';
								}
								_html += '</select></td>';
								_html += '<td><span style="text-align:left;">TOTAL REGISTROS:['+_total_rows+']</span></td>';
							_html += '</tr></table>';
						_html += '<div>';
					_html += '<div>';
				_html += '</div>';
			_html += '</div>';

			$(this).empty().append(_html).addClass('datatable').css('width',_width_grid);
			
			if(typeof _config.buttons!='undefined' && typeof _config.buttons=='object'){
				$.each(_config.buttons,function(key,button){
					id=_config.idGrid+'_button_'+key;
					$('#'+id).click(function(){
						if(button.selected){						
							if($('#'+_config.idGrid+' tr').hasClass('datatable-data-row-selected')){
								idRow=$($('#'+_config.idGrid+' tr.datatable-data-row-selected')).find('td:eq(0)').text();
								button.click($.getRow(idRow-1));
							}else{
								alert('SELECCIONE UN REGISTRO.!!!');
							}
						}else{
							button.click();
						}
					});
				});									
			}

			//HANDLER TOOLBAR
			_page_actual=1;
			$.showRows(_config.idGrid,1,_config.show);

			$('#'+_config.idGrid+'_next').click(function(){
				if(_page_actual >= _total_paginas){return false;}
				inicio = (_page_actual * _config.show)+1;
				fin = (inicio - 1)+ _config.show;
				_page_actual++;
				$('#'+_config.idGrid+' _toolbar select').val(_page_actual);
				$.rowNext(_config.idGrid,inicio,fin);
			});

			$('#'+_config.idGrid+'_previous').click(function(){
				if(_page_actual<=1){return false;}
				_page_actual--;
				fin = (_page_actual * _config.show);
				inicio = (fin - _config.show) + 1;
				$.rowPrevious(_config.idGrid,inicio,fin);
				$('#'+_config.idGrid+'_toolbar select').val(_page_actual);
			});					

			$('#'+_config.idGrid+'_last').click(function(){
				inicio= ((_total_paginas * _config.show) - _config.show)+1;
				fin = _config.data.length;
				_page_actual=_total_paginas;
				$.rowLast(_config.idGrid,inicio,fin);
				$('#'+_config.idGrid+'_toolbar select').val(_page_actual);
			});	

			$('#'+_config.idGrid+'_first').click(function(){
				inicio = 1;
				fin = _config.show;
				_page_actual=1;
				$.showRows(_config.idGrid,inicio,fin);
				$('#'+_config.idGrid+'_toolbar select').val(_page_actual);
			});

			$('#'+_config.idGrid+'_pages').change(function(){
				_page_actual=this.value;
				fin=_page_actual * _config.show;
				inicio=(fin - _config.show + 1);
				$.showRows(_config.idGrid,inicio,fin);
			});					
			//ZEBRA
			$('#'+_config.idGrid+' tr:odd').addClass('datatable-data-row-odd');
			$('#'+_config.idGrid+' tr:even').addClass('datatable-data-row-even');

			//$('div.datatable-data table tr td:eq(0)').removeClass($('div.datatable-data table tr td:eq(0)').attr('className')).addClass('datatable-data-td-none');
			//MOUSEOVER
			$('#'+_config.idGrid+' tr').hover(
				function(){
					$(this).addClass('datatable-data-row-selected-hover')
				},
				function(){
					$(this).removeClass('datatable-data-row-selected-hover')
				}
			);

			//CLICK
			$('#'+_config.idGrid+' tr').click(
				function(){
					if(typeof _config.multipleSelect=='undefined'){
						$('#'+_config.idGrid+' tr').removeClass('datatable-data-row-selected');
					}
					if($(this).hasClass('datatable-data-row-selected')){
						$(this).removeClass('datatable-data-row-selected');								
					}else{
						$(this).addClass('datatable-data-row-selected');
					}
					//add handler on clic a row selected

					if(typeof _config.rowSelected!='undefined' && typeof _config.rowSelected=='function'){
						idRow=$(this).children('td:eq(0)').text();
						//console.info($.getRow(idRow));
						_config.rowSelected($.getRow(idRow-1));
					}
				}
			);

			if(typeof _config.finish!='undefined' && typeof _config.finish=='function'){
				_config.finish();
			}
		});				
	};

	$.typeCell = function(_type){					
		var _classType=[];
		_classType['undefined']='datatable-data-row-cell-text';
		_classType['text']='datatable-data-row-cell-text';
		_classType['number']='datatable-data-row-cell-number';
		_classType['date']='datatable-data-row-cell-date';
		return _classType[_type];
	};

	$.rowNext=function(id,inicio,fin){
		$.showRows(id,inicio,fin);
	};

	$.rowPrevious=function(id,inicio,fin){
		$.showRows(id,inicio,fin);
	};

	$.rowFirst=function(id,inicio,fin){
		$.showRows(id,inicio,fin);
	};

	$.rowLast=function(id,inicio,fin){
		$.showRows(id,inicio,fin);
	};

	$.showRows=function(id,inicio,fin){
	$('#'+id+' tr').hide();
		for(var pos=inicio;pos<=fin;pos++){
		   $('#'+id+' tr:eq('+(pos-1)+')').show();
		}
	};

	$.addRowTmp=function(obj){
		__tmp_data_grid[__tmp_data_grid.length]=obj;
	};

	$.getRow=function(index){
		return __tmp_data_grid[index];
	};

	$.getRowCell=function(_row,col){
		var valueCell='';
		var _col=0;
		$.each(_row,function(key,field){
			if(_col == col){
				valueCell=field;
				return false;
			}
			_col++;
		});
		return valueCell;
	};

	$.createExtra=function(obj){
		switch (obj.type){
			case 'link':
				return '<a href="#" id="lk'+obj.id+'" title="'+obj.text+'">'+obj.text+'</a>';
			break;
			case 'checkbox':
				return '<input type="checkbox" '+(obj.checked)+' value="'+obj.value+'" id="_chk'+obj.id+'" />';
			break;
			case 'img':
				return '<img src="'+obj.ico+'" alt="'+obj.id+'" id="_img'+obj.id+'" />';
			break;
			case 'radio':
				return '<input type="radio" value="'+obj.value+'" id="_opt'+obj.id+'" />';
			break;			
			default:
				return obj.text;
			break;
		}
	};
})(jQuery);