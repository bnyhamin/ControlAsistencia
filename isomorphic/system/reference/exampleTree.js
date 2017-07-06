isc.ExampleTree.create({
    ID:"exampleTree",
    nodeVisibility:"sdk",
    root:{
        name:"root/",
        children:[
            {
                screenshot:"screenshots/tabs_ds_code.png",
                screenshotHeight:"176",
                screenshotWidth:"291",
                description:"\n    Welcome to the SmartClient Feature Tour!\n    <BR>\n    <BR>\n    Click on the name of an example in the tree on the left to load it.\n    <BR>\n    <BR>\n    With an example loaded, you can view source code by clicking on the tabs shown above the\n    running example.\n    <BR>\n    <BR>\n    For an overview of how to use this Feature Explorer, including specific instructions for\n    using code shown here in a standalone application, please see the <a target=_blank\n    href='${isc.ExampleViewer.getRefDocsURL()}#featureExplorerOverview'>\n    Feature Explorer Overview</a> topic in the Reference Docs.\n    \n      \n\n",
                isOpen:true,
                title:"Welcome",
                children:[
                    {jsURL:"welcome/helloButton.js", xmlURL:"welcome/helloButton.xml", 
                     description:"\n        A SmartClient <code>IButton</code> component responds to mouse clicks by showing a\n        modal <code>Dialog</code> component with the \"Hello world!\" message.  Source code is\n        provided in both XML and JS formats.\n        ",showSkinSwitcher:true, 
                     title:"Hello World"},
                    {
                        jsURL:"welcome/helloStyled.js",
                        visibility:"sdk",
                        xmlURL:"welcome/helloStyled.xml",
                        description:"\n        This <code>Label</code> component is heavily styled with a combination of CSS class,\n        CSS attribute shortcuts, and SmartClient attributes.  Source code is\n        provided in both XML and JS formats.\n        ",
                        tabs:[
                            {title:"CSS", url:"welcome/helloStyled.css"}
                        ],
                        title:"Hello World (styling)"
                    },
                    {jsURL:"welcome/helloForm.js", visibility:"sdk", xmlURL:"welcome/helloForm.xml", 
                     description:"\n        This SmartClient <code>FormLayout</code> provides a text field and a button control.\n        Type your name in the field, then click the button for a personalized message.\n        Source code is provided in both XML and JS formats.\n        ",title:"Hello You (form)"},
                    {
                        dataSource:"supplyCategory",
                        fullScreen:"true",
                        jsURL:"demoApp/demoAppJS.js",
                        needServer:"true",
                        screenshot:"demoApp/demoApp.png",
                        screenshotHeight:"337",
                        screenshotWidth:"480",
                        xmlURL:"demoApp/demoAppXML.xml",
                        description:"Demonstrates a range of SmartClient GUI components, data binding operations,\n        and layout managers in a single-page application.\n        ",
                        showSkinSwitcher:true,
                        tabs:[
                            {title:"supplyItem", url:"supplyItem.ds.xml"}
                        ],
                        id:"showcaseApp",
                        title:"Showcase Application"
                    },
                    {jsURL:"devConsole/devConsole.js", 
                     description:"\nThe Developer Console is a suite of development tools implemented in SmartClient itself.  The\nConsole runs in its own browser window, parallel to your running application, so it is always\navailable: in every browser, and in every deployment environment.<BR> \nClick on the name of a screenshot below to see more information about developer\nconsole features.\n        ",showSource:false, 
                     title:"Developer Console"},
                    {jsURL:"docs/docs.js", 
                     description:"\n        SmartClient contains over 100 documented components with more than 2000 documented,\n        supported APIs.  All of SmartClient's documentation is integrated into a\n        SmartClient-based, searchable documentation browser, including API reference, concepts,\n        tutorials, live examples, architectural blueprints and deployment instructions.\n        ",showSource:false, 
                     title:"SmartClient Docs"}
                ]
            },
            {
                description:"\n    Basic capabilities shared by all SmartClient visual components.\n",
                isOpen:false,
                title:"Basics",
                children:[
                    {
                        description:"\n    Basic capabilities shared by all SmartClient visual components.\n",
                        isOpen:false,
                        title:"Components",
                        children:[
                            {jsURL:"basics/create.js", 
                             description:"\n        Click the button to create new cube objects.\n        ",id:"create", 
                             title:"Create"},
                            {jsURL:"basics/draw.js", 
                             description:"\n        Click the button to draw another Label component. The first Label is configured\n        to draw automatically.\n        ",id:"autodraw", 
                             title:"Draw"},
                            {jsURL:"basics/show.js", 
                             description:"\n        Click the buttons to show or hide the message.\n        ",id:"showAndHide", 
                             title:"Show & Hide"},
                            {jsURL:"basics/move.js", 
                             description:"\n        Click and hold the arrow to move the van. Click on the solid circle to return to\n        the starting position.\n        ",id:"move", 
                             title:"Move"},
                            {jsURL:"basics/resize.js", 
                             description:"\n        Click the buttons to expand or collapse the text box.\n        ",id:"resize", 
                             title:"Resize"},
                            {jsURL:"basics/layer.js", 
                             description:"\n        Click the buttons to move the draggable box above or below the other boxes.\n        ",id:"layer", 
                             title:"Layer"},
                            {jsURL:"basics/stack.js", 
                             description:"\n        <code>HStack</code> and <code>VStack</code> containers manage the stacked positions\n        of multiple member components.\n        ",title:"Stack"},
                            {jsURL:"basics/layout.js", 
                             description:"\n        <code>HLayout</code> and <code>VLayout</code> containers manage the stacked positions and\n        sizes of multiple member components. Resize the browser window to reflow these layouts.\n        ",title:"Layout"},
                            {
                                doEval:"false",
                                iframe:"true",
                                url:"inlineComponents/inlineComponents.html",
                                description:"\n        SmartClient GUI components are assembled from the same standard HTML and CSS as\n        plain old web pages. So you can add SmartClient controls above, below, inline,\n        and inside your existing web page elements.\n        ",
                                tabs:[
                                    {title:"cssLayout.css", url:"inlineComponents/cssLayout.css"}
                                ],
                                id:"inlineComponents",
                                title:"Inline components"
                            }
                        ]
                    },
                    {
                        description:"\n    Mixing SmartClient components with HTML pages, chunks, and elements.\n",
                        isOpen:false,
                        title:"HTML",
                        children:[
                            {ref:"inlineComponents", title:"Inline Components"},
                            {description:"\n        SmartClient supports browser history management.  Click your browser's Back button to go\n        to a previous example, and click forward to return to this example.  You can even\n        navigate off the SmartClient site and navigate back.  SmartClient's History module\n        allows you to pick which application events create history entries.\n        ", 
                             title:"Back Button"},
                            {jsURL:"html/htmlFlow.js", xmlURL:"html/htmlFlow.xml", 
                             description:"\n        The <code>HTMLFlow</code> component displays a chunk of standard HTML in a free-form,\n        flowable region.\n        ",id:"htmlFlow", 
                             title:"HTMLFlow"},
                            {jsURL:"html/htmlPane.js", xmlURL:"html/htmlPane.xml", 
                             description:"\n        The <code>HTMLPane</code> component displays a chunk or page of standard HTML in a\n        sizeable, scrollable pane.\n        ",id:"htmlPane", 
                             title:"HTMLPane"},
                            {jsURL:"html/htmlLabel.js", xmlURL:"html/htmlLabel.xml", 
                             description:"\n        The <code>Label</code> component adds alignment, text wrapping, and icon support for\n        small chunks of standard HTML.\n        ",id:"label", 
                             title:"Label"},
                            {jsURL:"html/richTextEditor.js", requiresModules:"RichTextEditor", 
                             xmlURL:"html/richTextEditor.xml",description:"RichTextEditor supports editing of HTML with a configurable set of\n       styling controls", 
                             id:"RichTextEditor",title:"Editing HTML"},
                            {jsURL:"html/htmlImg.js", 
                             description:"\n        The <code>Img</code> component displays images in the standard web formats\n        (png, gif, jpg) and other image formats supported by the web browser.\n        ",id:"img", 
                             title:"Img"},
                            {jsURL:"html/htmlDynamic1.js", 
                             description:"\n        Embed JavaScript expressions inside chunks of HTML to create simple dynamic elements.\n        ",id:"dynamicContents", 
                             title:"Dynamic HTML (inline)"},
                            {jsURL:"html/htmlDynamic2.js", 
                             description:"\n        Click the buttons to display different chunks of HTML.\n        ",id:"setContents", 
                             title:"Dynamic HTML (set)"},
                            {jsURL:"html/htmlLoadImg.js", 
                             description:"\n        Click the buttons to load different images.\n        ",id:"loadImages", 
                             title:"Load images"},
                            {jsURL:"html/htmlLoadChunks.js", 
                             description:"\n        Click the buttons to load different chunks of HTML.\n        ",title:"Load HTML chunks"},
                            {jsURL:"html/htmlLoadPages.js", 
                             description:"\n        Click the buttons to display different websites.\n        ",id:"loadHtmlPages", 
                             title:"Load HTML pages"}
                        ]
                    },
                    {
                        description:"\n    Basic interactive component capabilities.\n    <BR>\n    <BR>\n    SmartClient components provide hundreds of hooks for event handlers, including\n    all the standard mouse, keyboard, and communication events.\n",
                        isOpen:false,
                        title:"Interaction",
                        children:[
                            {jsURL:"interact/mouseEvents.js", 
                             description:"\n        Mouse over the blue square to see the color respond to your position.  Click and hold\n        to see a fade.  If you have a mousewheel, roll up and down to change size.\n        SmartClient components support the standard mouse events in addition to custom events\n        like \"mouseStillDown\".\n        ",id:"customMouseEvents", 
                             title:"Mouse events"},
                            {jsURL:"interact/dragEvents.js", 
                             description:"\n        Click and drag the pawn over \"Show Drop Reticle\" to see a simple custom drag and drop\n        interaction.\n        ",id:"customDrag", 
                             title:"Drag events"},
                            {css:"interact/hover.css", jsURL:"interact/hover.js", 
                             description:"\n        Hover over the button, the image, the \"Interesting Facts\" field of the grid, and the\n        \"Severity\" form label to see various hovers.\n        ",showSkinSwitcher:true, 
                             id:"customHovers",title:"Hovers / Tooltips"},
                            {jsURL:"interact/contextmenu.js", 
                             description:"\n        Right click (or option-click on Macs) on the Yin Yang image to access a context menu.\n        You can also click on the \"Widget\" button to access the identical menu.\n        ",showSkinSwitcher:true, 
                             id:"contextMenus",title:"Context menus"},
                            {ref:"fieldEnableDisable", title:"Enable / Disable"},
                            {jsURL:"interact/focus.js", 
                             description:"\n        Press the Tab key to cycle through through the tab order starting from the blue\n        piece.  Then drag reorder either piece, click on the leftmost piece and use Tab to\n        cycle through again. Tab order is automatically updated to reflect the visual order.\n        ",id:"focus", 
                             title:"Focus & Tabbing"},
                            {jsURL:"interact/cursor.js", 
                             description:"\n        Mouse over the draggable labels for a 4-way move cursor.  Move over drag resizeable\n        edges to see resize cursors.  Mouse over the \"Save\" button to see the hand cursor,\n        which is not shown if the \"Save\" button is disabled.\n        ",id:"cursors", 
                             title:"Cursors"},
                            {jsURL:"interact/keyboard.js", 
                             description:"\n        Click the \"Move Me\" label, then use the arrow keys to move it around.  Hold down keys to see the\n        component respond to key repetition. SmartClient unifies keyboard event handling across browsers.\n        ",id:"keyboardEvents", 
                             title:"Keyboard events"},
                            {jsURL:"interact/modality.js", 
                             description:"\n        Click on \"Show Window\" to show a modal window.  Note that the \"Touch This\" button no\n        longer shows rollovers or an interactive cursor, nothing outside the window can be\n        clicked, clicks outside the window cause the window to flash, and tabbing remains in a\n        closed loop cycling through only the contents of the window.\n        ",showSkinSwitcher:true, 
                             id:"modality",title:"Modality"}
                        ]
                    }
                ]
            },
            {
                description:"\n    Effects for creating a polished, branded, appealing application.\n    <BR>\n    <BR>\n    SmartClient supports rich skinning and styling capabilities, drag and drop interactions,\n    and built-in animations.\n",
                isOpen:true,
                title:"Effects",
                children:[
                    {
                        description:"\n    Drag & drop services and built-in drag & drop interactions.\n",
                        isOpen:false,
                        title:"Drag & Drop",
                        children:[
                            {
                                jsURL:"dragdrop/dragListCopy.js",
                                description:"\n        Drag and drop to copy items from the first list to the second list.\n        You can drag over the top or bottom edge of a scrolling list to scroll\n        in that direction before dropping.\n        ",
                                showSkinSwitcher:true,
                                tabs:[
                                    {title:"exampleData", url:"dragdrop/dragList_data.js"}
                                ],
                                title:"Drag list (copy)"
                            },
                            {
                                jsURL:"dragdrop/dragListMove.js",
                                description:"\n        Drag and drop to move items within or between the lists.\n        You can drag over the top or bottom edge of a scrolling list to scroll\n        in that direction before dropping.\n        ",
                                showSkinSwitcher:true,
                                tabs:[
                                    {title:"exampleData", url:"dragdrop/dragList_data.js"}
                                ],
                                id:"dragListMove",
                                title:"Drag list (move)"
                            },
                            {
                                jsURL:"dragdrop/dragListSelect.js",
                                description:"\n        Drag to select items in the first list. The second list will\n        mirror your selection.\n        ",
                                showSkinSwitcher:true,
                                tabs:[
                                    {title:"exampleData", url:"dragdrop/dragList_data.js"}
                                ],
                                id:"dragListSelect",
                                title:"Drag list (select)"
                            },
                            {
                                jsURL:"dragdrop/dragTreeMove.js",
                                description:"\n        Drag and drop to move parts and folders within and between the trees.\n        You can open a closed folder by pausing over it during a drag interaction\n        (aka \"spring loaded folders\").\n        ",
                                showSkinSwitcher:true,
                                tabs:[
                                    {title:"exampleData", url:"dragdrop/dragTree_data.js"}
                                ],
                                id:"dragTree",
                                title:"Drag tree (move)"
                            },
                            {jsURL:"dragdrop/dragMove.js", 
                             description:"\n        Drag and drop to move pieces between the boxes. The green box sets a thicker green\n        \"drop line\" indicator to match its border. The blue box shows a \"drag placeholder\"\n        outline at the original location of the dragged object while dragging.\n        ",id:"dragMove", 
                             title:"Drag move"},
                            {jsURL:"dragdrop/dragReorder.js", 
                             description:"\n        Drag and drop to rearrange the order of the pieces.\n        ",title:"Drag reorder"},
                            {jsURL:"dragdrop/dragTypes.js", 
                             description:"\n        Drag and drop to move pieces between the three boxes.\n        The gray box accepts any piece.\n        The blue and green boxes accept pieces of the same color only.\n        ",title:"Drag types"},
                            {jsURL:"dragdrop/dragCreate.js", 
                             description:"\n        Drag the large cubes into the boxes to create new small cubes.\n        The blue, yellow, and green boxes accept cubes with the same color only.\n        The gray box accepts any color.\n        Right-click on the small cubes to remove them from the boxes.\n        ",id:"dragCreate", 
                             title:"Drag create"},
                            {jsURL:"dragdrop/dragEffects.js", 
                             description:"\n        Click and drag to move the labels.\n        ",id:"dragEffects", 
                             title:"Drag effects"},
                            {jsURL:"dragdrop/dragReposition.js", visibility:"sdk", 
                             description:"\n        Click and drag to move the piece.\n        ",title:"Drag reposition"},
                            {jsURL:"dragdrop/dragResize.js", 
                             description:"\n        Click and drag on the edges of the labels to resize.\n        ",id:"dragResize", 
                             title:"Drag resize"},
                            {jsURL:"dragdrop/dragTracker.js", 
                             description:"\n        Drag and drop the pieces onto the box.\n        ",id:"dragTracker", 
                             title:"Drag tracker"},
                            {jsURL:"dragdrop/dragPan.js", 
                             description:"\n        Click and drag to pan the image inside its frame.\n        ",id:"dragPan", 
                             title:"Drag pan"}
                        ]
                    },
                    {
                        description:"\n    Animation services and built-in animation effects.\n",
                        isOpen:false,
                        title:"Animation",
                        children:[
                            {
                                jsURL:"animate/animateTree.js",
                                description:"\n        Click the open/close icon for any folder.\n        ",
                                showSkinSwitcher:true,
                                tabs:[
                                    {title:"exampleData", url:"animate/animateTreeData.js"}
                                ],
                                id:"animateTree",
                                title:"Animate tree"
                            },
                            {jsURL:"animate/animateMinimize.js", 
                             description:"\n        Click on the minimize button (round button in header with flat line).\n        ",showSkinSwitcher:true, 
                             id:"windowMinimize",title:"Animate minimize"},
                            {jsURL:"animate/animateSections.xml", 
                             description:"\n        Click on any section header to expand/collapse sections.\n        ",showSkinSwitcher:true, 
                             id:"animateSections",title:"Animate sections"},
                            {jsURL:"animate/animateLayout.js", 
                             description:"\n        Click on the buttons to hide and show the green star.\n        ",id:"animateLayout", 
                             title:"Animate layout"},
                            {jsURL:"animate/animateMove.js", 
                             description:"\n        Click the buttons to move the Label into view or out of view.\n        ",id:"animateMove", 
                             title:"Animate move"},
                            {jsURL:"animate/animateResize.js", 
                             description:"\n        Click the buttons to expand or collapse the text box.\n        ",id:"animateResize", 
                             title:"Animate resize"},
                            {jsURL:"animate/animateWipe.js", 
                             description:"\n        Click the buttons to show or hide the Label with a \"wipe\" effect.\n        ",id:"animateWipe", 
                             title:"Animate wipe"},
                            {jsURL:"animate/animateSlide.js", 
                             description:"\n        Click the buttons to show or hide the Label with a \"slide\" effect.\n        ",title:"Animate slide"},
                            {jsURL:"animate/animateZoom.js", 
                             description:"\n        Click the buttons to zoom or shrink the image.\n        ",id:"animateZoom", 
                             title:"Animate zoom"},
                            {jsURL:"animate/animateFade.js", 
                             description:"\n        Click the buttons to fade the image.\n        ",id:"animateFade", 
                             title:"Animate fade"},
                            {jsURL:"animate/animateSeqSimple.js", 
                             description:"\n        Click the buttons for a 2-stage expand or collapse effect.\n        ",title:"Animate sequence (simple)"},
                            {jsURL:"animate/animateSeqComplex.js", 
                             description:"\n        Click to select and zoom each piece.\n        ",title:"Animate sequence (complex)"},
                            {jsURL:"animate/animateCustom.js", 
                             description:"\n        Click on the globe for a custom \"orbit\" animation.\n        ",id:"customAnimation", 
                             title:"Animate custom"}
                        ]
                    },
                    {
                        description:"\n    Apply rich visual styles to SmartClient components.\n",
                        isOpen:false,
                        title:"Look & Feel",
                        children:[
                            {jsURL:"lookfeel/edges.js", 
                             description:"\n        Drag the text boxes. These boxes show customized frame and glow effects\n        using edge images.\n        ",id:"edges", 
                             title:"Edges"},
                            {jsURL:"lookfeel/corners.js", 
                             description:"\n        Drag the text boxes. These boxes show customized rounded-corner effects\n        using edge images.        \n        ",id:"corners", 
                             title:"Corners"},
                            {jsURL:"lookfeel/shadows.js", 
                             description:"\n        Drag the slider to change the shadow depth for the text box.\n        ",id:"shadows", 
                             title:"Shadows"},
                            {jsURL:"lookfeel/bgColor.js", visibility:"sdk", 
                             description:"\n        Click on the color picker to select a background color for the box.\n        ",title:"Background color"},
                            {jsURL:"lookfeel/bgImage.js", visibility:"sdk", 
                             description:"\n        Click any button to change the background texture for the box.\n        ",title:"Background texture"},
                            {jsURL:"lookfeel/opacity.js", 
                             description:"\n        Drag the slider to change opacity.\n        ",id:"translucency", 
                             title:"Translucency"},
                            {jsURL:"lookfeel/boxAttrs.js", visibility:"sdk", 
                             description:"\n        Drag the sliders to change the CSS box attributes.\n        ",title:"Box attributes"},
                            {
                                jsURL:"lookfeel/styles.js",
                                description:"\n        Click the radio buttons to apply different CSS styles to the text. Click the CSS tab for\n        CSS class definitions.<BR>\n        This container auto-sizes to the styled text.\n        ",
                                tabs:[
                                    {title:"CSS", url:"lookfeel/styles.css"}
                                ],
                                id:"styles",
                                title:"CSS styles"
                            },
                            {css:"lookfeel/consistentSizing.css", 
                             jsURL:"lookfeel/consistentSizing.js",description:"\n      Drag the slider to resize all three text boxes. The box sizes match despite different\n      edge styling specified in CSS, enabling CSS-based skinning without affecting\n      application layout.\n    ", 
                             id:"consistentSizing",title:"Consistent sizing"},
                            {
                                jsURL:"grids/formatting/cellStyles.js",
                                description:"\n        Mouse over the rows and click-drag to select rows, to see the effects of different\n        base styles on these two grids.\n        ",
                                showSkinSwitcher:true,
                                tabs:[
                                    {title:"CSS", url:"grids/formatting/cellStyles.css"},
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"gridCells",
                                title:"Grid cells"
                            }
                        ]
                    }
                ]
            },
            {
                showSkinSwitcher:"true",
                description:"\n    High-performance interactive data grids.\n",
                isOpen:true,
                title:"Grids",
                children:[
                    {
                        description:"\n    Styling, sizing and formatting options for grids, as well as built-in end user controls.\n",
                        isOpen:false,
                        title:"Appearance",
                        children:[
                            {
                                jsURL:"grids/layout/columnOrder.js",
                                description:"\n        Drag and drop the column headers to rearrange columns in the grid.\n        Right-click the column headers to hide or show columns.\n        Click the buttons to hide or show the \"Capital\" column.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"columnOrder",
                                title:"Column order"
                            },
                            {
                                jsURL:"grids/layout/columnSize.js",
                                description:"\n        Click and drag between the column headers to resize columns in the grid.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"columnSize",
                                title:"Column size"
                            },
                            {
                                jsURL:"grids/layout/columnAlign.js",
                                visibility:"sdk",
                                description:"\n        Click the radio buttons to change the alignment of the \"Flag\" column.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                title:"Column align"
                            },
                            {
                                jsURL:"grids/layout/columnHeaders.js",
                                visibility:"sdk",
                                description:"\n        Click the buttons to show or hide the column headers.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                title:"Column headers"
                            },
                            {
                                jsURL:"grids/layout/columnTitles.js",
                                visibility:"sdk",
                                description:"\n        Click the buttons to change the title of the \"Country\" column.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                title:"Column titles"
                            },
                            {
                                jsURL:"grids/layout/multiLineValues.js",
                                description:"\n        Click and drag between the \"Background\" and \"Flag\" column headers, or resize your browser\n        window to change the size of the entire grid. The \"Background\" values are\n        confined to a fixed row height.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryDataDetail.js"}
                                ],
                                id:"multilineValues",
                                title:"Multiline values"
                            },
                            {
                                jsURL:"grids/layout/autoFitValues.js",
                                description:"\n        Click and drag between the \"Background\" and \"Flag\" column headers, or resize your browser\n        window to change the size of the entire grid. The rows resize to fit\n        the \"Background\" values.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryDataDetail.js"}
                                ],
                                id:"autofitValues",
                                title:"Autofit values"
                            },
                            {
                                jsURL:"grids/layout/autoFitRows.js",
                                description:"\n        Click the buttons to show different numbers of records. The grid resizes to fit\n        all rows without scrolling.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"autofitRows",
                                title:"Autofit rows"
                            },
                            {
                                jsURL:"grids/formatting/cellStyles.js",
                                description:"\n        Mouse over the rows and click-drag to select rows, to see the effects of different\n        base styles on these two grids.\n        ",
                                tabs:[
                                    {title:"CSS", url:"grids/formatting/cellStyles.css"},
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                title:"Cell styles"
                            },
                            {
                                jsURL:"grids/formatting/addStyle.js",
                                description:"\n        This grid hilites \"Population\" values greater than 1 billion or less than 50 million\n        using additive style attributes (text color and weight).\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"addStyle",
                                title:"Hilite cells (add style)"
                            },
                            {
                                jsURL:"grids/formatting/replaceStyle.js",
                                description:"\n        This grid hilites \"Population\" values greater than 1 billion or less than 50 million\n        using a full set of compound styles (with customized background colors). Mouse over or\n        click-drag rows to see how these styles apply to different row states.\n        ",
                                tabs:[
                                    {title:"CSS", url:"grids/formatting/replaceStyle.css"},
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"replaceStyle",
                                title:"Hilite cells (replace style)"
                            },
                            {
                                jsURL:"grids/formatting/formatValues.js",
                                description:"\n        This grid applies custom formatters to the \"Nationhood\" and \"Area\" columns.\n        Click on the \"Nationhood\" or \"Area\" column headers to sort the underlying data values.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"formatValues",
                                title:"Format values"
                            },
                            {
                                jsURL:"grids/formatting/emptyValues.js",
                                description:"\n        Double-click any cell, delete its value, and press Enter or click outside the cell to\n        save and display the empty value. This grid shows \"--\" for empty date values, and\n        \"unknown\" for other empty values.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"emptyValues",
                                title:"Empty values"
                            },
                            {
                                jsURL:"grids/layout/emptyGrid.js",
                                description:"\n        Click the buttons to add or remove all data in the grid.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"emptyGrid",
                                title:"Empty grid"
                            }
                        ]
                    },
                    {
                        description:"\n    Selection and drag and drop of data, hovers, and grid events.\n",
                        isOpen:false,
                        title:"Interaction",
                        children:[
                            {
                                jsURL:"grids/interaction/rollover.js",
                                visibility:"sdk",
                                description:"\n        Move the mouse over rows in the grid to see rollover highlights.\n        Click the buttons to enable or disable this behavior.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                title:"Rollover"
                            },
                            {
                                jsURL:"grids/selection/singleSelect.js",
                                visibility:"sdk",
                                description:"\n        Click to select any single row in the grid.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                title:"Single select"
                            },
                            {
                                jsURL:"grids/selection/simpleSelect.js",
                                visibility:"sdk",
                                description:"\n        Click to select or deselect any row in the grid.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                title:"Simple select"
                            },
                            {
                                jsURL:"grids/selection/multipleSelect.js",
                                description:"\n        Click to select a single row in the grid. Shift-click to select a continuous range of rows.\n        Ctrl-click to add or remove individual rows from the selection.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"multipleSelect",
                                title:"Multiple select"
                            },
                            {
                                jsURL:"grids/selection/dragSelect.js",
                                description:"\n        Click and drag to select a continuous range of rows in the grid.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                title:"Drag select"
                            },
                            {
                                jsURL:"grids/interaction/valueHover.js",
                                description:"\n        Move the mouse over a value in the \"Government\" column and pause (hover) for a\n        longer description of that value.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"valueHoverTips",
                                title:"Value hover tips"
                            },
                            {
                                jsURL:"grids/interaction/headerHover.js",
                                description:"\n        Move the mouse over a column header and pause (hover) for a longer description\n        of that column.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                title:"Header hover tips"
                            },
                            {
                                jsURL:"grids/interaction/dragOrder.js",
                                description:"\n        Drag and drop to change the order of countries in this list.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"gridsDragReorder",
                                title:"Drag reorder"
                            },
                            {
                                jsURL:"grids/interaction/dragMove.js",
                                description:"\n        Drag and drop to move rows between the two lists. \n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"gridsDragMove",
                                title:"Drag move"
                            },
                            {
                                jsURL:"grids/interaction/dragCopy.js",
                                description:"\n        Drag and drop to copy rows from the first list to the second list.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"gridsDragCopy",
                                title:"Drag copy"
                            },
                            {
                                jsURL:"grids/interaction/disabled.js",
                                description:"\n        Mouse over, drag, or click on any values in this grid.\n        All \"Europe\" country records in this grid are disabled.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"disabledRows",
                                title:"Disabled rows"
                            },
                            {
                                jsURL:"grids/interaction/recordClicks.js",
                                description:"\n        Click, double-click, or right-click any row in the grid.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"recordClicks",
                                title:"Record clicks"
                            },
                            {
                                jsURL:"grids/interaction/cellClicks.js",
                                description:"\n        Click, double-click, or right-click any value in the grid.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"cellClicks",
                                title:"Cell clicks"
                            }
                        ]
                    },
                    {
                        description:"\n    SmartClient grids provide interactive sorting of standard and custom data types,\n    with automatic client/server coordination.\n",
                        isOpen:false,
                        title:"Sort & Filter",
                        children:[
                            {
                                jsURL:"grids/sorting/sort.js",
                                description:"\n        Click on any column header to sort by that column. To reverse the sort direction,\n        click on the same column header, or the top-right corner of the grid.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"sort",
                                title:"Sort"
                            },
                            {
                                jsURL:"grids/sorting/disableSort.js",
                                visibility:"sdk",
                                description:"\n        Sorting is disabled on the \"Flag\" column. Click on any other column header to sort\n        on the corresponding column.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                title:"Disable sort"
                            },
                            {
                                jsURL:"grids/sorting/sortArrow.js",
                                visibility:"sdk",
                                description:"\n        Click on any column header to sort or reverse-sort by that column.\n        This grid shows the sort-direction arrow in the top-right corner only.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                title:"Sort arrows"
                            },
                            {
                                jsURL:"grids/sorting/dataTypes.js",
                                description:"\n        Click on any column header to sort by that column.\n        The \"Nationhood\", \"Area\", and \"GDP (per capita)\" columns are sorted as date, number, and\n        calculated number values, respectively.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"dataTypes",
                                title:"Data types"
                            },
                            {
                                jsURL:"grids/filtering/filter.js",
                                description:"\n        Type \"island\" above the Country column, then press Enter or click the filter button\n        (top-right corner of the grid) to show only countries with \"island\" in their name.\n        Select \"North America\" above the Continent column to filter countries by that continent.\n        ",
                                tabs:[
                                    {canEdit:"false", title:"worldDS", 
                                     url:"grids/ds/worldSQLDS.ds.xml"}
                                ],
                                id:"filter",
                                title:"Filter"
                            },
                            {
                                jsURL:"grids/filtering/disable.js",
                                description:"\n        Type \"island\" above the Country column, then press Enter or click the filter button\n        (top-right corner of the grid) to show only countries with \"island\" in their name.\n        Select \"North America\" above the Continent column to filter countries by that continent.\n        Filtering is disabled on the \"Flag\" and \"Capital\" columns.\n        ",
                                tabs:[
                                    {canEdit:"false", title:"worldDS", 
                                     url:"grids/ds/worldSQLDS.ds.xml"}
                                ],
                                id:"disableFilter",
                                title:"Disable filter"
                            }
                        ]
                    },
                    {
                        description:"\n    SmartClient grids provide inline editing of all data types, with automatic validation and\n    client/server updates.<br><br>\n    These examples are all bound to the same remote DataSource, so your\n    changes are saved on SmartClient.com and will appear in all Grid Editing examples during this\n    session. To end your SmartClient.com session and reset the example data on the server, simply\n    close all instances of your web browser.\n",
                        isOpen:false,
                        title:"Editing",
                        children:[
                            {
                                jsURL:"grids/editing/editRows.js",
                                description:"\n        <b>Click</b> on any cell to start editing. Use <b>Tab</b>, <b>Shift-Tab</b>,\n        <b>Up Arrow</b>, and <b>Down Arrow</b> to move between cells. Changes are saved\n        automatically when you move to another row. Press <b>Enter</b> to save the current row\n        and dismiss the editors, or <b>Esc</b> to discard changes for the current row and dismiss\n        the editors.\n        ",
                                tabs:[
                                    {canEdit:"false", title:"countryDS", 
                                     url:"grids/ds/countrySQLDS.ds.xml"}
                                ],
                                id:"editByRow",
                                title:"Edit by row"
                            },
                            {
                                jsURL:"grids/editing/editCells.js",
                                description:"\n        <b>Click</b> on any cell to start editing. Use <b>Tab</b>, <b>Shift-Tab</b>,\n        <b>Up Arrow</b>, and <b>Down Arrow</b> to move between cells. Press <b>Enter</b> to save\n        the current row and dismiss the editors, or <b>Esc</b> to discard changes for the current\n        cell and dismiss the editors.\n        ",
                                tabs:[
                                    {canEdit:"false", title:"countryDS", 
                                     url:"grids/ds/countrySQLDS.ds.xml"}
                                ],
                                id:"editByCell",
                                title:"Edit by cell"
                            },
                            {
                                jsURL:"grids/editing/enterRows.js",
                                description:"\n        <b>Click</b> on any cell to start editing, then <b>Tab</b> or <b>Down Arrow</b> past the\n        last row in the grid to create a new row. Alternatively, click the <b>Edit New</b> button\n        to create a new data-entry row at the end of the grid.\n        ",
                                tabs:[
                                    {canEdit:"false", title:"countryDS", 
                                     url:"grids/ds/countrySQLDS.ds.xml"}
                                ],
                                id:"enterNewRows",
                                title:"Enter new rows"
                            },
                            {
                                jsURL:"grids/editing/modalEditing.js",
                                description:"\n        <b>Double-click</b> on any cell to start editing. Click anywhere outside of the cell\n        editors to save changes, or press the <b>Esc</b> key to discard changes.\n        ",
                                tabs:[
                                    {canEdit:"false", title:"countryDS", 
                                     url:"grids/ds/countrySQLDS.ds.xml"}
                                ],
                                id:"modalEditing",
                                title:"Modal editing"
                            },
                            {
                                jsURL:"grids/editing/disableEditing.js",
                                description:"\n        <b>Click</b> on any cell to start editing. Use Tab/Arrow keys to move between cells,\n        Enter/Esc keys to save or cancel. Editing is disabled for the \"Country\" and \"G8\" columns.\n        ",
                                tabs:[
                                    {canEdit:"false", title:"countryDS", 
                                     url:"grids/ds/countrySQLDS.ds.xml"}
                                ],
                                id:"disableEditing",
                                title:"Disable editing"
                            },
                            {
                                jsURL:"grids/editing/customEditors.js",
                                description:"\n        <b>Click</b> on any cell to start editing. The \"Government\", \"Population\", and \"Nationhood\"\n        columns specify custom editors: a multiple-line textarea, a numeric spinner, and a compound\n        date control.\n        ",
                                tabs:[
                                    {canEdit:"false", title:"countryDS", 
                                     url:"grids/ds/countrySQLDS.ds.xml"}
                                ],
                                id:"customEditors",
                                title:"Custom editors"
                            },
                            {
                                jsURL:"grids/editing/validation.js",
                                description:"\n        <b>Click</b> on any cell to start editing. Delete the value in a \"Country\" cell, or type a\n        non-numeric value in a \"Population\" cell, to see validation errors.\n        ",
                                tabs:[
                                    {canEdit:"false", title:"countryDS", 
                                     url:"grids/ds/countrySQLDS.ds.xml"}
                                ],
                                id:"dataValidation",
                                title:"Data validation"
                            },
                            {jsURL:"grids/editing/dependentSelects.js", 
                             description:"\n        \n        <b>Double Click</b> on any row to start editing. Select a value in the \"Division\" column\n        to change the set of options available in the \"Department\" column.\n        ",title:"Dependent Selects"},
                            {
                                jsURL:"grids/editing/databoundDependentSelects.js",
                                description:"\n        \n        Click the <b>Order New Item</b> button to add an editable row to the grid.\n        Select a Category in the second column to change the set of options available in \n        the \"Item\" column.\n        ",
                                tabs:[
                                    {dataSource:"supplyItem", name:"supplyItem"},
                                    {dataSource:"supplyCategory", name:"supplyCategory"}
                                ],
                                title:"Databound Dependent Selects"
                            }
                        ]
                    },
                    {
                        description:"\n    SmartClient supports rendering out grids with frozen fields.<br><br>\n    Frozen fields are fields that do not scroll horizontally with the other fields, remaining\n    visible on the screen while other fields may be scrolled out of view.\n",
                        isOpen:false,
                        title:"Frozen Columns",
                        children:[
                            {
                                jsURL:"grids/freezeFields/simpleFreeze.js",
                                description:"\n        Setting <code>frozen:true</code> on a column definition establishes a\n        frozen column.  Column resize and reorder work normally.\n        ",
                                tabs:[
                                    {dataSource:"supplyItem", name:"supplyItem"}
                                ],
                                id:"simpleFreeze",
                                title:"Simple Freeze"
                            },
                            {
                                jsURL:"grids/freezeFields/dynamicFreeze.js",
                                description:"\n        Right click on any column header to show a menu that allows you to freeze\n        that column.<br>\n        Multiple columns may be frozen, and frozen columns may be\n        reordered.<br>\n        Right click on a frozen column to unfreeze it.\n        ",
                                tabs:[
                                    {dataSource:"supplyItem", name:"supplyItem"}
                                ],
                                id:"dynamicFreeze",
                                title:"Dynamic Freeze"
                            },
                            {
                                jsURL:"grids/freezeFields/freezeEditing.js",
                                description:"\n        SmartClient's inline editing support works normally with frozen columns\n        with no further configuration.\n        ",
                                tabs:[
                                    {dataSource:"supplyItem", name:"supplyItem"}
                                ],
                                id:"canEditFreeze",
                                title:"Editing"
                            },
                            {
                                jsURL:"grids/freezeFields/freezeDragDrop.js",
                                description:"\n        SmartClient's drag and drop support works normally with frozen columns\n        with no further configuration.  Drag countries within grids to reorder them, or between\n        grids to move countries back and forth.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/splitCountryData.js"}
                                ],
                                title:"Drag and Drop"
                            }
                        ]
                    },
                    {
                        isopen:"false",
                        description:"\n    List entries can be grouped according to field value.\n    ",
                        title:"Grouping",
                        children:[
                            {
                                jsURL:"grids/grouping/dynamicGrouping.js",
                                description:"\n        Right click on any column header to show a menu that allows you to group by that \n        column. Right click and pick \"ungroup\" to go back to a flat listing.\n        ",
                                tabs:[
                                    {canEdit:"false", title:"countryDS", 
                                     url:"grids/ds/countrySQLDS.ds.xml"}
                                ],
                                id:"dynamicGrouping",
                                title:"Dynamic Grouping"
                            },
                            {
                                jsURL:"grids/grouping/groupedEditing.js",
                                description:"\n        Inline editing works normally with grouped data. Try editing the field that records \n        are grouped by and notice that the record moves to its new group automatically.\n        ",
                                tabs:[
                                    {canEdit:"false", title:"countryDS", 
                                     url:"grids/ds/countrySQLDS.ds.xml"}
                                ],
                                id:"groupedEditing",
                                title:"Grouped Editing"
                            },
                            {
                                jsURL:"grids/grouping/customGrouping.js",
                                description:"\n        You can specify custom grouping behavior for a field. Group by the Nationhood and \n        Population fields to see examples of custom grouping.\n        ",
                                tabs:[
                                    {canEdit:"false", title:"countryDS", 
                                     url:"grids/ds/countrySQLDS.ds.xml"}
                                ],
                                id:"customGrouping",
                                title:"Custom Grouping"
                            }
                        ]
                    },
                    {
                        description:"\n    Built-in display and editing behaviors for common data types, and how to customize them.\n",
                        isOpen:false,
                        id:"gridsDataTypes",
                        title:"Data types",
                        children:[
                            {
                                jsURL:"grids/dataTypes/text.js",
                                description:"\n        Click on column headers to sort, or data values to edit.\n        All fields in this grid are text fields.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                title:"Text"
                            },
                            {
                                jsURL:"grids/dataTypes/image.js",
                                description:"\n        \"Flag\" is an image field.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"imageType",
                                title:"Image"
                            },
                            {
                                jsURL:"grids/dataTypes/longtext.js",
                                description:"\n        Click on data values to edit.\n        \"Government\" is a long text field with a popup editor.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"longText",
                                title:"Long Text"
                            },
                            {
                                jsURL:"grids/dataTypes/date.js",
                                description:"\n        Click on column headers to sort, or data values to edit.\n        \"Nationhood\" is a date field.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                title:"Date"
                            },
                            {
                                jsURL:"grids/dataTypes/integer.js",
                                description:"\n        Click on column headers to sort, or data values to edit.\n        \"Population\" is an integer field.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                title:"Integer"
                            },
                            {
                                jsURL:"grids/dataTypes/decimal.js",
                                description:"\n        Click on column headers to sort, or data values to edit.\n        \"GDP\" is a decimal (aka float) field.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                title:"Decimal"
                            },
                            {
                                jsURL:"grids/dataTypes/boolean.js",
                                description:"\n        Click on column headers to sort, or data values to edit.\n        \"G8\" is a boolean (true/false) field.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                title:"Boolean"
                            },
                            {
                                jsURL:"grids/dataTypes/linkText.js",
                                description:"\n        Click on the values in the \"Info\" column to open external links.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                title:"Link (text)"
                            },
                            {
                                jsURL:"grids/dataTypes/linkImage.js",
                                description:"\n        Click on the book images in the \"Info\" column to open external links.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"linkImage",
                                title:"Link (image)"
                            },
                            {
                                jsURL:"grids/dataTypes/list.js",
                                description:"\n        Click on column headers to sort, or data values to edit.\n        \"Continent\" is a list (aka valueMapped) field.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"listType",
                                title:"List"
                            },
                            {
                                jsURL:"grids/dataTypes/calculated.js",
                                description:"\n        Click on column headers to sort, or data values to edit.\n        \"GDP (per capita)\" is calculated from the \"GDP\" and \"Population\" fields.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"calculatedCellValue",
                                title:"Calculated"
                            }
                        ]
                    },
                    {
                        description:"\n    How to bind grids to DataSources to share field (column) definitions with other components,\n    and how to load data from local and remote data sources and services.    \n",
                        isOpen:false,
                        id:"gridsDataBinding",
                        title:"Data binding",
                        children:[
                            {
                                jsURL:"grids/dataBinding/fieldsGrid.js",
                                description:"\n        This <code>ListGrid</code> takes its field (column) settings from the \"fields\"\n        property of the component definition only. This technique is appropriate for\n        presentation-only grids that do not require data binding.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"listGridFields",
                                title:"ListGrid fields"
                            },
                            {
                                jsURL:"grids/dataBinding/fieldsDS.js",
                                description:"\n        This <code>ListGrid</code> takes its field (column) settings from the\n        <code>countryDS</code> DataSource specified in the \"dataSource\" property of the\n        component definition. This technique is appropriate for easy display of a shared\n        data model with the default UI appearance and behaviors.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"},
                                    {title:"countryDS", url:"grids/ds/countryMergeDS.ds.js"}
                                ],
                                id:"dataSourceFields",
                                title:"DataSource fields"
                            },
                            {
                                jsURL:"grids/dataBinding/fieldsMerged.js",
                                description:"\n        This <code>ListGrid</code> merges field settings from both the component \"fields\"\n        (for presentation attributes) and the <code>countryDS</code> DataSource (for\n        data model attributes). This is the usual approach to customize the look and feel of a\n        data-bound component.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"},
                                    {title:"countryDS", url:"grids/ds/countryMergeDS.ds.js"}
                                ],
                                id:"mergedFields",
                                title:"Merged fields"
                            },
                            {jsURL:"grids/dataProviders/inlineData.js", 
                             description:"\n        This <code>ListGrid</code> uses an inline data array in the component definition. This\n        technique is appropriate for very small read-only data sets, typically with static data\n        values.\n        ",id:"inlineData", 
                             title:"Inline data"},
                            {
                                jsURL:"grids/dataProviders/localData.js",
                                description:"\n        This <code>ListGrid</code> loads data from a local data array (included in a separate\n        JavaScript data file). This technique is appropriate for read-only data sets, typically\n        with less than 500 records.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                id:"localData",
                                title:"Local data"
                            },
                            {
                                jsURL:"grids/dataProviders/databound.js",
                                description:"\n        This <code>ListGrid</code> binds to a client-only <code>DataSource</code> that loads data\n        from a local data array. This technique is appropriate for client-only rapid prototyping\n        when the production application will support add or update (write operations), switchable\n        data providers (JSON, XML, WSDL, Java), arbitrarily large data sets (1000+ records), or\n        a data model that is shared by multiple components.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"},
                                    {title:"countryDS", url:"grids/ds/countryLocalDS.ds.js"}
                                ],
                                id:"localDataSource",
                                title:"Local DataSource"
                            },
                            {
                                jsURL:"grids/dataProviders/databound.js",
                                description:"\n        This <code>ListGrid</code> binds to a <code>DataSource</code> that loads data from a\n        remote JSON data provider.  This approach of loading simple JSON data over HTTP can be\n        used with PHP and other server technologies.\n        ",
                                tabs:[
                                    {title:"countryDS", url:"grids/ds/countryJSONDS.ds.xml"},
                                    {canEdit:"false", doEval:"false", title:"countryData.json", 
                                     url:"grids/data/countryData.json"}
                                ],
                                id:"jsonDataSource",
                                title:"JSON DataSource"
                            },
                            {
                                jsURL:"grids/dataProviders/databound.js",
                                needXML:"true",
                                description:"\n        This <code>ListGrid</code> binds to a <code>DataSource</code> that loads data from a\n        remote XML data provider.  This approach of loading simple XML data over HTTP can be\n        used with PHP and other server technologies.\n        ",
                                tabs:[
                                    {title:"countryDS", url:"grids/ds/countryXMLDS.ds.xml"},
                                    {canEdit:"false", doEval:"false", title:"countryData.xml", 
                                     url:"grids/data/countryData.xml"}
                                ],
                                id:"xmlDataSource",
                                title:"XML DataSource"
                            },
                            {
                                jsURL:"grids/dataProviders/WSDLBound.js",
                                needXML:"true",
                                description:"\n        This <code>ListGrid</code> binds to a <code>DataSource</code> that loads data via a\n        WSDL service.  This example WSDL service supports all 4 basic operation types (fetch,\n        add, update, remove) and can be implemented with any server technology.  Sample\n        request/response SOAP messages for a \"fetch\" operation are shown.\n        ",
                                tabs:[
                                    {title:"countryDS", url:"grids/ds/countryWSDLDS.ds.xml"},
                                    {canEdit:"false", doEval:"false", title:"soapRequest.xml", 
                                     url:"grids/data/countrySoapRequest.xml"},
                                    {canEdit:"false", doEval:"false", title:"soapResponse.xml", 
                                     url:"grids/data/countrySoapResponse.xml"},
                                    {canEdit:"false", doEval:"false", title:"WSDL", 
                                     url:"grids/ds/SmartClientOperations.wsdl"}
                                ],
                                id:"WSDLDataSource",
                                title:"WSDL DataSource"
                            }
                        ]
                    },
                    {
                        description:"\n    Basic operations on datasets, both local and remote.\n",
                        isOpen:false,
                        title:"Data operations",
                        children:[
                            {
                                jsURL:"grids/dataOperations/localSet.js",
                                visibility:"sdk",
                                description:"\n        Click the buttons to populate the grid with records from a local data set.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                title:"Local set"
                            },
                            {
                                jsURL:"grids/dataOperations/localAdd.js",
                                visibility:"sdk",
                                description:"\n        Click the buttons to add records to the top and bottom of the list.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                title:"Local add"
                            },
                            {
                                jsURL:"grids/dataOperations/localRemove.js",
                                visibility:"sdk",
                                description:"\n        Click \"Remove first\" to remove the first record in the list. Click the other buttons to\n        remove records based on your selection (click, Ctrl-click, or\n        Shift-click in the list to select records).\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                title:"Local remove"
                            },
                            {
                                jsURL:"grids/dataOperations/localUpdate.js",
                                visibility:"sdk",
                                description:"\n        Click to select any record in the list, then click one of the buttons to change\n        the \"Continent\" value for that record. Also see the <b>Grids > Editing</b> examples\n        for automatic update behavior.\n        ",
                                tabs:[
                                    {title:"countryData", url:"grids/data/countryData.js"}
                                ],
                                title:"Local update"
                            },
                            {
                                jsURL:"grids/dataOperations/databoundFetch.js",
                                description:"\n        Click the buttons to fetch (exact match) country records from the server.\n        Click the \"Fetch All\" button to fetch the first \"page\" of 50 records, then scroll\n        the grid to fetch new pages of data on demand.\n        ",
                                tabs:[
                                    {canEdit:"false", title:"worldDS", 
                                     url:"grids/ds/worldSQLDS.ds.xml"}
                                ],
                                id:"databoundFetch",
                                title:"Databound fetch"
                            },
                            {
                                jsURL:"grids/dataOperations/databoundFilter.js",
                                description:"\n        Click the buttons to filter (partial match) records from the server. Also see the\n        <b>Grids &gt; Sort &amp; filter &gt; Filter</b> example for automatic databound Filter\n        operations triggered by user input.\n        ",
                                tabs:[
                                    {canEdit:"false", title:"worldDS", 
                                     url:"grids/ds/worldSQLDS.ds.xml"}
                                ],
                                id:"databoundFilter",
                                title:"Databound filter"
                            },
                            {
                                jsURL:"grids/dataOperations/databoundAdd.js",
                                description:"\n        Click the \"Add new country\" button to create a new country record on the server.\n        Also see the <b>Grids &gt; Editing &gt; Enter new rows</b> example for automatic databound\n        Add operations triggered by user input.\n        ",
                                tabs:[
                                    {canEdit:"false", title:"worldDS", 
                                     url:"grids/ds/worldSQLDS.ds.xml"}
                                ],
                                id:"databoundAdd",
                                title:"Databound add"
                            },
                            {
                                jsURL:"grids/dataOperations/databoundRemove.js",
                                description:"\n        Click \"Remove first\" to remove (delete) the first record in the list from the server.\n        Click the other buttons to remove records based on your selection (click, Ctrl-click, or\n        Shift-click in the list to select records).\n        ",
                                tabs:[
                                    {canEdit:"false", title:"worldDS", 
                                     url:"grids/ds/worldSQLDS.ds.xml"}
                                ],
                                id:"databoundRemove",
                                title:"Databound remove"
                            },
                            {
                                jsURL:"grids/dataOperations/databoundUpdate.js",
                                description:"\n        Click to select any record in the list, then click one of the buttons to change\n        the \"Continent\" value for that record on the server. Also see the <b>Grids &gt; Editing</b>\n        examples for automatic databound Update operations triggered by user input.\n        ",
                                tabs:[
                                    {canEdit:"false", title:"worldDS", 
                                     url:"grids/ds/worldSQLDS.ds.xml"}
                                ],
                                id:"databoundUpdate",
                                title:"Databound update"
                            }
                        ]
                    },
                    {ref:"dataIntegration", visibility:"www", title:"Service Integration"}
                ]
            },
            {
                showSkinSwitcher:"true",
                description:"\n    High-performance interactive tree views\n    <BR>\n    <BR>\n    Trees are based on grid views, and so share all of the appearance, interactivity and\n    databinding features of grids, in addition to tree-specific features.\n",
                isOpen:false,
                title:"Trees",
                children:[
                    {
                        description:"\n        Trees can have dynamic titles, display multiple columns and show connector\n        lines.\n    ",
                        isOpen:false,
                        title:"Appearance",
                        children:[
                            {
                                dataSource:"employees",
                                jsURL:"trees/appearance/nodeTitles.js",
                                description:"\n            Formatter interfaces allow you to add custom tree titles.\n            ",
                                tabs:[
                                    {name:"employeeData", url:"trees/employeeData.js"}
                                ],
                                id:"nodeTitles",
                                title:"Node Titles"
                            },
                            {dataSource:"employees", jsURL:"trees/appearance/multipleColumns.js", 
                             description:"\n            Trees can show multiple columns of data for each node.  Each column has the\n            styling, formatting, and data type awareness features of columns in a normal\n            grid.\n\n            Try drag reordering columns, or sorting by the Salary field.\n            ",title:"Multiple Columns"},
                            {cssURL:"trees/appearance/connectors.css", dataSource:"employees", 
                             jsURL:"trees/appearance/connectors.js",description:"\n            Trees can show skinnable connector lines.\n            ", 
                             id:"connectors",title:"Connectors"}
                        ]
                    },
                    {
                        description:"\n        Trees have built-in drag and drop behaviors and tree-specific events.\n    ",
                        isOpen:false,
                        title:"Interaction",
                        children:[
                            {
                                jsURL:"trees/interaction/dragReparent.js",
                                description:"\n            Try dragging employees under new managers.  Note that a position indicator line\n            appears during drag, allowing employees to be placed in a particular order.\n            ",
                                tabs:[
                                    {name:"employeeData", url:"trees/employeeData.js"}
                                ],
                                id:"treeDragReparent",
                                title:"Drag reparent"
                            },
                            {ref:"dragTree", title:"Drag nodes"},
                            {ref:"treeDragReparent", 
                             description:"\n            Try dragging employees under new managers.  Note that closed folders automatically\n            open if you hover over them momentarily.\n            ",title:"Springloaded Folders"},
                            {
                                jsURL:"trees/interaction/dropEvents.js",
                                description:"\n            Click on any category on the left to show items from that category on the right.  \n            Drag and drop items from the list on the right into new categories in the tree on\n            the left.\n            ",
                                tabs:[
                                    {dataSource:"supplyCategory", name:"supplyCategory"},
                                    {dataSource:"supplyItem", name:"supplyItem"}
                                ],
                                id:"treeDropEvents",
                                title:"Drop Events"
                            }
                        ]
                    },
                    {
                        description:"\n        Trees can bind to DataSources and handle all the data formats that grids can, using\n        additional properties to control tree structure, open state, and folderness.\n    ",
                        isOpen:false,
                        id:"treesDataBinding",
                        title:"Data binding",
                        children:[
                            {jsURL:"trees/dataBinding/parentLinking.js", 
                             description:"\n            Tree data can be specified as a flat list of nodes that refer to each other by\n            ID.  This format is also used for load on demand.\n            ",id:"parentLinking", 
                             title:"Parent Linking"},
                            {jsURL:"trees/dataBinding/childrenArrays.js", 
                             description:"\n            Tree data can be specified as a tree of nodes where each node lists its children.\n            ",id:"childrenArrays", 
                             title:"Children Arrays"},
                            {
                                jsURL:"trees/dataBinding/loadXMLChildrenArrays.js",
                                needXML:"true",
                                description:"\n            Tree data can be loaded in XML or JSON format.  Properties declared on DataSource\n            fields control how data is interpreted to form the tree structure.  This example\n            shows the XML format for children-array trees.\n            ",
                                tabs:[
                                    {canEdit:"false", doEval:"false", title:"employeesXMLData", 
                                     url:"trees/dataBinding/employeesDataChildrenArrays.xml"}
                                ],
                                id:"treeLoadXML",
                                title:"Load XML"
                            },
                            {dataSource:"employees", jsURL:"trees/dataBinding/loadOnDemand.js", 
                             description:"\n            Begin opening folders and note the prompt which briefly appears during server\n            fetches.\n            \n            DataBound Trees support load on demand.  When a folder is opened for the first\n            time, the tree asks the server for the children of the node just opened.\n            ",title:"Load on Demand"},
                            {dataSource:"employees", jsURL:"trees/dataBinding/initialDataLOD.js", 
                             description:"\n            Begin opening folders and note the load on demand behavior.\n            \n            Trees that use load on demand can specify an initial dataset set up front.  \n            ",id:"initialData", 
                             title:"Initial Data (Load on Demand)"}
                        ]
                    },
                    {
                        jsURL:"trees/sorting.js",
                        description:"\n        Trees sort per folder.  Click on the \"Name\" column header to sort alphabetically by\n        folder name, or on the \"Salary\" column header to sort by Salary.\n    ",
                        tabs:[
                            {name:"employeeData", url:"trees/employeeData.js"}
                        ],
                        title:"Sorting"
                    },
                    {dataSource:"employees", jsURL:"trees/editing.js", 
                     description:"\n        Click on employees in the tree to edit them, and drag and drop employees to rearrange them.\n        Choose an employee via the menu to see that employee's direct reports in the ListGrid.  Changes\n        made in the tree or ListGrid are automatically saved to the server and reflected in the other\n        components.\n    ",id:"treesEditing", 
                     title:"Editing"},
                    {dataSource:"employees", jsURL:"trees/freezeTree.js", 
                     description:"\n     Setting <code>frozen:true</code> enables frozen columns for Trees.  Columns\n     can be frozen and unfrozen by right-clicking on column headers.<br>\n     Column resize, column reorder, drag and drop and load on demand all function normally.\n     ",id:"freezeTree", 
                     title:"Frozen Columns"}
                ]
            },
            {
                description:"\n    Form managers and input controls.\n",
                isOpen:false,
                title:"Forms",
                children:[
                    {
                        description:"\n        A specialized form layout manager allows your forms to grow into available space,\n        hide sections, and span across tabs.\n    ",
                        isOpen:false,
                        id:"formsLayout",
                        title:"Layout",
                        children:[
                            {jsURL:"forms/layout/titles.js", 
                             description:"\n            Click on \"Swap Titles\" to change title orientation.\n            \n            Form layout automatically places titles next to fields.  Left-oriented titles take\n            up a column so that labels line up.  Top oriented titles don't.\n            ",id:"formLayoutTitles", 
                             title:"Titles"},
                            {jsURL:"forms/layout/spanning.js", 
                             description:"\n            Drag resize the form from the right edge to see the effect of spanning.\n            \n            Specifying column widths and column spanning items allows for larger and smaller\n            input areas.\n            ",id:"columnSpanning", 
                             title:"Spanning"},
                            {jsURL:"forms/layout/filling.js", 
                             description:"\n            Click on the \"Short Message\" and \"Long Message\" buttons to change the amount of\n            space available to the form.\n            \n            SmartClient form layouts allow you to fill available space, even when\n            available space cannot be known in advance because it is data-dependant.\n            ",id:"formLayoutFilling", 
                             title:"Filling"},
                            {xmlURL:"ValuesManager.xml", 
                             description:"\n            Click \"Submit\" to jump to a validation error in the \"Stock\" tab.\n            \n            Forms which are split for layout purposes can behave like a single logical form for\n            validation and saves.\n            ",showSkinSwitcher:true, 
                             id:"formSplitting",title:"Splitting"},
                            {xmlURL:"SectionItem.xml", 
                             description:"\n            Click on \"Stock\" to reveal fields relating to stock on hand.\n            ",showSkinSwitcher:true, 
                             id:"formSections",title:"Sections"},
                            {ref:"validationFieldBinding", title:"Data Binding"}
                        ]
                    },
                    {
                        description:"\n        Common field dependencies within a form, such as fields that are only applicable to\n        some users, can be handled by specifying simple expressions.\n    ",
                        isOpen:false,
                        title:"Field Dependencies",
                        children:[
                            {jsURL:"forms/fieldDependencies/showAndHide.js", 
                             description:"\n            Select \"On order\" to reveal the \"Shipping Date\" field.\n            ",id:"formShowAndHide", 
                             title:"Show & Hide"},
                            {jsURL:"forms/fieldDependencies/enableAndDisable.js", 
                             description:"\n            Check \"I accept the agreement\" to enable the \"Proceed\" button.\n            ",id:"fieldEnableDisable", 
                             title:"Enable & Disable"},
                            {jsURL:"forms/fieldDependencies/conditionallyRequired.js", 
                             description:"\n            Select \"No\" and click the \"Validate\" button - the reason field becomes required.\n            ",id:"conditionallyRequired", 
                             title:"Conditionally Required"},
                            {jsURL:"forms/fieldDependencies/matchValue.js", 
                             description:"\n            Try entering mismatched values for \"Password\" and \"Password Again\", then click\n            \"Create Account\" to see a validation error.\n            ",id:"matchValue", 
                             title:"Match Value"},
                            {jsURL:"forms/fieldDependencies/dependentSelects.js", 
                             description:"\n            Select a \"Division\" to cause the \"Department\" select to be populated with\n            departments from that division.\n            ",id:"formDependentSelects", 
                             title:"Dependent Selects"}
                        ]
                    },
                    {
                        description:"\n        The form has built-in editors and pickers for common types such as numbers and dates,\n        as well as the ability to use the databinding framework to pick from lists and trees of\n        related records.\n    ",
                        isOpen:false,
                        title:"Data Types",
                        children:[
                            {xmlURL:"TextItem.xml", description:"\n            ", id:"textItem", 
                             title:"Text"},
                            {xmlURL:"TextAreaItem.xml", description:"\n            ", 
                             id:"textAreaItem",title:"TextArea"},
                            {xmlURL:"DateItem.xml", 
                             description:"\n            DateItems support direct or pickList-based input of dates, and have a built-in\n            pop-up day picker.\n            ",id:"dateItem", 
                             title:"Date"},
                            {xmlURL:"TimeItem.xml", 
                             description:"\n            TimeItem supports text-based input of Times\n            ",id:"timeItem", 
                             title:"Time"},
                            {jsURL:"forms/dataTypes/numberSpinner.js", 
                             description:"\n            Click the up and down buttons to change shoe size.  Click and hold to change shoe\n            size quickly.  Note spinner stops at a maximum and minimum value.\n            ",id:"spinnerItem", 
                             title:"Number - Spinner"},
                            {jsURL:"forms/dataTypes/numberSlider.js", 
                             description:"\n            Change the value by clicking and dragging the thumb, clicking on the track, or\n            using the keyboard. \n            ",id:"sliderItem", 
                             title:"Number - Slider"},
                            {xmlURL:"CheckboxItem.xml", description:"\n            ", 
                             id:"checkboxItem",title:"Boolean - Checkbox"},
                            {jsURL:"forms/dataTypes/listSelect.js", 
                             description:"\n            Note the icons and customized text styling.  Click to reveal the options and note\n            the drop shadow.  \n            \n            The SmartClient SelectItem offers more powerful and consistent control over\n            appearance and behavior than the HTML &lt;SELECT&gt; element.\n            ",id:"selectItem", 
                             title:"List - Select"},
                            {dataSource:"supplyItem", jsURL:"forms/dataTypes/listComboBox.js", 
                             description:"\n            Start typing in either field to see a list of matching options.  The field\n            labelled \"Item Name\" retrieves options dynamically from the SupplyItem\n            DataSource\n            ",id:"listComboBox", 
                             title:"List - Combo Box"},
                            {dataSource:"supplyItem", jsURL:"forms/dataTypes/relatedRecords.js", 
                             description:"\n            Open the picker in either form to select the item you want to order from the\n            \"supplyItem\" DataSource.  The picker on the left stores the \"itemId\" from the\n            related \"supplyItem\" records.  The picker on the right stores the \"SKU\" while\n            displaying multiple fields.  You can scroll to dynamically load more records.  \n            This pattern works with any DataSource.  \n            ",showSkinSwitcher:true, 
                             id:"relatedRecords",title:"List - Related Records"},
                            {dataSource:"supplyCategory", xmlURL:"PickTree.xml", 
                             description:"\n            Click on \"Department\" or \"Category\" below to show hierarchical menus.  The\n            \"Category\" menu loads options dynamically from the SupplyCategory DataSource.\n            ",showSkinSwitcher:true, 
                             id:"pickTree",title:"Tree"},
                            {visibility:"sdk", xmlURL:"SelectOtherItem.xml", 
                             description:"\n            Select \"Other..\" from the drop down to enter a custom value.\n            ",title:"List - Select Other"},
                            {ref:"RichTextEditor", title:"HTML"}
                        ]
                    },
                    {ref:"validation", isOpen:false, title:"Validation"},
                    {
                        description:"\n        Hovers and hints explain the form to the user.  Icons provide an easy extension point\n        for help, custom pickers and other extensions.\n    ",
                        isOpen:false,
                        title:"Details",
                        children:[
                            {jsURL:"forms/layout/icons.js", 
                             description:"\n            Click on the help icon below to see a description for severity levels.  Form items\n            can show an arbitrary number of icons to do whatever you need.\n            ",id:"formIcons", 
                             title:"Icons"},
                            {jsURL:"forms/details/hovers.js", 
                             description:"\n            Hover anywhere over the field to see what the current value means.  Change the\n            value or disable the field to see different hovers.  Note that the hovers contain\n            HTML formatting.  \n            ",id:"itemHoverHTML", 
                             title:"Hovers"},
                            {jsURL:"forms/layout/hints.js", 
                             description:"\n            Hints provide guidance to the user filling out the form.  In this case, the MM/YYYY\n            hint tells the user the expected format for the free-form date field.\n            ",id:"formHints", 
                             title:"Hints"}
                        ]
                    }
                ]
            },
            {
                description:"\n    Liquid layout managers and user interface containers.\n",
                isOpen:false,
                title:"Layout",
                children:[
                    {jsURL:"forms/layout/filling.js", 
                     description:"\n        Click on the \"Short Message\" and \"Long Message\" buttons to change the amount of\n        space available to the form.\n        \n        Layouts automatically react to resizes and re-apply the layout policy.\n        ",title:"Filling"},
                    {jsURL:"layout/nesting.js", 
                     description:"\n        Use the resize bars to reallocate space between the 3 panes.\n        \n        Layouts can be nested to create standard application views.  Resize bars are built-in.\n        ",showSkinSwitcher:true, 
                     id:"layoutNesting",title:"Nesting"},
                    {jsURL:"layout/userSizing.js", 
                     description:"\n        Resize the outer frame to watch \"Member 1\" and \"Member 2\" split the space.  Now resize\n        either member and resize the outer frame again.\n        \n        Layouts track sizes which have been set by user action and respect the user's settings.\n        ",id:"userSizing", 
                     title:"User Sizing"},
                    {ref:"formsLayout", title:"Form Layout"},
                    {
                        showSkinSwitcher:"true",
                        description:"\n        Windows for dialogs, wizards, tools and free-form application layouts.\n    ",
                        isOpen:false,
                        title:"Windows",
                        children:[
                            {jsURL:"layout/window/autoSize.js", 
                             description:"\n            Windows can autoSize to content or can dictate the content's size.\n            ",id:"windowAutosize", 
                             title:"Auto Size"},
                            {ref:"modality", title:"Modality"},
                            {jsURL:"layout/window/dragging.js", 
                             description:"\n            Grab the window by its title bar to move it around.  Resize it by the right or\n            bottom edge.\n            ",title:"Dragging"},
                            {ref:"windowMinimize", title:"Minimize"},
                            {jsURL:"layout/window/controls.js", 
                             description:"\n            Header controls can be reordered and custom controls added.\n            ",id:"windowHeaderControls", 
                             title:"Header Controls"},
                            {jsURL:"layout/window/footer.js", 
                             description:"\n            Windows support a footer with a visible resizer and updateable status bar.\n            ",id:"windowFooter", 
                             title:"Footer"}
                        ]
                    },
                    {
                        showSkinSwitcher:"true",
                        description:"\n        Tabs for sectioning applications and forms.\n    ",
                        isOpen:false,
                        title:"Tabs",
                        children:[
                            {jsURL:"layout/tabs/orientation.js", 
                             description:"\n            Tabs can be horizontally or vertically oriented.  To select tabs, click on them, or\n            on click the \"Select Blue\" and \"Select Green\" buttons.\n            ",id:"tabsOrientation", 
                             title:"Orientation"},
                            {jsURL:"layout/tabs/align.js", 
                             description:"\n            Tabs can be left or right aligned (for horizontal tabs) or top or bottom aligned\n            (for vertical tabs)\n            ",id:"tabsAlign", 
                             title:"Align"},
                            {jsURL:"layout/tabs/addAndRemove.js", 
                             description:"\n            Click on \"Add Tab\" and \"Remove Tab\" to add and remove tabs.   When you add too many\n            tabs to display at once, a tab scrolling interface will appear.\n            ",id:"tabsAddAndRemove", 
                             title:"Add and Remove"},
                            {jsURL:"layout/tabs/closeableTabs.js", 
                             description:"\n            Click on the red close icons to close tabs.  Tabbed views can have any mixture of\n            closeable and permanent tabs.\n            ",id:"closeableTabs", 
                             title:"Closeable Tabs"},
                            {jsURL:"layout/tabs/titleChange.js", 
                             description:"\n            Titles can be changed on the fly.  Type in your name to see the \"Preferences\" tab\n            change its title to include your name.  Note that the tab automatically sizes to\n            accomodate the longer title - automatic sizing also happens at initialization.\n            ",id:"titleChange", 
                             title:"Title Change"},
                            {
                                jsURL:"advanced/viewLoading.js",
                                needXHR:"true",
                                description:"\n            Click on \"Tab2\" to load a grid view on the fly.\n            \n            Declarative view loading allows extremely large applications to be split into\n            separately loadable chunks, and creates an easy integration path for applications\n            with server-driven application flow.\n            ",
                                tabs:[
                                    {canEdit:"false", doEval:"false", title:"loadedView", 
                                     url:"advanced/loadedView.js"}
                                ],
                                id:"viewLoading",
                                title:"View Loading"
                            }
                        ]
                    },
                    {
                        showSkinSwitcher:"true",
                        description:"\n        Sections (also called Accordions) label sections of the application\n        and allow users to hide or resize sections.\n    ",
                        isOpen:false,
                        title:"Sections",
                        children:[
                            {jsURL:"layout/sections/expandCollapse.js", 
                             description:"\n            Click on any section header showing an arrow to expand and collapse it (the \"Green \n            Cube\" section is marked not collapsible).  Click on the \"Expand Blue\" and \n            \"Collapse Blue\" buttons to expand and collapse sections externally.\n            ",id:"sectionsExpandCollapse", 
                             title:"Expand / Collapse"},
                            {jsURL:"layout/sections/resizeSections.js", 
                             description:"\n            Drag the \"Help 2\" header to resize sections, or press \"Resize Help 1\" to resize to\n            fixed height.  The \"Blue Pawn\" section is marked not resizeable.\n            ",id:"resizeSections", 
                             title:"Resize Sections"},
                            {jsURL:"layout/sections/addAndRemove.js", 
                             description:"\n            Press the \"Add Section\" and \"Remove Section\" buttons to add or remove sections.\n            ",id:"sectionsAddAndRemove", 
                             title:"Add and Remove"},
                            {jsURL:"layout/sections/showAndHide.js", 
                             description:"\n            Press the \"Show Section\" and \"Hide Section\" buttons to reveal or hide the Yellow\n            Section.  Showing and hiding sections lets you reuse a SectionStack for slightly\n            different purposes, hiding or revealing relevant sections.\n            ",id:"sectionsShowAndHide", 
                             title:"Show and Hide"}
                        ]
                    }
                ]
            },
            {
                description:"\n    Navigation and action controls.\n",
                isOpen:false,
                title:"Control",
                children:[
                    {
                        description:"\n    SmartClient buttons are visually appealing, easily skinned, and easy to use.\n    ",
                        isOpen:false,
                        title:"Buttons",
                        children:[
                            {jsURL:"actions/buttons/appearance.js", 
                             description:"\n            Buttons come in three basic types: CSS buttons, single-image buttons, and \n            multiple-image stretch buttons.  All share a basic set of capabilities.\n        ",showSkinSwitcher:true, 
                             id:"buttonAppearance",title:"Appearance"},
                            {css:"actions/buttons/states.css", jsURL:"actions/buttons/states.js", 
                             description:"\n            Move the mouse over the buttons, and click and hold to see buttons in different\n            states.  Click \"Disable All\" to put all buttons in the disabled state.\n            \n            Edit the CSS style definitions to change the appearance of various states.\n        ",id:"buttonStates", 
                             title:"States"},
                            {jsURL:"actions/buttons/icons.js", 
                             description:"\n            Click and hold on the \"Save\" button to see the icon change as the button goes\n            down.  Note that the binoculars icon does not change when the button goes down.\n            Click \"Disable Save\" to see the icon change to reflect disabled state.\n            \n            Button icons can be left or right oriented, and can optionally react to any the\n            state of the button.\n        ",id:"buttonIcons", 
                             title:"Icons"},
                            {jsURL:"actions/buttons/autoFit.js", 
                             description:"\n            Buttons can automatically size to accomodate the title and icon, and resize\n            automatically when the title is changed, notifying components around them they have\n            changed size.\n        ",id:"buttonAutoFit", 
                             title:"Auto Fit"},
                            {jsURL:"actions/buttons/radioCheckbox.js", 
                             description:"\n            Click on the buttons for Bold, Italic, and Underline and note that they stick in a\n            down state.  Click on the buttons for left, center and right justify and note that\n            they are mutually exclusive.\n        ",id:"buttonRadioToggle", 
                             title:"Radio / Toggle Behavior"}
                        ]
                    },
                    {
                        showSkinSwitcher:"true",
                        description:"\n    Dynamic, appealing menus that can bind directly to data.\n    ",
                        isOpen:false,
                        title:"Menus",
                        children:[
                            {jsURL:"actions/menus/appearance.js", 
                             description:"\n            Click \"File\" to see a typical File menu with icons, submenus, checks,\n            separators, disabled items, and keyboard shortcut hints.  Note the beveled edge and\n            drop shadow.\n            ",id:"fullMenu", 
                             title:"Appearance"},
                            {jsURL:"actions/menus/dynamicItems.js", 
                             description:"\n            Open the \"File\" menu to see the \"New file in..\" item initially disabled.  Select a\n            project and note that the menu item has become enabled, changed title and changed\n            icon.  Pick \"Project Listing\" to show and hide the project list, and note the item\n            checks and unchecks itself.\n            ",id:"menuDynamicItems", 
                             title:"Dynamic Items"},
                            {ref:"fullMenu", 
                             description:"\n            Click \"File\" and navigate over \"Recent Documents\" or \"Export as...\" to see\n            submenus.\n            ",id:"subMenus", 
                             title:"Submenus"},
                            {jsURL:"actions/menus/columns.js", 
                             description:"\n            Open the menu to see a standard column showing item titles, and an additional\n            column showing an option to close menu items. Clicking in the second column will\n            remove the item from the menu.\n            ",id:"menuColumns", 
                             title:"Custom Columns"},
                            {dataSource:"supplyCategory", jsURL:"actions/menus/treeBinding.js", 
                             description:"\n            Click on \"Department\" or \"Category\" below to show hierarchical menus.  The\n            \"Category\" menu loads options dynamically from the SupplyCategory DataSource.\n            ",id:"treeBinding", 
                             title:"Tree Binding"}
                        ]
                    },
                    {jsURL:"actions/toolStrips.js", 
                     description:"\n        Click the icons at left to see \"radio\"-style selection.  Click the drop-down to see\n        font options.\n        ",id:"toolstrip", 
                     title:"ToolStrips"},
                    {jsURL:"actions/dialogs.js", 
                     description:"\n        Click \"Confirm\" and \"Ask\" to show two of the pre-built, skinnable SmartClient Dialogs\n        for common interactions.  \n        ",showSkinSwitcher:true, 
                     id:"dialogs",title:"Dialogs"},
                    {xmlURL:"actions/slider.js", 
                     description:"\n        Move either Slider to update the other.  You can change the value by clicking and\n        dragging the thumb, clicking on the track, or using the keyboard (once you've focused\n        on one of the sliders)\n        ",id:"slider", 
                     title:"Slider"}
                ]
            },
            {
                description:"\n    Data binding allows multiple components to share a central definition of an object (called\n    a DataSource), so that all components can consistently retrieve, display, edit, validate\n    and save objects of that type.\n",
                isOpen:false,
                title:"Data Binding",
                children:[
                    {ref:"gridsDataBinding", showSkinSwitcher:"true", isOpen:false, title:"Lists"},
                    {ref:"treesDataBinding", showSkinSwitcher:"true", isOpen:false, title:"Trees"},
                    {
                        showSkinSwitcher:"true",
                        description:"\n    DataBound Components understand a core set of operations called \"Fetch\", \"Add\", \"Update\"\n    and \"Remove\" (also known as CRUD operations).  These operations can be programmatically\n    initiated or automatically initiated in response to user action.\n    In either case the integration model and APIs are the same.\n    ",
                        isOpen:false,
                        title:"Operations",
                        children:[
                            {dataSource:"supplyItem", jsURL:"databind/operations/fetch.js", 
                             xmlURL:"databind/operations/fetch.xml",description:"\n            Rows are fetched automatically as the user drags the scrollbar.  Drag the scrollbar\n            quickly to the bottom to fetch a range near the end (a prompt will appear during\n            server fetch).  Scroll slowly back up to fill in the middle.\n            ", 
                             id:"fetchOperation",title:"Fetch"},
                            {dataSource:"supplyItem", xmlURL:"databind/operations/add.xml", 
                             description:"\n            Use the form to create a new stock item.  Create an item in the currently shown\n            category to see it appear in the filtered listing automatically.  Create an item in\n            any other category and note that it is filtered out.\n            ",id:"addOperation", 
                             title:"Add"},
                            {dataSource:"supplyItem", xmlURL:"databind/operations/update.xml", 
                             description:"\n            Select an item and use the form to change its price.  The list updates\n            automatically.  Now change the item's category and note that it is removed\n            automatically from the list.\n            ",id:"updateOperation", 
                             title:"Update"},
                            {dataSource:"supplyItem", xmlURL:"databind/operations/remove.xml", 
                             description:"\n            Click the \"Remove\" button to remove the selected item.\n            ",id:"removeOperation", 
                             title:"Remove"}
                        ]
                    },
                    {
                        description:"\n        Typical validation needs are covered by validators built-in to the SmartClient\n        framework.  Validators can be combined into custom type definitions which are reusable\n        across all components.\n    ",
                        isOpen:false,
                        id:"validation",
                        title:"Validation",
                        children:[
                            {dataSource:"databind/validation/type.ds.xml", 
                             jsURL:"databind/validation/type.js",description:"\n            Type a non-numeric value into the field and press \"Validate\" to receive a\n            validation error.\n            \n            Declaring field type implies automatic validation anywhere a value is edited.\n            ", 
                             id:"validationType",title:"Type"},
                            {dataSource:"databind/validation/builtins.ds.xml", 
                             jsURL:"databind/validation/builtins.js",description:"\n            Type a number greater than 20 or less than 1 and press \"Validate\" to receive a\n            validation error.\n            \n            SmartClient implements the XML Schema set of validators on both client and server\n            ", 
                             id:"validationBuiltins",title:"Built-ins"},
                            {dataSource:"databind/validation/regularExpression.ds.xml", 
                             jsURL:"databind/validation/regularExpression.js",description:"\n            Enter a bad email address (eg just \"mike\") and press \"Validate\" to receive a\n            validation error.\n            \n            The regular expression validator allows simple custom field types, with automatic\n            enforcement on client on server.\n            ", 
                             id:"regularExpression",title:"Regular Expression"},
                            {dataSource:"databind/validation/valueTransform.ds.xml", 
                             jsURL:"databind/validation/valueTransform.js",description:"\n            Enter a 10 digit US phone number with any typical punctuation press \"Validate\" to see it\n            transformed to a canonical format.\n            ", 
                             id:"valueTransform",title:"Value Transform"},
                            {dataSource:"databind/validation/customTypes.ds.xml", 
                             jsURL:"databind/validation/customTypes.js",description:"\n            Enter a bad zip code (eg just \"123\") and press \"Validate\" to receive a\n            validation error.\n            \n            Custom types can be declared based on built-in validators and re-used in multiple\n            DataSources\n            ", 
                             id:"customSimpleType",title:"Custom Types"},
                            {dataSource:"databind/forms/users.ds.xml", 
                             jsURL:"databind/forms/customBinding.js",description:"\n            Click \"Validate\" to see validation errors triggered by rules both in this form and\n            in the DataSource.\n            \n            Screen-specific fields and validation logic, such as the duplicate password entry\n            box, can be added to a particular form while still sharing schema information that\n            applies to all views.\n            ", 
                             id:"validationFieldBinding",title:"Customized Binding"}
                        ]
                    },
                    {ref:"relatedRecords", 
                     description:"\n        Open the picker in either form to select the item you want to order from the\n        \"supplyItem\" DataSource.  The picker on the left stores the \"itemId\" from the\n        related \"supplyItem\" records.  The picker on the right stores the \"SKU\" while\n        displaying multiple fields.  You can scroll to dynamically load more records.  \n        This pattern works with any DataSource.  \n    ",title:"Related Records"}
                ]
            },
            {
                description:"\n    SmartClient supports declarative, XPath-based binding of visual components to web services\n    that return XML or JSON responses.  SmartClient understands XML Schema and can bind components directly to\n    WSDL web services.  An optional Java-based integration server supports automatic \n    Java<->JSON translation and synchronized client-server validation rules.\n",
                isOpen:false,
                id:"dataIntegration",
                title:"Data Integration",
                children:[
                    {
                        description:"\n        The optional SmartClient Java Server enables accelerated integration with \n        popular Java frameworks, such as Java Beans, Hibernate, Spring and Struts.\n    ",
                        isOpen:false,
                        id:"javaDataIntegration",
                        title:"Java",
                        children:[
                            {dataSource:"supplyItem", 
                             jsURL:"dataIntegration/java/serverValidation.js",requiresModules:"SCServer", 
                             description:"\n            Validation rules are automatically enforced on both the client- and server-side based on\n            a single, shared declaration.  Press \"Save\" to see errors from client-side\n            validation.  Press \"Clear Errors\" then \"Disable Validation\" then \"Save\" again to see the\n            same errors caught by the SmartClient server.\n            ",id:"serverValidation", 
                             title:"Validation"},
                            {
                                dataSource:"supplyItemDMI",
                                jsURL:"dataIntegration/java/javaBeans.js",
                                requiresModules:"SCServer",
                                description:"\n            SmartClient DataSource operations can be fulfilled by returning Java Beans (aka EJBs \n            or POJOs) from your existing business logic.  When you call SmartClient's \n            <code>DSResponse.setData()</code> API, your Java objects are automatically translated \n            to JavaScript, transmitted to the browser, and provided to the requesting component.\n            See the sample implementation of the \"fetch\" operation in SupplyItemFetch.java\n            ",
                                tabs:[
                                    {canEdit:"false", doEval:"false", title:"SupplyItemFetch.java", 
                                     url:"dataIntegration/java/SupplyItemFetch.java"},
                                    {canEdit:"false", doEval:"false", title:"SupplyItem.java", 
                                     url:"dataIntegration/java/SupplyItem.java"}
                                ],
                                id:"javaBeans",
                                title:"Java Beans"
                            },
                            {
                                dataSource:"supplyItemDMI",
                                jsURL:"dataIntegration/java/dmi.js",
                                requiresModules:"SCServer",
                                description:"\n            Direct Method Invocation (DMI) allows you to map DataSource operations directly \n            to Java methods via XML configuration in a DataSource descriptor (.ds.xml file).\n            The arguments of your Java methods are automatically populated from the inbound \n            request.  See the sample implementation in SupplyItemDMI.java\n            ",
                                tabs:[
                                    {canEdit:"false", doEval:"false", title:"SupplyItemDMI.java", 
                                     url:"dataIntegration/java/SupplyItemDMI.java"},
                                    {canEdit:"false", doEval:"false", title:"SupplyItem.java", 
                                     url:"dataIntegration/java/SupplyItem.java"}
                                ],
                                id:"DMI",
                                title:"DMI"
                            },
                            {dataSource:"supplyItemHB", 
                             jsURL:"dataIntegration/java/hibernatePrototyping.js",requiresModules:"SCServer", 
                             description:"\n            SmartClient supports codeless integration with Hibernate for rapid prototyping.\n            Simply declaring a DataSource with <code>serverType:\"hibernate\"</code> enables you\n            to automatically generate tables, import sample data and perform all four\n            DataSource operations\n            ",id:"hibernatePrototyping", 
                             title:"Hibernate (Prototyping)"},
                            {
                                dataSource:"supplyItemSpringDMI",
                                jsURL:"dataIntegration/java/hibernateProduction.js",
                                requiresModules:"SCServer",
                                description:"\n            Hibernate's <code>Criteria</code> object can be created from SmartClient's \n            <code>DSRequest</code> in order to fulfill the \"fetch\" operation, with data paging \n            enabled.  Hibernate-managed beans can be populated with inbound, validated data\n            with a single method call.\n            ",
                                tabs:[
                                    {canEdit:"false", doEval:"false", 
                                     title:"Spring applicationContext.xml",url:"dataIntegration/java/applicationContext.xml"},
                                    {canEdit:"false", doEval:"false", title:"SupplyItemDao.java", 
                                     url:"dataIntegration/java/SupplyItemDao.java"},
                                    {canEdit:"false", doEval:"false", title:"SupplyItem.hbml.xml", 
                                     url:"dataIntegration/java/SupplyItem.hbm.xml"},
                                    {canEdit:"false", doEval:"false", title:"SupplyItem.java", 
                                     url:"dataIntegration/java/SupplyItem.java"}
                                ],
                                id:"hibernateProduction",
                                title:"Hibernate (Production)"
                            }
                        ]
                    },
                    {
                        description:"\n        SmartClient can declaratively bind to standard formats like WSDL or RSS, homebrew\n        formats, or simple flat files.  \n    ",
                        isOpen:false,
                        id:"xmlDataIntegration",
                        title:"XML",
                        children:[
                            {jsURL:"dataIntegration/xml/rssFeed.js", needXML:"true", 
                             description:"\n            DataSources can bind directly to simple XML documents where field values appear as\n            attributes or subelements.\n            ",showSkinSwitcher:true, 
                             id:"rssFeed",title:"RSS Feed"},
                            {
                                jsURL:"dataIntegration/xml/xpathBinding.js",
                                needXML:"true",
                                description:"\n            DataSources can extract field values from complex XML documents via XPath\n            expressions.  Note how the address fields, which are represented in the contacts\n            data as a subelement, appear as columns in the grid. This approach of loading\n            simple XML data over HTTP can be used with PHP and other server technologies.\n            ",
                                showSkinSwitcher:true,
                                tabs:[
                                    {canEdit:"false", doEval:"false", title:"contactsData.xml", 
                                     url:"dataIntegration/xml/contactsData.xml"}
                                ],
                                id:"xpathBinding",
                                title:"XPath Binding"
                            },
                            {jsURL:"dataIntegration/xml/yahooWebServices.js", needXML:"true", 
                             description:"\n            XPath binding allows declarative integration with web services.  Note how the\n            height and width for the thumbnail images have been declaratively extracted from\n            the \"Thumbnail\" subobject.\n            ",showSkinSwitcher:true, 
                             id:"xmlYahooWebServices",title:"Yahoo! Web Services"},
                            {jsURL:"dataIntegration/xml/wsdlWebServiceOperations.js", needXML:"true", 
                             description:"\n            SmartClient can load WSDL service definitions and call web service operations\n            with automatic JSON<->XML translation.\n            \n            SOAP encoding rules, namespacing, and element ordering are handled automatically\n            for your inputs and outputs. \n            ",showSkinSwitcher:false, 
                             id:"wsdlOperation",title:"WSDL Web Services"},
                            {jsURL:"dataIntegration/xml/googleSOAPSearch.js", needXML:"true", 
                             description:"\n            Enter a search in the \"q\" field to search the web with Google. \n            \n            DataSources can bind directly to the structure of WSDL messages.\n            ",showSkinSwitcher:true, 
                             id:"wsdlBinding",title:"Google SOAP Search"},
                            {
                                jsURL:"dataIntegration/xml/operationBinding_dataURL.js",
                                description:"\n        Demonstrates <b>Add</b>, <b>Update</b> and <b>Remove</b> operations with a server that\n        returns simple XML responses, an integration strategy popular with PHP, Ruby and Perl\n        backends.\n        <br>\n        Each operation is directed to a different XML file containing a sample response for\n        that operationType.  The server returns the data-as-saved to allow the grid to update\n        its cache.\n        ",
                                tabs:[
                                    {canEdit:"false", doEval:"false", title:"country_fetch.xml", 
                                     url:"dataIntegration/xml/responses/country_fetch.xml"},
                                    {canEdit:"false", doEval:"false", title:"country_add.xml", 
                                     url:"dataIntegration/xml/responses/country_add.xml"},
                                    {canEdit:"false", doEval:"false", title:"country_update.xml", 
                                     url:"dataIntegration/xml/responses/country_update.xml"},
                                    {canEdit:"false", doEval:"false", title:"country_remove.xml", 
                                     url:"dataIntegration/xml/responses/country_remove.xml"}
                                ],
                                id:"xmlEditSave",
                                title:"Edit and Save"
                            },
                            {
                                jsURL:"dataIntegration/xml/restDS_operationBinding.js",
                                description:"\n        The RestDataSource provides a simple protocol based on XML or JSON over HTTP.  This\n        protocol can be implemented with any server technology (PHP, Ruby, ..) and \n        includes all the features of SmartClient's databinding layer (data paging, server\n        validation errors, cache sync, etc).<br>\n        In this example, each DataSource operation is directed to a different XML file\n        containing a sample response for that operationType.  The server returns the\n        data-as-saved to allow the grid to update its cache.\n        ",
                                tabs:[
                                    {canEdit:"false", doEval:"false", title:"country_fetch.xml", 
                                     url:"dataIntegration/xml/responses/country_fetch_rest.xml"},
                                    {canEdit:"false", doEval:"false", title:"country_add.xml", 
                                     url:"dataIntegration/xml/responses/country_add_rest.xml"},
                                    {canEdit:"false", doEval:"false", title:"country_update.xml", 
                                     url:"dataIntegration/xml/responses/country_update_rest.xml"},
                                    {canEdit:"false", doEval:"false", title:"country_remove.xml", 
                                     url:"dataIntegration/xml/responses/country_remove_rest.xml"}
                                ],
                                id:"restEditSave",
                                title:"RestDataSource - Edit and Save"
                            },
                            {
                                jsURL:"dataIntegration/xml/serverValidationErrors/serverValidationErrors.js",
                                needXML:"true",
                                description:"\n            Click \"Save\" to see validation errors derived from an XML response.\n            \n            Validation errors expressed in application-specific XML formats can be \n            communicated to visual components by implementing\n            <code>DataSource.transformResponse()</code>.  The resulting validation\n            errors will be displayed and tracked by forms and editabled grids.\n            ",
                                showSkinSwitcher:false,
                                tabs:[
                                    {canEdit:"false", doEval:"false", title:"serverResponse.xml", 
                                     url:"dataIntegration/xml/serverValidationErrors/serverResponse.xml"}
                                ],
                                id:"xmlServerValidationErrors",
                                title:"Server Validation Errors"
                            },
                            {
                                needXML:"true",
                                url:"dataIntegration/xml/xmlSchemaImport.js",
                                description:"\n            Click \"Load Schema\" to load a version of the <code>supplyItem</code>\n            DataSource expressed in XML Schema format, and bind the Grid and Form to it.  Note\n            that the form and grid choose appropriate editors according to declared XML Schema\n            types.  Click \"Validate\" to see validation errors from automatically imported\n            validators.\n            ",
                                showSkinSwitcher:true,
                                tabs:[
                                    {canEdit:"false", doEval:"false", title:"supplyItem.xsd", 
                                     url:"dataIntegration/xml/supplyItem.xsd"}
                                ],
                                id:"xmlSchemaImport",
                                title:"XML Schema Import"
                            },
                            {
                                needXML:"true",
                                url:"dataIntegration/xml/schemaChaining.js",
                                description:"\n            Click \"Load Schema\" to load a <code>supplyItem</code> DataSource from\n            XML Schema format, then extend that schema with SmartClient-specific presentation\n            attributes, and bind the Grid and Form to it.  Note that the internal \"itemId\"\n            field has been hidden from the user, some fields have been retitled, and default\n            editors overriden.\n            ",
                                showSkinSwitcher:true,
                                tabs:[
                                    {canEdit:"false", doEval:"false", title:"supplyItem.xsd", 
                                     url:"dataIntegration/xml/supplyItem.xsd"}
                                ],
                                id:"schemaChaining",
                                title:"Schema Chaining"
                            },
                            {needXML:"true", ref:"WSDLDataSource", showSkinSwitcher:true, 
                             title:"SmartClient WSDL"}
                        ]
                    },
                    {
                        description:"\n        SmartClient brings declarative XPath binding and typed schema (even XML Schema) to the\n        simple and convenient JSON format.\n    ",
                        isOpen:false,
                        title:"JSON",
                        children:[
                            {
                                jsURL:"dataIntegration/json/simpleJSON.js",
                                description:"\n            DataSources can bind directly to JSON data where records appear as an Array of\n            JavaScript Objects with field values as properties.  This approach of loading\n            simple JSON data over HTTP can be used with PHP and other server technologies.\n            ",
                                showSkinSwitcher:true,
                                tabs:[
                                    {canEdit:"false", doEval:"false", title:"countries_small.js", 
                                     url:"dataIntegration/json/countries_small.js"}
                                ],
                                id:"simpleJSON",
                                title:"Simple JSON"
                            },
                            {
                                jsURL:"dataIntegration/json/xpathBinding.js",
                                description:"\n            DataSources can extract field values from complex JSON structures via XPath\n            expressions.  Note how the address fields, which are represented in the contacts\n            data as a subobject, appear as columns in the grid.\n            ",
                                showSkinSwitcher:true,
                                tabs:[
                                    {canEdit:"false", doEval:"false", title:"contactsData.js", 
                                     url:"dataIntegration/json/contactsData.js"}
                                ],
                                id:"jsonXPath",
                                title:"JSON XPath Binding"
                            },
                            {jsURL:"dataIntegration/json/yahooWebServices.js", 
                             description:"\n            Enter a search term in the Query input field to see images from Yahoo Image\n            Search.<BR>\n            XPath binding allows declarative integration with web services.  Note how the\n            height and width for the thumbnail images have been declaratively extracted from\n            the \"Thumbnail\" subobject.\n            ",showSkinSwitcher:true, 
                             id:"jsonYahooWebServices",title:"Yahoo! Web Services"},
                            {jsURL:"dataIntegration/json/yahooWebServices.js", 
                             description:"\n            Using the \"scriptInclude\" protocol, SmartClient applications can contact compatible\n            JSON web services without the need for any intervening server.\n            ",showSkinSwitcher:true, 
                             title:"Cross-Site JSON"},
                            {
                                jsURL:"dataIntegration/json/serverValidationErrors/serverValidationErrors.js",
                                description:"\n            Click \"Save\" to see validation errors derived from an XML response.<BR>\n            \n            Validation errors expressed in application-specific XML formats can be \n            communicated to the SmartClient system by implementing\n            <code>DataSource.transformResponse()</code>.  The resulting validation\n            errors will be displayed and tracked by forms and editabled grids.\n            ",
                                showSkinSwitcher:false,
                                tabs:[
                                    {canEdit:"false", doEval:"false", title:"serverResponse.js", 
                                     url:"dataIntegration/json/serverValidationErrors/serverResponse.js"}
                                ],
                                id:"jsonServerValidationErrors",
                                title:"Server Validation Errors"
                            }
                        ]
                    }
                ]
            },
            {
                requiresModules:"PluginBridges,Analytics",
                description:"\n    SmartClient supports a pluggable Charting API that can be easily connected to multiple\n    charting systems, with out of the box integration with the FusionCharts package (sold\n    separately by <a target=\"_blank\" href=\"http://www.infosoftglobal.com/\">Infosoft Global</a>).\n",
                isOpen:false,
                title:"Charting",
                children:[
                    {jsURL:"charts/gridChart.js", requiresModules:"PluginBridges,Analytics", 
                     description:"\n        Data loaded into a ListGrid can be charted with a single API call.\n        <P>\n        Use the \"Chart Type\" selector below to see same data rendered by multiple different\n        chart types.  Edit the data in the grid to have the chart regenerated automatically.\n        ",id:"gridCharting", 
                     title:"Grid Charting"},
                    {ref:"analytics", requiresModules:"PluginBridges,Analytics", 
                     description:"\n       This example shows binding to a multi-dimensional dataset, where each cell value has a\n       series of attributes, called \"facets\", that appear as headers labelling the cell value.\n       Drag facets onto the grid to expand the cube model.<BR>\n       Right click on any cell and pick \"Chart\" to chart values by any two facets.\n       ",title:"CubeGrid Charting"}
                ]
            },
            {
                description:"\n    Demos of complete applications based on SmartClient.\n",
                isOpen:false,
                title:"Applications",
                children:[
                    {ref:"showcaseApp", title:"Office Supply Catalog"},
                    {
                        backgroundColor:"#F5F5F5",
                        dataSource:"productRevenue",
                        fullScreen:"true",
                        jsURL:"advanced/cubegrid/databound_cubegrid.js",
                        requiresModules:"PluginBridges,Analytics",
                        screenshot:"advanced/cubegrid/databound_cubegrid.png",
                        screenshotHeight:"327",
                        screenshotWidth:"468",
                        description:"\n        This example shows binding to a multi-dimensional dataset, where each\n        cell value has a series of attributes, \"called\" facets, that appear as headers\n        labelling the cell value.  Facets can be added to the view, exposing more detail, by\n        dragging the menu buttons onto the grid, or into the \"Row Facets\" and \"Column Facets\"\n        listings. \n        Click grid turndown controls to expand tree facets.  Note that data loads as it is\n        revealed.\n    ",
                        showSkinSwitcher:true,
                        tabs:[
                            {loadAtEnd:"true", title:"facet controls", 
                             url:"advanced/cubegrid/facet_controls.js"}
                        ],
                        id:"analytics",
                        title:"Interactive Analytics"
                    }
                ]
            },
            {
                description:"\n    Demos of complete applications based on SmartClient.\n",
                isOpen:false,
                title:"Extending",
                children:[
                    {css:"extending/portlet.css", jsURL:"extending/componentReuse.js", 
                     description:"\n        The portlets below are a custom component created with less than one page of code\n        (see the \"JS\" tab).  The portlets support drag repositioning, drag resizing, a close\n        button, can contain any HTML content, and are skinnable.\n    ",title:"Component Reuse"},
                    {
                        dataSource:"supplyItem",
                        jsURL:"extending/patternReuse.js",
                        description:"\n        Click to select a DataSource, click on records to edit them in the adjacent form, then\n        click the \"Save\" button to save changes.<br>\n        This custom component combines a databound form and grid into a reusable application\n        pattern of side-by-side editing, that can be used with any DataSource.\n    ",
                        tabs:[
                            {canEdit:"false", title:"countryDS", url:"grids/ds/countrySQLDS.ds.xml"}
                        ],
                        id:"patternReuse",
                        title:"Pattern Reuse"
                    },
                    {ref:"schemaChaining", title:"Schema Reuse"},
                    {ref:"customSimpleType", title:"Type Reuse"},
                    {dataSource:"supplyItem", jsURL:"extending/customizeFields.js", 
                     description:"\n        Edit field definitions in the grid below to override how this form binds to the \n        <code>supplyItem</code> DataSource.  This is a simplified example of how\n        you can deliver an application that can be customized with organization-specific fields\n        and rules.  Dynamic schema binding makes building WYSIWYG editing interfaces very\n        simple.  \n        ",title:"Customize Fields"},
                    {ref:"customDrag", title:"Drag and Drop"},
                    {ref:"customHovers", title:"Hovers"},
                    {ref:"customMouseEvents", title:"Mouse Handling"},
                    {ref:"customAnimation", title:"Animation"}
                ]
            }
        ]
    },
    openProperty:"isOpen"
})

