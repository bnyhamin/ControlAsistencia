/***********************************************************************************
*	(c) Ger Versluis 2000 version 5.411 24 December 2001 (updated Jan 31st, 2003 by Dynamic Drive for Opera7)
*	For info write to menus@burmees.nl		          *
*	You may remove all comments for faster loading	          *		
***********************************************************************************/
 
	var NoOffFirstLineMenus=1;			// Number of first level items
	var LowBgColor='#FEFEF1';			// Background color when mouse is not over
	var LowSubBgColor='#FEFEF1';			// Background color when mouse is not over on subs
	var HighBgColor='#E7E7D6';			// Background color when mouse is over 
	var HighSubBgColor='#F3F3E7';			// Background color when mouse is over on subs
	var FontLowColor='black';			// Font color when mouse is not over
	var FontSubLowColor='black';			// Font color subs when mouse is not over
	var FontHighColor='#1A1A1A';			// Font color when mouse is over
	var FontSubHighColor='#5F5F5F';			// Font color subs when mouse is over
	var BorderColor='black';			// Border color
	var BorderSubColor='black';			// Border color for subs
	var BorderWidth=1;				// Border width
	var BorderBtwnElmnts=1;			// Border between elements 1 or 0
	var FontFamily="arial,comic sans ms,technical"	// Font family menu items
	var FontSize=8;				// Font size menu items
	var FontBold=0;				// Bold menu items 1 or 0
	var FontItalic=0;				// Italic menu items 1 or 0
	var MenuTextCentered='left';			// Item text position 'left', 'center' or 'right'
	var MenuCentered='left';			// Menu horizontal position 'left', 'center' or 'right'
	var MenuVerticalCentered='top';		// Menu vertical position 'top', 'middle','bottom' or static
	var ChildOverlap=.2;				// horizontal overlap child/ parent
	var ChildVerticalOverlap=.2;			// vertical overlap child/ parent
	var StartTop=1;				// Menu offset x coordinate
	var StartLeft=1;				// Menu offset y coordinate
	var VerCorrect=0;				// Multiple frames y correction
	var HorCorrect=0;				// Multiple frames x correction
	var LeftPaddng=3;				// Left padding
	var TopPaddng=2;				// Top padding
	var FirstLineHorizontal=1;			// SET TO 1 FOR HORIZONTAL MENU, 0 FOR VERTICAL
	var MenuFramesVertical=0;			// Frames in cols or rows 1 or 0
	var DissapearDelay=1000;			// delay before menu folds in
	var TakeOverBgColor=1;			// Menu frame takes over background color subitem frame
	var FirstLineFrame='middle';			// Frame where first level appears
	var SecLineFrame='middle';			// Frame where sub levels appear
	var DocTargetFrame='middle';			// Frame where target documents appear
	var TargetLoc='';				// span id for relative positioning
	var HideTop=0;				// Hide first level when loading new document 1 or 0
	var MenuWrap=1;				// enables/ disables menu wrap 1 or 0
	var RightToLeft=0;				// enables/ disables right to left unfold 1 or 0
	var UnfoldsOnClick=0;			// Level 1 unfolds onclick/ onmouseover
	var WebMasterCheck=0;			// menu tree checking on or off 1 or 0
	var ShowArrow=1;				// Uses arrow gifs when 1
	var KeepHilite=1;				// Keep selected path highligthed
	var Arrws=['tri.gif',5,10,'tridown.gif',10,5,'trileft.gif',5,10];	// Arrow source, width and height

function BeforeStart(){return}
function AfterBuild(){return}
function BeforeFirstOpen(){return}
function AfterCloseAll(){return}


// Menu tree
//	MenuX=new Array(Text to show, Link, background image (optional), number of sub elements, height, width);
//	For rollover images set "Text to show" to:  "rollover:Image1.jpg:Image2.jpg"

Menu1=new Array("Registrar","javascript:nada()","",2,15,80);
	Menu1_1=new Array("Incidencias","javascript:cmdIncidencias_onclick()","",0,15,80);
	Menu1_2=new Array("Conexion","javascript:cmdTiempos_onclick()","",0,15,80);
//Menu2=new Array("Conexion","javascript:nada()","",1);
//	Menu2_1=new Array("Registrar","javascript:cmdTiempos_onclick()","",0,15,80);
///Menu2=new Array("Turnos","javascript:nada()","",1);
///	Menu2_1=new Array("Modificar","javascript:cmdModificar_onclick()","",0,15,80);
////Menu2=new Array("Estados","javascript:nada()","",1);
////	Menu2_1=new Array("Anular","javascript:cmdAnular_onclick() ","",0,15,80);
//Menu2=new Array("Servicios","javascript:nada()","",1);
//	Menu2_1=new Array("Activación","javascript:activar_servicio() ","",0,15,80);	
	/*
Menu1=new Array("Ficha","javascript:nada()","",6,15,80);
	Menu1_1=new Array("Ver","javascript:editar()","",0,15,80);
	Menu1_2=new Array("Resumen","javascript:resumen()","",0,15,80);
	Menu1_3=new Array("Elegir","javascript:elegir()","",0,15,80);
	Menu1_4=new Array("Descartar","javascript:Descartar()","",0,15,80);
	Menu1_5=new Array("Comentarios","javascript:Comentarios()","",0,15,80);
	Menu1_6=new Array("Historico","javascript:historico()","",0,15,80);
	
Menu2=new Array("Utilidades","javascript:nada()","",4);
	Menu2_1=new Array("Grupos","javascript:nada()","",2,15,80);
		Menu2_1_1=new Array("Agregar","javascript:Agregar_Grupo()","",0,15,80);
		Menu2_1_2=new Array("Postulantes","javascript:Listar_Postulantes()","",0,15,80);
	Menu2_2=new Array("Importar","javascript:importar()","",0,15,80);
	Menu2_3=new Array("Exportar","javascript:exportar()","",0);
	Menu2_4=new Array("Bandejas","javascript:nada()","",2,15,80);
		Menu2_4_1=new Array("Agregar","javascript:Agregar_Bandeja()","",0,15,80);
		Menu2_4_2=new Array("Listar","javascript:listabandejas()","",0,15,80);
	
Menu3=new Array("Salir","javascript:self.location.href='../../index.php'","",0);	*/
		
