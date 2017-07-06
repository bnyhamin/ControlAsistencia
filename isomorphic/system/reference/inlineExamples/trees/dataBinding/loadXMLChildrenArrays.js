isc.DataSource.create({
    ID:"employees",
    dataURL:"/isomorphic/system/reference/inlineExamples/trees/dataBinding/employeesDataChildrenArrays.xml",
    recordXPath:"/Employees/employee",
    fields:[
        {type:"text", title:"Name", length:128, name:"Name"},
        {type:"integer", required:true, title:"Employee ID", primaryKey:true, name:"EmployeeId"},
        {type:"integer", required:true, title:"Manager", rootValue:"1", name:"ReportsTo"},
        {type:"text", title:"Title", length:128, name:"Job"},
        {type:"text", title:"Email", length:128, name:"Email"},
        {type:"text", title:"Employee Type", length:40, name:"EmployeeType"},
        {type:"text", title:"Status", length:40, name:"EmployeeStatus"},
        {type:"float", title:"Salary", name:"Salary"},
        {type:"text", title:"Org Unit", length:128, name:"OrgUnit"},
        {type:"text", title:"Gender", length:7, name:"Gender", valueMap:["male", "female"]},
        {type:"text", title:"Marital Status", length:10, name:"MaritalStatus",
                       valueMap:["married", "single"]},
        {childrenProperty:true, name:"directReports"}
    ]
});


isc.TreeGrid.create({
    ID: "employeeTree",
    width: 500,
    height: 400,
    dataSource: "employees",
    autoFetchData: true,
    nodeIcon:"icons/16/person.png",
    folderIcon:"icons/16/person.png",
    showOpenIcons:false,
    showDropIcons:false,
    closedIconSuffix:""
});
