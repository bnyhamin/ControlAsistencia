isc.TreeGrid.create({
    ID: "employeeTree",
    width: 500,
    height: 400,
    dataSource: "employees",
    nodeIcon:"icons/16/person.png",
    folderIcon:"icons/16/person.png",
    showOpenIcons:false,
    showDropIcons:false,
    closedIconSuffix:"",
    data: isc.Tree.create({
        modelType: "parent",
        rootValue: "1",
        nameProperty: "Name",
        idField: "EmployeeId",
        parentIdField: "ReportsTo",
        data: employeeData
    }),
    fields: [
        {name: "Name", formatCellValue: "record.Job+':&nbsp;'+value"}
    ]
});


