isc.TreeGrid.create({
    ID: "employeeTree",
    width: 500,
    height: 400,
    dataSource: "employees",
    autoFetchData: true,
    showConnectors: true,
    nodeIcon:"icons/16/person.png",
    folderIcon:"icons/16/person.png",
    showOpenIcons:false,
    showDropIcons:false,
    closedIconSuffix:"",
    baseStyle: "noBorderCell",
    fields: [
        {name: "Name"}
    ]
});
