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
        modelType: "children",
        nameProperty: "Name",
        childrenProperty: "directReports",
        root: {EmployeeId: "1", directReports: [
            {EmployeeId:"4", Name:"Charles Madigen", directReports: [
                {EmployeeId:"188", Name:"Rogine Leger"},
                {EmployeeId:"189", Name:"Gene Porter", directReports: [
                    {EmployeeId:"265", Name:"Olivier Doucet"},
                    {EmployeeId:"264", Name:"Cheryl Pearson"}
                ]}
            ]}
        ]}
    })
});

employeeTree.getData().openAll();