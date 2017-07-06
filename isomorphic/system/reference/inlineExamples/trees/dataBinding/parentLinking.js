isc.TreeGrid.create({
    ID: "employeeTree",
    width: 500,
    height: 400,
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
        data: [
            {EmployeeId:"4", ReportsTo:"1", Name:"Charles Madigen"},
            {EmployeeId:"188", ReportsTo:"4", Name:"Rogine Leger"},
            {EmployeeId:"189", ReportsTo:"4", Name:"Gene Porter"},
            {EmployeeId:"265", ReportsTo:"189", Name:"Olivier Doucet"},
            {EmployeeId:"264", ReportsTo:"189", Name:"Cheryl Pearson"}
        ]
    })
});

employeeTree.getData().openAll();
