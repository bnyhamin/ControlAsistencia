var outerLayout, innerLayout;
$(document).ready( function() {
    // create the OUTER LAYOUT
    outerLayout = $("body").layout( layoutSettings_Outer );

    var westSelector = "body > .ui-layout-west"; // outer-west pane

     // CREATE SPANs for pin-buttons - using a generic class as identifiers
    $("<span></span>").addClass("pin-button").prependTo( westSelector );

    // BIND events to pin-buttons to make them functional
    outerLayout.addPinBtn( westSelector +" .pin-button", "west");


     // CREATE SPANs for close-buttons - using unique IDs as identifiers
    $("<span></span>").attr("id", "west-closer" ).prependTo( westSelector );

    // BIND layout events to close-buttons to make them functional
    outerLayout.addCloseBtn("#west-closer", "west");

});
layoutSettings_Inner = {
  applyDefaultStyles:true // basic styling for testing & demo purposes
	,	minSize:						20 // TESTING ONLY
	,	spacing_closed:					14
	,	north__spacing_closed:			8
	
	,	north__togglerLength_closed:	-1 // = 100% - so cannot 'slide open'
	,	south__togglerLength_closed:	-1
	,	fxName:							"slide" // do not confuse with "slidable" option!
	,	fxSpeed_open:					1000
	,	fxSpeed_close:					2500
	,	fxSettings_open:				{ easing: "easeInQuint" }
	,	fxSettings_close:				{ easing: "easeOutQuint" }
	,	north__fxName:					"none"
	
	//,	initClosed:						true
	,	center__minWidth:				200
	,	center__minHeight:				200
};
var layoutSettings_Outer = {
    name: "outerLayout" // NO FUNCTIONAL USE, but could be used by custom code to 'identify' a layout
    // options.defaults apply to ALL PANES - but overridden by pane-specific settings
		,	defaults: {
    	  size:					"auto"
		    ,	minSize:				50
		    ,	paneClass:				"pane" 		// default = 'ui-layout-pane'
		    ,	resizerClass:			"resizer"	// default = 'ui-layout-resizer'
		    ,	togglerClass:			"toggler"	// default = 'ui-layout-toggler'
		    ,	buttonClass:			"button"	// default = 'ui-layout-button'
		    ,	contentSelector:		".content"	// inner div to auto-size so only it scrolls, not the entire pane!
		    ,	contentIgnoreSelector:	"span"		// 'paneSelector' for content to 'ignore' when measuring room for content
		    ,	togglerLength_open:		35			// WIDTH of toggler on north/south edges - HEIGHT on east/west edges
		    ,	togglerLength_closed:	35			// "100%" OR -1 = full height
		    ,	hideTogglerOnSlide:		true		// hide the toggler when pane is 'slid open'
		    ,	togglerTip_open:		"Close This Pane"
		    ,	togglerTip_closed:		"Open This Pane"
		    ,	resizerTip:				"Resize This Pane"
		    //	effect defaults - overridden on some panes
		    ,	fxName:					"slide"		// none, slide, drop, scale
		    ,	fxSpeed_open:			750
		    ,	fxSpeed_close:			1500
		    ,	fxSettings_open:		{ easing: "easeInQuint" }
		    ,	fxSettings_close:		{ easing: "easeOutQuint" }
	}
	,	north: {
	        size:					73
	    ,	spacing_closed:			10			// HIDE resizer & toggler when 'closed'
	    ,	slidable:				true		// REFERENCE - cannot slide if spacing_closed = 0
	    ,	initClosed:				false
	    ,	resizable:				false
	
	}
	,	west: {
	     size:					350
	    ,	spacing_closed:			21			// wider space when closed
	    ,	togglerLength_closed:	21			// make toggler 'square' - 21x21
	    ,	togglerAlign_closed:	"top"		// align to top of resizer
	    ,	togglerLength_open:		0			// NONE - using custom togglers INSIDE west-pane
	    ,	togglerTip_open:		"Close West Pane"
	    ,	togglerTip_closed:		"Open West Pane"
	    ,	resizerTip_open:		"Resize West Pane"
	    ,	slideTrigger_open:		"mouseover" 	// default
	    ,	initClosed:				false
			,	resizable:				true
	    ,	fxName:					"drop"
	    ,	fxSpeed:				"normal"
	    ,	fxSettings:				{ easing: "" } // nullify default easing
	}

	,	center: {
        paneSelector:			"#mainContent" 			// sample: use an ID to select pane instead of a class
	    ,	onresize:				"innerLayout.resizeAll"	// resize INNER LAYOUT when center pane resizes
	    ,	minWidth:				200
	    ,	minHeight:				200
	}
};
