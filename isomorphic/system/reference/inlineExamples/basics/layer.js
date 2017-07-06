isc.Label.create({
    align:"center", showEdges:true,
    backgroundColor:"lightblue",
    contents:"Bottom"
})

isc.Label.create({
    ID:"middleWidget",
    left:60, top:60, align:"center", showEdges:true,
    backgroundColor:"lightgreen",
    contents:"Middle"
})

isc.Label.create({
    left:120, top:120, align:"center", showEdges:true,
    backgroundColor:"pink",
    contents:"Top"
})

isc.Label.create({
    ID:"dragWidget",
    left:120, top:0, align:"center", showEdges:true,
    backgroundColor:"lightyellow",
    contents:"Drag Me",
    canDragReposition:true, dragAppearance:"target"
})

isc.VStack.create({
    left:250, membersMargin:10, members:[
        isc.IButton.create({title:"Front", click:"dragWidget.bringToFront()"}),
        isc.IButton.create({title:"Back", click:"dragWidget.sendToBack()"}),
        isc.IButton.create({title:"Above Middle", click:"dragWidget.moveAbove(middleWidget)"}),
        isc.IButton.create({title:"Below Middle", click:"dragWidget.moveBelow(middleWidget)"})
    ]
})
