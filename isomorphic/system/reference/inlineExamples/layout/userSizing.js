isc.HLayout.create({
    width: "80%",
    showEdges: true,
    edgeImage:"edges/custom/sharpframe_10.png",
    dragAppearance: "target",
    overflow: "hidden",
    canDragResize: true,
    layoutMargin: 10,
    membersMargin: 10,
    minWidth: 100,
    minHeight: 50,
    members : [
        isc.Label.create({
            overflow: "hidden",
            showEdges: true,
            canDragResize: true,
            contents: "Member 1",
            align: "center"
        }),
        isc.Label.create({
            overflow: "hidden",
            showEdges: true,
            canDragResize: true,
            contents: "Member 2",
            align: "center"
        })
    ]
});