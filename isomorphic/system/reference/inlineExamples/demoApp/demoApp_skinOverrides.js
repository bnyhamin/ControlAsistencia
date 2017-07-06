isc.setAutoDraw(false);

// Custom skinning for this application
isc.TabSet.addProperties({
    styleName:"demoTabSet",
    paneContainerClassName:"demoTabPane",
    showPaneContainerEdges:false,
	paneContainerDefaults:{backgroundColor:"#C7C7C7"},
	tabBarDefaults:{baseLineCapSize:0}
});

isc.DynamicForm.addProperties({
    titleSuffix:"",
    requiredTitleSuffix:"",
    wrapItemTitles:false
});

isc.Canvas.addProperties({
    hoverDelay : 300,
    hoverStyle : "demoHoverStyle",
    hoverOpacity : 90,
    hoverMoveWithMouse : true
});
